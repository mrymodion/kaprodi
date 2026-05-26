<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\PengampuMkModel;
use App\Services\SuratValidator;
use Config\Database;

/**
 * Halaman validasi publik (tanpa login) untuk Surat Tugas Mengajar.
 * Diakses lewat QR code pada dokumen PDF.
 */
class Validasi extends BaseController
{
    public function surat()
    {
        $dosenId = (int) $this->request->getGet('d');
        $taId    = (int) $this->request->getGet('t');
        $token   = (string) $this->request->getGet('k');

        if (!$dosenId || !$taId || !(new SuratValidator())->verify($dosenId, $taId, $token)) {
            return view('validasi/surat', ['valid' => false]);
        }

        $db   = Database::connect();
        $d    = (new DosenModel())->find($dosenId);
        $list = (new PengampuMkModel())->listByDosenTa($dosenId, $taId);
        if (!$d || empty($list)) {
            return view('validasi/surat', ['valid' => false]);
        }

        $ta = $db->table('tahun_akademik')->where('id', $taId)->get()->getRowArray() ?: ['kode' => '-'];

        $prodi = $db->table('prodi p')
            ->select('p.nama AS prodi_nama, p.jenjang, p.kaprodi_dosen_id,
                      pt.nama AS pt_nama')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->where('p.id', (int) $d['prodi_id'])->get()->getRowArray() ?: [];

        $signer = '';
        $kid = (int) ($prodi['kaprodi_dosen_id'] ?? 0);
        if ($kid && ($k = (new DosenModel())->find($kid))) {
            $signer = trim(($k['gelar_depan'] ? $k['gelar_depan'] . ' ' : '') . $k['nama'] . ($k['gelar_belakang'] ? ', ' . $k['gelar_belakang'] : ''));
        }

        return view('validasi/surat', [
            'valid'     => true,
            'namaDosen' => trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '')),
            'nidn'      => $d['nidn'] ?? '',
            'prodiNama' => trim(($prodi['prodi_nama'] ?? '') . (!empty($prodi['jenjang']) ? ' ' . $prodi['jenjang'] : '')),
            'ptNama'    => $prodi['pt_nama'] ?? '',
            'ta'        => $ta,
            'list'      => $list,
            'signer'    => $signer,
        ]);
    }
}
