<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Kanonikalisasi DOI yang sudah tersimpan: buang prefiks resolver (https://doi.org/)
 * dan jadikan huruf kecil. Membuat anti-duplikat impor konsisten dan mencegah
 * tautan doi.org berlipat (https://doi.org/https://doi.org/...).
 */
class NormalizePublikasiDoi extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('doi', 'publikasi')) {
            return;
        }
        $rows = $this->db->table('publikasi')
            ->select('id, doi')
            ->where('doi IS NOT NULL')
            ->where('doi !=', '')
            ->get()->getResultArray();

        foreach ($rows as $r) {
            $norm = \App\Models\PublikasiModel::normDoi($r['doi'] ?? '');
            if ($norm !== '' && $norm !== $r['doi']) {
                $this->db->table('publikasi')->where('id', (int) $r['id'])->update(['doi' => $norm]);
            }
        }
    }

    public function down(): void
    {
        // Tidak reversibel (nilai asli tidak disimpan); no-op.
    }
}
