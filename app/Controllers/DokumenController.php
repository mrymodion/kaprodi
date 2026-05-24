<?php

namespace App\Controllers;

use App\Models\DokumenEvidenModel;
use App\Services\EvidenceStorage;

/**
 * Unggah/lihat/hapus dokumen eviden (PDF) untuk dosen & tendik.
 */
class DokumenController extends BaseController
{
    protected DokumenEvidenModel $model;

    public function __construct()
    {
        $this->model = new DokumenEvidenModel();
    }

    private function allowed(): bool
    {
        $u = auth()->user();
        return $u && ($u->can('dosen.manage') || $u->can('prodi.manage'));
    }

    /** Dosen boleh mengelola dokumen miliknya sendiri (ref 'dosen' & id = record-nya). */
    private function ownsDosen(string $ref, int $id): bool
    {
        if ($ref !== 'dosen') return false;
        $u = auth()->user();
        if (!$u) return false;
        $d = (new \App\Models\DosenModel())->findByUserId((int) $u->id);
        return $d && (int) $d['id'] === $id;
    }

    public function upload(string $ref, int $id)
    {
        if (!$this->allowed() && !$this->ownsDosen($ref, $id)) return redirect()->back()->with('error', 'Akses ditolak.');
        if (!isset(DokumenEvidenModel::JENIS[$ref])) {
            return redirect()->back()->with('error', 'Jenis pemilik dokumen tidak valid.');
        }

        $jenis = $this->request->getPost('jenis');
        if (!isset(DokumenEvidenModel::JENIS[$ref][$jenis])) {
            return redirect()->back()->with('error', 'Jenis dokumen tidak valid.');
        }

        $file = $this->request->getFile('dokumen_pdf');
        if (!$file || !$file->isValid() || $file->getSize() === 0) {
            return redirect()->back()->with('error', 'Pilih berkas PDF terlebih dahulu.');
        }

        $res = (new EvidenceStorage())->storePdf($file);
        if (!$res['ok']) {
            return redirect()->back()->with('error', 'Gagal unggah: ' . $res['error']);
        }

        $this->model->insert([
            'ref_table' => $ref,
            'ref_id'    => $id,
            'jenis'     => $jenis,
            'file'      => $res['name'],
            'nama_asli' => $file->getClientName(),
        ]);

        $info = $res['compressed']
            ? ' (dikompres)'
            : ' (kompresi dilewati — Ghostscript tidak terpasang)';
        return redirect()->back()->with('success', 'Dokumen berhasil diunggah' . $info . '.');
    }

    public function lihat(int $id)
    {
        $row = $this->model->find($id);
        if (!$row) throw new \CodeIgniter\Exceptions\PageNotFoundException();
        if (!$this->allowed() && !$this->ownsDosen($row['ref_table'], (int) $row['ref_id'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        $path = WRITEPATH . 'bukti/' . basename($row['file']);
        if (!is_file($path)) throw new \CodeIgniter\Exceptions\PageNotFoundException('Berkas tidak ditemukan.');
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($row['file']) . '"')
            ->setBody(file_get_contents($path));
    }

    public function delete(int $id)
    {
        $row = $this->model->find($id);
        if ($row && !$this->allowed() && !$this->ownsDosen($row['ref_table'], (int) $row['ref_id'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        if ($row) {
            (new EvidenceStorage())->delete($row['file']);
            $this->model->delete($id);
        }
        return redirect()->back()->with('success', 'Dokumen dihapus.');
    }
}
