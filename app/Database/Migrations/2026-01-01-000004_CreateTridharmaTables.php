<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tridharma: Penelitian, PkM, Publikasi, HKI, Kerjasama, Prestasi.
 * Memenuhi LKPS Tabel 3.A.2, 3.C.1-3, 4.A.2, 4.C.1-3, 2.D.
 */
class CreateTridharmaTables extends Migration
{
    public function up(): void
    {
        // Penelitian
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'judul'             => ['type' => 'TEXT'],
            'ketua_dosen_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'sumber_dana'       => ['type' => 'ENUM', 'constraint' => ['pt_sendiri','mandiri','dalam_negeri','luar_negeri'], 'default' => 'pt_sendiri'],
            'nama_sumber_dana'  => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'jumlah_dana'       => ['type' => 'BIGINT', 'default' => 0],
            'mata_uang'         => ['type' => 'VARCHAR', 'constraint' => 5, 'default' => 'IDR'],
            'tahun_pelaksanaan' => ['type' => 'YEAR', 'null' => true],
            'libatkan_mahasiswa'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'jumlah_mhs_terlibat' => ['type' => 'INT', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ketua_dosen_id', 'dosen', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('penelitian');

        // Anggota dosen di penelitian
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'penelitian_id'  => ['type' => 'INT', 'unsigned' => true],
            'dosen_id'       => ['type' => 'INT', 'unsigned' => true],
            'peran'          => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'anggota'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('penelitian_id', 'penelitian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penelitian_anggota');

        // PkM
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'judul'             => ['type' => 'TEXT'],
            'ketua_dosen_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'sumber_dana'       => ['type' => 'ENUM', 'constraint' => ['pt_sendiri','mandiri','dalam_negeri','luar_negeri'], 'default' => 'pt_sendiri'],
            'nama_sumber_dana'  => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'jumlah_dana'       => ['type' => 'BIGINT', 'default' => 0],
            'mitra'             => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'lokasi'            => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'tahun_pelaksanaan' => ['type' => 'YEAR', 'null' => true],
            'libatkan_mahasiswa'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'jumlah_mhs_terlibat' => ['type' => 'INT', 'default' => 0],
            'dampak'            => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ketua_dosen_id', 'dosen', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pkm');

        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'pkm_id'   => ['type' => 'INT', 'unsigned' => true],
            'dosen_id' => ['type' => 'INT', 'unsigned' => true],
            'peran'    => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'anggota'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pkm_id', 'pkm', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pkm_anggota');

        // Publikasi (Tabel 3.C.2 / 4.C.2)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'       => ['type' => 'INT', 'unsigned' => true],
            'kategori'       => ['type' => 'ENUM', 'constraint' => ['penelitian','pkm']],
            'jenis'          => ['type' => 'ENUM', 'constraint' => [
                'jurnal_nasional_tidak_terakreditasi',
                'jurnal_nasional_terakreditasi',
                'jurnal_internasional',
                'jurnal_internasional_bereputasi',
                'seminar_lokal',
                'seminar_nasional',
                'seminar_internasional',
                'media_massa_nasional',
                'media_massa_internasional',
                'buku',
                'book_chapter',
            ]],
            'judul'          => ['type' => 'TEXT'],
            'penulis'        => ['type' => 'TEXT'],
            'nama_media'     => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'tahun'          => ['type' => 'YEAR', 'null' => true],
            'volume'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'doi'            => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'url'            => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'sitasi'         => ['type' => 'INT', 'default' => 0],
            'sinta_score'    => ['type' => 'TINYINT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('publikasi');

        // HKI (Tabel 3.C.3 / 4.C.3)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'   => ['type' => 'INT', 'unsigned' => true],
            'kategori'   => ['type' => 'ENUM', 'constraint' => ['penelitian','pkm']],
            'jenis'      => ['type' => 'ENUM', 'constraint' => ['paten','paten_sederhana','hak_cipta','desain_industri','dtlst','indikasi_geografis','prl_varietas']],
            'judul'      => ['type' => 'TEXT'],
            'no_hki'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun'      => ['type' => 'YEAR', 'null' => true],
            'pemilik'    => ['type' => 'TEXT', 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['terdaftar','granted'], 'default' => 'granted'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hki');

        // Kerjasama (Tabel 3.C.1 / 4.C.1)
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'nama_mitra'        => ['type' => 'VARCHAR', 'constraint' => 250],
            'bidang'            => ['type' => 'ENUM', 'constraint' => ['pendidikan','penelitian','pkm','tridharma']],
            'tingkat'           => ['type' => 'ENUM', 'constraint' => ['lokal','wilayah','nasional','internasional']],
            'jenis_dokumen'     => ['type' => 'ENUM', 'constraint' => ['mou','moa','ia','sk']],
            'no_dokumen'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_mulai'         => ['type' => 'DATE'],
            'tgl_berakhir'      => ['type' => 'DATE', 'null' => true],
            'manfaat'           => ['type' => 'TEXT', 'null' => true],
            'bukti_implementasi'=> ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kerjasama');

        // Prestasi mahasiswa (Tabel 2.D)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'       => ['type' => 'INT', 'unsigned' => true],
            'mahasiswa_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nama_kegiatan'  => ['type' => 'VARCHAR', 'constraint' => 250],
            'penyelenggara'  => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'tingkat'        => ['type' => 'ENUM', 'constraint' => ['lokal','wilayah','nasional','internasional']],
            'jenis'          => ['type' => 'ENUM', 'constraint' => ['akademik','non_akademik']],
            'prestasi'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tahun'          => ['type' => 'YEAR', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('prestasi_mahasiswa');
    }

    public function down(): void
    {
        foreach (['prestasi_mahasiswa','kerjasama','hki','publikasi','pkm_anggota','pkm','penelitian_anggota','penelitian'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
