<?php

namespace App\Models;

class PkmModel extends AppModel
{
    protected $table      = 'pkm';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'prodi_id', 'tahun_akademik_id', 'judul', 'ketua_dosen_id',
        'sumber_dana', 'nama_sumber_dana', 'jumlah_dana',
        'mitra', 'lokasi', 'tahun_pelaksanaan',
        'libatkan_mahasiswa', 'jumlah_mhs_terlibat', 'dampak', 'link_bukti',
    ];

    // prodi_id dikecualikan — nilainya diset server-side, bukan dari form POST
    protected $validationRules = [
        'judul' => 'required|min_length[5]',
    ];

    public function listByProdi(int $prodiId, array $filter = []): array
    {
        $q = $this->select('pkm.*, d.nama AS nama_ketua, ta.kode AS ta_kode')
            ->join('dosen d', 'd.id = pkm.ketua_dosen_id', 'left')
            ->join('tahun_akademik ta', 'ta.id = pkm.tahun_akademik_id', 'left')
            ->where('pkm.prodi_id', $prodiId)
            ->orderBy('pkm.tahun_pelaksanaan DESC, pkm.judul ASC');

        if (!empty($filter['tahun'])) {
            $q->where('pkm.tahun_pelaksanaan', $filter['tahun']);
        }
        return $q->findAll();
    }

    public function getWithAnggota(int $id): array
    {
        $row = $this->findOrFail($id);
        $db  = \Config\Database::connect();
        $row['anggota'] = $db->query("
            SELECT pa.*, d.nama, d.nidn FROM pkm_anggota pa
            JOIN dosen d ON d.id = pa.dosen_id
            WHERE pa.pkm_id = ?
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
            FROM pkm WHERE prodi_id = ?
        ", [$prodiId])->getRowArray();

        return [
            'total'     => (int)($agg['total'] ?? 0),
            'danaTotal' => (int)($agg['dana_total'] ?? 0),
            'libatMhs'  => (int)($agg['libat_mhs'] ?? 0),
        ];
    }

    public function saveAnggota(int $pkmId, array $dosenIds): void
    {
        $db = \Config\Database::connect();
        $db->query("DELETE FROM pkm_anggota WHERE pkm_id = ?", [$pkmId]);
        foreach ($dosenIds as $dId) {
            $db->query("INSERT INTO pkm_anggota (pkm_id, dosen_id, peran) VALUES (?,?,'anggota')",
                [$pkmId, (int)$dId]);
        }
    }
}
