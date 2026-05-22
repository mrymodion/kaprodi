<?php

namespace App\Models;

use App\Models\AppModel;

class ProdiModel extends AppModel
{
    protected $table      = 'prodi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'upps_id','kode_pddikti','nama','jenjang','gelar','singkatan_gelar',
        'akreditasi_saat_ini','no_sk_akreditasi','tgl_sk_akreditasi','tgl_kadaluarsa','status',
    ];

    protected $validationRules = [
        'nama'         => 'required|min_length[3]|max_length[200]',
        'kode_pddikti' => 'required|max_length[20]',
        'jenjang'      => 'required|in_list[D1,D2,D3,D4,S1,S2,S3,Profesi,Sp1,Sp2]',
        'upps_id'      => 'required|is_natural_no_zero',
    ];

    public function findAll(?int $limit = null, int $offset = 0): array
    {
        $this->orderBy('nama ASC');
        return parent::findAll($limit, $offset);
    }

    /** Daftar prodi + nama UPPS & PT untuk listing. */
    public function listWithUpps(): array
    {
        return $this->db->table('prodi p')
            ->select('p.*, u.nama AS upps_nama, pt.nama AS pt_nama')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->orderBy('p.nama', 'ASC')
            ->get()->getResultArray();
    }
}
