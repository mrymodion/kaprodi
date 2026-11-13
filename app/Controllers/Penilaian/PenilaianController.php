<?php

namespace App\Controllers\Penilaian;

use App\Controllers\BaseController;
use Modules\Akreditasi\Engine\AkreditasiEngine;
use Config\Database;

class PenilaianController extends BaseController
{
    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    /**
     * Dashboard Rekap Penilaian per Kriteria.
     */
    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $engine = new AkreditasiEngine();
        $instrumen = $engine->getInstrumen($prodiId);
        if (!$instrumen) return redirect()->to('/')->with('error', 'Instrumen LAM belum diatur untuk prodi ini.');

        $db = Database::connect();
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai', 'DESC')->get()->getResultArray();

        $taId = $this->request->getGet('ta_id');
        $taId = $taId !== '' && $taId !== null ? (int) $taId : null;

        $total = $engine->totalSkor($prodiId, $instrumen['id'], $taId);
        $kriteria = $engine->getKriteriaWithButir($prodiId, $instrumen['id'], $taId);

        return view('penilaian/index', [
            'instrumen' => $instrumen,
            'total'     => $total,
            'kriteria'  => $kriteria,
            'taList'    => $taList,
            'taId'      => $taId,
        ]);
    }

    /**
     * Memicu perhitungan auto-skor (Rumus Kuantitatif) dari Driver LAM.
     */
    public function runAuto()
    {
        $prodiId = $this->prodiId();
        $engine = new AkreditasiEngine();
        $instrumen = $engine->getInstrumen($prodiId);
        if (!$instrumen) return redirect()->back()->with('error', 'Instrumen tidak ditemukan.');

        $driver = $engine->getDriver($prodiId);
        if (!$driver) return redirect()->back()->with('error', 'Driver kalkulasi tidak ditemukan.');

        $taId = $this->request->getPost('ta_id');
        $taId = $taId !== '' && $taId !== null ? (int) $taId : null;

        $db = Database::connect();
        $autoScores = $driver->computeScores($prodiId, $taId);
        $explanations = $driver->explanations();

        // Ambil ID butir berdasarkan kode
        $butirRows = $db->table('lam_butir')
            ->select('id, kode')->whereIn('kode', array_keys($autoScores))->get()->getResultArray();
        
        $updated = 0;
        foreach ($butirRows as $b) {
            $kode = $b['kode'];
            $skor = $autoScores[$kode] ?? null;
            if ($skor === null) continue;

            $data = [
                'prodi_id'     => $prodiId,
                'lam_butir_id' => $b['id'],
                'tahun_akademik_id' => $taId,
                'skor_auto'    => $skor,
                'catatan'      => $explanations[$kode] ?? null,
                'updated_at'   => date('Y-m-d H:i:s'),
                'updated_by'   => auth()->id(),
            ];

            $qExisting = $db->table('butir_skor')->where(['prodi_id' => $prodiId, 'lam_butir_id' => $b['id']]);
            if ($taId) {
                $qExisting->where('tahun_akademik_id', $taId);
            } else {
                $qExisting->where('tahun_akademik_id IS NULL', null, false);
            }
            $existing = $qExisting->get()->getRowArray();

            if ($existing) {
                if ($existing['skor_manual'] === null) $data['skor_final'] = $skor;
                $db->table('butir_skor')->where('id', $existing['id'])->update($data);
            } else {
                $data['skor_final'] = $skor;
                $db->table('butir_skor')->insert($data);
            }
            $updated++;
        }

        return redirect()->back()->with('success', "Auto-skor berhasil dijalankan. {$updated} butir kuantitatif diperbarui.");
    }

    /**
     * Halaman Input Skor Manual per Kriteria.
     */
    public function kriteria(int $kriteriaId)
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        $taId = $this->request->getGet('ta_id');
        $taId = $taId !== '' && $taId !== null ? (int) $taId : null;

        $kriteria = $db->table('lam_kriteria')->where('id', $kriteriaId)->get()->getRowArray();
        if (!$kriteria) throw new \CodeIgniter\Exceptions\PageNotFoundException('Kriteria tidak ditemukan.');

        $butir = $db->table('lam_butir')->where('lam_kriteria_id', $kriteriaId)->orderBy('urutan')->get()->getResultArray();
        $butirIds = array_column($butir, 'id');

        $rubrik = [];
        $skorData = [];
        if (!empty($butirIds)) {
            $rb = $db->table('lam_butir_rubrik')->whereIn('lam_butir_id', $butirIds)->orderBy('skor', 'DESC')->get()->getResultArray();
            foreach ($rb as $r) $rubrik[$r['lam_butir_id']][$r['skor']] = $r;

            $qSk = $db->table('butir_skor')->where('prodi_id', $prodiId)->whereIn('lam_butir_id', $butirIds);
            if ($taId) {
                $qSk->where('tahun_akademik_id', $taId);
            } else {
                $qSk->where('tahun_akademik_id IS NULL', null, false);
            }
            $sk = $qSk->get()->getResultArray();

            foreach ($sk as $s) $skorData[$s['lam_butir_id']] = $s;
        }

        return view('penilaian/kriteria', [
            'kriteria' => $kriteria,
            'butir'    => $butir,
            'rubrik'   => $rubrik,
            'skorData' => $skorData,
            'taId'     => $taId,
        ]);
    }

    /**
     * Simpan Skor Manual (Override).
     */
    public function saveSkor(int $butirId)
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();
        
        $taId = $this->request->getPost('ta_id');
        $taId = $taId !== '' && $taId !== null ? (int) $taId : null;

        $skorInput = $this->request->getPost('skor_manual');
        $catatan   = $this->request->getPost('catatan');

        $skorVal = ($skorInput !== '' && $skorInput !== null) ? (float) $skorInput : null;

        $qExisting = $db->table('butir_skor')->where(['prodi_id' => $prodiId, 'lam_butir_id' => $butirId]);
        if ($taId) {
            $qExisting->where('tahun_akademik_id', $taId);
        } else {
            $qExisting->where('tahun_akademik_id IS NULL', null, false);
        }
        $existing = $qExisting->get()->getRowArray();
        
        $data = [
            'prodi_id'     => $prodiId,
            'lam_butir_id' => $butirId,
            'tahun_akademik_id' => $taId,
            'skor_manual'  => $skorVal,
            'skor_final'   => $skorVal ?? ($existing['skor_auto'] ?? 0),
            'catatan'      => $catatan,
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by'   => auth()->id(),
        ];

        if ($existing) $db->table('butir_skor')->where('id', $existing['id'])->update($data);
        else $db->table('butir_skor')->insert($data);

        return redirect()->back()->with('success', 'Skor berhasil disimpan.');
    }
}