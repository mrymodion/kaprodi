<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratTugasMetaModel extends Model
{
    protected $table         = 'surat_tugas_meta';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['dosen_id', 'tahun_akademik_id', 'nomor', 'tanggal', 'kota', 'dasar', 'signer_nama', 'signer_nip', 'updated_at'];
    protected $useTimestamps = false;

    public function get(int $dosenId, int $taId): ?array
    {
        return $this->where(['dosen_id' => $dosenId, 'tahun_akademik_id' => $taId])->first();
    }

    public function upsert(int $dosenId, int $taId, array $data): void
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $existing = $this->get($dosenId, $taId);
        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $data['dosen_id']          = $dosenId;
            $data['tahun_akademik_id'] = $taId;
            $this->insert($data);
        }
    }
}
