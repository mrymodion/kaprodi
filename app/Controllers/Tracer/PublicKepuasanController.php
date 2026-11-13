<?php

namespace App\Controllers\Tracer;

use App\Controllers\BaseController;
use App\Services\TracerToken;
use Config\Database;

/**
 * Form publik Survei Kepuasan Pengguna Lulusan (Kriteria C2 - LKPS 2.B.6).
 * Diisi oleh Atasan/Pimpinan Perusahaan via tautan bertoken dari WhatsApp.
 */
class PublicKepuasanController extends BaseController
{
    private const PURPOSE = 'kepuasan';

    public function form(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Tautan tidak valid atau sudah kedaluwarsa. Silakan hubungi Program Studi.']);
        }

        $db = Database::connect();
        $data = $db->table('kepuasan_pengguna_lulusan')->where('mahasiswa_id', $mhs['id'])->get()->getRowArray() ?: [];

        // Ambil data atasan dari tracer_study sebagai default jika belum diisi
        if (empty($data['nama_atasan'])) {
            $ts = $db->table('tracer_study')->where('mahasiswa_id', $mhs['id'])->get()->getRowArray();
            $data['nama_atasan']     = $ts['nama_atasan'] ?? '';
            $data['nama_perusahaan'] = $ts['nama_perusahaan'] ?? '';
        }

        $aspek = [
            'etika'             => 'Etika',
            'keahlian'          => 'Keahlian pada bidang ilmu (Kompetensi Utama)',
            'bahasa_asing'      => 'Kemampuan Berbahasa Asing',
            'teknologi_info'    => 'Penggunaan Teknologi Informasi',
            'komunikasi'        => 'Kemampuan Berkomunikasi',
            'kerjasama_tim'     => 'Kerjasama Tim',
            'pengembangan_diri' => 'Pengembangan Diri'
        ];

        return view('tracer_public/kepuasan_form', [
            'token' => $token,
            'mhs'   => $mhs,
            'data'  => $data,
            'aspek' => $aspek,
        ]);
    }

    public function submit(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Tautan tidak valid atau sudah kedaluwarsa.']);
        }

        $req  = $this->request;
        $db   = Database::connect();
        
        $data = [
            'mahasiswa_id'      => (int) $mhs['id'],
            'nama_atasan'       => trim((string) $req->getPost('nama_atasan')) ?: null,
            'jabatan_atasan'    => trim((string) $req->getPost('jabatan_atasan')) ?: null,
            'nama_perusahaan'   => trim((string) $req->getPost('nama_perusahaan')) ?: null,
            'etika'             => (int) $req->getPost('etika') ?: null,
            'keahlian'          => (int) $req->getPost('keahlian') ?: null,
            'bahasa_asing'      => (int) $req->getPost('bahasa_asing') ?: null,
            'teknologi_info'    => (int) $req->getPost('teknologi_info') ?: null,
            'komunikasi'        => (int) $req->getPost('komunikasi') ?: null,
            'kerjasama_tim'     => (int) $req->getPost('kerjasama_tim') ?: null,
            'pengembangan_diri' => (int) $req->getPost('pengembangan_diri') ?: null,
            'saran'             => trim((string) $req->getPost('saran')) ?: null,
            'tgl_isi'           => date('Y-m-d'),
        ];

        $existing = $db->table('kepuasan_pengguna_lulusan')->where('mahasiswa_id', $mhs['id'])->get()->getRowArray();
        if ($existing) {
            $db->table('kepuasan_pengguna_lulusan')->where('id', $existing['id'])->update($data);
        } else {
            $db->table('kepuasan_pengguna_lulusan')->insert($data);
        }

        return view('tracer_public/message', [
            'success' => 'Terima kasih atas partisipasi Bapak/Ibu. Penilaian Anda sangat berharga bagi peningkatan mutu lulusan ' . esc($mhs['prodi_nama']) . '.',
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