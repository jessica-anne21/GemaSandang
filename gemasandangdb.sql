-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 29, 2026 at 12:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gemasandangdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bargains`
--

CREATE TABLE `bargains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `harga_tawaran` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bargains`
--

INSERT INTO `bargains` (`id`, `user_id`, `product_id`, `harga_tawaran`, `status`, `catatan_admin`, `created_at`, `updated_at`, `is_read`) VALUES
(1, 4, 4, 100000, 'accepted', NULL, '2025-12-19 00:10:21', '2025-12-28 10:24:19', 1),
(2, 4, 4, 160000, 'rejected', NULL, '2025-12-27 07:36:17', '2025-12-28 10:24:19', 1),
(3, 2, 4, 100000, 'rejected', 'Tawaran terlalu rendah. Minimal 125.000 ya', '2025-12-28 09:23:31', '2025-12-28 10:21:02', 1),
(4, 2, 4, 125000, 'accepted', NULL, '2025-12-28 09:35:21', '2025-12-28 10:21:02', 1),
(5, 2, 6, 50000, 'rejected', 'Stok habis', '2025-12-28 10:23:20', '2025-12-28 17:20:12', 1),
(6, 1, 6, 80000, 'rejected', 'Maaf, kesepakatan dibatalkan karena melebihi batas waktu pembayaran.', '2025-12-28 10:23:51', '2025-12-28 10:31:22', 1),
(7, 4, 6, 42500, 'rejected', 'Maaf, kesepakatan dibatalkan karena melebihi batas waktu pembayaran.', '2025-12-28 10:24:18', '2025-12-28 10:34:53', 1),
(20, 4, 8, 60000, 'accepted', NULL, '2026-01-08 14:54:54', '2026-01-08 14:55:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('gema_sandang_cache_budi@gmail.com|127.0.0.1', 'i:1;', 1767883584),
('gema_sandang_cache_budi@gmail.com|127.0.0.1:timer', 'i:1767883584;', 1767883584);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` float NOT NULL,
  `is_bargain` tinyint(1) NOT NULL DEFAULT 0,
  `bargain_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `price`, `is_bargain`, `bargain_id`, `created_at`, `updated_at`) VALUES
(3, 1, 4, 1, 190000, 0, NULL, '2026-01-08 14:54:11', '2026-01-08 14:54:11');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Outer', '2025-10-23 01:17:25', '2025-12-14 09:44:08'),
(2, 'Celana', '2025-10-23 07:24:25', '2025-10-23 07:24:25'),
(3, 'Atasan', '2025-11-22 09:53:50', '2025-11-22 09:53:50'),
(4, 'Dress', '2025-12-14 09:43:58', '2025-12-14 09:43:58'),
(5, 'Aksesoris', '2025-12-14 09:44:33', '2025-12-14 09:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_09_171657_create_categories_table', 1),
(5, '2025_10_09_171657_create_products_table', 1),
(6, '2025_10_09_171658_create_orders_table', 1),
(7, '2025_10_09_172923_create_order_items_table', 2),
(8, '2025_11_22_170016_add_shipping_details_to_orders_table', 3),
(9, '2025_11_22_171258_add_payment_proof_to_orders_table', 4),
(10, '2025_11_22_172614_change_status_column_in_orders_table', 5),
(11, '2025_11_22_174800_add_received_date_to_orders_table', 6),
(12, '2025_12_19_064416_create_bargains_table', 7),
(13, '2025_12_28_204915_add_soft_deletes_to_products_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_harga` float NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_pembayaran',
  `alamat_pengiriman` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kurir` varchar(255) DEFAULT NULL,
  `layanan` varchar(255) DEFAULT NULL,
  `ongkir` int(11) NOT NULL DEFAULT 0,
  `nomor_resi` varchar(255) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `tanggal_diterima` timestamp NULL DEFAULT NULL,
  `nomor_hp` varchar(20) NOT NULL,
  `catatan_customer` varchar(255) DEFAULT NULL,
  `catatan_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_harga`, `status`, `alamat_pengiriman`, `created_at`, `updated_at`, `kurir`, `layanan`, `ongkir`, `nomor_resi`, `bukti_bayar`, `tanggal_diterima`, `nomor_hp`, `catatan_customer`, `catatan_admin`) VALUES
(7, 1, 60000, 'selesai', 'Jl Pakar Timur IV', '2025-11-22 10:39:38', '2025-11-22 10:45:43', 'jne', NULL, 20000, '12345678', 'payment_proofs/4g5PP2YkI3ZF4uccvajmcyMhyaHyjOZShymfMg8k.jpg', NULL, '08123456789', '', NULL),
(8, 1, 170000, 'selesai', 'Jl Pakar Timur IV', '2025-11-22 10:52:38', '2025-11-22 11:08:12', 'sicepat', NULL, 20000, '119273192371', 'payment_proofs/SFaPeYpoBJKftsg9pP1pTWQmSPmUD9kHwaToKfwc.jpg', '2025-11-22 11:08:12', '0', '', NULL),
(9, 2, 50000, 'selesai', 'Jl. Cibogo 31', '2025-11-27 18:01:42', '2025-11-27 18:06:25', 'grab', NULL, 20000, '764767479', 'payment_proofs/LN62trykCEPV37fMVQfLjJpVfDe9mDQcSY8fb8YH.jpg', '2025-11-27 18:06:25', '0812345678', '', NULL),
(10, 4, 210000, 'selesai', 'Jl Pakar Timur IV', '2025-12-14 22:52:24', '2025-12-28 13:46:01', 'sicepat', NULL, 35000, '119273192371', 'payment_proofs/7lVbK0vCYrRhEgTgIRLXrifzKjyhhEStSCdpfYbJ.png', '2025-12-28 13:46:01', '0812345678', '', NULL),
(11, 4, 210000, 'dibatalkan', 'Jl Surya Sumantri', '2025-12-27 10:17:25', '2025-12-27 10:55:56', 'sicepat', NULL, 20000, NULL, NULL, NULL, '0812345678', '', NULL),
(18, 4, 225000, 'dibatalkan', 'Jl Dago', '2025-12-27 11:54:26', '2025-12-27 11:56:14', 'jnt', NULL, 35000, NULL, NULL, NULL, '0812345678', 'tolong packing yg aman', NULL),
(43, 2, 25000, 'dikirim', 'Jl Dago', '2025-12-28 18:13:46', '2025-12-28 18:20:22', 'jnt', NULL, 20000, '119273192371', 'payment_proofs/vPhcNS7oXcVLyn7joCbCtSLi2f0knzKzSd0S3MrN.jpg', NULL, '0812345678', NULL, 'nominal salah'),
(44, 4, 270000, 'menunggu_pembayaran', 'Dago', '2026-01-08 14:57:55', '2026-01-08 14:57:55', 'jnt', NULL, 20000, NULL, NULL, NULL, '0812345678', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_saat_beli` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `kuantitas`, `harga_saat_beli`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 1, 40000, '2025-11-22 10:39:38', '2025-11-22 10:39:38'),
(2, 8, NULL, 1, 150000, '2025-11-22 10:52:38', '2025-11-22 10:52:38'),
(3, 9, NULL, 1, 30000, '2025-11-27 18:01:42', '2025-11-27 18:01:42'),
(4, 10, 5, 1, 175000, '2025-12-14 22:52:24', '2025-12-14 22:52:24'),
(5, 11, 4, 1, 190000, '2025-12-27 10:17:25', '2025-12-27 10:17:25'),
(12, 18, 4, 1, 190000, '2025-12-27 11:54:26', '2025-12-27 11:54:26'),
(33, 43, 11, 1, 5000, '2025-12-28 18:13:46', '2025-12-28 18:13:46'),
(34, 44, 4, 1, 190000, '2026-01-08 14:57:55', '2026-01-08 14:57:55'),
(35, 44, 8, 1, 60000, '2026-01-08 14:57:55', '2026-01-08 14:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` float NOT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `nama_produk`, `deskripsi`, `harga`, `foto_produk`, `stok`, `created_at`, `updated_at`) VALUES
(2, 3, 'Atasan Pink \"Bebe\"', NULL, 40000, 'products/xsshC80Yc7OVE9RfGawJybBtwLwNSMY90OuyZBhT.jpg', 0, '2025-11-22 09:55:34', '2025-11-22 10:39:38'),
(4, 4, 'Vintage Floral Dress 90s Chic', 'Dress selip (slip dress) cantik dengan spaghetti strap. Motif bunga lembut bernuansa dusty rose dan hijau di atas dasar kain warna krem. Terdapat detail renda marun di bagian dada dan bawah gaun.\r\n\r\nBahan: Sifon tipis berlapis furing (tidak menerawang).\r\nKondisi: 9.5/10 (Excellent Condition)\r\nMinus: TIDAK ADA. Warna masih cerah dan tidak ada noda/lubang.\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: L (Fit to M/L)\r\nLingkar Dada (LD): 90 - 95 cm (Dada elastis)\r\nPanjang Baju: 105 cm', 190000, 'products/aPeM8hg8shpcbzbgmNl3mHU0LMLj9dgEEIaDwBsB.jpg', 0, '2025-12-14 09:47:51', '2026-01-08 14:57:55'),
(5, 2, 'Vintage Celana Corduroy Coklat Tua', 'Celana panjang berbahan corduroy (kain beludru bergaris) yang tebal dan kokoh. Warna cokelat tua yang sangat cocok untuk fall/winter look. Detail saku tempel di depan dan saku koin kecil. Resleting dan kancing berfungsi normal.\r\n\r\nKondisi: 8.5/10 (Very Good Condition)\r\nMinus: Ada sedikit pudar warna alami di bagian lutut dan ujung kaki (sesuai foto), khas material corduroy vintage.\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: 28 (Setara S/M lokal)\r\nLingkar Pinggang (LP): 72 cm\r\nPanjang Celana: 102 cm', 175000, 'products/umsGTo6ifcT0x1e3hAZIDbEzWo0FaQTAOsk10DZq.jpg', 0, '2025-12-14 09:50:06', '2025-12-14 22:52:24'),
(6, 1, 'Vintage Olive Cable Knit Cardigan', 'Cardigan rajut tebal (Chunky Knit) dengan motif Cable Knit (rajutan kepang) klasik. Warna hijau olive yang mewah dan mudah dipadukan dengan earth tone look. Model kancing penuh di bagian depan, bisa dipakai sebagai outer atau sweater tertutup.\r\n\r\nCocok untuk tampilan yang nyaman, retro, dan aesthetic untuk musim dingin atau ruangan ber-AC.\r\n\r\nKONDISI :\r\nKondisi: 9/10 (Excellent Condition)\r\nMinus: TIDAK ADA. Rajutan masih rapat, tidak ada benang tertarik, dan tidak berbulu (pilling).\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: M (Fit Oversize S ke M, atau Fit L biasa)\r\nLingkar Dada (LD): 100 - 105 cm\r\nPanjang Baju: 58 cm\r\nPanjang Lengan: 60 cm', 85000, 'products/Ow1E7bWoZQsypTlmGZaCwO6WB25NshUxeTMRojDZ.jpg', 0, '2025-12-14 22:19:09', '2025-12-28 11:16:23'),
(7, 4, 'Babydoll Floral Dress', 'Dress model babydoll yang super cantik dengan motif floral klasik. Dilengkapi detail renda (lace) cokelat di bagian dada dan bawah dress yang memberikan kesan bohemian vintage.\r\n\r\nKondisi: Like New (9.8/10).\r\nMinus: Tidak ada (No defect).\r\nKebersihan: Sudah dicuci bersih, disetrika uap, dan wangi (Ready to wear).\r\nDetail Ukuran (Size M):\r\nLingkar Dada (LD): 88-92 cm\r\nPanjang Baju: 85 cm', 185000, 'products/QbRx6QCtOe9aAerCNgZ9f882EKnyJasDYHFQiijn.jpg', 0, '2025-12-28 13:29:30', '2025-12-28 14:25:45'),
(8, 3, 'White Coquette Floral Top', 'Blouse vintage yang sangat cantik dengan motif floral pastel yang lembut. Memiliki detail kerah v-neck dengan aksen bunga mawar kecil di tengah, serta tepian renda (scalloped lace) yang sangat detail di bagian bawah dan lengan puff. Bagian dada memiliki detail smocked yang memberikan kesan ramping.\r\n\r\nKondisi: Excellent Condition (9.5/10).\r\nMinus: Tidak ada. Warna masih bersih, renda utuh tidak ada yang lepas.\r\nKebersihan: Sudah di-laundry dan di-steam (Siap pakai).\r\nDetail Ukuran (Size S fit to M):\r\nLingkar Dada (LD): 84 - 90 cm (bahan agak melar di bagian dada).\r\nPanjang Baju: 55 cm.', 90000, 'products/sqKNoslfFytbgxxeaikoUQUmF6k7BJSuzTm5p8mm.jpg', 0, '2025-12-28 13:31:15', '2026-01-08 14:57:55'),
(9, 3, 'Gingham Coquette Blouse with Lace Detail', 'Blouse bergaya Coquette yang sangat manis dengan motif Gingham cokelat-putih. Detail kerah renda (lace) dan aksen pita cokelat di bagian depan memberikan kesan feminin dan aesthetic. Model lengan puff pendek dengan pinggiran renda yang rapi.\r\n\r\nDetail Item:\r\nKondisi: 9.5/10 (Sangat terawat, kain masih kaku dan warna pekat).\r\nBahan: Katun seersucker premium (dingin dan menyerap keringat).\r\nSize: Fit to L (Lingkar Dada: 95-100 cm, Panjang: 55 cm).\r\nWarna: Brown & White Gingham.', 75000, 'products/FUjL2ILboV8Oc7iGqXhyfepCD6thWEzbKC6AJp7I.jpg', 0, '2025-12-28 15:53:35', '2025-12-28 17:37:24'),
(10, 2, 'test', NULL, 15000, 'products/sG2LCJJQp0IZsAw7cE3ZQCpaC7umBTLtwC8SnilS.jpg', 0, '2025-12-28 17:20:04', '2025-12-28 17:58:24'),
(11, 2, 'testt', NULL, 5000, 'products/cguPlNMhHDpnghTppYKEp8yEXuUOQL7vBYb4vU8m.jpg', 0, '2025-12-28 18:13:32', '2025-12-28 18:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sbJXUMDqXXoPg2UwFoaAKQoAugP5AQDVmjnkjdaO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWm1HUGV5NzhwckJ5QWl1a2VZN2l4Q3RYaFhCUUdPbWUwaENvM1pXaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1767886403);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_hp` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `alamat`, `nomor_hp`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jessica Anne', 'jesshar2108@gmail.com', NULL, '$2y$12$BUdJ3qPPaMdATQcJw2R97ORyQO2dphz/pmGCpFXhJMnw/Octe69o6', 'Jl Pakar Timur', '082121349200', 'customer', NULL, '2025-10-16 01:45:14', '2026-01-01 12:35:04'),
(2, 'Jonathan', 'jonathan@gmail.com', NULL, '$2y$12$gDcEJGQLWVfv/wCcuksSGuY1uQMmRdtRWD7f7fG7RTdJKjbmEnwsO', 'Jl Dago', '0812345678', 'customer', NULL, '2025-10-16 17:49:01', '2025-12-28 09:47:06'),
(3, 'Admin', 'admin1@gmail.com', NULL, '$2y$12$AzvKjT2F8gUIKlVTISXUlOl00rAHOq14ov3UWOrKkeKjYdxmyfvIK', NULL, NULL, 'admin', NULL, '2025-10-21 02:31:01', '2025-10-21 02:31:01'),
(4, 'Anne', 'anne@gmail.com', NULL, '$2y$12$2fot7KE8dMna1o7nPkup6.bsTliSyXjQRpFHTZooUEkI0U1aLvIXK', 'Dago', '0812345678', 'customer', NULL, '2025-12-14 22:51:54', '2025-12-28 11:16:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bargains`
--
ALTER TABLE `bargains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bargains_user_id_foreign` (`user_id`),
  ADD KEY `bargains_product_id_foreign` (`product_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_user` (`user_id`),
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `fk_cart_bargain` (`bargain_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

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
-- AUTO_INCREMENT for table `bargains`
--
ALTER TABLE `bargains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bargains`
--
ALTER TABLE `bargains`
  ADD CONSTRAINT `bargains_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bargains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_cart_bargain` FOREIGN KEY (`bargain_id`) REFERENCES `bargains` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
