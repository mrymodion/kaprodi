<?php

namespace App\Models;

use App\Models\AppModel;

class PublikasiModel extends AppModel
{
    protected $table      = 'publikasi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'prodi_id', 'dosen_id', 'kategori', 'jenis', 'judul', 'penulis',
        'nama_media', 'tahun', 'volume', 'doi', 'url', 'sitasi', 'sinta_score',
        'created_at',
    ];

    protected $validationRules = [
        'judul'    => 'required|min_length[5]',
        'prodi_id' => 'required|is_natural_no_zero',
        'kategori' => 'required|in_list[penelitian,pkm]',
        'jenis'    => 'required',
    ];

    const JENIS_LABELS = [
        'jurnal_nasional_tidak_terakreditasi' => 'Jurnal Nasional Tidak Terakreditasi',
        'jurnal_nasional_terakreditasi'       => 'Jurnal Nasional Terakreditasi (SINTA)',
        'jurnal_internasional'                => 'Jurnal Internasional',
        'jurnal_internasional_bereputasi'     => 'Jurnal Internasional Bereputasi (Scopus/WoS)',
        'seminar_lokal'                       => 'Seminar/Prosiding Lokal',
        'seminar_nasional'                    => 'Seminar/Prosiding Nasional',
        'seminar_internasional'               => 'Seminar/Prosiding Internasional',
        'media_massa_nasional'                => 'Media Massa Nasional',
        'media_massa_internasional'           => 'Media Massa Internasional',
        'buku'                                => 'Buku',
        'book_chapter'                        => 'Book Chapter',
    ];

    public function listByDosen(int $dosenId): array
    {
        return $this->select('publikasi.*')
            ->join('publikasi_dosen pd', 'pd.publikasi_id = publikasi.id')
            ->where('pd.dosen_id', $dosenId)
            ->orderBy('publikasi.tahun DESC, publikasi.judul ASC')
            ->findAll();
    }

    /** Publikasi prodi yang BELUM diklaim oleh dosen ini (boleh sudah dimiliki dosen lain — karya bersama). */
    public function listClaimableByDosen(int $prodiId, int $dosenId): array
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT p.*,
                   (SELECT COUNT(*) FROM publikasi_dosen pc WHERE pc.publikasi_id = p.id) AS jml_pemilik
            FROM publikasi p
            WHERE p.prodi_id = ?
              AND NOT EXISTS (SELECT 1 FROM publikasi_dosen pd WHERE pd.publikasi_id = p.id AND pd.dosen_id = ?)
            ORDER BY p.tahun DESC, p.judul ASC
        ", [$prodiId, $dosenId])->getResultArray();
    }

    /** Tautkan dosen ke publikasi (abaikan bila sudah ada). */
    public function attachDosen(int $publikasiId, int $dosenId): void
    {
        $db = \Config\Database::connect();
        $exists = $db->table('publikasi_dosen')
            ->where('publikasi_id', $publikasiId)->where('dosen_id', $dosenId)->countAllResults();
        if (!$exists) {
            $db->table('publikasi_dosen')->insert([
                'publikasi_id' => $publikasiId,
                'dosen_id'     => $dosenId,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function detachDosen(int $publikasiId, int $dosenId): void
    {
        \Config\Database::connect()->table('publikasi_dosen')
            ->where('publikasi_id', $publikasiId)->where('dosen_id', $dosenId)->delete();
    }

    /** Ganti seluruh pemilik publikasi dengan daftar dosen baru. */
    public function syncDosen(int $publikasiId, array $dosenIds): void
    {
        $db = \Config\Database::connect();
        $db->table('publikasi_dosen')->where('publikasi_id', $publikasiId)->delete();
        $now = date('Y-m-d H:i:s');
        $batch = [];
        foreach (array_unique(array_map('intval', $dosenIds)) as $did) {
            if ($did > 0) {
                $batch[] = ['publikasi_id' => $publikasiId, 'dosen_id' => $did, 'created_at' => $now];
            }
        }
        if ($batch) {
            $db->table('publikasi_dosen')->insertBatch($batch);
        }
    }

    public function dosenOwns(int $publikasiId, int $dosenId): bool
    {
        return (bool) \Config\Database::connect()->table('publikasi_dosen')
            ->where('publikasi_id', $publikasiId)->where('dosen_id', $dosenId)->countAllResults();
    }

    public function dosenCount(int $publikasiId): int
    {
        return (int) \Config\Database::connect()->table('publikasi_dosen')
            ->where('publikasi_id', $publikasiId)->countAllResults();
    }

    /** @return array<int,int[]> map publikasi_id => daftar dosen_id (hindari N+1 di daftar). */
    public function dosenIdsForMany(array $publikasiIds): array
    {
        $out = [];
        $publikasiIds = array_values(array_filter(array_map('intval', $publikasiIds)));
        if (!$publikasiIds) return $out;
        $rows = \Config\Database::connect()->table('publikasi_dosen')
            ->select('publikasi_id, dosen_id')->whereIn('publikasi_id', $publikasiIds)->get()->getResultArray();
        foreach ($rows as $r) {
            $out[(int) $r['publikasi_id']][] = (int) $r['dosen_id'];
        }
        return $out;
    }

    public function listByProdi(int $prodiId, string $kategori = '', bool $onlyUnclaimed = false): array
    {
        $q = $this->where('prodi_id', $prodiId)->orderBy('tahun DESC, judul ASC');
        if ($kategori) $q->where('kategori', $kategori);
        if ($onlyUnclaimed) {
            $q->whereNotIn('id', function ($builder) {
                return $builder->select('DISTINCT publikasi_id')->from('publikasi_dosen');
            });
        }
        return $q->findAll();
    }

    public function countUnclaimedByProdi(int $prodiId): int
    {
        return (int) $this->where('prodi_id', $prodiId)
            ->whereNotIn('id', function ($builder) {
                return $builder->select('DISTINCT publikasi_id')->from('publikasi_dosen');
            })->countAllResults();
    }

    public function summaryByProdi(int $prodiId): array
    {
        $db = \Config\Database::connect();
        $rows = $db->query("
            SELECT kategori, jenis, COUNT(*) AS jml, SUM(sitasi) AS total_sitasi
            FROM publikasi WHERE prodi_id = ?
            GROUP BY kategori, jenis
        ", [$prodiId])->getResultArray();

        $summary = ['penelitian' => [], 'pkm' => [], 'total' => 0];
        foreach ($rows as $r) {
            $summary[$r['kategori']][$r['jenis']] = ['jml' => (int)$r['jml'], 'sitasi' => (int)$r['total_sitasi']];
            $summary['total'] += (int)$r['jml'];
        }
        return $summary;
    }
}
