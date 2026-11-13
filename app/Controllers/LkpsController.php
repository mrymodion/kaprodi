<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TahunAkademikModel;
use App\Services\LkpsGenerator;

/**
 * Controller untuk manajemen dan unduh Laporan Kinerja Program Studi (LKPS) (Excel).
 */
class LkpsController extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    /**
     * Menampilkan halaman dashboard unduh LKPS.
     */
    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) {
            return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');
        }

        // Mengambil daftar Tahun Akademik untuk filter (opsional)
        $taModel = new TahunAkademikModel();
        $taList = $taModel->orderBy('tahun_mulai', 'DESC')->findAll();

        return view('lkps/index', [
            'taList' => $taList
        ]);
    }

    /**
     * Memproses request generate dan unduh file Excel LKPS.
     */
    public function download()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Prodi aktif belum dipilih.');
        }

        $kriteria = $this->request->getGet('kriteria') ?: 'all';
        $taId = $this->request->getGet('ta_id');
        $taId = $taId ? (int) $taId : null;

        $generator = new LkpsGenerator();

        try {
            if ($kriteria === 'all') {
                $path = $generator->generateAll($prodiId, $taId);
            } else {
                $path = $generator->generateByKriteria($prodiId, $kriteria, $taId);
            }
            return $this->response->download($path, null)->setFileName(basename($path));
        } catch (\Throwable $e) {
            log_message('error', '[LKPS Generator] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate Excel LKPS: ' . $e->getMessage());
        }
    }
}