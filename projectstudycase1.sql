-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2026 at 06:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectstudycase1`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `activity`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 6, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-20 18:45:27', '2026-09-20 18:45:27'),
(2, 1, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:37:17', '2026-09-21 18:37:17'),
(3, 1, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:37:42', '2026-09-21 18:37:42'),
(4, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:37:48', '2026-09-21 18:37:48'),
(5, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:38:01', '2026-09-21 18:38:01'),
(6, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:38:08', '2026-09-21 18:38:08'),
(7, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:38:14', '2026-09-21 18:38:14'),
(8, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:38:19', '2026-09-21 18:38:19'),
(9, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:42:47', '2026-09-21 18:42:47'),
(10, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:42:53', '2026-09-21 18:42:53'),
(11, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:43:41', '2026-09-21 18:43:41'),
(12, 6, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:43:48', '2026-09-21 18:43:48'),
(13, 6, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:44:18', '2026-09-21 18:44:18'),
(14, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:44:24', '2026-09-21 18:44:24'),
(15, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:48:07', '2026-09-21 18:48:07'),
(16, 6, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:48:15', '2026-09-21 18:48:15'),
(17, 6, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:49:26', '2026-09-21 18:49:26'),
(18, 6, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:49:32', '2026-09-21 18:49:32'),
(19, 6, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:50:23', '2026-09-21 18:50:23'),
(20, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:50:29', '2026-09-21 18:50:29'),
(21, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 18:51:17', '2026-09-21 18:51:17'),
(22, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 18:51:22', '2026-09-21 18:51:22'),
(23, 2, 'Pengajuan RAB RAB-2026-001 berhasil dibuat dengan status Menunggu Verifikasi Finance.', '127.0.0.1', '2026-09-21 18:52:54', '2026-09-21 18:52:54'),
(24, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 19:59:15', '2026-09-21 19:59:15'),
(25, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 19:59:30', '2026-09-21 19:59:30'),
(26, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:00:00', '2026-09-21 20:00:00'),
(27, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:00:06', '2026-09-21 20:00:06'),
(28, 5, 'Melakukan verifikasi Tahap 1 (Finance) pada Pengajuan RAB #1 dengan keputusan Ditolak.', '127.0.0.1', '2026-09-21 20:00:43', '2026-09-21 20:00:43'),
(29, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:00:48', '2026-09-21 20:00:48'),
(30, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:00:55', '2026-09-21 20:00:55'),
(31, 2, 'Pengajuan RAB RAB-2026-002 berhasil dibuat dengan status Menunggu Verifikasi Finance.', '127.0.0.1', '2026-09-21 20:02:42', '2026-09-21 20:02:42'),
(32, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:03:09', '2026-09-21 20:03:09'),
(33, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:03:18', '2026-09-21 20:03:18'),
(34, 5, 'Melakukan verifikasi Tahap 1 (Finance) pada Pengajuan RAB #2 dengan keputusan ACC.', '127.0.0.1', '2026-09-21 20:05:13', '2026-09-21 20:05:13'),
(35, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:07:09', '2026-09-21 20:07:09'),
(36, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:07:14', '2026-09-21 20:07:14'),
(37, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:17:45', '2026-09-21 20:17:45'),
(38, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:17:50', '2026-09-21 20:17:50'),
(39, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:18:02', '2026-09-21 20:18:02'),
(40, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:18:05', '2026-09-21 20:18:05'),
(41, 5, 'Melakukan verifikasi Final (Atas nama Pimpinan) pada Pengajuan RAB #2 dengan keputusan Revisi.', '127.0.0.1', '2026-09-21 20:21:18', '2026-09-21 20:21:18'),
(42, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:21:23', '2026-09-21 20:21:23'),
(43, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:21:28', '2026-09-21 20:21:28'),
(44, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:21:42', '2026-09-21 20:21:42'),
(45, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:21:46', '2026-09-21 20:21:46'),
(46, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:21:58', '2026-09-21 20:21:58'),
(47, 1, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:22:02', '2026-09-21 20:22:02'),
(48, 1, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:22:05', '2026-09-21 20:22:05'),
(49, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:22:11', '2026-09-21 20:22:11'),
(50, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:30:43', '2026-09-21 20:30:43'),
(51, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:31:19', '2026-09-21 20:31:19'),
(52, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:32:11', '2026-09-21 20:32:11'),
(53, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:32:16', '2026-09-21 20:32:16'),
(54, 5, 'Melakukan verifikasi Tahap 1 (Finance) pada Pengajuan RAB #2 dengan keputusan Revisi.', '127.0.0.1', '2026-09-21 20:33:01', '2026-09-21 20:33:01'),
(55, 5, 'Melakukan verifikasi Tahap 1 (Finance) pada Pengajuan RAB #1 dengan keputusan ACC.', '127.0.0.1', '2026-09-21 20:33:12', '2026-09-21 20:33:12'),
(56, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:33:20', '2026-09-21 20:33:20'),
(57, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:33:27', '2026-09-21 20:33:27'),
(58, 2, 'Pengajuan RAB RAB-2026-003 berhasil dibuat dengan status Menunggu Verifikasi Finance.', '127.0.0.1', '2026-09-21 20:35:48', '2026-09-21 20:35:48'),
(59, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:52:00', '2026-09-21 20:52:00'),
(60, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:52:20', '2026-09-21 20:52:20'),
(61, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 20:59:48', '2026-09-21 20:59:48'),
(62, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 20:59:52', '2026-09-21 20:59:52'),
(63, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 21:16:06', '2026-09-21 21:16:06'),
(64, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 21:16:17', '2026-09-21 21:16:17'),
(65, 1, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 21:19:25', '2026-09-21 21:19:25'),
(66, 1, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 21:19:43', '2026-09-21 21:19:43'),
(67, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 21:19:50', '2026-09-21 21:19:50'),
(68, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 21:51:44', '2026-09-21 21:51:44'),
(69, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 21:51:49', '2026-09-21 21:51:49'),
(70, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 22:18:09', '2026-09-21 22:18:09'),
(71, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 22:19:20', '2026-09-21 22:19:20'),
(72, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 22:19:25', '2026-09-21 22:19:25'),
(73, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 22:19:33', '2026-09-21 22:19:33'),
(74, 5, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 22:19:38', '2026-09-21 22:19:38'),
(75, 5, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 22:26:15', '2026-09-21 22:26:15'),
(76, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 22:26:21', '2026-09-21 22:26:21'),
(77, 2, 'Logout dari sistem.', '127.0.0.1', '2026-09-21 22:38:56', '2026-09-21 22:38:56'),
(78, 2, 'Berhasil login ke dalam sistem.', '127.0.0.1', '2026-09-21 22:39:04', '2026-09-21 22:39:04');

-- --------------------------------------------------------

--
-- Table structure for table `alur_persetujuan`
--

CREATE TABLE `alur_persetujuan` (
  `id_persetujuan` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `id_reviewer` int UNSIGNED NOT NULL,
  `level_persetujuan` int NOT NULL DEFAULT '1',
  `status_persetujuan` enum('ACC','Ditolak','Revisi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_proses` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alur_persetujuan`
--

INSERT INTO `alur_persetujuan` (`id_persetujuan`, `id_pengajuan`, `id_reviewer`, `level_persetujuan`, `status_persetujuan`, `catatan`, `tanggal_proses`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 'Ditolak', 'setuju', '2026-09-22 03:00:43', '2026-09-21 20:00:43', '2026-09-21 20:00:43'),
(2, 2, 5, 1, 'ACC', 'ACC Tahap 1 oleh Finance', '2026-09-22 03:05:13', '2026-09-21 20:05:13', '2026-09-21 20:05:13'),
(3, 2, 5, 2, 'Revisi', 'harga ketinggian\n[Diproses Atas Nama Pimpinan]', '2026-09-22 03:21:18', '2026-09-21 20:21:18', '2026-09-21 20:21:18'),
(4, 2, 5, 1, 'Revisi', 'harga harusnya 50.000', '2026-09-22 03:33:01', '2026-09-21 20:33:01', '2026-09-21 20:33:01'),
(5, 1, 5, 1, 'ACC', 'ok', '2026-09-22 03:33:12', '2026-09-21 20:33:12', '2026-09-21 20:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delegation_authorities`
--

CREATE TABLE `delegation_authorities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `delegate_to_user_id` int UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Aktif','Selesai','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delegation_authorities`
--

INSERT INTO `delegation_authorities` (`id`, `user_id`, `delegate_to_user_id`, `start_date`, `end_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(2, 6, 5, '2026-09-22', '2026-09-30', 'cuti', 'Aktif', '2026-09-21 18:50:18', '2026-09-21 18:50:18');

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_divisi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`, `created_at`, `updated_at`) VALUES
(1, 'Kurikulum & Pembelajaran', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(2, 'Kesiswaan & Ekstrakurikuler', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(3, 'Sarana & Prasarana', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(4, 'Tata Usaha', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(5, 'Perpustakaan', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(6, 'Humas & Kemitraan', '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(7, 'Laboratorium', '2026-09-20 18:36:44', '2026-09-20 18:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pendukung`
--

CREATE TABLE `dokumen_pendukung` (
  `id_dokumen` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_dokumen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_unggah` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumen_pendukung`
--

INSERT INTO `dokumen_pendukung` (`id_dokumen`, `id_pengajuan`, `nama_file`, `tipe_dokumen`, `path_file`, `waktu_unggah`, `created_at`, `updated_at`) VALUES
(1, 1, 'OSI LAYER FAJAR.pdf', 'application/pdf', 'dokumen_rab/WkZzmv9fdbaIOz90Dc3jvqK7D3ovovB4YZL7oCsK.pdf', '2026-09-22 01:52:53', '2026-09-21 18:52:53', '2026-09-21 18:52:53'),
(2, 2, 'Analisa dari wireshark.pdf', 'application/pdf', 'dokumen_rab/YzZx82sm5WTDBvrrtcJ4qK2Dn6MnsjCgRMtX37Qd.pdf', '2026-09-22 03:02:42', '2026-09-21 20:02:42', '2026-09-21 20:02:42'),
(3, 3, 'wallpaper.jpeg', 'image/jpeg', 'dokumen_rab/97YiuWoPgKrBJ3MF9nTZ9UlG7d94f4LcPCU1DW1j.jpg', '2026-09-22 03:35:48', '2026-09-21 20:35:48', '2026-09-21 20:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_08_000001_add_role_and_division_to_users_table', 1),
(5, '2026_09_08_000002_create_rabs_table', 1),
(6, '2026_09_08_000003_create_rab_items_table', 1),
(7, '2026_09_08_000004_create_rab_attachments_table', 1),
(8, '2026_09_08_010001_create_divisi_table', 1),
(9, '2026_09_08_010002_create_pengguna_table', 1),
(10, '2026_09_08_010003_create_pengajuan_rab_table', 1),
(11, '2026_09_08_010004_create_rincian_item_table', 1),
(12, '2026_09_08_010005_create_dokumen_pendukung_table', 1),
(13, '2026_09_08_010006_create_alur_persetujuan_table', 1),
(14, '2026_09_10_000001_expand_roles_and_approval_statuses', 1),
(15, '2026_09_10_051254_modify_status_and_add_bukti_pencairan_to_pengajuan_rab_table', 1),
(16, '2026_09_20_012151_create_delegation_authorities_table', 1),
(17, '2026_09_20_013316_create_activity_logs_table', 1),
(18, '2026_09_20_014444_create_settings_table', 1),
(19, '2026_09_21_013345_update_pengguna_role_column', 1),
(20, '2026_09_22_020431_replace_prioritas_with_kategori_anggaran_on_pengajuan_rab_table', 2),
(21, '2026_09_22_025233_update_divisi_data_for_school_context', 3),
(22, '2026_09_22_032023_add_revisi_to_status_persetujuan_on_alur_persetujuan_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_rab`
--

CREATE TABLE `pengajuan_rab` (
  `id_pengajuan` int UNSIGNED NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_divisi` int UNSIGNED NOT NULL,
  `no_rab` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_penggunaan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_anggaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latar_belakang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimasi_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi Finance',
  `bukti_pencairan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pengajuan` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_rab`
--

INSERT INTO `pengajuan_rab` (`id_pengajuan`, `id_pengguna`, `id_divisi`, `no_rab`, `judul_pengajuan`, `periode_penggunaan`, `kategori_anggaran`, `latar_belakang`, `estimasi_total`, `status`, `bukti_pencairan`, `tanggal_pengajuan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'RAB-2026-001', 'laptop', 'September 2026', 'Operasional Rutin', 'penting', 134000.00, 'Menunggu Persetujuan Pimpinan', NULL, '2026-09-22 03:29:57', '2026-09-21 18:52:52', '2026-09-21 20:33:12'),
(2, 2, 3, 'RAB-2026-002', 'laptop', 'September 2026', 'Pengadaan Barang/Aset', 'lab', 179960.00, 'Revisi', NULL, '2026-09-22 03:02:41', '2026-09-21 20:02:41', '2026-09-21 20:33:01'),
(3, 2, 7, 'RAB-2026-003', 'pc', 'September 2026', 'Pengadaan Barang/Aset', 'urgent', 2000000.00, 'Menunggu Verifikasi Finance', NULL, '2026-09-22 03:35:48', '2026-09-21 20:35:48', '2026-09-21 20:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$2y$12$eA3.kG9a0V4l6VbWJq1j8uWbWf7h5I/J4t/j4uWbWf7h5I/J4t/j4',
  `role` enum('admin','user','staff','finance','pimpinan','admin_it') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `id_divisi`, `nama_lengkap`, `jabatan`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 2, 'Drs. Arif Rachman', 'Direktur Keuangan', 'arif@sirab.local', '$2y$12$T1DSNm16ohU1z/gNM6DuzuujfONTTFkt1kbCS7Uymap9nO3lrMmsC', 'admin', NULL, '2026-09-20 18:36:44', '2026-09-20 18:36:44'),
(2, 1, 'Sari Dewi', 'Staf IT', 'sari@sirab.local', '$2y$12$mvIa71UhIfIrIZ0b5WH0.uuP49P77vtZJ4Xf7BNGYZ5TxXw9oFElS', 'user', NULL, '2026-09-20 18:36:45', '2026-09-20 18:36:45'),
(3, 5, 'Budi Santoso', 'Staf Umum', 'budi@sirab.local', '$2y$12$9OydZDM9E3qAWFvpDt.g6eCSLDxilKDamIugiPLEBZG03TZVsV8j2', 'user', NULL, '2026-09-20 18:36:45', '2026-09-20 18:36:45'),
(4, 3, 'Dina Marlina', 'Staf Pemasaran', 'dina@sirab.local', '$2y$12$2NdzFPRzHjDLlN4ZAnWlnuexJrac6Q4paIvgF1gt4nD50eKvBMPoW', 'user', NULL, '2026-09-20 18:36:46', '2026-09-20 18:36:46'),
(5, 2, 'Akun Finance', 'Bendahara', 'finance@sirab.local', '$2y$12$PN2WZXx6FgYXP.jwnErsi.PvjtorEUtvEzurpUXbUtx.h0TKr6gx2', 'finance', NULL, '2026-09-20 18:36:47', '2026-09-20 18:36:47'),
(6, 7, 'Akun Pimpinan', 'Kepala Sekolah', 'pimpinan@sirab.local', '$2y$12$iuo/wFyu2zly42lLfqZDdOV9lURk45/ayPcE3k7jKwDZWr.raQfIe', 'pimpinan', NULL, '2026-09-20 18:36:47', '2026-09-20 18:36:47');

-- --------------------------------------------------------

--
-- Table structure for table `rabs`
--

CREATE TABLE `rabs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sedang',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `justification` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diajukan',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rab_attachments`
--

CREATE TABLE `rab_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `rab_id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rab_items`
--

CREATE TABLE `rab_items` (
  `id` bigint UNSIGNED NOT NULL,
  `rab_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rincian_item`
--

CREATE TABLE `rincian_item` (
  `id_item` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `uraian_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rincian_item`
--

INSERT INTO `rincian_item` (`id_item`, `id_pengajuan`, `uraian_barang`, `satuan`, `volume`, `harga_satuan`, `total_harga`, `created_at`, `updated_at`) VALUES
(4, 2, 'laptop', 'Unit', 2, 89980.00, 179960.00, '2026-09-21 20:24:12', '2026-09-21 20:24:12'),
(6, 1, 'laptop', 'Unit', 2, 67000.00, 134000.00, '2026-09-21 20:29:57', '2026-09-21 20:29:57'),
(7, 3, 'pc', 'Unit', 1, 2000000.00, 2000000.00, '2026-09-21 20:35:48', '2026-09-21 20:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('e1vcs2yEM0m6iaFFEr026OSxZoib3mQhhZ0eYtvW', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.138.0 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36', 'eyJfdG9rZW4iOiJDQW9VQ0ZYcmZFQXpPelJFMGt3UzR5OTN6bXA4V2VuNEhyQ1RkZmZQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC91c2VyXC9kYXNoYm9hcmQiLCJyb3V0ZSI6InVzZXIuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1790054291),
('no7DJrQb1VriCmPpjmkMCYxmEOvmKjVALOnLSaG1', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI2N3pKMWZneUJURDVkSlI2Mnlaa0x3cHgzcW8xa1VMVEdFemlYR0lkIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3N0YWZmXC9yYWJcL2NyZWF0ZSIsInJvdXRlIjoic3RhZmYucmFiLmNyZWF0ZSJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=', 1790057669),
('XMewAiK4xigcNl9BznGVRc9PzlDOdyu8JlZZYfzk', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJpa3RSSTdub2RQbFkyUmJlaldVUDFNZVFYUVJxSjlETXkydUZzU0R3IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3N0YWZmXC9yaXdheWF0Iiwicm91dGUiOiJzdGFmZi5yaXdheWF0In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==', 1790054800);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `division`, `position`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Drs. Arif Rachman', 'arif@sirab.local', NULL, '$2y$12$eoO8pyAywKqEYCAUoIFKUutNFT6269CZuTIrNoBMLPfoJu3Cp3x9y', 'admin', 'Keuangan', 'Direktur Keuangan', NULL, '2026-09-20 18:36:46', '2026-09-20 18:36:46'),
(2, 'Sari Dewi', 'sari@sirab.local', NULL, '$2y$12$/NdnaYV/SBJGo1iiefY9r.a8lq9BaWiq/OSvHD1fwA72jpl.mDXL.', 'user', 'Teknologi Informasi', 'Staf IT', NULL, '2026-09-20 18:36:47', '2026-09-20 18:36:47'),
(3, 'Akun Finance', 'finance@sirab.local', NULL, '$2y$12$k7YTi410SUdZLRVd4qUQu.Yh1zbH15bA.dm1q5vwcuyu/EJn.mj.K', 'finance', 'Keuangan', 'Bendahara', NULL, '2026-09-20 18:36:48', '2026-09-20 18:36:48'),
(4, 'Akun Pimpinan', 'pimpinan@sirab.local', NULL, '$2y$12$qALAZE3CZIOIYx3egVIJt.qWoMuATU/fgL8KWkJfSuaD.tpgkHucK', 'pimpinan', 'Administrasi', 'Kepala Sekolah', NULL, '2026-09-20 18:36:48', '2026-09-20 18:36:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  ADD PRIMARY KEY (`id_persetujuan`),
  ADD KEY `alur_persetujuan_id_pengajuan_foreign` (`id_pengajuan`),
  ADD KEY `alur_persetujuan_id_reviewer_foreign` (`id_reviewer`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `delegation_authorities`
--
ALTER TABLE `delegation_authorities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delegation_authorities_user_id_foreign` (`user_id`),
  ADD KEY `delegation_authorities_delegate_to_user_id_foreign` (`delegate_to_user_id`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `dokumen_pendukung_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD UNIQUE KEY `pengajuan_rab_no_rab_unique` (`no_rab`),
  ADD KEY `pengajuan_rab_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `pengajuan_rab_id_divisi_foreign` (`id_divisi`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`),
  ADD KEY `pengguna_id_divisi_foreign` (`id_divisi`);

--
-- Indexes for table `rabs`
--
ALTER TABLE `rabs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rabs_code_unique` (`code`),
  ADD KEY `rabs_user_id_foreign` (`user_id`),
  ADD KEY `rabs_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `rab_attachments`
--
ALTER TABLE `rab_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rab_attachments_rab_id_foreign` (`rab_id`);

--
-- Indexes for table `rab_items`
--
ALTER TABLE `rab_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rab_items_rab_id_foreign` (`rab_id`);

--
-- Indexes for table `rincian_item`
--
ALTER TABLE `rincian_item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `rincian_item_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  MODIFY `id_persetujuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delegation_authorities`
--
ALTER TABLE `delegation_authorities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  MODIFY `id_dokumen` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  MODIFY `id_pengajuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rabs`
--
ALTER TABLE `rabs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rab_attachments`
--
ALTER TABLE `rab_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rab_items`
--
ALTER TABLE `rab_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rincian_item`
--
ALTER TABLE `rincian_item`
  MODIFY `id_item` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Constraints for table `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  ADD CONSTRAINT `alur_persetujuan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `alur_persetujuan_id_reviewer_foreign` FOREIGN KEY (`id_reviewer`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `delegation_authorities`
--
ALTER TABLE `delegation_authorities`
  ADD CONSTRAINT `delegation_authorities_delegate_to_user_id_foreign` FOREIGN KEY (`delegate_to_user_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE,
  ADD CONSTRAINT `delegation_authorities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Constraints for table `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  ADD CONSTRAINT `dokumen_pendukung_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  ADD CONSTRAINT `pengajuan_rab_id_divisi_foreign` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pengajuan_rab_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_id_divisi_foreign` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE RESTRICT;

--
-- Constraints for table `rabs`
--
ALTER TABLE `rabs`
  ADD CONSTRAINT `rabs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rabs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rab_attachments`
--
ALTER TABLE `rab_attachments`
  ADD CONSTRAINT `rab_attachments_rab_id_foreign` FOREIGN KEY (`rab_id`) REFERENCES `rabs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rab_items`
--
ALTER TABLE `rab_items`
  ADD CONSTRAINT `rab_items_rab_id_foreign` FOREIGN KEY (`rab_id`) REFERENCES `rabs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rincian_item`
--
ALTER TABLE `rincian_item`
  ADD CONSTRAINT `rincian_item_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
