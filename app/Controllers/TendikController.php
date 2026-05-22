<?php

namespace App\Controllers;

use App\Models\TendikModel;
use Config\Database;

class TendikController extends BaseController
{
    protected TendikModel $model;

    public function __construct()
    {
        $this->model = new TendikModel();
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
        return view('tendik/index', ['list' => $list, 'prodiId' => $prodiId, 'jenis' => TendikModel::JENIS]);
    }

    public function create(): string
    {
        return view('tendik/form', ['item' => null, 'action' => site_url('tendik/store'), 'jenis' => TendikModel::JENIS]);
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
        return redirect()->to(site_url('tendik'))->with('success', 'Tenaga kependidikan ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('tendik/form', ['item' => $this->model->findOrFail($id), 'action' => site_url("tendik/{$id}/update"), 'jenis' => TendikModel::JENIS]);
    }

    public function update(int $id)
    {
        $item = $this->model->findOrFail($id);
        $data = $this->collect((int) $item['prodi_id']);
        if (!$this->validateData($data, $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('tendik'))->with('success', 'Tenaga kependidikan diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('tendik'))->with('success', 'Tenaga kependidikan dihapus.');
    }

    private function collect(int $prodiId): array
    {
        $req = $this->request;
        return [
            'prodi_id'              => $prodiId,
            'nip'                   => trim((string) $req->getPost('nip')) ?: null,
            'nama'                  => trim((string) $req->getPost('nama')),
            'jenis'                 => $req->getPost('jenis'),
            'pendidikan_terakhir'   => $req->getPost('pendidikan_terakhir') ?: null,
            'sertifikat_kompetensi' => trim((string) $req->getPost('sertifikat_kompetensi')) ?: null,
            'aktif'                 => $req->getPost('aktif') ? 1 : 0,
        ];
    }
}
