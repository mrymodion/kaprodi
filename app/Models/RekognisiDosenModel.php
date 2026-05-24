<?php

namespace App\Models;

use App\Models\AppModel;

class RekognisiDosenModel extends AppModel
{
    protected $table      = 'rekognisi_dosen';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'dosen_id', 'tahun_akademik_id', 'jenis', 'judul',
        'penyelenggara', 'tingkat', 'tahun', 'link_bukti', 'created_at',
    ];

    protected $validationRules = [
        'judul'    => 'required|min_length[3]',
        'jenis'    => 'required',
        'prodi_id' => 'required|is_natural_no_zero',
        'dosen_id' => 'required|is_natural_no_zero',
    ];

    const JENIS_LABELS = [
        'reviewer_jurnal'  => 'Reviewer/Mitra Bestari Jurnal',
        'editor_jurnal'    => 'Editor/Dewan Redaksi Jurnal',
        'keynote_speaker'  => 'Keynote/Invited Speaker',
        'pemakalah'        => 'Pemakalah Seminar',
        'asesor'           => 'Asesor/Reviewer Eksternal',
        'narasumber'       => 'Narasumber/Tenaga Ahli',
        'visiting_scholar' => 'Visiting Scholar/Dosen Tamu',
        'penghargaan'      => 'Penghargaan/Award',
        'pengurus_organisasi' => 'Pengurus Organisasi Profesi',
        'lainnya'          => 'Lainnya',
    ];

    public function listByDosen(int $dosenId): array
    {
        return $this->where('dosen_id', $dosenId)->orderBy('tahun DESC, id DESC')->findAll();
    }

    public function listByProdi(int $prodiId): array
    {
        return $this->select('rekognisi_dosen.*, d.nama AS dosen_nama, d.gelar_depan, d.gelar_belakang')
            ->join('dosen d', 'd.id = rekognisi_dosen.dosen_id')
            ->where('rekognisi_dosen.prodi_id', $prodiId)
            ->orderBy('rekognisi_dosen.tahun DESC, d.nama')
            ->findAll();
    }
}
