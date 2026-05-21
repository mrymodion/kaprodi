<?php

namespace App\Models;

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

    // prodi_id dikecualikan — nilainya diset server-side, bukan dari form POST
    protected $validationRules = [
        'judul' => 'required|min_length[5]',
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

    /** Satu query agregat menggantikan 3 query terpisah sebelumnya. */
    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();

        $agg = $db->query("
            SELECT
                COUNT(*) AS total,
                COALESCE(SUM(jumlah_dana), 0) AS dana_total,
                SUM(libatkan_mahasiswa) AS libat_mhs
            FROM penelitian WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();

        $perSumber = $db->query("
            SELECT sumber_dana, COUNT(*) AS jml, SUM(jumlah_dana) AS total_dana
            FROM penelitian WHERE prodi_id = ?
            GROUP BY sumber_dana
        ", [$prodiId])->getResultArray();

        return [
            'total'     => (int)($agg['total'] ?? 0),
            'danaTotal' => (int)($agg['dana_total'] ?? 0),
            'libatMhs'  => (int)($agg['libat_mhs'] ?? 0),
            'perSumber' => $perSumber,
        ];
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
