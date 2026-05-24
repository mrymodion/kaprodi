<?php

namespace App\Controllers\Tridharma;

use App\Controllers\BaseController;
use App\Models\PublikasiModel;
use App\Models\HkiModel;
use App\Models\ProdiModel;
use App\Models\DosenModel;
use App\Services\OpenAlexClient;

class PublikasiController extends BaseController
{
    protected PublikasiModel $model;
    protected HkiModel $hkiModel;

    public function __construct()
    {
        $this->model    = new PublikasiModel();
        $this->hkiModel = new HkiModel();
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

    // ---------- PUBLIKASI ----------

    public function index(): string
    {
        $prodiId  = $this->getProdiId();
        $kategori = $this->request->getGet('kategori') ?? '';
        $pubList  = $prodiId ? $this->model->listByProdi($prodiId, $kategori) : [];
        $hkiList  = $prodiId ? $this->hkiModel->listByProdi($prodiId, $kategori) : [];
        $pubSum   = $prodiId ? $this->model->summaryByProdi($prodiId) : [];
        $hkiSum   = $prodiId ? $this->hkiModel->summaryByProdi($prodiId) : [];

        return view('tridharma/publikasi/index', compact('pubList', 'hkiList', 'pubSum', 'hkiSum', 'prodiId', 'kategori'));
    }

    public function createPublikasi(): string
    {
        return view('tridharma/publikasi/form_pub', [
            'item'   => null,
            'action' => site_url('publikasi/store'),
            'jenis'  => PublikasiModel::JENIS_LABELS,
        ]);
    }

    public function storePublikasi()
    {
        $prodiId = $this->getProdiId();
        $data    = $this->request->getPost([
            'kategori','jenis','judul','penulis','nama_media','tahun','volume','doi','url','sitasi','sinta_score',
        ]);
        $data['prodi_id']   = $prodiId;
        if (!$this->validateData($data, $this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->model->insert($data);
        return redirect()->to(site_url('publikasi'))->with('success', 'Publikasi berhasil disimpan.');
    }

    public function editPublikasi(int $id): string
    {
        $item = $this->model->findOrFail($id);
        return view('tridharma/publikasi/form_pub', [
            'item'   => $item,
            'action' => site_url("publikasi/{$id}/update"),
            'jenis'  => PublikasiModel::JENIS_LABELS,
        ]);
    }

    public function updatePublikasi(int $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->request->getPost([
            'kategori','jenis','judul','penulis','nama_media','tahun','volume','doi','url','sitasi','sinta_score',
        ]);
        $data['prodi_id'] = (int) $item['prodi_id'];
        if (!$this->validateData($data, $this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('publikasi'))->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function deletePublikasi(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('publikasi'))->with('success', 'Publikasi berhasil dihapus.');
    }

    // ---------- CARI PUBLIKASI (OpenAlex) ----------

    public function cariPublikasi(): string
    {
        $prodiId = $this->getProdiId();
        $dosen   = $prodiId ? (new DosenModel())->listByProdi($prodiId) : [];
        return view('tridharma/publikasi/cari', [
            'dosen'   => $dosen,
            'prodiId' => $prodiId,
        ]);
    }

    public function searchPublikasi()
    {
        $orcid = (string) $this->request->getPost('orcid');
        $nama  = (string) $this->request->getPost('nama');

        $res = (new OpenAlexClient())->searchWorks($orcid, $nama);
        if (empty($res['ok'])) {
            return $this->response->setJSON(['ok' => false, 'error' => $res['error'] ?? 'Pencarian gagal.']);
        }

        // Tandai publikasi yang DOI-nya sudah ada untuk prodi ini agar tidak diimpor ganda.
        $prodiId = $this->getProdiId();
        $existing = [];
        if ($prodiId) {
            $rows = $this->model->select('doi')
                ->where('prodi_id', $prodiId)
                ->where('doi !=', '')
                ->findAll();
            foreach ($rows as $r) {
                if (!empty($r['doi'])) {
                    $existing[strtolower(trim($r['doi']))] = true;
                }
            }
        }
        foreach ($res['items'] as &$it) {
            $it['exists'] = $it['doi'] !== '' && isset($existing[strtolower($it['doi'])]);
        }
        unset($it);

        return $this->response->setJSON([
            'ok'       => true,
            'items'    => $res['items'],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash(),
        ]);
    }

    public function importPublikasi()
    {
        $prodiId = $this->getProdiId();
        if (!$prodiId) {
            return redirect()->to(site_url('publikasi'))->with('error', 'Prodi aktif belum dipilih.');
        }

        $rows = $this->request->getPost('rows');
        if (!is_array($rows) || $rows === []) {
            return redirect()->to(site_url('publikasi/cari'))->with('error', 'Tidak ada publikasi yang dipilih.');
        }

        $allowedJenis = array_keys(PublikasiModel::JENIS_LABELS);
        $now = date('Y-m-d H:i:s');
        $imported = 0; $skipped = 0;

        foreach ($rows as $r) {
            // Hanya impor baris yang dicentang (checkbox mengirim [sel]).
            if (empty($r['sel'])) { continue; }

            $judul = trim((string) ($r['judul'] ?? ''));
            if (strlen($judul) < 5) { $skipped++; continue; }

            $doi = trim((string) ($r['doi'] ?? ''));
            // Lewati bila DOI sudah ada untuk prodi ini.
            if ($doi !== '' && $this->model->where('prodi_id', $prodiId)->where('doi', $doi)->countAllResults() > 0) {
                $skipped++; continue;
            }

            $jenis = (string) ($r['jenis'] ?? '');
            if (!in_array($jenis, $allowedJenis, true)) {
                $jenis = 'jurnal_internasional';
            }

            $this->model->insert([
                'prodi_id'   => $prodiId,
                'kategori'   => 'penelitian',
                'jenis'      => $jenis,
                'judul'      => $judul,
                'penulis'    => trim((string) ($r['penulis'] ?? '')),
                'nama_media' => trim((string) ($r['nama_media'] ?? '')),
                'tahun'      => (int) ($r['tahun'] ?? 0) ?: null,
                'volume'     => trim((string) ($r['volume'] ?? '')) ?: null,
                'doi'        => $doi ?: null,
                'url'        => trim((string) ($r['url'] ?? '')) ?: null,
                'sitasi'     => (int) ($r['sitasi'] ?? 0),
                'created_at' => $now,
            ]);
            $imported++;
        }

        if ($imported === 0 && $skipped === 0) {
            return redirect()->to(site_url('publikasi/cari'))->with('error', 'Tidak ada publikasi yang dipilih.');
        }

        $msg = "{$imported} publikasi diimpor" . ($skipped ? ", {$skipped} dilewati (duplikat/tidak valid)" : '') . '. Harap verifikasi & lengkapi data.';
        return redirect()->to(site_url('publikasi'))->with('success', $msg);
    }

    // ---------- HKI ----------

    public function createHki(): string
    {
        return view('tridharma/publikasi/form_hki', [
            'item'   => null,
            'action' => site_url('publikasi/hki/store'),
            'jenis'  => HkiModel::JENIS_LABELS,
        ]);
    }

    public function storeHki()
    {
        $prodiId = $this->getProdiId();
        $data    = $this->request->getPost(['kategori','jenis','judul','no_hki','tahun','pemilik','status']);
        $data['prodi_id']   = $prodiId;
        if (!$this->validateData($data, $this->hkiModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $res = (new \App\Services\EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->hkiModel->insert($data);
        return redirect()->to(site_url('publikasi'))->with('success', 'HKI berhasil disimpan.');
    }

    public function editHki(int $id): string
    {
        $item = $this->hkiModel->findOrFail($id);
        return view('tridharma/publikasi/form_hki', [
            'item'   => $item,
            'action' => site_url("publikasi/hki/{$id}/update"),
            'jenis'  => HkiModel::JENIS_LABELS,
        ]);
    }

    public function updateHki(int $id)
    {
        $item = $this->hkiModel->findOrFail($id);
        $data = $this->request->getPost(['kategori','jenis','judul','no_hki','tahun','pemilik','status']);
        $data['prodi_id'] = (int) $item['prodi_id'];
        if (!$this->validateData($data, $this->hkiModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $res = (new \App\Services\EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $item['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $this->hkiModel->update($id, $data);
        return redirect()->to(site_url('publikasi'))->with('success', 'HKI berhasil diperbarui.');
    }

    public function deleteHki(int $id)
    {
        $this->hkiModel->findOrFail($id);
        $this->hkiModel->delete($id);
        return redirect()->to(site_url('publikasi'))->with('success', 'HKI berhasil dihapus.');
    }
}
