<?php

namespace App\Controllers\Tridharma;

use App\Controllers\BaseController;
use App\Models\KerjasamaModel;
use App\Models\ProdiModel;

class KerjasamaController extends BaseController
{
    protected KerjasamaModel $model;

    public function __construct()
    {
        $this->model = new KerjasamaModel();
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
        $prodiId = $this->getProdiId();
        $bidang  = $this->request->getGet('bidang') ?? '';
        $list    = $prodiId ? $this->model->listByProdi($prodiId, $bidang) : [];
        $summary = $prodiId ? $this->model->summaryByProdi($prodiId) : [];
        return view('tridharma/kerjasama/index', compact('list', 'summary', 'prodiId', 'bidang'));
    }

    public function create(): string
    {
        return view('tridharma/kerjasama/form', [
            'item'   => null,
            'action' => site_url('kerjasama/store'),
        ]);
    }

    public function store()
    {
        $prodiId = $this->getProdiId();
        $data    = $this->request->getPost([
            'nama_mitra','bidang','tingkat','jenis_dokumen','no_dokumen',
            'tgl_mulai','tgl_berakhir','manfaat','bukti_implementasi',
        ]);
        $data['prodi_id'] = $prodiId;
        if (!$this->validateData($data, $this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert($data);
        return redirect()->to(site_url('kerjasama'))->with('success', 'Kerjasama berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $item = $this->model->findOrFail($id);
        return view('tridharma/kerjasama/form', [
            'item'   => $item,
            'action' => site_url("kerjasama/{$id}/update"),
        ]);
    }

    public function update(int $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->request->getPost([
            'nama_mitra','bidang','tingkat','jenis_dokumen','no_dokumen',
            'tgl_mulai','tgl_berakhir','manfaat','bukti_implementasi',
        ]);
        $data['prodi_id'] = (int) $item['prodi_id'];
        if (!$this->validateData($data, $this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('kerjasama'))->with('success', 'Kerjasama berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('kerjasama'))->with('success', 'Kerjasama berhasil dihapus.');
    }
}
