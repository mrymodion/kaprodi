<?php

namespace App\Controllers\Akun;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\PublikasiModel;
use App\Models\PenelitianModel;
use App\Models\PkmModel;
use App\Models\TahunAkademikModel;
use App\Services\EvidenceStorage;
use App\Services\OpenAlexClient;
use App\Services\CrossrefClient;
use App\Services\SemanticScholarClient;
use App\Services\DblpClient;
use App\Services\OrcidClient;
use App\Services\ArxivClient;
use App\Services\DoiImportClient;

/**
 * Self-service dosen: kelola publikasi, penelitian, dan PkM miliknya sendiri.
 * Semua aksi ber-scope ketat pada record dosen yang tertaut ke akun login;
 * penelitian/PkM dibatasi pada kegiatan yang DIKETUAI dosen tersebut.
 */
class KaryaController extends BaseController
{
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

    // ───────────────────────── PUBLIKASI ─────────────────────────

    public function publikasi()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model     = new PublikasiModel();
        $list      = $model->listByDosen((int) $d['id']);
        $unclaimed = $model->listUnclaimedByProdi((int) $d['prodi_id']);
        return view('akun/publikasi/index', [
            'list'      => $list,
            'unclaimed' => $unclaimed,
            'jenis'     => PublikasiModel::JENIS_LABELS,
        ]);
    }

    /** Dosen mengklaim publikasi prodi yang belum dimiliki siapa pun (set dosen_id = dirinya). */
    public function publikasiKlaim(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PublikasiModel();
        $item  = $model->find($id);
        if (!$item || (int) $item['prodi_id'] !== (int) $d['prodi_id']) {
            return redirect()->to(site_url('akun/publikasi'))->with('error', 'Publikasi tidak ditemukan di prodi Anda.');
        }
        if (!empty($item['dosen_id'])) {
            return redirect()->to(site_url('akun/publikasi'))->with('error', 'Publikasi ini sudah dimiliki dosen lain.');
        }
        $model->update($id, ['dosen_id' => (int) $d['id']]);
        return redirect()->to(site_url('akun/publikasi'))->with('success', 'Publikasi diklaim sebagai milik Anda.');
    }

    public function publikasiCreate()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        return view('tridharma/publikasi/form_pub', [
            'item'    => null,
            'action'  => site_url('akun/publikasi/baru'),
            'backUrl' => site_url('akun/publikasi'),
            'jenis'   => PublikasiModel::JENIS_LABELS,
        ]);
    }

    public function publikasiStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PublikasiModel();
        $data = $this->request->getPost([
            'kategori','jenis','judul','penulis','nama_media','tahun','volume','doi','url','sitasi','sinta_score',
        ]);
        $data['prodi_id'] = (int) $d['prodi_id'];
        $data['dosen_id'] = (int) $d['id'];
        if (!$this->validateData($data, $model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $model->insert($data);
        return redirect()->to(site_url('akun/publikasi'))->with('success', 'Publikasi disimpan.');
    }

    public function publikasiEdit(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PublikasiModel();
        $item = $model->find($id);
        if (!$item || (int) $item['dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/publikasi'))->with('error', 'Publikasi tidak ditemukan / bukan milik Anda.');
        }
        return view('tridharma/publikasi/form_pub', [
            'item'    => $item,
            'action'  => site_url("akun/publikasi/{$id}/update"),
            'backUrl' => site_url('akun/publikasi'),
            'jenis'   => PublikasiModel::JENIS_LABELS,
        ]);
    }

    public function publikasiUpdate(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PublikasiModel();
        $item = $model->find($id);
        if (!$item || (int) $item['dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/publikasi'))->with('error', 'Bukan milik Anda.');
        }
        $data = $this->request->getPost([
            'kategori','jenis','judul','penulis','nama_media','tahun','volume','doi','url','sitasi','sinta_score',
        ]);
        $data['prodi_id'] = (int) $item['prodi_id'];
        $data['dosen_id'] = (int) $d['id'];
        if (!$this->validateData($data, $model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $model->update($id, $data);
        return redirect()->to(site_url('akun/publikasi'))->with('success', 'Publikasi diperbarui.');
    }

    public function publikasiDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PublikasiModel();
        $item = $model->find($id);
        if ($item && (int) $item['dosen_id'] === (int) $d['id']) {
            $model->delete($id);
        }
        return redirect()->to(site_url('akun/publikasi'))->with('success', 'Publikasi dihapus.');
    }

    public function publikasiCari()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        return view('tridharma/publikasi/cari', [
            'dosen'     => [$d], // hanya dirinya pada pemilih "Pilih Dosen"
            'searchUrl' => site_url('akun/publikasi/cari/search'),
            'importUrl' => site_url('akun/publikasi/cari/import'),
            'backUrl'   => site_url('akun/publikasi'),
        ]);
    }

    public function publikasiSearch()
    {
        $d = $this->dosen();
        if (!$d) return $this->response->setJSON(['ok' => false, 'error' => 'Akun belum tertaut dosen.']);

        $orcid  = (string) $this->request->getPost('orcid');
        $nama   = (string) $this->request->getPost('nama');
        $source = (string) $this->request->getPost('source');
        $client = match ($source) {
            'crossref'        => new CrossrefClient(),
            'semanticscholar' => new SemanticScholarClient(),
            'dblp'            => new DblpClient(),
            'orcid'           => new OrcidClient(),
            'arxiv'           => new ArxivClient(),
            'doi'             => new DoiImportClient(),
            default           => new OpenAlexClient(),
        };
        $res = $client->searchWorks($orcid, $nama);
        if (empty($res['ok'])) {
            return $this->response->setJSON(['ok' => false, 'error' => $res['error'] ?? 'Pencarian gagal.']);
        }

        [$existDoi, $existTitle] = $this->existingKeys((int) $d['id']);
        foreach ($res['items'] as &$it) {
            $doiKey = strtolower(trim($it['doi']));
            $titleKey = $this->normTitle($it['judul']);
            $it['exists'] = ($doiKey !== '' && isset($existDoi[$doiKey])) || ($titleKey !== '' && isset($existTitle[$titleKey]));
        }
        unset($it);

        return $this->response->setJSON([
            'ok' => true, 'items' => $res['items'], 'warn' => $res['error'] ?? null,
            'csrfName' => csrf_token(), 'csrfHash' => csrf_hash(),
        ]);
    }

    public function publikasiImport()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');

        $rows = $this->request->getPost('rows');
        if (!is_array($rows) || $rows === []) {
            return redirect()->to(site_url('akun/publikasi/cari'))->with('error', 'Tidak ada publikasi yang dipilih.');
        }
        $model = new PublikasiModel();
        $allowed = array_keys(PublikasiModel::JENIS_LABELS);
        $now = date('Y-m-d H:i:s');
        [$seenDoi, $seenTitle] = $this->existingKeys((int) $d['id']);
        $imported = 0; $skipped = 0;

        foreach ($rows as $r) {
            if (empty($r['sel'])) continue;
            $judul = trim((string) ($r['judul'] ?? ''));
            if (strlen($judul) < 5) { $skipped++; continue; }
            $doi = trim((string) ($r['doi'] ?? ''));
            $doiKey = strtolower($doi);
            $titleKey = $this->normTitle($judul);
            if (($doiKey !== '' && isset($seenDoi[$doiKey])) || ($titleKey !== '' && isset($seenTitle[$titleKey]))) {
                $skipped++; continue;
            }
            $jenis = (string) ($r['jenis'] ?? '');
            if (!in_array($jenis, $allowed, true)) $jenis = 'jurnal_internasional';

            $model->insert([
                'prodi_id'   => (int) $d['prodi_id'],
                'dosen_id'   => (int) $d['id'],
                'kategori'   => 'penelitian',
                'jenis'      => $jenis,
                'judul'      => $judul,
                'penulis'    => trim((string) ($r['penulis'] ?? '')),
                'nama_media' => trim((string) ($r['nama_media'] ?? '')),
                'tahun'      => (int) ($r['tahun'] ?? 0) ?: null,
                'volume'     => trim((string) ($r['volume'] ?? '')) ?: null,
                'doi'        => $doi ?: null,
                'url'        => trim((string) ($r['url'] ?? '')) ?: null,
                'sitasi'     => (int) ($r['sitasi'] ?? 0),
                'created_at' => $now,
            ]);
            if ($doiKey !== '')   $seenDoi[$doiKey] = true;
            if ($titleKey !== '') $seenTitle[$titleKey] = true;
            $imported++;
        }
        if ($imported === 0 && $skipped === 0) {
            return redirect()->to(site_url('akun/publikasi/cari'))->with('error', 'Tidak ada publikasi yang dipilih.');
        }
        $msg = "{$imported} publikasi diimpor" . ($skipped ? ", {$skipped} dilewati (duplikat/tidak valid)" : '') . '. Harap verifikasi.';
        return redirect()->to(site_url('akun/publikasi'))->with('success', $msg);
    }

    /** @return array{0:array<string,true>,1:array<string,true>} */
    private function existingKeys(int $dosenId): array
    {
        $doi = []; $title = [];
        foreach ((new PublikasiModel())->select('doi, judul')->where('dosen_id', $dosenId)->findAll() as $r) {
            $dd = strtolower(trim((string) ($r['doi'] ?? '')));
            if ($dd !== '') $doi[$dd] = true;
            $tt = $this->normTitle((string) ($r['judul'] ?? ''));
            if ($tt !== '') $title[$tt] = true;
        }
        return [$doi, $title];
    }

    private function normTitle(string $s): string
    {
        $s = strtolower(trim($s));
        $s = preg_replace('/[^a-z0-9]+/u', ' ', $s);
        return trim((string) preg_replace('/\s+/', ' ', $s));
    }

    // ───────────────────────── PENELITIAN ─────────────────────────

    public function penelitian()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $list = (new PenelitianModel())
            ->select('penelitian.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = penelitian.tahun_akademik_id', 'left')
            ->where('penelitian.ketua_dosen_id', (int) $d['id'])
            ->orderBy('penelitian.tahun_pelaksanaan DESC')
            ->findAll();
        return view('akun/karya/index', [
            'list' => $list, 'tipe' => 'penelitian', 'judul' => 'Penelitian', 'icon' => 'bi-journal-text',
        ]);
    }

    public function penelitianCreate()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        return view('tridharma/penelitian/form', $this->formVars($d, null, [], site_url('akun/penelitian/baru'), site_url('akun/penelitian')));
    }

    public function penelitianStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PenelitianModel();
        if (!$this->validate($model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->penelitianData($d);
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $id = $model->insert($data);
        $model->saveAnggota((int) $id, $this->request->getPost('anggota') ?? []);
        return redirect()->to(site_url('akun/penelitian'))->with('success', 'Penelitian disimpan.');
    }

    public function penelitianEdit(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PenelitianModel();
        $item = $model->find($id);
        if (!$item || (int) $item['ketua_dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/penelitian'))->with('error', 'Bukan milik Anda.');
        }
        $full = $model->getWithAnggota($id);
        return view('tridharma/penelitian/form', $this->formVars($d, $full, array_column($full['anggota'], 'dosen_id'), site_url("akun/penelitian/{$id}/update"), site_url('akun/penelitian')));
    }

    public function penelitianUpdate(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PenelitianModel();
        $item = $model->find($id);
        if (!$item || (int) $item['ketua_dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/penelitian'))->with('error', 'Bukan milik Anda.');
        }
        if (!$this->validate($model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->penelitianData($d, false);
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $item['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $model->update($id, $data);
        $model->saveAnggota($id, $this->request->getPost('anggota') ?? []);
        return redirect()->to(site_url('akun/penelitian'))->with('success', 'Penelitian diperbarui.');
    }

    public function penelitianDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PenelitianModel();
        $item = $model->find($id);
        if ($item && (int) $item['ketua_dosen_id'] === (int) $d['id']) $model->delete($id);
        return redirect()->to(site_url('akun/penelitian'))->with('success', 'Penelitian dihapus.');
    }

    private function penelitianData(array $d, bool $withProdi = true): array
    {
        $data = $this->request->getPost([
            'tahun_akademik_id','judul','sumber_dana','nama_sumber_dana',
            'jumlah_dana','mata_uang','tahun_pelaksanaan','libatkan_mahasiswa','jumlah_mhs_terlibat',
        ]);
        $data['ketua_dosen_id']     = (int) $d['id']; // dipaksa: ketua = dosen yang login
        $data['libatkan_mahasiswa'] = $this->request->getPost('libatkan_mahasiswa') ? 1 : 0;
        $data['jumlah_dana']        = str_replace(['.', ','], ['', ''], $data['jumlah_dana'] ?? '0');
        if ($withProdi) $data['prodi_id'] = (int) $d['prodi_id'];
        return $data;
    }

    // ───────────────────────── PKM ─────────────────────────

    public function pkm()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $list = (new PkmModel())
            ->select('pkm.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = pkm.tahun_akademik_id', 'left')
            ->where('pkm.ketua_dosen_id', (int) $d['id'])
            ->orderBy('pkm.tahun_pelaksanaan DESC')
            ->findAll();
        return view('akun/karya/index', [
            'list' => $list, 'tipe' => 'pkm', 'judul' => 'PkM', 'icon' => 'bi-megaphone',
        ]);
    }

    public function pkmCreate()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        return view('tridharma/pkm/form', $this->formVars($d, null, [], site_url('akun/pkm/baru'), site_url('akun/pkm')));
    }

    public function pkmStore()
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PkmModel();
        if (!$this->validate($model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->pkmData($d);
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $id = $model->insert($data);
        $model->saveAnggota((int) $id, $this->request->getPost('anggota') ?? []);
        return redirect()->to(site_url('akun/pkm'))->with('success', 'PkM disimpan.');
    }

    public function pkmEdit(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PkmModel();
        $item = $model->find($id);
        if (!$item || (int) $item['ketua_dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/pkm'))->with('error', 'Bukan milik Anda.');
        }
        $full = $model->getWithAnggota($id);
        return view('tridharma/pkm/form', $this->formVars($d, $full, array_column($full['anggota'], 'dosen_id'), site_url("akun/pkm/{$id}/update"), site_url('akun/pkm')));
    }

    public function pkmUpdate(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PkmModel();
        $item = $model->find($id);
        if (!$item || (int) $item['ketua_dosen_id'] !== (int) $d['id']) {
            return redirect()->to(site_url('akun/pkm'))->with('error', 'Bukan milik Anda.');
        }
        if (!$this->validate($model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->pkmData($d, false);
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), (bool) $this->request->getPost('hapus_link_bukti'), $item['link_bukti'] ?? null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $model->update($id, $data);
        $model->saveAnggota($id, $this->request->getPost('anggota') ?? []);
        return redirect()->to(site_url('akun/pkm'))->with('success', 'PkM diperbarui.');
    }

    public function pkmDelete(int $id)
    {
        $d = $this->dosen();
        if (!$d) return view('akun/not_linked');
        $model = new PkmModel();
        $item = $model->find($id);
        if ($item && (int) $item['ketua_dosen_id'] === (int) $d['id']) $model->delete($id);
        return redirect()->to(site_url('akun/pkm'))->with('success', 'PkM dihapus.');
    }

    private function pkmData(array $d, bool $withProdi = true): array
    {
        $data = $this->request->getPost([
            'tahun_akademik_id','judul','sumber_dana','nama_sumber_dana','jumlah_dana',
            'mitra','lokasi','tahun_pelaksanaan','libatkan_mahasiswa','jumlah_mhs_terlibat','dampak',
        ]);
        $data['ketua_dosen_id']     = (int) $d['id'];
        $data['libatkan_mahasiswa'] = $this->request->getPost('libatkan_mahasiswa') ? 1 : 0;
        $data['jumlah_dana']        = str_replace(['.', ','], ['', ''], $data['jumlah_dana'] ?? '0');
        if ($withProdi) $data['prodi_id'] = (int) $d['prodi_id'];
        return $data;
    }

    /** Variabel umum untuk form penelitian/PkM versi dosen (ketua dikunci ke dirinya). */
    private function formVars(array $d, ?array $item, array $anggota, string $action, string $backUrl): array
    {
        return [
            'item'         => $item,
            'anggota'      => $anggota,
            'dosen'        => $this->dosenModel->listByProdi((int) $d['prodi_id']), // untuk pilihan anggota
            'taList'       => (new TahunAkademikModel())->findAll(),
            'action'       => $action,
            'backUrl'      => $backUrl,
            'lockKetua'    => (int) $d['id'],
            'lockKetuaNama'=> $this->namaDosen($d),
        ];
    }
}
