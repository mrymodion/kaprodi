<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel meta-data akreditasi (driver-agnostic).
 * Memungkinkan menambah lembaga / jenjang / versi instrumen baru
 * cukup dengan seeder, tanpa mengubah kode aplikasi.
 */
class CreateAkreditasiMetaTables extends Migration
{
    public function up(): void
    {
        // lam_lembaga: LAM INFOKOM, LAMDIK, LAMEMBA, BAN-PT, dll.
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode'       => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama'       => ['type' => 'VARCHAR', 'constraint' => 150],
            'website'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'aktif'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('kode');
        $this->forge->createTable('lam_lembaga');

        // lam_instrumen: kombinasi lembaga + jenjang + versi instrumen.
        // Contoh: "LAM INFOKOM 2.0 D3", "BAN-PT APS 4.0 S1".
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_lembaga_id' => ['type' => 'INT', 'unsigned' => true],
            'jenjang'        => ['type' => 'VARCHAR', 'constraint' => 10],
            'versi'          => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 200],
            'tahun_terbit'   => ['type' => 'YEAR', 'null' => true],
            'driver_class'   => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'total_bobot'    => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 400.00],
            'aktif'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['lam_lembaga_id','jenjang','versi']);
        $this->forge->addForeignKey('lam_lembaga_id', 'lam_lembaga', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lam_instrumen');

        // lam_kriteria: kriteria di dalam sebuah instrumen.
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_instrumen_id'  => ['type' => 'INT', 'unsigned' => true],
            'kode'              => ['type' => 'VARCHAR', 'constraint' => 20],   // C1, C2, A, B, D
            'nama'              => ['type' => 'VARCHAR', 'constraint' => 200],
            'jenis'             => ['type' => 'ENUM', 'constraint' => ['kriteria','kondisi_eksternal','profil_upps','suplemen'], 'default' => 'kriteria'],
            'bobot'             => ['type' => 'DECIMAL', 'constraint' => '6,2', 'default' => 0],
            'urutan'            => ['type' => 'INT', 'default' => 0],
            'deskripsi'         => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['lam_instrumen_id','kode']);
        $this->forge->addForeignKey('lam_instrumen_id', 'lam_instrumen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lam_kriteria');

        // lam_butir: butir penilaian (item rubrik).
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_kriteria_id'   => ['type' => 'INT', 'unsigned' => true],
            'kode'              => ['type' => 'VARCHAR', 'constraint' => 30],
            'judul'             => ['type' => 'VARCHAR', 'constraint' => 250],
            'deskripsi'         => ['type' => 'TEXT', 'null' => true],
            'jenis_ipo'         => ['type' => 'ENUM', 'constraint' => ['input','proses','output']],
            'bobot'             => ['type' => 'DECIMAL', 'constraint' => '6,2', 'default' => 0],
            'tipe_data'         => ['type' => 'ENUM', 'constraint' => ['kuantitatif','kualitatif','campuran'], 'default' => 'kualitatif'],
            'formula'           => ['type' => 'TEXT', 'null' => true],  // formula/SQL untuk auto-hitung
            'tabel_lkps_ref'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'urutan'            => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['lam_kriteria_id','kode']);
        $this->forge->addForeignKey('lam_kriteria_id', 'lam_kriteria', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lam_butir');

        // lam_butir_rubrik: 4 level rubrik (4=Sangat Baik s.d. 1=Kurang).
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_butir_id' => ['type' => 'INT', 'unsigned' => true],
            'skor'         => ['type' => 'TINYINT', 'constraint' => 1],
            'label'        => ['type' => 'VARCHAR', 'constraint' => 30],
            'deskriptor'   => ['type' => 'TEXT'],
            'syarat_json'  => ['type' => 'LONGTEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['lam_butir_id','skor']);
        $this->forge->addForeignKey('lam_butir_id', 'lam_butir', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lam_butir_rubrik');

        // lam_tabel_lkps: definisi tabel LKPS (schema kolom JSON).
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_instrumen_id'  => ['type' => 'INT', 'unsigned' => true],
            'kode'              => ['type' => 'VARCHAR', 'constraint' => 30],
            'judul'             => ['type' => 'VARCHAR', 'constraint' => 250],
            'kriteria_kode'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'schema_json'       => ['type' => 'LONGTEXT', 'null' => true],
            'urutan'            => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['lam_instrumen_id','kode']);
        $this->forge->addForeignKey('lam_instrumen_id', 'lam_instrumen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lam_tabel_lkps');

        // lam_template_led: template & prompt LED per kriteria.
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'lam_instrumen_id' => ['type' => 'INT', 'unsigned' => true],
            'lam_kriteria_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'bagian'           => ['type' => 'VARCHAR', 'constraint' => 50], // pendahuluan|kriteria|penutup
            'sub_bagian'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // penetapan|pelaksanaan|evaluasi|pengendalian|peningkatan
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 250],
            'template_isi'     => ['type' => 'LONGTEXT', 'null' => true],
            'prompt_groq'      => ['type' => 'LONGTEXT', 'null' => true],
            'urutan'           => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('lam_instrumen_id', 'lam_instrumen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lam_kriteria_id', 'lam_kriteria', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('lam_template_led');

        // prodi_instrumen: mapping prodi -> instrumen LAM yang dipakai.
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'lam_instrumen_id'  => ['type' => 'INT', 'unsigned' => true],
            'periode_mulai'     => ['type' => 'DATE', 'null' => true],
            'aktif'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id','lam_instrumen_id']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lam_instrumen_id', 'lam_instrumen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prodi_instrumen');

        // butir_skor: hasil penilaian per butir per prodi per TS.
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'lam_butir_id'      => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'skor_auto'         => ['type' => 'DECIMAL', 'constraint' => '3,2', 'null' => true],
            'skor_manual'       => ['type' => 'DECIMAL', 'constraint' => '3,2', 'null' => true],
            'skor_final'        => ['type' => 'DECIMAL', 'constraint' => '3,2', 'null' => true],
            'sumber_data_json'  => ['type' => 'LONGTEXT', 'null' => true],
            'catatan'           => ['type' => 'TEXT', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_by'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id','lam_butir_id','tahun_akademik_id'], false, 'uniq_skor');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lam_butir_id', 'lam_butir', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('butir_skor');
    }

    public function down(): void
    {
        foreach (['butir_skor','prodi_instrumen','lam_template_led','lam_tabel_lkps','lam_butir_rubrik','lam_butir','lam_kriteria','lam_instrumen','lam_lembaga'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
