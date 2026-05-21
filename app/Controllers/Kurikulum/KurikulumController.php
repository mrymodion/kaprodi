<?php

namespace App\Controllers\Kurikulum;

use App\Controllers\BaseController;
use App\Models\{KurikulumModel, CplModel, MataKuliahModel, MkCplModel, CplKetercapaianModel};
use Config\Database;

class KurikulumController extends BaseController
{
    protected KurikulumModel       $kuriModel;
    protected CplModel             $cplModel;
    protected MataKuliahModel      $mkModel;
    protected MkCplModel           $mkCplModel;
    protected CplKetercapaianModel $ketModel;

    public function __construct()
    {
        $this->kuriModel = new KurikulumModel();
        $this->cplModel  = new CplModel();
        $this->mkModel   = new MataKuliahModel();
        $this->mkCplModel= new MkCplModel();
        $this->ketModel  = new CplKetercapaianModel();
    }

    // ══════════════════════════════════════════════════════
    // KURIKULUM
    // ══════════════════════════════════════════════════════

    public function index(): string
    {
        $prodiId    = (int) session('active_prodi_id');
        $kurikulum  = $prodiId ? $this->kuriModel->listByProdi($prodiId) : [];
        return view('kurikulum/index', compact('kurikulum', 'prodiId'));
    }

    public function create(): string
    {
        $prodiList = $this->getProdiList();
        return view('kurikulum/form', [
            'kuri'     => null,
            'prodiList'=> $prodiList,
            'action'   => site_url('kurikulum/store'),
        ]);
    }

    public function store()
    {
        if (!$this->validate($this->kuriModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['prodi_id','kode','nama','tahun_berlaku','aktif']);
        $data['aktif'] = $data['aktif'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->kuriModel->insert($data);
        return redirect()->to(site_url('kurikulum'))->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function show(int $id): string
    {
        $kuri   = $this->kuriModel->findOrFail($id);
        $cpl    = $this->cplModel->byKurikulum($id);
        $mk     = $this->mkModel->withCplCount($id);
        $totSks = $this->mkModel->totalSksKurikulum($id);

        // peta matrix: mkId => [cplId => true]
        $petaRaw = $this->mkCplModel->petaByKurikulum($id);
        $peta    = [];
        foreach ($petaRaw as $p) {
            $peta[$p['mata_kuliah_id']][$p['cpl_id']] = true;
        }

        // Ketercapaian per TA
        $db     = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $taId   = (int)($this->request->getGet('ta_id') ?: ($db->table('tahun_akademik')->where('is_ts',1)->get()->getRowArray()['id'] ?? 0));
        $avgCpl = $taId ? $this->ketModel->avgKetercapaianPerCpl($id, $taId) : [];

        return view('kurikulum/show', compact('kuri','cpl','mk','totSks','peta','taList','taId','avgCpl'));
    }

    public function edit(int $id): string
    {
        $kuri      = $this->kuriModel->findOrFail($id);
        $prodiList = $this->getProdiList();
        return view('kurikulum/form', [
            'kuri'     => $kuri,
            'prodiList'=> $prodiList,
            'action'   => site_url("kurikulum/{$id}/update"),
        ]);
    }

    public function update(int $id)
    {
        $this->kuriModel->findOrFail($id);
        if (!$this->validate($this->kuriModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['prodi_id','kode','nama','tahun_berlaku','aktif']);
        $data['aktif'] = $data['aktif'] ?? 0;
        $this->kuriModel->update($id, $data);
        return redirect()->to(site_url("kurikulum/{$id}"))->with('success', 'Kurikulum diperbarui.');
    }

    public function delete(int $id)
    {
        $this->kuriModel->findOrFail($id);
        $this->kuriModel->update($id, ['aktif' => 0]);
        return redirect()->to(site_url('kurikulum'))->with('success', 'Kurikulum dinonaktifkan.');
    }

    // ══════════════════════════════════════════════════════
    // CPL
    // ══════════════════════════════════════════════════════

    public function cplCreate(int $kuriId): string
    {
        $kuri = $this->kuriModel->findOrFail($kuriId);
        return view('kurikulum/cpl_form', [
            'kuri'   => $kuri,
            'cpl'    => null,
            'action' => site_url("kurikulum/{$kuriId}/cpl/store"),
        ]);
    }

    public function cplStore(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        if (!$this->validate($this->cplModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['kode','aspek','deskripsi']);
        $data['kurikulum_id'] = $kuriId;
        $this->cplModel->insert($data);
        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-cpl"))->with('success', 'CPL berhasil ditambahkan.');
    }

    public function cplEdit(int $kuriId, int $cplId): string
    {
        $kuri = $this->kuriModel->findOrFail($kuriId);
        $cpl  = $this->cplModel->findOrFail($cplId);
        return view('kurikulum/cpl_form', [
            'kuri'   => $kuri,
            'cpl'    => $cpl,
            'action' => site_url("kurikulum/{$kuriId}/cpl/{$cplId}/update"),
        ]);
    }

    public function cplUpdate(int $kuriId, int $cplId)
    {
        $this->cplModel->findOrFail($cplId);
        if (!$this->validate($this->cplModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['kode','aspek','deskripsi']);
        $data['kurikulum_id'] = $kuriId;
        $this->cplModel->update($cplId, $data);
        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-cpl"))->with('success', 'CPL diperbarui.');
    }

    public function cplDelete(int $kuriId, int $cplId)
    {
        $this->cplModel->findOrFail($cplId);
        $this->cplModel->delete($cplId);
        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-cpl"))->with('success', 'CPL dihapus.');
    }

    // ══════════════════════════════════════════════════════
    // MATA KULIAH
    // ══════════════════════════════════════════════════════

    public function mkCreate(int $kuriId): string
    {
        $kuri = $this->kuriModel->findOrFail($kuriId);
        $cpl  = $this->cplModel->byKurikulum($kuriId);
        return view('kurikulum/mk_form', [
            'kuri'  => $kuri,
            'mk'    => null,
            'cpl'   => $cpl,
            'checked'=>[],
            'action'=> site_url("kurikulum/{$kuriId}/mk/store"),
        ]);
    }

    public function mkStore(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        if (!$this->validate($this->mkModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->collectMkPost($kuriId);
        $mkId = $this->mkModel->insert($data, true);

        // Simpan peta CPL
        $cplIds = $this->request->getPost('cpl_ids') ?? [];
        foreach ($cplIds as $cplId) {
            $this->mkCplModel->insert(['mata_kuliah_id'=>$mkId,'cpl_id'=>(int)$cplId,'bobot'=>1.00]);
        }

        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-mk"))->with('success', 'Mata Kuliah ditambahkan.');
    }

    public function mkEdit(int $kuriId, int $mkId): string
    {
        $kuri    = $this->kuriModel->findOrFail($kuriId);
        $mk      = $this->mkModel->findOrFail($mkId);
        $cpl     = $this->cplModel->byKurikulum($kuriId);
        $checked = array_column($this->mkCplModel->cplByMk($mkId), 'id');

        return view('kurikulum/mk_form', [
            'kuri'   => $kuri,
            'mk'     => $mk,
            'cpl'    => $cpl,
            'checked'=> $checked,
            'action' => site_url("kurikulum/{$kuriId}/mk/{$mkId}/update"),
        ]);
    }

    public function mkUpdate(int $kuriId, int $mkId)
    {
        $this->mkModel->findOrFail($mkId);
        if (!$this->validate($this->mkModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->collectMkPost($kuriId);
        $this->mkModel->update($mkId, $data);

        // Update peta CPL: hapus lama, insert baru
        $this->mkCplModel->where('mata_kuliah_id', $mkId)->delete();
        $cplIds = $this->request->getPost('cpl_ids') ?? [];
        foreach ($cplIds as $cplId) {
            $this->mkCplModel->insert(['mata_kuliah_id'=>$mkId,'cpl_id'=>(int)$cplId,'bobot'=>1.00]);
        }

        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-mk"))->with('success', 'Mata Kuliah diperbarui.');
    }

    public function mkDelete(int $kuriId, int $mkId)
    {
        $this->mkModel->findOrFail($mkId);
        $this->mkModel->delete($mkId);
        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-mk"))->with('success', 'Mata Kuliah dihapus.');
    }

    // ══════════════════════════════════════════════════════
    // PETA CPL (matrix simpan bulk)
    // ══════════════════════════════════════════════════════

    public function petaSave(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);

        // peta[mkId][cplId] dikirim sebagai checkbox peta[mkId][] = cplId
        $raw = $this->request->getPost('peta') ?? [];
        $checked = [];
        foreach ($raw as $mkId => $cplIds) {
            $checked[(int)$mkId] = array_map('intval', (array)$cplIds);
        }
        $this->mkCplModel->savePeta($kuriId, $checked);

        return redirect()->to(site_url("kurikulum/{$kuriId}#tab-peta"))->with('success', 'Peta CPL-MK disimpan.');
    }

    // ══════════════════════════════════════════════════════
    // KETERCAPAIAN CPL
    // ══════════════════════════════════════════════════════

    public function ketercapaianForm(int $kuriId): string
    {
        $kuri   = $this->kuriModel->findOrFail($kuriId);
        $db     = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        $taId   = (int)($this->request->getGet('ta_id') ?: ($db->table('tahun_akademik')->where('is_ts',1)->get()->getRowArray()['id'] ?? 0));

        // Ambil semua MK yang punya CPL di kurikulum ini
        $db2 = Database::connect();
        $rows = $db2->query("
            SELECT mk.id AS mk_id, mk.kode AS mk_kode, mk.nama AS mk_nama, mk.semester,
                   c.id AS cpl_id, c.kode AS cpl_kode,
                   ck.persen_tercapai, ck.jumlah_mhs
            FROM mata_kuliah mk
            JOIN mk_cpl mc ON mc.mata_kuliah_id = mk.id
            JOIN cpl c ON c.id = mc.cpl_id
            LEFT JOIN cpl_ketercapaian ck ON ck.mata_kuliah_id = mk.id AND ck.cpl_id = c.id AND ck.tahun_akademik_id = ?
            WHERE mk.kurikulum_id = ?
            ORDER BY mk.semester, mk.kode, c.kode
        ", [$taId, $kuriId])->getResultArray();

        return view('kurikulum/ketercapaian_form', compact('kuri','rows','taList','taId'));
    }

    public function ketercapaianSave(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        $taId = (int)$this->request->getPost('tahun_akademik_id');

        // data[mkId_cplId] = ['pct'=>..,'mhs'=>..]
        $data = $this->request->getPost('ket') ?? [];
        foreach ($data as $key => $val) {
            [$mkId, $cplId] = explode('_', $key);
            $pct = isset($val['pct']) && $val['pct'] !== '' ? (float)$val['pct'] : null;
            $mhs = isset($val['mhs']) && $val['mhs'] !== '' ? (int)$val['mhs'] : 0;
            if ($pct !== null) {
                $this->ketModel->upsert((int)$mkId, (int)$cplId, $taId, $pct, $mhs);
            }
        }

        return redirect()->to(site_url("kurikulum/{$kuriId}?ta_id={$taId}#tab-ket"))->with('success', 'Ketercapaian CPL disimpan.');
    }

    // ══════════════════════════════════════════════════════
    // helpers
    // ══════════════════════════════════════════════════════

    private function getProdiList(): array
    {
        return Database::connect()->table('prodi')->where('status','aktif')->orderBy('nama')->get()->getResultArray();
    }

    private function collectMkPost(int $kuriId): array
    {
        $data = $this->request->getPost(['kode','nama','sks_teori','sks_praktek','sks_lapangan','semester','jenis','rps_path']);
        $data['kurikulum_id'] = $kuriId;
        foreach (['sks_teori','sks_praktek','sks_lapangan'] as $f) {
            $data[$f] = (int)($data[$f] ?? 0);
        }
        return $data;
    }
}
