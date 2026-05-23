<?php

namespace App\Models;

use App\Models\AppModel;

class DokumenEvidenModel extends AppModel
{
    protected $table      = 'dokumen_eviden';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $updatedField  = ''; // hanya ada created_at

    protected $allowedFields = ['ref_table', 'ref_id', 'jenis', 'file', 'nama_asli'];

    /** Pilihan jenis dokumen per pemilik. */
    public const JENIS = [
        'dosen' => [
            'ijazah'     => 'Ijazah Terakhir',
            'sk_jad'     => 'SK Jabatan Akademik',
            'serdos'     => 'Sertifikat Pendidik (Serdos)',
            'sertifikat' => 'Sertifikat Profesi/Kompetensi',
            'lainnya'    => 'Lainnya',
        ],
        'tendik' => [
            'ijazah'     => 'Ijazah',
            'sertifikat' => 'Sertifikat Kompetensi',
            'sk'         => 'SK Pengangkatan',
            'lainnya'    => 'Lainnya',
        ],
    ];

    public function listFor(string $ref, int $id): array
    {
        return $this->where(['ref_table' => $ref, 'ref_id' => $id])->orderBy('jenis, id')->findAll();
    }
}
