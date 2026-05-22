<?php

namespace App\Models;

use App\Models\AppModel;

class TendikModel extends AppModel
{
    protected $table      = 'tendik';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'nip', 'nama', 'jenis', 'pendidikan_terakhir', 'sertifikat_kompetensi', 'aktif',
    ];

    protected $validationRules = [
        'prodi_id' => 'required|is_natural_no_zero',
        'nama'     => 'required|max_length[150]',
        'jenis'    => 'required',
    ];

    public const JENIS = [
        'pustakawan'    => 'Pustakawan',
        'laboran'       => 'Laboran',
        'teknisi'       => 'Teknisi',
        'operator'      => 'Operator',
        'administrasi'  => 'Administrasi',
        'lainnya'       => 'Lainnya',
    ];

    public function listByProdi(int $prodiId): array
    {
        return $this->where('prodi_id', $prodiId)->orderBy('jenis, nama')->findAll();
    }
}
