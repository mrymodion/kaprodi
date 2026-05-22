<?php

namespace App\Models;

use App\Models\AppModel;

class ButirSkorModel extends AppModel
{
    protected $table      = 'butir_skor';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'lam_butir_id', 'tahun_akademik_id',
        'skor_auto', 'skor_manual', 'skor_final',
        'sumber_data_json', 'catatan', 'link_bukti', 'updated_at', 'updated_by',
    ];

    public function getByButir(int $prodiId, int $butirId, ?int $taId): ?array
    {
        $q = $this->where(['prodi_id' => $prodiId, 'lam_butir_id' => $butirId]);
        if ($taId) {
            $q->where('tahun_akademik_id', $taId);
        } else {
            $q->where('tahun_akademik_id IS NULL');
        }
        return $q->first();
    }
}
