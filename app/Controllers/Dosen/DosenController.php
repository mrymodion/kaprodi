<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\DosenEwmpModel;
use Config\Database;

class DosenController extends BaseController
{
    protected DosenModel $model;
    protected DosenEwmpModel $ewmpModel;

    public function __construct()
    {
        $this->model     = new DosenModel();
        $this->ewmpModel = new DosenEwmpModel();
    }

    // ---------- INDEX ----------
    public function index(): string
    {
        $user        = auth()->user();
        $isSuperadmin = $user->inGroup('superadmin');
        $prodiId     = session('active_prodi_id');

        $dosen   = $prodiId ? $this->model->listByProdi((int) $prodiId) : [];
        $summary = $prodiId ? $this->model->summaryForProdi((int) $prodiId) : null;

        return view('dosen/index', compact('dosen', 'summary', 'prodiId', 'isSuperadmin'));
    }

    // ---------- CREATE ----------
    public function create(): string
    {
        $prodiList = $this->getProdiList();
        return view('dosen/form', [
            'dosen'     => null,
            'prodiList' => $prodiList,
            'action'    => site_url('dosen/store'),
        ]);
    }

    public function store()
    {
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost([
            'prodi_id','nidn','nip','nama','gelar_depan','gelar_belakang',
            'jenis_kelamin','tempat_lahir','tanggal_lahir','email','no_hp',
            'pendidikan_terakhir','bidang_keahlian','linieritas','jabatan_akademik',
            'no_sk_jad','tgl_sk_jad','sertifikat_pendidik','no_serdos',
            'status_kepegawaian','sinta_id','scopus_id','google_scholar_id','orcid',
        ]);
        $data['aktif'] = 1;
        $data['sertifikat_pendidik'] = isset($data['sertifikat_pendidik']) ? 1 : 0;

        $this->model->insert($data);
        return redirect()->to(site_url('dosen'))->with('success', 'Dosen berhasil ditambahkan.');
    }

    // ---------- EDIT ----------
    public function edit(int $id): string
    {
        $dosen = $this->model->findOrFail($id);
        $prodiList = $this->getProdiList();
        return view('dosen/form', [
            'dosen'     => $dosen,
            'prodiList' => $prodiList,
            'action'    => site_url("dosen/{$id}/update"),
        ]);
    }

    public function update(int $id)
    {
        $dosen = $this->model->findOrFail($id);

        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost([
            'prodi_id','nidn','nip','nama','gelar_depan','gelar_belakang',
            'jenis_kelamin','tempat_lahir','tanggal_lahir','email','no_hp',
            'pendidikan_terakhir','bidang_keahlian','linieritas','jabatan_akademik',
            'no_sk_jad','tgl_sk_jad','sertifikat_pendidik','no_serdos',
            'status_kepegawaian','sinta_id','scopus_id','google_scholar_id','orcid','aktif',
        ]);
        $data['sertifikat_pendidik'] = isset($data['sertifikat_pendidik']) ? 1 : 0;
        $data['aktif'] = $data['aktif'] ?? 1;

        $this->model->update($id, $data);
        return redirect()->to(site_url('dosen'))->with('success', 'Data dosen berhasil diperbarui.');
    }

    // ---------- DELETE ----------
    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->update($id, ['aktif' => 0]);
        return redirect()->to(site_url('dosen'))->with('success', 'Dosen dinonaktifkan.');
    }

    // ---------- SHOW ----------
    public function show(int $id): string
    {
        $dosen    = $this->model->findOrFail($id);
        $db       = Database::connect();
        $ewmpList = $db->table('dosen_ewmp e')
            ->select('e.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = e.tahun_akademik_id')
            ->where('e.dosen_id', $id)
            ->orderBy('ta.tahun_mulai DESC')
            ->get()->getResultArray();

        return view('dosen/show', compact('dosen', 'ewmpList'));
    }

    // ---------- EWMP ----------
    public function ewmpIndex(): string
    {
        $db      = Database::connect();
        $prodiId = (int) session('active_prodi_id');

        // Superadmin tanpa prodi aktif: pakai prodi pertama yang ada
        if (!$prodiId && session('is_superadmin')) {
            $firstProdi = $db->table('prodi')->where('status','aktif')->orderBy('nama')->get()->getRowArray();
            $prodiId    = $firstProdi ? (int)$firstProdi['id'] : 0;
        }

        $taList  = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $taId    = (int) ($this->request->getGet('ta_id') ?: ($db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? 0));

        $ewmp    = $taId ? $this->ewmpModel->listByProdi($prodiId, $taId) : [];

        // Dosen yang belum punya EWMP di TA ini
        $dosenIds = array_column($ewmp, 'dosen_id');
        $belum = $prodiId
            ? $db->table('dosen')
                ->where(['prodi_id' => $prodiId, 'aktif' => 1])
                ->whereNotIn('id', $dosenIds ?: [0])
                ->get()->getResultArray()
            : [];

        return view('dosen/ewmp/index', compact('ewmp', 'belum', 'taList', 'taId', 'prodiId'));
    }

    public function ewmpForm(int $dosenId): string
    {
        $db    = Database::connect();
        $dosen = $this->model->findOrFail($dosenId);
        $taId  = (int) ($this->request->getGet('ta_id') ?: ($db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? 0));
        $taList= $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $ewmp  = $taId ? ($this->ewmpModel->getByDosenTa($dosenId, $taId) ?? []) : [];

        return view('dosen/ewmp/form', compact('dosen', 'ewmp', 'taList', 'taId'));
    }

    public function ewmpStore(int $dosenId)
    {
        $this->model->findOrFail($dosenId);
        $taId = (int) $this->request->getPost('tahun_akademik_id');

        if (!$taId) {
            return redirect()->back()->with('error', 'Pilih tahun akademik.');
        }

        $fields = ['sks_pendidikan_ps','sks_pendidikan_ps_lain_pt','sks_pendidikan_pt_lain',
                   'sks_penelitian','sks_pkm','sks_tugas_tambahan'];
        $data   = [];
        foreach ($fields as $f) {
            $data[$f] = (float) ($this->request->getPost($f) ?? 0);
        }

        $this->ewmpModel->upsert($dosenId, $taId, $data);

        $taGet = $this->request->getGet('ta_id') ?? $taId;
        return redirect()->to(site_url("dosen/ewmp?ta_id={$taGet}"))
            ->with('success', 'EWMP berhasil disimpan.');
    }

    // ---------- helpers ----------
    private function getProdiList(): array
    {
        $db = Database::connect();
        return $db->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getResultArray();
    }
}
