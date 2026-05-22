<?php

namespace App\Models;

use App\Models\AppModel;

class KeuanganModel extends AppModel
{
    protected $table      = 'keuangan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'tahun_akademik_id', 'arah', 'sumber_atau_pos', 'kategori', 'jumlah', 'keterangan',
    ];

    protected $validationRules = [
        'prodi_id'        => 'required|is_natural_no_zero',
        'arah'            => 'required|in_list[pemasukan,pengeluaran]',
        'sumber_atau_pos' => 'required|max_length[200]',
    ];

    public function listByProdi(int $prodiId, string $arah = ''): array
    {
        $q = $this->select('keuangan.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = keuangan.tahun_akademik_id', 'left')
            ->where('keuangan.prodi_id', $prodiId)
            ->orderBy('ta.tahun_mulai DESC, keuangan.arah, keuangan.sumber_atau_pos');
        if ($arah) $q->where('keuangan.arah', $arah);
        return $q->findAll();
    }
}
