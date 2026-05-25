<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom kop_footer_path pada prodi: gambar kaki surat (footer) yang
 * dipakai di bagian bawah Surat Tugas Mengajar. Pelengkap kop_path (header).
 */
class AddKopFooterToProdi extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('kop_footer_path', 'prodi')) {
            $this->forge->addColumn('prodi', [
                'kop_footer_path' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'kop_path'],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('kop_footer_path', 'prodi')) {
            $this->forge->dropColumn('prodi', 'kop_footer_path');
        }
    }
}
