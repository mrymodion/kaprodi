<?php

namespace App\Models;

use App\Models\AppModel;

class PengampuMkModel extends AppModel
{
    protected $table      = 'pengampu_mk';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'dosen_id', 'tahun_akademik_id', 'mata_kuliah_id',
        'nama_mk', 'kategori', 'sks', 'jumlah_kelas', 'created_at',
    ];

    public const KATEGORI_LABELS = [
        'ps_sendiri' => 'Pendidikan di PS Sendiri',
        'ps_lain_pt' => 'Pendidikan di PS Lain (PT ini)',
        'pt_lain'    => 'Pendidikan di PT Lain',
    ];

    /** Daftar penugasan seorang dosen pada satu tahun akademik. */
    public function listByDosenTa(int $dosenId, int $taId): array
    {
        return $this->select('pengampu_mk.*, (pengampu_mk.sks * pengampu_mk.jumlah_kelas) AS total_sks')
            ->where(['dosen_id' => $dosenId, 'tahun_akademik_id' => $taId])
            ->orderBy('kategori, nama_mk')
            ->findAll();
    }

    /**
     * Jumlah SKS (sks × jumlah_kelas) per kategori untuk satu dosen & TA.
     * @return array{ps_sendiri:float, ps_lain_pt:float, pt_lain:float}
     */
    public function sumByKategori(int $dosenId, int $taId): array
    {
        $rows = $this->select('kategori, SUM(sks * jumlah_kelas) AS jml')
            ->where(['dosen_id' => $dosenId, 'tahun_akademik_id' => $taId])
            ->groupBy('kategori')
            ->findAll();

        $out = ['ps_sendiri' => 0.0, 'ps_lain_pt' => 0.0, 'pt_lain' => 0.0];
        foreach ($rows as $r) {
            if (isset($out[$r['kategori']])) {
                $out[$r['kategori']] = (float) $r['jml'];
            }
        }
        return $out;
    }
}
