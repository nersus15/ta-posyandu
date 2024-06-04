-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 01:27 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posyandu2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bayi`
--

CREATE TABLE `bayi` (
  `id` varchar(8) NOT NULL,
  `nama` varchar(46) DEFAULT NULL,
  `pencatat` varchar(46) NOT NULL,
  `umur` int(11) NOT NULL COMMENT 'dalam hari',
  `kelamin` enum('L','P') NOT NULL,
  `bbl` int(11) DEFAULT NULL COMMENT 'dalam gr',
  `akb` int(11) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `ttl_estimasi` tinyint(1) NOT NULL DEFAULT 0,
  `alamat` varchar(92) NOT NULL,
  `ayah` varchar(46) NOT NULL,
  `ibu` varchar(46) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bayi`
--

INSERT INTO `bayi` (`id`, `nama`, `pencatat`, `umur`, `kelamin`, `bbl`, `akb`, `tanggal_lahir`, `ttl_estimasi`, `alamat`, `ayah`, `ibu`, `createdAt`) VALUES
('27TyoLDC', 'Anak 1', 'kader', 34, 'L', 240, 121, '2024-05-01', 0, 'Konoha', 'Minato', 'Kushina', '2024-05-24 15:34:42'),
('EIUu7l9V', 'Anak 2', 'kader', 358, 'L', 240, 121, '2023-06-12', 0, 'Kepo', 'Minato', 'Kushina', '2024-06-04 14:47:33'),
('hVsBEyeG', '', 'kader', 11, 'P', 200, 12, '2024-05-13', 0, 'testes', 'adad', 'Ibu Aja', '2024-05-24 15:44:45'),
('NirbbfLJ', 'Anak 2', 'bidan2', 25, 'L', 240, 23, '2024-04-27', 1, 'Sukarara', 'Minato', 'Kushina', '2024-05-23 04:44:11'),
('NirbbfLK', 'Anak 1', 'bidan1', 25, 'L', 240, 23, '2024-04-27', 1, 'Sukarara', 'Minato', 'Kushina', '2024-05-23 04:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `bumil`
--

CREATE TABLE `bumil` (
  `id` varchar(8) NOT NULL,
  `nomor` varchar(10) DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `nama` varchar(46) NOT NULL,
  `nama_suami` varchar(46) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `ttl_estimasi` enum('1','0') NOT NULL DEFAULT '0',
  `domisili` varchar(92) DEFAULT NULL,
  `alamat` varchar(92) NOT NULL,
  `pendidikan` varchar(8) NOT NULL DEFAULT '-',
  `pekerjaan` varchar(46) DEFAULT NULL,
  `agama` enum('-','islam','kristen katolik','hindu','kristen protestan','buda','konghucu') NOT NULL DEFAULT '-',
  `kartu_kesehatan` varchar(46) DEFAULT NULL,
  `golongan_darah` enum('-','A','B','O','AB') NOT NULL DEFAULT '-',
  `hp` varchar(15) DEFAULT NULL,
  `pencatat` varchar(46) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bumil`
--

INSERT INTO `bumil` (`id`, `nomor`, `rt`, `nama`, `nama_suami`, `tanggal_lahir`, `ttl_estimasi`, `domisili`, `alamat`, `pendidikan`, `pekerjaan`, `agama`, `kartu_kesehatan`, `golongan_darah`, `hp`, `pencatat`, `createdAt`) VALUES
('ekdPTCu4', '345353221', NULL, 'Kushina', 'Minato', '1999-04-27', '0', 'Konohagakure', 'Uzugakure', '-', 'Shinobi', 'islam', 'jamsostek', '-', '083142808426', 'kader', '2024-05-24 14:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan_anak`
--

CREATE TABLE `kunjungan_anak` (
  `id` int(11) NOT NULL,
  `pencatat` varchar(46) NOT NULL,
  `nama_pemeriksa` varchar(46) NOT NULL,
  `dibuat` datetime NOT NULL DEFAULT current_timestamp(),
  `anak` varchar(8) NOT NULL,
  `tgl_periksa` date NOT NULL,
  `berat` int(11) NOT NULL COMMENT 'dalam gr',
  `tinggi` int(11) NOT NULL COMMENT 'dalam cm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kunjungan_anak`
--

INSERT INTO `kunjungan_anak` (`id`, `pencatat`, `nama_pemeriksa`, `dibuat`, `anak`, `tgl_periksa`, `berat`, `tinggi`) VALUES
(1, 'kader', 'fathurrahman', '2024-05-24 17:01:09', '27TyoLDC', '2024-05-07', 56, 23),
(6, 'kader', 'Saa', '2024-05-25 00:08:26', '27TyoLDC', '2024-01-23', 42, 34),
(8, 'kader', 'fathurrahman', '2024-05-25 00:48:46', '27TyoLDC', '2023-05-01', 43, 23);

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan_bumil`
--

CREATE TABLE `kunjungan_bumil` (
  `id` int(11) NOT NULL,
  `ibu` varchar(8) NOT NULL,
  `faskes` varchar(92) DEFAULT NULL,
  `tgl_periksa` date NOT NULL DEFAULT current_timestamp(),
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `pencatat` varchar(46) NOT NULL,
  `nama_pemeriksa` varchar(46) NOT NULL,
  `posyandu` varchar(46) DEFAULT NULL,
  `dukun` varchar(46) DEFAULT NULL,
  `gravida` int(3) NOT NULL DEFAULT 1,
  `paritas` int(3) NOT NULL DEFAULT 0,
  `abortus` int(3) NOT NULL DEFAULT 0,
  `hidup` int(3) NOT NULL DEFAULT 0,
  `hpht` date DEFAULT NULL,
  `hpl` date DEFAULT NULL,
  `persalinan_sebelumnya` date DEFAULT NULL,
  `bb` int(11) DEFAULT NULL,
  `tb` int(11) DEFAULT NULL,
  `buku_kia` enum('','1','0') NOT NULL DEFAULT '',
  `riwayat_komplikasi` varchar(115) DEFAULT NULL,
  `penyakit` varchar(115) DEFAULT NULL,
  `persalinan_tgl` date DEFAULT NULL,
  `persalinan_penolong` varchar(92) DEFAULT NULL,
  `persalinan_pendamping` varchar(92) DEFAULT NULL,
  `persalinan_tempat` varchar(92) DEFAULT NULL,
  `persalinan_transportasi` varchar(92) DEFAULT NULL,
  `persalinan_pendonor` varchar(92) DEFAULT NULL,
  `persalinan_kunjungan_rumah` varchar(46) DEFAULT NULL,
  `persalinan_kondisi_rumah` varchar(46) DEFAULT NULL,
  `persalinan_persedian` varchar(46) DEFAULT NULL,
  `lila` int(11) DEFAULT NULL,
  `fundus` int(11) DEFAULT NULL,
  `hb` int(11) DEFAULT NULL,
  `usia_kehamilan` int(11) DEFAULT NULL COMMENT 'dalam hari',
  `bj` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kunjungan_bumil`
--

INSERT INTO `kunjungan_bumil` (`id`, `ibu`, `faskes`, `tgl_periksa`, `createdAt`, `pencatat`, `nama_pemeriksa`, `posyandu`, `dukun`, `gravida`, `paritas`, `abortus`, `hidup`, `hpht`, `hpl`, `persalinan_sebelumnya`, `bb`, `tb`, `buku_kia`, `riwayat_komplikasi`, `penyakit`, `persalinan_tgl`, `persalinan_penolong`, `persalinan_pendamping`, `persalinan_tempat`, `persalinan_transportasi`, `persalinan_pendonor`, `persalinan_kunjungan_rumah`, `persalinan_kondisi_rumah`, `persalinan_persedian`, `lila`, `fundus`, `hb`, `usia_kehamilan`, `bj`) VALUES
(1, 'ekdPTCu4', NULL, '2024-05-23', '2024-05-25', 'kader', 'fathurrahman', '', '', 1, 0, 0, 0, '0000-00-00', '2024-06-14', '0000-00-00', 65, 123, '1', '', '', '0000-00-00', '7', '6', '1', '1', '5', '', '', '', 12, 30, 110, 70, 'Kambing'),
(6, 'ekdPTCu4', NULL, '2024-06-03', '2024-06-04', 'bidan1', 'Kepo Deh', 'Lepakk', 'Tidak deee', 2, 1, 0, 1, '2024-06-21', '2024-06-19', '2003-04-23', 12, 123, '1', 'adada', 'adadad', '2024-06-04', '3', '2', '4', '3', '2', 'adada', 'addaaa', 'adadada', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lansia`
--

CREATE TABLE `lansia` (
  `id` varchar(8) NOT NULL,
  `pencatat` varchar(46) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `nama` varchar(46) NOT NULL,
  `alamat` varchar(22) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `estimasi_ttl` tinyint(1) NOT NULL DEFAULT 0,
  `nik` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `lansia`
--

INSERT INTO `lansia` (`id`, `pencatat`, `createdAt`, `nama`, `alamat`, `tanggal_lahir`, `estimasi_ttl`, `nik`) VALUES
('60onecxG', 'kader', '2024-05-24 14:48:43', 'Nenek Chio', 'Sunagakure', '1947-07-08', 1, '5203196408990012'),
('WPFP19Cx', 'kader', '2024-06-04 16:25:34', 'Kushina', 'adadada', '1974-01-15', 0, '5203196408990012');

-- --------------------------------------------------------

--
-- Table structure for table `periksa_lansia`
--

CREATE TABLE `periksa_lansia` (
  `id` int(11) NOT NULL,
  `pencatat` varchar(46) NOT NULL,
  `pemeriksa` varchar(46) NOT NULL,
  `lansia` varchar(8) NOT NULL,
  `tgl_periksa` date NOT NULL,
  `berat` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `periksa_lansia`
--

INSERT INTO `periksa_lansia` (`id`, `pencatat`, `pemeriksa`, `lansia`, `tgl_periksa`, `berat`, `createdAt`) VALUES
(1, 'kader', 'Saya', '60onecxG', '2024-05-23', 60, '0000-00-00 00:00:00'),
(3, 'kader', 'Saya', '60onecxG', '2024-01-15', 60, '0000-00-00 00:00:00'),
(5, 'kader', 'fathurrahman', '60onecxG', '2023-11-01', 20, '0000-00-00 00:00:00'),
(6, 'kader', 'fathurrahman', 'WPFP19Cx', '2024-06-01', 56, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(46) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(92) NOT NULL,
  `alamat` varchar(92) NOT NULL,
  `no_hp` varchar(13) NOT NULL,
  `email` varchar(46) NOT NULL,
  `kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `role` enum('admin','kader','bidan') NOT NULL DEFAULT 'admin',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(46) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `alamat`, `no_hp`, `email`, `kelamin`, `role`, `createdAt`, `photo`) VALUES
('bidan1', '$2y$10$PV53kdtQTxQE2xNFwTW15.FZEeTyzni7Y8qxDWXU.Bc411fLX2osq', 'Tsunade Senju', 'Konohagakureeeee', '0387232322324', 'b@m.com', 'P', 'bidan', '2024-05-21 23:54:58', 'default.jpg'),
('kader', '$2y$10$QZHi3K/AA6.OLva1a9sazeHM67pevlcEonJMi6bAHVvOgAtPLtfv6', 'fathurrahman', 'Desa Sukarara, Kecamatan Sakra Barat, Kabupaten Lombok Timur', '083142808426', 'fathur.ashter15@gmail.com', 'L', 'kader', '2024-05-23 00:37:47', 'default.jpg'),
('kamscode', '$2y$10$bJ3h5wwt1gKSpTOKLw/0CeCMhquVWkrlG8Zb2hH5T5110T6iZMemi', 'Fathurrahman', 'Desa Sukarara, Kec. Sakra Barat, Kab. Lombok Timur', '083142808426', 'fathur.ashter15@gmail.com', 'L', 'admin', '2024-05-21 20:10:44', 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bayi`
--
ALTER TABLE `bayi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pencatat` (`pencatat`);

--
-- Indexes for table `bumil`
--
ALTER TABLE `bumil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kunjungan_anak`
--
ALTER TABLE `kunjungan_anak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kunjungan_anak_anak_foreign` (`anak`);

--
-- Indexes for table `kunjungan_bumil`
--
ALTER TABLE `kunjungan_bumil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lansia`
--
ALTER TABLE `lansia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `periksa_lansia`
--
ALTER TABLE `periksa_lansia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kunjungan_anak`
--
ALTER TABLE `kunjungan_anak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kunjungan_bumil`
--
ALTER TABLE `kunjungan_bumil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `periksa_lansia`
--
ALTER TABLE `periksa_lansia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
