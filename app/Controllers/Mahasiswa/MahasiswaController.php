<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\TracerStudyModel;
use Config\Database;

class MahasiswaController extends BaseController
{
    protected MahasiswaModel $model;
    protected TracerStudyModel $tracerModel;

    public function __construct()
    {
        $this->model       = new MahasiswaModel();
        $this->tracerModel = new TracerStudyModel();
    }

    // ---------- INDEX ----------
    public function index(): string
    {
        $user        = auth()->user();
        $isSuperadmin = $user->inGroup('superadmin');
        $prodiId     = (int) session('active_prodi_id');

        $statusFilter  = $this->request->getGet('status') ?? '';
        $taFilter      = (int) ($this->request->getGet('ta_id') ?? 0);

        $filter = [];
        if ($statusFilter) $filter['status'] = $statusFilter;
        if ($taFilter)     $filter['tahun_masuk_id'] = $taFilter;

        $mahasiswa = $prodiId ? $this->model->listByProdi($prodiId, $filter) : [];
        $summary   = $prodiId ? $this->model->summaryByProdi($prodiId) : null;
        $belumTracer = $prodiId ? $this->tracerModel->lulusanBelumTracer($prodiId) : [];

        $db     = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();

        return view('mahasiswa/index', compact(
            'mahasiswa', 'summary', 'belumTracer', 'taList',
            'statusFilter', 'taFilter', 'prodiId', 'isSuperadmin'
        ));
    }

    // ---------- CREATE ----------
    public function create(): string
    {
        $db    = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $prodiList = $db->table('prodi')->where('status','aktif')->orderBy('nama')->get()->getResultArray();

        return view('mahasiswa/form', [
            'mhs'      => null,
            'taList'   => $taList,
            'prodiList'=> $prodiList,
            'action'   => site_url('mahasiswa/store'),
        ]);
    }

    public function store()
    {
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->collectPost();
        $this->model->insert($data);
        return redirect()->to(site_url('mahasiswa'))->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    // ---------- EDIT ----------
    public function edit(int $id): string
    {
        $mhs    = $this->model->findOrFail($id);
        $db     = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $prodiList = $db->table('prodi')->where('status','aktif')->orderBy('nama')->get()->getResultArray();

        return view('mahasiswa/form', [
            'mhs'      => $mhs,
            'taList'   => $taList,
            'prodiList'=> $prodiList,
            'action'   => site_url("mahasiswa/{$id}/update"),
        ]);
    }

    public function update(int $id)
    {
        $this->model->findOrFail($id);

        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->collectPost();
        $this->model->update($id, $data);
        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    // ---------- DELETE ----------
    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->update($id, ['status' => 'do']);
        return redirect()->to(site_url('mahasiswa'))->with('success', 'Status mahasiswa diubah ke DO.');
    }

    // ---------- SHOW ----------
    public function show(int $id): string
    {
        $mhs   = $this->model->findOrFail($id);
        $tracer = $this->tracerModel->getByMahasiswa($id);
        return view('mahasiswa/show', compact('mhs', 'tracer'));
    }

    // ---------- TRACER STUDY ----------
    public function tracerIndex(): string
    {
        $prodiId = (int) session('active_prodi_id');
        $tracer  = $prodiId ? $this->tracerModel->listByProdi($prodiId) : [];
        $summary = $prodiId ? $this->tracerModel->summaryByProdi($prodiId) : null;
        $belum   = $prodiId ? $this->tracerModel->lulusanBelumTracer($prodiId) : [];

        return view('mahasiswa/tracer/index', compact('tracer', 'summary', 'belum', 'prodiId'));
    }

    public function tracerForm(int $mahasiswaId): string
    {
        $mhs    = $this->model->findOrFail($mahasiswaId);
        $tracer = $this->tracerModel->getByMahasiswa($mahasiswaId) ?? [];
        return view('mahasiswa/tracer/form', compact('mhs', 'tracer'));
    }

    public function tracerStore(int $mahasiswaId)
    {
        $this->model->findOrFail($mahasiswaId);

        $data = $this->request->getPost([
            'tgl_isi','masa_tunggu_bulan','kesesuaian_bidang_kerja',
            'tingkat_pekerjaan','nama_perusahaan','jabatan','gaji_pertama',
        ]);
        $data['mahasiswa_id'] = $mahasiswaId;
        $data['lanjut_studi'] = $this->request->getPost('lanjut_studi') ? 1 : 0;
        $data['masa_tunggu_bulan'] = $data['masa_tunggu_bulan'] !== '' ? (int)$data['masa_tunggu_bulan'] : null;
        $data['gaji_pertama']      = $data['gaji_pertama'] !== '' ? (int)str_replace(['.', ','], '', $data['gaji_pertama']) : null;

        $existing = $this->tracerModel->getByMahasiswa($mahasiswaId);
        if ($existing) {
            $this->tracerModel->update($existing['id'], $data);
        } else {
            $this->tracerModel->insert($data);
        }

        return redirect()->to(site_url('mahasiswa/tracer'))->with('success', 'Data tracer study berhasil disimpan.');
    }

    // ---------- helpers ----------
    private function collectPost(): array
    {
        $data = $this->request->getPost([
            'prodi_id','nim','nama','jenis_kelamin','tempat_lahir','tanggal_lahir',
            'email','no_hp','kota_asal','provinsi_asal','negara_asal',
            'tahun_masuk_id','jalur_masuk','status','tgl_lulus','ipk','masa_studi_bulan',
        ]);
        foreach (['tgl_lulus','tanggal_lahir'] as $d) {
            if ($data[$d] === '') $data[$d] = null;
        }
        foreach (['tahun_masuk_id','masa_studi_bulan'] as $i) {
            if ($data[$i] === '') $data[$i] = null;
        }
        foreach (['ipk'] as $f) {
            if ($data[$f] === '') $data[$f] = null;
        }
        return $data;
    }
}
