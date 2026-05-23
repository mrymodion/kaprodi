<?php

namespace App\Controllers\Tridharma;

use App\Controllers\BaseController;
use App\Models\PublikasiModel;
use App\Models\HkiModel;
use App\Models\ProdiModel;

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
        $data    = $this->request->getPost(['kategori','jenis','judul','no_hki','tahun','pemilik','status','link_bukti']);
        $data['prodi_id']   = $prodiId;
        if (!$this->validateData($data, $this->hkiModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
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
        $data = $this->request->getPost(['kategori','jenis','judul','no_hki','tahun','pemilik','status','link_bukti']);
        $data['prodi_id'] = (int) $item['prodi_id'];
        if (!$this->validateData($data, $this->hkiModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
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
