-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Des 2019 pada 08.42
-- Versi server: 10.3.16-MariaDB
-- Versi PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-learning`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'guru', 'Pembuat Soal dan ujian'),
(3, 'siswa', 'Peserta Ujian');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` char(12) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `id_user`, `nip`, `nama_guru`, `email`, `mapel_id`, `kelas_id`) VALUES
(10, 20, '11111111', 'Nadiem Makar', 'nadiem@gmail.com', 8, '13,14,15'),
(11, 21, '22222222', 'Wishnutama', 'wisnu@gmail.com', 8, '16,17'),
(12, 22, '33333333', 'Erick Tohir', 'erick@gmail.com', 2, '13,14,15'),
(13, 23, '44444444', 'Sandiaga Uno', 'sandi@gmail.com', 2, '16,17'),
(14, 33, '87654321', 'Zeddin Guru', 'zeddin@guru.com', 2, '14'),
(16, 0, '98129812990', 'Joko Widodo', 'jokowi@ri.go.id', 8, '13,14,15'),
(17, 0, '91203813013', 'Ma\'ruf Amin', 'maruf@ri.go.id', 8, '15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_tugas`
--

CREATE TABLE `hasil_tugas` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `list_soal` longtext CHARACTER SET utf8 NOT NULL,
  `jenis_tugas` enum('tugas','kuis') DEFAULT NULL,
  `list_jawaban` longtext CHARACTER SET utf8 NOT NULL,
  `jml_benar` int(11) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') CHARACTER SET utf8 NOT NULL,
  `jenis_soal` enum('pilgan','essay') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `hasil_tugas`
--

INSERT INTO `hasil_tugas` (`id`, `tugas_id`, `siswa_id`, `list_soal`, `jenis_tugas`, `list_jawaban`, `jml_benar`, `nilai`, `nilai_bobot`, `tgl_mulai`, `tgl_selesai`, `status`, `jenis_soal`) VALUES
(19, 12, 6, '<p>Jelaskan tentang diri anda !</p>', 'tugas', '<p>ayoooooo</p>', 0, '90.00', '0.00', '2019-12-09 11:25:44', '2019-12-09 11:25:44', 'N', 'essay'),
(21, 14, 6, '<p>Jelaskan tentang diri anda !</p>', 'kuis', '<p>awdawdawdawd</p>', 0, '0.00', '0.00', '2019-12-09 11:26:30', '2019-12-09 11:29:30', 'N', 'essay'),
(23, 11, 6, '18,19,20', 'tugas', '18:A:N,19:C:N,20:B:N', 3, '100.00', '100.00', '2019-12-09 12:09:50', '2019-12-09 12:09:50', 'N', 'pilgan'),
(24, 13, 6, '18,19,20', 'kuis', '18::N,19::N,20::N', 0, '0.00', '100.00', '2019-12-09 12:26:53', '2019-12-09 12:29:53', 'N', 'pilgan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_ujian`
--

CREATE TABLE `hasil_ujian` (
  `id` int(11) NOT NULL,
  `ujian_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `jenis_soal` enum('pilgan','essay') NOT NULL,
  `list_soal` longtext NOT NULL,
  `list_jawaban` longtext NOT NULL,
  `jml_benar` int(11) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'IPA'),
(2, 'IPS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(30) NOT NULL,
  `jurusan_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `jurusan_id`) VALUES
(13, '10 IPA 1', 1),
(14, '10 IPA 2', 1),
(15, '10 IPA 3', 1),
(16, '10 IPA 4', 1),
(17, '10 IPA 5', 1),
(18, '10 IPS 1', 2),
(19, '10 IPS 2', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `nama_mapel` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `nama_mapel`) VALUES
(1, 'Bhs Indonesia'),
(2, 'Matematika'),
(8, 'Bhs Inggris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nis` char(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `kelas_id` int(11) NOT NULL COMMENT 'kelas&jurusan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `id_user`, `nama`, `nis`, `email`, `jenis_kelamin`, `kelas_id`) VALUES
(6, 24, 'Muhammad Rayyan', '00111222', 'rayyan@gmail.com', 'L', 13),
(7, 25, 'Zeddin Arief', '00111223', 'zeddin@gmail.com', 'L', 13),
(8, 26, 'Shafitri N P', '00111224', 'sasa@gmail.com', 'P', 13),
(9, 28, 'Hugo Ghally', '00111226', 'hugo@gmail.com', 'L', 14),
(10, 27, 'Fajri Fernanda', '00111225', 'fajri@gmail.com', 'L', 14),
(11, 29, 'Maulidiya Qurrota', '00111227', 'maul@gmail.com', 'P', 14),
(12, 30, 'Achmad Alim', '00111228', 'alim@gmail.com', 'L', 15),
(13, 31, 'Ubaidillah Hakim Fadly', '00111229', 'ubed@gmail.com', 'L', 15),
(14, 32, 'Wahyu Ridiansyah', '00111230', 'wahyu@gmail.com', 'L', 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `jenis_soal` varchar(6) NOT NULL,
  `bobot` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `tipe_file` varchar(50) NOT NULL,
  `topik` varchar(20) NOT NULL,
  `soal` longtext NOT NULL,
  `opsi_a` longtext NOT NULL,
  `opsi_b` longtext NOT NULL,
  `opsi_c` longtext NOT NULL,
  `opsi_d` longtext NOT NULL,
  `opsi_e` longtext NOT NULL,
  `file_a` varchar(255) NOT NULL,
  `file_b` varchar(255) NOT NULL,
  `file_c` varchar(255) NOT NULL,
  `file_d` varchar(255) NOT NULL,
  `file_e` varchar(255) NOT NULL,
  `jawaban` varchar(5) NOT NULL,
  `created_on` int(11) NOT NULL,
  `updated_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id_soal`, `guru_id`, `mapel_id`, `jenis_soal`, `bobot`, `file`, `tipe_file`, `topik`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `file_a`, `file_b`, `file_c`, `file_d`, `file_e`, `jawaban`, `created_on`, `updated_on`) VALUES
(18, 10, 8, 'pilgan', 1, '', '', '18,20,28,29', '<p>Jawabannya A</p>', '<p>kuda</p>', '<p>kijang</p>', '<p>kancil</p>', '<p>kelinci</p>', '<p>kecoa</p>', '', '', '', '', '', 'A', 1575114336, 1575212688),
(19, 10, 8, 'pilgan', 1, '', '', '18,20,28,29', '<p>Jawabannya C</p>', '<p>tikus</p>', '<p>tupai</p>', '<p>trenggiling</p>', '<p>tapir</p>', '<p>tokek</p>', '', '', '', '', '', 'C', 1575114395, 1575212679),
(20, 10, 8, 'pilgan', 1, '', '', '18,20,28,29', '<p>Jawabannya B</p>', '<p>tikus</p>', '<p>tupai</p>', '<p>trenggiling</p>', '<p>tapir</p>', '<p>tokek</p>', '', '', '', '', '', 'B', 0, 1575212701),
(21, 10, 8, 'essay', 1, '', '', '18', '<p>Jelaskan tentang diri anda !</p>', '', '', '', '', '', '', '', '', '', '', '', 1575114486, 1575114486),
(22, 10, 8, 'essay', 1, '', '', '19', '<p>jelaskan kenapa ayam bertelur !</p>', '', '', '', '', '', '', '', '', '', '', '', 1575127782, 1575127782),
(23, 12, 2, 'pilgan', 1, '', '', '6,16,17', '<p>1+1 = ....</p>', '<p>2</p>', '<p>3</p>', '<p>1</p>', '<p>21</p>', '<p>4</p>', '', '', '', '', '', 'A', 1575360382, 1575360542),
(24, 12, 2, 'pilgan', 1, '', '', '6,16,17', '<p>2+2 = ....</p>', '<p>2</p>', '<p>1</p>', '<p>3</p>', '<p>4</p>', '<p>5</p>', '', '', '', '', '', 'D', 1575360411, 1575360532),
(25, 12, 2, 'essay', 1, '', '', '6,16,17', '<p>jelaskan trigonometri!!!</p>', '', '', '', '', '', '', '', '', '', '', '', 1575360430, 1575360442);

-- --------------------------------------------------------

--
-- Struktur dari tabel `topik`
--

CREATE TABLE `topik` (
  `id_topik` int(5) NOT NULL,
  `kelas` enum('10','11','12') DEFAULT NULL,
  `nama_topik` varchar(255) NOT NULL,
  `mapel_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `topik`
--

INSERT INTO `topik` (`id_topik`, `kelas`, `nama_topik`, `mapel_id`) VALUES
(6, '10', 'MTK-KD1', 2),
(7, '10', 'MTK-KD2', 2),
(8, '10', 'MTK-KD3', 2),
(9, '10', 'MTK-KD4', 2),
(10, '10', 'MTK-KD5', 2),
(11, '10', 'MTK-KD6', 2),
(12, '10', 'MTK-KD7', 2),
(13, '10', 'MTK-KD8', 2),
(14, '10', 'MTK-KD9', 2),
(15, '10', 'MTK-KD10', 2),
(18, '10', 'BIG-KD1', 8),
(19, '10', 'BIG-KD2', 8),
(20, '10', 'BIG-KD3', 8),
(21, '10', 'BIG-KD4', 8),
(22, '10', 'BIG-KD5', 8),
(23, '10', 'BIG-KD6', 8),
(24, '10', 'BIG-KD7', 8),
(25, '10', 'BIG-KD8', 8),
(26, '10', 'BIG-KD9', 8),
(27, '10', 'BIG-KD10', 8),
(30, '11', 'BIG-1', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `topik_id` int(11) NOT NULL,
  `jenis_tugas` enum('tugas','kuis') NOT NULL,
  `nama_tugas` varchar(200) CHARACTER SET utf8 NOT NULL,
  `jumlah_soal` int(11) NOT NULL,
  `jenis` enum('acak','urut') CHARACTER SET utf8 NOT NULL,
  `waktu` int(11) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `terlambat` datetime NOT NULL,
  `token` varchar(5) CHARACTER SET utf8 NOT NULL,
  `jenis_soal` enum('pilgan','essay') CHARACTER SET utf8 NOT NULL,
  `id_soal_essay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `guru_id`, `mapel_id`, `topik_id`, `jenis_tugas`, `nama_tugas`, `jumlah_soal`, `jenis`, `waktu`, `tgl_mulai`, `terlambat`, `token`, `jenis_soal`, `id_soal_essay`) VALUES
(11, 10, 8, 18, 'tugas', 'Tugas 1', 3, 'acak', 0, '2019-12-09 11:20:00', '2019-12-10 11:20:02', 'WAGWT', 'pilgan', 0),
(12, 10, 8, 18, 'tugas', 'Tugas 2', 1, 'acak', 0, '2019-12-09 11:21:41', '2019-12-10 11:21:44', 'ZCIHC', 'essay', 21),
(13, 10, 8, 18, 'kuis', 'Kuis 1', 3, 'urut', 3, '2019-12-09 11:23:29', '2019-12-10 11:23:31', 'OUIXM', 'pilgan', 0),
(14, 10, 8, 18, 'kuis', 'Kuis 2', 1, 'acak', 3, '2019-12-09 11:24:05', '2019-12-10 11:24:07', 'QQAXI', 'essay', 21);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ujian`
--

CREATE TABLE `ujian` (
  `id_ujian` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nilai_uts` int(5) DEFAULT NULL,
  `nilai_uas` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ujian`
--

INSERT INTO `ujian` (`id_ujian`, `guru_id`, `mapel_id`, `id_siswa`, `kelas_id`, `nilai_uts`, `nilai_uas`) VALUES
(35, 10, 8, 6, 13, 50, 90),
(37, 10, 8, 8, 13, 80, 80),
(38, 10, 8, 7, 13, 90, 90),
(39, 10, 8, 12, 15, 90, 80),
(40, 10, 8, 13, 15, 80, 90),
(41, 10, 8, 14, 15, 90, 100),
(42, 10, 8, 10, 14, 90, 80),
(43, 10, 8, 9, 14, 80, 89),
(44, 10, 8, 11, 14, 90, 89);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
(1, '127.0.0.1', 'admin', '$2y$12$duBtimAQzpTC6CaH9MFOheoCN5ss48Fe/i1PeO236GTPxozToItZG', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1575787602, 1, 'Admin', 'Istrator', 'ADMIN', '0'),
(20, '::1', '11111111', '$2y$10$1TxtDxsflgJgNwsV9RP4HePBwB1f1XDdRqshRLaxi3IIbVQPiAaxS', 'nadiem@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575112954, 1575691957, 1, 'Nadiem', 'Makar', NULL, NULL),
(21, '::1', '22222222', '$2y$10$7SKToztmx9fL.tW9Rh2RqeZxa6ganahqiusYUlIByQsXU/T6uPmXm', 'wisnu@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113094, 1575538931, 1, 'Wishnutama', 'Wishnutama', NULL, NULL),
(22, '::1', '33333333', '$2y$10$dKWq4qRRkx4I.acUYGjRBeKvmkdXLuzVoMXBKZxiJ/qY5hAwtiaqi', 'erick@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113097, 1575360342, 1, 'Erick', 'Tohir', NULL, NULL),
(23, '::1', '44444444', '$2y$10$Ky/sMRSkSn1nKwrwaoPF6OSedDL/7jWOYckjaEMtHurbLlkvB3qVy', 'sandi@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113100, NULL, 1, 'Sandiaga', 'Uno', NULL, NULL),
(24, '::1', '00111222', '$2y$10$sMs4VBEG6DcFwQhxx1mLMONHyvI3Hf9rIQADRX7WjnyKnggzLr/3m', 'rayyan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113400, 1575865228, 1, 'Muhammad', 'Rayyan', NULL, NULL),
(25, '::1', '00111223', '$2y$10$cLWqId1W65.GgYy9vsYVtu54C1CcLxN3ODxAA72qyvjIhC4V2Offq', 'zeddin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113402, 1575541512, 1, 'Zeddin', 'Arief', NULL, NULL),
(26, '::1', '00111224', '$2y$10$gpntgciL54po8.CAxIRYeeCgxTR7qU3b8scd8LiZsM8VMgknPewvG', 'sasa@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113480, NULL, 1, 'Shafitri', 'P', NULL, NULL),
(27, '::1', '00111225', '$2y$10$YUvwgZ6sNl40uMYwy6tLLuj3vF5fAqjhcRHX4plVsK4aMftqf5sVS', 'fajri@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113556, 1575118364, 1, 'Fajri', 'Fernanda', NULL, NULL),
(28, '::1', '00111226', '$2y$10$R9rCJKNG0aRn7QeLLSDACu3MbPb12GVU3Xuyb29nx135vKA5w6Jy2', 'hugo@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113559, NULL, 1, 'Hugo', 'Ghally', NULL, NULL),
(29, '::1', '00111227', '$2y$10$KZnaO6zKeTAfdlcmv3/H2ONKFrVUMOIQc.SKPN62Ep1Vd55XKE92a', 'maul@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113674, NULL, 1, 'Maulidiya', 'Qurrota', NULL, NULL),
(30, '::1', '00111228', '$2y$10$KuLP4bjz7PT4B5cwAom6Ueok.k/mY54KQNhAocLJc3dqCetAjf1KK', 'alim@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113677, NULL, 1, 'Achmad', 'Alim', NULL, NULL),
(31, '::1', '00111229', '$2y$10$.chWhJrnsmc0esSX5tsMg.Pxg5rs2OOLkVnBY67oIPbqJCxfvF2aC', 'ubed@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113679, NULL, 1, 'Ubaidillah', 'Fadly', NULL, NULL),
(32, '::1', '00111230', '$2y$10$XcNdoU15EWQJAqJQBAKGc.lsC7lyeECIw5r7RhJ0qv7AAm3k6kpbK', 'wahyu@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575113720, NULL, 1, 'Wahyu', 'Ridiansyah', NULL, NULL),
(33, '::1', '87654321', '$2y$10$mjLt1EMSaC.oqEsLmuYtiuP8cHWcwh5UlwSzipNVAuFCflURJab/.', 'zeddin@guru.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1575301340, NULL, 1, 'Zeddin', 'Guru', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(22, 20, 2),
(23, 21, 2),
(24, 22, 2),
(25, 23, 2),
(26, 24, 3),
(27, 25, 3),
(28, 26, 3),
(29, 27, 3),
(30, 28, 3),
(31, 29, 3),
(32, 30, 3),
(33, 31, 3),
(34, 32, 3),
(35, 33, 2);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`) USING BTREE,
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `hasil_tugas`
--
ALTER TABLE `hasil_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ujian_id` (`ujian_id`),
  ADD KEY `mahasiswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`) USING BTREE,
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD KEY `mapel_id` (`mapel_id`) USING BTREE,
  ADD KEY `guru_id` (`guru_id`) USING BTREE;

--
-- Indeks untuk tabel `topik`
--
ALTER TABLE `topik`
  ADD PRIMARY KEY (`id_topik`) USING BTREE,
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`);

--
-- Indeks untuk tabel `ujian`
--
ALTER TABLE `ujian`
  ADD PRIMARY KEY (`id_ujian`) USING BTREE,
  ADD KEY `matkul_id` (`mapel_id`),
  ADD KEY `dosen_id` (`guru_id`),
  ADD KEY `topik_id` (`kelas_id`),
  ADD KEY `siswa_id` (`id_siswa`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`),
  ADD UNIQUE KEY `uc_email` (`email`) USING BTREE;

--
-- Indeks untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `hasil_tugas`
--
ALTER TABLE `hasil_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `topik`
--
ALTER TABLE `topik`
  MODIFY `id_topik` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `ujian`
--
ALTER TABLE `ujian`
  MODIFY `id_ujian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_tugas`
--
ALTER TABLE `hasil_tugas`
  ADD CONSTRAINT `hasil_tugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id_tugas`),
  ADD CONSTRAINT `hasil_tugas_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD CONSTRAINT `hasil_ujian_ibfk_1` FOREIGN KEY (`ujian_id`) REFERENCES `ujian` (`id_ujian`),
  ADD CONSTRAINT `hasil_ujian_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD CONSTRAINT `soal_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`),
  ADD CONSTRAINT `soal_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`);

--
-- Ketidakleluasaan untuk tabel `topik`
--
ALTER TABLE `topik`
  ADD CONSTRAINT `topik_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `ujian`
--
ALTER TABLE `ujian`
  ADD CONSTRAINT `ujian_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `ujian_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`),
  ADD CONSTRAINT `ujian_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `ujian_ibfk_4` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
