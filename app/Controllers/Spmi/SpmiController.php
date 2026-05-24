<?php

namespace App\Controllers\Spmi;

use App\Controllers\BaseController;
use App\Services\EvidenceStorage;
use Config\Database;

/**
 * Modul SPMI (Sistem Penjaminan Mutu Internal):
 *  - Siklus PPEPP (tabel ppepp)
 *  - Audit Mutu Internal / temuan (ami_temuan)
 *  - Dokumen Mutu (dokumen_mutu)
 *  - Registry auditor (spmi_auditor)
 */
class SpmiController extends BaseController
{
    public const TAHAP = [
        'penetapan' => 'Penetapan', 'pelaksanaan' => 'Pelaksanaan', 'evaluasi' => 'Evaluasi',
        'pengendalian' => 'Pengendalian', 'peningkatan' => 'Peningkatan',
    ];
    public const DOK_JENIS = [
        'kebijakan' => 'Kebijakan SPMI', 'manual' => 'Manual Mutu', 'standar' => 'Standar',
        'formulir' => 'Formulir', 'sop' => 'SOP/Prosedur', 'lainnya' => 'Lainnya',
    ];
    public const AMI_KATEGORI = ['mayor' => 'Mayor', 'minor' => 'Minor', 'observasi' => 'Observasi'];
    public const AMI_STATUS   = ['terbuka' => 'Terbuka', 'proses' => 'Proses', 'selesai' => 'Selesai'];

    private function db()
    {
        return Database::connect();
    }

    private function prodiId(): int
    {
        $pid = (int) session('active_prodi_id');
        if (!$pid && session('is_superadmin')) {
            $first = $this->db()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        return $pid;
    }

    private function taList(): array
    {
        return $this->db()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
    }

    // ───────────────────────── PPEPP ─────────────────────────

    public function ppepp(): string
    {
        $prodiId = $this->prodiId();
        $rows = $prodiId ? $this->db()->table('ppepp')->where('prodi_id', $prodiId)
            ->orderBy("siklus DESC, FIELD(tahap,'penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan')")
            ->get()->getResultArray() : [];
        // Kelompokkan per siklus
        $grup = [];
        foreach ($rows as $r) {
            $grup[$r['siklus']][] = $r;
        }
        return view('spmi/ppepp/index', ['grup' => $grup, 'tahap' => self::TAHAP]);
    }

    public function ppeppForm(int $id = 0): string
    {
        $item = $id ? $this->db()->table('ppepp')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->get()->getRowArray() : null;
        return view('spmi/ppepp/form', ['item' => $item, 'tahap' => self::TAHAP]);
    }

    public function ppeppStore(int $id = 0)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi aktif belum dipilih.');
        $tahap = (string) $this->request->getPost('tahap');
        $data = [
            'prodi_id'      => $prodiId,
            'siklus'        => trim((string) $this->request->getPost('siklus')),
            'tahap'         => array_key_exists($tahap, self::TAHAP) ? $tahap : 'penetapan',
            'judul'         => trim((string) $this->request->getPost('judul')),
            'isi'           => trim((string) $this->request->getPost('isi')) ?: null,
            'tindak_lanjut' => trim((string) $this->request->getPost('tindak_lanjut')) ?: null,
        ];
        if ($data['siklus'] === '' || strlen($data['judul']) < 3) {
            return redirect()->back()->withInput()->with('error', 'Isi siklus dan judul (min. 3 karakter).');
        }
        if ($id) {
            $this->db()->table('ppepp')->where(['id' => $id, 'prodi_id' => $prodiId])->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db()->table('ppepp')->insert($data);
        }
        return redirect()->to(site_url('spmi/ppepp'))->with('success', 'Siklus PPEPP disimpan.');
    }

    public function ppeppDelete(int $id)
    {
        $this->db()->table('ppepp')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to(site_url('spmi/ppepp'))->with('success', 'Data dihapus.');
    }

    // ───────────────────────── AMI / TEMUAN ─────────────────────────

    public function ami(): string
    {
        $prodiId = $this->prodiId();
        $rows = $prodiId ? $this->db()->table('ami_temuan t')
            ->select('t.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = t.tahun_akademik_id', 'left')
            ->where('t.prodi_id', $prodiId)->orderBy('t.tanggal_audit DESC, t.id DESC')
            ->get()->getResultArray() : [];
        $ringkas = ['total' => count($rows), 'terbuka' => 0, 'proses' => 0, 'selesai' => 0];
        foreach ($rows as $r) {
            $ringkas[$r['status']] = ($ringkas[$r['status']] ?? 0) + 1;
        }
        return view('spmi/ami/index', [
            'rows' => $rows, 'ringkas' => $ringkas,
            'kategori' => self::AMI_KATEGORI, 'status' => self::AMI_STATUS,
        ]);
    }

    public function amiForm(int $id = 0): string
    {
        $item = $id ? $this->db()->table('ami_temuan')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->get()->getRowArray() : null;
        return view('spmi/ami/form', [
            'item' => $item, 'taList' => $this->taList(),
            'kategori' => self::AMI_KATEGORI, 'status' => self::AMI_STATUS,
        ]);
    }

    public function amiStore(int $id = 0)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi aktif belum dipilih.');
        $kat = (string) $this->request->getPost('kategori');
        $st  = (string) $this->request->getPost('status');
        $data = [
            'prodi_id'          => $prodiId,
            'tahun_akademik_id' => (int) ($this->request->getPost('tahun_akademik_id') ?: 0) ?: null,
            'tanggal_audit'     => $this->request->getPost('tanggal_audit') ?: null,
            'lingkup'           => trim((string) $this->request->getPost('lingkup')),
            'kriteria'          => trim((string) $this->request->getPost('kriteria')) ?: null,
            'kategori'          => array_key_exists($kat, self::AMI_KATEGORI) ? $kat : 'minor',
            'deskripsi'         => trim((string) $this->request->getPost('deskripsi')),
            'akar_masalah'      => trim((string) $this->request->getPost('akar_masalah')) ?: null,
            'rekomendasi'       => trim((string) $this->request->getPost('rekomendasi')) ?: null,
            'tindak_lanjut'     => trim((string) $this->request->getPost('tindak_lanjut')) ?: null,
            'status'            => array_key_exists($st, self::AMI_STATUS) ? $st : 'terbuka',
            'auditor'           => trim((string) $this->request->getPost('auditor')) ?: null,
        ];
        if (strlen($data['lingkup']) < 2 || strlen($data['deskripsi']) < 3) {
            return redirect()->back()->withInput()->with('error', 'Isi lingkup dan deskripsi temuan.');
        }
        $existing = $id ? $this->db()->table('ami_temuan')->where(['id' => $id, 'prodi_id' => $prodiId])->get()->getRowArray() : null;
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $existing['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];

        if ($id && $existing) {
            $this->db()->table('ami_temuan')->where('id', $id)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db()->table('ami_temuan')->insert($data);
        }
        return redirect()->to(site_url('spmi/ami'))->with('success', 'Temuan audit disimpan.');
    }

    public function amiDelete(int $id)
    {
        $this->db()->table('ami_temuan')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to(site_url('spmi/ami'))->with('success', 'Temuan dihapus.');
    }

    // ───────────────────────── DOKUMEN MUTU ─────────────────────────

    public function dokumen(): string
    {
        $prodiId = $this->prodiId();
        $rows = $prodiId ? $this->db()->table('dokumen_mutu')->where('prodi_id', $prodiId)
            ->orderBy("FIELD(jenis,'kebijakan','manual','standar','sop','formulir','lainnya'), judul")
            ->get()->getResultArray() : [];
        return view('spmi/dokumen/index', ['rows' => $rows, 'jenisOpt' => self::DOK_JENIS]);
    }

    public function dokumenStore(int $id = 0)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi aktif belum dipilih.');
        $jenis = (string) $this->request->getPost('jenis');
        $data = [
            'prodi_id'        => $prodiId,
            'jenis'           => array_key_exists($jenis, self::DOK_JENIS) ? $jenis : 'standar',
            'kode'            => trim((string) $this->request->getPost('kode')) ?: null,
            'judul'           => trim((string) $this->request->getPost('judul')),
            'versi'           => trim((string) $this->request->getPost('versi')) ?: null,
            'tanggal_berlaku' => $this->request->getPost('tanggal_berlaku') ?: null,
        ];
        if (strlen($data['judul']) < 3) {
            return redirect()->back()->withInput()->with('error', 'Isi judul dokumen (min. 3 karakter).');
        }
        $existing = $id ? $this->db()->table('dokumen_mutu')->where(['id' => $id, 'prodi_id' => $prodiId])->get()->getRowArray() : null;
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $existing['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Berkas: ' . $res['error']);
        $data['link_bukti'] = $res['value'];

        if ($id && $existing) {
            $this->db()->table('dokumen_mutu')->where('id', $id)->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db()->table('dokumen_mutu')->insert($data);
        }
        return redirect()->to(site_url('spmi/dokumen'))->with('success', 'Dokumen mutu disimpan.');
    }

    public function dokumenDelete(int $id)
    {
        $this->db()->table('dokumen_mutu')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to(site_url('spmi/dokumen'))->with('success', 'Dokumen dihapus.');
    }

    // ───────────────────────── REGISTRY AUDITOR ─────────────────────────

    public function auditor(): string
    {
        $prodiId = $this->prodiId();
        $rows = $prodiId ? $this->db()->table('spmi_auditor a')
            ->select("a.*, TRIM(CONCAT(COALESCE(d.gelar_depan,''), ' ', COALESCE(d.nama,''))) AS dosen_nama")
            ->join('dosen d', 'd.id = a.dosen_id', 'left')
            ->where('a.prodi_id', $prodiId)->orderBy('a.tersertifikasi DESC, a.nama')
            ->get()->getResultArray() : [];
        $dosenList = $prodiId ? $this->db()->table('dosen')
            ->select('id, nama, gelar_depan, gelar_belakang')
            ->where(['prodi_id' => $prodiId, 'aktif' => 1])->orderBy('nama')->get()->getResultArray() : [];
        $ringkas = ['total' => count($rows), 'tersertifikasi' => 0];
        foreach ($rows as $r) {
            if ($r['tersertifikasi']) $ringkas['tersertifikasi']++;
        }
        return view('spmi/auditor/index', ['rows' => $rows, 'dosenList' => $dosenList, 'ringkas' => $ringkas]);
    }

    public function auditorStore()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->back()->with('error', 'Prodi aktif belum dipilih.');
        $dosenId = (int) $this->request->getPost('dosen_id') ?: null;
        $nama = trim((string) $this->request->getPost('nama'));
        if ($dosenId && $nama === '') {
            $d = $this->db()->table('dosen')->where('id', $dosenId)->get()->getRowArray();
            if ($d) $nama = trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : ''));
        }
        if ($nama === '') {
            return redirect()->back()->with('error', 'Pilih dosen atau isi nama auditor.');
        }
        $this->db()->table('spmi_auditor')->insert([
            'prodi_id'       => $prodiId,
            'dosen_id'       => $dosenId,
            'nama'           => $nama,
            'tersertifikasi' => $this->request->getPost('tersertifikasi') ? 1 : 0,
            'no_sertifikat'  => trim((string) $this->request->getPost('no_sertifikat')) ?: null,
            'lembaga'        => trim((string) $this->request->getPost('lembaga')) ?: null,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(site_url('spmi/auditor'))->with('success', 'Auditor ditambahkan.');
    }

    public function auditorDelete(int $id)
    {
        $this->db()->table('spmi_auditor')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to(site_url('spmi/auditor'))->with('success', 'Auditor dihapus.');
    }
}
