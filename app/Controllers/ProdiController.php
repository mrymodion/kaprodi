<?php

namespace App\Controllers;

use App\Models\ProdiModel;
use Config\Database;

class ProdiController extends BaseController
{
    protected ProdiModel $model;

    public function __construct()
    {
        $this->model = new ProdiModel();
    }

    private const FIELDS = [
        'upps_id','kode_pddikti','nama','jenjang','gelar','singkatan_gelar',
        'akreditasi_saat_ini','no_sk_akreditasi','tgl_sk_akreditasi','tgl_kadaluarsa','status',
    ];

    private function uppsList(): array
    {
        return Database::connect()->table('upps u')
            ->select('u.id, u.nama AS upps_nama, pt.nama AS pt_nama')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->orderBy('pt.nama', 'ASC')
            ->get()->getResultArray();
    }

    public function index(): string
    {
        return view('prodi/index', ['list' => $this->model->listWithUpps()]);
    }

    public function create(): string
    {
        return view('prodi/form', [
            'item'      => null,
            'action'    => site_url('prodi/store'),
            'uppsList'  => $this->uppsList(),
        ]);
    }

    public function store()
    {
        $data = $this->cleanData($this->request->getPost(self::FIELDS));
        $rules = $this->model->getValidationRules();
        $rules['kode_pddikti'] = 'required|max_length[20]|is_unique[prodi.kode_pddikti]';
        if (!$this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert($data);
        return redirect()->to(site_url('prodi'))->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('prodi/form', [
            'item'      => $this->model->findOrFail($id),
            'action'    => site_url("prodi/{$id}/update"),
            'uppsList'  => $this->uppsList(),
        ]);
    }

    public function update(int $id)
    {
        $this->model->findOrFail($id);
        $data = $this->cleanData($this->request->getPost(self::FIELDS));
        $rules = $this->model->getValidationRules();
        $rules['kode_pddikti'] = "required|max_length[20]|is_unique[prodi.kode_pddikti,id,{$id}]";
        if (!$this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, $data);
        return redirect()->to(site_url('prodi'))->with('success', 'Prodi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('prodi'))->with('success', 'Prodi berhasil dihapus.');
    }

    /** Ubah string kosong pada field tanggal/nullable menjadi null. */
    private function cleanData(array $data): array
    {
        foreach (['gelar','singkatan_gelar','akreditasi_saat_ini','no_sk_akreditasi','tgl_sk_akreditasi','tgl_kadaluarsa'] as $f) {
            if (isset($data[$f]) && $data[$f] === '') {
                $data[$f] = null;
            }
        }
        return $data;
    }
}
