<?php

namespace App\Controllers;

use App\Models\EvidenPpeppModel;
use App\Models\EvidenPpeppBuktiModel;
use App\Services\SuratValidator;
use Config\Database;

/**
 * Halaman publik (tanpa login) untuk asesor visitasi: matriks PPEPP
 * per kriteria/butir untuk prodi tertentu, diakses via URL bertanda
 * tangan (token HMAC) yang dibagikan oleh prodi.
 */
class Visitasi extends BaseController
{
    public function eviden()
    {
        $prodiId = (int) $this->request->getGet('p');
        $token   = (string) $this->request->getGet('k');

        if (!$prodiId || !(new SuratValidator())->verifyVisitasi($prodiId, $token)) {
            return view('visitasi/invalid');
        }

        $db = Database::connect();
        $prodi = $db->table('prodi p')
            ->select('p.nama AS prodi_nama, p.jenjang, p.akreditasi_saat_ini,
                      pt.nama AS pt_nama, u.nama AS upps_nama, u.jenis AS upps_jenis')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->where('p.id', $prodiId)->get()->getRowArray() ?: [];

        $instrumenIds = array_map(fn($r) => (int) $r['lam_instrumen_id'],
            $db->table('prodi_instrumen')->select('lam_instrumen_id')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getResultArray()
        );

        $rows = !empty($instrumenIds)
            ? $db->table('lam_butir b')
                ->select('b.id, b.kode AS butir_kode, b.judul, b.deskripsi,
                          k.id AS kriteria_id, k.kode AS kriteria_kode, k.nama AS kriteria_nama,
                          i.id AS instrumen_id, i.nama AS instrumen_nama')
                ->join('lam_kriteria k', 'k.id = b.lam_kriteria_id')
                ->join('lam_instrumen i', 'i.id = k.lam_instrumen_id')
                ->whereIn('i.id', $instrumenIds)
                ->orderBy('i.id ASC, k.urutan ASC, b.urutan ASC')
                ->get()->getResultArray()
            : [];

        // Ambil narasi + bukti yang ada untuk prodi ini.
        $evidenByButir = [];
        $buktiByEviden = [];
        if (!empty($rows)) {
            $butirIds = array_column($rows, 'id');
            $eviden = $db->table('eviden_ppepp')
                ->whereIn('lam_butir_id', $butirIds)
                ->where('prodi_id', $prodiId)
                ->get()->getResultArray();
            $evIds = [];
            foreach ($eviden as $e) {
                $evidenByButir[(int) $e['lam_butir_id']] = $e;
                $evIds[] = (int) $e['id'];
            }
            if (!empty($evIds)) {
                $b = $db->table('eviden_ppepp_bukti')->whereIn('eviden_id', $evIds)->orderBy('id')->get()->getResultArray();
                foreach ($b as $row) $buktiByEviden[(int) $row['eviden_id']][$row['fase']][] = $row;
            }
        }

        // Group per (instrumen → kriteria → butir).
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['instrumen_id']]['nama'] = $r['instrumen_nama'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['kode'] = $r['kriteria_kode'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['nama'] = $r['kriteria_nama'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['butir'][] = $r;
        }

        return view('visitasi/eviden', [
            'prodi'         => $prodi,
            'grouped'       => $grouped,
            'evidenByButir' => $evidenByButir,
            'buktiByEviden' => $buktiByEviden,
        ]);
    }
}
