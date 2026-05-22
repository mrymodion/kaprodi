<?php

namespace App\Controllers;

use App\Models\KeuanganModel;
use Config\Database;

class KeuanganController extends BaseController
{
    protected KeuanganModel $model;

    public function __construct()
    {
        $this->model = new KeuanganModel();
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

    private function taList(): array
    {
        return Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
    }

    public function index(): string
    {
        $prodiId = $this->prodiId();
        $list    = $prodiId ? $this->model->listByProdi($prodiId) : [];
        return view('keuangan/index', ['list' => $list, 'prodiId' => $prodiId]);
    }

    public function create(): string
    {
        return view('keuangan/form', ['item' => null, 'action' => site_url('keuangan/store'), 'taList' => $this->taList()]);
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
        return redirect()->to(site_url('keuangan'))->with('success', 'Data keuangan ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('keuangan/form', ['item' => $this->model->findOrFail($id), 'action' => site_url("keuangan/{$id}/update"), 'taList' => $this->taList()]);
    }

    public function update(int $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->collect((int) $item['prodi_id']);
        if (!$this->validateData($data, $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('keuangan'))->with('success', 'Data keuangan diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('keuangan'))->with('success', 'Data keuangan dihapus.');
    }

    private function collect(int $prodiId): array
    {
        $req = $this->request;
        return [
            'prodi_id'          => $prodiId,
            'tahun_akademik_id' => (int) $req->getPost('tahun_akademik_id') ?: null,
            'arah'              => $req->getPost('arah'),
            'sumber_atau_pos'   => trim((string) $req->getPost('sumber_atau_pos')),
            'kategori'          => $req->getPost('kategori') ?: null,
            'jumlah'            => (int) preg_replace('/[^0-9]/', '', (string) $req->getPost('jumlah')),
            'keterangan'        => trim((string) $req->getPost('keterangan')) ?: null,
        ];
    }
}
