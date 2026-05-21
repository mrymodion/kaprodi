<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // Perguruan Tinggi
        $this->db->table('perguruan_tinggi')->insert([
            'kode'      => 'UTD',
            'nama'      => 'Universitas Teknologi Digital',
            'singkatan' => 'UTD',
            'kota'      => 'Bandung',
            'provinsi'  => 'Jawa Barat',
            'logo_path' => 'assets/logo-utd.png',
            'website'   => 'https://utd.ac.id',
            'created_at'=> $now,
            'updated_at'=> $now,
        ]);
        $ptId = $this->db->insertID();

        // UPPS (tidak ada fakultas → satu UPPS "tidak_ada")
        $this->db->table('upps')->insert([
            'perguruan_tinggi_id' => $ptId,
            'kode'                => '-',
            'nama'                => '-',
            'jenis'               => 'tidak_ada',
            'created_at'          => $now,
            'updated_at'          => $now,
        ]);
        $uppsId = $this->db->insertID();

        // 11 Prodi UTD
        $prodi = [
            ['62201','Akuntansi','S1','Baik Sekali'],
            ['62401','Akuntansi','D3','Baik Sekali'],
            ['90241','Desain Komunikasi Visual','S1','Baik'],
            ['74201','Hukum','S1','Baik'],
            ['55200','Informatika','S1','Baik'],
            ['61201','Manajemen','S1','Baik'],
            ['57401','Manajemen Informatika','D3','Baik'],
            ['57472','Manajemen Informatika (Kampus Kota Tegal)','D3','Baik'],
            ['93104','Manajemen Inovasi','S2','Baik'],
            ['61405','Manajemen Perusahaan','D3','Baik'],
            ['26201','Teknik Industri','S1','Baik'],
        ];
        foreach ($prodi as [$kode, $nama, $jenjang, $akr]) {
            $this->db->table('prodi')->insert([
                'upps_id'             => $uppsId,
                'kode_pddikti'        => $kode,
                'nama'                => $nama,
                'jenjang'             => $jenjang,
                'akreditasi_saat_ini' => $akr,
                'status'              => 'aktif',
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);
        }

        // Tahun Akademik: TS-4 sampai TS (5 tahun)
        $ts = (int) date('Y'); // tahun saat ini sebagai TS
        for ($i = 4; $i >= 0; $i--) {
            $mulai = $ts - $i - 1;
            $akhir = $ts - $i;
            $this->db->table('tahun_akademik')->insert([
                'kode'        => $mulai . '/' . $akhir,
                'tahun_mulai' => $mulai,
                'tahun_akhir' => $akhir,
                'semester'    => 'tahunan',
                'is_ts'       => $i === 0 ? 1 : 0,
                'aktif'       => 1,
            ]);
        }
    }
}
