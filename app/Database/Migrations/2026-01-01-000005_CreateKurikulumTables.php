<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Kurikulum & OBE: CPL, CPMK, Mata Kuliah, RPS.
 * Memenuhi LKPS Tabel 2.B.1 (Isi), 2.B.2, 2.B.3 (Peta CPL).
 */
class CreateKurikulumTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'  => ['type' => 'INT', 'unsigned' => true],
            'kode'      => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'      => ['type' => 'VARCHAR', 'constraint' => 200],
            'tahun_berlaku' => ['type' => 'YEAR'],
            'aktif'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kurikulum');

        // CPL
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kurikulum_id' => ['type' => 'INT', 'unsigned' => true],
            'kode'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'aspek'        => ['type' => 'ENUM', 'constraint' => ['sikap','pengetahuan','keterampilan_umum','keterampilan_khusus']],
            'deskripsi'    => ['type' => 'TEXT'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['kurikulum_id','kode']);
        $this->forge->addForeignKey('kurikulum_id', 'kurikulum', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cpl');

        // Mata Kuliah
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kurikulum_id' => ['type' => 'INT', 'unsigned' => true],
            'kode'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'sks_teori'    => ['type' => 'TINYINT', 'default' => 0],
            'sks_praktek'  => ['type' => 'TINYINT', 'default' => 0],
            'sks_lapangan' => ['type' => 'TINYINT', 'default' => 0],
            'semester'     => ['type' => 'TINYINT'],
            'jenis'        => ['type' => 'ENUM', 'constraint' => ['wajib','pilihan','mbkm']],
            'rps_path'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['kurikulum_id','kode']);
        $this->forge->addForeignKey('kurikulum_id', 'kurikulum', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mata_kuliah');

        // CPMK
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'mata_kuliah_id' => ['type' => 'INT', 'unsigned' => true],
            'kode'           => ['type' => 'VARCHAR', 'constraint' => 20],
            'deskripsi'      => ['type' => 'TEXT'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('mata_kuliah_id', 'mata_kuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cpmk');

        // Peta CPL ↔ MK (Tabel 2.B.3)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'mata_kuliah_id' => ['type' => 'INT', 'unsigned' => true],
            'cpl_id'         => ['type' => 'INT', 'unsigned' => true],
            'bobot'          => ['type' => 'DECIMAL', 'constraint' => '3,2', 'default' => 1.00],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['mata_kuliah_id','cpl_id']);
        $this->forge->addForeignKey('mata_kuliah_id', 'mata_kuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('cpl_id', 'cpl', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mk_cpl');

        // Ketercapaian CPL per MK per TS
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'mata_kuliah_id'    => ['type' => 'INT', 'unsigned' => true],
            'cpl_id'            => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true],
            'persen_tercapai'   => ['type' => 'DECIMAL', 'constraint' => '5,2'],
            'jumlah_mhs'        => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['mata_kuliah_id','cpl_id','tahun_akademik_id'], false, 'uniq_cpl_capaian');
        $this->forge->createTable('cpl_ketercapaian');
    }

    public function down(): void
    {
        foreach (['cpl_ketercapaian','mk_cpl','cpmk','mata_kuliah','cpl','kurikulum'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
