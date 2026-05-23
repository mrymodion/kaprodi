<?php

namespace App\Controllers;

use App\Services\LkpsGenerator;
use App\Services\LedGenerator;
use Modules\Akreditasi\Engine\AkreditasiEngine;
use Config\Database;

/**
 * Dashboard Kesiapan Akreditasi: skor per kriteria + kelengkapan data,
 * LKPS, dan LED untuk prodi aktif dalam satu halaman.
 */
class KesiapanController extends BaseController
{
    private function prodiId(): int
    {
        $pid = (int) session('active_prodi_id');
        if (!$pid && session('is_superadmin')) {
            $first = Database::connect()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        return $pid;
    }

    public function index(): string
    {
        $prodiId = $this->prodiId();
        $engine  = new AkreditasiEngine();
        $instrumen = $prodiId ? $engine->getInstrumen($prodiId) : null;

        if (!$prodiId || !$instrumen) {
            return view('kesiapan/index', ['prodiId' => $prodiId, 'instrumen' => null]);
        }

        $db    = Database::connect();
        $prodi = $db->table('prodi')->where('id', $prodiId)->get()->getRowArray();

        $total    = $engine->totalSkor($prodiId, $instrumen['id'], null);
        $kriteria = $engine->getKriteriaWithButir($prodiId, $instrumen['id'], null);

        // Butir penilaian
        $butirTotal = $butirDinilai = 0;
        foreach ($kriteria as $k) { $butirTotal += $k['jml_butir']; $butirDinilai += $k['jml_isi']; }

        // Kelengkapan LKPS
        $driver = $engine->getDriver($prodiId);
        $tabel  = (new LkpsGenerator())->listTabel($prodiId);
        $lkpsTotal = count($tabel); $lkpsTerisi = 0;
        if ($driver) {
            foreach ($tabel as $t) {
                if (!empty($driver->buildLkpsTable($t['kode'], $prodiId, null))) $lkpsTerisi++;
            }
        }

        // Kelengkapan LED
        $templates = (new LedGenerator())->getTemplatesWithNarasi($prodiId);
        $ledTotal  = count($templates);
        $ledTerisi = count(array_filter($templates, fn($t) => !empty($t['narasi'])));

        // Kelengkapan data per modul
        $c = fn(string $table, array $where = []) => $db->table($table)->where(['prodi_id' => $prodiId] + $where)->countAllResults();
        $kuri = $db->table('kurikulum')->where(['prodi_id' => $prodiId, 'aktif' => 1])->get()->getRowArray();
        $kid  = $kuri['id'] ?? 0;
        $dosenIds = array_column($db->table('dosen')->select('id')->where('prodi_id', $prodiId)->get()->getResultArray(), 'id');

        $data = [
            ['VMTS', $c('vmts', ['aktif' => 1]), 1],
            ['DTPR (Dosen aktif)', $c('dosen', ['aktif' => 1]), 5],
            ['EWMP dosen', $dosenIds ? $db->table('dosen_ewmp')->whereIn('dosen_id', $dosenIds)->countAllResults() : 0, 1],
            ['Tenaga Kependidikan', $c('tendik'), 1],
            ['Mahasiswa', $c('mahasiswa'), 1],
            ['Lulusan', $c('mahasiswa', ['status' => 'lulus']), 1],
            ['Kurikulum aktif', $kuri ? 1 : 0, 1],
            ['CPL', $kid ? $db->table('cpl')->where('kurikulum_id', $kid)->countAllResults() : 0, 1],
            ['Mata Kuliah', $kid ? $db->table('mata_kuliah')->where('kurikulum_id', $kid)->countAllResults() : 0, 1],
            ['Penelitian', $c('penelitian'), 1],
            ['PkM', $c('pkm'), 1],
            ['Publikasi', $c('publikasi'), 1],
            ['HKI', $c('hki'), 1],
            ['Kerjasama', $c('kerjasama'), 1],
            ['Sarana & Prasarana', $c('sarpras'), 1],
            ['Keuangan', $c('keuangan'), 1],
            ['Tracer Study', $dosenIds !== null ? $db->table('tracer_study ts')->join('mahasiswa m', 'm.id = ts.mahasiswa_id')->where('m.prodi_id', $prodiId)->countAllResults() : 0, 1],
            ['Kepuasan Pengguna', $db->table('kepuasan_pengguna_lulusan k')->join('mahasiswa m', 'm.id = k.mahasiswa_id')->where('m.prodi_id', $prodiId)->countAllResults(), 1],
            ['Dokumen Eviden Dosen', $dosenIds ? $db->table('dokumen_eviden')->where('ref_table', 'dosen')->whereIn('ref_id', $dosenIds)->countAllResults() : 0, 1],
        ];

        return view('kesiapan/index', compact(
            'prodiId', 'instrumen', 'prodi', 'total', 'kriteria',
            'butirTotal', 'butirDinilai', 'lkpsTotal', 'lkpsTerisi', 'ledTotal', 'ledTerisi', 'data'
        ));
    }
}
