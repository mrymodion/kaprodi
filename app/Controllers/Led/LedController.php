<?php

namespace App\Controllers\Led;

use App\Controllers\BaseController;
use App\Models\LedNarasiModel;
use App\Models\ProdiModel;
use App\Models\TahunAkademikModel;
use App\Services\LedGenerator;
use Modules\Akreditasi\Engine\AkreditasiEngine;

class LedController extends BaseController
{
    protected LedGenerator $generator;
    protected AkreditasiEngine $engine;

    public function __construct()
    {
        $this->generator = new LedGenerator();
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
        $taList    = (new TahunAkademikModel())->findAll();
        $instrumen = $prodiId ? $this->engine->getInstrumen($prodiId) : null;
        $templates = $prodiId ? $this->generator->getTemplatesWithNarasi($prodiId) : [];
        $files     = $this->generator->listFiles();
        $groqEnabled = !empty(env('groq.apiKey', ''));

        // Kelompokkan templates by bagian/kriteria
        $byBagian = [];
        foreach ($templates as $t) {
            $key = $t['kriteria_kode'] ?: $t['bagian'];
            $byBagian[$key][] = $t;
        }

        return view('led/index', compact('prodiId', 'instrumen', 'templates', 'byBagian', 'taList', 'files', 'groqEnabled'));
    }

    /** POST — generate full LED .docx dan download. */
    public function generate()
    {
        if (!auth()->user()->can('led.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $prodiId = $this->getProdiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi tidak ditemukan.');

        $taId = $this->request->getPost('tahun_akademik_id') ?: null;

        try {
            $path = $this->generator->generate($prodiId, $taId ? (int)$taId : null);
            return $this->response->download($path, null)->setFileName(basename($path));
        } catch (\Throwable $e) {
            log_message('error', 'LED gen error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate LED: ' . $e->getMessage());
        }
    }

    /** POST — simpan narasi manual satu bagian. */
    public function saveNarasi(int $templateId)
    {
        if (!auth()->user()->can('led.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $prodiId = $this->getProdiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi tidak ditemukan.');

        $isi = $this->request->getPost('isi') ?? '';
        (new LedNarasiModel())->upsert($prodiId, $templateId, $isi, false, auth()->id());

        return redirect()->back()->with('success', 'Narasi berhasil disimpan.');
    }

    /** POST — generate narasi via Groq AI untuk satu bagian. */
    public function groqGenerate(int $templateId)
    {
        if (!auth()->user()->can('led.generate')) {
            return $this->response->setJSON(['error' => 'Akses ditolak.'])->setStatusCode(403);
        }
        $prodiId = $this->getProdiId();
        if (!$prodiId) {
            return $this->response->setJSON(['error' => 'Prodi tidak ditemukan.'])->setStatusCode(400);
        }

        try {
            $narasi = $this->generator->generateNarasiGroq($prodiId, $templateId);
            (new LedNarasiModel())->upsert($prodiId, $templateId, $narasi, true, auth()->id());
            return $this->response->setJSON(['success' => true, 'narasi' => $narasi]);
        } catch (\Throwable $e) {
            log_message('error', 'Groq error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /** GET — download file LED yang sudah ada. */
    public function download(string $filename)
    {
        if (!auth()->user()->can('led.generate')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $filename = basename($filename);
        $path = WRITEPATH . 'led/' . $filename;
        if (!is_file($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
        return $this->response->download($path, null)->setFileName($filename);
    }
}
