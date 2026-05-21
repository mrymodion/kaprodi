<?php

namespace App\Models;

use App\Models\AppModel;

class HkiModel extends AppModel
{
    protected $table      = 'hki';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'kategori', 'jenis', 'judul', 'no_hki',
        'tahun', 'pemilik', 'status', 'created_at',
    ];

    protected $validationRules = [
        'judul'    => 'required|min_length[5]',
        'prodi_id' => 'required|is_natural_no_zero',
    ];

    const JENIS_LABELS = [
        'paten'           => 'Paten',
        'paten_sederhana' => 'Paten Sederhana',
        'hak_cipta'       => 'Hak Cipta',
        'desain_industri' => 'Desain Industri',
        'dtlst'           => 'DTLST',
        'indikasi_geografis' => 'Indikasi Geografis',
        'prl_varietas'    => 'PRL/Varietas Tanaman',
    ];

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
            SELECT kategori, jenis, COUNT(*) AS jml
            FROM hki WHERE prodi_id = ?
            GROUP BY kategori, jenis
        ", [$prodiId])->getResultArray();

        $summary = ['penelitian' => [], 'pkm' => [], 'total' => 0];
        foreach ($rows as $r) {
            $summary[$r['kategori']][$r['jenis']] = (int)$r['jml'];
            $summary['total'] += (int)$r['jml'];
        }
        return $summary;
    }
}
