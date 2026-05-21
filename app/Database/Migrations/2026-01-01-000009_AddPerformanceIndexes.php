<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tambahkan indeks pada kolom yang sering digunakan sebagai WHERE/JOIN.
 * Tanpa indeks ini setiap query filter prodi_id melakukan full table scan.
 */
class AddPerformanceIndexes extends Migration
{
    private array $indexes = [
        // tabel               => kolom
        'penelitian'           => [['prodi_id'], ['tahun_pelaksanaan'], ['ketua_dosen_id']],
        'pkm'                  => [['prodi_id'], ['tahun_pelaksanaan'], ['ketua_dosen_id']],
        'publikasi'            => [['prodi_id'], ['kategori'], ['tahun']],
        'hki'                  => [['prodi_id'], ['tahun']],
        'kerjasama'            => [['prodi_id'], ['tingkat']],
        'dosen'                => [['prodi_id'], ['aktif']],
        'mahasiswa'            => [['prodi_id'], ['status']],
        'butir_skor'           => [['prodi_id'], ['lam_butir_id'], ['prodi_id', 'lam_butir_id']],
        'prodi_instrumen'      => [['prodi_id'], ['aktif']],
        'lam_tabel_lkps'       => [['lam_instrumen_id'], ['urutan']],
        'lam_butir'            => [['lam_kriteria_id'], ['urutan']],
        'lam_kriteria'         => [['lam_instrumen_id'], ['urutan']],
        'lam_template_led'     => [['lam_instrumen_id'], ['lam_kriteria_id'], ['urutan']],
        'led_narasi'           => [['prodi_id']],
        'penelitian_anggota'   => [['penelitian_id'], ['dosen_id']],
        'pkm_anggota'          => [['pkm_id'], ['dosen_id']],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $colGroups) {
            // Skip kalau tabel belum ada
            if (!$this->db->tableExists($table)) {
                continue;
            }

            // Ambil daftar kolom yang benar-benar ada di tabel
            $existingCols = array_map('strtolower', $this->db->getFieldNames($table));

            foreach ($colGroups as $cols) {
                // Skip kalau salah satu kolom tidak ada
                foreach ($cols as $c) {
                    if (!in_array(strtolower($c), $existingCols, true)) {
                        continue 2;
                    }
                }

                $indexName = 'idx_' . $table . '_' . implode('_', $cols);
                $exists = $this->db->query(
                    "SELECT COUNT(*) AS cnt FROM information_schema.statistics
                     WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
                    [$table, $indexName]
                )->getRowArray()['cnt'] ?? 0;

                if (!$exists) {
                    $colList = implode(', ', $cols);
                    $this->db->query("CREATE INDEX {$indexName} ON {$table} ({$colList})");
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $colGroups) {
            if (!$this->db->tableExists($table)) {
                continue;
            }
            foreach ($colGroups as $cols) {
                $indexName = 'idx_' . $table . '_' . implode('_', $cols);
                try {
                    $this->db->query("DROP INDEX {$indexName} ON {$table}");
                } catch (\Throwable $e) {
                    // ignore — index might not exist
                }
            }
        }
    }
}
