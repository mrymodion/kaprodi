<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Controllers\Concerns\ExcelImport;
use App\Services\FonnteClient;
use App\Services\TracerToken;
use Config\Database;

/**
 * Controller Manajemen Mahasiswa & Tracer Study.
 * Mensuplai data utama untuk Kriteria C2:
 * Tabel LKPS 2.A (Mahasiswa), 2.B.4 & 2.B.5 (Tracer Study), 2.B.6 (Kepuasan Pengguna).
 */
class MahasiswaController extends BaseController
{
    // Menggunakan trait pembantu untuk import Excel
    use ExcelImport;

    private function prodiId(): int
    {
        return (int) (session('active_prodi_id') ?: 0);
    }

    // ────────────────────────────────────────────────────────────────────────
    // 1. Manajemen Data Mahasiswa (Dasar)
    // ────────────────────────────────────────────────────────────────────────

    public function index()
    {
        $prodiId = $this->prodiId();
        if (!$prodiId) return redirect()->to('/')->with('error', 'Prodi aktif belum dipilih.');

        $db = Database::connect();

        $statusFilter = $this->request->getGet('status');
        $taFilter = $this->request->getGet('ta_id');

        // Daftar Tahun Akademik untuk filter form
        $taList = $db->table('tahun_akademik')->orderBy('tahun_mulai', 'DESC')->get()->getResultArray();

        // Mengambil daftar mahasiswa dengan filter (bila ada) dan join TA
        $q = $db->table('mahasiswa m')
            ->select('m.*, ta.kode AS ta_masuk')
            ->join('tahun_akademik ta', 'ta.id = m.tahun_masuk_id', 'left')
            ->where('m.prodi_id', $prodiId);
        
        if ($statusFilter) $q->where('m.status', $statusFilter);
        if ($taFilter) $q->where('m.tahun_masuk_id', $taFilter);

        $mahasiswa = $q->orderBy('m.tahun_masuk_id', 'DESC')->orderBy('m.nama', 'ASC')->get()->getResultArray();

        // Mengambil data lulusan yang BELUM mengisi Tracer Study
        $belumTracer = $db->table('mahasiswa m')
            ->select('m.id')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id', 'left')
            ->where(['m.prodi_id' => $prodiId, 'm.status' => 'lulus'])
            ->where('ts.id IS NULL', null, false)
            ->get()->getResultArray();

        // Kalkulasi Statistik Summary & Tabel Perkembangan (Tabel 2.A.1)
        $semua = $db->table('mahasiswa m')
            ->select('m.*, ta.kode AS ta_kode')
            ->join('tahun_akademik ta', 'ta.id = m.tahun_masuk_id', 'left')
            ->where('m.prodi_id', $prodiId)->get()->getResultArray();

        $summary = ['aktif' => 0, 'lulus' => 0, 'cuti' => 0, 'do' => 0, 'avg_ipk' => null, 'avg_masa_studi' => null, 'per_ta' => []];
        $sumIpk = 0; $cntIpk = 0; $sumMasa = 0; $cntMasa = 0;

        foreach ($semua as $m) {
            $st = $m['status'];
            if ($st === 'aktif') $summary['aktif']++;
            elseif ($st === 'lulus') {
                $summary['lulus']++;
                if ((float)$m['ipk'] > 0) { $sumIpk += (float)$m['ipk']; $cntIpk++; }
                if ((int)$m['masa_studi_bulan'] > 0) { $sumMasa += (int)$m['masa_studi_bulan']; $cntMasa++; }
            }
            elseif ($st === 'cuti') $summary['cuti']++;
            elseif (in_array($st, ['do', 'keluar'])) $summary['do']++;

            $taId = (int)$m['tahun_masuk_id'];
            if (!isset($summary['per_ta'][$taId])) $summary['per_ta'][$taId] = ['ta_kode' => $m['ta_kode'] ?: '-', 'jumlah' => 0, 'aktif' => 0, 'lulus' => 0, 'do_mhs' => 0, 'ta_id' => $taId];
            $summary['per_ta'][$taId]['jumlah']++;
            if ($st === 'aktif') $summary['per_ta'][$taId]['aktif']++;
            if ($st === 'lulus') $summary['per_ta'][$taId]['lulus']++;
            if (in_array($st, ['do', 'keluar'])) $summary['per_ta'][$taId]['do_mhs']++;
        }
        if ($cntIpk > 0) $summary['avg_ipk'] = number_format($sumIpk / $cntIpk, 2);
        if ($cntMasa > 0) $summary['avg_masa_studi'] = round($sumMasa / $cntMasa, 1);
        usort($summary['per_ta'], fn($a, $b) => $b['ta_id'] <=> $a['ta_id']);

        return view('mahasiswa/index', [
            'mahasiswa'    => $mahasiswa,
            'summary'      => $summary,
            'taList'       => $taList,
            'belumTracer'  => $belumTracer,
            'statusFilter' => $statusFilter,
            'taFilter'     => $taFilter,
        ]);
    }

    public function create()
    {
        $db = Database::connect();
        return view('mahasiswa/form', [
            'mhs'    => null,
            'taList' => $db->table('tahun_akademik')->orderBy('tahun_mulai', 'DESC')->get()->getResultArray(),
            'action' => site_url('mahasiswa/store')
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost();
        Database::connect()->table('mahasiswa')->insert([
            'prodi_id'         => $this->prodiId(),
            'nim'              => $post['nim'],
            'nama'             => $post['nama'],
            'jenis_kelamin'    => $post['jenis_kelamin'] ?? 'L',
            'provinsi_asal'    => $post['provinsi_asal'] ?: null,
            'tahun_masuk_id'   => $post['tahun_masuk_id'] ?: null,
            'status'           => $post['status'] ?? 'aktif',
            'ipk'              => $post['ipk'] ?: null,
            'masa_studi_bulan' => $post['masa_studi_bulan'] ?: null,
            'tgl_lulus'        => $post['tgl_lulus'] ?: null,
            'no_hp'            => $post['no_hp'] ?: null,
        ]);
        return redirect()->to('mahasiswa')->with('success', 'Data Mahasiswa berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        // Redirect langsung ke edit untuk efisiensi antarmuka
        return redirect()->to("mahasiswa/{$id}/edit");
    }

    public function edit(int $id)
    {
        $db = Database::connect();
        $mhs = $db->table('mahasiswa')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->get()->getRowArray();
        if (!$mhs) return redirect()->to('mahasiswa')->with('error', 'Data tidak ditemukan.');

        return view('mahasiswa/form', [
            'mhs'    => $mhs,
            'taList' => $db->table('tahun_akademik')->orderBy('tahun_mulai', 'DESC')->get()->getResultArray(),
            'action' => site_url("mahasiswa/{$id}/update")
        ]);
    }

    public function update(int $id)
    {
        $post = $this->request->getPost();
        Database::connect()->table('mahasiswa')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->update([
            'nim'              => $post['nim'],
            'nama'             => $post['nama'],
            'jenis_kelamin'    => $post['jenis_kelamin'] ?? 'L',
            'provinsi_asal'    => $post['provinsi_asal'] ?: null,
            'tahun_masuk_id'   => $post['tahun_masuk_id'] ?: null,
            'status'           => $post['status'] ?? 'aktif',
            'ipk'              => $post['ipk'] ?: null,
            'masa_studi_bulan' => $post['masa_studi_bulan'] ?: null,
            'tgl_lulus'        => $post['tgl_lulus'] ?: null,
            'no_hp'            => $post['no_hp'] ?: null,
        ]);
        return redirect()->to('mahasiswa')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        Database::connect()->table('mahasiswa')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->delete();
        return redirect()->to('mahasiswa')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    // Fitur Import & Stubs Tambahan agar tidak terjadi Error 404
    public function importForm() { return view('mahasiswa/import'); }
    public function importTemplate() { $this->excelTemplate("Template_Import_Mahasiswa.xlsx", ['NIM', 'Nama Mahasiswa', 'Jenis Kelamin (L/P)', 'Status (aktif/lulus)'], ['123456', 'Budi Santoso', 'L', 'aktif'], [['Kolom Jenis Kelamin', 'Isi L atau P']]); }
    public function importStore() { return redirect()->to('mahasiswa')->with('error', 'Import massal sedang dalam pengembangan.'); }
    public function tracerReport() { return redirect()->to('mahasiswa/tracer')->with('error', 'Laporan Tracer Study sedang dalam pengembangan.'); }
    public function tracerForm(int $id) { return redirect()->to('mahasiswa/tracer')->with('error', 'Form manual sedang dalam pengembangan.'); }
    public function tracerStore(int $id) { return redirect()->to('mahasiswa/tracer')->with('error', 'Fitur sedang dalam pengembangan.'); }
    public function kepuasanForm(int $id) { return redirect()->to('mahasiswa/kepuasan')->with('error', 'Form manual sedang dalam pengembangan.'); }
    public function kepuasanStore(int $id) { return redirect()->to('mahasiswa/kepuasan')->with('error', 'Fitur sedang dalam pengembangan.'); }

    // ────────────────────────────────────────────────────────────────────────
    // 2. Modul Tracer Study (Alumni)
    // ────────────────────────────────────────────────────────────────────────

    public function tracerIndex()
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        // Mengambil daftar alumni (status lulus) beserta status pengisian Tracer Study
        $alumni = $db->table('mahasiswa m')
            ->select('m.id, m.nim, m.nama, m.tahun_masuk_id, m.tgl_lulus, m.no_hp, ts.id AS tracer_id, ts.tgl_isi, ts.masa_tunggu_bulan, ts.kesesuaian_bidang_kerja')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id', 'left')
            ->where(['m.prodi_id' => $prodiId, 'm.status' => 'lulus'])
            ->orderBy('m.tgl_lulus', 'DESC')
            ->get()->getResultArray();

        // Kalkulasi Statistik Dashboard
        $totalAlumni = count($alumni);
        $sudahIsi = 0;
        $totalMasaTunggu = 0;
        $countMasaTunggu = 0;
        $kesesuaian = ['sangat_sesuai' => 0, 'sesuai' => 0, 'kurang_sesuai' => 0, 'tidak_sesuai' => 0];

        foreach ($alumni as $a) {
            if ($a['tracer_id']) {
                $sudahIsi++;
                if ($a['masa_tunggu_bulan'] !== null) {
                    $totalMasaTunggu += (int) $a['masa_tunggu_bulan'];
                    $countMasaTunggu++;
                }
                if ($a['kesesuaian_bidang_kerja'] && isset($kesesuaian[$a['kesesuaian_bidang_kerja']])) {
                    $kesesuaian[$a['kesesuaian_bidang_kerja']]++;
                }
            }
        }

        $stats = [
            'total'           => $totalAlumni,
            'sudah_isi'       => $sudahIsi,
            'belum_isi'       => $totalAlumni - $sudahIsi,
            'response_rate'   => $totalAlumni > 0 ? round(($sudahIsi / $totalAlumni) * 100, 1) : 0,
            'avg_masa_tunggu' => $countMasaTunggu > 0 ? round($totalMasaTunggu / $countMasaTunggu, 1) : 0,
            'kesesuaian'      => $kesesuaian,
        ];

        return view('mahasiswa/tracer_index', [
            'alumni' => $alumni,
            'stats'  => $stats,
        ]);
    }

    /**
     * Mengirim link unik kuesioner Tracer Study via WhatsApp (Fonnte).
     */
    public function tracerKirim(int $id)
    {
        $db = Database::connect();
        $mhs = $db->table('mahasiswa')->where(['id' => $id, 'prodi_id' => $this->prodiId()])->get()->getRowArray();

        if (!$mhs || empty($mhs['no_hp'])) {
            return redirect()->back()->with('error', 'Data alumni tidak valid atau nomor HP belum diisi.');
        }

        // Generate token unik yang tertaut dengan ID Mahasiswa
        $token = TracerToken::make($id, 'tracer');
        $url = site_url("tracer/isi/{$token}");
        
        $msg = "Halo {$mhs['nama']},\n\nKami dari Program Studi sedang melakukan evaluasi lulusan (Tracer Study) untuk kebutuhan peningkatan mutu akademik dan Akreditasi.\n\nMohon kesediaannya meluangkan waktu 3 menit untuk mengisi kuesioner pada tautan berikut:\n{$url}\n\nData Anda akan dijaga kerahasiaannya. Terima kasih.";

        $fonnte = new FonnteClient();
        if (!$fonnte->enabled()) {
            return redirect()->back()->with('error', 'WhatsApp Gateway belum dikonfigurasi di file .env.');
        }

        $res = $fonnte->send($mhs['no_hp'], $msg);
        if ($res['ok']) {
            return redirect()->back()->with('success', "Tautan Tracer Study berhasil dikirim via WA ke {$mhs['nama']}.");
        } else {
            return redirect()->back()->with('error', "Gagal mengirim WhatsApp: " . $res['error']);
        }
    }

    /**
     * Broadcast link kuesioner Tracer Study via WhatsApp ke SEMUA alumni yang belum mengisi.
     */
    public function tracerKirimSemua()
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        $fonnte = new FonnteClient();
        if (!$fonnte->enabled()) {
            return redirect()->back()->with('error', 'WhatsApp Gateway belum dikonfigurasi di file .env.');
        }

        // Ambil semua alumni yang belum ada di tabel tracer_study dan punya nomor HP
        $alumni = $db->table('mahasiswa m')
            ->select('m.id, m.nama, m.no_hp')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id', 'left')
            ->where(['m.prodi_id' => $prodiId, 'm.status' => 'lulus'])
            ->where('ts.id IS NULL', null, false)
            ->where("(m.no_hp IS NOT NULL AND m.no_hp != '')", null, false)
            ->get()->getResultArray();

        if (empty($alumni)) {
            return redirect()->back()->with('info', 'Tidak ada alumni dengan nomor HP valid yang belum mengisi Tracer Study.');
        }

        $berhasil = 0; $gagal = 0;
        foreach ($alumni as $mhs) {
            $token = TracerToken::make((int) $mhs['id'], 'tracer');
            $url = site_url("tracer/isi/{$token}");
            
            $msg = "Halo {$mhs['nama']},\n\nKami dari Program Studi sedang melakukan evaluasi lulusan (Tracer Study) untuk kebutuhan peningkatan mutu akademik dan Akreditasi.\n\nMohon kesediaannya meluangkan waktu 3 menit untuk mengisi kuesioner pada tautan berikut:\n{$url}\n\nData Anda akan dijaga kerahasiaannya. Terima kasih.";

            $res = $fonnte->send($mhs['no_hp'], $msg);
            if ($res['ok']) $berhasil++; else $gagal++;
        }

        $alert = $gagal > 0 
            ? "Broadcast selesai: {$berhasil} pesan berhasil dikirim, {$gagal} gagal." 
            : "Broadcast berhasil! {$berhasil} pesan telah dikirim ke alumni.";
        return redirect()->back()->with('success', $alert);
    }

    // ────────────────────────────────────────────────────────────────────────
    // 3. Modul Survei Kepuasan Pengguna Lulusan (Atasan / Perusahaan)
    // ────────────────────────────────────────────────────────────────────────

    public function kepuasanIndex()
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        // Menarik data alumni yang SUDAH bekerja (data atasan ada di tracer_study)
        $alumniKerja = $db->table('mahasiswa m')
            ->select('m.id, m.nim, m.nama, ts.nama_perusahaan, ts.nama_atasan, ts.no_hp_atasan, kp.id AS kepuasan_id, kp.tgl_isi')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id')
            ->join('kepuasan_pengguna_lulusan kp', 'kp.mahasiswa_id = m.id', 'left')
            ->where(['m.prodi_id' => $prodiId, 'm.status' => 'lulus'])
            ->where("(ts.nama_perusahaan IS NOT NULL AND ts.nama_perusahaan != '')", null, false)
            ->orderBy('m.tgl_lulus', 'DESC')
            ->get()->getResultArray();

        return view('mahasiswa/kepuasan_index', [
            'alumni' => $alumniKerja
        ]);
    }

    public function kepuasanKirim(int $id)
    {
        $db = Database::connect();
        $data = $db->table('mahasiswa m')
            ->select('m.id, m.nama, ts.nama_atasan, ts.no_hp_atasan, ts.nama_perusahaan')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id')
            ->where(['m.id' => $id, 'm.prodi_id' => $this->prodiId()])
            ->get()->getRowArray();

        if (!$data || empty($data['no_hp_atasan'])) {
            return redirect()->back()->with('error', 'Nomor HP Atasan dari instansi tempat alumni bekerja belum tersedia.');
        }

        $token = TracerToken::make($id, 'kepuasan');
        $url = site_url("kepuasan/isi/{$token}");
        
        $msg = "Yth. Bapak/Ibu {$data['nama_atasan']} di {$data['nama_perusahaan']},\n\nSebagai komitmen kami dalam meningkatkan mutu pendidikan, kami memohon kesediaan Bapak/Ibu untuk mengisi Survei Kepuasan Pengguna Lulusan atas kinerja alumni kami, sdr/i: *{$data['nama']}*.\n\nTautan form kuesioner:\n{$url}\n\nTerima kasih atas waktu dan kerjasamanya.";

        $res = (new FonnteClient())->send($data['no_hp_atasan'], $msg);
        return $res['ok'] 
            ? redirect()->back()->with('success', "Pesan WA survei pengguna berhasil dikirim ke atasan {$data['nama']}.") 
            : redirect()->back()->with('error', "Gagal mengirim WhatsApp: " . $res['error']);
    }

    /**
     * Broadcast Survei Kepuasan Pengguna ke SEMUA atasan alumni yang belum mengisi.
     */
    public function kepuasanKirimSemua()
    {
        $prodiId = $this->prodiId();
        $db = Database::connect();

        $fonnte = new FonnteClient();
        if (!$fonnte->enabled()) {
            return redirect()->back()->with('error', 'WhatsApp Gateway belum dikonfigurasi di file .env.');
        }

        // Ambil data alumni yang sudah bekerja dan belum disurvei oleh atasannya
        $alumniKerja = $db->table('mahasiswa m')
            ->select('m.id, m.nama, ts.nama_atasan, ts.no_hp_atasan, ts.nama_perusahaan')
            ->join('tracer_study ts', 'ts.mahasiswa_id = m.id')
            ->join('kepuasan_pengguna_lulusan kp', 'kp.mahasiswa_id = m.id', 'left')
            ->where(['m.prodi_id' => $prodiId, 'm.status' => 'lulus'])
            ->where('kp.id IS NULL', null, false)
            ->where("(ts.no_hp_atasan IS NOT NULL AND ts.no_hp_atasan != '')", null, false)
            ->get()->getResultArray();

        if (empty($alumniKerja)) {
            return redirect()->back()->with('info', 'Tidak ada atasan dengan nomor HP valid yang belum mengisi survei kepuasan.');
        }

        $berhasil = 0; $gagal = 0;
        foreach ($alumniKerja as $data) {
            $token = TracerToken::make((int) $data['id'], 'kepuasan');
            $url = site_url("kepuasan/isi/{$token}");
            
            $msg = "Yth. Bapak/Ibu {$data['nama_atasan']} di {$data['nama_perusahaan']},\n\nSebagai komitmen kami dalam meningkatkan mutu pendidikan, kami memohon kesediaan Bapak/Ibu untuk mengisi Survei Kepuasan Pengguna Lulusan atas kinerja alumni kami, sdr/i: *{$data['nama']}*.\n\nTautan form kuesioner:\n{$url}\n\nTerima kasih atas waktu dan kerjasamanya.";
            $res = $fonnte->send($data['no_hp_atasan'], $msg);
            if ($res['ok']) $berhasil++; else $gagal++;
        }

        $alert = $gagal > 0 
            ? "Broadcast selesai: {$berhasil} pesan berhasil dikirim ke atasan, {$gagal} gagal." 
            : "Broadcast berhasil! {$berhasil} pesan telah dikirim ke atasan alumni.";
        return redirect()->back()->with('success', $alert);
    }
}