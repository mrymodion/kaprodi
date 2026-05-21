<?php

namespace App\Models;

use App\Models\AppModel;

class MahasiswaModel extends AppModel
{
    protected $table      = 'mahasiswa';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'prodi_id', 'nim', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'email', 'no_hp', 'kota_asal', 'provinsi_asal', 'negara_asal',
        'tahun_masuk_id', 'jalur_masuk', 'status', 'tgl_lulus', 'ipk', 'masa_studi_bulan',
    ];

    protected $validationRules = [
        'nim'      => 'required|max_length[30]',
        'nama'     => 'required|min_length[3]|max_length[150]',
        'prodi_id' => 'required|is_natural_no_zero',
    ];

    public function listByProdi(int $prodiId, array $filter = []): array
    {
        $q = $this->select('mahasiswa.*, ta.kode AS ta_masuk')
            ->join('tahun_akademik ta', 'ta.id = mahasiswa.tahun_masuk_id', 'left')
            ->where('mahasiswa.prodi_id', $prodiId)
            ->orderBy('mahasiswa.status, mahasiswa.nama');

        if (!empty($filter['status'])) {
            $q->where('mahasiswa.status', $filter['status']);
        }
        if (!empty($filter['tahun_masuk_id'])) {
            $q->where('mahasiswa.tahun_masuk_id', $filter['tahun_masuk_id']);
        }
        return $q->findAll();
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();

        $statusCounts = $db->query("
            SELECT status, COUNT(*) AS jumlah
            FROM mahasiswa WHERE prodi_id = ?
            GROUP BY status
        ", [$prodiId])->getResultArray();

        $summary = ['aktif'=>0,'lulus'=>0,'do'=>0,'cuti'=>0,'keluar'=>0,'total'=>0];
        foreach ($statusCounts as $r) {
            $summary[$r['status']] = (int)$r['jumlah'];
        }
        $summary['total'] = array_sum(array_diff_key($summary, ['total'=>0]));

        // Per tahun masuk (Tabel 2.A.1)
        $perTa = $db->query("
            SELECT ta.kode AS ta_kode, ta.id AS ta_id,
                   COUNT(*) AS jumlah,
                   SUM(CASE WHEN m.status = 'lulus' THEN 1 ELSE 0 END) AS lulus,
                   SUM(CASE WHEN m.status = 'do' THEN 1 ELSE 0 END) AS do_mhs,
                   SUM(CASE WHEN m.status = 'aktif' THEN 1 ELSE 0 END) AS aktif
            FROM mahasiswa m
            LEFT JOIN tahun_akademik ta ON ta.id = m.tahun_masuk_id
            WHERE m.prodi_id = ?
            GROUP BY ta.id, ta.kode
            ORDER BY ta.tahun_mulai DESC
        ", [$prodiId])->getResultArray();

        $summary['per_ta'] = $perTa;

        // Rata-rata IPK & masa studi lulusan
        $lulusan = $db->query("
            SELECT AVG(ipk) AS avg_ipk, AVG(masa_studi_bulan) AS avg_masa_studi
            FROM mahasiswa WHERE prodi_id = ? AND status = 'lulus' AND ipk IS NOT NULL
        ", [$prodiId])->getRowArray();
        $summary['avg_ipk']        = $lulusan['avg_ipk'] ? round($lulusan['avg_ipk'], 2) : null;
        $summary['avg_masa_studi'] = $lulusan['avg_masa_studi'] ? round($lulusan['avg_masa_studi']) : null;

        return $summary;
    }
}
