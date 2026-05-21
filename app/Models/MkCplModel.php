<?php

namespace App\Models;

use CodeIgniter\Model;

class MkCplModel extends Model
{
    protected $table      = 'mk_cpl';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['mata_kuliah_id','cpl_id','bobot'];

    public function petaByKurikulum(int $kurikulumId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT mc.mata_kuliah_id, mc.cpl_id, mc.bobot
            FROM mk_cpl mc
            JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id
            WHERE mk.kurikulum_id = ?
        ", [$kurikulumId])->getResultArray();
    }

    public function savePeta(int $kurikulumId, array $checked): void
    {
        $db = \Config\Database::connect();

        // Hapus semua peta lama untuk kurikulum ini
        $db->query("
            DELETE mc FROM mk_cpl mc
            JOIN mata_kuliah mk ON mk.id = mc.mata_kuliah_id
            WHERE mk.kurikulum_id = ?
        ", [$kurikulumId]);

        // Insert baru
        foreach ($checked as $mkId => $cplIds) {
            foreach ($cplIds as $cplId) {
                $this->insert([
                    'mata_kuliah_id' => (int)$mkId,
                    'cpl_id'         => (int)$cplId,
                    'bobot'          => 1.00,
                ]);
            }
        }
    }

    public function cplByMk(int $mkId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT c.* FROM cpl c
            JOIN mk_cpl mc ON mc.cpl_id = c.id
            WHERE mc.mata_kuliah_id = ?
            ORDER BY c.kode
        ", [$mkId])->getResultArray();
    }
}
