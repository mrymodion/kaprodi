<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel pendukung: VMTS, evidence, audit trail, sync log,
 * sarpras, dana, SPMI/AMI, survei.
 */
class CreateSupportingTables extends Migration
{
    public function up(): void
    {
        // VMTS
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'   => ['type' => 'INT', 'unsigned' => true],
            'visi'       => ['type' => 'TEXT'],
            'misi'       => ['type' => 'TEXT'],
            'tujuan'     => ['type' => 'TEXT'],
            'strategi'   => ['type' => 'TEXT', 'null' => true],
            'no_sk'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tgl_sk'     => ['type' => 'DATE', 'null' => true],
            'berlaku_mulai' => ['type' => 'DATE', 'null' => true],
            'berlaku_sampai' => ['type' => 'DATE', 'null' => true],
            'aktif'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vmts');

        // Sarpras (Tabel 3.A.1, 4.A.1, 5.2)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'       => ['type' => 'INT', 'unsigned' => true],
            'kategori'       => ['type' => 'ENUM', 'constraint' => ['ruang_kelas','laboratorium','perpustakaan','peralatan','sistem_informasi','lainnya']],
            'nama'           => ['type' => 'VARCHAR', 'constraint' => 200],
            'jumlah'         => ['type' => 'INT', 'default' => 1],
            'luas_m2'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'kapasitas'      => ['type' => 'INT', 'null' => true],
            'kondisi'        => ['type' => 'ENUM', 'constraint' => ['baik','rusak_ringan','rusak_berat'], 'default' => 'baik'],
            'kepemilikan'    => ['type' => 'ENUM', 'constraint' => ['milik','sewa','pinjam'], 'default' => 'milik'],
            'untuk_penelitian'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'untuk_pkm'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'untuk_pendidikan'=> ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'keterangan'     => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sarpras');

        // Dana (Tabel 1.A.2, 1.A.3)
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'          => ['type' => 'INT', 'unsigned' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'unsigned' => true],
            'arah'              => ['type' => 'ENUM', 'constraint' => ['pemasukan','pengeluaran']],
            'sumber_atau_pos'   => ['type' => 'VARCHAR', 'constraint' => 200],
            'kategori'          => ['type' => 'ENUM', 'constraint' => ['mahasiswa','pt_sendiri','kementerian','hibah','kerjasama','lainnya','operasional','tridharma','investasi','publikasi','lainnya2'], 'null' => true],
            'jumlah'            => ['type' => 'BIGINT', 'default' => 0],
            'keterangan'        => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('keuangan');

        // SPMI / AMI siklus
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'   => ['type' => 'INT', 'unsigned' => true],
            'siklus'     => ['type' => 'VARCHAR', 'constraint' => 20],     // 2024-2025
            'tahap'      => ['type' => 'ENUM', 'constraint' => ['penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan']],
            'judul'      => ['type' => 'VARCHAR', 'constraint' => 250],
            'isi'        => ['type' => 'LONGTEXT', 'null' => true],
            'tindak_lanjut' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ppepp');

        // Survei (generik: kepuasan mhs, dosen, tendik, mitra)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'     => ['type' => 'INT', 'unsigned' => true],
            'judul'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'target'       => ['type' => 'ENUM', 'constraint' => ['mahasiswa','dosen','tendik','mitra','pengguna_lulusan']],
            'periode'      => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'token'        => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'mulai'        => ['type' => 'DATETIME', 'null' => true],
            'selesai'      => ['type' => 'DATETIME', 'null' => true],
            'pertanyaan_json' => ['type' => 'LONGTEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('survei');

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'survei_id'  => ['type' => 'INT', 'unsigned' => true],
            'responden'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'jawaban_json' => ['type' => 'LONGTEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('survei_id', 'survei', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('survei_response');

        // Evidence (repository dokumen, tag ke butir)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'     => ['type' => 'INT', 'unsigned' => true],
            'judul'        => ['type' => 'VARCHAR', 'constraint' => 250],
            'deskripsi'    => ['type' => 'TEXT', 'null' => true],
            'file_path'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'file_mime'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'file_size'    => ['type' => 'BIGINT', 'null' => true],
            'tag_butir_json' => ['type' => 'LONGTEXT', 'null' => true],
            'tag_tabel_lkps_json' => ['type' => 'LONGTEXT', 'null' => true],
            'uploaded_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('evidence');

        // Audit trail
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'prodi_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'aksi'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'tabel'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'record_id'  => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'before_json'=> ['type' => 'LONGTEXT', 'null' => true],
            'after_json' => ['type' => 'LONGTEXT', 'null' => true],
            'ip'         => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'ua'         => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('audit_trail');

        // Sync log (integrasi PDDikti/SISTER/Sinta)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'sumber'     => ['type' => 'VARCHAR', 'constraint' => 30],
            'aksi'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'     => ['type' => 'ENUM', 'constraint' => ['sukses','gagal','sebagian']],
            'detail'     => ['type' => 'LONGTEXT', 'null' => true],
            'jumlah_diproses' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('sync_log');
    }

    public function down(): void
    {
        foreach (['sync_log','audit_trail','evidence','survei_response','survei','ppepp','keuangan','sarpras','vmts'] as $t) {
            $this->forge->dropTable($t, true);
        }
    }
}
