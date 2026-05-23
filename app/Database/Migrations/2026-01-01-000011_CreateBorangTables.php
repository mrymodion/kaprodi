<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel pendukung untuk melengkapi LKPS yang belum ada modulnya:
 *  - tata_pamong       (1.A.1 Pimpinan & Tupoksi)
 *  - spmi_unit         (1.B  Unit SPMI & SDM)
 *  - fleksibilitas     (2.C  Fleksibilitas Pembelajaran / MBKM)
 *  - rekognisi_lulusan (2.D  Rekognisi & Apresiasi Kompetensi Lulusan)
 *  - pengembangan_dtpr (3.A.3 Pengembangan DTPR di Bidang Penelitian)
 *  - tata_kelola_si    (5.1  Sistem Tata Kelola / Sistem Informasi)
 */
class CreateBorangTables extends Migration
{
    public function up(): void
    {
        $prodiFk = fn() => ['prodi_id' => ['type' => 'INT', 'unsigned' => true]];

        // 1.A.1 — Tata Pamong & Pimpinan
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'            => ['type' => 'INT', 'unsigned' => true],
            'unit_kerja'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'nama_pejabat'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'jabatan'             => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'periode'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'pendidikan_terakhir' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'jabatan_fungsional'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tupoksi'             => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tata_pamong');

        // 1.B — Unit SPMI & SDM
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'              => ['type' => 'INT', 'unsigned' => true],
            'tingkat'               => ['type' => 'VARCHAR', 'constraint' => 20],
            'nama_unit'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'dokumen_spmi'          => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'jml_auditor'           => ['type' => 'INT', 'default' => 0],
            'auditor_certified'     => ['type' => 'INT', 'default' => 0],
            'auditor_non_certified' => ['type' => 'INT', 'default' => 0],
            'frekuensi_audit'       => ['type' => 'INT', 'default' => 0],
            'link_bukti'            => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('spmi_unit');

        // 2.C — Fleksibilitas Pembelajaran (MBKM)
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'bentuk'            => ['type' => 'VARCHAR', 'constraint' => 50],
            'jumlah_mahasiswa'  => ['type' => 'INT', 'default' => 0],
            'keterangan'        => ['type' => 'TEXT', 'null' => true],
            'link_bukti'        => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('fleksibilitas');

        // 2.D — Rekognisi & Apresiasi Kompetensi Lulusan
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'sumber'            => ['type' => 'VARCHAR', 'constraint' => 50],
            'jenis_pengakuan'   => ['type' => 'VARCHAR', 'constraint' => 250],
            'jumlah'            => ['type' => 'INT', 'default' => 0],
            'link_bukti'        => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('rekognisi_lulusan');

        // 3.A.3 — Pengembangan DTPR di Bidang Penelitian
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'           => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'nama_dtpr'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'jenis_pengembangan' => ['type' => 'VARCHAR', 'constraint' => 200],
            'jumlah'             => ['type' => 'INT', 'default' => 1],
            'link_bukti'         => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pengembangan_dtpr');

        // 5.1 — Sistem Tata Kelola / Sistem Informasi
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'       => ['type' => 'INT', 'unsigned' => true],
            'jenis'          => ['type' => 'VARCHAR', 'constraint' => 50],
            'nama_sistem'    => ['type' => 'VARCHAR', 'constraint' => 200],
            'akses'          => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'unit_pengelola' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'link_bukti'     => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tata_kelola_si');
    }

    public function down(): void
    {
        foreach (['tata_pamong', 'spmi_unit', 'fleksibilitas', 'rekognisi_lulusan', 'pengembangan_dtpr', 'tata_kelola_si'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
