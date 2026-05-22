<?php

namespace App\Models;

use App\Models\AppModel;

class TahunAkademikModel extends AppModel
{
    protected $table      = 'tahun_akademik';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['kode','tahun_mulai','tahun_akhir','semester','aktif'];

    public function aktif(): ?array
    {
        return $this->where('aktif', 1)->first();
    }

    public function findAll(?int $limit = null, int $offset = 0): array
    {
        $this->orderBy('tahun_mulai DESC, semester DESC');
        return parent::findAll($limit, $offset);
    }
}
