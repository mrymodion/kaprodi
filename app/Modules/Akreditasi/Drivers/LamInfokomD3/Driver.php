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

        // ── C3: Penelitian ────────────────────────────────────────────────────
        $penelitian = $db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) AS eksternal,
                SUM(CASE WHEN sumber_dana = 'luar_negeri' THEN 1 ELSE 0 END) AS luar_negeri,
                SUM(CASE WHEN libatkan_mahasiswa = 1 THEN 1 ELSE 0 END) AS libat_mhs
            FROM penelitian WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();

        if ($penelitian && (int)$penelitian['total'] > 0) {
            // C3.1 — Jumlah penelitian per dosen per tahun (≥1/thn/dosen=4, ≥0.5=3, ≥0.25=2, <0.25=1)
            if ($jumlahDosen > 0) {
                $penelitianPerDosen = (int)$penelitian['total'] / $jumlahDosen;
                $scores['C3.1'] = $penelitianPerDosen >= 1 ? 4 : ($penelitianPerDosen >= 0.5 ? 3 : ($penelitianPerDosen >= 0.25 ? 2 : 1));
            }
            // C3.2 — % penelitian eksternal (≥50%=4, ≥30%=3, ≥15%=2, <15%=1)
            $pctEksternal = (int)$penelitian['eksternal'] / (int)$penelitian['total'] * 100;
            $scores['C3.2'] = $pctEksternal >= 50 ? 4 : ($pctEksternal >= 30 ? 3 : ($pctEksternal >= 15 ? 2 : 1));
            // C3.3 — Keterlibatan mahasiswa (≥50% penelitian libatkan mhs=4, ≥30%=3, ≥10%=2, <10%=1)
            $pctLibatMhs = (int)$penelitian['libat_mhs'] / (int)$penelitian['total'] * 100;
            $scores['C3.3'] = $pctLibatMhs >= 50 ? 4 : ($pctLibatMhs >= 30 ? 3 : ($pctLibatMhs >= 10 ? 2 : 1));
        }

        // Publikasi dari penelitian (C3.4 — jurnal internasional bereputasi)
        $pub = $db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN jenis = 'jurnal_internasional_bereputasi' THEN 1 ELSE 0 END) AS intl_bereputasi,
                SUM(CASE WHEN jenis IN ('jurnal_internasional','jurnal_internasional_bereputasi') THEN 1 ELSE 0 END) AS intl
            FROM publikasi WHERE prodi_id = ? AND kategori = 'penelitian'
        ", [$prodiId])->getRowArray();

        if ($pub && (int)$pub['total'] > 0 && $jumlahDosen > 0) {
            $pubPerDosen = (int)$pub['total'] / $jumlahDosen;
            $scores['C3.4'] = $pubPerDosen >= 2 ? 4 : ($pubPerDosen >= 1 ? 3 : ($pubPerDosen >= 0.5 ? 2 : 1));
        }

        // HKI dari penelitian (C3.5)
        $hki = $db->query("
            SELECT COUNT(*) AS total FROM hki WHERE prodi_id = ? AND kategori = 'penelitian'
        ", [$prodiId])->getRowArray();

        if ($hki && $jumlahDosen > 0) {
            $hkiPerDosen = (int)$hki['total'] / $jumlahDosen;
            $scores['C3.5'] = $hkiPerDosen >= 1 ? 4 : ($hkiPerDosen >= 0.5 ? 3 : ($hkiPerDosen >= 0.2 ? 2 : 1));
        }

        // ── C4: PkM ───────────────────────────────────────────────────────────
        $pkm = $db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) AS eksternal,
                SUM(CASE WHEN libatkan_mahasiswa = 1 THEN 1 ELSE 0 END) AS libat_mhs
            FROM pkm WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();

        if ($pkm && (int)$pkm['total'] > 0) {
            // C4.1 — Jumlah PkM per dosen per tahun
            if ($jumlahDosen > 0) {
                $pkmPerDosen = (int)$pkm['total'] / $jumlahDosen;
                $scores['C4.1'] = $pkmPerDosen >= 1 ? 4 : ($pkmPerDosen >= 0.5 ? 3 : ($pkmPerDosen >= 0.25 ? 2 : 1));
            }
            // C4.2 — % PkM eksternal
            $pctEksternalPkm = (int)$pkm['eksternal'] / (int)$pkm['total'] * 100;
            $scores['C4.2'] = $pctEksternalPkm >= 50 ? 4 : ($pctEksternalPkm >= 30 ? 3 : ($pctEksternalPkm >= 15 ? 2 : 1));
            // C4.3 — Keterlibatan mahasiswa
            $pctLibatMhsPkm = (int)$pkm['libat_mhs'] / (int)$pkm['total'] * 100;
            $scores['C4.3'] = $pctLibatMhsPkm >= 50 ? 4 : ($pctLibatMhsPkm >= 30 ? 3 : ($pctLibatMhsPkm >= 10 ? 2 : 1));
        }

        // Kerjasama internasional (C4.5 — mendukung akreditasi internasional)
        $ksIntl = $db->query("
            SELECT COUNT(*) AS total FROM kerjasama
            WHERE prodi_id = ? AND tingkat = 'internasional'
        ", [$prodiId])->getRowArray();
        if ($ksIntl) {
            $n = (int)$ksIntl['total'];
            $scores['C4.5'] = $n >= 5 ? 4 : ($n >= 3 ? 3 : ($n >= 1 ? 2 : 1));
        }

        return $scores;
    }

    public function buildLkpsTable(string $kodeTabel, int $prodiId, ?int $tahunAkademikId = null): array
    {
        return match ($kodeTabel) {
            '1.A.4' => $this->tabelEwmp($prodiId, $tahunAkademikId),
            '2.A.1' => $this->tabelDataMahasiswa($prodiId),
            '2.A.2' => $this->tabelKeragamanMhs($prodiId),
            '2.A.3' => $this->tabelKondisiMhs($prodiId),
            '2.B.1' => $this->tabelIsiPembelajaran($prodiId),
            '2.B.2' => $this->tabelDaftarCpl($prodiId),
            '2.B.3' => $this->tabelPemenuhanCpl($prodiId),
            '2.B.4' => $this->tabelMasaTunggu($prodiId),
            '2.B.5' => $this->tabelKesesuaian($prodiId),
            '2.B.6' => $this->tabelKepuasan($prodiId),
            '3.A.2' => $this->tabelPenelitian($prodiId),
            '3.C.1' => $this->tabelKerjasama($prodiId, 'penelitian'),
            '3.C.2' => $this->tabelPublikasi($prodiId, 'penelitian'),
            '3.C.3' => $this->tabelHki($prodiId, 'penelitian'),
            '4.A.2' => $this->tabelPkm($prodiId),
            '4.C.1' => $this->tabelKerjasama($prodiId, 'pkm'),
            '4.C.2' => $this->tabelPublikasi($prodiId, 'pkm'),
            '4.C.3' => $this->tabelHki($prodiId, 'pkm'),
            '3.A.1' => $this->tabelSarpras($prodiId, 'untuk_penelitian'),
            '4.A.1' => $this->tabelSarpras($prodiId, 'untuk_pkm'),
            '5.2'   => $this->tabelSarpras($prodiId, 'untuk_pendidikan'),
            '6'     => $this->tabelVmts($prodiId),
            '1.A.2' => $this->tabelKeuangan($prodiId, 'pemasukan'),
            '1.A.3' => $this->tabelKeuangan($prodiId, 'pengeluaran'),
            '1.A.5' => $this->tabelTendik($prodiId),
            '1.A.1' => $this->tabelBorang($prodiId, 'tata_pamong', false),
            '1.B'   => $this->tabelBorang($prodiId, 'spmi_unit', false),
            '2.C'   => $this->tabelBorang($prodiId, 'fleksibilitas', true),
            '2.D'   => $this->tabelBorang($prodiId, 'rekognisi_lulusan', true),
            '3.A.3' => $this->tabelBorang($prodiId, 'pengembangan_dtpr', true),
            '5.1'   => $this->tabelBorang($prodiId, 'tata_kelola_si', false),
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

    private function kurikulumAktifId(int $prodiId): ?int
    {
        $row = Database::connect()->table('kurikulum')->select('id')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getRowArray();
        return $row ? (int) $row['id'] : null;
    }

    /** Tabel 2.A.2 — Keragaman Asal Mahasiswa (per provinsi asal). */
    private function tabelKeragamanMhs(int $prodiId): array
    {
        return Database::connect()->table('mahasiswa')
            ->select('provinsi_asal, COUNT(*) AS jumlah', false)
            ->where('prodi_id', $prodiId)
            ->groupBy('provinsi_asal')
            ->orderBy('jumlah', 'DESC')
            ->get()->getResultArray();
    }

    /** Tabel 2.A.3 — Kondisi Jumlah Mahasiswa (per status). */
    private function tabelKondisiMhs(int $prodiId): array
    {
        $db     = Database::connect();
        $labels = ['aktif' => 'Mahasiswa Aktif', 'lulus' => 'Lulus', 'cuti' => 'Cuti', 'do' => 'Drop Out (DO)', 'keluar' => 'Mengundurkan Diri/Keluar'];
        $rows   = $db->table('mahasiswa')->select('status, COUNT(*) AS jumlah', false)
            ->where('prodi_id', $prodiId)->groupBy('status')->get()->getResultArray();
        $map = [];
        foreach ($rows as $r) $map[$r['status']] = (int) $r['jumlah'];
        $out = [];
        foreach ($labels as $k => $l) $out[] = ['kondisi' => $l, 'jumlah' => $map[$k] ?? 0];
        return $out;
    }

    /** Tabel 2.B.1 — Isi Pembelajaran (daftar mata kuliah kurikulum aktif). */
    private function tabelIsiPembelajaran(int $prodiId): array
    {
        $kid = $this->kurikulumAktifId($prodiId);
        if (!$kid) return [];
        return Database::connect()->table('mata_kuliah')
            ->select('kode, nama, (sks_teori + sks_praktek + sks_lapangan) AS sks, semester, jenis', false)
            ->where('kurikulum_id', $kid)
            ->orderBy('semester, kode')
            ->get()->getResultArray();
    }

    /** Tabel 2.B.2 — Daftar CPL kurikulum aktif. */
    private function tabelDaftarCpl(int $prodiId): array
    {
        $kid = $this->kurikulumAktifId($prodiId);
        if (!$kid) return [];
        return Database::connect()->table('cpl')
            ->select('kode, aspek, deskripsi')
            ->where('kurikulum_id', $kid)
            ->orderBy("FIELD(aspek,'sikap','pengetahuan','keterampilan_umum','keterampilan_khusus'), kode", '', false)
            ->get()->getResultArray();
    }

    /** Tabel 2.B.3 — Peta Pemenuhan CPL oleh Mata Kuliah. */
    private function tabelPemenuhanCpl(int $prodiId): array
    {
        $kid = $this->kurikulumAktifId($prodiId);
        if (!$kid) return [];
        return Database::connect()->query("
            SELECT c.kode AS cpl_kode,
                   COUNT(mc.id) AS jml_mk,
                   GROUP_CONCAT(mk.kode ORDER BY mk.kode SEPARATOR ', ') AS mk_list
            FROM cpl c
            LEFT JOIN mk_cpl mc ON mc.cpl_id = c.id
            LEFT JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id
            WHERE c.kurikulum_id = ?
            GROUP BY c.id, c.kode
            ORDER BY c.kode
        ", [$kid])->getResultArray();
    }

    /** Tabel 1.A.2 / 1.A.3 — Sumber Pendanaan / Penggunaan Dana. */
    private function tabelKeuangan(int $prodiId, string $arah): array
    {
        return Database::connect()->table('keuangan k')
            ->select('k.sumber_atau_pos, k.kategori, k.jumlah, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = k.tahun_akademik_id', 'left')
            ->where(['k.prodi_id' => $prodiId, 'k.arah' => $arah])
            ->orderBy('ta.tahun_mulai DESC, k.sumber_atau_pos')
            ->get()->getResultArray();
    }

    /** Tabel 1.A.5 — Kualifikasi Tenaga Kependidikan (rekap jenis x pendidikan). */
    private function tabelTendik(int $prodiId): array
    {
        $db   = Database::connect();
        $rows = $db->table('tendik')->select('jenis, pendidikan_terakhir, COUNT(*) AS n', false)
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])
            ->groupBy(['jenis', 'pendidikan_terakhir'])->get()->getResultArray();

        $jenisLabel = ['pustakawan' => 'Pustakawan', 'laboran' => 'Laboran', 'teknisi' => 'Teknisi',
            'operator' => 'Operator', 'administrasi' => 'Administrasi', 'lainnya' => 'Lainnya'];
        $jenjang = ['S3', 'S2', 'S1', 'D4', 'D3', 'D2', 'D1', 'SMA'];

        $rekap = [];
        foreach ($jenisLabel as $jk => $jl) {
            $rekap[$jk] = ['jenis' => $jl] + array_fill_keys($jenjang, 0) + ['total' => 0];
        }
        foreach ($rows as $r) {
            $jk = $r['jenis']; $pd = $r['pendidikan_terakhir'];
            if (!isset($rekap[$jk])) continue;
            if ($pd && isset($rekap[$jk][$pd])) $rekap[$jk][$pd] += (int) $r['n'];
            $rekap[$jk]['total'] += (int) $r['n'];
        }
        // Hanya tampilkan jenis yang ada datanya
        return array_values(array_filter($rekap, fn($r) => $r['total'] > 0));
    }

    /** Tabel borang pelengkap (1.A.1, 1.B, 2.C, 2.D, 3.A.3, 5.1). */
    private function tabelBorang(int $prodiId, string $table, bool $withTa): array
    {
        $q = Database::connect()->table($table . ' t')->where('t.prodi_id', $prodiId);
        if ($withTa) {
            $q->select('t.*, ta.kode AS ta_kode')->join('tahun_akademik ta', 'ta.id = t.tahun_akademik_id', 'left');
        } else {
            $q->select('t.*');
        }
        return $q->orderBy('t.id')->get()->getResultArray();
    }

    /** Tabel 3.A.1 / 4.A.1 / 5.2 — Sarana & Prasarana (per peruntukan). */
    private function tabelSarpras(int $prodiId, string $flag): array
    {
        return Database::connect()->table('sarpras')
            ->select('nama, kategori, jumlah, luas_m2, kapasitas, kondisi, kepemilikan')
            ->where('prodi_id', $prodiId)
            ->where($flag, 1)
            ->orderBy('kategori, nama')
            ->get()->getResultArray();
    }

    /** Tabel 6 — Kesesuaian Visi, Misi, Tujuan, Strategi. */
    private function tabelVmts(int $prodiId): array
    {
        $v = Database::connect()->table('vmts')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->orderBy('id', 'DESC')->get()->getRowArray();
        if (!$v) return [];
        return [
            ['komponen' => 'Visi',     'isi' => $v['visi'] ?? '-'],
            ['komponen' => 'Misi',     'isi' => $v['misi'] ?? '-'],
            ['komponen' => 'Tujuan',   'isi' => $v['tujuan'] ?? '-'],
            ['komponen' => 'Strategi', 'isi' => $v['strategi'] ?? '-'],
        ];
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

    /** Tabel 2.B.5 — Kesesuaian Bidang Kerja Lulusan (per responden tracer). */
    private function tabelKesesuaian(int $prodiId): array
    {
        $db = Database::connect();
        return $db->table('tracer_study ts')
            ->select('m.nim, m.nama, m.tgl_lulus, ts.kesesuaian_bidang_kerja, ts.tingkat_pekerjaan, ts.nama_perusahaan, ts.jabatan')
            ->join('mahasiswa m', 'm.id = ts.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->orderBy('m.tgl_lulus DESC, m.nama')
            ->get()->getResultArray();
    }

    /** Tabel 2.B.6 — Kepuasan Pengguna Lulusan (% per aspek, 7 baris). */
    private function tabelKepuasan(int $prodiId): array
    {
        $db   = Database::connect();
        $rows = $db->query("
            SELECT etika, keahlian, bahasa_asing, teknologi_info, komunikasi, kerjasama_tim, pengembangan_diri
            FROM kepuasan_pengguna_lulusan k JOIN mahasiswa m ON m.id = k.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getResultArray();

        $aspek = [
            'etika' => 'Etika', 'keahlian' => 'Keahlian pada bidang ilmu',
            'bahasa_asing' => 'Kemampuan bahasa asing', 'teknologi_info' => 'Penggunaan teknologi informasi',
            'komunikasi' => 'Kemampuan komunikasi', 'kerjasama_tim' => 'Kerjasama tim',
            'pengembangan_diri' => 'Pengembangan diri',
        ];
        $out = [];
        foreach ($aspek as $key => $label) {
            $vals = array_filter(array_column($rows, $key), fn($v) => $v !== null && $v !== '');
            $n    = count($vals);
            $cnt  = [4 => 0, 3 => 0, 2 => 0, 1 => 0];
            foreach ($vals as $v) { $vi = (int) $v; if (isset($cnt[$vi])) $cnt[$vi]++; }
            $pct = fn(int $c) => $n ? round($c / $n * 100, 1) : 0;
            $out[] = [
                'aspek'       => $label,
                'sangat_baik' => $pct($cnt[4]),
                'baik'        => $pct($cnt[3]),
                'cukup'       => $pct($cnt[2]),
                'kurang'      => $pct($cnt[1]),
                'responden'   => $n,
            ];
        }
        return $out;
    }

    private function tabelPenelitian(int $prodiId): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT p.judul, d.nama AS ketua, p.sumber_dana, p.nama_sumber_dana,
                   p.jumlah_dana, p.tahun_pelaksanaan, p.libatkan_mahasiswa, p.jumlah_mhs_terlibat, p.link_bukti
            FROM penelitian p
            LEFT JOIN dosen d ON d.id = p.ketua_dosen_id
            WHERE p.prodi_id = ?
            ORDER BY p.tahun_pelaksanaan DESC
        ", [$prodiId])->getResultArray();
    }

    private function tabelPkm(int $prodiId): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT p.judul, d.nama AS ketua, p.sumber_dana, p.nama_sumber_dana,
                   p.jumlah_dana, p.mitra, p.lokasi, p.tahun_pelaksanaan,
                   p.libatkan_mahasiswa, p.jumlah_mhs_terlibat, p.link_bukti
            FROM pkm p
            LEFT JOIN dosen d ON d.id = p.ketua_dosen_id
            WHERE p.prodi_id = ?
            ORDER BY p.tahun_pelaksanaan DESC
        ", [$prodiId])->getResultArray();
    }

    private function tabelPublikasi(int $prodiId, string $kategori): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT jenis, judul, penulis, nama_media, tahun, volume, doi, sitasi, sinta_score, url
            FROM publikasi
            WHERE prodi_id = ? AND kategori = ?
            ORDER BY tahun DESC
        ", [$prodiId, $kategori])->getResultArray();
    }

    private function tabelHki(int $prodiId, string $kategori): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT jenis, judul, no_hki, tahun, pemilik, status, link_bukti
            FROM hki
            WHERE prodi_id = ? AND kategori = ?
            ORDER BY tahun DESC
        ", [$prodiId, $kategori])->getResultArray();
    }

    private function tabelKerjasama(int $prodiId, string $bidang): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT nama_mitra, tingkat, jenis_dokumen, no_dokumen,
                   tgl_mulai, tgl_berakhir, manfaat, bukti_implementasi
            FROM kerjasama
            WHERE prodi_id = ? AND bidang IN (?, 'tridharma')
            ORDER BY tgl_mulai DESC
        ", [$prodiId, $bidang])->getResultArray();
    }

    private function clamp(float $v): float
    {
        return max(1.0, min(4.0, round($v, 2)));
    }
}
