<?php

namespace App\Controllers\Tridharma;

use App\Controllers\BaseController;
use App\Models\PkmModel;
use App\Models\DosenModel;
use App\Models\TahunAkademikModel;
use App\Models\ProdiModel;

class PkmController extends BaseController
{
    protected PkmModel $model;
    protected DosenModel $dosenModel;

    public function __construct()
    {
        $this->model      = new PkmModel();
        $this->dosenModel = new DosenModel();
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
        $filter  = ['tahun' => $this->request->getGet('tahun')];
        $list    = $prodiId ? $this->model->listByProdi($prodiId, array_filter($filter)) : [];
        $summary = $prodiId ? $this->model->summaryByProdi($prodiId) : [];
        return view('tridharma/pkm/index', compact('list', 'summary', 'prodiId', 'filter'));
    }

    public function create(): string
    {
        $prodiId = $this->getProdiId();
        $dosen   = $prodiId ? $this->dosenModel->listByProdi($prodiId) : [];
        $taList  = (new TahunAkademikModel())->findAll();
        return view('tridharma/pkm/form', [
            'item'    => null,
            'anggota' => [],
            'dosen'   => $dosen,
            'taList'  => $taList,
            'action'  => site_url('pkm/store'),
        ]);
    }

    public function store()
    {
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $prodiId = $this->getProdiId();
        $data = $this->request->getPost([
            'tahun_akademik_id','judul','ketua_dosen_id','sumber_dana','nama_sumber_dana',
            'jumlah_dana','mitra','lokasi','tahun_pelaksanaan',
            'libatkan_mahasiswa','jumlah_mhs_terlibat','dampak',
        ]);
        $data['prodi_id']           = $prodiId;
        $data['libatkan_mahasiswa'] = $data['libatkan_mahasiswa'] ? 1 : 0;
        $data['jumlah_dana']        = str_replace(['.', ','], ['', ''], $data['jumlah_dana'] ?? '0');

        $res = (new \App\Services\EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];

        $id = $this->model->insert($data);
        $anggota = $this->request->getPost('anggota') ?? [];
        if ($anggota) $this->model->saveAnggota((int)$id, $anggota);

        return redirect()->to(site_url('pkm'))->with('success', 'PkM berhasil disimpan.');
    }

    public function edit(int $id): string
    {
        $item    = $this->model->getWithAnggota($id);
        $prodiId = (int)$item['prodi_id'];
        $dosen   = $this->dosenModel->listByProdi($prodiId);
        $taList  = (new TahunAkademikModel())->findAll();
        return view('tridharma/pkm/form', [
            'item'    => $item,
            'anggota' => array_column($item['anggota'], 'dosen_id'),
            'dosen'   => $dosen,
            'taList'  => $taList,
            'action'  => site_url("pkm/{$id}/update"),
        ]);
    }

    public function update(int $id)
    {
        $item = $this->model->findOrFail($id);
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost([
            'tahun_akademik_id','judul','ketua_dosen_id','sumber_dana','nama_sumber_dana',
            'jumlah_dana','mitra','lokasi','tahun_pelaksanaan',
            'libatkan_mahasiswa','jumlah_mhs_terlibat','dampak',
        ]);
        $data['libatkan_mahasiswa'] = $data['libatkan_mahasiswa'] ? 1 : 0;
        $data['jumlah_dana']        = str_replace(['.', ','], ['', ''], $data['jumlah_dana'] ?? '0');

        $res = (new \App\Services\EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $item['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];

        $this->model->update($id, $data);
        $anggota = $this->request->getPost('anggota') ?? [];
        $this->model->saveAnggota($id, $anggota);

        return redirect()->to(site_url('pkm'))->with('success', 'PkM berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->delete($id);
        return redirect()->to(site_url('pkm'))->with('success', 'PkM berhasil dihapus.');
    }
}
