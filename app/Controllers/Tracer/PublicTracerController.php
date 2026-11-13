<?php

namespace App\Controllers\Tracer;

use App\Controllers\BaseController;
use App\Services\TracerToken;
use Config\Database;

/**
 * Form publik Tracer Study (Kriteria C2 - LKPS 2.B.4 & 2.B.5),
 * diisi oleh alumni via tautan bertoken dari WhatsApp (tanpa login).
 */
class PublicTracerController extends BaseController
{
    private const PURPOSE = 'tracer';

    /**
     * Menampilkan formulir Tracer Study kepada alumni.
     */
    public function form(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Tautan tidak valid atau sudah kedaluwarsa. Silakan hubungi Program Studi.']);
        }

        $db = Database::connect();
        $data = $db->table('tracer_study')->where('mahasiswa_id', $mhs['id'])->get()->getRowArray() ?: [];

        return view('tracer_public/tracer_form', [
            'token' => $token,
            'mhs'   => $mhs,
            'data'  => $data,
        ]);
    }

    /**
     * Memproses pengiriman data Tracer Study dari alumni.
     */
    public function submit(string $token)
    {
        $mhs = $this->resolve($token);
        if (!$mhs) {
            return view('tracer_public/message', ['error' => 'Tautan tidak valid atau sudah kedaluwarsa.']);
        }

        $req  = $this->request;
        $db   = Database::connect();
        
        $data = [
            'mahasiswa_id'            => (int) $mhs['id'],
            'tgl_isi'                 => date('Y-m-d'),
            'masa_tunggu_bulan'       => (int) $req->getPost('masa_tunggu_bulan'),
            'kesesuaian_bidang_kerja' => $req->getPost('kesesuaian_bidang_kerja') ?: null,
            'tingkat_pekerjaan'       => $req->getPost('tingkat_pekerjaan') ?: null,
            'nama_perusahaan'         => trim((string) $req->getPost('nama_perusahaan')) ?: null,
            'jabatan'                 => trim((string) $req->getPost('jabatan')) ?: null,
            'nama_atasan'             => trim((string) $req->getPost('nama_atasan')) ?: null,
            'no_hp_atasan'            => trim((string) $req->getPost('no_hp_atasan')) ?: null,
        ];

        $existing = $db->table('tracer_study')->where('mahasiswa_id', $mhs['id'])->get()->getRowArray();
        if ($existing) {
            $db->table('tracer_study')->where('id', $existing['id'])->update($data);
        } else {
            $db->table('tracer_study')->insert($data);
        }

        return view('tracer_public/message', [
            'success' => 'Terima kasih, ' . esc($mhs['nama']) . '. Data Tracer Study Anda berhasil disimpan dan sangat berguna bagi pengembangan mutu ' . esc($mhs['prodi_nama']) . '.',
        ]);
    }

    /**
     * Memverifikasi token dan mengambil data mahasiswa yang bersangkutan.
     */
    private function resolve(string $token): ?array
    {
        $id = TracerToken::verify($token, self::PURPOSE);
        if (!$id) return null;
        
        $row = Database::connect()->table('mahasiswa m')
            ->select('m.id, m.nim, m.nama, m.tgl_lulus, p.nama AS prodi_nama')
            ->join('prodi p', 'p.id = m.prodi_id', 'left')
            ->where('m.id', $id)
            ->get()->getRowArray();
            
        return $row ?: null;
    }
}