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
