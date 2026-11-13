<?php

namespace App\Controllers\Eviden;

use App\Controllers\BaseController;
use App\Models\EvidenPpeppModel;
use App\Services\EvidenAutoTarik;
use Config\Database;

/**
 * Sprint 1 Otomatisasi Kriteria A (Kondisi Eksternal):
 * audit kesiapan otomatisasi per butir, dengan rekomendasi tindakan.
 *
 * Sumber butir: lam_kriteria.jenis = 'kondisi_eksternal' pada instrumen
 * LAM aktif prodi. Halaman ini TIDAK mengubah data apapun — hanya
 * membaca state dan menampilkan ringkasan + rekomendasi.
 */
class AuditController extends BaseController
{
    // Konstanta Skor Readiness
    private const SCORE_FULL_RUBRIK = 25;
    private const SCORE_PARTIAL_RUBRIK = 10;
    private const SCORE_HAS_SOURCE = 15;
    private const SCORE_HAS_NILAI = 20;
    private const SCORE_PPEPP_PER_FASE = 8; // Maks 40 (5 fase)

    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    private function activeInstrumenIds(int $prodiId): array
    {
        return array_map(
            fn($r) => (int) $r['lam_instrumen_id'],
            Database::connect()->table('prodi_instrumen')->select('lam_instrumen_id')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getResultArray()
        );
    }

    public function kondisiEksternal()
    {
        return $this->prosesAuditKriteriaByJenis('kondisi_eksternal', 'eviden/audit/kondisi_eksternal');
    }

    /**
     * Endpoint dinamis untuk meng-audit Kriteria apa pun (B, C1, C2, dst.)
     * URL: /eviden/audit/kriteria/C1
     */
    public function kriteria(string $kode)
    {
        return $this->prosesAuditKriteriaByKode($kode, 'eviden/audit/kriteria_umum');
    }

    private function prosesAuditKriteriaByJenis(string $jenis, string $view)
    {
        return $this->jalankanAuditEngine(
            ['jenis' => $jenis], $view
        );
    }

    private function prosesAuditKriteriaByKode(string $kode, string $view)
    {
        return $this->jalankanAuditEngine(
            ['kode' => $kode], $view
        );
    }

    /**
     * Core Engine Eager Loading untuk semua jenis audit kriteria.
     */
    private function jalankanAuditEngine(array $whereKriteria, string $view)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $instrumenIds = $this->activeInstrumenIds($prodiId);
        $db = Database::connect();

        $kriteria = [];
        $butirRows = [];
        if (!empty($instrumenIds)) {
            $q = $db->table('lam_kriteria')
                ->select('id, kode, nama, jenis, lam_instrumen_id, urutan')
                ->whereIn('lam_instrumen_id', $instrumenIds);
            
            foreach ($whereKriteria as $k => $v) $q->where($k, $v);
            
            $kriteria = $q->orderBy('urutan')->get()->getResultArray();

            if (!empty($kriteria)) {
                $kIds = array_column($kriteria, 'id');
                $butirRows = $db->table('lam_butir')
                    ->whereIn('lam_kriteria_id', $kIds)
                    ->orderBy('urutan')->get()->getResultArray();
            }
        }

        $svc = new EvidenAutoTarik();
        
        // --- EAGER LOADING (Solusi N+1 Query) ---
        $audit = [];
        $mappedData = ['rubrik' => [], 'skor' => [], 'eviden' => [], 'bukti' => []];

        if (!empty($butirRows)) {
            $bIds = array_column($butirRows, 'id');

            // 1. Tarik semua count rubrik
            $rs = $db->table('lam_butir_rubrik')->select('lam_butir_id, COUNT(id) as n')->whereIn('lam_butir_id', $bIds)->groupBy('lam_butir_id')->get()->getResultArray();
            foreach ($rs as $r) $mappedData['rubrik'][$r['lam_butir_id']] = (int) $r['n'];

            // 2. Tarik semua count butir skor
            $ss = $db->table('butir_skor')->select('lam_butir_id, COUNT(id) as n')->where('prodi_id', $prodiId)->whereIn('lam_butir_id', $bIds)->groupBy('lam_butir_id')->get()->getResultArray();
            foreach ($ss as $s) $mappedData['skor'][$s['lam_butir_id']] = (int) $s['n'];

            // 3. Tarik semua eviden ppepp
            $es = $db->table('eviden_ppepp')->where('prodi_id', $prodiId)->whereIn('lam_butir_id', $bIds)->get()->getResultArray();
            $evidenIds = [];
            foreach ($es as $e) {
                $mappedData['eviden'][$e['lam_butir_id']] = $e;
                $evidenIds[] = $e['id'];
            }

            // 4. Tarik semua eviden_ppepp_bukti
            if (!empty($evidenIds)) {
                $bs = $db->table('eviden_ppepp_bukti')->select('eviden_id, fase, COUNT(id) as n')->whereIn('eviden_id', $evidenIds)->groupBy('eviden_id, fase')->get()->getResultArray();
                foreach ($bs as $bf) $mappedData['bukti'][$bf['eviden_id']][$bf['fase']] = (int) $bf['n'];
            }
        }

        foreach ($butirRows as $b) {
            $audit[(int) $b['id']] = $this->auditButir($b, $mappedData, $svc);
        }

        // Gunakan ringkasan spesifik jika Kriteria A, jika tidak gunakan ringkasan umum
        $isKondisiEksternal = isset($whereKriteria['jenis']) && $whereKriteria['jenis'] === 'kondisi_eksternal';
        $summary = $isKondisiEksternal ? $this->ringkasanKondisiEksternal($audit) : $this->ringkasanUmum($audit, $kriteria);

        return view($view, [
            'instrumenIds' => $instrumenIds,
            'kriteria'     => $kriteria,
            'kode_kriteria'=> $whereKriteria['kode'] ?? '',
            'butirRows'    => $butirRows,
            'audit'        => $audit,
            'summary'      => $summary,
        ]);
    }

    private function auditButir(array $b, array $mappedData, EvidenAutoTarik $svc): array
    {
        $bid = (int) $b['id'];

        $rubrikN = $mappedData['rubrik'][$bid] ?? 0;
        $butirSkorN = $mappedData['skor'][$bid] ?? 0;

        // Eviden PPEPP (narasi & bukti)
        $ev = $mappedData['eviden'][$bid] ?? null;
        $ppeppFase = 0;
        $ppeppBukti = 0;
        if ($ev) {
            $byFase = $mappedData['bukti'][$ev['id']] ?? [];
            $faseDone = [];
            foreach ($byFase as $fase => $n) { $faseDone[$fase] = [1]; $ppeppBukti += $n; }
            $ppeppFase = EvidenPpeppModel::progress($ev, $faseDone);
        }

        // Sumber otomatis relevan untuk butir ini (signal otomatisasi yg mungkin)
        $relevantSources = $svc->relevantFor($b);

        // Skor readiness 0..100
        $score = 0;
        if ($rubrikN >= 4) $score += self::SCORE_FULL_RUBRIK; elseif ($rubrikN > 0) $score += self::SCORE_PARTIAL_RUBRIK;
        if (!empty($b['tabel_lkps_ref']) || !empty($b['formula'])) $score += self::SCORE_HAS_SOURCE;
        if ($butirSkorN > 0) $score += self::SCORE_HAS_NILAI;
        $score += min(40, $ppeppFase * self::SCORE_PPEPP_PER_FASE);

        $status = $score >= 75 ? 'siap' : ($score >= 35 ? 'sebagian' : 'kosong');

        // Rekomendasi tindakan utama (paling prioritas dulu)
        $rekomendasi = [];
        if ($rubrikN < 4) $rekomendasi[] = ['warn', 'Rubrik 4 level belum lengkap di instrumen master — lengkapi `lam_butir_rubrik` (Sprint 4)'];
        if ($ppeppFase === 0) {
            if (!empty($relevantSources)) {
                $rekomendasi[] = ['info', 'Klik Tarik Eviden Otomatis — sumber relevan: ' . implode(', ', $relevantSources)];
            } else {
                $rekomendasi[] = ['info', 'PPEPP belum diisi — tidak ada sumber otomatis cocok; isi narasi manual'];
            }
        } elseif ($ppeppFase < 5) {
            $rekomendasi[] = ['info', "PPEPP $ppeppFase/5 fase terisi — lengkapi sisanya"];
        }
        if ($butirSkorN === 0 && $rubrikN > 0) {
            $rekomendasi[] = ['info', 'Belum ada penilaian — masukkan skor manual atau lewat formula (Sprint 4)'];
        }
        if (empty($b['tabel_lkps_ref']) && empty($b['formula']) && $b['tipe_data'] !== 'kualitatif') {
            $rekomendasi[] = ['warn', 'Butir kuantitatif tapi belum punya `tabel_lkps_ref`/`formula` — perlu definisi sumber data (Sprint 2)'];
        }

        return [
            'butir'             => $b,
            'has_rubrik'        => $rubrikN,
            'has_formula'       => !empty($b['formula']),
            'has_lkps_ref'      => !empty($b['tabel_lkps_ref']),
            'tabel_lkps_ref'    => $b['tabel_lkps_ref'] ?? null,
            'butir_skor_n'      => $butirSkorN,
            'ppepp_fase'        => $ppeppFase,
            'ppepp_bukti'       => $ppeppBukti,
            'relevant_sources'  => $relevantSources,
            'readiness_pct'     => $score,
            'status'            => $status,
            'rekomendasi'       => $rekomendasi,
        ];
    }

    private function ringkasanKondisiEksternal(array $audit): array
    {
        $n = count($audit);
        $sumScore = 0;
        $bucket   = ['siap' => 0, 'sebagian' => 0, 'kosong' => 0];
        $tanpaRubrik = 0;
        $tanpaInputData = 0;
        $tanpaPpepp = 0;
        foreach ($audit as $a) {
            $sumScore += $a['readiness_pct'];
            $bucket[$a['status']]++;
            if ($a['has_rubrik'] < 4) $tanpaRubrik++;
            if (!$a['has_lkps_ref'] && !$a['has_formula'] && $a['butir']['tipe_data'] !== 'kualitatif') $tanpaInputData++;
            if ($a['ppepp_fase'] === 0) $tanpaPpepp++;
        }
        $avgScore = $n > 0 ? round($sumScore / $n) : 0;

        $rekomendasiGlobal = [];
        if ($n === 0) {
            $rekomendasiGlobal[] = ['danger', 'Instrumen LAM aktif belum punya butir dengan jenis `kondisi_eksternal` — perlu seeding butir Kriteria A terlebih dahulu (lihat manual instrumen).'];
        } else {
            if ($tanpaRubrik > 0) {
                $rekomendasiGlobal[] = ['warn', "$tanpaRubrik dari $n butir belum punya rubrik 4 level — perlu seeding `lam_butir_rubrik` (Sprint 4)."];
            }
            if ($tanpaInputData > 0) {
                $rekomendasiGlobal[] = ['warn', "$tanpaInputData butir kuantitatif belum punya sumber data (`tabel_lkps_ref`/`formula`) — Sprint 2 perlu mendefinisikan dan membangun input form Kondisi Eksternal terstruktur (analisis PESTEL/SWOT/demografi)."];
            }
            if ($tanpaPpepp > 0) {
                $rekomendasiGlobal[] = ['info', "$tanpaPpepp butir belum punya narasi PPEPP — gunakan tombol `Tarik Eviden Seluruh Butir` lalu lengkapi narasi manual atau via AI draft (Sprint 3)."];
            }
            $db = Database::connect();
            if ($db->tableExists('kondisi_eksternal')) {
                $cnt = $db->table('kondisi_eksternal')->where('prodi_id', $this->prodiId())->countAllResults();
                $rekomendasiGlobal[] = ['ok', '<strong>Sprint 2 sudah aktif:</strong> modul Input Kondisi Eksternal terstruktur tersedia ('.$cnt.' entri terinput). <a href="'.site_url('akreditasi/kondisi-eksternal').'">Buka modul</a> &middot; <a href="'.site_url('akreditasi/kondisi-eksternal/narasi').'">Lihat narasi LED</a>.'];
                if ($cnt === 0) {
                    $rekomendasiGlobal[] = ['warn', 'Mulai isi entri di 10 kategori (PESTEL + Pasar + SWOT-Eksternal). Tiap entri yang punya link_bukti otomatis muncul sebagai bukti Penetapan pada butir VMTS/SWOT.'];
                }
            } else {
                $rekomendasiGlobal[] = ['warn', 'Sprint 2 belum terdeploy — migration `kondisi_eksternal` belum dijalankan (`php spark migrate`).'];
            }
        }

        return [
            'n_butir'        => $n,
            'avg_readiness'  => $avgScore,
            'bucket'         => $bucket,
            'tanpa_rubrik'   => $tanpaRubrik,
            'tanpa_input'    => $tanpaInputData,
            'tanpa_ppepp'    => $tanpaPpepp,
            'rekomendasi'    => $rekomendasiGlobal,
        ];
    }

    private function ringkasanUmum(array $audit, array $kriteria): array
    {
        $n = count($audit);
        $sumScore = 0;
        $bucket   = ['siap' => 0, 'sebagian' => 0, 'kosong' => 0];
        
        foreach ($audit as $a) {
            $sumScore += $a['readiness_pct'];
            $bucket[$a['status']]++;
        }
        $avgScore = $n > 0 ? round($sumScore / $n) : 0;

        $rekomendasiGlobal = [];
        if ($n === 0) {
            $rekomendasiGlobal[] = ['danger', 'Kriteria ini belum memiliki butir penilaian di instrumen master.'];
        } else {
            if ($avgScore < 50) {
                $rekomendasiGlobal[] = ['warn', 'Kesiapan dokumen dan penilaian masih di bawah 50%. Prioritaskan pengisian nilai skor mandiri terlebih dahulu untuk memetakan kekurangan.'];
            }
            if ($bucket['kosong'] > 0) {
                $rekomendasiGlobal[] = ['info', "Terdapat {$bucket['kosong']} butir yang sama sekali belum memiliki narasi/bukti. Gunakan fitur Tarik Otomatis Eviden untuk mempercepat pengisian."];
            }
        }

        return [
            'n_butir'        => $n,
            'avg_readiness'  => $avgScore,
            'bucket'         => $bucket,
            'rekomendasi'    => $rekomendasiGlobal,
        ];
    }
}
