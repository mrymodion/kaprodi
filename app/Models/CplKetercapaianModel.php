<?php

namespace App\Models;

use CodeIgniter\Model;

class CplKetercapaianModel extends Model
{
    protected $table      = 'cpl_ketercapaian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['mata_kuliah_id','cpl_id','tahun_akademik_id','persen_tercapai','jumlah_mhs'];

    public function byKurikulumTa(int $kurikulumId, int $taId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT ck.*, mk.kode AS mk_kode, mk.nama AS mk_nama, c.kode AS cpl_kode
            FROM cpl_ketercapaian ck
            JOIN mata_kuliah mk ON mk.id = ck.mata_kuliah_id
            JOIN cpl c ON c.id = ck.cpl_id
            WHERE mk.kurikulum_id = ? AND ck.tahun_akademik_id = ?
            ORDER BY mk.semester, mk.kode, c.kode
        ", [$kurikulumId, $taId])->getResultArray();
    }

    public function avgKetercapaianPerCpl(int $kurikulumId, int $taId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT c.id, c.kode, c.aspek, c.deskripsi,
                AVG(ck.persen_tercapai) AS avg_pct,
                COUNT(ck.id) AS data_pts
            FROM cpl c
            JOIN kurikulum k ON k.id = c.kurikulum_id
            LEFT JOIN mk_cpl mc ON mc.cpl_id = c.id
            LEFT JOIN cpl_ketercapaian ck ON ck.cpl_id = c.id AND ck.mata_kuliah_id = mc.mata_kuliah_id AND ck.tahun_akademik_id = ?
            WHERE k.id = ?
            GROUP BY c.id
            ORDER BY c.kode
        ", [$taId, $kurikulumId])->getResultArray();
    }

    public function upsert(int $mkId, int $cplId, int $taId, float $pct, int $jmlMhs): void
    {
        $existing = $this->where([
            'mata_kuliah_id'    => $mkId,
            'cpl_id'            => $cplId,
            'tahun_akademik_id' => $taId,
        ])->first();

        $data = [
            'mata_kuliah_id'    => $mkId,
            'cpl_id'            => $cplId,
            'tahun_akademik_id' => $taId,
            'persen_tercapai'   => $pct,
            'jumlah_mhs'        => $jmlMhs,
        ];

        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $this->insert($data);
        }
    }
}
