<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder Instrumen LAM INFOKOM 2.0 untuk Program SARJANA (S1).
 *
 * Sumber: Lampiran PerLAM No. 06/PERLAM/MA/LAM-INFOKOM/VI/2025
 *   - Matriks Penilaian Kinerja Program Studi (S1-Matriks Penilaian.pdf)
 *   - Panduan LKPS (S1-LKPS.pdf)
 *   - Panduan LED (S1-LED.pdf)
 *
 * Struktur kriteria & bobot (dari "BOBOT BAGIAN PER KRITERIA", total 400):
 *   A  Kondisi Eksternal ............ 4    (1 butir)
 *   B  Profil UPPS .................. 4    (1 butir)
 *   C1 Budaya Mutu ................. 40   (10 butir)
 *   C2 Relevansi Pendidikan ....... 120   (20 butir)
 *   C3 Relevansi Penelitian ........ 72   (15 butir)
 *   C4 Relevansi PkM ............... 60   (15 butir)
 *   C5 Akuntabilitas ............... 40   (10 butir)
 *   C6 Diferensiasi Misi ........... 40    (5 butir)
 *   D  Suplemen Program Studi ...... 20    (5 butir)
 *
 * Bobot tiap butir diambil dari kolom "Bobot dari 400" pada matriks.
 * Catatan: terdapat sedikit ketidakkonsistenan internal pada PDF sumber
 * (jumlah/bobot butir di tabel ringkasan vs matriks utama). Nilai per-butir
 * mengikuti matriks utama; bobot kriteria mengikuti tabel ringkasan resmi.
 * Skor akhir dihitung dengan menormalkan skor butir ke bobot kriteria,
 * sehingga selisih kecil per-butir tidak mempengaruhi nilai akhir.
 *
 * Idempotent: jika instrumen S1 v2.0 sudah ada, seeder dilewati.
 */
class LamInfokomS1Seeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Reuse lembaga LAM-INFOKOM (dibuat oleh seeder D3) atau buat bila belum ada.
        $lembaga = $this->db->table('lam_lembaga')->where('kode', 'LAM-INFOKOM')->get()->getRowArray();
        if ($lembaga) {
            $lembagaId = (int) $lembaga['id'];
        } else {
            $this->db->table('lam_lembaga')->insert([
                'kode'    => 'LAM-INFOKOM',
                'nama'    => 'Lembaga Akreditasi Mandiri Informatika dan Komputer',
                'website' => 'https://laminfokom.or.id',
                'aktif'   => 1,
            ]);
            $lembagaId = $this->db->insertID();
        }

        // Guard idempotent: instrumen S1 v2.0 sudah ada?
        $sudahAda = $this->db->table('lam_instrumen')
            ->where(['lam_lembaga_id' => $lembagaId, 'jenjang' => 'S1', 'versi' => '2.0'])
            ->countAllResults();
        if ($sudahAda) {
            return; // sudah di-seed sebelumnya
        }

        // 1. Instrumen
        $this->db->table('lam_instrumen')->insert([
            'lam_lembaga_id' => $lembagaId,
            'jenjang'        => 'S1',
            'versi'          => '2.0',
            'nama'           => 'Instrumen Akreditasi Program Studi 2.0 Sarjana (Informatika & Komputer)',
            'tahun_terbit'   => 2025,
            'driver_class'   => 'Modules\\Akreditasi\\Drivers\\LamInfokomS1\\Driver',
            'total_bobot'    => 400.00,
            'aktif'          => 1,
            'created_at'     => $now,
        ]);
        $instrumenId = $this->db->insertID();

        // 2. Kriteria
        $kriteriaDef = [
            ['A',  'Kondisi Eksternal',                    'kondisi_eksternal',   4, 1],
            ['B',  'Profil Unit Pengelola Program Studi',  'profil_upps',         4, 2],
            ['C1', 'Budaya Mutu',                          'kriteria',           40, 3],
            ['C2', 'Relevansi Pendidikan',                 'kriteria',          120, 4],
            ['C3', 'Relevansi Penelitian',                 'kriteria',           72, 5],
            ['C4', 'Relevansi PkM',                        'kriteria',           60, 6],
            ['C5', 'Akuntabilitas',                        'kriteria',           40, 7],
            ['C6', 'Diferensiasi Misi',                    'kriteria',           40, 8],
            ['D',  'Suplemen Program Studi',               'suplemen',           20, 9],
        ];
        $kriteriaIds = [];
        foreach ($kriteriaDef as [$kode, $nama, $jenis, $bobot, $urutan]) {
            $this->db->table('lam_kriteria')->insert([
                'lam_instrumen_id' => $instrumenId,
                'kode' => $kode, 'nama' => $nama, 'jenis' => $jenis,
                'bobot' => $bobot, 'urutan' => $urutan,
            ]);
            $kriteriaIds[$kode] = $this->db->insertID();
        }

        // 3. Butir per kriteria: [kode_butir, jenis_ipo, tahap, bobot, elemen]
        foreach ($this->butirData() as $kodeKriteria => $butirList) {
            $urut = 1;
            foreach ($butirList as [$kodeButir, $ipo, $tahap, $bobot, $elemen]) {
                $judul = $tahap
                    ? sprintf('%s [%s] - %s', $kodeButir, $tahap, $elemen)
                    : sprintf('%s - %s', $kodeButir, $elemen);

                $this->db->table('lam_butir')->insert([
                    'lam_kriteria_id' => $kriteriaIds[$kodeKriteria],
                    'kode'        => $kodeButir,
                    'judul'       => mb_substr($judul, 0, 250),
                    'deskripsi'   => $elemen . ($tahap ? " (tahap PPEPP: {$tahap})" : '')
                                   . ' — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).',
                    'jenis_ipo'   => $ipo,
                    'bobot'       => $bobot,
                    'tipe_data'   => 'kualitatif',
                    'urutan'      => $urut++,
                ]);
                $butirId = $this->db->insertID();

                $labels = [4 => 'Sangat Baik', 3 => 'Baik', 2 => 'Cukup', 1 => 'Kurang'];
                foreach ($this->rubrik($elemen) as $skor => $deskriptor) {
                    $this->db->table('lam_butir_rubrik')->insert([
                        'lam_butir_id' => $butirId,
                        'skor'         => $skor,
                        'label'        => $labels[$skor],
                        'deskriptor'   => $deskriptor,
                    ]);
                }
            }
        }

        // 4. Tabel LKPS
        $tabelLkps = [
            ['1.A.1', 'Pimpinan dan Tupoksi UPPS dan PS', 'C1'],
            ['1.A.2', 'Sumber Pendanaan UPPS/PS', 'C1'],
            ['1.A.3', 'Penggunaan Dana UPPS/PS', 'C1'],
            ['1.A.4', 'Rata-rata Beban DTPR per Semester (EWMP)', 'C1'],
            ['1.A.5', 'Kualifikasi Tenaga Kependidikan', 'C1'],
            ['1.B',   'Unit SPMI dan SDM', 'C1'],
            ['2.A.1', 'Data Mahasiswa', 'C2'],
            ['2.A.2', 'Keragaman Asal Mahasiswa', 'C2'],
            ['2.A.3', 'Kondisi Jumlah Mahasiswa', 'C2'],
            ['2.B.1', 'Isi Pembelajaran (Kurikulum)', 'C2'],
            ['2.B.2', 'Pemetaan CPL dengan Profil Lulusan', 'C2'],
            ['2.B.3', 'Peta Pemenuhan CPL', 'C2'],
            ['2.B.4', 'Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali', 'C2'],
            ['2.B.5', 'Kesesuaian Bidang Kerja Lulusan', 'C2'],
            ['2.B.6', 'Kepuasan Pengguna Lulusan', 'C2'],
            ['2.C',   'Fleksibilitas dalam Proses Pembelajaran', 'C2'],
            ['2.D',   'Rekognisi dan Apresiasi Kompetensi Lulusan', 'C2'],
            ['3.A.1', 'Sarana dan Prasarana Penelitian', 'C3'],
            ['3.A.2', 'Penelitian DTPR, Hibah dan Pembiayaan Penelitian', 'C3'],
            ['3.A.3', 'Pengembangan DTPR di Bidang Penelitian', 'C3'],
            ['3.C.1', 'Kerjasama Penelitian', 'C3'],
            ['3.C.2', 'Publikasi Penelitian', 'C3'],
            ['3.C.3', 'Perolehan HKI (Granted) Penelitian', 'C3'],
            ['4.A.1', 'Sarana dan Prasarana PkM', 'C4'],
            ['4.A.2', 'PkM DTPR, Hibah dan Pembiayaan PkM', 'C4'],
            ['4.C.1', 'Kerjasama PkM', 'C4'],
            ['4.C.2', 'Diseminasi Hasil PkM', 'C4'],
            ['4.C.3', 'Perolehan HKI PkM', 'C4'],
            ['5.1',   'Sistem Tata Kelola', 'C5'],
            ['5.2',   'Sarana dan Prasarana Pendidikan', 'C5'],
            ['6',     'Kesesuaian Visi dan Misi', 'C6'],
        ];
        $urut = 1;
        foreach ($tabelLkps as [$kode, $judul, $kriteriaKode]) {
            $this->db->table('lam_tabel_lkps')->insert([
                'lam_instrumen_id' => $instrumenId,
                'kode'             => $kode,
                'judul'            => $judul,
                'kriteria_kode'    => $kriteriaKode,
                'urutan'           => $urut++,
            ]);
        }

        // 5. Template LED (PPEPP per kriteria)
        $bagianLed = [
            ['pendahuluan', null, 'Pendahuluan LED', null],
            ['penutup',     null, 'Penutup LED',     null],
        ];
        foreach (['C1','C2','C3','C4','C5','C6'] as $kodeKriteria) {
            foreach (['penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan'] as $sub) {
                $bagianLed[] = ['kriteria', $kodeKriteria, ucfirst($sub) . ' - ' . $kodeKriteria, $sub];
            }
        }
        foreach ($bagianLed as $idx => [$bagian, $kriteriaKode, $judul, $sub]) {
            $this->db->table('lam_template_led')->insert([
                'lam_instrumen_id' => $instrumenId,
                'lam_kriteria_id'  => $kriteriaKode ? $kriteriaIds[$kriteriaKode] : null,
                'bagian'           => $bagian,
                'sub_bagian'       => $sub,
                'judul'            => $judul,
                'template_isi'     => '',
                'prompt_groq'      => $this->defaultPrompt($kriteriaKode, $sub),
                'urutan'           => $idx + 1,
            ]);
        }

        // 6. Mapping prodi S1 Informatika (kode_pddikti 55200) -> instrumen
        $prodi = $this->db->table('prodi')->where('kode_pddikti', '55200')->get()->getRowArray();
        if ($prodi) {
            // Non-aktifkan instrumen lain untuk prodi ini, lalu aktifkan S1
            $this->db->table('prodi_instrumen')->where('prodi_id', $prodi['id'])->update(['aktif' => 0]);
            $this->db->table('prodi_instrumen')->insert([
                'prodi_id'         => $prodi['id'],
                'lam_instrumen_id' => $instrumenId,
                'periode_mulai'    => date('Y-m-d'),
                'aktif'            => 1,
            ]);
        }
    }

    /** Daftar butir per kriteria: [kode, jenis_ipo, tahap, bobot, elemen]. */
    private function butirData(): array
    {
        return [
            'A' => [
                ['A', 'input', null, 4, 'Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS'],
            ],
            'B' => [
                ['B', 'input', null, 4, 'Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria'],
            ],
            'C1' => [
                ['1.1.A', 'input',  'Penetapan',    3,   'Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP'],
                ['1.1.B', 'input',  'Penetapan',    2.5, 'Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten'],
                ['1.2.A', 'proses', 'Pelaksanaan',  5,   'Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP'],
                ['1.2.B', 'proses', 'Pelaksanaan',  5,   'Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten'],
                ['1.3.A', 'output', 'Evaluasi',     5,   'Efektifitas evaluasi ketercapaian standar sistem tata kelola internal'],
                ['1.3.B', 'output', 'Evaluasi',     5,   'Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana'],
                ['1.4.A', 'proses', 'Pengendalian', 2,   'Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal'],
                ['1.4.B', 'proses', 'Pengendalian', 2,   'Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana'],
                ['1.5.A', 'output', 'Peningkatan',  5,   'Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal'],
                ['1.5.B', 'output', 'Peningkatan',  5,   'Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana'],
            ],
            'C2' => [
                ['2.1.A', 'input',  'Penetapan',    5,  'Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK)'],
                ['2.1.B', 'input',  'Penetapan',    4,  'Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6)'],
                ['2.1.C', 'input',  'Penetapan',    4,  'Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL)'],
                ['2.1.D', 'input',  'Penetapan',    4,  'Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan)'],
                ['2.2.A', 'proses', 'Pelaksanaan',  9,  'Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru'],
                ['2.2.B', 'proses', 'Pelaksanaan',  7,  'Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE'],
                ['2.2.C', 'proses', 'Pelaksanaan',  7,  'Efektifitas pelaksanaan fleksibilitas proses pembelajaran'],
                ['2.2.D', 'output', 'Pelaksanaan',  30, 'Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja)'],
                ['2.3.A', 'output', 'Evaluasi',     5,  'Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru'],
                ['2.3.B', 'output', 'Evaluasi',     5,  'Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE'],
                ['2.3.C', 'output', 'Evaluasi',     4,  'Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran'],
                ['2.3.D', 'output', 'Evaluasi',     4,  'Efektifitas evaluasi ketercapaian kompetensi lulusan'],
                ['2.4.A', 'proses', 'Pengendalian', 4,  'Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru'],
                ['2.4.B', 'proses', 'Pengendalian', 4,  'Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE'],
                ['2.4.C', 'proses', 'Pengendalian', 3,  'Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar'],
                ['2.4.D', 'proses', 'Pengendalian', 3,  'Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan'],
                ['2.5.A', 'output', 'Peningkatan',  5,  'Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru'],
                ['2.5.B', 'output', 'Peningkatan',  5,  'Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE'],
                ['2.5.C', 'output', 'Peningkatan',  4,  'Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar'],
                ['2.5.D', 'output', 'Peningkatan',  4,  'Efektifitas peningkatan/optimalisasi kompetensi lulusan'],
            ],
            'C3' => [
                ['3.1.A', 'input',  'Penetapan',    4,  'Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian'],
                ['3.1.B', 'input',  'Penetapan',    4,  'Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan)'],
                ['3.1.C', 'input',  'Penetapan',    3,  'Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian'],
                ['3.2.A', 'proses', 'Pelaksanaan',  8,  'Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan'],
                ['3.2.B', 'proses', 'Pelaksanaan',  6,  'Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa'],
                ['3.2.C', 'output', 'Pelaksanaan',  18, 'Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan'],
                ['3.3.A', 'output', 'Evaluasi',     3,  'Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan'],
                ['3.3.B', 'output', 'Evaluasi',     3,  'Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa'],
                ['3.3.C', 'output', 'Evaluasi',     3,  'Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI'],
                ['3.4.A', 'proses', 'Pengendalian', 3,  'Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan'],
                ['3.4.B', 'proses', 'Pengendalian', 3,  'Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian'],
                ['3.4.C', 'proses', 'Pengendalian', 3,  'Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI'],
                ['3.5.A', 'output', 'Peningkatan',  4,  'Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan'],
                ['3.5.B', 'output', 'Peningkatan',  3,  'Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian'],
                ['3.5.C', 'output', 'Peningkatan',  3,  'Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI'],
            ],
            'C4' => [
                ['4.1.A', 'input',  'Penetapan',    3,   'Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran)'],
                ['4.1.B', 'input',  'Penetapan',    2.5, 'Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi)'],
                ['4.1.C', 'input',  'Penetapan',    2,   'Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM'],
                ['4.2.A', 'proses', 'Pelaksanaan',  7,   'Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM'],
                ['4.2.B', 'proses', 'Pelaksanaan',  6,   'Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa'],
                ['4.2.C', 'output', 'Pelaksanaan',  15,  'Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI'],
                ['4.3.A', 'output', 'Evaluasi',     3,   'Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM'],
                ['4.3.B', 'output', 'Evaluasi',     3,   'Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa'],
                ['4.3.C', 'output', 'Evaluasi',     3,   'Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI'],
                ['4.4.A', 'proses', 'Pengendalian', 3,   'Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan'],
                ['4.4.B', 'proses', 'Pengendalian', 2,   'Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM'],
                ['4.4.C', 'proses', 'Pengendalian', 2,   'Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI'],
                ['4.5.A', 'output', 'Peningkatan',  3,   'Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan'],
                ['4.5.B', 'output', 'Peningkatan',  2,   'Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa'],
                ['4.5.C', 'output', 'Peningkatan',  2.5, 'Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi'],
            ],
            'C5' => [
                ['5.1.A', 'input',  'Penetapan',    3, 'Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional)'],
                ['5.1.B', 'input',  'Penetapan',    2, 'Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM'],
                ['5.2.A', 'proses', 'Pelaksanaan',  5, 'Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM)'],
                ['5.2.B', 'proses', 'Pelaksanaan',  4, 'Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong'],
                ['5.3.A', 'output', 'Evaluasi',     6, 'Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel'],
                ['5.3.B', 'output', 'Evaluasi',     5, 'Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong'],
                ['5.4.A', 'proses', 'Pengendalian', 3, 'Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel'],
                ['5.4.B', 'proses', 'Pengendalian', 2, 'Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi'],
                ['5.5.A', 'output', 'Peningkatan',  5, 'Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel'],
                ['5.5.B', 'output', 'Peningkatan',  5, 'Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi'],
            ],
            'C6' => [
                ['6.1', 'input',  'Penetapan',    5,  'Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi'],
                ['6.2', 'proses', 'Pelaksanaan',  8,  'Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan'],
                ['6.3', 'output', 'Evaluasi',     13, 'Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan'],
                ['6.4', 'proses', 'Pengendalian', 6,  'Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan'],
                ['6.5', 'output', 'Peningkatan',  10, 'Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan'],
            ],
            'D' => [
                ['D.1', 'output', null, 4, 'Mata kuliah inti/khas program studi'],
                ['D.2', 'output', null, 3, 'Mata kuliah domain spesifik dan lingkungan prodi infokom'],
                ['D.3', 'output', null, 3, 'Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan'],
                ['D.4', 'output', null, 5, 'Proyek Utama (Capstone Project) yang relevan'],
                ['D.5', 'output', null, 5, 'Pengembangan bidang Infokom yang digunakan di masyarakat'],
            ],
        ];
    }

    private function rubrik(string $elemen): array
    {
        return [
            4 => 'Sangat baik: ' . $elemen . ', terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',
            3 => 'Baik: ' . $elemen . ', terpenuhi dengan bukti yang sahih dan lengkap.',
            2 => 'Cukup: ' . $elemen . ', terpenuhi sebagian besar dengan bukti yang cukup lengkap.',
            1 => 'Kurang: ' . $elemen . ', terpenuhi sebagian dengan bukti yang kurang lengkap.',
        ];
    }

    private function defaultPrompt(?string $kriteria, ?string $sub): string
    {
        if (!$kriteria) {
            return 'Tuliskan bagian "' . ($sub ?: 'Pendahuluan') . '" dari Laporan Evaluasi Diri (LED) program studi sarjana dalam bahasa Indonesia formal akademik.';
        }
        return "Tuliskan narasi tahap {$sub} untuk kriteria {$kriteria} pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.";
    }
}
