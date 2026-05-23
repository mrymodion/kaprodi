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
(1,1,'email_password',NULL,'superadmin@utd.ac.id','$2y$12$Sd0YcAUJVaQ.oaleI0ZpOelrfgz8sJxdf0HanQ177r2gcQzN3y0DC',NULL,NULL,0,'2026-05-23 17:30:44','2026-05-22 01:13:44','2026-05-23 17:30:44'),
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_logins` */

insert  into `auth_logins`(`id`,`ip_address`,`user_agent`,`id_type`,`identifier`,`user_id`,`date`,`success`) values 
(1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',NULL,'2026-05-22 01:13:17',0),
(2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-22 01:14:01',1),
(3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-22 06:24:24',1),
(4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-23 17:30:44',1);

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

/*Table structure for table `dokumen_eviden` */

DROP TABLE IF EXISTS `dokumen_eviden`;

CREATE TABLE `dokumen_eviden` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_table` varchar(20) NOT NULL,
  `ref_id` int(10) unsigned NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `file` varchar(255) NOT NULL,
  `nama_asli` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref_table_ref_id` (`ref_table`,`ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `dokumen_eviden` */

insert  into `dokumen_eviden`(`id`,`ref_table`,`ref_id`,`jenis`,`file`,`nama_asli`,`created_at`) values 
(1,'dosen',1,'ijazah','b59a1c0abf0385bd1.pdf','IJASAH S2 compress.pdf','2026-05-23 18:06:13');

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

/*Table structure for table `fleksibilitas` */

DROP TABLE IF EXISTS `fleksibilitas`;

CREATE TABLE `fleksibilitas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `bentuk` varchar(50) NOT NULL,
  `jumlah_mahasiswa` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fleksibilitas_prodi_id_foreign` (`prodi_id`),
  KEY `fleksibilitas_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  CONSTRAINT `fleksibilitas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fleksibilitas_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fleksibilitas` */

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
  `link_bukti` varchar(500) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=411 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_butir` */

insert  into `lam_butir`(`id`,`lam_kriteria_id`,`kode`,`judul`,`deskripsi`,`jenis_ipo`,`bobot`,`tipe_data`,`formula`,`tabel_lkps_ref`,`urutan`) values 
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
(164,18,'D.5','D.5 - Pengembangan bidang Infokom yang digunakan di masyarakat','Pengembangan bidang Infokom yang digunakan di masyarakat — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).','output',5.00,'kualitatif',NULL,NULL,5),
(329,37,'A','A - Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS','Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(330,38,'B','B - Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria','Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(331,39,'1.1.A','1.1.A [Penetapan] - Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP','Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,1),
(332,39,'1.1.B','1.1.B [Penetapan] - Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten','Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',2.00,'kualitatif',NULL,NULL,2),
(333,39,'1.2.A','1.2.A [Pelaksanaan] - Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP','Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',7.00,'kualitatif',NULL,NULL,3),
(334,39,'1.2.B','1.2.B [Pelaksanaan] - Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten','Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,4),
(335,39,'1.3.A','1.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian standar sistem tata kelola internal','Efektifitas evaluasi ketercapaian standar sistem tata kelola internal (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',7.50,'kualitatif',NULL,NULL,5),
(336,39,'1.3.B','1.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian standar fungsi SPMI','Efektifitas evaluasi ketercapaian standar fungsi SPMI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',3.00,'kualitatif',NULL,NULL,6),
(337,39,'1.4.A','1.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal','Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',4.00,'kualitatif',NULL,NULL,7),
(338,39,'1.4.B','1.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fungsi SPMI','Efektifitas tindak lanjut hasil evaluasi fungsi SPMI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',2.00,'kualitatif',NULL,NULL,8),
(339,39,'1.5.A','1.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal','Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(340,39,'1.5.B','1.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi fungsi SPMI','Efektifitas peningkatan/optimalisasi fungsi SPMI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',2.50,'kualitatif',NULL,NULL,10),
(341,40,'2.1.A','2.1.A [Penetapan] - Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal','Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',5.00,'kualitatif',NULL,NULL,1),
(342,40,'2.1.B','2.1.B [Penetapan] - Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5)','Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,2),
(343,40,'2.1.C','2.1.C [Penetapan] - Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential)','Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,3),
(344,40,'2.1.D','2.1.D [Penetapan] - Kebijakan kompetensi lulusan (rekognisi DUDIKA)','Kebijakan kompetensi lulusan (rekognisi DUDIKA) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,4),
(345,40,'2.2.A','2.2.A [Pelaksanaan] - Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru','Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',9.00,'kualitatif',NULL,NULL,5),
(346,40,'2.2.B','2.2.B [Pelaksanaan] - Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE','Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',7.00,'kualitatif',NULL,NULL,6),
(347,40,'2.2.C','2.2.C [Pelaksanaan] - Efektifitas pelaksanaan fleksibilitas proses pembelajaran','Efektifitas pelaksanaan fleksibilitas proses pembelajaran (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',7.00,'kualitatif',NULL,NULL,7),
(348,40,'2.2.D','2.2.D [Pelaksanaan] - Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja)','Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja) (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',30.00,'kualitatif',NULL,NULL,8),
(349,40,'2.3.A','2.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru','Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(350,40,'2.3.B','2.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE','Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,10),
(351,40,'2.3.C','2.3.C [Evaluasi] - Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran','Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',4.00,'kualitatif',NULL,NULL,11),
(352,40,'2.3.D','2.3.D [Evaluasi] - Efektifitas evaluasi ketercapaian kompetensi lulusan','Efektifitas evaluasi ketercapaian kompetensi lulusan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',4.00,'kualitatif',NULL,NULL,12),
(353,40,'2.4.A','2.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru','Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',4.00,'kualitatif',NULL,NULL,13),
(354,40,'2.4.B','2.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE','Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',4.00,'kualitatif',NULL,NULL,14),
(355,40,'2.4.C','2.4.C [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran','Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,15),
(356,40,'2.4.D','2.4.D [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan','Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,16),
(357,40,'2.5.A','2.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru','Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,17),
(358,40,'2.5.B','2.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE','Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,18),
(359,40,'2.5.C','2.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran','Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',4.00,'kualitatif',NULL,NULL,19),
(360,40,'2.5.D','2.5.D [Peningkatan] - Efektifitas peningkatan/optimalisasi kompetensi lulusan','Efektifitas peningkatan/optimalisasi kompetensi lulusan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',4.00,'kualitatif',NULL,NULL,20),
(361,41,'3.1.A','3.1.A [Penetapan] - Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian','Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',1.50,'kualitatif',NULL,NULL,1),
(362,41,'3.1.B','3.1.B [Penetapan] - Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa','Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',1.50,'kualitatif',NULL,NULL,2),
(363,41,'3.1.C','3.1.C [Penetapan] - Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian','Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',1.50,'kualitatif',NULL,NULL,3),
(364,41,'3.2.A','3.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan','Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.50,'kualitatif',NULL,NULL,4),
(365,41,'3.2.B','3.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa','Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',2.50,'kualitatif',NULL,NULL,5),
(366,41,'3.2.C','3.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian','Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',8.00,'kualitatif',NULL,NULL,6),
(367,41,'3.3.A','3.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan','Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',2.00,'kualitatif',NULL,NULL,7),
(368,41,'3.3.B','3.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan penelitian','Efektifitas evaluasi implementasi peta jalan penelitian (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',1.50,'kualitatif',NULL,NULL,8),
(369,41,'3.3.C','3.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah, publikasi, HKI','Efektifitas evaluasi perolehan hibah, publikasi, HKI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',1.00,'kualitatif',NULL,NULL,9),
(370,41,'3.4.A','3.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan','Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',2.00,'kualitatif',NULL,NULL,10),
(371,41,'3.4.B','3.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian','Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',2.00,'kualitatif',NULL,NULL,11),
(372,41,'3.4.C','3.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI','Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',1.00,'kualitatif',NULL,NULL,12),
(373,41,'3.5.A','3.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan','Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',1.50,'kualitatif',NULL,NULL,13),
(374,41,'3.5.B','3.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian','Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',1.50,'kualitatif',NULL,NULL,14),
(375,41,'3.5.C','3.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI','Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',1.00,'kualitatif',NULL,NULL,15),
(376,42,'4.1.A','4.1.A [Penetapan] - Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM','Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',6.00,'kualitatif',NULL,NULL,1),
(377,42,'4.1.B','4.1.B [Penetapan] - Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa','Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',5.00,'kualitatif',NULL,NULL,2),
(378,42,'4.1.C','4.1.C [Penetapan] - Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI','Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',4.00,'kualitatif',NULL,NULL,3),
(379,42,'4.2.A','4.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM','Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',12.00,'kualitatif',NULL,NULL,4),
(380,42,'4.2.B','4.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa','Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',9.00,'kualitatif',NULL,NULL,5),
(381,42,'4.2.C','4.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI','Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',20.00,'kualitatif',NULL,NULL,6),
(382,42,'4.3.A','4.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM','Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',6.00,'kualitatif',NULL,NULL,7),
(383,42,'4.3.B','4.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan PkM','Efektifitas evaluasi implementasi peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.50,'kualitatif',NULL,NULL,8),
(384,42,'4.3.C','4.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI','Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(385,42,'4.4.A','4.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan','Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',5.00,'kualitatif',NULL,NULL,10),
(386,42,'4.4.B','4.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM','Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,11),
(387,42,'4.4.C','4.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI','Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,12),
(388,42,'4.5.A','4.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan','Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',6.00,'kualitatif',NULL,NULL,13),
(389,42,'4.5.B','4.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM','Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.50,'kualitatif',NULL,NULL,14),
(390,42,'4.5.C','4.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI','Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,15),
(391,43,'5.1.A','5.1.A [Penetapan] - Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional)','Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',3.00,'kualitatif',NULL,NULL,1),
(392,43,'5.1.B','5.1.B [Penetapan] - Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM','Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',2.00,'kualitatif',NULL,NULL,2),
(393,43,'5.2.A','5.2.A [Pelaksanaan] - Efektifitas pelaksanaan sistem tata kelola otonom/transparan','Efektifitas pelaksanaan sistem tata kelola otonom/transparan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',5.00,'kualitatif',NULL,NULL,3),
(394,43,'5.2.B','5.2.B [Pelaksanaan] - Efektifitas pelaksanaan audit mutu pemenuhan tupoksi','Efektifitas pelaksanaan audit mutu pemenuhan tupoksi (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',4.00,'kualitatif',NULL,NULL,4),
(395,43,'5.3.A','5.3.A [Evaluasi] - Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel','Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',6.00,'kualitatif',NULL,NULL,5),
(396,43,'5.3.B','5.3.B [Evaluasi] - Efektifitas evaluasi audit mutu pemenuhan tupoksi','Efektifitas evaluasi audit mutu pemenuhan tupoksi (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,6),
(397,43,'5.4.A','5.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sistem tata kelola','Efektifitas tindak lanjut evaluasi sistem tata kelola (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',3.00,'kualitatif',NULL,NULL,7),
(398,43,'5.4.B','5.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi audit mutu','Efektifitas tindak lanjut evaluasi audit mutu (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',2.00,'kualitatif',NULL,NULL,8),
(399,43,'5.5.A','5.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sistem tata kelola','Efektifitas peningkatan/optimalisasi sistem tata kelola (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,9),
(400,43,'5.5.B','5.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi audit mutu','Efektifitas peningkatan/optimalisasi audit mutu (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,10),
(401,44,'6.1','6.1 [Penetapan] - Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi','Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','input',5.00,'kualitatif',NULL,NULL,1),
(402,44,'6.2','6.2 [Pelaksanaan] - Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan','Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',8.00,'kualitatif',NULL,NULL,2),
(403,44,'6.3','6.3 [Evaluasi] - Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan','Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',13.00,'kualitatif',NULL,NULL,3),
(404,44,'6.4','6.4 [Pengendalian] - Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan','Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','proses',4.00,'kualitatif',NULL,NULL,4),
(405,44,'6.5','6.5 [Peningkatan] - Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan','Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',10.00,'kualitatif',NULL,NULL,5),
(406,45,'D.1','D.1 - Mata kuliah inti/khas program studi','Mata kuliah inti/khas program studi — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',4.00,'kualitatif',NULL,NULL,1),
(407,45,'D.2','D.2 - Mata kuliah domain spesifik dan lingkungan prodi infokom','Mata kuliah domain spesifik dan lingkungan prodi infokom — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',3.00,'kualitatif',NULL,NULL,2),
(408,45,'D.3','D.3 - Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan','Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',3.00,'kualitatif',NULL,NULL,3),
(409,45,'D.4','D.4 - Proyek Utama (Capstone Project) yang relevan','Proyek Utama (Capstone Project) yang relevan — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,4),
(410,45,'D.5','D.5 - Pengembangan bidang Infokom yang digunakan di masyarakat','Pengembangan bidang Infokom yang digunakan di masyarakat — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).','output',5.00,'kualitatif',NULL,NULL,5);

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
) ENGINE=InnoDB AUTO_INCREMENT=1641 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_butir_rubrik` */

insert  into `lam_butir_rubrik`(`id`,`lam_butir_id`,`skor`,`label`,`deskriptor`,`syarat_json`) values 
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
(656,164,1,'Kurang','Kurang: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1313,329,4,'Sangat Baik','Sangat baik: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1314,329,3,'Baik','Baik: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1315,329,2,'Cukup','Cukup: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1316,329,1,'Kurang','Kurang: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1317,330,4,'Sangat Baik','Sangat baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1318,330,3,'Baik','Baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1319,330,2,'Cukup','Cukup: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1320,330,1,'Kurang','Kurang: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1321,331,4,'Sangat Baik','Sangat baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1322,331,3,'Baik','Baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1323,331,2,'Cukup','Cukup: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1324,331,1,'Kurang','Kurang: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1325,332,4,'Sangat Baik','Sangat baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1326,332,3,'Baik','Baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1327,332,2,'Cukup','Cukup: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1328,332,1,'Kurang','Kurang: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1329,333,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1330,333,3,'Baik','Baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1331,333,2,'Cukup','Cukup: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1332,333,1,'Kurang','Kurang: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1333,334,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1334,334,3,'Baik','Baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1335,334,2,'Cukup','Cukup: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1336,334,1,'Kurang','Kurang: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1337,335,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1338,335,3,'Baik','Baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1339,335,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1340,335,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1341,336,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1342,336,3,'Baik','Baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1343,336,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1344,336,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1345,337,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1346,337,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1347,337,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1348,337,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1349,338,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1350,338,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1351,338,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1352,338,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1353,339,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1354,339,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1355,339,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1356,339,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1357,340,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1358,340,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1359,340,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1360,340,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1361,341,4,'Sangat Baik','Sangat baik: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1362,341,3,'Baik','Baik: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1363,341,2,'Cukup','Cukup: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1364,341,1,'Kurang','Kurang: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1365,342,4,'Sangat Baik','Sangat baik: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1366,342,3,'Baik','Baik: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1367,342,2,'Cukup','Cukup: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1368,342,1,'Kurang','Kurang: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1369,343,4,'Sangat Baik','Sangat baik: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1370,343,3,'Baik','Baik: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1371,343,2,'Cukup','Cukup: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1372,343,1,'Kurang','Kurang: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1373,344,4,'Sangat Baik','Sangat baik: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1374,344,3,'Baik','Baik: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1375,344,2,'Cukup','Cukup: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1376,344,1,'Kurang','Kurang: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1377,345,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1378,345,3,'Baik','Baik: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1379,345,2,'Cukup','Cukup: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1380,345,1,'Kurang','Kurang: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1381,346,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1382,346,3,'Baik','Baik: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1383,346,2,'Cukup','Cukup: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1384,346,1,'Kurang','Kurang: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1385,347,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1386,347,3,'Baik','Baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1387,347,2,'Cukup','Cukup: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1388,347,1,'Kurang','Kurang: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1389,348,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1390,348,3,'Baik','Baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1391,348,2,'Cukup','Cukup: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1392,348,1,'Kurang','Kurang: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1393,349,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1394,349,3,'Baik','Baik: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1395,349,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1396,349,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1397,350,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1398,350,3,'Baik','Baik: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1399,350,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1400,350,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1401,351,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1402,351,3,'Baik','Baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1403,351,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1404,351,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1405,352,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1406,352,3,'Baik','Baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1407,352,2,'Cukup','Cukup: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1408,352,1,'Kurang','Kurang: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1409,353,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1410,353,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1411,353,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1412,353,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1413,354,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1414,354,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1415,354,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1416,354,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1417,355,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1418,355,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1419,355,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1420,355,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1421,356,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1422,356,3,'Baik','Baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1423,356,2,'Cukup','Cukup: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1424,356,1,'Kurang','Kurang: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1425,357,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1426,357,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1427,357,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1428,357,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1429,358,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1430,358,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1431,358,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1432,358,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1433,359,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1434,359,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1435,359,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1436,359,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1437,360,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1438,360,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1439,360,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1440,360,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1441,361,4,'Sangat Baik','Sangat baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1442,361,3,'Baik','Baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1443,361,2,'Cukup','Cukup: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1444,361,1,'Kurang','Kurang: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1445,362,4,'Sangat Baik','Sangat baik: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1446,362,3,'Baik','Baik: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1447,362,2,'Cukup','Cukup: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1448,362,1,'Kurang','Kurang: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1449,363,4,'Sangat Baik','Sangat baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1450,363,3,'Baik','Baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1451,363,2,'Cukup','Cukup: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1452,363,1,'Kurang','Kurang: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1453,364,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1454,364,3,'Baik','Baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1455,364,2,'Cukup','Cukup: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1456,364,1,'Kurang','Kurang: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1457,365,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1458,365,3,'Baik','Baik: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1459,365,2,'Cukup','Cukup: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1460,365,1,'Kurang','Kurang: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1461,366,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1462,366,3,'Baik','Baik: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1463,366,2,'Cukup','Cukup: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1464,366,1,'Kurang','Kurang: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1465,367,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1466,367,3,'Baik','Baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1467,367,2,'Cukup','Cukup: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1468,367,1,'Kurang','Kurang: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1469,368,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1470,368,3,'Baik','Baik: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1471,368,2,'Cukup','Cukup: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1472,368,1,'Kurang','Kurang: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1473,369,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1474,369,3,'Baik','Baik: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1475,369,2,'Cukup','Cukup: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1476,369,1,'Kurang','Kurang: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1477,370,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1478,370,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1479,370,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1480,370,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1481,371,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1482,371,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1483,371,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1484,371,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1485,372,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1486,372,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1487,372,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1488,372,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1489,373,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1490,373,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1491,373,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1492,373,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1493,374,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1494,374,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1495,374,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1496,374,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1497,375,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1498,375,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1499,375,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1500,375,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1501,376,4,'Sangat Baik','Sangat baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1502,376,3,'Baik','Baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1503,376,2,'Cukup','Cukup: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1504,376,1,'Kurang','Kurang: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1505,377,4,'Sangat Baik','Sangat baik: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1506,377,3,'Baik','Baik: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1507,377,2,'Cukup','Cukup: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1508,377,1,'Kurang','Kurang: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1509,378,4,'Sangat Baik','Sangat baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1510,378,3,'Baik','Baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1511,378,2,'Cukup','Cukup: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1512,378,1,'Kurang','Kurang: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1513,379,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1514,379,3,'Baik','Baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1515,379,2,'Cukup','Cukup: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1516,379,1,'Kurang','Kurang: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1517,380,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1518,380,3,'Baik','Baik: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1519,380,2,'Cukup','Cukup: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1520,380,1,'Kurang','Kurang: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1521,381,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1522,381,3,'Baik','Baik: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1523,381,2,'Cukup','Cukup: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1524,381,1,'Kurang','Kurang: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1525,382,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1526,382,3,'Baik','Baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1527,382,2,'Cukup','Cukup: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1528,382,1,'Kurang','Kurang: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1529,383,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1530,383,3,'Baik','Baik: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1531,383,2,'Cukup','Cukup: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1532,383,1,'Kurang','Kurang: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1533,384,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1534,384,3,'Baik','Baik: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1535,384,2,'Cukup','Cukup: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1536,384,1,'Kurang','Kurang: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1537,385,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1538,385,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1539,385,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1540,385,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1541,386,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1542,386,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1543,386,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1544,386,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1545,387,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1546,387,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1547,387,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1548,387,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1549,388,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1550,388,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1551,388,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1552,388,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1553,389,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1554,389,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1555,389,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1556,389,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1557,390,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1558,390,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1559,390,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1560,390,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1561,391,4,'Sangat Baik','Sangat baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1562,391,3,'Baik','Baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1563,391,2,'Cukup','Cukup: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1564,391,1,'Kurang','Kurang: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1565,392,4,'Sangat Baik','Sangat baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1566,392,3,'Baik','Baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1567,392,2,'Cukup','Cukup: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1568,392,1,'Kurang','Kurang: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1569,393,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1570,393,3,'Baik','Baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1571,393,2,'Cukup','Cukup: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1572,393,1,'Kurang','Kurang: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1573,394,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1574,394,3,'Baik','Baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1575,394,2,'Cukup','Cukup: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1576,394,1,'Kurang','Kurang: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1577,395,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1578,395,3,'Baik','Baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1579,395,2,'Cukup','Cukup: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1580,395,1,'Kurang','Kurang: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1581,396,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1582,396,3,'Baik','Baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1583,396,2,'Cukup','Cukup: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1584,396,1,'Kurang','Kurang: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1585,397,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1586,397,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1587,397,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1588,397,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1589,398,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1590,398,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1591,398,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1592,398,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1593,399,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1594,399,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1595,399,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1596,399,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1597,400,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1598,400,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1599,400,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1600,400,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1601,401,4,'Sangat Baik','Sangat baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1602,401,3,'Baik','Baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1603,401,2,'Cukup','Cukup: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1604,401,1,'Kurang','Kurang: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1605,402,4,'Sangat Baik','Sangat baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1606,402,3,'Baik','Baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1607,402,2,'Cukup','Cukup: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1608,402,1,'Kurang','Kurang: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1609,403,4,'Sangat Baik','Sangat baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1610,403,3,'Baik','Baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1611,403,2,'Cukup','Cukup: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1612,403,1,'Kurang','Kurang: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1613,404,4,'Sangat Baik','Sangat baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1614,404,3,'Baik','Baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1615,404,2,'Cukup','Cukup: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1616,404,1,'Kurang','Kurang: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1617,405,4,'Sangat Baik','Sangat baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1618,405,3,'Baik','Baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1619,405,2,'Cukup','Cukup: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1620,405,1,'Kurang','Kurang: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1621,406,4,'Sangat Baik','Sangat baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1622,406,3,'Baik','Baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1623,406,2,'Cukup','Cukup: Mata kuliah inti/khas program studi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1624,406,1,'Kurang','Kurang: Mata kuliah inti/khas program studi, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1625,407,4,'Sangat Baik','Sangat baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1626,407,3,'Baik','Baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1627,407,2,'Cukup','Cukup: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1628,407,1,'Kurang','Kurang: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1629,408,4,'Sangat Baik','Sangat baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1630,408,3,'Baik','Baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1631,408,2,'Cukup','Cukup: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1632,408,1,'Kurang','Kurang: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1633,409,4,'Sangat Baik','Sangat baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1634,409,3,'Baik','Baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1635,409,2,'Cukup','Cukup: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1636,409,1,'Kurang','Kurang: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL),
(1637,410,4,'Sangat Baik','Sangat baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.',NULL),
(1638,410,3,'Baik','Baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih dan lengkap.',NULL),
(1639,410,2,'Cukup','Cukup: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian besar dengan bukti yang cukup lengkap.',NULL),
(1640,410,1,'Kurang','Kurang: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian dengan bukti yang kurang lengkap.',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_instrumen` */

insert  into `lam_instrumen`(`id`,`lam_lembaga_id`,`jenjang`,`versi`,`nama`,`tahun_terbit`,`driver_class`,`total_bobot`,`aktif`,`created_at`) values 
(2,1,'S1','2.0','Instrumen Akreditasi Program Studi 2.0 Sarjana (Informatika & Komputer)',2025,'Modules\\Akreditasi\\Drivers\\LamInfokomS1\\Driver',400.00,1,'2026-05-22 06:24:17'),
(5,1,'D3','2.0','Instrumen Akreditasi Program Studi 2.0 D3 (Informatika & Komputer)',2025,'Modules\\Akreditasi\\Drivers\\LamInfokomD3\\Driver',400.00,1,'2026-05-23 23:32:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_kriteria` */

insert  into `lam_kriteria`(`id`,`lam_instrumen_id`,`kode`,`nama`,`jenis`,`bobot`,`urutan`,`deskripsi`) values 
(10,2,'A','Kondisi Eksternal','kondisi_eksternal',4.00,1,NULL),
(11,2,'B','Profil Unit Pengelola Program Studi','profil_upps',4.00,2,NULL),
(12,2,'C1','Budaya Mutu','kriteria',40.00,3,NULL),
(13,2,'C2','Relevansi Pendidikan','kriteria',120.00,4,NULL),
(14,2,'C3','Relevansi Penelitian','kriteria',72.00,5,NULL),
(15,2,'C4','Relevansi PkM','kriteria',60.00,6,NULL),
(16,2,'C5','Akuntabilitas','kriteria',40.00,7,NULL),
(17,2,'C6','Diferensiasi Misi','kriteria',40.00,8,NULL),
(18,2,'D','Suplemen Program Studi','suplemen',20.00,9,NULL),
(37,5,'A','Kondisi Eksternal','kondisi_eksternal',4.00,1,NULL),
(38,5,'B','Profil Unit Pengelola Program Studi','profil_upps',4.00,2,NULL),
(39,5,'C1','Budaya Mutu','kriteria',40.00,3,NULL),
(40,5,'C2','Relevansi Pendidikan','kriteria',120.00,4,NULL),
(41,5,'C3','Relevansi Penelitian','kriteria',32.00,5,NULL),
(42,5,'C4','Relevansi PkM','kriteria',100.00,6,NULL),
(43,5,'C5','Akuntabilitas','kriteria',40.00,7,NULL),
(44,5,'C6','Diferensiasi Misi','kriteria',40.00,8,NULL),
(45,5,'D','Suplemen Program Studi','suplemen',20.00,9,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_tabel_lkps` */

insert  into `lam_tabel_lkps`(`id`,`lam_instrumen_id`,`kode`,`judul`,`kriteria_kode`,`schema_json`,`urutan`) values 
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
(62,2,'6','Kesesuaian Visi dan Misi','C6',NULL,31),
(125,5,'1.A.1','Pimpinan dan Tupoksi UPPS dan PS','C1',NULL,1),
(126,5,'1.A.2','Sumber Pendanaan UPPS/PS','C1',NULL,2),
(127,5,'1.A.3','Penggunaan Dana UPPS/PS','C1',NULL,3),
(128,5,'1.A.4','Rata-rata Beban DTPR per Semester (EWMP)','C1',NULL,4),
(129,5,'1.A.5','Kualifikasi Tenaga Kependidikan','C1',NULL,5),
(130,5,'1.B','Unit SPMI dan SDM','C1',NULL,6),
(131,5,'2.A.1','Data Mahasiswa','C2',NULL,7),
(132,5,'2.A.2','Keragaman Asal Mahasiswa','C2',NULL,8),
(133,5,'2.A.3','Kondisi Jumlah Mahasiswa','C2',NULL,9),
(134,5,'2.B.1','Isi Pembelajaran (Kurikulum)','C2',NULL,10),
(135,5,'2.B.2','Pemetaan CPL dengan Profil Lulusan','C2',NULL,11),
(136,5,'2.B.3','Peta Pemenuhan CPL','C2',NULL,12),
(137,5,'2.B.4','Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali','C2',NULL,13),
(138,5,'2.B.5','Kesesuaian Bidang Kerja Lulusan','C2',NULL,14),
(139,5,'2.B.6','Kepuasan Pengguna Lulusan','C2',NULL,15),
(140,5,'2.C','Fleksibilitas dalam Proses Pembelajaran','C2',NULL,16),
(141,5,'2.D','Rekognisi dan Apresiasi Kompetensi Lulusan','C2',NULL,17),
(142,5,'3.A.1','Sarana dan Prasarana Penelitian','C3',NULL,18),
(143,5,'3.A.2','Penelitian DTPR, Hibah dan Pembiayaan Penelitian','C3',NULL,19),
(144,5,'3.A.3','Pengembangan DTPR di Bidang Penelitian','C3',NULL,20),
(145,5,'3.C.1','Kerjasama Penelitian','C3',NULL,21),
(146,5,'3.C.2','Publikasi Penelitian','C3',NULL,22),
(147,5,'3.C.3','Perolehan HKI (Granted) Penelitian','C3',NULL,23),
(148,5,'4.A.1','Sarana dan Prasarana PkM','C4',NULL,24),
(149,5,'4.A.2','PkM DTPR, Hibah dan Pembiayaan PkM','C4',NULL,25),
(150,5,'4.C.1','Kerjasama PkM','C4',NULL,26),
(151,5,'4.C.2','Diseminasi Hasil PkM','C4',NULL,27),
(152,5,'4.C.3','Perolehan HKI PkM','C4',NULL,28),
(153,5,'5.1','Sistem Tata Kelola','C5',NULL,29),
(154,5,'5.2','Sarana dan Prasarana Pendidikan','C5',NULL,30),
(155,5,'6','Kesesuaian Visi dan Misi','C6',NULL,31);

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
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_template_led` */

insert  into `lam_template_led`(`id`,`lam_instrumen_id`,`lam_kriteria_id`,`bagian`,`sub_bagian`,`judul`,`template_isi`,`prompt_groq`,`urutan`) values 
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
(64,2,17,'kriteria','peningkatan','Peningkatan - C6','','Tuliskan narasi tahap peningkatan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',32),
(129,5,NULL,'pendahuluan',NULL,'Pendahuluan LED','','Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi diploma tiga dalam bahasa Indonesia formal akademik.',1),
(130,5,NULL,'penutup',NULL,'Penutup LED','','Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi diploma tiga dalam bahasa Indonesia formal akademik.',2),
(131,5,39,'kriteria','penetapan','Penetapan - C1','','Tuliskan narasi tahap penetapan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',3),
(132,5,39,'kriteria','pelaksanaan','Pelaksanaan - C1','','Tuliskan narasi tahap pelaksanaan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',4),
(133,5,39,'kriteria','evaluasi','Evaluasi - C1','','Tuliskan narasi tahap evaluasi untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',5),
(134,5,39,'kriteria','pengendalian','Pengendalian - C1','','Tuliskan narasi tahap pengendalian untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',6),
(135,5,39,'kriteria','peningkatan','Peningkatan - C1','','Tuliskan narasi tahap peningkatan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',7),
(136,5,40,'kriteria','penetapan','Penetapan - C2','','Tuliskan narasi tahap penetapan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',8),
(137,5,40,'kriteria','pelaksanaan','Pelaksanaan - C2','','Tuliskan narasi tahap pelaksanaan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',9),
(138,5,40,'kriteria','evaluasi','Evaluasi - C2','','Tuliskan narasi tahap evaluasi untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',10),
(139,5,40,'kriteria','pengendalian','Pengendalian - C2','','Tuliskan narasi tahap pengendalian untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',11),
(140,5,40,'kriteria','peningkatan','Peningkatan - C2','','Tuliskan narasi tahap peningkatan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',12),
(141,5,41,'kriteria','penetapan','Penetapan - C3','','Tuliskan narasi tahap penetapan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',13),
(142,5,41,'kriteria','pelaksanaan','Pelaksanaan - C3','','Tuliskan narasi tahap pelaksanaan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',14),
(143,5,41,'kriteria','evaluasi','Evaluasi - C3','','Tuliskan narasi tahap evaluasi untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',15),
(144,5,41,'kriteria','pengendalian','Pengendalian - C3','','Tuliskan narasi tahap pengendalian untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',16),
(145,5,41,'kriteria','peningkatan','Peningkatan - C3','','Tuliskan narasi tahap peningkatan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',17),
(146,5,42,'kriteria','penetapan','Penetapan - C4','','Tuliskan narasi tahap penetapan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',18),
(147,5,42,'kriteria','pelaksanaan','Pelaksanaan - C4','','Tuliskan narasi tahap pelaksanaan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',19),
(148,5,42,'kriteria','evaluasi','Evaluasi - C4','','Tuliskan narasi tahap evaluasi untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',20),
(149,5,42,'kriteria','pengendalian','Pengendalian - C4','','Tuliskan narasi tahap pengendalian untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',21),
(150,5,42,'kriteria','peningkatan','Peningkatan - C4','','Tuliskan narasi tahap peningkatan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',22),
(151,5,43,'kriteria','penetapan','Penetapan - C5','','Tuliskan narasi tahap penetapan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',23),
(152,5,43,'kriteria','pelaksanaan','Pelaksanaan - C5','','Tuliskan narasi tahap pelaksanaan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',24),
(153,5,43,'kriteria','evaluasi','Evaluasi - C5','','Tuliskan narasi tahap evaluasi untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',25),
(154,5,43,'kriteria','pengendalian','Pengendalian - C5','','Tuliskan narasi tahap pengendalian untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',26),
(155,5,43,'kriteria','peningkatan','Peningkatan - C5','','Tuliskan narasi tahap peningkatan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',27),
(156,5,44,'kriteria','penetapan','Penetapan - C6','','Tuliskan narasi tahap penetapan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',28),
(157,5,44,'kriteria','pelaksanaan','Pelaksanaan - C6','','Tuliskan narasi tahap pelaksanaan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',29),
(158,5,44,'kriteria','evaluasi','Evaluasi - C6','','Tuliskan narasi tahap evaluasi untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',30),
(159,5,44,'kriteria','pengendalian','Pengendalian - C6','','Tuliskan narasi tahap pengendalian untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',31),
(160,5,44,'kriteria','peningkatan','Peningkatan - C6','','Tuliskan narasi tahap peningkatan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',32);

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13,'2026-01-01-000010','App\\Database\\Migrations\\AddLinkBuktiToButirSkor','default','App',1779416333,3),
(14,'2026-01-01-000011','App\\Database\\Migrations\\CreateBorangTables','default','App',1779557512,4),
(15,'2026-01-01-000012','App\\Database\\Migrations\\CreateDokumenEviden','default','App',1779557512,4),
(16,'2026-01-01-000013','App\\Database\\Migrations\\AddLinkBuktiTridharma','default','App',1779557513,4);

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
  `link_bukti` varchar(500) DEFAULT NULL,
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

/*Table structure for table `pengembangan_dtpr` */

DROP TABLE IF EXISTS `pengembangan_dtpr`;

CREATE TABLE `pengembangan_dtpr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `nama_dtpr` varchar(150) NOT NULL,
  `jenis_pengembangan` varchar(200) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `link_bukti` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengembangan_dtpr_prodi_id_foreign` (`prodi_id`),
  KEY `pengembangan_dtpr_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  CONSTRAINT `pengembangan_dtpr_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengembangan_dtpr_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pengembangan_dtpr` */

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
  `link_bukti` varchar(500) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prodi_instrumen` */

insert  into `prodi_instrumen`(`id`,`prodi_id`,`lam_instrumen_id`,`periode_mulai`,`aktif`) values 
(3,5,2,'2026-05-22',1),
(8,7,5,'2026-05-23',1),
(9,8,5,'2026-05-23',1);

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

/*Table structure for table `rekognisi_lulusan` */

DROP TABLE IF EXISTS `rekognisi_lulusan`;

CREATE TABLE `rekognisi_lulusan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tahun_akademik_id` int(10) unsigned DEFAULT NULL,
  `sumber` varchar(50) NOT NULL,
  `jenis_pengakuan` varchar(250) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `link_bukti` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rekognisi_lulusan_prodi_id_foreign` (`prodi_id`),
  KEY `rekognisi_lulusan_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  CONSTRAINT `rekognisi_lulusan_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rekognisi_lulusan_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `rekognisi_lulusan` */

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

/*Table structure for table `spmi_unit` */

DROP TABLE IF EXISTS `spmi_unit`;

CREATE TABLE `spmi_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `nama_unit` varchar(150) NOT NULL,
  `dokumen_spmi` varchar(200) DEFAULT NULL,
  `jml_auditor` int(11) NOT NULL DEFAULT 0,
  `auditor_certified` int(11) NOT NULL DEFAULT 0,
  `auditor_non_certified` int(11) NOT NULL DEFAULT 0,
  `frekuensi_audit` int(11) NOT NULL DEFAULT 0,
  `link_bukti` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spmi_unit_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `spmi_unit_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `spmi_unit` */

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

/*Table structure for table `tata_kelola_si` */

DROP TABLE IF EXISTS `tata_kelola_si`;

CREATE TABLE `tata_kelola_si` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `nama_sistem` varchar(200) NOT NULL,
  `akses` varchar(30) DEFAULT NULL,
  `unit_pengelola` varchar(150) DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tata_kelola_si_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `tata_kelola_si_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tata_kelola_si` */

/*Table structure for table `tata_pamong` */

DROP TABLE IF EXISTS `tata_pamong`;

CREATE TABLE `tata_pamong` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodi_id` int(10) unsigned NOT NULL,
  `unit_kerja` varchar(150) NOT NULL,
  `nama_pejabat` varchar(150) NOT NULL,
  `jabatan` varchar(150) DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `jabatan_fungsional` varchar(100) DEFAULT NULL,
  `tupoksi` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tata_pamong_prodi_id_foreign` (`prodi_id`),
  CONSTRAINT `tata_pamong_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tata_pamong` */

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tendik` */

insert  into `tendik`(`id`,`prodi_id`,`nip`,`nama`,`jenis`,`pendidikan_terakhir`,`sertifikat_kompetensi`,`aktif`) values 
(1,7,NULL,'Muhammad Yusron Fathul Islam, A.md.Kom.','laboran','D3',NULL,1);

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
(1,'superadmin',NULL,NULL,0,'2026-05-23 18:10:05','2026-05-22 01:13:44','2026-05-22 01:13:44',NULL),
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
