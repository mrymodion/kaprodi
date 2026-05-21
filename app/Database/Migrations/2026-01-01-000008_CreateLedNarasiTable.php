<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLedNarasiTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'             => ['type' => 'INT', 'unsigned' => true],
            'lam_template_led_id'  => ['type' => 'INT', 'unsigned' => true],
            'isi'                  => ['type' => 'LONGTEXT', 'null' => true],
            'generated_by_ai'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'updated_by'           => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id', 'lam_template_led_id']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lam_template_led_id', 'lam_template_led', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('led_narasi');
    }

    public function down(): void
    {
        $this->forge->dropTable('led_narasi', true);
    }
}
