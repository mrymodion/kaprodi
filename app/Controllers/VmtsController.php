<?php

namespace App\Controllers;

use App\Models\VmtsModel;
use Config\Database;

class VmtsController extends BaseController
{
    protected VmtsModel $model;

    public function __construct()
    {
        $this->model = new VmtsModel();
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
        $vmts    = $prodiId ? $this->model->aktifByProdi($prodiId) : null;
        return view('vmts/form', ['vmts' => $vmts, 'prodiId' => $prodiId]);
    }

    public function save()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu.');
        }

        $data = $this->request->getPost(['visi', 'misi', 'tujuan', 'strategi', 'no_sk', 'tgl_sk', 'berlaku_mulai', 'berlaku_sampai']);
        $data['prodi_id'] = $prodiId;
        $data['aktif']    = 1;
        foreach (['strategi', 'no_sk', 'tgl_sk', 'berlaku_mulai', 'berlaku_sampai'] as $f) {
            if (($data[$f] ?? '') === '') $data[$f] = null;
        }

        if (!$this->validateData($data, $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $existing = $this->model->aktifByProdi($prodiId);
        if ($existing) {
            $this->model->update($existing['id'], $data);
        } else {
            $this->model->insert($data);
        }
        return redirect()->to(site_url('vmts'))->with('success', 'VMTS berhasil disimpan.');
    }
}
