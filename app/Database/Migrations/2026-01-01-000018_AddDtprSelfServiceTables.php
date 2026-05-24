<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Dukungan self-service DTPR untuk akreditasi:
 *  - pengembangan_dtpr.dosen_id : tautkan catatan pengembangan ke dosen tertentu
 *  - hki.dosen_id               : tautkan luaran HKI ke dosen pemilik
 *  - rekognisi_dosen            : tabel baru rekognisi/pengakuan DTPR (reviewer, editor,
 *                                 keynote, asesor, penghargaan, dll) — LKPS 3.A.3 / C3
 */
class AddDtprSelfServiceTables extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('dosen_id', 'pengembangan_dtpr')) {
            $this->forge->addColumn('pengembangan_dtpr', [
                'dosen_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'prodi_id'],
            ]);
        }

        if (!$this->db->fieldExists('dosen_id', 'hki')) {
            $this->forge->addColumn('hki', [
                'dosen_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'prodi_id'],
            ]);
        }

        if (!$this->db->tableExists('rekognisi_dosen')) {
            $this->forge->addField([
                'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
                'dosen_id'          => ['type' => 'INT', 'unsigned' => true],
                'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'jenis'             => ['type' => 'VARCHAR', 'constraint' => 40],
                'judul'             => ['type' => 'VARCHAR', 'constraint' => 250],
                'penyelenggara'     => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
                'tingkat'           => ['type' => 'ENUM', 'constraint' => ['lokal', 'nasional', 'internasional'], 'default' => 'nasional'],
                'tahun'             => ['type' => 'INT', 'null' => true],
                'link_bukti'        => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'created_at'        => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addKey('dosen_id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('rekognisi_dosen');
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('rekognisi_dosen', true);
        if ($this->db->fieldExists('dosen_id', 'hki')) {
            $this->forge->dropColumn('hki', 'dosen_id');
        }
        if ($this->db->fieldExists('dosen_id', 'pengembangan_dtpr')) {
            $this->forge->dropColumn('pengembangan_dtpr', 'dosen_id');
        }
    }
}
