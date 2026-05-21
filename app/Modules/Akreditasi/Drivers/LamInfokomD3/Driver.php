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

        // Contoh implementasi awal: butir terkait kepuasan pengguna lulusan
        // (skor rata-rata 7 aspek dibagi 1, sudah skala 1-4).
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
