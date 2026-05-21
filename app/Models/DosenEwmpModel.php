<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenEwmpModel extends Model
{
    protected $table      = 'dosen_ewmp';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'dosen_id', 'tahun_akademik_id',
        'sks_pendidikan_ps', 'sks_pendidikan_ps_lain_pt', 'sks_pendidikan_pt_lain',
        'sks_penelitian', 'sks_pkm', 'sks_tugas_tambahan', 'total_sks',
    ];

    public function getByDosenTa(int $dosenId, int $taId): ?array
    {
        return $this->where(['dosen_id' => $dosenId, 'tahun_akademik_id' => $taId])->first();
    }

    public function listByProdi(int $prodiId, int $taId): array
    {
        return $this->select('dosen_ewmp.*, d.nidn, d.nama, d.jabatan_akademik, d.pendidikan_terakhir')
            ->join('dosen d', 'd.id = dosen_ewmp.dosen_id')
            ->where(['d.prodi_id' => $prodiId, 'dosen_ewmp.tahun_akademik_id' => $taId, 'd.aktif' => 1])
            ->orderBy('d.nama')
            ->findAll();
    }

    public function upsert(int $dosenId, int $taId, array $data): void
    {
        $existing = $this->getByDosenTa($dosenId, $taId);
        $total = ($data['sks_pendidikan_ps'] ?? 0)
               + ($data['sks_pendidikan_ps_lain_pt'] ?? 0)
               + ($data['sks_pendidikan_pt_lain'] ?? 0)
               + ($data['sks_penelitian'] ?? 0)
               + ($data['sks_pkm'] ?? 0)
               + ($data['sks_tugas_tambahan'] ?? 0);

        $row = array_merge($data, [
            'dosen_id'          => $dosenId,
            'tahun_akademik_id' => $taId,
            'total_sks'         => $total,
        ]);

        if ($existing) {
            $this->update($existing['id'], $row);
        } else {
            $this->insert($row);
        }
    }
}
