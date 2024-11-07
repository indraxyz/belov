-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 14, 2021 at 05:58 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `belov`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `username`, `password`, `nama`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'indra', '123', 'indra cahya XX', NULL, NULL, '2021-10-12 18:02:21'),
(4, 'ALIHD', 'SIDOARJON11', 'ALIHD', NULL, '2021-10-12 17:00:00', NULL),
(5, 'SITA', 'SIDOARJON11', 'SITA', NULL, '2021-10-12 17:00:00', NULL),
(6, 'NURIFA', 'SIDOARJON11', 'NURIFA', NULL, '2021-10-12 17:00:00', NULL),
(7, 'BANA', 'SIDOARJON11', 'BANA', NULL, '2021-10-12 17:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_akun`
--

CREATE TABLE `detail_akun` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `id_akun` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id` int(11) NOT NULL,
  `no_tiket` varchar(20) DEFAULT NULL,
  `status_peserta` tinyint(1) DEFAULT NULL COMMENT '0/-, 1/non aktif, 2/aktif',
  `pemohon` tinyint(1) DEFAULT NULL,
  `perusahaan` varchar(100) DEFAULT NULL,
  `pic_hrd` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `no_kartu_peserta` varchar(15) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `nohp` varchar(15) DEFAULT NULL,
  `data_perbaikan` varchar(50) DEFAULT NULL COMMENT 'text array/ object',
  `email` varchar(50) DEFAULT NULL,
  `file_kartu_bpjs` varchar(100) DEFAULT NULL,
  `file_ktp` varchar(100) DEFAULT NULL,
  `file_foto` varchar(100) DEFAULT NULL,
  `file_formulir` varchar(100) DEFAULT NULL,
  `status_tiket` tinyint(1) DEFAULT NULL COMMENT '0/baru, 1/proses, 2/ditolak, 3/selesai',
  `id_admin` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tiket_progres`
--

CREATE TABLE `tiket_progres` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `progres` tinyint(1) DEFAULT NULL COMMENT '0 1 2 3',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_akun`
--
ALTER TABLE `detail_akun`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detailAkun_to_akun` (`id_akun`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tiket_to_akun` (`id_admin`);

--
-- Indexes for table `tiket_progres`
--
ALTER TABLE `tiket_progres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tiketProgres_to_tiket` (`id_tiket`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detail_akun`
--
ALTER TABLE `detail_akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `tiket_progres`
--
ALTER TABLE `tiket_progres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_akun`
--
ALTER TABLE `detail_akun`
  ADD CONSTRAINT `detailAkun_to_akun` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_to_akun` FOREIGN KEY (`id_admin`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tiket_progres`
--
ALTER TABLE `tiket_progres`
  ADD CONSTRAINT `tiketProgres_to_tiket` FOREIGN KEY (`id_tiket`) REFERENCES `tiket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
