<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $jumlahProdi  = $db->table('prodi')->countAllResults();
        $jumlahDosen  = $db->table('dosen')->where('aktif', 1)->countAllResults();
        $jumlahMhs    = $db->table('mahasiswa')->where('status', 'aktif')->countAllResults();
        $jumlahInstr  = $db->table('lam_instrumen')->where('aktif', 1)->countAllResults();

        $prodiList = $db->table('prodi p')
            ->select('p.id, p.kode_pddikti, p.nama, p.jenjang, p.akreditasi_saat_ini, li.nama AS instrumen')
            ->join('prodi_instrumen pi', 'pi.prodi_id = p.id AND pi.aktif = 1', 'left')
            ->join('lam_instrumen li', 'li.id = pi.lam_instrumen_id', 'left')
            ->orderBy('p.jenjang, p.nama')
            ->get()->getResultArray();

        return view('dashboard/index', compact(
            'jumlahProdi', 'jumlahDosen', 'jumlahMhs', 'jumlahInstr', 'prodiList'
        ));
    }
}
