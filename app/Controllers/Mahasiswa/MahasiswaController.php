<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\TracerStudyModel;
use App\Models\KepuasanModel;
use App\Services\FonnteClient;
use App\Services\TracerToken;
use Config\Database;

class MahasiswaController extends BaseController
{
    protected MahasiswaModel $model;
    protected TracerStudyModel $tracerModel;
    protected KepuasanModel $kepuasanModel;

    public function __construct()
    {
        $this->model         = new MahasiswaModel();
        $this->tracerModel   = new TracerStudyModel();
        $this->kepuasanModel = new KepuasanModel();
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
        // Superadmin tanpa prodi aktif: gunakan prodi pertama
        if (!$prodiId && session('is_superadmin')) {
            $firstProdi = Database::connect()->table('prodi')->where('status','aktif')->orderBy('nama')->get()->getRowArray();
            $prodiId    = $firstProdi ? (int)$firstProdi['id'] : 0;
        }
        $tracer  = $prodiId ? $this->tracerModel->listByProdi($prodiId) : [];
        $summary = $prodiId ? $this->tracerModel->summaryByProdi($prodiId) : null;
        $belum   = $prodiId ? $this->tracerModel->lulusanBelumTracer($prodiId) : [];
        $fonnteEnabled = (new FonnteClient())->enabled();

        return view('mahasiswa/tracer/index', compact('tracer', 'summary', 'belum', 'prodiId', 'fonnteEnabled'));
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

    // ---------- KIRIM FORM TRACER VIA WHATSAPP (FONNTE) ----------

    /** Kirim link kuesioner tracer ke satu alumnus. */
    public function tracerKirim(int $mahasiswaId)
    {
        $mhs = $this->model->findOrFail($mahasiswaId);
        if (empty($mhs['no_hp'])) {
            return redirect()->back()->with('error', "Nomor WhatsApp \"{$mhs['nama']}\" belum diisi.");
        }
        $prodiNama = $this->prodiNama((int) $mhs['prodi_id']);
        $res = (new FonnteClient())->send($mhs['no_hp'], $this->tracerMessage($mhs['nama'], (int) $mhs['id'], $prodiNama));

        return $res['ok']
            ? redirect()->back()->with('success', "Link kuesioner terkirim ke {$mhs['nama']}.")
            : redirect()->back()->with('error', "Gagal mengirim ke {$mhs['nama']}: {$res['error']}");
    }

    /** Kirim link kuesioner ke semua lulusan yang belum mengisi tracer. */
    public function tracerKirimSemua()
    {
        $prodiId = $this->importProdiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu.');
        }
        $belum     = $this->tracerModel->lulusanBelumTracer($prodiId);
        $prodiNama = $this->prodiNama($prodiId);
        $fonnte    = new FonnteClient();
        if (!$fonnte->enabled()) {
            return redirect()->back()->with('error', 'WhatsApp gateway (Fonnte) belum dikonfigurasi. Set fonnte.token di .env.');
        }

        $sent = 0; $fail = 0; $skip = 0; $contoh = [];
        foreach ($belum as $b) {
            if (empty($b['no_hp'])) { $skip++; continue; }
            $res = $fonnte->send($b['no_hp'], $this->tracerMessage($b['nama'], (int) $b['id'], $prodiNama));
            if ($res['ok']) {
                $sent++;
            } else {
                $fail++;
                if (count($contoh) < 5) $contoh[] = "{$b['nama']}: {$res['error']}";
            }
        }

        $msg = "Terkirim: {$sent}. Gagal: {$fail}. Tanpa nomor WA: {$skip}.";
        if ($contoh) $msg .= ' Contoh gagal — ' . implode('; ', $contoh);
        return redirect()->to(site_url('mahasiswa/tracer'))->with($fail ? 'error' : 'success', $msg);
    }

    private function prodiNama(int $prodiId): string
    {
        $row = Database::connect()->table('prodi')->select('nama')->where('id', $prodiId)->get()->getRowArray();
        return $row['nama'] ?? 'Program Studi';
    }

    private function tracerMessage(string $nama, int $mahasiswaId, string $prodiNama): string
    {
        $link = site_url('tracer/isi/' . TracerToken::make($mahasiswaId));
        $tpl  = trim((string) env('fonnte.tracerMessage', ''));
        if ($tpl === '') {
            $tpl = "Halo {nama}, kami dari {prodi} memohon kesediaan Anda mengisi kuesioner tracer study alumni "
                 . "untuk keperluan akreditasi program studi. Mohon klik tautan berikut (cukup beberapa menit):\n\n{link}\n\nTerima kasih banyak.";
        }
        return strtr($tpl, ['{nama}' => $nama, '{prodi}' => $prodiNama, '{link}' => $link]);
    }

    // ---------- SURVEI KEPUASAN PENGGUNA LULUSAN (2.B.6) ----------

    public function kepuasanIndex(): string
    {
        $prodiId = $this->importProdiId();
        $list    = $prodiId ? $this->kepuasanModel->listByProdi($prodiId) : [];
        $summary = $prodiId ? $this->kepuasanModel->summaryByProdi($prodiId) : [];
        $belum   = $prodiId ? $this->kepuasanModel->lulusanBelum($prodiId) : [];
        $fonnteEnabled = (new FonnteClient())->enabled();
        $aspek   = KepuasanModel::ASPEK;

        return view('mahasiswa/kepuasan/index', compact('list', 'summary', 'belum', 'prodiId', 'fonnteEnabled', 'aspek'));
    }

    /** Form input kepuasan oleh admin/GKM. */
    public function kepuasanForm(int $mahasiswaId): string
    {
        $mhs  = $this->model->findOrFail($mahasiswaId);
        $data = $this->kepuasanModel->getByMahasiswa($mahasiswaId) ?? [];
        return view('mahasiswa/kepuasan/form', ['mhs' => $mhs, 'data' => $data, 'aspek' => KepuasanModel::ASPEK]);
    }

    public function kepuasanStore(int $mahasiswaId)
    {
        $this->model->findOrFail($mahasiswaId);
        $req  = $this->request;
        $data = [
            'mahasiswa_id'    => $mahasiswaId,
            'tgl_isi'         => date('Y-m-d'),
            'nama_atasan'     => trim((string) $req->getPost('nama_atasan')) ?: null,
            'jabatan_atasan'  => trim((string) $req->getPost('jabatan_atasan')) ?: null,
            'nama_perusahaan' => trim((string) $req->getPost('nama_perusahaan')) ?: null,
            'saran'           => trim((string) $req->getPost('saran')) ?: null,
        ];
        foreach (array_keys(KepuasanModel::ASPEK) as $a) {
            $v = (int) $req->getPost($a);
            $data[$a] = ($v >= 1 && $v <= 4) ? $v : null;
        }
        $existing = $this->kepuasanModel->getByMahasiswa($mahasiswaId);
        if ($existing) {
            $this->kepuasanModel->update($existing['id'], $data);
        } else {
            $this->kepuasanModel->insert($data);
        }
        return redirect()->to(site_url('mahasiswa/kepuasan'))->with('success', 'Survei kepuasan berhasil disimpan.');
    }

    public function kepuasanKirim(int $mahasiswaId)
    {
        $mhs = $this->model->findOrFail($mahasiswaId);
        if (empty($mhs['no_hp'])) {
            return redirect()->back()->with('error', "Nomor WhatsApp \"{$mhs['nama']}\" belum diisi.");
        }
        $res = (new FonnteClient())->send($mhs['no_hp'], $this->kepuasanMessage($mhs['nama'], (int) $mhs['id'], $this->prodiNama((int) $mhs['prodi_id'])));
        return $res['ok']
            ? redirect()->back()->with('success', "Link survei kepuasan terkirim ke {$mhs['nama']}.")
            : redirect()->back()->with('error', "Gagal mengirim ke {$mhs['nama']}: {$res['error']}");
    }

    public function kepuasanKirimSemua()
    {
        $prodiId = $this->importProdiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu.');
        }
        $fonnte = new FonnteClient();
        if (!$fonnte->enabled()) {
            return redirect()->back()->with('error', 'WhatsApp gateway (Fonnte) belum dikonfigurasi. Set fonnte.token di .env.');
        }
        $prodiNama = $this->prodiNama($prodiId);
        $sent = 0; $fail = 0; $skip = 0; $contoh = [];
        foreach ($this->kepuasanModel->lulusanBelum($prodiId) as $b) {
            if (empty($b['no_hp'])) { $skip++; continue; }
            $res = $fonnte->send($b['no_hp'], $this->kepuasanMessage($b['nama'], (int) $b['id'], $prodiNama));
            if ($res['ok']) { $sent++; } else { $fail++; if (count($contoh) < 5) $contoh[] = "{$b['nama']}: {$res['error']}"; }
        }
        $msg = "Terkirim: {$sent}. Gagal: {$fail}. Tanpa nomor WA: {$skip}.";
        if ($contoh) $msg .= ' Contoh gagal — ' . implode('; ', $contoh);
        return redirect()->to(site_url('mahasiswa/kepuasan'))->with($fail ? 'error' : 'success', $msg);
    }

    private function kepuasanMessage(string $nama, int $mahasiswaId, string $prodiNama): string
    {
        $link = site_url('kepuasan/isi/' . TracerToken::make($mahasiswaId, 'kepuasan'));
        $tpl  = trim((string) env('fonnte.kepuasanMessage', ''));
        if ($tpl === '') {
            $tpl = "Halo {nama}, kami dari {prodi} memohon bantuan Anda meneruskan tautan berikut kepada ATASAN/PIMPINAN "
                 . "di tempat kerja Anda untuk mengisi Survei Kepuasan Pengguna Lulusan (untuk akreditasi prodi):\n\n{link}\n\nTerima kasih banyak.";
        }
        return strtr($tpl, ['{nama}' => $nama, '{prodi}' => $prodiNama, '{link}' => $link]);
    }

    // ---------- IMPORT EXCEL ----------

    /** Kolom kanonik yang dipakai pada file import. */
    private const IMPORT_COLS = [
        'nim', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'email', 'no_hp', 'kota_asal', 'provinsi_asal', 'negara_asal',
        'tahun_masuk', 'jalur_masuk', 'status', 'ipk', 'masa_studi_bulan', 'tgl_lulus',
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
        $taList = Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
        return view('mahasiswa/import', ['taList' => $taList]);
    }

    /** Unduh template .xlsx berisi header + contoh baris. */
    public function importTemplate()
    {
        $ss    = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setTitle('Mahasiswa');

        $col = 1;
        foreach (self::IMPORT_COLS as $h) {
            $sheet->setCellValue([$col, 1], $h);
            $col++;
        }
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Contoh baris
        $contoh = [
            '2021010001', 'Budi Santoso', 'L', 'Bandung', '2003-05-12',
            'budi@example.ac.id', '081200000001', 'Bandung', 'Jawa Barat', 'Indonesia',
            '2021/2022', 'SNMPTN', 'aktif', '3.45', '', '',
        ];
        $col = 1;
        foreach ($contoh as $v) {
            $sheet->setCellValueExplicit([$col, 2], $v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $col++;
        }
        foreach (range('A', 'P') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Sheet petunjuk
        $info = $ss->createSheet();
        $info->setTitle('Petunjuk');
        $baris = [
            ['Kolom wajib', 'nim, nama'],
            ['jenis_kelamin', 'L atau P'],
            ['status', 'aktif | cuti | do | keluar | lulus (kosong = aktif)'],
            ['tanggal_lahir / tgl_lulus', 'format YYYY-MM-DD (mis. 2003-05-12)'],
            ['ipk', 'angka 0–4, gunakan titik (mis. 3.45)'],
            ['tahun_masuk', 'kode tahun akademik, harus cocok dengan daftar di bawah'],
            ['', ''],
            ['Daftar kode Tahun Akademik:', ''],
        ];
        foreach (Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray() as $ta) {
            $baris[] = [$ta['kode'], $ta['semester'] ?? ''];
        }
        $r = 1;
        foreach ($baris as $row) {
            $info->setCellValue([1, $r], $row[0]);
            $info->setCellValue([2, $r], $row[1]);
            $r++;
        }
        $info->getColumnDimension('A')->setWidth(30);
        $info->getColumnDimension('B')->setWidth(50);

        $filename = 'Template_Import_Mahasiswa.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss))->save('php://output');
        exit;
    }

    public function importStore()
    {
        $prodiId = $this->importProdiId();
        if (!$prodiId) {
            return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu sebelum impor.');
        }

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid. Unggah file Excel (.xlsx).');
        }
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'], true)) {
            return redirect()->back()->with('error', 'Format harus .xlsx atau .xls.');
        }

        try {
            $ss = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $sheet     = $ss->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $highestCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

        // Petakan header (baris 1) -> indeks kolom
        $colOf = [];
        for ($c = 1; $c <= $highestCol; $c++) {
            $key = $this->normHeader((string) $sheet->getCell([$c, 1])->getValue());
            if ($key !== '') $colOf[$key] = $c;
        }
        if (!isset($colOf['nim']) || !isset($colOf['nama'])) {
            return redirect()->back()->with('error', 'Header tidak ditemukan. Pastikan baris pertama memuat kolom "nim" dan "nama" (gunakan template).');
        }

        // Map kode TA -> id
        $taMap = [];
        foreach (Database::connect()->table('tahun_akademik')->get()->getResultArray() as $ta) {
            $taMap[strtolower(trim($ta['kode']))] = (int) $ta['id'];
        }

        $inserted = 0; $updated = 0; $errors = []; $warnings = [];
        $statusValid = ['aktif', 'cuti', 'do', 'keluar', 'lulus'];

        for ($r = 2; $r <= $highestRow; $r++) {
            $val = fn(string $key) => isset($colOf[$key]) ? trim((string) $sheet->getCell([$colOf[$key], $r])->getValue()) : '';

            $nim  = $val('nim');
            $nama = $val('nama');
            if ($nim === '' && $nama === '') continue; // baris kosong
            if ($nim === '' || $nama === '') {
                $errors[] = "Baris {$r}: NIM dan Nama wajib diisi.";
                continue;
            }

            $jk = strtoupper(substr($val('jenis_kelamin'), 0, 1));
            $jk = in_array($jk, ['L', 'P'], true) ? $jk : null;

            $status = strtolower($val('status')) ?: 'aktif';
            if (!in_array($status, $statusValid, true)) {
                $errors[] = "Baris {$r}: status \"{$val('status')}\" tidak valid (aktif/cuti/do/keluar/lulus).";
                continue;
            }

            $ipkRaw = str_replace(',', '.', $val('ipk'));
            $ipk = null;
            if ($ipkRaw !== '') {
                if (is_numeric($ipkRaw) && (float) $ipkRaw >= 0 && (float) $ipkRaw <= 4) {
                    $ipk = round((float) $ipkRaw, 2);
                } else {
                    $warnings[] = "Baris {$r}: IPK \"{$val('ipk')}\" diabaikan (harus 0–4).";
                }
            }

            $taId = null;
            $taKode = $val('tahun_masuk');
            if ($taKode !== '') {
                $taId = $taMap[strtolower($taKode)] ?? null;
                if ($taId === null) $warnings[] = "Baris {$r}: tahun masuk \"{$taKode}\" tidak cocok, dikosongkan.";
            }

            $masaStudi = $val('masa_studi_bulan');
            $data = [
                'prodi_id'         => $prodiId,
                'nim'              => $nim,
                'nama'             => $nama,
                'jenis_kelamin'    => $jk,
                'tempat_lahir'     => $val('tempat_lahir') ?: null,
                'tanggal_lahir'    => $this->parseDateCell($sheet, $colOf['tanggal_lahir'] ?? null, $r),
                'email'            => $val('email') ?: null,
                'no_hp'            => $val('no_hp') ?: null,
                'kota_asal'        => $val('kota_asal') ?: null,
                'provinsi_asal'    => $val('provinsi_asal') ?: null,
                'negara_asal'      => $val('negara_asal') ?: 'Indonesia',
                'tahun_masuk_id'   => $taId,
                'jalur_masuk'      => $val('jalur_masuk') ?: null,
                'status'           => $status,
                'tgl_lulus'        => $this->parseDateCell($sheet, $colOf['tgl_lulus'] ?? null, $r),
                'ipk'              => $ipk,
                'masa_studi_bulan' => $masaStudi !== '' && is_numeric($masaStudi) ? (int) $masaStudi : null,
            ];

            $existing = $this->model->where(['prodi_id' => $prodiId, 'nim' => $nim])->first();
            try {
                if ($existing) {
                    $ok = $this->model->update($existing['id'], $data);
                    $ok ? $updated++ : $errors[] = "Baris {$r} (NIM {$nim}): " . implode('; ', $this->model->errors());
                } else {
                    $ok = $this->model->insert($data, false);
                    $ok ? $inserted++ : $errors[] = "Baris {$r} (NIM {$nim}): " . implode('; ', $this->model->errors());
                }
            } catch (\Throwable $e) {
                $errors[] = "Baris {$r} (NIM {$nim}): gagal disimpan.";
            }
        }

        return redirect()->to(site_url('mahasiswa/import'))
            ->with('importResult', compact('inserted', 'updated', 'errors', 'warnings'));
    }

    /** Normalisasi nama header kolom Excel ke kunci kanonik. */
    private function normHeader(string $v): string
    {
        $h = strtolower(trim($v));
        $h = preg_replace('/\(.*?\)/', '', $h);
        $h = preg_replace('/[^a-z0-9]+/', '_', $h);
        $h = trim((string) $h, '_');
        $alias = [
            'jk' => 'jenis_kelamin', 'kelamin' => 'jenis_kelamin',
            'tgl_lahir' => 'tanggal_lahir', 'hp' => 'no_hp', 'telp' => 'no_hp',
            'ta_masuk' => 'tahun_masuk', 'tahun_akademik_masuk' => 'tahun_masuk', 'angkatan' => 'tahun_masuk',
            'masa_studi' => 'masa_studi_bulan',
        ];
        return $alias[$h] ?? $h;
    }

    /** Baca sel tanggal (mendukung serial Excel maupun teks) -> 'Y-m-d' atau null. */
    private function parseDateCell($sheet, ?int $col, int $row): ?string
    {
        if (!$col) return null;
        $cell = $sheet->getCell([$col, $row]);
        $raw  = $cell->getValue();
        if ($raw === null || $raw === '') return null;
        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell) && is_numeric($raw)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $raw)->format('Y-m-d');
        }
        $ts = strtotime((string) $raw);
        return $ts ? date('Y-m-d', $ts) : null;
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
