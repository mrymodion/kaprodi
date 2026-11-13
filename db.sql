-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2026 at 02:35 PM
-- Server version: 10.11.19-MariaDB-cll-lve
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ylvbhilz_kaprodi`
--

-- --------------------------------------------------------

--
-- Table structure for table `ami_temuan`
--

CREATE TABLE `ami_temuan` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `tanggal_audit` date DEFAULT NULL,
  `lingkup` varchar(200) NOT NULL,
  `kriteria` varchar(50) DEFAULT NULL,
  `standar_mutu_id` int(10) UNSIGNED DEFAULT NULL,
  `kategori` enum('mayor','minor','observasi') NOT NULL DEFAULT 'minor',
  `deskripsi` text NOT NULL,
  `akar_masalah` text DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `status` enum('terbuka','proses','selesai') NOT NULL DEFAULT 'terbuka',
  `auditor` varchar(150) DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `tanggal_verifikasi` date DEFAULT NULL,
  `verifikator` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `prodi_id` int(10) UNSIGNED DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `tabel` varchar(100) DEFAULT NULL,
  `record_id` varchar(64) DEFAULT NULL,
  `before_json` longtext DEFAULT NULL,
  `after_json` longtext DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `ua` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'superadmin', '2026-05-22 01:13:45'),
(2, 2, 'kaprodi', '2026-05-22 01:13:45'),
(3, 3, 'sekprodi', '2026-05-22 01:13:46'),
(4, 4, 'gkm', '2026-05-22 01:13:46'),
(5, 5, 'dosen', '2026-05-22 01:13:47'),
(6, 6, 'tendik', '2026-05-22 01:13:47'),
(7, 7, 'mahasiswa', '2026-05-22 01:13:48'),
(8, 8, 'asesor', '2026-05-22 01:13:48'),
(9, 9, 'auditor', '2026-05-22 01:13:48'),
(12, 12, 'dosen', '2026-05-24 14:01:08'),
(13, 11, 'kaprodi', '2026-05-25 11:04:03'),
(14, 10, 'dosen', '2026-05-26 01:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', NULL, 'superadmin@utd.ac.id', '$2y$12$Sd0YcAUJVaQ.oaleI0ZpOelrfgz8sJxdf0HanQ177r2gcQzN3y0DC', NULL, NULL, 0, '2026-06-03 07:00:20', '2026-05-22 01:13:44', '2026-06-03 07:00:20'),
(2, 2, 'email_password', NULL, 'kaprodi@utd.ac.id', '$2y$12$5Iga90UBcNeshBrGnpN66ORKFu/2MUpWYdSFUH0m7KPhXdweAYPdC', NULL, NULL, 0, NULL, '2026-05-22 01:13:45', '2026-05-22 01:13:45'),
(3, 3, 'email_password', NULL, 'sekprodi@utd.ac.id', '$2y$12$S0vMeOL2UWTg2Hztb4aLHukU4x.LgaJ7uwiMUFycsvZ.XGbdvzdA.', NULL, NULL, 0, NULL, '2026-05-22 01:13:45', '2026-05-22 01:13:46'),
(4, 4, 'email_password', NULL, 'gkm@utd.ac.id', '$2y$12$R/48TR/8WPYg5nH9w0uswe5mGwh.r2kHNSs/GB4/N0kfKG/OWgPXm', NULL, NULL, 0, NULL, '2026-05-22 01:13:46', '2026-05-22 01:13:46'),
(5, 5, 'email_password', NULL, 'dosen@utd.ac.id', '$2y$12$xPltwspDaB6kZTwL4LRBDOnROxi9Uw4rKRPf8hNTMfWnLGajlqAbW', NULL, NULL, 0, NULL, '2026-05-22 01:13:46', '2026-05-22 01:13:47'),
(6, 6, 'email_password', NULL, 'tendik@utd.ac.id', '$2y$12$AX6BpeQQIagQ9t0ZgM24Au9GmRogoqYWt.uZ/un6Yld5qhpBz7O5q', NULL, NULL, 0, NULL, '2026-05-22 01:13:47', '2026-05-22 01:13:47'),
(7, 7, 'email_password', NULL, 'mahasiswa@utd.ac.id', '$2y$12$l4pkVPDOrby/jeph8YrQhOBmSr1oqsvJCJ3AR9wEEOYIyyts7INo.', NULL, NULL, 0, NULL, '2026-05-22 01:13:47', '2026-05-22 01:13:48'),
(8, 8, 'email_password', NULL, 'asesor@utd.ac.id', '$2y$12$2vO9Pu.hzucd2OH5enbmK.rSbNsvVm9NqJtQ4aUASg9/0T1s6MWLK', NULL, NULL, 0, NULL, '2026-05-22 01:13:48', '2026-05-22 01:13:48'),
(9, 9, 'email_password', NULL, 'auditor@utd.ac.id', '$2y$12$W6zefi1Wj02OAhue7C8XCOcdGXodEBeJnx.LeF/2kI3RCmj7D3tBS', NULL, NULL, 0, NULL, '2026-05-22 01:13:48', '2026-05-22 01:13:48'),
(10, 10, 'email_password', NULL, 'mamokandri@digitechuniversity.ac.id', '$2y$12$1sJdO3U89fWgqzwUDX9KjOM59E4JI9K0LhMxbFOWTfO.X6SSV4Hii', NULL, NULL, 0, '2026-05-31 02:56:01', '2026-05-24 13:32:54', '2026-05-31 02:56:01'),
(11, 11, 'email_password', NULL, 'informatika-d3@digitechuniversity.ac.id', '$2y$12$EtQLITb2t2ZyY.Wc5Z9SqOzo6YIO7BQY.eFwsd6fQe13Gd9HShqnO', NULL, NULL, 0, '2026-11-13 06:38:56', '2026-05-24 13:34:59', '2026-11-13 06:38:56'),
(12, 12, 'email_password', NULL, 'darsiti@digitechuniversity.ac.id', '$2y$12$oWhImPGYYbwdjW8zM4uJZuo.m.xRlj/34Z34BCR8eX.pbYftFqDPu', NULL, NULL, 0, '2026-05-24 15:03:32', '2026-05-24 14:01:07', '2026-05-24 15:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', NULL, '2026-05-22 01:13:17', 0),
(2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-22 01:14:01', 1),
(3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-22 06:24:24', 1),
(4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-23 17:30:44', 1),
(5, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 04:35:19', 1),
(6, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 09:18:22', 1),
(7, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 13:30:16', 1),
(8, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 13:33:14', 1),
(9, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 13:33:28', 1),
(10, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-24 13:35:06', 1),
(11, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 13:35:41', 1),
(12, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 13:41:25', 1),
(13, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 13:45:38', 1),
(14, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 13:46:38', 1),
(15, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-24 13:55:23', 1),
(16, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'darsiti@digitechuniversity.ac.id', 12, '2026-05-24 14:01:35', 1),
(17, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-24 14:02:13', 1),
(18, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 14:15:27', 1),
(19, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'darsiti@digitechuniversity.ac.id', 12, '2026-05-24 14:28:25', 1),
(20, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 14:43:57', 1),
(21, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'darsiti@digitechuniversity.ac.id', 12, '2026-05-24 15:03:32', 1),
(22, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 15:04:27', 1),
(23, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 16:24:22', 1),
(24, '103.41.250.239', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 21:24:42', 1),
(25, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-24 23:09:07', 1),
(26, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-24 23:09:16', 1),
(27, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-25 10:39:57', 1),
(28, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-26 00:44:03', 1),
(29, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-26 08:32:28', 1),
(30, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-26 01:55:57', 1),
(31, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-26 01:56:04', 1),
(32, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-26 01:57:10', 1),
(33, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-26 02:22:06', 1),
(34, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-26 02:59:41', 1),
(35, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-26 03:46:31', 1),
(36, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-26 03:47:36', 1),
(37, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-26 06:21:19', 1),
(38, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-29 00:13:44', 1),
(39, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-29 01:53:38', 1),
(40, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-29 01:59:34', 1),
(41, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-29 02:09:30', 1),
(42, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-29 02:09:56', 1),
(43, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-29 06:24:21', 1),
(44, '114.10.149.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-30 02:11:20', 1),
(45, '103.182.209.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-05-30 02:20:56', 1),
(46, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-31 02:52:18', 1),
(47, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'mamokandri@digitechuniversity.ac.id', 10, '2026-05-31 02:56:01', 1),
(48, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-05-31 02:56:37', 1),
(49, '103.182.209.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'email_password', 'superadmin@utd.ac.id', 1, '2026-06-03 07:00:20', 1),
(50, '158.140.182.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'email_password', 'kodratmahatma@digitechuniversity.ac.id', NULL, '2026-07-05 09:47:35', 0),
(51, '158.140.182.3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'email_password', 'kodratmahatma@digitechuniversity.ac.id', NULL, '2026-07-05 09:47:40', 0),
(52, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'mrymodion@gmail.com', NULL, '2026-11-13 06:38:29', 0),
(53, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', NULL, '2026-11-13 06:38:44', 0),
(54, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', NULL, '2026-11-13 06:38:50', 0),
(55, '103.41.250.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'email_password', 'informatika-d3@digitechuniversity.ac.id', 11, '2026-11-13 06:38:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `butir_skor`
--

CREATE TABLE `butir_skor` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `lam_butir_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `skor_auto` decimal(3,2) DEFAULT NULL,
  `skor_manual` decimal(3,2) DEFAULT NULL,
  `skor_final` decimal(3,2) DEFAULT NULL,
  `sumber_data_json` longtext DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `butir_skor`
--

INSERT INTO `butir_skor` (`id`, `prodi_id`, `lam_butir_id`, `tahun_akademik_id`, `skor_auto`, `skor_manual`, `skor_final`, `sumber_data_json`, `catatan`, `link_bukti`, `updated_at`, `updated_by`) VALUES
(8, 7, 427, NULL, 2.20, NULL, 2.20, NULL, NULL, NULL, '2026-05-24 22:13:31', NULL),
(9, 7, 427, 5, 2.20, NULL, 2.20, NULL, NULL, NULL, '2026-05-29 15:05:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cpl`
--

CREATE TABLE `cpl` (
  `id` int(10) UNSIGNED NOT NULL,
  `kurikulum_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `aspek` enum('sikap','pengetahuan','keterampilan_umum','keterampilan_khusus') NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cpl_ketercapaian`
--

CREATE TABLE `cpl_ketercapaian` (
  `id` int(10) UNSIGNED NOT NULL,
  `mata_kuliah_id` int(10) UNSIGNED NOT NULL,
  `cpl_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED NOT NULL,
  `persen_tercapai` decimal(5,2) NOT NULL,
  `jumlah_mhs` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cpmk`
--

CREATE TABLE `cpmk` (
  `id` int(10) UNSIGNED NOT NULL,
  `mata_kuliah_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_eviden`
--

CREATE TABLE `dokumen_eviden` (
  `id` int(10) UNSIGNED NOT NULL,
  `ref_table` varchar(20) NOT NULL,
  `ref_id` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `file` varchar(255) NOT NULL,
  `nama_asli` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumen_eviden`
--

INSERT INTO `dokumen_eviden` (`id`, `ref_table`, `ref_id`, `jenis`, `file`, `nama_asli`, `created_at`) VALUES
(1, 'dosen', 1, 'ijazah', 'b59a1c0abf0385bd1.pdf', 'IJASAH S2 compress.pdf', '2026-05-23 18:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_mutu`
--

CREATE TABLE `dokumen_mutu` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `jenis` enum('kebijakan','manual','standar','formulir','sop','lainnya') NOT NULL DEFAULT 'standar',
  `kode` varchar(100) DEFAULT NULL,
  `judul` varchar(250) NOT NULL,
  `versi` varchar(30) DEFAULT NULL,
  `tanggal_berlaku` date DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
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
  `h_index` int(11) DEFAULT NULL,
  `i10_index` int(11) DEFAULT NULL,
  `sitasi_total` int(11) DEFAULT NULL,
  `karya_total` int(11) DEFAULT NULL,
  `metrik_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `prodi_id`, `user_id`, `nidn`, `nip`, `nama`, `gelar_depan`, `gelar_belakang`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `email`, `no_hp`, `pendidikan_terakhir`, `bidang_keahlian`, `linieritas`, `jabatan_akademik`, `no_sk_jad`, `tgl_sk_jad`, `sertifikat_pendidik`, `no_serdos`, `status_kepegawaian`, `sinta_id`, `scopus_id`, `google_scholar_id`, `orcid`, `aktif`, `created_at`, `updated_at`, `h_index`, `i10_index`, `sitasi_total`, `karya_total`, `metrik_updated_at`) VALUES
(1, 7, 10, '8849970018', '', 'Mamok Andri Senubekti', 'Ir.', 'S.Kom., M.Kom.', 'L', 'Malang', '1982-04-11', 'mamokandri@digitechuniversity.ac.id', '081323945454', 'S2', 'Sistem Informasi, Data Science', 'linier', 'Asisten Ahli', '7628/LL4/KP/2022', '2022-11-28', 0, '', 'tidak_tetap', '6756574', '60558151400', 'ZZcT_-oAAAAJ&hl', '0000-0002-1893-0746', 1, '2026-05-22 01:24:44', '2026-05-25 10:43:17', 3, 2, 78, 29, '2026-05-25 10:43:16'),
(2, 7, 12, '0424098204', '', 'Darsiti', '', 'S.Kom.,M.Kom.', 'P', '', '0000-00-00', 'darsiti@digitechuniversity.ac.id', '085220977847', 'S2', 'Sistem Informasi', 'linier', 'Lektor', '', '0000-00-00', 1, '', 'tetap', '', '', '', '', 1, '2026-05-24 14:00:51', '2026-05-24 15:12:54', NULL, NULL, NULL, NULL, NULL),
(3, 7, NULL, NULL, '', 'Mohammad Syafiuddin Usman', '', 'S.T., M.Kom.', 'L', '', '0000-00-00', 'Syafiuddin@digitechuniversity.ac.id', '082298872659', 'S2', '', 'linier', 'Asisten Ahli', '', '0000-00-00', 0, '', 'tetap', '', '', '', '', 1, '2026-05-24 14:04:00', '2026-05-24 14:04:00', NULL, NULL, NULL, NULL, NULL),
(4, 7, NULL, NULL, '', 'Kodrat Mahatma', '', 'S.T., M.Kom.', 'L', '', '0000-00-00', 'kodratmahatma@digitechuniversity.ac.id', '', 'S2', '', 'linier', 'Tenaga Pengajar', '', '0000-00-00', 0, '', 'tetap', '', '', '', '', 1, '2026-05-24 15:11:36', '2026-05-24 15:11:36', NULL, NULL, NULL, NULL, NULL),
(5, 7, NULL, NULL, '', 'Fadzar Rusghana', '', 'S.Si., M.M.', 'L', '', '0000-00-00', 'fadzarrusghana@digitechuniversity.ac.id', '', 'S2', '', 'tidak_linier', 'Tenaga Pengajar', '', '0000-00-00', 0, '', 'tetap', '', '', '', '', 1, '2026-05-24 15:12:30', '2026-05-24 15:12:30', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dosen_ewmp`
--

CREATE TABLE `dosen_ewmp` (
  `id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED NOT NULL,
  `sks_pendidikan_ps` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pendidikan_ps_lain_pt` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pendidikan_pt_lain` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_penelitian` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_pkm` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sks_tugas_tambahan` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_sks` decimal(5,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evidence`
--

CREATE TABLE `evidence` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_mime` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `tag_butir_json` longtext DEFAULT NULL,
  `tag_tabel_lkps_json` longtext DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eviden_ppepp`
--

CREATE TABLE `eviden_ppepp` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `lam_butir_id` int(10) UNSIGNED NOT NULL,
  `penetapan` text DEFAULT NULL,
  `pelaksanaan` text DEFAULT NULL,
  `evaluasi` text DEFAULT NULL,
  `pengendalian` text DEFAULT NULL,
  `peningkatan` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eviden_ppepp`
--

INSERT INTO `eviden_ppepp` (`id`, `prodi_id`, `lam_butir_id`, `penetapan`, `pelaksanaan`, `evaluasi`, `pengendalian`, `peningkatan`, `updated_at`, `updated_by`) VALUES
(1, 7, 411, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:26:18', 11),
(2, 7, 420, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:26:38', 11),
(3, 7, 412, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:34:43', 11),
(4, 7, 413, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:40:52', 11),
(5, 7, 426, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:27', 11),
(6, 7, 430, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:27', 11),
(7, 7, 443, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:27', 11),
(8, 7, 444, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:27', 11),
(9, 7, 445, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:27', 11),
(10, 7, 446, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:28', 11),
(11, 7, 447, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:28', 11),
(12, 7, 448, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:28', 11),
(13, 7, 449, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:30', 11),
(14, 7, 450, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:30', 11),
(15, 7, 451, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:30', 11),
(16, 7, 452, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(17, 7, 453, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(18, 7, 454, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(19, 7, 455, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(20, 7, 456, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(21, 7, 457, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:36', 11),
(22, 7, 458, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(23, 7, 459, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(24, 7, 460, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(25, 7, 461, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(26, 7, 462, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(27, 7, 463, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(28, 7, 464, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(29, 7, 465, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(30, 7, 466, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(31, 7, 467, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(32, 7, 468, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(33, 7, 469, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(34, 7, 470, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(35, 7, 471, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11),
(36, 7, 472, NULL, NULL, NULL, NULL, NULL, '2026-05-29 02:48:39', 11);

-- --------------------------------------------------------

--
-- Table structure for table `eviden_ppepp_bukti`
--

CREATE TABLE `eviden_ppepp_bukti` (
  `id` int(10) UNSIGNED NOT NULL,
  `eviden_id` int(10) UNSIGNED NOT NULL,
  `fase` enum('penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan') NOT NULL,
  `label` varchar(250) NOT NULL,
  `url` text NOT NULL,
  `sumber` varchar(40) NOT NULL DEFAULT 'manual',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eviden_ppepp_bukti`
--

INSERT INTO `eviden_ppepp_bukti` (`id`, `eviden_id`, `fase`, `label`, `url`, `sumber`, `created_at`) VALUES
(1, 9, 'pelaksanaan', 'Publikasi: An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability (2026)', 'https://doi.org/10.65780/bima.v1i2.7', 'publikasi', '2026-05-29 02:48:27'),
(2, 9, 'pelaksanaan', 'Publikasi: Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases:… (2026)', 'https://doi.org/10.1063/5.0322114', 'publikasi', '2026-05-29 02:48:27'),
(3, 9, 'pelaksanaan', 'Publikasi: Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehous… (2026)', 'https://doi.org/10.37012/jtik.v12i1.3447', 'publikasi', '2026-05-29 02:48:27'),
(4, 9, 'pelaksanaan', 'Publikasi: Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Al… (2025)', 'https://doi.org/10.62565/tekno.v3i1.55', 'publikasi', '2026-05-29 02:48:27'),
(5, 9, 'pelaksanaan', 'Publikasi: Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif (2025)', 'https://doi.org/10.51903/2gxcdc03', 'publikasi', '2026-05-29 02:48:27'),
(6, 9, 'pelaksanaan', 'Publikasi: Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak (2025)', 'https://doi.org/10.62951/router.v3i2.411', 'publikasi', '2026-05-29 02:48:28'),
(7, 9, 'pelaksanaan', 'Publikasi: Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Stud… (2025)', 'https://doi.org/10.35194/mji.v17i1.5139', 'publikasi', '2026-05-29 02:48:28'),
(8, 9, 'pelaksanaan', 'Publikasi: Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia (2025)', 'https://doi.org/10.55606/jtmei.v4i2.5010', 'publikasi', '2026-05-29 02:48:28'),
(9, 9, 'pelaksanaan', 'Publikasi: PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA (2025)', 'https://doi.org/10.33884/jif.v13i02.10147', 'publikasi', '2026-05-29 02:48:28'),
(10, 9, 'pelaksanaan', 'Publikasi: Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Band… (2025)', 'https://doi.org/10.33633/joins.v10i1.12668', 'publikasi', '2026-05-29 02:48:28'),
(11, 9, 'pelaksanaan', 'Publikasi: PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BA… (2025)', 'https://doi.org/10.33627/pk.v8i1.2311', 'publikasi', '2026-05-29 02:48:28'),
(12, 9, 'pelaksanaan', 'Publikasi: Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Mengg… (2025)', 'https://doi.org/10.62565/tekno.v3i1.87', 'publikasi', '2026-05-29 02:48:28'),
(13, 9, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB (2025)', 'https://doi.org/10.36040/jati.v9i4.13886', 'publikasi', '2026-05-29 02:48:28'),
(14, 9, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL (2025)', 'https://doi.org/10.36040/jati.v9i4.14093', 'publikasi', '2026-05-29 02:48:28'),
(15, 9, 'pelaksanaan', 'Publikasi: Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping (2025)', 'https://doi.org/10.53513/jsk.v8i2.11650', 'publikasi', '2026-05-29 02:48:28'),
(16, 9, 'pelaksanaan', 'Publikasi: PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE (2025)', 'https://doi.org/10.36040/jati.v9i5.14877', 'publikasi', '2026-05-29 02:48:28'),
(17, 9, 'pelaksanaan', 'Publikasi: PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GU… (2025)', 'https://doi.org/10.36040/jati.v9i5.15045', 'publikasi', '2026-05-29 02:48:28'),
(18, 9, 'pelaksanaan', 'Publikasi: PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL (2025)', 'https://doi.org/10.36040/jati.v9i5.14965', 'publikasi', '2026-05-29 02:48:28'),
(19, 9, 'pelaksanaan', 'Publikasi: Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes (2024)', 'https://doi.org/10.62565/tekno.v2i2.52', 'publikasi', '2026-05-29 02:48:28'),
(20, 9, 'pelaksanaan', 'Publikasi: Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas (2024)', 'https://doi.org/10.59024/faedah.v2i1.723', 'publikasi', '2026-05-29 02:48:28'),
(21, 9, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan (2024)', 'https://doi.org/10.55606/kreatif.v4i1.2812', 'publikasi', '2026-05-29 02:48:28'),
(22, 9, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food (2024)', 'https://doi.org/10.61132/bumi.v2i1.52', 'publikasi', '2026-05-29 02:48:28'),
(23, 9, 'pelaksanaan', 'Publikasi: Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur (2024)', 'https://doi.org/10.62565/tekno.v2i1.14', 'publikasi', '2026-05-29 02:48:28'),
(24, 9, 'pelaksanaan', 'Publikasi: Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python (2024)', 'https://doi.org/10.62565/tekno.v2i1.23', 'publikasi', '2026-05-29 02:48:28'),
(25, 9, 'pelaksanaan', 'Publikasi: Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions (2024)', 'https://doi.org/10.62565/tekno.v2i1.22', 'publikasi', '2026-05-29 02:48:28'),
(26, 9, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma (2024)', 'https://doi.org/10.54914/jit.v10i1.1001', 'publikasi', '2026-05-29 02:48:28'),
(27, 9, 'pelaksanaan', 'Publikasi: DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP (2024)', 'https://doi.org/10.36040/jati.v8i5.10713', 'publikasi', '2026-05-29 02:48:28'),
(28, 9, 'pelaksanaan', 'Publikasi: PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB (2024)', 'https://doi.org/10.36040/jati.v8i5.10641', 'publikasi', '2026-05-29 02:48:28'),
(29, 9, 'pelaksanaan', 'Publikasi: PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE (2024)', 'https://doi.org/10.36040/jati.v8i5.11094', 'publikasi', '2026-05-29 02:48:28'),
(30, 9, 'pelaksanaan', 'Publikasi: Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin (2024)', 'https://doi.org/10.62565/keadilan.v2i2.51', 'publikasi', '2026-05-29 02:48:28'),
(31, 9, 'pelaksanaan', 'Publikasi: OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP (2024)', 'https://doi.org/10.36040/jati.v8i6.11603', 'publikasi', '2026-05-29 02:48:28'),
(32, 9, 'pelaksanaan', 'Publikasi: IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGK… (2024)', 'https://doi.org/10.36040/jati.v8i6.12148', 'publikasi', '2026-05-29 02:48:28'),
(33, 9, 'pelaksanaan', 'Publikasi: Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH) (2023)', 'https://doi.org/10.62565/tekno.v1i1.2', 'publikasi', '2026-05-29 02:48:28'),
(34, 9, 'pelaksanaan', 'Publikasi: Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Keluraha… (2023)', 'https://doi.org/10.62565/tekno.v1i1.5', 'publikasi', '2026-05-29 02:48:28'),
(35, 9, 'pelaksanaan', 'Publikasi: Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners (2023)', 'https://doi.org/10.62565/tekno.v1i1.3', 'publikasi', '2026-05-29 02:48:28'),
(36, 9, 'pelaksanaan', 'Publikasi: Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet) (2023)', 'https://doi.org/10.62565/tekno.v1i1.1', 'publikasi', '2026-05-29 02:48:28'),
(37, 9, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD (2023)', 'https://doi.org/10.62565/tekno.v1i1.4', 'publikasi', '2026-05-29 02:48:28'),
(38, 9, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4670', 'publikasi', '2026-05-29 02:48:28'),
(39, 9, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4661', 'publikasi', '2026-05-29 02:48:28'),
(40, 9, 'pelaksanaan', 'Publikasi: NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4559', 'publikasi', '2026-05-29 02:48:28'),
(41, 9, 'pelaksanaan', 'Publikasi: Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung (2022)', 'https://doi.org/10.55916/smart.v18i1.63', 'publikasi', '2026-05-29 02:48:28'),
(42, 9, 'pelaksanaan', 'Publikasi: PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5 (2022)', 'https://doi.org/10.25134/nuansa.v16i2.5834', 'publikasi', '2026-05-29 02:48:28'),
(43, 9, 'pelaksanaan', 'Publikasi: DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG) (2021)', 'https://doi.org/10.36456/best.vol3.no2.4221', 'publikasi', '2026-05-29 02:48:28'),
(44, 12, 'pelaksanaan', 'Publikasi: An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability (2026)', 'https://doi.org/10.65780/bima.v1i2.7', 'publikasi', '2026-05-29 02:48:28'),
(45, 12, 'pelaksanaan', 'Publikasi: Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases:… (2026)', 'https://doi.org/10.1063/5.0322114', 'publikasi', '2026-05-29 02:48:28'),
(46, 12, 'pelaksanaan', 'Publikasi: Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehous… (2026)', 'https://doi.org/10.37012/jtik.v12i1.3447', 'publikasi', '2026-05-29 02:48:28'),
(47, 12, 'pelaksanaan', 'Publikasi: Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Al… (2025)', 'https://doi.org/10.62565/tekno.v3i1.55', 'publikasi', '2026-05-29 02:48:28'),
(48, 12, 'pelaksanaan', 'Publikasi: Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif (2025)', 'https://doi.org/10.51903/2gxcdc03', 'publikasi', '2026-05-29 02:48:28'),
(49, 12, 'pelaksanaan', 'Publikasi: Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak (2025)', 'https://doi.org/10.62951/router.v3i2.411', 'publikasi', '2026-05-29 02:48:28'),
(50, 12, 'pelaksanaan', 'Publikasi: Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Stud… (2025)', 'https://doi.org/10.35194/mji.v17i1.5139', 'publikasi', '2026-05-29 02:48:28'),
(51, 12, 'pelaksanaan', 'Publikasi: Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia (2025)', 'https://doi.org/10.55606/jtmei.v4i2.5010', 'publikasi', '2026-05-29 02:48:28'),
(52, 12, 'pelaksanaan', 'Publikasi: PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA (2025)', 'https://doi.org/10.33884/jif.v13i02.10147', 'publikasi', '2026-05-29 02:48:28'),
(53, 12, 'pelaksanaan', 'Publikasi: Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Band… (2025)', 'https://doi.org/10.33633/joins.v10i1.12668', 'publikasi', '2026-05-29 02:48:28'),
(54, 12, 'pelaksanaan', 'Publikasi: PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BA… (2025)', 'https://doi.org/10.33627/pk.v8i1.2311', 'publikasi', '2026-05-29 02:48:29'),
(55, 12, 'pelaksanaan', 'Publikasi: Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Mengg… (2025)', 'https://doi.org/10.62565/tekno.v3i1.87', 'publikasi', '2026-05-29 02:48:29'),
(56, 12, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB (2025)', 'https://doi.org/10.36040/jati.v9i4.13886', 'publikasi', '2026-05-29 02:48:29'),
(57, 12, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL (2025)', 'https://doi.org/10.36040/jati.v9i4.14093', 'publikasi', '2026-05-29 02:48:29'),
(58, 12, 'pelaksanaan', 'Publikasi: Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping (2025)', 'https://doi.org/10.53513/jsk.v8i2.11650', 'publikasi', '2026-05-29 02:48:29'),
(59, 12, 'pelaksanaan', 'Publikasi: PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE (2025)', 'https://doi.org/10.36040/jati.v9i5.14877', 'publikasi', '2026-05-29 02:48:29'),
(60, 12, 'pelaksanaan', 'Publikasi: PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GU… (2025)', 'https://doi.org/10.36040/jati.v9i5.15045', 'publikasi', '2026-05-29 02:48:29'),
(61, 12, 'pelaksanaan', 'Publikasi: PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL (2025)', 'https://doi.org/10.36040/jati.v9i5.14965', 'publikasi', '2026-05-29 02:48:29'),
(62, 12, 'pelaksanaan', 'Publikasi: Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes (2024)', 'https://doi.org/10.62565/tekno.v2i2.52', 'publikasi', '2026-05-29 02:48:29'),
(63, 12, 'pelaksanaan', 'Publikasi: Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas (2024)', 'https://doi.org/10.59024/faedah.v2i1.723', 'publikasi', '2026-05-29 02:48:29'),
(64, 12, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan (2024)', 'https://doi.org/10.55606/kreatif.v4i1.2812', 'publikasi', '2026-05-29 02:48:29'),
(65, 12, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food (2024)', 'https://doi.org/10.61132/bumi.v2i1.52', 'publikasi', '2026-05-29 02:48:29'),
(66, 12, 'pelaksanaan', 'Publikasi: Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur (2024)', 'https://doi.org/10.62565/tekno.v2i1.14', 'publikasi', '2026-05-29 02:48:29'),
(67, 12, 'pelaksanaan', 'Publikasi: Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python (2024)', 'https://doi.org/10.62565/tekno.v2i1.23', 'publikasi', '2026-05-29 02:48:29'),
(68, 12, 'pelaksanaan', 'Publikasi: Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions (2024)', 'https://doi.org/10.62565/tekno.v2i1.22', 'publikasi', '2026-05-29 02:48:29'),
(69, 12, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma (2024)', 'https://doi.org/10.54914/jit.v10i1.1001', 'publikasi', '2026-05-29 02:48:29'),
(70, 12, 'pelaksanaan', 'Publikasi: DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP (2024)', 'https://doi.org/10.36040/jati.v8i5.10713', 'publikasi', '2026-05-29 02:48:29'),
(71, 12, 'pelaksanaan', 'Publikasi: PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB (2024)', 'https://doi.org/10.36040/jati.v8i5.10641', 'publikasi', '2026-05-29 02:48:29'),
(72, 12, 'pelaksanaan', 'Publikasi: PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE (2024)', 'https://doi.org/10.36040/jati.v8i5.11094', 'publikasi', '2026-05-29 02:48:29'),
(73, 12, 'pelaksanaan', 'Publikasi: Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin (2024)', 'https://doi.org/10.62565/keadilan.v2i2.51', 'publikasi', '2026-05-29 02:48:29'),
(74, 12, 'pelaksanaan', 'Publikasi: OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP (2024)', 'https://doi.org/10.36040/jati.v8i6.11603', 'publikasi', '2026-05-29 02:48:29'),
(75, 12, 'pelaksanaan', 'Publikasi: IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGK… (2024)', 'https://doi.org/10.36040/jati.v8i6.12148', 'publikasi', '2026-05-29 02:48:29'),
(76, 12, 'pelaksanaan', 'Publikasi: Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH) (2023)', 'https://doi.org/10.62565/tekno.v1i1.2', 'publikasi', '2026-05-29 02:48:29'),
(77, 12, 'pelaksanaan', 'Publikasi: Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Keluraha… (2023)', 'https://doi.org/10.62565/tekno.v1i1.5', 'publikasi', '2026-05-29 02:48:29'),
(78, 12, 'pelaksanaan', 'Publikasi: Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners (2023)', 'https://doi.org/10.62565/tekno.v1i1.3', 'publikasi', '2026-05-29 02:48:29'),
(79, 12, 'pelaksanaan', 'Publikasi: Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet) (2023)', 'https://doi.org/10.62565/tekno.v1i1.1', 'publikasi', '2026-05-29 02:48:29'),
(80, 12, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD (2023)', 'https://doi.org/10.62565/tekno.v1i1.4', 'publikasi', '2026-05-29 02:48:29'),
(81, 12, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4670', 'publikasi', '2026-05-29 02:48:29'),
(82, 12, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4661', 'publikasi', '2026-05-29 02:48:29'),
(83, 12, 'pelaksanaan', 'Publikasi: NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4559', 'publikasi', '2026-05-29 02:48:29'),
(84, 12, 'pelaksanaan', 'Publikasi: Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung (2022)', 'https://doi.org/10.55916/smart.v18i1.63', 'publikasi', '2026-05-29 02:48:30'),
(85, 12, 'pelaksanaan', 'Publikasi: PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5 (2022)', 'https://doi.org/10.25134/nuansa.v16i2.5834', 'publikasi', '2026-05-29 02:48:30'),
(86, 12, 'pelaksanaan', 'Publikasi: DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG) (2021)', 'https://doi.org/10.36456/best.vol3.no2.4221', 'publikasi', '2026-05-29 02:48:30'),
(87, 15, 'pelaksanaan', 'Publikasi: An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability (2026)', 'https://doi.org/10.65780/bima.v1i2.7', 'publikasi', '2026-05-29 02:48:30'),
(88, 15, 'pelaksanaan', 'Publikasi: Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases:… (2026)', 'https://doi.org/10.1063/5.0322114', 'publikasi', '2026-05-29 02:48:31'),
(89, 15, 'pelaksanaan', 'Publikasi: Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehous… (2026)', 'https://doi.org/10.37012/jtik.v12i1.3447', 'publikasi', '2026-05-29 02:48:31'),
(90, 15, 'pelaksanaan', 'Publikasi: Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Al… (2025)', 'https://doi.org/10.62565/tekno.v3i1.55', 'publikasi', '2026-05-29 02:48:31'),
(91, 15, 'pelaksanaan', 'Publikasi: Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif (2025)', 'https://doi.org/10.51903/2gxcdc03', 'publikasi', '2026-05-29 02:48:31'),
(92, 15, 'pelaksanaan', 'Publikasi: Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak (2025)', 'https://doi.org/10.62951/router.v3i2.411', 'publikasi', '2026-05-29 02:48:31'),
(93, 15, 'pelaksanaan', 'Publikasi: Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Stud… (2025)', 'https://doi.org/10.35194/mji.v17i1.5139', 'publikasi', '2026-05-29 02:48:31'),
(94, 15, 'pelaksanaan', 'Publikasi: Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia (2025)', 'https://doi.org/10.55606/jtmei.v4i2.5010', 'publikasi', '2026-05-29 02:48:31'),
(95, 15, 'pelaksanaan', 'Publikasi: PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA (2025)', 'https://doi.org/10.33884/jif.v13i02.10147', 'publikasi', '2026-05-29 02:48:32'),
(96, 15, 'pelaksanaan', 'Publikasi: Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Band… (2025)', 'https://doi.org/10.33633/joins.v10i1.12668', 'publikasi', '2026-05-29 02:48:32'),
(97, 15, 'pelaksanaan', 'Publikasi: PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BA… (2025)', 'https://doi.org/10.33627/pk.v8i1.2311', 'publikasi', '2026-05-29 02:48:32'),
(98, 15, 'pelaksanaan', 'Publikasi: Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Mengg… (2025)', 'https://doi.org/10.62565/tekno.v3i1.87', 'publikasi', '2026-05-29 02:48:32'),
(99, 15, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB (2025)', 'https://doi.org/10.36040/jati.v9i4.13886', 'publikasi', '2026-05-29 02:48:32'),
(100, 15, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL (2025)', 'https://doi.org/10.36040/jati.v9i4.14093', 'publikasi', '2026-05-29 02:48:32'),
(101, 15, 'pelaksanaan', 'Publikasi: Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping (2025)', 'https://doi.org/10.53513/jsk.v8i2.11650', 'publikasi', '2026-05-29 02:48:32'),
(102, 15, 'pelaksanaan', 'Publikasi: PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE (2025)', 'https://doi.org/10.36040/jati.v9i5.14877', 'publikasi', '2026-05-29 02:48:32'),
(103, 15, 'pelaksanaan', 'Publikasi: PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GU… (2025)', 'https://doi.org/10.36040/jati.v9i5.15045', 'publikasi', '2026-05-29 02:48:32'),
(104, 15, 'pelaksanaan', 'Publikasi: PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL (2025)', 'https://doi.org/10.36040/jati.v9i5.14965', 'publikasi', '2026-05-29 02:48:32'),
(105, 15, 'pelaksanaan', 'Publikasi: Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes (2024)', 'https://doi.org/10.62565/tekno.v2i2.52', 'publikasi', '2026-05-29 02:48:32'),
(106, 15, 'pelaksanaan', 'Publikasi: Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas (2024)', 'https://doi.org/10.59024/faedah.v2i1.723', 'publikasi', '2026-05-29 02:48:32'),
(107, 15, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan (2024)', 'https://doi.org/10.55606/kreatif.v4i1.2812', 'publikasi', '2026-05-29 02:48:32'),
(108, 15, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food (2024)', 'https://doi.org/10.61132/bumi.v2i1.52', 'publikasi', '2026-05-29 02:48:32'),
(109, 15, 'pelaksanaan', 'Publikasi: Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur (2024)', 'https://doi.org/10.62565/tekno.v2i1.14', 'publikasi', '2026-05-29 02:48:32'),
(110, 15, 'pelaksanaan', 'Publikasi: Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python (2024)', 'https://doi.org/10.62565/tekno.v2i1.23', 'publikasi', '2026-05-29 02:48:32'),
(111, 15, 'pelaksanaan', 'Publikasi: Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions (2024)', 'https://doi.org/10.62565/tekno.v2i1.22', 'publikasi', '2026-05-29 02:48:33'),
(112, 15, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma (2024)', 'https://doi.org/10.54914/jit.v10i1.1001', 'publikasi', '2026-05-29 02:48:34'),
(113, 15, 'pelaksanaan', 'Publikasi: DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP (2024)', 'https://doi.org/10.36040/jati.v8i5.10713', 'publikasi', '2026-05-29 02:48:34'),
(114, 15, 'pelaksanaan', 'Publikasi: PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB (2024)', 'https://doi.org/10.36040/jati.v8i5.10641', 'publikasi', '2026-05-29 02:48:35'),
(115, 15, 'pelaksanaan', 'Publikasi: PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE (2024)', 'https://doi.org/10.36040/jati.v8i5.11094', 'publikasi', '2026-05-29 02:48:35'),
(116, 15, 'pelaksanaan', 'Publikasi: Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin (2024)', 'https://doi.org/10.62565/keadilan.v2i2.51', 'publikasi', '2026-05-29 02:48:35'),
(117, 15, 'pelaksanaan', 'Publikasi: OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP (2024)', 'https://doi.org/10.36040/jati.v8i6.11603', 'publikasi', '2026-05-29 02:48:35'),
(118, 15, 'pelaksanaan', 'Publikasi: IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGK… (2024)', 'https://doi.org/10.36040/jati.v8i6.12148', 'publikasi', '2026-05-29 02:48:35'),
(119, 15, 'pelaksanaan', 'Publikasi: Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH) (2023)', 'https://doi.org/10.62565/tekno.v1i1.2', 'publikasi', '2026-05-29 02:48:35'),
(120, 15, 'pelaksanaan', 'Publikasi: Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Keluraha… (2023)', 'https://doi.org/10.62565/tekno.v1i1.5', 'publikasi', '2026-05-29 02:48:35'),
(121, 15, 'pelaksanaan', 'Publikasi: Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners (2023)', 'https://doi.org/10.62565/tekno.v1i1.3', 'publikasi', '2026-05-29 02:48:35'),
(122, 15, 'pelaksanaan', 'Publikasi: Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet) (2023)', 'https://doi.org/10.62565/tekno.v1i1.1', 'publikasi', '2026-05-29 02:48:35'),
(123, 15, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD (2023)', 'https://doi.org/10.62565/tekno.v1i1.4', 'publikasi', '2026-05-29 02:48:35'),
(124, 15, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4670', 'publikasi', '2026-05-29 02:48:35'),
(125, 15, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4661', 'publikasi', '2026-05-29 02:48:35'),
(126, 15, 'pelaksanaan', 'Publikasi: NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4559', 'publikasi', '2026-05-29 02:48:36'),
(127, 15, 'pelaksanaan', 'Publikasi: Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung (2022)', 'https://doi.org/10.55916/smart.v18i1.63', 'publikasi', '2026-05-29 02:48:36'),
(128, 15, 'pelaksanaan', 'Publikasi: PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5 (2022)', 'https://doi.org/10.25134/nuansa.v16i2.5834', 'publikasi', '2026-05-29 02:48:36'),
(129, 15, 'pelaksanaan', 'Publikasi: DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG) (2021)', 'https://doi.org/10.36456/best.vol3.no2.4221', 'publikasi', '2026-05-29 02:48:36'),
(130, 18, 'pelaksanaan', 'Publikasi: An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability (2026)', 'https://doi.org/10.65780/bima.v1i2.7', 'publikasi', '2026-05-29 02:48:36'),
(131, 18, 'pelaksanaan', 'Publikasi: Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases:… (2026)', 'https://doi.org/10.1063/5.0322114', 'publikasi', '2026-05-29 02:48:36'),
(132, 18, 'pelaksanaan', 'Publikasi: Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehous… (2026)', 'https://doi.org/10.37012/jtik.v12i1.3447', 'publikasi', '2026-05-29 02:48:36'),
(133, 18, 'pelaksanaan', 'Publikasi: Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Al… (2025)', 'https://doi.org/10.62565/tekno.v3i1.55', 'publikasi', '2026-05-29 02:48:36'),
(134, 18, 'pelaksanaan', 'Publikasi: Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif (2025)', 'https://doi.org/10.51903/2gxcdc03', 'publikasi', '2026-05-29 02:48:36'),
(135, 18, 'pelaksanaan', 'Publikasi: Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak (2025)', 'https://doi.org/10.62951/router.v3i2.411', 'publikasi', '2026-05-29 02:48:36'),
(136, 18, 'pelaksanaan', 'Publikasi: Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Stud… (2025)', 'https://doi.org/10.35194/mji.v17i1.5139', 'publikasi', '2026-05-29 02:48:36'),
(137, 18, 'pelaksanaan', 'Publikasi: Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia (2025)', 'https://doi.org/10.55606/jtmei.v4i2.5010', 'publikasi', '2026-05-29 02:48:36'),
(138, 18, 'pelaksanaan', 'Publikasi: PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA (2025)', 'https://doi.org/10.33884/jif.v13i02.10147', 'publikasi', '2026-05-29 02:48:36'),
(139, 18, 'pelaksanaan', 'Publikasi: Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Band… (2025)', 'https://doi.org/10.33633/joins.v10i1.12668', 'publikasi', '2026-05-29 02:48:36'),
(140, 18, 'pelaksanaan', 'Publikasi: PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BA… (2025)', 'https://doi.org/10.33627/pk.v8i1.2311', 'publikasi', '2026-05-29 02:48:36'),
(141, 18, 'pelaksanaan', 'Publikasi: Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Mengg… (2025)', 'https://doi.org/10.62565/tekno.v3i1.87', 'publikasi', '2026-05-29 02:48:36'),
(142, 18, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB (2025)', 'https://doi.org/10.36040/jati.v9i4.13886', 'publikasi', '2026-05-29 02:48:36'),
(143, 18, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL (2025)', 'https://doi.org/10.36040/jati.v9i4.14093', 'publikasi', '2026-05-29 02:48:36'),
(144, 18, 'pelaksanaan', 'Publikasi: Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping (2025)', 'https://doi.org/10.53513/jsk.v8i2.11650', 'publikasi', '2026-05-29 02:48:36'),
(145, 18, 'pelaksanaan', 'Publikasi: PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE (2025)', 'https://doi.org/10.36040/jati.v9i5.14877', 'publikasi', '2026-05-29 02:48:36'),
(146, 18, 'pelaksanaan', 'Publikasi: PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GU… (2025)', 'https://doi.org/10.36040/jati.v9i5.15045', 'publikasi', '2026-05-29 02:48:36'),
(147, 18, 'pelaksanaan', 'Publikasi: PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL (2025)', 'https://doi.org/10.36040/jati.v9i5.14965', 'publikasi', '2026-05-29 02:48:36'),
(148, 18, 'pelaksanaan', 'Publikasi: Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes (2024)', 'https://doi.org/10.62565/tekno.v2i2.52', 'publikasi', '2026-05-29 02:48:36'),
(149, 18, 'pelaksanaan', 'Publikasi: Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas (2024)', 'https://doi.org/10.59024/faedah.v2i1.723', 'publikasi', '2026-05-29 02:48:36'),
(150, 18, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan (2024)', 'https://doi.org/10.55606/kreatif.v4i1.2812', 'publikasi', '2026-05-29 02:48:36'),
(151, 18, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food (2024)', 'https://doi.org/10.61132/bumi.v2i1.52', 'publikasi', '2026-05-29 02:48:36'),
(152, 18, 'pelaksanaan', 'Publikasi: Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur (2024)', 'https://doi.org/10.62565/tekno.v2i1.14', 'publikasi', '2026-05-29 02:48:36'),
(153, 18, 'pelaksanaan', 'Publikasi: Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python (2024)', 'https://doi.org/10.62565/tekno.v2i1.23', 'publikasi', '2026-05-29 02:48:36'),
(154, 18, 'pelaksanaan', 'Publikasi: Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions (2024)', 'https://doi.org/10.62565/tekno.v2i1.22', 'publikasi', '2026-05-29 02:48:36'),
(155, 18, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma (2024)', 'https://doi.org/10.54914/jit.v10i1.1001', 'publikasi', '2026-05-29 02:48:36'),
(156, 18, 'pelaksanaan', 'Publikasi: DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP (2024)', 'https://doi.org/10.36040/jati.v8i5.10713', 'publikasi', '2026-05-29 02:48:36'),
(157, 18, 'pelaksanaan', 'Publikasi: PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB (2024)', 'https://doi.org/10.36040/jati.v8i5.10641', 'publikasi', '2026-05-29 02:48:36'),
(158, 18, 'pelaksanaan', 'Publikasi: PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE (2024)', 'https://doi.org/10.36040/jati.v8i5.11094', 'publikasi', '2026-05-29 02:48:36'),
(159, 18, 'pelaksanaan', 'Publikasi: Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin (2024)', 'https://doi.org/10.62565/keadilan.v2i2.51', 'publikasi', '2026-05-29 02:48:36'),
(160, 18, 'pelaksanaan', 'Publikasi: OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP (2024)', 'https://doi.org/10.36040/jati.v8i6.11603', 'publikasi', '2026-05-29 02:48:36'),
(161, 18, 'pelaksanaan', 'Publikasi: IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGK… (2024)', 'https://doi.org/10.36040/jati.v8i6.12148', 'publikasi', '2026-05-29 02:48:36'),
(162, 18, 'pelaksanaan', 'Publikasi: Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH) (2023)', 'https://doi.org/10.62565/tekno.v1i1.2', 'publikasi', '2026-05-29 02:48:36'),
(163, 18, 'pelaksanaan', 'Publikasi: Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Keluraha… (2023)', 'https://doi.org/10.62565/tekno.v1i1.5', 'publikasi', '2026-05-29 02:48:36'),
(164, 18, 'pelaksanaan', 'Publikasi: Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners (2023)', 'https://doi.org/10.62565/tekno.v1i1.3', 'publikasi', '2026-05-29 02:48:36'),
(165, 18, 'pelaksanaan', 'Publikasi: Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet) (2023)', 'https://doi.org/10.62565/tekno.v1i1.1', 'publikasi', '2026-05-29 02:48:36'),
(166, 18, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD (2023)', 'https://doi.org/10.62565/tekno.v1i1.4', 'publikasi', '2026-05-29 02:48:36'),
(167, 18, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4670', 'publikasi', '2026-05-29 02:48:36'),
(168, 18, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4661', 'publikasi', '2026-05-29 02:48:36'),
(169, 18, 'pelaksanaan', 'Publikasi: NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4559', 'publikasi', '2026-05-29 02:48:36'),
(170, 18, 'pelaksanaan', 'Publikasi: Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung (2022)', 'https://doi.org/10.55916/smart.v18i1.63', 'publikasi', '2026-05-29 02:48:36'),
(171, 18, 'pelaksanaan', 'Publikasi: PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5 (2022)', 'https://doi.org/10.25134/nuansa.v16i2.5834', 'publikasi', '2026-05-29 02:48:36'),
(172, 18, 'pelaksanaan', 'Publikasi: DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG) (2021)', 'https://doi.org/10.36456/best.vol3.no2.4221', 'publikasi', '2026-05-29 02:48:36'),
(173, 21, 'pelaksanaan', 'Publikasi: An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability (2026)', 'https://doi.org/10.65780/bima.v1i2.7', 'publikasi', '2026-05-29 02:48:36'),
(174, 21, 'pelaksanaan', 'Publikasi: Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases:… (2026)', 'https://doi.org/10.1063/5.0322114', 'publikasi', '2026-05-29 02:48:37'),
(175, 21, 'pelaksanaan', 'Publikasi: Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehous… (2026)', 'https://doi.org/10.37012/jtik.v12i1.3447', 'publikasi', '2026-05-29 02:48:37'),
(176, 21, 'pelaksanaan', 'Publikasi: Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Al… (2025)', 'https://doi.org/10.62565/tekno.v3i1.55', 'publikasi', '2026-05-29 02:48:37'),
(177, 21, 'pelaksanaan', 'Publikasi: Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif (2025)', 'https://doi.org/10.51903/2gxcdc03', 'publikasi', '2026-05-29 02:48:37'),
(178, 21, 'pelaksanaan', 'Publikasi: Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak (2025)', 'https://doi.org/10.62951/router.v3i2.411', 'publikasi', '2026-05-29 02:48:37'),
(179, 21, 'pelaksanaan', 'Publikasi: Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Stud… (2025)', 'https://doi.org/10.35194/mji.v17i1.5139', 'publikasi', '2026-05-29 02:48:37'),
(180, 21, 'pelaksanaan', 'Publikasi: Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia (2025)', 'https://doi.org/10.55606/jtmei.v4i2.5010', 'publikasi', '2026-05-29 02:48:37'),
(181, 21, 'pelaksanaan', 'Publikasi: PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA (2025)', 'https://doi.org/10.33884/jif.v13i02.10147', 'publikasi', '2026-05-29 02:48:37'),
(182, 21, 'pelaksanaan', 'Publikasi: Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Band… (2025)', 'https://doi.org/10.33633/joins.v10i1.12668', 'publikasi', '2026-05-29 02:48:37'),
(183, 21, 'pelaksanaan', 'Publikasi: PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BA… (2025)', 'https://doi.org/10.33627/pk.v8i1.2311', 'publikasi', '2026-05-29 02:48:37'),
(184, 21, 'pelaksanaan', 'Publikasi: Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Mengg… (2025)', 'https://doi.org/10.62565/tekno.v3i1.87', 'publikasi', '2026-05-29 02:48:37'),
(185, 21, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB (2025)', 'https://doi.org/10.36040/jati.v9i4.13886', 'publikasi', '2026-05-29 02:48:37'),
(186, 21, 'pelaksanaan', 'Publikasi: SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL (2025)', 'https://doi.org/10.36040/jati.v9i4.14093', 'publikasi', '2026-05-29 02:48:37'),
(187, 21, 'pelaksanaan', 'Publikasi: Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping (2025)', 'https://doi.org/10.53513/jsk.v8i2.11650', 'publikasi', '2026-05-29 02:48:37'),
(188, 21, 'pelaksanaan', 'Publikasi: PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE (2025)', 'https://doi.org/10.36040/jati.v9i5.14877', 'publikasi', '2026-05-29 02:48:37'),
(189, 21, 'pelaksanaan', 'Publikasi: PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GU… (2025)', 'https://doi.org/10.36040/jati.v9i5.15045', 'publikasi', '2026-05-29 02:48:37'),
(190, 21, 'pelaksanaan', 'Publikasi: PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL (2025)', 'https://doi.org/10.36040/jati.v9i5.14965', 'publikasi', '2026-05-29 02:48:37'),
(191, 21, 'pelaksanaan', 'Publikasi: Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes (2024)', 'https://doi.org/10.62565/tekno.v2i2.52', 'publikasi', '2026-05-29 02:48:37'),
(192, 21, 'pelaksanaan', 'Publikasi: Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas (2024)', 'https://doi.org/10.59024/faedah.v2i1.723', 'publikasi', '2026-05-29 02:48:37'),
(193, 21, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan (2024)', 'https://doi.org/10.55606/kreatif.v4i1.2812', 'publikasi', '2026-05-29 02:48:38'),
(194, 21, 'pelaksanaan', 'Publikasi: Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food (2024)', 'https://doi.org/10.61132/bumi.v2i1.52', 'publikasi', '2026-05-29 02:48:38'),
(195, 21, 'pelaksanaan', 'Publikasi: Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur (2024)', 'https://doi.org/10.62565/tekno.v2i1.14', 'publikasi', '2026-05-29 02:48:38'),
(196, 21, 'pelaksanaan', 'Publikasi: Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python (2024)', 'https://doi.org/10.62565/tekno.v2i1.23', 'publikasi', '2026-05-29 02:48:38'),
(197, 21, 'pelaksanaan', 'Publikasi: Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions (2024)', 'https://doi.org/10.62565/tekno.v2i1.22', 'publikasi', '2026-05-29 02:48:38'),
(198, 21, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma (2024)', 'https://doi.org/10.54914/jit.v10i1.1001', 'publikasi', '2026-05-29 02:48:38'),
(199, 21, 'pelaksanaan', 'Publikasi: DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP (2024)', 'https://doi.org/10.36040/jati.v8i5.10713', 'publikasi', '2026-05-29 02:48:38'),
(200, 21, 'pelaksanaan', 'Publikasi: PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB (2024)', 'https://doi.org/10.36040/jati.v8i5.10641', 'publikasi', '2026-05-29 02:48:38'),
(201, 21, 'pelaksanaan', 'Publikasi: PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE (2024)', 'https://doi.org/10.36040/jati.v8i5.11094', 'publikasi', '2026-05-29 02:48:38'),
(202, 21, 'pelaksanaan', 'Publikasi: Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin (2024)', 'https://doi.org/10.62565/keadilan.v2i2.51', 'publikasi', '2026-05-29 02:48:38'),
(203, 21, 'pelaksanaan', 'Publikasi: OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP (2024)', 'https://doi.org/10.36040/jati.v8i6.11603', 'publikasi', '2026-05-29 02:48:38'),
(204, 21, 'pelaksanaan', 'Publikasi: IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGK… (2024)', 'https://doi.org/10.36040/jati.v8i6.12148', 'publikasi', '2026-05-29 02:48:38'),
(205, 21, 'pelaksanaan', 'Publikasi: Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH) (2023)', 'https://doi.org/10.62565/tekno.v1i1.2', 'publikasi', '2026-05-29 02:48:38'),
(206, 21, 'pelaksanaan', 'Publikasi: Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Keluraha… (2023)', 'https://doi.org/10.62565/tekno.v1i1.5', 'publikasi', '2026-05-29 02:48:38'),
(207, 21, 'pelaksanaan', 'Publikasi: Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners (2023)', 'https://doi.org/10.62565/tekno.v1i1.3', 'publikasi', '2026-05-29 02:48:38'),
(208, 21, 'pelaksanaan', 'Publikasi: Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet) (2023)', 'https://doi.org/10.62565/tekno.v1i1.1', 'publikasi', '2026-05-29 02:48:38'),
(209, 21, 'pelaksanaan', 'Publikasi: Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD (2023)', 'https://doi.org/10.62565/tekno.v1i1.4', 'publikasi', '2026-05-29 02:48:38'),
(210, 21, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4670', 'publikasi', '2026-05-29 02:48:38'),
(211, 21, 'pelaksanaan', 'Publikasi: PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4661', 'publikasi', '2026-05-29 02:48:39'),
(212, 21, 'pelaksanaan', 'Publikasi: NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG) (2022)', 'https://doi.org/10.25134/nuansa.v16i1.4559', 'publikasi', '2026-05-29 02:48:39'),
(213, 21, 'pelaksanaan', 'Publikasi: Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung (2022)', 'https://doi.org/10.55916/smart.v18i1.63', 'publikasi', '2026-05-29 02:48:39'),
(214, 21, 'pelaksanaan', 'Publikasi: PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5 (2022)', 'https://doi.org/10.25134/nuansa.v16i2.5834', 'publikasi', '2026-05-29 02:48:39'),
(215, 21, 'pelaksanaan', 'Publikasi: DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG) (2021)', 'https://doi.org/10.36456/best.vol3.no2.4221', 'publikasi', '2026-05-29 02:48:39'),
(216, 9, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(217, 9, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(218, 9, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(219, 9, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(220, 9, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(221, 9, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(222, 9, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(223, 9, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(224, 12, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(225, 12, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(226, 12, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(227, 12, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(228, 12, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(229, 12, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(230, 12, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(231, 12, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40');
INSERT INTO `eviden_ppepp_bukti` (`id`, `eviden_id`, `fase`, `label`, `url`, `sumber`, `created_at`) VALUES
(232, 15, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(233, 15, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(234, 15, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(235, 15, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(236, 15, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(237, 15, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(238, 15, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(239, 15, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(240, 18, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(241, 18, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(242, 18, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(243, 18, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(244, 18, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(245, 18, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(246, 18, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(247, 18, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(248, 21, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(249, 21, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(250, 21, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(251, 21, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(252, 21, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(253, 21, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(254, 21, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(255, 21, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(256, 24, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(257, 24, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(258, 24, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(259, 24, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(260, 24, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(261, 24, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(262, 24, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(263, 24, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(264, 27, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(265, 27, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(266, 27, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(267, 27, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(268, 27, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(269, 27, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(270, 27, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(271, 27, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(272, 30, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(273, 30, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(274, 30, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(275, 30, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(276, 30, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(277, 30, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(278, 30, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(279, 30, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(280, 33, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(281, 33, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(282, 33, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(283, 33, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(284, 33, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(285, 33, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(286, 33, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(287, 33, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40'),
(288, 36, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat [000925071]', 'b0ec7dd033862c910.pdf', 'hki', '2026-05-29 02:54:40'),
(289, 36, 'pelaksanaan', 'HKI (hak cipta): Layung Peradilan (Layanan Unggul Peradilan) [000946282]', 'baf0a9f44ef1c5e39.pdf', 'hki', '2026-05-29 02:54:40'),
(290, 36, 'pelaksanaan', 'HKI (hak cipta): Perancangan Aplikasi Pertanian Tani Bedas [000639187]', 'b9a3dbec3c5df9c1b.pdf', 'hki', '2026-05-29 02:54:40'),
(291, 36, 'pelaksanaan', 'HKI (hak cipta): Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Ranca… [000652087]', 'b509dd8ee39d5e307.pdf', 'hki', '2026-05-29 02:54:40'),
(292, 36, 'pelaksanaan', 'HKI (hak cipta): Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma… [000652089]', 'bb9d53e04be3c04e7.pdf', 'hki', '2026-05-29 02:54:40'),
(293, 36, 'pelaksanaan', 'HKI (hak cipta): Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengad… [000651295]', 'b400cc0f382183998.pdf', 'hki', '2026-05-29 02:54:40'),
(294, 36, 'pelaksanaan', 'HKI (hak cipta): E-Bale Sunda [000797988]', 'bc8c4d67af17cceeb.pdf', 'hki', '2026-05-29 02:54:40'),
(295, 36, 'pelaksanaan', 'HKI (hak cipta): BASIS DATA TERAPAN [000803072]', 'b306d123cb52d0937.pdf', 'hki', '2026-05-29 02:54:40');

-- --------------------------------------------------------

--
-- Table structure for table `fleksibilitas`
--

CREATE TABLE `fleksibilitas` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `bentuk` varchar(50) NOT NULL,
  `jumlah_mahasiswa` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hki`
--

CREATE TABLE `hki` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED DEFAULT NULL,
  `kategori` enum('penelitian','pkm') NOT NULL,
  `jenis` enum('paten','paten_sederhana','hak_cipta','desain_industri','dtlst','indikasi_geografis','prl_varietas') NOT NULL,
  `judul` text NOT NULL,
  `no_hki` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `pemilik` text DEFAULT NULL,
  `status` enum('terdaftar','granted') NOT NULL DEFAULT 'granted',
  `created_at` datetime DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hki`
--

INSERT INTO `hki` (`id`, `prodi_id`, `dosen_id`, `kategori`, `jenis`, `judul`, `no_hki`, `tahun`, `pemilik`, `status`, `created_at`, `link_bukti`) VALUES
(1, 7, 1, 'pkm', 'hak_cipta', 'Layung Peradilan (Layanan Unggul Peradilan)', '000946282', '2025', 'Rendra Yozar Dharmaputra, S.H., M.H., Dr. Nandang Sunandar, S.H., M.H. dkk', 'granted', '2026-05-29 02:50:20', 'baf0a9f44ef1c5e39.pdf'),
(2, 7, 1, 'pkm', 'hak_cipta', 'Pengembangan Website Pangauban Karinding Sebagai Media Edukasi Dan Pelestarian Seni Untuk Masyarakat', '000925071', '2025', 'Mamok Andri Senubekti S.kom., M.kom., Adinda Salsabila dkk', 'granted', '2026-05-29 02:50:40', 'b0ec7dd033862c910.pdf'),
(3, 7, 1, 'pkm', 'hak_cipta', 'BASIS DATA TERAPAN', '000803072', '2024', 'Yance Sonatha, S.Kom.,MT, Ikbal Nidauddin, M.Kom dkk', 'granted', '2026-05-29 02:51:26', 'b306d123cb52d0937.pdf'),
(4, 7, 1, 'pkm', 'hak_cipta', 'E-Bale Sunda', '000797988', '2024', 'Dr. Nandang Sunandar, S.H., M.H., Achmad Noval Fahrezi dkk', 'granted', '2026-05-29 02:51:46', 'bc8c4d67af17cceeb.pdf'),
(5, 7, 1, 'pkm', 'hak_cipta', 'Pengembangan Aplikasi Sopwatch Untuk Standar Operasional Prosedur Berbasis Web (Studi Kasus_ Pengadilan Negeri Bale Bandung)', '000651295', '2024', 'Mamok Andri Senubekti, S.Kom, M.Kom., Ismaya Dewi Priyani, S.H., M.H. dkk', 'granted', '2026-05-29 02:52:30', 'b400cc0f382183998.pdf'),
(6, 7, NULL, 'pkm', 'hak_cipta', 'Implementasi Aplikasi Penjualan Dan Persediaan Obat Berbasis Desktop (Studi Kasus_Apotek Wisa Farma).', '000652089', '2024', 'Novita Anggraini, S.Kom.,M.Kom., Rola Manjaleni, S.E., M.Si. dkk', 'granted', '2026-05-29 02:53:16', 'bb9d53e04be3c04e7.pdf'),
(7, 7, 2, 'pkm', 'hak_cipta', 'Aplikasi Website Peminjaman Perpustakaan Menggunakan Bootstrap (Studi Kasus Pada SMK Pasundan Rancaekek)', '000652087', '2024', 'Darsiti, S.Kom., M.Kom., Fadzar Rusghana, S.Si., M.M. dkk', 'granted', '2026-05-29 02:54:04', 'b509dd8ee39d5e307.pdf'),
(8, 7, 1, 'pkm', 'hak_cipta', 'Perancangan Aplikasi Pertanian Tani Bedas', '000639187', '2024', 'Novita anggraini, S.Kom., M.Kom., Mamok Andri Senubekti, S.Kom., M.Kom. dkk', 'granted', '2026-05-29 02:54:32', 'b9a3dbec3c5df9c1b.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `kepuasan_pengguna_lulusan`
--

CREATE TABLE `kepuasan_pengguna_lulusan` (
  `id` int(10) UNSIGNED NOT NULL,
  `mahasiswa_id` int(10) UNSIGNED NOT NULL,
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
  `tgl_isi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kerjasama`
--

CREATE TABLE `kerjasama` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kerjasama`
--

INSERT INTO `kerjasama` (`id`, `prodi_id`, `nama_mitra`, `bidang`, `tingkat`, `jenis_dokumen`, `no_dokumen`, `tgl_mulai`, `tgl_berakhir`, `manfaat`, `bukti_implementasi`, `created_at`, `updated_at`) VALUES
(1, 7, 'Kantor Kecamatan Naringgul', 'pendidikan', 'lokal', 'mou', '012/UTD/MOU/12/II/2026', '2026-02-05', '2031-02-05', '', '', '2026-05-22 02:02:54', '2026-05-24 04:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED NOT NULL,
  `arah` enum('pemasukan','pengeluaran') NOT NULL,
  `sumber_atau_pos` varchar(200) NOT NULL,
  `kategori` enum('mahasiswa','pt_sendiri','kementerian','hibah','kerjasama','lainnya','operasional','tridharma','investasi','publikasi','lainnya2') DEFAULT NULL,
  `jumlah` bigint(20) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_eksternal`
--

CREATE TABLE `kondisi_eksternal` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `kategori` enum('politik','ekonomi','sosial','teknologi','hukum','demografi','pasar_kerja','kompetitor','peluang','ancaman') NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text NOT NULL,
  `dampak` enum('rendah','sedang','tinggi') NOT NULL DEFAULT 'sedang',
  `respons_prodi` text DEFAULT NULL,
  `sumber_referensi` varchar(250) DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `tahun_relevansi` year(4) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kondisi_eksternal`
--

INSERT INTO `kondisi_eksternal` (`id`, `prodi_id`, `kategori`, `judul`, `deskripsi`, `dampak`, `respons_prodi`, `sumber_referensi`, `link_bukti`, `tahun_relevansi`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 7, 'politik', 'Kebijakan Pemerintah tentang Pengembangan Teknologi Digital', 'Pemerintah Indonesia telah mengeluarkan kebijakan untuk mengembangkan teknologi digital di berbagai sektor, termasuk pendidikan. Hal ini relevan untuk Prodi Manajemen Informatika D3 karena dapat membuka peluang kerja sama dan penelitian dengan pemerintah.', 'sedang', 'Mengembangkan kerja sama dengan pemerintah dan lembaga terkait untuk mengembangkan teknologi digital', NULL, NULL, NULL, 0, '2026-05-29 07:48:11', '2026-05-29 07:48:11'),
(2, 7, 'politik', 'Peraturan Menteri Ristekdikti tentang Standar Nasional Pendidikan Tinggi', 'Peraturan ini mengatur standar nasional pendidikan tinggi di Indonesia, termasuk untuk program D3. Hal ini relevan untuk Prodi Manajemen Informatika D3 karena harus memenuhi standar yang telah ditetapkan.', 'tinggi', 'Mengupdate kurikulum dan sistem pendidikan untuk memenuhi standar nasional', NULL, NULL, NULL, 0, '2026-05-29 07:48:11', '2026-05-29 07:48:11'),
(3, 7, 'politik', 'Stabilitas Politik di Jawa Barat', 'Stabilitas politik di Jawa Barat dapat mempengaruhi keamanan dan kenyamanan mahasiswa dan dosen. Hal ini relevan untuk Prodi Manajemen Informatika D3 karena dapat mempengaruhi kualitas pendidikan.', 'rendah', 'Mengawasi situasi politik di Jawa Barat dan mengambil langkah-langkah untuk menjaga keamanan dan kenyamanan kampus', NULL, NULL, NULL, 0, '2026-05-29 07:48:11', '2026-05-29 07:48:11'),
(4, 7, 'politik', 'Kebijakan Pemerintah tentang Pengembangan Sumber Daya Manusia di Sektor Teknologi Informasi', 'Pemerintah Indonesia telah mengeluarkan kebijakan untuk mengembangkan sumber daya manusia di sektor teknologi informasi. Hal ini relevan untuk Prodi Manajemen Informatika D3 karena dapat membuka peluang kerja dan penelitian untuk lulusan.', 'sedang', 'Mengembangkan kurikulum dan program penelitian yang relevan dengan kebutuhan industri teknologi informasi', NULL, NULL, NULL, 0, '2026-05-29 07:48:11', '2026-05-29 07:48:11'),
(5, 7, 'politik', 'Peraturan tentang Perlindungan Data Pribadi', 'Peraturan ini mengatur tentang perlindungan data pribadi di Indonesia, termasuk untuk lembaga pendidikan. Hal ini relevan untuk Prodi Manajemen Informatika D3 karena harus memenuhi standar perlindungan data pribadi.', 'tinggi', 'Mengupdate sistem keamanan data dan memenuhi standar perlindungan data pribadi', NULL, NULL, NULL, 0, '2026-05-29 07:48:11', '2026-05-29 07:48:11'),
(6, 7, 'ekonomi', 'Pertumbuhan Ekonomi Digital di Jawa Barat', 'Pertumbuhan ekonomi digital di Jawa Barat yang pesat membuka peluang kerja bagi lulusan Manajemen Informatika D3. Hal ini relevan karena prodi dapat mempersiapkan lulusan untuk memenuhi kebutuhan tenaga kerja di sektor digital.', 'tinggi', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri digital dan meningkatkan kerja sama dengan perusahaan digital di Jawa Barat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:24', '2026-05-29 07:48:24'),
(7, 7, 'ekonomi', 'Ketersediaan Beasiswa untuk Pendidikan Vokasi', 'Ketersediaan beasiswa untuk pendidikan vokasi seperti D3 Manajemen Informatika dapat meningkatkan aksesibilitas pendidikan bagi mahasiswa dari latar belakang ekonomi lemah. Hal ini relevan karena prodi dapat meningkatkan kesempatan bagi mahasiswa untuk mengakses pendidikan berkualitas.', 'sedang', 'Mengidentifikasi sumber beasiswa yang tersedia dan mempromosikannya kepada calon mahasiswa, serta meningkatkan kerja sama dengan pemberi beasiswa.', NULL, NULL, NULL, 0, '2026-05-29 07:48:24', '2026-05-29 07:48:24'),
(8, 7, 'ekonomi', 'Daya Beli Masyarakat di Bandung', 'Daya beli masyarakat di Bandung yang relatif tinggi dapat meningkatkan permintaan akan jasa dan produk digital, sehingga membuka peluang kerja bagi lulusan Manajemen Informatika D3. Hal ini relevan karena prodi dapat mempersiapkan lulusan untuk memenuhi kebutuhan masyarakat akan jasa dan produk digital.', 'sedang', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan masyarakat dan meningkatkan kerja sama dengan perusahaan yang menyediakan jasa dan produk digital.', NULL, NULL, NULL, 0, '2026-05-29 07:48:24', '2026-05-29 07:48:24'),
(9, 7, 'ekonomi', 'Kondisi Industri Teknologi Informasi di Jawa Barat', 'Kondisi industri teknologi informasi di Jawa Barat yang berkembang pesat dapat membuka peluang kerja bagi lulusan Manajemen Informatika D3. Hal ini relevan karena prodi dapat mempersiapkan lulusan untuk memenuhi kebutuhan tenaga kerja di sektor teknologi informasi.', 'tinggi', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri teknologi informasi dan meningkatkan kerja sama dengan perusahaan teknologi informasi di Jawa Barat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:24', '2026-05-29 07:48:24'),
(10, 7, 'ekonomi', 'Pengaruh Inflasi terhadap Biaya Pendidikan', 'Pengaruh inflasi terhadap biaya pendidikan dapat meningkatkan biaya operasional prodi dan mempengaruhi kemampuan mahasiswa untuk membayar biaya pendidikan. Hal ini relevan karena prodi perlu mengelola biaya operasional dan membantu mahasiswa untuk mengatasi dampak inflasi.', 'rendah', 'Mengelola biaya operasional dengan efektif dan membantu mahasiswa untuk mengakses sumber daya keuangan yang tersedia.', NULL, NULL, NULL, 0, '2026-05-29 07:48:24', '2026-05-29 07:48:24'),
(11, 7, 'sosial', 'Digitalisasi Masyarakat', 'Masyarakat Bandung, Jawa Barat, memiliki tingkat digitalisasi yang tinggi, sehingga lulusan Manajemen Informatika D3 diharapkan dapat memenuhi kebutuhan masyarakat akan solusi teknologi informasi. Hal ini relevan karena prodi Manajemen Informatika D3 harus menghasilkan lulusan yang siap menghadapi tantangan digitalisasi.', 'tinggi', 'Mengembangkan kurikulum yang fokus pada pengembangan kemampuan teknologi informasi dan analisis data, serta meningkatkan kerja sama dengan industri untuk memenuhi kebutuhan masyarakat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:33', '2026-05-29 07:48:33'),
(12, 7, 'sosial', 'Inklusi Digital', 'Masyarakat Bandung, Jawa Barat, memiliki keragaman latar belakang sosial dan ekonomi, sehingga lulusan Manajemen Informatika D3 harus dapat memahami dan mengatasi isu inklusi digital. Hal ini relevan karena prodi Manajemen Informatika D3 harus menghasilkan lulusan yang siap menghadapi tantangan inklusi digital.', 'sedang', 'Mengembangkan program yang fokus pada inklusi digital, seperti pelatihan untuk masyarakat yang kurang beruntung dan pengembangan aplikasi yang dapat diakses oleh semua lapisan masyarakat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:33', '2026-05-29 07:48:33'),
(13, 7, 'sosial', 'Ekspektasi Masyarakat terhadap Lulusan', 'Masyarakat Bandung, Jawa Barat, memiliki ekspektasi yang tinggi terhadap lulusan Manajemen Informatika D3, yaitu memiliki kemampuan yang baik dalam mengelola teknologi informasi dan analisis data. Hal ini relevan karena prodi Manajemen Informatika D3 harus menghasilkan lulusan yang siap memenuhi kebutuhan masyarakat.', 'tinggi', 'Mengembangkan kurikulum yang fokus pada pengembangan kemampuan teknologi informasi dan analisis data, serta meningkatkan kerja sama dengan industri untuk memenuhi kebutuhan masyarakat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:33', '2026-05-29 07:48:33'),
(14, 7, 'sosial', 'Perubahan Gaya Hidup', 'Masyarakat Bandung, Jawa Barat, memiliki gaya hidup yang dinamis dan terus berubah, sehingga lulusan Manajemen Informatika D3 harus dapat memahami dan mengatasi perubahan gaya hidup tersebut. Hal ini relevan karena prodi Manajemen Informatika D3 harus menghasilkan lulusan yang siap menghadapi tantangan perubahan gaya hidup.', 'rendah', 'Mengembangkan program yang fokus pada pengembangan kemampuan adaptasi dan inovasi, serta meningkatkan kerja sama dengan industri untuk memenuhi kebutuhan masyarakat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:33', '2026-05-29 07:48:33'),
(15, 7, 'sosial', 'Keragaman Latar Belakang Sosial', 'Masyarakat Bandung, Jawa Barat, memiliki keragaman latar belakang sosial yang tinggi, sehingga lulusan Manajemen Informatika D3 harus dapat memahami dan mengatasi isu keragaman latar belakang sosial tersebut. Hal ini relevan karena prodi Manajemen Informatika D3 harus menghasilkan lulusan yang siap menghadapi tantangan keragaman latar belakang sosial.', 'sedang', 'Mengembangkan program yang fokus pada pengembangan kemampuan keragaman latar belakang sosial, serta meningkatkan kerja sama dengan industri untuk memenuhi kebutuhan masyarakat.', NULL, NULL, NULL, 0, '2026-05-29 07:48:33', '2026-05-29 07:48:33'),
(16, 7, 'teknologi', 'Pengembangan Teknologi Artificial Intelligence (AI)', 'Pengembangan AI yang pesat dapat mempengaruhi proses belajar mengajar di Manajemen Informatika D3, karena AI dapat membantu dalam analisis data dan pengambilan keputusan. Hal ini relevan karena prodi dapat memanfaatkan AI untuk meningkatkan kualitas pendidikan.', 'tinggi', 'Mengintegrasikan kursus AI dalam kurikulum dan menyediakan sumber daya untuk pengembangan AI', NULL, NULL, NULL, 0, '2026-05-29 07:48:58', '2026-05-29 07:48:58'),
(17, 7, 'teknologi', 'Perkembangan Internet of Things (IoT)', 'Perkembangan IoT dapat membuka peluang baru bagi lulusan Manajemen Informatika D3 di Bandung, karena kota ini memiliki potensi besar dalam pengembangan smart city. Hal ini relevan karena prodi dapat mempersiapkan lulusan untuk menghadapi tantangan dan peluang di industri IoT.', 'sedang', 'Mengembangkan kursus yang terkait dengan IoT dan membangun kerja sama dengan industri terkait', NULL, NULL, NULL, 0, '2026-05-29 07:48:58', '2026-05-29 07:48:58'),
(18, 7, 'teknologi', 'Penggunaan Cloud Computing', 'Penggunaan cloud computing yang semakin luas dapat mempengaruhi cara kerja dan belajar di Manajemen Informatika D3, karena cloud computing dapat membantu dalam pengelolaan data dan aplikasi. Hal ini relevan karena prodi dapat memanfaatkan cloud computing untuk meningkatkan efisiensi dan fleksibilitas.', 'rendah', 'Menggunakan layanan cloud computing untuk mendukung kegiatan belajar mengajar dan administrasi', NULL, NULL, NULL, 0, '2026-05-29 07:48:58', '2026-05-29 07:48:58'),
(19, 7, 'teknologi', 'Disrupsi Teknologi Blockchain', 'Disrupsi teknologi blockchain dapat membuka peluang baru bagi lulusan Manajemen Informatika D3 di bidang keamanan data dan pengembangan aplikasi blockchain. Hal ini relevan karena prodi dapat mempersiapkan lulusan untuk menghadapi tantangan dan peluang di industri blockchain.', 'tinggi', 'Mengembangkan kursus yang terkait dengan blockchain dan membangun kerja sama dengan industri terkait', NULL, NULL, NULL, 0, '2026-05-29 07:48:58', '2026-05-29 07:48:58'),
(20, 7, 'teknologi', 'Pengembangan Teknologi Cyber Security', 'Pengembangan teknologi cyber security yang semakin penting dapat mempengaruhi cara kerja dan belajar di Manajemen Informatika D3, karena cyber security dapat membantu dalam melindungi data dan sistem informasi. Hal ini relevan karena prodi dapat memanfaatkan teknologi cyber security untuk meningkatkan keamanan dan privasi.', 'sedang', 'Mengembangkan kursus yang terkait dengan cyber security dan membangun kerja sama dengan industri terkait', NULL, NULL, NULL, 0, '2026-05-29 07:48:58', '2026-05-29 07:48:58'),
(21, 7, 'hukum', 'UU No. 20 Tahun 2003 tentang Sistem Pendidikan Nasional', 'Undang-undang ini mengatur sistem pendidikan nasional, termasuk pendidikan tinggi, yang relevan dengan prodi Manajemen Informatika D3 di Universitas Teknologi Digital. Peraturan ini mempengaruhi kurikulum dan standar pendidikan.', 'sedang', 'Mengintegrasikan prinsip-prinsip sistem pendidikan nasional ke dalam kurikulum dan proses pembelajaran', NULL, NULL, NULL, 0, '2026-05-29 07:49:08', '2026-05-29 07:49:08'),
(22, 7, 'hukum', 'Permendikbudristek No. 5 Tahun 2022 tentang Standar Nasional Pendidikan Tinggi', 'Peraturan ini menetapkan standar nasional pendidikan tinggi yang harus dipenuhi oleh semua perguruan tinggi, termasuk Universitas Teknologi Digital. Standar ini mencakup aspek akademik, sumber daya, dan lain-lain.', 'tinggi', 'Mengevaluasi dan memastikan bahwa prodi Manajemen Informatika D3 memenuhi standar nasional pendidikan tinggi', NULL, NULL, NULL, 0, '2026-05-29 07:49:08', '2026-05-29 07:49:08'),
(23, 7, 'hukum', 'KKNI (Kerangka Kualifikasi Nasional Indonesia)', 'KKNI merupakan kerangka kualifikasi yang digunakan untuk mengukur kemampuan lulusan pendidikan tinggi. Prodi Manajemen Informatika D3 perlu memastikan bahwa lulusannya memenuhi standar KKNI.', 'sedang', 'Mengintegrasikan kompetensi KKNI ke dalam kurikulum dan proses pembelajaran', NULL, NULL, NULL, 0, '2026-05-29 07:49:08', '2026-05-29 07:49:08'),
(24, 7, 'hukum', 'Peraturan Pemerintah No. 71 Tahun 2010 tentang Standar Nasional Pendidikan', 'Peraturan ini mengatur standar nasional pendidikan, termasuk standar isi, standar proses, dan standar penilaian. Prodi Manajemen Informatika D3 perlu memastikan bahwa proses pembelajaran dan penilaian memenuhi standar ini.', 'rendah', 'Mengreview dan memastikan bahwa proses pembelajaran dan penilaian sudah memenuhi standar nasional', NULL, NULL, NULL, 0, '2026-05-29 07:49:08', '2026-05-29 07:49:08'),
(25, 7, 'hukum', 'SN-Dikti (Standar Nasional Dikti)', 'SN-Dikti merupakan standar nasional pendidikan tinggi yang diterapkan oleh Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi. Prodi Manajemen Informatika D3 perlu memastikan bahwa proses pembelajaran dan penilaian memenuhi standar ini.', 'tinggi', 'Mengevaluasi dan memastikan bahwa prodi Manajemen Informatika D3 memenuhi standar nasional dikti', NULL, NULL, NULL, 0, '2026-05-29 07:49:08', '2026-05-29 07:49:08'),
(26, 7, 'demografi', 'Jumlah Lulusan SMA di Jawa Barat', 'Jumlah lulusan SMA di Jawa Barat yang relatif tinggi setiap tahunnya membuat persaingan untuk masuk ke perguruan tinggi semakin ketat. Hal ini relevan karena Manajemen Informatika D3 di Universitas Teknologi Digital berpotensi menarik minat calon mahasiswa dari lulusan SMA di Jawa Barat.', 'sedang', 'Mengembangkan promosi dan kerja sama dengan sekolah-sekolah SMA di Jawa Barat untuk meningkatkan kesadaran dan minat calon mahasiswa.', NULL, NULL, NULL, 0, '2026-05-29 07:49:18', '2026-05-29 07:49:18'),
(27, 7, 'demografi', 'Tren Minat pada Bidang Teknologi', 'Tren minat masyarakat terhadap bidang teknologi dan informatika yang meningkat, terutama di kalangan generasi muda. Hal ini relevan karena Manajemen Informatika D3 di Universitas Teknologi Digital berfokus pada bidang teknologi dan informatika.', 'tinggi', 'Mengembangkan kurikulum yang relevan dengan tren terbaru di bidang teknologi dan informatika, serta meningkatkan fasilitas dan sumber daya untuk mendukung minat dan kebutuhan calon mahasiswa.', NULL, NULL, NULL, 0, '2026-05-29 07:49:18', '2026-05-29 07:49:18'),
(28, 7, 'demografi', 'Persaingan Kuota Nasional', 'Persaingan kuota nasional untuk masuk ke perguruan tinggi yang semakin ketat, terutama untuk program studi yang populer seperti Manajemen Informatika. Hal ini relevan karena Universitas Teknologi Digital perlu bersaing dengan perguruan tinggi lain di Indonesia untuk menarik calon mahasiswa.', 'tinggi', 'Mengembangkan strategi pemasaran dan promosi yang efektif untuk meningkatkan visibilitas dan daya tarik program studi, serta meningkatkan kualitas dan reputasi program studi.', NULL, NULL, NULL, 0, '2026-05-29 07:49:18', '2026-05-29 07:49:18'),
(29, 7, 'demografi', 'Jumlah Pendaftar Program Studi Serupa', 'Jumlah pendaftar program studi serupa di wilayah Jawa Barat yang relatif tinggi, sehingga membuat persaingan untuk masuk ke program studi Manajemen Informatika D3 di Universitas Teknologi Digital semakin ketat. Hal ini relevan karena program studi perlu meningkatkan daya tarik dan kualitas untuk menarik calon mahasiswa.', 'sedang', 'Mengembangkan keunggulan dan diferensiasi program studi, serta meningkatkan kualitas dan reputasi program studi untuk meningkatkan daya tarik calon mahasiswa.', NULL, NULL, NULL, 0, '2026-05-29 07:49:18', '2026-05-29 07:49:18'),
(30, 7, 'demografi', 'Minat Calon Mahasiswa terhadap Pendidikan Vokasi', 'Minat calon mahasiswa terhadap pendidikan vokasi yang meningkat, terutama di kalangan lulusan SMA yang ingin memiliki keterampilan praktis dan siap kerja. Hal ini relevan karena Manajemen Informatika D3 di Universitas Teknologi Digital merupakan program studi vokasi yang berfokus pada keterampilan praktis.', 'rendah', 'Mengembangkan kerja sama dengan industri dan dunia kerja untuk meningkatkan kesadaran dan minat calon mahasiswa terhadap program studi vokasi, serta meningkatkan kualitas dan relevansi kurikulum dengan kebutuhan industri.', NULL, NULL, NULL, 0, '2026-05-29 07:49:18', '2026-05-29 07:49:18'),
(31, 7, 'pasar_kerja', 'Kebutuhan Tenaga Kerja di Sektor Digital', 'Sektor digital di Bandung, Jawa Barat, membutuhkan tenaga kerja yang kompeten dalam bidang manajemen informatika, sehingga lulusan Manajemen Informatika D3 dari Universitas Teknologi Digital memiliki peluang besar untuk diterima di industri ini.', 'tinggi', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri dan meningkatkan kerja sama dengan perusahaan di sektor digital', NULL, NULL, NULL, 0, '2026-05-29 07:49:25', '2026-05-29 07:49:25'),
(32, 7, 'pasar_kerja', 'Tren Rekrutmen Berbasis Kompetensi', 'Perusahaan di Bandung, Jawa Barat, cenderung merekrut kandidat berdasarkan kompetensi yang sesuai dengan kebutuhan mereka, sehingga lulusan Manajemen Informatika D3 perlu memiliki kompetensi yang relevan dengan industri', 'sedang', 'Mengembangkan program pengembangan kompetensi yang sesuai dengan kebutuhan industri dan meningkatkan kesadaran lulusan tentang pentingnya kompetensi', NULL, NULL, NULL, 0, '2026-05-29 07:49:26', '2026-05-29 07:49:26'),
(33, 7, 'pasar_kerja', 'Kekurangan Tenaga Kerja di Bidang Data Analyst', 'Sektor digital di Bandung, Jawa Barat, mengalami kekurangan tenaga kerja yang kompeten dalam bidang data analyst, sehingga lulusan Manajemen Informatika D3 yang memiliki kompetensi ini memiliki peluang besar untuk diterima', 'tinggi', 'Mengembangkan program khusus untuk meningkatkan kompetensi lulusan di bidang data analyst', NULL, NULL, NULL, 0, '2026-05-29 07:49:26', '2026-05-29 07:49:26'),
(34, 7, 'pasar_kerja', 'Pengaruh Perubahan Teknologi terhadap Kebutuhan Tenaga Kerja', 'Perubahan teknologi yang cepat di sektor digital dapat memengaruhi kebutuhan tenaga kerja, sehingga lulusan Manajemen Informatika D3 perlu memiliki kemampuan untuk beradaptasi dengan perubahan teknologi', 'rendah', 'Mengembangkan program pengembangan kemampuan beradaptasi dengan perubahan teknologi dan meningkatkan kesadaran lulusan tentang pentingnya beradaptasi', NULL, NULL, NULL, 0, '2026-05-29 07:49:26', '2026-05-29 07:49:26'),
(35, 7, 'pasar_kerja', 'Kebutuhan Tenaga Kerja di Sektor E-Commerce', 'Sektor e-commerce di Bandung, Jawa Barat, membutuhkan tenaga kerja yang kompeten dalam bidang manajemen informatika, sehingga lulusan Manajemen Informatika D3 dari Universitas Teknologi Digital memiliki peluang besar untuk diterima di industri ini', 'sedang', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri e-commerce dan meningkatkan kerja sama dengan perusahaan di sektor e-commerce', NULL, NULL, NULL, 0, '2026-05-29 07:49:26', '2026-05-29 07:49:26'),
(36, 7, 'kompetitor', 'Kompetitor Lokal: Politeknik Bandung', 'Politeknik Bandung memiliki program studi serupa yang menawarkan keunggulan dalam bidang teknologi digital, sehingga menjadi salah satu pilihan utama bagi calon mahasiswa di Bandung. Hal ini relevan karena dapat mempengaruhi minat calon mahasiswa untuk memilih Universitas Teknologi Digital.', 'sedang', 'Mengembangkan keunggulan unik dan memperbarui kurikulum untuk meningkatkan daya saing', NULL, NULL, NULL, 0, '2026-05-29 07:49:39', '2026-05-29 07:49:39'),
(37, 7, 'kompetitor', 'Perguruan Tinggi Swasta di Jawa Barat', 'Jawa Barat memiliki banyak perguruan tinggi swasta yang menawarkan program studi Manajemen Informatika, sehingga meningkatkan persaingan di pasar pendidikan tinggi. Hal ini relevan karena mempengaruhi strategi pemasaran dan rekrutmen mahasiswa.', 'rendah', 'Mengoptimalkan strategi pemasaran dan meningkatkan kerja sama dengan industri untuk meningkatkan visibilitas', NULL, NULL, NULL, 0, '2026-05-29 07:49:39', '2026-05-29 07:49:39'),
(38, 7, 'kompetitor', 'Universitas Negeri di Jawa Barat', 'Universitas negeri di Jawa Barat seperti Universitas Padjadjaran dan Universitas Islam Bandung juga menawarkan program studi serupa, sehingga menjadi kompetitor yang signifikan. Hal ini relevan karena dapat mempengaruhi keputusan calon mahasiswa dalam memilih institusi pendidikan.', 'tinggi', 'Mengembangkan keunggulan dalam bidang riset dan pengabdian masyarakat untuk meningkatkan reputasi', NULL, NULL, NULL, 0, '2026-05-29 07:49:39', '2026-05-29 07:49:39'),
(39, 7, 'kompetitor', 'Kualitas Lulusan', 'Kualitas lulusan dari program studi Manajemen Informatika di Universitas Teknologi Digital dibandingkan dengan kompetitor lain di Jawa Barat. Hal ini relevan karena dapat mempengaruhi kepercayaan industri dan masyarakat terhadap lulusan.', 'sedang', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri dan meningkatkan kualitas pengajaran', NULL, NULL, NULL, 0, '2026-05-29 07:49:39', '2026-05-29 07:49:39'),
(40, 7, 'kompetitor', 'Akreditasi dan Reputasi', 'Akreditasi dan reputasi program studi Manajemen Informatika di Universitas Teknologi Digital dibandingkan dengan kompetitor lain di Jawa Barat. Hal ini relevan karena dapat mempengaruhi kepercayaan calon mahasiswa dan industri terhadap program studi.', 'tinggi', 'Mengembangkan strategi untuk meningkatkan akreditasi dan reputasi melalui peningkatan kualitas pengajaran dan riset', NULL, NULL, NULL, 0, '2026-05-29 07:49:39', '2026-05-29 07:49:39'),
(41, 7, 'peluang', 'Permintaan SDM Teknologi Informasi', 'Meningkatnya permintaan lulusan Manajemen Informatika D3 di perusahaan teknologi dan industri digital di Bandung. Hal ini relevan karena prodi dapat meningkatkan kualitas lulusan untuk memenuhi kebutuhan industri.', 'tinggi', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri dan meningkatkan kerja sama dengan perusahaan teknologi di Bandung', NULL, NULL, NULL, 0, '2026-05-29 07:49:46', '2026-05-29 07:49:46'),
(42, 7, 'peluang', 'Pengembangan Ekosistem Digital di Jawa Barat', 'Pemerintah Jawa Barat mengembangkan ekosistem digital untuk meningkatkan pertumbuhan ekonomi daerah. Prodi dapat memanfaatkan peluang ini untuk meningkatkan kualitas pendidikan dan penelitian.', 'sedang', 'Mengembangkan penelitian dan pengabdian masyarakat yang terkait dengan pengembangan ekosistem digital di Jawa Barat', NULL, NULL, NULL, 0, '2026-05-29 07:49:46', '2026-05-29 07:49:46'),
(43, 7, 'peluang', 'Kebijakan Pemerintah tentang Digitalisasi', 'Pemerintah Indonesia mengeluarkan kebijakan untuk mendukung digitalisasi di berbagai sektor. Prodi dapat memanfaatkan peluang ini untuk meningkatkan kualitas pendidikan dan penelitian.', 'rendah', 'Mengikuti perkembangan kebijakan pemerintah tentang digitalisasi dan mengembangkan kurikulum yang sesuai dengan kebutuhan industri', NULL, NULL, NULL, 0, '2026-05-29 07:49:46', '2026-05-29 07:49:46'),
(44, 7, 'peluang', 'Pertumbuhan Industri E-Commerce di Indonesia', 'Pertumbuhan industri e-commerce di Indonesia yang pesat membuka peluang bagi lulusan Manajemen Informatika D3 untuk bekerja di perusahaan e-commerce. Prodi dapat memanfaatkan peluang ini untuk meningkatkan kualitas pendidikan.', 'tinggi', 'Mengembangkan kurikulum yang sesuai dengan kebutuhan industri e-commerce dan meningkatkan kerja sama dengan perusahaan e-commerce', NULL, NULL, NULL, 0, '2026-05-29 07:49:46', '2026-05-29 07:49:46'),
(45, 7, 'peluang', 'Kerja Sama dengan Industri Teknologi di Bandung', 'Kerja sama dengan industri teknologi di Bandung dapat membuka peluang bagi prodi untuk meningkatkan kualitas pendidikan dan penelitian. Prodi dapat memanfaatkan peluang ini untuk meningkatkan kualitas lulusan.', 'sedang', 'Mengembangkan kerja sama dengan industri teknologi di Bandung untuk meningkatkan kualitas pendidikan dan penelitian', NULL, NULL, NULL, 0, '2026-05-29 07:49:46', '2026-05-29 07:49:46'),
(46, 7, 'ancaman', 'Perkembangan Teknologi Cepat', 'Perkembangan teknologi yang cepat dapat membuat kurikulum Manajemen Informatika D3 menjadi tidak relevan jika tidak diupdate secara berkala. Hal ini dapat berdampak pada kemampuan lulusan dalam bersaing di dunia kerja.', 'tinggi', 'Mengupdate kurikulum secara berkala dan meningkatkan kerja sama dengan industri untuk memastikan relevansi program studi.', NULL, NULL, NULL, 0, '2026-05-29 07:49:57', '2026-05-29 07:49:57'),
(47, 7, 'ancaman', 'Persaingan Perguruan Tinggi', 'Persaingan antar perguruan tinggi di Bandung, Jawa Barat, dapat mempengaruhi minat calon mahasiswa untuk memilih Universitas Teknologi Digital. Hal ini dapat berdampak pada jumlah pendaftar dan kemampuan prodi untuk mencapai target mahasiswa.', 'sedang', 'Meningkatkan promosi dan kerja sama dengan sekolah-sekolah di sekitar Bandung untuk meningkatkan kesadaran dan minat calon mahasiswa.', NULL, NULL, NULL, 0, '2026-05-29 07:49:57', '2026-05-29 07:49:57'),
(48, 7, 'ancaman', 'Peraturan Pemerintah tentang Pendidikan', 'Peraturan pemerintah tentang pendidikan dapat berubah dan mempengaruhi keberlangsungan program studi Manajemen Informatika D3. Hal ini dapat berdampak pada akreditasi dan izin operasional prodi.', 'rendah', 'Mengikuti perkembangan peraturan pemerintah tentang pendidikan dan melakukan penyesuaian jika diperlukan.', NULL, NULL, NULL, 0, '2026-05-29 07:49:57', '2026-05-29 07:49:57'),
(49, 7, 'ancaman', 'Ketergantungan pada Teknologi', 'Ketergantungan pada teknologi dapat membuat prodi Manajemen Informatika D3 rentan terhadap gangguan teknologi, seperti serangan siber atau kegagalan sistem. Hal ini dapat berdampak pada keamanan dan kenyamanan mahasiswa.', 'sedang', 'Meningkatkan keamanan siber dan melakukan backup data secara berkala untuk mengurangi risiko gangguan teknologi.', NULL, NULL, NULL, 0, '2026-05-29 07:49:57', '2026-05-29 07:49:57'),
(50, 7, 'ancaman', 'Perubahan Kebutuhan Industri', 'Perubahan kebutuhan industri dapat mempengaruhi relevansi program studi Manajemen Informatika D3. Hal ini dapat berdampak pada kemampuan lulusan untuk mendapatkan pekerjaan yang sesuai.', 'tinggi', 'Mengembangkan kurikulum yang fleksibel dan melakukan kerja sama dengan industri untuk memastikan bahwa lulusan memiliki kemampuan yang sesuai dengan kebutuhan industri.', NULL, NULL, NULL, 0, '2026-05-29 07:49:57', '2026-05-29 07:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_eksternal_narasi`
--

CREATE TABLE `kondisi_eksternal_narasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `sub_bagian` varchar(10) NOT NULL,
  `narasi` longtext NOT NULL,
  `source_count` int(11) NOT NULL DEFAULT 0,
  `generated_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

CREATE TABLE `kurikulum` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tahun_berlaku` year(4) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kurikulum`
--

INSERT INTO `kurikulum` (`id`, `prodi_id`, `kode`, `nama`, `tahun_berlaku`, `aktif`, `created_at`) VALUES
(1, 7, 'K-2025', 'Kurikulum OBE 2025', '2026', 1, '2026-05-24 21:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `lam_butir`
--

CREATE TABLE `lam_butir` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_kriteria_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis_ipo` enum('input','proses','output') NOT NULL,
  `bobot` decimal(6,2) NOT NULL DEFAULT 0.00,
  `tipe_data` enum('kuantitatif','kualitatif','campuran') NOT NULL DEFAULT 'kualitatif',
  `formula` text DEFAULT NULL,
  `tabel_lkps_ref` varchar(50) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_butir`
--

INSERT INTO `lam_butir` (`id`, `lam_kriteria_id`, `kode`, `judul`, `deskripsi`, `jenis_ipo`, `bobot`, `tipe_data`, `formula`, `tabel_lkps_ref`, `urutan`) VALUES
(83, 10, 'A', 'A - Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS', 'Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(84, 11, 'B', 'B - Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria', 'Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(85, 12, '1.1.A', '1.1.A [Penetapan] - Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP', 'Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 3.00, 'kualitatif', NULL, NULL, 1),
(86, 12, '1.1.B', '1.1.B [Penetapan] - Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten', 'Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 2.50, 'kualitatif', NULL, NULL, 2),
(87, 12, '1.2.A', '1.2.A [Pelaksanaan] - Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP', 'Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 5.00, 'kualitatif', NULL, NULL, 3),
(88, 12, '1.2.B', '1.2.B [Pelaksanaan] - Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten', 'Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 5.00, 'kualitatif', NULL, NULL, 4),
(89, 12, '1.3.A', '1.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian standar sistem tata kelola internal', 'Efektifitas evaluasi ketercapaian standar sistem tata kelola internal (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 5),
(90, 12, '1.3.B', '1.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana', 'Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 6),
(91, 12, '1.4.A', '1.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal', 'Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 7),
(92, 12, '1.4.B', '1.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana', 'Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 8),
(93, 12, '1.5.A', '1.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal', 'Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(94, 12, '1.5.B', '1.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana', 'Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 10),
(95, 13, '2.1.A', '2.1.A [Penetapan] - Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK)', 'Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 5.00, 'kualitatif', NULL, NULL, 1),
(96, 13, '2.1.B', '2.1.B [Penetapan] - Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6)', 'Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 2),
(97, 13, '2.1.C', '2.1.C [Penetapan] - Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL)', 'Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 3),
(98, 13, '2.1.D', '2.1.D [Penetapan] - Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan)', 'Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 4),
(99, 13, '2.2.A', '2.2.A [Pelaksanaan] - Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru', 'Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 9.00, 'kualitatif', NULL, NULL, 5),
(100, 13, '2.2.B', '2.2.B [Pelaksanaan] - Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE', 'Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 6),
(101, 13, '2.2.C', '2.2.C [Pelaksanaan] - Efektifitas pelaksanaan fleksibilitas proses pembelajaran', 'Efektifitas pelaksanaan fleksibilitas proses pembelajaran (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 7),
(102, 13, '2.2.D', '2.2.D [Pelaksanaan] - Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja)', 'Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja) (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 30.00, 'kualitatif', NULL, NULL, 8),
(103, 13, '2.3.A', '2.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru', 'Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(104, 13, '2.3.B', '2.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE', 'Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 10),
(105, 13, '2.3.C', '2.3.C [Evaluasi] - Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran', 'Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 11),
(106, 13, '2.3.D', '2.3.D [Evaluasi] - Efektifitas evaluasi ketercapaian kompetensi lulusan', 'Efektifitas evaluasi ketercapaian kompetensi lulusan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 12),
(107, 13, '2.4.A', '2.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru', 'Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 13),
(108, 13, '2.4.B', '2.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE', 'Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 14),
(109, 13, '2.4.C', '2.4.C [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar', 'Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 15),
(110, 13, '2.4.D', '2.4.D [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan', 'Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 16),
(111, 13, '2.5.A', '2.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru', 'Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 17),
(112, 13, '2.5.B', '2.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE', 'Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 18),
(113, 13, '2.5.C', '2.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar', 'Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 19),
(114, 13, '2.5.D', '2.5.D [Peningkatan] - Efektifitas peningkatan/optimalisasi kompetensi lulusan', 'Efektifitas peningkatan/optimalisasi kompetensi lulusan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 20),
(115, 14, '3.1.A', '3.1.A [Penetapan] - Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian', 'Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(116, 14, '3.1.B', '3.1.B [Penetapan] - Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan)', 'Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 2),
(117, 14, '3.1.C', '3.1.C [Penetapan] - Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian', 'Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 3.00, 'kualitatif', NULL, NULL, 3),
(118, 14, '3.2.A', '3.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan', 'Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 8.00, 'kualitatif', NULL, NULL, 4),
(119, 14, '3.2.B', '3.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa', 'Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 6.00, 'kualitatif', NULL, NULL, 5),
(120, 14, '3.2.C', '3.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan', 'Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 18.00, 'kualitatif', NULL, NULL, 6),
(121, 14, '3.3.A', '3.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan', 'Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 7),
(122, 14, '3.3.B', '3.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa', 'Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 8),
(123, 14, '3.3.C', '3.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI', 'Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 9),
(124, 14, '3.4.A', '3.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan', 'Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 10),
(125, 14, '3.4.B', '3.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian', 'Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 11),
(126, 14, '3.4.C', '3.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI', 'Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 12),
(127, 14, '3.5.A', '3.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan', 'Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 13),
(128, 14, '3.5.B', '3.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian', 'Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 14),
(129, 14, '3.5.C', '3.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI', 'Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 15),
(130, 15, '4.1.A', '4.1.A [Penetapan] - Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran)', 'Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 3.00, 'kualitatif', NULL, NULL, 1),
(131, 15, '4.1.B', '4.1.B [Penetapan] - Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi)', 'Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 2.50, 'kualitatif', NULL, NULL, 2),
(132, 15, '4.1.C', '4.1.C [Penetapan] - Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM', 'Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 2.00, 'kualitatif', NULL, NULL, 3),
(133, 15, '4.2.A', '4.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM', 'Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 4),
(134, 15, '4.2.B', '4.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa', 'Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 6.00, 'kualitatif', NULL, NULL, 5),
(135, 15, '4.2.C', '4.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI', 'Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 15.00, 'kualitatif', NULL, NULL, 6),
(136, 15, '4.3.A', '4.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM', 'Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 7),
(137, 15, '4.3.B', '4.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa', 'Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 8),
(138, 15, '4.3.C', '4.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI', 'Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 9),
(139, 15, '4.4.A', '4.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan', 'Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 10),
(140, 15, '4.4.B', '4.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM', 'Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 11),
(141, 15, '4.4.C', '4.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI', 'Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 12),
(142, 15, '4.5.A', '4.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan', 'Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 13),
(143, 15, '4.5.B', '4.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa', 'Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 2.00, 'kualitatif', NULL, NULL, 14),
(144, 15, '4.5.C', '4.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi', 'Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 2.50, 'kualitatif', NULL, NULL, 15),
(145, 16, '5.1.A', '5.1.A [Penetapan] - Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional)', 'Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional) (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 3.00, 'kualitatif', NULL, NULL, 1),
(146, 16, '5.1.B', '5.1.B [Penetapan] - Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM', 'Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 2.00, 'kualitatif', NULL, NULL, 2),
(147, 16, '5.2.A', '5.2.A [Pelaksanaan] - Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM)', 'Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM) (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 5.00, 'kualitatif', NULL, NULL, 3),
(148, 16, '5.2.B', '5.2.B [Pelaksanaan] - Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong', 'Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 4),
(149, 16, '5.3.A', '5.3.A [Evaluasi] - Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel', 'Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 6.00, 'kualitatif', NULL, NULL, 5),
(150, 16, '5.3.B', '5.3.B [Evaluasi] - Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong', 'Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 6),
(151, 16, '5.4.A', '5.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel', 'Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 7),
(152, 16, '5.4.B', '5.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi', 'Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 8),
(153, 16, '5.5.A', '5.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel', 'Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(154, 16, '5.5.B', '5.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi', 'Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 10),
(155, 17, '6.1', '6.1 [Penetapan] - Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi', 'Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi (tahap PPEPP: Penetapan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'input', 5.00, 'kualitatif', NULL, NULL, 1),
(156, 17, '6.2', '6.2 [Pelaksanaan] - Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan', 'Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Pelaksanaan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 8.00, 'kualitatif', NULL, NULL, 2),
(157, 17, '6.3', '6.3 [Evaluasi] - Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan', 'Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Evaluasi) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 13.00, 'kualitatif', NULL, NULL, 3),
(158, 17, '6.4', '6.4 [Pengendalian] - Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan', 'Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Pengendalian) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'proses', 6.00, 'kualitatif', NULL, NULL, 4),
(159, 17, '6.5', '6.5 [Peningkatan] - Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan', 'Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Peningkatan) — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 10.00, 'kualitatif', NULL, NULL, 5),
(160, 18, 'D.1', 'D.1 - Mata kuliah inti/khas program studi', 'Mata kuliah inti/khas program studi — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 1),
(161, 18, 'D.2', 'D.2 - Mata kuliah domain spesifik dan lingkungan prodi infokom', 'Mata kuliah domain spesifik dan lingkungan prodi infokom — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 2),
(162, 18, 'D.3', 'D.3 - Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan', 'Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 3),
(163, 18, 'D.4', 'D.4 - Proyek Utama (Capstone Project) yang relevan', 'Proyek Utama (Capstone Project) yang relevan — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 4),
(164, 18, 'D.5', 'D.5 - Pengembangan bidang Infokom yang digunakan di masyarakat', 'Pengembangan bidang Infokom yang digunakan di masyarakat — mengacu pada Matriks Penilaian LAM INFOKOM 2.0 Sarjana (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 5),
(411, 46, 'A', 'A - Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS', 'Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(412, 47, 'B', 'B - Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria', 'Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(413, 48, '1.1.A', '1.1.A [Penetapan] - Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP', 'Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 1),
(414, 48, '1.1.B', '1.1.B [Penetapan] - Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten', 'Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 2.00, 'kualitatif', NULL, NULL, 2),
(415, 48, '1.2.A', '1.2.A [Pelaksanaan] - Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP', 'Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 3),
(416, 48, '1.2.B', '1.2.B [Pelaksanaan] - Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten', 'Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 4),
(417, 48, '1.3.A', '1.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian standar sistem tata kelola internal', 'Efektifitas evaluasi ketercapaian standar sistem tata kelola internal (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 7.50, 'kualitatif', NULL, NULL, 5),
(418, 48, '1.3.B', '1.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian standar fungsi SPMI', 'Efektifitas evaluasi ketercapaian standar fungsi SPMI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 6),
(419, 48, '1.4.A', '1.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal', 'Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 7),
(420, 48, '1.4.B', '1.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fungsi SPMI', 'Efektifitas tindak lanjut hasil evaluasi fungsi SPMI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 8),
(421, 48, '1.5.A', '1.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal', 'Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(422, 48, '1.5.B', '1.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi fungsi SPMI', 'Efektifitas peningkatan/optimalisasi fungsi SPMI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 2.50, 'kualitatif', NULL, NULL, 10),
(423, 49, '2.1.A', '2.1.A [Penetapan] - Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal', 'Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 5.00, 'kualitatif', NULL, NULL, 1),
(424, 49, '2.1.B', '2.1.B [Penetapan] - Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5)', 'Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 2),
(425, 49, '2.1.C', '2.1.C [Penetapan] - Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential)', 'Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 3),
(426, 49, '2.1.D', '2.1.D [Penetapan] - Kebijakan kompetensi lulusan (rekognisi DUDIKA)', 'Kebijakan kompetensi lulusan (rekognisi DUDIKA) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 4),
(427, 49, '2.2.A', '2.2.A [Pelaksanaan] - Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru', 'Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 9.00, 'kualitatif', NULL, NULL, 5),
(428, 49, '2.2.B', '2.2.B [Pelaksanaan] - Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE', 'Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 6),
(429, 49, '2.2.C', '2.2.C [Pelaksanaan] - Efektifitas pelaksanaan fleksibilitas proses pembelajaran', 'Efektifitas pelaksanaan fleksibilitas proses pembelajaran (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 7.00, 'kualitatif', NULL, NULL, 7),
(430, 49, '2.2.D', '2.2.D [Pelaksanaan] - Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja)', 'Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja) (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 30.00, 'kualitatif', NULL, NULL, 8),
(431, 49, '2.3.A', '2.3.A [Evaluasi] - Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru', 'Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(432, 49, '2.3.B', '2.3.B [Evaluasi] - Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE', 'Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 10),
(433, 49, '2.3.C', '2.3.C [Evaluasi] - Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran', 'Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 11),
(434, 49, '2.3.D', '2.3.D [Evaluasi] - Efektifitas evaluasi ketercapaian kompetensi lulusan', 'Efektifitas evaluasi ketercapaian kompetensi lulusan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 12),
(435, 49, '2.4.A', '2.4.A [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru', 'Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 13),
(436, 49, '2.4.B', '2.4.B [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE', 'Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 14),
(437, 49, '2.4.C', '2.4.C [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran', 'Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 15),
(438, 49, '2.4.D', '2.4.D [Pengendalian] - Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan', 'Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 16),
(439, 49, '2.5.A', '2.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru', 'Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 17),
(440, 49, '2.5.B', '2.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE', 'Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 18),
(441, 49, '2.5.C', '2.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran', 'Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 19),
(442, 49, '2.5.D', '2.5.D [Peningkatan] - Efektifitas peningkatan/optimalisasi kompetensi lulusan', 'Efektifitas peningkatan/optimalisasi kompetensi lulusan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 20),
(443, 50, '3.1.A', '3.1.A [Penetapan] - Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian', 'Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 1.50, 'kualitatif', NULL, NULL, 1),
(444, 50, '3.1.B', '3.1.B [Penetapan] - Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa', 'Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 1.50, 'kualitatif', NULL, NULL, 2),
(445, 50, '3.1.C', '3.1.C [Penetapan] - Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian', 'Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 1.50, 'kualitatif', NULL, NULL, 3),
(446, 50, '3.2.A', '3.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan', 'Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.50, 'kualitatif', NULL, NULL, 4),
(447, 50, '3.2.B', '3.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa', 'Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 2.50, 'kualitatif', NULL, NULL, 5),
(448, 50, '3.2.C', '3.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian', 'Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 8.00, 'kualitatif', NULL, NULL, 6),
(449, 50, '3.3.A', '3.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan', 'Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 2.00, 'kualitatif', NULL, NULL, 7),
(450, 50, '3.3.B', '3.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan penelitian', 'Efektifitas evaluasi implementasi peta jalan penelitian (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 1.50, 'kualitatif', NULL, NULL, 8),
(451, 50, '3.3.C', '3.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah, publikasi, HKI', 'Efektifitas evaluasi perolehan hibah, publikasi, HKI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 1.00, 'kualitatif', NULL, NULL, 9),
(452, 50, '3.4.A', '3.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan', 'Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 10),
(453, 50, '3.4.B', '3.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian', 'Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 11),
(454, 50, '3.4.C', '3.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI', 'Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 1.00, 'kualitatif', NULL, NULL, 12),
(455, 50, '3.5.A', '3.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan', 'Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 1.50, 'kualitatif', NULL, NULL, 13),
(456, 50, '3.5.B', '3.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian', 'Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 1.50, 'kualitatif', NULL, NULL, 14),
(457, 50, '3.5.C', '3.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI', 'Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 1.00, 'kualitatif', NULL, NULL, 15),
(458, 51, '4.1.A', '4.1.A [Penetapan] - Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM', 'Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 6.00, 'kualitatif', NULL, NULL, 1),
(459, 51, '4.1.B', '4.1.B [Penetapan] - Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa', 'Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 5.00, 'kualitatif', NULL, NULL, 2),
(460, 51, '4.1.C', '4.1.C [Penetapan] - Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI', 'Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 4.00, 'kualitatif', NULL, NULL, 3),
(461, 51, '4.2.A', '4.2.A [Pelaksanaan] - Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM', 'Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 12.00, 'kualitatif', NULL, NULL, 4),
(462, 51, '4.2.B', '4.2.B [Pelaksanaan] - Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa', 'Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 9.00, 'kualitatif', NULL, NULL, 5),
(463, 51, '4.2.C', '4.2.C [Pelaksanaan] - Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI', 'Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 20.00, 'kualitatif', NULL, NULL, 6),
(464, 51, '4.3.A', '4.3.A [Evaluasi] - Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM', 'Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 6.00, 'kualitatif', NULL, NULL, 7),
(465, 51, '4.3.B', '4.3.B [Evaluasi] - Efektifitas evaluasi implementasi peta jalan PkM', 'Efektifitas evaluasi implementasi peta jalan PkM (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.50, 'kualitatif', NULL, NULL, 8),
(466, 51, '4.3.C', '4.3.C [Evaluasi] - Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI', 'Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(467, 51, '4.4.A', '4.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan', 'Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 5.00, 'kualitatif', NULL, NULL, 10),
(468, 51, '4.4.B', '4.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM', 'Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 11),
(469, 51, '4.4.C', '4.4.C [Pengendalian] - Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI', 'Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 12),
(470, 51, '4.5.A', '4.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan', 'Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 6.00, 'kualitatif', NULL, NULL, 13),
(471, 51, '4.5.B', '4.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM', 'Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.50, 'kualitatif', NULL, NULL, 14),
(472, 51, '4.5.C', '4.5.C [Peningkatan] - Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI', 'Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 15),
(473, 52, '5.1.A', '5.1.A [Penetapan] - Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional)', 'Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional) (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 3.00, 'kualitatif', NULL, NULL, 1),
(474, 52, '5.1.B', '5.1.B [Penetapan] - Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM', 'Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 2.00, 'kualitatif', NULL, NULL, 2),
(475, 52, '5.2.A', '5.2.A [Pelaksanaan] - Efektifitas pelaksanaan sistem tata kelola otonom/transparan', 'Efektifitas pelaksanaan sistem tata kelola otonom/transparan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 5.00, 'kualitatif', NULL, NULL, 3),
(476, 52, '5.2.B', '5.2.B [Pelaksanaan] - Efektifitas pelaksanaan audit mutu pemenuhan tupoksi', 'Efektifitas pelaksanaan audit mutu pemenuhan tupoksi (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 4),
(477, 52, '5.3.A', '5.3.A [Evaluasi] - Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel', 'Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 6.00, 'kualitatif', NULL, NULL, 5),
(478, 52, '5.3.B', '5.3.B [Evaluasi] - Efektifitas evaluasi audit mutu pemenuhan tupoksi', 'Efektifitas evaluasi audit mutu pemenuhan tupoksi (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 6),
(479, 52, '5.4.A', '5.4.A [Pengendalian] - Efektifitas tindak lanjut evaluasi sistem tata kelola', 'Efektifitas tindak lanjut evaluasi sistem tata kelola (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 3.00, 'kualitatif', NULL, NULL, 7),
(480, 52, '5.4.B', '5.4.B [Pengendalian] - Efektifitas tindak lanjut evaluasi audit mutu', 'Efektifitas tindak lanjut evaluasi audit mutu (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 2.00, 'kualitatif', NULL, NULL, 8),
(481, 52, '5.5.A', '5.5.A [Peningkatan] - Efektifitas peningkatan/optimalisasi sistem tata kelola', 'Efektifitas peningkatan/optimalisasi sistem tata kelola (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 9),
(482, 52, '5.5.B', '5.5.B [Peningkatan] - Efektifitas peningkatan/optimalisasi audit mutu', 'Efektifitas peningkatan/optimalisasi audit mutu (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 10),
(483, 53, '6.1', '6.1 [Penetapan] - Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi', 'Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi (tahap PPEPP: Penetapan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'input', 5.00, 'kualitatif', NULL, NULL, 1),
(484, 53, '6.2', '6.2 [Pelaksanaan] - Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan', 'Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Pelaksanaan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 8.00, 'kualitatif', NULL, NULL, 2),
(485, 53, '6.3', '6.3 [Evaluasi] - Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan', 'Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan (tahap PPEPP: Evaluasi) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 13.00, 'kualitatif', NULL, NULL, 3);
INSERT INTO `lam_butir` (`id`, `lam_kriteria_id`, `kode`, `judul`, `deskripsi`, `jenis_ipo`, `bobot`, `tipe_data`, `formula`, `tabel_lkps_ref`, `urutan`) VALUES
(486, 53, '6.4', '6.4 [Pengendalian] - Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan', 'Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Pengendalian) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'proses', 4.00, 'kualitatif', NULL, NULL, 4),
(487, 53, '6.5', '6.5 [Peningkatan] - Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan', 'Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan (tahap PPEPP: Peningkatan) — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 10.00, 'kualitatif', NULL, NULL, 5),
(488, 54, 'D.1', 'D.1 - Mata kuliah inti/khas program studi', 'Mata kuliah inti/khas program studi — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 4.00, 'kualitatif', NULL, NULL, 1),
(489, 54, 'D.2', 'D.2 - Mata kuliah domain spesifik dan lingkungan prodi infokom', 'Mata kuliah domain spesifik dan lingkungan prodi infokom — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 2),
(490, 54, 'D.3', 'D.3 - Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan', 'Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 3.00, 'kualitatif', NULL, NULL, 3),
(491, 54, 'D.4', 'D.4 - Proyek Utama (Capstone Project) yang relevan', 'Proyek Utama (Capstone Project) yang relevan — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 4),
(492, 54, 'D.5', 'D.5 - Pengembangan bidang Infokom yang digunakan di masyarakat', 'Pengembangan bidang Infokom yang digunakan di masyarakat — mengacu Matriks Penilaian LAM INFOKOM 2.0 D3 (2025).', 'output', 5.00, 'kualitatif', NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `lam_butir_rubrik`
--

CREATE TABLE `lam_butir_rubrik` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_butir_id` int(10) UNSIGNED NOT NULL,
  `skor` tinyint(1) NOT NULL,
  `label` varchar(30) NOT NULL,
  `deskriptor` text NOT NULL,
  `syarat_json` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_butir_rubrik`
--

INSERT INTO `lam_butir_rubrik` (`id`, `lam_butir_id`, `skor`, `label`, `deskriptor`, `syarat_json`) VALUES
(329, 83, 4, 'Sangat Baik', 'Sangat baik: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(330, 83, 3, 'Baik', 'Baik: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(331, 83, 2, 'Cukup', 'Cukup: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(332, 83, 1, 'Kurang', 'Kurang: Kondisi Eksternal: analisis lingkungan makro dan mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(333, 84, 4, 'Sangat Baik', 'Sangat baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(334, 84, 3, 'Baik', 'Baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(335, 84, 2, 'Cukup', 'Cukup: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(336, 84, 1, 'Kurang', 'Kurang: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(337, 85, 4, 'Sangat Baik', 'Sangat baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(338, 85, 3, 'Baik', 'Baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(339, 85, 2, 'Cukup', 'Cukup: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(340, 85, 1, 'Kurang', 'Kurang: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(341, 86, 4, 'Sangat Baik', 'Sangat baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(342, 86, 3, 'Baik', 'Baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(343, 86, 2, 'Cukup', 'Cukup: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(344, 86, 1, 'Kurang', 'Kurang: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(345, 87, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(346, 87, 3, 'Baik', 'Baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(347, 87, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(348, 87, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(349, 88, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(350, 88, 3, 'Baik', 'Baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(351, 88, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(352, 88, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(353, 89, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(354, 89, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(355, 89, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(356, 89, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(357, 90, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(358, 90, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(359, 90, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(360, 90, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(361, 91, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(362, 91, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(363, 91, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(364, 91, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(365, 92, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(366, 92, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(367, 92, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(368, 92, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(369, 93, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(370, 93, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(371, 93, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(372, 93, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(373, 94, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(374, 94, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(375, 94, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(376, 94, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi standar fungsi SPMI dan SDM pelaksana, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(377, 95, 4, 'Sangat Baik', 'Sangat baik: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(378, 95, 3, 'Baik', 'Baik: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(379, 95, 2, 'Cukup', 'Cukup: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(380, 95, 1, 'Kurang', 'Kurang: Kebijakan/standar/indikator DTPR dan penerimaan mahasiswa baru (akses, keragaman, afirmasi, ABK), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(381, 96, 4, 'Sangat Baik', 'Sangat baik: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(382, 96, 3, 'Baik', 'Baik: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(383, 96, 2, 'Cukup', 'Cukup: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(384, 96, 1, 'Kurang', 'Kurang: Kebijakan isi pembelajaran dan kurikulum OBE (KKNI level 6), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(385, 97, 4, 'Sangat Baik', 'Sangat baik: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(386, 97, 3, 'Baik', 'Baik: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(387, 97, 2, 'Cukup', 'Cukup: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(388, 97, 1, 'Kurang', 'Kurang: Kebijakan fleksibilitas pembelajaran (luring/daring/hibrida, CBL, PBL, RPL), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(389, 98, 4, 'Sangat Baik', 'Sangat baik: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(390, 98, 3, 'Baik', 'Baik: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(391, 98, 2, 'Cukup', 'Cukup: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(392, 98, 1, 'Kurang', 'Kurang: Kebijakan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja lulusan), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(393, 99, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(394, 99, 3, 'Baik', 'Baik: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(395, 99, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(396, 99, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(397, 100, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(398, 100, 3, 'Baik', 'Baik: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(399, 100, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(400, 100, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(401, 101, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(402, 101, 3, 'Baik', 'Baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(403, 101, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(404, 101, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(405, 102, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(406, 102, 3, 'Baik', 'Baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(407, 102, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(408, 102, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan kompetensi lulusan (rekognisi, DUDIKA, sebaran kerja), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(409, 103, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(410, 103, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(411, 103, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(412, 103, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(413, 104, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(414, 104, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(415, 104, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(416, 104, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(417, 105, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(418, 105, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(419, 105, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(420, 105, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(421, 106, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(422, 106, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(423, 106, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(424, 106, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(425, 107, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(426, 107, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(427, 107, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(428, 107, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(429, 108, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(430, 108, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(431, 108, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(432, 108, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(433, 109, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(434, 109, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(435, 109, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(436, 109, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(437, 110, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(438, 110, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(439, 110, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(440, 110, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(441, 111, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(442, 111, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(443, 111, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(444, 111, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi DTPR dan penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(445, 112, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(446, 112, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(447, 112, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(448, 112, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi isi pembelajaran dan kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(449, 113, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(450, 113, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(451, 113, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(452, 113, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi fleksibilitas dan pemenuhan beban belajar, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(453, 114, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(454, 114, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(455, 114, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(456, 114, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(457, 115, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(458, 115, 3, 'Baik', 'Baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(459, 115, 2, 'Cukup', 'Cukup: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(460, 115, 1, 'Kurang', 'Kurang: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, dan peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(461, 116, 4, 'Sangat Baik', 'Sangat baik: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(462, 116, 3, 'Baik', 'Baik: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(463, 116, 2, 'Cukup', 'Cukup: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(464, 116, 1, 'Kurang', 'Kurang: Kebijakan implementasi peta jalan penelitian dan pelibatan mahasiswa (visi misi keilmuan), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(465, 117, 4, 'Sangat Baik', 'Sangat baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(466, 117, 3, 'Baik', 'Baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(467, 117, 2, 'Cukup', 'Cukup: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(468, 117, 1, 'Kurang', 'Kurang: Kebijakan perolehan hibah, kerjasama, publikasi, HKI, dan keberlanjutan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(469, 118, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(470, 118, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(471, 118, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(472, 118, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(473, 119, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(474, 119, 3, 'Baik', 'Baik: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(475, 119, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(476, 119, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(477, 120, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(478, 120, 3, 'Baik', 'Baik: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(479, 120, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(480, 120, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan perolehan hibah, kerjasama, publikasi, HKI, keberlanjutan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(481, 121, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(482, 121, 3, 'Baik', 'Baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(483, 121, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(484, 121, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(485, 122, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(486, 122, 3, 'Baik', 'Baik: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(487, 122, 2, 'Cukup', 'Cukup: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(488, 122, 1, 'Kurang', 'Kurang: Efektifitas evaluasi implementasi peta jalan penelitian dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(489, 123, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(490, 123, 3, 'Baik', 'Baik: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(491, 123, 2, 'Cukup', 'Cukup: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(492, 123, 1, 'Kurang', 'Kurang: Efektifitas evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(493, 124, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(494, 124, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(495, 124, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(496, 124, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(497, 125, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(498, 125, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(499, 125, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(500, 125, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(501, 126, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(502, 126, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(503, 126, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(504, 126, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(505, 127, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(506, 127, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(507, 127, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(508, 127, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana, DTPR, pembiayaan, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(509, 128, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(510, 128, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(511, 128, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(512, 128, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(513, 129, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(514, 129, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(515, 129, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(516, 129, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah, kerjasama, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(517, 130, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(518, 130, 3, 'Baik', 'Baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(519, 130, 2, 'Cukup', 'Cukup: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(520, 130, 1, 'Kurang', 'Kurang: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM (layanan kepakaran), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(521, 131, 4, 'Sangat Baik', 'Sangat baik: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(522, 131, 3, 'Baik', 'Baik: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(523, 131, 2, 'Cukup', 'Cukup: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(524, 131, 1, 'Kurang', 'Kurang: Kebijakan implementasi peta jalan PkM dan pelibatan mahasiswa (visi misi), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(525, 132, 4, 'Sangat Baik', 'Sangat baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(526, 132, 3, 'Baik', 'Baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(527, 132, 2, 'Cukup', 'Cukup: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(528, 132, 1, 'Kurang', 'Kurang: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, keberlanjutan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(529, 133, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(530, 133, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(531, 133, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(532, 133, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(533, 134, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(534, 134, 3, 'Baik', 'Baik: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(535, 134, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(536, 134, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(537, 135, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(538, 135, 3, 'Baik', 'Baik: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(539, 135, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(540, 135, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(541, 136, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(542, 136, 3, 'Baik', 'Baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(543, 136, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(544, 136, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(545, 137, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(546, 137, 3, 'Baik', 'Baik: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(547, 137, 2, 'Cukup', 'Cukup: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(548, 137, 1, 'Kurang', 'Kurang: Efektifitas evaluasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(549, 138, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(550, 138, 3, 'Baik', 'Baik: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(551, 138, 2, 'Cukup', 'Cukup: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(552, 138, 1, 'Kurang', 'Kurang: Efektifitas evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(553, 139, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(554, 139, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(555, 139, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(556, 139, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(557, 140, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(558, 140, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(559, 140, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(560, 140, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(561, 141, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(562, 141, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(563, 141, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(564, 141, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(565, 142, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(566, 142, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(567, 142, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(568, 142, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, DTPR, pembiayaan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(569, 143, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(570, 143, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(571, 143, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(572, 143, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM dan pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(573, 144, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(574, 144, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(575, 144, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(576, 144, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, kerjasama, diseminasi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(577, 145, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(578, 145, 3, 'Baik', 'Baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(579, 145, 2, 'Cukup', 'Cukup: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(580, 145, 1, 'Kurang', 'Kurang: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana dan SDM profesional), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(581, 146, 4, 'Sangat Baik', 'Sangat baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(582, 146, 3, 'Baik', 'Baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(583, 146, 2, 'Cukup', 'Cukup: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(584, 146, 1, 'Kurang', 'Kurang: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(585, 147, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(586, 147, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(587, 147, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(588, 147, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sistem tata kelola otonom/transparan (sarana dan SDM), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(589, 148, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(590, 148, 3, 'Baik', 'Baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(591, 148, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(592, 148, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(593, 149, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(594, 149, 3, 'Baik', 'Baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(595, 149, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(596, 149, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(597, 150, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(598, 150, 3, 'Baik', 'Baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(599, 150, 2, 'Cukup', 'Cukup: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(600, 150, 1, 'Kurang', 'Kurang: Efektifitas evaluasi audit mutu pemenuhan tupoksi tata kelola/tata pamong, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL);
INSERT INTO `lam_butir_rubrik` (`id`, `lam_butir_id`, `skor`, `label`, `deskriptor`, `syarat_json`) VALUES
(601, 151, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(602, 151, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(603, 151, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(604, 151, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(605, 152, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(606, 152, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(607, 152, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(608, 152, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(609, 153, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(610, 153, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(611, 153, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(612, 153, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(613, 154, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(614, 154, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(615, 154, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(616, 154, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(617, 155, 4, 'Sangat Baik', 'Sangat baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(618, 155, 3, 'Baik', 'Baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(619, 155, 2, 'Cukup', 'Cukup: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(620, 155, 1, 'Kurang', 'Kurang: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(621, 156, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(622, 156, 3, 'Baik', 'Baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(623, 156, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(624, 156, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(625, 157, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(626, 157, 3, 'Baik', 'Baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(627, 157, 2, 'Cukup', 'Cukup: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(628, 157, 1, 'Kurang', 'Kurang: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(629, 158, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(630, 158, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(631, 158, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(632, 158, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(633, 159, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(634, 159, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(635, 159, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(636, 159, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(637, 160, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(638, 160, 3, 'Baik', 'Baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(639, 160, 2, 'Cukup', 'Cukup: Mata kuliah inti/khas program studi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(640, 160, 1, 'Kurang', 'Kurang: Mata kuliah inti/khas program studi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(641, 161, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(642, 161, 3, 'Baik', 'Baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(643, 161, 2, 'Cukup', 'Cukup: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(644, 161, 1, 'Kurang', 'Kurang: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(645, 162, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(646, 162, 3, 'Baik', 'Baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(647, 162, 2, 'Cukup', 'Cukup: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(648, 162, 1, 'Kurang', 'Kurang: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(649, 163, 4, 'Sangat Baik', 'Sangat baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(650, 163, 3, 'Baik', 'Baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(651, 163, 2, 'Cukup', 'Cukup: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(652, 163, 1, 'Kurang', 'Kurang: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(653, 164, 4, 'Sangat Baik', 'Sangat baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(654, 164, 3, 'Baik', 'Baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(655, 164, 2, 'Cukup', 'Cukup: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(656, 164, 1, 'Kurang', 'Kurang: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1641, 411, 4, 'Sangat Baik', 'Sangat baik: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1642, 411, 3, 'Baik', 'Baik: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1643, 411, 2, 'Cukup', 'Cukup: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1644, 411, 1, 'Kurang', 'Kurang: Kondisi Eksternal: analisis lingkungan makro & mikro yang mempengaruhi eksistensi dan pengembangan PS/UPPS, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1645, 412, 4, 'Sangat Baik', 'Sangat baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1646, 412, 3, 'Baik', 'Baik: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1647, 412, 2, 'Cukup', 'Cukup: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1648, 412, 1, 'Kurang', 'Kurang: Profil UPPS / Analisis Internal: penyajian informasi ringkas dan konsisten untuk seluruh kriteria, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1649, 413, 4, 'Sangat Baik', 'Sangat baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1650, 413, 3, 'Baik', 'Baik: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1651, 413, 2, 'Cukup', 'Cukup: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1652, 413, 1, 'Kurang', 'Kurang: Kebijakan, standar, dan indikator sistem tata kelola internal (administrasi akademik, keuangan, SDM) dalam siklus PPEPP, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1653, 414, 4, 'Sangat Baik', 'Sangat baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1654, 414, 3, 'Baik', 'Baik: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1655, 414, 2, 'Cukup', 'Cukup: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1656, 414, 1, 'Kurang', 'Kurang: Kebijakan, standar, dan indikator fungsi SPMI dengan SDM yang kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1657, 415, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1658, 415, 3, 'Baik', 'Baik: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1659, 415, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1660, 415, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan kegiatan sistem tata kelola internal sesuai SOP, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1661, 416, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1662, 416, 3, 'Baik', 'Baik: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1663, 416, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1664, 416, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan standar fungsi SPMI dengan SDM kompeten, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1665, 417, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1666, 417, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1667, 417, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1668, 417, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1669, 418, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1670, 418, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1671, 418, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1672, 418, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian standar fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1673, 419, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1674, 419, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1675, 419, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1676, 419, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1677, 420, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1678, 420, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1679, 420, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1680, 420, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1681, 421, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1682, 421, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1683, 421, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1684, 421, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi standar sistem tata kelola internal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1685, 422, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1686, 422, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1687, 422, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1688, 422, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi fungsi SPMI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1689, 423, 4, 'Sangat Baik', 'Sangat baik: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1690, 423, 3, 'Baik', 'Baik: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1691, 423, 2, 'Cukup', 'Cukup: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1692, 423, 1, 'Kurang', 'Kurang: Kebijakan DTPR & dosen praktisi, penerimaan mahasiswa baru, keragaman asal, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1693, 424, 4, 'Sangat Baik', 'Sangat baik: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1694, 424, 3, 'Baik', 'Baik: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1695, 424, 2, 'Cukup', 'Cukup: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1696, 424, 1, 'Kurang', 'Kurang: Kebijakan isi pembelajaran & kurikulum OBE (KKNI level 5), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1697, 425, 4, 'Sangat Baik', 'Sangat baik: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1698, 425, 3, 'Baik', 'Baik: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1699, 425, 2, 'Cukup', 'Cukup: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1700, 425, 1, 'Kurang', 'Kurang: Kebijakan fleksibilitas proses pembelajaran (RPL, micro-credential), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1701, 426, 4, 'Sangat Baik', 'Sangat baik: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1702, 426, 3, 'Baik', 'Baik: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1703, 426, 2, 'Cukup', 'Cukup: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1704, 426, 1, 'Kurang', 'Kurang: Kebijakan kompetensi lulusan (rekognisi DUDIKA), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1705, 427, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1706, 427, 3, 'Baik', 'Baik: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1707, 427, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1708, 427, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan DTPR & dosen praktisi, penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1709, 428, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1710, 428, 3, 'Baik', 'Baik: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1711, 428, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1712, 428, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1713, 429, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1714, 429, 3, 'Baik', 'Baik: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1715, 429, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1716, 429, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1717, 430, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1718, 430, 3, 'Baik', 'Baik: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1719, 430, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1720, 430, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan kompetensi lulusan (rekognisi DUDIKA, sebaran kerja), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1721, 431, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1722, 431, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1723, 431, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1724, 431, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1725, 432, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1726, 432, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1727, 432, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1728, 432, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1729, 433, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1730, 433, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1731, 433, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1732, 433, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian fleksibilitas proses pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1733, 434, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1734, 434, 3, 'Baik', 'Baik: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1735, 434, 2, 'Cukup', 'Cukup: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1736, 434, 1, 'Kurang', 'Kurang: Efektifitas evaluasi ketercapaian kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1737, 435, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1738, 435, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1739, 435, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1740, 435, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1741, 436, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1742, 436, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1743, 436, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1744, 436, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1745, 437, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1746, 437, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1747, 437, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1748, 437, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi fleksibilitas pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1749, 438, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1750, 438, 3, 'Baik', 'Baik: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1751, 438, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1752, 438, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut hasil evaluasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1753, 439, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1754, 439, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1755, 439, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1756, 439, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi DTPR & penerimaan mahasiswa baru, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1757, 440, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1758, 440, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1759, 440, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1760, 440, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi isi pembelajaran & kurikulum OBE, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1761, 441, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1762, 441, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1763, 441, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1764, 441, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi fleksibilitas pembelajaran, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1765, 442, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1766, 442, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1767, 442, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1768, 442, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi kompetensi lulusan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1769, 443, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1770, 443, 3, 'Baik', 'Baik: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1771, 443, 2, 'Cukup', 'Cukup: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1772, 443, 1, 'Kurang', 'Kurang: Kebijakan sarana/prasarana penelitian, DTPR, pembiayaan, peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1773, 444, 4, 'Sangat Baik', 'Sangat baik: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1774, 444, 3, 'Baik', 'Baik: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1775, 444, 2, 'Cukup', 'Cukup: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1776, 444, 1, 'Kurang', 'Kurang: Kebijakan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1777, 445, 4, 'Sangat Baik', 'Sangat baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1778, 445, 3, 'Baik', 'Baik: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1779, 445, 2, 'Cukup', 'Cukup: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1780, 445, 1, 'Kurang', 'Kurang: Kebijakan perolehan hibah, kerjasama, publikasi, HKI penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1781, 446, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1782, 446, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1783, 446, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1784, 446, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1785, 447, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1786, 447, 3, 'Baik', 'Baik: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1787, 447, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1788, 447, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan implementasi peta jalan penelitian & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1789, 448, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1790, 448, 3, 'Baik', 'Baik: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1791, 448, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1792, 448, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan perolehan hibah, publikasi, HKI penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1793, 449, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1794, 449, 3, 'Baik', 'Baik: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1795, 449, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1796, 449, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sarana/prasarana penelitian, DTPR, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1797, 450, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1798, 450, 3, 'Baik', 'Baik: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1799, 450, 2, 'Cukup', 'Cukup: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1800, 450, 1, 'Kurang', 'Kurang: Efektifitas evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1801, 451, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1802, 451, 3, 'Baik', 'Baik: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1803, 451, 2, 'Cukup', 'Cukup: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1804, 451, 1, 'Kurang', 'Kurang: Efektifitas evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1805, 452, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1806, 452, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1807, 452, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1808, 452, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1809, 453, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1810, 453, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1811, 453, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1812, 453, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1813, 454, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1814, 454, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1815, 454, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1816, 454, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1817, 455, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1818, 455, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1819, 455, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1820, 455, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana penelitian, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1821, 456, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1822, 456, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1823, 456, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1824, 456, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan penelitian, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1825, 457, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1826, 457, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1827, 457, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1828, 457, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah, publikasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1829, 458, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1830, 458, 3, 'Baik', 'Baik: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1831, 458, 2, 'Cukup', 'Cukup: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1832, 458, 1, 'Kurang', 'Kurang: Kebijakan sarana/prasarana PkM, DTPR, pembiayaan, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1833, 459, 4, 'Sangat Baik', 'Sangat baik: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1834, 459, 3, 'Baik', 'Baik: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1835, 459, 2, 'Cukup', 'Cukup: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1836, 459, 1, 'Kurang', 'Kurang: Kebijakan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1837, 460, 4, 'Sangat Baik', 'Sangat baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1838, 460, 3, 'Baik', 'Baik: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1839, 460, 2, 'Cukup', 'Cukup: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1840, 460, 1, 'Kurang', 'Kurang: Kebijakan perolehan hibah PkM, kerjasama, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1841, 461, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1842, 461, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1843, 461, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1844, 461, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1845, 462, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1846, 462, 3, 'Baik', 'Baik: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1847, 462, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1848, 462, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan implementasi peta jalan PkM & pelibatan mahasiswa, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1849, 463, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1850, 463, 3, 'Baik', 'Baik: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1851, 463, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1852, 463, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1853, 464, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1854, 464, 3, 'Baik', 'Baik: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1855, 464, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1856, 464, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sarana/prasarana PkM, DTPR, peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1857, 465, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1858, 465, 3, 'Baik', 'Baik: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1859, 465, 2, 'Cukup', 'Cukup: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1860, 465, 1, 'Kurang', 'Kurang: Efektifitas evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1861, 466, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1862, 466, 3, 'Baik', 'Baik: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1863, 466, 2, 'Cukup', 'Cukup: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1864, 466, 1, 'Kurang', 'Kurang: Efektifitas evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1865, 467, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1866, 467, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1867, 467, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL);
INSERT INTO `lam_butir_rubrik` (`id`, `lam_butir_id`, `skor`, `label`, `deskriptor`, `syarat_json`) VALUES
(1868, 467, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1869, 468, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1870, 468, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1871, 468, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1872, 468, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1873, 469, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1874, 469, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1875, 469, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1876, 469, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1877, 470, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1878, 470, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1879, 470, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1880, 470, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sarana/prasarana PkM, peta jalan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1881, 471, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1882, 471, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1883, 471, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1884, 471, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi implementasi peta jalan PkM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1885, 472, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1886, 472, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1887, 472, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1888, 472, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi perolehan hibah PkM, diseminasi, HKI, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1889, 473, 4, 'Sangat Baik', 'Sangat baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1890, 473, 3, 'Baik', 'Baik: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1891, 473, 2, 'Cukup', 'Cukup: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1892, 473, 1, 'Kurang', 'Kurang: Kebijakan sistem tata kelola otonom, transparan, akuntabel (sarana & SDM profesional), terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1893, 474, 4, 'Sangat Baik', 'Sangat baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1894, 474, 3, 'Baik', 'Baik: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1895, 474, 2, 'Cukup', 'Cukup: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1896, 474, 1, 'Kurang', 'Kurang: Kebijakan audit mutu pemenuhan tupoksi tata kelola, tata pamong, sarana, SDM, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1897, 475, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1898, 475, 3, 'Baik', 'Baik: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1899, 475, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1900, 475, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan sistem tata kelola otonom/transparan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1901, 476, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1902, 476, 3, 'Baik', 'Baik: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1903, 476, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1904, 476, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1905, 477, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1906, 477, 3, 'Baik', 'Baik: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1907, 477, 2, 'Cukup', 'Cukup: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1908, 477, 1, 'Kurang', 'Kurang: Efektifitas evaluasi sistem tata kelola otonom/transparan/akuntabel, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1909, 478, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1910, 478, 3, 'Baik', 'Baik: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1911, 478, 2, 'Cukup', 'Cukup: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1912, 478, 1, 'Kurang', 'Kurang: Efektifitas evaluasi audit mutu pemenuhan tupoksi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1913, 479, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1914, 479, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1915, 479, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1916, 479, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi sistem tata kelola, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1917, 480, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1918, 480, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1919, 480, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1920, 480, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi audit mutu, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1921, 481, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1922, 481, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1923, 481, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1924, 481, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi sistem tata kelola, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1925, 482, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1926, 482, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1927, 482, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1928, 482, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi audit mutu, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1929, 483, 4, 'Sangat Baik', 'Sangat baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1930, 483, 3, 'Baik', 'Baik: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1931, 483, 2, 'Cukup', 'Cukup: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1932, 483, 1, 'Kurang', 'Kurang: Kebijakan tridarma PT (VMTS, renstra UPPS/PS), ciri khas keilmuan PS, pengakuan/apresiasi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1933, 484, 4, 'Sangat Baik', 'Sangat baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1934, 484, 3, 'Baik', 'Baik: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1935, 484, 2, 'Cukup', 'Cukup: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1936, 484, 1, 'Kurang', 'Kurang: Efektifitas pelaksanaan tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1937, 485, 4, 'Sangat Baik', 'Sangat baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1938, 485, 3, 'Baik', 'Baik: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1939, 485, 2, 'Cukup', 'Cukup: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1940, 485, 1, 'Kurang', 'Kurang: Efektifitas evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, pengakuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1941, 486, 4, 'Sangat Baik', 'Sangat baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1942, 486, 3, 'Baik', 'Baik: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1943, 486, 2, 'Cukup', 'Cukup: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1944, 486, 1, 'Kurang', 'Kurang: Efektifitas tindak lanjut evaluasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1945, 487, 4, 'Sangat Baik', 'Sangat baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1946, 487, 3, 'Baik', 'Baik: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1947, 487, 2, 'Cukup', 'Cukup: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1948, 487, 1, 'Kurang', 'Kurang: Efektifitas peningkatan/optimalisasi tridarma PT (VMTS, renstra), ciri khas keilmuan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1949, 488, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1950, 488, 3, 'Baik', 'Baik: Mata kuliah inti/khas program studi, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1951, 488, 2, 'Cukup', 'Cukup: Mata kuliah inti/khas program studi, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1952, 488, 1, 'Kurang', 'Kurang: Mata kuliah inti/khas program studi, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1953, 489, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1954, 489, 3, 'Baik', 'Baik: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1955, 489, 2, 'Cukup', 'Cukup: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1956, 489, 1, 'Kurang', 'Kurang: Mata kuliah domain spesifik dan lingkungan prodi infokom, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1957, 490, 4, 'Sangat Baik', 'Sangat baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1958, 490, 3, 'Baik', 'Baik: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1959, 490, 2, 'Cukup', 'Cukup: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1960, 490, 1, 'Kurang', 'Kurang: Mata kuliah terkait Matematika/metode atau Analisis Kuantitatif yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1961, 491, 4, 'Sangat Baik', 'Sangat baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1962, 491, 3, 'Baik', 'Baik: Proyek Utama (Capstone Project) yang relevan, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1963, 491, 2, 'Cukup', 'Cukup: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1964, 491, 1, 'Kurang', 'Kurang: Proyek Utama (Capstone Project) yang relevan, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL),
(1965, 492, 4, 'Sangat Baik', 'Sangat baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih, sangat lengkap, dan menunjukkan tren peningkatan 3 tahun terakhir.', NULL),
(1966, 492, 3, 'Baik', 'Baik: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi dengan bukti yang sahih dan lengkap.', NULL),
(1967, 492, 2, 'Cukup', 'Cukup: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian besar dengan bukti yang cukup lengkap.', NULL),
(1968, 492, 1, 'Kurang', 'Kurang: Pengembangan bidang Infokom yang digunakan di masyarakat, terpenuhi sebagian dengan bukti yang kurang lengkap.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lam_instrumen`
--

CREATE TABLE `lam_instrumen` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_lembaga_id` int(10) UNSIGNED NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `versi` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `driver_class` varchar(200) DEFAULT NULL,
  `total_bobot` decimal(8,2) NOT NULL DEFAULT 400.00,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_instrumen`
--

INSERT INTO `lam_instrumen` (`id`, `lam_lembaga_id`, `jenjang`, `versi`, `nama`, `tahun_terbit`, `driver_class`, `total_bobot`, `aktif`, `created_at`) VALUES
(2, 1, 'S1', '2.0', 'Instrumen Akreditasi Program Studi 2.0 Sarjana (Informatika & Komputer)', '2025', 'Modules\\Akreditasi\\Drivers\\LamInfokomS1\\Driver', 400.00, 1, '2026-05-22 06:24:17'),
(6, 1, 'D3', '2.0', 'Instrumen Akreditasi Program Studi 2.0 D3 (Informatika & Komputer)', '2025', 'Modules\\Akreditasi\\Drivers\\LamInfokomD3\\Driver', 400.00, 1, '2026-05-23 23:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `lam_kriteria`
--

CREATE TABLE `lam_kriteria` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_instrumen_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jenis` enum('kriteria','kondisi_eksternal','profil_upps','suplemen') NOT NULL DEFAULT 'kriteria',
  `bobot` decimal(6,2) NOT NULL DEFAULT 0.00,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_kriteria`
--

INSERT INTO `lam_kriteria` (`id`, `lam_instrumen_id`, `kode`, `nama`, `jenis`, `bobot`, `urutan`, `deskripsi`) VALUES
(10, 2, 'A', 'Kondisi Eksternal', 'kondisi_eksternal', 4.00, 1, NULL),
(11, 2, 'B', 'Profil Unit Pengelola Program Studi', 'profil_upps', 4.00, 2, NULL),
(12, 2, 'C1', 'Budaya Mutu', 'kriteria', 40.00, 3, NULL),
(13, 2, 'C2', 'Relevansi Pendidikan', 'kriteria', 120.00, 4, NULL),
(14, 2, 'C3', 'Relevansi Penelitian', 'kriteria', 72.00, 5, NULL),
(15, 2, 'C4', 'Relevansi PkM', 'kriteria', 60.00, 6, NULL),
(16, 2, 'C5', 'Akuntabilitas', 'kriteria', 40.00, 7, NULL),
(17, 2, 'C6', 'Diferensiasi Misi', 'kriteria', 40.00, 8, NULL),
(18, 2, 'D', 'Suplemen Program Studi', 'suplemen', 20.00, 9, NULL),
(46, 6, 'A', 'Kondisi Eksternal', 'kondisi_eksternal', 4.00, 1, NULL),
(47, 6, 'B', 'Profil Unit Pengelola Program Studi', 'profil_upps', 4.00, 2, NULL),
(48, 6, 'C1', 'Budaya Mutu', 'kriteria', 40.00, 3, NULL),
(49, 6, 'C2', 'Relevansi Pendidikan', 'kriteria', 120.00, 4, NULL),
(50, 6, 'C3', 'Relevansi Penelitian', 'kriteria', 32.00, 5, NULL),
(51, 6, 'C4', 'Relevansi PkM', 'kriteria', 100.00, 6, NULL),
(52, 6, 'C5', 'Akuntabilitas', 'kriteria', 40.00, 7, NULL),
(53, 6, 'C6', 'Diferensiasi Misi', 'kriteria', 40.00, 8, NULL),
(54, 6, 'D', 'Suplemen Program Studi', 'suplemen', 20.00, 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lam_lembaga`
--

CREATE TABLE `lam_lembaga` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `website` varchar(150) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_lembaga`
--

INSERT INTO `lam_lembaga` (`id`, `kode`, `nama`, `website`, `aktif`) VALUES
(1, 'LAM-INFOKOM', 'Lembaga Akreditasi Mandiri Informatika dan Komputer', 'https://laminfokom.or.id', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lam_tabel_lkps`
--

CREATE TABLE `lam_tabel_lkps` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_instrumen_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(30) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `kriteria_kode` varchar(20) DEFAULT NULL,
  `schema_json` longtext DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_tabel_lkps`
--

INSERT INTO `lam_tabel_lkps` (`id`, `lam_instrumen_id`, `kode`, `judul`, `kriteria_kode`, `schema_json`, `urutan`) VALUES
(32, 2, '1.A.1', 'Pimpinan dan Tupoksi UPPS dan PS', 'C1', NULL, 1),
(33, 2, '1.A.2', 'Sumber Pendanaan UPPS/PS', 'C1', NULL, 2),
(34, 2, '1.A.3', 'Penggunaan Dana UPPS/PS', 'C1', NULL, 3),
(35, 2, '1.A.4', 'Rata-rata Beban DTPR per Semester (EWMP)', 'C1', NULL, 4),
(36, 2, '1.A.5', 'Kualifikasi Tenaga Kependidikan', 'C1', NULL, 5),
(37, 2, '1.B', 'Unit SPMI dan SDM', 'C1', NULL, 6),
(38, 2, '2.A.1', 'Data Mahasiswa', 'C2', NULL, 7),
(39, 2, '2.A.2', 'Keragaman Asal Mahasiswa', 'C2', NULL, 8),
(40, 2, '2.A.3', 'Kondisi Jumlah Mahasiswa', 'C2', NULL, 9),
(41, 2, '2.B.1', 'Isi Pembelajaran (Kurikulum)', 'C2', NULL, 10),
(42, 2, '2.B.2', 'Pemetaan CPL dengan Profil Lulusan', 'C2', NULL, 11),
(43, 2, '2.B.3', 'Peta Pemenuhan CPL', 'C2', NULL, 12),
(44, 2, '2.B.4', 'Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali', 'C2', NULL, 13),
(45, 2, '2.B.5', 'Kesesuaian Bidang Kerja Lulusan', 'C2', NULL, 14),
(46, 2, '2.B.6', 'Kepuasan Pengguna Lulusan', 'C2', NULL, 15),
(47, 2, '2.C', 'Fleksibilitas dalam Proses Pembelajaran', 'C2', NULL, 16),
(48, 2, '2.D', 'Rekognisi dan Apresiasi Kompetensi Lulusan', 'C2', NULL, 17),
(49, 2, '3.A.1', 'Sarana dan Prasarana Penelitian', 'C3', NULL, 18),
(50, 2, '3.A.2', 'Penelitian DTPR, Hibah dan Pembiayaan Penelitian', 'C3', NULL, 19),
(51, 2, '3.A.3', 'Pengembangan DTPR di Bidang Penelitian', 'C3', NULL, 20),
(52, 2, '3.C.1', 'Kerjasama Penelitian', 'C3', NULL, 21),
(53, 2, '3.C.2', 'Publikasi Penelitian', 'C3', NULL, 22),
(54, 2, '3.C.3', 'Perolehan HKI (Granted) Penelitian', 'C3', NULL, 23),
(55, 2, '4.A.1', 'Sarana dan Prasarana PkM', 'C4', NULL, 24),
(56, 2, '4.A.2', 'PkM DTPR, Hibah dan Pembiayaan PkM', 'C4', NULL, 25),
(57, 2, '4.C.1', 'Kerjasama PkM', 'C4', NULL, 26),
(58, 2, '4.C.2', 'Diseminasi Hasil PkM', 'C4', NULL, 27),
(59, 2, '4.C.3', 'Perolehan HKI PkM', 'C4', NULL, 28),
(60, 2, '5.1', 'Sistem Tata Kelola', 'C5', NULL, 29),
(61, 2, '5.2', 'Sarana dan Prasarana Pendidikan', 'C5', NULL, 30),
(62, 2, '6', 'Kesesuaian Visi dan Misi', 'C6', NULL, 31),
(156, 6, '1.A.1', 'Pimpinan dan Tupoksi UPPS dan PS', 'C1', NULL, 1),
(157, 6, '1.A.2', 'Sumber Pendanaan UPPS/PS', 'C1', NULL, 2),
(158, 6, '1.A.3', 'Penggunaan Dana UPPS/PS', 'C1', NULL, 3),
(159, 6, '1.A.4', 'Rata-rata Beban DTPR per Semester (EWMP)', 'C1', NULL, 4),
(160, 6, '1.A.5', 'Kualifikasi Tenaga Kependidikan', 'C1', NULL, 5),
(161, 6, '1.B', 'Unit SPMI dan SDM', 'C1', NULL, 6),
(162, 6, '2.A.1', 'Data Mahasiswa', 'C2', NULL, 7),
(163, 6, '2.A.2', 'Keragaman Asal Mahasiswa', 'C2', NULL, 8),
(164, 6, '2.A.3', 'Kondisi Jumlah Mahasiswa', 'C2', NULL, 9),
(165, 6, '2.B.1', 'Isi Pembelajaran (Kurikulum)', 'C2', NULL, 10),
(166, 6, '2.B.2', 'Pemetaan CPL dengan Profil Lulusan', 'C2', NULL, 11),
(167, 6, '2.B.3', 'Peta Pemenuhan CPL', 'C2', NULL, 12),
(168, 6, '2.B.4', 'Rata-rata Masa Tunggu Lulusan untuk Bekerja Pertama Kali', 'C2', NULL, 13),
(169, 6, '2.B.5', 'Kesesuaian Bidang Kerja Lulusan', 'C2', NULL, 14),
(170, 6, '2.B.6', 'Kepuasan Pengguna Lulusan', 'C2', NULL, 15),
(171, 6, '2.C', 'Fleksibilitas dalam Proses Pembelajaran', 'C2', NULL, 16),
(172, 6, '2.D', 'Rekognisi dan Apresiasi Kompetensi Lulusan', 'C2', NULL, 17),
(173, 6, '3.A.1', 'Sarana dan Prasarana Penelitian', 'C3', NULL, 18),
(174, 6, '3.A.2', 'Penelitian DTPR, Hibah dan Pembiayaan Penelitian', 'C3', NULL, 19),
(175, 6, '3.A.3', 'Pengembangan DTPR di Bidang Penelitian', 'C3', NULL, 20),
(176, 6, '3.C.1', 'Kerjasama Penelitian', 'C3', NULL, 21),
(177, 6, '3.C.2', 'Publikasi Penelitian', 'C3', NULL, 22),
(178, 6, '3.C.3', 'Perolehan HKI (Granted) Penelitian', 'C3', NULL, 23),
(179, 6, '4.A.1', 'Sarana dan Prasarana PkM', 'C4', NULL, 24),
(180, 6, '4.A.2', 'PkM DTPR, Hibah dan Pembiayaan PkM', 'C4', NULL, 25),
(181, 6, '4.C.1', 'Kerjasama PkM', 'C4', NULL, 26),
(182, 6, '4.C.2', 'Diseminasi Hasil PkM', 'C4', NULL, 27),
(183, 6, '4.C.3', 'Perolehan HKI PkM', 'C4', NULL, 28),
(184, 6, '5.1', 'Sistem Tata Kelola', 'C5', NULL, 29),
(185, 6, '5.2', 'Sarana dan Prasarana Pendidikan', 'C5', NULL, 30),
(186, 6, '6', 'Kesesuaian Visi dan Misi', 'C6', NULL, 31);

-- --------------------------------------------------------

--
-- Table structure for table `lam_template_led`
--

CREATE TABLE `lam_template_led` (
  `id` int(10) UNSIGNED NOT NULL,
  `lam_instrumen_id` int(10) UNSIGNED NOT NULL,
  `lam_kriteria_id` int(10) UNSIGNED DEFAULT NULL,
  `bagian` varchar(50) NOT NULL,
  `sub_bagian` varchar(50) DEFAULT NULL,
  `judul` varchar(250) NOT NULL,
  `template_isi` longtext DEFAULT NULL,
  `prompt_groq` longtext DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lam_template_led`
--

INSERT INTO `lam_template_led` (`id`, `lam_instrumen_id`, `lam_kriteria_id`, `bagian`, `sub_bagian`, `judul`, `template_isi`, `prompt_groq`, `urutan`) VALUES
(33, 2, NULL, 'pendahuluan', NULL, 'Pendahuluan LED', '', 'Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi sarjana dalam bahasa Indonesia formal akademik.', 1),
(34, 2, NULL, 'penutup', NULL, 'Penutup LED', '', 'Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi sarjana dalam bahasa Indonesia formal akademik.', 2),
(35, 2, 12, 'kriteria', 'penetapan', 'Penetapan - C1', '', 'Tuliskan narasi tahap penetapan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 3),
(36, 2, 12, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C1', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 4),
(37, 2, 12, 'kriteria', 'evaluasi', 'Evaluasi - C1', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 5),
(38, 2, 12, 'kriteria', 'pengendalian', 'Pengendalian - C1', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 6),
(39, 2, 12, 'kriteria', 'peningkatan', 'Peningkatan - C1', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 7),
(40, 2, 13, 'kriteria', 'penetapan', 'Penetapan - C2', '', 'Tuliskan narasi tahap penetapan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 8),
(41, 2, 13, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C2', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 9),
(42, 2, 13, 'kriteria', 'evaluasi', 'Evaluasi - C2', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 10),
(43, 2, 13, 'kriteria', 'pengendalian', 'Pengendalian - C2', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 11),
(44, 2, 13, 'kriteria', 'peningkatan', 'Peningkatan - C2', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 12),
(45, 2, 14, 'kriteria', 'penetapan', 'Penetapan - C3', '', 'Tuliskan narasi tahap penetapan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 13),
(46, 2, 14, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C3', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 14),
(47, 2, 14, 'kriteria', 'evaluasi', 'Evaluasi - C3', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 15),
(48, 2, 14, 'kriteria', 'pengendalian', 'Pengendalian - C3', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 16),
(49, 2, 14, 'kriteria', 'peningkatan', 'Peningkatan - C3', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 17),
(50, 2, 15, 'kriteria', 'penetapan', 'Penetapan - C4', '', 'Tuliskan narasi tahap penetapan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 18),
(51, 2, 15, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C4', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 19),
(52, 2, 15, 'kriteria', 'evaluasi', 'Evaluasi - C4', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 20),
(53, 2, 15, 'kriteria', 'pengendalian', 'Pengendalian - C4', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 21),
(54, 2, 15, 'kriteria', 'peningkatan', 'Peningkatan - C4', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 22),
(55, 2, 16, 'kriteria', 'penetapan', 'Penetapan - C5', '', 'Tuliskan narasi tahap penetapan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 23),
(56, 2, 16, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C5', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 24),
(57, 2, 16, 'kriteria', 'evaluasi', 'Evaluasi - C5', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 25),
(58, 2, 16, 'kriteria', 'pengendalian', 'Pengendalian - C5', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 26),
(59, 2, 16, 'kriteria', 'peningkatan', 'Peningkatan - C5', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 27),
(60, 2, 17, 'kriteria', 'penetapan', 'Penetapan - C6', '', 'Tuliskan narasi tahap penetapan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 28),
(61, 2, 17, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C6', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 29),
(62, 2, 17, 'kriteria', 'evaluasi', 'Evaluasi - C6', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 30),
(63, 2, 17, 'kriteria', 'pengendalian', 'Pengendalian - C6', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 31),
(64, 2, 17, 'kriteria', 'peningkatan', 'Peningkatan - C6', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 Sarjana berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 32),
(161, 6, NULL, 'pendahuluan', NULL, 'Pendahuluan LED', '', 'Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi diploma tiga dalam bahasa Indonesia formal akademik.', 1),
(162, 6, NULL, 'penutup', NULL, 'Penutup LED', '', 'Tuliskan bagian \"Pendahuluan\" dari Laporan Evaluasi Diri (LED) program studi diploma tiga dalam bahasa Indonesia formal akademik.', 2),
(163, 6, 48, 'kriteria', 'penetapan', 'Penetapan - C1', '', 'Tuliskan narasi tahap penetapan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 3),
(164, 6, 48, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C1', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 4),
(165, 6, 48, 'kriteria', 'evaluasi', 'Evaluasi - C1', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 5),
(166, 6, 48, 'kriteria', 'pengendalian', 'Pengendalian - C1', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 6),
(167, 6, 48, 'kriteria', 'peningkatan', 'Peningkatan - C1', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C1 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 7),
(168, 6, 49, 'kriteria', 'penetapan', 'Penetapan - C2', '', 'Tuliskan narasi tahap penetapan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 8),
(169, 6, 49, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C2', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 9),
(170, 6, 49, 'kriteria', 'evaluasi', 'Evaluasi - C2', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 10),
(171, 6, 49, 'kriteria', 'pengendalian', 'Pengendalian - C2', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 11),
(172, 6, 49, 'kriteria', 'peningkatan', 'Peningkatan - C2', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C2 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 12),
(173, 6, 50, 'kriteria', 'penetapan', 'Penetapan - C3', '', 'Tuliskan narasi tahap penetapan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 13),
(174, 6, 50, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C3', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 14),
(175, 6, 50, 'kriteria', 'evaluasi', 'Evaluasi - C3', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 15),
(176, 6, 50, 'kriteria', 'pengendalian', 'Pengendalian - C3', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 16),
(177, 6, 50, 'kriteria', 'peningkatan', 'Peningkatan - C3', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C3 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 17),
(178, 6, 51, 'kriteria', 'penetapan', 'Penetapan - C4', '', 'Tuliskan narasi tahap penetapan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 18),
(179, 6, 51, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C4', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 19),
(180, 6, 51, 'kriteria', 'evaluasi', 'Evaluasi - C4', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 20),
(181, 6, 51, 'kriteria', 'pengendalian', 'Pengendalian - C4', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 21),
(182, 6, 51, 'kriteria', 'peningkatan', 'Peningkatan - C4', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C4 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 22),
(183, 6, 52, 'kriteria', 'penetapan', 'Penetapan - C5', '', 'Tuliskan narasi tahap penetapan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 23),
(184, 6, 52, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C5', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 24),
(185, 6, 52, 'kriteria', 'evaluasi', 'Evaluasi - C5', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 25),
(186, 6, 52, 'kriteria', 'pengendalian', 'Pengendalian - C5', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 26),
(187, 6, 52, 'kriteria', 'peningkatan', 'Peningkatan - C5', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C5 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 27),
(188, 6, 53, 'kriteria', 'penetapan', 'Penetapan - C6', '', 'Tuliskan narasi tahap penetapan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 28),
(189, 6, 53, 'kriteria', 'pelaksanaan', 'Pelaksanaan - C6', '', 'Tuliskan narasi tahap pelaksanaan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 29),
(190, 6, 53, 'kriteria', 'evaluasi', 'Evaluasi - C6', '', 'Tuliskan narasi tahap evaluasi untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 30),
(191, 6, 53, 'kriteria', 'pengendalian', 'Pengendalian - C6', '', 'Tuliskan narasi tahap pengendalian untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 31),
(192, 6, 53, 'kriteria', 'peningkatan', 'Peningkatan - C6', '', 'Tuliskan narasi tahap peningkatan untuk kriteria C6 pada Laporan Evaluasi Diri (LED) akreditasi LAM INFOKOM 2.0 D3 berdasarkan data prodi yang disediakan. Gaya: formal akademik, faktual, 250-400 kata, sertakan tren 3 tahun (TS-2, TS-1, TS) bila ada, dan rujukan ke tabel LKPS terkait.', 32);

-- --------------------------------------------------------

--
-- Table structure for table `led_narasi`
--

CREATE TABLE `led_narasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `lam_template_led_id` int(10) UNSIGNED NOT NULL,
  `isi` longtext DEFAULT NULL,
  `generated_by_ai` tinyint(1) NOT NULL DEFAULT 0,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
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
  `tahun_masuk_id` int(10) UNSIGNED DEFAULT NULL,
  `jalur_masuk` varchar(50) DEFAULT NULL,
  `status` enum('aktif','cuti','do','keluar','lulus') NOT NULL DEFAULT 'aktif',
  `tgl_lulus` date DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `masa_studi_bulan` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `prodi_id`, `nim`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `email`, `no_hp`, `kota_asal`, `provinsi_asal`, `negara_asal`, `tahun_masuk_id`, `jalur_masuk`, `status`, `tgl_lulus`, `ipk`, `masa_studi_bulan`, `created_at`, `updated_at`) VALUES
(1, 7, '04403321370013', 'ABDURAHMAN NOVAL', 'L', 'BANDUNG', '2001-12-06', 'abdurahman0612@gmail.com', '082116005635', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(2, 7, '04403321370007', 'ACHMAD FAUJY MULYANA PUTRA', 'L', 'CIAMIS', '2001-05-17', 'aji052001@gmail.com', '085862545303', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(3, 7, '04403321370030', 'ADHITYA NUGRAHA SUWANDA', 'L', 'BANDUNG', '2000-11-22', 'adhityan210@gmail.com', '082258693010', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(4, 7, '04403321370043', 'AKIK JAINUL HAK', 'L', 'TASIKMALAYA', '2001-06-11', 'akikjainulhak11@gmail.com', '089661089955', 'Kab. Tasikmalaya', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(5, 7, '04403321370048', 'ALDI MUHYI SUBAGJA SUHERMAN', 'L', 'BANDUNG', '1994-06-13', 'aljack664@gmail.com', '082116370974', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(6, 7, '04403321370014', 'ALFAT GUFRON SIREGAR', 'L', 'MEDAN', '2001-02-18', 'alfatgufrons@gmail.com', '08961699736', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(7, 7, '04403321370047', 'ALI MUHAMMAD AZINUDIN', 'L', 'BANDUNG', '2001-07-16', 'zziaa.167@gmail.com', '085158881607', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(8, 7, '04403321370037', 'ANGGI YANA SABAN', 'L', 'BANDUNG', '2001-11-04', 'yana.syaban04@gmail.com', '089656264614', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(9, 7, '04403321370040', 'ARIF SAPUTRA', 'L', 'BANDUNG', '2003-02-22', 'nevssaputra@gmail.com', '089530178351', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(10, 7, '04403321370034', 'AYU CAHYA FERARIANI', 'P', 'BANDUNG', '2001-09-29', 'ayucahyaferariani029@gmail.com', '088229010860', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(11, 7, '04403321370029', 'AZIS ABDUL PATAH SAEFUDIN', 'L', 'BANDUNG', '2002-05-20', 'azis.abdulfs@gmail.com', '08986932101', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(12, 7, '04403321370008', 'BIANDA SALFABILA', 'P', 'BANDUNG', '2003-06-06', 'bianda@digitechuniversity.ac.id', '081573521689', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(13, 7, '04403321370033', 'DEFANI OKTAVIANI', 'P', 'BANDUNG', '2002-10-31', 'defanioktaviani127@gmail.com', '088213968058', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(14, 7, '04403321370018', 'DZAKWAN ABDUL HAKIM', 'L', 'SUBANG', '2003-04-24', 'qirisilethii12@gmail.com', '08818247757', 'Kab. Kuningan', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(15, 7, '04403321370005', 'FADLAH AULIA', 'P', 'BANDUNG', '2003-09-16', 'auliafadlah706@gmail.com', '083829849009', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(16, 7, '04403321370009', 'FAUZAN GALIB', 'L', 'MAJALENGKA', '2001-10-10', 'ozangaib@gmail.com', '083109627926', 'Kab. Sumedang', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(17, 7, '04403321370002', 'FERDY FATURAHMAN', 'L', 'BANDUNG', '2002-12-11', 'ferdyfaturahmanbisnis@gmail.com', '088218678861', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(18, 7, '04403321370045', 'FIRMAN FATURRAHMAN', 'L', 'TASIKMALAYA', '2002-08-14', 'firmanfaturrahman17@gmail.com', '0895703037470', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(19, 7, '04403321370036', 'FIRMANSYAH', 'L', 'BANDUNG', '2000-02-11', 'fs5712973@gmail.com', '082319185090', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(20, 7, '04403321370052', 'GALUH HERLINA HARJANEGARA', 'P', 'BANDUNG', '2003-06-28', 'galuhherlina709@gmail.com', '087711711049', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(21, 7, '04403321370039', 'GITA SALSA SYAHARANI', 'P', 'BANDUNG', '2002-12-10', 'gitasalsass@gmail.com', '085760679845', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(22, 7, '04403321370041', 'HINDUN QOTRU SA\'ADAH', 'P', 'BANDUNG', '2004-01-09', 'hindunqs@gmail.com', '083801414852', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(23, 7, '04403321370011', 'ILHAM NURZAMAN', 'L', 'BANDUNG', '2002-03-15', 'nurzaman15203@gmail.com', '085559023470', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(24, 7, '04403321370006', 'ILMA AMALIA JAZA', 'P', 'BANDUNG', '2001-04-06', 'amaliajazailma@gmail.com', '087824967606', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(25, 7, '04403321370053', 'JERRY OLIMPIA BARCELONA', 'L', 'CIANJUR', '1992-08-03', 'jerryolympia90@gmail.com', '087848489898', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(26, 7, '04403321370027', 'MAHARDIKA AGUS SETIONO', 'L', 'PURBALINGGA', '2003-08-10', 'yantipurbalingga88@gmail.com', '081315280546', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(27, 7, '04403321370010', 'MOHAMAD FARHAN NUR DZULFIKAR', 'L', 'BANDUNG', '2002-11-08', 'mohammadfarhannurdzulfikar@gmail.com', '088971017126', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(28, 7, '04403321370003', 'MUHAMMAD IKSAN MAULANA', 'L', 'BANDUNG', '2002-05-07', 'mikhsanmaulana2017@gmail.com', '083824192925', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(29, 7, '04403321370032', 'MUHAMMAD MALIK RIZKY RAMDHANI', 'L', 'TASIKMALAYA', '2002-11-25', 'ramdhanirizky872@gmail.com', '081394776915', 'Kab. Sumedang', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(30, 7, '04403321370015', 'MUHAMMAD RIFKY PRATAMA', 'L', 'BANDUNG', '2003-01-05', 'rifkyprtm1955@gmail.com', '088220667952', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(31, 7, '04403321370021', 'MUHAMMAD RIZAL KHOIRUL INSAN', 'L', 'BANDUNG', '2003-03-09', 'muhammadrizalkhoirulinsan@gmail.com', '089509102265', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(32, 7, '04403321370038', 'NISA NURUL QOYIMAH', 'P', 'BANDUNG', '2003-01-26', 'nisaqrh@gmail.com', '082115919361', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(33, 7, '04403321370017', 'REZA WIDJAYA', 'L', 'BANDUNG', '2001-07-21', 'rezza.ajjah35@gmail.com', '0895346662274', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(34, 7, '04403321370022', 'REZI FAUZI', 'L', 'BANDUNG', '2001-01-01', 'rezikece12@gmail.com', '0895361194304', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(35, 7, '04403321370026', 'RIFKI RAKA FADILA', 'L', 'BANDUNG', '2003-10-06', 'rifkiraka40@gmail.com', '081222885183', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(36, 7, '04403321370023', 'RIFKY FACHREGI', 'L', 'BANDUNG', '2002-12-26', 'rifkyfachregi45@gmail.com', '085156607812', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(37, 7, '04403321370051', 'RIVAL NURHUDAN', 'L', 'BANDUNG', '2002-06-04', 'rivalnurhudan@gmail.com', '085722812291', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(38, 7, '04403321370020', 'RIZQI FAUZAN SYADIDA', 'L', 'BANDUNG', '2003-03-25', 'rizqifauzan26@gmail.com', '08889390413', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(39, 7, '04403321370031', 'SITI FITRIYANI', 'P', 'BANDUNG', '2002-11-13', 'fitriyaniiisiti@gmail.com', '083801485162', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(40, 7, '04403321370035', 'UJANG SARIFFUDIN', 'L', 'BANDUNG', '2001-08-06', 'sariffudinujang@gmail.com', '0881022631219', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(41, 7, '04403321370024', 'WIDYA FAUZI AMELIA', 'P', 'BANDUNG', '2002-04-10', 'ameliawidyafauzi@gmail.com', '081461103145', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(42, 7, '04403321370001', 'ZAKFA ISNAN', 'L', 'TASIKMALAYA', '2003-02-10', 'zackisnan@gmail.com', '083876942904', 'Kab. Tasikmalaya', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:19:14', '2026-05-22 07:26:33'),
(43, 7, '04403321370028', 'AGUNG GUNAWAN', 'L', 'BANDUNG', '2003-02-15', 'goseen1502@gmail.com', '089655260715', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(44, 7, '04403321370050', 'LUKMANSYAH ADHITYA', 'L', 'BANDUNG', '2001-11-14', 'lukmansyahadhitya@gmail.com', '0895414763187', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(45, 7, '04403321370042', 'MUHAMMAD DARUL FIKRI', 'L', 'BANDUNG', '2002-10-11', 'darulfikri111234@gmail.com', '081933010338', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(46, 7, '04403321370016', 'NATASHA NURUL SALSABILA', 'P', 'BANDUNG', '2003-05-01', 'natashanurulsalsabila@gmail.com', '081910211795', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(47, 7, '04403321370044', 'SUHENDAR', 'L', 'GARUT', '2002-03-05', 'nirmanfakot@gmail.com', '081223913309', 'Kab. Garut', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(48, 7, '04403321370019', 'SYIFA AMALIA SYARINA PUTRI', 'P', 'BANDUNG', '2001-04-03', 'syifaasp3@gmail.com', '085795301845', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(49, 7, '04403321370049', 'ZALSA DEVA ZULFANI', 'P', 'CIANJUR', '2002-08-06', 'zalsabilaa06@gmail.com', '083825442837', 'Kab. Cianjur', 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:25:04', '2026-05-22 07:26:33'),
(50, 7, '04403321370004', 'AAS SRIYANI', 'P', 'SADAI', '2002-11-26', 'puratayo@gmail.com', '085938635383', NULL, 'Jawa Barat', 'Indonesia', 1, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:26:33', '2026-05-22 07:26:33'),
(51, 7, '04403322380021', 'Abellia Oke Mardiana', 'P', 'Bandung', '2003-10-10', 'abelliaokemardiana981@gmail.com', '08387732440', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(52, 7, '04403322380014', 'Adinda Salsabila', 'P', 'Bandung', '2004-05-22', 'adindasalsabila004@gmail.com', '081320112138', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(53, 7, '04403322380002', 'Agung Wiguna', 'L', 'Bandung', '2004-09-03', 'siqmalik46@gmail.com', '083820923821', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(54, 7, '04403322380003', 'Alip Barokah', 'L', 'Bandung', '2004-07-26', 'alifbarokah951@gmail.com', '083132917394', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(55, 7, '04403322380010', 'Andri Maulana Yusuf', 'L', 'Bandung', '2002-06-09', NULL, '08812212697', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(56, 7, '04403322380020', 'Audia', 'P', 'Bandung', '2002-11-10', 'audiaaudia117@gmail.com', '085794918995', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(57, 7, '04403322380019', 'Chandra Ramadhan', 'L', 'Bandung', '2003-03-11', 'ramadhancandra736@gmail.com', '085728151870', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(58, 7, '04403322380018', 'Daffa Andika Pasha', 'L', 'Bandung', '2004-04-27', 'andikadaffa78@gmail.com', '0895323031328', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(59, 7, '04403322380012', 'Fathan Ali Satriani', 'L', 'Bandung', '2003-07-25', 'fthnrzki01@gmail.com', '083154664476', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(60, 7, '04403322380025', 'Fenti Febrian A.', 'P', 'Bandung', '2005-10-02', NULL, '083103293755', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(61, 7, '04403322380024', 'Hendra Lesmana', 'L', 'Bandung', '2001-01-23', 'lesmnhendra961@gmail.com', '088218106435', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(62, 7, '04403322380017', 'Ilman Nur Rohman Yusuf', 'L', 'Bandung', '2004-01-19', 'ilmannurohman83@gmail.com', '089517650241', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(63, 7, '04403322380004', 'Indra Maulana', 'L', 'Bandung', '2003-05-16', 'indramaulanaa003@gmail.com', '083822120629', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(64, 7, '04403322380015', 'M. Zidan Ramadan', 'L', 'Bandung', '2004-11-14', 'mzidan9432@gmail.com', '085156782779', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:08'),
(65, 7, '04403322380001', 'Mohammad Rendi Valentino', 'L', 'Bandung', '2004-02-14', 'mohamrendivalentino14@gmail.com', '08977879055', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(66, 7, '04403322380029', 'Mohammad Rezha Suparman', 'L', 'Bandung', '2003-10-14', NULL, '089652719447', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(67, 7, '04403322380031', 'Muhamad Afif Maulia Ilham', 'L', 'Tangerang', '2004-09-21', 'afifmuh575@gmail.com', '08120297598', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(68, 7, '04403322380008', 'Muhamad Shafar Al Farisi', 'L', 'Bandung', '2000-05-11', 'muhamadshafaralparizi@gmail.com', '085863227407', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(69, 7, '04403322380007', 'Muhammad Faizal Pratama', 'L', 'Bandung', '2003-05-15', 'faizalmozart@gmail.com', '085798457191', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(70, 7, '04403322380030', 'Nabila Rahimi', 'P', 'Batam', '2004-11-06', NULL, '082114591920', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(71, 7, '04403322380009', 'Novitasari Ramadhani Lubis', 'P', 'Medan', '2004-11-07', 'novitasarirl0711@gmail.com', '089662147823', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(72, 7, '04403322380022', 'Reki Kurnia Permana', 'L', 'Bandung', '2004-02-12', 'rekipermana82@gmail.com', '0881022114375', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(73, 7, '04403322380011', 'Rifki Fajar Nugraha', 'L', 'Bandung', '2002-08-26', 'rfnkanir1@gmail.com', '0882002084994', 'Kab. Bogor', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(74, 7, '04403322380026', 'Sahrul Rahmadan', 'L', 'Bandung', '2003-10-30', 'sahrulude2@gmail.com', '083198981906', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(75, 7, '04403322380016', 'Seanno Shaggy Irawansyah', 'L', 'Bandung', '2003-11-09', 'shaggyseanno@gmail.com', '082117567075', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(76, 7, '04403322380027', 'Syifa Huzaifah', 'P', 'Bandung', '2002-10-29', NULL, '0895331379033', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'keluar', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(77, 7, '04403322380005', 'Thomas Lazuardi Muhammad', 'L', 'Bandung', '2002-05-19', 'thomaslm1905@gmail.com', '0895360567439', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(78, 7, '04403322380023', 'Tsabat Zhilalul Huda', 'L', 'Bandung', '2002-12-18', 'zhilalulth@gmail.com', '0895360975538', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(79, 7, '04403322380006', 'Yaris Fajar Baihaqi', 'L', 'Bandung', '2003-10-29', 'yarisfajar203@gmail.com', '085159997025', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 2, 'Mandiri', 'lulus', NULL, NULL, NULL, '2026-05-22 07:48:36', '2026-05-22 07:49:09'),
(80, 7, '20423005', 'Adhfar Muftiana Miftah', 'L', 'Bandung', '2005-03-11', 'adhfar20423005@digitechuniversity.ac.id', '089629961108', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(81, 7, '20423004', 'Bayu fajri noorjamil', 'L', 'Bandung', '2003-01-19', 'bayu20423004@digitechuniversity.ac.id', '081572035281', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(82, 7, '20423001', 'Chica Anggraeni', 'P', 'Bandung', '2005-06-07', 'chica20423001@digitechuniversity.ac.id', '081223008115', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(83, 7, '20423006', 'Dimas Faturachman', 'L', 'Bandung', NULL, 'dimas20423006@digitechuniversity.ac.id', '088222165490', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(84, 7, '20423007', 'Fitri Rahmawati', 'P', 'Bandung', '2004-12-05', 'fitrahrw@gmail.com', '08812265141', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(85, 7, '20423008', 'Gilan Ma’Danil Khowwat', 'L', 'Bandung', '2005-07-20', 'gilan20423008@digitechuniversity.ac.id', '085640815878', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(86, 7, '20423015', 'Hilmy Ramadhan Permana', 'L', 'Bandung', '2005-09-10', 'hilmy20423015@digitechuniversity.ac.id', '089603056421', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(87, 7, '20423002', 'Liani Siti Lutfiah', 'P', 'Bandung', '2004-04-19', 'liani20423002@digitechuniversity.ac.id', '083818352500', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(88, 7, '20423003', 'Maulana Mulya Oktavian', 'L', 'Bandung', '2002-10-12', 'maulana20423003@digitechuniversity.ac.id', '087723808003', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(89, 7, '20423009', 'Muhammad Fajri Ashofwani', 'L', 'Bandung', '2003-10-23', 'muhammad20423009@digitechuniversity.ac.id', '083822981314', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(90, 7, '20423010', 'Namia Resca Kirana', 'P', 'Bandung', '2003-06-07', 'namia20423010@digitechuniversity.ac.id', '083838695431', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(91, 7, '20423014', 'Nanda Alia Destiara', 'P', 'Bandung', '2004-06-12', 'nanda20423014@digitechuniversity.ac.id', '083823927647', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(92, 7, '20423016', 'Neng Elsa Destianti', 'P', 'Bandung', '2005-12-24', 'neng20423016@digitechuniversity.ac.id', '083125094835', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(93, 7, '20423013', 'Nita Andriyani', 'P', 'Bandung', '2004-02-28', 'nita20423013@digitechuniversity.ac.id', '082385078700', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(94, 7, '20423017', 'Rafi Fadhal Ash Shidiq', 'L', 'Cimahi', '2005-01-29', 'rafi20423017@digitechuniversity.ac.id', '08986060838', 'Kota Cimahi', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(95, 7, '20423011', 'Saskia Aulia Rachman', 'P', 'Bandung', '2005-06-23', 'saskia20423011@digitechuniversity.ac.id', '081214649407', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(96, 7, '20423012', 'Wida Waryani', 'P', 'Bandung', '2003-04-19', 'wida20423012@digitechuniversity.ac.id', '085624934342', 'Kab. Bandung Barat', 'Jawa Barat', 'Indonesia', 3, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:53:08', '2026-05-22 07:53:08'),
(97, 7, '20424013', 'ANGGA GILANG PERMANA', 'L', 'Bandung', '2006-01-08', 'angga20424013@digitechuniversity.ac.id', '083169358490', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(98, 7, '20424011', 'ANISA ZAHWA', 'P', 'Bandung', '2005-05-05', 'anisa20424011@digitechuniversity.ac.id', '089504742085', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(99, 7, '20424014', 'Asri Hartini', 'P', 'Bandung', '2006-04-08', 'asri20424014@digitechuniversity.ac.id', '083816063905', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(100, 7, '20424001', 'BELA SITI ALAWIYAH', 'P', 'Bandung', '2004-12-11', 'bela20424001@digitechuniversity.ac.id', '83875754259', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(101, 7, '20424008', 'CINTIANI NURAENI', 'P', 'Bandung', '2004-06-08', 'cintiani20424008@digitechuniversity.ac.id', '089656102816', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(102, 7, '20424015', 'FUTURI ASANDA HAWAMIDA', 'P', 'Bandung', '2004-10-11', 'futuri20424015@digitechuniversity.ac.id', '0895420633222', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(103, 7, '20424003', 'HARIST AZIS PERMANA', 'L', 'Bandung', '2003-12-28', 'harist20424003@digitechuniversity.ac.id', '895321762861', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(104, 7, '20424012', 'JIHAD FATHIR NASHIF ALDAME', 'L', 'Cimahi', '2005-06-15', 'jihad20424012@digitechuniversity.ac.id', '082219580699', 'Kota Cimahi', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(105, 7, '20424007', 'MUFTI ALIANSYAH', 'L', 'Bandung', '2005-04-10', 'mufti20424007@digitechuniversity.ac.id', '0895324224439', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(106, 7, '20424002', 'MUHAMMAD KHALI FAUZY', 'L', 'Bandung', '2004-07-29', 'muhammad20424002@digitechuniversity.ac.id', '087715902964', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(107, 7, '20424009', 'PRIAN ZUL ARIFIN', 'L', 'Purworejo', '2005-01-01', 'prian20424009@digitechuniversity.ac.id', '081563995810', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(108, 7, '20424005', 'SITI SALWA FADILA', 'P', 'Bandung', '2006-08-08', 'siti20424005@digitechuniversity.ac.id', '085795451290', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(109, 7, '20424006', 'Sri Wahyuni', 'P', 'Sumedang', '2004-04-11', 'sri20424006@digitechuniversity.ac.id', '088218424661', 'Kab. Sumedang', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(110, 7, '20424010', 'Sultan Dzikrie Muhamad Al-Amri', 'L', 'Bandung', '2006-06-05', 'sultan20424010@digitechuniversity.ac.id', '08984239916', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(111, 7, '20424004', 'YADI SUKMA PRIHARYADI', 'L', 'Garut', '1986-08-29', 'yadi20424004@digitechuniversity.ac.id', '081221698243', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 4, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:56:09', '2026-05-22 07:56:09'),
(112, 7, '20425003', 'Ahsan Ramadhan Shakir', NULL, 'Tangerang', '2005-10-22', 'ahsan20425003@digitechuniversity.ac.id', '085559669063', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(113, 7, '20425009', 'Azmi Saiful Fasha', 'L', 'Bandung', '2001-11-12', 'azmi20425009@digitechuniversity.ac.id', '081318178197', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(114, 7, '20425004', 'Dikdik Ramdan Maulana', 'L', 'Bandung', '2006-11-18', 'dikdik20425004@digitechuniversity.ac.id', '085624368552', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(115, 7, '20425005', 'Mochamad Keyza Putra Adinda', 'L', 'Bandung', '2007-07-08', 'mochamad20425005@digitechuniversity.ac.id', '08815447927', 'Kab. Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(116, 7, '20425001', 'Muhammad Rafi Mujahid', 'L', 'Bandung', '2004-09-30', 'muhammad20425001@digitechuniversity.ac.id', '085930930424', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(117, 7, '20425006', 'Nurhasim', 'L', 'Jakarta', '1979-08-13', 'nurhasim20425006@digitechuniversity.ac.id', '085888665588', 'Kab. Kepulauan Seribu', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(118, 7, '20425002', 'Rahmalia Noviansyah', 'L', 'Cimahi', '2006-11-07', 'rahmalia20425002@digitechuniversity.ac.id', '0881023429009', 'Kota Bandung', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47'),
(119, 7, '20425008', 'Rama Noveriansyah', 'P', 'Jakarta', '2003-11-15', 'rama20425008@digitechuniversity.ac.id', '081223657108', 'Kab. Sumedang', 'Jawa Barat', 'Indonesia', 5, 'Mandiri', 'aktif', NULL, NULL, NULL, '2026-05-22 07:59:47', '2026-05-22 07:59:47');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(10) UNSIGNED NOT NULL,
  `kurikulum_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `sks_teori` tinyint(4) NOT NULL DEFAULT 0,
  `sks_praktek` tinyint(4) NOT NULL DEFAULT 0,
  `sks_lapangan` tinyint(4) NOT NULL DEFAULT 0,
  `semester` tinyint(4) NOT NULL,
  `jenis` enum('wajib','pilihan','mbkm') NOT NULL,
  `rps_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1780038449, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1780038449, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1780038449, 1),
(4, '2026-01-01-000001', 'App\\Database\\Migrations\\CreateMasterTables', 'default', 'App', 1780038449, 1),
(5, '2026-01-01-000002', 'App\\Database\\Migrations\\CreateAkreditasiMetaTables', 'default', 'App', 1780038450, 1),
(6, '2026-01-01-000003', 'App\\Database\\Migrations\\CreateSdmTables', 'default', 'App', 1780038450, 1),
(7, '2026-01-01-000004', 'App\\Database\\Migrations\\CreateTridharmaTables', 'default', 'App', 1780038450, 1),
(8, '2026-01-01-000005', 'App\\Database\\Migrations\\CreateKurikulumTables', 'default', 'App', 1780038450, 1),
(9, '2026-01-01-000006', 'App\\Database\\Migrations\\CreateSupportingTables', 'default', 'App', 1780038450, 1),
(10, '2026-01-01-000007', 'App\\Database\\Migrations\\CreateUserProdiMapping', 'default', 'App', 1780038450, 1),
(11, '2026-01-01-000008', 'App\\Database\\Migrations\\CreateLedNarasiTable', 'default', 'App', 1780038451, 1),
(12, '2026-01-01-000009', 'App\\Database\\Migrations\\AddPerformanceIndexes', 'default', 'App', 1780038452, 1),
(13, '2026-01-01-000010', 'App\\Database\\Migrations\\AddLinkBuktiToButirSkor', 'default', 'App', 1780038452, 1),
(14, '2026-01-01-000011', 'App\\Database\\Migrations\\CreateBorangTables', 'default', 'App', 1780038453, 1),
(15, '2026-01-01-000012', 'App\\Database\\Migrations\\CreateDokumenEviden', 'default', 'App', 1780038453, 1),
(16, '2026-01-01-000013', 'App\\Database\\Migrations\\AddLinkBuktiTridharma', 'default', 'App', 1780038453, 1),
(17, '2026-01-01-000014', 'App\\Database\\Migrations\\AddMetrikDosen', 'default', 'App', 1780038453, 1),
(18, '2026-01-01-000015', 'App\\Database\\Migrations\\AddDosenUserLink', 'default', 'App', 1780038453, 1),
(19, '2026-01-01-000016', 'App\\Database\\Migrations\\CreatePublikasiDosenPivot', 'default', 'App', 1780038453, 1),
(20, '2026-01-01-000017', 'App\\Database\\Migrations\\NormalizePublikasiDoi', 'default', 'App', 1780038453, 1),
(21, '2026-01-01-000018', 'App\\Database\\Migrations\\AddDtprSelfServiceTables', 'default', 'App', 1780038453, 1),
(22, '2026-01-01-000019', 'App\\Database\\Migrations\\CreateSpmiTables', 'default', 'App', 1780038453, 1),
(23, '2026-01-01-000020', 'App\\Database\\Migrations\\AddSpmiCompletenessTables', 'default', 'App', 1780038453, 1),
(24, '2026-01-01-000021', 'App\\Database\\Migrations\\CreatePengampuMk', 'default', 'App', 1780038453, 1),
(25, '2026-01-01-000022', 'App\\Database\\Migrations\\AddKodeMkToPengampu', 'default', 'App', 1780038453, 1),
(26, '2026-01-01-000023', 'App\\Database\\Migrations\\AddKopPathToProdi', 'default', 'App', 1780038453, 1),
(27, '2026-01-01-000024', 'App\\Database\\Migrations\\AddKopFooterToProdi', 'default', 'App', 1780038453, 1),
(28, '2026-01-01-000025', 'App\\Database\\Migrations\\AddKaprodiToProdi', 'default', 'App', 1780038454, 1),
(29, '2026-01-01-000026', 'App\\Database\\Migrations\\CreateSuratTugasMeta', 'default', 'App', 1780038454, 1),
(30, '2026-01-01-000027', 'App\\Database\\Migrations\\CreateEvidenPpepp', 'default', 'App', 1780038454, 1),
(31, '2026-01-01-000028', 'App\\Database\\Migrations\\CreateKondisiEksternal', 'default', 'App', 1780038454, 1),
(32, '2026-01-01-000029', 'App\\Database\\Migrations\\CreateKondisiEksternalNarasi', 'default', 'App', 1780039982, 2);

-- --------------------------------------------------------

--
-- Table structure for table `mk_cpl`
--

CREATE TABLE `mk_cpl` (
  `id` int(10) UNSIGNED NOT NULL,
  `mata_kuliah_id` int(10) UNSIGNED NOT NULL,
  `cpl_id` int(10) UNSIGNED NOT NULL,
  `bobot` decimal(3,2) NOT NULL DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penelitian`
--

CREATE TABLE `penelitian` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `judul` text NOT NULL,
  `ketua_dosen_id` int(10) UNSIGNED DEFAULT NULL,
  `sumber_dana` enum('pt_sendiri','mandiri','dalam_negeri','luar_negeri') NOT NULL DEFAULT 'pt_sendiri',
  `nama_sumber_dana` varchar(200) DEFAULT NULL,
  `jumlah_dana` bigint(20) NOT NULL DEFAULT 0,
  `mata_uang` varchar(5) NOT NULL DEFAULT 'IDR',
  `tahun_pelaksanaan` year(4) DEFAULT NULL,
  `libatkan_mahasiswa` tinyint(1) NOT NULL DEFAULT 0,
  `jumlah_mhs_terlibat` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penelitian_anggota`
--

CREATE TABLE `penelitian_anggota` (
  `id` int(10) UNSIGNED NOT NULL,
  `penelitian_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `peran` varchar(50) NOT NULL DEFAULT 'anggota'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengampu_mk`
--

CREATE TABLE `pengampu_mk` (
  `id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED NOT NULL,
  `mata_kuliah_id` int(10) UNSIGNED DEFAULT NULL,
  `kode_mk` varchar(30) DEFAULT NULL,
  `nama_mk` varchar(200) NOT NULL,
  `kategori` enum('ps_sendiri','ps_lain_pt','pt_lain') NOT NULL DEFAULT 'ps_sendiri',
  `sks` decimal(4,1) NOT NULL DEFAULT 0.0,
  `jumlah_kelas` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengembangan_dtpr`
--

CREATE TABLE `pengembangan_dtpr` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED DEFAULT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `nama_dtpr` varchar(150) NOT NULL,
  `jenis_pengembangan` varchar(200) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perguruan_tinggi`
--

CREATE TABLE `perguruan_tinggi` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `singkatan` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perguruan_tinggi`
--

INSERT INTO `perguruan_tinggi` (`id`, `kode`, `nama`, `singkatan`, `alamat`, `kota`, `provinsi`, `logo_path`, `website`, `created_at`, `updated_at`) VALUES
(1, 'UTD', 'Universitas Teknologi Digital', 'UTD', NULL, 'Bandung', 'Jawa Barat', 'assets/logo-utd.png', 'https://utd.ac.id', '2026-05-22 01:13:44', '2026-05-22 01:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `pkm`
--

CREATE TABLE `pkm` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `judul` text NOT NULL,
  `ketua_dosen_id` int(10) UNSIGNED DEFAULT NULL,
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
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pkm_anggota`
--

CREATE TABLE `pkm_anggota` (
  `id` int(10) UNSIGNED NOT NULL,
  `pkm_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `peran` varchar(50) NOT NULL DEFAULT 'anggota'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppepp`
--

CREATE TABLE `ppepp` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `siklus` varchar(20) NOT NULL,
  `tahap` enum('penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan') NOT NULL,
  `judul` varchar(250) NOT NULL,
  `isi` longtext DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prestasi_mahasiswa`
--

CREATE TABLE `prestasi_mahasiswa` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `mahasiswa_id` int(10) UNSIGNED DEFAULT NULL,
  `nama_kegiatan` varchar(250) NOT NULL,
  `penyelenggara` varchar(200) DEFAULT NULL,
  `tingkat` enum('lokal','wilayah','nasional','internasional') NOT NULL,
  `jenis` enum('akademik','non_akademik') NOT NULL,
  `prestasi` varchar(100) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `id` int(10) UNSIGNED NOT NULL,
  `upps_id` int(10) UNSIGNED NOT NULL,
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
  `kop_path` varchar(255) DEFAULT NULL,
  `kop_footer_path` varchar(255) DEFAULT NULL,
  `kaprodi_dosen_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `upps_id`, `kode_pddikti`, `nama`, `jenjang`, `gelar`, `singkatan_gelar`, `akreditasi_saat_ini`, `no_sk_akreditasi`, `tgl_sk_akreditasi`, `tgl_kadaluarsa`, `status`, `kop_path`, `kop_footer_path`, `kaprodi_dosen_id`, `created_at`, `updated_at`) VALUES
(1, 1, '62201', 'Akuntansi', 'S1', NULL, NULL, 'Baik Sekali', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(2, 1, '62401', 'Akuntansi', 'D3', NULL, NULL, 'Baik Sekali', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(3, 1, '90241', 'Desain Komunikasi Visual', 'S1', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(4, 1, '74201', 'Hukum', 'S1', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(5, 1, '55200', 'Informatika', 'S1', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(6, 1, '61201', 'Manajemen', 'S1', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(7, 1, '57401', 'Manajemen Informatika', 'D3', 'Ahli Madya Komputer', 'A.Md.Kom', 'Baik', '254/SK/LAM-INFOKOM/Ak/D3/VIII/2025', '2025-08-18', '2030-08-18', 'aktif', 'kop_atas_7_f64f676b.png', 'kop_bawah_7_398a259a.png', 1, '2026-05-22 01:13:44', '2026-05-22 02:39:35'),
(8, 1, '57472', 'Manajemen Informatika (Kampus Kota Tegal)', 'D3', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(9, 1, '93104', 'Manajemen Inovasi', 'S2', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(10, 1, '61405', 'Manajemen Perusahaan', 'D3', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44'),
(11, 1, '26201', 'Teknik Industri', 'S1', NULL, NULL, 'Baik', NULL, NULL, NULL, 'aktif', NULL, NULL, NULL, '2026-05-22 01:13:44', '2026-05-22 01:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `prodi_instrumen`
--

CREATE TABLE `prodi_instrumen` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `lam_instrumen_id` int(10) UNSIGNED NOT NULL,
  `periode_mulai` date DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi_instrumen`
--

INSERT INTO `prodi_instrumen` (`id`, `prodi_id`, `lam_instrumen_id`, `periode_mulai`, `aktif`) VALUES
(3, 5, 2, '2026-05-22', 1),
(10, 7, 6, '2026-05-23', 1),
(11, 8, 6, '2026-05-23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `publikasi`
--

CREATE TABLE `publikasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED DEFAULT NULL,
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
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publikasi`
--

INSERT INTO `publikasi` (`id`, `prodi_id`, `dosen_id`, `kategori`, `jenis`, `judul`, `penulis`, `nama_media`, `tahun`, `volume`, `doi`, `url`, `sitasi`, `sinta_score`, `created_at`) VALUES
(1, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'Data Integration Approaches for AI-Ready Data Architecture: A Comparative Review of Relational Databases, Data Warehouses, and Knowledge Graphs', 'Kodrat Mahatma, Mamok Andri Senubekti, Binastya Anggara Sekti', 'Jurnal Teknologi Informatika dan Komputer', '2026', '12', '10.37012/jtik.v12i1.3447', 'https://doi.org/10.37012/jtik.v12i1.3447', 0, NULL, '2026-05-24 04:46:27'),
(2, 7, 1, 'penelitian', 'jurnal_internasional_bereputasi', 'Factors influencing the adoption and usage of case-based reasoning systems by judicial professionals in criminal cases: A systematic literature review', 'Mamok Andri Senubekti, Pujiyono, Foni Agus Setiawan, Mohammad Nur Fitriyadi, Darsiti, Novita Anggraini', 'AIP conference proceedings', '2026', '3416', '10.1063/5.0322114', 'https://doi.org/10.1063/5.0322114', 1, 0, '2026-05-24 04:46:27'),
(3, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PERANCANGAN KONSEPTUAL APLIKASI KEUANGAN BERBASIS WEB UNTUK YAYASAN ASSALAM: TRANSFORMASI DARI SISTEM MANUAL KE DIGITAL', 'Mahardika Agus Setiono, Ridha Adjie Eryadi, Mamok Andri Senubekti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i5.14965', 'https://doi.org/10.36040/jati.v9i5.14965', 0, NULL, '2026-05-24 04:46:27'),
(4, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENERAPAN METODE PROTOTYPING DALAM IMPLEMENTASI APLIKASI PEMESANAN MAKANAN ONLINE MENGGUNAKAN BARCODE PADA KEDAI MAS GUNARTO', 'Mohammad Rendi Valentino, Mohammad Nur Fitriyadi, Mamok Andri Senubekti, Darsiti Darsiti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i5.15045', 'https://doi.org/10.36040/jati.v9i5.15045', 7, NULL, '2026-05-24 04:46:27'),
(5, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PREDIKSI KELULUSAN MAHASISWA DENGAN MENGGUNAKAN ALGORITMA DECISION TREE', 'Reki Kurnia Permana, Mamok Andri Senubekti, Novita Anggraini, Muhammad Usman', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i5.14877', 'https://doi.org/10.36040/jati.v9i5.14877', 1, NULL, '2026-05-24 04:46:27'),
(6, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'Aplikasi Mobile Programming Forum Kesehatan Mental dengan Framework Flutter Metode Prototyping', 'Muhammad Faizal Pratama, Novita Anggraini, Mamok Andri Senubekti, M. Syafiuddin Usman', 'J-SISKO TECH (Jurnal Teknologi Sistem Informasi dan Sistem Komputer TGD)', '2025', '8', '10.53513/jsk.v8i2.11650', 'https://doi.org/10.53513/jsk.v8i2.11650', 0, NULL, '2026-05-24 04:46:27'),
(7, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'SISTEM INFORMASI PENGOLAHAN DATA SISWA BERBASIS WEB STUDI KASUS PADA MIN 3 TEGAL', 'Sevira Apriliyani Sugiarti, Mamok Andri Senubekti, Jaka Subrata', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i4.14093', 'https://doi.org/10.36040/jati.v9i4.14093', 0, NULL, '2026-05-24 04:46:27'),
(8, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'SISTEM INFORMASI PEMBAYARAN SPP BERBASIS WEB', 'Riski Agustina S, Mamok Andri Senubekti, Aries Setyani Wahyu Prasetyawati', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i4.13886', 'https://doi.org/10.36040/jati.v9i4.13886', 2, NULL, '2026-05-24 04:46:27'),
(9, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pengembangan Aplikasi e-Bale Sunda untuk Digitalisasi Peminjaman Arsip di Pengadilan Negeri Bale Bandung Kelas 1A Menggunakan CodeIgniter 4', 'Fenti Febrian A, Mamok Andri Senubekti, Novita Anggraini, Fadzar Rusghana', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2025', '3', '10.62565/tekno.v3i1.87', 'https://doi.org/10.62565/tekno.v3i1.87', 0, NULL, '2026-05-24 04:46:27'),
(10, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'IMPLEMENTASI PROTOTYPING DALAM PENGEMBANGAN APLIKASI KEUANGAN YAYASAN ASSALAAM AL MADANI SUMEDANG SEBAGAI UPAYA MENINGKATKAN AKUNTABILITAS KINERJA', 'Mohammad Nur Fitriyadi, Mamok Andri Senubekti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i6.12148', 'https://doi.org/10.36040/jati.v8i6.12148', 2, NULL, '2026-05-24 04:46:27'),
(11, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'OPTIMALISASI PENGALAMAN PENGGUNA MELALUI DESAIN ANTARMUKA PENGGUNA INTUITIF MELALUI SISTEM MONEV SOP', 'Ayu Cahya Ferariani, Mamok Andri Senubekti, Tarsinah Sumarni', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i6.11603', 'https://doi.org/10.36040/jati.v8i6.11603', 3, NULL, '2026-05-24 04:46:27'),
(12, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Akibat Hukum Mengkomersilkan Lagu Orang Lain Tanpa Izin', 'Asep Imroni, Alwi Al Hadad, Mamok Andri Senubekti', 'KEADILAN Jurnal Penelitian Hukum dan Peradilan', '2024', '2', '10.62565/keadilan.v2i2.51', 'https://doi.org/10.62565/keadilan.v2i2.51', 0, NULL, '2026-05-24 04:46:27'),
(13, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENGEMBANGAN TAHAP PERTAMA APLIKASI SOPWATCH UNTUK MONITORING STANDAR OPERASIONAL PROSEDUR BERBASIS WEBSITE', 'Nisa Nurul Qoyimah, Novita Anggraini, Mamok Andri Senubekti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i5.11094', 'https://doi.org/10.36040/jati.v8i5.11094', 0, NULL, '2026-05-24 04:46:27'),
(14, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENGEMBANGAN APLIKASI \'SOPWATCH\' UNTUK STANDAR OPERASIONAL PROSEDUR BERBASIS WEB', 'Bianda Salfabila, Mamok Andri Senubekti, Novita Anggraini', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i5.10641', 'https://doi.org/10.36040/jati.v8i5.10641', 1, NULL, '2026-05-24 04:46:27'),
(15, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'DESAIN UI/UX APLIKASI ‘SOPWATCH’ DIBUAT DENGAN METODE PROTOTYPING MENGGUNAKAN FIGMA UNTUK MONITORING SOP', 'Galuh Herlina Harjanegara, Mamok Andri Senubekti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i5.10713', 'https://doi.org/10.36040/jati.v8i5.10713', 2, NULL, '2026-05-24 04:46:27'),
(16, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'Pembuatan Desain UI/UX dengan Metode Prototyping pada Aplikasi Layanan Pengadilan Negeri Bale Bandung menggunakan Figma', 'Mamok Andri Senubekti, Geovanny Lea Dajoreyta, Novita Anggraini', 'Jurnal Informatika Terpadu', '2024', '10', '10.54914/jit.v10i1.1001', 'https://doi.org/10.54914/jit.v10i1.1001', 37, NULL, '2026-05-24 04:46:27'),
(17, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pembuatan Motion Graphic Animasi Profile Company Untuk PT. Atap Promotions', 'Nur Fitriyani, Mamok Andri Senubekti', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2024', '2', '10.62565/tekno.v2i1.22', 'https://doi.org/10.62565/tekno.v2i1.22', 1, NULL, '2026-05-24 04:46:27'),
(18, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Prediksi Penilaian Kinerja Hakim Dengan Penerapan Machine Learning Menggunakan Tools Python', 'Novita Febriana, Firly Fadzira, Mamok Andri Senubekti, Ririn Suharsih', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2024', '2', '10.62565/tekno.v2i1.23', 'https://doi.org/10.62565/tekno.v2i1.23', 4, NULL, '2026-05-24 04:46:27'),
(19, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Perancangan Sistem Informasi Pengolahan Data Penduduk Pada Kelurahan Pungkur', 'Yana Rohyana, Mamok Andri Senubekti', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2024', '2', '10.62565/tekno.v2i1.14', 'https://doi.org/10.62565/tekno.v2i1.14', 1, NULL, '2026-05-24 04:46:27'),
(20, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pembuatan Desain UI/UX Aplikasi Pemesanan Depot Kurnia Water Dengan Metode Prototyping Dengan Tools Adobe XD', 'Reza Widjaya, Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2023', '1', '10.62565/tekno.v1i1.4', 'https://doi.org/10.62565/tekno.v1i1.4', 1, NULL, '2026-05-24 04:46:27'),
(21, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Rancang Bangun Website Sekolah Dengan Menggunakan Bootsrap (Studi Kasus SMP Negeri 3 Pacet)', 'Hendra Sopandi, Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2023', '1', '10.62565/tekno.v1i1.1', 'https://doi.org/10.62565/tekno.v1i1.1', 0, NULL, '2026-05-24 04:46:27'),
(22, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Perancangan Prototype UI/UX Design Pada Aplikasi Peminjaman Tools Pada PT Taka Turbo Machiners', 'Mahardika Agus Setiono, Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2023', '1', '10.62565/tekno.v1i1.3', 'https://doi.org/10.62565/tekno.v1i1.3', 3, NULL, '2026-05-24 04:46:27'),
(23, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pembuatan Video Animasi SOP Pelayanan Menggunakan Adobe After Effect CC dan Adobe Premiere Pro CC (Studi Kasus Kelurahan Pungkur)', 'Nina Anita, Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2023', '1', '10.62565/tekno.v1i1.5', 'https://doi.org/10.62565/tekno.v1i1.5', 1, NULL, '2026-05-24 04:46:27'),
(24, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Perancangan Aplikasi Monitoring Tumbuh Kembang Anak Usia Dini Berbasis WEB (Studi Kasus PAUD AL-BAROKAH)', 'S E Mm Sopian, Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2023', '1', '10.62565/tekno.v1i1.2', 'https://doi.org/10.62565/tekno.v1i1.2', 1, NULL, '2026-05-24 04:46:27'),
(25, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PRINSIP KLASIFIKASI DAN DATA MINING DENGAN ALGORITMA C4.5', 'Mamok Andri Senubekti, Lies Anggi Puspita Dewi', 'NUANSA INFORMATIKA', '2022', '16', '10.25134/nuansa.v16i2.5834', 'https://doi.org/10.25134/nuansa.v16i2.5834', 8, NULL, '2026-05-24 04:46:27'),
(26, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pengaruh Pengembangan Karir dan Motivasi Terhadap Kepuasan Kerja Karyawan pada Pengadilan Negeri Bale Bandung', 'Lies Anggi Puspita Dewi, Mamok Andri Senubekti', 'Jurnal Study and Management Research', '2022', '18', '10.55916/smart.v18i1.63', 'https://doi.org/10.55916/smart.v18i1.63', 0, NULL, '2026-05-24 04:46:27'),
(27, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'NOTIFIKASI SMS TILANG LAYANAN PENGADILAN (STUDI KASUS: PENGADILAN NEGERI BALE BANDUNG)', 'Mamok Andri Senubekti', 'NUANSA INFORMATIKA', '2022', '16', '10.25134/nuansa.v16i1.4559', 'https://doi.org/10.25134/nuansa.v16i1.4559', 0, NULL, '2026-05-24 04:46:27'),
(28, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PERANCANGAN APLIKASI PENJUALAN ONLINE BERBASIS WEBSITE (STUDI KASUS : Bakso Emsa)', 'Aldi Emsa Yanuar, Mamok Andri Senubekti', 'NUANSA INFORMATIKA', '2022', '16', '10.25134/nuansa.v16i1.4661', 'https://doi.org/10.25134/nuansa.v16i1.4661', 31, NULL, '2026-05-24 04:46:27'),
(29, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PERANCANGAN APLIKASI PENGELOLA KEUANGAN PRIBADI BERBASIS ANDROID', 'Erga Trivaika, Mamok Andri Senubekti', 'NUANSA INFORMATIKA', '2022', '16', '10.25134/nuansa.v16i1.4670', 'https://doi.org/10.25134/nuansa.v16i1.4670', 181, NULL, '2026-05-24 04:46:27'),
(30, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'DESIGN OF WEBSITE-BASED SANTRI ATTENDANCE INFORMATION SYSTEM (CASE STUDY: RUMAH YATIM AND TAHFIDZ QUR\'AN MADANI BANDUNG)', 'Mamok Andri Senubekti, Muhammad Yusron Fathul Islam', 'BEST Journal of Applied Electrical Science & Technology', '2021', '3', '10.36456/best.vol3.no2.4221', 'https://doi.org/10.36456/best.vol3.no2.4221', 0, NULL, '2026-05-24 04:46:27'),
(31, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENGARUH INOVASI DRIP BAG COFFEE PADA PRODUK KOPI SUSU KEMASAN BRAND KOPASUS TERHADAP PENJUALAN DI KEDAI LYTE COFFEE BANDUNG', 'Lendra Prasasti, Mamok Andri Senubekti', 'JURNAL PENKOMI KAJIAN PENDIDIKAN DAN EKONOMI', '2025', NULL, '10.33627/pk.v8i1.2311', 'https://doi.org/10.33627/pk.v8i1.2311', 0, NULL, '2026-05-24 05:06:33'),
(32, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'Penerapan Metode Hybrid Waterfall-RAD pada Sistem Antrian Web untuk Optimalisasi Layanan di Pengadilan Negeri Bale Bandung', 'Mamok Andri Senubekti, M. Kartawidjaja, Gede Bagia Wijaya Adyana', 'JOINS (Journal of Information System)', '2025', NULL, '10.33633/joins.v10i1.12668', 'https://doi.org/10.33633/joins.v10i1.12668', 5, NULL, '2026-05-24 05:06:33'),
(33, 7, 1, 'penelitian', 'jurnal_nasional_terakreditasi', 'PERANCANGAN UI/UX APLIKASI MOBILE PANGAUBAN KARINDING DENGAN MENGGUNAKAN FIGMA', 'Novitasari Ramadhani Lubis, Novita Anggraini, Mamok Andri Senubekti', 'JURNAL ILMIAH INFORMATIKA', '2025', NULL, '10.33884/jif.v13i02.10147', 'https://doi.org/10.33884/jif.v13i02.10147', 1, NULL, '2026-05-24 05:06:33'),
(34, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Meningkatkan Kualitas Pembelajaran Melalui Media Interaktif Berbasis Multimedia', 'C. Ramadhan, Mamok Andri Senubekti, Nurdiansyah Nurdiansyah', 'Jurnal Teknik Mesin, Industri, Elektro dan Informatika', '2025', NULL, '10.55606/jtmei.v4i2.5010', 'https://doi.org/10.55606/jtmei.v4i2.5010', 4, NULL, '2026-05-24 05:06:33'),
(35, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pengembangan Sistem Pemesanan Layanan Produksi Cetak Mobile Berbasis Web untuk Meningkatkan Efisiensi Operasional (Studi Kasus : Percetakan Dan Sablon Teguh Desain)', 'Muhammad Syah Farros Mahfudz, Mamok Andri Senubekti, Ari Prasetyawati', 'Media jurnal informatika', '2025', NULL, '10.35194/mji.v17i1.5139', 'https://doi.org/10.35194/mji.v17i1.5139', 1, NULL, '2026-05-24 05:06:33'),
(36, 7, 1, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Penerapan Metodologi Agile dalam Pengembangan Perangkat Lunak', 'C. Ramadhan, Mamok Andri Senubekti, Diena Amalia', 'Router : Jurnal Teknik Informatika dan Terapan', '2025', '', '10.62951/router.v3i2.411', 'https://doi.org/10.62951/router.v3i2.411', 29, 0, '2026-05-24 05:06:33'),
(37, 7, 2, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'An LSTM-Based Approach for Short-Term Solar Power Forecasting with Diurnal and Intra-Day Variability', 'Darsiti Darsiti, Tarsinah Sumarni, Fahmi Abdullah, Budiman', 'Bulletin of Intelligent Machines and Algorithms', '2026', '1', '10.65780/bima.v1i2.7', 'https://doi.org/10.65780/bima.v1i2.7', 0, NULL, '2026-05-24 14:35:48'),
(39, 7, 2, 'penelitian', 'jurnal_nasional_terakreditasi', 'Perancangan UI/UX Website Bandung Bawah Tanah Yang Responsif', 'Adinda Salsabila, Darsiti Darsiti, Novita Anggraini, Mamok Andri Senubekti', 'Jurnal Manajemen Informatika &amp; Teknologi', '2025', NULL, '10.51903/2gxcdc03', 'https://doi.org/10.51903/2gxcdc03', 1, NULL, '2026-05-24 14:37:23'),
(40, 7, 2, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Studi Kelayakan Bisnis Pengembangan Pemasaran PT. Rizki Prima Food', 'Iqlima Octavianidori, Muhamad Nazar Fadilah, Sadiah Permata Sari, Doni Ramdani, M. Randi Rukmana, Darsiti Darsiti, Meilani Purwanti', 'Bumi : Jurnal Hasil Kegiatan Sosialisasi Pengabdian kepada Masyarakat', '2024', NULL, '10.61132/bumi.v2i1.52', 'https://doi.org/10.61132/bumi.v2i1.52', 0, NULL, '2026-05-24 14:37:23'),
(41, 7, 2, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Studi Kelayakan Bisnis Pengembangan Inovasi Varian Rasa Bakso Aci Pada Badan Usaha Tercabaikan', 'Darsiti Darsiti, Asep Mahmudin, Cahya Putri Julyandaru, Putri Meilani Gustian,  Salsa Sabila', 'KREATIF: Jurnal Pengabdian Masyarakat Nusantara', '2024', NULL, '10.55606/kreatif.v4i1.2812', 'https://doi.org/10.55606/kreatif.v4i1.2812', 0, NULL, '2026-05-24 14:37:23'),
(42, 7, 2, 'penelitian', 'jurnal_nasional_terakreditasi', 'Analisis Kelayakan Bisnis Untuk Pengembangan Pemasaran Kacang Sangrai Ewooww Yang Luas', 'Darsiti Darsiti, Ikbal Saepul Ramdan, Naila Syafitri, Widi Aulia Lestari, Wilda Jamila Suryaman, Surya Kurnia Alamsyah', 'Faedah : Jurnal Hasil Kegiatan Pengabdian Masyarakat Indonesia', '2024', NULL, '10.59024/faedah.v2i1.723', 'https://doi.org/10.59024/faedah.v2i1.723', 0, NULL, '2026-05-24 14:37:23'),
(43, 7, 3, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Klasifikasi Tingkat Kepuasan Mahasiswa Universitas Teknologi Digital Terhadap Pembelajaran Secara Daring Menggunakan Algoritma Naïve Bayes', 'Moch. Akbar Ramdani, Zulfan Nugraha, Mohammad Syafiuddin Usman', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2025', '3', '10.62565/tekno.v3i1.55', 'https://doi.org/10.62565/tekno.v3i1.55', 0, NULL, '2026-05-24 14:47:47'),
(44, 7, 3, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Prediksi Kehadiran Mahasiswa Universitas Teknologi Digital Menggunakan Naïve Bayes', 'Paris Al Davie Saepudin, Mohammad Syafiuddin Usman', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2024', '2', '10.62565/tekno.v2i2.52', 'https://doi.org/10.62565/tekno.v2i2.52', 0, NULL, '2026-05-24 14:47:47'),
(45, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'The Web as an Enabler of Digital Transformation', 'Syifa Maulida Akmalia, Kodrat Mahatma, Gusti Muhamad Sardana', '', '2025', '', '10.4018/979-8-3373-5167-4.ch001', 'https://doi.org/10.4018/979-8-3373-5167-4.ch001', 0, 0, '2026-05-30 02:22:48'),
(46, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'SENTIMEN PUBLIK TERHADAP KONTROVERSI BYON COMBAT SHOWBIZ BERBASIS SUPPORT VECTOR MACHINE DAN NAIVE BAYES', 'Moch. Akbar Ramdani, Mohammad Nur Fitriyadi, Mamok Andri Senubekti', 'Jurnal Informatika Progres', '2026', NULL, '10.56708/progres.v18i2.475', 'https://doi.org/10.56708/progres.v18i2.475', 0, NULL, '2026-11-13 06:43:38'),
(47, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Predicting Customer Dropout from Early Behavioral Data in a Vocational EdTech Platform', 'Lies Anggi Puspita Dewi, Novita Anggraini, Mamok Andri Senubekti', 'International Journal Of Economics Social And Technology', '2026', '5', '10.59086/ijest.v5i3.2127', 'https://doi.org/10.59086/ijest.v5i3.2127', 0, NULL, '2026-11-13 06:43:38'),
(48, 7, NULL, 'penelitian', 'jurnal_internasional_bereputasi', 'An expert-augmented case-based reasoning framework with knowledge graph for criminal verdict recommendation in Indonesia', 'Mamok Andri Senubekti, Pujiyono Pujiyono, Foni Agus Setiawan', 'Expert Systems with Applications', '2026', '332', '10.1016/j.eswa.2026.133748', 'https://doi.org/10.1016/j.eswa.2026.133748', 1, NULL, '2026-11-13 06:43:38'),
(49, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Dinamika Beban Kerja dan Manajemen Kinerja: Studi Kualitatif Terhadap Efektivitas Pegawai di Lembaga Peradilan', 'Lies Anggi Puspita Dewi, Mamok Andri Senubekti', 'Mandiri Jurnal Akuntansi dan Keuangan', '2026', NULL, '10.59086/jak.v5i1.1572', 'https://doi.org/10.59086/jak.v5i1.1572', 0, NULL, '2026-11-13 06:43:38'),
(50, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'SISTEM INFORMASI PENGELOLAAN DATA LETTER C BERBASIS WEBSITE: STUDI KASUS : BALAI DESA TRAYEMAN', 'Pusparani Rahmadani, Mamok Andri Senubekti, Aries Setyani Wahyu Prasetyawati', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i4.14119', 'https://doi.org/10.36040/jati.v9i4.14119', 1, NULL, '2026-11-13 06:43:38'),
(51, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'SISTEM INFORMASI RAPOR SISWA BERBASIS WEBSITE: STUDI KASUS: RA HIDAYATUT THOLIBIN ADIWERNA', 'Laeli Hidayah, Mamok Andri Senubekti, Jaka Subrata', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2025', '9', '10.36040/jati.v9i4.14330', 'https://doi.org/10.36040/jati.v9i4.14330', 1, NULL, '2026-11-13 06:43:38'),
(52, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Perbandingan SQL dan NoSQL: Studi Literatur Tentang Keunggulan, Kelemahan, dan Penerapannya', 'Chandra Ramadhan, Mamok Andri Senubekti, Suhendri Suhendri', 'Jupiter Publikasi Ilmu Keteknikan Industri Teknik Elektro dan Informatika', '2025', '3', '10.61132/jupiter.v3i3.785', 'https://doi.org/10.61132/jupiter.v3i3.785', 2, NULL, '2026-11-13 06:43:38'),
(53, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'YOLO26n-Based Apple Leaf Disease Detection for Precision Agriculture Using Lightweight Deep Learning and Object Detection', 'Darsiti Darsiti, Budiman, Dhika Wdiyanto, Tarsinah Sumarni', 'Bulletin of Intelligent Machines and Algorithms', '2026', '1', '10.65780/bima.v1i4.22', 'https://doi.org/10.65780/bima.v1i4.22', 1, NULL, '2026-11-13 06:55:13'),
(54, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'A Comprehensive Machine Learning Approach for Predicting Beats Per Minute (BPM) in Music Using Audio Features', 'Darsiti Darsiti', 'Bulletin of Intelligent Machines and Algorithms', '2025', '1', '10.65780/bima.v1i1.2', 'https://doi.org/10.65780/bima.v1i1.2', 1, NULL, '2026-11-13 06:55:13'),
(55, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENGEMBANGAN APLIKASI PELATIHAN KELAS KESENIAN DAERAH BERBASIS WEB PADA SANGGAR KASUNDA', 'Muhammad Shafar Alfarizi Shafar, Mamok Andri S, Darsiti Darsiti, Fadzar Rusghana', 'Jurnal Informatika dan Teknik Elektro Terapan', '2025', '13', '10.23960/jitet.v13i3s1.7562', 'https://doi.org/10.23960/jitet.v13i3s1.7562', 1, NULL, '2026-11-13 06:55:13'),
(56, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'DESAIN PROTOTIPE ANTARMUKA DAN PENGALAMAN PENGGUNA SISTEM INFORMASI PELAYANAN KEPENDUDUKAN PADA KELURAHAN KEBON KANGKUNG', 'Andri Maulana Yusuf Author, Mamok Andri S, Darsiti Author', 'Jurnal Informatika dan Teknik Elektro Terapan', '2025', '13', '10.23960/jitet.v13i3s1.7494', 'https://doi.org/10.23960/jitet.v13i3s1.7494', 1, NULL, '2026-11-13 06:55:13'),
(57, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'PEMBUATAN WEBSITE INVENTARIS BARANG DI KOBER AULIA', 'Thomas Lazuardi Muhammad, Darsiti Darsiti', 'JURNAL ILMIAH INFORMATIKA', '2025', '13', '10.33884/jif.v13i02.10152', 'https://doi.org/10.33884/jif.v13i02.10152', 0, NULL, '2026-11-13 06:55:13'),
(58, 7, NULL, 'penelitian', 'jurnal_internasional', 'Data Localization and Competitive Reconfiguration: Strategic Adaptation in Emerging Digital Economies', 'Darsiti', 'Manexia Journal of Business Management and Creative Economy', '2025', '1', '10.66203/manexia.01304', 'https://doi.org/10.66203/manexia.01304', 0, NULL, '2026-11-13 06:55:13'),
(59, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Evaluasi Perubahan Pola Interaksi Sosial Mahasiswa Manajemen Informatika di Era Pembelajaran Digital', 'Widya Fauzi Amelia, Darsiti', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2025', '3', '10.62565/tekno.v3i2.65', 'https://doi.org/10.62565/tekno.v3i2.65', 1, NULL, '2026-11-13 06:55:13'),
(60, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Implementasi Website Company Profile Berbasis PHP dan Bootstrap untuk Optimalisasi Akses Informasi Pariwisata', 'Fadlah Aulia, Darsiti Darsiti', 'Journal of Information Technology', '2024', '4', '10.46229/jifotech.v4i2.919', 'https://doi.org/10.46229/jifotech.v4i2.919', 2, NULL, '2026-11-13 06:55:13'),
(61, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'PENGEMBANGAN SISTEM INFORMASI PERPUSTAKAAN BERBASIS WEB: STUDI KASUS : SMK PASUNDAN RANCAEKEK', 'Alfat Gufron Siregar, Winda Sulastri, Darsiti Darsiti', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2024', '8', '10.36040/jati.v8i6.11593', 'https://doi.org/10.36040/jati.v8i6.11593', 2, NULL, '2026-11-13 06:55:13'),
(62, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'IMPLEMENTASI MULTIMEDIA DEVELOPMENT LIFE CYCLE (MDLC) PADA APLIKASI MEDIA PEMBELAJARAN BUDAYA JAWA BARAT DI KOMPEPAR GIRI HARJA JELEKONG', 'Ilma Amalia Jaza Ilma, Darsiti', 'Jurnal Multimedia dan Teknologi Informasi (Jatilima)', '2024', NULL, '10.54209/jatilima.v6i02.609', 'https://doi.org/10.54209/jatilima.v6i02.609', 3, NULL, '2026-11-13 06:55:13'),
(63, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Implementasi CorelDRAW X7 Dalam Pengembangan Corporate Identity Sebagai Media Promosi', 'Defani Oktaviani, Darsiti', 'Jurnal Multimedia dan Teknologi Informasi (Jatilima)', '2024', NULL, '10.54209/jatilima.v6i02.600', 'https://doi.org/10.54209/jatilima.v6i02.600', 1, NULL, '2026-11-13 06:55:13'),
(64, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Pengembangan Aplikasi Keuangan Berbasis Web (Studi Kasus : Mts AT-Taufiq Kota Bandung)', 'Dina Sari Fujiwati Lestari, Darsiti Darsiti', 'NUANSA INFORMATIKA', '2023', '17', '10.25134/ilkom.v17i2.17', 'https://doi.org/10.25134/ilkom.v17i2.17', 1, NULL, '2026-11-13 06:55:13'),
(65, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Perancangan Aplikasi Pengolahan Data Konsumen Pada Perusahaan Jukut Fotografi', 'Darsiti Darsiti', 'Prosiding FRIMA (Festival Riset Ilmiah Manajemen dan Akuntansi)', '2019', NULL, '10.55916/frima.v0i2.97', 'https://doi.org/10.55916/frima.v0i2.97', 0, NULL, '2026-11-13 06:55:13'),
(66, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Implementation of Smart Environmental Security in the Village Community by Applying CCTV and IoT Technology', 'Rano Agustino, Abu Sopian, Dedi Setiadi, Febrianti Binastya Anggara Sekti, Kodrat Mahatma, Yohanes Bowo Widodo', 'Jurnal Pemberdayaan Komunitas MH Thamrin', '2026', '8', '10.37012/jpkmht.v8i1.3395', 'https://doi.org/10.37012/jpkmht.v8i1.3395', 0, NULL, '2026-11-13 07:21:13'),
(67, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Enhancing Post Implementation ERP Business Process Maturity Using ISO 9001:2015 and CMMI v3.0: A Case Study of a Manufacturing Company', 'Bill Petrus Cerullo Gabriel Martin Hutabarat, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'JST (Jurnal Sains dan Teknologi)', '2025', '14', '10.23887/jst-undiksha.v14i3.109995', 'https://doi.org/10.23887/jst-undiksha.v14i3.109995', 0, NULL, '2026-11-13 07:21:13'),
(68, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Strengthening High School Students\' Digital Literacy through the Implementation and Training of School Information Systems', 'Rano Agustino, Dedi Setiadi, Abu Sopian, Febrianti Widyahastuti, Binastya Anggara Sekti, Kodrat Mahatma', 'Jurnal Pemberdayaan Komunitas MH Thamrin', '2025', '7', '10.37012/jpkmht.v7i2.3078', 'https://doi.org/10.37012/jpkmht.v7i2.3078', 0, NULL, '2026-11-13 07:21:13'),
(69, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Transforming Public Sector Auditing from Tradition to Agility with Scrum: A Case for the Indonesian Public Sector', 'Benidiktus Bambang Triasmoro, Kodrat Mahatma, Eko Kuswardono Budiardjo', 'Jurnal Locus Penelitian dan Pengabdian', '2025', '4', '10.58344/locus.v4i9.4769', 'https://doi.org/10.58344/locus.v4i9.4769', 0, NULL, '2026-11-13 07:21:13'),
(70, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Feature Model for Pet Health Application Domain using Feature Oriented Domain Analysis', 'Ajeng Nurhidayati A Darsan Satoh, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'Indonesian Journal of Computer Science', '2024', '13', '10.33022/ijcs.v13i4.4296', 'https://doi.org/10.33022/ijcs.v13i4.4296', 0, NULL, '2026-11-13 07:21:13'),
(71, 7, NULL, 'penelitian', 'jurnal_internasional_bereputasi', 'Recommendation for Scrum-Based Software Development Process with Scrum at Scale: A Case Study of Software House XYZ', 'Ahmad Jalaluddin, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'Jurnal RESTI (Rekayasa Sistem dan Teknologi Informasi)', '2024', '8', '10.29207/resti.v8i3.5646', 'https://doi.org/10.29207/resti.v8i3.5646', 3, NULL, '2026-11-13 07:21:13'),
(72, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Onboarding Model to Integrate Newcomers into Scrum Team at an Insurance Company', 'Pramudya Mahardhika, Eko Kuswardono Budiardjo, Kodrat Mahatma, Shinta Dewi Larasati', 'Jurnal Sistem Informasi', '2024', '20', '10.21609/jsi.v20i1.1390', 'https://doi.org/10.21609/jsi.v20i1.1390', 0, NULL, '2026-11-13 07:21:13'),
(73, 7, NULL, 'penelitian', 'seminar_internasional', 'Measuring E-Governance System (SPBE) Development Maturity Level with CMMI Model V2.0: A Case Study of XYZ City Government', 'Mohamad Hilmy Zhafrani, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Software Engineering and Information Technology (ICoSEIT)', '2024', NULL, '10.1109/icoseit60086.2024.10497476', 'https://doi.org/10.1109/icoseit60086.2024.10497476', 4, NULL, '2026-11-13 07:21:13'),
(74, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Problems in The Adoption of Agile-Scrum Software Development Process in Small Organization: A Systematic Literature Review', 'Rahmawati Putrianasari, Eko Kuswardono Budiardjo, Kodrat Mahatma, Teguh Raharjo', 'SinkrOn', '2024', '9', '10.33395/sinkron.v9i1.13271', 'https://doi.org/10.33395/sinkron.v9i1.13271', 17, NULL, '2026-11-13 07:21:13'),
(75, 7, NULL, 'penelitian', 'jurnal_internasional_bereputasi', 'Scrum Maturity Level Evaluation and Improvement Recommendation: Case Study on ABC Application', 'Alcredo Simanjuntak, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'Jurnal RESTI (Rekayasa Sistem dan Teknologi Informasi)', '2023', '7', '10.29207/resti.v7i5.5134', 'https://doi.org/10.29207/resti.v7i5.5134', 0, NULL, '2026-11-13 07:21:13'),
(76, 7, NULL, 'penelitian', 'seminar_internasional', 'Predicting Market Manipulation in Stock Market using Supervised Machine Learning: A Case Study from the Indonesia Stock Exchange Unusual Market Activities', 'Muhammad Ghozy Ul-Haq, Betty Purwandari, Kodrat Mahatma', 'International Conference on Artificial Intelligence, Robotics, Signal and Image Processing (AIRoSIP)', '2023', NULL, '10.1109/airosip58759.2023.10874005', 'https://doi.org/10.1109/airosip58759.2023.10874005', 1, NULL, '2026-11-13 07:21:13'),
(77, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Peningkatan Kematangan Manajemen Proyek menggunakan Kerangka Kerja KPMMM: Studi Kasus Perusahaan Konsultan Teknologi Informasi PQR', 'Izza Ilma Insyifani, Teguh Raharjo, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'Technomedia Journal', '2023', '8', '10.33050/tmj.v8i1sp.2005', 'https://doi.org/10.33050/tmj.v8i1sp.2005', 9, NULL, '2026-11-13 07:21:13'),
(78, 7, NULL, 'penelitian', 'seminar_internasional', 'PREP: A Post-Requirements Effort Estimation Method in Scrum\'s Sprint Grooming', 'Gusti Raditia Madya, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Data and Software Engineering', '2022', '19', '10.1109/icodse56892.2022.9972012', 'https://doi.org/10.1109/icodse56892.2022.9972012', 3, NULL, '2026-11-13 07:21:13'),
(79, 7, NULL, 'penelitian', 'seminar_internasional', 'Impact of Remote Working During Covid-19 Pandemic on Scrum Team: Experts View on Indonesian E-Commerce Companies Case', 'Kretawiweka Nuraga Sani, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Software Engineering and Information Management (ICSIM)', '2022', NULL, '10.1145/3520084.3520089', 'https://doi.org/10.1145/3520084.3520089', 3, NULL, '2026-11-13 07:21:13'),
(80, 7, NULL, 'penelitian', 'seminar_internasional', 'Evaluation of Scrum-Based Software Development Process Maturity using the SMM and AMM: A Case of Education Technology Startup', 'Kevin Chandra Abimaulana, Eko Kuswardono Budiardjo, Kodrat Mahatma, Anita Hidayati', 'International Conference on Advanced Computer Science and Information Systems', '2021', NULL, '10.1109/icacsis53237.2021.9631308', 'https://doi.org/10.1109/icacsis53237.2021.9631308', 14, NULL, '2026-11-13 07:21:13'),
(81, 7, NULL, 'penelitian', 'seminar_internasional', 'Scrum Best Practices Recommendation: a Media and Community Startup Case Study', 'Angga Kurniawan, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Advancement in Data Science, E-learning and Information System (ICADEIS)', '2021', NULL, '10.1109/icadeis52521.2021.9701958', 'https://doi.org/10.1109/icadeis52521.2021.9701958', 7, NULL, '2026-11-13 07:21:13'),
(82, 7, NULL, 'penelitian', 'seminar_internasional', 'Improving the Quality of Requirements Engineering Process in Software Development with Agile Methods: a Case Study Telemedicine Startup XYZ', 'Adi Sukma Wibawa, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Advancement in Data Science, E-learning and Information System (ICADEIS)', '2021', NULL, '10.1109/icadeis52521.2021.9701962', 'https://doi.org/10.1109/icadeis52521.2021.9701962', 8, NULL, '2026-11-13 07:21:13'),
(83, 7, NULL, 'penelitian', 'seminar_internasional', 'Software Craftsmanship Skill using Extreme Programming for Quality Improvement: A Case of Very Small Software Organization', 'Ahmadi Ahmadi, Eko Kuswardono Budiardjo, Kodrat Mahatma', 'International Conference on Software and Computer Applications (ICSCA)', '2021', NULL, '10.1145/3457784.3457835', 'https://doi.org/10.1145/3457784.3457835', 4, NULL, '2026-11-13 07:21:13'),
(84, 7, NULL, 'penelitian', 'seminar_internasional', 'ProgArch: Modeling the Adoption of Enterprise Architecture to Manage Development Program', 'Kodrat Mahatma, Slamet Risnanto, Prima Dina Atika, Bayu Waseso', 'International Conference on ICT for Smart Society (ICISS)', '2020', '1', '10.1109/iciss50791.2020.9307593', 'https://doi.org/10.1109/iciss50791.2020.9307593', 2, NULL, '2026-11-13 07:21:13'),
(85, 7, NULL, 'penelitian', 'seminar_internasional', 'U-Vote: Ubiquitous Voting Model For General Election in Global Pandemic', 'Slamet Risnanto, Yahaya Bin Abd Rahim, Kodrat Mahatma, Asep Effendi, Hendra Garnida, Kusmadi Kusmadi', 'International Conference on Telecommunication Systems, Services, and Applications', '2020', '1', '10.1109/tssa51342.2020.9310797', 'https://doi.org/10.1109/tssa51342.2020.9310797', 0, NULL, '2026-11-13 07:21:13'),
(86, 7, NULL, 'penelitian', 'seminar_internasional', 'Social Media Based Policy Implementation Monitoring System: A Design Exploration of YouTube Posting Utilization', 'Kodrat Mahatma, Muhammad Handzalah Ridwan', 'International Congress on Applied Information Technology (AIT)', '2019', NULL, '10.1109/ait49014.2019.9144777', 'https://doi.org/10.1109/ait49014.2019.9144777', 2, NULL, '2026-11-13 07:21:13'),
(87, 7, NULL, 'penelitian', 'seminar_internasional', 'The Design and Implementation of Data Visualization for Integrated Referral and Service System', 'Kodrat Mahatma, Bayu Waseso, Wirdawati Darwin', 'International Conference on ICT for Rural Development (IC-ICTRuDev)', '2018', '18', '10.1109/icictr.2018.8706846', 'https://doi.org/10.1109/icictr.2018.8706846', 2, NULL, '2026-11-13 07:21:13'),
(88, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'Development of a GPS-Based Digital Attendance System with Attendance Validation in a Learning Management System using the Rapid Application Development Method (Case Study: Digitech University)', 'Liani Siti Lutfiah, Fadzar Rusghana, Ridha Adjie Eryadi', 'Journal of Artificial Intelligence and Engineering Applications (JAIEA)', '2026', '6', '10.59934/jaiea.v6i1.2687', 'https://doi.org/10.59934/jaiea.v6i1.2687', 0, NULL, '2026-11-13 07:27:27'),
(89, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'APLIKASI INVENTARIS DOKUMEN S.O.P UNTUK OPTIMALISASI MANAJEMEN DOKUMEN PEMERINTAH KABUPATEN BANDUNG BARAT: STUDI KASUS: DINAS KOMUNIKASI DAN STATISTIKA', 'Nita Andriyani, Novita Anggraini, Fadzar Rusghana', 'JATI (Jurnal Mahasiswa Teknik Informatika)', '2026', '10', '10.36040/jati.v10i5.19132', 'https://doi.org/10.36040/jati.v10i5.19132', 0, NULL, '2026-11-13 07:27:27'),
(90, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'SISTEM PUSH NOTIFIKASI WHATSAPP PADA PENGAJUAN SURAT DI KELURAHAN SUKAHAJI KECAMATAN BABAKAN CIPARAY', 'Hendra Lesmana, Fadzar Rusghana, Syafiuddin Usman, Novita Anggraini', 'Jurnal Informatika dan Teknik Elektro Terapan', '2026', '14', '10.23960/jitet.v14i3.11046', 'https://doi.org/10.23960/jitet.v14i3.11046', 0, NULL, '2026-11-13 07:27:27'),
(91, 7, NULL, 'penelitian', 'jurnal_nasional_terakreditasi', 'PERANCANGAN ANTARMUKA PENGGUNAAN (UI/UX) PADA APLIKASI RAPOR SISWA TK AL-HUSNA', 'Chandra Ramadhan, Harry Pribadi Fitrian, Fadzar Rusghana', 'Jurnal Informatika dan Teknik Elektro Terapan', '2025', '13', '10.23960/jitet.v13i3s1.7515', 'https://doi.org/10.23960/jitet.v13i3s1.7515', 2, NULL, '2026-11-13 07:27:27'),
(92, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Pembuatan Aplikasi Sistem Informasi Surat di Kelurahan Cigereleng Menggunakan Aplikasi Embarcadero Delphi XE 8 (Studi Kasus : Kantor Kelurahan Cigereleng)', 'Fajar Julian Aruga, Fadzar Rusghana', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2025', '3', '10.62565/tekno.v3i2.66', 'https://doi.org/10.62565/tekno.v3i2.66', 0, NULL, '2026-11-13 07:27:27'),
(93, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'INSTRUMEN USABILITY TESTING WEBSITE DALAM 5 TAHUN TERAKHIR', 'Novita Anggraini, Ridha Adjie Eryadi, Mohammad Nur Fitriyadi, Harry Pribadi Fitrian, Robby Rohman Sukarya, Fadzar Rusghana', 'TEKNO Jurnal Penelitian Teknologi dan Peradilan', '2024', '2', '10.62565/tekno.v2i2.60', 'https://doi.org/10.62565/tekno.v2i2.60', 0, NULL, '2026-11-13 07:27:27'),
(94, 7, NULL, 'penelitian', 'jurnal_nasional_tidak_terakreditasi', 'Aplikasi pengelolaan data nilai siswa berbasis website dengan PHP dan MySQL', 'Neng Sri Hardianti, Fadzar Rusghana', 'Nautical Jurnal Ilmiah Multidisiplin Indonesia', '2023', '2', '10.55904/nautical.v2i7.912', 'https://doi.org/10.55904/nautical.v2i7.912', 0, NULL, '2026-11-13 07:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `publikasi_dosen`
--

CREATE TABLE `publikasi_dosen` (
  `id` int(10) UNSIGNED NOT NULL,
  `publikasi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publikasi_dosen`
--

INSERT INTO `publikasi_dosen` (`id`, `publikasi_id`, `dosen_id`, `created_at`) VALUES
(3, 3, 1, '2026-05-24 14:27:15'),
(7, 7, 1, '2026-05-24 14:27:15'),
(8, 8, 1, '2026-05-24 14:27:15'),
(10, 10, 1, '2026-05-24 14:27:15'),
(11, 11, 1, '2026-05-24 14:27:15'),
(12, 12, 1, '2026-05-24 14:27:15'),
(13, 13, 1, '2026-05-24 14:27:15'),
(14, 14, 1, '2026-05-24 14:27:15'),
(15, 15, 1, '2026-05-24 14:27:15'),
(16, 16, 1, '2026-05-24 14:27:15'),
(17, 17, 1, '2026-05-24 14:27:15'),
(18, 18, 1, '2026-05-24 14:27:15'),
(19, 19, 1, '2026-05-24 14:27:15'),
(20, 20, 1, '2026-05-24 14:27:15'),
(21, 21, 1, '2026-05-24 14:27:15'),
(22, 22, 1, '2026-05-24 14:27:15'),
(23, 23, 1, '2026-05-24 14:27:15'),
(24, 24, 1, '2026-05-24 14:27:15'),
(25, 25, 1, '2026-05-24 14:27:15'),
(26, 26, 1, '2026-05-24 14:27:15'),
(27, 27, 1, '2026-05-24 14:27:15'),
(28, 28, 1, '2026-05-24 14:27:15'),
(29, 29, 1, '2026-05-24 14:27:15'),
(30, 30, 1, '2026-05-24 14:27:15'),
(31, 31, 1, '2026-05-24 14:27:15'),
(32, 32, 1, '2026-05-24 14:27:15'),
(33, 33, 1, '2026-05-24 14:27:15'),
(34, 34, 1, '2026-05-24 14:27:15'),
(35, 35, 1, '2026-05-24 14:27:15'),
(36, 36, 1, '2026-05-24 14:27:15'),
(37, 37, 2, '2026-05-24 14:55:53'),
(38, 38, 2, '2026-05-24 14:56:06'),
(39, 43, 3, '2026-05-24 14:56:16'),
(40, 39, 1, '2026-05-24 14:56:30'),
(41, 39, 2, '2026-05-24 14:56:30'),
(42, 42, 2, '2026-05-24 14:56:52'),
(43, 44, 3, '2026-05-24 14:57:11'),
(44, 41, 2, '2026-05-24 14:57:24'),
(45, 40, 2, '2026-05-24 14:57:37'),
(46, 2, 1, '2026-05-24 14:58:14'),
(47, 2, 2, '2026-05-24 14:58:14'),
(48, 4, 1, '2026-05-24 14:58:32'),
(49, 4, 2, '2026-05-24 14:58:32'),
(50, 5, 1, '2026-05-24 14:59:15'),
(51, 5, 3, '2026-05-24 14:59:15'),
(52, 1, 1, '2026-05-24 15:14:01'),
(53, 1, 4, '2026-05-24 15:14:01'),
(54, 6, 1, '2026-05-24 15:14:17'),
(55, 6, 3, '2026-05-24 15:14:17'),
(56, 9, 1, '2026-05-24 15:14:44'),
(57, 9, 5, '2026-05-24 15:14:44'),
(58, 45, 4, '2026-05-30 02:24:04'),
(59, 48, 1, '2026-11-13 06:43:53'),
(60, 49, 1, '2026-11-13 06:44:03'),
(61, 47, 1, '2026-11-13 06:44:13'),
(62, 46, 1, '2026-11-13 06:44:22'),
(63, 52, 1, '2026-11-13 06:44:33'),
(64, 50, 1, '2026-11-13 06:44:42'),
(65, 51, 1, '2026-11-13 06:44:53'),
(66, 53, 2, '2026-11-13 06:55:25'),
(67, 54, 2, '2026-11-13 06:56:17'),
(68, 58, 2, '2026-11-13 06:56:36'),
(69, 56, 1, '2026-11-13 07:03:01'),
(70, 56, 2, '2026-11-13 07:03:01'),
(71, 59, 2, '2026-11-13 07:10:40'),
(72, 55, 1, '2026-11-13 07:11:01'),
(73, 55, 2, '2026-11-13 07:11:01'),
(74, 55, 5, '2026-11-13 07:11:01'),
(75, 60, 2, '2026-11-13 07:11:19'),
(76, 57, 2, '2026-11-13 07:11:27'),
(77, 63, 2, '2026-11-13 07:11:37'),
(78, 62, 2, '2026-11-13 07:11:46'),
(79, 61, 2, '2026-11-13 07:12:02'),
(80, 64, 2, '2026-11-13 07:12:12'),
(81, 65, 2, '2026-11-13 07:12:24'),
(82, 66, 4, '2026-11-13 07:21:47'),
(83, 67, 4, '2026-11-13 07:22:01'),
(84, 68, 4, '2026-11-13 07:22:10'),
(85, 69, 4, '2026-11-13 07:22:19'),
(86, 70, 4, '2026-11-13 07:22:30'),
(87, 73, 4, '2026-11-13 07:22:42'),
(88, 72, 4, '2026-11-13 07:22:54'),
(89, 74, 4, '2026-11-13 07:23:05'),
(90, 71, 4, '2026-11-13 07:23:16'),
(91, 77, 4, '2026-11-13 07:23:26'),
(92, 76, 4, '2026-11-13 07:23:36'),
(93, 75, 4, '2026-11-13 07:23:45'),
(94, 79, 4, '2026-11-13 07:23:54'),
(95, 78, 4, '2026-11-13 07:24:05'),
(96, 80, 4, '2026-11-13 07:24:15'),
(97, 82, 4, '2026-11-13 07:24:28'),
(98, 81, 4, '2026-11-13 07:24:38'),
(99, 83, 4, '2026-11-13 07:24:48'),
(100, 84, 4, '2026-11-13 07:25:06'),
(101, 85, 4, '2026-11-13 07:25:15'),
(102, 86, 4, '2026-11-13 07:25:25'),
(103, 87, 4, '2026-11-13 07:25:34'),
(104, 89, 5, '2026-11-13 07:27:34'),
(106, 90, 5, '2026-11-13 07:27:54'),
(107, 92, 5, '2026-11-13 07:28:10'),
(108, 91, 5, '2026-11-13 07:28:20'),
(109, 93, 5, '2026-11-13 07:28:32'),
(110, 94, 5, '2026-11-13 07:28:41'),
(111, 88, 5, '2026-11-13 07:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `rekognisi_dosen`
--

CREATE TABLE `rekognisi_dosen` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `jenis` varchar(40) NOT NULL,
  `judul` varchar(250) NOT NULL,
  `penyelenggara` varchar(200) DEFAULT NULL,
  `tingkat` enum('lokal','nasional','internasional') NOT NULL DEFAULT 'nasional',
  `tahun` int(11) DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekognisi_dosen`
--

INSERT INTO `rekognisi_dosen` (`id`, `prodi_id`, `dosen_id`, `tahun_akademik_id`, `jenis`, `judul`, `penyelenggara`, `tingkat`, `tahun`, `link_bukti`, `created_at`) VALUES
(1, 7, 1, 5, 'editor_jurnal', 'SK PENGANGKATAN TIM PENGELOLA JURNAL PENELITIAN TEKNOLOGI DAN PERADILAN (TEKNO) PADA PENGADILAN NEGERI BALE BANDUNG KELAS 1 A', 'Pengadilan Negeri Bale Bandung', 'nasional', NULL, 'b49c5bd1e54e8e096.pdf', '2026-05-29 08:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `rekognisi_lulusan`
--

CREATE TABLE `rekognisi_lulusan` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED DEFAULT NULL,
  `sumber` varchar(50) NOT NULL,
  `jenis_pengakuan` varchar(250) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sarpras`
--

CREATE TABLE `sarpras` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
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
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spmi_auditor`
--

CREATE TABLE `spmi_auditor` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `tersertifikasi` tinyint(1) NOT NULL DEFAULT 0,
  `no_sertifikat` varchar(100) DEFAULT NULL,
  `lembaga` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spmi_unit`
--

CREATE TABLE `spmi_unit` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `nama_unit` varchar(150) NOT NULL,
  `dokumen_spmi` varchar(200) DEFAULT NULL,
  `jml_auditor` int(11) NOT NULL DEFAULT 0,
  `auditor_certified` int(11) NOT NULL DEFAULT 0,
  `auditor_non_certified` int(11) NOT NULL DEFAULT 0,
  `frekuensi_audit` int(11) NOT NULL DEFAULT 0,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `standar_mutu`
--

CREATE TABLE `standar_mutu` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `kategori` enum('sndikti','melampaui') NOT NULL DEFAULT 'sndikti',
  `kode` varchar(50) DEFAULT NULL,
  `nama` varchar(250) NOT NULL,
  `pernyataan` text DEFAULT NULL,
  `indikator` text DEFAULT NULL,
  `target` varchar(250) DEFAULT NULL,
  `capaian` varchar(250) DEFAULT NULL,
  `status` enum('tercapai','sebagian','belum') NOT NULL DEFAULT 'belum',
  `link_bukti` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_tugas_meta`
--

CREATE TABLE `surat_tugas_meta` (
  `id` int(10) UNSIGNED NOT NULL,
  `dosen_id` int(10) UNSIGNED NOT NULL,
  `tahun_akademik_id` int(10) UNSIGNED NOT NULL,
  `nomor` varchar(80) DEFAULT NULL,
  `tanggal` varchar(20) DEFAULT NULL,
  `kota` varchar(80) DEFAULT NULL,
  `dasar` text DEFAULT NULL,
  `signer_nama` varchar(150) DEFAULT NULL,
  `signer_nip` varchar(30) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_tugas_meta`
--

INSERT INTO `surat_tugas_meta` (`id`, `dosen_id`, `tahun_akademik_id`, `nomor`, `tanggal`, `kota`, `dasar`, `signer_nama`, `signer_nip`, `updated_at`) VALUES
(1, 1, 5, '003/UTD/SB/3.2.4-INF/II/2026', '27-02-2026', 'Bandung', '', 'Ir. Mamok Andri Senubekti, S.Kom., M.Kom.', '8849970018', '2026-05-29 01:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `survei`
--

CREATE TABLE `survei` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `target` enum('mahasiswa','dosen','tendik','mitra','pengguna_lulusan') NOT NULL,
  `periode` varchar(30) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `mulai` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `pertanyaan_json` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survei_response`
--

CREATE TABLE `survei_response` (
  `id` int(10) UNSIGNED NOT NULL,
  `survei_id` int(10) UNSIGNED NOT NULL,
  `responden` varchar(150) DEFAULT NULL,
  `jawaban_json` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sync_log`
--

CREATE TABLE `sync_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `sumber` varchar(30) NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `status` enum('sukses','gagal','sebagian') NOT NULL,
  `detail` longtext DEFAULT NULL,
  `jumlah_diproses` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `tahun_mulai` year(4) NOT NULL,
  `tahun_akhir` year(4) NOT NULL,
  `semester` enum('ganjil','genap','tahunan') NOT NULL DEFAULT 'tahunan',
  `is_ts` tinyint(1) NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id`, `kode`, `tahun_mulai`, `tahun_akhir`, `semester`, `is_ts`, `aktif`) VALUES
(1, '2021/2022', '2021', '2022', 'tahunan', 0, 1),
(2, '2022/2023', '2022', '2023', 'tahunan', 0, 1),
(3, '2023/2024', '2023', '2024', 'tahunan', 0, 1),
(4, '2024/2025', '2024', '2025', 'tahunan', 0, 1),
(5, '2025/2026', '2025', '2026', 'tahunan', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tata_kelola_si`
--

CREATE TABLE `tata_kelola_si` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `nama_sistem` varchar(200) NOT NULL,
  `akses` varchar(30) DEFAULT NULL,
  `unit_pengelola` varchar(150) DEFAULT NULL,
  `link_bukti` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tata_pamong`
--

CREATE TABLE `tata_pamong` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `unit_kerja` varchar(150) NOT NULL,
  `nama_pejabat` varchar(150) NOT NULL,
  `jabatan` varchar(150) DEFAULT NULL,
  `periode` varchar(50) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `jabatan_fungsional` varchar(100) DEFAULT NULL,
  `tupoksi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tendik`
--

CREATE TABLE `tendik` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis` enum('pustakawan','laboran','teknisi','operator','administrasi','lainnya') NOT NULL,
  `pendidikan_terakhir` enum('SMA','D1','D2','D3','D4','S1','S2','S3') DEFAULT NULL,
  `sertifikat_kompetensi` varchar(200) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tendik`
--

INSERT INTO `tendik` (`id`, `prodi_id`, `nip`, `nama`, `jenis`, `pendidikan_terakhir`, `sertifikat_kompetensi`, `aktif`) VALUES
(1, 7, NULL, 'Muhammad Yusron Fathul Islam, A.md.Kom.', 'laboran', 'D3', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tinjauan_manajemen`
--

CREATE TABLE `tinjauan_manajemen` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `agenda` varchar(250) NOT NULL,
  `keputusan` text DEFAULT NULL,
  `penanggung_jawab` varchar(150) DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `status` enum('terbuka','proses','selesai') NOT NULL DEFAULT 'terbuka',
  `link_bukti` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracer_study`
--

CREATE TABLE `tracer_study` (
  `id` int(10) UNSIGNED NOT NULL,
  `mahasiswa_id` int(10) UNSIGNED NOT NULL,
  `tgl_isi` date DEFAULT NULL,
  `masa_tunggu_bulan` int(11) DEFAULT NULL,
  `kesesuaian_bidang_kerja` enum('sangat_sesuai','sesuai','kurang_sesuai','tidak_sesuai') DEFAULT NULL,
  `tingkat_pekerjaan` enum('lokal','nasional','multinasional','internasional','wirausaha') DEFAULT NULL,
  `nama_perusahaan` varchar(200) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `gaji_pertama` bigint(20) DEFAULT NULL,
  `lanjut_studi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upps`
--

CREATE TABLE `upps` (
  `id` int(10) UNSIGNED NOT NULL,
  `perguruan_tinggi_id` int(10) UNSIGNED NOT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(200) NOT NULL,
  `jenis` enum('fakultas','sekolah','jurusan','departemen','direktorat','tidak_ada') NOT NULL DEFAULT 'tidak_ada',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upps`
--

INSERT INTO `upps` (`id`, `perguruan_tinggi_id`, `kode`, `nama`, `jenis`, `created_at`, `updated_at`) VALUES
(1, 1, '-', '-', 'tidak_ada', '2026-05-22 01:13:44', '2026-05-22 01:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'superadmin', NULL, NULL, 0, '2026-06-03 07:02:24', '2026-05-22 01:13:44', '2026-05-22 01:13:44', NULL),
(2, 'kaprodi', NULL, NULL, 0, NULL, '2026-05-22 01:13:45', '2026-05-22 01:13:45', NULL),
(3, 'sekprodi', NULL, NULL, 0, NULL, '2026-05-22 01:13:45', '2026-05-22 01:13:45', NULL),
(4, 'gkm', NULL, NULL, 0, NULL, '2026-05-22 01:13:46', '2026-05-22 01:13:46', NULL),
(5, 'dosen', NULL, NULL, 0, NULL, '2026-05-22 01:13:46', '2026-05-22 01:13:46', NULL),
(6, 'tendik', NULL, NULL, 0, NULL, '2026-05-22 01:13:47', '2026-05-22 01:13:47', NULL),
(7, 'mahasiswa', NULL, NULL, 0, NULL, '2026-05-22 01:13:47', '2026-05-22 01:13:47', NULL),
(8, 'asesor', NULL, NULL, 0, NULL, '2026-05-22 01:13:48', '2026-05-22 01:13:48', NULL),
(9, 'auditor', NULL, NULL, 0, NULL, '2026-05-22 01:13:48', '2026-05-22 01:13:48', NULL),
(10, 'mamokandri@digitechuniversity.', NULL, NULL, 0, '2026-05-31 02:56:29', '2026-05-24 13:32:54', '2026-05-24 13:32:54', NULL),
(11, 'informatika-d3@digitechunivers', NULL, NULL, 0, '2026-11-13 07:29:54', '2026-05-24 13:34:59', '2026-05-24 13:34:59', NULL),
(12, '0424098204', NULL, NULL, 0, '2026-05-24 15:03:35', '2026-05-24 14:01:07', '2026-05-24 14:01:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_prodi`
--

CREATE TABLE `user_prodi` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `peran` varchar(50) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_prodi`
--

INSERT INTO `user_prodi` (`id`, `user_id`, `prodi_id`, `peran`, `is_default`, `created_at`) VALUES
(1, 2, 7, 'kaprodi', 1, '2026-05-22 01:13:45'),
(2, 3, 7, 'sekprodi', 1, '2026-05-22 01:13:46'),
(3, 4, 7, 'gkm', 1, '2026-05-22 01:13:46'),
(4, 5, 7, 'dosen', 1, '2026-05-22 01:13:47'),
(5, 6, 7, 'tendik', 1, '2026-05-22 01:13:47'),
(6, 7, 7, 'mahasiswa', 1, '2026-05-22 01:13:48'),
(9, 12, 7, 'dosen', 1, NULL),
(10, 11, 7, 'kaprodi', 1, '2026-05-25 11:04:03'),
(11, 10, 7, 'dosen', 1, '2026-05-26 01:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `vmts`
--

CREATE TABLE `vmts` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_id` int(10) UNSIGNED NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `tujuan` text NOT NULL,
  `strategi` text DEFAULT NULL,
  `no_sk` varchar(100) DEFAULT NULL,
  `tgl_sk` date DEFAULT NULL,
  `berlaku_mulai` date DEFAULT NULL,
  `berlaku_sampai` date DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ami_temuan`
--
ALTER TABLE `ami_temuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ami_temuan_prodi_id_foreign` (`prodi_id`),
  ADD KEY `ami_temuan_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `butir_skor`
--
ALTER TABLE `butir_skor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_lam_butir_id_tahun_akademik_id` (`prodi_id`,`lam_butir_id`,`tahun_akademik_id`),
  ADD KEY `idx_butir_skor_prodi_id` (`prodi_id`),
  ADD KEY `idx_butir_skor_lam_butir_id` (`lam_butir_id`),
  ADD KEY `idx_butir_skor_prodi_id_lam_butir_id` (`prodi_id`,`lam_butir_id`);

--
-- Indexes for table `cpl`
--
ALTER TABLE `cpl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kurikulum_id_kode` (`kurikulum_id`,`kode`);

--
-- Indexes for table `cpl_ketercapaian`
--
ALTER TABLE `cpl_ketercapaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliah_id_cpl_id_tahun_akademik_id` (`mata_kuliah_id`,`cpl_id`,`tahun_akademik_id`);

--
-- Indexes for table `cpmk`
--
ALTER TABLE `cpmk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cpmk_mata_kuliah_id_foreign` (`mata_kuliah_id`);

--
-- Indexes for table `dokumen_eviden`
--
ALTER TABLE `dokumen_eviden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_table_ref_id` (`ref_table`,`ref_id`);

--
-- Indexes for table `dokumen_mutu`
--
ALTER TABLE `dokumen_mutu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokumen_mutu_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_dosen_user` (`user_id`),
  ADD KEY `nidn` (`nidn`),
  ADD KEY `idx_dosen_prodi_id` (`prodi_id`),
  ADD KEY `idx_dosen_aktif` (`aktif`);

--
-- Indexes for table `dosen_ewmp`
--
ALTER TABLE `dosen_ewmp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_id_tahun_akademik_id` (`dosen_id`,`tahun_akademik_id`),
  ADD KEY `dosen_ewmp_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `evidence`
--
ALTER TABLE `evidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evidence_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `eviden_ppepp`
--
ALTER TABLE `eviden_ppepp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_lam_butir_id` (`prodi_id`,`lam_butir_id`),
  ADD KEY `eviden_ppepp_lam_butir_id_foreign` (`lam_butir_id`);

--
-- Indexes for table `eviden_ppepp_bukti`
--
ALTER TABLE `eviden_ppepp_bukti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eviden_id_fase` (`eviden_id`,`fase`);

--
-- Indexes for table `fleksibilitas`
--
ALTER TABLE `fleksibilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fleksibilitas_prodi_id_foreign` (`prodi_id`),
  ADD KEY `fleksibilitas_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `hki`
--
ALTER TABLE `hki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hki_prodi_id` (`prodi_id`),
  ADD KEY `idx_hki_tahun` (`tahun`);

--
-- Indexes for table `kepuasan_pengguna_lulusan`
--
ALTER TABLE `kepuasan_pengguna_lulusan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kepuasan_pengguna_lulusan_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `kerjasama`
--
ALTER TABLE `kerjasama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kerjasama_prodi_id` (`prodi_id`),
  ADD KEY `idx_kerjasama_tingkat` (`tingkat`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keuangan_prodi_id_foreign` (`prodi_id`),
  ADD KEY `keuangan_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `kondisi_eksternal`
--
ALTER TABLE `kondisi_eksternal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_id_kategori` (`prodi_id`,`kategori`);

--
-- Indexes for table `kondisi_eksternal_narasi`
--
ALTER TABLE `kondisi_eksternal_narasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_sub_bagian` (`prodi_id`,`sub_bagian`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kurikulum_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `lam_butir`
--
ALTER TABLE `lam_butir`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lam_kriteria_id_kode` (`lam_kriteria_id`,`kode`),
  ADD KEY `idx_lam_butir_lam_kriteria_id` (`lam_kriteria_id`),
  ADD KEY `idx_lam_butir_urutan` (`urutan`);

--
-- Indexes for table `lam_butir_rubrik`
--
ALTER TABLE `lam_butir_rubrik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lam_butir_id_skor` (`lam_butir_id`,`skor`);

--
-- Indexes for table `lam_instrumen`
--
ALTER TABLE `lam_instrumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lam_lembaga_id_jenjang_versi` (`lam_lembaga_id`,`jenjang`,`versi`);

--
-- Indexes for table `lam_kriteria`
--
ALTER TABLE `lam_kriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lam_instrumen_id_kode` (`lam_instrumen_id`,`kode`),
  ADD KEY `idx_lam_kriteria_lam_instrumen_id` (`lam_instrumen_id`),
  ADD KEY `idx_lam_kriteria_urutan` (`urutan`);

--
-- Indexes for table `lam_lembaga`
--
ALTER TABLE `lam_lembaga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `lam_tabel_lkps`
--
ALTER TABLE `lam_tabel_lkps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lam_instrumen_id_kode` (`lam_instrumen_id`,`kode`),
  ADD KEY `idx_lam_tabel_lkps_lam_instrumen_id` (`lam_instrumen_id`),
  ADD KEY `idx_lam_tabel_lkps_urutan` (`urutan`);

--
-- Indexes for table `lam_template_led`
--
ALTER TABLE `lam_template_led`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lam_template_led_lam_instrumen_id` (`lam_instrumen_id`),
  ADD KEY `idx_lam_template_led_lam_kriteria_id` (`lam_kriteria_id`),
  ADD KEY `idx_lam_template_led_urutan` (`urutan`);

--
-- Indexes for table `led_narasi`
--
ALTER TABLE `led_narasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_lam_template_led_id` (`prodi_id`,`lam_template_led_id`),
  ADD KEY `led_narasi_lam_template_led_id_foreign` (`lam_template_led_id`),
  ADD KEY `idx_led_narasi_prodi_id` (`prodi_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_nim` (`prodi_id`,`nim`),
  ADD KEY `mahasiswa_tahun_masuk_id_foreign` (`tahun_masuk_id`),
  ADD KEY `idx_mahasiswa_prodi_id` (`prodi_id`),
  ADD KEY `idx_mahasiswa_status` (`status`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kurikulum_id_kode` (`kurikulum_id`,`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mk_cpl`
--
ALTER TABLE `mk_cpl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliah_id_cpl_id` (`mata_kuliah_id`,`cpl_id`),
  ADD KEY `mk_cpl_cpl_id_foreign` (`cpl_id`);

--
-- Indexes for table `penelitian`
--
ALTER TABLE `penelitian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_penelitian_prodi_id` (`prodi_id`),
  ADD KEY `idx_penelitian_tahun_pelaksanaan` (`tahun_pelaksanaan`),
  ADD KEY `idx_penelitian_ketua_dosen_id` (`ketua_dosen_id`);

--
-- Indexes for table `penelitian_anggota`
--
ALTER TABLE `penelitian_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_penelitian_anggota_penelitian_id` (`penelitian_id`),
  ADD KEY `idx_penelitian_anggota_dosen_id` (`dosen_id`);

--
-- Indexes for table `pengampu_mk`
--
ALTER TABLE `pengampu_mk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengampu_mk_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `pengampu_mk_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `dosen_id_tahun_akademik_id` (`dosen_id`,`tahun_akademik_id`);

--
-- Indexes for table `pengembangan_dtpr`
--
ALTER TABLE `pengembangan_dtpr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengembangan_dtpr_prodi_id_foreign` (`prodi_id`),
  ADD KEY `pengembangan_dtpr_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `perguruan_tinggi`
--
ALTER TABLE `perguruan_tinggi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pkm`
--
ALTER TABLE `pkm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pkm_prodi_id` (`prodi_id`),
  ADD KEY `idx_pkm_tahun_pelaksanaan` (`tahun_pelaksanaan`),
  ADD KEY `idx_pkm_ketua_dosen_id` (`ketua_dosen_id`);

--
-- Indexes for table `pkm_anggota`
--
ALTER TABLE `pkm_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pkm_anggota_pkm_id` (`pkm_id`),
  ADD KEY `idx_pkm_anggota_dosen_id` (`dosen_id`);

--
-- Indexes for table `ppepp`
--
ALTER TABLE `ppepp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ppepp_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `prestasi_mahasiswa`
--
ALTER TABLE `prestasi_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestasi_mahasiswa_prodi_id_foreign` (`prodi_id`),
  ADD KEY `prestasi_mahasiswa_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pddikti` (`kode_pddikti`),
  ADD KEY `prodi_upps_id_foreign` (`upps_id`);

--
-- Indexes for table `prodi_instrumen`
--
ALTER TABLE `prodi_instrumen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodi_id_lam_instrumen_id` (`prodi_id`,`lam_instrumen_id`),
  ADD KEY `prodi_instrumen_lam_instrumen_id_foreign` (`lam_instrumen_id`),
  ADD KEY `idx_prodi_instrumen_prodi_id` (`prodi_id`),
  ADD KEY `idx_prodi_instrumen_aktif` (`aktif`);

--
-- Indexes for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_publikasi_prodi_id` (`prodi_id`),
  ADD KEY `idx_publikasi_kategori` (`kategori`),
  ADD KEY `idx_publikasi_tahun` (`tahun`);

--
-- Indexes for table `publikasi_dosen`
--
ALTER TABLE `publikasi_dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `publikasi_id_dosen_id` (`publikasi_id`,`dosen_id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `rekognisi_dosen`
--
ALTER TABLE `rekognisi_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekognisi_dosen_prodi_id_foreign` (`prodi_id`),
  ADD KEY `rekognisi_dosen_tahun_akademik_id_foreign` (`tahun_akademik_id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `rekognisi_lulusan`
--
ALTER TABLE `rekognisi_lulusan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rekognisi_lulusan_prodi_id_foreign` (`prodi_id`),
  ADD KEY `rekognisi_lulusan_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `sarpras`
--
ALTER TABLE `sarpras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sarpras_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spmi_auditor`
--
ALTER TABLE `spmi_auditor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spmi_auditor_prodi_id_foreign` (`prodi_id`),
  ADD KEY `spmi_auditor_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `spmi_unit`
--
ALTER TABLE `spmi_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spmi_unit_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `standar_mutu`
--
ALTER TABLE `standar_mutu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `standar_mutu_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `surat_tugas_meta`
--
ALTER TABLE `surat_tugas_meta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_id_tahun_akademik_id` (`dosen_id`,`tahun_akademik_id`),
  ADD KEY `surat_tugas_meta_tahun_akademik_id_foreign` (`tahun_akademik_id`);

--
-- Indexes for table `survei`
--
ALTER TABLE `survei`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survei_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `survei_response`
--
ALTER TABLE `survei_response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survei_response_survei_id_foreign` (`survei_id`);

--
-- Indexes for table `sync_log`
--
ALTER TABLE `sync_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_semester` (`kode`,`semester`);

--
-- Indexes for table `tata_kelola_si`
--
ALTER TABLE `tata_kelola_si`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tata_kelola_si_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `tata_pamong`
--
ALTER TABLE `tata_pamong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tata_pamong_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `tendik`
--
ALTER TABLE `tendik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tendik_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `tinjauan_manajemen`
--
ALTER TABLE `tinjauan_manajemen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tinjauan_manajemen_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `tracer_study`
--
ALTER TABLE `tracer_study`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracer_study_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `upps`
--
ALTER TABLE `upps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upps_perguruan_tinggi_id_foreign` (`perguruan_tinggi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_prodi`
--
ALTER TABLE `user_prodi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_prodi_id_peran` (`user_id`,`prodi_id`,`peran`),
  ADD KEY `user_prodi_prodi_id_foreign` (`prodi_id`);

--
-- Indexes for table `vmts`
--
ALTER TABLE `vmts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vmts_prodi_id_foreign` (`prodi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ami_temuan`
--
ALTER TABLE `ami_temuan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `butir_skor`
--
ALTER TABLE `butir_skor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cpl`
--
ALTER TABLE `cpl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpl_ketercapaian`
--
ALTER TABLE `cpl_ketercapaian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cpmk`
--
ALTER TABLE `cpmk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumen_eviden`
--
ALTER TABLE `dokumen_eviden`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dokumen_mutu`
--
ALTER TABLE `dokumen_mutu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dosen_ewmp`
--
ALTER TABLE `dosen_ewmp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evidence`
--
ALTER TABLE `evidence`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eviden_ppepp`
--
ALTER TABLE `eviden_ppepp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `eviden_ppepp_bukti`
--
ALTER TABLE `eviden_ppepp_bukti`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `fleksibilitas`
--
ALTER TABLE `fleksibilitas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hki`
--
ALTER TABLE `hki`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kepuasan_pengguna_lulusan`
--
ALTER TABLE `kepuasan_pengguna_lulusan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kerjasama`
--
ALTER TABLE `kerjasama`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kondisi_eksternal`
--
ALTER TABLE `kondisi_eksternal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `kondisi_eksternal_narasi`
--
ALTER TABLE `kondisi_eksternal_narasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lam_butir`
--
ALTER TABLE `lam_butir`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=493;

--
-- AUTO_INCREMENT for table `lam_butir_rubrik`
--
ALTER TABLE `lam_butir_rubrik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1969;

--
-- AUTO_INCREMENT for table `lam_instrumen`
--
ALTER TABLE `lam_instrumen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lam_kriteria`
--
ALTER TABLE `lam_kriteria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `lam_lembaga`
--
ALTER TABLE `lam_lembaga`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lam_tabel_lkps`
--
ALTER TABLE `lam_tabel_lkps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `lam_template_led`
--
ALTER TABLE `lam_template_led`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `led_narasi`
--
ALTER TABLE `led_narasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `mk_cpl`
--
ALTER TABLE `mk_cpl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penelitian`
--
ALTER TABLE `penelitian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penelitian_anggota`
--
ALTER TABLE `penelitian_anggota`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengampu_mk`
--
ALTER TABLE `pengampu_mk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengembangan_dtpr`
--
ALTER TABLE `pengembangan_dtpr`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perguruan_tinggi`
--
ALTER TABLE `perguruan_tinggi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pkm`
--
ALTER TABLE `pkm`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pkm_anggota`
--
ALTER TABLE `pkm_anggota`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppepp`
--
ALTER TABLE `ppepp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestasi_mahasiswa`
--
ALTER TABLE `prestasi_mahasiswa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `prodi_instrumen`
--
ALTER TABLE `prodi_instrumen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `publikasi`
--
ALTER TABLE `publikasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `publikasi_dosen`
--
ALTER TABLE `publikasi_dosen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `rekognisi_dosen`
--
ALTER TABLE `rekognisi_dosen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rekognisi_lulusan`
--
ALTER TABLE `rekognisi_lulusan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sarpras`
--
ALTER TABLE `sarpras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spmi_auditor`
--
ALTER TABLE `spmi_auditor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spmi_unit`
--
ALTER TABLE `spmi_unit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `standar_mutu`
--
ALTER TABLE `standar_mutu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_tugas_meta`
--
ALTER TABLE `surat_tugas_meta`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `survei`
--
ALTER TABLE `survei`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survei_response`
--
ALTER TABLE `survei_response`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sync_log`
--
ALTER TABLE `sync_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tata_kelola_si`
--
ALTER TABLE `tata_kelola_si`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tata_pamong`
--
ALTER TABLE `tata_pamong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tendik`
--
ALTER TABLE `tendik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tinjauan_manajemen`
--
ALTER TABLE `tinjauan_manajemen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracer_study`
--
ALTER TABLE `tracer_study`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upps`
--
ALTER TABLE `upps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_prodi`
--
ALTER TABLE `user_prodi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vmts`
--
ALTER TABLE `vmts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ami_temuan`
--
ALTER TABLE `ami_temuan`
  ADD CONSTRAINT `ami_temuan_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ami_temuan_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `butir_skor`
--
ALTER TABLE `butir_skor`
  ADD CONSTRAINT `butir_skor_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `butir_skor_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cpl`
--
ALTER TABLE `cpl`
  ADD CONSTRAINT `cpl_kurikulum_id_foreign` FOREIGN KEY (`kurikulum_id`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cpmk`
--
ALTER TABLE `cpmk`
  ADD CONSTRAINT `cpmk_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dokumen_mutu`
--
ALTER TABLE `dokumen_mutu`
  ADD CONSTRAINT `dokumen_mutu_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dosen_ewmp`
--
ALTER TABLE `dosen_ewmp`
  ADD CONSTRAINT `dosen_ewmp_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dosen_ewmp_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evidence`
--
ALTER TABLE `evidence`
  ADD CONSTRAINT `evidence_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eviden_ppepp`
--
ALTER TABLE `eviden_ppepp`
  ADD CONSTRAINT `eviden_ppepp_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `eviden_ppepp_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eviden_ppepp_bukti`
--
ALTER TABLE `eviden_ppepp_bukti`
  ADD CONSTRAINT `eviden_ppepp_bukti_eviden_id_foreign` FOREIGN KEY (`eviden_id`) REFERENCES `eviden_ppepp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fleksibilitas`
--
ALTER TABLE `fleksibilitas`
  ADD CONSTRAINT `fleksibilitas_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fleksibilitas_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `hki`
--
ALTER TABLE `hki`
  ADD CONSTRAINT `hki_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kepuasan_pengguna_lulusan`
--
ALTER TABLE `kepuasan_pengguna_lulusan`
  ADD CONSTRAINT `kepuasan_pengguna_lulusan_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kerjasama`
--
ALTER TABLE `kerjasama`
  ADD CONSTRAINT `kerjasama_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `keuangan_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keuangan_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kondisi_eksternal`
--
ALTER TABLE `kondisi_eksternal`
  ADD CONSTRAINT `kondisi_eksternal_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kondisi_eksternal_narasi`
--
ALTER TABLE `kondisi_eksternal_narasi`
  ADD CONSTRAINT `kondisi_eksternal_narasi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD CONSTRAINT `kurikulum_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_butir`
--
ALTER TABLE `lam_butir`
  ADD CONSTRAINT `lam_butir_lam_kriteria_id_foreign` FOREIGN KEY (`lam_kriteria_id`) REFERENCES `lam_kriteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_butir_rubrik`
--
ALTER TABLE `lam_butir_rubrik`
  ADD CONSTRAINT `lam_butir_rubrik_lam_butir_id_foreign` FOREIGN KEY (`lam_butir_id`) REFERENCES `lam_butir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_instrumen`
--
ALTER TABLE `lam_instrumen`
  ADD CONSTRAINT `lam_instrumen_lam_lembaga_id_foreign` FOREIGN KEY (`lam_lembaga_id`) REFERENCES `lam_lembaga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_kriteria`
--
ALTER TABLE `lam_kriteria`
  ADD CONSTRAINT `lam_kriteria_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_tabel_lkps`
--
ALTER TABLE `lam_tabel_lkps`
  ADD CONSTRAINT `lam_tabel_lkps_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lam_template_led`
--
ALTER TABLE `lam_template_led`
  ADD CONSTRAINT `lam_template_led_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lam_template_led_lam_kriteria_id_foreign` FOREIGN KEY (`lam_kriteria_id`) REFERENCES `lam_kriteria` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `led_narasi`
--
ALTER TABLE `led_narasi`
  ADD CONSTRAINT `led_narasi_lam_template_led_id_foreign` FOREIGN KEY (`lam_template_led_id`) REFERENCES `lam_template_led` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `led_narasi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswa_tahun_masuk_id_foreign` FOREIGN KEY (`tahun_masuk_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_kurikulum_id_foreign` FOREIGN KEY (`kurikulum_id`) REFERENCES `kurikulum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mk_cpl`
--
ALTER TABLE `mk_cpl`
  ADD CONSTRAINT `mk_cpl_cpl_id_foreign` FOREIGN KEY (`cpl_id`) REFERENCES `cpl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mk_cpl_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penelitian`
--
ALTER TABLE `penelitian`
  ADD CONSTRAINT `penelitian_ketua_dosen_id_foreign` FOREIGN KEY (`ketua_dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `penelitian_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penelitian_anggota`
--
ALTER TABLE `penelitian_anggota`
  ADD CONSTRAINT `penelitian_anggota_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penelitian_anggota_penelitian_id_foreign` FOREIGN KEY (`penelitian_id`) REFERENCES `penelitian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengampu_mk`
--
ALTER TABLE `pengampu_mk`
  ADD CONSTRAINT `pengampu_mk_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengampu_mk_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `pengampu_mk_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengembangan_dtpr`
--
ALTER TABLE `pengembangan_dtpr`
  ADD CONSTRAINT `pengembangan_dtpr_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengembangan_dtpr_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `pkm`
--
ALTER TABLE `pkm`
  ADD CONSTRAINT `pkm_ketua_dosen_id_foreign` FOREIGN KEY (`ketua_dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `pkm_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pkm_anggota`
--
ALTER TABLE `pkm_anggota`
  ADD CONSTRAINT `pkm_anggota_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pkm_anggota_pkm_id_foreign` FOREIGN KEY (`pkm_id`) REFERENCES `pkm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ppepp`
--
ALTER TABLE `ppepp`
  ADD CONSTRAINT `ppepp_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prestasi_mahasiswa`
--
ALTER TABLE `prestasi_mahasiswa`
  ADD CONSTRAINT `prestasi_mahasiswa_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `prestasi_mahasiswa_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_upps_id_foreign` FOREIGN KEY (`upps_id`) REFERENCES `upps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prodi_instrumen`
--
ALTER TABLE `prodi_instrumen`
  ADD CONSTRAINT `prodi_instrumen_lam_instrumen_id_foreign` FOREIGN KEY (`lam_instrumen_id`) REFERENCES `lam_instrumen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prodi_instrumen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD CONSTRAINT `publikasi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekognisi_dosen`
--
ALTER TABLE `rekognisi_dosen`
  ADD CONSTRAINT `rekognisi_dosen_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekognisi_dosen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekognisi_dosen_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `rekognisi_lulusan`
--
ALTER TABLE `rekognisi_lulusan`
  ADD CONSTRAINT `rekognisi_lulusan_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rekognisi_lulusan_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `sarpras`
--
ALTER TABLE `sarpras`
  ADD CONSTRAINT `sarpras_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spmi_auditor`
--
ALTER TABLE `spmi_auditor`
  ADD CONSTRAINT `spmi_auditor_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `spmi_auditor_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spmi_unit`
--
ALTER TABLE `spmi_unit`
  ADD CONSTRAINT `spmi_unit_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `standar_mutu`
--
ALTER TABLE `standar_mutu`
  ADD CONSTRAINT `standar_mutu_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `surat_tugas_meta`
--
ALTER TABLE `surat_tugas_meta`
  ADD CONSTRAINT `surat_tugas_meta_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `surat_tugas_meta_tahun_akademik_id_foreign` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survei`
--
ALTER TABLE `survei`
  ADD CONSTRAINT `survei_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survei_response`
--
ALTER TABLE `survei_response`
  ADD CONSTRAINT `survei_response_survei_id_foreign` FOREIGN KEY (`survei_id`) REFERENCES `survei` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tata_kelola_si`
--
ALTER TABLE `tata_kelola_si`
  ADD CONSTRAINT `tata_kelola_si_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tata_pamong`
--
ALTER TABLE `tata_pamong`
  ADD CONSTRAINT `tata_pamong_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tendik`
--
ALTER TABLE `tendik`
  ADD CONSTRAINT `tendik_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tinjauan_manajemen`
--
ALTER TABLE `tinjauan_manajemen`
  ADD CONSTRAINT `tinjauan_manajemen_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tracer_study`
--
ALTER TABLE `tracer_study`
  ADD CONSTRAINT `tracer_study_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `upps`
--
ALTER TABLE `upps`
  ADD CONSTRAINT `upps_perguruan_tinggi_id_foreign` FOREIGN KEY (`perguruan_tinggi_id`) REFERENCES `perguruan_tinggi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_prodi`
--
ALTER TABLE `user_prodi`
  ADD CONSTRAINT `user_prodi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vmts`
--
ALTER TABLE `vmts`
  ADD CONSTRAINT `vmts_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
