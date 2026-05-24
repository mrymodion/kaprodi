<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Melengkapi modul SPMI agar selaras instrumen (C1 Budaya Mutu & C5 Akuntabilitas):
 *  - tinjauan_manajemen : log Rapat Tinjauan Manajemen (RTM) — penutup siklus PPEPP
 *  - standar_mutu        : register standar (SN-Dikti & melampaui) + target vs capaian
 *  - kolom traceability pada ami_temuan (standar terkait + verifikasi penutupan)
 */
class AddSpmiCompletenessTables extends Migration
{
    public function up(): void
    {
        if (!$this->db->tableExists('tinjauan_manajemen')) {
            $this->forge->addField([
                'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'         => ['type' => 'INT', 'unsigned' => true],
                'tanggal'          => ['type' => 'DATE', 'null' => true],
                'agenda'           => ['type' => 'VARCHAR', 'constraint' => 250],
                'keputusan'        => ['type' => 'TEXT', 'null' => true],
                'penanggung_jawab' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
                'tindak_lanjut'    => ['type' => 'TEXT', 'null' => true],
                'status'           => ['type' => 'ENUM', 'constraint' => ['terbuka', 'proses', 'selesai'], 'default' => 'terbuka'],
                'link_bukti'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'created_at'       => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('tinjauan_manajemen');
        }

        if (!$this->db->tableExists('standar_mutu')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'prodi_id'   => ['type' => 'INT', 'unsigned' => true],
                'kategori'   => ['type' => 'ENUM', 'constraint' => ['sndikti', 'melampaui'], 'default' => 'sndikti'],
                'kode'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'nama'       => ['type' => 'VARCHAR', 'constraint' => 250],
                'pernyataan' => ['type' => 'TEXT', 'null' => true],
                'indikator'  => ['type' => 'TEXT', 'null' => true],
                'target'     => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
                'capaian'    => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
                'status'     => ['type' => 'ENUM', 'constraint' => ['tercapai', 'sebagian', 'belum'], 'default' => 'belum'],
                'link_bukti' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('standar_mutu');
        }

        // Traceability temuan AMI → standar terkait + verifikasi penutupan.
        $cols = [];
        if (!$this->db->fieldExists('standar_mutu_id', 'ami_temuan')) {
            $cols['standar_mutu_id'] = ['type' => 'INT', 'unsigned' => true, 'null' => true, 'after' => 'kriteria'];
        }
        if (!$this->db->fieldExists('tanggal_verifikasi', 'ami_temuan')) {
            $cols['tanggal_verifikasi'] = ['type' => 'DATE', 'null' => true];
        }
        if (!$this->db->fieldExists('verifikator', 'ami_temuan')) {
            $cols['verifikator'] = ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true];
        }
        if ($cols) {
            $this->forge->addColumn('ami_temuan', $cols);
        }
    }

    public function down(): void
    {
        foreach (['standar_mutu_id', 'tanggal_verifikasi', 'verifikator'] as $c) {
            if ($this->db->fieldExists($c, 'ami_temuan')) {
                $this->forge->dropColumn('ami_temuan', $c);
            }
        }
        foreach (['tinjauan_manajemen', 'standar_mutu'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
