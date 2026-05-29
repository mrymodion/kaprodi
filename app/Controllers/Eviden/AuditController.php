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
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $instrumenIds = $this->activeInstrumenIds($prodiId);
        $db = Database::connect();

        $kriteria = [];
        $butirRows = [];
        if (!empty($instrumenIds)) {
            $kriteria = $db->table('lam_kriteria')
                ->select('id, kode, nama, lam_instrumen_id, urutan')
                ->whereIn('lam_instrumen_id', $instrumenIds)
                ->where('jenis', 'kondisi_eksternal')
                ->orderBy('urutan')->get()->getResultArray();

            if (!empty($kriteria)) {
                $kIds = array_column($kriteria, 'id');
                $butirRows = $db->table('lam_butir')
                    ->whereIn('lam_kriteria_id', $kIds)
                    ->orderBy('urutan')->get()->getResultArray();
            }
        }

        $svc = new EvidenAutoTarik();
        $audit = [];
        foreach ($butirRows as $b) {
            $audit[(int) $b['id']] = $this->auditButir($db, $prodiId, $b, $svc);
        }

        $summary = $this->ringkasan($audit);

        return view('eviden/audit/kondisi_eksternal', [
            'instrumenIds' => $instrumenIds,
            'kriteria'     => $kriteria,
            'butirRows'    => $butirRows,
            'audit'        => $audit,
            'summary'      => $summary,
        ]);
    }

    private function auditButir($db, int $prodiId, array $b, EvidenAutoTarik $svc): array
    {
        $bid = (int) $b['id'];

        $rubrikN = $db->table('lam_butir_rubrik')->where('lam_butir_id', $bid)->countAllResults();
        $butirSkorN = $db->table('butir_skor')
            ->where(['prodi_id' => $prodiId, 'lam_butir_id' => $bid])->countAllResults();

        // Eviden PPEPP (narasi & bukti)
        $ev = $db->table('eviden_ppepp')
            ->where(['prodi_id' => $prodiId, 'lam_butir_id' => $bid])->get()->getRowArray();
        $ppeppFase = 0;
        $ppeppBukti = 0;
        if ($ev) {
            $byFase = $db->table('eviden_ppepp_bukti')->select('fase, COUNT(id) AS n')
                ->where('eviden_id', $ev['id'])->groupBy('fase')->get()->getResultArray();
            $faseDone = [];
            foreach ($byFase as $bf) { $faseDone[$bf['fase']] = [1]; $ppeppBukti += (int) $bf['n']; }
            $ppeppFase = EvidenPpeppModel::progress($ev, $faseDone);
        }

        // Sumber otomatis relevan untuk butir ini (signal otomatisasi yg mungkin)
        $relevantSources = $svc->relevantFor($b);

        // Skor readiness 0..100
        $score = 0;
        if ($rubrikN >= 4) $score += 25; elseif ($rubrikN > 0) $score += 10;
        if (!empty($b['tabel_lkps_ref']) || !empty($b['formula'])) $score += 15;
        if ($butirSkorN > 0) $score += 20;
        $score += min(40, $ppeppFase * 8);

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

    private function ringkasan(array $audit): array
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
            $rekomendasiGlobal[] = ['ok', 'Prioritas Sprint 2: bangun modul "Input Kondisi Eksternal" terstruktur sebagai pengganti narasi LED bebas — output berupa data terstruktur yang dapat dirujuk oleh `tabel_lkps_ref` dan dirangkum AI di Sprint 3.'];
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
}
