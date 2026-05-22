<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Controllers\Concerns\ExcelImport;
use App\Models\DosenModel;
use App\Models\DosenEwmpModel;
use Config\Database;

class DosenController extends BaseController
{
    use ExcelImport;

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

        $dosen     = $prodiId ? $this->model->listByProdi((int) $prodiId) : [];
        $summary   = $prodiId ? $this->model->summaryForProdi((int) $prodiId) : null;
        $kepatuhan = $prodiId ? $this->kepatuhanDtpr((int) $prodiId) : null;

        return view('dosen/index', compact('dosen', 'summary', 'prodiId', 'isSuperadmin', 'kepatuhan'));
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

        if ($dupe = $this->nidnDuplikat($data['nidn'] ?? null)) {
            return redirect()->back()->withInput()->with('errors', ['nidn' => $this->pesanDuplikat($data['nidn'], $dupe)]);
        }
        $data['nidn'] = trim((string)($data['nidn'] ?? '')) ?: null;

        $this->model->insert($data);
        return redirect()->to(site_url('dosen'))->with('success', $this->pesanSimpan('Dosen berhasil ditambahkan.', $data));
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

        if ($dupe = $this->nidnDuplikat($data['nidn'] ?? null, $id)) {
            return redirect()->back()->withInput()->with('errors', ['nidn' => $this->pesanDuplikat($data['nidn'], $dupe)]);
        }
        $data['nidn'] = trim((string)($data['nidn'] ?? '')) ?: null;

        $this->model->update($id, $data);
        return redirect()->to(site_url('dosen'))->with('success', $this->pesanSimpan('Data dosen berhasil diperbarui.', $data));
    }

    // ---------- DELETE ----------
    public function delete(int $id)
    {
        $this->model->findOrFail($id);
        $this->model->update($id, ['aktif' => 0]);
        return redirect()->to(site_url('dosen'))->with('success', 'Dosen dinonaktifkan.');
    }

    /** Evaluasi kepatuhan DTPR terhadap kaidah LAM untuk satu prodi. */
    private function kepatuhanDtpr(int $prodiId): array
    {
        $db    = Database::connect();
        $aktif = fn() => $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1]);

        $jml     = (int) $aktif()->countAllResults();
        $belumS2 = (int) $aktif()->groupStart()->where('pendidikan_terakhir', 'S1')
                        ->orWhere('pendidikan_terakhir IS NULL')->groupEnd()->countAllResults();
        $s3      = (int) $aktif()->where('pendidikan_terakhir', 'S3')->countAllResults();
        $lkgb    = (int) $aktif()->whereIn('jabatan_akademik', ['Lektor Kepala', 'Guru Besar'])->countAllResults();
        $serdos  = (int) $aktif()->where('sertifikat_pendidik', 1)->countAllResults();
        $linier  = (int) $aktif()->where('linieritas', 'linier')->countAllResults();

        $mhsAktif = (int) $db->table('mahasiswa')->where(['prodi_id' => $prodiId, 'status' => 'aktif'])->countAllResults();
        $pct = fn(int $n) => $jml > 0 ? round($n / $jml * 100) : 0;

        // EWMP di Tahun Sekarang (TS)
        $tsId = $db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? null;
        $ewmpDinilai = 0; $ewmpLuar = 0;
        if ($tsId) {
            $rows = $db->table('dosen_ewmp e')->select('e.total_sks')
                ->join('dosen d', 'd.id = e.dosen_id')
                ->where(['d.prodi_id' => $prodiId, 'e.tahun_akademik_id' => $tsId])
                ->get()->getResultArray();
            $ewmpDinilai = count($rows);
            $ewmpLuar = count(array_filter($rows, fn($r) => (float) $r['total_sks'] < 12 || (float) $r['total_sks'] > 16));
        }

        $rasio = $jml > 0 ? $mhsAktif / $jml : null;

        $checks = [];
        $checks[] = [
            'label' => 'Jumlah DTPR aktif', 'wajib' => true,
            'nilai' => "{$jml} dosen",
            'status' => $jml >= 5 ? 'ok' : 'bad',
            'ket' => 'Minimal 5 dosen homebase untuk program sarjana.',
        ];
        $checks[] = [
            'label' => 'Kualifikasi minimal S2', 'wajib' => true,
            'nilai' => $belumS2 === 0 ? 'Semua ≥ S2' : "{$belumS2} dosen belum S2",
            'status' => $belumS2 === 0 ? 'ok' : 'bad',
            'ket' => 'Seluruh DTPR wajib berpendidikan minimal magister (S2).',
        ];
        $checks[] = [
            'label' => 'Linieritas bidang keahlian', 'wajib' => true,
            'nilai' => "{$linier}/{$jml} ({$pct($linier)}%)",
            'status' => $jml > 0 && $linier === $jml ? 'ok' : ($pct($linier) >= 80 ? 'warn' : 'bad'),
            'ket' => 'Bidang keahlian DTPR sesuai (linier) dengan kompetensi inti PS.',
        ];
        $checks[] = [
            'label' => 'Rasio dosen : mahasiswa', 'wajib' => true,
            'nilai' => $rasio !== null ? '1 : ' . round($rasio, 1) : '-',
            'status' => $rasio === null ? 'info' : ($rasio <= 25 ? 'ok' : ($rasio <= 30 ? 'warn' : 'bad')),
            'ket' => 'Ideal ≤ 1:25, batas wajar ≤ 1:30 (bidang informatika/komputer).',
        ];
        $checks[] = [
            'label' => 'Bersertifikat pendidik (Serdos)', 'wajib' => false,
            'nilai' => "{$serdos}/{$jml} ({$pct($serdos)}%)",
            'status' => $pct($serdos) >= 80 ? 'ok' : ($pct($serdos) >= 40 ? 'warn' : 'bad'),
            'ket' => 'Indikator: makin tinggi makin baik.',
        ];
        $checks[] = [
            'label' => 'Jabatan Lektor Kepala / Guru Besar', 'wajib' => false,
            'nilai' => "{$lkgb}/{$jml} ({$pct($lkgb)}%)",
            'status' => $lkgb > 0 ? ($pct($lkgb) >= 30 ? 'ok' : 'warn') : 'warn',
            'ket' => 'Indikator: keberadaan dosen jabatan tinggi.',
        ];
        $checks[] = [
            'label' => 'Bergelar S3 (Doktor)', 'wajib' => false,
            'nilai' => "{$s3}/{$jml} ({$pct($s3)}%)",
            'status' => $pct($s3) >= 30 ? 'ok' : ($pct($s3) >= 10 ? 'warn' : 'bad'),
            'ket' => 'Indikator: target ≥ 30%.',
        ];
        $checks[] = [
            'label' => 'EWMP 12–16 SKS (TS)', 'wajib' => false,
            'nilai' => $ewmpDinilai === 0 ? 'Belum ada data EWMP' : ($ewmpLuar === 0 ? 'Semua dalam rentang' : "{$ewmpLuar} di luar rentang"),
            'status' => $ewmpDinilai === 0 ? 'info' : ($ewmpLuar === 0 ? 'ok' : 'warn'),
            'ket' => 'Beban kerja normal 12–16 SKS per semester.',
        ];

        $wajibBad = count(array_filter($checks, fn($c) => $c['wajib'] && $c['status'] === 'bad'));
        return [
            'checks'    => $checks,
            'memenuhi'  => $wajibBad === 0,
            'wajibBad'  => $wajibBad,
        ];
    }

    /** Cari dosen lain dengan NIDN yang sama (lintas prodi). Null bila NIDN kosong/unik. */
    private function nidnDuplikat(?string $nidn, int $exceptId = 0): ?array
    {
        $nidn = trim((string) $nidn);
        if ($nidn === '') return null;
        return Database::connect()->table('dosen d')
            ->select('d.id, d.nama, p.nama AS prodi_nama')
            ->join('prodi p', 'p.id = d.prodi_id', 'left')
            ->where('d.nidn', $nidn)
            ->where('d.id !=', $exceptId)
            ->get()->getRowArray();
    }

    /** Tambahkan peringatan kualifikasi pada pesan sukses bila pendidikan belum S2. */
    private function pesanSimpan(string $base, array $data): string
    {
        if (in_array($data['pendidikan_terakhir'] ?? '', ['', 'S1'], true)) {
            $base .= ' Peringatan: pendidikan belum magister (S2) — DTPR disyaratkan minimal S2.';
        }
        return $base;
    }

    private function pesanDuplikat(string $nidn, array $dupe): string
    {
        return "NIDN {$nidn} sudah terdaftar atas nama \"{$dupe['nama']}\" (prodi: "
            . ($dupe['prodi_nama'] ?: '-') . "). Setiap dosen hanya didaftarkan sebagai DTPR di SATU prodi "
            . "(homebase). Jika dosen ini mengajar di prodi lain, catat bebannya melalui menu EWMP "
            . "(kolom SKS di PS lain) — bukan dengan menambah data dosen baru.";
    }

    // ---------- IMPORT DOSEN (EXCEL) ----------

    private const IMPORT_COLS_DOSEN = [
        'nidn', 'nip', 'nama', 'gelar_depan', 'gelar_belakang', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'email', 'no_hp', 'pendidikan_terakhir',
        'bidang_keahlian', 'linieritas', 'jabatan_akademik', 'sertifikat_pendidik',
        'no_serdos', 'status_kepegawaian', 'sinta_id', 'scopus_id', 'google_scholar_id', 'orcid',
    ];

    private const IMPORT_COLS_EWMP = [
        'nidn', 'tahun_akademik', 'sks_pendidikan_ps', 'sks_pendidikan_ps_lain_pt',
        'sks_pendidikan_pt_lain', 'sks_penelitian', 'sks_pkm', 'sks_tugas_tambahan',
    ];

    private function importProdiId(): int
    {
        $prodiId = (int) session('active_prodi_id');
        if (!$prodiId && session('is_superadmin')) {
            $first = Database::connect()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $prodiId = $first ? (int) $first['id'] : 0;
        }
        return $prodiId;
    }

    public function importForm(): string
    {
        return view('dosen/import');
    }

    public function importTemplate()
    {
        $contoh = [
            '0012345678', '198001012005011001', 'Dr. Andi Wijaya', 'Dr.', 'M.Kom.', 'L',
            'Surabaya', '1980-01-01', 'andi@example.ac.id', '081200000002', 'S3',
            'Rekayasa Perangkat Lunak', 'linier', 'Lektor Kepala', 'ya', '12345', 'tetap',
            '6012345', '57000000', 'abcdEFG', '0000-0001-0000-0000',
        ];
        $petunjuk = [
            ['Kolom wajib', 'nama (nidn sangat disarankan untuk hindari duplikat)'],
            ['nidn', '10 digit angka; jika diisi tidak boleh terdaftar di prodi lain'],
            ['jenis_kelamin', 'L atau P'],
            ['pendidikan_terakhir', 'S1 | S2 | S3 | Sp | Sp2 (DTPR minimal S2)'],
            ['linieritas', 'linier | tidak_linier'],
            ['jabatan_akademik', 'Tenaga Pengajar | Asisten Ahli | Lektor | Lektor Kepala | Guru Besar'],
            ['sertifikat_pendidik', 'ya/1 atau tidak/0'],
            ['status_kepegawaian', 'tetap | tidak_tetap | dosen_industri'],
            ['tanggal_lahir', 'format YYYY-MM-DD'],
        ];
        return $this->keluarkanTemplate('Template_Import_Dosen.xlsx', self::IMPORT_COLS_DOSEN, $contoh, $petunjuk);
    }

    public function importStore()
    {
        $prodiId = $this->importProdiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu sebelum impor.');
        }
        $load = $this->bacaUpload();
        if (is_string($load)) return redirect()->back()->with('error', $load);
        [$sheet, $colOf, $highestRow] = $load;

        if (!isset($colOf['nama'])) {
            return redirect()->back()->with('error', 'Header "nama" tidak ditemukan. Gunakan template.');
        }

        $inserted = 0; $updated = 0; $errors = []; $warnings = [];
        $jabatanValid = ['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];
        $pendValid    = ['S1', 'S2', 'S3', 'Sp', 'Sp2'];
        $statusValid  = ['tetap', 'tidak_tetap', 'dosen_industri'];

        for ($r = 2; $r <= $highestRow; $r++) {
            $val  = fn(string $k) => isset($colOf[$k]) ? trim((string) $sheet->getCell([$colOf[$k], $r])->getValue()) : '';
            $nama = $val('nama');
            $nidn = preg_replace('/\s+/', '', $val('nidn'));
            if ($nama === '' && $nidn === '') continue;
            if ($nama === '') { $errors[] = "Baris {$r}: nama wajib diisi."; continue; }

            $existing = null;
            if ($nidn !== '') {
                if (!preg_match('/^[0-9]{10}$/', $nidn)) {
                    $errors[] = "Baris {$r}: NIDN \"{$nidn}\" harus 10 digit angka."; continue;
                }
                $existing = $this->model->where('nidn', $nidn)->first();
                if ($existing && (int) $existing['prodi_id'] !== $prodiId) {
                    $errors[] = "Baris {$r}: NIDN {$nidn} sudah terdaftar di prodi lain (\"{$existing['nama']}\")."; continue;
                }
            } else {
                $warnings[] = "Baris {$r}: tanpa NIDN — tidak dapat dicek duplikat (selalu ditambah baru).";
            }

            $jk = strtoupper(substr($val('jenis_kelamin'), 0, 1));
            $pend = strtoupper(str_replace([' ', '-'], '', $val('pendidikan_terakhir')));
            $pend = $pend === 'SP' ? 'Sp' : ($pend === 'SP2' ? 'Sp2' : $pend);
            if ($pend !== '' && !in_array($pend, $pendValid, true)) { $pend = null; $warnings[] = "Baris {$r}: pendidikan tidak dikenal, dikosongkan."; }
            if (in_array($pend, ['S1', null], true) || $pend === '') $warnings[] = "Baris {$r}: pendidikan belum S2 (syarat DTPR).";

            $lin = strtolower(str_replace(' ', '_', $val('linieritas')));
            $lin = in_array($lin, ['linier', 'tidak_linier'], true) ? $lin : 'linier';

            $jab = $val('jabatan_akademik');
            $jabMatch = null;
            foreach ($jabatanValid as $jv) { if (strcasecmp($jab, $jv) === 0) { $jabMatch = $jv; break; } }
            if ($jab !== '' && $jabMatch === null) $warnings[] = "Baris {$r}: jabatan \"{$jab}\" tidak dikenal, dikosongkan.";

            $stat = strtolower(str_replace(' ', '_', $val('status_kepegawaian')));
            $stat = in_array($stat, $statusValid, true) ? $stat : 'tetap';

            $serdos = in_array(strtolower($val('sertifikat_pendidik')), ['1', 'ya', 'yes', 'true', 'sudah'], true) ? 1 : 0;

            $data = [
                'prodi_id'            => $prodiId,
                'nidn'                => $nidn ?: null,
                'nip'                 => $val('nip') ?: null,
                'nama'                => $nama,
                'gelar_depan'         => $val('gelar_depan') ?: null,
                'gelar_belakang'      => $val('gelar_belakang') ?: null,
                'jenis_kelamin'       => in_array($jk, ['L', 'P'], true) ? $jk : null,
                'tempat_lahir'        => $val('tempat_lahir') ?: null,
                'tanggal_lahir'       => $this->parseDateCell($sheet, $colOf['tanggal_lahir'] ?? null, $r),
                'email'               => $val('email') ?: null,
                'no_hp'               => $val('no_hp') ?: null,
                'pendidikan_terakhir' => $pend ?: null,
                'bidang_keahlian'     => $val('bidang_keahlian') ?: null,
                'linieritas'          => $lin,
                'jabatan_akademik'    => $jabMatch,
                'sertifikat_pendidik' => $serdos,
                'no_serdos'           => $val('no_serdos') ?: null,
                'status_kepegawaian'  => $stat,
                'sinta_id'            => $val('sinta_id') ?: null,
                'scopus_id'           => $val('scopus_id') ?: null,
                'google_scholar_id'   => $val('google_scholar_id') ?: null,
                'orcid'               => $val('orcid') ?: null,
            ];

            try {
                if ($existing) {
                    $this->model->update($existing['id'], $data) ? $updated++ : $errors[] = "Baris {$r}: " . implode('; ', $this->model->errors());
                } else {
                    $data['aktif'] = 1;
                    $this->model->insert($data, false) ? $inserted++ : $errors[] = "Baris {$r}: " . implode('; ', $this->model->errors());
                }
            } catch (\Throwable $e) {
                $errors[] = "Baris {$r}: gagal disimpan.";
            }
        }

        return redirect()->to(site_url('dosen/import'))->with('importResult', compact('inserted', 'updated', 'errors', 'warnings'));
    }

    // ---------- IMPORT EWMP (EXCEL) ----------

    public function ewmpImportForm(): string
    {
        $taList = Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        return view('dosen/ewmp_import', ['taList' => $taList]);
    }

    public function ewmpImportTemplate()
    {
        $contoh = ['0012345678', '2024/2025', '9', '3', '0', '2', '1', '1'];
        $petunjuk = [
            ['nidn', 'NIDN dosen (harus sudah terdaftar di prodi aktif)'],
            ['tahun_akademik', 'kode tahun akademik (lihat daftar di bawah)'],
            ['sks_*', 'angka SKS; total dihitung otomatis. EWMP normal 12–16 SKS.'],
            ['sks_pendidikan_ps', 'SKS mengajar di PS sendiri'],
            ['sks_pendidikan_ps_lain_pt', 'SKS mengajar di PS lain pada PT yang sama'],
            ['sks_pendidikan_pt_lain', 'SKS mengajar di PT lain'],
        ];
        foreach (Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray() as $ta) {
            $petunjuk[] = ['Kode TA', $ta['kode']];
        }
        return $this->keluarkanTemplate('Template_Import_EWMP.xlsx', self::IMPORT_COLS_EWMP, $contoh, $petunjuk);
    }

    public function ewmpImportStore()
    {
        $prodiId = $this->importProdiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu sebelum impor.');
        }
        $load = $this->bacaUpload();
        if (is_string($load)) return redirect()->back()->with('error', $load);
        [$sheet, $colOf, $highestRow] = $load;

        if (!isset($colOf['nidn']) || !isset($colOf['tahun_akademik'])) {
            return redirect()->back()->with('error', 'Header "nidn" dan "tahun_akademik" wajib ada. Gunakan template.');
        }

        $taMap = [];
        foreach (Database::connect()->table('tahun_akademik')->get()->getResultArray() as $ta) {
            $taMap[strtolower(trim($ta['kode']))] = (int) $ta['id'];
        }

        $disimpan = 0; $errors = [];
        for ($r = 2; $r <= $highestRow; $r++) {
            $val = fn(string $k) => isset($colOf[$k]) ? trim((string) $sheet->getCell([$colOf[$k], $r])->getValue()) : '';
            $nidn = preg_replace('/\s+/', '', $val('nidn'));
            $taKode = $val('tahun_akademik');
            if ($nidn === '' && $taKode === '') continue;

            $dosen = $this->model->where(['prodi_id' => $prodiId, 'nidn' => $nidn])->first();
            if (!$dosen) { $errors[] = "Baris {$r}: dosen NIDN \"{$nidn}\" tidak ditemukan di prodi aktif."; continue; }
            $taId = $taMap[strtolower($taKode)] ?? null;
            if (!$taId) { $errors[] = "Baris {$r}: tahun akademik \"{$taKode}\" tidak dikenal."; continue; }

            $num = fn(string $k) => (float) str_replace(',', '.', $val($k) ?: '0');
            $this->ewmpModel->upsert((int) $dosen['id'], $taId, [
                'sks_pendidikan_ps'         => $num('sks_pendidikan_ps'),
                'sks_pendidikan_ps_lain_pt' => $num('sks_pendidikan_ps_lain_pt'),
                'sks_pendidikan_pt_lain'    => $num('sks_pendidikan_pt_lain'),
                'sks_penelitian'            => $num('sks_penelitian'),
                'sks_pkm'                   => $num('sks_pkm'),
                'sks_tugas_tambahan'        => $num('sks_tugas_tambahan'),
            ]);
            $disimpan++;
        }

        $warnings = [];
        return redirect()->to(site_url('dosen/ewmp/import'))
            ->with('importResult', ['inserted' => $disimpan, 'updated' => 0, 'errors' => $errors, 'warnings' => $warnings]);
    }

    /** Baca file upload Excel; return [sheet, colOf, highestRow] atau string pesan error. */
    private function bacaUpload()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) return 'File tidak valid. Unggah file Excel (.xlsx).';
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'], true)) return 'Format harus .xlsx atau .xls.';
        try {
            $ss = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return 'Gagal membaca file: ' . $e->getMessage();
        }
        $sheet = $ss->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $highestCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $alias = ['nip_nidn' => 'nidn', 'ta' => 'tahun_akademik', 'tahun' => 'tahun_akademik'];
        $colOf = [];
        for ($c = 1; $c <= $highestCol; $c++) {
            $key = $this->normHeader((string) $sheet->getCell([$c, 1])->getValue(), $alias);
            if ($key !== '') $colOf[$key] = $c;
        }
        return [$sheet, $colOf, $highestRow];
    }

    /** Hasilkan & unduh file template .xlsx. */
    private function keluarkanTemplate(string $filename, array $cols, array $contoh, array $petunjuk)
    {
        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setTitle('Data');
        $col = 1;
        foreach ($cols as $h) { $sheet->setCellValue([$col, 1], $h); $col++; }
        $sheet->getStyle('A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($cols)) . '1')->getFont()->setBold(true);
        $col = 1;
        foreach ($contoh as $v) {
            $sheet->setCellValueExplicit([$col, 2], $v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $col++;
        }
        for ($c = 1; $c <= count($cols); $c++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }
        $info = $ss->createSheet();
        $info->setTitle('Petunjuk');
        $r = 1;
        foreach ($petunjuk as $row) {
            $info->setCellValue([1, $r], $row[0]);
            $info->setCellValue([2, $r], $row[1]);
            $r++;
        }
        $info->getColumnDimension('A')->setWidth(28);
        $info->getColumnDimension('B')->setWidth(55);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss))->save('php://output');
        exit;
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
