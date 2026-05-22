<?php

namespace App\Models;

use App\Models\AppModel;

class SarprasModel extends AppModel
{
    protected $table      = 'sarpras';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'kategori', 'nama', 'jumlah', 'luas_m2', 'kapasitas',
        'kondisi', 'kepemilikan', 'untuk_penelitian', 'untuk_pkm', 'untuk_pendidikan', 'keterangan',
    ];

    protected $validationRules = [
        'prodi_id' => 'required|is_natural_no_zero',
        'nama'     => 'required|max_length[200]',
        'kategori' => 'required',
    ];

    public const KATEGORI = [
        'ruang_kelas'      => 'Ruang Kelas',
        'laboratorium'     => 'Laboratorium',
        'perpustakaan'     => 'Perpustakaan',
        'peralatan'        => 'Peralatan',
        'sistem_informasi' => 'Sistem Informasi',
        'lainnya'          => 'Lainnya',
    ];

    public function listByProdi(int $prodiId): array
    {
        return $this->where('prodi_id', $prodiId)->orderBy('kategori, nama')->findAll();
    }
}
