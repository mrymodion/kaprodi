<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom kaprodi_dosen_id pada prodi: menunjuk dosen yang menjabat
 * Ketua Program Studi (penandatangan Surat Tugas). Memudahkan pergantian
 * kaprodi dengan memilih dari daftar dosen yang sudah ada.
 */
class AddKaprodiToProdi extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('kaprodi_dosen_id', 'prodi')) {
            $this->forge->addColumn('prodi', [
                'kaprodi_dosen_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'kop_footer_path'],
            ]);
            $this->forge->addForeignKey('kaprodi_dosen_id', 'dosen', 'id', 'SET NULL', 'CASCADE', 'prodi_kaprodi_dosen_id_foreign');
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('kaprodi_dosen_id', 'prodi')) {
            $this->forge->dropForeignKey('prodi', 'prodi_kaprodi_dosen_id_foreign');
            $this->forge->dropColumn('prodi', 'kaprodi_dosen_id');
        }
    }
}
