<?php

namespace Modules\Akreditasi\Drivers\LamInfokomS1;

use Config\Database;
use Modules\Akreditasi\Drivers\LamInfokomD3\Driver as D3Driver;

/**
 * Driver Instrumen LAM INFOKOM 2.0 untuk Program SARJANA (S1).
 *
 * Mewarisi pembangunan tabel LKPS (buildLkpsTable) dari driver D3 karena
 * tabel data inti (EWMP, mahasiswa, masa tunggu, penelitian, PkM, publikasi,
 * HKI, kerjasama) identik antar jenjang. Tabel S1 yang belum punya sumber
 * data terstruktur akan menghasilkan tabel kosong dan diisi manual.
 *
 * Auto-skor (computeScores) memetakan data prodi ke butir Pelaksanaan/Output
 * yang relevan dengan struktur kriteria S1 (berbeda dari D3). Butir kualitatif
 * (Penetapan/Evaluasi/Pengendalian/Peningkatan) tetap diisi manual.
 */
class Driver extends D3Driver
{
    public function code(): string { return 'lam-infokom-2.0-s1'; }
    public function name(): string { return 'LAM INFOKOM 2.0 Sarjana'; }

    public function computeScores(int $prodiId, ?int $tahunAkademikId = null): array
    {
        $db     = Database::connect();
        $scores = [];

        $jumlahDosen = (int) $db->table('dosen')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();

        // ── 2.2.A — Pelaksanaan DTPR & penerimaan mahasiswa baru ──────────────
        if ($jumlahDosen > 0) {
            $sub = [];

            $s3 = (int) $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'pendidikan_terakhir' => 'S3'])->countAllResults();
            $sub[] = $this->band($s3 / $jumlahDosen * 100, [30, 20, 10]);

            $lkGb = (int) $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1])
                ->whereIn('jabatan_akademik', ['Lektor Kepala', 'Guru Besar'])->countAllResults();
            $sub[] = $this->band($lkGb / $jumlahDosen * 100, [30, 20, 10]);

            $serdos = (int) $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'sertifikat_pendidik' => 1])->countAllResults();
            $sub[] = $this->band($serdos / $jumlahDosen * 100, [80, 60, 40]);

            $linier = (int) $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1, 'linieritas' => 'linier'])->countAllResults();
            $sub[] = $this->band($linier / $jumlahDosen * 100, [80, 60, 40]);

            $mhsAktif = (int) $db->table('mahasiswa')
                ->where(['prodi_id' => $prodiId, 'status' => 'aktif'])->countAllResults();
            // Rasio dosen:mahasiswa (makin kecil makin baik)
            $rasio = $mhsAktif / $jumlahDosen;
            $sub[] = $rasio <= 20 ? 4 : ($rasio <= 25 ? 3 : ($rasio <= 30 ? 2 : 1));

            $scores['2.2.A'] = $this->avg($sub);
        }

        // ── 2.2.B — Pelaksanaan isi pembelajaran & kurikulum OBE ──────────────
        $kuri = $db->table('kurikulum')->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getRowArray();
        if ($kuri) {
            $sub    = [];
            $kuriId = $kuri['id'];
            $jmlCpl = (int) $db->table('cpl')->where('kurikulum_id', $kuriId)->countAllResults();
            $jmlMk  = (int) $db->table('mata_kuliah')->where('kurikulum_id', $kuriId)->countAllResults();

            if ($jmlCpl > 0) {
                $sub[] = $this->band($jmlCpl, [10, 7, 4]);
                if ($jmlMk > 0) {
                    $cov = $db->query("
                        SELECT COUNT(DISTINCT mc.cpl_id) n FROM mk_cpl mc
                        JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id
                        WHERE mk.kurikulum_id = ?
                    ", [$kuriId])->getRow()->n;
                    $sub[] = $this->band($cov / $jmlCpl * 100, [100, 80, 60]);
                }
            }
            if ($tahunAkademikId) {
                $ket = $db->query("
                    SELECT AVG(ck.persen_tercapai) p
                    FROM cpl_ketercapaian ck
                    JOIN mata_kuliah mk ON mk.id = ck.mata_kuliah_id
                    WHERE mk.kurikulum_id = ? AND ck.tahun_akademik_id = ?
                ", [$kuriId, $tahunAkademikId])->getRow();
                if ($ket && $ket->p !== null) {
                    $sub[] = $this->band((float) $ket->p, [75, 60, 50]);
                }
            }
            if ($sub) $scores['2.2.B'] = $this->avg($sub);
        }

        // ── 2.2.D — Pelaksanaan kompetensi lulusan (tracer + kepuasan pengguna) ─
        $sub = [];
        $tracer = $db->query("
            SELECT AVG(ts.masa_tunggu_bulan) avg_tunggu,
                   SUM(CASE WHEN ts.kesesuaian_bidang_kerja IN ('sesuai','sangat_sesuai') THEN 1 ELSE 0 END) sesuai,
                   COUNT(*) total
            FROM tracer_study ts JOIN mahasiswa m ON m.id = ts.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getRowArray();
        if ($tracer && (int) $tracer['total'] > 0) {
            if ($tracer['avg_tunggu'] !== null) {
                $t = (float) $tracer['avg_tunggu'];
                $sub[] = $t < 3 ? 4 : ($t < 6 ? 3 : ($t < 12 ? 2 : 1));
            }
            $sub[] = $this->band((int) $tracer['sesuai'] / (int) $tracer['total'] * 100, [80, 60, 40]);
        }
        // Kepuasan pengguna lulusan (skala 1-4, langsung jadi sub-skor)
        $kpl = $db->query("
            SELECT AVG((etika + keahlian + bahasa_asing + teknologi_info + komunikasi + kerjasama_tim + pengembangan_diri) / 7) r
            FROM kepuasan_pengguna_lulusan k JOIN mahasiswa m ON m.id = k.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getRow();
        if ($kpl && $kpl->r !== null) {
            $sub[] = max(1, min(4, (int) round((float) $kpl->r)));
        }
        if ($sub) $scores['2.2.D'] = $this->avg($sub);

        // ── 3.2.C — Pelaksanaan luaran penelitian (hibah/publikasi/HKI) ───────
        $pen = $db->query("
            SELECT COUNT(*) total,
                   SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) eksternal
            FROM penelitian WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();
        if ($pen && (int) $pen['total'] > 0 && $jumlahDosen > 0) {
            $sub   = [];
            $total = (int) $pen['total'];
            $sub[] = $this->band($total / $jumlahDosen, [1, 0.5, 0.25]);
            $sub[] = $this->band((int) $pen['eksternal'] / $total * 100, [50, 30, 15]);

            $pub = (int) $db->table('publikasi')->where(['prodi_id' => $prodiId, 'kategori' => 'penelitian'])->countAllResults();
            $sub[] = $this->band($pub / $jumlahDosen, [2, 1, 0.5]);

            $hki = (int) $db->table('hki')->where(['prodi_id' => $prodiId, 'kategori' => 'penelitian'])->countAllResults();
            $sub[] = $this->band($hki / $jumlahDosen, [1, 0.5, 0.2]);

            $scores['3.2.C'] = $this->avg($sub);
        }

        // ── 4.2.C — Pelaksanaan luaran PkM (hibah/kerjasama/diseminasi) ───────
        $pkm = $db->query("
            SELECT COUNT(*) total,
                   SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) eksternal
            FROM pkm WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();
        if ($pkm && (int) $pkm['total'] > 0 && $jumlahDosen > 0) {
            $sub   = [];
            $total = (int) $pkm['total'];
            $sub[] = $this->band($total / $jumlahDosen, [1, 0.5, 0.25]);
            $sub[] = $this->band((int) $pkm['eksternal'] / $total * 100, [50, 30, 15]);

            $ksIntl = (int) $db->table('kerjasama')
                ->where(['prodi_id' => $prodiId, 'tingkat' => 'internasional'])->countAllResults();
            $sub[] = $ksIntl >= 5 ? 4 : ($ksIntl >= 3 ? 3 : ($ksIntl >= 1 ? 2 : 1));

            $scores['4.2.C'] = $this->avg($sub);
        }

        return $scores;
    }

    /** Skor 4/3/2/1 berdasarkan 3 ambang menurun [a4, a3, a2]. */
    private function band(float $nilai, array $ambang): int
    {
        [$a4, $a3, $a2] = $ambang;
        return $nilai >= $a4 ? 4 : ($nilai >= $a3 ? 3 : ($nilai >= $a2 ? 2 : 1));
    }

    /** Rata-rata sub-skor, dibulatkan 2 desimal, dibatasi 1..4. */
    private function avg(array $scores): float
    {
        $vals = array_filter($scores, fn($v) => $v !== null);
        if (!$vals) return 1.0;
        return max(1.0, min(4.0, round(array_sum($vals) / count($vals), 2)));
    }
}
