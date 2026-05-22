<?php

namespace App\Models;

use App\Models\AppModel;

class TracerStudyModel extends AppModel
{
    protected $table      = 'tracer_study';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $updatedField  = ''; // tabel tracer_study tidak punya kolom updated_at

    protected $allowedFields = [
        'mahasiswa_id', 'tgl_isi', 'masa_tunggu_bulan', 'kesesuaian_bidang_kerja',
        'tingkat_pekerjaan', 'nama_perusahaan', 'jabatan', 'gaji_pertama', 'lanjut_studi',
    ];

    public function getByMahasiswa(int $mahasiswaId): ?array
    {
        return $this->where('mahasiswa_id', $mahasiswaId)->first();
    }

    public function listByProdi(int $prodiId): array
    {
        return $this->select('tracer_study.*, m.nim, m.nama AS nama_mhs, m.tgl_lulus, m.ipk, ta.kode AS ta_masuk')
            ->join('mahasiswa m', 'm.id = tracer_study.mahasiswa_id')
            ->join('tahun_akademik ta', 'ta.id = m.tahun_masuk_id', 'left')
            ->where('m.prodi_id', $prodiId)
            ->orderBy('m.nama')
            ->findAll();
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();

        $rows = $db->query("
            SELECT ts.masa_tunggu_bulan, ts.kesesuaian_bidang_kerja, ts.tingkat_pekerjaan, ts.lanjut_studi
            FROM tracer_study ts
            JOIN mahasiswa m ON m.id = ts.mahasiswa_id
            WHERE m.prodi_id = ?
        ", [$prodiId])->getResultArray();

        if (empty($rows)) return [];

        $total        = count($rows);
        $masaTunggu   = array_filter(array_column($rows, 'masa_tunggu_bulan'));
        $sesuai       = count(array_filter($rows, fn($r) => in_array($r['kesesuaian_bidang_kerja'], ['sesuai','sangat_sesuai'])));
        $multinasional= count(array_filter($rows, fn($r) => in_array($r['tingkat_pekerjaan'], ['multinasional','internasional'])));
        $wirausaha    = count(array_filter($rows, fn($r) => $r['tingkat_pekerjaan'] === 'wirausaha'));
        $lanjutStudi  = count(array_filter($rows, fn($r) => $r['lanjut_studi']));

        return [
            'total'           => $total,
            'avg_masa_tunggu' => $masaTunggu ? round(array_sum($masaTunggu) / count($masaTunggu), 1) : null,
            'pct_sesuai'      => round($sesuai / $total * 100),
            'pct_multinasional'=> round($multinasional / $total * 100),
            'pct_wirausaha'   => round($wirausaha / $total * 100),
            'pct_lanjut_studi'=> round($lanjutStudi / $total * 100),
        ];
    }

    public function lulusanBelumTracer(int $prodiId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT m.id, m.nim, m.nama, m.no_hp, m.tgl_lulus, m.ipk
            FROM mahasiswa m
            LEFT JOIN tracer_study ts ON ts.mahasiswa_id = m.id
            WHERE m.prodi_id = ? AND m.status = 'lulus' AND ts.id IS NULL
            ORDER BY m.nama
        ", [$prodiId])->getResultArray();
    }
}
