<?php

namespace App\Controllers;

use Config\Database;

/**
 * Halaman ringan Instrumen LAM:
 *  - Lihat daftar instrumen + struktur (jumlah kriteria/butir/tabel LKPS/template LED).
 *  - Kelola pemetaan prodi → instrumen aktif (prodi_instrumen).
 * CRUD penuh instrumen (kriteria/butir) belum termasuk — dibuat lewat seeder.
 */
class InstrumenController extends BaseController
{
    public function index(): string
    {
        $db = Database::connect();

        $instrumen = $db->table('lam_instrumen li')
            ->select('li.*, ll.nama AS lembaga_nama')
            ->join('lam_lembaga ll', 'll.id = li.lam_lembaga_id', 'left')
            ->orderBy('li.id', 'ASC')
            ->get()->getResultArray();

        foreach ($instrumen as &$ins) {
            $ins['jml_kriteria'] = $db->table('lam_kriteria')->where('lam_instrumen_id', $ins['id'])->countAllResults();
            $ins['jml_butir']    = $db->query("
                SELECT COUNT(*) n FROM lam_butir b
                JOIN lam_kriteria k ON k.id = b.lam_kriteria_id
                WHERE k.lam_instrumen_id = ?
            ", [$ins['id']])->getRow()->n;
            $ins['jml_tabel']    = $db->table('lam_tabel_lkps')->where('lam_instrumen_id', $ins['id'])->countAllResults();
            $ins['jml_template'] = $db->table('lam_template_led')->where('lam_instrumen_id', $ins['id'])->countAllResults();
            $ins['kriteria']     = $db->table('lam_kriteria')
                ->where('lam_instrumen_id', $ins['id'])->orderBy('urutan', 'ASC')->get()->getResultArray();
        }
        unset($ins);

        // Daftar prodi + instrumen aktif yang dipetakan
        $prodi = $db->table('prodi p')
            ->select('p.id, p.nama, p.jenjang, pi.lam_instrumen_id AS aktif_instrumen_id')
            ->join('prodi_instrumen pi', 'pi.prodi_id = p.id AND pi.aktif = 1', 'left')
            ->orderBy('p.nama', 'ASC')
            ->get()->getResultArray();

        return view('instrumen/index', compact('instrumen', 'prodi'));
    }

    /** Set instrumen aktif untuk satu prodi. */
    public function assign()
    {
        $prodiId     = (int) $this->request->getPost('prodi_id');
        $instrumenId = (int) $this->request->getPost('lam_instrumen_id');

        $db = Database::connect();

        if (!$db->table('prodi')->where('id', $prodiId)->countAllResults()) {
            return redirect()->back()->with('error', 'Prodi tidak ditemukan.');
        }

        // Non-aktifkan semua pemetaan prodi ini
        $db->table('prodi_instrumen')->where('prodi_id', $prodiId)->update(['aktif' => 0]);

        if ($instrumenId > 0) {
            if (!$db->table('lam_instrumen')->where('id', $instrumenId)->countAllResults()) {
                return redirect()->back()->with('error', 'Instrumen tidak ditemukan.');
            }
            $existing = $db->table('prodi_instrumen')
                ->where(['prodi_id' => $prodiId, 'lam_instrumen_id' => $instrumenId])
                ->get()->getRowArray();
            if ($existing) {
                $db->table('prodi_instrumen')->where('id', $existing['id'])->update(['aktif' => 1]);
            } else {
                $db->table('prodi_instrumen')->insert([
                    'prodi_id'         => $prodiId,
                    'lam_instrumen_id' => $instrumenId,
                    'periode_mulai'    => date('Y-m-d'),
                    'aktif'            => 1,
                ]);
            }
        }

        return redirect()->to(site_url('instrumen'))->with('success', 'Pemetaan instrumen prodi berhasil diperbarui.');
    }
}
