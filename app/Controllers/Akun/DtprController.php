<?php

namespace App\Controllers\Akun;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\DosenEwmpModel;
use App\Models\HkiModel;
use App\Models\RekognisiDosenModel;
use App\Services\EvidenceStorage;
use Config\Database;

/**
 * Self-service DTPR untuk data pendukung akreditasi:
 * EWMP, pengembangan diri (3.A.3), HKI (3.C.3/4.C.3), dan rekognisi dosen (C3).
 * Semua aksi ber-scope ketat pada record dosen yang tertaut ke akun login.
 */
class DtprController extends BaseController
{
    use \App\Controllers\Concerns\ScanHki;

    private DosenModel $dosenModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
    }

    private function dosen(): ?array
    {
        $uid = (int) (auth()->id() ?? 0);
        return $uid ? $this->dosenModel->findByUserId($uid) : null;
    }

    private function namaDosen(array $d): string
    {
        return trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : ''));
    }

    private function taList(): array
    {
        return Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
    }

    // ───────────────────────── EWMP ─────────────────────────

    public function ewmp()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $db    = Database::connect();
        $taId  = (int) ($this->request->getGet('ta_id') ?: ($db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? 0));
        $ewmp  = $taId ? ((new DosenEwmpModel())->getByDosenTa((int) $d['id'], $taId) ?? []) : [];
        $riwayat = $db->table('dosen_ewmp de')
            ->select('de.*, ta.kode AS ta_kode, ta.is_ts')
            ->join('tahun_akademik ta', 'ta.id = de.tahun_akademik_id')
            ->where('de.dosen_id', (int) $d['id'])
            ->orderBy('ta.tahun_mulai DESC')->get()->getResultArray();
        return view('akun/ewmp/index', [
            'dosen' => $d, 'ewmp' => $ewmp, 'taId' => $taId,
            'taList' => $this->taList(), 'riwayat' => $riwayat,
        ]);
    }

    public function ewmpStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $taId = (int) $this->request->getPost('tahun_akademik_id');
        if (!$taId) {
            return redirect()->back()->with('error', 'Pilih tahun akademik.');
        }
        $fields = ['sks_pendidikan_ps', 'sks_pendidikan_ps_lain_pt', 'sks_pendidikan_pt_lain',
                   'sks_penelitian', 'sks_pkm', 'sks_tugas_tambahan'];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = (float) ($this->request->getPost($f) ?? 0);
        }
        (new DosenEwmpModel())->upsert((int) $d['id'], $taId, $data);
        return redirect()->to(site_url("akun/ewmp?ta_id={$taId}"))->with('success', 'EWMP berhasil disimpan.');
    }

    // ───────────────────────── PENGEMBANGAN DIRI (3.A.3) ─────────────────────────

    public function pengembangan()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $list = Database::connect()->table('pengembangan_dtpr pd')
            ->select('pd.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = pd.tahun_akademik_id', 'left')
            ->where('pd.dosen_id', (int) $d['id'])
            ->orderBy('pd.id DESC')->get()->getResultArray();
        return view('akun/pengembangan/index', ['list' => $list, 'taList' => $this->taList()]);
    }

    public function pengembanganStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $jenis = trim((string) $this->request->getPost('jenis_pengembangan'));
        if (strlen($jenis) < 3) {
            return redirect()->back()->with('error', 'Isi jenis/nama kegiatan pengembangan.');
        }
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        Database::connect()->table('pengembangan_dtpr')->insert([
            'prodi_id'           => (int) $d['prodi_id'],
            'dosen_id'           => (int) $d['id'],
            'tahun_akademik_id'  => (int) ($this->request->getPost('tahun_akademik_id') ?: 0) ?: null,
            'nama_dtpr'          => $this->namaDosen($d),
            'jenis_pengembangan' => $jenis,
            'jumlah'             => max(1, (int) $this->request->getPost('jumlah')),
            'link_bukti'         => $res['value'],
        ]);
        return redirect()->to(site_url('akun/pengembangan'))->with('success', 'Pengembangan diri disimpan.');
    }

    public function pengembanganDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        Database::connect()->table('pengembangan_dtpr')
            ->where(['id' => $id, 'dosen_id' => (int) $d['id']])->delete();
        return redirect()->to(site_url('akun/pengembangan'))->with('success', 'Data dihapus.');
    }

    // ───────────────────────── HKI (3.C.3 / 4.C.3) ─────────────────────────

    public function hki()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new HkiModel();
        return view('akun/hki/index', [
            'list'      => $model->listByDosen((int) $d['id']),
            'claimable' => $model->listClaimableByProdi((int) $d['prodi_id']),
            'jenis'     => HkiModel::JENIS_LABELS,
        ]);
    }

    public function hkiForm(int $id = 0)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $item = null;
        if ($id) {
            $item = (new HkiModel())->find($id);
            if (!$item || (int) $item['dosen_id'] !== (int) $d['id']) {
                return redirect()->to(site_url('akun/hki'))->with('error', 'HKI tidak ditemukan / bukan milik Anda.');
            }
        }
        return view('akun/hki/form', [
            'item'   => $item,
            'jenis'  => HkiModel::JENIS_LABELS,
            'action' => $id ? site_url("akun/hki/{$id}/update") : site_url('akun/hki/baru'),
        ]);
    }

    public function hkiStore(int $id = 0)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new HkiModel();

        $existing = null;
        if ($id) {
            $existing = $model->find($id);
            if (!$existing || (int) $existing['dosen_id'] !== (int) $d['id']) {
                return redirect()->to(site_url('akun/hki'))->with('error', 'Bukan milik Anda.');
            }
        }

        $jenis = (string) $this->request->getPost('jenis');
        $data = [
            'prodi_id'   => (int) $d['prodi_id'],
            'dosen_id'   => (int) $d['id'],
            'kategori'   => $this->request->getPost('kategori') === 'pkm' ? 'pkm' : 'penelitian',
            'jenis'      => array_key_exists($jenis, HkiModel::JENIS_LABELS) ? $jenis : 'hak_cipta',
            'judul'      => trim((string) $this->request->getPost('judul')),
            'no_hki'     => trim((string) $this->request->getPost('no_hki')) ?: null,
            'tahun'      => (int) ($this->request->getPost('tahun') ?: 0) ?: null,
            'pemilik'    => trim((string) $this->request->getPost('pemilik')) ?: $this->namaDosen($d),
            'status'     => $this->request->getPost('status') === 'terdaftar' ? 'terdaftar' : 'granted',
        ];
        if (!$this->validateData($data, $model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $res = (new EvidenceStorage())->syncUpload(
            $this->request->getFile('link_bukti'),
            (bool) $this->request->getPost('hapus_link_bukti'),
            $existing['link_bukti'] ?? null
        );
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];

        if ($id && $existing) {
            $model->update($id, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $model->insert($data);
        }
        return redirect()->to(site_url('akun/hki'))->with('success', 'HKI disimpan.');
    }

    public function hkiScan()
    {
        if (!$this->dosen()) return $this->response->setJSON(['ok' => false, 'error' => 'Akun belum tertaut dosen.']);
        return $this->scanHkiResponse();
    }

    public function hkiKlaim(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new HkiModel();
        $item  = $model->find($id);
        if (!$item || (int) $item['prodi_id'] !== (int) $d['prodi_id']) {
            return redirect()->to(site_url('akun/hki'))->with('error', 'HKI tidak ditemukan di prodi Anda.');
        }
        if (!empty($item['dosen_id'])) {
            return redirect()->to(site_url('akun/hki'))->with('error', 'HKI ini sudah dimiliki dosen lain.');
        }
        $model->update($id, ['dosen_id' => (int) $d['id']]);
        return redirect()->to(site_url('akun/hki'))->with('success', 'HKI diklaim sebagai milik Anda.');
    }

    public function hkiDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new HkiModel();
        $item  = $model->find($id);
        if ($item && (int) $item['dosen_id'] === (int) $d['id']) {
            $model->delete($id);
        }
        return redirect()->to(site_url('akun/hki'))->with('success', 'HKI dihapus.');
    }

    // ───────────────────────── REKOGNISI DOSEN (C3) ─────────────────────────

    public function rekognisi()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        return view('akun/rekognisi/index', [
            'list'   => (new RekognisiDosenModel())->listByDosen((int) $d['id']),
            'jenis'  => RekognisiDosenModel::JENIS_LABELS,
            'taList' => $this->taList(),
        ]);
    }

    public function rekognisiStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new RekognisiDosenModel();
        $jenis = (string) $this->request->getPost('jenis');
        $data = [
            'prodi_id'          => (int) $d['prodi_id'],
            'dosen_id'          => (int) $d['id'],
            'tahun_akademik_id' => (int) ($this->request->getPost('tahun_akademik_id') ?: 0) ?: null,
            'jenis'             => array_key_exists($jenis, RekognisiDosenModel::JENIS_LABELS) ? $jenis : 'lainnya',
            'judul'             => trim((string) $this->request->getPost('judul')),
            'penyelenggara'     => trim((string) $this->request->getPost('penyelenggara')) ?: null,
            'tingkat'           => in_array($this->request->getPost('tingkat'), ['lokal', 'nasional', 'internasional'], true) ? $this->request->getPost('tingkat') : 'nasional',
            'tahun'             => (int) ($this->request->getPost('tahun') ?: 0) ?: null,
            'created_at'        => date('Y-m-d H:i:s'),
        ];
        if (!$this->validateData($data, $model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $model->insert($data);
        return redirect()->to(site_url('akun/rekognisi'))->with('success', 'Rekognisi disimpan.');
    }

    public function rekognisiDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new RekognisiDosenModel();
        $item  = $model->find($id);
        if ($item && (int) $item['dosen_id'] === (int) $d['id']) {
            $model->delete($id);
        }
        return redirect()->to(site_url('akun/rekognisi'))->with('success', 'Rekognisi dihapus.');
    }
}
