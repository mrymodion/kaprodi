<?php

namespace App\Controllers\Kurikulum;

use App\Controllers\BaseController;
use App\Controllers\Concerns\ExcelImport;
use App\Models\{KurikulumModel, CplModel, MataKuliahModel, MkCplModel, CplKetercapaianModel};
use Config\Database;

class KurikulumController extends BaseController
{
    use ExcelImport;

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
        $isSuperadmin = (bool) session('is_superadmin');
        $prodiId      = (int) session('active_prodi_id');

        if ($isSuperadmin) {
            // Superadmin: tampilkan semua kurikulum dari semua prodi
            $db        = Database::connect();
            $kurikulum = $db->query("
                SELECT k.*,
                    p.nama AS nama_prodi,
                    (SELECT COUNT(*) FROM cpl WHERE kurikulum_id = k.id) AS jml_cpl,
                    (SELECT COUNT(*) FROM mata_kuliah WHERE kurikulum_id = k.id) AS jml_mk
                FROM kurikulum k
                JOIN prodi p ON p.id = k.prodi_id
                ORDER BY p.nama, k.tahun_berlaku DESC
            ")->getResultArray();
        } else {
            $kurikulum = $prodiId ? $this->kuriModel->listByProdi($prodiId) : [];
        }

        return view('kurikulum/index', compact('kurikulum', 'prodiId', 'isSuperadmin'));
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
        $id = $this->kuriModel->insert($data, true);
        return redirect()->to(site_url("kurikulum/{$id}"))->with('success', 'Kurikulum berhasil ditambahkan. Mulai tambahkan CPL dan Mata Kuliah.');
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

    // ══════════════════════════════════════════════════════
    // IMPORT EXCEL — CPL
    // ══════════════════════════════════════════════════════

    public function cplImportForm(int $kuriId): string
    {
        return view('kurikulum/cpl_import', ['kuri' => $this->kuriModel->findOrFail($kuriId)]);
    }

    public function cplImportTemplate(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        $contoh = ['CPL-01', 'pengetahuan', 'Mampu menerapkan konsep teoritis bidang informatika.'];
        $petunjuk = [
            ['Kolom wajib', 'kode, aspek, deskripsi'],
            ['aspek', 'sikap | pengetahuan | keterampilan_umum | keterampilan_khusus'],
            ['kode', 'unik per kurikulum; jika sudah ada akan diperbarui'],
        ];
        return $this->excelTemplate('Template_Import_CPL.xlsx', ['kode', 'aspek', 'deskripsi'], $contoh, $petunjuk);
    }

    public function cplImportStore(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        $load = $this->readUpload();
        if (is_string($load)) return redirect()->back()->with('error', $load);
        [$sheet, $colOf, $highestRow] = $load;
        if (!isset($colOf['kode']) || !isset($colOf['deskripsi'])) {
            return redirect()->back()->with('error', 'Header "kode" dan "deskripsi" wajib ada. Gunakan template.');
        }

        $aspekValid = ['sikap', 'pengetahuan', 'keterampilan_umum', 'keterampilan_khusus'];
        $inserted = 0; $updated = 0; $errors = []; $warnings = [];

        for ($r = 2; $r <= $highestRow; $r++) {
            $val  = fn(string $k) => isset($colOf[$k]) ? trim((string) $sheet->getCell([$colOf[$k], $r])->getValue()) : '';
            $kode = $val('kode');
            $desk = $val('deskripsi');
            if ($kode === '' && $desk === '') continue;
            if ($kode === '' || $desk === '') { $errors[] = "Baris {$r}: kode dan deskripsi wajib."; continue; }

            $aspek = strtolower(str_replace([' ', '-'], '_', $val('aspek')));
            if (!in_array($aspek, $aspekValid, true)) {
                $errors[] = "Baris {$r}: aspek \"{$val('aspek')}\" tidak valid (sikap/pengetahuan/keterampilan_umum/keterampilan_khusus)."; continue;
            }

            $data = ['kurikulum_id' => $kuriId, 'kode' => $kode, 'aspek' => $aspek, 'deskripsi' => $desk];
            $existing = $this->cplModel->where(['kurikulum_id' => $kuriId, 'kode' => $kode])->first();
            if ($existing) {
                $this->cplModel->update($existing['id'], $data) ? $updated++ : $errors[] = "Baris {$r}: " . implode('; ', $this->cplModel->errors());
            } else {
                $this->cplModel->insert($data, false) ? $inserted++ : $errors[] = "Baris {$r}: " . implode('; ', $this->cplModel->errors());
            }
        }

        return redirect()->to(site_url("kurikulum/{$kuriId}/cpl/import"))->with('importResult', compact('inserted', 'updated', 'errors', 'warnings'));
    }

    // ══════════════════════════════════════════════════════
    // IMPORT EXCEL — MATA KULIAH
    // ══════════════════════════════════════════════════════

    public function mkImportForm(int $kuriId): string
    {
        return view('kurikulum/mk_import', ['kuri' => $this->kuriModel->findOrFail($kuriId)]);
    }

    public function mkImportTemplate(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        $cols = ['kode', 'nama', 'sks_teori', 'sks_praktek', 'sks_lapangan', 'semester', 'jenis', 'cpl'];
        $contoh = ['IF101', 'Algoritma & Pemrograman', '2', '1', '0', '1', 'wajib', 'CPL-01; CPL-03'];
        $petunjuk = [
            ['Kolom wajib', 'kode, nama, semester'],
            ['jenis', 'wajib | pilihan | mbkm (kosong = wajib)'],
            ['sks_teori/praktek/lapangan', 'angka SKS (kosong = 0)'],
            ['cpl', 'daftar kode CPL yang didukung, dipisah ; atau , (mis. CPL-01; CPL-03)'],
            ['kode', 'unik per kurikulum; jika sudah ada akan diperbarui'],
        ];
        return $this->excelTemplate('Template_Import_MataKuliah.xlsx', $cols, $contoh, $petunjuk);
    }

    public function mkImportStore(int $kuriId)
    {
        $this->kuriModel->findOrFail($kuriId);
        $load = $this->readUpload();
        if (is_string($load)) return redirect()->back()->with('error', $load);
        [$sheet, $colOf, $highestRow] = $load;
        if (!isset($colOf['kode']) || !isset($colOf['nama'])) {
            return redirect()->back()->with('error', 'Header "kode" dan "nama" wajib ada. Gunakan template.');
        }

        // Peta kode CPL -> id untuk kurikulum ini
        $cplMap = [];
        foreach ($this->cplModel->where('kurikulum_id', $kuriId)->findAll() as $c) {
            $cplMap[strtolower(trim($c['kode']))] = (int) $c['id'];
        }

        $jenisValid = ['wajib', 'pilihan', 'mbkm'];
        $inserted = 0; $updated = 0; $errors = []; $warnings = [];

        for ($r = 2; $r <= $highestRow; $r++) {
            $val  = fn(string $k) => isset($colOf[$k]) ? trim((string) $sheet->getCell([$colOf[$k], $r])->getValue()) : '';
            $kode = $val('kode');
            $nama = $val('nama');
            if ($kode === '' && $nama === '') continue;
            if ($kode === '' || $nama === '') { $errors[] = "Baris {$r}: kode dan nama wajib."; continue; }

            $sem = $val('semester');
            if ($sem === '' || !is_numeric($sem) || (int) $sem < 1) { $errors[] = "Baris {$r}: semester wajib angka >= 1."; continue; }

            $jenis = strtolower($val('jenis')) ?: 'wajib';
            if (!in_array($jenis, $jenisValid, true)) $jenis = 'wajib';

            $intval = fn(string $k) => is_numeric($val($k)) ? (int) $val($k) : 0;
            $data = [
                'kurikulum_id' => $kuriId,
                'kode'         => $kode,
                'nama'         => $nama,
                'sks_teori'    => $intval('sks_teori'),
                'sks_praktek'  => $intval('sks_praktek'),
                'sks_lapangan' => $intval('sks_lapangan'),
                'semester'     => (int) $sem,
                'jenis'        => $jenis,
            ];

            $existing = $this->mkModel->where(['kurikulum_id' => $kuriId, 'kode' => $kode])->first();
            if ($existing) {
                if ($this->mkModel->update($existing['id'], $data)) { $mkId = (int) $existing['id']; $updated++; }
                else { $errors[] = "Baris {$r}: " . implode('; ', $this->mkModel->errors()); continue; }
            } else {
                $mkId = $this->mkModel->insert($data, true);
                if (!$mkId) { $errors[] = "Baris {$r}: " . implode('; ', $this->mkModel->errors()); continue; }
                $inserted++;
            }

            // Set peta CPL (ganti yang lama)
            $cplCol = $val('cpl');
            if ($cplCol !== '') {
                $this->mkCplModel->where('mata_kuliah_id', $mkId)->delete();
                foreach (preg_split('/[;,]/', $cplCol) as $kk) {
                    $kk = strtolower(trim($kk));
                    if ($kk === '') continue;
                    if (isset($cplMap[$kk])) {
                        $this->mkCplModel->insert(['mata_kuliah_id' => $mkId, 'cpl_id' => $cplMap[$kk], 'bobot' => 1.00]);
                    } else {
                        $warnings[] = "Baris {$r}: CPL \"{$kk}\" tidak ditemukan di kurikulum ini, dilewati.";
                    }
                }
            }
        }

        return redirect()->to(site_url("kurikulum/{$kuriId}/mk/import"))->with('importResult', compact('inserted', 'updated', 'errors', 'warnings'));
    }
}
