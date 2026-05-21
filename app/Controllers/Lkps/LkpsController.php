<?php

namespace App\Controllers\Lkps;

use App\Controllers\BaseController;
use App\Models\ProdiModel;
use App\Models\TahunAkademikModel;
use App\Services\LkpsGenerator;
use Modules\Akreditasi\Engine\AkreditasiEngine;

class LkpsController extends BaseController
{
    protected LkpsGenerator $generator;
    protected AkreditasiEngine $engine;

    public function __construct()
    {
        $this->generator = new LkpsGenerator();
        $this->engine    = new AkreditasiEngine();
    }

    private function getProdiId(): ?int
    {
        $user = auth()->user();
        if ($user->inGroup('superadmin')) {
            $pid = session('active_prodi_id');
            if (!$pid) {
                $first = (new ProdiModel())->first();
                $pid = $first ? $first['id'] : null;
            }
            return $pid ? (int)$pid : null;
        }
        return session('active_prodi_id') ? (int)session('active_prodi_id') : null;
    }

    public function index(): string
    {
        $prodiId   = $this->getProdiId();
        $taId      = $this->request->getGet('ta') ? (int)$this->request->getGet('ta') : null;
        $taList    = (new TahunAkademikModel())->findAll();
        $instrumen = $prodiId ? $this->engine->getInstrumen($prodiId) : null;
        $tabelList = $prodiId ? $this->generator->listTabel($prodiId) : [];

        // Cek file LKPS sebelumnya
        $existingFiles = [];
        $lkpsDir = WRITEPATH . 'lkps/';
        if (is_dir($lkpsDir)) {
            foreach (glob($lkpsDir . '*.xlsx') as $f) {
                $existingFiles[] = [
                    'name'  => basename($f),
                    'size'  => filesize($f),
                    'mtime' => filemtime($f),
                ];
            }
            usort($existingFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);
            $existingFiles = array_slice($existingFiles, 0, 10);
        }

        return view('lkps/index', compact('prodiId', 'instrumen', 'tabelList', 'taList', 'taId', 'existingFiles'));
    }

    /** Generate full workbook (semua tabel). */
    public function generateAll()
    {
        if (!auth()->user()->can('lkps.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $prodiId = $this->getProdiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi tidak ditemukan.');

        $taId = $this->request->getPost('tahun_akademik_id') ?: null;

        try {
            $path = $this->generator->generateAll($prodiId, $taId ? (int)$taId : null);
            return $this->response->download($path, null)->setFileName(basename($path));
        } catch (\Throwable $e) {
            log_message('error', 'LKPS gen error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate LKPS: ' . $e->getMessage());
        }
    }

    /** Generate satu tabel saja. */
    public function generateSingle(string $kodeTabel)
    {
        if (!auth()->user()->can('lkps.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $prodiId = $this->getProdiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi tidak ditemukan.');

        $taId = $this->request->getPost('tahun_akademik_id') ?: null;

        try {
            $path = $this->generator->generateSingle($prodiId, $kodeTabel, $taId ? (int)$taId : null);
            return $this->response->download($path, null)->setFileName(basename($path));
        } catch (\Throwable $e) {
            log_message('error', 'LKPS gen error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate: ' . $e->getMessage());
        }
    }

    /** Download file LKPS yang sudah ada di writable/lkps/. */
    public function download(string $filename)
    {
        if (!auth()->user()->can('lkps.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $filename = basename($filename); // sanitize
        $path = WRITEPATH . 'lkps/' . $filename;
        if (!is_file($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
        return $this->response->download($path, null)->setFileName($filename);
    }
}
