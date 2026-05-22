<?php

namespace App\Controllers\Tracer;

use App\Controllers\BaseController;
use App\Models\KepuasanModel;
use App\Services\TracerToken;
use Config\Database;

/**
 * Form publik Survei Kepuasan Pengguna Lulusan (LKPS 2.B.6),
 * diisi oleh atasan/pengguna lulusan via link bertoken. Tanpa login.
 */
class PublicKepuasanController extends BaseController
{
    private const PURPOSE = 'kepuasan';

    public function form(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Link tidak valid atau sudah kedaluwarsa. Hubungi program studi terkait.']);
        }
        $data = (new KepuasanModel())->getByMahasiswa((int) $mhs['id']) ?? [];
        return view('tracer_public/kepuasan_form', [
            'token' => $token,
            'mhs'   => $mhs,
            'data'  => $data,
            'aspek' => KepuasanModel::ASPEK,
        ]);
    }

    public function submit(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Link tidak valid atau sudah kedaluwarsa.']);
        }

        $req  = $this->request;
        $data = [
            'mahasiswa_id'    => (int) $mhs['id'],
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

        $model    = new KepuasanModel();
        $existing = $model->getByMahasiswa((int) $mhs['id']);
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }

        return view('tracer_public/message', [
            'success' => 'Terima kasih atas penilaian Anda terhadap ' . esc($mhs['nama']) . '. Masukan ini sangat berarti bagi peningkatan mutu lulusan.',
        ]);
    }

    private function resolve(string $token): ?array
    {
        $id = TracerToken::verify($token, self::PURPOSE);
        if (!$id) return null;
        $row = Database::connect()->table('mahasiswa m')
            ->select('m.id, m.nim, m.nama, p.nama AS prodi_nama')
            ->join('prodi p', 'p.id = m.prodi_id', 'left')
            ->where('m.id', $id)
            ->get()->getRowArray();
        return $row ?: null;
    }
}
