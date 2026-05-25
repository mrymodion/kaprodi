<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom kop_path pada prodi: gambar kop surat (letterhead) yang dipakai
 * di bagian atas Surat Tugas Mengajar. Disimpan di writable/kop/.
 */
class AddKopPathToProdi extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('kop_path', 'prodi')) {
            $this->forge->addColumn('prodi', [
                'kop_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'status'],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('kop_path', 'prodi')) {
            $this->forge->dropColumn('prodi', 'kop_path');
        }
    }
}
