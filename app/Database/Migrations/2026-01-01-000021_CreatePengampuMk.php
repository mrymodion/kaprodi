<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Penugasan mengajar dosen (mata kuliah diampu) per tahun akademik.
 * Menjadi sumber data untuk menghitung SKS Pendidikan pada EWMP secara otomatis.
 * Kategori memetakan ke bucket EWMP:
 *   ps_sendiri  -> sks_pendidikan_ps
 *   ps_lain_pt  -> sks_pendidikan_ps_lain_pt
 *   pt_lain     -> sks_pendidikan_pt_lain
 */
class CreatePengampuMk extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('pengampu_mk')) {
            return;
        }
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'dosen_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true],
            'mata_kuliah_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nama_mk'           => ['type' => 'VARCHAR', 'constraint' => 200],
            'kategori'          => ['type' => 'ENUM', 'constraint' => ['ps_sendiri', 'ps_lain_pt', 'pt_lain'], 'default' => 'ps_sendiri'],
            'sks'               => ['type' => 'DECIMAL', 'constraint' => '4,1', 'default' => 0],
            'jumlah_kelas'      => ['type' => 'INT', 'default' => 1],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['dosen_id', 'tahun_akademik_id']);
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mata_kuliah_id', 'mata_kuliah', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pengampu_mk');
    }

    public function down(): void
    {
        $this->forge->dropTable('pengampu_mk', true);
    }
}
