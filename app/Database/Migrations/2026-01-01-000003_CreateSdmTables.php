<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel SDM: DTPR (dosen), Tendik, Mahasiswa, Lulusan.
 * Skema disesuaikan dengan kebutuhan LKPS LAM INFOKOM 2.0 D3
 * (Tabel 1.A.4 EWMP, 1.A.5 Tendik, 2.A.1-3 Mahasiswa, 2.B.4-6 Lulusan).
 */
class CreateSdmTables extends Migration
{
    public function up(): void
    {
        // DTPR
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'            => ['type' => 'INT', 'unsigned' => true],
            'nidn'                => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nip'                 => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nama'                => ['type' => 'VARCHAR', 'constraint' => 150],
            'gelar_depan'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'gelar_belakang'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'jenis_kelamin'       => ['type' => 'ENUM', 'constraint' => ['L','P'], 'null' => true],
            'tempat_lahir'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'       => ['type' => 'DATE', 'null' => true],
            'email'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_hp'               => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
            'pendidikan_terakhir' => ['type' => 'ENUM', 'constraint' => ['S1','S2','S3','Sp','Sp2'], 'null' => true],
            'bidang_keahlian'     => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'linieritas'          => ['type' => 'ENUM', 'constraint' => ['linier','tidak_linier'], 'default' => 'linier'],
            'jabatan_akademik'    => ['type' => 'ENUM', 'constraint' => ['Tenaga Pengajar','Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'], 'null' => true],
            'no_sk_jad'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_sk_jad'          => ['type' => 'DATE', 'null' => true],
            'sertifikat_pendidik' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'no_serdos'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'status_kepegawaian'  => ['type' => 'ENUM', 'constraint' => ['tetap','tidak_tetap','dosen_industri'], 'default' => 'tetap'],
            'sinta_id'            => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'scopus_id'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'google_scholar_id'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'orcid'               => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'aktif'               => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('nidn');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dosen');

        // EWMP per dosen per semester (Tabel 1.A.4)
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'dosen_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true],
            'sks_pendidikan_ps'           => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'sks_pendidikan_ps_lain_pt'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'sks_pendidikan_pt_lain'      => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'sks_penelitian'              => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'sks_pkm'                     => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'sks_tugas_tambahan'          => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'total_sks'                   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['dosen_id','tahun_akademik_id']);
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dosen_ewmp');

        // Tendik (Tabel 1.A.5)
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'            => ['type' => 'INT', 'unsigned' => true],
            'nip'                 => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nama'                => ['type' => 'VARCHAR', 'constraint' => 150],
            'jenis'               => ['type' => 'ENUM', 'constraint' => ['pustakawan','laboran','teknisi','operator','administrasi','lainnya']],
            'pendidikan_terakhir' => ['type' => 'ENUM', 'constraint' => ['SMA','D1','D2','D3','D4','S1','S2','S3'], 'null' => true],
            'sertifikat_kompetensi' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'aktif'               => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tendik');

        // Mahasiswa (Tabel 2.A.1, 2.A.2, 2.A.3)
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'            => ['type' => 'INT', 'unsigned' => true],
            'nim'                 => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'                => ['type' => 'VARCHAR', 'constraint' => 150],
            'jenis_kelamin'       => ['type' => 'ENUM', 'constraint' => ['L','P'], 'null' => true],
            'tempat_lahir'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'       => ['type' => 'DATE', 'null' => true],
            'email'               => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'no_hp'               => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
            'kota_asal'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provinsi_asal'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'negara_asal'         => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'Indonesia'],
            'tahun_masuk_id'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'jalur_masuk'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'              => ['type' => 'ENUM', 'constraint' => ['aktif','cuti','do','keluar','lulus'], 'default' => 'aktif'],
            'tgl_lulus'           => ['type' => 'DATE', 'null' => true],
            'ipk'                 => ['type' => 'DECIMAL', 'constraint' => '3,2', 'null' => true],
            'masa_studi_bulan'    => ['type' => 'INT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id','nim']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_masuk_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('mahasiswa');

        // Tracer Study (Tabel 2.B.4, 2.B.5)
        $this->forge->addField([
            'id'                       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'mahasiswa_id'             => ['type' => 'INT', 'unsigned' => true],
            'tgl_isi'                  => ['type' => 'DATE', 'null' => true],
            'masa_tunggu_bulan'        => ['type' => 'INT', 'null' => true],
            'kesesuaian_bidang_kerja'  => ['type' => 'ENUM', 'constraint' => ['sangat_sesuai','sesuai','kurang_sesuai','tidak_sesuai'], 'null' => true],
            'tingkat_pekerjaan'        => ['type' => 'ENUM', 'constraint' => ['lokal','nasional','multinasional','internasional','wirausaha'], 'null' => true],
            'nama_perusahaan'          => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'jabatan'                  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'gaji_pertama'             => ['type' => 'BIGINT', 'null' => true],
            'lanjut_studi'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tracer_study');

        // Kepuasan Pengguna Lulusan (Tabel 2.B.6)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'mahasiswa_id'    => ['type' => 'INT', 'unsigned' => true],
            'nama_atasan'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'jabatan_atasan'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'nama_perusahaan' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            // 7 aspek skala 1-4 sesuai LAM
            'etika'           => ['type' => 'TINYINT', 'null' => true],
            'keahlian'        => ['type' => 'TINYINT', 'null' => true],
            'bahasa_asing'    => ['type' => 'TINYINT', 'null' => true],
            'teknologi_info'  => ['type' => 'TINYINT', 'null' => true],
            'komunikasi'      => ['type' => 'TINYINT', 'null' => true],
            'kerjasama_tim'   => ['type' => 'TINYINT', 'null' => true],
            'pengembangan_diri' => ['type' => 'TINYINT', 'null' => true],
            'saran'           => ['type' => 'TEXT', 'null' => true],
            'tgl_isi'         => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kepuasan_pengguna_lulusan');
    }

    public function down(): void
    {
        foreach (['kepuasan_pengguna_lulusan','tracer_study','mahasiswa','tendik','dosen_ewmp','dosen'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
