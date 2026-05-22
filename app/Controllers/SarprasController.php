<?php

namespace App\Controllers;

use App\Models\SarprasModel;
use Config\Database;

class SarprasController extends BaseController
{
    protected SarprasModel $model;

    public function __construct()
    {
        $this->model = new SarprasModel();
    }

    private function prodiId(): int
    {
        $pid = (int) session('active_prodi_id');
        if (!$pid && session('is_superadmin')) {
            $first = Database::connect()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        return $pid;
    }

    public function index(): string
    {
        $prodiId = $this->prodiId();
        $list    = $prodiId ? $this->model->listByProdi($prodiId) : [];
        return view('sarpras/index', ['list' => $list, 'prodiId' => $prodiId, 'kategori' => SarprasModel::KATEGORI]);
    }

    public function create(): string
    {
        return view('sarpras/form', ['item' => null, 'action' => site_url('sarpras/store'), 'kategori' => SarprasModel::KATEGORI]);
    }

    public function store()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu.');
        $data = $this->collect($prodiId);
        if (!$this->validateData($data, $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert($data);
        return redirect()->to(site_url('sarpras'))->with('success', 'Sarana/prasarana ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('sarpras/form', ['item' => $this->model->findOrFail($id), 'action' => site_url("sarpras/{$id}/update"), 'kategori' => SarprasModel::KATEGORI]);
    }

    public function update(int $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->collect((int) $item['prodi_id']);
        if (!$this->validateData($data, $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('sarpras'))->with('success', 'Sarana/prasarana diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('sarpras'))->with('success', 'Sarana/prasarana dihapus.');
    }

    private function collect(int $prodiId): array
    {
        $req = $this->request;
        return [
            'prodi_id'         => $prodiId,
            'kategori'         => $req->getPost('kategori'),
            'nama'             => trim((string) $req->getPost('nama')),
            'jumlah'           => (int) ($req->getPost('jumlah') ?: 1),
            'luas_m2'          => $req->getPost('luas_m2') !== '' ? (float) $req->getPost('luas_m2') : null,
            'kapasitas'        => $req->getPost('kapasitas') !== '' ? (int) $req->getPost('kapasitas') : null,
            'kondisi'          => $req->getPost('kondisi') ?: 'baik',
            'kepemilikan'      => $req->getPost('kepemilikan') ?: 'milik',
            'untuk_penelitian' => $req->getPost('untuk_penelitian') ? 1 : 0,
            'untuk_pkm'        => $req->getPost('untuk_pkm') ? 1 : 0,
            'untuk_pendidikan' => $req->getPost('untuk_pendidikan') ? 1 : 0,
            'keterangan'       => trim((string) $req->getPost('keterangan')) ?: null,
        ];
    }
}
