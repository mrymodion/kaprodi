<?php

namespace App\Controllers\Eviden;

use App\Controllers\BaseController;
use App\Models\EvidenPpeppModel;
use App\Models\EvidenPpeppBuktiModel;
use App\Models\SuratTugasMetaModel;
use App\Services\SuratValidator;
use Config\Database;

/**
 * Manajemen Eviden PPEPP per butir penilaian LAM, untuk persiapan
 * visitasi asesor. Sumber butir mengikuti instrumen LAM aktif prodi.
 */
class PpeppController extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    private function activeInstrumenIds(int $prodiId): array
    {
        $db = Database::connect();
        $rows = $db->table('prodi_instrumen')->select('lam_instrumen_id')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getResultArray();
        return array_map(fn($r) => (int) $r['lam_instrumen_id'], $rows);
    }

    // ───────────────────────── Daftar ─────────────────────────

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $db = Database::connect();
        $instrumenIds = $this->activeInstrumenIds($prodiId);

        // Ambil semua butir dari semua instrumen aktif prodi, lengkap dengan kriteria induknya.
        $rows = !empty($instrumenIds)
            ? $db->table('lam_butir b')
                ->select('b.id, b.kode AS butir_kode, b.judul, b.jenis_ipo, b.urutan AS b_urutan,
                          k.id AS kriteria_id, k.kode AS kriteria_kode, k.nama AS kriteria_nama, k.urutan AS k_urutan,
                          i.id AS instrumen_id, i.nama AS instrumen_nama')
                ->join('lam_kriteria k', 'k.id = b.lam_kriteria_id')
                ->join('lam_instrumen i', 'i.id = k.lam_instrumen_id')
                ->whereIn('i.id', $instrumenIds)
                ->orderBy('i.id ASC, k.urutan ASC, b.urutan ASC')
                ->get()->getResultArray()
            : [];

        // Peta progress PPEPP per butir.
        $progress = [];
        if (!empty($rows)) {
            $butirIds = array_column($rows, 'id');
            $eviden = $db->table('eviden_ppepp')
                ->whereIn('lam_butir_id', $butirIds)
                ->where('prodi_id', $prodiId)
                ->get()->getResultArray();
            $buktiCnt = [];
            if (!empty($eviden)) {
                $evIds = array_column($eviden, 'id');
                $cnt = $db->table('eviden_ppepp_bukti')->select('eviden_id, fase, COUNT(id) AS n')
                    ->whereIn('eviden_id', $evIds)->groupBy('eviden_id, fase')->get()->getResultArray();
                foreach ($cnt as $c) $buktiCnt[(int)$c['eviden_id']][$c['fase']] = (int) $c['n'];
            }
            foreach ($eviden as $e) {
                $byFase = array_fill_keys(EvidenPpeppModel::FASE, []);
                foreach ($buktiCnt[(int) $e['id']] ?? [] as $f => $n) if ($n > 0) $byFase[$f] = [1];
                $progress[(int) $e['lam_butir_id']] = EvidenPpeppModel::progress($e, $byFase);
            }
        }

        // Group per (instrumen → kriteria).
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['instrumen_id']]['nama'] = $r['instrumen_nama'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['kode'] = $r['kriteria_kode'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['nama'] = $r['kriteria_nama'];
            $grouped[$r['instrumen_id']]['kriteria'][$r['kriteria_id']]['butir'][] = $r;
        }

        return view('eviden/ppepp/index', [
            'grouped'     => $grouped,
            'progress'    => $progress,
            'visitasiUrl' => (new SuratValidator())->visitasiUrl($prodiId),
        ]);
    }

    // ───────────────────────── Edit per butir ─────────────────────────

    private function butirInScope(int $butirId): ?array
    {
        $prodiId = $this->prodiId();
        $instrumenIds = $this->activeInstrumenIds($prodiId);
        if (empty($instrumenIds)) return null;

        $db = Database::connect();
        $row = $db->table('lam_butir b')
            ->select('b.*, k.kode AS kriteria_kode, k.nama AS kriteria_nama, k.lam_instrumen_id, i.nama AS instrumen_nama')
            ->join('lam_kriteria k', 'k.id = b.lam_kriteria_id')
            ->join('lam_instrumen i', 'i.id = k.lam_instrumen_id')
            ->where('b.id', $butirId)
            ->whereIn('k.lam_instrumen_id', $instrumenIds)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function edit(int $butirId)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan / di luar instrumen aktif prodi.');

        $prodiId = $this->prodiId();
        $eviden = (new EvidenPpeppModel())->getOrCreate($prodiId, $butirId, (int) (auth()->id() ?? 0));
        $bukti = (new EvidenPpeppBuktiModel())->byEviden((int) $eviden['id']);

        return view('eviden/ppepp/edit', [
            'butir'  => $butir,
            'eviden' => $eviden,
            'bukti'  => $bukti,
        ]);
    }

    public function save(int $butirId)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan.');

        $data = [];
        foreach (EvidenPpeppModel::FASE as $f) {
            $data[$f] = trim((string) $this->request->getPost($f));
        }
        (new EvidenPpeppModel())->upsert($this->prodiId(), $butirId, $data, (int) (auth()->id() ?? 0));
        return redirect()->to(site_url("eviden/ppepp/{$butirId}/edit"))->with('success', 'Narasi PPEPP tersimpan.');
    }

    // ───────────────────────── Bukti per fase ─────────────────────────

    public function buktiAdd(int $butirId)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan.');

        $fase  = (string) $this->request->getPost('fase');
        $label = trim((string) $this->request->getPost('label'));
        $url   = trim((string) $this->request->getPost('url'));

        if (!in_array($fase, EvidenPpeppModel::FASE, true) || $label === '' || $url === '') {
            return redirect()->back()->with('error', 'Fase, label, dan URL wajib diisi.');
        }
        $eviden = (new EvidenPpeppModel())->getOrCreate($this->prodiId(), $butirId, (int) (auth()->id() ?? 0));
        (new EvidenPpeppBuktiModel())->tambah((int) $eviden['id'], $fase, $label, $url, 'manual');
        return redirect()->to(site_url("eviden/ppepp/{$butirId}/edit#fase-{$fase}"))->with('success', 'Bukti ditambahkan.');
    }

    public function buktiDelete(int $buktiId)
    {
        $db = Database::connect();
        $row = $db->table('eviden_ppepp_bukti b')
            ->select('b.id, b.eviden_id, e.prodi_id, e.lam_butir_id')
            ->join('eviden_ppepp e', 'e.id = b.eviden_id')
            ->where('b.id', $buktiId)->get()->getRowArray();
        if (!$row || (int) $row['prodi_id'] !== $this->prodiId()) {
            return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Bukti tidak ditemukan.');
        }
        (new EvidenPpeppBuktiModel())->delete($buktiId);
        return redirect()->to(site_url("eviden/ppepp/{$row['lam_butir_id']}/edit"))->with('success', 'Bukti dihapus.');
    }

    /** Tarik otomatis semua URL validasi Surat Tugas Mengajar prodi sebagai
     *  bukti Pelaksanaan. Cocok untuk butir terkait dosen/SDM/pendidikan. */
    public function autoTarikSurat(int $butirId)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan.');

        $prodiId = $this->prodiId();
        $db = Database::connect();

        $rows = $db->table('surat_tugas_meta stm')
            ->select('stm.dosen_id, stm.tahun_akademik_id, stm.nomor, d.nama, ta.kode AS ta_kode')
            ->join('dosen d', 'd.id = stm.dosen_id')
            ->join('tahun_akademik ta', 'ta.id = stm.tahun_akademik_id')
            ->where('d.prodi_id', $prodiId)
            ->where("(stm.nomor IS NOT NULL AND stm.nomor <> '')", null, false)
            ->orderBy('ta.tahun_mulai DESC, d.nama ASC')->get()->getResultArray();

        if (empty($rows)) {
            return redirect()->back()->with('error', 'Belum ada Surat Tugas yang diterbitkan untuk ditarik.');
        }

        $eviden = (new EvidenPpeppModel())->getOrCreate($prodiId, $butirId, (int) (auth()->id() ?? 0));
        $bModel = new EvidenPpeppBuktiModel();
        $sv = new SuratValidator();
        $added = 0;
        foreach ($rows as $r) {
            $url = $sv->url((int) $r['dosen_id'], (int) $r['tahun_akademik_id']);
            if ($bModel->exists((int) $eviden['id'], 'pelaksanaan', $url)) continue;
            $label = 'Surat Tugas ' . $r['nama'] . ' TA ' . $r['ta_kode'] . ' (No. ' . $r['nomor'] . ')';
            $bModel->tambah((int) $eviden['id'], 'pelaksanaan', $label, $url, 'surat_tugas');
            $added++;
        }
        $msg = $added > 0
            ? "$added bukti Surat Tugas ditambahkan ke kolom Pelaksanaan."
            : 'Semua Surat Tugas yang tersedia sudah ada di kolom Pelaksanaan.';
        return redirect()->to(site_url("eviden/ppepp/{$butirId}/edit#fase-pelaksanaan"))->with('success', $msg);
    }
}
