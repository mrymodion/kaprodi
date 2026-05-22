/*
SQLyog Ultimate
MySQL - 10.4.32-MariaDB : Database - kaprodi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `kaprodi`;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_groups_users` */

insert  into `auth_groups_users`(`id`,`user_id`,`group`,`created_at`) values 
(1,1,'superadmin','2026-05-21 06:42:17'),
(2,3,'sekprodi','2026-05-21 06:43:23'),
(3,4,'gkm','2026-05-21 06:43:24'),
(4,5,'dosen','2026-05-21 06:43:24'),
(5,6,'tendik','2026-05-21 06:43:25'),
(6,7,'mahasiswa','2026-05-21 06:43:25'),
(7,8,'asesor','2026-05-21 06:43:26'),
(8,9,'auditor','2026-05-21 06:43:26');

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
(1,1,'email_password',NULL,'superadmin@utd.ac.id','$2y$12$TOYShsOx1OQxd3pRedAvX.BzI.g/BPcOSIAvRyZtWY29jKCu13Com',NULL,NULL,0,'2026-05-21 10:05:13','2026-05-21 06:42:17','2026-05-21 10:05:13'),
(2,2,'email_password',NULL,'kaprodi@utd.ac.id','$2y$12$J4vkoEAR0FoFe.c5jtk.Eues1VOtkIE97jqrIhWVoewxqqv9Wnthi',NULL,NULL,0,NULL,'2026-05-21 06:42:18','2026-05-21 06:42:18'),
(3,3,'email_password',NULL,'sekprodi@utd.ac.id','$2y$12$5j7Fr1YuFJoEPRkmy6kEG.iDglf/2nEjhkaUb3Cqricv.su335yI2',NULL,NULL,0,NULL,'2026-05-21 06:43:23','2026-05-21 06:43:23'),
(4,4,'email_password',NULL,'gkm@utd.ac.id','$2y$12$Q9PMlljPrWFYieKwK0bI1.dIRALkpYTVb29SoqMtMRJ3YxTv8CN5.',NULL,NULL,0,NULL,'2026-05-21 06:43:23','2026-05-21 06:43:24'),
(5,5,'email_password',NULL,'dosen@utd.ac.id','$2y$12$1.zoJ85EXWeX8FI9uwMvbukuugys7F18a8Wpb5MbtoLbp4mr.Qh0m',NULL,NULL,0,NULL,'2026-05-21 06:43:24','2026-05-21 06:43:24'),
(6,6,'email_password',NULL,'tendik@utd.ac.id','$2y$12$Knn8uhT.243mVi84vZGgL.54t6Xj63E3/iamYqlZXGjmAbZngUJ0e',NULL,NULL,0,NULL,'2026-05-21 06:43:25','2026-05-21 06:43:25'),
(7,7,'email_password',NULL,'mahasiswa@utd.ac.id','$2y$12$H5sAXjPRqS/g6H0B3JXCiOjbnLcdvof.EOayBWYitvZcHOOGkgkba',NULL,NULL,0,NULL,'2026-05-21 06:43:25','2026-05-21 06:43:25'),
(8,8,'email_password',NULL,'asesor@utd.ac.id','$2y$12$97ejxC.PFg1idIkKnLRGJOKEAGEgoMvivie0TanL/klEVHbcp11lG',NULL,NULL,0,NULL,'2026-05-21 06:43:26','2026-05-21 06:43:26'),
(9,9,'email_password',NULL,'auditor@utd.ac.id','$2y$12$LuwmW/qdH0ObVZbTMulr0eqbwf3HEMVV3NCkxqmurIMW9xRlQG1IC',NULL,NULL,0,NULL,'2026-05-21 06:43:26','2026-05-21 06:43:26');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `auth_logins` */

insert  into `auth_logins`(`id`,`ip_address`,`user_agent`,`id_type`,`identifier`,`user_id`,`date`,`success`) values 
(1,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-21 06:45:38',1),
(2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','email_password','superadmin@utd.ac.id',1,'2026-05-21 10:05:13',1);

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
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prodi_id_lam_butir_id_tahun_akademik_id` (`prodi_id`,`lam_butir_id`,`tahun_akademik_id`),
  KEY `idx_butir_skor_prodi_id` (`prodi_id`),
  KEY `idx_butir_skor_lam_butir_id` (`lam_butir_id`),
  KEY `idx_butir_skor_prodi_id_lam_butir_id` (`prodi_id`,`lam_butir_id`),
  CONSTRAINT `butir_skor_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `butir_skor_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1,7,'8849970018','','Mamok Andri Senubekti','Ir.','S.Kom., M.Kom.','L','Malang','1982-04-11','mamokandri@digitechuniversity.ac.id','081323945454','S2','Sistem Informasi, Data Science','linier','Asisten Ahli','7628/LL4/KP/2022','2022-11-28',0,'','tidak_tetap','6756574','60558151400','ZZcT_-oAAAAJ&hl=id','0000-0002-1893-0746',1,'2026-05-21 09:31:27','2026-05-21 11:23:38');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kerjasama` */

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kurikulum` */

insert  into `kurikulum`(`id`,`prodi_id`,`kode`,`nama`,`tahun_berlaku`,`aktif`,`created_at`) values 
(1,7,'K-2026','Kurikulum OBE 2026',2026,1,'2026-05-21 07:11:07'),
(2,7,'K-2026','Kurikulum OBE 2026',2026,0,'2026-05-21 07:11:44');

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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(82,9,'D.5','Butir D.5 [Peningkatan] - Output','[ISI SESUAI INSTRUMEN] Deskripsi butir D.5 mengacu pada Matriks Penilaian Kinerja Program Studi LAM INFOKOM 2.0 (2025), tahap PPEPP: Peningkatan, kategori IPO: output.','output',4.00,'kualitatif',NULL,NULL,5);

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
) ENGINE=InnoDB AUTO_INCREMENT=329 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(328,82,1,'Kurang','Kurang: Memenuhi sebagian aspek dengan bukti yang kurang memadai.',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lam_instrumen` */

insert  into `lam_instrumen`(`id`,`lam_lembaga_id`,`jenjang`,`versi`,`nama`,`tahun_terbit`,`driver_class`,`total_bobot`,`aktif`,`created_at`) values 
(1,1,'D3','2.0','Instrumen Akreditasi Program Studi 2.0 D3 (Informatika & Komputer)',2025,'Modules\\Akreditasi\\Drivers\\LamInfokomD3\\Driver',400.00,1,'2026-05-21 06:42:15');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(9,1,'D','Suplemen Program Studi','suplemen',20.00,9,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(31,1,'6','Kesesuaian Visi dan Misi','C6',NULL,31);

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(32,1,8,'kriteria','peningkatan','Peningkatan - C6','','Tuliskan narasi tahap peningkatan untuk kriteria C6 pada LED LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.',32);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `mahasiswa` */

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(1,'2020-12-28-223112','CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables','default','CodeIgniter\\Shield',1779345728,1),
(2,'2021-07-04-041948','CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable','default','CodeIgniter\\Settings',1779345728,1),
(3,'2021-11-14-143905','CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn','default','CodeIgniter\\Settings',1779345728,1),
(4,'2026-01-01-000001','App\\Database\\Migrations\\CreateMasterTables','default','App',1779345728,1),
(5,'2026-01-01-000002','App\\Database\\Migrations\\CreateAkreditasiMetaTables','default','App',1779345728,1),
(6,'2026-01-01-000003','App\\Database\\Migrations\\CreateSdmTables','default','App',1779345728,1),
(7,'2026-01-01-000004','App\\Database\\Migrations\\CreateTridharmaTables','default','App',1779345728,1),
(8,'2026-01-01-000005','App\\Database\\Migrations\\CreateKurikulumTables','default','App',1779345729,1),
(9,'2026-01-01-000006','App\\Database\\Migrations\\CreateSupportingTables','default','App',1779345729,1),
(10,'2026-01-01-000007','App\\Database\\Migrations\\CreateUserProdiMapping','default','App',1779345729,1),
(11,'2026-01-01-000008','App\\Database\\Migrations\\CreateLedNarasiTable','default','App',1779360680,2),
(12,'2026-01-01-000009','App\\Database\\Migrations\\AddPerformanceIndexes','default','App',1779360882,3);

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
(1,'UTD','Universitas Teknologi Digital','UTD',NULL,'Bandung','Jawa Barat','assets/logo-utd.png','https://utd.ac.id','2026-05-21 06:42:14','2026-05-21 06:42:14');

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
(1,1,'62201','Akuntansi','S1',NULL,NULL,'Baik Sekali',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(2,1,'62401','Akuntansi','D3',NULL,NULL,'Baik Sekali',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(3,1,'90241','Desain Komunikasi Visual','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(4,1,'74201','Hukum','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(5,1,'55200','Informatika','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(6,1,'61201','Manajemen','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(7,1,'57401','Manajemen Informatika','D3',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(8,1,'57472','Manajemen Informatika (Kampus Kota Tegal)','D3',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(9,1,'93104','Manajemen Inovasi','S2',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(10,1,'61405','Manajemen Perusahaan','D3',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14'),
(11,1,'26201','Teknik Industri','S1',NULL,NULL,'Baik',NULL,NULL,NULL,'aktif','2026-05-21 06:42:14','2026-05-21 06:42:14');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `prodi_instrumen` */

insert  into `prodi_instrumen`(`id`,`prodi_id`,`lam_instrumen_id`,`periode_mulai`,`aktif`) values 
(1,7,1,'2026-05-21',1),
(2,8,1,'2026-05-21',1);

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
(1,1,'-','-','tidak_ada','2026-05-21 06:42:14','2026-05-21 06:42:14');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_prodi` */

insert  into `user_prodi`(`id`,`user_id`,`prodi_id`,`peran`,`is_default`,`created_at`) values 
(1,3,7,'sekprodi',1,'2026-05-21 06:43:23'),
(2,4,7,'gkm',1,'2026-05-21 06:43:24'),
(3,5,7,'dosen',1,'2026-05-21 06:43:24'),
(4,6,7,'tendik',1,'2026-05-21 06:43:25'),
(5,7,7,'mahasiswa',1,'2026-05-21 06:43:25');

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
(1,'superadmin',NULL,NULL,0,'2026-05-21 11:23:49','2026-05-21 06:42:17','2026-05-21 06:42:17',NULL),
(2,'kaprodi',NULL,NULL,0,NULL,'2026-05-21 06:42:17','2026-05-21 06:42:17',NULL),
(3,'sekprodi',NULL,NULL,0,NULL,'2026-05-21 06:43:23','2026-05-21 06:43:23',NULL),
(4,'gkm',NULL,NULL,0,NULL,'2026-05-21 06:43:23','2026-05-21 06:43:23',NULL),
(5,'dosen',NULL,NULL,0,NULL,'2026-05-21 06:43:24','2026-05-21 06:43:24',NULL),
(6,'tendik',NULL,NULL,0,NULL,'2026-05-21 06:43:24','2026-05-21 06:43:24',NULL),
(7,'mahasiswa',NULL,NULL,0,NULL,'2026-05-21 06:43:25','2026-05-21 06:43:25',NULL),
(8,'asesor',NULL,NULL,0,NULL,'2026-05-21 06:43:25','2026-05-21 06:43:25',NULL),
(9,'auditor',NULL,NULL,0,NULL,'2026-05-21 06:43:26','2026-05-21 06:43:26',NULL);

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
