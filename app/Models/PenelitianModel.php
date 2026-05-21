<?php

namespace App\Models;

use App\Models\AppModel;

class PenelitianModel extends AppModel
{
    protected $table      = 'penelitian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'prodi_id', 'tahun_akademik_id', 'judul', 'ketua_dosen_id',
        'sumber_dana', 'nama_sumber_dana', 'jumlah_dana', 'mata_uang',
        'tahun_pelaksanaan', 'libatkan_mahasiswa', 'jumlah_mhs_terlibat',
    ];

    protected $validationRules = [
        'judul'    => 'required|min_length[5]',
        'prodi_id' => 'required|is_natural_no_zero',
    ];

    public function listByProdi(int $prodiId, array $filter = []): array
    {
        $q = $this->select('penelitian.*, d.nama AS nama_ketua, ta.kode AS ta_kode')
            ->join('dosen d', 'd.id = penelitian.ketua_dosen_id', 'left')
            ->join('tahun_akademik ta', 'ta.id = penelitian.tahun_akademik_id', 'left')
            ->where('penelitian.prodi_id', $prodiId)
            ->orderBy('penelitian.tahun_pelaksanaan DESC, penelitian.judul ASC');

        if (!empty($filter['tahun'])) {
            $q->where('penelitian.tahun_pelaksanaan', $filter['tahun']);
        }
        if (!empty($filter['sumber_dana'])) {
            $q->where('penelitian.sumber_dana', $filter['sumber_dana']);
        }
        return $q->findAll();
    }

    public function getWithAnggota(int $id): array
    {
        $row = $this->findOrFail($id);
        $db  = \Config\Database::connect();
        $row['anggota'] = $db->query("
            SELECT pa.*, d.nama, d.nidn FROM penelitian_anggota pa
            JOIN dosen d ON d.id = pa.dosen_id
            WHERE pa.penelitian_id = ?
        ", [$id])->getResultArray();
        return $row;
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();

        $total = $this->where('prodi_id', $prodiId)->countAllResults();

        $perSumber = $db->query("
            SELECT sumber_dana, COUNT(*) AS jml, SUM(jumlah_dana) AS total_dana
            FROM penelitian WHERE prodi_id = ?
            GROUP BY sumber_dana
        ", [$prodiId])->getResultArray();

        $danaTotal = array_sum(array_column($perSumber, 'total_dana'));
        $libatMhs  = $this->where(['prodi_id' => $prodiId, 'libatkan_mahasiswa' => 1])->countAllResults();

        return compact('total', 'perSumber', 'danaTotal', 'libatMhs');
    }

    public function saveAnggota(int $penelitianId, array $dosenIds): void
    {
        $db = \Config\Database::connect();
        $db->query("DELETE FROM penelitian_anggota WHERE penelitian_id = ?", [$penelitianId]);
        foreach ($dosenIds as $dId) {
            $db->query("INSERT INTO penelitian_anggota (penelitian_id, dosen_id, peran) VALUES (?,?,'anggota')",
                [$penelitianId, (int)$dId]);
        }
    }
}
