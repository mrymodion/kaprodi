<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom link_bukti pada butir_skor untuk menyimpan
 * tautan eviden (mis. Google Drive) per butir penilaian.
 */
class AddLinkBuktiToButirSkor extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('butir_skor') && !$this->db->fieldExists('link_bukti', 'butir_skor')) {
            $this->forge->addColumn('butir_skor', [
                'link_bukti' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 500,
                    'null'       => true,
                    'after'      => 'catatan',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->tableExists('butir_skor') && $this->db->fieldExists('link_bukti', 'butir_skor')) {
            $this->forge->dropColumn('butir_skor', 'link_bukti');
        }
    }
}
