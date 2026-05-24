<?php

namespace App\Controllers\Akun;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use Config\Database;
use CodeIgniter\Shield\Models\UserModel;

/**
 * Area self-service dosen ("Profil Saya"): dosen memutakhirkan datanya sendiri.
 * Semua aksi ber-scope ketat pada record dosen yang tertaut ke akun login.
 */
class AkunController extends BaseController
{
    protected DosenModel $model;

    public function __construct()
    {
        $this->model = new DosenModel();
    }

    /** Record dosen milik user yang sedang login (atau null bila belum tertaut). */
    private function dosen(): ?array
    {
        $uid = (int) (auth()->id() ?? 0);
        return $uid ? $this->model->findByUserId($uid) : null;
    }

    public function index()
    {
        $d = $this->dosen();
        if (!$d) {
            return view('akun/not_linked');
        }
        $db = Database::connect();
        $stats = [
            'publikasi'  => (int) $db->table('publikasi_dosen')->where('dosen_id', $d['id'])->countAllResults(),
            'penelitian' => (int) $db->table('penelitian')->where('ketua_dosen_id', $d['id'])->countAllResults(),
            'pkm'        => (int) $db->table('pkm')->where('ketua_dosen_id', $d['id'])->countAllResults(),
            'dokumen'    => (int) $db->table('dokumen_eviden')->where(['ref_table' => 'dosen', 'ref_id' => $d['id']])->countAllResults(),
        ];
        $dokumen  = (new \App\Models\DokumenEvidenModel())->listFor('dosen', (int) $d['id']);
        $jenisOpt = \App\Models\DokumenEvidenModel::JENIS['dosen'];

        $checklist = $this->checklistProfil($d, $stats, $db);
        $persen = $checklist ? (int) round(100 * count(array_filter(array_column($checklist, 'done'))) / count($checklist)) : 0;

        return view('akun/index', [
            'dosen' => $d, 'stats' => $stats, 'dokumen' => $dokumen, 'jenisOpt' => $jenisOpt,
            'checklist' => $checklist, 'persen' => $persen,
        ]);
    }

    /** Daftar kelengkapan data DTPR untuk akreditasi (pendorong agar dosen melengkapi). */
    private function checklistProfil(array $d, array $stats, $db): array
    {
        $tsId = (int) ($db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? 0);
        $ewmpTs = $tsId ? (bool) $db->table('dosen_ewmp')->where(['dosen_id' => $d['id'], 'tahun_akademik_id' => $tsId])->countAllResults() : false;

        return [
            ['label' => 'Pendidikan terakhir diisi',            'done' => !empty($d['pendidikan_terakhir']), 'url' => 'akun/profil'],
            ['label' => 'Jabatan akademik diisi',               'done' => !empty($d['jabatan_akademik']),    'url' => 'akun/profil'],
            ['label' => 'Bidang keahlian diisi',                'done' => !empty($d['bidang_keahlian']),     'url' => 'akun/profil'],
            ['label' => 'Sertifikat pendidik (no. serdos)',     'done' => !empty($d['sertifikat_pendidik']) && !empty($d['no_serdos']), 'url' => 'akun/profil'],
            ['label' => 'ORCID diisi (sinkron metrik sitasi)',  'done' => !empty($d['orcid']),               'url' => 'akun/profil'],
            ['label' => 'Minimal 1 publikasi tercatat',         'done' => ($stats['publikasi'] ?? 0) > 0,    'url' => 'akun/publikasi'],
            ['label' => 'EWMP tahun berjalan terisi',           'done' => $ewmpTs,                            'url' => 'akun/ewmp'],
            ['label' => 'Dokumen bukti (ijazah/SK) diunggah',   'done' => ($stats['dokumen'] ?? 0) > 0,      'url' => 'akun'],
        ];
    }

    public function profil()
    {
        $d = $this->dosen();
        if (!$d) {
            return view('akun/not_linked');
        }
        return view('akun/profil', ['dosen' => $d]);
    }

    public function saveProfil()
    {
        $d = $this->dosen();
        if (!$d) {
            return view('akun/not_linked');
        }

        // Hanya field profil diri; prodi_id/aktif/user_id/nidn TIDAK dapat diubah dosen.
        $data = $this->request->getPost([
            'gelar_depan', 'gelar_belakang', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'email', 'no_hp', 'pendidikan_terakhir', 'bidang_keahlian', 'jabatan_akademik',
            'no_sk_jad', 'tgl_sk_jad', 'sertifikat_pendidik', 'no_serdos',
            'sinta_id', 'scopus_id', 'google_scholar_id', 'orcid',
        ]);
        $data['sertifikat_pendidik'] = $this->request->getPost('sertifikat_pendidik') ? 1 : 0;

        $rules = [
            'email' => 'permit_empty|valid_email',
            'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
        ];
        if (!$this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($d['id'], $data);
        return redirect()->to(site_url('akun/profil'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function password()
    {
        if (!$this->dosen()) {
            return view('akun/not_linked');
        }
        return view('akun/password');
    }

    public function savePassword()
    {
        $d = $this->dosen();
        if (!$d) {
            return view('akun/not_linked');
        }
        $baru   = (string) $this->request->getPost('password');
        $ulang  = (string) $this->request->getPost('password_confirm');
        if (strlen($baru) < 8) {
            return redirect()->back()->with('error', 'Password minimal 8 karakter.');
        }
        if ($baru !== $ulang) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }
        $um = new UserModel();
        $user = $um->findById((int) auth()->id());
        $user->password = $baru;
        $um->save($user);
        return redirect()->to(site_url('akun'))->with('success', 'Password berhasil diganti.');
    }
}
