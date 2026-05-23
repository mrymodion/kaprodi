<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Dokumen eviden polimorfik untuk dosen & tenaga kependidikan
 * (ijazah, SK jabatan, sertifikat pendidik/kompetensi, dll).
 */
class CreateDokumenEviden extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ref_table'  => ['type' => 'VARCHAR', 'constraint' => 20],   // dosen | tendik
            'ref_id'     => ['type' => 'INT', 'unsigned' => true],
            'jenis'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'file'       => ['type' => 'VARCHAR', 'constraint' => 255],  // nama file di writable/bukti
            'nama_asli'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['ref_table', 'ref_id']);
        $this->forge->createTable('dokumen_eviden');
    }

    public function down(): void
    {
        $this->forge->dropTable('dokumen_eviden', true);
    }
}
