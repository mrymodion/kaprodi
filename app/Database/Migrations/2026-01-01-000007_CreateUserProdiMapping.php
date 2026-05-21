<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Mapping user (Shield) <-> prodi untuk multi-tenant scoping.
 * Satu user bisa terkait dengan banyak prodi (mis. Dekan/Direktur),
 * tapi tipikal Kaprodi hanya 1.
 */
class CreateUserProdiMapping extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'   => ['type' => 'INT', 'unsigned' => true],
            'prodi_id'  => ['type' => 'INT', 'unsigned' => true],
            'peran'     => ['type' => 'VARCHAR', 'constraint' => 50],   // sinkron dengan kode group Shield (kaprodi, dosen, ...)
            'is_default'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'=> ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['user_id','prodi_id','peran'], false, 'uniq_user_prodi_peran');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        // user_id FK ke tabel users dari Shield ditambahkan setelah Shield migrate (lihat catatan README).
        $this->forge->createTable('user_prodi');
    }

    public function down(): void
    {
        $this->forge->dropTable('user_prodi', true);
    }
}
