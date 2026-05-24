<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Relasi many-to-many publikasi ↔ dosen agar satu publikasi (karya bersama)
 * dapat dimiliki/diakui oleh lebih dari satu dosen. Kolom publikasi.dosen_id
 * lama dipertahankan sebagai legacy; sumber kebenaran kepemilikan kini di pivot.
 */
class CreatePublikasiDosenPivot extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('publikasi_dosen')) {
            return;
        }

        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'publikasi_id' => ['type' => 'INT', 'unsigned' => true],
            'dosen_id'     => ['type' => 'INT', 'unsigned' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['publikasi_id', 'dosen_id']);
        $this->forge->addKey('dosen_id');
        $this->forge->createTable('publikasi_dosen');

        // Backfill dari kolom dosen_id lama.
        if ($this->db->fieldExists('dosen_id', 'publikasi')) {
            $now = date('Y-m-d H:i:s');
            $rows = $this->db->table('publikasi')
                ->select('id, dosen_id')
                ->where('dosen_id IS NOT NULL')
                ->where('dosen_id >', 0)
                ->get()->getResultArray();
            $batch = [];
            foreach ($rows as $r) {
                $batch[] = [
                    'publikasi_id' => (int) $r['id'],
                    'dosen_id'     => (int) $r['dosen_id'],
                    'created_at'   => $now,
                ];
            }
            if ($batch) {
                $this->db->table('publikasi_dosen')->insertBatch($batch);
            }
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('publikasi_dosen', true);
    }
}
