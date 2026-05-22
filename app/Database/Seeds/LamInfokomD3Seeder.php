<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder Instrumen LAM INFOKOM 2.0 untuk Program Studi D3.
 *
 * Struktur sesuai Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025):
 *
 *   Bagian              I   P   O   Butir   Bobot   %
 *   A Kondisi Eksternal 1   0   0    1        4     1%
 *   B Profil UPPS       1   0   0    1        4     1%
 *   C1 Budaya Mutu      2   4   4   10       40    10%
 *   C2 Pendidikan       4   7   9   20      120    30%
 *   C3 Penelitian       3   5   7   15       32     8%
 *   C4 PkM              3   5   7   15      100    25%
 *   C5 Akuntabilitas    2   4   4   10       40    10%
 *   C6 Diferensiasi     1   2   2    5       40    10%
 *   D Suplemen          0   0   5    5       20     5%
 *   ---------------------------------------------------
 *   Total             17  27  38   82      400   100%
 *
 * Judul tiap butir di-generate sebagai placeholder bermakna (kode + IPO + tahap PPEPP).
 * Deskripsi & rubrik 4 level memakai template generik PPEPP, yang HARUS
 * di-refine oleh tim GKM prodi sesuai narasi resmi instrumen LAM INFOKOM 2.0
 * sebelum dipakai untuk asesmen riil. UI Manajemen Instrumen tersedia untuk
 * mengedit teks tanpa ubah kode.
 */
class LamInfokomD3Seeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // 1. Lembaga
        $this->db->table('lam_lembaga')->insert([
            'kode' => 'LAM-INFOKOM',
            'nama' => 'Lembaga Akreditasi Mandiri Informatika dan Komputer',
            'website' => 'https://laminfokom.or.id',
            'aktif' => 1,
        ]);
        $lembagaId = $this->db->insertID();

        // 2. Instrumen
        $this->db->table('lam_instrumen')->insert([
            'lam_lembaga_id' => $lembagaId,
            'jenjang'        => 'D3',
            'versi'          => '2.0',
            'nama'           => 'Instrumen Akreditasi Program Studi 2.0 D3 (Informatika & Komputer)',
            'tahun_terbit'   => 2025,
            'driver_class'   => 'Modules\\Akreditasi\\Drivers\\LamInfokomD3\\Driver',
            'total_bobot'    => 400.00,
            'aktif'          => 1,
            'created_at'     => $now,
        ]);
        $instrumenId = $this->db->insertID();

        // 3. Kriteria (urutan & bobot resmi LAM INFOKOM 2.0)
        $kriteriaDef = [
            ['A',  'Kondisi Eksternal',         'kondisi_eksternal',   4, 1],
            ['B',  'Profil Unit Pengelola Program Studi', 'profil_upps', 4, 2],
            ['C1', 'Budaya Mutu',               'kriteria',           40, 3],
            ['C2', 'Relevansi Pendidikan',      'kriteria',          120, 4],
            ['C3', 'Relevansi Penelitian',      'kriteria',           32, 5],
            ['C4', 'Relevansi PkM',             'kriteria',          100, 6],
            ['C5', 'Akuntabilitas',             'kriteria',           40, 7],
            ['C6', 'Diferensiasi Misi',         'kriteria',           40, 8],
            ['D',  'Suplemen Program Studi',    'suplemen',           20, 9],
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

        // 4. Butir per kriteria: [I, P, O]
        $ipoMatrix = [
            'A'  => [1, 0, 0],
            'B'  => [1, 0, 0],
            'C1' => [2, 4, 4],
            'C2' => [4, 7, 9],
            'C3' => [3, 5, 7],
            'C4' => [3, 5, 7],
            'C5' => [2, 4, 4],
            'C6' => [1, 2, 2],
            'D'  => [0, 0, 5],
        ];
        // Tahap PPEPP per kriteria (untuk C1-C6 mengikuti pola Penetapan-Pelaksanaan-Evaluasi-Pengendalian-Peningkatan)
        $tahapPpepp = ['Penetapan','Pelaksanaan','Evaluasi','Pengendalian','Peningkatan'];

        foreach ($ipoMatrix as $kodeKriteria => [$i, $p, $o]) {
            $bobotKriteria = (float) array_values(array_filter($kriteriaDef, fn($k) => $k[0] === $kodeKriteria))[0][3];
            $jumlahButir = $i + $p + $o;
            if ($jumlahButir === 0) continue;
            $bobotPerButir = round($bobotKriteria / $jumlahButir, 2);

            $no = 1;
            foreach (['input' => $i, 'proses' => $p, 'output' => $o] as $ipoLabel => $count) {
                for ($k = 1; $k <= $count; $k++) {
                    $kodeButir = sprintf('%s.%d', $kodeKriteria, $no);
                    $tahap = $tahapPpepp[($no - 1) % 5];
                    $judul = sprintf('Butir %s [%s] - %s', $kodeButir, $tahap, ucfirst($ipoLabel));

                    $this->db->table('lam_butir')->insert([
                        'lam_kriteria_id' => $kriteriaIds[$kodeKriteria],
                        'kode'        => $kodeButir,
                        'judul'       => $judul,
                        'deskripsi'   => "[ISI SESUAI INSTRUMEN] Deskripsi butir {$kodeButir} mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: {$tahap}, kategori IPO: {$ipoLabel}.",
                        'jenis_ipo'   => $ipoLabel,
                        'bobot'       => $bobotPerButir,
                        'tipe_data'   => 'kualitatif',
                        'urutan'      => $no,
                    ]);
                    $butirId = $this->db->insertID();

                    // Rubrik 4 level default - sangat baik / baik / cukup / kurang
                    $rubrikDefault = [
                        4 => 'Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',
                        3 => 'Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',
                        2 => 'Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',
                        1 => 'Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',
                    ];
                    $labels = [4 => 'Sangat Baik', 3 => 'Baik', 2 => 'Cukup', 1 => 'Kurang'];
                    foreach ($rubrikDefault as $skor => $deskriptor) {
                        $this->db->table('lam_butir_rubrik')->insert([
                            'lam_butir_id' => $butirId,
                            'skor'         => $skor,
                            'label'        => $labels[$skor],
                            'deskriptor'   => $deskriptor,
                        ]);
                    }

                    $no++;
                }
            }
        }

        // 5. Definisi tabel LKPS LAM INFOKOM 2.0 D3
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
            ['2.B.2', 'Pemetaan Mata Kuliah dengan CPL', 'C2'],
            ['2.B.3', 'Peta Pemenuhan CPL', 'C2'],
            ['2.B.4', 'Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali', 'C2'],
            ['2.B.5', 'Kesesuaian Bidang Kerja Lulusan', 'C2'],
            ['2.B.6', 'Kepuasan Pengguna Lulusan', 'C2'],
            ['2.C',   'Fleksibilitas dalam Proses Pembelajaran (MBKM)', 'C2'],
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

        // 6. Template LED per kriteria (struktur PPEPP)
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

        // 7. Mapping prodi MI D3 -> instrumen
        $miD3 = $this->db->table('prodi')->where('kode_pddikti', '57401')->get()->getRowArray();
        $miD3Tegal = $this->db->table('prodi')->where('kode_pddikti', '57472')->get()->getRowArray();
        foreach ([$miD3, $miD3Tegal] as $p) {
            if ($p) {
                $this->db->table('prodi_instrumen')->insert([
                    'prodi_id'         => $p['id'],
                    'lam_instrumen_id' => $instrumenId,
                    'periode_mulai'    => date('Y-m-d'),
                    'aktif'            => 1,
                ]);
            }
        }
    }

    private function defaultPrompt(?string $kriteria, ?string $sub): string
    {
        if (!$kriteria) {
            return 'Tuliskan bagian "' . ($sub ?: 'Pendahuluan') . '" dari Laporan Evaluasi Diri (LED) program studi dalam bahasa Indonesia formal akademik.';
        }
        return "Tuliskan narasi tahap {$sub} untuk kriteria {$kriteria} pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.";
    }
}
