<?php

namespace App\Controllers\Akreditasi;

use App\Controllers\BaseController;
use App\Models\KondisiEksternalModel;

/**
 * Sprint 2 Kriteria A: input terstruktur Kondisi Eksternal.
 * Output dipakai sebagai sumber narasi LED (Sprint 3 AI assist) dan
 * sumber eviden otomatis untuk PPEPP (via EvidenAutoTarik).
 */
class KondisiEksternal extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $m = new KondisiEksternalModel();
        return view('akreditasi/kondisi_eksternal/index', [
            'grouped' => $m->byProdiGrouped($prodiId),
            'jumlah'  => $m->countByProdi($prodiId),
        ]);
    }

    public function form(?int $id = null)
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $row = null;
        if ($id) {
            $row = (new KondisiEksternalModel())->find($id);
            if (!$row || (int) $row['prodi_id'] !== $prodiId) {
                return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('error', 'Entri tidak ditemukan.');
            }
        }

        return view('akreditasi/kondisi_eksternal/form', [
            'row'      => $row,
            'kategoriDefault' => $this->request->getGet('kategori'),
            'mode'     => $row ? 'edit' : 'create',
        ]);
    }

    public function store()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $data = $this->collectInput($prodiId);
        if (!$data) return redirect()->back()->withInput()->with('error', 'Field wajib belum lengkap.');

        $id = (int) $this->request->getPost('id');
        $m = new KondisiEksternalModel();
        if ($id) {
            $existing = $m->find($id);
            if (!$existing || (int) $existing['prodi_id'] !== $prodiId) {
                return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('error', 'Entri tidak ditemukan.');
            }
            $m->update($id, $data);
        } else {
            $m->insert($data);
        }
        return redirect()->to(site_url('akreditasi/kondisi-eksternal') . '?kategori=' . $data['kategori'])
            ->with('success', $id ? 'Entri diperbarui.' : 'Entri ditambahkan.');
    }

    public function delete(int $id)
    {
        $prodiId = $this->prodiId();
        $m = new KondisiEksternalModel();
        $row = $m->find($id);
        if ($row && (int) $row['prodi_id'] === $prodiId) $m->delete($id);
        return redirect()->to(site_url('akreditasi/kondisi-eksternal'))->with('success', 'Entri dihapus.');
    }

    public function narasi()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $grouped = (new KondisiEksternalModel())->byProdiGrouped($prodiId);
        return view('akreditasi/kondisi_eksternal/narasi', ['grouped' => $grouped]);
    }

    private function collectInput(int $prodiId): ?array
    {
        $kategori = (string) $this->request->getPost('kategori');
        if (!array_key_exists($kategori, KondisiEksternalModel::KATEGORI)) return null;

        $judul = trim((string) $this->request->getPost('judul'));
        $desk  = trim((string) $this->request->getPost('deskripsi'));
        if ($judul === '' || $desk === '') return null;

        $dampak = (string) $this->request->getPost('dampak');
        if (!array_key_exists($dampak, KondisiEksternalModel::DAMPAK_LABELS)) $dampak = 'sedang';

        $tahun = (int) $this->request->getPost('tahun_relevansi');

        return [
            'prodi_id'         => $prodiId,
            'kategori'         => $kategori,
            'judul'            => $judul,
            'deskripsi'        => $desk,
            'dampak'           => $dampak,
            'respons_prodi'    => trim((string) $this->request->getPost('respons_prodi')) ?: null,
            'sumber_referensi' => trim((string) $this->request->getPost('sumber_referensi')) ?: null,
            'link_bukti'       => trim((string) $this->request->getPost('link_bukti')) ?: null,
            'tahun_relevansi'  => ($tahun >= 2000 && $tahun <= 2100) ? $tahun : null,
            'urutan'           => (int) $this->request->getPost('urutan'),
        ];
    }
}
