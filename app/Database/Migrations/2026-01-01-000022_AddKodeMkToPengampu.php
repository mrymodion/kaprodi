<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom kode_mk pada pengampu_mk agar kode & nama mata kuliah terpisah
 * (mengikuti format Surat Tugas Mengajar: kolom "Mata Kuliah" dan "Kode Mata Kuliah").
 */
class AddKodeMkToPengampu extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('pengampu_mk') && !$this->db->fieldExists('kode_mk', 'pengampu_mk')) {
            $this->forge->addColumn('pengampu_mk', [
                'kode_mk' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'mata_kuliah_id'],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('kode_mk', 'pengampu_mk')) {
            $this->forge->dropColumn('pengampu_mk', 'kode_mk');
        }
    }
}
