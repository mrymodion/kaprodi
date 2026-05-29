<?php

namespace App\Models;

use CodeIgniter\Model;

class EvidenPpeppBuktiModel extends Model
{
    protected $table         = 'eviden_ppepp_bukti';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['eviden_id','fase','label','url','sumber','created_at'];
    protected $useTimestamps = false;

    public function byEviden(int $evidenId): array
    {
        $rows = $this->where('eviden_id', $evidenId)->orderBy('id')->findAll();
        $byFase = ['penetapan' => [], 'pelaksanaan' => [], 'evaluasi' => [], 'pengendalian' => [], 'peningkatan' => []];
        foreach ($rows as $r) $byFase[$r['fase']][] = $r;
        return $byFase;
    }

    public function tambah(int $evidenId, string $fase, string $label, string $url, string $sumber = 'manual'): int
    {
        $this->insert([
            'eviden_id'  => $evidenId,
            'fase'       => $fase,
            'label'      => mb_substr($label, 0, 250),
            'url'        => $url,
            'sumber'     => $sumber,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return (int) $this->getInsertID();
    }

    /** Cek apakah URL sudah ada untuk eviden+fase tertentu (anti-duplikat saat auto-tarik). */
    public function exists(int $evidenId, string $fase, string $url): bool
    {
        return $this->where(['eviden_id' => $evidenId, 'fase' => $fase, 'url' => $url])->countAllResults() > 0;
    }
}
