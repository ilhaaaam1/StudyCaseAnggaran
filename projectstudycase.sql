-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 10 Sep 2026 pada 03.51
-- Versi server: 8.0.30
-- Versi PHP: 8.3.33

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
-- Struktur dari tabel `alur_persetujuan`
--

CREATE TABLE `alur_persetujuan` (
  `id_persetujuan` int UNSIGNED NOT NULL,
  `id_pengajuan` int UNSIGNED NOT NULL,
  `id_reviewer` int UNSIGNED NOT NULL,
  `level_persetujuan` int NOT NULL DEFAULT '1',
  `status_persetujuan` enum('ACC','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_proses` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `alur_persetujuan`
--

INSERT INTO `alur_persetujuan` (`id_persetujuan`, `id_pengajuan`, `id_reviewer`, `level_persetujuan`, `status_persetujuan`, `catatan`, `tanggal_proses`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'ACC', 'Disetujui sesuai spesifikasi dan pagu anggaran IT triwulan berjalan.', '2026-09-06 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(2, 3, 1, 1, 'Ditolak', 'Alokasi anggaran fasilitas kantor periode ini difokuskan untuk perbaikan AC sentral.', '2026-09-01 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(3, 4, 1, 1, 'Ditolak', NULL, '2026-09-09 05:50:47', '2026-09-08 22:50:47', '2026-09-08 22:50:47'),
(4, 5, 1, 1, 'ACC', NULL, '2026-09-09 08:11:58', '2026-09-09 01:11:58', '2026-09-09 01:11:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_divisi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`, `created_at`, `updated_at`) VALUES
(1, 'Teknologi Informasi', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(2, 'Keuangan', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(3, 'Marketing', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(4, 'HRD', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(5, 'Umum & Fasilitas', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(6, 'Logistik', '2026-09-08 22:48:05', '2026-09-08 22:48:05'),
(7, 'Administrasi', '2026-09-08 22:48:05', '2026-09-08 22:48:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_pendukung`
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
-- Dumping data untuk tabel `dokumen_pendukung`
--

INSERT INTO `dokumen_pendukung` (`id_dokumen`, `id_pengajuan`, `nama_file`, `tipe_dokumen`, `path_file`, `waktu_unggah`, `created_at`, `updated_at`) VALUES
(1, 1, 'Surat_Permintaan_Pengadaan.pdf', 'PDF', 'dokumen_rab/mock_surat.pdf', '2026-09-04 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
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
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_rab`
--

CREATE TABLE `pengajuan_rab` (
  `id_pengajuan` int UNSIGNED NOT NULL,
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_divisi` int UNSIGNED NOT NULL,
  `no_rab` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_penggunaan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioritas` enum('Rendah','Sedang','Tinggi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sedang',
  `latar_belakang` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimasi_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Pending','ACC','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `tanggal_pengajuan` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_rab`
--

INSERT INTO `pengajuan_rab` (`id_pengajuan`, `id_pengguna`, `id_divisi`, `no_rab`, `judul_pengajuan`, `periode_penggunaan`, `prioritas`, `latar_belakang`, `estimasi_total`, `status`, `tanggal_pengajuan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'RAB-2026-001', 'Pengadaan Perangkat Server & Jaringan', 'Q4 2026', 'Tinggi', 'Peningkatan kapasitas infrastruktur server dan peremajaan switch jaringan untuk operasional kantor.', 148500000.00, 'ACC', '2026-09-04 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(2, 2, 1, 'RAB-2026-002', 'Pelatihan Sertifikasi Cyber Security Staf IT', 'Q4 2026', 'Sedang', 'Pelatihan sertifikasi keamanan informasi ISO 27001 untuk memperkuat tata kelola TI.', 25000000.00, 'Pending', '2026-09-08 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(3, 3, 5, 'RAB-2026-003', 'Peremajaan Sofa Ruang Tamu VIP', 'Q3 2026', 'Rendah', 'Penggantian kursi sofa ruang tamu gedung direksi yang sudah usang.', 38000000.00, 'Ditolak', '2026-08-30 05:48:07', '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(4, 4, 3, 'RAB-2026-004', 'Kampanye Iklan Digital & Pameran Akhir Tahun', 'Q4 2026', 'Tinggi', 'Promosi kampanye akhir tahun di media sosial dan partisipasi pameran industri nasional.', 65000000.00, 'Ditolak', '2026-09-08 23:48:07', '2026-09-08 22:48:07', '2026-09-08 22:50:47'),
(5, 2, 1, 'RAB-2026-005', 'Tes', 'Q4 2026', 'Sedang', 'Coba Coba', 2064000.00, 'ACC', '2026-09-09 08:11:07', '2026-09-09 01:11:07', '2026-09-09 01:11:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int UNSIGNED NOT NULL,
  `id_divisi` int UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `id_divisi`, `nama_lengkap`, `jabatan`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 2, 'Drs. Arif Rachman', 'Direktur Keuangan', 'arif@sirab.local', '$2y$12$oaHaZrvD5T909QBg3qmlU.bl4ZPxqlPZJ40QU8YUHLWJfh5p0Ckra', 'admin', NULL, '2026-09-08 22:48:06', '2026-09-08 22:48:06'),
(2, 1, 'Sari Dewi', 'Staf IT', 'sari@sirab.local', '$2y$12$VC67vokim.x2TzMW2HRDt.zq7YiaPThJRLYa8XBaFJeHwclA4gNue', 'user', NULL, '2026-09-08 22:48:06', '2026-09-08 22:48:06'),
(3, 5, 'Budi Santoso', 'Staf Umum', 'budi@sirab.local', '$2y$12$kb7pGqpP/pzcHvNEqNWi8.ueg0BzEdMcmUwGY6UTW9CLH/hIq/Roq', 'user', NULL, '2026-09-08 22:48:07', '2026-09-09 01:40:06'),
(4, 3, 'Dina Marlina', 'Staf Pemasaran', 'dina@sirab.local', '$2y$12$oDLbw9dNE/38t6fCroS8VeriGw7tvoNnHA3G/ZDcudyDJiFKX/dJ2', 'user', NULL, '2026-09-08 22:48:07', '2026-09-08 22:48:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rabs`
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
-- Struktur dari tabel `rab_attachments`
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
-- Struktur dari tabel `rab_items`
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
-- Struktur dari tabel `rincian_item`
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
-- Dumping data untuk tabel `rincian_item`
--

INSERT INTO `rincian_item` (`id_item`, `id_pengajuan`, `uraian_barang`, `satuan`, `volume`, `harga_satuan`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laptop Dell XPS 15 (Core i7)', 'Unit', 5, 18500000.00, 92500000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(2, 1, 'Monitor 27\" 4K UltraSharp', 'Unit', 5, 6200000.00, 31000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(3, 1, 'Switch Jaringan 24-Port Managed', 'Unit', 2, 12500000.00, 25000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(4, 2, 'Kursus & Sertifikasi CEH (Certified Ethical Hacker)', 'Peserta', 2, 12500000.00, 25000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(5, 3, 'Sofa Kulit 3 Seater Premium', 'Set', 2, 19000000.00, 38000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(6, 4, 'Biaya Sewa Booth Pameran 3x3m', 'Hari', 3, 15000000.00, 45000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(7, 4, 'Google Ads & Meta Ads Placement', 'Bulan', 2, 10000000.00, 20000000.00, '2026-09-08 22:48:07', '2026-09-08 22:48:07'),
(8, 5, 'Laptop', 'Unit', 3, 688000.00, 2064000.00, '2026-09-09 01:11:07', '2026-09-09 01:11:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4jc0mN4IFJ3gYmrlbQEnoLwjfXugrQod9Ljn9SDi', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJHWEt6cmVTcVV1TWtwRkM1a2hwdEJXTjZsVVZKSTNWNHpEc2FBUEpzIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9wZXJzZXR1anVhbiIsInJvdXRlIjoiYWRtaW4uYXBwcm92YWwubGlzdCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1788943329),
('6ow3X3SCjqgJYCTEFvboPYqOqyX6myjhKx2sI4sa', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI0WmVRRGRHYllWNHdlb05BdmZaelplTXJ0eXJIcU94bE4ydWpDcUZnIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1789011807),
('aFfcJzAUjgH9ot10snXbLwC2dKLXAuACh26dBFnQ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIxVkZXck9OZWw5YnhPVXVtN3A1Y3F4bGtxQTJUU2U5ektlMmRsUUlIIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3VzZXJcL2xhcG9yYW4iLCJyb3V0ZSI6InVzZXIubGFwb3JhbiJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=', 1788965193);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  ADD PRIMARY KEY (`id_persetujuan`),
  ADD KEY `alur_persetujuan_id_pengajuan_foreign` (`id_pengajuan`),
  ADD KEY `alur_persetujuan_id_reviewer_foreign` (`id_reviewer`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indeks untuk tabel `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `dokumen_pendukung_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD UNIQUE KEY `pengajuan_rab_no_rab_unique` (`no_rab`),
  ADD KEY `pengajuan_rab_id_pengguna_foreign` (`id_pengguna`),
  ADD KEY `pengajuan_rab_id_divisi_foreign` (`id_divisi`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`),
  ADD KEY `pengguna_id_divisi_foreign` (`id_divisi`);

--
-- Indeks untuk tabel `rabs`
--
ALTER TABLE `rabs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rabs_code_unique` (`code`),
  ADD KEY `rabs_user_id_foreign` (`user_id`),
  ADD KEY `rabs_approved_by_foreign` (`approved_by`);

--
-- Indeks untuk tabel `rab_attachments`
--
ALTER TABLE `rab_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rab_attachments_rab_id_foreign` (`rab_id`);

--
-- Indeks untuk tabel `rab_items`
--
ALTER TABLE `rab_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rab_items_rab_id_foreign` (`rab_id`);

--
-- Indeks untuk tabel `rincian_item`
--
ALTER TABLE `rincian_item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `rincian_item_id_pengajuan_foreign` (`id_pengajuan`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  MODIFY `id_persetujuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  MODIFY `id_dokumen` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  MODIFY `id_pengajuan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `rabs`
--
ALTER TABLE `rabs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rab_attachments`
--
ALTER TABLE `rab_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rab_items`
--
ALTER TABLE `rab_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rincian_item`
--
ALTER TABLE `rincian_item`
  MODIFY `id_item` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alur_persetujuan`
--
ALTER TABLE `alur_persetujuan`
  ADD CONSTRAINT `alur_persetujuan_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `alur_persetujuan_id_reviewer_foreign` FOREIGN KEY (`id_reviewer`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `dokumen_pendukung`
--
ALTER TABLE `dokumen_pendukung`
  ADD CONSTRAINT `dokumen_pendukung_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_rab`
--
ALTER TABLE `pengajuan_rab`
  ADD CONSTRAINT `pengajuan_rab_id_divisi_foreign` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pengajuan_rab_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_id_divisi_foreign` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `rabs`
--
ALTER TABLE `rabs`
  ADD CONSTRAINT `rabs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rabs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rab_attachments`
--
ALTER TABLE `rab_attachments`
  ADD CONSTRAINT `rab_attachments_rab_id_foreign` FOREIGN KEY (`rab_id`) REFERENCES `rabs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rab_items`
--
ALTER TABLE `rab_items`
  ADD CONSTRAINT `rab_items_rab_id_foreign` FOREIGN KEY (`rab_id`) REFERENCES `rabs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rincian_item`
--
ALTER TABLE `rincian_item`
  ADD CONSTRAINT `rincian_item_id_pengajuan_foreign` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_rab` (`id_pengajuan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
