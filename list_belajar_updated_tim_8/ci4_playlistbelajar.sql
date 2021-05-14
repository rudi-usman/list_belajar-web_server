-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2021 at 04:03 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci4_playlistbelajar`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `id_bookmark` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  `marked_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`id_bookmark`, `id_akun`, `id_playlist`, `marked_at`) VALUES
(61, 2, 2, '2021-04-15 20:00:01'),
(100, 1, 2, '2021-04-23 15:10:59');

-- --------------------------------------------------------

--
-- Table structure for table `konten`
--

CREATE TABLE `konten` (
  `id_materi` int(11) NOT NULL,
  `id_tipe` int(11) NOT NULL,
  `id_file` int(11) NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `konten`
--

INSERT INTO `konten` (`id_materi`, `id_tipe`, `id_file`, `nama_file`, `link`) VALUES
(5, 4, 5, NULL, 'https://www.youtube.com/embed/qXtARcBbDpg'),
(6, 2, 6, 'dnavsrna.pdf', NULL),
(7, 1, 7, '', 'https://id.wikipedia.org/wiki/Hierarki_kebutuhan_Maslow'),
(8, 2, 8, 'Hierarki.pdf', ''),
(10, 3, 10, 'HIERARKISKRIPSI_1.pdf', ''),
(11, 4, 11, '', 'https://www.youtube.com/embed/Hfpm7tkoay4'),
(13, 1, 13, '', 'https://tanya-tanya.com/contoh-soal-pembahasan-uas-kimia-sem-1-kelas-xii/'),
(14, 2, 14, 'SOALKIMIA.pdf', ''),
(15, 3, 15, '', 'https://www.bospedia.com/2019/01/soal.uas.kimia.kelas.xii.sma.ma.2019.2020.2021.2022.html'),
(16, 4, 16, 'WWWM.mp4', '');

-- --------------------------------------------------------

--
-- Table structure for table `materi`
--

CREATE TABLE `materi` (
  `id_playlist` int(11) NOT NULL,
  `id_materi` int(11) NOT NULL,
  `id_tipe` int(11) NOT NULL,
  `nama_materi` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `materi`
--

INSERT INTO `materi` (`id_playlist`, `id_materi`, `id_tipe`, `nama_materi`, `updated_at`) VALUES
(2, 5, 4, 'Video 1', '2021-04-14 10:40:14'),
(2, 6, 2, 'Catatan', '2021-04-14 17:23:11'),
(5, 7, 1, 'Hierarki : Mashlow Wikipedia', '2021-04-23 13:40:07'),
(5, 8, 2, 'Hierarki : Mashlow', '2021-04-23 13:45:05'),
(5, 10, 3, 'Hierarki : Mashlow Catatan', '2021-04-23 13:52:51'),
(5, 11, 4, 'Hierarki : Mashlow Video', '2021-04-23 13:54:31'),
(7, 13, 1, 'Belajar Soal UAS', '2021-04-23 14:05:27'),
(7, 14, 2, 'Soal UAS Kelas 10-12', '2021-04-23 14:08:15'),
(7, 15, 3, 'Kumpulan soal lengkap ', '2021-04-23 14:09:41'),
(7, 16, 4, 'Video Kumpulan UAS', '2021-04-23 14:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_belajar`
--

CREATE TABLE `playlist_belajar` (
  `id_akun` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL,
  `profile_pict` varchar(255) NOT NULL,
  `nama_playlist` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist_belajar`
--

INSERT INTO `playlist_belajar` (`id_akun`, `id_playlist`, `profile_pict`, `nama_playlist`, `kategori`, `deskripsi`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 'alligator.png', 'DNA VS RNA', 'Biologi', 'DNA dan RNA masih sangat banyak diperdebatkan dikalangan ilmuwan, sehingga playlist ini akan lebih menjelaskan bagaimana perbedaan keduanya.', 186, '2021-04-14 10:11:47', '2021-04-14 10:40:14'),
(4, 3, 'web_design.png', 'Kingdom : Plantae', 'Biologi', 'Playlist ini mejelaskan tentang sistem kingdom yang lebih di khususkan pada jenis plantae', 23, '2021-04-14 11:06:56', '2021-04-14 11:06:56'),
(5, 4, 'web_design_1.png', 'Majas Hiperbola', 'Bahasa Indonesia', 'Playlist ini menjelaskan tentang bagaimana Majas Hiperbola dipergunakan, sehingga dapat mengetahui tata cara untuk melakukannya', 25, '2021-04-14 11:12:37', '2021-04-14 11:12:37'),
(1, 5, 'maslow-pyramid.jpg', 'Pyramid Mashlow', 'Sejarah', 'Playlist ini merupakan piramid yang menjelaskan sikap berprilaku manusia yang sehat.', 41, '2021-04-23 13:37:21', '2021-04-23 13:57:48'),
(6, 7, 'kimia uas.jpg', 'Kimia : Bahan UAS', 'Kimia', 'Playlist ini digunakan sebagai referensi untuk bahan UAS pada tahun 2018.', 16, '2021-04-23 14:02:20', '2021-04-23 14:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `tipe`
--

CREATE TABLE `tipe` (
  `id_tipe` int(11) NOT NULL,
  `tipe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tipe`
--

INSERT INTO `tipe` (`id_tipe`, `tipe`) VALUES
(1, 'url'),
(2, 'file'),
(3, 'catatan'),
(4, 'video');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`id_bookmark`);

--
-- Indexes for table `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`id_file`);

--
-- Indexes for table `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id_materi`);

--
-- Indexes for table `playlist_belajar`
--
ALTER TABLE `playlist_belajar`
  ADD PRIMARY KEY (`id_playlist`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookmark`
--
ALTER TABLE `bookmark`
  MODIFY `id_bookmark` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `konten`
--
ALTER TABLE `konten`
  MODIFY `id_file` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `materi`
--
ALTER TABLE `materi`
  MODIFY `id_materi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `playlist_belajar`
--
ALTER TABLE `playlist_belajar`
  MODIFY `id_playlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
