<?php

namespace App\Models;

use App\Models\AppModel;

class PublikasiModel extends AppModel
{
    protected $table      = 'publikasi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'dosen_id', 'kategori', 'jenis', 'judul', 'penulis',
        'nama_media', 'tahun', 'volume', 'doi', 'url', 'sitasi', 'sinta_score',
        'created_at',
    ];

    protected $validationRules = [
        'judul'    => 'required|min_length[5]',
        'prodi_id' => 'required|is_natural_no_zero',
        'kategori' => 'required|in_list[penelitian,pkm]',
        'jenis'    => 'required',
    ];

    const JENIS_LABELS = [
        'jurnal_nasional_tidak_terakreditasi' => 'Jurnal Nasional Tidak Terakreditasi',
        'jurnal_nasional_terakreditasi'       => 'Jurnal Nasional Terakreditasi (SINTA)',
        'jurnal_internasional'                => 'Jurnal Internasional',
        'jurnal_internasional_bereputasi'     => 'Jurnal Internasional Bereputasi (Scopus/WoS)',
        'seminar_lokal'                       => 'Seminar/Prosiding Lokal',
        'seminar_nasional'                    => 'Seminar/Prosiding Nasional',
        'seminar_internasional'               => 'Seminar/Prosiding Internasional',
        'media_massa_nasional'                => 'Media Massa Nasional',
        'media_massa_internasional'           => 'Media Massa Internasional',
        'buku'                                => 'Buku',
        'book_chapter'                        => 'Book Chapter',
    ];

    public function listByDosen(int $dosenId): array
    {
        return $this->where('dosen_id', $dosenId)->orderBy('tahun DESC, judul ASC')->findAll();
    }

    /** Publikasi prodi yang belum dimiliki dosen mana pun (untuk diklaim). */
    public function listUnclaimedByProdi(int $prodiId): array
    {
        return $this->where('prodi_id', $prodiId)
            ->groupStart()->where('dosen_id', null)->orWhere('dosen_id', 0)->groupEnd()
            ->orderBy('tahun DESC, judul ASC')->findAll();
    }

    public function listByProdi(int $prodiId, string $kategori = ''): array
    {
        $q = $this->where('prodi_id', $prodiId)->orderBy('tahun DESC, judul ASC');
        if ($kategori) $q->where('kategori', $kategori);
        return $q->findAll();
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT kategori, jenis, COUNT(*) AS jml, SUM(sitasi) AS total_sitasi
            FROM publikasi WHERE prodi_id = ?
            GROUP BY kategori, jenis
        ", [$prodiId])->getResultArray();

        $summary = ['penelitian' => [], 'pkm' => [], 'total' => 0];
        foreach ($rows as $r) {
            $summary[$r['kategori']][$r['jenis']] = ['jml' => (int)$r['jml'], 'sitasi' => (int)$r['total_sitasi']];
            $summary['total'] += (int)$r['jml'];
        }
        return $summary;
    }
}
