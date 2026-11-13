<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDatabaseRelations extends Migration
{
    public function up()
    {
        $db = $this->db;

        // --- CLEANUP ORPHANED DATA SEBELUM MENAMBAH FOREIGN KEY ---
        // Hapus relasi publikasi dosen yang publikasi atau dosennya sudah tidak ada
        $db->query("DELETE FROM `publikasi_dosen` WHERE `publikasi_id` NOT IN (SELECT `id` FROM `publikasi`)");
        $db->query("DELETE FROM `publikasi_dosen` WHERE `dosen_id` NOT IN (SELECT `id` FROM `dosen`)");

        // Hapus ketercapaian cpl yang MK, CPL, atau Tahun Akademiknya sudah tidak ada
        $db->query("DELETE FROM `cpl_ketercapaian` WHERE `mata_kuliah_id` NOT IN (SELECT `id` FROM `mata_kuliah`)");
        $db->query("DELETE FROM `cpl_ketercapaian` WHERE `cpl_id` NOT IN (SELECT `id` FROM `cpl`)");
        $db->query("DELETE FROM `cpl_ketercapaian` WHERE `tahun_akademik_id` NOT IN (SELECT `id` FROM `tahun_akademik`)");
        // ----------------------------------------------------------

        // 1. Publikasi Dosen - Tambahkan Foreign Keys
        $db->query("ALTER TABLE `publikasi_dosen`
            ADD CONSTRAINT `fk_pub_dosen_pub` FOREIGN KEY (`publikasi_id`) REFERENCES `publikasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_pub_dosen_dos` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // 2. CPL Ketercapaian - Tambahkan Foreign Keys
        $db->query("ALTER TABLE `cpl_ketercapaian`
            ADD CONSTRAINT `fk_cpl_ket_mk` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_cpl_ket_cpl` FOREIGN KEY (`cpl_id`) REFERENCES `cpl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_cpl_ket_ta` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
        ");

        // 3. Perbaiki Ketidaksesuaian Tipe (int 10 vs int 11) dan Tambahkan FK untuk User
        $db->query("ALTER TABLE `dosen` MODIFY `user_id` int(11) unsigned DEFAULT NULL;");
        $db->query("ALTER TABLE `user_prodi` MODIFY `user_id` int(11) unsigned NOT NULL;");
        $db->query("ALTER TABLE `dosen` ADD CONSTRAINT `fk_dosen_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;");
        $db->query("ALTER TABLE `user_prodi` ADD CONSTRAINT `fk_user_prodi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

        // 4. Publikasi - Perpanjang kolom URL untuk mencegah Data Truncation
        $db->query("ALTER TABLE `publikasi` MODIFY `url` varchar(500) DEFAULT NULL;");

        // 5. Eviden PPEPP Bukti - Tambahkan Indeks untuk Optimasi Kinerja Dashboard Audit
        $db->query("ALTER TABLE `eviden_ppepp_bukti` ADD INDEX `idx_fase` (`fase`);");
    }

    public function down()
    {
        $db = $this->db;

        // Rollback Index dan Panjang URL
        $db->query("ALTER TABLE `eviden_ppepp_bukti` DROP INDEX `idx_fase`;");
        $db->query("ALTER TABLE `publikasi` MODIFY `url` varchar(250) DEFAULT NULL;");

        // Rollback Foreign Keys User & Kembalikan Tipe Data Asal
        $db->query("ALTER TABLE `user_prodi` DROP FOREIGN KEY `fk_user_prodi_user`;");
        $db->query("ALTER TABLE `dosen` DROP FOREIGN KEY `fk_dosen_user`;");
        $db->query("ALTER TABLE `user_prodi` MODIFY `user_id` int(10) unsigned NOT NULL;");
        $db->query("ALTER TABLE `dosen` MODIFY `user_id` int(10) unsigned DEFAULT NULL;");

        // Rollback Foreign Keys CPL Ketercapaian
        $db->query("ALTER TABLE `cpl_ketercapaian` DROP FOREIGN KEY `fk_cpl_ket_mk`, DROP FOREIGN KEY `fk_cpl_ket_cpl`, DROP FOREIGN KEY `fk_cpl_ket_ta`;");

        // Rollback Foreign Keys Publikasi Dosen
        $db->query("ALTER TABLE `publikasi_dosen` DROP FOREIGN KEY `fk_pub_dosen_pub`, DROP FOREIGN KEY `fk_pub_dosen_dos`;");
    }
}