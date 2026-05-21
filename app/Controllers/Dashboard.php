<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        $user = function_exists('auth') ? auth()->user() : null;
        $isSuperadmin = $user && $user->inGroup('superadmin');
        $activeProdiId = session('active_prodi_id');

        // Scope query berdasarkan role
        $prodiQ = $db->table('prodi p')
            ->select('p.id, p.kode_pddikti, p.nama, p.jenjang, p.akreditasi_saat_ini, li.nama AS instrumen')
            ->join('prodi_instrumen pi', 'pi.prodi_id = p.id AND pi.aktif = 1', 'left')
            ->join('lam_instrumen li', 'li.id = pi.lam_instrumen_id', 'left')
            ->orderBy('p.jenjang, p.nama');
        if (!$isSuperadmin && $activeProdiId) {
            $prodiQ->where('p.id', $activeProdiId);
        }
        $prodiList = $prodiQ->get()->getResultArray();

        $prodiIds = array_column($prodiList, 'id');

        $jumlahProdi = count($prodiList);
        $jumlahDosen = $prodiIds
            ? $db->table('dosen')->whereIn('prodi_id', $prodiIds)->where('aktif', 1)->countAllResults()
            : 0;
        $jumlahMhs = $prodiIds
            ? $db->table('mahasiswa')->whereIn('prodi_id', $prodiIds)->where('status', 'aktif')->countAllResults()
            : 0;
        $jumlahInstr = $db->table('lam_instrumen')->where('aktif', 1)->countAllResults();

        return view('dashboard/index', compact(
            'jumlahProdi', 'jumlahDosen', 'jumlahMhs', 'jumlahInstr',
            'prodiList', 'isSuperadmin', 'user'
        ));
    }
}
