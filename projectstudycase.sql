-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2026 at 05:20 PM
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
-- Database: `projectstudycase`
--

-- --------------------------------------------------------

--
-- Table structure for table `alur_persetujuan`
--

CREATE TABLE `alur_persetujuan` (
  `id_persetujuan` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `id_reviewer` int UNSIGNED NOT NULL,
  `level_persetujuan` int NOT NULL,
  `status_persetujuan` enum('menunggu','disetujui','ditolak','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_proses` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alur_persetujuan`
--

INSERT INTO `alur_persetujuan` (`id_persetujuan`, `id_pengajuan`, `id_reviewer`, `level_persetujuan`, `status_persetujuan`, `catatan`, `tanggal_proses`) VALUES
(1, 2, 1, 1, 'disetujui', NULL, '2026-09-08 16:31:20');

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
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_divisi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'Teknologi Informasi'),
(2, 'Keuangan'),
(3, 'Marketing'),
(4, 'HRD'),
(5, 'Umum & Fasilitas'),
(6, 'Logistik'),
(7, 'Administrasi');

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
  `waktu_unggah` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13, '2026_09_08_010006_create_alur_persetujuan_table', 1);

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
  `prioritas` enum('rendah','sedang','tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sedang',
  `latar_belakang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimasi_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','diajukan','disetujui','ditolak','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `tanggal_pengajuan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_rab`
--

INSERT INTO `pengajuan_rab` (`id_pengajuan`, `id_pengguna`, `id_divisi`, `no_rab`, `judul_pengajuan`, `periode_penggunaan`, `prioritas`, `latar_belakang`, `estimasi_total`, `status`, `tanggal_pengajuan`) VALUES
(1, 2, 1, 'RAB-2026-001', 'Pengadaan Perangkat Server & Jaringan', 'Q4 2026', 'tinggi', 'Peningkatan kapasitas infrastruktur server dan peremajaan switch jaringan.', 185000000.00, 'disetujui', '2026-09-01 16:19:20'),
(2, 2, 4, 'RAB-2026-002', 'Pelatihan SDM & Sertifikasi Karyawan Q4', 'Q4 2026', 'tinggi', 'Pelatihan sertifikasi teknis dan manajemen mutu untuk 15 karyawan inti.', 72500000.00, 'disetujui', '2026-09-06 16:19:20'),
(3, 3, 5, 'RAB-2026-003', 'Renovasi Ruang Rapat Lantai 2', 'Q3 2026', 'rendah', 'Pengecatan ulang, perbaikan partisi peredam suara, dan karpet ruang rapat utama.', 43200000.00, 'revisi', '2026-09-03 16:19:20'),
(4, 2, 1, 'RAB-2026-004', 'STB', 'Q2 2026', 'tinggi', 'Keperluan Kantor', 7600000.00, 'diajukan', '2026-09-08 16:22:28');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `id_divisi`, `nama_lengkap`, `jabatan`, `email`) VALUES
(1, 2, 'Drs. Arif Rachman', 'Direktur Keuangan', 'arif@sirab.local'),
(2, 1, 'Sari Dewi', 'Staf IT', 'sari@sirab.local'),
(3, 5, 'Budi Santoso', 'Staf Umum', 'budi@sirab.local');

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

--
-- Dumping data for table `rabs`
--

INSERT INTO `rabs` (`id`, `user_id`, `code`, `title`, `division`, `period`, `priority`, `total_amount`, `justification`, `status`, `admin_note`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'RAB-2026-001', 'Pengadaan Perangkat Server & Jaringan', 'Teknologi Informasi', 'Q4 2026', 'tinggi', 185000000.00, 'Peningkatan kapasitas infrastruktur server dan peremajaan switch jaringan untuk mendukung operasional kantor pusat.', 'disetujui', 'Disetujui sesuai spesifikasi dan penawaran vendor terlampir.', 1, '2026-09-01 09:19:19', '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(2, 2, 'RAB-2026-002', 'Pelatihan SDM & Sertifikasi Karyawan Q4', 'HRD', 'Q4 2026', 'tinggi', 72500000.00, 'Pelatihan sertifikasi teknis dan manajemen mutu untuk 15 karyawan inti.', 'disetujui', NULL, 1, '2026-09-08 09:30:48', '2026-09-08 09:19:20', '2026-09-08 09:30:48'),
(3, 3, 'RAB-2026-003', 'Renovasi Ruang Rapat Lantai 2', 'Umum & Fasilitas', 'Q3 2026', 'rendah', 43200000.00, 'Pengecatan ulang, perbaikan partisi peredam suara, dan karpet ruang rapat utama.', 'revisi', 'Mohon sertakan perbandingan harga dari minimal 2 kontraktor interior lain.', 1, '2026-09-03 09:19:20', '2026-09-08 09:19:20', '2026-09-08 09:19:20'),
(4, 4, 'RAB-2026-004', 'Kampanye Pemasaran Digital Q4 2026', 'Marketing', 'Q4 2026', 'tinggi', 210000000.00, 'Anggaran promosi digital ads, influencer marketing, dan event launching produk akhir tahun.', 'diajukan', NULL, NULL, NULL, '2026-09-08 09:19:20', '2026-09-08 09:19:20'),
(5, 5, 'RAB-2026-005', 'Pemeliharaan Kendaraan Operasional', 'Logistik', 'Q3 2026', 'sedang', 28750000.00, 'Servis berkala, perpanjangan STNK/KIR, dan penggantian ban untuk 4 unit armada operasional.', 'ditolak', 'Biaya ban dan servis melebihi plafon semester ini. Harap revisi alokasi biaya per unit kendaraan.', 1, '2026-08-28 09:19:20', '2026-09-08 09:19:20', '2026-09-08 09:19:20'),
(6, 6, 'RAB-2026-006', 'Pengembangan Sistem ERP & Integrasi API', 'Keuangan', 'Q3 2026', 'tinggi', 395000000.00, 'Implementasi modul akuntansi dan persediaan berbasis web dengan konsultan independen.', 'disetujui', 'Persetujuan disahkan sesuai keputusan rapat dewan direksi.', 1, '2026-08-25 09:19:20', '2026-09-08 09:19:20', '2026-09-08 09:19:20'),
(7, 7, 'RAB-2026-007', 'Inventaris Alat Tulis Kantor Q4', 'Administrasi', 'Q4 2026', 'rendah', 12300000.00, 'Pengadaan kertas HVS, tinta printer, dan perlengkapan administrasi kantor triwulan ke-4.', 'draft', NULL, NULL, NULL, '2026-09-08 09:19:20', '2026-09-08 09:19:20'),
(8, 8, 'RAB-2026-008', 'Audit Eksternal & Konsultasi Pajak Tahunan', 'Keuangan', 'Q4 2026', 'sedang', 55000000.00, 'Jasa Kantor Akuntan Publik (KAP) untuk audit laporan keuangan tahun berjalan.', 'draft', NULL, NULL, NULL, '2026-09-08 09:19:20', '2026-09-08 09:19:20');

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

--
-- Dumping data for table `rab_attachments`
--

INSERT INTO `rab_attachments` (`id`, `rab_id`, `file_name`, `file_path`, `file_size`, `file_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Surat Permintaan Pengadaan.pdf', 'attachments/mock_surat.pdf', '245 KB', 'pdf', '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(2, 1, 'Spesifikasi Teknis.xlsx', 'attachments/mock_spek.xlsx', '245 KB', 'xlsx', '2026-09-08 09:19:20', '2026-09-08 09:19:20');

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

--
-- Dumping data for table `rab_items`
--

INSERT INTO `rab_items` (`id`, `rab_id`, `description`, `unit`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laptop Dell XPS 15 (Core i7)', 'Unit', 5, 18500000.00, 92500000.00, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(2, 1, 'Monitor 27\" 4K UltraSharp', 'Unit', 5, 6200000.00, 31000000.00, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(3, 1, 'Keyboard & Mouse Wireless', 'Set', 10, 850000.00, 8500000.00, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(4, 1, 'Lisensi Microsoft 365 Business', 'Tahun', 10, 2800000.00, 28000000.00, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(5, 1, 'Switch Jaringan 24-Port Managed', 'Unit', 2, 12500000.00, 25000000.00, '2026-09-08 09:19:19', '2026-09-08 09:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `rincian_item`
--

CREATE TABLE `rincian_item` (
  `id_item` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `uraian_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rincian_item`
--

INSERT INTO `rincian_item` (`id_item`, `id_pengajuan`, `uraian_barang`, `satuan`, `volume`, `harga_satuan`, `total_harga`) VALUES
(1, 1, 'Laptop Dell XPS 15 (Core i7)', 'Unit', 5, 18500000.00, 92500000.00),
(2, 1, 'Monitor 27\" 4K UltraSharp', 'Unit', 5, 6200000.00, 31000000.00),
(3, 1, 'Switch Jaringan 24-Port Managed', 'Unit', 2, 12500000.00, 25000000.00),
(4, 4, 'Laptop', 'unit', 3, 1200000.00, 3600000.00),
(5, 4, 'Hp', 'Unit', 2, 2000000.00, 4000000.00);

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
('cybmbhY3NJcyMUhmrHV54QSk6xQLEtKvWEBE5e9k', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIxSUdKeEN3R3hOT3JhREt4bGZ4N3BkcW9OdXlweFZoTHd5WkhVaXdWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9yYWIiLCJyb3V0ZSI6InJhYi5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1788885154);

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
(1, 'Drs. Arif Rachman', 'arif@sirab.local', NULL, '$2y$12$HN.13cxP3LxXbWtEz8ZztemQDv4CatxzD7gv0vUbGFRvLm7F0b1Wi', 'admin', 'Keuangan', 'Direktur Keuangan', NULL, '2026-09-08 09:19:17', '2026-09-08 09:19:17'),
(2, 'Sari Dewi', 'sari@sirab.local', NULL, '$2y$12$PMkK/5DlaIuQokrYaMVdq.dZes.UlQdk5/E/uRL6I8kEo6gAxldCO', 'user', 'Teknologi Informasi', 'Staf IT', NULL, '2026-09-08 09:19:18', '2026-09-08 09:19:18'),
(3, 'Budi Santoso', 'budi@sirab.local', NULL, '$2y$12$KPfp5ZI9EnrOl6zAM0raDeZ3vT76hxMMS2O8VaWIbnsYGi0KYzn5S', 'user', 'Umum & Fasilitas', 'Staf Umum', NULL, '2026-09-08 09:19:18', '2026-09-08 09:19:18'),
(4, 'Dina Marlina', 'dina@sirab.local', NULL, '$2y$12$HwKwatcLoSEpQLOsM4SMg.qlq1Om/vRXD655VVAmB2dL32pFPlBOi', 'user', 'Marketing', 'Staf Pemasaran', NULL, '2026-09-08 09:19:18', '2026-09-08 09:19:18'),
(5, 'Hendra Wijaya', 'hendra@sirab.local', NULL, '$2y$12$3UdOf8KBFdvVF92Qs4cCV.KEKjYZCAxBlJQfS6XglL3TB3QlDde36', 'user', 'Logistik', 'Staf Logistik', NULL, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(6, 'Ratna Sari', 'ratna@sirab.local', NULL, '$2y$12$4W8AJKypkaxODaCGTWDGy.ttQAfecge0HXQEnXfv1nJgN2HolxPe.', 'user', 'Keuangan', 'Staf Keuangan', NULL, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(7, 'Yusuf Hakim', 'yusuf@sirab.local', NULL, '$2y$12$vsBCW0Pd/OJxo3NtT6VUI.XS9yb5Oy01r6KrYkVK.oxzVBGNN120C', 'user', 'Administrasi', 'Staf Administrasi', NULL, '2026-09-08 09:19:19', '2026-09-08 09:19:19'),
(8, 'Lestari Wulandari', 'lestari@sirab.local', NULL, '$2y$12$39gOBtxAliNsXL/nUYWz5O4sg7.zNX8Aid/VLGXLeRygh81rwt/9S', 'user', 'Keuangan', 'Staf Keuangan', NULL, '2026-09-08 09:19:19', '2026-09-08 09:19:19');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  MODIFY `id_persetujuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  MODIFY `id_dokumen` int UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  MODIFY `id_pengajuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rabs`
--
ALTER TABLE `rabs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rab_attachments`
--
ALTER TABLE `rab_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rab_items`
--
ALTER TABLE `rab_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rincian_item`
--
ALTER TABLE `rincian_item`
  MODIFY `id_item` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  ADD CONSTRAINT `alur_persetujuan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `alur_persetujuan_id_reviewer_foreign` FOREIGN KEY (`id_reviewer`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

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
