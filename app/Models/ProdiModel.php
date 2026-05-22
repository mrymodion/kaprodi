<?php

namespace App\Models;

use App\Models\AppModel;

class ProdiModel extends AppModel
{
    protected $table      = 'prodi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['nama','kode','jenjang','fakultas','upps','status'];

    public function findAll(?int $limit = null, int $offset = 0): array
    {
        $this->orderBy('nama ASC');
        return parent::findAll($limit, $offset);
    }
}
