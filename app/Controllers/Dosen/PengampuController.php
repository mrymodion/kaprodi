<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\KurikulumModel;
use App\Models\MataKuliahModel;
use App\Models\PengampuMkModel;
use Config\Database;

/**
 * Admin/Kaprodi: penugasan mengajar (mata kuliah diampu) per dosen & tahun akademik,
 * sekaligus generate Surat Tugas Mengajar (PDF) per dosen. Data ditulis ke tabel
 * pengampu_mk yang sama dengan self-service, sehingga hitung EWMP otomatis tetap jalan.
 */
class PengampuController extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    private function taList(): array
    {
        return Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray();
    }

    private function currentTaId(): int
    {
        $db = Database::connect();
        return (int) ($this->request->getGet('ta_id')
            ?: ($db->table('tahun_akademik')->where('is_ts', 1)->get()->getRowArray()['id'] ?? 0));
    }

    /** Dosen milik prodi aktif (pengaman scope). */
    private function dosenInProdi(int $dosenId): ?array
    {
        $d = (new DosenModel())->find($dosenId);
        return ($d && (int) $d['prodi_id'] === $this->prodiId()) ? $d : null;
    }

    private static function namaLengkap(array $d): string
    {
        return trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : ''));
    }

    // ───────────────────────── Daftar ─────────────────────────

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to(site_url('dosen'))->with('error', 'Pilih prodi aktif terlebih dahulu.');

        $taId  = $this->currentTaId();
        $dosen = (new DosenModel())->listByProdi($prodiId);

        // Rekap jumlah MK & total SKS pendidikan tiap dosen pada TA terpilih.
        $rekap = [];
        if ($taId) {
            $rows = Database::connect()->table('pengampu_mk')
                ->select('dosen_id, COUNT(*) AS jml, SUM(sks * jumlah_kelas) AS total_sks')
                ->where('tahun_akademik_id', $taId)
                ->groupBy('dosen_id')->get()->getResultArray();
            foreach ($rows as $r) $rekap[(int) $r['dosen_id']] = $r;
        }

        $kid = (int) (Database::connect()->table('prodi')->select('kaprodi_dosen_id')->where('id', $prodiId)->get()->getRowArray()['kaprodi_dosen_id'] ?? 0);
        $kd  = $kid ? (new DosenModel())->find($kid) : null;

        return view('dosen/pengampu/index', [
            'dosen'       => $dosen,
            'rekap'       => $rekap,
            'taId'        => $taId,
            'taList'      => $this->taList(),
            'kopAtas'     => $this->kopDataUri('kop_path'),
            'kopBawah'    => $this->kopDataUri('kop_footer_path'),
            'kaprodiNama' => $kd ? self::namaLengkap($kd) : '',
        ]);
    }

    // ───────────────────────── Kop Surat (header & footer) ─────────────────────────

    private const KOP_COLS = ['atas' => 'kop_path', 'bawah' => 'kop_footer_path'];

    private function kopDir(): string
    {
        $d = WRITEPATH . 'kop/';
        if (!is_dir($d)) @mkdir($d, 0775, true);
        return $d;
    }

    public function uploadKop()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to(site_url('dosen/pengampu'))->with('error', 'Pilih prodi aktif terlebih dahulu.');

        $diunggah = 0;
        foreach (self::KOP_COLS as $jenis => $col) {
            $file = $this->request->getFile('kop_' . $jenis);
            if (!$file || !$file->isValid() || $file->getSize() === 0) continue; // dilewati bila tak diisi

            $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                return redirect()->back()->with('error', 'Kop surat harus berupa gambar JPG atau PNG.');
            }
            if ($file->getSize() > 3 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran gambar maksimal 3 MB.');
            }
            [$w] = @getimagesize($file->getTempName()) ?: [0];
            if ($w < 1) {
                return redirect()->back()->with('error', 'Berkas bukan gambar yang sah.');
            }

            $this->deleteKopFile($prodiId, $col);
            $name = 'kop_' . $jenis . '_' . $prodiId . '_' . bin2hex(random_bytes(4)) . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
            $file->move($this->kopDir(), $name);
            Database::connect()->table('prodi')->where('id', $prodiId)->update([$col => $name]);
            $diunggah++;
        }

        if (!$diunggah) return redirect()->back()->with('error', 'Pilih minimal satu gambar (kop atas atau kop bawah).');
        return redirect()->to(site_url('dosen/pengampu'))->with('success', 'Kop surat diperbarui.');
    }

    public function hapusKop()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to(site_url('dosen/pengampu'));
        $jenis = (string) $this->request->getPost('jenis');
        $col = self::KOP_COLS[$jenis] ?? null;
        if ($col) {
            $this->deleteKopFile($prodiId, $col);
            Database::connect()->table('prodi')->where('id', $prodiId)->update([$col => null]);
        }
        return redirect()->to(site_url('dosen/pengampu'))->with('success', 'Kop surat dihapus.');
    }

    private function deleteKopFile(int $prodiId, string $col): void
    {
        $row = Database::connect()->table('prodi')->select($col)->where('id', $prodiId)->get()->getRowArray();
        if (!empty($row[$col]) && is_file($this->kopDir() . basename($row[$col]))) {
            @unlink($this->kopDir() . basename($row[$col]));
        }
    }

    /** Gambar kop (header/footer) sebagai data URI untuk preview & dompdf; null bila kosong. */
    private function kopDataUri(string $col): ?string
    {
        $row = Database::connect()->table('prodi')->select($col)->where('id', $this->prodiId())->get()->getRowArray();
        if (empty($row[$col])) return null;
        $p = $this->kopDir() . basename($row[$col]);
        if (!is_file($p) || !is_readable($p)) return null;
        $ext = strtolower(pathinfo($p, PATHINFO_EXTENSION)) ?: 'png';
        return 'data:image/' . ($ext === 'jpg' ? 'jpeg' : $ext) . ';base64,' . base64_encode((string) file_get_contents($p));
    }

    // ───────────────────────── Kelola MK per dosen ─────────────────────────

    public function kelola(int $dosenId)
    {
        $d = $this->dosenInProdi($dosenId);
        if (!$d) return redirect()->to(site_url('dosen/pengampu'))->with('error', 'Dosen tidak ditemukan di prodi Anda.');

        $taId = $this->currentTaId();
        $mkList = [];
        $kur = (new KurikulumModel())->aktifByProdi($this->prodiId());
        if ($kur) $mkList = (new MataKuliahModel())->byKurikulum((int) $kur['id']);

        return view('dosen/pengampu/kelola', [
            'dosen'    => $d,
            'namaDosen'=> self::namaLengkap($d),
            'taId'     => $taId,
            'taList'   => $this->taList(),
            'mkList'   => $mkList,
            'list'     => $taId ? (new PengampuMkModel())->listByDosenTa($dosenId, $taId) : [],
            'kategori' => PengampuMkModel::KATEGORI_LABELS,
        ]);
    }

    public function store()
    {
        $dosenId = (int) $this->request->getPost('dosen_id');
        $d = $this->dosenInProdi($dosenId);
        if (!$d) return redirect()->to(site_url('dosen/pengampu'))->with('error', 'Dosen tidak valid.');

        $taId = (int) $this->request->getPost('tahun_akademik_id');
        if (!$taId) return redirect()->back()->with('error', 'Pilih tahun akademik.');

        $kategori = (string) $this->request->getPost('kategori');
        if (!array_key_exists($kategori, PengampuMkModel::KATEGORI_LABELS)) $kategori = 'ps_sendiri';

        $mkId   = (int) ($this->request->getPost('mata_kuliah_id') ?: 0);
        $namaMk = trim((string) $this->request->getPost('nama_mk'));
        $kodeMk = trim((string) $this->request->getPost('kode_mk'));
        $sks    = (float) $this->request->getPost('sks');

        if ($mkId) {
            $mk  = (new MataKuliahModel())->find($mkId);
            $kur = $mk ? (new KurikulumModel())->find((int) $mk['kurikulum_id']) : null;
            if (!$mk || !$kur || (int) $kur['prodi_id'] !== $this->prodiId()) {
                return redirect()->back()->withInput()->with('error', 'Mata kuliah tidak ditemukan di prodi Anda.');
            }
            $namaMk = trim($mk['nama']);
            $kodeMk = trim((string) $mk['kode']);
            $sks    = (float) ($mk['sks_teori'] + $mk['sks_praktek'] + $mk['sks_lapangan']);
        } else {
            if (mb_strlen($namaMk) < 3) return redirect()->back()->withInput()->with('error', 'Isi nama mata kuliah.');
            if ($sks <= 0)             return redirect()->back()->withInput()->with('error', 'Isi SKS mata kuliah (> 0).');
        }

        (new PengampuMkModel())->insert([
            'dosen_id'          => $dosenId,
            'tahun_akademik_id' => $taId,
            'mata_kuliah_id'    => $mkId ?: null,
            'kode_mk'           => $kodeMk ?: null,
            'nama_mk'           => $namaMk,
            'kategori'          => $kategori,
            'sks'               => $sks,
            'jumlah_kelas'      => max(1, (int) $this->request->getPost('jumlah_kelas')),
            'created_at'        => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(site_url("dosen/pengampu/{$dosenId}/kelola?ta_id={$taId}"))->with('success', 'Mata kuliah ditambahkan.');
    }

    public function delete(int $id)
    {
        $model = new PengampuMkModel();
        $item  = $model->find($id);
        if (!$item) return redirect()->to(site_url('dosen/pengampu'));
        $dosenId = (int) $item['dosen_id'];
        $taId    = (int) $item['tahun_akademik_id'];
        if ($this->dosenInProdi($dosenId)) $model->delete($id);
        return redirect()->to(site_url("dosen/pengampu/{$dosenId}/kelola?ta_id={$taId}"))->with('success', 'Data dihapus.');
    }

    // ───────────────────────── Surat Tugas Mengajar (PDF) ─────────────────────────

    public function suratForm(int $dosenId)
    {
        $d = $this->dosenInProdi($dosenId);
        if (!$d) return redirect()->to(site_url('dosen/pengampu'))->with('error', 'Dosen tidak ditemukan di prodi Anda.');

        $taId = $this->currentTaId();
        $list = $taId ? (new PengampuMkModel())->listByDosenTa($dosenId, $taId) : [];
        $signer = $this->kaprodiSigner();

        return view('dosen/pengampu/surat_form', [
            'dosen'     => $d,
            'namaDosen' => self::namaLengkap($d),
            'taId'      => $taId,
            'taList'    => $this->taList(),
            'list'      => $list,
            'signer'    => $signer,
            'kop'       => $this->letterhead(),
            'kategori'  => PengampuMkModel::KATEGORI_LABELS,
        ]);
    }

    public function suratGenerate(int $dosenId)
    {
        $d = $this->dosenInProdi($dosenId);
        if (!$d) return redirect()->to(site_url('dosen/pengampu'))->with('error', 'Dosen tidak ditemukan di prodi Anda.');

        $taId = (int) $this->request->getPost('tahun_akademik_id');
        $list = $taId ? (new PengampuMkModel())->listByDosenTa($dosenId, $taId) : [];
        if (empty($list)) {
            return redirect()->back()->with('error', 'Belum ada mata kuliah diampu pada TA tersebut.');
        }

        $db = Database::connect();
        $ta = $db->table('tahun_akademik')->where('id', $taId)->get()->getRowArray() ?: ['kode' => '-'];

        $data = [
            'kop'        => $this->letterhead(),
            'logo'       => $this->logoDataUri(),
            'kopImage'   => $this->kopDataUri('kop_path'),
            'kopFooter'  => $this->kopDataUri('kop_footer_path'),
            'dosen'      => $d,
            'namaDosen'  => self::namaLengkap($d),
            'ta'         => $ta,
            'list'       => $list,
            'kategori'   => PengampuMkModel::KATEGORI_LABELS,
            'nomor'      => trim((string) $this->request->getPost('nomor')) ?: '...../...../...../' . date('Y'),
            'kota'       => trim((string) $this->request->getPost('kota')) ?: '-',
            'tanggal'    => trim((string) $this->request->getPost('tanggal')) ?: date('d-m-Y'),
            'dasar'      => trim((string) $this->request->getPost('dasar')),
            'signerNama' => trim((string) $this->request->getPost('signer_nama')),
            'signerNip'  => trim((string) $this->request->getPost('signer_nip')),
            'qr'         => (new \App\Services\SuratValidator())->qrDataUri($dosenId, $taId),
        ];

        $html   = view('dosen/pengampu/surat_tugas', $data);
        $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => false, 'defaultFont' => 'Helvetica']);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fname = 'Surat_Tugas_Mengajar_' . preg_replace('/[^A-Za-z0-9]/', '_', $d['nama']) . '_' . preg_replace('/[^A-Za-z0-9]/', '_', $ta['kode']) . '.pdf';
        $dompdf->stream($fname, ['Attachment' => true]);
        exit;
    }

    /** Data kop surat dari prodi → upps → perguruan tinggi. */
    private function letterhead(): array
    {
        $row = Database::connect()->table('prodi p')
            ->select('p.nama AS prodi_nama, p.jenjang, p.gelar,
                      u.nama AS upps_nama, u.jenis AS upps_jenis,
                      pt.nama AS pt_nama, pt.alamat AS pt_alamat, pt.kota AS pt_kota, pt.provinsi AS pt_provinsi, pt.website AS pt_website, pt.logo_path')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->where('p.id', $this->prodiId())->get()->getRowArray();
        return $row ?: ['prodi_nama' => '-', 'jenjang' => '', 'upps_nama' => '', 'pt_nama' => '-', 'pt_alamat' => '', 'logo_path' => null];
    }

    /** Penandatangan default = kaprodi yang ditunjuk; fallback ke user_prodi, lalu akun login. */
    private function kaprodiSigner(): array
    {
        $db = Database::connect();

        // 1) Dosen yang ditunjuk sebagai kaprodi pada data prodi.
        $kid = (int) ($db->table('prodi')->select('kaprodi_dosen_id')->where('id', $this->prodiId())->get()->getRowArray()['kaprodi_dosen_id'] ?? 0);
        $row = $kid ? (new DosenModel())->find($kid) : null;

        // 2) Dosen yang terdaftar sebagai kaprodi pada prodi aktif (via user_prodi).
        if (!$row || empty($row['nama'])) {
            $row = $db->table('user_prodi up')
                ->select('d.nama, d.nidn, d.nip, d.gelar_depan, d.gelar_belakang')
                ->join('dosen d', 'd.user_id = up.user_id')
                ->where(['up.prodi_id' => $this->prodiId(), 'up.peran' => 'kaprodi'])
                ->get()->getRowArray();
        }

        // 3) Fallback: profil dosen milik akun yang sedang login (umumnya kaprodi pembuat surat).
        if (!$row || empty($row['nama'])) {
            $uid = (int) (auth()->id() ?? 0);
            $row = $uid ? (new DosenModel())->findByUserId($uid) : null;
        }

        if (!$row || empty($row['nama'])) return ['nama' => '', 'nip' => ''];
        return [
            'nama' => self::namaLengkap($row),
            'nip'  => $row['nip'] ?: ($row['nidn'] ?: ''),
        ];
    }

    /** Logo perguruan tinggi sebagai data URI (aman untuk dompdf tanpa remote). */
    private function logoDataUri(): ?string
    {
        $kop = $this->letterhead();
        $candidates = [];
        if (!empty($kop['logo_path'])) {
            $candidates[] = FCPATH . ltrim($kop['logo_path'], '/');
            $candidates[] = WRITEPATH . ltrim($kop['logo_path'], '/');
        }
        $candidates[] = FCPATH . 'assets/logo-utd.png';
        foreach ($candidates as $p) {
            if (is_file($p) && is_readable($p)) {
                $ext = strtolower(pathinfo($p, PATHINFO_EXTENSION)) ?: 'png';
                return 'data:image/' . ($ext === 'jpg' ? 'jpeg' : $ext) . ';base64,' . base64_encode((string) file_get_contents($p));
            }
        }
        return null;
    }
}
