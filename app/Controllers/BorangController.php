<?php

namespace App\Controllers;

use App\Services\EvidenceStorage;
use Config\Database;

/**
 * CRUD generik (config-driven) untuk borang LKPS pelengkap:
 * tata pamong, SPMI, fleksibilitas, rekognisi lulusan, pengembangan DTPR,
 * sistem tata kelola. Satu controller melayani semua via slug.
 */
class BorangController extends BaseController
{
    /**
     * Definisi entitas. fields: nama => [label, type, required, options?].
     * type: text | textarea | number | ta | select
     */
    private const ENT = [
        'tata-pamong' => [
            'title' => 'Tata Pamong & Pimpinan', 'lkps' => '1.A.1', 'table' => 'tata_pamong', 'icon' => 'bi-diagram-3',
            'fields' => [
                'unit_kerja'          => ['Unit Kerja', 'text', true],
                'nama_pejabat'        => ['Nama Pejabat', 'text', true],
                'jabatan'             => ['Jabatan', 'text', false],
                'periode'             => ['Periode Jabatan', 'text', false],
                'pendidikan_terakhir' => ['Pendidikan Terakhir', 'text', false],
                'jabatan_fungsional'  => ['Jabatan Fungsional', 'text', false],
                'tupoksi'             => ['Tugas Pokok & Fungsi', 'textarea', false],
            ],
        ],
        'spmi' => [
            'title' => 'Unit SPMI & SDM', 'lkps' => '1.B', 'table' => 'spmi_unit', 'icon' => 'bi-shield-check',
            'fields' => [
                'tingkat'               => ['Tingkat', 'select', true, ['PT' => 'PT', 'UPPS' => 'UPPS', 'PS' => 'PS']],
                'nama_unit'             => ['Nama Unit SPMI', 'text', true],
                'dokumen_spmi'          => ['Dokumen SPMI', 'text', false],
                'jml_auditor'           => ['Jumlah Auditor', 'number', false],
                'auditor_certified'     => ['Auditor Bersertifikat', 'number', false],
                'auditor_non_certified' => ['Auditor Non-sertifikat', 'number', false],
                'frekuensi_audit'       => ['Frekuensi Audit/Monev per Tahun', 'number', false],
                'link_bukti'            => ['Bukti (PDF)', 'file', false],
            ],
        ],
        'fleksibilitas' => [
            'title' => 'Fleksibilitas Pembelajaran (MBKM)', 'lkps' => '2.C', 'table' => 'fleksibilitas', 'icon' => 'bi-arrow-left-right',
            'fields' => [
                'tahun_akademik_id' => ['Tahun Akademik', 'ta', false],
                'bentuk'            => ['Bentuk Pembelajaran', 'select', true, [
                    'micro_credential' => 'Micro-credential', 'rpl' => 'RPL',
                    'mbkm_ps_lain' => 'Pembelajaran di PS lain', 'mbkm_pt_lain' => 'Pembelajaran di PT lain',
                    'cbl_pbl' => 'CBL / PBL', 'lainnya' => 'Lainnya']],
                'jumlah_mahasiswa'  => ['Jumlah Mahasiswa', 'number', false],
                'keterangan'        => ['Keterangan', 'textarea', false],
                'link_bukti'        => ['Bukti (PDF)', 'file', false],
            ],
        ],
        'rekognisi' => [
            'title' => 'Rekognisi & Apresiasi Lulusan', 'lkps' => '2.D', 'table' => 'rekognisi_lulusan', 'icon' => 'bi-award',
            'fields' => [
                'tahun_akademik_id' => ['Tahun Akademik', 'ta', false],
                'sumber'            => ['Sumber Rekognisi', 'select', true, [
                    'masyarakat' => 'Masyarakat', 'dunia_usaha' => 'Dunia Usaha',
                    'dunia_industri' => 'Dunia Industri', 'dunia_kerja' => 'Dunia Kerja']],
                'jenis_pengakuan'   => ['Jenis Pengakuan/Rekognisi', 'text', true],
                'jumlah'            => ['Jumlah', 'number', false],
                'link_bukti'        => ['Bukti (PDF)', 'file', false],
            ],
        ],
        'pengembangan-dtpr' => [
            'title' => 'Pengembangan DTPR (Penelitian)', 'lkps' => '3.A.3', 'table' => 'pengembangan_dtpr', 'icon' => 'bi-graph-up',
            'fields' => [
                'tahun_akademik_id'  => ['Tahun Akademik', 'ta', false],
                'nama_dtpr'          => ['Nama DTPR', 'text', true],
                'jenis_pengembangan' => ['Jenis Pengembangan', 'text', true],
                'jumlah'             => ['Jumlah', 'number', false],
                'link_bukti'         => ['Bukti (PDF)', 'file', false],
            ],
        ],
        'tata-kelola' => [
            'title' => 'Sistem Tata Kelola / SI', 'lkps' => '5.1', 'table' => 'tata_kelola_si', 'icon' => 'bi-hdd-network',
            'fields' => [
                'jenis'          => ['Jenis Tata Kelola', 'select', true, [
                    'pendidikan' => 'Pendidikan', 'keuangan' => 'Keuangan', 'sdm' => 'SDM',
                    'sarpras' => 'Sarana Prasarana', 'spmi' => 'Penjaminan Mutu']],
                'nama_sistem'    => ['Nama Sistem Informasi', 'text', true],
                'akses'          => ['Akses', 'select', false, ['lokal' => 'Lokal', 'internet' => 'Internet']],
                'unit_pengelola' => ['Unit/SDM Pengelola', 'text', false],
                'link_bukti'     => ['Bukti (PDF)', 'file', false],
            ],
        ],
    ];

    public static function entities(): array
    {
        return self::ENT;
    }

    private function def(string $slug): array
    {
        if (!isset(self::ENT[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Borang '{$slug}' tidak ditemukan.");
        }
        return self::ENT[$slug];
    }

    private function prodiId(): int
    {
        $pid = (int) session('active_prodi_id');
        if (!$pid && session('is_superadmin')) {
            $first = Database::connect()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        return $pid;
    }

    private function taList(): array
    {
        return Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
    }

    public function index(string $slug): string
    {
        $def     = $this->def($slug);
        $prodiId = $this->prodiId();
        $list    = [];
        if ($prodiId) {
            $q = Database::connect()->table($def['table'] . ' t')->where('t.prodi_id', $prodiId);
            if (isset($def['fields']['tahun_akademik_id'])) {
                $q->select('t.*, ta.kode AS ta_kode')->join('tahun_akademik ta', 'ta.id = t.tahun_akademik_id', 'left');
            } else {
                $q->select('t.*');
            }
            $list = $q->orderBy('t.id', 'DESC')->get()->getResultArray();
        }
        return view('borang/index', ['slug' => $slug, 'def' => $def, 'list' => $list]);
    }

    public function create(string $slug): string
    {
        $def = $this->def($slug);
        return view('borang/form', ['slug' => $slug, 'def' => $def, 'item' => null, 'taList' => $this->taList()]);
    }

    public function store(string $slug)
    {
        $def     = $this->def($slug);
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Pilih prodi aktif terlebih dahulu.');

        $data = $this->collect($def, $prodiId);
        if ($err = $this->validateReq($def, $data)) {
            return redirect()->back()->withInput()->with('error', $err);
        }
        if ($err = $this->applyFiles($def, $data, null)) {
            return redirect()->back()->withInput()->with('error', $err);
        }
        Database::connect()->table($def['table'])->insert($data);
        return redirect()->to(site_url("borang/{$slug}"))->with('success', $def['title'] . ' ditambahkan.');
    }

    public function edit(string $slug, int $id): string
    {
        $def  = $this->def($slug);
        $item = Database::connect()->table($def['table'])->where('id', $id)->get()->getRowArray();
        if (!$item) throw new \CodeIgniter\Exceptions\PageNotFoundException();
        return view('borang/form', ['slug' => $slug, 'def' => $def, 'item' => $item, 'taList' => $this->taList()]);
    }

    public function update(string $slug, int $id)
    {
        $def  = $this->def($slug);
        $db   = Database::connect();
        $item = $db->table($def['table'])->where('id', $id)->get()->getRowArray();
        if (!$item) throw new \CodeIgniter\Exceptions\PageNotFoundException();
        $data = $this->collect($def, (int) $item['prodi_id']);
        if ($err = $this->validateReq($def, $data)) {
            return redirect()->back()->withInput()->with('error', $err);
        }
        if ($err = $this->applyFiles($def, $data, $item)) {
            return redirect()->back()->withInput()->with('error', $err);
        }
        $db->table($def['table'])->where('id', $id)->update($data);
        return redirect()->to(site_url("borang/{$slug}"))->with('success', $def['title'] . ' diperbarui.');
    }

    public function delete(string $slug, int $id)
    {
        $def = $this->def($slug);
        Database::connect()->table($def['table'])->where('id', $id)->delete();
        return redirect()->to(site_url("borang/{$slug}"))->with('success', 'Data dihapus.');
    }

    private function collect(array $def, int $prodiId): array
    {
        $data = ['prodi_id' => $prodiId];
        foreach ($def['fields'] as $name => $f) {
            $type = $f[1];
            if ($type === 'file') continue; // ditangani applyFiles()
            $val = $this->request->getPost($name);
            if ($type === 'number' || $type === 'ta') {
                $data[$name] = ($val !== null && $val !== '') ? (int) $val : ($type === 'ta' ? null : 0);
            } else {
                $data[$name] = trim((string) $val) !== '' ? trim((string) $val) : null;
            }
        }
        return $data;
    }

    /** Proses field bertipe 'file' (upload/keep/hapus). Return pesan error atau null. */
    private function applyFiles(array $def, array &$data, ?array $existing): ?string
    {
        $store = new EvidenceStorage();
        foreach ($def['fields'] as $name => $f) {
            if ($f[1] !== 'file') continue;
            $res = $store->syncUpload(
                $this->request->getFile($name),
                (bool) $this->request->getPost('hapus_' . $name),
                $existing[$name] ?? null
            );
            if (isset($res['error'])) return 'Bukti "' . $f[0] . '": ' . $res['error'];
            $data[$name] = $res['value'];
        }
        return null;
    }

    private function validateReq(array $def, array $data): ?string
    {
        foreach ($def['fields'] as $name => $f) {
            if (!empty($f[2]) && ($data[$name] === null || $data[$name] === '')) {
                return 'Kolom "' . $f[0] . '" wajib diisi.';
            }
        }
        return null;
    }
}
