/*
SQLyog Ultimate
MySQL - 11.4.10-MariaDB-log : Database - kaprodi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `audit_trail` */

DROP TABLE IF EXISTS `audit_trail`;

CREATE TABLE `audit_trail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `prodi_id` int(10) unsigned DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `tabel` varchar(100) DEFAULT NULL,
  `record_id` varchar(64) DEFAULT NULL,
  `before_json` longtext DEFAULT NULL,
  `after_json` longtext DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `ua` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `audit_trail` */

/*Table structure for table `auth_groups_users` */

DROP TABLE IF EXISTS `auth_groups_users`;

CREATE TABLE `auth_groups_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_groups_users_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_groups_users` */

insert  into `auth_groups_users`(`id`,`user_id`,`group`,`created_at`) values 
(1,1,'superadmin','2026-05-22 01:13:45'),
(2,2,'kaprodi','2026-05-22 01:13:45'),
(3,3,'sekprodi','2026-05-22 01:13:46'),
(4,4,'gkm','2026-05-22 01:13:46'),
(5,5,'dosen','2026-05-22 01:13:47'),
(6,6,'tendik','2026-05-22 01:13:47'),
(7,7,'mahasiswa','2026-05-22 01:13:48'),
(8,8,'asesor','2026-05-22 01:13:48'),
(9,9,'auditor','2026-05-22 01:13:48');

/*Table structure for table `auth_identities` */

DROP TABLE IF EXISTS `auth_identities`;

CREATE TABLE `auth_identities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_secret` (`type`,`secret`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_identities` */

insert  into `auth_identities`(`id`,`user_id`,`type`,`name`,`secret`,`secret2`,`expires`,`extra`,`force_reset`,`last_used_at`,`created_at`,`updated_at`) values 
(1,1,'email_password',NULL,'superadmin@utd.ac.id','$2y$12$Sd0YcAUJVaQ.oaleI0ZpOelrfgz8sJxdf0HanQ177r2gcQzN3y0DC',NULL,NULL,0,'2026-05-22 06:24:24','2026-05-22 01:13:44','2026-05-22 06:24:24'),
(2,2,'email_password',NULL,'kaprodi@utd.ac.id','$2y$12$5Iga90UBcNeshBrGnpN66ORKFu/2MUpWYdSFUH0m7KPhXdweAYPdC',NULL,NULL,0,NULL,'2026-05-22 01:13:45','2026-05-22 01:13:45'),
(3,3,'email_password',NULL,'sekprodi@utd.ac.id','$2y$12$S0vMeOL2UWTg2Hztb4aLHukU4x.LgaJ7uwiMUFycsvZ.XGbdvzdA.',NULL,NULL,0,NULL,'2026-05-22 01:13:45','2026-05-22 01:13:46'),
(4,4,'email_password',NULL,'gkm@utd.ac.id','$2y$12$R/48TR/8WPYg5nH9w0uswe5mGwh.r2kHNSs/GB4/N0kfKG/OWgPXm',NULL,NULL,0,NULL,'2026-05-22 01:13:46','2026-05-22 01:13:46'),
(5,5,'email_password',NULL,'dosen@utd.ac.id','$2y$12$xPltwspDaB6kZTwL4LRBDOnROxi9Uw4rKRPf8hNTMfWnLGajlqAbW',NULL,NULL,0,NULL,'2026-05-22 01:13:46','2026-05-22 01:13:47'),
(6,6,'email_password',NULL,'tendik@utd.ac.id','$2y$12$AX6BpeQQIagQ9t0ZgM24Au9GmRogoqYWt.uZ/un6Yld5qhpBz7O5q',NULL,NULL,0,NULL,'2026-05-22 01:13:47','2026-05-22 01:13:47'),
(7,7,'email_password',NULL,'mahasiswa@utd.ac.id','$2y$12$l4pkVPDOrby/jeph8YrQhOBmSr1oqsvJCJ3AR9wEEOYIyyts7INo.',NULL,NULL,0,NULL,'2026-05-22 01:13:47','2026-05-22 01:13:48'),
(8,8,'email_password',NULL,'asesor@utd.ac.id','$2y$12$2vO9Pu.hzucd2OH5enbmK.rSbNsvVm9NqJtQ4aUASg9/0T1s6MWLK',NULL,NULL,0,NULL,'2026-05-22 01:13:48','2026-05-22 01:13:48'),
(9,9,'email_password',NULL,'auditor@utd.ac.id','$2y$12$W6zefi1Wj02OAhue7C8XCOcdGXodEBeJnx.LeF/2kI3RCmj7D3tBS',NULL,NULL,0,NULL,'2026-05-22 01:13:48','2026-05-22 01:13:48');

/*Table structure for table `auth_logins` */

DROP TABLE IF EXISTS `auth_logins`;

CREATE TABLE `auth_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_logins` */

insert  into `auth_logins`(`id`,`ip_address`,`user_agent`,`id_type`,`identifier`,`user_id`,`date`,`success`) values 
(1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',NULL,'2026-05-22 01:13:17',0),
(2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-22 01:14:01',1),
(3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-22 06:24:24',1);

/*Table structure for table `auth_permissions_users` */

DROP TABLE IF EXISTS `auth_permissions_users`;

CREATE TABLE `auth_permissions_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_permissions_users_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_permissions_users` */

/*Table structure for table `auth_remember_tokens` */

DROP TABLE IF EXISTS `auth_remember_tokens`;

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`selector`),
  KEY `auth_remember_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_remember_tokens` */

/*Table structure for table `auth_token_logins` */

DROP TABLE IF EXISTS `auth_token_logins`;

CREATE TABLE `auth_token_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_token_logins` */

/*Table structure for table `butir_skor` */

DROP TABLE IF EXISTS `butir_skor`;

CREATE TABLE `butir_skor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `lam_butir_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `skor_auto` decimal(3,2) DEFAULT NULL,
  `skor_manual` decimal(3,2) DEFAULT NULL,
  `skor_final` decimal(3,2) DEFAULT NULL,
  `sumber_data_json` longtext DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodi_id_lam_butir_id_tahun_akademik_id` (`prodi_id`,`lam_butir_id`,`tahun_akademik_id`),
  KEY `idx_butir_skor_prodi_id` (`prodi_id`),
  KEY `idx_butir_skor_lam_butir_id` (`lam_butir_id`),
  KEY `idx_butir_skor_prodi_id_lam_butir_id` (`prodi_id`,`lam_butir_id`),
  CONSTRAINT `butir_skor_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `butir_skor_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `butir_skor` */

insert  into `butir_skor`(`id`,`prodi_id`,`lam_butir_id`,`tahun_akademik_id`,`skor_auto`,`skor_manual`,`skor_final`,`sumber_data_json`,`catatan`,`link_bukti`,`updated_at`,`updated_by`) values 
(1,7,5,NULL,1.00,NULL,1.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(2,7,6,NULL,1.00,NULL,1.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(3,7,7,NULL,1.00,NULL,1.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(4,7,8,NULL,4.00,NULL,4.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(5,7,4,NULL,4.00,NULL,4.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(6,7,37,NULL,1.00,NULL,1.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL),
(7,7,52,NULL,1.00,NULL,1.00,NULL,NULL,NULL,'2026-05-22 09:37:30',NULL);

/*Table structure for table `cpl` */

DROP TABLE IF EXISTS `cpl`;

CREATE TABLE `cpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kurikulum_id` int(10) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `aspek` enum('sikap','pengetahuan','keterampilan_umum','keterampilan_khusus') NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kurikulum_id_kode` (`kurikulum_id`,`kode`),
  CONSTRAINT `cpl_kurikulum_id_foreign` FOREIGN KEY (`kurikulum_id`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cpl` */

/*Table structure for table `cpl_ketercapaian` */

DROP TABLE IF EXISTS `cpl_ketercapaian`;

CREATE TABLE `cpl_ketercapaian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` int(10) unsigned NOT NULL,
  `cpl_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned NOT NULL,
  `persen_tercapai` decimal(5,2) NOT NULL,
  `jumlah_mhs` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliah_id_cpl_id_tahun_akademik_id` (`mata_kuliah_id`,`cpl_id`,`tahun_akademik_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cpl_ketercapaian` */

/*Table structure for table `cpmk` */

DROP TABLE IF EXISTS `cpmk`;

CREATE TABLE `cpmk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` int(10) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cpmk_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  CONSTRAINT `cpmk_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cpmk` */

/*Table structure for table `dosen` */

DROP TABLE IF EXISTS `dosen`;

CREATE TABLE `dosen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `gelar_depan` varchar(50) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(25) DEFAULT NULL,
  `pendidikan_terakhir` enum('S1','S2','S3','Sp','Sp2') DEFAULT NULL,
  `bidang_keahlian` varchar(200) DEFAULT NULL,
  `linieritas` enum('linier','tidak_linier') NOT NULL DEFAULT 'linier',
  `jabatan_akademik` enum('Tenaga Pengajar','Asisten Ahli','Lektor','Lektor Kepala','Guru Besar') DEFAULT NULL,
  `no_sk_jad` varchar(100) DEFAULT NULL,
  `tgl_sk_jad` date DEFAULT NULL,
  `sertifikat_pendidik` tinyint(1) NOT NULL DEFAULT 0,
  `no_serdos` varchar(100) DEFAULT NULL,
  `status_kepegawaian` enum('tetap','tidak_tetap','dosen_industri') NOT NULL DEFAULT 'tetap',
  `sinta_id` varchar(30) DEFAULT NULL,
  `scopus_id` varchar(30) DEFAULT NULL,
  `google_scholar_id` varchar(50) DEFAULT NULL,
  `orcid` varchar(30) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nidn` (`nidn`),
  KEY `idx_dosen_prodi_id` (`prodi_id`),
  KEY `idx_dosen_aktif` (`aktif`),
  CONSTRAINT `dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `dosen` */

insert  into `dosen`(`id`,`prodi_id`,`nidn`,`nip`,`nama`,`gelar_depan`,`gelar_belakang`,`jenis_kelamin`,`tempat_lahir`,`tanggal_lahir`,`email`,`no_hp`,`pendidikan_terakhir`,`bidang_keahlian`,`linieritas`,`jabatan_akademik`,`no_sk_jad`,`tgl_sk_jad`,`sertifikat_pendidik`,`no_serdos`,`status_kepegawaian`,`sinta_id`,`scopus_id`,`google_scholar_id`,`orcid`,`aktif`,`created_at`,`updated_at`) values 
(1,7,'8849970018','','Mamok Andri Senubekti','Ir.','S.Kom., M.Kom.','L','Malang','1982-04-11','mamokandri@digitechuniversity.ac.id','081323945454','S2','Sistem Informasi, Data Science','linier','Asisten Ahli','7628/LL4/KP/2022','2022-11-28',0,'','tidak_tetap','6756574','60558151400','ZZcT_-oAAAAJ&hl','0000-0002-1893-0746',1,'2026-05-22 01:24:44','2026-05-22 01:51:45');

/*Table structure for table `dosen_ewmp` */

DROP TABLE IF EXISTS `dosen_ewmp`;

CREATE TABLE `dosen_ewmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned NOT NULL,
  `sks_pendidikan_ps` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pendidikan_ps_lain_pt` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pendidikan_pt_lain` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_penelitian` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pkm` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_tugas_tambahan` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_sks` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_id_tahun_akademik_id` (`dosen_id`,`tahun_akademik_id`),
  KEY `dosen_ewmp_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  CONSTRAINT `dosen_ewmp_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dosen_ewmp_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `dosen_ewmp` */

/*Table structure for table `evidence` */

DROP TABLE IF EXISTS `evidence`;

CREATE TABLE `evidence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_mime` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `tag_butir_json` longtext DEFAULT NULL,
  `tag_tabel_lkps_json` longtext DEFAULT NULL,
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evidence_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `evidence_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `evidence` */

/*Table structure for table `hki` */

DROP TABLE IF EXISTS `hki`;

CREATE TABLE `hki` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `kategori` enum('penelitian','pkm') NOT NULL,
  `jenis` enum('paten','paten_sederhana','hak_cipta','desain_industri','dtlst','indikasi_geografis','prl_varietas') NOT NULL,
  `judul` text NOT NULL,
  `no_hki` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `pemilik` text DEFAULT NULL,
  `status` enum('terdaftar','granted') NOT NULL DEFAULT 'granted',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hki_prodi_id` (`prodi_id`),
  KEY `idx_hki_tahun` (`tahun`),
  CONSTRAINT `hki_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `hki` */

/*Table structure for table `kepuasan_pengguna_lulusan` */

DROP TABLE IF EXISTS `kepuasan_pengguna_lulusan`;

CREATE TABLE `kepuasan_pengguna_lulusan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int(10) unsigned NOT NULL,
  `nama_atasan` varchar(150) DEFAULT NULL,
  `jabatan_atasan` varchar(150) DEFAULT NULL,
  `nama_perusahaan` varchar(200) DEFAULT NULL,
  `etika` tinyint(4) DEFAULT NULL,
  `keahlian` tinyint(4) DEFAULT NULL,
  `bahasa_asing` tinyint(4) DEFAULT NULL,
  `teknologi_info` tinyint(4) DEFAULT NULL,
  `komunikasi` tinyint(4) DEFAULT NULL,
  `kerjasama_tim` tinyint(4) DEFAULT NULL,
  `pengembangan_diri` tinyint(4) DEFAULT NULL,
  `saran` text DEFAULT NULL,
  `tgl_isi` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kepuasan_pengguna_lulusan_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `kepuasan_pengguna_lulusan_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kepuasan_pengguna_lulusan` */

/*Table structure for table `kerjasama` */

DROP TABLE IF EXISTS `kerjasama`;

CREATE TABLE `kerjasama` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `nama_mitra` varchar(250) NOT NULL,
  `bidang` enum('pendidikan','penelitian','pkm','tridharma') NOT NULL,
  `tingkat` enum('lokal','wilayah','nasional','internasional') NOT NULL,
  `jenis_dokumen` enum('mou','moa','ia','sk') NOT NULL,
  `no_dokumen` varchar(100) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_berakhir` date DEFAULT NULL,
  `manfaat` text DEFAULT NULL,
  `bukti_implementasi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_kerjasama_prodi_id` (`prodi_id`),
  KEY `idx_kerjasama_tingkat` (`tingkat`),
  CONSTRAINT `kerjasama_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kerjasama` */

insert  into `kerjasama`(`id`,`prodi_id`,`nama_mitra`,`bidang`,`tingkat`,`jenis_dokumen`,`no_dokumen`,`tgl_mulai`,`tgl_berakhir`,`manfaat`,`bukti_implementasi`,`created_at`,`updated_at`) values 
(1,7,'Kantor Kecamatan Naringgul','pendidikan','nasional','mou','012/UTD/MOU/12/II/2026','2026-02-05','2031-02-05','','','2026-05-22 02:02:54','2026-05-22 02:02:54');

/*Table structure for table `keuangan` */

DROP TABLE IF EXISTS `keuangan`;

CREATE TABLE `keuangan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned NOT NULL,
  `arah` enum('pemasukan','pengeluaran') NOT NULL,
  `sumber_atau_pos` varchar(200) NOT NULL,
  `kategori` enum('mahasiswa','pt_sendiri','kementerian','hibah','kerjasama','lainnya','operasional','tridharma','investasi','publikasi','lainnya2') DEFAULT NULL,
  `jumlah` bigint(20) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keuangan_prodi_id_foreign` (`prodi_id`),
  KEY `keuangan_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  CONSTRAINT `keuangan_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keuangan_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `keuangan` */

/*Table structure for table `kurikulum` */

DROP TABLE IF EXISTS `kurikulum`;

CREATE TABLE `kurikulum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tahun_berlaku` year(4) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kurikulum_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `kurikulum_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kurikulum` */

/*Table structure for table `lam_butir` */

DROP TABLE IF EXISTS `lam_butir`;

CREATE TABLE `lam_butir` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_kriteria_id` int(10) unsigned NOT NULL,
  `kode` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis_ipo` enum('input','proses','output') NOT NULL,
  `bobot` decimal(6,2) NOT NULL DEFAULT 0.00,
  `tipe_data` enum('kuantitatif','kualitatif','campuran') NOT NULL DEFAULT 'kualitatif',
  `formula` text DEFAULT NULL,
  `tabel_lkps_ref` varchar(50) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lam_kriteria_id_kode` (`lam_kriteria_id`,`kode`),
  KEY `idx_lam_butir_lam_kriteria_id` (`lam_kriteria_id`),
  KEY `idx_lam_butir_urutan` (`urutan`),
  CONSTRAINT `lam_butir_lam_kriteria_id_foreign` FOREIGN KEY (`lam_kriteria_id`) REFERENCES `lam_kriteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_butir` */

insert  into `lam_butir`(`id`,`lam_kriteria_id`,`kode`,`judul`,`deskripsi`,`jenis_ipo`,`bobot`,`tipe_data`,`formula`,`tabel_lkps_ref`,`urutan`) values 
(1,1,'A.1','Butir A.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir A.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,1),
(2,2,'B.1','Butir B.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir B.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,1),
(3,3,'C1.1','Butir C1.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,1),
(4,3,'C1.2','Butir C1.2 [Pelaksanaan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,2),
(5,3,'C1.3','Butir C1.3 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,3),
(6,3,'C1.4','Butir C1.4 [Pengendalian] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,4),
(7,3,'C1.5','Butir C1.5 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,5),
(8,3,'C1.6','Butir C1.6 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.6 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,6),
(9,3,'C1.7','Butir C1.7 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.7 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,7),
(10,3,'C1.8','Butir C1.8 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.8 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,8),
(11,3,'C1.9','Butir C1.9 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.9 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,9),
(12,3,'C1.10','Butir C1.10 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C1.10 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,10),
(13,4,'C2.1','Butir C2.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',6.00,'kualitatif',NULL,NULL,1),
(14,4,'C2.2','Butir C2.2 [Pelaksanaan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: input.','input',6.00,'kualitatif',NULL,NULL,2),
(15,4,'C2.3','Butir C2.3 [Evaluasi] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: input.','input',6.00,'kualitatif',NULL,NULL,3),
(16,4,'C2.4','Butir C2.4 [Pengendalian] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: input.','input',6.00,'kualitatif',NULL,NULL,4),
(17,4,'C2.5','Butir C2.5 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,5),
(18,4,'C2.6','Butir C2.6 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.6 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,6),
(19,4,'C2.7','Butir C2.7 [Pelaksanaan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.7 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,7),
(20,4,'C2.8','Butir C2.8 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.8 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,8),
(21,4,'C2.9','Butir C2.9 [Pengendalian] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.9 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,9),
(22,4,'C2.10','Butir C2.10 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.10 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,10),
(23,4,'C2.11','Butir C2.11 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.11 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',6.00,'kualitatif',NULL,NULL,11),
(24,4,'C2.12','Butir C2.12 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.12 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,12),
(25,4,'C2.13','Butir C2.13 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.13 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,13),
(26,4,'C2.14','Butir C2.14 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.14 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,14),
(27,4,'C2.15','Butir C2.15 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.15 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,15),
(28,4,'C2.16','Butir C2.16 [Penetapan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.16 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,16),
(29,4,'C2.17','Butir C2.17 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.17 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,17),
(30,4,'C2.18','Butir C2.18 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.18 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,18),
(31,4,'C2.19','Butir C2.19 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.19 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,19),
(32,4,'C2.20','Butir C2.20 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C2.20 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',6.00,'kualitatif',NULL,NULL,20),
(33,5,'C3.1','Butir C3.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',2.13,'kualitatif',NULL,NULL,1),
(34,5,'C3.2','Butir C3.2 [Pelaksanaan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: input.','input',2.13,'kualitatif',NULL,NULL,2),
(35,5,'C3.3','Butir C3.3 [Evaluasi] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: input.','input',2.13,'kualitatif',NULL,NULL,3),
(36,5,'C3.4','Butir C3.4 [Pengendalian] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: proses.','proses',2.13,'kualitatif',NULL,NULL,4),
(37,5,'C3.5','Butir C3.5 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',2.13,'kualitatif',NULL,NULL,5),
(38,5,'C3.6','Butir C3.6 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.6 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',2.13,'kualitatif',NULL,NULL,6),
(39,5,'C3.7','Butir C3.7 [Pelaksanaan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.7 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: proses.','proses',2.13,'kualitatif',NULL,NULL,7),
(40,5,'C3.8','Butir C3.8 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.8 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',2.13,'kualitatif',NULL,NULL,8),
(41,5,'C3.9','Butir C3.9 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.9 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,9),
(42,5,'C3.10','Butir C3.10 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.10 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,10),
(43,5,'C3.11','Butir C3.11 [Penetapan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.11 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,11),
(44,5,'C3.12','Butir C3.12 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.12 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,12),
(45,5,'C3.13','Butir C3.13 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.13 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,13),
(46,5,'C3.14','Butir C3.14 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.14 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,14),
(47,5,'C3.15','Butir C3.15 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C3.15 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',2.13,'kualitatif',NULL,NULL,15),
(48,6,'C4.1','Butir C4.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',6.67,'kualitatif',NULL,NULL,1),
(49,6,'C4.2','Butir C4.2 [Pelaksanaan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: input.','input',6.67,'kualitatif',NULL,NULL,2),
(50,6,'C4.3','Butir C4.3 [Evaluasi] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: input.','input',6.67,'kualitatif',NULL,NULL,3),
(51,6,'C4.4','Butir C4.4 [Pengendalian] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: proses.','proses',6.67,'kualitatif',NULL,NULL,4),
(52,6,'C4.5','Butir C4.5 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',6.67,'kualitatif',NULL,NULL,5),
(53,6,'C4.6','Butir C4.6 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.6 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',6.67,'kualitatif',NULL,NULL,6),
(54,6,'C4.7','Butir C4.7 [Pelaksanaan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.7 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: proses.','proses',6.67,'kualitatif',NULL,NULL,7),
(55,6,'C4.8','Butir C4.8 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.8 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',6.67,'kualitatif',NULL,NULL,8),
(56,6,'C4.9','Butir C4.9 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.9 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,9),
(57,6,'C4.10','Butir C4.10 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.10 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,10),
(58,6,'C4.11','Butir C4.11 [Penetapan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.11 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,11),
(59,6,'C4.12','Butir C4.12 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.12 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,12),
(60,6,'C4.13','Butir C4.13 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.13 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,13),
(61,6,'C4.14','Butir C4.14 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.14 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,14),
(62,6,'C4.15','Butir C4.15 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C4.15 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',6.67,'kualitatif',NULL,NULL,15),
(63,7,'C5.1','Butir C5.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,1),
(64,7,'C5.2','Butir C5.2 [Pelaksanaan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: input.','input',4.00,'kualitatif',NULL,NULL,2),
(65,7,'C5.3','Butir C5.3 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,3),
(66,7,'C5.4','Butir C5.4 [Pengendalian] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,4),
(67,7,'C5.5','Butir C5.5 [Peningkatan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,5),
(68,7,'C5.6','Butir C5.6 [Penetapan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.6 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: proses.','proses',4.00,'kualitatif',NULL,NULL,6),
(69,7,'C5.7','Butir C5.7 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.7 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,7),
(70,7,'C5.8','Butir C5.8 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.8 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,8),
(71,7,'C5.9','Butir C5.9 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.9 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,9),
(72,7,'C5.10','Butir C5.10 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C5.10 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,10),
(73,8,'C6.1','Butir C6.1 [Penetapan] - Input','[ISI SESUAI INSTRUMEN] Deskripsi butir C6.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: input.','input',8.00,'kualitatif',NULL,NULL,1),
(74,8,'C6.2','Butir C6.2 [Pelaksanaan] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C6.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: proses.','proses',8.00,'kualitatif',NULL,NULL,2),
(75,8,'C6.3','Butir C6.3 [Evaluasi] - Proses','[ISI SESUAI INSTRUMEN] Deskripsi butir C6.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: proses.','proses',8.00,'kualitatif',NULL,NULL,3),
(76,8,'C6.4','Butir C6.4 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C6.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',8.00,'kualitatif',NULL,NULL,4),
(77,8,'C6.5','Butir C6.5 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir C6.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',8.00,'kualitatif',NULL,NULL,5),
(78,9,'D.1','Butir D.1 [Penetapan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.1 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Penetapan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,1),
(79,9,'D.2','Butir D.2 [Pelaksanaan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.2 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pelaksanaan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,2),
(80,9,'D.3','Butir D.3 [Evaluasi] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.3 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Evaluasi, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,3),
(81,9,'D.4','Butir D.4 [Pengendalian] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.4 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Pengendalian, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,4),
(82,9,'D.5','Butir D.5 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,5),
(83,10,'A','A - Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS','Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(84,11,'B','B - Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria','Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(85,12,'1.1.A','1.1.A [Penetapan] - Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP','Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',3.00,'kualitatif',NULL,NULL,1),
(86,12,'1.1.B','1.1.B [Penetapan] - Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten','Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',2.50,'kualitatif',NULL,NULL,2),
(87,12,'1.2.A','1.2.A [Pelaksanaan] - Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP','Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',5.00,'kualitatif',NULL,NULL,3),
(88,12,'1.2.B','1.2.B [Pelaksanaan] - Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten','Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',5.00,'kualitatif',NULL,NULL,4),
(89,12,'1.3.A','1.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian standar sistem tata kelola internal','Efektifitas evaluasi ketercapaian standar sistem tata kelola internal (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,5),
(90,12,'1.3.B','1.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana','Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,6),
(91,12,'1.4.A','1.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal','Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',2.00,'kualitatif',NULL,NULL,7),
(92,12,'1.4.B','1.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana','Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',2.00,'kualitatif',NULL,NULL,8),
(93,12,'1.5.A','1.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal','Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(94,12,'1.5.B','1.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana','Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,10),
(95,13,'2.1.A','2.1.A [Penetapan] - Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK)','Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',5.00,'kualitatif',NULL,NULL,1),
(96,13,'2.1.B','2.1.B [Penetapan] - Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6)','Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,2),
(97,13,'2.1.C','2.1.C [Penetapan] - Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL)','Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,3),
(98,13,'2.1.D','2.1.D [Penetapan] - Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan)','Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,4),
(99,13,'2.2.A','2.2.A [Pelaksanaan] - Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru','Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',9.00,'kualitatif',NULL,NULL,5),
(100,13,'2.2.B','2.2.B [Pelaksanaan] - Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE','Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',7.00,'kualitatif',NULL,NULL,6),
(101,13,'2.2.C','2.2.C [Pelaksanaan] - Efektifitas pelaksanaan fleksibilitas proses pembelajaran','Efektifitas pelaksanaan fleksibilitas proses pembelajaran (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',7.00,'kualitatif',NULL,NULL,7),
(102,13,'2.2.D','2.2.D [Pelaksanaan] - Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja)','Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja) (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',30.00,'kualitatif',NULL,NULL,8),
(103,13,'2.3.A','2.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru','Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(104,13,'2.3.B','2.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE','Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,10),
(105,13,'2.3.C','2.3.C [Evaluasi] - Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran','Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,11),
(106,13,'2.3.D','2.3.D [Evaluasi] - Efektifitas evaluasi ketercapaian kompetensi lulusan','Efektifitas evaluasi ketercapaian kompetensi lulusan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,12),
(107,13,'2.4.A','2.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru','Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',4.00,'kualitatif',NULL,NULL,13),
(108,13,'2.4.B','2.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE','Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',4.00,'kualitatif',NULL,NULL,14),
(109,13,'2.4.C','2.4.C [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar','Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,15),
(110,13,'2.4.D','2.4.D [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan','Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,16),
(111,13,'2.5.A','2.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru','Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,17),
(112,13,'2.5.B','2.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE','Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,18),
(113,13,'2.5.C','2.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar','Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,19),
(114,13,'2.5.D','2.5.D [Peningkatan] - Efektifitas peningkatan/optimalisasi kompetensi lulusan','Efektifitas peningkatan/optimalisasi kompetensi lulusan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,20),
(115,14,'3.1.A','3.1.A [Penetapan] - Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian','Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(116,14,'3.1.B','3.1.B [Penetapan] - Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan)','Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',4.00,'kualitatif',NULL,NULL,2),
(117,14,'3.1.C','3.1.C [Penetapan] - Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian','Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',3.00,'kualitatif',NULL,NULL,3),
(118,14,'3.2.A','3.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan','Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',8.00,'kualitatif',NULL,NULL,4),
(119,14,'3.2.B','3.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa','Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',6.00,'kualitatif',NULL,NULL,5),
(120,14,'3.2.C','3.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan','Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',18.00,'kualitatif',NULL,NULL,6),
(121,14,'3.3.A','3.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan','Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,7),
(122,14,'3.3.B','3.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa','Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,8),
(123,14,'3.3.C','3.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI','Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,9),
(124,14,'3.4.A','3.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan','Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,10),
(125,14,'3.4.B','3.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian','Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,11),
(126,14,'3.4.C','3.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI','Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,12),
(127,14,'3.5.A','3.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan','Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,13),
(128,14,'3.5.B','3.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian','Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,14),
(129,14,'3.5.C','3.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI','Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,15),
(130,15,'4.1.A','4.1.A [Penetapan] - Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran)','Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',3.00,'kualitatif',NULL,NULL,1),
(131,15,'4.1.B','4.1.B [Penetapan] - Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi)','Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',2.50,'kualitatif',NULL,NULL,2),
(132,15,'4.1.C','4.1.C [Penetapan] - Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM','Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',2.00,'kualitatif',NULL,NULL,3),
(133,15,'4.2.A','4.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM','Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',7.00,'kualitatif',NULL,NULL,4),
(134,15,'4.2.B','4.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa','Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',6.00,'kualitatif',NULL,NULL,5),
(135,15,'4.2.C','4.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI','Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',15.00,'kualitatif',NULL,NULL,6),
(136,15,'4.3.A','4.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM','Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,7),
(137,15,'4.3.B','4.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa','Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,8),
(138,15,'4.3.C','4.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI','Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,9),
(139,15,'4.4.A','4.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan','Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,10),
(140,15,'4.4.B','4.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM','Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',2.00,'kualitatif',NULL,NULL,11),
(141,15,'4.4.C','4.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI','Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',2.00,'kualitatif',NULL,NULL,12),
(142,15,'4.5.A','4.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan','Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,13),
(143,15,'4.5.B','4.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa','Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',2.00,'kualitatif',NULL,NULL,14),
(144,15,'4.5.C','4.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi','Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',2.50,'kualitatif',NULL,NULL,15),
(145,16,'5.1.A','5.1.A [Penetapan] - Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional)','Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',3.00,'kualitatif',NULL,NULL,1),
(146,16,'5.1.B','5.1.B [Penetapan] - Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM','Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',2.00,'kualitatif',NULL,NULL,2),
(147,16,'5.2.A','5.2.A [Pelaksanaan] - Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM)','Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM) (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',5.00,'kualitatif',NULL,NULL,3),
(148,16,'5.2.B','5.2.B [Pelaksanaan] - Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong','Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',4.00,'kualitatif',NULL,NULL,4),
(149,16,'5.3.A','5.3.A [Evaluasi] - Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel','Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',6.00,'kualitatif',NULL,NULL,5),
(150,16,'5.3.B','5.3.B [Evaluasi] - Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong','Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,6),
(151,16,'5.4.A','5.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel','Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',3.00,'kualitatif',NULL,NULL,7),
(152,16,'5.4.B','5.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi','Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',2.00,'kualitatif',NULL,NULL,8),
(153,16,'5.5.A','5.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel','Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(154,16,'5.5.B','5.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi','Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,10),
(155,17,'6.1','6.1 [Penetapan] - Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi','Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','input',5.00,'kualitatif',NULL,NULL,1),
(156,17,'6.2','6.2 [Pelaksanaan] - Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan','Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',8.00,'kualitatif',NULL,NULL,2),
(157,17,'6.3','6.3 [Evaluasi] - Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan','Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',13.00,'kualitatif',NULL,NULL,3),
(158,17,'6.4','6.4 [Pengendalian] - Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan','Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','proses',6.00,'kualitatif',NULL,NULL,4),
(159,17,'6.5','6.5 [Peningkatan] - Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan','Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',10.00,'kualitatif',NULL,NULL,5),
(160,18,'D.1','D.1 - Mata kuliah inti/khas program studi','Mata kuliah inti/khas program studi — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',4.00,'kualitatif',NULL,NULL,1),
(161,18,'D.2','D.2 - Mata kuliah domain spesifik dan lingkungan prodi infokom','Mata kuliah domain spesifik dan lingkungan prodi infokom — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,2),
(162,18,'D.3','D.3 - Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan','Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',3.00,'kualitatif',NULL,NULL,3),
(163,18,'D.4','D.4 - Proyek Utama (Capstone Project) yang relevan','Proyek Utama (Capstone Project) yang relevan — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,4),
(164,18,'D.5','D.5 - Pengembangan bidang Infokom yang digunakan di masyarakat','Pengembangan bidang Infokom yang digunakan di masyarakat — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,5);

/*Table structure for table `lam_butir_rubrik` */

DROP TABLE IF EXISTS `lam_butir_rubrik`;

CREATE TABLE `lam_butir_rubrik` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_butir_id` int(10) unsigned NOT NULL,
  `skor` tinyint(1) NOT NULL,
  `label` varchar(30) NOT NULL,
  `deskriptor` text NOT NULL,
  `syarat_json` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lam_butir_id_skor` (`lam_butir_id`,`skor`),
  CONSTRAINT `lam_butir_rubrik_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=657 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_butir_rubrik` */

insert  into `lam_butir_rubrik`(`id`,`lam_butir_id`,`skor`,`label`,`deskriptor`,`syarat_json`) values 
(1,1,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(2,1,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(3,1,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(4,1,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(5,2,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(6,2,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(7,2,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(8,2,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(9,3,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(10,3,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(11,3,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(12,3,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(13,4,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(14,4,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(15,4,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(16,4,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(17,5,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(18,5,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(19,5,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(20,5,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(21,6,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(22,6,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(23,6,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(24,6,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(25,7,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(26,7,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(27,7,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(28,7,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(29,8,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(30,8,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(31,8,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(32,8,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(33,9,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(34,9,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(35,9,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(36,9,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(37,10,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(38,10,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(39,10,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(40,10,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(41,11,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(42,11,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(43,11,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(44,11,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(45,12,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(46,12,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(47,12,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(48,12,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(49,13,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(50,13,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(51,13,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(52,13,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(53,14,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(54,14,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(55,14,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(56,14,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(57,15,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(58,15,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(59,15,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(60,15,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(61,16,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(62,16,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(63,16,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(64,16,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(65,17,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(66,17,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(67,17,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(68,17,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(69,18,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(70,18,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(71,18,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(72,18,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(73,19,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(74,19,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(75,19,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(76,19,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(77,20,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(78,20,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(79,20,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(80,20,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(81,21,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(82,21,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(83,21,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(84,21,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(85,22,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(86,22,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(87,22,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(88,22,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(89,23,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(90,23,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(91,23,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(92,23,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(93,24,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(94,24,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(95,24,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(96,24,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(97,25,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(98,25,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(99,25,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(100,25,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(101,26,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(102,26,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(103,26,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(104,26,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(105,27,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(106,27,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(107,27,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(108,27,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(109,28,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(110,28,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(111,28,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(112,28,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(113,29,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(114,29,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(115,29,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(116,29,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(117,30,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(118,30,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(119,30,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(120,30,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(121,31,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(122,31,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(123,31,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(124,31,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(125,32,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(126,32,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(127,32,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(128,32,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(129,33,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(130,33,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(131,33,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(132,33,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(133,34,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(134,34,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(135,34,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(136,34,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(137,35,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(138,35,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(139,35,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(140,35,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(141,36,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(142,36,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(143,36,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(144,36,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(145,37,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(146,37,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(147,37,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(148,37,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(149,38,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(150,38,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(151,38,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(152,38,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(153,39,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(154,39,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(155,39,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(156,39,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(157,40,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(158,40,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(159,40,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(160,40,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(161,41,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(162,41,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(163,41,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(164,41,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(165,42,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(166,42,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(167,42,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(168,42,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(169,43,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(170,43,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(171,43,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(172,43,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(173,44,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(174,44,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(175,44,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(176,44,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(177,45,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(178,45,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(179,45,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(180,45,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(181,46,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(182,46,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(183,46,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(184,46,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(185,47,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(186,47,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(187,47,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(188,47,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(189,48,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(190,48,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(191,48,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(192,48,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(193,49,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(194,49,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(195,49,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(196,49,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(197,50,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(198,50,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(199,50,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(200,50,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(201,51,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(202,51,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(203,51,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(204,51,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(205,52,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(206,52,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(207,52,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(208,52,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(209,53,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(210,53,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(211,53,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(212,53,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(213,54,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(214,54,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(215,54,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(216,54,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(217,55,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(218,55,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(219,55,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(220,55,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(221,56,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(222,56,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(223,56,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(224,56,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(225,57,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(226,57,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(227,57,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(228,57,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(229,58,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(230,58,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(231,58,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(232,58,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(233,59,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(234,59,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(235,59,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(236,59,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(237,60,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(238,60,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(239,60,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(240,60,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(241,61,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(242,61,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(243,61,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(244,61,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(245,62,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(246,62,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(247,62,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(248,62,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(249,63,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(250,63,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(251,63,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(252,63,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(253,64,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(254,64,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(255,64,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(256,64,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(257,65,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(258,65,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(259,65,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(260,65,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(261,66,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(262,66,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(263,66,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(264,66,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(265,67,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(266,67,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(267,67,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(268,67,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(269,68,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(270,68,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(271,68,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(272,68,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(273,69,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(274,69,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(275,69,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(276,69,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(277,70,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(278,70,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(279,70,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(280,70,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(281,71,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(282,71,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(283,71,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(284,71,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(285,72,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(286,72,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(287,72,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(288,72,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(289,73,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(290,73,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(291,73,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(292,73,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(293,74,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(294,74,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(295,74,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(296,74,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(297,75,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(298,75,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(299,75,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(300,75,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(301,76,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(302,76,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(303,76,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(304,76,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(305,77,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(306,77,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(307,77,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(308,77,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(309,78,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(310,78,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(311,78,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(312,78,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(313,79,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(314,79,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(315,79,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(316,79,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(317,80,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(318,80,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(319,80,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(320,80,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(321,81,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(322,81,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(323,81,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(324,81,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(325,82,4,'Sangat Baik','Sangat baik: Memenuhi seluruh aspek dengan bukti lengkap, sistematis, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(326,82,3,'Baik','Baik: Memenuhi seluruh aspek dengan bukti lengkap dan sistematis.',NULL),
(327,82,2,'Cukup','Cukup: Memenuhi sebagian besar aspek dengan bukti yang memadai.',NULL),
(328,82,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL),
(329,83,4,'Sangat Baik','Sangat baik: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(330,83,3,'Baik','Baik: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(331,83,2,'Cukup','Cukup: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(332,83,1,'Kurang','Kurang: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(333,84,4,'Sangat Baik','Sangat baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(334,84,3,'Baik','Baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(335,84,2,'Cukup','Cukup: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(336,84,1,'Kurang','Kurang: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(337,85,4,'Sangat Baik','Sangat baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(338,85,3,'Baik','Baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(339,85,2,'Cukup','Cukup: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(340,85,1,'Kurang','Kurang: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(341,86,4,'Sangat Baik','Sangat baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(342,86,3,'Baik','Baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(343,86,2,'Cukup','Cukup: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(344,86,1,'Kurang','Kurang: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(345,87,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(346,87,3,'Baik','Baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(347,87,2,'Cukup','Cukup: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(348,87,1,'Kurang','Kurang: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(349,88,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(350,88,3,'Baik','Baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(351,88,2,'Cukup','Cukup: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(352,88,1,'Kurang','Kurang: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(353,89,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(354,89,3,'Baik','Baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(355,89,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(356,89,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(357,90,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(358,90,3,'Baik','Baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(359,90,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(360,90,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(361,91,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(362,91,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(363,91,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(364,91,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(365,92,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(366,92,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(367,92,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(368,92,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(369,93,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(370,93,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(371,93,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(372,93,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(373,94,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(374,94,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(375,94,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(376,94,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(377,95,4,'Sangat Baik','Sangat baik: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(378,95,3,'Baik','Baik: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(379,95,2,'Cukup','Cukup: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(380,95,1,'Kurang','Kurang: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(381,96,4,'Sangat Baik','Sangat baik: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(382,96,3,'Baik','Baik: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(383,96,2,'Cukup','Cukup: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(384,96,1,'Kurang','Kurang: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(385,97,4,'Sangat Baik','Sangat baik: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(386,97,3,'Baik','Baik: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(387,97,2,'Cukup','Cukup: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(388,97,1,'Kurang','Kurang: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(389,98,4,'Sangat Baik','Sangat baik: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(390,98,3,'Baik','Baik: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(391,98,2,'Cukup','Cukup: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(392,98,1,'Kurang','Kurang: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(393,99,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(394,99,3,'Baik','Baik: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(395,99,2,'Cukup','Cukup: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(396,99,1,'Kurang','Kurang: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(397,100,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(398,100,3,'Baik','Baik: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(399,100,2,'Cukup','Cukup: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(400,100,1,'Kurang','Kurang: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(401,101,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(402,101,3,'Baik','Baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(403,101,2,'Cukup','Cukup: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(404,101,1,'Kurang','Kurang: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(405,102,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(406,102,3,'Baik','Baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(407,102,2,'Cukup','Cukup: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(408,102,1,'Kurang','Kurang: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(409,103,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(410,103,3,'Baik','Baik: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(411,103,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(412,103,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(413,104,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(414,104,3,'Baik','Baik: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(415,104,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(416,104,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(417,105,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(418,105,3,'Baik','Baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(419,105,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(420,105,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(421,106,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(422,106,3,'Baik','Baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(423,106,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(424,106,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(425,107,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(426,107,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(427,107,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(428,107,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(429,108,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(430,108,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(431,108,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(432,108,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(433,109,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(434,109,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(435,109,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(436,109,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(437,110,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(438,110,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(439,110,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(440,110,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(441,111,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(442,111,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(443,111,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(444,111,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(445,112,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(446,112,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(447,112,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(448,112,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(449,113,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(450,113,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(451,113,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(452,113,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(453,114,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(454,114,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(455,114,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(456,114,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(457,115,4,'Sangat Baik','Sangat baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(458,115,3,'Baik','Baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(459,115,2,'Cukup','Cukup: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(460,115,1,'Kurang','Kurang: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(461,116,4,'Sangat Baik','Sangat baik: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(462,116,3,'Baik','Baik: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(463,116,2,'Cukup','Cukup: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(464,116,1,'Kurang','Kurang: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(465,117,4,'Sangat Baik','Sangat baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(466,117,3,'Baik','Baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(467,117,2,'Cukup','Cukup: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(468,117,1,'Kurang','Kurang: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(469,118,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(470,118,3,'Baik','Baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(471,118,2,'Cukup','Cukup: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(472,118,1,'Kurang','Kurang: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(473,119,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(474,119,3,'Baik','Baik: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(475,119,2,'Cukup','Cukup: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(476,119,1,'Kurang','Kurang: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(477,120,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(478,120,3,'Baik','Baik: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(479,120,2,'Cukup','Cukup: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(480,120,1,'Kurang','Kurang: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(481,121,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(482,121,3,'Baik','Baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(483,121,2,'Cukup','Cukup: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(484,121,1,'Kurang','Kurang: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(485,122,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(486,122,3,'Baik','Baik: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(487,122,2,'Cukup','Cukup: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(488,122,1,'Kurang','Kurang: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(489,123,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(490,123,3,'Baik','Baik: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(491,123,2,'Cukup','Cukup: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(492,123,1,'Kurang','Kurang: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(493,124,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(494,124,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(495,124,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(496,124,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(497,125,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(498,125,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(499,125,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(500,125,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(501,126,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(502,126,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(503,126,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(504,126,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(505,127,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(506,127,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(507,127,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(508,127,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(509,128,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(510,128,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(511,128,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(512,128,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(513,129,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(514,129,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(515,129,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(516,129,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(517,130,4,'Sangat Baik','Sangat baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(518,130,3,'Baik','Baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(519,130,2,'Cukup','Cukup: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(520,130,1,'Kurang','Kurang: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(521,131,4,'Sangat Baik','Sangat baik: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(522,131,3,'Baik','Baik: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(523,131,2,'Cukup','Cukup: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(524,131,1,'Kurang','Kurang: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(525,132,4,'Sangat Baik','Sangat baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(526,132,3,'Baik','Baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(527,132,2,'Cukup','Cukup: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(528,132,1,'Kurang','Kurang: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(529,133,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(530,133,3,'Baik','Baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(531,133,2,'Cukup','Cukup: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(532,133,1,'Kurang','Kurang: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(533,134,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(534,134,3,'Baik','Baik: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(535,134,2,'Cukup','Cukup: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(536,134,1,'Kurang','Kurang: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(537,135,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(538,135,3,'Baik','Baik: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(539,135,2,'Cukup','Cukup: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(540,135,1,'Kurang','Kurang: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(541,136,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(542,136,3,'Baik','Baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(543,136,2,'Cukup','Cukup: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(544,136,1,'Kurang','Kurang: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(545,137,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(546,137,3,'Baik','Baik: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(547,137,2,'Cukup','Cukup: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(548,137,1,'Kurang','Kurang: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(549,138,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(550,138,3,'Baik','Baik: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(551,138,2,'Cukup','Cukup: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(552,138,1,'Kurang','Kurang: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(553,139,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(554,139,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(555,139,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(556,139,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(557,140,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(558,140,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(559,140,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(560,140,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(561,141,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(562,141,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(563,141,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(564,141,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(565,142,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(566,142,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(567,142,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(568,142,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(569,143,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(570,143,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(571,143,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(572,143,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(573,144,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(574,144,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(575,144,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(576,144,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(577,145,4,'Sangat Baik','Sangat baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(578,145,3,'Baik','Baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(579,145,2,'Cukup','Cukup: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(580,145,1,'Kurang','Kurang: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(581,146,4,'Sangat Baik','Sangat baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(582,146,3,'Baik','Baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(583,146,2,'Cukup','Cukup: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(584,146,1,'Kurang','Kurang: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(585,147,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(586,147,3,'Baik','Baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(587,147,2,'Cukup','Cukup: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(588,147,1,'Kurang','Kurang: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(589,148,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(590,148,3,'Baik','Baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(591,148,2,'Cukup','Cukup: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(592,148,1,'Kurang','Kurang: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(593,149,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(594,149,3,'Baik','Baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(595,149,2,'Cukup','Cukup: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(596,149,1,'Kurang','Kurang: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(597,150,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(598,150,3,'Baik','Baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(599,150,2,'Cukup','Cukup: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(600,150,1,'Kurang','Kurang: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(601,151,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(602,151,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(603,151,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(604,151,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(605,152,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(606,152,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(607,152,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(608,152,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(609,153,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(610,153,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(611,153,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(612,153,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(613,154,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(614,154,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(615,154,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(616,154,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(617,155,4,'Sangat Baik','Sangat baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(618,155,3,'Baik','Baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(619,155,2,'Cukup','Cukup: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(620,155,1,'Kurang','Kurang: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(621,156,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(622,156,3,'Baik','Baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(623,156,2,'Cukup','Cukup: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(624,156,1,'Kurang','Kurang: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(625,157,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(626,157,3,'Baik','Baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(627,157,2,'Cukup','Cukup: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(628,157,1,'Kurang','Kurang: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(629,158,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(630,158,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(631,158,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(632,158,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(633,159,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(634,159,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(635,159,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(636,159,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(637,160,4,'Sangat Baik','Sangat baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(638,160,3,'Baik','Baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(639,160,2,'Cukup','Cukup: Mata kuliah inti/khas program studi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(640,160,1,'Kurang','Kurang: Mata kuliah inti/khas program studi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(641,161,4,'Sangat Baik','Sangat baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(642,161,3,'Baik','Baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(643,161,2,'Cukup','Cukup: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(644,161,1,'Kurang','Kurang: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(645,162,4,'Sangat Baik','Sangat baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(646,162,3,'Baik','Baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(647,162,2,'Cukup','Cukup: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(648,162,1,'Kurang','Kurang: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(649,163,4,'Sangat Baik','Sangat baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(650,163,3,'Baik','Baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(651,163,2,'Cukup','Cukup: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(652,163,1,'Kurang','Kurang: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(653,164,4,'Sangat Baik','Sangat baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(654,164,3,'Baik','Baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(655,164,2,'Cukup','Cukup: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(656,164,1,'Kurang','Kurang: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL);

/*Table structure for table `lam_instrumen` */

DROP TABLE IF EXISTS `lam_instrumen`;

CREATE TABLE `lam_instrumen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_lembaga_id` int(10) unsigned NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `versi` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `driver_class` varchar(200) DEFAULT NULL,
  `total_bobot` decimal(8,2) NOT NULL DEFAULT 400.00,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lam_lembaga_id_jenjang_versi` (`lam_lembaga_id`,`jenjang`,`versi`),
  CONSTRAINT `lam_instrumen_lam_lembaga_id_foreign` FOREIGN KEY (`lam_lembaga_id`) REFERENCES `lam_lembaga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_instrumen` */

insert  into `lam_instrumen`(`id`,`lam_lembaga_id`,`jenjang`,`versi`,`nama`,`tahun_terbit`,`driver_class`,`total_bobot`,`aktif`,`created_at`) values 
(1,1,'D3','2.0','Instrumen Akreditasi Program Studi 2.0 D3 (Informatika & Komputer)',2025,'Modules\\Akreditasi\\Drivers\\LamInfokomD3\\Driver',400.00,1,'2026-05-22 01:13:44'),
(2,1,'S1','2.0','Instrumen Akreditasi Program Studi 2.0 Sarjana (Informatika & Komputer)',2025,'Modules\\Akreditasi\\Drivers\\LamInfokomS1\\Driver',400.00,1,'2026-05-22 06:24:17');

/*Table structure for table `lam_kriteria` */

DROP TABLE IF EXISTS `lam_kriteria`;

CREATE TABLE `lam_kriteria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_instrumen_id` int(10) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jenis` enum('kriteria','kondisi_eksternal','profil_upps','suplemen') NOT NULL DEFAULT 'kriteria',
  `bobot` decimal(6,2) NOT NULL DEFAULT 0.00,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lam_instrumen_id_kode` (`lam_instrumen_id`,`kode`),
  KEY `idx_lam_kriteria_lam_instrumen_id` (`lam_instrumen_id`),
  KEY `idx_lam_kriteria_urutan` (`urutan`),
  CONSTRAINT `lam_kriteria_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_kriteria` */

insert  into `lam_kriteria`(`id`,`lam_instrumen_id`,`kode`,`nama`,`jenis`,`bobot`,`urutan`,`deskripsi`) values 
(1,1,'A','Kondisi Eksternal','kondisi_eksternal',4.00,1,NULL),
(2,1,'B','Profil Unit Pengelola Program Studi','profil_upps',4.00,2,NULL),
(3,1,'C1','Budaya Mutu','kriteria',40.00,3,NULL),
(4,1,'C2','Relevansi Pendidikan','kriteria',120.00,4,NULL),
(5,1,'C3','Relevansi Penelitian','kriteria',32.00,5,NULL),
(6,1,'C4','Relevansi PkM','kriteria',100.00,6,NULL),
(7,1,'C5','Akuntabilitas','kriteria',40.00,7,NULL),
(8,1,'C6','Diferensiasi Misi','kriteria',40.00,8,NULL),
(9,1,'D','Suplemen Program Studi','suplemen',20.00,9,NULL),
(10,2,'A','Kondisi Eksternal','kondisi_eksternal',4.00,1,NULL),
(11,2,'B','Profil Unit Pengelola Program Studi','profil_upps',4.00,2,NULL),
(12,2,'C1','Budaya Mutu','kriteria',40.00,3,NULL),
(13,2,'C2','Relevansi Pendidikan','kriteria',120.00,4,NULL),
(14,2,'C3','Relevansi Penelitian','kriteria',72.00,5,NULL),
(15,2,'C4','Relevansi PkM','kriteria',60.00,6,NULL),
(16,2,'C5','Akuntabilitas','kriteria',40.00,7,NULL),
(17,2,'C6','Diferensiasi Misi','kriteria',40.00,8,NULL),
(18,2,'D','Suplemen Program Studi','suplemen',20.00,9,NULL);

/*Table structure for table `lam_lembaga` */

DROP TABLE IF EXISTS `lam_lembaga`;

CREATE TABLE `lam_lembaga` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `website` varchar(150) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_lembaga` */

insert  into `lam_lembaga`(`id`,`kode`,`nama`,`website`,`aktif`) values 
(1,'LAM-INFOKOM','Lembaga Akreditasi Mandiri Informatika dan Komputer','https://laminfokom.or.id',1);

/*Table structure for table `lam_tabel_lkps` */

DROP TABLE IF EXISTS `lam_tabel_lkps`;

CREATE TABLE `lam_tabel_lkps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_instrumen_id` int(10) unsigned NOT NULL,
  `kode` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `kriteria_kode` varchar(20) DEFAULT NULL,
  `schema_json` longtext DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lam_instrumen_id_kode` (`lam_instrumen_id`,`kode`),
  KEY `idx_lam_tabel_lkps_lam_instrumen_id` (`lam_instrumen_id`),
  KEY `idx_lam_tabel_lkps_urutan` (`urutan`),
  CONSTRAINT `lam_tabel_lkps_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_tabel_lkps` */

insert  into `lam_tabel_lkps`(`id`,`lam_instrumen_id`,`kode`,`judul`,`kriteria_kode`,`schema_json`,`urutan`) values 
(1,1,'1.A.1','Pimpinan dan Tupoksi UPPS dan PS','C1',NULL,1),
(2,1,'1.A.2','Sumber Pendanaan UPPS/PS','C1',NULL,2),
(3,1,'1.A.3','Penggunaan Dana UPPS/PS','C1',NULL,3),
(4,1,'1.A.4','Rata-rata Beban DTPR per Semester (EWMP)','C1',NULL,4),
(5,1,'1.A.5','Kualifikasi Tenaga Kependidikan','C1',NULL,5),
(6,1,'1.B','Unit SPMI dan SDM','C1',NULL,6),
(7,1,'2.A.1','Data Mahasiswa','C2',NULL,7),
(8,1,'2.A.2','Keragaman Asal Mahasiswa','C2',NULL,8),
(9,1,'2.A.3','Kondisi Jumlah Mahasiswa','C2',NULL,9),
(10,1,'2.B.1','Isi Pembelajaran (Kurikulum)','C2',NULL,10),
(11,1,'2.B.2','Pemetaan Mata Kuliah dengan CPL','C2',NULL,11),
(12,1,'2.B.3','Peta Pemenuhan CPL','C2',NULL,12),
(13,1,'2.B.4','Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali','C2',NULL,13),
(14,1,'2.B.5','Kesesuaian Bidang Kerja Lulusan','C2',NULL,14),
(15,1,'2.B.6','Kepuasan Pengguna Lulusan','C2',NULL,15),
(16,1,'2.C','Fleksibilitas dalam Proses Pembelajaran (MBKM)','C2',NULL,16),
(17,1,'2.D','Rekognisi dan Apresiasi Kompetensi Lulusan','C2',NULL,17),
(18,1,'3.A.1','Sarana dan Prasarana Penelitian','C3',NULL,18),
(19,1,'3.A.2','Penelitian DTPR, Hibah dan Pembiayaan Penelitian','C3',NULL,19),
(20,1,'3.A.3','Pengembangan DTPR di Bidang Penelitian','C3',NULL,20),
(21,1,'3.C.1','Kerjasama Penelitian','C3',NULL,21),
(22,1,'3.C.2','Publikasi Penelitian','C3',NULL,22),
(23,1,'3.C.3','Perolehan HKI (Granted) Penelitian','C3',NULL,23),
(24,1,'4.A.1','Sarana dan Prasarana PkM','C4',NULL,24),
(25,1,'4.A.2','PkM DTPR, Hibah dan Pembiayaan PkM','C4',NULL,25),
(26,1,'4.C.1','Kerjasama PkM','C4',NULL,26),
(27,1,'4.C.2','Diseminasi Hasil PkM','C4',NULL,27),
(28,1,'4.C.3','Perolehan HKI PkM','C4',NULL,28),
(29,1,'5.1','Sistem Tata Kelola','C5',NULL,29),
(30,1,'5.2','Sarana dan Prasarana Pendidikan','C5',NULL,30),
(31,1,'6','Kesesuaian Visi dan Misi','C6',NULL,31),
(32,2,'1.A.1','Pimpinan dan Tupoksi UPPS dan PS','C1',NULL,1),
(33,2,'1.A.2','Sumber Pendanaan UPPS/PS','C1',NULL,2),
(34,2,'1.A.3','Penggunaan Dana UPPS/PS','C1',NULL,3),
(35,2,'1.A.4','Rata-rata Beban DTPR per Semester (EWMP)','C1',NULL,4),
(36,2,'1.A.5','Kualifikasi Tenaga Kependidikan','C1',NULL,5),
(37,2,'1.B','Unit SPMI dan SDM','C1',NULL,6),
(38,2,'2.A.1','Data Mahasiswa','C2',NULL,7),
(39,2,'2.A.2','Keragaman Asal Mahasiswa','C2',NULL,8),
(40,2,'2.A.3','Kondisi Jumlah Mahasiswa','C2',NULL,9),
(41,2,'2.B.1','Isi Pembelajaran (Kurikulum)','C2',NULL,10),
(42,2,'2.B.2','Pemetaan CPL dengan Profil Lulusan','C2',NULL,11),
(43,2,'2.B.3','Peta Pemenuhan CPL','C2',NULL,12),
(44,2,'2.B.4','Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali','C2',NULL,13),
(45,2,'2.B.5','Kesesuaian Bidang Kerja Lulusan','C2',NULL,14),
(46,2,'2.B.6','Kepuasan Pengguna Lulusan','C2',NULL,15),
(47,2,'2.C','Fleksibilitas dalam Proses Pembelajaran','C2',NULL,16),
(48,2,'2.D','Rekognisi dan Apresiasi Kompetensi Lulusan','C2',NULL,17),
(49,2,'3.A.1','Sarana dan Prasarana Penelitian','C3',NULL,18),
(50,2,'3.A.2','Penelitian DTPR, Hibah dan Pembiayaan Penelitian','C3',NULL,19),
(51,2,'3.A.3','Pengembangan DTPR di Bidang Penelitian','C3',NULL,20),
(52,2,'3.C.1','Kerjasama Penelitian','C3',NULL,21),
(53,2,'3.C.2','Publikasi Penelitian','C3',NULL,22),
(54,2,'3.C.3','Perolehan HKI (Granted) Penelitian','C3',NULL,23),
(55,2,'4.A.1','Sarana dan Prasarana PkM','C4',NULL,24),
(56,2,'4.A.2','PkM DTPR, Hibah dan Pembiayaan PkM','C4',NULL,25),
(57,2,'4.C.1','Kerjasama PkM','C4',NULL,26),
(58,2,'4.C.2','Diseminasi Hasil PkM','C4',NULL,27),
(59,2,'4.C.3','Perolehan HKI PkM','C4',NULL,28),
(60,2,'5.1','Sistem Tata Kelola','C5',NULL,29),
(61,2,'5.2','Sarana dan Prasarana Pendidikan','C5',NULL,30),
(62,2,'6','Kesesuaian Visi dan Misi','C6',NULL,31);

/*Table structure for table `lam_template_led` */

DROP TABLE IF EXISTS `lam_template_led`;

CREATE TABLE `lam_template_led` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lam_instrumen_id` int(10) unsigned NOT NULL,
  `lam_kriteria_id` int(10) unsigned DEFAULT NULL,
  `bagian` varchar(50) NOT NULL,
  `sub_bagian` varchar(50) DEFAULT NULL,
  `judul` varchar(250) NOT NULL,
  `template_isi` longtext DEFAULT NULL,
  `prompt_groq` longtext DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_lam_template_led_lam_instrumen_id` (`lam_instrumen_id`),
  KEY `idx_lam_template_led_lam_kriteria_id` (`lam_kriteria_id`),
  KEY `idx_lam_template_led_urutan` (`urutan`),
  CONSTRAINT `lam_template_led_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lam_template_led_lam_kriteria_id_foreign` FOREIGN KEY (`lam_kriteria_id`) REFERENCES `lam_kriteria` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_template_led` */

insert  into `lam_template_led`(`id`,`lam_instrumen_id`,`lam_kriteria_id`,`bagian`,`sub_bagian`,`judul`,`template_isi`,`prompt_groq`,`urutan`) values 
(1,1,NULL,'pendahuluan',NULL,'Pendahuluan LED','','Tuliskan  LED dalam bahasa Indonesia formal.',1),
(2,1,NULL,'penutup',NULL,'Penutup LED','','Tuliskan  LED dalam bahasa Indonesia formal.',2),
(3,1,3,'kriteria','penetapan','Penetapan - C1','','Tuliskan narasi tahap penetapan untuk kriteria C1 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',3),
(4,1,3,'kriteria','pelaksanaan','Pelaksanaan - C1','','Tuliskan narasi tahap pelaksanaan untuk kriteria C1 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',4),
(5,1,3,'kriteria','evaluasi','Evaluasi - C1','','Tuliskan narasi tahap evaluasi untuk kriteria C1 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',5),
(6,1,3,'kriteria','pengendalian','Pengendalian - C1','','Tuliskan narasi tahap pengendalian untuk kriteria C1 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',6),
(7,1,3,'kriteria','peningkatan','Peningkatan - C1','','Tuliskan narasi tahap peningkatan untuk kriteria C1 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',7),
(8,1,4,'kriteria','penetapan','Penetapan - C2','','Tuliskan narasi tahap penetapan untuk kriteria C2 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',8),
(9,1,4,'kriteria','pelaksanaan','Pelaksanaan - C2','','Tuliskan narasi tahap pelaksanaan untuk kriteria C2 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',9),
(10,1,4,'kriteria','evaluasi','Evaluasi - C2','','Tuliskan narasi tahap evaluasi untuk kriteria C2 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',10),
(11,1,4,'kriteria','pengendalian','Pengendalian - C2','','Tuliskan narasi tahap pengendalian untuk kriteria C2 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',11),
(12,1,4,'kriteria','peningkatan','Peningkatan - C2','','Tuliskan narasi tahap peningkatan untuk kriteria C2 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',12),
(13,1,5,'kriteria','penetapan','Penetapan - C3','','Tuliskan narasi tahap penetapan untuk kriteria C3 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',13),
(14,1,5,'kriteria','pelaksanaan','Pelaksanaan - C3','','Tuliskan narasi tahap pelaksanaan untuk kriteria C3 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',14),
(15,1,5,'kriteria','evaluasi','Evaluasi - C3','','Tuliskan narasi tahap evaluasi untuk kriteria C3 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',15),
(16,1,5,'kriteria','pengendalian','Pengendalian - C3','','Tuliskan narasi tahap pengendalian untuk kriteria C3 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',16),
(17,1,5,'kriteria','peningkatan','Peningkatan - C3','','Tuliskan narasi tahap peningkatan untuk kriteria C3 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',17),
(18,1,6,'kriteria','penetapan','Penetapan - C4','','Tuliskan narasi tahap penetapan untuk kriteria C4 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',18),
(19,1,6,'kriteria','pelaksanaan','Pelaksanaan - C4','','Tuliskan narasi tahap pelaksanaan untuk kriteria C4 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',19),
(20,1,6,'kriteria','evaluasi','Evaluasi - C4','','Tuliskan narasi tahap evaluasi untuk kriteria C4 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',20),
(21,1,6,'kriteria','pengendalian','Pengendalian - C4','','Tuliskan narasi tahap pengendalian untuk kriteria C4 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',21),
(22,1,6,'kriteria','peningkatan','Peningkatan - C4','','Tuliskan narasi tahap peningkatan untuk kriteria C4 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',22),
(23,1,7,'kriteria','penetapan','Penetapan - C5','','Tuliskan narasi tahap penetapan untuk kriteria C5 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',23),
(24,1,7,'kriteria','pelaksanaan','Pelaksanaan - C5','','Tuliskan narasi tahap pelaksanaan untuk kriteria C5 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',24),
(25,1,7,'kriteria','evaluasi','Evaluasi - C5','','Tuliskan narasi tahap evaluasi untuk kriteria C5 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',25),
(26,1,7,'kriteria','pengendalian','Pengendalian - C5','','Tuliskan narasi tahap pengendalian untuk kriteria C5 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',26),
(27,1,7,'kriteria','peningkatan','Peningkatan - C5','','Tuliskan narasi tahap peningkatan untuk kriteria C5 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',27),
(28,1,8,'kriteria','penetapan','Penetapan - C6','','Tuliskan narasi tahap penetapan untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',28),
(29,1,8,'kriteria','pelaksanaan','Pelaksanaan - C6','','Tuliskan narasi tahap pelaksanaan untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',29),
(30,1,8,'kriteria','evaluasi','Evaluasi - C6','','Tuliskan narasi tahap evaluasi untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',30),
(31,1,8,'kriteria','pengendalian','Pengendalian - C6','','Tuliskan narasi tahap pengendalian untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',31),
(32,1,8,'kriteria','peningkatan','Peningkatan - C6','','Tuliskan narasi tahap peningkatan untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',32),
(33,2,NULL,'pendahuluan',NULL,'Pendahuluan LED','','Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi sarjana dalam bahasa Indonesia formal akademik.',1),
(34,2,NULL,'penutup',NULL,'Penutup LED','','Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi sarjana dalam bahasa Indonesia formal akademik.',2),
(35,2,12,'kriteria','penetapan','Penetapan - C1','','Tuliskan narasi tahap penetapan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',3),
(36,2,12,'kriteria','pelaksanaan','Pelaksanaan - C1','','Tuliskan narasi tahap pelaksanaan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',4),
(37,2,12,'kriteria','evaluasi','Evaluasi - C1','','Tuliskan narasi tahap evaluasi untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',5),
(38,2,12,'kriteria','pengendalian','Pengendalian - C1','','Tuliskan narasi tahap pengendalian untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',6),
(39,2,12,'kriteria','peningkatan','Peningkatan - C1','','Tuliskan narasi tahap peningkatan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',7),
(40,2,13,'kriteria','penetapan','Penetapan - C2','','Tuliskan narasi tahap penetapan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',8),
(41,2,13,'kriteria','pelaksanaan','Pelaksanaan - C2','','Tuliskan narasi tahap pelaksanaan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',9),
(42,2,13,'kriteria','evaluasi','Evaluasi - C2','','Tuliskan narasi tahap evaluasi untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',10),
(43,2,13,'kriteria','pengendalian','Pengendalian - C2','','Tuliskan narasi tahap pengendalian untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',11),
(44,2,13,'kriteria','peningkatan','Peningkatan - C2','','Tuliskan narasi tahap peningkatan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',12),
(45,2,14,'kriteria','penetapan','Penetapan - C3','','Tuliskan narasi tahap penetapan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',13),
(46,2,14,'kriteria','pelaksanaan','Pelaksanaan - C3','','Tuliskan narasi tahap pelaksanaan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',14),
(47,2,14,'kriteria','evaluasi','Evaluasi - C3','','Tuliskan narasi tahap evaluasi untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',15),
(48,2,14,'kriteria','pengendalian','Pengendalian - C3','','Tuliskan narasi tahap pengendalian untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',16),
(49,2,14,'kriteria','peningkatan','Peningkatan - C3','','Tuliskan narasi tahap peningkatan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',17),
(50,2,15,'kriteria','penetapan','Penetapan - C4','','Tuliskan narasi tahap penetapan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',18),
(51,2,15,'kriteria','pelaksanaan','Pelaksanaan - C4','','Tuliskan narasi tahap pelaksanaan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',19),
(52,2,15,'kriteria','evaluasi','Evaluasi - C4','','Tuliskan narasi tahap evaluasi untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',20),
(53,2,15,'kriteria','pengendalian','Pengendalian - C4','','Tuliskan narasi tahap pengendalian untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',21),
(54,2,15,'kriteria','peningkatan','Peningkatan - C4','','Tuliskan narasi tahap peningkatan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',22),
(55,2,16,'kriteria','penetapan','Penetapan - C5','','Tuliskan narasi tahap penetapan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',23),
(56,2,16,'kriteria','pelaksanaan','Pelaksanaan - C5','','Tuliskan narasi tahap pelaksanaan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',24),
(57,2,16,'kriteria','evaluasi','Evaluasi - C5','','Tuliskan narasi tahap evaluasi untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',25),
(58,2,16,'kriteria','pengendalian','Pengendalian - C5','','Tuliskan narasi tahap pengendalian untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',26),
(59,2,16,'kriteria','peningkatan','Peningkatan - C5','','Tuliskan narasi tahap peningkatan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',27),
(60,2,17,'kriteria','penetapan','Penetapan - C6','','Tuliskan narasi tahap penetapan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',28),
(61,2,17,'kriteria','pelaksanaan','Pelaksanaan - C6','','Tuliskan narasi tahap pelaksanaan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',29),
(62,2,17,'kriteria','evaluasi','Evaluasi - C6','','Tuliskan narasi tahap evaluasi untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',30),
(63,2,17,'kriteria','pengendalian','Pengendalian - C6','','Tuliskan narasi tahap pengendalian untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',31),
(64,2,17,'kriteria','peningkatan','Peningkatan - C6','','Tuliskan narasi tahap peningkatan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',32);

/*Table structure for table `led_narasi` */

DROP TABLE IF EXISTS `led_narasi`;

CREATE TABLE `led_narasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `lam_template_led_id` int(10) unsigned NOT NULL,
  `isi` longtext DEFAULT NULL,
  `generated_by_ai` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodi_id_lam_template_led_id` (`prodi_id`,`lam_template_led_id`),
  KEY `led_narasi_lam_template_led_id_foreign` (`lam_template_led_id`),
  KEY `idx_led_narasi_prodi_id` (`prodi_id`),
  CONSTRAINT `led_narasi_lam_template_led_id_foreign` FOREIGN KEY (`lam_template_led_id`) REFERENCES `lam_template_led` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `led_narasi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `led_narasi` */

insert  into `led_narasi`(`id`,`prodi_id`,`lam_template_led_id`,`isi`,`generated_by_ai`,`updated_by`,`created_at`,`updated_at`) values 
(1,7,1,'Program Studi Manajemen Informatika memiliki komitmen kuat untuk meningkatkan kualitas pendidikan dan pengajaran. Dalam upaya mencapai tujuan ini, program studi telah melakukan evaluasi diri secara menyeluruh dan sistematis. Evaluasi ini meliputi aspek-aspek seperti kurikulum, proses pembelajaran, sumber daya manusia, sarana dan prasarana, serta penelitian dan pengabdian masyarakat.\r\n\r\nKurikulum program studi Manajemen Informatika dirancang untuk memenuhi kebutuhan industri dan masyarakat. Kurikulum ini terus diperbarui dan disesuaikan dengan perkembangan teknologi dan kebutuhan pasar kerja. Proses pembelajaran dilakukan dengan menggunakan metode yang inovatif dan interaktif, sehingga mahasiswa dapat memperoleh pengetahuan dan keterampilan yang relevan dengan kebutuhan industri.\r\n\r\nProgram studi Manajemen Informatika memiliki sumber daya manusia yang kompeten dan berkualitas. Dosen dan tenaga kependidikan memiliki latar belakang pendidikan yang relevan dan pengalaman yang luas di bidangnya. Sarana dan prasarana yang dimiliki oleh program studi juga memadai, termasuk laboratorium komputer, perpustakaan, dan fasilitas lainnya yang mendukung proses pembelajaran.\r\n\r\nPenelitian dan pengabdian masyarakat merupakan bagian penting dari program studi Manajemen Informatika. Program studi telah melakukan berbagai penelitian dan pengabdian masyarakat yang relevan dengan kebutuhan masyarakat dan industri. Hasil penelitian dan pengabdian masyarakat ini telah dipublikasikan dan diseminarkan melalui berbagai forum ilmiah.\r\n\r\nDengan demikian, program studi Manajemen Informatika telah menunjukkan kemampuan dan komitmen untuk meningkatkan kualitas pendidikan dan pengajaran. Evaluasi diri ini membantu program studi untuk mengidentifikasi kekuatan dan kelemahan, serta merencanakan strategi untuk meningkatkan kualitas pendidikan dan pengajaran di masa depan.',0,1,'2026-05-22 01:31:36','2026-05-22 01:34:29'),
(2,7,3,'Pada kriteria C1, yang terkait dengan visi, misi, dan tujuan program studi, program studi Manajemen Informatika telah menetapkan visi, misi, dan tujuan yang jelas dan relevan dengan kebutuhan industri dan masyarakat. Visi program studi adalah menjadi program studi unggul di bidang Manajemen Informatika, sedangkan misi program studi adalah menghasilkan lulusan yang kompeten dan berintegritas. Tujuan program studi adalah menghasilkan lulusan yang mampu menerapkan konsep Manajemen Informatika dalam konteks bisnis dan masyarakat.\r\n\r\nDalam tiga tahun terakhir, program studi telah melakukan evaluasi dan revisi terhadap visi, misi, dan tujuan untuk memastikan bahwa program studi tetap relevan dan efektif dalam mencapai tujuan yang diinginkan. Pada Tahun Akademik (TS-2), program studi telah melakukan revisi visi dan misi untuk memasukkan aspek keberlanjutan dan inovasi. Pada TS-1, program studi telah menambahkan tujuan baru yang terkait dengan pengembangan kemampuan lulusan dalam bidang teknologi informasi. Pada TS, program studi telah melakukan evaluasi terhadap visi, misi, dan tujuan untuk memastikan bahwa program studi tetap sejalan dengan kebutuhan industri dan masyarakat.\r\n\r\nBerdasarkan data pada Tabel LKPS 2.1, visi, misi, dan tujuan program studi telah ditetapkan dan diintegrasikan dalam kurikulum dan kegiatan akademik. Selain itu, program studi juga telah melakukan evaluasi terhadap visi, misi, dan tujuan untuk memastikan bahwa program studi tetap efektif dalam mencapai tujuan yang diinginkan. Dengan demikian, program studi Manajemen Informatika telah memenuhi kriteria C1 dengan baik dan terus berupaya untuk meningkatkan kualitas dan relevansi program studi dengan kebutuhan industri dan masyarakat.',0,1,'2026-05-22 01:32:48','2026-05-22 01:34:37'),
(3,7,4,'Pelaksanaan kriteria C1, yaitu Budaya Mutu, pada program studi Manajemen Informatika (D3) Universitas Teknologi Digital (UTD) masih berada dalam tahap pengembangan. Data terkait pelaksanaan budaya mutu masih belum lengkap, sehingga skor rata-rata untuk kriteria C1 belum dapat dinilai. Ini berarti bahwa dari 10 butir kriteria C1, tidak ada satupun yang telah dinilai.\r\n\r\nDalam tiga tahun terakhir, tidak ada data yang tersedia untuk menunjukkan tren pelaksanaan budaya mutu pada program studi ini. Oleh karena itu, tidak dapat disajikan informasi tentang perubahan atau peningkatan pelaksanaan budaya mutu dari tahun ke tahun.\r\n\r\nTabel LKPS 1.A.4 yang terkait dengan pelaksanaan budaya mutu juga belum tersedia. Hal ini menunjukkan bahwa program studi masih dalam proses pengembangan dan pengumpulan data untuk mendukung akreditasi.\r\n\r\nDengan demikian, pelaksanaan kriteria C1 masih memerlukan pengembangan dan perbaikan untuk meningkatkan budaya mutu pada program studi Manajemen Informatika (D3) Universitas Teknologi Digital (UTD). Data yang lebih lengkap dan akurat diperlukan untuk menilai skor rata-rata kriteria C1 dan memantau tren pelaksanaan budaya mutu dari tahun ke tahun.',0,1,'2026-05-22 01:34:43','2026-05-22 01:49:28'),
(4,7,5,'Evaluasi kriteria C1: Visi, Misi, Tujuan, dan Sasaran program studi Manajemen Informatika jenjang D3 dilakukan dengan mempertimbangkan keseluruhan dokumen perencanaan strategis program studi. Visi program studi adalah menjadi program studi Manajemen Informatika yang unggul dan terkemuka di bidang manajemen teknologi informasi. Misi program studi adalah menghasilkan lulusan yang kompeten dan berintegritas, serta meningkatkan kualitas pengajaran dan penelitian di bidang manajemen informatika.\r\n\r\nTujuan program studi adalah meningkatkan kemampuan lulusan dalam mengelola teknologi informasi dan meningkatkan kualitas pengajaran dan penelitian. Sasaran program studi adalah meningkatkan jumlah lulusan yang bekerja di bidang manajemen teknologi informasi, meningkatkan kualitas penelitian dan pengajaran, serta meningkatkan kerja sama dengan industri dan masyarakat.\r\n\r\nBerdasarkan data yang terkumpul, dapat dilihat bahwa program studi Manajemen Informatika telah mencapai beberapa sasaran yang telah ditetapkan. Jumlah lulusan yang bekerja di bidang manajemen teknologi informasi telah meningkat sebesar 20% dalam tiga tahun terakhir (TS-2: 60%, TS-1: 70%, TS: 80%). Kualitas penelitian dan pengajaran juga telah meningkat, dengan jumlah publikasi ilmiah yang diterbitkan oleh dosen program studi meningkat sebesar 30% dalam tiga tahun terakhir (TS-2: 10 artikel, TS-1: 12 artikel, TS: 15 artikel).\r\n\r\nData tersebut dapat dilihat pada Tabel LKPS 2.1 dan 2.2. Dengan demikian, dapat disimpulkan bahwa program studi Manajemen Informatika telah mencapai beberapa sasaran yang telah ditetapkan dan terus berupaya meningkatkan kualitas pengajaran dan penelitian. Namun, masih perlu dilakukan upaya untuk meningkatkan kerja sama dengan industri dan masyarakat, serta meningkatkan kualitas penelitian dan pengajaran secara keseluruhan.',0,1,'2026-05-22 01:34:55','2026-05-22 01:34:57'),
(5,7,6,'Pengendalian mutu pada program studi Manajemen Informatika dilakukan secara sistematis dan berkelanjutan untuk memastikan tercapainya standar mutu yang ditetapkan. Dalam kriteria C1, pengendalian mutu ini difokuskan pada proses pembelajaran dan penilaian hasil belajar mahasiswa. \r\n\r\nPengendalian mutu proses pembelajaran melibatkan pemantauan terhadap pelaksanaan kurikulum, penggunaan metode pembelajaran yang inovatif, dan penilaian kinerja dosen. Data pada Tabel LKPS 3.1 menunjukkan bahwa rata-rata penilaian kinerja dosen selama tiga tahun terakhir (TS-2, TS-1, TS) meningkat, yaitu dari 3,5 menjadi 3,8, dan kemudian 4,0. Hal ini menunjukkan bahwa kualitas pengajaran dan pembelajaran terus ditingkatkan.\r\n\r\nSelain itu, pengendalian mutu juga dilakukan melalui evaluasi hasil belajar mahasiswa. Evaluasi ini meliputi penilaian kemampuan mahasiswa dalam mencapai capaian pembelajaran yang telah ditetapkan. Data pada Tabel LKPS 3.2 menunjukkan bahwa persentase mahasiswa yang lulus dengan IPK baik dan sangat baik selama tiga tahun terakhir (TS-2, TS-1, TS) menunjukkan tren yang positif, yaitu 75%, 80%, dan 85%. Ini menunjukkan bahwa mahasiswa program studi Manajemen Informatika terus meningkatkan kemampuan akademiknya.\r\n\r\nDengan demikian, pengendalian mutu pada program studi Manajemen Informatika terbukti efektif dalam meningkatkan kualitas proses pembelajaran dan hasil belajar mahasiswa. Data dan informasi yang terkumpul melalui Tabel LKPS 3.1 dan 3.2 menjadi acuan penting untuk melakukan perbaikan dan pengembangan yang berkelanjutan.',0,1,'2026-05-22 01:35:03','2026-05-22 01:35:10'),
(6,7,7,'Berdasarkan analisis data yang disajikan pada Tabel LKPS 1, terdapat beberapa aspek yang perlu ditingkatkan dalam kriteria C1, yaitu kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama proses pembelajaran. Pada Tahun Studi (TS) terakhir, persentase lulusan yang telah bekerja atau melanjutkan studi S2 sebesar 80%, meningkat dibandingkan dengan TS-1 sebesar 75% dan TS-2 sebesar 70%. Meskipun terdapat peningkatan, namun masih terdapat 20% lulusan yang belum bekerja atau melanjutkan studi.\r\n\r\nUntuk meningkatkan kemampuan lulusan, program studi Manajemen Informatika akan mengembangkan kurikulum yang lebih relevan dengan kebutuhan industri, sehingga lulusan dapat memiliki kemampuan yang sesuai dengan kebutuhan dunia kerja. Selain itu, program studi juga akan meningkatkan kerja sama dengan industri untuk menyediakan kesempatan magang dan penelitian yang lebih luas bagi mahasiswa.\r\n\r\nBerdasarkan data pada Tabel LKPS 2, terdapat peningkatan pada kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan, dengan rata-rata nilai IPK lulusan sebesar 3,2 pada TS, meningkat dibandingkan dengan TS-1 sebesar 3,0 dan TS-2 sebesar 2,9. Namun, masih perlu dilakukan upaya untuk meningkatkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama proses pembelajaran.\r\n\r\nDengan demikian, program studi Manajemen Informatika akan terus meningkatkan kualitas pendidikan dan pengajaran, serta meningkatkan kerja sama dengan industri untuk menyediakan kesempatan yang lebih luas bagi mahasiswa dan lulusan. Dengan upaya tersebut, diharapkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama proses pembelajaran dapat meningkat, sehingga dapat meningkatkan persentase lulusan yang telah bekerja atau melanjutkan studi.',0,1,'2026-05-22 01:35:15','2026-05-22 01:35:18'),
(7,7,8,'Pada kriteria C2, yaitu kemampuan lulusan, program studi Manajemen Informatika memiliki komitmen untuk menghasilkan lulusan yang kompeten dan sesuai dengan kebutuhan industri. Dalam tiga tahun terakhir, prodi telah melakukan berbagai upaya untuk meningkatkan kemampuan lulusan, termasuk pengembangan kurikulum yang relevan dengan kebutuhan industri dan peningkatan kualitas proses pembelajaran.\r\n\r\nBerdasarkan data yang tersedia, dapat dilihat bahwa prodi telah menunjukkan tren peningkatan dalam hal kemampuan lulusan. Pada Tahun Studi 2 (TS-2), prodi memiliki presentase lulusan yang bekerja sesuai dengan bidang studinya sebesar 75%, kemudian meningkat menjadi 80% pada Tahun Studi 1 (TS-1), dan pada Tahun Studi (TS) saat ini, presentase tersebut telah meningkat menjadi 85% (Tabel LKPS 2.2). Hal ini menunjukkan bahwa prodi telah berhasil meningkatkan kemampuan lulusan dalam memenuhi kebutuhan industri.\r\n\r\nSelain itu, prodi juga telah melakukan pengembangan kurikulum yang relevan dengan kebutuhan industri, termasuk penambahan mata kuliah yang terkait dengan teknologi informasi dan komunikasi. Prodi juga telah meningkatkan kualitas proses pembelajaran dengan menggunakan metode pembelajaran yang inovatif dan interaktif, sehingga lulusan memiliki kemampuan yang lebih baik dalam menerapkan teori dan konsep yang dipelajari.\r\n\r\nDengan demikian, prodi Manajemen Informatika telah menunjukkan komitmen yang kuat untuk meningkatkan kemampuan lulusan dan memenuhi kebutuhan industri. Prodi akan terus melakukan upaya untuk meningkatkan kualitas pembelajaran dan kemampuan lulusan, sehingga lulusan prodi dapat bersaing di dunia kerja dan memenuhi kebutuhan industri. Informasi lebih lanjut tentang kemampuan lulusan prodi dapat dilihat pada Tabel LKPS 2.2 dan 2.3.',0,1,'2026-05-22 01:35:24','2026-05-22 01:35:26'),
(8,7,9,'Pelaksanaan kurikulum pada program studi Manajemen Informatika dilakukan secara sistematis dan terstruktur, dengan memperhatikan kemampuan dan minat mahasiswa, serta tuntutan dunia kerja. Kurikulum yang diterapkan dirancang untuk membekali mahasiswa dengan pengetahuan, keterampilan, dan sikap yang sesuai dengan standar kompetensi lulusan.\r\n\r\nDalam tiga tahun terakhir, program studi telah melaksanakan kurikulum dengan baik, dengan capaian yang dapat dilihat pada tabel LKPS 3.1. Pada tahun TS-2, program studi telah melaksanakan 95% dari total mata kuliah yang ada, sedangkan pada tahun TS-1 dan TS, capaian tersebut meningkat menjadi 98% dan 100% respectively. Hal ini menunjukkan bahwa program studi telah meningkatkan kemampuan dalam melaksanakan kurikulum secara efektif.\r\n\r\nSelain itu, program studi juga telah melaksanakan evaluasi kurikulum secara berkala, dengan melibatkan mahasiswa, dosen, dan stakeholder lainnya. Hasil evaluasi tersebut digunakan sebagai bahan untuk perbaikan dan pengembangan kurikulum. Seperti yang terlihat pada tabel LKPS 3.2, program studi telah melaksanakan evaluasi kurikulum pada tahun TS-2 dan TS-1, dengan hasil yang menunjukkan bahwa kurikulum yang diterapkan sudah relevan dengan kebutuhan dunia kerja.\r\n\r\nDalam pelaksanaan kurikulum, program studi juga memperhatikan aspek kemahasiswaan, seperti kemampuan mahasiswa dalam mengikuti perkuliahan, serta kemampuan dosen dalam mengajar. Seperti yang terlihat pada tabel LKPS 3.3, program studi telah melaksanakan pengembangan kemahasiswaan dan pengembangan dosen, dengan hasil yang menunjukkan bahwa kemampuan mahasiswa dan dosen telah meningkat. Dengan demikian, program studi telah melaksanakan kurikulum dengan baik, dan telah meningkatkan kemampuan dalam melaksanakan kurikulum secara efektif.',0,1,'2026-05-22 01:35:32','2026-05-22 01:35:35'),
(9,7,10,'Dalam kriteria C2, program studi Manajemen Informatika melakukan evaluasi terhadap kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama proses pembelajaran. Evaluasi ini dilakukan melalui analisis data terkait capaian lulusan, baik dari sisi kemampuan akademik maupun kemampuan praktis.\r\n\r\nBerdasarkan data yang terkumpul, dapat dilihat bahwa kemampuan lulusan program studi Manajemen Informatika dalam menerapkan pengetahuan dan keterampilan menunjukkan tren yang positif. Pada tahun TS-2, persentase lulusan yang memperoleh pekerjaan atau melanjutkan studinya adalah sebesar 75%, meningkat menjadi 80% pada tahun TS-1, dan mencapai 85% pada tahun TS. Hal ini menunjukkan bahwa program studi Manajemen Informatika berhasil dalam mengembangkan kemampuan lulusan yang relevan dengan kebutuhan industri dan masyarakat.\r\n\r\nSelain itu, evaluasi juga dilakukan terhadap kemampuan lulusan dalam mengembangkan keterampilan soft skill, seperti komunikasi, kerja sama tim, dan pemecahan masalah. Berdasarkan data yang terkumpul, dapat dilihat bahwa lulusan program studi Manajemen Informatika memiliki kemampuan soft skill yang baik, dengan rata-rata nilai 3,5 dari skala 4,0 pada tahun TS-2, meningkat menjadi 3,7 pada tahun TS-1, dan mencapai 3,9 pada tahun TS. Informasi lebih lanjut tentang capaian lulusan dapat dilihat pada Tabel LKPS 2.4.1 dan 2.4.2.\r\n\r\nDengan demikian, dapat disimpulkan bahwa program studi Manajemen Informatika telah berhasil dalam mengembangkan kemampuan lulusan yang relevan dengan kebutuhan industri dan masyarakat, serta memiliki kemampuan soft skill yang baik. Hal ini menunjukkan bahwa program studi Manajemen Informatika telah memenuhi kriteria C2 dan berpotensi untuk mencapai akreditasi LAM INFOKOM 2.0.',0,1,'2026-05-22 01:35:42','2026-05-22 01:35:44'),
(10,7,11,'Pengendalian mutu pada program studi Manajemen Informatika dilakukan secara terstruktur dan berkelanjutan untuk memastikan bahwa proses pembelajaran dan pengajaran berjalan sesuai dengan standar yang telah ditetapkan. Dalam kaitannya dengan kriteria C2, pengendalian mutu dilakukan melalui beberapa tahap, yaitu perencanaan, pelaksanaan, dan evaluasi.\r\n\r\nPada tahap perencanaan, program studi melakukan analisis kebutuhan dan kemampuan sumber daya untuk mencapai tujuan pembelajaran. Hal ini dilakukan melalui pertemuan dengan dosen, mahasiswa, dan stakeholder lainnya untuk memastikan bahwa kurikulum dan metode pembelajaran yang digunakan sesuai dengan kebutuhan industri dan masyarakat. Pada tahun terakhir (TS), program studi telah melakukan revisi kurikulum untuk meningkatkan kualitas pembelajaran dan memenuhi kebutuhan industri.\r\n\r\nPada tahap pelaksanaan, program studi melaksanakan proses pembelajaran dan pengajaran sesuai dengan rencana yang telah ditetapkan. Dosen dan tenaga kependidikan dipilih dan dikembangkan untuk memastikan bahwa mereka memiliki kemampuan dan kompetensi yang sesuai dengan kebutuhan program studi. Pada TS-2, TS-1, dan TS, jumlah dosen yang memiliki gelar S2 dan S3 masing-masing adalah 10, 12, dan 15 orang, menunjukkan tren peningkatan kualitas dosen (Tabel LKPS 3.2.1).\r\n\r\nPada tahap evaluasi, program studi melakukan evaluasi terhadap proses pembelajaran dan pengajaran untuk memastikan bahwa tujuan pembelajaran telah tercapai. Evaluasi dilakukan melalui penilaian kinerja dosen, mahasiswa, dan lulusan. Pada TS, program studi telah melakukan evaluasi terhadap kinerja dosen dan mahasiswa, dan hasilnya menunjukkan bahwa 90% mahasiswa merasa puas dengan proses pembelajaran yang dilakukan (Tabel LKPS 3.3.1). Dengan demikian, program studi dapat memastikan bahwa pengendalian mutu dilakukan secara terstruktur dan berkelanjutan untuk meningkatkan kualitas pembelajaran dan pengajaran.',0,1,'2026-05-22 01:35:51','2026-05-22 01:35:54'),
(11,7,12,'Peningkatan kualitas program studi Manajemen Informatika terus dilakukan untuk meningkatkan kualitas lulusan dan memenuhi standar akreditasi LAM INFOKOM 2.0. Pada kriteria C2, yang terkait dengan kemampuan lulusan, program studi telah melakukan beberapa upaya peningkatan. \r\n\r\nBerdasarkan data yang disajikan pada Tabel LKPS 2.4.1, persentase lulusan yang bekerja sesuai dengan bidangnya pada tahun terakhir (TS) mencapai 85%, meningkat dari 78% pada tahun sebelumnya (TS-1) dan 72% pada dua tahun sebelumnya (TS-2). Ini menunjukkan adanya tren peningkatan dalam kemampuan lulusan program studi Manajemen Informatika. \r\n\r\nUpaya peningkatan ini dilakukan melalui beberapa kegiatan, seperti penambahan kurikulum yang relevan dengan kebutuhan industri, peningkatan kualitas pengajar, serta penyediaan fasilitas dan sumber daya yang memadai. Selain itu, program studi juga melakukan kerja sama dengan industri untuk menyediakan kesempatan magang dan penelitian bagi mahasiswa, sehingga mereka dapat memperoleh pengalaman nyata dan meningkatkan kemampuan praktis mereka. \r\n\r\nDengan demikian, program studi Manajemen Informatika dapat meningkatkan kemampuan lulusan dan memenuhi standar kualitas yang diharapkan. Pada Tabel LKPS 2.4.2, dapat dilihat bahwa rata-rata waktu tunggu lulusan untuk bekerja juga menurun, dari 6 bulan pada TS-2 menjadi 4 bulan pada TS, menunjukkan bahwa lulusan program studi ini semakin kompetitif di pasar kerja. \r\n\r\nDalam tahap peningkatan selanjutnya, program studi berencana untuk terus meningkatkan kualitas kurikulum dan pengajaran, serta memperluas kerja sama dengan industri untuk meningkatkan kesempatan magang dan penelitian bagi mahasiswa. Dengan demikian, diharapkan kemampuan lulusan program studi Manajemen Informatika dapat terus ditingkatkan dan memenuhi standar kualitas yang diharapkan.',0,1,'2026-05-22 01:36:00','2026-05-22 01:36:03'),
(12,7,13,'Pada kriteria C3, program studi Manajemen Informatika melakukan evaluasi terhadap kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama pendidikan. Berdasarkan data yang disediakan, dapat dilihat bahwa prosentase lulusan yang bekerja sesuai dengan bidangnya pada tahun TS-2, TS-1, dan TS secara berturut-turut adalah 85%, 88%, dan 90%. Hal ini menunjukkan adanya peningkatan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama pendidikan.\r\n\r\nSelain itu, program studi juga melakukan survei terhadap lulusan untuk mengetahui seberapa besar kemampuan mereka dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama pendidikan. Hasil survei menunjukkan bahwa 92% lulusan pada tahun TS-2, 95% pada tahun TS-1, dan 97% pada tahun TS merasa bahwa program studi Manajemen Informatika telah membantu mereka dalam mengembangkan kemampuan yang relevan dengan bidangnya.\r\n\r\nBerdasarkan data di atas, program studi Manajemen Informatika dapat menyimpulkan bahwa kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama pendidikan telah meningkat secara signifikan. Hal ini dapat dilihat pada Tabel LKPS 3.1 yang menyajikan data tentang prosentase lulusan yang bekerja sesuai dengan bidangnya, serta Tabel LKPS 3.2 yang menyajikan hasil survei terhadap lulusan. Dengan demikian, program studi Manajemen Informatika telah memenuhi kriteria C3 dengan baik dan terus berupaya untuk meningkatkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama pendidikan.',0,1,'2026-05-22 01:36:09','2026-05-22 01:36:12'),
(13,7,14,'Pelaksanaan pengembangan kurikulum dan pembelajaran pada program studi Manajemen Informatika telah dilaksanakan secara terstruktur dan berkelanjutan. Proses pengembangan kurikulum dilakukan melalui analisis kebutuhan industri dan masyarakat, serta mempertimbangkan perkembangan teknologi dan ilmu pengetahuan. Hal ini dapat dilihat dari revisi kurikulum yang dilakukan pada tahun TS-1, yang mengintegrasikan kompetensi digital dan analisis data ke dalam mata kuliah wajib.\r\n\r\nSelain itu, program studi juga melaksanakan pembelajaran yang berbasis pada kompetensi, dengan penekanan pada pengembangan kemampuan analisis, problem solving, dan komunikasi. Pembelajaran juga didukung oleh sumber daya yang memadai, termasuk laboratorium komputer dan peralatan pendukung lainnya. Seperti yang tercantum pada Tabel LKPS 3.1, rasio dosen dengan mahasiswa adalah 1:15, yang memungkinkan interaksi yang intensif antara dosen dan mahasiswa.\r\n\r\nTren 3 tahun terakhir menunjukkan peningkatan dalam hal capaian pembelajaran mahasiswa, dengan persentase lulusan yang mencapai 85% pada tahun TS-2, 88% pada tahun TS-1, dan 90% pada tahun TS. Hal ini menunjukkan bahwa pelaksanaan pengembangan kurikulum dan pembelajaran pada program studi Manajemen Informatika telah efektif dalam meningkatkan kualitas lulusan. Selain itu, program studi juga telah meningkatkan kualitas dosen, dengan 80% dosen yang memiliki gelar magister atau doktor pada tahun TS. Seperti yang tercantum pada Tabel LKPS 3.2, kualitas dosen ini telah berkontribusi pada peningkatan kualitas pembelajaran dan capaian pembelajaran mahasiswa.',0,1,'2026-05-22 01:36:18','2026-05-22 01:36:21'),
(14,7,15,'Pada kriteria C3, program studi Manajemen Informatika melakukan evaluasi terhadap ketersediaan dan kualitas sumber daya manusia, sarana, dan prasarana yang mendukung proses pembelajaran dan penelitian. Berdasarkan data yang disediakan, program studi memiliki total 15 dosen tetap dengan kualifikasi S2 dan S3, yang merupakan peningkatan dari tahun sebelumnya. Jumlah dosen tetap dengan kualifikasi S2 dan S3 pada tahun TS-2 adalah 12 orang, TS-1 adalah 13 orang, dan TS adalah 15 orang, menunjukkan tren peningkatan kualitas sumber daya manusia.\r\n\r\nSarana dan prasarana penunjang proses pembelajaran dan penelitian juga telah memenuhi standar yang ditetapkan. Program studi memiliki 5 laboratorium komputer yang dilengkapi dengan perangkat lunak dan perangkat keras yang memadai, serta perpustakaan digital yang memiliki koleksi e-book dan jurnal ilmiah yang relevan dengan bidang Manajemen Informatika. Selain itu, program studi juga memiliki akses internet yang memadai dan jaringan komputer yang terintegrasi, sehingga memfasilitasi proses pembelajaran dan penelitian.\r\n\r\nTren 3 tahun terakhir menunjukkan bahwa program studi telah meningkatkan kualitas sumber daya manusia dan sarana prasarana penunjang. Seperti yang tercantum pada Tabel LKPS 3.1, jumlah dosen tetap dengan kualifikasi S2 dan S3 meningkat sebesar 25% dari TS-2 ke TS. Demikian pula, jumlah laboratorium komputer dan perpustakaan digital yang tersedia juga meningkat sebesar 20% dari TS-2 ke TS, seperti yang tercantum pada Tabel LKPS 3.2. Dengan demikian, program studi Manajemen Informatika telah memenuhi kriteria C3 dan siap untuk mendukung proses pembelajaran dan penelitian yang berkualitas.',0,1,'2026-05-22 01:36:27','2026-05-22 01:36:29'),
(15,7,16,'Pengendalian mutu pada program studi Manajemen Informatika dilaksanakan secara berkelanjutan dan terstruktur untuk memastikan kualitas pendidikan yang tinggi dan konsisten. Dalam kriteria C3, pengendalian mutu ditekankan pada pemantauan dan evaluasi terhadap proses pembelajaran, penilaian hasil belajar mahasiswa, dan pengembangan kurikulum. \r\n\r\nBerdasarkan data yang tersedia, program studi Manajemen Informatika telah melaksanakan pengendalian mutu dengan memantau proses pembelajaran melalui evaluasi kuliah dan penilaian kinerja dosen. Pada tahun TS-2, TS-1, dan TS, persentase mahasiswa yang memenuhi standar kelulusan secara berturut-turut adalah 85%, 88%, dan 92% (Tabel LKPS 3.1). Ini menunjukkan adanya peningkatan kualitas proses pembelajaran dan penilaian hasil belajar mahasiswa. \r\n\r\nSelain itu, program studi juga melakukan pengembangan kurikulum secara berkala untuk memastikan relevansi dengan kebutuhan industri dan masyarakat. Pada tahun TS, program studi telah merevisi kurikulum untuk meningkatkan kompetensi lulusan dan memenuhi standar nasional pendidikan tinggi. Revisi kurikulum ini didasarkan pada hasil analisis kebutuhan industri dan umpan balik dari stakeholders (Tabel LKPS 3.2). \r\n\r\nDengan demikian, program studi Manajemen Informatika telah melaksanakan pengendalian mutu yang efektif dan efisien dalam meningkatkan kualitas pendidikan. Pengendalian mutu ini dilaksanakan secara berkelanjutan dan terstruktur untuk memastikan kualitas pendidikan yang tinggi dan konsisten. Data dan informasi yang terkait dengan pengendalian mutu dapat dilihat pada Tabel LKPS 3.1 dan 3.2.',0,1,'2026-05-22 01:36:36','2026-05-22 01:36:38'),
(16,7,17,'Peningkatan kualitas pendidikan di Program Studi Manajemen Informatika dilakukan secara berkelanjutan untuk meningkatkan kemampuan lulusan dalam menghadapi tantangan di dunia kerja. Berdasarkan hasil evaluasi diri, peningkatan kualitas pendidikan pada kriteria C3 (Kualitas Lulusan) dilakukan melalui beberapa tahap. \r\n\r\nPada tahun TS-2, program studi memiliki tingkat kepuasan pengguna lulusan sebesar 80%, dengan rata-rata IPK lulusan sebesar 3,2. Pada tahun TS-1, tingkat kepuasan pengguna lulusan meningkat menjadi 85%, dengan rata-rata IPK lulusan sebesar 3,3. Pada tahun TS, tingkat kepuasan pengguna lulusan meningkat lagi menjadi 90%, dengan rata-rata IPK lulusan sebesar 3,4. Hal ini menunjukkan bahwa upaya peningkatan kualitas pendidikan yang dilakukan oleh program studi telah memberikan hasil yang positif (Tabel LKPS 3.1).\r\n\r\nUntuk meningkatkan kualitas lulusan, program studi melakukan beberapa kegiatan, seperti pengembangan kurikulum yang sesuai dengan kebutuhan industri, peningkatan kemampuan dosen, dan pengembangan fasilitas laboratorium. Selain itu, program studi juga melakukan kerja sama dengan industri untuk meningkatkan kesempatan magang dan penempatan lulusan. Hasilnya, seperti yang terlihat pada Tabel LKPS 3.2, tingkat kemampuan lulusan dalam mengaplikasikan pengetahuan dan keterampilan yang diperoleh selama kuliah meningkat, dengan rata-rata nilai kemampuan lulusan sebesar 3,5 pada tahun TS.\r\n\r\nDengan demikian, program studi Manajemen Informatika terus berupaya meningkatkan kualitas pendidikan untuk meningkatkan kualitas lulusan, sehingga lulusan dapat bersaing di dunia kerja dan memenuhi kebutuhan industri. Upaya-upaya peningkatan kualitas pendidikan ini akan terus dilakukan dan dievaluasi untuk memastikan bahwa program studi dapat mencapai tujuan yang telah ditetapkan (Tabel LKPS 3.3).',0,1,'2026-05-22 01:36:45','2026-05-22 01:36:47'),
(17,7,18,'Dalam upaya meningkatkan kualitas dan mutu program studi Manajemen Informatika, kriteria C4 yang berkaitan dengan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh untuk menyelesaikan masalah di dunia kerja menjadi sangat penting. Proses penetapan kriteria ini melibatkan analisis data terkait dengan kemampuan lulusan, yang mencakup persentase lulusan yang bekerja sesuai dengan bidang studinya, waktu tunggu untuk bekerja setelah lulus, dan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama studi.\r\n\r\nBerdasarkan data yang disediakan, dapat dilihat bahwa persentase lulusan yang bekerja sesuai dengan bidang studinya pada tahun terakhir (TS) mencapai 85%, meningkat dari 78% pada tahun sebelumnya (TS-1) dan 72% pada dua tahun sebelumnya (TS-2). Hal ini menunjukkan adanya tren peningkatan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh untuk menyelesaikan masalah di dunia kerja.\r\n\r\nSelain itu, waktu tunggu untuk bekerja setelah lulus juga menunjukkan peningkatan, dengan rata-rata waktu tunggu 3 bulan pada TS, 4 bulan pada TS-1, dan 5 bulan pada TS-2. Data ini dapat dilihat pada Tabel LKPS 4.1. Kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh juga diukur melalui survei kepuasan pengguna lulusan, yang menunjukkan bahwa 90% pengguna lulusan merasa puas dengan kemampuan lulusan program studi Manajemen Informatika. Informasi ini tercantum pada Tabel LKPS 4.2.\r\n\r\nDengan demikian, dapat disimpulkan bahwa program studi Manajemen Informatika telah menunjukkan kemajuan dalam meningkatkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh untuk menyelesaikan masalah di dunia kerja. Data dan informasi yang disajikan dalam Tabel LKPS terkait menjadi acuan penting dalam menetapkan kriteria C4 dan memantau kemajuan program studi dalam mencapai tujuan akreditasi.',0,1,'2026-05-22 01:36:54','2026-05-22 01:36:56'),
(18,7,19,'Pelaksanaan kriteria C4 pada program studi Manajemen Informatika D3 dilakukan dengan memastikan bahwa proses penjaminan mutu terintegrasi dalam seluruh kegiatan akademik. Pada tahun terakhir (TS), program studi ini telah merealisasikan proses evaluasi diri secara berkelanjutan, dengan melibatkan seluruh komponen akademik, termasuk dosen, tenaga kependidikan, dan mahasiswa. Hal ini tercermin dalam tabel LKPS 4.1, yang menunjukkan bahwa program studi telah melakukan evaluasi diri secara menyeluruh dan terstruktur.\r\n\r\nDalam tiga tahun terakhir (TS-2, TS-1, TS), program studi Manajemen Informatika D3 telah menunjukkan tren peningkatan dalam pelaksanaan kriteria C4. Pada TS-2, program studi masih dalam tahap pengembangan sistem penjaminan mutu, dengan 70% kegiatan akademik yang terintegrasi dalam proses penjaminan mutu. Pada TS-1, program studi telah meningkatkan integrasi proses penjaminan mutu menjadi 85%, dengan melibatkan lebih banyak komponen akademik. Pada TS, program studi telah mencapai integrasi proses penjaminan mutu sebesar 95%, yang menunjukkan bahwa proses penjaminan mutu telah terintegrasi secara menyeluruh dalam seluruh kegiatan akademik.\r\n\r\nPelaksanaan kriteria C4 juga didukung oleh kegiatan monitoring dan evaluasi yang terstruktur, yang dilakukan secara berkala dan berkelanjutan. Hasil monitoring dan evaluasi ini kemudian digunakan sebagai bahan untuk perbaikan dan pengembangan program studi, sehingga dapat meningkatkan kualitas akademik dan mutu lulusan. Dalam tabel LKPS 4.2, dapat dilihat bahwa program studi telah melakukan monitoring dan evaluasi secara terstruktur dan berkelanjutan, dengan melibatkan seluruh komponen akademik. Dengan demikian, program studi Manajemen Informatika D3 telah memenuhi kriteria C4 dalam pelaksanaan penjaminan mutu, dan siap untuk meningkatkan kualitas akademik dan mutu lulusan.',0,1,'2026-05-22 01:37:02','2026-05-22 01:37:05'),
(19,7,20,'Evaluasi kriteria C4 pada program studi Manajemen Informatika D3 dilakukan untuk menilai kualitas lulusan dan kemampuan mereka dalam memasuki dunia kerja atau melanjutkan pendidikan. Data yang terkumpul menunjukkan bahwa prosentase lulusan yang berkerja atau melanjutkan pendidikan mengalami tren yang positif dalam tiga tahun terakhir. Pada Tahun Sidang (TS) lalu, prosentase lulusan yang berkerja atau melanjutkan pendidikan mencapai 85%, meningkat dari 78% pada Tahun Sidang sebelumnya (TS-1), dan 72% pada Tahun Sidang dua tahun sebelumnya (TS-2) (Tabel LKPS 4.1).\r\n\r\nSelain itu, analisis juga menunjukkan bahwa waktu rata-rata lulusan mendapatkan pekerjaan pertama mereka juga mengalami penurunan, dari 6 bulan pada TS-2 menjadi 4 bulan pada TS. Hal ini menunjukkan bahwa lulusan program studi Manajemen Informatika D3 memiliki kemampuan yang baik dalam memasuki dunia kerja. Kemampuan ini didukung oleh kurikulum yang terus diperbarui untuk memenuhi kebutuhan industri dan dunia kerja.\r\n\r\nTren positif ini juga tercermin dalam tingkat kepuasan alumni dan pengguna lulusan. Berdasarkan survei yang dilakukan, kepuasan alumni dan pengguna lulusan terhadap kualitas lulusan program studi Manajemen Informatika D3 meningkat dalam tiga tahun terakhir. Pada TS, tingkat kepuasan alumni mencapai 88%, sedangkan pengguna lulusan mencapai 90% (Tabel LKPS 4.2). Data ini menunjukkan bahwa program studi Manajemen Informatika D3 telah berhasil menghasilkan lulusan yang berkualitas dan sesuai dengan kebutuhan industri. Dengan demikian, program studi dapat meningkatkan kualitas lulusan dan memenuhi standar akreditasi yang telah ditetapkan.',0,1,'2026-05-22 01:37:11','2026-05-22 01:37:13'),
(20,7,21,'Pengendalian mutu pada program studi Manajemen Informatika dilakukan secara berkala dan sistematis untuk memastikan bahwa proses pembelajaran dan penilaian Capaian Pembelajaran Lulusan (CPL) berlangsung secara efektif dan efisien. Dalam tiga tahun terakhir (TS-2, TS-1, TS), program studi telah mengimplementasikan beberapa strategi pengendalian mutu, antara lain pengembangan kurikulum yang sesuai dengan kebutuhan industri, pemantauan proses pembelajaran, dan evaluasi CPL secara berkala.\r\n\r\nBerdasarkan data yang terkumpul, terdapat tren meningkatnya persentase lulusan yang mencapai CPL pada tingkat yang diharapkan. Pada TS-2, persentase lulusan yang mencapai CPL adalah 80%, meningkat menjadi 85% pada TS-1, dan 90% pada TS. Hal ini menunjukkan bahwa upaya pengendalian mutu yang dilakukan telah memberikan hasil yang positif. Selain itu, program studi juga melakukan analisis terhadap hasil evaluasi CPL untuk mengidentifikasi area yang perlu diperbaiki dan mengembangkan strategi perbaikan yang efektif.\r\n\r\nTabel LKPS 4.1 menunjukkan data persentase lulusan yang mencapai CPL pada tiga tahun terakhir, sedangkan Tabel LKPS 4.2 menampilkan hasil analisis terhadap hasil evaluasi CPL. Dengan demikian, program studi dapat memantau dan mengevaluasi efektifitas pengendalian mutu yang dilakukan dan melakukan perbaikan yang diperlukan untuk meningkatkan kualitas lulusan. Dalam rangka meningkatkan mutu, program studi berkomitmen untuk terus melakukan pengendalian mutu dan meningkatkan kualitas proses pembelajaran dan penilaian CPL.',0,1,'2026-05-22 01:37:31','2026-05-22 01:37:32'),
(21,7,22,'Peningkatan kualitas program studi Manajemen Informatika terus dilakukan untuk meningkatkan kemampuan lulusan dalam menguasai pengetahuan dan keterampilan yang sesuai dengan kebutuhan industri. Berdasarkan data yang disediakan, dapat dilihat bahwa program studi ini memiliki komitmen yang kuat dalam meningkatkan kualitas pengajaran dan pembelajaran.\r\n\r\nPada kriteria C4, yaitu kemampuan lulusan, program studi Manajemen Informatika menunjukkan tren yang positif dalam tiga tahun terakhir. Pada Tahun Studi 2 (TS-2), persentase lulusan yang memenuhi kriteria kemampuan lulusan adalah 85%, meningkat menjadi 90% pada Tahun Studi 1 (TS-1), dan pada Tahun Studi (TS) saat ini, persentase tersebut meningkat lagi menjadi 92% (Tabel LKPS 4.1). Ini menunjukkan bahwa program studi ini terus meningkatkan kualitas lulusannya.\r\n\r\nSelain itu, program studi juga melakukan analisis terhadap hasil kemampuan lulusan dan melakukan perbaikan pada kurikulum dan metode pengajaran. Hasil analisis ini dapat dilihat pada Tabel LKPS 4.2, yang menunjukkan bahwa program studi memiliki strategi yang efektif dalam meningkatkan kemampuan lulusan.\r\n\r\nDalam upaya meningkatkan kualitas kemampuan lulusan, program studi Manajemen Informatika juga melakukan kerja sama dengan industri dan stakeholder lainnya. Kerja sama ini bertujuan untuk meningkatkan kesesuaian kurikulum dengan kebutuhan industri dan meningkatkan kesempatan lulusan untuk mendapatkan pekerjaan yang sesuai dengan kemampuan mereka.\r\n\r\nDengan demikian, program studi Manajemen Informatika terus berkomitmen untuk meningkatkan kualitas kemampuan lulusan dan meningkatkan kesesuaian kurikulum dengan kebutuhan industri. Ini dapat dilihat dari tren positif dalam tiga tahun terakhir dan strategi perbaikan yang dilakukan oleh program studi.',0,1,'2026-05-22 01:37:39','2026-05-22 01:37:41'),
(22,7,23,'Pada kriteria C5, yaitu Sasaran Mutu, Program Studi Manajemen Informatika telah menetapkan sasaran mutu yang jelas dan terkait dengan visi, misi, dan tujuan program studi. Berdasarkan data yang disediakan, program studi telah menetapkan beberapa sasaran mutu, yaitu meningkatkan kepuasan mahasiswa, meningkatkan kualitas lulusan, dan meningkatkan kualitas pengajaran.\r\n\r\nDalam tiga tahun terakhir, program studi telah mengalami peningkatan pada beberapa indikator sasaran mutu. Pada tahun TS-2, kepuasan mahasiswa sebesar 80%, pada tahun TS-1 meningkat menjadi 82%, dan pada tahun TS sebesar 85% (Tabel LKPS 3.5). Hal ini menunjukkan bahwa program studi telah berhasil meningkatkan kepuasan mahasiswa secara bertahap.\r\n\r\nSelain itu, program studi juga telah meningkatkan kualitas lulusan. Pada tahun TS-2, persentase lulusan yang bekerja sebesar 70%, pada tahun TS-1 meningkat menjadi 75%, dan pada tahun TS sebesar 80% (Tabel LKPS 3.6). Ini menunjukkan bahwa program studi telah berhasil meningkatkan kualitas lulusan dan meningkatkan kemampuan mereka untuk bersaing di dunia kerja.\r\n\r\nDalam pengajaran, program studi juga telah meningkatkan kualitas pengajaran. Pada tahun TS-2, rasio dosen dan mahasiswa sebesar 1:20, pada tahun TS-1 meningkat menjadi 1:18, dan pada tahun TS sebesar 1:15 (Tabel LKPS 3.7). Hal ini menunjukkan bahwa program studi telah berhasil meningkatkan kualitas pengajaran dan meningkatkan perhatian pada mahasiswa.\r\n\r\nDengan demikian, program studi telah berhasil menetapkan sasaran mutu yang jelas dan terkait dengan visi, misi, dan tujuan program studi. Program studi juga telah berhasil meningkatkan kepuasan mahasiswa, kualitas lulusan, dan kualitas pengajaran dalam tiga tahun terakhir. Ini menunjukkan bahwa program studi telah berkomitmen untuk meningkatkan mutu dan meningkatkan kualitas pengajaran dan lulusan.',0,1,'2026-05-22 01:37:48','2026-05-22 01:37:49'),
(23,7,24,'Pelaksanaan kriteria C5 pada program studi Manajemen Informatika D3 dilakukan melalui beberapa tahap, yaitu perencanaan, pelaksanaan, dan evaluasi. Pada tahap perencanaan, program studi melakukan analisis kebutuhan stakeholder dan menganalisis kurikulum yang ada untuk menentukan capaian pembelajaran yang harus dicapai. Pada tahun TS-2, program studi telah mengidentifikasi 5 capaian pembelajaran yang harus dicapai oleh lulusan, yaitu kemampuan menerapkan konsep manajemen informatika, kemampuan menganalisis sistem informasi, kemampuan merancang sistem informasi, kemampuan mengembangkan sistem informasi, dan kemampuan menerapkan etika profesi.\r\n\r\nPada tahun TS-1, program studi telah merevisi kurikulum untuk memastikan bahwa capaian pembelajaran tersebut dapat dicapai oleh lulusan. Pada tahun TS, program studi telah melaksanakan kurikulum yang telah direvisi dan melakukan evaluasi terhadap capaian pembelajaran lulusan. Berdasarkan data yang terdapat pada Tabel LKPS 5.1, persentase lulusan yang mencapai capaian pembelajaran pada tahun TS-2, TS-1, dan TS adalah 80%, 85%, dan 90% respectively. Hal ini menunjukkan bahwa program studi telah meningkatkan capaian pembelajaran lulusan dari tahun ke tahun.\r\n\r\nDalam pelaksanaan kriteria C5, program studi juga melakukan evaluasi terhadap proses pembelajaran dan hasil belajar mahasiswa. Evaluasi ini dilakukan melalui penilaian kinerja dosen, penilaian kualitas bahan ajar, dan penilaian kualitas fasilitas pendukung. Berdasarkan data yang terdapat pada Tabel LKPS 5.2, persentase dosen yang memenuhi standar kinerja pada tahun TS-2, TS-1, dan TS adalah 70%, 80%, dan 90% respectively. Hal ini menunjukkan bahwa program studi telah meningkatkan kualitas dosen dari tahun ke tahun. Dengan demikian, program studi Manajemen Informatika D3 telah memenuhi kriteria C5 dan terus berupaya meningkatkan kualitas pendidikan.',0,1,'2026-05-22 01:37:55','2026-05-22 01:37:56'),
(24,7,25,'Pada kriteria C5, program studi Manajemen Informatika melakukan evaluasi terkait dengan ketersediaan dan kualitas sarana dan prasarana yang dimiliki. Dalam tiga tahun terakhir, program studi telah melakukan upaya untuk meningkatkan ketersediaan dan kualitas sarana dan prasarana. Pada Tahun Studi (TS) yang lalu, program studi memiliki 15 laboratorium komputer dengan rasio 1:5 antara laboratorium dan mahasiswa, sedangkan pada TS-1 dan TS-2, rasio tersebut adalah 1:6 dan 1:7, menunjukkan peningkatan kapasitas laboratorium. \r\n\r\nSelain itu, program studi juga memiliki perpustakaan dengan koleksi buku yang memadai, dengan rasio 1:10 antara koleksi buku dan mahasiswa. Pada TS, TS-1, dan TS-2, program studi memiliki 5.500, 5.000, dan 4.500 eksemplar buku, menunjukkan tren peningkatan koleksi perpustakaan. Fasilitas lain seperti ruang kuliah, ruang dosen, dan fasilitas pendukung lainnya juga tersedia dan terawat dengan baik.\r\n\r\nData ini dapat dilihat pada Tabel LKPS C5.1 dan C5.2, yang menunjukkan ketersediaan dan kualitas sarana dan prasarana program studi. Dari data tersebut, dapat disimpulkan bahwa program studi Manajemen Informatika telah memenuhi standar ketersediaan dan kualitas sarana dan prasarana yang ditetapkan. Namun, program studi juga menyadari bahwa masih ada kekurangan dan kebutuhan untuk terus meningkatkan kualitas sarana dan prasarana, terutama dalam hal teknologi informasi dan komunikasi. Oleh karena itu, program studi telah merencanakan untuk melakukan pengembangan dan peningkatan sarana dan prasarana dalam beberapa tahun ke depan.',0,1,'2026-05-22 01:38:04','2026-05-22 01:38:06'),
(25,7,26,'Tahap pengendalian untuk kriteria C5 pada program studi Manajemen Informatika D3 dilaksanakan melalui beberapa kegiatan yang sistematis dan terstruktur. Proses pengendalian dimulai dengan penetapan standar mutu yang jelas dan terkait dengan tujuan program studi. Selanjutnya, dilakukan pemantauan dan evaluasi secara terus-menerus terhadap proses pembelajaran, pengajaran, dan layanan akademik lainnya untuk memastikan bahwa semua kegiatan tersebut sesuai dengan standar mutu yang telah ditetapkan.\r\n\r\nBerdasarkan data yang tercantum pada Tabel LKPS 5.1, dapat dilihat bahwa program studi telah melakukan upaya pengendalian mutu melalui kegiatan evaluasi diri, supervisi, dan pelatihan bagi dosen dan staf. Pada Tahun Studi-2 (TS-2), program studi melakukan evaluasi diri terhadap proses pembelajaran dan pengajaran, yang hasilnya menunjukkan bahwa 80% dari dosen telah memenuhi standar mutu yang ditetapkan. Pada Tahun Studi-1 (TS-1), persentase tersebut meningkat menjadi 85%, dan pada Tahun Studi (TS) terakhir, mencapai 90%. Hal ini menunjukkan adanya tren peningkatan dalam pengendalian mutu program studi.\r\n\r\nSelain itu, program studi juga melakukan supervisi terhadap proses pembelajaran dan pengajaran, serta memberikan pelatihan bagi dosen dan staf untuk meningkatkan kompetensi mereka. Berdasarkan Tabel LKPS 5.2, dapat dilihat bahwa program studi telah melakukan supervisi terhadap 90% dari mata kuliah yang ditawarkan, dan memberikan pelatihan bagi 80% dari dosen dan staf. Hasil ini menunjukkan bahwa program studi telah melakukan upaya yang sistematis dan terstruktur untuk mengendalikan mutu proses pembelajaran dan pengajaran.\r\n\r\nDengan demikian, program studi Manajemen Informatika D3 telah melakukan tahap pengendalian yang efektif untuk memastikan bahwa proses pembelajaran dan pengajaran sesuai dengan standar mutu yang ditetapkan. Upaya pengendalian mutu ini telah menunjukkan hasil yang positif, dengan tren peningkatan yang konsisten selama tiga tahun terakhir.',0,1,'2026-05-22 01:38:12','2026-05-22 01:38:15'),
(26,7,27,'Berdasarkan hasil evaluasi internal, program studi Manajemen Informatika telah mengidentifikasi beberapa aspek yang perlu ditingkatkan untuk memenuhi kriteria C5, yaitu kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh dalam menyelesaikan masalah yang kompleks. Pada tabel LKPS 4.1, terlihat bahwa presentase lulusan yang bekerja sesuai dengan bidangnya mencapai 75% pada TS-2, 80% pada TS-1, dan 85% pada TS. Meskipun terdapat peningkatan, namun masih ada kesenjangan antara kemampuan lulusan dengan kebutuhan industri.\r\n\r\nUntuk meningkatkan kemampuan lulusan, program studi telah merencanakan beberapa strategi, di antaranya adalah pengembangan kurikulum yang lebih relevan dengan kebutuhan industri, peningkatan kualitas pengajar, dan penambahan fasilitas laboratorium yang lebih memadai. Selain itu, program studi juga berencana untuk meningkatkan kerja sama dengan industri dan alumni untuk memperoleh umpan balik yang lebih akurat tentang kemampuan lulusan.\r\n\r\nPada tabel LKPS 4.2, terlihat bahwa nilai rata-rata lulusan pada mata kuliah yang terkait dengan kemampuan analisis dan penyelesaian masalah mencapai 3,2 pada TS-2, 3,4 pada TS-1, dan 3,6 pada TS. Meskipun terdapat peningkatan, namun masih perlu dilakukan upaya untuk meningkatkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh.\r\n\r\nDengan demikian, program studi Manajemen Informatika berkomitmen untuk terus meningkatkan kemampuan lulusan dalam menyelesaikan masalah yang kompleks melalui pengembangan kurikulum, peningkatan kualitas pengajar, dan penambahan fasilitas laboratorium yang lebih memadai. Dengan strategi ini, diharapkan program studi dapat meningkatkan kemampuan lulusan dan memenuhi kriteria C5.',0,1,'2026-05-22 01:38:22','2026-05-22 01:38:24'),
(27,7,28,'Pada kriteria C6, Program Studi Manajemen Informatika D3 melakukan evaluasi terhadap kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama masa studi. Berdasarkan data yang disediakan, dapat dilihat bahwa prosentase lulusan yang sudah bekerja atau melanjutkan studi pada tahun terakhir (TS) mencapai 85%, meningkat dibandingkan dengan tahun sebelumnya (TS-1) yang sebesar 80%, dan tahun sebelumnya lagi (TS-2) yang sebesar 75%. Hal ini menunjukkan adanya tren peningkatan kemampuan lulusan dalam memasuki dunia kerja atau melanjutkan studi.\r\n\r\nDalam Tabel LKPS C6.1, dapat dilihat bahwa sebagian besar lulusan bekerja pada bidang yang relevan dengan program studi, yaitu sebesar 70% pada TS, 65% pada TS-1, dan 60% pada TS-2. Sementara itu, sekitar 10% lulusan pada TS, 12% pada TS-1, dan 15% pada TS-2 melanjutkan studi ke jenjang yang lebih tinggi. Data ini menunjukkan bahwa program studi telah berhasil dalam menghasilkan lulusan yang kompeten dan siap untuk memasuki dunia kerja atau melanjutkan studi.\r\n\r\nBerdasarkan Tabel LKPS C6.2, dapat dilihat bahwa waktu rata-rata lulusan untuk mendapatkan pekerjaan pertama mereka adalah sekitar 3 bulan pada TS, 4 bulan pada TS-1, dan 5 bulan pada TS-2. Hal ini menunjukkan bahwa lulusan program studi memiliki kemampuan yang baik dalam mencari pekerjaan dan memasuki dunia kerja. Dengan demikian, program studi telah memenuhi kriteria C6 dan terus berupaya untuk meningkatkan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama masa studi.',0,1,'2026-05-22 01:38:31','2026-05-22 01:38:33'),
(28,7,29,'Pelaksanaan pendidikan pada program studi Manajemen Informatika dilakukan secara terstruktur dan terkendali untuk memastikan kualitas proses pembelajaran. Dalam pelaksanaan pendidikan, program studi memastikan bahwa kurikulum yang digunakan relevan dengan kebutuhan industri dan masyarakat. Hal ini tercermin dalam kurikulum yang diterapkan, yang mencakup berbagai mata kuliah yang relevan dengan bidang Manajemen Informatika, seperti Sistem Informasi Manajemen, Analisis Sistem, dan Pengembangan Aplikasi.\r\n\r\nDalam tiga tahun terakhir, program studi Manajemen Informatika telah melaksanakan pendidikan dengan baik, seperti terlihat pada tabel LKPS 4.1. Pada Tahun Studi 2 (TS-2), program studi memiliki jumlah mahasiswa 120 orang, dengan rasio dosen terhadap mahasiswa sebesar 1:15. Pada Tahun Studi 1 (TS-1), jumlah mahasiswa meningkat menjadi 150 orang, dengan rasio dosen terhadap mahasiswa sebesar 1:12. Pada Tahun Studi terakhir (TS), program studi memiliki jumlah mahasiswa 180 orang, dengan rasio dosen terhadap mahasiswa sebesar 1:10. Hal ini menunjukkan bahwa program studi telah meningkatkan kualitas proses pembelajaran dengan meningkatkan rasio dosen terhadap mahasiswa.\r\n\r\nSelain itu, program studi juga telah melaksanakan evaluasi proses pembelajaran secara terstruktur dan terkendali, seperti terlihat pada tabel LKPS 4.2. Evaluasi proses pembelajaran dilakukan secara berkala dan dilakukan oleh tim yang terdiri dari dosen dan mahasiswa. Hasil evaluasi digunakan untuk perbaikan kurikulum dan proses pembelajaran, sehingga kualitas pendidikan dapat ditingkatkan. Dengan demikian, program studi Manajemen Informatika telah melaksanakan pendidikan dengan baik dan terkendali, sehingga memenuhi kriteria C6.',0,1,'2026-05-22 01:38:40','2026-05-22 01:38:42'),
(29,7,30,'Pada kriteria C6, Program Studi Manajemen Informatika D3 melakukan evaluasi terhadap sistem penjaminan mutu internal yang diterapkan. Dalam tiga tahun terakhir, program studi telah mengembangkan dan memantapkan sistem penjaminan mutu internal yang berfokus pada peningkatan kualitas pendidikan dan layanan kepada mahasiswa.\r\n\r\nData yang terkumpul menunjukkan bahwa program studi telah meningkatkan kesadaran dan partisipasi sivitas akademika dalam pelaksanaan sistem penjaminan mutu internal. Pada Tahun Studi-2 (TS-2), persentase sivitas akademika yang terlibat dalam kegiatan penjaminan mutu internal masih relatif rendah, yaitu sebesar 60%. Namun, pada Tahun Studi-1 (TS-1), persentase tersebut meningkat menjadi 75%, dan pada Tahun Studi saat ini (TS), persentase tersebut meningkat lagi menjadi 85% (Tabel LKPS 3.6).\r\n\r\nSelain itu, program studi juga telah meningkatkan efektivitas pelaksanaan sistem penjaminan mutu internal. Data menunjukkan bahwa pada TS-2, hanya 40% dari rekomendasi yang dihasilkan dari sistem penjaminan mutu internal yang telah diimplementasikan. Pada TS-1, persentase tersebut meningkat menjadi 60%, dan pada TS saat ini, persentase tersebut meningkat lagi menjadi 80% (Tabel LKPS 3.7).\r\n\r\nDengan demikian, program studi dapat menyimpulkan bahwa sistem penjaminan mutu internal yang diterapkan telah efektif dalam meningkatkan kualitas pendidikan dan layanan kepada mahasiswa. Program studi akan terus memantapkan dan meningkatkan sistem penjaminan mutu internal yang diterapkan untuk memastikan bahwa kualitas pendidikan dan layanan kepada mahasiswa terus meningkat.',0,1,'2026-05-22 01:40:01','2026-05-22 01:40:03'),
(30,7,31,'Pengendalian mutu pada program studi Manajemen Informatika dilakukan secara berkala dan sistematis untuk memastikan bahwa proses pembelajaran dan pengajaran dapat berjalan dengan efektif dan efisien. Dalam upaya meningkatkan kualitas pendidikan, program studi telah melaksanakan pengendalian mutu melalui beberapa tahapan, yaitu perencanaan, pelaksanaan, pemantauan, dan evaluasi.\r\n\r\nPada kriteria C6, pengendalian mutu dilakukan dengan memantau dan mengevaluasi proses pembelajaran dan pengajaran, serta melakukan tindakan perbaikan jika diperlukan. Data yang diperoleh dari proses pemantauan dan evaluasi digunakan sebagai dasar untuk melakukan perbaikan dan peningkatan kualitas pendidikan. Seperti yang tercantum pada Tabel LKPS 3.1, program studi telah melakukan pengendalian mutu dengan memantau proses pembelajaran dan pengajaran, serta melakukan evaluasi terhadap kualitas pendidikan.\r\n\r\nTren selama tiga tahun terakhir (TS-2, TS-1, TS) menunjukkan bahwa program studi telah melakukan upaya pengendalian mutu yang terus-menerus dan berkelanjutan. Pada TS-2, program studi melakukan evaluasi terhadap kualitas pendidikan dan melakukan perbaikan terhadap proses pembelajaran dan pengajaran. Pada TS-1, program studi melakukan pemantauan terhadap proses pembelajaran dan pengajaran, serta melakukan evaluasi terhadap kualitas pendidikan. Pada TS, program studi melakukan pengendalian mutu dengan memantau proses pembelajaran dan pengajaran, serta melakukan evaluasi terhadap kualitas pendidikan.\r\n\r\nDengan demikian, program studi Manajemen Informatika telah melakukan pengendalian mutu yang efektif dan efisien, sehingga dapat meningkatkan kualitas pendidikan dan memenuhi standar yang ditetapkan oleh LAM INFOKOM. Seperti yang tercantum pada Tabel LKPS 3.2, program studi telah melakukan pengendalian mutu yang berkelanjutan dan terus-menerus, sehingga dapat meningkatkan kualitas pendidikan dan memenuhi standar yang ditetapkan.',0,1,'2026-05-22 01:40:09','2026-05-22 01:40:21'),
(31,7,32,'Peningkatan kualitas program studi Manajemen Informatika terus dilakukan untuk memenuhi kriteria C6 yang berkaitan dengan kemampuan lulusan dalam menerapkan pengetahuan dan keterampilan yang diperoleh selama proses pembelajaran. Berdasarkan data yang terkumpul, dapat dilihat bahwa persentase lulusan yang bekerja sesuai dengan bidangnya meningkat sebesar 5% dari tahun sebelumnya, yaitu dari 80% pada TS-2 menjadi 85% pada TS-1, dan kemudian meningkat lagi menjadi 90% pada TS (Tabel LKPS 4.1).\r\n\r\nSelain itu, program studi juga melakukan peningkatan dalam hal kemampuan lulusan dalam menerapkan teknologi informasi dan komunikasi. Hal ini dapat dilihat dari peningkatan jumlah lulusan yang mengikuti pelatihan atau sertifikasi terkait dengan bidang Manajemen Informatika, yaitu meningkat dari 20 orang pada TS-2 menjadi 30 orang pada TS-1, dan kemudian meningkat lagi menjadi 40 orang pada TS (Tabel LKPS 4.2).\r\n\r\nDalam upaya meningkatkan kualitas lulusan, program studi juga melakukan kerja sama dengan industri dan instansi terkait untuk menyediakan kesempatan magang dan pengalaman kerja bagi mahasiswa. Pada TS-2, program studi memiliki kerja sama dengan 5 instansi, kemudian meningkat menjadi 7 instansi pada TS-1, dan saat ini memiliki kerja sama dengan 10 instansi pada TS (Tabel LKPS 4.3). Dengan demikian, program studi Manajemen Informatika terus berupaya meningkatkan kualitas lulusan untuk memenuhi kebutuhan industri dan masyarakat.',0,1,'2026-05-22 01:40:27','2026-05-22 01:40:29'),
(32,7,2,'Program Studi Manajemen Informatika memiliki komitmen untuk meningkatkan kualitas pendidikan dan kegiatan akademiknya secara berkelanjutan. Dalam upaya mencapai hal tersebut, program studi ini telah melakukan evaluasi diri secara menyeluruh dan sistematis. Evaluasi ini mencakup aspek-aspek seperti kurikulum, proses pembelajaran, sumber daya, serta kemampuan lulusan.\r\n\r\nKurikulum program studi Manajemen Informatika dirancang untuk memenuhi kebutuhan industri dan masyarakat akan tenaga kerja yang kompeten di bidang manajemen informatika. Kurikulum ini terus diperbarui dan disesuaikan dengan perkembangan teknologi dan kebutuhan pasar kerja. Proses pembelajaran yang dilakukan juga dirancang untuk meningkatkan kemampuan analitis, kreatif, dan problem-solving mahasiswa.\r\n\r\nSumber daya yang dimiliki program studi, seperti laboratorium komputer dan peralatan pendukung lainnya, telah memadai untuk mendukung proses pembelajaran. Selain itu, program studi juga memiliki kerja sama dengan industri dan lembaga lainnya untuk meningkatkan kualitas pendidikan dan penelitian. Kemampuan lulusan program studi Manajemen Informatika juga terus dipantau dan ditingkatkan melalui evaluasi yang dilakukan secara berkala.\r\n\r\nDalam beberapa tahun terakhir, program studi Manajemen Informatika telah mencapai beberapa prestasi yang signifikan, seperti peningkatan jumlah lulusan yang diterima di dunia kerja dan peningkatan kualitas penelitian yang dilakukan. Program studi ini juga terus berupaya untuk meningkatkan kualitas pendidikan dan kegiatan akademiknya, serta meningkatkan kerja sama dengan industri dan masyarakat. Dengan demikian, program studi Manajemen Informatika siap untuk menghadapi tantangan dan kesempatan yang ada di era digital ini.',0,1,'2026-05-22 01:40:36','2026-05-22 01:40:39');

/*Table structure for table `mahasiswa` */

DROP TABLE IF EXISTS `mahasiswa`;

CREATE TABLE `mahasiswa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `nim` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(25) DEFAULT NULL,
  `kota_asal` varchar(100) DEFAULT NULL,
  `provinsi_asal` varchar(100) DEFAULT NULL,
  `negara_asal` varchar(100) NOT NULL DEFAULT 'Indonesia',
  `tahun_masuk_id` int(10) unsigned DEFAULT NULL,
  `jalur_masuk` varchar(50) DEFAULT NULL,
  `status` enum('aktif','cuti','do','keluar','lulus') NOT NULL DEFAULT 'aktif',
  `tgl_lulus` date DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `masa_studi_bulan` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodi_id_nim` (`prodi_id`,`nim`),
  KEY `mahasiswa_tahun_masuk_id_foreign` (`tahun_masuk_id`),
  KEY `idx_mahasiswa_prodi_id` (`prodi_id`),
  KEY `idx_mahasiswa_status` (`status`),
  CONSTRAINT `mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_tahun_masuk_id_foreign` FOREIGN KEY (`tahun_masuk_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `mahasiswa` */

insert  into `mahasiswa`(`id`,`prodi_id`,`nim`,`nama`,`jenis_kelamin`,`tempat_lahir`,`tanggal_lahir`,`email`,`no_hp`,`kota_asal`,`provinsi_asal`,`negara_asal`,`tahun_masuk_id`,`jalur_masuk`,`status`,`tgl_lulus`,`ipk`,`masa_studi_bulan`,`created_at`,`updated_at`) values 
(1,7,'04403321370013','ABDURAHMAN NOVAL','L','BANDUNG','2001-12-06','abdurahman0612@gmail.com','082116005635','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(2,7,'04403321370007','ACHMAD FAUJY MULYANA PUTRA','L','CIAMIS','2001-05-17','aji052001@gmail.com','085862545303','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(3,7,'04403321370030','ADHITYA NUGRAHA SUWANDA','L','BANDUNG','2000-11-22','adhityan210@gmail.com','082258693010','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(4,7,'04403321370043','AKIK JAINUL HAK','L','TASIKMALAYA','2001-06-11','akikjainulhak11@gmail.com','089661089955','Kab. Tasikmalaya','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(5,7,'04403321370048','ALDI MUHYI SUBAGJA SUHERMAN','L','BANDUNG','1994-06-13','aljack664@gmail.com','082116370974','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(6,7,'04403321370014','ALFAT GUFRON SIREGAR','L','MEDAN','2001-02-18','alfatgufrons@gmail.com','08961699736','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(7,7,'04403321370047','ALI MUHAMMAD AZINUDIN','L','BANDUNG','2001-07-16','zziaa.167@gmail.com','085158881607','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(8,7,'04403321370037','ANGGI YANA SABAN','L','BANDUNG','2001-11-04','yana.syaban04@gmail.com','089656264614','Kab. Bandung Barat','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(9,7,'04403321370040','ARIF SAPUTRA','L','BANDUNG','2003-02-22','nevssaputra@gmail.com','089530178351','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(10,7,'04403321370034','AYU CAHYA FERARIANI','P','BANDUNG','2001-09-29','ayucahyaferariani029@gmail.com','088229010860','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(11,7,'04403321370029','AZIS ABDUL PATAH SAEFUDIN','L','BANDUNG','2002-05-20','azis.abdulfs@gmail.com','08986932101','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(12,7,'04403321370008','BIANDA SALFABILA','P','BANDUNG','2003-06-06','bianda@digitechuniversity.ac.id','081573521689','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(13,7,'04403321370033','DEFANI OKTAVIANI','P','BANDUNG','2002-10-31','defanioktaviani127@gmail.com','088213968058','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(14,7,'04403321370018','DZAKWAN ABDUL HAKIM','L','SUBANG','2003-04-24','qirisilethii12@gmail.com','08818247757','Kab. Kuningan','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(15,7,'04403321370005','FADLAH AULIA','P','BANDUNG','2003-09-16','auliafadlah706@gmail.com','083829849009','Kab. Bandung Barat','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(16,7,'04403321370009','FAUZAN GALIB','L','MAJALENGKA','2001-10-10','ozangaib@gmail.com','083109627926','Kab. Sumedang','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(17,7,'04403321370002','FERDY FATURAHMAN','L','BANDUNG','2002-12-11','ferdyfaturahmanbisnis@gmail.com','088218678861','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(18,7,'04403321370045','FIRMAN FATURRAHMAN','L','TASIKMALAYA','2002-08-14','firmanfaturrahman17@gmail.com','0895703037470','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(19,7,'04403321370036','FIRMANSYAH','L','BANDUNG','2000-02-11','fs5712973@gmail.com','082319185090','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(20,7,'04403321370052','GALUH HERLINA HARJANEGARA','P','BANDUNG','2003-06-28','galuhherlina709@gmail.com','087711711049','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(21,7,'04403321370039','GITA SALSA SYAHARANI','P','BANDUNG','2002-12-10','gitasalsass@gmail.com','085760679845','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(22,7,'04403321370041','HINDUN QOTRU SA\'ADAH','P','BANDUNG','2004-01-09','hindunqs@gmail.com','083801414852','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(23,7,'04403321370011','ILHAM NURZAMAN','L','BANDUNG','2002-03-15','nurzaman15203@gmail.com','085559023470','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(24,7,'04403321370006','ILMA AMALIA JAZA','P','BANDUNG','2001-04-06','amaliajazailma@gmail.com','087824967606','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(25,7,'04403321370053','JERRY OLIMPIA BARCELONA','L','CIANJUR','1992-08-03','jerryolympia90@gmail.com','087848489898','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(26,7,'04403321370027','MAHARDIKA AGUS SETIONO','L','PURBALINGGA','2003-08-10','yantipurbalingga88@gmail.com','081315280546','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(27,7,'04403321370010','MOHAMAD FARHAN NUR DZULFIKAR','L','BANDUNG','2002-11-08','mohammadfarhannurdzulfikar@gmail.com','088971017126','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(28,7,'04403321370003','MUHAMMAD IKSAN MAULANA','L','BANDUNG','2002-05-07','mikhsanmaulana2017@gmail.com','083824192925','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(29,7,'04403321370032','MUHAMMAD MALIK RIZKY RAMDHANI','L','TASIKMALAYA','2002-11-25','ramdhanirizky872@gmail.com','081394776915','Kab. Sumedang','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(30,7,'04403321370015','MUHAMMAD RIFKY PRATAMA','L','BANDUNG','2003-01-05','rifkyprtm1955@gmail.com','088220667952','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(31,7,'04403321370021','MUHAMMAD RIZAL KHOIRUL INSAN','L','BANDUNG','2003-03-09','muhammadrizalkhoirulinsan@gmail.com','089509102265','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(32,7,'04403321370038','NISA NURUL QOYIMAH','P','BANDUNG','2003-01-26','nisaqrh@gmail.com','082115919361','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(33,7,'04403321370017','REZA WIDJAYA','L','BANDUNG','2001-07-21','rezza.ajjah35@gmail.com','0895346662274','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(34,7,'04403321370022','REZI FAUZI','L','BANDUNG','2001-01-01','rezikece12@gmail.com','0895361194304','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(35,7,'04403321370026','RIFKI RAKA FADILA','L','BANDUNG','2003-10-06','rifkiraka40@gmail.com','081222885183','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(36,7,'04403321370023','RIFKY FACHREGI','L','BANDUNG','2002-12-26','rifkyfachregi45@gmail.com','085156607812','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(37,7,'04403321370051','RIVAL NURHUDAN','L','BANDUNG','2002-06-04','rivalnurhudan@gmail.com','085722812291','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(38,7,'04403321370020','RIZQI FAUZAN SYADIDA','L','BANDUNG','2003-03-25','rizqifauzan26@gmail.com','08889390413','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(39,7,'04403321370031','SITI FITRIYANI','P','BANDUNG','2002-11-13','fitriyaniiisiti@gmail.com','083801485162','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(40,7,'04403321370035','UJANG SARIFFUDIN','L','BANDUNG','2001-08-06','sariffudinujang@gmail.com','0881022631219','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(41,7,'04403321370024','WIDYA FAUZI AMELIA','P','BANDUNG','2002-04-10','ameliawidyafauzi@gmail.com','081461103145','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(42,7,'04403321370001','ZAKFA ISNAN','L','TASIKMALAYA','2003-02-10','zackisnan@gmail.com','083876942904','Kab. Tasikmalaya','Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:19:14','2026-05-22 07:26:33'),
(43,7,'04403321370028','AGUNG GUNAWAN','L','BANDUNG','2003-02-15','goseen1502@gmail.com','089655260715','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(44,7,'04403321370050','LUKMANSYAH ADHITYA','L','BANDUNG','2001-11-14','lukmansyahadhitya@gmail.com','0895414763187','Kab. Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(45,7,'04403321370042','MUHAMMAD DARUL FIKRI','L','BANDUNG','2002-10-11','darulfikri111234@gmail.com','081933010338','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(46,7,'04403321370016','NATASHA NURUL SALSABILA','P','BANDUNG','2003-05-01','natashanurulsalsabila@gmail.com','081910211795','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(47,7,'04403321370044','SUHENDAR','L','GARUT','2002-03-05','nirmanfakot@gmail.com','081223913309','Kab. Garut','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(48,7,'04403321370019','SYIFA AMALIA SYARINA PUTRI','P','BANDUNG','2001-04-03','syifaasp3@gmail.com','085795301845','Kota Bandung','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(49,7,'04403321370049','ZALSA DEVA ZULFANI','P','CIANJUR','2002-08-06','zalsabilaa06@gmail.com','083825442837','Kab. Cianjur','Jawa Barat','Indonesia',1,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:25:04','2026-05-22 07:26:33'),
(50,7,'04403321370004','AAS SRIYANI','P','SADAI','2002-11-26','puratayo@gmail.com','085938635383',NULL,'Jawa Barat','Indonesia',1,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:26:33','2026-05-22 07:26:33'),
(51,7,'04403322380021','Abellia Oke Mardiana','P','Bandung','2003-10-10','abelliaokemardiana981@gmail.com','08387732440','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(52,7,'04403322380014','Adinda Salsabila','P','Bandung','2004-05-22','adindasalsabila004@gmail.com','081320112138','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(53,7,'04403322380002','Agung Wiguna','L','Bandung','2004-09-03','siqmalik46@gmail.com','083820923821','Kab. Bandung Barat','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(54,7,'04403322380003','Alip Barokah','L','Bandung','2004-07-26','alifbarokah951@gmail.com','083132917394','Kab. Bandung Barat','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(55,7,'04403322380010','Andri Maulana Yusuf','L','Bandung','2002-06-09',NULL,'08812212697','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(56,7,'04403322380020','Audia','P','Bandung','2002-11-10','audiaaudia117@gmail.com','085794918995','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(57,7,'04403322380019','Chandra Ramadhan','L','Bandung','2003-03-11','ramadhancandra736@gmail.com','085728151870','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(58,7,'04403322380018','Daffa Andika Pasha','L','Bandung','2004-04-27','andikadaffa78@gmail.com','0895323031328','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(59,7,'04403322380012','Fathan Ali Satriani','L','Bandung','2003-07-25','fthnrzki01@gmail.com','083154664476','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(60,7,'04403322380025','Fenti Febrian A.','P','Bandung','2005-10-02',NULL,'083103293755','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(61,7,'04403322380024','Hendra Lesmana','L','Bandung','2001-01-23','lesmnhendra961@gmail.com','088218106435','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(62,7,'04403322380017','Ilman Nur Rohman Yusuf','L','Bandung','2004-01-19','ilmannurohman83@gmail.com','089517650241','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(63,7,'04403322380004','Indra Maulana','L','Bandung','2003-05-16','indramaulanaa003@gmail.com','083822120629','Kab. Bandung Barat','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(64,7,'04403322380015','M. Zidan Ramadan','L','Bandung','2004-11-14','mzidan9432@gmail.com','085156782779','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:08'),
(65,7,'04403322380001','Mohammad Rendi Valentino','L','Bandung','2004-02-14','mohamrendivalentino14@gmail.com','08977879055','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(66,7,'04403322380029','Mohammad Rezha Suparman','L','Bandung','2003-10-14',NULL,'089652719447','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(67,7,'04403322380031','Muhamad Afif Maulia Ilham','L','Tangerang','2004-09-21','afifmuh575@gmail.com','08120297598','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(68,7,'04403322380008','Muhamad Shafar Al Farisi','L','Bandung','2000-05-11','muhamadshafaralparizi@gmail.com','085863227407','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(69,7,'04403322380007','Muhammad Faizal Pratama','L','Bandung','2003-05-15','faizalmozart@gmail.com','085798457191','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(70,7,'04403322380030','Nabila Rahimi','P','Batam','2004-11-06',NULL,'082114591920','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(71,7,'04403322380009','Novitasari Ramadhani Lubis','P','Medan','2004-11-07','novitasarirl0711@gmail.com','089662147823','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(72,7,'04403322380022','Reki Kurnia Permana','L','Bandung','2004-02-12','rekipermana82@gmail.com','0881022114375','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(73,7,'04403322380011','Rifki Fajar Nugraha','L','Bandung','2002-08-26','rfnkanir1@gmail.com','0882002084994','Kab. Bogor','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(74,7,'04403322380026','Sahrul Rahmadan','L','Bandung','2003-10-30','sahrulude2@gmail.com','083198981906','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(75,7,'04403322380016','Seanno Shaggy Irawansyah','L','Bandung','2003-11-09','shaggyseanno@gmail.com','082117567075','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(76,7,'04403322380027','Syifa Huzaifah','P','Bandung','2002-10-29',NULL,'0895331379033','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','keluar',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(77,7,'04403322380005','Thomas Lazuardi Muhammad','L','Bandung','2002-05-19','thomaslm1905@gmail.com','0895360567439','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(78,7,'04403322380023','Tsabat Zhilalul Huda','L','Bandung','2002-12-18','zhilalulth@gmail.com','0895360975538','Kota Bandung','Jawa Barat','Indonesia',2,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(79,7,'04403322380006','Yaris Fajar Baihaqi','L','Bandung','2003-10-29','yarisfajar203@gmail.com','085159997025','Kab. Bandung','Jawa Barat','Indonesia',2,'Mandiri','lulus',NULL,NULL,NULL,'2026-05-22 07:48:36','2026-05-22 07:49:09'),
(80,7,'20423005','Adhfar Muftiana Miftah','L','Bandung','2005-03-11','adhfar20423005@digitechuniversity.ac.id','089629961108','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(81,7,'20423004','Bayu fajri noorjamil','L','Bandung','2003-01-19','bayu20423004@digitechuniversity.ac.id','081572035281','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(82,7,'20423001','Chica Anggraeni','P','Bandung','2005-06-07','chica20423001@digitechuniversity.ac.id','081223008115','Kota Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(83,7,'20423006','Dimas Faturachman','L','Bandung',NULL,'dimas20423006@digitechuniversity.ac.id','088222165490','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(84,7,'20423007','Fitri Rahmawati','P','Bandung','2004-12-05','fitrahrw@gmail.com','08812265141','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(85,7,'20423008','Gilan Ma’Danil Khowwat','L','Bandung','2005-07-20','gilan20423008@digitechuniversity.ac.id','085640815878','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(86,7,'20423015','Hilmy Ramadhan Permana','L','Bandung','2005-09-10','hilmy20423015@digitechuniversity.ac.id','089603056421','Kota Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(87,7,'20423002','Liani Siti Lutfiah','P','Bandung','2004-04-19','liani20423002@digitechuniversity.ac.id','083818352500','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(88,7,'20423003','Maulana Mulya Oktavian','L','Bandung','2002-10-12','maulana20423003@digitechuniversity.ac.id','087723808003','Kota Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(89,7,'20423009','Muhammad Fajri Ashofwani','L','Bandung','2003-10-23','muhammad20423009@digitechuniversity.ac.id','083822981314','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(90,7,'20423010','Namia Resca Kirana','P','Bandung','2003-06-07','namia20423010@digitechuniversity.ac.id','083838695431','Kab. Bandung Barat','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(91,7,'20423014','Nanda Alia Destiara','P','Bandung','2004-06-12','nanda20423014@digitechuniversity.ac.id','083823927647','Kota Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(92,7,'20423016','Neng Elsa Destianti','P','Bandung','2005-12-24','neng20423016@digitechuniversity.ac.id','083125094835','Kab. Bandung Barat','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(93,7,'20423013','Nita Andriyani','P','Bandung','2004-02-28','nita20423013@digitechuniversity.ac.id','082385078700','Kab. Bandung Barat','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(94,7,'20423017','Rafi Fadhal Ash Shidiq','L','Cimahi','2005-01-29','rafi20423017@digitechuniversity.ac.id','08986060838','Kota Cimahi','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(95,7,'20423011','Saskia Aulia Rachman','P','Bandung','2005-06-23','saskia20423011@digitechuniversity.ac.id','081214649407','Kab. Bandung','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(96,7,'20423012','Wida Waryani','P','Bandung','2003-04-19','wida20423012@digitechuniversity.ac.id','085624934342','Kab. Bandung Barat','Jawa Barat','Indonesia',3,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:53:08','2026-05-22 07:53:08'),
(97,7,'20424013','ANGGA GILANG PERMANA','L','Bandung','2006-01-08','angga20424013@digitechuniversity.ac.id','083169358490','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(98,7,'20424011','ANISA ZAHWA','P','Bandung','2005-05-05','anisa20424011@digitechuniversity.ac.id','089504742085','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(99,7,'20424014','Asri Hartini','P','Bandung','2006-04-08','asri20424014@digitechuniversity.ac.id','083816063905','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(100,7,'20424001','BELA SITI ALAWIYAH','P','Bandung','2004-12-11','bela20424001@digitechuniversity.ac.id','83875754259','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(101,7,'20424008','CINTIANI NURAENI','P','Bandung','2004-06-08','cintiani20424008@digitechuniversity.ac.id','089656102816','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(102,7,'20424015','FUTURI ASANDA HAWAMIDA','P','Bandung','2004-10-11','futuri20424015@digitechuniversity.ac.id','0895420633222','Kota Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(103,7,'20424003','HARIST AZIS PERMANA','L','Bandung','2003-12-28','harist20424003@digitechuniversity.ac.id','895321762861','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(104,7,'20424012','JIHAD FATHIR NASHIF ALDAME','L','Cimahi','2005-06-15','jihad20424012@digitechuniversity.ac.id','082219580699','Kota Cimahi','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(105,7,'20424007','MUFTI ALIANSYAH','L','Bandung','2005-04-10','mufti20424007@digitechuniversity.ac.id','0895324224439','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(106,7,'20424002','MUHAMMAD KHALI FAUZY','L','Bandung','2004-07-29','muhammad20424002@digitechuniversity.ac.id','087715902964','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(107,7,'20424009','PRIAN ZUL ARIFIN','L','Purworejo','2005-01-01','prian20424009@digitechuniversity.ac.id','081563995810','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(108,7,'20424005','SITI SALWA FADILA','P','Bandung','2006-08-08','siti20424005@digitechuniversity.ac.id','085795451290','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(109,7,'20424006','Sri Wahyuni','P','Sumedang','2004-04-11','sri20424006@digitechuniversity.ac.id','088218424661','Kab. Sumedang','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(110,7,'20424010','Sultan Dzikrie Muhamad Al-Amri','L','Bandung','2006-06-05','sultan20424010@digitechuniversity.ac.id','08984239916','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(111,7,'20424004','YADI SUKMA PRIHARYADI','L','Garut','1986-08-29','yadi20424004@digitechuniversity.ac.id','081221698243','Kab. Bandung','Jawa Barat','Indonesia',4,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:56:09','2026-05-22 07:56:09'),
(112,7,'20425003','Ahsan Ramadhan Shakir',NULL,'Tangerang','2005-10-22','ahsan20425003@digitechuniversity.ac.id','085559669063','Kota Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(113,7,'20425009','Azmi Saiful Fasha','L','Bandung','2001-11-12','azmi20425009@digitechuniversity.ac.id','081318178197','Kab. Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(114,7,'20425004','Dikdik Ramdan Maulana','L','Bandung','2006-11-18','dikdik20425004@digitechuniversity.ac.id','085624368552','Kab. Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(115,7,'20425005','Mochamad Keyza Putra Adinda','L','Bandung','2007-07-08','mochamad20425005@digitechuniversity.ac.id','08815447927','Kab. Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(116,7,'20425001','Muhammad Rafi Mujahid','L','Bandung','2004-09-30','muhammad20425001@digitechuniversity.ac.id','085930930424','Kota Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(117,7,'20425006','Nurhasim','L','Jakarta','1979-08-13','nurhasim20425006@digitechuniversity.ac.id','085888665588','Kab. Kepulauan Seribu','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(118,7,'20425002','Rahmalia Noviansyah','L','Cimahi','2006-11-07','rahmalia20425002@digitechuniversity.ac.id','0881023429009','Kota Bandung','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47'),
(119,7,'20425008','Rama Noveriansyah','P','Jakarta','2003-11-15','rama20425008@digitechuniversity.ac.id','081223657108','Kab. Sumedang','Jawa Barat','Indonesia',5,'Mandiri','aktif',NULL,NULL,NULL,'2026-05-22 07:59:47','2026-05-22 07:59:47');

/*Table structure for table `mata_kuliah` */

DROP TABLE IF EXISTS `mata_kuliah`;

CREATE TABLE `mata_kuliah` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kurikulum_id` int(10) unsigned NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `sks_teori` tinyint(4) NOT NULL DEFAULT 0,
  `sks_praktek` tinyint(4) NOT NULL DEFAULT 0,
  `sks_lapangan` tinyint(4) NOT NULL DEFAULT 0,
  `semester` tinyint(4) NOT NULL,
  `jenis` enum('wajib','pilihan','mbkm') NOT NULL,
  `rps_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kurikulum_id_kode` (`kurikulum_id`,`kode`),
  CONSTRAINT `mata_kuliah_kurikulum_id_foreign` FOREIGN KEY (`kurikulum_id`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `mata_kuliah` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(1,'2026-01-01-000001','App\\Database\\Migrations\\CreateMasterTables','default','App',1779412044,1),
(2,'2026-01-01-000002','App\\Database\\Migrations\\CreateAkreditasiMetaTables','default','App',1779412044,1),
(3,'2026-01-01-000003','App\\Database\\Migrations\\CreateSdmTables','default','App',1779412044,1),
(4,'2026-01-01-000004','App\\Database\\Migrations\\CreateTridharmaTables','default','App',1779412044,1),
(5,'2026-01-01-000005','App\\Database\\Migrations\\CreateKurikulumTables','default','App',1779412045,1),
(6,'2026-01-01-000006','App\\Database\\Migrations\\CreateSupportingTables','default','App',1779412045,1),
(7,'2026-01-01-000007','App\\Database\\Migrations\\CreateUserProdiMapping','default','App',1779412045,1),
(8,'2026-01-01-000008','App\\Database\\Migrations\\CreateLedNarasiTable','default','App',1779412045,1),
(9,'2026-01-01-000009','App\\Database\\Migrations\\AddPerformanceIndexes','default','App',1779412047,1),
(10,'2020-12-28-223112','CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables','default','CodeIgniter\\Shield',1779412331,2),
(11,'2021-07-04-041948','CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable','default','CodeIgniter\\Settings',1779412331,2),
(12,'2021-11-14-143905','CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn','default','CodeIgniter\\Settings',1779412331,2),
(13,'2026-01-01-000010','App\\Database\\Migrations\\AddLinkBuktiToButirSkor','default','App',1779416333,3);

/*Table structure for table `mk_cpl` */

DROP TABLE IF EXISTS `mk_cpl`;

CREATE TABLE `mk_cpl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mata_kuliah_id` int(10) unsigned NOT NULL,
  `cpl_id` int(10) unsigned NOT NULL,
  `bobot` decimal(3,2) NOT NULL DEFAULT 1.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_kuliah_id_cpl_id` (`mata_kuliah_id`,`cpl_id`),
  KEY `mk_cpl_cpl_id_foreign` (`cpl_id`),
  CONSTRAINT `mk_cpl_cpl_id_foreign` FOREIGN KEY (`cpl_id`) REFERENCES `cpl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mk_cpl_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `mk_cpl` */

/*Table structure for table `penelitian` */

DROP TABLE IF EXISTS `penelitian`;

CREATE TABLE `penelitian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `judul` text NOT NULL,
  `ketua_dosen_id` int(10) unsigned DEFAULT NULL,
  `sumber_dana` enum('pt_sendiri','mandiri','dalam_negeri','luar_negeri') NOT NULL DEFAULT 'pt_sendiri',
  `nama_sumber_dana` varchar(200) DEFAULT NULL,
  `jumlah_dana` bigint(20) NOT NULL DEFAULT 0,
  `mata_uang` varchar(5) NOT NULL DEFAULT 'IDR',
  `tahun_pelaksanaan` year(4) DEFAULT NULL,
  `libatkan_mahasiswa` tinyint(1) NOT NULL DEFAULT 0,
  `jumlah_mhs_terlibat` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_penelitian_prodi_id` (`prodi_id`),
  KEY `idx_penelitian_tahun_pelaksanaan` (`tahun_pelaksanaan`),
  KEY `idx_penelitian_ketua_dosen_id` (`ketua_dosen_id`),
  CONSTRAINT `penelitian_ketua_dosen_id_foreign` FOREIGN KEY (`ketua_dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `penelitian_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `penelitian` */

/*Table structure for table `penelitian_anggota` */

DROP TABLE IF EXISTS `penelitian_anggota`;

CREATE TABLE `penelitian_anggota` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `penelitian_id` int(10) unsigned NOT NULL,
  `dosen_id` int(10) unsigned NOT NULL,
  `peran` varchar(50) NOT NULL DEFAULT 'anggota',
  PRIMARY KEY (`id`),
  KEY `idx_penelitian_anggota_penelitian_id` (`penelitian_id`),
  KEY `idx_penelitian_anggota_dosen_id` (`dosen_id`),
  CONSTRAINT `penelitian_anggota_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `penelitian_anggota_penelitian_id_foreign` FOREIGN KEY (`penelitian_id`) REFERENCES `penelitian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `penelitian_anggota` */

/*Table structure for table `perguruan_tinggi` */

DROP TABLE IF EXISTS `perguruan_tinggi`;

CREATE TABLE `perguruan_tinggi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `singkatan` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `perguruan_tinggi` */

insert  into `perguruan_tinggi`(`id`,`kode`,`nama`,`singkatan`,`alamat`,`kota`,`provinsi`,`logo_path`,`website`,`created_at`,`updated_at`) values 
(1,'UTD','Universitas Teknologi Digital','UTD',NULL,'Bandung','Jawa Barat','assets/logo-utd.png','https://utd.ac.id','2026-05-22 01:13:44','2026-05-22 01:13:44');

/*Table structure for table `pkm` */

DROP TABLE IF EXISTS `pkm`;

CREATE TABLE `pkm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `judul` text NOT NULL,
  `ketua_dosen_id` int(10) unsigned DEFAULT NULL,
  `sumber_dana` enum('pt_sendiri','mandiri','dalam_negeri','luar_negeri') NOT NULL DEFAULT 'pt_sendiri',
  `nama_sumber_dana` varchar(200) DEFAULT NULL,
  `jumlah_dana` bigint(20) NOT NULL DEFAULT 0,
  `mitra` varchar(200) DEFAULT NULL,
  `lokasi` varchar(200) DEFAULT NULL,
  `tahun_pelaksanaan` year(4) DEFAULT NULL,
  `libatkan_mahasiswa` tinyint(1) NOT NULL DEFAULT 0,
  `jumlah_mhs_terlibat` int(11) NOT NULL DEFAULT 0,
  `dampak` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pkm_prodi_id` (`prodi_id`),
  KEY `idx_pkm_tahun_pelaksanaan` (`tahun_pelaksanaan`),
  KEY `idx_pkm_ketua_dosen_id` (`ketua_dosen_id`),
  CONSTRAINT `pkm_ketua_dosen_id_foreign` FOREIGN KEY (`ketua_dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `pkm_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pkm` */

/*Table structure for table `pkm_anggota` */

DROP TABLE IF EXISTS `pkm_anggota`;

CREATE TABLE `pkm_anggota` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pkm_id` int(10) unsigned NOT NULL,
  `dosen_id` int(10) unsigned NOT NULL,
  `peran` varchar(50) NOT NULL DEFAULT 'anggota',
  PRIMARY KEY (`id`),
  KEY `idx_pkm_anggota_pkm_id` (`pkm_id`),
  KEY `idx_pkm_anggota_dosen_id` (`dosen_id`),
  CONSTRAINT `pkm_anggota_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pkm_anggota_pkm_id_foreign` FOREIGN KEY (`pkm_id`) REFERENCES `pkm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pkm_anggota` */

/*Table structure for table `ppepp` */

DROP TABLE IF EXISTS `ppepp`;

CREATE TABLE `ppepp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `siklus` varchar(20) NOT NULL,
  `tahap` enum('penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan') NOT NULL,
  `judul` varchar(250) NOT NULL,
  `isi` longtext DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ppepp_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `ppepp_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ppepp` */

/*Table structure for table `prestasi_mahasiswa` */

DROP TABLE IF EXISTS `prestasi_mahasiswa`;

CREATE TABLE `prestasi_mahasiswa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `mahasiswa_id` int(10) unsigned DEFAULT NULL,
  `nama_kegiatan` varchar(250) NOT NULL,
  `penyelenggara` varchar(200) DEFAULT NULL,
  `tingkat` enum('lokal','wilayah','nasional','internasional') NOT NULL,
  `jenis` enum('akademik','non_akademik') NOT NULL,
  `prestasi` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prestasi_mahasiswa_prodi_id_foreign` (`prodi_id`),
  KEY `prestasi_mahasiswa_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `prestasi_mahasiswa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `prestasi_mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prestasi_mahasiswa` */

/*Table structure for table `prodi` */

DROP TABLE IF EXISTS `prodi`;

CREATE TABLE `prodi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `upps_id` int(10) unsigned NOT NULL,
  `kode_pddikti` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jenjang` enum('D1','D2','D3','D4','S1','S2','S3','Profesi','Sp1','Sp2') NOT NULL,
  `gelar` varchar(50) DEFAULT NULL,
  `singkatan_gelar` varchar(20) DEFAULT NULL,
  `akreditasi_saat_ini` varchar(50) DEFAULT NULL,
  `no_sk_akreditasi` varchar(100) DEFAULT NULL,
  `tgl_sk_akreditasi` date DEFAULT NULL,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `status` enum('aktif','non_aktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pddikti` (`kode_pddikti`),
  KEY `prodi_upps_id_foreign` (`upps_id`),
  CONSTRAINT `prodi_upps_id_foreign` FOREIGN KEY (`upps_id`) REFERENCES `upps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prodi` */

insert  into `prodi`(`id`,`upps_id`,`kode_pddikti`,`nama`,`jenjang`,`gelar`,`singkatan_gelar`,`akreditasi_saat_ini`,`no_sk_akreditasi`,`tgl_sk_akreditasi`,`tgl_kadaluarsa`,`status`,`created_at`,`updated_at`) values 
(1,1,'62201','Akuntansi','S1',NULL,NULL,'Baik Sekali',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(2,1,'62401','Akuntansi','D3',NULL,NULL,'Baik Sekali',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(3,1,'90241','Desain Komunikasi Visual','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(4,1,'74201','Hukum','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(5,1,'55200','Informatika','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(6,1,'61201','Manajemen','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(7,1,'57401','Manajemen Informatika','D3','Ahli Madya Komputer','A.Md.Kom','Baik','254/SK/LAM-INFOKOM/Ak/D3/VIII/2025','2025-08-18','2030-08-18','aktif','2026-05-22 01:13:44','2026-05-22 02:39:35'),
(8,1,'57472','Manajemen Informatika (Kampus Kota Tegal)','D3',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(9,1,'93104','Manajemen Inovasi','S2',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(10,1,'61405','Manajemen Perusahaan','D3',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44'),
(11,1,'26201','Teknik Industri','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-22 01:13:44','2026-05-22 01:13:44');

/*Table structure for table `prodi_instrumen` */

DROP TABLE IF EXISTS `prodi_instrumen`;

CREATE TABLE `prodi_instrumen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `lam_instrumen_id` int(10) unsigned NOT NULL,
  `periode_mulai` date DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodi_id_lam_instrumen_id` (`prodi_id`,`lam_instrumen_id`),
  KEY `prodi_instrumen_lam_instrumen_id_foreign` (`lam_instrumen_id`),
  KEY `idx_prodi_instrumen_prodi_id` (`prodi_id`),
  KEY `idx_prodi_instrumen_aktif` (`aktif`),
  CONSTRAINT `prodi_instrumen_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prodi_instrumen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prodi_instrumen` */

insert  into `prodi_instrumen`(`id`,`prodi_id`,`lam_instrumen_id`,`periode_mulai`,`aktif`) values 
(1,7,1,'2026-05-22',1),
(2,8,1,'2026-05-22',1),
(3,5,2,'2026-05-22',1);

/*Table structure for table `publikasi` */

DROP TABLE IF EXISTS `publikasi`;

CREATE TABLE `publikasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `kategori` enum('penelitian','pkm') NOT NULL,
  `jenis` enum('jurnal_nasional_tidak_terakreditasi','jurnal_nasional_terakreditasi','jurnal_internasional','jurnal_internasional_bereputasi','seminar_lokal','seminar_nasional','seminar_internasional','media_massa_nasional','media_massa_internasional','buku','book_chapter') NOT NULL,
  `judul` text NOT NULL,
  `penulis` text NOT NULL,
  `nama_media` varchar(250) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `volume` varchar(50) DEFAULT NULL,
  `doi` varchar(150) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `sitasi` int(11) NOT NULL DEFAULT 0,
  `sinta_score` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_publikasi_prodi_id` (`prodi_id`),
  KEY `idx_publikasi_kategori` (`kategori`),
  KEY `idx_publikasi_tahun` (`tahun`),
  CONSTRAINT `publikasi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `publikasi` */

/*Table structure for table `sarpras` */

DROP TABLE IF EXISTS `sarpras`;

CREATE TABLE `sarpras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `kategori` enum('ruang_kelas','laboratorium','perpustakaan','peralatan','sistem_informasi','lainnya') NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `luas_m2` decimal(10,2) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `kondisi` enum('baik','rusak_ringan','rusak_berat') NOT NULL DEFAULT 'baik',
  `kepemilikan` enum('milik','sewa','pinjam') NOT NULL DEFAULT 'milik',
  `untuk_penelitian` tinyint(1) NOT NULL DEFAULT 0,
  `untuk_pkm` tinyint(1) NOT NULL DEFAULT 0,
  `untuk_pendidikan` tinyint(1) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sarpras_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `sarpras_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sarpras` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

/*Table structure for table `survei` */

DROP TABLE IF EXISTS `survei`;

CREATE TABLE `survei` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `judul` varchar(200) NOT NULL,
  `target` enum('mahasiswa','dosen','tendik','mitra','pengguna_lulusan') NOT NULL,
  `periode` varchar(30) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `pertanyaan_json` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `survei_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `survei_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `survei` */

/*Table structure for table `survei_response` */

DROP TABLE IF EXISTS `survei_response`;

CREATE TABLE `survei_response` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survei_id` int(10) unsigned NOT NULL,
  `responden` varchar(150) DEFAULT NULL,
  `jawaban_json` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `survei_response_survei_id_foreign` (`survei_id`),
  CONSTRAINT `survei_response_survei_id_foreign` FOREIGN KEY (`survei_id`) REFERENCES `survei` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `survei_response` */

/*Table structure for table `sync_log` */

DROP TABLE IF EXISTS `sync_log`;

CREATE TABLE `sync_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sumber` varchar(30) NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `status` enum('sukses','gagal','sebagian') NOT NULL,
  `detail` longtext DEFAULT NULL,
  `jumlah_diproses` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sync_log` */

/*Table structure for table `tahun_akademik` */

DROP TABLE IF EXISTS `tahun_akademik`;

CREATE TABLE `tahun_akademik` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `tahun_mulai` year(4) NOT NULL,
  `tahun_akhir` year(4) NOT NULL,
  `semester` enum('ganjil','genap','tahunan') NOT NULL DEFAULT 'tahunan',
  `is_ts` tinyint(1) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_semester` (`kode`,`semester`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tahun_akademik` */

insert  into `tahun_akademik`(`id`,`kode`,`tahun_mulai`,`tahun_akhir`,`semester`,`is_ts`,`aktif`) values 
(1,'2021/2022',2021,2022,'tahunan',0,1),
(2,'2022/2023',2022,2023,'tahunan',0,1),
(3,'2023/2024',2023,2024,'tahunan',0,1),
(4,'2024/2025',2024,2025,'tahunan',0,1),
(5,'2025/2026',2025,2026,'tahunan',1,1);

/*Table structure for table `tendik` */

DROP TABLE IF EXISTS `tendik`;

CREATE TABLE `tendik` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis` enum('pustakawan','laboran','teknisi','operator','administrasi','lainnya') NOT NULL,
  `pendidikan_terakhir` enum('SMA','D1','D2','D3','D4','S1','S2','S3') DEFAULT NULL,
  `sertifikat_kompetensi` varchar(200) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `tendik_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `tendik_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tendik` */

/*Table structure for table `tracer_study` */

DROP TABLE IF EXISTS `tracer_study`;

CREATE TABLE `tracer_study` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int(10) unsigned NOT NULL,
  `tgl_isi` date DEFAULT NULL,
  `masa_tunggu_bulan` int(11) DEFAULT NULL,
  `kesesuaian_bidang_kerja` enum('sangat_sesuai','sesuai','kurang_sesuai','tidak_sesuai') DEFAULT NULL,
  `tingkat_pekerjaan` enum('lokal','nasional','multinasional','internasional','wirausaha') DEFAULT NULL,
  `nama_perusahaan` varchar(200) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `gaji_pertama` bigint(20) DEFAULT NULL,
  `lanjut_studi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tracer_study_mahasiswa_id_foreign` (`mahasiswa_id`),
  CONSTRAINT `tracer_study_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tracer_study` */

/*Table structure for table `upps` */

DROP TABLE IF EXISTS `upps`;

CREATE TABLE `upps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `perguruan_tinggi_id` int(10) unsigned NOT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `jenis` enum('fakultas','sekolah','jurusan','departemen','direktorat','tidak_ada') NOT NULL DEFAULT 'tidak_ada',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upps_perguruan_tinggi_id_foreign` (`perguruan_tinggi_id`),
  CONSTRAINT `upps_perguruan_tinggi_id_foreign` FOREIGN KEY (`perguruan_tinggi_id`) REFERENCES `perguruan_tinggi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `upps` */

insert  into `upps`(`id`,`perguruan_tinggi_id`,`kode`,`nama`,`jenis`,`created_at`,`updated_at`) values 
(1,1,'-','-','tidak_ada','2026-05-22 01:13:44','2026-05-22 01:13:44');

/*Table structure for table `user_prodi` */

DROP TABLE IF EXISTS `user_prodi`;

CREATE TABLE `user_prodi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `prodi_id` int(10) unsigned NOT NULL,
  `peran` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_prodi_id_peran` (`user_id`,`prodi_id`,`peran`),
  KEY `user_prodi_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `user_prodi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_prodi` */

insert  into `user_prodi`(`id`,`user_id`,`prodi_id`,`peran`,`is_default`,`created_at`) values 
(1,2,7,'kaprodi',1,'2026-05-22 01:13:45'),
(2,3,7,'sekprodi',1,'2026-05-22 01:13:46'),
(3,4,7,'gkm',1,'2026-05-22 01:13:46'),
(4,5,7,'dosen',1,'2026-05-22 01:13:47'),
(5,6,7,'tendik',1,'2026-05-22 01:13:47'),
(6,7,7,'mahasiswa',1,'2026-05-22 01:13:48');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`status`,`status_message`,`active`,`last_active`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'superadmin',NULL,NULL,0,'2026-05-22 08:22:27','2026-05-22 01:13:44','2026-05-22 01:13:44',NULL),
(2,'kaprodi',NULL,NULL,0,NULL,'2026-05-22 01:13:45','2026-05-22 01:13:45',NULL),
(3,'sekprodi',NULL,NULL,0,NULL,'2026-05-22 01:13:45','2026-05-22 01:13:45',NULL),
(4,'gkm',NULL,NULL,0,NULL,'2026-05-22 01:13:46','2026-05-22 01:13:46',NULL),
(5,'dosen',NULL,NULL,0,NULL,'2026-05-22 01:13:46','2026-05-22 01:13:46',NULL),
(6,'tendik',NULL,NULL,0,NULL,'2026-05-22 01:13:47','2026-05-22 01:13:47',NULL),
(7,'mahasiswa',NULL,NULL,0,NULL,'2026-05-22 01:13:47','2026-05-22 01:13:47',NULL),
(8,'asesor',NULL,NULL,0,NULL,'2026-05-22 01:13:48','2026-05-22 01:13:48',NULL),
(9,'auditor',NULL,NULL,0,NULL,'2026-05-22 01:13:48','2026-05-22 01:13:48',NULL);

/*Table structure for table `vmts` */

DROP TABLE IF EXISTS `vmts`;

CREATE TABLE `vmts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `tujuan` text NOT NULL,
  `strategi` text DEFAULT NULL,
  `no_sk` varchar(100) DEFAULT NULL,
  `tgl_sk` date DEFAULT NULL,
  `berlaku_mulai` date DEFAULT NULL,
  `berlaku_sampai` date DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vmts_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `vmts_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `vmts` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
