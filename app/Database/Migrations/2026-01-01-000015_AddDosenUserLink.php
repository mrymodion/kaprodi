<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tautkan akun login (Shield users) ke record dosen agar dosen dapat
 * memutakhirkan datanya sendiri, dan tandai kepemilikan publikasi per dosen.
 */
class AddDosenUserLink extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('user_id', 'dosen')) {
            $this->forge->addColumn('dosen', [
                'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'prodi_id'],
            ]);
            $this->forge->addKey('user_id');
            // index unik agar 1 akun = 1 dosen
            $this->db->query('CREATE UNIQUE INDEX uniq_dosen_user ON dosen (user_id)');
        }

        if (!$this->db->fieldExists('dosen_id', 'publikasi')) {
            $this->forge->addColumn('publikasi', [
                'dosen_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'prodi_id'],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('user_id', 'dosen')) {
            $this->db->query('DROP INDEX uniq_dosen_user ON dosen');
            $this->forge->dropColumn('dosen', 'user_id');
        }
        if ($this->db->fieldExists('dosen_id', 'publikasi')) {
            $this->forge->dropColumn('publikasi', 'dosen_id');
        }
    }
}
