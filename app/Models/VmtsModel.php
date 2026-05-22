<?php

namespace App\Models;

use App\Models\AppModel;

class VmtsModel extends AppModel
{
    protected $table      = 'vmts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'visi', 'misi', 'tujuan', 'strategi',
        'no_sk', 'tgl_sk', 'berlaku_mulai', 'berlaku_sampai', 'aktif',
    ];

    protected $validationRules = [
        'prodi_id' => 'required|is_natural_no_zero',
        'visi'     => 'required',
        'misi'     => 'required',
        'tujuan'   => 'required',
    ];

    public function aktifByProdi(int $prodiId): ?array
    {
        return $this->where(['prodi_id' => $prodiId, 'aktif' => 1])->orderBy('id', 'DESC')->first();
    }
}
