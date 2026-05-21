<?php

namespace Modules\Akreditasi\Drivers\LamInfokomD3;

use Config\Database;
use Modules\Akreditasi\Contracts\AccreditationDriver;

/**
 * Driver Instrumen LAM INFOKOM 2.0 untuk D3.
 *
 * Strategi auto-skor:
 * - Untuk butir kuantitatif yang formulanya bisa dihitung dari data DB
 *   (mis. % linieritas DTPR, EWMP, masa tunggu, kesesuaian bidang kerja),
 *   driver mengembalikan skor 1-4 berdasarkan ambang.
 * - Untuk butir kualitatif, driver tidak mengisi skor (dibiarkan null
 *   agar tim GKM input manual di UI penilaian).
 *
 * Catatan: ini fondasi. Penambahan formula per butir dilakukan inkremental
 * sesuai prioritas (mulai dari C2 & C4 karena bobot terbesar).
 */
class Driver implements AccreditationDriver
{
    public function code(): string { return 'lam-infokom-2.0-d3'; }
    public function name(): string { return 'LAM INFOKOM 2.0 D3'; }

    public function computeScores(int $prodiId, ?int $tahunAkademikId = null): array
    {
        $scores = [];
        $db = Database::connect();

        // ── C1: DTPR ──────────────────────────────────────────────────────────
        $jumlahDosen = $db->table('dosen')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();

        if ($jumlahDosen > 0) {
            // % S3 → C1.x (ambang: ≥30% = 4, ≥20% = 3, ≥10% = 2, <10% = 1)
            $s3 = $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'pendidikan_terakhir' => 'S3'])
                ->countAllResults();
            $pctS3 = $s3 / $jumlahDosen * 100;
            $scores['C1.3'] = $pctS3 >= 30 ? 4 : ($pctS3 >= 20 ? 3 : ($pctS3 >= 10 ? 2 : 1));

            // % LK+GB → C1.x (ambang: ≥30%=4, ≥20%=3, ≥10%=2, <10%=1)
            $lkGb = $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1])
                ->whereIn('jabatan_akademik', ['Lektor Kepala', 'Guru Besar'])
                ->countAllResults();
            $pctLkGb = $lkGb / $jumlahDosen * 100;
            $scores['C1.4'] = $pctLkGb >= 30 ? 4 : ($pctLkGb >= 20 ? 3 : ($pctLkGb >= 10 ? 2 : 1));

            // % Serdos (≥80%=4, ≥60%=3, ≥40%=2, <40%=1)
            $serdos = $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'sertifikat_pendidik' => 1])
                ->countAllResults();
            $pctSerdos = $serdos / $jumlahDosen * 100;
            $scores['C1.5'] = $pctSerdos >= 80 ? 4 : ($pctSerdos >= 60 ? 3 : ($pctSerdos >= 40 ? 2 : 1));

            // % Linier (≥80%=4, ≥60%=3, ≥40%=2, <40%=1)
            $linier = $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'linieritas' => 'linier'])
                ->countAllResults();
            $pctLinier = $linier / $jumlahDosen * 100;
            $scores['C1.6'] = $pctLinier >= 80 ? 4 : ($pctLinier >= 60 ? 3 : ($pctLinier >= 40 ? 2 : 1));
        }

        // Rasio Dosen:Mahasiswa (D3 ideal 1:20 → 4; 1:25→3; 1:30→2; >30→1)
        $jumlahMhs = $db->table('mahasiswa')
            ->where(['prodi_id' => $prodiId, 'status' => 'aktif'])->countAllResults();
        if ($jumlahDosen > 0) {
            $rasio = $jumlahMhs / $jumlahDosen;
            $scores['C1.2'] = $rasio <= 20 ? 4 : ($rasio <= 25 ? 3 : ($rasio <= 30 ? 2 : 1));
        }

        // EWMP rata-rata total SKS (normal 12-16 → 4; 10-12 → 3; 8-10 → 2; <8 → 1)
        if ($tahunAkademikId && $jumlahDosen > 0) {
            $ewmpAvg = $db->table('dosen_ewmp e')
                ->selectAvg('e.total_sks', 'avg_sks')
                ->join('dosen d', 'd.id = e.dosen_id')
                ->where(['d.prodi_id' => $prodiId, 'e.tahun_akademik_id' => $tahunAkademikId])
                ->get()->getRowArray();
            if ($ewmpAvg && $ewmpAvg['avg_sks']) {
                $avg = (float)$ewmpAvg['avg_sks'];
                $scores['C1.7'] = ($avg >= 12 && $avg <= 16) ? 4 : ($avg >= 10 ? 3 : ($avg >= 8 ? 2 : 1));
            }
        }

        // ── C2: Kurikulum OBE ─────────────────────────────────────────────────
        $kuriRow = $db->table('kurikulum')->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getRowArray();
        if ($kuriRow) {
            $kuriId  = $kuriRow['id'];
            $jmlCpl  = $db->table('cpl')->where('kurikulum_id', $kuriId)->countAllResults();
            $jmlMk   = $db->table('mata_kuliah')->where('kurikulum_id', $kuriId)->countAllResults();

            // C2.1 — CPL tersedia & minimal (≥10=4, ≥7=3, ≥4=2, <4=1)
            if ($jmlCpl > 0) {
                $scores['C2.1'] = $jmlCpl >= 10 ? 4 : ($jmlCpl >= 7 ? 3 : ($jmlCpl >= 4 ? 2 : 1));
            }

            // C2.3 — Coverage CPL oleh MK: % CPL yang didukung ≥1 MK (≥100%=4, ≥80%=3, ≥60%=2, <60%=1)
            if ($jmlCpl > 0 && $jmlMk > 0) {
                $cplCovered = $db->query("
                    SELECT COUNT(DISTINCT mc.cpl_id) AS n FROM mk_cpl mc
                    JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id
                    WHERE mk.kurikulum_id = ?
                ", [$kuriId])->getRowArray();
                $pctCoverage = ($cplCovered['n'] ?? 0) / $jmlCpl * 100;
                $scores['C2.3'] = $pctCoverage >= 100 ? 4 : ($pctCoverage >= 80 ? 3 : ($pctCoverage >= 60 ? 2 : 1));
            }

            // C2.5 — Ketercapaian CPL rata-rata (≥75%=4, ≥60%=3, ≥50%=2, <50%=1)
            if ($tahunAkademikId) {
                $ketRow = $db->query("
                    SELECT AVG(ck.persen_tercapai) AS avg_pct
                    FROM cpl_ketercapaian ck
                    JOIN mata_kuliah mk ON mk.id = ck.mata_kuliah_id
                    WHERE mk.kurikulum_id = ? AND ck.tahun_akademik_id = ?
                ", [$kuriId, $tahunAkademikId])->getRowArray();
                if ($ketRow && $ketRow['avg_pct'] !== null) {
                    $avgPct = (float)$ketRow['avg_pct'];
                    $scores['C2.5'] = $avgPct >= 75 ? 4 : ($avgPct >= 60 ? 3 : ($avgPct >= 50 ? 2 : 1));
                }
            }
        }

        // ── C2: Kepuasan pengguna & Lulusan ───────────────────────────────────
        $db = Database::connect();
        $kpl = $db->table('kepuasan_pengguna_lulusan kpl')
            ->select('AVG((etika + keahlian + bahasa_asing + teknologi_info + komunikasi + kerjasama_tim + pengembangan_diri) / 7) AS rata')
            ->join('mahasiswa m', 'm.id = kpl.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->get()->getRowArray();
        if ($kpl && $kpl['rata']) {
            // Asumsi butir terkait kepuasan pengguna lulusan ada di C2 output
            $scores['C2.20'] = $this->clamp((float)$kpl['rata']);
        }

        // Contoh: masa tunggu kerja - skor 4 jika rata2 < 3 bulan, 3 jika <6, 2 jika <12, 1 jika >=12
        $ts = $db->table('tracer_study ts')
            ->select('AVG(masa_tunggu_bulan) AS rata')
            ->join('mahasiswa m', 'm.id = ts.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->get()->getRowArray();
        if ($ts && $ts['rata']) {
            $rata = (float)$ts['rata'];
            $scores['C2.16'] = $rata < 3 ? 4 : ($rata < 6 ? 3 : ($rata < 12 ? 2 : 1));
        }

        // Contoh: kesesuaian bidang kerja - % "sesuai" atau "sangat sesuai"
        $kes = $db->table('tracer_study ts')
            ->select("SUM(CASE WHEN kesesuaian_bidang_kerja IN ('sesuai','sangat_sesuai') THEN 1 ELSE 0 END) AS sesuai, COUNT(*) AS total")
            ->join('mahasiswa m', 'm.id = ts.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->get()->getRowArray();
        if ($kes && (int)$kes['total'] > 0) {
            $persen = ((int)$kes['sesuai'] / (int)$kes['total']) * 100;
            $scores['C2.17'] = $persen >= 80 ? 4 : ($persen >= 60 ? 3 : ($persen >= 40 ? 2 : 1));
        }

        return $scores;
    }

    public function buildLkpsTable(string $kodeTabel, int $prodiId, ?int $tahunAkademikId = null): array
    {
        return match ($kodeTabel) {
            '1.A.4' => $this->tabelEwmp($prodiId, $tahunAkademikId),
            '2.A.1' => $this->tabelDataMahasiswa($prodiId),
            '2.B.4' => $this->tabelMasaTunggu($prodiId),
            default => [],
        };
    }

    public function validateSubmission(int $prodiId): array
    {
        $err = [];
        $db = Database::connect();
        $vmts = $db->table('vmts')->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();
        if (!$vmts) $err[] = 'VMTS aktif belum diisi.';
        $jumlahDosen = $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();
        if ($jumlahDosen < 5) $err[] = "Jumlah DTPR aktif kurang dari 5 (saat ini: {$jumlahDosen}).";
        return $err;
    }

    // ---------- helpers ----------
    private function tabelEwmp(int $prodiId, ?int $taId): array
    {
        $db = Database::connect();
        $q = $db->table('dosen_ewmp e')
            ->select('d.nidn, d.nama, e.sks_pendidikan_ps, e.sks_pendidikan_ps_lain_pt, e.sks_pendidikan_pt_lain, e.sks_penelitian, e.sks_pkm, e.sks_tugas_tambahan, e.total_sks')
            ->join('dosen d', 'd.id = e.dosen_id')
            ->where('d.prodi_id', $prodiId);
        if ($taId) $q->where('e.tahun_akademik_id', $taId);
        return $q->get()->getResultArray();
    }

    private function tabelDataMahasiswa(int $prodiId): array
    {
        $db = Database::connect();
        return $db->table('mahasiswa')
            ->select('jenis_kelamin, status, COUNT(*) AS jumlah', false)
            ->where('prodi_id', $prodiId)
            ->groupBy(['jenis_kelamin','status'])
            ->get()->getResultArray();
    }

    private function tabelMasaTunggu(int $prodiId): array
    {
        $db = Database::connect();
        return $db->table('tracer_study ts')
            ->select('m.nim, m.nama, ts.masa_tunggu_bulan, ts.kesesuaian_bidang_kerja, ts.nama_perusahaan, ts.jabatan')
            ->join('mahasiswa m', 'm.id = ts.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->get()->getResultArray();
    }

    private function clamp(float $v): float
    {
        return max(1.0, min(4.0, round($v, 2)));
    }
}
