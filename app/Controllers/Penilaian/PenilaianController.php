<?php

namespace App\Controllers\Penilaian;

use App\Controllers\BaseController;
use App\Models\TahunAkademikModel;
use App\Models\ProdiModel;
use Modules\Akreditasi\Engine\AkreditasiEngine;

class PenilaianController extends BaseController
{
    protected AkreditasiEngine $engine;

    public function __construct()
    {
        $this->engine = new AkreditasiEngine();
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

    /** Dashboard penilaian: semua kriteria + progress + total skor. */
    public function index(): string
    {
        $prodiId   = $this->getProdiId();
        $taId      = $this->request->getGet('ta') ? (int)$this->request->getGet('ta') : null;
        $taList    = (new TahunAkademikModel())->findAll();
        $instrumen = $prodiId ? $this->engine->getInstrumen($prodiId) : null;

        $kriteria = [];
        $total    = ['nilai_akhir' => 0, 'max_nilai' => 400, 'pct' => 0, 'predikat' => '-'];

        if ($instrumen && $prodiId) {
            $kriteria = $this->engine->getKriteriaWithButir($prodiId, $instrumen['id'], $taId);
            $total    = $this->engine->totalSkor($prodiId, $instrumen['id'], $taId);
        }

        return view('penilaian/index', compact('prodiId', 'instrumen', 'kriteria', 'total', 'taList', 'taId'));
    }

    /** Halaman butir dalam satu kriteria. */
    public function kriteria(int $kriteriaId): string
    {
        $prodiId   = $this->getProdiId();
        $taId      = $this->request->getGet('ta') ? (int)$this->request->getGet('ta') : null;
        $taList    = (new TahunAkademikModel())->findAll();

        $db        = \Config\Database::connect();
        $k         = $db->query("SELECT * FROM lam_kriteria WHERE id=?", [$kriteriaId])->getRowArray();
        if (!$k) throw new \CodeIgniter\Exceptions\PageNotFoundException("Kriteria tidak ditemukan.");

        $instrumen = $prodiId ? $this->engine->getInstrumen($prodiId) : null;

        $butir = $db->query("
            SELECT b.*,
                bs.id AS skor_id, bs.skor_auto, bs.skor_manual, bs.skor_final, bs.catatan, bs.link_bukti, bs.updated_at AS skor_updated_at
            FROM lam_butir b
            LEFT JOIN butir_skor bs ON bs.lam_butir_id = b.id
                AND bs.prodi_id = ?
                AND (bs.tahun_akademik_id = ? OR (bs.tahun_akademik_id IS NULL AND ? IS NULL))
            WHERE b.lam_kriteria_id = ?
            ORDER BY b.urutan
        ", [$prodiId, $taId, $taId, $kriteriaId])->getResultArray();

        // Rubrik per butir
        foreach ($butir as &$b) {
            $b['rubrik'] = $db->query("
                SELECT * FROM lam_butir_rubrik WHERE lam_butir_id=? ORDER BY skor DESC
            ", [$b['id']])->getResultArray();
        }

        return view('penilaian/kriteria', compact('k', 'butir', 'instrumen', 'prodiId', 'taList', 'taId', 'kriteriaId'));
    }

    /** Simpan skor manual satu butir (POST via form kecil). */
    public function saveSkor(int $butirId)
    {
        if (!auth()->user()->can('penilaian.input')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $prodiId  = $this->getProdiId();
        $taId     = $this->request->getPost('tahun_akademik_id') ?: null;
        $taIdInt  = $taId ? (int) $taId : null;
        $skor     = $this->request->getPost('skor_manual') !== '' ? (float)$this->request->getPost('skor_manual') : null;
        $catatan  = $this->request->getPost('catatan');
        $userId   = (int)auth()->user()->id;

        if ($skor !== null && ($skor < 1 || $skor > 4)) {
            return redirect()->back()->with('error', 'Skor harus antara 1-4.');
        }

        // Bukti PDF: pertahankan yang lama kecuali ada unggahan baru / minta hapus
        $store     = new \App\Services\EvidenceStorage();
        $existing  = (new \App\Models\ButirSkorModel())->getByButir($prodiId, $butirId, $taIdInt);
        $linkBukti = $existing['link_bukti'] ?? null;
        $isLocal   = fn($v) => $v && !str_starts_with((string) $v, 'http');
        $info      = '';

        if ($this->request->getPost('hapus_bukti')) {
            if ($isLocal($linkBukti)) $store->delete($linkBukti);
            $linkBukti = null;
        }

        $file = $this->request->getFile('bukti_pdf');
        if ($file && $file->isValid() && $file->getSize() > 0) {
            $res = $store->storePdf($file);
            if (!$res['ok']) {
                return redirect()->back()->withInput()->with('error', 'Gagal unggah bukti: ' . $res['error']);
            }
            if ($isLocal($linkBukti)) $store->delete($linkBukti); // hapus file lama
            $linkBukti = $res['name'];
            $info = $res['compressed']
                ? sprintf(' Bukti PDF dikompres %s → %s.', $this->fmtBytes($res['before']), $this->fmtBytes($res['after']))
                : ' Bukti PDF tersimpan (kompresi dilewati — Ghostscript tidak terpasang).';
        }

        $this->engine->saveSkorManual($prodiId, $butirId, $taIdInt, $skor, $catatan, $userId, $linkBukti);

        return redirect()->back()->with('success', 'Skor berhasil disimpan.' . $info);
    }

    /** Draf catatan/justifikasi skor butir via Groq AI (JSON). */
    public function catatanAi(int $butirId)
    {
        if (!auth()->user()->can('penilaian.input')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak.']);
        }
        $client = new \App\Services\Ai\GroqClient();
        if (!$client->isConfigured()) {
            return $this->response->setJSON(['error' => 'Groq AI belum dikonfigurasi (set groq.apiKey di .env).']);
        }

        $db    = \Config\Database::connect();
        $butir = $db->query("
            SELECT b.kode, b.judul, b.deskripsi, k.kode AS kriteria_kode, k.nama AS kriteria_nama
            FROM lam_butir b JOIN lam_kriteria k ON k.id = b.lam_kriteria_id
            WHERE b.id = ?
        ", [$butirId])->getRowArray();
        if (!$butir) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Butir tidak ditemukan.']);
        }

        $skor  = (float) ($this->request->getPost('skor') ?: 0);
        $rubrik = ($skor >= 1 && $skor <= 4)
            ? $db->table('lam_butir_rubrik')->where(['lam_butir_id' => $butirId, 'skor' => (int) round($skor)])->get()->getRowArray()
            : null;
        $prodiId = $this->getProdiId();
        $prodi   = $prodiId ? $db->table('prodi')->where('id', $prodiId)->get()->getRowArray() : null;

        $sys = 'Anda penyusun dokumen akreditasi LAM INFOKOM. Tulis "catatan/justifikasi penilaian" singkat '
             . '(2-4 kalimat) dalam bahasa Indonesia formal akademik yang menjelaskan dasar pemberian skor untuk satu butir. '
             . 'Faktual dan langsung ke inti, tanpa basa-basi dan tanpa menyebut diri sebagai AI.';
        $usr = 'Program studi: ' . ($prodi['nama'] ?? '-') . ".\n"
             . "Kriteria {$butir['kriteria_kode']} - {$butir['kriteria_nama']}.\n"
             . "Butir {$butir['kode']}: {$butir['judul']}.\n"
             . 'Deskripsi butir: ' . ($butir['deskripsi'] ?? '-') . "\n"
             . 'Skor yang diberikan: ' . ($skor >= 1 ? $skor . ' dari 4' : 'belum ditentukan') . ".\n"
             . ($rubrik ? "Rubrik skor {$rubrik['skor']} ({$rubrik['label']}): {$rubrik['deskriptor']}\n" : '')
             . "\nTuliskan catatan justifikasi yang konsisten dengan skor tersebut.";

        try {
            $text = $client->chat([
                ['role' => 'system', 'content' => $sys],
                ['role' => 'user', 'content' => $usr],
            ], false, 0.5);
            return $this->response->setJSON(['ok' => true, 'text' => trim($text)]);
        } catch (\Throwable $e) {
            log_message('error', 'Groq catatan error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Gagal menghubungi Groq AI.']);
        }
    }

    /** Unduh / tampilkan berkas bukti PDF. */
    public function bukti(string $name)
    {
        if (!auth()->user()->can('penilaian.input')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $name = basename($name);
        $path = WRITEPATH . 'bukti/' . $name;
        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berkas bukti tidak ditemukan.');
        }
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $name . '"')
            ->setBody(file_get_contents($path));
    }

    private function fmtBytes(int $b): string
    {
        if ($b >= 1048576) return number_format($b / 1048576, 1) . ' MB';
        if ($b >= 1024) return number_format($b / 1024, 0) . ' KB';
        return $b . ' B';
    }

    /** Jalankan auto-skor dari driver untuk semua butir. */
    public function runAuto()
    {
        if (!auth()->user()->can('penilaian.input')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $prodiId = $this->getProdiId();
        $taId    = $this->request->getPost('tahun_akademik_id') ?: null;
        $count   = $this->engine->runAutoScore($prodiId, $taId ? (int)$taId : null);

        return redirect()->to(site_url('penilaian') . ($taId ? '?ta=' . $taId : ''))
            ->with('success', "Auto-skor selesai: {$count} butir diperbarui.");
    }
}
