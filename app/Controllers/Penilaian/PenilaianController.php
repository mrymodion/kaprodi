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
        $skor     = $this->request->getPost('skor_manual') !== '' ? (float)$this->request->getPost('skor_manual') : null;
        $catatan  = $this->request->getPost('catatan');
        $linkBukti = trim((string)$this->request->getPost('link_bukti'));
        $userId   = (int)auth()->user()->id;

        if ($skor !== null && ($skor < 1 || $skor > 4)) {
            return redirect()->back()->with('error', 'Skor harus antara 1-4.');
        }

        if ($linkBukti !== '' && !filter_var($linkBukti, FILTER_VALIDATE_URL)) {
            return redirect()->back()->withInput()->with('error', 'Link bukti harus berupa URL yang valid (diawali http:// atau https://).');
        }

        $this->engine->saveSkorManual($prodiId, $butirId, $taId ? (int)$taId : null, $skor, $catatan, $userId, $linkBukti === '' ? null : $linkBukti);

        return redirect()->back()->with('success', 'Skor berhasil disimpan.');
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
