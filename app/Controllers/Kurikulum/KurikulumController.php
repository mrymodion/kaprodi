<?php

namespace App\Controllers\Kurikulum;

use App\Controllers\BaseController;
use App\Controllers\Concerns\ExcelImport;
use Config\Database;

/**
 * Controller Manajemen Kurikulum OBE.
 * Mensuplai data untuk LKPS Kriteria C2 (Tabel 2.B.1, 2.B.2, 2.B.3).
 */
class KurikulumController extends BaseController
{
    // Menggunakan trait pembantu untuk import Excel
    use ExcelImport;

    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $db = Database::connect();
        $kurikulum = $db->table('kurikulum k')
            ->select('k.*, 
                (SELECT COUNT(id) FROM cpl WHERE kurikulum_id = k.id) as jml_cpl,
                (SELECT COUNT(id) FROM mata_kuliah WHERE kurikulum_id = k.id) as jml_mk
            ')
            ->where('k.prodi_id', $prodiId)
            ->orderBy('k.tahun_berlaku', 'DESC')
            ->get()->getResultArray();

        return view('kurikulum/index', [
            'kurikulum' => $kurikulum
        ]);
    }

    public function create()
    {
        $db = Database::connect();
        return view('kurikulum/form', [
            'kuri'      => null,
            'prodiList' => $db->table('prodi')->orderBy('jenjang')->orderBy('nama')->get()->getResultArray(),
            'action'    => site_url('kurikulum/store')
        ]);
    }

    public function store()
    {
        $db = Database::connect();
        $post = $this->request->getPost();
        
        $db->table('kurikulum')->insert([
            'prodi_id'      => $post['prodi_id'],
            'kode'          => $post['kode'],
            'nama'          => $post['nama'],
            'tahun_berlaku' => $post['tahun_berlaku'],
            'aktif'         => $post['aktif'] ?? 1,
        ]);

        return redirect()->to('kurikulum')->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Kurikulum tidak ditemukan atau akses ditolak.');

        return view('kurikulum/form', [
            'kuri'      => $kuri,
            'prodiList' => $db->table('prodi')->orderBy('jenjang')->orderBy('nama')->get()->getResultArray(),
            'action'    => site_url("kurikulum/{$id}/update")
        ]);
    }

    public function update(int $id)
    {
        $db = Database::connect();
        $post = $this->request->getPost();
        
        $db->table('kurikulum')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->update([
            'prodi_id'      => $post['prodi_id'],
            'kode'          => $post['kode'],
            'nama'          => $post['nama'],
            'tahun_berlaku' => $post['tahun_berlaku'],
            'aktif'         => $post['aktif'] ?? 1,
        ]);

        return redirect()->to('kurikulum')->with('success', 'Kurikulum berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        Database::connect()->table('kurikulum')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to('kurikulum')->with('success', 'Kurikulum berhasil dihapus.');
    }

    /**
     * Menampilkan Detail Kurikulum beserta Matriks Peta CPL - MK.
     */
    public function show(int $id)
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        $kurikulum = $db->table('kurikulum')->where(['id' => $id, 'prodi_id' => $prodiId])->get()->getRowArray();
        if (!$kurikulum) return redirect()->to('kurikulum')->with('error', 'Data kurikulum tidak ditemukan.');

        $cpl = $db->table('cpl')->where('kurikulum_id', $id)->orderBy('kode', 'ASC')->get()->getResultArray();
        $mk  = $db->table('mata_kuliah')
            ->select('mata_kuliah.*, (sks_teori + sks_praktek + sks_lapangan) AS sks')
            ->where('kurikulum_id', $id)->orderBy('semester', 'ASC')->orderBy('kode', 'ASC')->get()->getResultArray();

        // Mengambil pemetaan CPL - MK saat ini
        $peta = [];
        if (!empty($mk)) {
            $mkIds = array_column($mk, 'id');
            $petaRows = $db->table('mk_cpl')->whereIn('mata_kuliah_id', $mkIds)->get()->getResultArray();
            foreach ($petaRows as $p) {
                $peta[$p['mata_kuliah_id']][$p['cpl_id']] = true;
            }
        }

        return view('kurikulum/show', [
            'kurikulum' => $kurikulum,
            'cpl'       => $cpl,
            'mk'        => $mk,
            'peta'      => $peta
        ]);
    }

    /**
     * Menyimpan hasil pemetaan (Matriks) CPL - Mata Kuliah secara massal.
     */
    public function petaSave(int $kurikulumId)
    {
        $db = Database::connect();
        $prodiId = $this->prodiId();

        $kurikulum = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $prodiId])->get()->getRowArray();
        if (!$kurikulum) return redirect()->back()->with('error', 'Akses ditolak.');

        $petaInput = $this->request->getPost('peta') ?? []; // Format struktur form: peta[mk_id][cpl_id] = 1

        $mkRows = $db->table('mata_kuliah')->where('kurikulum_id', $kurikulumId)->get()->getResultArray();
        $mkIds  = array_column($mkRows, 'id');

        if (!empty($mkIds)) {
            $db->transStart();
            $db->table('mk_cpl')->whereIn('mata_kuliah_id', $mkIds)->delete(); // Bersihkan pemetaan lama
            $insertData = [];
            foreach ($petaInput as $mkId => $cplData) foreach ($cplData as $cplId => $val) if ($val == 1) $insertData[] = ['mata_kuliah_id' => $mkId, 'cpl_id' => $cplId];
            if (!empty($insertData)) $db->table('mk_cpl')->insertBatch($insertData);
            $db->transComplete();
        }

        return redirect()->to(site_url("kurikulum/{$kurikulumId}"))->with('success', 'Peta CPL - Mata Kuliah berhasil diperbarui.');
    }

    /**
     * Handle AJAX request untuk Auto-Save pemetaan CPL ke Mata Kuliah
     * Dipanggil dari UI Matriks CPL-MK
     */
    public function togglePeta()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'message' => 'Invalid request']);
        }

        $mkId   = $this->request->getPost('mata_kuliah_id');
        $cplId  = $this->request->getPost('cpl_id');
        $status = $this->request->getPost('status'); // 1 = dicentang, 0 = di-uncheck

        if (!$mkId || !$cplId) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'message' => 'Data tidak lengkap']);
        }

        $db = Database::connect();

        try {
            if ($status == 1) {
                // Cek eksistensi untuk mencegah duplikasi
                $exists = $db->table('mk_cpl')->where(['mata_kuliah_id' => $mkId, 'cpl_id' => $cplId])->countAllResults();
                if ($exists === 0) $db->table('mk_cpl')->insert(['mata_kuliah_id' => $mkId, 'cpl_id' => $cplId]);
            } else {
                $db->table('mk_cpl')->where(['mata_kuliah_id' => $mkId, 'cpl_id' => $cplId])->delete();
            }

            return $this->response->setJSON(['ok' => true, 'message' => 'Pemetaan berhasil disimpan']);
        } catch (\Exception $e) {
            log_message('error', '[KurikulumController::togglePeta] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'message' => 'Gagal menyimpan ke database']);
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // Fitur Import CPL dari Excel
    // ────────────────────────────────────────────────────────────────────────

    public function cplImportForm(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        return view('kurikulum/cpl_import', ['kuri' => $kuri]);
    }

    public function cplImportTemplate(int $kurikulumId)
    {
        $this->excelTemplate(
            "Template_Import_CPL.xlsx",
            ['Kode CPL', 'Aspek', 'Deskripsi'],
            ['CPL-01', 'sikap', 'Menunjukkan sikap bertanggungjawab atas pekerjaan di bidang keahliannya secara mandiri.'],
            [
                ['Kolom Kode CPL', 'Wajib, mis. CPL-01, S-01, P-01.'],
                ['Kolom Aspek', 'Wajib, pilih salah satu: sikap, pengetahuan, keterampilan_umum, keterampilan_khusus.'],
                ['Kolom Deskripsi', 'Wajib diisi dengan narasi utuh CPL.']
            ]
        );
    }

    public function cplImportStore(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        $upload = $this->readUpload(['kode_cpl' => 'kode']);
        if (is_string($upload)) return redirect()->back()->with('error', $upload);
        [$sheet, $colOf, $highestRow] = $upload;

        if (empty($colOf['kode']) || empty($colOf['aspek']) || empty($colOf['deskripsi'])) {
            return redirect()->back()->with('error', 'Format kolom tidak sesuai. Gunakan template yang disediakan.');
        }

        $inserted = 0; $updated = 0; $errors = [];
        $db->transStart();

        for ($r = 2; $r <= $highestRow; $r++) {
            $kode  = trim((string) $sheet->getCell([$colOf['kode'], $r])->getValue());
            $aspek = strtolower(trim((string) $sheet->getCell([$colOf['aspek'], $r])->getValue()));
            $desk  = trim((string) $sheet->getCell([$colOf['deskripsi'], $r])->getValue());

            if ($kode === '') continue; // Skip baris kosong

            if (!in_array($aspek, ['sikap', 'pengetahuan', 'keterampilan_umum', 'keterampilan_khusus'])) {
                $errors[] = "Baris $r: Aspek '$aspek' tidak valid untuk CPL $kode.";
                continue;
            }

            if ($desk === '') {
                $errors[] = "Baris $r: Deskripsi CPL $kode tidak boleh kosong.";
                continue;
            }

            $existing = $db->table('cpl')->where(['kurikulum_id' => $kurikulumId, 'kode' => $kode])->get()->getRowArray();
            $data = ['kurikulum_id' => $kurikulumId, 'kode' => $kode, 'aspek' => $aspek, 'deskripsi' => $desk];

            if ($existing) {
                $db->table('cpl')->where('id', $existing['id'])->update($data);
                $updated++;
            } else {
                $db->table('cpl')->insert($data);
                $inserted++;
            }
        }

        $db->transComplete();
        session()->setFlashdata('importResult', ['inserted' => $inserted, 'updated' => $updated, 'errors' => $errors]);
        return redirect()->to(site_url("kurikulum/{$kurikulumId}/cpl/import"));
    }

    public function cplCreate(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        return view('kurikulum/cpl_form', [
            'kuri'   => $kuri,
            'cpl'    => null,
            'action' => site_url("kurikulum/{$kurikulumId}/cpl/store")
        ]);
    }

    public function cplStore(int $kurikulumId)
    {
        $post = $this->request->getPost();
        Database::connect()->table('cpl')->insert([
            'kurikulum_id' => $kurikulumId,
            'kode'         => $post['kode'],
            'aspek'        => $post['aspek'],
            'deskripsi'    => $post['deskripsi'],
        ]);
        return redirect()->to("kurikulum/{$kurikulumId}#tab-cpl")->with('success', 'CPL berhasil ditambahkan.');
    }

    public function cplEdit(int $kurikulumId, int $cplId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        $cpl  = $db->table('cpl')->where('id', $cplId)->get()->getRowArray();

        if (!$kuri || !$cpl) return redirect()->to("kurikulum/{$kurikulumId}")->with('error', 'Data tidak ditemukan.');

        return view('kurikulum/cpl_form', [
            'kuri'   => $kuri,
            'cpl'    => $cpl,
            'action' => site_url("kurikulum/{$kurikulumId}/cpl/{$cplId}/update")
        ]);
    }

    public function cplUpdate(int $kurikulumId, int $cplId)
    {
        $post = $this->request->getPost();
        Database::connect()->table('cpl')->where('id', $cplId)->update([
            'kode'      => $post['kode'],
            'aspek'     => $post['aspek'],
            'deskripsi' => $post['deskripsi'],
        ]);
        return redirect()->to("kurikulum/{$kurikulumId}#tab-cpl")->with('success', 'CPL berhasil diperbarui.');
    }

    public function cplDelete(int $kurikulumId, int $cplId)
    {
        Database::connect()->table('cpl')->where('id', $cplId)->delete();
        return redirect()->to("kurikulum/{$kurikulumId}#tab-cpl")->with('success', 'CPL berhasil dihapus.');
    }

    // ────────────────────────────────────────────────────────────────────────
    // Fitur Import Mata Kuliah (MK) dari Excel
    // ────────────────────────────────────────────────────────────────────────

    public function mkImportForm(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        return view('kurikulum/mk_import', ['kuri' => $kuri]);
    }

    public function mkImportTemplate(int $kurikulumId)
    {
        $this->excelTemplate(
            "Template_Import_MK.xlsx",
            ['Kode MK', 'Nama Mata Kuliah', 'Semester', 'SKS Teori', 'SKS Praktik', 'SKS Lapangan', 'Jenis', 'CPL'],
            ['MI301', 'Algoritma dan Struktur Data', '1', '2', '1', '0', 'wajib', 'CPL-01; CPL-02'],
            [
                ['Kolom Kode & Nama', 'Wajib diisi.'],
                ['Kolom Semester', 'Angka 1-8. Wajib.'],
                ['Kolom SKS', 'Isi angka (0-6). Total SKS akan dijumlah otomatis.'],
                ['Kolom Jenis', 'wajib / pilihan / mbkm.'],
                ['Kolom CPL', 'Daftar kode CPL yang didukung, pisahkan dengan koma atau titik koma (;). CPL harus sudah ada di sistem.']
            ]
        );
    }

    public function mkImportStore(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        $upload = $this->readUpload(['kode_mk' => 'kode', 'nama_mata_kuliah' => 'nama', 'sks_praktik' => 'sks_praktek']);
        if (is_string($upload)) return redirect()->back()->with('error', $upload);
        [$sheet, $colOf, $highestRow] = $upload;

        if (empty($colOf['kode']) || empty($colOf['nama']) || empty($colOf['semester'])) {
            return redirect()->back()->with('error', 'Format kolom tidak sesuai. Wajib ada: Kode MK, Nama Mata Kuliah, Semester.');
        }

        $inserted = 0; $updated = 0; $errors = []; $warnings = [];
        
        // Ambil pemetaan CPL untuk lookup
        $cplRows = $db->table('cpl')->where('kurikulum_id', $kurikulumId)->get()->getResultArray();
        $cplMap = [];
        foreach ($cplRows as $c) $cplMap[strtolower($c['kode'])] = $c['id'];

        $db->transStart();

        for ($r = 2; $r <= $highestRow; $r++) {
            $kode = trim((string) $sheet->getCell([$colOf['kode'], $r])->getValue());
            if ($kode === '') continue;

            $nama = trim((string) $sheet->getCell([$colOf['nama'], $r])->getValue());
            $sem  = (int) $sheet->getCell([$colOf['semester'], $r])->getValue();
            
            $teori    = !empty($colOf['sks_teori']) ? (int) $sheet->getCell([$colOf['sks_teori'], $r])->getValue() : 0;
            $praktek  = !empty($colOf['sks_praktek']) ? (int) $sheet->getCell([$colOf['sks_praktek'], $r])->getValue() : 0;
            $lapangan = !empty($colOf['sks_lapangan']) ? (int) $sheet->getCell([$colOf['sks_lapangan'], $r])->getValue() : 0;
            $jenis    = !empty($colOf['jenis']) ? strtolower(trim((string) $sheet->getCell([$colOf['jenis'], $r])->getValue())) : 'wajib';
            
            if (!in_array($jenis, ['wajib', 'pilihan', 'mbkm'])) $jenis = 'wajib';

            if ($nama === '' || $sem < 1) {
                $errors[] = "Baris $r: Nama MK atau Semester tidak valid untuk $kode.";
                continue;
            }

            $data = [
                'kurikulum_id' => $kurikulumId,
                'kode'         => $kode,
                'nama'         => $nama,
                'sks_teori'    => $teori,
                'sks_praktek'  => $praktek,
                'sks_lapangan' => $lapangan,
                'semester'     => $sem,
                'jenis'        => $jenis,
            ];

            $existing = $db->table('mata_kuliah')->where(['kurikulum_id' => $kurikulumId, 'kode' => $kode])->get()->getRowArray();
            if ($existing) {
                $mkId = $existing['id'];
                $db->table('mata_kuliah')->where('id', $mkId)->update($data);
                $updated++;
            } else {
                $db->table('mata_kuliah')->insert($data);
                $mkId = $db->insertID();
                $inserted++;
            }

            // Auto-map CPL jika diisi pada Excel
            if (!empty($colOf['cpl'])) {
                $cplRaw = trim((string) $sheet->getCell([$colOf['cpl'], $r])->getValue());
                if ($cplRaw !== '') {
                    $cplKodes = preg_split('/[,;]/', $cplRaw);
                    $mappedCplIds = [];
                    foreach ($cplKodes as $ck) {
                        $ckClean = strtolower(trim($ck));
                        if ($ckClean === '') continue;
                        if (isset($cplMap[$ckClean])) {
                            $mappedCplIds[] = $cplMap[$ckClean];
                        } else {
                            $warnings[] = "Baris $r: CPL '$ck' tidak terdaftar di kurikulum ini, dilewati.";
                        }
                    }
                    
                    if (!empty($mappedCplIds)) {
                        $db->table('mk_cpl')->where('mata_kuliah_id', $mkId)->delete();
                        $insertPeta = [];
                        foreach (array_unique($mappedCplIds) as $cid) {
                            $insertPeta[] = ['mata_kuliah_id' => $mkId, 'cpl_id' => $cid];
                        }
                        $db->table('mk_cpl')->insertBatch($insertPeta);
                    }
                }
            }
        }

        $db->transComplete();
        session()->setFlashdata('importResult', ['inserted' => $inserted, 'updated' => $updated, 'errors' => $errors, 'warnings' => array_unique($warnings)]);
        return redirect()->to(site_url("kurikulum/{$kurikulumId}/mk/import"));
    }

    public function mkCreate(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        $cpl = $db->table('cpl')->where('kurikulum_id', $kurikulumId)->orderBy('kode', 'ASC')->get()->getResultArray();

        return view('kurikulum/mk_form', [
            'kuri'    => $kuri,
            'mk'      => null,
            'cpl'     => $cpl,
            'checked' => [],
            'action'  => site_url("kurikulum/{$kurikulumId}/mk/store")
        ]);
    }

    public function mkStore(int $kurikulumId)
    {
        $db = Database::connect();
        $post = $this->request->getPost();

        $db->transStart();
        $db->table('mata_kuliah')->insert([
            'kurikulum_id' => $kurikulumId,
            'kode'         => $post['kode'],
            'nama'         => $post['nama'],
            'sks_teori'    => (int)($post['sks_teori'] ?? 0),
            'sks_praktek'  => (int)($post['sks_praktek'] ?? 0),
            'sks_lapangan' => (int)($post['sks_lapangan'] ?? 0),
            'semester'     => $post['semester'],
            'jenis'        => $post['jenis'] ?? 'wajib',
            'rps_path'     => $post['rps_path'] ?? null,
        ]);
        
        $mkId = $db->insertID();
        $cplIds = $post['cpl_ids'] ?? [];
        if (!empty($cplIds)) {
            $insertPeta = array_map(fn($cId) => ['mata_kuliah_id' => $mkId, 'cpl_id' => $cId], $cplIds);
            $db->table('mk_cpl')->insertBatch($insertPeta);
        }
        $db->transComplete();

        return redirect()->to("kurikulum/{$kurikulumId}#tab-mk")->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function mkEdit(int $kurikulumId, int $mkId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        $mk   = $db->table('mata_kuliah')->where('id', $mkId)->get()->getRowArray();

        if (!$kuri || !$mk) return redirect()->to("kurikulum/{$kurikulumId}")->with('error', 'Data tidak ditemukan.');

        $cpl = $db->table('cpl')->where('kurikulum_id', $kurikulumId)->orderBy('kode', 'ASC')->get()->getResultArray();
        $peta = $db->table('mk_cpl')->where('mata_kuliah_id', $mkId)->get()->getResultArray();

        return view('kurikulum/mk_form', [
            'kuri'    => $kuri,
            'mk'      => $mk,
            'cpl'     => $cpl,
            'checked' => array_column($peta, 'cpl_id'),
            'action'  => site_url("kurikulum/{$kurikulumId}/mk/{$mkId}/update")
        ]);
    }

    public function mkUpdate(int $kurikulumId, int $mkId)
    {
        $db = Database::connect();
        $post = $this->request->getPost();

        $db->transStart();
        $db->table('mata_kuliah')->where('id', $mkId)->update([
            'kode'         => $post['kode'],
            'nama'         => $post['nama'],
            'sks_teori'    => (int)($post['sks_teori'] ?? 0),
            'sks_praktek'  => (int)($post['sks_praktek'] ?? 0),
            'sks_lapangan' => (int)($post['sks_lapangan'] ?? 0),
            'semester'     => $post['semester'],
            'jenis'        => $post['jenis'] ?? 'wajib',
            'rps_path'     => $post['rps_path'] ?? null,
        ]);

        $db->table('mk_cpl')->where('mata_kuliah_id', $mkId)->delete();
        $cplIds = $post['cpl_ids'] ?? [];
        if (!empty($cplIds)) {
            $insertPeta = array_map(fn($cId) => ['mata_kuliah_id' => $mkId, 'cpl_id' => $cId], $cplIds);
            $db->table('mk_cpl')->insertBatch($insertPeta);
        }
        $db->transComplete();

        return redirect()->to("kurikulum/{$kurikulumId}#tab-mk")->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function mkDelete(int $kurikulumId, int $mkId)
    {
        Database::connect()->table('mata_kuliah')->where('id', $mkId)->delete();
        return redirect()->to("kurikulum/{$kurikulumId}#tab-mk")->with('success', 'Mata Kuliah berhasil dihapus.');
    }

    // ────────────────────────────────────────────────────────────────────────
    // Fitur Ketercapaian CPL
    // ────────────────────────────────────────────────────────────────────────

    public function ketercapaianForm(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        $taId = (int) ($this->request->getGet('ta_id') ?: $db->table('tahun_akademik')->orderBy('is_ts', 'DESC')->orderBy('kode', 'DESC')->get()->getRow('id'));
        $taList = $db->table('tahun_akademik')->orderBy('kode', 'DESC')->get()->getResultArray();

        $rows = $db->query("
            SELECT p.mata_kuliah_id AS mk_id, mk.kode AS mk_kode, mk.nama AS mk_nama, mk.semester,
                   p.cpl_id, c.kode AS cpl_kode, k.persen_tercapai, k.jumlah_mhs
            FROM mk_cpl p
            JOIN mata_kuliah mk ON mk.id = p.mata_kuliah_id
            JOIN cpl c ON c.id = p.cpl_id
            LEFT JOIN cpl_ketercapaian k ON k.mata_kuliah_id = p.mata_kuliah_id AND k.cpl_id = p.cpl_id AND k.tahun_akademik_id = ?
            WHERE mk.kurikulum_id = ?
            ORDER BY mk.semester ASC, mk.kode ASC, c.kode ASC
        ", [$taId, $kurikulumId])->getResultArray();

        return view('kurikulum/ketercapaian_form', [
            'kuri' => $kuri, 'taId' => $taId, 'taList' => $taList, 'rows' => $rows
        ]);
    }

    public function ketercapaianSave(int $kurikulumId)
    {
        $db = Database::connect();
        $kuri = $db->table('kurikulum')->where(['id' => $kurikulumId, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$kuri) return redirect()->to('kurikulum')->with('error', 'Akses ditolak.');

        $post = $this->request->getPost();
        $taId = (int) ($post['tahun_akademik_id'] ?? 0);
        $ketData = $post['ket'] ?? []; // Format: ket[mk_id_cpl_id][pct] dan ket[mk_id_cpl_id][mhs]

        if (!$taId) {
            return redirect()->back()->with('error', 'Tahun Akademik tidak valid.');
        }

        $mkRows = $db->table('mata_kuliah')->select('id')->where('kurikulum_id', $kurikulumId)->get()->getResultArray();
        $mkIds  = array_column($mkRows, 'id');

        if (!empty($mkIds)) {
            $db->transStart();
            
            // 1. Bersihkan data ketercapaian lama khusus untuk Tahun Akademik ini & MK pada Kurikulum ini
            $db->table('cpl_ketercapaian')->where('tahun_akademik_id', $taId)->whereIn('mata_kuliah_id', $mkIds)->delete();

            // 2. Siapkan data baru untuk di-insert
            $insertData = [];
            foreach ($ketData as $key => $val) {
                $parts = explode('_', $key); // misal "15_3" menjadi [15, 3]
                if (count($parts) !== 2) continue;

                $pct = $val['pct'] !== '' ? (float) $val['pct'] : null;
                $mhs = $val['mhs'] !== '' ? (int) $val['mhs'] : null;

                // Hanya simpan jika persen tercapai atau jumlah mhs diisi (mencegah insert data kosong)
                if ($pct !== null || $mhs !== null) {
                    $insertData[] = ['tahun_akademik_id' => $taId, 'mata_kuliah_id' => (int)$parts[0], 'cpl_id' => (int)$parts[1], 'persen_tercapai' => $pct, 'jumlah_mhs' => $mhs];
                }
            }

            if (!empty($insertData)) $db->table('cpl_ketercapaian')->insertBatch($insertData);
            $db->transComplete();
        }

        return redirect()->to("kurikulum/{$kurikulumId}#tab-ket")->with('success', 'Ketercapaian CPL per Mata Kuliah berhasil disimpan.');
    }
}