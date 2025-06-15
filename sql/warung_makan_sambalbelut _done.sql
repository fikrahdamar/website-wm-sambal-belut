-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jun 2025 pada 07.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warung_makan_sambalbelut`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `is_reward` tinyint(1) DEFAULT NULL,
  `point_cost` int(11) NOT NULL DEFAULT 100,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `available`, `is_reward`, `point_cost`, `image`) VALUES
(1, 1, 'Sambal belut ori', 'Sambal belut khas pedas', 26000, 1, 1, 160, 'sambal-belut.jpg'),
(2, 1, 'Lele Goreng', 'Lele Goreng Kriuk', 10000, 1, 1, 110, 'lele.jpg'),
(4, 2, 'Es Teh Manis', 'Teh manis dingin', 5000, 1, 1, 60, 'es-teh.jpg'),
(5, 1, 'Nasi Putih', 'Tambahan Nasi Putih', 4000, 1, 1, 60, 'nasi-putih.jpg'),
(12, 1, 'Bebek Goreng', 'Bebek goreng besar ', 18000, 1, 1, 130, 'bebek-goreng.jpg'),
(13, 1, 'Bebek Kecil', 'Bebek goreng mini', 10000, 1, 1, 110, 'bebek-goreng-kecil.jpg'),
(14, 1, 'Kepala/Ati Ampela Bebek', 'Kepala/Ati Ampela Bebek Goreng', 10000, 1, 1, 110, 'ati-kepala-bebek.jpg'),
(15, 1, 'Ayam Goreng', 'Ayam Goreng Penyet', 16000, 1, 1, 130, 'ayam-penyet.jpg'),
(16, 1, 'Kepala/Ati Ampela Ayam', 'Kepala/Ati Ampela Ayam Goreng', 10000, 1, 1, 110, 'ati-kepala-ayam.jpg'),
(17, 1, 'Wader', 'Wader Crispy', 10000, 1, 1, 110, 'wader-krispi.jpg'),
(18, 1, 'Tahu Tempe', 'Tahu Tempe Goreng', 7000, 1, 1, 80, 'tahu-tempe.jpg'),
(19, 1, 'Tahu Tempe Telur', 'Tahu Tempe Telur Goreng', 9000, 1, 1, 90, 'tahu-tempe-telur.jpg'),
(20, 2, 'Es Jeruk', 'Es Jeruk Segar', 5000, 1, 1, 60, 'es-jeruk.jpg'),
(21, 2, 'Jeruk Hangat', 'Jeruk Hangat Nikmat', 4000, 1, 1, 50, 'jeruk-hangat.jpg'),
(22, 2, 'Teh Hangat', 'Teh Hangat Nikmat', 3000, 1, 1, 50, 'teh-hangat.jpg'),
(23, 2, 'Air Mineral', 'Air Mineral Kemasan', 4000, 1, 1, 50, 'air-mineral.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` int(11) NOT NULL,
  `menu_categoris_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `menu_categoris_name`) VALUES
(1, 'makanan'),
(2, 'minuman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `status` enum('pending','process','completed','canceled') NOT NULL,
  `payment_method` enum('tunai','qris') NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_type` enum('dine-in','take-away','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `users_id`, `status`, `payment_method`, `total_price`, `created_at`, `service_type`) VALUES
(1, 2, 'completed', 'tunai', 27000, '2025-05-23 08:05:08', 'dine-in'),
(2, NULL, 'completed', 'qris', 34000, '2025-05-23 08:05:08', 'take-away'),
(3, 1, 'completed', 'qris', 15000, '2025-05-25 14:01:11', 'dine-in'),
(4, 2, 'completed', 'qris', 18000, '2025-05-25 14:01:11', ''),
(6, 2, 'completed', 'tunai', 60000, '2025-05-31 15:04:38', 'dine-in'),
(7, 2, 'completed', 'qris', 25000, '2025-05-31 15:05:54', 'take-away'),
(8, 2, 'completed', 'qris', 32000, '2025-05-31 15:44:34', 'take-away'),
(9, 2, 'completed', 'tunai', 30000, '2025-05-31 15:45:22', 'take-away'),
(10, 2, 'completed', 'tunai', 20000, '2025-05-31 16:15:14', 'take-away'),
(11, NULL, 'completed', 'qris', 30000, '2025-05-31 16:28:55', 'dine-in'),
(12, NULL, 'completed', 'tunai', 54000, '2025-05-31 17:25:16', 'dine-in'),
(13, NULL, 'completed', 'tunai', 18000, '2025-05-31 17:29:02', 'take-away'),
(14, NULL, 'completed', 'tunai', 15000, '2025-05-31 17:31:42', 'dine-in'),
(15, NULL, 'completed', 'tunai', 32000, '2025-05-31 17:35:56', 'take-away'),
(16, NULL, 'completed', 'tunai', 54000, '2025-06-02 02:07:45', 'dine-in'),
(17, NULL, 'completed', 'tunai', 15000, '2025-06-02 02:07:54', 'dine-in'),
(18, 2, 'completed', 'tunai', 45000, '2025-06-02 02:11:04', 'dine-in'),
(19, 2, 'completed', 'tunai', 16000, '2025-06-02 02:11:10', 'dine-in'),
(20, 2, 'completed', 'tunai', 60000, '2025-06-02 02:21:54', 'dine-in'),
(21, 2, 'completed', 'tunai', 4000, '2025-06-02 02:21:57', 'dine-in'),
(22, 2, 'process', 'tunai', 5000, '2025-06-02 02:22:00', 'dine-in'),
(23, NULL, 'completed', 'tunai', 54000, '2025-06-02 02:22:11', 'dine-in'),
(24, NULL, 'completed', 'tunai', 5000, '2025-06-02 02:22:15', 'dine-in'),
(25, NULL, 'completed', 'tunai', 32000, '2025-06-02 02:22:21', 'dine-in'),
(26, NULL, 'process', 'tunai', 16000, '2025-06-02 18:47:39', 'dine-in');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders_items`
--

CREATE TABLE `orders_items` (
  `id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `menus_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders_items`
--

INSERT INTO `orders_items` (`id`, `orders_id`, `menus_id`, `quantity`, `subtotal`) VALUES
(1, 1, 2, 1, 15000),
(2, 1, 5, 3, 12000),
(3, 2, NULL, 1, 16000),
(4, 2, 1, 1, 18000),
(5, 3, 2, 1, 15000),
(6, 4, 1, 1, 15000),
(7, 6, 2, 4, 60000),
(8, 7, 4, 5, 25000),
(9, 8, NULL, 2, 32000),
(10, 9, 2, 2, 30000),
(11, 10, 4, 1, 0),
(12, 10, 2, 1, 15000),
(13, 10, 4, 1, 5000),
(14, 11, 2, 2, 30000),
(15, 12, 1, 3, 54000),
(16, 13, 1, 1, 18000),
(17, 14, 2, 1, 15000),
(18, 15, NULL, 2, 32000),
(19, 16, 1, 3, 54000),
(20, 17, 2, 1, 15000),
(21, 18, 2, 3, 45000),
(22, 19, NULL, 1, 16000),
(23, 20, 2, 4, 60000),
(24, 21, 5, 1, 4000),
(25, 22, 4, 1, 5000),
(26, 23, 1, 3, 54000),
(27, 24, 4, 1, 5000),
(28, 25, NULL, 2, 32000),
(29, 26, NULL, 1, 16000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `menus_id` int(11) DEFAULT NULL,
  `stocks_name` varchar(100) NOT NULL,
  `stock_in` int(11) NOT NULL,
  `stock_out` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `current_stock` int(11) GENERATED ALWAYS AS (`stock_in` - `stock_out`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stocks`
--

INSERT INTO `stocks` (`id`, `menus_id`, `stocks_name`, `stock_in`, `stock_out`, `updated_at`) VALUES
(1, 1, 'Belut Segar', 20, 15, '2025-06-02 02:22:11'),
(2, 2, 'Lele Hidup', 30, 19, '2025-06-02 02:21:54'),
(3, 15, 'Daging Ayam', 50, 29, '2025-06-15 05:44:56'),
(4, 4, 'Teh Celup', 100, 22, '2025-06-02 02:22:15'),
(5, 5, 'Beras', 50, 16, '2025-06-02 02:21:57'),
(8, 12, 'Daging bebek', 60, 20, '2025-06-15 05:48:55'),
(9, 13, 'Daging bebek', 60, 20, '2025-06-15 05:48:55'),
(10, 14, 'Kepala / ati ampela bebek', 30, 15, '2025-06-15 05:48:55'),
(11, 16, 'Kepala / ati ampela ayam', 20, 5, '2025-06-15 05:48:55'),
(12, 17, 'Ikan Wader', 20, 3, '2025-06-15 05:48:55'),
(13, 18, 'Tahu tempe mentah', 16, 5, '2025-06-15 05:48:55'),
(14, 19, 'Tahu Tempe Telur', 16, 5, '2025-06-15 05:48:55'),
(15, 20, 'Buah Jeruk', 50, 4, '2025-06-15 05:48:55'),
(16, 21, 'Buah Jeruk', 50, 4, '2025-06-15 05:48:55'),
(17, 22, 'Teh Celup', 100, 22, '2025-06-15 05:48:55'),
(18, 23, 'Aqua', 20, 10, '2025-06-15 05:48:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `payment_method` enum('tunai','qris') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `amount`, `payment_method`, `created_at`) VALUES
(1, 1, 27000, 'tunai', '2025-05-23 08:09:29'),
(2, 2, 34000, 'qris', '2025-05-23 08:09:29'),
(3, 3, 15000, 'tunai', '2025-05-25 14:02:36'),
(4, 4, 18000, 'qris', '2025-05-25 14:02:36'),
(5, 6, 60000, 'tunai', '2025-05-31 01:48:17'),
(6, 7, 25000, 'qris', '2025-05-31 01:48:17'),
(7, 8, 32000, 'qris', '2025-05-31 01:48:17'),
(8, 13, 18000, 'tunai', '2025-06-01 01:48:17'),
(9, 23, 54000, 'tunai', '2025-06-02 02:28:20'),
(10, 25, 32000, 'tunai', '2025-06-02 02:28:32'),
(11, 10, 20000, 'tunai', '2025-06-11 14:35:45'),
(12, 18, 45000, 'tunai', '2025-06-11 14:35:46'),
(13, 20, 60000, 'tunai', '2025-06-11 14:35:47'),
(14, 21, 4000, 'tunai', '2025-06-11 14:35:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `users_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('member','admin') NOT NULL,
  `point` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `users_name`, `password`, `role`, `point`, `created_at`) VALUES
(1, 'damars0', '$2y$10$AhcE3qMIN9i/.xTq28ofF.x2CUyW8sXq7L.gYyrX7fCFhIRLPXaBW', 'member', 20, '2025-05-23 07:34:50'),
(2, 'damars1', '$2y$10$SvD2x43Z9RRNjLwGOMMT/OYXGvtIUP6XCrezJ6C4KboJtneg4CQj.', 'member', 155, '2025-05-23 07:34:50'),
(4, 'fikrah0', '$2y$10$SvD2x43Z9RRNjLwGOMMT/OYXGvtIUP6XCrezJ6C4KboJtneg4CQj.', 'admin', 0, '2025-05-23 07:35:28'),
(5, 'fikrah1', '$2y$10$SvD2x43Z9RRNjLwGOMMT/OYXGvtIUP6XCrezJ6C4KboJtneg4CQj.', 'admin', 0, '2025-05-23 07:35:28'),
(6, 'fikrah2', '$2y$10$SvD2x43Z9RRNjLwGOMMT/OYXGvtIUP6XCrezJ6C4KboJtneg4CQj.', 'admin', 0, '2025-05-23 07:35:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menus_category_id` (`category_id`);

--
-- Indeks untuk tabel `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_users_id` (`users_id`);

--
-- Indeks untuk tabel `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_items_orders_id` (`orders_id`),
  ADD KEY `fk_orders_items_menus_id` (`menus_id`);

--
-- Indeks untuk tabel `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stocks_menus_id` (`menus_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transactions_orders_id` (`order_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_category_id` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `fk_orders_items_menus_id` FOREIGN KEY (`menus_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_items_orders_id` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `fk_stocks_menus_id` FOREIGN KEY (`menus_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_orders_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
