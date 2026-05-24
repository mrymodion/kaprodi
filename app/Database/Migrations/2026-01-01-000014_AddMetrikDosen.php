<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambah kolom metrik penelitian dosen yang diisi otomatis dari OpenAlex
 * (berdasarkan ORCID): h-index, total sitasi, jumlah karya, i10-index,
 * dan kapan terakhir disinkronkan.
 */
class AddMetrikDosen extends Migration
{
    private array $cols = [
        'h_index'           => ['type' => 'INT', 'null' => true],
        'i10_index'         => ['type' => 'INT', 'null' => true],
        'sitasi_total'      => ['type' => 'INT', 'null' => true],
        'karya_total'       => ['type' => 'INT', 'null' => true],
        'metrik_updated_at' => ['type' => 'DATETIME', 'null' => true],
    ];

    public function up(): void
    {
        $add = [];
        foreach ($this->cols as $name => $def) {
            if (!$this->db->fieldExists($name, 'dosen')) {
                $add[$name] = $def;
            }
        }
        if ($add !== []) {
            $this->forge->addColumn('dosen', $add);
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->cols) as $name) {
            if ($this->db->fieldExists($name, 'dosen')) {
                $this->forge->dropColumn('dosen', $name);
            }
        }
    }
}
