<?php

namespace App\Models;

use CodeIgniter\Model;

class KondisiEksternalNarasiModel extends Model
{
    protected $table         = 'kondisi_eksternal_narasi';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['prodi_id','sub_bagian','narasi','source_count','generated_at','updated_at','updated_by'];
    protected $useTimestamps = false;

    public function get(int $prodiId, string $sub): ?array
    {
        return $this->where(['prodi_id' => $prodiId, 'sub_bagian' => $sub])->first();
    }

    public function byProdi(int $prodiId): array
    {
        $rows = $this->where('prodi_id', $prodiId)->orderBy('sub_bagian')->findAll();
        $out = [];
        foreach ($rows as $r) $out[$r['sub_bagian']] = $r;
        return $out;
    }

    public function upsert(int $prodiId, string $sub, string $narasi, int $sourceCount, bool $regenerated, ?int $userId = null): void
    {
        $now = date('Y-m-d H:i:s');
        $row = $this->get($prodiId, $sub);
        $data = [
            'narasi'       => $narasi,
            'source_count' => $sourceCount,
            'updated_at'   => $now,
            'updated_by'   => $userId,
        ];
        if ($regenerated) $data['generated_at'] = $now;
        if ($row) {
            $this->update($row['id'], $data);
        } else {
            $data['prodi_id']    = $prodiId;
            $data['sub_bagian']  = $sub;
            $data['generated_at']= $now;
            $this->insert($data);
        }
    }
}
