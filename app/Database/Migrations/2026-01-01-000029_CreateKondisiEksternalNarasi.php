<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Sprint 3 Kriteria A: simpan narasi LED hasil draft AI per sub-bagian
 * Kondisi Eksternal (A.1, A.2, A.3, A.4), agar tidak perlu regenerate
 * setiap kali halaman dibuka & bisa diedit prodi secara berkala.
 */
class CreateKondisiEksternalNarasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'     => ['type' => 'INT', 'unsigned' => true],
            'sub_bagian'   => ['type' => 'VARCHAR', 'constraint' => 10],
            'narasi'       => ['type' => 'LONGTEXT'],
            'source_count' => ['type' => 'INT', 'default' => 0],
            'generated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_by'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id', 'sub_bagian']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kondisi_eksternal_narasi');
    }

    public function down()
    {
        $this->forge->dropTable('kondisi_eksternal_narasi', true);
    }
}
