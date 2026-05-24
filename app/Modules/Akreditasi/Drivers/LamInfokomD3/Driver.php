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

    /** Penjelasan dasar perhitungan auto-skor terakhir: [kode_butir => teks]. */
    protected array $explain = [];

    public function explanations(): array
    {
        return $this->explain;
    }

    public function computeScores(int $prodiId, ?int $tahunAkademikId = null): array
    {
        $db     = Database::connect();
        $scores = [];
        $this->explain = [];

        $jumlahDosen = (int) $db->table('dosen')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults();

        // ── 2.2.A — Pelaksanaan DTPR & penerimaan mahasiswa baru ──────────────
        if ($jumlahDosen > 0) {
            $sub = [];
            $s3 = (int) $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1, 'pendidikan_terakhir' => 'S3'])->countAllResults();
            $sub[] = $this->band($s3 / $jumlahDosen * 100, [30, 20, 10]);
            $lkGb = (int) $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1])
                ->whereIn('jabatan_akademik', ['Lektor Kepala', 'Guru Besar'])->countAllResults();
            $sub[] = $this->band($lkGb / $jumlahDosen * 100, [30, 20, 10]);
            $serdos = (int) $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1, 'sertifikat_pendidik' => 1])->countAllResults();
            $sub[] = $this->band($serdos / $jumlahDosen * 100, [80, 60, 40]);
            $linier = (int) $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1, 'linieritas' => 'linier'])->countAllResults();
            $sub[] = $this->band($linier / $jumlahDosen * 100, [80, 60, 40]);
            $mhsAktif = (int) $db->table('mahasiswa')->where(['prodi_id' => $prodiId, 'status' => 'aktif'])->countAllResults();
            $rasio = $mhsAktif / $jumlahDosen;
            $sub[] = $rasio <= 20 ? 4 : ($rasio <= 25 ? 3 : ($rasio <= 30 ? 2 : 1));
            $scores['2.2.A'] = $this->avg($sub);
            $this->explain['2.2.A'] = sprintf('DTPR: S3=%d, LK+GB=%d, serdos=%d, linier=%d dari %d dosen; rasio 1:%.1f. (rata-rata sub-skor 1-4)', $s3, $lkGb, $serdos, $linier, $jumlahDosen, $rasio);
        }

        // ── 2.2.B — Pelaksanaan isi pembelajaran & kurikulum OBE ──────────────
        $kuri = $db->table('kurikulum')->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getRowArray();
        if ($kuri) {
            $sub = []; $kuriId = $kuri['id'];
            $jmlCpl = (int) $db->table('cpl')->where('kurikulum_id', $kuriId)->countAllResults();
            $jmlMk  = (int) $db->table('mata_kuliah')->where('kurikulum_id', $kuriId)->countAllResults();
            if ($jmlCpl > 0) {
                $sub[] = $this->band($jmlCpl, [10, 7, 4]);
                if ($jmlMk > 0) {
                    $cov = $db->query("SELECT COUNT(DISTINCT mc.cpl_id) n FROM mk_cpl mc JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id WHERE mk.kurikulum_id = ?", [$kuriId])->getRow()->n;
                    $sub[] = $this->band($cov / $jmlCpl * 100, [100, 80, 60]);
                }
            }
            if ($tahunAkademikId) {
                $ket = $db->query("SELECT AVG(ck.persen_tercapai) p FROM cpl_ketercapaian ck JOIN mata_kuliah mk ON mk.id = ck.mata_kuliah_id WHERE mk.kurikulum_id = ? AND ck.tahun_akademik_id = ?", [$kuriId, $tahunAkademikId])->getRow();
                if ($ket && $ket->p !== null) $sub[] = $this->band((float) $ket->p, [75, 60, 50]);
            }
            if ($sub) { $scores['2.2.B'] = $this->avg($sub); $this->explain['2.2.B'] = sprintf('Kurikulum OBE: %d CPL, %d mata kuliah, coverage & ketercapaian CPL. (rata-rata sub-skor 1-4)', $jmlCpl, $jmlMk); }
        }

        // ── 2.2.D — Pelaksanaan kompetensi lulusan (tracer + kepuasan) ────────
        $sub = [];
        $tracer = $db->query("SELECT AVG(ts.masa_tunggu_bulan) avg_tunggu, SUM(CASE WHEN ts.kesesuaian_bidang_kerja IN ('sesuai','sangat_sesuai') THEN 1 ELSE 0 END) sesuai, COUNT(*) total FROM tracer_study ts JOIN mahasiswa m ON m.id = ts.mahasiswa_id WHERE m.prodi_id = ?", [$prodiId])->getRowArray();
        if ($tracer && (int) $tracer['total'] > 0) {
            if ($tracer['avg_tunggu'] !== null) { $t = (float) $tracer['avg_tunggu']; $sub[] = $t < 3 ? 4 : ($t < 6 ? 3 : ($t < 12 ? 2 : 1)); }
            $sub[] = $this->band((int) $tracer['sesuai'] / (int) $tracer['total'] * 100, [80, 60, 40]);
        }
        $kpl = $db->query("SELECT AVG((etika + keahlian + bahasa_asing + teknologi_info + komunikasi + kerjasama_tim + pengembangan_diri) / 7) r FROM kepuasan_pengguna_lulusan k JOIN mahasiswa m ON m.id = k.mahasiswa_id WHERE m.prodi_id = ?", [$prodiId])->getRow();
        if ($kpl && $kpl->r !== null) $sub[] = max(1, min(4, (int) round((float) $kpl->r)));
        if ($sub) { $scores['2.2.D'] = $this->avg($sub); $this->explain['2.2.D'] = 'Kompetensi lulusan: masa tunggu kerja, kesesuaian bidang, kepuasan pengguna. (rata-rata sub-skor 1-4)'; }

        // ── 3.2.C — Pelaksanaan luaran penelitian ─────────────────────────────
        $pen = $db->query("SELECT COUNT(*) total, SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) eksternal FROM penelitian WHERE prodi_id = ?", [$prodiId])->getRowArray();
        if ($pen && (int) $pen['total'] > 0 && $jumlahDosen > 0) {
            $sub = []; $total = (int) $pen['total'];
            $sub[] = $this->band($total / $jumlahDosen, [1, 0.5, 0.25]);
            $sub[] = $this->band((int) $pen['eksternal'] / $total * 100, [50, 30, 15]);
            $pub = (int) $db->table('publikasi')->where(['prodi_id' => $prodiId, 'kategori' => 'penelitian'])->countAllResults();
            $sub[] = $this->band($pub / $jumlahDosen, [2, 1, 0.5]);
            $hki = (int) $db->table('hki')->where(['prodi_id' => $prodiId, 'kategori' => 'penelitian'])->countAllResults();
            $sub[] = $this->band($hki / $jumlahDosen, [1, 0.5, 0.2]);
            $scores['3.2.C'] = $this->avg($sub);
            $this->explain['3.2.C'] = sprintf('Luaran penelitian: %d penelitian, %d publikasi, %d HKI untuk %d dosen. (rata-rata sub-skor 1-4)', $total, $pub, $hki, $jumlahDosen);
        }

        // ── 4.2.C — Pelaksanaan luaran PkM ────────────────────────────────────
        $pkm = $db->query("SELECT COUNT(*) total, SUM(CASE WHEN sumber_dana IN ('dalam_negeri','luar_negeri') THEN 1 ELSE 0 END) eksternal FROM pkm WHERE prodi_id = ?", [$prodiId])->getRowArray();
        if ($pkm && (int) $pkm['total'] > 0 && $jumlahDosen > 0) {
            $sub = []; $total = (int) $pkm['total'];
            $sub[] = $this->band($total / $jumlahDosen, [1, 0.5, 0.25]);
            $sub[] = $this->band((int) $pkm['eksternal'] / $total * 100, [50, 30, 15]);
            $ksIntl = (int) $db->table('kerjasama')->where(['prodi_id' => $prodiId, 'tingkat' => 'internasional'])->countAllResults();
            $sub[] = $ksIntl >= 5 ? 4 : ($ksIntl >= 3 ? 3 : ($ksIntl >= 1 ? 2 : 1));
            $scores['4.2.C'] = $this->avg($sub);
            $this->explain['4.2.C'] = sprintf('Luaran PkM: %d PkM, %d kerjasama internasional untuk %d dosen. (rata-rata sub-skor 1-4)', $total, $ksIntl, $jumlahDosen);
        }

        return $scores;
    }

    /** Skor 4/3/2/1 berdasarkan 3 ambang menurun [a4, a3, a2]. */
    protected function band(float $nilai, array $ambang): int
    {
        [$a4, $a3, $a2] = $ambang;
        return $nilai >= $a4 ? 4 : ($nilai >= $a3 ? 3 : ($nilai >= $a2 ? 2 : 1));
    }

    /** Rata-rata sub-skor, dibulatkan 2 desimal, dibatasi 1..4. */
    protected function avg(array $scores): float
    {
        $vals = array_filter($scores, fn($v) => $v !== null);
        if (!$vals) return 1.0;
        return max(1.0, min(4.0, round(array_sum($vals) / count($vals), 2)));
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
            SELECT p.jenis, p.judul, p.penulis,
                   GROUP_CONCAT(
                       CONCAT(COALESCE(d.gelar_depan,''), IF(d.gelar_depan IS NOT NULL AND d.gelar_depan <> '', ' ', ''), d.nama)
                       ORDER BY d.nama SEPARATOR '; '
                   ) AS dtpr_penulis,
                   p.nama_media, p.tahun, p.volume, p.doi, p.sitasi, p.sinta_score, p.url
            FROM publikasi p
            LEFT JOIN publikasi_dosen pd ON pd.publikasi_id = p.id
            LEFT JOIN dosen d ON d.id = pd.dosen_id AND d.prodi_id = p.prodi_id
            WHERE p.prodi_id = ? AND p.kategori = ?
            GROUP BY p.id
            ORDER BY p.tahun DESC
        ", [$prodiId, $kategori])->getResultArray();
    }

    private function tabelHki(int $prodiId, string $kategori): array
    {
        $db = Database::connect();
        return $db->query("
            SELECT h.jenis, h.judul, h.no_hki, h.tahun, h.pemilik,
                   TRIM(CONCAT(COALESCE(d.gelar_depan,''), ' ', COALESCE(d.nama,''))) AS dtpr_pemilik,
                   h.status, h.link_bukti
            FROM hki h
            LEFT JOIN dosen d ON d.id = h.dosen_id AND d.prodi_id = h.prodi_id
            WHERE h.prodi_id = ? AND h.kategori = ?
            ORDER BY h.tahun DESC
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
