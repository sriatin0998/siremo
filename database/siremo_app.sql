-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 06:28 AM
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
-- Database: `siremo_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `denda_log`
--

CREATE TABLE `denda_log` (
  `id_denda` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `tanggal_denda` datetime NOT NULL,
  `jenis_denda` varchar(100) NOT NULL,
  `jumlah_denda` decimal(10,0) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `merek` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `plat_nomor` varchar(15) NOT NULL,
  `tahun` year(4) NOT NULL,
  `warna` varchar(30) DEFAULT NULL,
  `tarif_sewa_per_hari` decimal(10,0) NOT NULL,
  `status_ketersediaan` enum('Tersedia','Disewa','Perawatan') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyewa`
--

CREATE TABLE `penyewa` (
  `id_penyewa` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_sewa`
--

CREATE TABLE `transaksi_sewa` (
  `id_transaksi` int(11) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `id_penyewa` int(11) NOT NULL,
  `tgl_sewa` date NOT NULL,
  `tgl_rencana_kembali` date NOT NULL,
  `tgl_aktual_kembali` date DEFAULT NULL,
  `lama_sewa_hari` int(11) NOT NULL,
  `total_bayar` decimal(12,0) NOT NULL,
  `status_transaksi` enum('Pending','Disewa','Selesai') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `ulasan` text NOT NULL,
  `rating` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','penyewa') NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`, `email`) VALUES
(1, 'admin1', '$2y$10$g/YWc1/xkdTTpOx6v.srJO5Z.cuUfuJhPLaAt5aYyivuUmvRY/I2q', 'Admin1', 'admin', 'admin1@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `denda_log`
--
ALTER TABLE `denda_log`
  ADD PRIMARY KEY (`id_denda`),
  ADD UNIQUE KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indexes for table `penyewa`
--
ALTER TABLE `penyewa`
  ADD PRIMARY KEY (`id_penyewa`),
  ADD UNIQUE KEY `no_ktp` (`no_ktp`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaksi_sewa`
--
ALTER TABLE `transaksi_sewa`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_mobil` (`id_mobil`),
  ADD KEY `id_penyewa` (`id_penyewa`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `denda_log`
--
ALTER TABLE `denda_log`
  MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penyewa`
--
ALTER TABLE `penyewa`
  MODIFY `id_penyewa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_sewa`
--
ALTER TABLE `transaksi_sewa`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
