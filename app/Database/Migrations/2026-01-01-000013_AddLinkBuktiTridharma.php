<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom link_bukti pada penelitian, pkm, hki agar bisa ditautkan
 * sebagai eviden di LKPS (3.A.2, 4.A.2, 3.C.3, 4.C.3).
 * (publikasi sudah punya kolom `url`; kerjasama punya `bukti_implementasi`.)
 */
class AddLinkBuktiTridharma extends Migration
{
    public function up(): void
    {
        foreach (['penelitian', 'pkm', 'hki'] as $t) {
            if ($this->db->tableExists($t) && !$this->db->fieldExists('link_bukti', $t)) {
                $this->forge->addColumn($t, [
                    'link_bukti' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach (['penelitian', 'pkm', 'hki'] as $t) {
            if ($this->db->tableExists($t) && $this->db->fieldExists('link_bukti', $t)) {
                $this->forge->dropColumn($t, 'link_bukti');
            }
        }
    }
}
