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
            ->select('b.id, b.kode AS butir_kode, b.judul, b.deskripsi, b.jenis_ipo, b.tabel_lkps_ref, b.lam_kriteria_id,
                      k.kode AS kriteria_kode, k.nama AS kriteria_nama, k.lam_instrumen_id,
                      i.nama AS instrumen_nama')
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

    /** Tarik otomatis bukti dari sumber sistem (surat_tugas, publikasi, dll). */
    public function autoTarik(int $butirId, string $source)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan.');

        $svc = new \App\Services\EvidenAutoTarik();
        if (!isset($svc->sources()[$source])) {
            return redirect()->back()->with('error', 'Sumber eviden tidak dikenali.');
        }
        $items = $svc->collect($source, $this->prodiId());
        if (empty($items)) {
            $sourceLabel = $svc->sources()[$source]['label'];
            return redirect()->back()->with('error', "Tidak ada data {$sourceLabel} dengan URL bukti yang dapat ditarik.");
        }

        $eviden = (new EvidenPpeppModel())->getOrCreate($this->prodiId(), $butirId, (int) (auth()->id() ?? 0));
        $bModel = new EvidenPpeppBuktiModel();
        $added = 0;
        $faseUtama = $items[0]['fase'] ?? 'pelaksanaan';
        foreach ($items as $it) {
            if ($bModel->exists((int) $eviden['id'], $it['fase'], $it['url'])) continue;
            $bModel->tambah((int) $eviden['id'], $it['fase'], $it['label'], $it['url'], $it['sumber']);
            $added++;
        }
        $msg = $added > 0
            ? "$added bukti dari {$svc->sources()[$source]['label']} ditambahkan."
            : 'Semua bukti dari sumber tersebut sudah ada.';
        return redirect()->to(site_url("eviden/ppepp/{$butirId}/edit#fase-{$faseUtama}"))->with('success', $msg);
    }

    /** Tarik dari semua sumber yang RELEVAN dengan butir (berdasarkan
     *  pencocokan tabel_lkps_ref/kriteria/keyword) — sekali klik. */
    public function autoTarikSemua(int $butirId)
    {
        $butir = $this->butirInScope($butirId);
        if (!$butir) return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Butir tidak ditemukan.');

        $svc = new \App\Services\EvidenAutoTarik();
        $relevant = $svc->relevantFor($butir);
        if (empty($relevant)) {
            return redirect()->back()->with('error', 'Tidak ada sumber sistem yang relevan dengan butir ini. Tambahkan bukti secara manual atau pilih sumber spesifik bila perlu.');
        }

        $eviden = (new EvidenPpeppModel())->getOrCreate($this->prodiId(), $butirId, (int) (auth()->id() ?? 0));
        $bModel = new EvidenPpeppBuktiModel();

        $totalAdded = 0;
        $sourcesUsed = [];
        foreach ($relevant as $source) {
            $items = $svc->collect($source, $this->prodiId());
            $sAdded = 0;
            foreach ($items as $it) {
                if ($bModel->exists((int) $eviden['id'], $it['fase'], $it['url'])) continue;
                $bModel->tambah((int) $eviden['id'], $it['fase'], $it['label'], $it['url'], $it['sumber']);
                $sAdded++;
            }
            if ($sAdded > 0) { $totalAdded += $sAdded; $sourcesUsed[] = $svc->sources()[$source]['label'] . " ($sAdded)"; }
        }
        $msg = $totalAdded > 0
            ? "Total $totalAdded bukti ditambahkan dari: " . implode(', ', $sourcesUsed) . '.'
            : 'Tidak ada bukti baru untuk ditarik — semua sudah ada atau belum ada data dengan URL.';
        return redirect()->to(site_url("eviden/ppepp/{$butirId}/edit"))->with('success', $msg);
    }

    /** Tarik bukti otomatis untuk SELURUH butir aktif prodi sekaligus.
     *  Optimasi: koleksi sumber & set bukti existing di-cache di memori
     *  sehingga tidak ada N×M query. */
    public function autoTarikSeluruh()
    {
        $prodiId = $this->prodiId();
        $instrumenIds = $this->activeInstrumenIds($prodiId);
        if (empty($instrumenIds)) {
            return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Belum ada instrumen LAM aktif untuk prodi.');
        }

        $db = Database::connect();
        $butirRows = $db->table('lam_butir b')
            ->select('b.id, b.kode AS butir_kode, b.judul, b.deskripsi, b.tabel_lkps_ref,
                      k.kode AS kriteria_kode, k.nama AS kriteria_nama')
            ->join('lam_kriteria k', 'k.id = b.lam_kriteria_id')
            ->whereIn('k.lam_instrumen_id', $instrumenIds)
            ->get()->getResultArray();
        if (empty($butirRows)) {
            return redirect()->to(site_url('eviden/ppepp'))->with('error', 'Tidak ada butir penilaian pada instrumen aktif.');
        }

        $svc = new \App\Services\EvidenAutoTarik();

        // 1) Pra-koleksi data semua sumber sekali jalan (per prodi).
        $sourceCache = [];
        foreach (array_keys($svc->sources()) as $src) {
            $sourceCache[$src] = $svc->collect($src, $prodiId);
        }

        // 2) Pra-fetch index bukti existing prodi → cek duplikat tanpa DB hit.
        $existing = []; // [eviden_id][fase][url] = true
        $existingRows = $db->table('eviden_ppepp_bukti b')
            ->select('b.eviden_id, b.fase, b.url')
            ->join('eviden_ppepp e', 'e.id = b.eviden_id')
            ->where('e.prodi_id', $prodiId)->get()->getResultArray();
        foreach ($existingRows as $er) {
            $existing[(int) $er['eviden_id']][$er['fase']][$er['url']] = true;
        }

        $eModel = new \App\Models\EvidenPpeppModel();
        $bModel = new \App\Models\EvidenPpeppBuktiModel();
        $uid = (int) (auth()->id() ?? 0);

        $totalAdded     = 0;
        $butirTouched   = 0;
        $sumberRingkasan = []; // sumber => jumlah
        foreach ($butirRows as $b) {
            $relevant = $svc->relevantFor($b);
            if (empty($relevant)) continue;

            $eviden = $eModel->getOrCreate($prodiId, (int) $b['id'], $uid);
            $evId = (int) $eviden['id'];
            $perButirAdded = 0;

            foreach ($relevant as $src) {
                foreach ($sourceCache[$src] ?? [] as $it) {
                    if (isset($existing[$evId][$it['fase']][$it['url']])) continue;
                    $bModel->tambah($evId, $it['fase'], $it['label'], $it['url'], $it['sumber']);
                    $existing[$evId][$it['fase']][$it['url']] = true;
                    $totalAdded++;
                    $perButirAdded++;
                    $sumberRingkasan[$src] = ($sumberRingkasan[$src] ?? 0) + 1;
                }
            }
            if ($perButirAdded > 0) $butirTouched++;
        }

        if ($totalAdded === 0) {
            return redirect()->to(site_url('eviden/ppepp'))->with('success', 'Tidak ada bukti baru — semua sumber relevan sudah tertaut atau belum ada data dengan URL.');
        }

        $labels = $svc->sources();
        $parts = [];
        foreach ($sumberRingkasan as $src => $n) $parts[] = $labels[$src]['label'] . " ($n)";
        $msg = "Selesai: $totalAdded bukti baru ditambahkan ke $butirTouched butir &mdash; dari " . implode(', ', $parts) . '.';
        return redirect()->to(site_url('eviden/ppepp'))->with('success', $msg);
    }
}
