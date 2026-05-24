<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Modul SPMI: Audit Mutu Internal (temuan), Dokumen Mutu, dan Registry Auditor.
 * Tabel siklus PPEPP (`ppepp`) sudah dibuat di migrasi 000006; di sini hanya
 * ditambahkan tabel pelengkap untuk mempermudah penginputan data SPMI.
 */
class CreateSpmiTables extends Migration
{
    public function up(): void
    {
        // Audit Mutu Internal — temuan & tindak lanjut
        if (!$this->db->tableExists('ami_temuan')) {
            $this->forge->addField([
                'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
                'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'tanggal_audit'     => ['type' => 'DATE', 'null' => true],
                'lingkup'           => ['type' => 'VARCHAR', 'constraint' => 200],
                'kriteria'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'kategori'          => ['type' => 'ENUM', 'constraint' => ['mayor', 'minor', 'observasi'], 'default' => 'minor'],
                'deskripsi'         => ['type' => 'TEXT'],
                'akar_masalah'      => ['type' => 'TEXT', 'null' => true],
                'rekomendasi'       => ['type' => 'TEXT', 'null' => true],
                'tindak_lanjut'     => ['type' => 'TEXT', 'null' => true],
                'status'            => ['type' => 'ENUM', 'constraint' => ['terbuka', 'proses', 'selesai'], 'default' => 'terbuka'],
                'auditor'           => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'link_bukti'        => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'created_at'        => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('ami_temuan');
        }

        // Dokumen Mutu SPMI (kebijakan, manual, standar, formulir/SOP)
        if (!$this->db->tableExists('dokumen_mutu')) {
            $this->forge->addField([
                'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'        => ['type' => 'INT', 'unsigned' => true],
                'jenis'           => ['type' => 'ENUM', 'constraint' => ['kebijakan', 'manual', 'standar', 'formulir', 'sop', 'lainnya'], 'default' => 'standar'],
                'kode'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'judul'           => ['type' => 'VARCHAR', 'constraint' => 250],
                'versi'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
                'tanggal_berlaku' => ['type' => 'DATE', 'null' => true],
                'link_bukti'      => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'created_at'      => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('dokumen_mutu');
        }

        // Registry auditor SPMI (boleh tertaut dosen internal atau auditor eksternal)
        if (!$this->db->tableExists('spmi_auditor')) {
            $this->forge->addField([
                'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'       => ['type' => 'INT', 'unsigned' => true],
                'dosen_id'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'nama'           => ['type' => 'VARCHAR', 'constraint' => 150],
                'tersertifikasi' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'no_sertifikat'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'lembaga'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'created_at'     => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'SET NULL', 'CASCADE');
            $this->forge->createTable('spmi_auditor');
        }
    }

    public function down(): void
    {
        foreach (['ami_temuan', 'dokumen_mutu', 'spmi_auditor'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
