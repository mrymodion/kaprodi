<?php

namespace App\Models;

use App\Models\AppModel;

class KerjasamaModel extends AppModel
{
    protected $table      = 'kerjasama';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'prodi_id', 'nama_mitra', 'bidang', 'tingkat', 'jenis_dokumen',
        'no_dokumen', 'tgl_mulai', 'tgl_berakhir', 'manfaat', 'bukti_implementasi',
    ];

    protected $validationRules = [
        'nama_mitra' => 'required|min_length[3]|max_length[250]',
        'prodi_id'   => 'required|is_natural_no_zero',
        'tgl_mulai'  => 'required|valid_date',
    ];

    public function listByProdi(int $prodiId, string $bidang = ''): array
    {
        $q = $this->where('prodi_id', $prodiId)->orderBy('tgl_mulai DESC');
        if ($bidang) $q->where('bidang', $bidang);
        return $q->findAll();
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT bidang, tingkat, COUNT(*) AS jml
            FROM kerjasama WHERE prodi_id = ?
            GROUP BY bidang, tingkat
        ", [$prodiId])->getResultArray();

        $summary = ['total' => 0, 'per_tingkat' => []];
        foreach ($rows as $r) {
            $summary['per_tingkat'][$r['tingkat']] = ($summary['per_tingkat'][$r['tingkat']] ?? 0) + (int)$r['jml'];
            $summary['total'] += (int)$r['jml'];
        }
        return $summary;
    }

    public function aktifByProdi(int $prodiId): array
    {
        return $this->where('prodi_id', $prodiId)
            ->where('tgl_mulai <=', date('Y-m-d'))
            ->groupStart()
                ->where('tgl_berakhir >=', date('Y-m-d'))
                ->orWhere('tgl_berakhir IS NULL')
            ->groupEnd()
            ->findAll();
    }
}
