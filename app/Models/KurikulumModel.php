<?php

namespace App\Models;

use App\Models\AppModel;

class KurikulumModel extends AppModel
{
    protected $table      = 'kurikulum';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['prodi_id','kode','nama','tahun_berlaku','aktif','created_at'];
    protected $validationRules = [
        'kode'     => 'required|max_length[30]',
        'nama'     => 'required|max_length[200]',
        'prodi_id' => 'required|is_natural_no_zero',
    ];

    public function aktifByProdi(int $prodiId): ?array
    {
        return $this->where(['prodi_id' => $prodiId, 'aktif' => 1])->orderBy('tahun_berlaku DESC')->first();
    }

    public function listByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT k.*,
                (SELECT COUNT(*) FROM cpl WHERE kurikulum_id = k.id) AS jml_cpl,
                (SELECT COUNT(*) FROM mata_kuliah WHERE kurikulum_id = k.id) AS jml_mk
            FROM kurikulum k
            WHERE k.prodi_id = ?
            ORDER BY k.tahun_berlaku DESC
        ", [$prodiId])->getResultArray();
    }
}
