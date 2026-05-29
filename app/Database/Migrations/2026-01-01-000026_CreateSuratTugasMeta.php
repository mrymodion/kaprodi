<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratTugasMeta extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'dosen_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true],
            'nomor'             => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'tanggal'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'kota'              => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'dasar'              => ['type' => 'TEXT', 'null' => true],
            'signer_nama'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'signer_nip'        => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['dosen_id', 'tahun_akademik_id']);
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('surat_tugas_meta');
    }

    public function down()
    {
        $this->forge->dropTable('surat_tugas_meta');
    }
}
