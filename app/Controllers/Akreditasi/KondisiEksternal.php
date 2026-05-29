<?php

namespace App\Controllers\Akreditasi;

use App\Controllers\BaseController;
use App\Models\KondisiEksternalModel;

/**
 * Sprint 2 Kriteria A: input terstruktur Kondisi Eksternal.
 * Output dipakai sebagai sumber narasi LED (Sprint 3 AI assist) dan
 * sumber eviden otomatis untuk PPEPP (via EvidenAutoTarik).
 */
class KondisiEksternal extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $m = new KondisiEksternalModel();
        return view('akreditasi/kondisi_eksternal/index', [
            'grouped' => $m->byProdiGrouped($prodiId),
            'jumlah'  => $m->countByProdi($prodiId),
        ]);
    }

    public function form(?int $id = null)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $row = null;
        if ($id) {
            $row = (new KondisiEksternalModel())->find($id);
            if (!$row || (int) $row['prodi_id'] !== $prodiId) {
                return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('error', 'Entri tidak ditemukan.');
            }
        }

        return view('akreditasi/kondisi_eksternal/form', [
            'row'      => $row,
            'kategoriDefault' => $this->request->getGet('kategori'),
            'mode'     => $row ? 'edit' : 'create',
        ]);
    }

    public function store()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $data = $this->collectInput($prodiId);
        if (!$data) return redirect()->back()->withInput()->with('error', 'Field wajib belum lengkap.');

        $id = (int) $this->request->getPost('id');
        $m = new KondisiEksternalModel();
        if ($id) {
            $existing = $m->find($id);
            if (!$existing || (int) $existing['prodi_id'] !== $prodiId) {
                return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('error', 'Entri tidak ditemukan.');
            }
            $m->update($id, $data);
        } else {
            $m->insert($data);
        }
        return redirect()->to(site_url('akreditasi/kondisi-eksternal') . '?kategori=' . $data['kategori'])
            ->with('success', $id ? 'Entri diperbarui.' : 'Entri ditambahkan.');
    }

    public function delete(int $id)
    {
        $prodiId = $this->prodiId();
        $m = new KondisiEksternalModel();
        $row = $m->find($id);
        if ($row && (int) $row['prodi_id'] === $prodiId) $m->delete($id);
        return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('success', 'Entri dihapus.');
    }

    /** AJAX: AI sarankan N entri untuk kategori tertentu (tidak otomatis simpan). */
    public function saranAi(string $kategori)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return $this->response->setJSON(['ok' => false, 'error' => 'Prodi aktif belum dipilih.']);
        try {
            $items = (new \App\Services\KondisiEksternalSuggester())->suggest($prodiId, $kategori, 5);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
        return $this->response->setJSON([
            'ok'        => true,
            'kategori'  => $kategori,
            'items'     => $items,
            'csrfName'  => csrf_token(),
            'csrfHash'  => csrf_hash(),
        ]);
    }

    /** Bulk insert dari saran AI yang sudah di-review prodi. */
    public function saranAiSimpan()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $kategori = (string) $this->request->getPost('kategori');
        if (!array_key_exists($kategori, KondisiEksternalModel::KATEGORI)) {
            return redirect()->back()->with('error', 'Kategori tidak dikenali.');
        }
        $items = (array) $this->request->getPost('items');
        if (empty($items)) return redirect()->back()->with('error', 'Tidak ada entri terpilih.');

        $m = new KondisiEksternalModel();
        $count = 0;
        foreach ($items as $it) {
            if (empty($it['selected']) || empty($it['judul']) || empty($it['deskripsi'])) continue;
            $dampak = in_array($it['dampak'] ?? '', ['rendah','sedang','tinggi'], true) ? $it['dampak'] : 'sedang';
            $m->insert([
                'prodi_id'         => $prodiId,
                'kategori'         => $kategori,
                'judul'            => mb_substr(trim((string) $it['judul']), 0, 250),
                'deskripsi'        => trim((string) $it['deskripsi']),
                'dampak'           => $dampak,
                'respons_prodi'    => trim((string) ($it['respons_prodi'] ?? '')) ?: null,
                'sumber_referensi' => trim((string) ($it['sumber_referensi'] ?? '')) ?: null,
            ]);
            $count++;
        }
        return redirect()->to(site_url('akreditasi/kondisi-eksternal') . '?kategori=' . $kategori)
            ->with('success', "$count entri ditambahkan dari saran AI. Mohon periksa dan lengkapi bila perlu.");
    }

    public function narasi()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $grouped = (new KondisiEksternalModel())->byProdiGrouped($prodiId);
        $narasiSaved = (new \App\Models\KondisiEksternalNarasiModel())->byProdi($prodiId);
        $gen = new \App\Services\KondisiEksternalNarasiGenerator();

        return view('akreditasi/kondisi_eksternal/narasi', [
            'grouped'      => $grouped,
            'narasiSaved'  => $narasiSaved,
            'subBagian'    => \App\Services\KondisiEksternalNarasiGenerator::SUB_BAGIAN,
            'aiReady'      => $gen->isAiReady(),
        ]);
    }

    /** AJAX: generate draft narasi via AI untuk satu sub-bagian. */
    public function narasiAiDraft(string $sub)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return $this->response->setJSON(['ok' => false, 'error' => 'Prodi aktif belum dipilih.']);

        try {
            $out = (new \App\Services\KondisiEksternalNarasiGenerator())->draft($prodiId, $sub);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(200)->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
        return $this->response->setJSON([
            'ok'           => true,
            'sub'          => $sub,
            'narasi'       => $out['narasi'],
            'source_count' => $out['source_count'],
        ]);
    }

    /** Simpan narasi (hasil edit manual atau hasil AI yang sudah di-review). */
    public function narasiSave(string $sub)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');
        if (!isset(\App\Services\KondisiEksternalNarasiGenerator::SUB_BAGIAN[$sub])) {
            return redirect()->back()->with('error', 'Sub-bagian tidak dikenali.');
        }
        $narasi = trim((string) $this->request->getPost('narasi'));
        if ($narasi === '') return redirect()->back()->with('error', 'Narasi kosong.');

        $generated = (bool) $this->request->getPost('generated');
        (new \App\Models\KondisiEksternalNarasiModel())->upsert(
            $prodiId, $sub, $narasi,
            (int) $this->request->getPost('source_count'),
            $generated,
            (int) (auth()->id() ?? 0)
        );
        return redirect()->to(site_url('akreditasi/kondisi-eksternal/narasi') . '#sub-' . $sub)
            ->with('success', "Narasi {$sub} tersimpan.");
    }

    /** Sprint 4: halaman evaluasi mutu (heuristik). AI Judge dipicu via tombol/AJAX. */
    public function mutu()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $eval = new \App\Services\KondisiEksternalEvaluator();
        return view('akreditasi/kondisi_eksternal/mutu', [
            'hasil'    => $eval->evaluate($prodiId, false),
            'meta'     => \App\Services\KondisiEksternalEvaluator::META,
            'bobot'    => \App\Services\KondisiEksternalEvaluator::BOBOT,
            'aiDimensi'=> \App\Services\KondisiEksternalEvaluator::AI_DIMENSI,
            'aiReady'  => $eval->isAiReady(),
        ]);
    }

    /** AJAX: re-evaluasi dengan AI Judge mengaktifkan 3 dimensi kualitatif. */
    public function mutuAi()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return $this->response->setJSON(['ok' => false, 'error' => 'Prodi aktif belum dipilih.']);

        try {
            $hasil = (new \App\Services\KondisiEksternalEvaluator())->evaluate($prodiId, true);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['ok' => false, 'error' => $e->getMessage()]);
        }
        return $this->response->setJSON(['ok' => true, 'hasil' => $hasil]);
    }

    private function collectInput(int $prodiId): ?array
    {
        $kategori = (string) $this->request->getPost('kategori');
        if (!array_key_exists($kategori, KondisiEksternalModel::KATEGORI)) return null;

        $judul = trim((string) $this->request->getPost('judul'));
        $desk  = trim((string) $this->request->getPost('deskripsi'));
        if ($judul === '' || $desk === '') return null;

        $dampak = (string) $this->request->getPost('dampak');
        if (!array_key_exists($dampak, KondisiEksternalModel::DAMPAK_LABELS)) $dampak = 'sedang';

        $tahun = (int) $this->request->getPost('tahun_relevansi');

        return [
            'prodi_id'         => $prodiId,
            'kategori'         => $kategori,
            'judul'            => $judul,
            'deskripsi'        => $desk,
            'dampak'           => $dampak,
            'respons_prodi'    => trim((string) $this->request->getPost('respons_prodi')) ?: null,
            'sumber_referensi' => trim((string) $this->request->getPost('sumber_referensi')) ?: null,
            'link_bukti'       => trim((string) $this->request->getPost('link_bukti')) ?: null,
            'tahun_relevansi'  => ($tahun >= 2000 && $tahun <= 2100) ? $tahun : null,
            'urutan'           => (int) $this->request->getPost('urutan'),
        ];
    }
}
