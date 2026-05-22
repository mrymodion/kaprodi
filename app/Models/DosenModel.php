<?php

namespace App\Models;

use App\Models\AppModel;

class DosenModel extends AppModel
{
    protected $table      = 'dosen';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;

    protected $allowedFields = [
        'prodi_id', 'nidn', 'nip', 'nama', 'gelar_depan', 'gelar_belakang',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'email', 'no_hp',
        'pendidikan_terakhir', 'bidang_keahlian', 'linieritas', 'jabatan_akademik',
        'no_sk_jad', 'tgl_sk_jad', 'sertifikat_pendidik', 'no_serdos',
        'status_kepegawaian', 'sinta_id', 'scopus_id', 'google_scholar_id', 'orcid', 'aktif',
    ];

    protected $validationRules = [
        'nama'     => 'required|min_length[3]|max_length[150]',
        'prodi_id' => 'required|is_natural_no_zero',
    ];

    public function listByProdi(int $prodiId): array
    {
        return $this->select('dosen.*, p.nama AS nama_prodi')
            ->join('prodi p', 'p.id = dosen.prodi_id', 'left')
            ->where('dosen.prodi_id', $prodiId)
            ->orderBy('dosen.nama')
            ->findAll();
    }

    public function summaryForProdi(int $prodiId): array
    {
        $base = $this->where(['prodi_id' => $prodiId, 'aktif' => 1]);

        $jumlah     = (clone $base)->countAllResults(false);
        $s3         = (clone $base)->where('pendidikan_terakhir', 'S3')->countAllResults(false);
        $lk_gb      = (clone $base)->whereIn('jabatan_akademik', ['Lektor Kepala', 'Guru Besar'])->countAllResults(false);
        $serdos     = (clone $base)->where('sertifikat_pendidik', 1)->countAllResults(false);
        $linier     = (clone $base)->where('linieritas', 'linier')->countAllResults(false);

        return compact('jumlah', 's3', 'lk_gb', 'serdos', 'linier');
    }
}
