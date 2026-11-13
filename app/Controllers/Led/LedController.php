<?php

namespace App\Controllers\Led;

use App\Controllers\BaseController;
use App\Services\LedGenerator;
use Config\Database;

/**
 * Controller Laporan Evaluasi Diri (LED) terintegrasi Groq AI.
 */
class LedController extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $generator = new LedGenerator();
        $templates = $generator->getTemplatesWithNarasi($prodiId);
        $files     = $generator->listFiles();

        // Kelompokkan template berdasarkan Kriteria (atau Umum untuk Pendahuluan/Penutup)
        $grouped = [];
        foreach ($templates as $t) {
            $k = $t['kriteria_kode'] ? "Kriteria {$t['kriteria_kode']} ({$t['kriteria_nama']})" : 'Bagian Umum';
            $grouped[$k][] = $t;
        }

        return view('led/index', [
            'grouped' => $grouped,
            'files'   => $files
        ]);
    }

    /**
     * Simpan narasi yang diketik/diedit manual oleh user.
     */
    public function saveNarasi(int $templateId)
    {
        $prodiId = $this->prodiId();
        $isi = $this->request->getPost('isi');

        $db = Database::connect();
        $existing = $db->table('led_narasi')
            ->where(['prodi_id' => $prodiId, 'lam_template_led_id' => $templateId])
            ->get()->getRowArray();

        $data = [
            'prodi_id'            => $prodiId,
            'lam_template_led_id' => $templateId,
            'isi'                 => $isi,
            'generated_by_ai'     => 0, // Reset flag AI jika diedit manual
            'updated_by'          => auth()->id(),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $db->table('led_narasi')->where('id', $existing['id'])->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $db->table('led_narasi')->insert($data);
        }

        return redirect()->back()->with('success', 'Narasi LED berhasil disimpan secara manual.');
    }

    /**
     * Minta Groq AI untuk menyusun draf narasi secara otomatis.
     */
    public function groqGenerate(int $templateId)
    {
        $prodiId = $this->prodiId();
        $generator = new LedGenerator();

        try {
            $narasi = $generator->generateNarasiGroq($prodiId, $templateId);

            // Auto-save hasil dari AI
            $db = Database::connect();
            $existing = $db->table('led_narasi')
                ->where(['prodi_id' => $prodiId, 'lam_template_led_id' => $templateId])
                ->get()->getRowArray();

            $data = [
                'prodi_id'            => $prodiId,
                'lam_template_led_id' => $templateId,
                'isi'                 => $narasi,
                'generated_by_ai'     => 1, // Tandai ini hasil AI
                'updated_by'          => auth()->id(),
                'updated_at'          => date('Y-m-d H:i:s'),
            ];

            if ($existing) {
                $db->table('led_narasi')->where('id', $existing['id'])->update($data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->table('led_narasi')->insert($data);
            }

            return redirect()->back()->with('success', 'Narasi LED berhasil di-generate secara menakjubkan oleh AI Groq!');
        } catch (\Throwable $e) {
            log_message('error', '[Groq AI LED] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses AI: ' . $e->getMessage());
        }
    }

    /**
     * Menghasilkan dokumen lengkap (Word) dengan cover & daftar isi.
     */
    public function generate()
    {
        $prodiId = $this->prodiId();
        
        try {
            $generator = new LedGenerator();
            $path = $generator->generate($prodiId);
            return redirect()->back()->with('success', 'Dokumen Word LED berhasil dirender. Silakan unduh di panel kanan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal render dokumen Word: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        $path = WRITEPATH . 'led/' . basename($filename);
        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $this->response->download($path, null)->setFileName(basename($path));
    }
}