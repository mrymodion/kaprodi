<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterTables extends Migration
{
    public function up(): void
    {
        // =========================================================
        // PERGURUAN TINGGI
        // =========================================================
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'singkatan'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'alamat'      => ['type' => 'TEXT', 'null' => true],
            'kota'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'provinsi'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'logo_path'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'website'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('perguruan_tinggi');

        // =========================================================
        // UPPS (Unit Pengelola Program Studi)
        // =========================================================
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'perguruan_tinggi_id'  => ['type' => 'INT', 'unsigned' => true],
            'kode'                 => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nama'                 => ['type' => 'VARCHAR', 'constraint' => 200],
            'jenis'                => ['type' => 'ENUM', 'constraint' => ['fakultas','sekolah','jurusan','departemen','direktorat','tidak_ada'], 'default' => 'tidak_ada'],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('perguruan_tinggi_id', 'perguruan_tinggi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('upps');

        // =========================================================
        // PROGRAM STUDI
        // =========================================================
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'upps_id'      => ['type' => 'INT', 'unsigned' => true],
            'kode_pddikti' => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'jenjang'      => ['type' => 'ENUM', 'constraint' => ['D1','D2','D3','D4','S1','S2','S3','Profesi','Sp1','Sp2']],
            'gelar'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'singkatan_gelar' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'akreditasi_saat_ini' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'no_sk_akreditasi'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_sk_akreditasi'   => ['type' => 'DATE', 'null' => true],
            'tgl_kadaluarsa'      => ['type' => 'DATE', 'null' => true],
            'status'              => ['type' => 'ENUM', 'constraint' => ['aktif','non_aktif'], 'default' => 'aktif'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('kode_pddikti');
        $this->forge->addForeignKey('upps_id', 'upps', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prodi');

        // =========================================================
        // TAHUN AKADEMIK
        // =========================================================
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 10],     // ex: 2024/2025
            'tahun_mulai'=> ['type' => 'YEAR'],
            'tahun_akhir'=> ['type' => 'YEAR'],
            'semester'   => ['type' => 'ENUM', 'constraint' => ['ganjil','genap','tahunan'], 'default' => 'tahunan'],
            'is_ts'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'aktif'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['kode','semester']);
        $this->forge->createTable('tahun_akademik');
    }

    public function down(): void
    {
        $this->forge->dropTable('tahun_akademik', true);
        $this->forge->dropTable('prodi', true);
        $this->forge->dropTable('upps', true);
        $this->forge->dropTable('perguruan_tinggi', true);
    }
}
