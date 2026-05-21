<?php

namespace App\Models;

use CodeIgniter\Model;

class MataKuliahModel extends Model
{
    protected $table      = 'mata_kuliah';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'kurikulum_id','kode','nama','sks_teori','sks_praktek','sks_lapangan','semester','jenis','rps_path',
    ];
    protected $validationRules = [
        'kode'         => 'required|max_length[20]',
        'nama'         => 'required|max_length[200]',
        'semester'     => 'required|is_natural_no_zero',
        'kurikulum_id' => 'required|is_natural_no_zero',
    ];

    public function byKurikulum(int $kurikulumId): array
    {
        return $this->where('kurikulum_id', $kurikulumId)
            ->orderBy('semester, kode')
            ->findAll();
    }

    public function withCplCount(int $kurikulumId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT mk.*,
                (mk.sks_teori + mk.sks_praktek + mk.sks_lapangan) AS total_sks,
                GROUP_CONCAT(c.kode ORDER BY c.kode SEPARATOR ', ') AS cpl_terkait,
                COUNT(mc.cpl_id) AS jml_cpl
            FROM mata_kuliah mk
            LEFT JOIN mk_cpl mc ON mc.mata_kuliah_id = mk.id
            LEFT JOIN cpl c ON c.id = mc.cpl_id
            WHERE mk.kurikulum_id = ?
            GROUP BY mk.id
            ORDER BY mk.semester, mk.kode
        ", [$kurikulumId])->getResultArray();
    }

    public function totalSksKurikulum(int $kurikulumId): array
    {
        $db = \Config\Database::connect();
        $row = $db->query("
            SELECT
                SUM(sks_teori + sks_praktek + sks_lapangan) AS total,
                SUM(CASE WHEN jenis='wajib' THEN sks_teori+sks_praktek+sks_lapangan ELSE 0 END) AS wajib,
                SUM(CASE WHEN jenis='pilihan' THEN sks_teori+sks_praktek+sks_lapangan ELSE 0 END) AS pilihan,
                SUM(CASE WHEN jenis='mbkm' THEN sks_teori+sks_praktek+sks_lapangan ELSE 0 END) AS mbkm,
                COUNT(*) AS jml_mk
            FROM mata_kuliah WHERE kurikulum_id = ?
        ", [$kurikulumId])->getRowArray();
        return $row ?? [];
    }
}
