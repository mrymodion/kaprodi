<?php

namespace App\Services;

use Config\Database;

/**
 * Layanan tarik-otomatis bukti eviden PPEPP dari berbagai sumber
 * data yang sudah diinput di sistem (Surat Tugas, Publikasi, Penelitian,
 * PkM, HKI, Rekognisi, Pengembangan Diri DTPR, Kerjasama).
 *
 * Setiap sumber mengembalikan item:
 *   ['label' => ..., 'url' => ..., 'fase' => ..., 'sumber' => ...]
 * Item dengan URL kosong di-skip (tidak ada bukti yang bisa ditampilkan).
 */
class EvidenAutoTarik
{
    /** Definisi sumber yang tersedia: label, ikon, fase default, dan
     *  kata kunci pemicu (matcher) ke butir yang relevan. */
    public function sources(): array
    {
        return [
            'surat_tugas'  => ['label' => 'Surat Tugas Mengajar', 'icon' => 'bi-file-earmark-pdf', 'fase' => 'pelaksanaan',
                'match' => ['surat tugas','pengampu','beban dosen','beban kerja','ewmp','dosen pengampu','3.a.1','3.a.4','3.b.1']],
            'publikasi'    => ['label' => 'Publikasi Dosen', 'icon' => 'bi-journal-text', 'fase' => 'pelaksanaan',
                'match' => ['publikasi','jurnal','prosiding','luaran','karya tulis','sitasi','3.c.2','4.c.2']],
            'penelitian'   => ['label' => 'Penelitian Dosen', 'icon' => 'bi-search', 'fase' => 'pelaksanaan',
                'match' => ['penelitian','riset','road map','dana penelitian','3.b.2','4.b.2']],
            'pkm'          => ['label' => 'PkM Dosen', 'icon' => 'bi-megaphone', 'fase' => 'pelaksanaan',
                'match' => ['pkm','pengabdian','dana pkm','3.b.3','4.b.3']],
            'hki'          => ['label' => 'HKI', 'icon' => 'bi-shield-check', 'fase' => 'pelaksanaan',
                'match' => ['hki','paten','hak cipta','kekayaan intelektual','3.c.3','4.c.3']],
            'rekognisi'    => ['label' => 'Rekognisi Dosen', 'icon' => 'bi-award', 'fase' => 'pelaksanaan',
                'match' => ['rekognisi','prestasi dosen','keynote','reviewer','visiting','3.a.4']],
            'pengembangan' => ['label' => 'Pengembangan Diri', 'icon' => 'bi-mortarboard', 'fase' => 'peningkatan',
                'match' => ['pengembangan diri','pengembangan dosen','pelatihan','workshop','sertifikasi','peningkatan kompetensi','3.a.3']],
            'kerjasama'    => ['label' => 'Kerjasama', 'icon' => 'bi-people-fill', 'fase' => 'pelaksanaan',
                'match' => ['kerjasama','mou','moa','mitra','3.c.1','4.c.1']],
            'kondisi_eksternal' => ['label' => 'Kondisi Eksternal', 'icon' => 'bi-globe2', 'fase' => 'penetapan',
                'match' => ['kondisi eksternal','vmts','visi','misi','strategi','swot','pestel','peluang','ancaman','analisis lingkungan']],
        ];
    }

    /** Tentukan sumber yang RELEVAN untuk sebuah butir berdasarkan
     *  butir_kode + kriteria_kode + judul + deskripsi + tabel_lkps_ref.
     *  Mengembalikan daftar key sumber yang cocok. Empty array = tidak ada
     *  pencocokan kuat → UI akan menampilkan semua sumber sebagai fallback. */
    public function relevantFor(array $butir): array
    {
        $hay = strtolower(implode(' ', array_filter([
            $butir['butir_kode']     ?? '',
            $butir['kriteria_kode']  ?? '',
            $butir['judul']          ?? '',
            $butir['deskripsi']      ?? '',
            $butir['tabel_lkps_ref'] ?? '',
        ])));
        if ($hay === '') return [];

        $matched = [];
        foreach ($this->sources() as $key => $info) {
            foreach ($info['match'] ?? [] as $needle) {
                if (str_contains($hay, strtolower($needle))) { $matched[] = $key; break; }
            }
        }
        return array_values(array_unique($matched));
    }

    public function collect(string $source, int $prodiId): array
    {
        return match ($source) {
            'surat_tugas'  => $this->fromSuratTugas($prodiId),
            'publikasi'    => $this->fromPublikasi($prodiId),
            'penelitian'   => $this->fromPenelitian($prodiId),
            'pkm'          => $this->fromPkm($prodiId),
            'hki'          => $this->fromHki($prodiId),
            'rekognisi'    => $this->fromRekognisi($prodiId),
            'pengembangan' => $this->fromPengembangan($prodiId),
            'kerjasama'    => $this->fromKerjasama($prodiId),
            'kondisi_eksternal' => $this->fromKondisiEksternal($prodiId),
            default        => [],
        };
    }

    // ───── Sumber: Surat Tugas (URL validasi publik) ─────
    private function fromSuratTugas(int $prodiId): array
    {
        $db = Database::connect();
        $rows = $db->table('surat_tugas_meta stm')
            ->select('stm.dosen_id, stm.tahun_akademik_id, stm.nomor, d.nama, ta.kode AS ta_kode')
            ->join('dosen d', 'd.id = stm.dosen_id')
            ->join('tahun_akademik ta', 'ta.id = stm.tahun_akademik_id')
            ->where('d.prodi_id', $prodiId)
            ->where("(stm.nomor IS NOT NULL AND stm.nomor <> '')", null, false)
            ->orderBy('ta.tahun_mulai DESC, d.nama ASC')->get()->getResultArray();
        $sv = new SuratValidator();
        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'label'  => 'Surat Tugas ' . $r['nama'] . ' TA ' . $r['ta_kode'] . ' (No. ' . $r['nomor'] . ')',
                'url'    => $sv->url((int) $r['dosen_id'], (int) $r['tahun_akademik_id']),
                'fase'   => 'pelaksanaan',
                'sumber' => 'surat_tugas',
            ];
        }
        return $out;
    }

    // ───── Sumber: Publikasi (url paper / DOI) ─────
    private function fromPublikasi(int $prodiId): array
    {
        $rows = Database::connect()->table('publikasi')
            ->select('judul, tahun, url')
            ->where('prodi_id', $prodiId)
            ->where("(url IS NOT NULL AND url <> '')", null, false)
            ->orderBy('tahun DESC, id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'Publikasi: ' . $this->potong((string) $r['judul'], 120) . ' (' . ($r['tahun'] ?? '-') . ')',
            'url'    => (string) $r['url'],
            'fase'   => 'pelaksanaan',
            'sumber' => 'publikasi',
        ], $rows);
    }

    // ───── Sumber: Penelitian ─────
    private function fromPenelitian(int $prodiId): array
    {
        $rows = Database::connect()->table('penelitian')
            ->select('judul, tahun_pelaksanaan, link_bukti')
            ->where('prodi_id', $prodiId)
            ->where("(link_bukti IS NOT NULL AND link_bukti <> '')", null, false)
            ->orderBy('tahun_pelaksanaan DESC, id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'Penelitian: ' . $this->potong((string) $r['judul'], 120) . ' (' . ($r['tahun_pelaksanaan'] ?? '-') . ')',
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'pelaksanaan',
            'sumber' => 'penelitian',
        ], $rows);
    }

    // ───── Sumber: PkM ─────
    private function fromPkm(int $prodiId): array
    {
        $rows = Database::connect()->table('pkm')
            ->select('judul, tahun_pelaksanaan, link_bukti')
            ->where('prodi_id', $prodiId)
            ->where("(link_bukti IS NOT NULL AND link_bukti <> '')", null, false)
            ->orderBy('tahun_pelaksanaan DESC, id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'PkM: ' . $this->potong((string) $r['judul'], 120) . ' (' . ($r['tahun_pelaksanaan'] ?? '-') . ')',
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'pelaksanaan',
            'sumber' => 'pkm',
        ], $rows);
    }

    // ───── Sumber: HKI ─────
    private function fromHki(int $prodiId): array
    {
        $rows = Database::connect()->table('hki')
            ->select('judul, jenis, tahun, no_hki, link_bukti')
            ->where('prodi_id', $prodiId)
            ->where("(link_bukti IS NOT NULL AND link_bukti <> '')", null, false)
            ->orderBy('tahun DESC, id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'HKI (' . str_replace('_', ' ', (string) $r['jenis']) . '): ' . $this->potong((string) $r['judul'], 100) . ($r['no_hki'] ? ' [' . $r['no_hki'] . ']' : ''),
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'pelaksanaan',
            'sumber' => 'hki',
        ], $rows);
    }

    // ───── Sumber: Rekognisi Dosen ─────
    private function fromRekognisi(int $prodiId): array
    {
        $db = Database::connect();
        if (!$db->tableExists('rekognisi_dosen') || !$db->fieldExists('link_bukti', 'rekognisi_dosen')) return [];

        $rows = $db->table('rekognisi_dosen rd')
            ->select('rd.judul, rd.tahun, rd.link_bukti, d.nama AS dosen_nama')
            ->join('dosen d', 'd.id = rd.dosen_id', 'left')
            ->where('d.prodi_id', $prodiId)
            ->where("(rd.link_bukti IS NOT NULL AND rd.link_bukti <> '')", null, false)
            ->orderBy('rd.tahun DESC, rd.id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'Rekognisi ' . ($r['dosen_nama'] ?? '') . ': ' . $this->potong((string) $r['judul'], 100) . ' (' . ($r['tahun'] ?? '-') . ')',
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'pelaksanaan',
            'sumber' => 'rekognisi',
        ], $rows);
    }

    // ───── Sumber: Pengembangan Diri DTPR ─────
    private function fromPengembangan(int $prodiId): array
    {
        $rows = Database::connect()->table('pengembangan_dtpr')
            ->select('nama_dtpr, jenis_pengembangan, link_bukti')
            ->where('prodi_id', $prodiId)
            ->where("(link_bukti IS NOT NULL AND link_bukti <> '')", null, false)
            ->orderBy('id DESC')->get()->getResultArray();
        return array_map(fn($r) => [
            'label'  => 'Pengembangan ' . ($r['nama_dtpr'] ?: '-') . ': ' . $this->potong((string) $r['jenis_pengembangan'], 100),
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'peningkatan',
            'sumber' => 'pengembangan',
        ], $rows);
    }

    // ───── Sumber: Kerjasama (bukti_implementasi sebagai URL) ─────
    private function fromKerjasama(int $prodiId): array
    {
        $rows = Database::connect()->table('kerjasama')
            ->select('nama_mitra, bidang, tingkat, jenis_dokumen, no_dokumen, tgl_mulai, bukti_implementasi')
            ->where('prodi_id', $prodiId)
            ->where("(bukti_implementasi IS NOT NULL AND bukti_implementasi <> '')", null, false)
            ->orderBy('tgl_mulai DESC, id DESC')->get()->getResultArray();
        $out = [];
        foreach ($rows as $r) {
            // bukti_implementasi adalah TEXT bebas — hanya ambil yang URL valid.
            $url = trim((string) $r['bukti_implementasi']);
            if (!preg_match('~^https?://~i', $url)) continue;
            $out[] = [
                'label'  => 'Kerjasama (' . $r['tingkat'] . ', ' . strtoupper((string) $r['jenis_dokumen']) . ') ' . $r['nama_mitra'] . ($r['no_dokumen'] ? ' — ' . $r['no_dokumen'] : ''),
                'url'    => $url,
                'fase'   => 'pelaksanaan',
                'sumber' => 'kerjasama',
            ];
        }
        return $out;
    }

    // ───── Sumber: Kondisi Eksternal (Sprint 2 Kriteria A) ─────
    private function fromKondisiEksternal(int $prodiId): array
    {
        $db = Database::connect();
        if (!$db->tableExists('kondisi_eksternal')) return [];
        $rows = $db->table('kondisi_eksternal')
            ->select('kategori, judul, dampak, sumber_referensi, link_bukti, tahun_relevansi')
            ->where('prodi_id', $prodiId)
            ->where("(link_bukti IS NOT NULL AND link_bukti <> '')", null, false)
            ->orderBy('kategori, urutan, id')->get()->getResultArray();
        $K = \App\Models\KondisiEksternalModel::KATEGORI;
        return array_map(fn($r) => [
            'label'  => 'Kondisi Eksternal — ' . ($K[$r['kategori']]['label'] ?? $r['kategori']) . ': ' . $this->potong((string) $r['judul'], 100) . ($r['tahun_relevansi'] ? ' (' . $r['tahun_relevansi'] . ')' : ''),
            'url'    => (string) $r['link_bukti'],
            'fase'   => 'penetapan',
            'sumber' => 'kondisi_eksternal',
        ], $rows);
    }

    private function potong(string $s, int $n): string
    {
        return mb_strlen($s) > $n ? mb_substr($s, 0, $n - 1) . '…' : $s;
    }
}
