<?php

namespace App\Models;

use App\Models\AppModel;

class CplModel extends AppModel
{
    protected $table      = 'cpl';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['kurikulum_id','kode','aspek','deskripsi'];
    protected $validationRules = [
        'kode'         => 'required|max_length[20]',
        'aspek'        => 'required',
        'deskripsi'    => 'required',
        'kurikulum_id' => 'required|is_natural_no_zero',
    ];

    public function byKurikulum(int $kurikulumId): array
    {
        return $this->where('kurikulum_id', $kurikulumId)
            ->orderBy("FIELD(aspek,'sikap','pengetahuan','keterampilan_umum','keterampilan_khusus'), kode")
            ->findAll();
    }

    public static function aspekLabel(string $aspek): string
    {
        return match($aspek) {
            'sikap'                => 'Sikap',
            'pengetahuan'          => 'Pengetahuan',
            'keterampilan_umum'    => 'Keterampilan Umum',
            'keterampilan_khusus'  => 'Keterampilan Khusus',
            default                => $aspek,
        };
    }
}
