<?php

namespace App\Services;

use Config\Database;
use Modules\Akreditasi\Engine\AkreditasiEngine;

/**
 * Merakit konteks data real program studi untuk prompt Groq AI,
 * sehingga narasi LED dihasilkan berdasarkan angka asli dari database,
 * bukan karangan model.
 *
 * Konteks yang dirakit per bagian LED:
 *  - Profil prodi + VMTS
 *  - Ringkasan kuantitatif (DTPR, mahasiswa, lulusan, penelitian, PkM,
 *    publikasi, HKI, kerjasama)
 *  - Skor penilaian akreditasi (total + butir kriteria terkait)
 *  - Tabel LKPS detail yang relevan dengan kriteria bagian tersebut
 */
class LedContextBuilder
{
    private \CodeIgniter\Database\BaseConnection $db;
    private AkreditasiEngine $engine;

    /** Maks baris detail tabel agar prompt tidak membengkak. */
    private const MAX_ROWS = 60;

    /** Pemetaan kriteria → kode tabel LKPS yang relevan. */
    private const KRITERIA_TABEL = [
        'C1' => ['1.A.4'],
        'C2' => ['2.A.1', '2.B.4', '2.B.5', '2.B.6'],
        'C3' => ['3.A.2', '3.C.2', '3.C.3', '3.C.1'],
        'C4' => ['4.A.2', '4.C.2', '4.C.3', '4.C.1'],
    ];

    public function __construct()
    {
        $this->db     = Database::connect();
        $this->engine = new AkreditasiEngine();
    }

    public function build(int $prodiId, int $templateId, ?int $taId = null): string
    {
        $tpl = $this->db->query("
            SELECT t.*, k.kode AS kriteria_kode, k.nama AS kriteria_nama
            FROM lam_template_led t
            LEFT JOIN lam_kriteria k ON k.id = t.lam_kriteria_id
            WHERE t.id = ?
        ", [$templateId])->getRowArray();

        $kriteriaKode = $tpl['kriteria_kode'] ?? null;

        $parts   = [];
        $parts[] = $this->profil($prodiId);
        $parts[] = $this->ringkasan($prodiId);
        $parts[] = $this->skor($prodiId, $kriteriaKode, $taId);

        if ($kriteriaKode && isset(self::KRITERIA_TABEL[$kriteriaKode])) {
            $driver = $this->engine->getDriver($prodiId);
            if ($driver) {
                foreach (self::KRITERIA_TABEL[$kriteriaKode] as $kode) {
                    $rows = $driver->buildLkpsTable($kode, $prodiId, $taId);
                    $parts[] = $this->renderTabel($kode, $rows);
                }
            }
        }

        $parts[] = "CATATAN PENTING: Gunakan HANYA angka dan fakta di atas. "
            . "Jika suatu data tertulis '(belum ada data)', nyatakan secara jujur "
            . "bahwa data tersebut belum tersedia/sedang dilengkapi — JANGAN mengarang angka.";

        return implode("\n\n", array_filter($parts));
    }

    // ── Bagian-bagian konteks ────────────────────────────────────────────────

    private function profil(int $prodiId): string
    {
        $p = $this->db->query("
            SELECT p.*, u.nama AS upps_nama, pt.nama AS pt_nama, pt.singkatan AS pt_singkatan
            FROM prodi p
            LEFT JOIN upps u ON u.id = p.upps_id
            LEFT JOIN perguruan_tinggi pt ON pt.id = u.perguruan_tinggi_id
            WHERE p.id = ?
        ", [$prodiId])->getRowArray();

        if (!$p) return '';

        $lines = [
            'Program Studi: ' . ($p['nama'] ?? '-') . ' (' . ($p['jenjang'] ?? '-') . ')',
            'Perguruan Tinggi: ' . ($p['pt_nama'] ?? '-') . ($p['pt_singkatan'] ? ' (' . $p['pt_singkatan'] . ')' : ''),
            'Unit Pengelola (UPPS): ' . ($p['upps_nama'] ?: '-'),
            'Kode PDDIKTI: ' . ($p['kode_pddikti'] ?? '-'),
            'Gelar lulusan: ' . ($p['gelar'] ?: '-'),
            'Peringkat akreditasi saat ini: ' . ($p['akreditasi_saat_ini'] ?: 'Belum terakreditasi')
                . ($p['no_sk_akreditasi'] ? ' (SK ' . $p['no_sk_akreditasi'] . ')' : ''),
        ];

        $v = $this->db->query("
            SELECT visi, misi, tujuan, strategi FROM vmts
            WHERE prodi_id = ? AND aktif = 1 ORDER BY id DESC LIMIT 1
        ", [$prodiId])->getRowArray();

        if ($v) {
            $lines[] = 'Visi: ' . trim((string) $v['visi']);
            $lines[] = 'Misi: ' . trim((string) $v['misi']);
            $lines[] = 'Tujuan: ' . trim((string) $v['tujuan']);
            if (!empty($v['strategi'])) $lines[] = 'Strategi: ' . trim((string) $v['strategi']);
        } else {
            $lines[] = 'VMTS: (belum ada data)';
        }

        return "=== PROFIL PROGRAM STUDI ===\n" . implode("\n", $lines);
    }

    private function ringkasan(int $prodiId): string
    {
        $out = ['=== RINGKASAN DATA KUANTITATIF ==='];

        // DTPR / Dosen
        $totalDosen = (int) $this->db->table('dosen')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();
        if ($totalDosen > 0) {
            $pend = $this->groupCount('dosen', 'pendidikan_terakhir', $prodiId, ['aktif' => 1]);
            $jab  = $this->groupCount('dosen', 'jabatan_akademik', $prodiId, ['aktif' => 1]);
            $serdos = (int) $this->db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'sertifikat_pendidik' => 1])->countAllResults();
            $linier = (int) $this->db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'linieritas' => 'linier'])->countAllResults();

            $out[] = "Dosen Tetap (DTPR) aktif: {$totalDosen} orang";
            $out[] = '  - Pendidikan terakhir: ' . $this->fmtGroup($pend);
            $out[] = '  - Jabatan akademik: ' . $this->fmtGroup($jab);
            $out[] = sprintf('  - Bersertifikat pendidik: %d (%.1f%%)', $serdos, $serdos / $totalDosen * 100);
            $out[] = sprintf('  - Linier dengan bidang prodi: %d (%.1f%%)', $linier, $linier / $totalDosen * 100);

            // Produktivitas riset DTPR (metrik OpenAlex via ORCID), jika tersedia.
            if ($this->db->fieldExists('h_index', 'dosen')) {
                $m = $this->db->query("
                    SELECT COUNT(metrik_updated_at) AS sinkron,
                           SUM(sitasi_total) AS sitasi, SUM(karya_total) AS karya,
                           MAX(h_index) AS h_max, AVG(h_index) AS h_avg
                    FROM dosen WHERE prodi_id = ? AND aktif = 1 AND metrik_updated_at IS NOT NULL
                ", [$prodiId])->getRowArray();
                if ($m && (int) $m['sinkron'] > 0) {
                    $out[] = sprintf(
                        '  - Produktivitas riset DTPR (sumber OpenAlex, %d dosen tersinkron): total sitasi %d; total karya %d; h-index tertinggi %d; rata-rata h-index %.1f',
                        (int) $m['sinkron'], (int) $m['sitasi'], (int) $m['karya'],
                        (int) $m['h_max'], (float) $m['h_avg']
                    );
                }
            }
        } else {
            $out[] = 'Dosen Tetap (DTPR): (belum ada data)';
        }

        // Mahasiswa
        $totalMhs = (int) $this->db->table('mahasiswa')->where('prodi_id', $prodiId)->countAllResults();
        if ($totalMhs > 0) {
            $byStatus = $this->groupCount('mahasiswa', 'status', $prodiId);
            $out[] = "Mahasiswa (total {$totalMhs}): " . $this->fmtGroup($byStatus);
        } else {
            $out[] = 'Mahasiswa: (belum ada data)';
        }

        // Lulusan / Tracer study
        $tracer = $this->db->query("
            SELECT COUNT(*) total, AVG(ts.masa_tunggu_bulan) avg_tunggu,
                   SUM(CASE WHEN ts.kesesuaian_bidang_kerja IN ('sesuai','sangat_sesuai') THEN 1 ELSE 0 END) sesuai
            FROM tracer_study ts JOIN mahasiswa m ON m.id = ts.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getRowArray();
        if ($tracer && (int) $tracer['total'] > 0) {
            $t = (int) $tracer['total'];
            $out[] = sprintf(
                'Tracer study lulusan: %d responden; rata-rata masa tunggu kerja %.1f bulan; kesesuaian bidang kerja %.1f%%',
                $t, (float) $tracer['avg_tunggu'], (int) $tracer['sesuai'] / $t * 100
            );
        } else {
            $out[] = 'Tracer study lulusan: (belum ada data)';
        }

        // Penelitian & PkM
        $out[] = $this->ringkasTridharma('penelitian', $prodiId, 'Penelitian');
        $out[] = $this->ringkasTridharma('pkm', $prodiId, 'PkM');

        // Publikasi, HKI, Kerjasama
        $pub = $this->groupCount('publikasi', 'jenis', $prodiId);
        if (array_sum($pub) > 0) {
            $out[] = 'Publikasi ilmiah (total ' . array_sum($pub) . '): ' . $this->fmtGroup($pub);
            $ps = $this->db->query("
                SELECT SUM(sitasi) AS sitasi,
                       SUM(CASE WHEN sitasi > 0 THEN 1 ELSE 0 END) AS tersitasi,
                       SUM(CASE WHEN doi IS NOT NULL AND doi <> '' THEN 1 ELSE 0 END) AS ber_doi
                FROM publikasi WHERE prodi_id = ?
            ", [$prodiId])->getRowArray();
            $total = array_sum($pub);
            $out[] = sprintf(
                '  - Sitasi: total %d sitasi; %d publikasi tersitasi; %d ber-DOI (%.0f%% cakupan DOI)',
                (int) ($ps['sitasi'] ?? 0), (int) ($ps['tersitasi'] ?? 0),
                (int) ($ps['ber_doi'] ?? 0), $total ? (int) $ps['ber_doi'] / $total * 100 : 0
            );
        } else {
            $out[] = 'Publikasi ilmiah: (belum ada data)';
        }

        $totalHki = (int) $this->db->table('hki')->where('prodi_id', $prodiId)->countAllResults();
        $out[] = $totalHki > 0 ? "HKI: {$totalHki} luaran" : 'HKI: (belum ada data)';

        $ks = $this->groupCount('kerjasama', 'tingkat', $prodiId);
        $out[] = array_sum($ks) > 0
            ? 'Kerjasama (total ' . array_sum($ks) . '): ' . $this->fmtGroup($ks)
            : 'Kerjasama: (belum ada data)';

        return implode("\n", $out);
    }

    private function ringkasTridharma(string $tabel, int $prodiId, string $label): string
    {
        $r = $this->db->query("
            SELECT COUNT(*) total,
                   SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) eksternal,
                   SUM(CASE WHEN libatkan_mahasiswa = 1 THEN 1 ELSE 0 END) libat_mhs,
                   MIN(tahun_pelaksanaan) thn_min, MAX(tahun_pelaksanaan) thn_max
            FROM {$tabel} WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();

        if (!$r || (int) $r['total'] === 0) {
            return "{$label}: (belum ada data)";
        }
        $t = (int) $r['total'];
        return sprintf(
            '%s: %d kegiatan (tahun %s–%s); pendanaan eksternal %d (%.1f%%); melibatkan mahasiswa %d (%.1f%%)',
            $label, $t, $r['thn_min'] ?? '-', $r['thn_max'] ?? '-',
            (int) $r['eksternal'], (int) $r['eksternal'] / $t * 100,
            (int) $r['libat_mhs'], (int) $r['libat_mhs'] / $t * 100
        );
    }

    private function skor(int $prodiId, ?string $kriteriaKode, ?int $taId): string
    {
        $instrumen = $this->engine->getInstrumen($prodiId);
        if (!$instrumen) return '';

        $total = $this->engine->totalSkor($prodiId, $instrumen['id'], $taId);
        $out = ['=== SKOR PENILAIAN AKREDITASI (LAM INFOKOM) ==='];
        $out[] = sprintf(
            'Nilai akhir sementara: %.2f dari %.0f (%.1f%%) — predikat: %s',
            $total['nilai_akhir'], $total['max_nilai'], $total['pct'], $total['predikat']
        );

        $kriteria = $this->engine->getKriteriaWithButir($prodiId, $instrumen['id'], $taId);

        // Ringkasan skor semua kriteria
        foreach ($kriteria as $k) {
            $skor = $k['skor_rata'] !== null ? number_format($k['skor_rata'], 2) : 'belum dinilai';
            $out[] = "  - {$k['kode']} {$k['nama']}: skor rata-rata {$skor} ({$k['jml_isi']}/{$k['jml_butir']} butir dinilai, bobot {$k['total_bobot']})";
        }

        // Detail butir untuk kriteria bagian ini
        if ($kriteriaKode) {
            foreach ($kriteria as $k) {
                if ($k['kode'] !== $kriteriaKode) continue;
                if (empty($k['butir'])) break;
                $out[] = "Detail butir kriteria {$kriteriaKode} ({$k['nama']}):";
                foreach ($k['butir'] as $b) {
                    $s = ($b['skor_final'] ?? $b['skor_auto']);
                    $out[] = sprintf('  - %s %s: skor %s', $b['kode'], $b['judul'], $s !== null ? $s : 'belum dinilai')
                        . (!empty($b['catatan']) ? ' | catatan: ' . $b['catatan'] : '');
                }
                break;
            }
        }

        return implode("\n", $out);
    }

    private function renderTabel(string $kode, array $rows): string
    {
        $judul = "Tabel LKPS {$kode}";
        if (empty($rows)) {
            return "=== DETAIL {$judul} ===\n(belum ada data)";
        }

        $headers = array_keys($rows[0]);
        $lines   = [implode(' | ', $headers)];

        $shown = array_slice($rows, 0, self::MAX_ROWS);
        foreach ($shown as $r) {
            $vals = array_map(fn($v) => is_scalar($v) ? (string) $v : json_encode($v), array_values($r));
            $lines[] = implode(' | ', $vals);
        }
        if (count($rows) > self::MAX_ROWS) {
            $lines[] = '... (' . (count($rows) - self::MAX_ROWS) . ' baris lainnya tidak ditampilkan)';
        }

        return "=== DETAIL {$judul} (total " . count($rows) . " baris) ===\n" . implode("\n", $lines);
    }

    // ── util ──────────────────────────────────────────────────────────────────

    /** GROUP BY kolom → [nilai => jumlah]. */
    private function groupCount(string $tabel, string $kolom, int $prodiId, array $extra = []): array
    {
        $q = $this->db->table($tabel)
            ->select("{$kolom} AS v, COUNT(*) AS n", false)
            ->where('prodi_id', $prodiId);
        foreach ($extra as $k => $val) $q->where($k, $val);
        $rows = $q->groupBy($kolom)->get()->getResultArray();

        $out = [];
        foreach ($rows as $r) {
            $out[$r['v'] ?: '(tidak diisi)'] = (int) $r['n'];
        }
        return $out;
    }

    private function fmtGroup(array $g): string
    {
        if (empty($g)) return '(belum ada data)';
        $parts = [];
        foreach ($g as $k => $n) $parts[] = "{$k}={$n}";
        return implode(', ', $parts);
    }
}
