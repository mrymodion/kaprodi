<?php

namespace App\Models;

use CodeIgniter\Model;

class EvidenPpeppModel extends Model
{
    protected $table         = 'eviden_ppepp';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['prodi_id','lam_butir_id','penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan','updated_at','updated_by'];
    protected $useTimestamps = false;

    public const FASE = ['penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan'];

    public function get(int $prodiId, int $butirId): ?array
    {
        return $this->where(['prodi_id' => $prodiId, 'lam_butir_id' => $butirId])->first();
    }

    public function getOrCreate(int $prodiId, int $butirId, ?int $userId = null): array
    {
        if ($row = $this->get($prodiId, $butirId)) return $row;
        $this->insert([
            'prodi_id'     => $prodiId,
            'lam_butir_id' => $butirId,
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => $userId,
        ]);
        return $this->get($prodiId, $butirId);
    }

    public function upsert(int $prodiId, int $butirId, array $data, ?int $userId = null): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $row = $this->get($prodiId, $butirId);
        if ($row) {
            $this->update($row['id'], $data);
            return (int) $row['id'];
        }
        $data['prodi_id']     = $prodiId;
        $data['lam_butir_id'] = $butirId;
        $this->insert($data);
        return (int) $this->getInsertID();
    }

    /** Hitung jumlah fase yang sudah berisi narasi/bukti (0..5). */
    public static function progress(array $row, array $buktiByFase = []): int
    {
        $n = 0;
        foreach (self::FASE as $f) {
            if (!empty(trim((string) ($row[$f] ?? ''))) || !empty($buktiByFase[$f] ?? [])) $n++;
        }
        return $n;
    }
}
