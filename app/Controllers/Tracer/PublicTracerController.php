<?php

namespace App\Controllers\Tracer;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\TracerStudyModel;
use App\Services\TracerToken;
use Config\Database;

/**
 * Form publik tracer study yang diisi alumni via link berisi token.
 * Tidak memerlukan login (route di luar grup session).
 */
class PublicTracerController extends BaseController
{
    private const KESESUAIAN = ['sangat_sesuai', 'sesuai', 'kurang_sesuai', 'tidak_sesuai'];
    private const TINGKAT     = ['lokal', 'nasional', 'multinasional', 'internasional', 'wirausaha'];

    public function form(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Link tidak valid atau sudah kedaluwarsa. Hubungi program studi Anda.']);
        }
        $tracer = (new TracerStudyModel())->getByMahasiswa((int) $mhs['id']) ?? [];
        return view('tracer_public/form', [
            'token'  => $token,
            'mhs'    => $mhs,
            'tracer' => $tracer,
        ]);
    }

    public function submit(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Link tidak valid atau sudah kedaluwarsa.']);
        }

        $req = $this->request;
        $kes = $req->getPost('kesesuaian_bidang_kerja');
        $tkt = $req->getPost('tingkat_pekerjaan');
        $gaji = preg_replace('/[^0-9]/', '', (string) $req->getPost('gaji_pertama'));
        $masa = $req->getPost('masa_tunggu_bulan');

        $data = [
            'mahasiswa_id'            => (int) $mhs['id'],
            'tgl_isi'                 => date('Y-m-d'),
            'masa_tunggu_bulan'       => ($masa !== '' && is_numeric($masa)) ? (int) $masa : null,
            'kesesuaian_bidang_kerja' => in_array($kes, self::KESESUAIAN, true) ? $kes : null,
            'tingkat_pekerjaan'       => in_array($tkt, self::TINGKAT, true) ? $tkt : null,
            'nama_perusahaan'         => trim((string) $req->getPost('nama_perusahaan')) ?: null,
            'jabatan'                 => trim((string) $req->getPost('jabatan')) ?: null,
            'gaji_pertama'            => $gaji !== '' ? (int) $gaji : null,
            'lanjut_studi'            => $req->getPost('lanjut_studi') ? 1 : 0,
        ];

        $model    = new TracerStudyModel();
        $existing = $model->getByMahasiswa((int) $mhs['id']);
        if ($existing) {
            $model->update($existing['id'], $data);
        } else {
            $model->insert($data);
        }

        return view('tracer_public/message', [
            'success' => 'Terima kasih, ' . esc($mhs['nama']) . '. Jawaban Anda sudah kami terima dan sangat berarti bagi pengembangan program studi.',
        ]);
    }

    /** Verifikasi token -> data mahasiswa (+ nama prodi) atau null. */
    private function resolve(string $token): ?array
    {
        $id = TracerToken::verify($token);
        if (!$id) return null;
        $row = Database::connect()->table('mahasiswa m')
            ->select('m.id, m.nim, m.nama, p.nama AS prodi_nama')
            ->join('prodi p', 'p.id = m.prodi_id', 'left')
            ->where('m.id', $id)
            ->get()->getRowArray();
        return $row ?: null;
    }
}
