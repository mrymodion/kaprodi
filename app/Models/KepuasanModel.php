<?php

namespace App\Models;

use App\Models\AppModel;

class KepuasanModel extends AppModel
{
    protected $table      = 'kepuasan_pengguna_lulusan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'mahasiswa_id', 'nama_atasan', 'jabatan_atasan', 'nama_perusahaan',
        'etika', 'keahlian', 'bahasa_asing', 'teknologi_info', 'komunikasi',
        'kerjasama_tim', 'pengembangan_diri', 'saran', 'tgl_isi',
    ];

    /** 7 aspek penilaian kepuasan pengguna lulusan (LKPS 2.B.6). */
    public const ASPEK = [
        'etika'             => 'Etika',
        'keahlian'          => 'Keahlian pada bidang ilmu (kompetensi utama)',
        'bahasa_asing'      => 'Kemampuan berbahasa asing',
        'teknologi_info'    => 'Penggunaan teknologi informasi',
        'komunikasi'        => 'Kemampuan berkomunikasi',
        'kerjasama_tim'     => 'Kerjasama tim',
        'pengembangan_diri' => 'Pengembangan diri',
    ];

    public function getByMahasiswa(int $mahasiswaId): ?array
    {
        return $this->where('mahasiswa_id', $mahasiswaId)->first();
    }

    public function listByProdi(int $prodiId): array
    {
        return $this->select('kepuasan_pengguna_lulusan.*, m.nim, m.nama AS nama_mhs')
            ->join('mahasiswa m', 'm.id = kepuasan_pengguna_lulusan.mahasiswa_id')
            ->where('m.prodi_id', $prodiId)
            ->orderBy('m.nama')
            ->findAll();
    }

    /** Rata-rata tiap aspek (skala 1-4) + jumlah responden. */
    public function summaryByProdi(int $prodiId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query("
            SELECT k.* FROM kepuasan_pengguna_lulusan k
            JOIN mahasiswa m ON m.id = k.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getResultArray();

        $total = count($rows);
        if (!$total) return [];

        $avg = [];
        foreach (array_keys(self::ASPEK) as $a) {
            $vals = array_filter(array_column($rows, $a), fn($v) => $v !== null && $v !== '');
            $avg[$a] = $vals ? round(array_sum($vals) / count($vals), 2) : null;
        }
        $all = array_filter($avg, fn($v) => $v !== null);
        return [
            'total'   => $total,
            'avg'     => $avg,
            'avg_all' => $all ? round(array_sum($all) / count($all), 2) : null,
        ];
    }

    /** Lulusan yang belum punya survei kepuasan (untuk kirim WA). */
    public function lulusanBelum(int $prodiId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT m.id, m.nim, m.nama, m.no_hp, m.tgl_lulus
            FROM mahasiswa m
            LEFT JOIN kepuasan_pengguna_lulusan k ON k.mahasiswa_id = m.id
            WHERE m.prodi_id = ? AND m.status = 'lulus' AND k.id IS NULL
            ORDER BY m.nama
        ", [$prodiId])->getResultArray();
    }
}
