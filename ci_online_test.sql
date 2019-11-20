-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Nov 2019 pada 06.52
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
-- Database: `ci_online_test`
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
  `nip` char(12) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `mapel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nip`, `nama_guru`, `email`, `mapel_id`) VALUES
(5, '12345678', 'Adam Hendra', 'adam@gmail.com', 2),
(6, '12345679', 'Herman Tolle', 'herman@gmail.com', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `h_ujian`
--

CREATE TABLE `h_ujian` (
  `id` int(11) NOT NULL,
  `ujian_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `list_soal` longtext NOT NULL,
  `list_jawaban` longtext NOT NULL,
  `jml_benar` int(11) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `h_ujian`
--

INSERT INTO `h_ujian` (`id`, `ujian_id`, `siswa_id`, `list_soal`, `list_jawaban`, `jml_benar`, `nilai`, `nilai_bobot`, `tgl_mulai`, `tgl_selesai`, `status`) VALUES
(8, 7, 2, '8,9', '8:A:N,9::N', 1, '50.00', '100.00', '2019-11-19 10:53:30', '2019-11-19 10:54:30', 'N');

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
-- Struktur dari tabel `jurusan_mapel`
--

CREATE TABLE `jurusan_mapel` (
  `id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `jurusan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `jurusan_mapel`
--

INSERT INTO `jurusan_mapel` (`id`, `mapel_id`, `jurusan_id`) VALUES
(8, 2, 1);

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
(14, '10 IPA 2', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_guru`
--

CREATE TABLE `kelas_guru` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `kelas_guru`
--

INSERT INTO `kelas_guru` (`id`, `kelas_id`, `guru_id`) VALUES
(14, 13, 5);

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
(3, 'Biologi'),
(4, 'Fisika'),
(5, 'Kimia'),
(6, 'Agama'),
(7, 'Olahraga'),
(8, 'Bhs Inggris'),
(9, 'TIK'),
(10, 'Seni Budaya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_ujian`
--

CREATE TABLE `m_ujian` (
  `id_ujian` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `nama_ujian` varchar(200) NOT NULL,
  `jumlah_soal` int(11) NOT NULL,
  `waktu` int(11) NOT NULL,
  `jenis` enum('acak','urut') NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `terlambat` datetime NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `m_ujian`
--

INSERT INTO `m_ujian` (`id_ujian`, `guru_id`, `mapel_id`, `nama_ujian`, `jumlah_soal`, `waktu`, `jenis`, `tgl_mulai`, `terlambat`, `token`) VALUES
(7, 5, 2, 'Penjumlahan', 2, 1, 'acak', '2019-11-19 10:42:20', '2019-11-20 10:42:30', 'VXNYE');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nis` char(20) NOT NULL,
  `email` varchar(254) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `kelas_id` int(11) NOT NULL COMMENT 'kelas&jurusan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `email`, `jenis_kelamin`, `kelas_id`) VALUES
(2, 'Shafitri', '99000001', 'shasa@gmail.com', 'P', 13),
(3, 'Muhammad Rayyan Abhad', '99000002', 'murayyan97@gmail.com', 'L', 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
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

INSERT INTO `soal` (`id_soal`, `guru_id`, `mapel_id`, `bobot`, `file`, `tipe_file`, `topik`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `file_a`, `file_b`, `file_c`, `file_d`, `file_e`, `jawaban`, `created_on`, `updated_on`) VALUES
(8, 5, 2, 1, '', '', '1,2', '<p>1+1 = ?</p>', '<p>2</p>', '<p>3</p>', '<p>4</p>', '<p>5</p>', '', '', '', '', '', '', 'A', 1574134873, 1574134873),
(9, 5, 2, 1, '', '', '2', '<p>2-1=?</p>', '<p>2</p>', '<p>3</p>', '<p>4</p>', '<p>1</p>', '<p>0</p>', '', '', '', '', '', 'D', 1574134914, 1574134914),
(10, 6, 2, 1, '', '', '1,2', '<p>12</p>', '<p>12</p>', '<p>12</p>', '<p>12</p>', '<p>12</p>', '<p>12</p>', '', '', '', '', '', 'B', 1574174052, 1574174052),
(11, 6, 2, 1, '', '', '1', '<p>aaaqqwq</p>', '<p>aa</p>', '<p>bb</p>', '<p>cc</p>', '<p>dd</p>', '<p>ee</p>', '', '', '', '', '', 'B', 1574174506, 1574174506);

-- --------------------------------------------------------

--
-- Struktur dari tabel `topik`
--

CREATE TABLE `topik` (
  `id` int(5) NOT NULL,
  `topik` varchar(255) NOT NULL,
  `mapel_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `topik`
--

INSERT INTO `topik` (`id`, `topik`, `mapel_id`) VALUES
(1, 'Aljabar', 2),
(2, 'UTS', 2);

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
(1, '127.0.0.1', 'Administrator', '$2y$12$duBtimAQzpTC6CaH9MFOheoCN5ss48Fe/i1PeO236GTPxozToItZG', 'admin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1574223036, 1, 'Admin', 'Istrator', 'ADMIN', '0'),
(11, '::1', '12345678', '$2y$10$Y19PtbOtFNBlxPMiFG4oJ.BFlvTs3h7aY2Hm1/ENrgWH7tILfAarW', 'adam@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1574134830, 1574224225, 1, 'Adam', 'Hendra', NULL, NULL),
(12, '::1', '99000001', '$2y$10$Eoz5fKX7s29WuPksYWyY5OCtx3u9HkMVQmHzyeNd3DcbRKTIiadd6', 'shasa@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1574135038, 1574135590, 1, 'Shafitri', 'Shafitri', NULL, NULL),
(13, '::1', '99000002', '$2y$10$A3gnbuH6ItUE.QRqhbyXoemMSkriEBLk.pptynuBjBtkXupU3eHHa', 'murayyan97@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1574135116, 1574135130, 1, 'Muhammad', 'Abhad', NULL, NULL),
(14, '::1', '12345679', '$2y$10$XajyRLFoBipTxlp8upSvOuvUzWU1NFrEK4szFCp4s3XD2cWwXlrA.', 'herman@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1574163761, 1574163779, 1, 'Herman', 'Tolle', NULL, NULL);

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
(13, 11, 2),
(14, 12, 3),
(15, 13, 3),
(16, 14, 2);

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
  ADD PRIMARY KEY (`id_guru`) USING BTREE;

--
-- Indeks untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ujian_id` (`ujian_id`),
  ADD KEY `mahasiswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indeks untuk tabel `jurusan_mapel`
--
ALTER TABLE `jurusan_mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jurusan_id` (`jurusan_id`),
  ADD KEY `matkul_id` (`mapel_id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `kelas_guru`
--
ALTER TABLE `kelas_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `dosen_id` (`guru_id`);

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
-- Indeks untuk tabel `m_ujian`
--
ALTER TABLE `m_ujian`
  ADD PRIMARY KEY (`id_ujian`),
  ADD KEY `matkul_id` (`mapel_id`),
  ADD KEY `dosen_id` (`guru_id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`) USING BTREE;

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD KEY `matkul_id` (`mapel_id`),
  ADD KEY `dosen_id` (`guru_id`);

--
-- Indeks untuk tabel `topik`
--
ALTER TABLE `topik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`);

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
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jurusan_mapel`
--
ALTER TABLE `jurusan_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kelas_guru`
--
ALTER TABLE `kelas_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `m_ujian`
--
ALTER TABLE `m_ujian`
  MODIFY `id_ujian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `topik`
--
ALTER TABLE `topik`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `h_ujian`
--
ALTER TABLE `h_ujian`
  ADD CONSTRAINT `h_ujian_ibfk_1` FOREIGN KEY (`ujian_id`) REFERENCES `m_ujian` (`id_ujian`),
  ADD CONSTRAINT `h_ujian_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `jurusan_mapel`
--
ALTER TABLE `jurusan_mapel`
  ADD CONSTRAINT `jurusan_mapel_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id_jurusan`),
  ADD CONSTRAINT `jurusan_mapel_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`);

--
-- Ketidakleluasaan untuk tabel `kelas_guru`
--
ALTER TABLE `kelas_guru`
  ADD CONSTRAINT `kelas_guru_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `kelas_guru_ibfk_3` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`);

--
-- Ketidakleluasaan untuk tabel `m_ujian`
--
ALTER TABLE `m_ujian`
  ADD CONSTRAINT `m_ujian_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `m_ujian_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id_mapel`);

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
-- Ketidakleluasaan untuk tabel `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
