-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Agu 2025 pada 07.31
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
-- Database: `simonev`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` bigint(20) NOT NULL,
  `kro` varchar(100) DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `detail_kegiatan` text DEFAULT NULL,
  `target_anggaran` bigint(20) DEFAULT NULL,
  `penyerapan` bigint(20) DEFAULT NULL,
  `realisasi` bigint(20) DEFAULT NULL,
  `progres` varchar(50) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `kendala` text DEFAULT NULL,
  `solusi` text DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL,
  `batas_waktu` date DEFAULT NULL,
  `pic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_mitra`
--

CREATE TABLE `kegiatan_mitra` (
  `id` int(11) NOT NULL,
  `kegiatan_id` bigint(20) DEFAULT NULL,
  `mitra_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar_kegiatan`
--

CREATE TABLE `komentar_kegiatan` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `sub_bagian` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `dibuat_oleh` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kro`
--

CREATE TABLE `kro` (
  `id` int(11) NOT NULL,
  `kode_kro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kro`
--

INSERT INTO `kro` (`id`, `kode_kro`) VALUES
(24, '2886.EBA.956'),
(25, '2886.EBA.962'),
(26, '2886.EBA.994'),
(27, '2886.EBD.955'),
(5, '2896.BMA.004'),
(2, '2897.BMA.004'),
(3, '2897.QDB.003'),
(4, '2898.BMA.007'),
(6, '2899.BMA.006'),
(7, '2900.BMA.005'),
(8, '2901.CAN.004'),
(9, '2902.BMA.004'),
(10, '2902.BMA.006'),
(11, '2903.BMA.009'),
(12, '2904.BMA.006'),
(13, '2905.BMA.004'),
(14, '2905.BMA.006'),
(15, '2906.BMA.003'),
(16, '2906.BMA.006'),
(17, '2907.BMA.006'),
(18, '2907.BMA.008'),
(19, '2908.BMA.004'),
(20, '2908.BMA.009'),
(21, '2909.BMA.005'),
(22, '2910.BMA.007'),
(23, '2910.BMA.008');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_laporan` varchar(255) NOT NULL,
  `sub_bagian` varchar(50) NOT NULL,
  `tanggal_upload` date NOT NULL,
  `uploaded_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_kegiatan`
--

CREATE TABLE `log_kegiatan` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `updated_by` int(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitra`
--

CREATE TABLE `mitra` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `posisi` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jk` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `sobat_id` bigint(12) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `mitra`
--

INSERT INTO `mitra` (`id`, `nama`, `posisi`, `alamat`, `jk`, `no_telp`, `sobat_id`, `email`) VALUES
(1, 'Adrianus Un', 'Mitra Pendataan', 'Mamsena RT 015 RW 006', 'Lk', '+62 821-4474-9822', 530524100008, 'ickoun240419@gmail.com'),
(2, 'Yohanes Kase Tonbesi', 'Mitra Pendataan', 'Jl. Cengkeh RT. 026 RW. 001', 'Lk', '+62 813-2862-7140', 530522030027, 'tonbesijoni@gmail.com'),
(3, 'FELIX OBENU', 'Mitra Pendataan', 'Batnes, RT 004/RW 002', 'Lk', '+62 812-7120-3561', 530522100261, 'felixobenu6@gmail.com'),
(4, 'Gradiana Tulasi', 'Mitra (Pendataan dan Pengolahan)', 'Boronubaen RT 010/RW 004 Desa Boronubaen, Kecamatan Biboki Utara', 'Pr', '+62 823-3909-3549', 530523030014, 'diianatulasi@gmail.com'),
(5, 'ELISABETH  LUSITANIA BINABU', 'Mitra Pendataan', 'Sap\'an, Rt/Rw: 010/004', 'Pr', '+62 214-4895-643', 0, 'lusitaniabinabu@gmail.com'),
(6, 'Filomena Amoi Usboko', 'Mitra Pendataan', 'OENOPU RT 003 RW 002 DESA T EBA', 'Pr', '+62 821-4532-3665', 530523030004, 'amoiusboko7@gmail.com'),
(7, 'Maria Elsi Sako Manek', 'Mitra Pendataan', 'Fafinesu C RT 001 RW 001', 'Pr', '+62 082-1468-48398', 530522100006, 'mariaelsisakomanek@gmail.com'),
(8, 'Januarius Tikneon', 'Mitra Pendataan', 'Jl. Mambramo Rt 03 Rw 01', 'Lk', '+62 822-1683-0724', 530523030149, 'belleckjanuarius@gmail.com'),
(9, 'Oktofianus Oki', 'Mitra Pendataan', 'RT/RW:002/001 DESA BANAIN B KECAMATAN BIKOMI UTARA KABUPATEN TTU', 'Lk', '+62 821-3725-5377', 0, 'ooktofianusoki@gmail.com'),
(10, 'Nurliah Apryanti', 'Mitra Pendataan', 'Jl. Imam Bonjol', 'Pr', '+62 813-5358-2105', 530523080004, 'nurliahapryamti@gmail.com'),
(11, 'Maria Febriana neno', 'Mitra Pendataan', 'Jalan trans Timor raya,RT/RW 006/003', 'Pr', '+62 813-3710-7703', 530523030029, 'febbyocky0@gmail.com'),
(12, 'Jefrianto Koes', 'Mitra Pendataan', 'Jalan Hati Suci', 'Lk', '+62 851-4324-6448', 530522020003, 'koesjefrianto@gmail.com'),
(13, 'Maria Arni De Jesus', 'Mitra Pendataan', 'Jalan Pante Makassar', 'Pr', '+62 853-3835-9564', 530522100165, 'arniidejesus99@gmail.com'),
(14, 'Heri Robertus Haekase', 'Mitra Pendataan', 'Oelatimo Rt 002 Rw 001', 'Lk', '+62 821-4400-7204', 530522030048, 'Herirobertushaekase96@gmail.com'),
(15, 'Ignasius Baba', 'Mitra Pendataan', 'Kotafoun, RT 008 RW 003', 'Lk', '+62 853-3308-6287', 530522030029, 'babaignas1234@gmail.com'),
(16, 'Hendrikus Arlando Amleni', 'Mitra Pendataan', 'Sapaen', 'Lk', '+62 812-3768-4325', 530523030056, 'hendrikus060494@gmail.com'),
(17, 'Maria Gradiana Siki', 'Mitra Pendataan', 'Femnasi', 'Pr', '+62 081-3394-59338', 530522100100, 'gradianasiki2@gmail.com'),
(18, 'Imakulata Bano', 'Mitra Pendataan', 'Jl. Matmanas, RT/RW: 010/003, Kel. Benpasi, Kec. Kota Kefamenanu, Kab. TTU', 'Pr', '+62 852-5351-6616', 0, 'imabano475@gmail.com'),
(19, 'Melki Asan Aplunggi', 'Mitra Pendataan', 'Sp 1 Rt021/RW 008 Desa Ponu', 'Lk', '+62 853-9583-5602', 530523030116, 'asanaplunggi2@gmail.com'),
(20, 'Apriana Erwinda Kefi', 'Mitra Pendataan', 'Benpasi', 'Pr', '+62 853-3746-2282', 0, 'aprianakefi9@gmail.com'),
(21, 'Wilhelmus Rio Metkono', 'Mitra Pendataan', 'DESA SALLU, RT 006/RW 003, KECAMATAN MIOMAFFO BARAT', 'Lk', '+62 812-8203-8586', 530522100075, 'riometkono12@gmail.com'),
(22, 'Dionisius Ambone', 'Mitra Pendataan', 'Haumuti', 'Lk', '+62 812-3922-5492', 530522030030, 'dionisiusambone27@gmail.com'),
(23, 'Gustaf Inyong Kobi', 'Mitra Pengolahan', 'Jln. Semangka II', 'Lk', '+62 822-4707-4910', 530522020001, 'inyongadvena@gmail.com'),
(24, 'Rikhardus Novertus Thaal', 'Mitra Pendataan', 'Desa Fatuneno RT 001 RW 001', 'Lk', '+62 082-1445-26003', 530522030062, 'rikhardusnovertus@gmail.com'),
(25, 'Dorothea Bertilya Seran Bria', 'Mitra (Pendataan dan Pengolahan)', 'Sasi km 7 , RT 030/RW 008', 'Pr', '+62 822-3388-9453', 530523030073, 'debbybria01@gmail.com'),
(26, 'Adriana Leltakaeb', 'Mitra Pendataan', 'Haulasi, RT/RW: 012/006, Desa: Haulasi, Kecamatan: Miomaffo Barat', 'Pr', '+62 822-3682-5244', 530522100223, 'adrianaleltakaeb97@gmail.com'),
(27, 'Adrianus Simau', 'Mitra Pendataan', 'Sunbaki, RT/RW 006/002', 'Lk', '+62 821-4706-6265', 0, 'adrysimau94@gmail.com'),
(28, 'Vinsensius Amsikan', 'Mitra Pendataan', 'Jln. Delima blok L, RT 015,RW 004', 'Lk', '+62 592-4917-872', 530523110046, 'amsikanvian@gmail.com'),
(29, 'Laurensius Saunoah', 'Mitra Pendataan', 'Bansone, RT/RW 001/001', 'Lk', '+62 852-5335-8686', 530524100001, 'lsaunoah@gmail.com'),
(30, 'Nicodemos de Carvalho Magno', 'Mitra Pendataan', 'Dusun: Sobe-Ainlite', 'Lk', '+62 823-4128-3777', 530523030146, 'decmagnonico@gmail.com'),
(31, 'BENYAMIN KOLO', 'Mitra Pendataan', 'RT/RW: 004/002, DESA FAENNAKE KEC. BIKOMI UTARA', 'Lk', '+62 823-4030-9131', 0, 'benykolo497@gmail.com'),
(32, 'MARIA OKTAVIANA ABI', 'Mitra Pendataan', 'KLATUN, RT 003/RW 002, DESA PONU, KECAMATAN BIBOKI ANLEU, KABUPATEN TIMOR TENGAH UTARA, PROVINSINSI NUSA TENGGARA TIMUR', 'Pr', '+62 812-3693-2741', 0, 'mariaoktavianaaby@gmail.com'),
(33, 'Mikhael Robert Anait', 'Mitra Pendataan', 'Naileku', 'Lk', '+62 812-3895-6988', 530522020021, 'robertanayt@gmail.com'),
(34, 'Maria Nonivia Nurak', 'Mitra Pendataan', 'JL. Sonbay RT 006 / RW 005', 'Pr', '+62 823-5940-9444', 530522100340, 'nony.nurak11@gmail.com'),
(35, 'Maria Yolanda Taena', 'Mitra Pendataan', 'RT 003 RW 001', 'Pr', '+62 821-9781-9518', 530522100325, 'yolandataena3@gmail.com'),
(36, 'Maria Floresty Lake', 'Mitra (Pendataan dan Pengolahan)', 'RT.007/RW.004, Desa Nimasi, Kec. Bikomi Tengah', 'Pr', '+62 082-2367-80911', 530523030122, 'estilake14@gmail.com'),
(37, 'Maria Euphrasia Tas\'au', 'Mitra Pendataan', 'Jalan sonbay Tunbakun', 'Pr', '+62 822-4718-7066', 530523030055, 'miratasau976@gmail.com'),
(38, 'Adelina Sanit', 'Mitra Pendataan', 'Tublopo Rt 09 Rw 03', 'Pr', '+62 823-4104-1132', 0, 'deliasanit15@gmail.com'),
(39, 'Vebrianti Meni Susu', 'Mitra Pendataan', 'BEBA, RT 022/RW 009, DESA OELAMI', 'Pr', '+62 813-3945-9365', 530523030012, 'vebysusu36@gmail.com'),
(40, 'ADIPONTIUS ALOISIUS TEFI', 'Mitra (Pendataan dan Pengolahan)', 'Jln. Sonbay Tanah Putih, RT/RW: 018/012, Kel. Kefamenanu Tengah, Kec. Kota Kefamenanu, Kab. TTU', 'Lk', '+62 081-3530-14861', 530523060018, 'tefiadipontus@gmail.com'),
(41, 'Ferdinandus Lake', 'Mitra Pendataan', 'RT 002 / RW 001', 'Lk', '+62 853-3344-5944', 530522030008, 'ferdinanduslake090@gmail.com'),
(42, 'Wilfridus Masaubat', 'Mitra Pendataan', 'Atolan,RT/RW 006/002,Desa Letneo Kecamatan Insana Barat', 'Lk', '+62 812-4611-6476', 530523030147, 'Fridusmasaubat25@gmail.com'),
(43, 'Coryanti Ermelinda Ati', 'Mitra Pengolahan', 'Jalan Sisingamangaraja No. 22, RT 04/RW 02', 'Pr', '+62 822-3773-3763', 530524100006, 'coriantyermelinda@gmail.com'),
(44, 'Frengkianus S. Ufa', 'Mitra Pendataan', 'Nansean', 'Lk', '+62 813-3713-7016', 530623110020, 'frengkianusufa@gmail.com'),
(45, 'YUNITA NENO', 'Mitra Pendataan', 'MAUBESI, RT 002 RW 001', 'Pr', '+62 852-6957-1671', 530522100110, 'yunitaneno2799@gmail.com'),
(46, 'Cornelius Mardianto Buan Talan', 'Mitra Pendataan', 'Kuatnana, RT/Rw/001/001', 'Lk', '+62 881-2159-52260', 530522020043, 'corneliustalan@gmail.com'),
(47, 'Ermelinda Wea Go\'o', 'Mitra Pendataan', 'Jalan Ahmad Yani, RT 010 RW 003', 'Pr', '+62 823-4238-8962', 530523060013, 'indagoo90@gmail.com'),
(48, 'Gradiana Talan', 'Mitra Pendataan', 'Kuatnana', 'Pr', '+62 822-8718-0381', 530522020042, 'talangradiana@gmail.com'),
(49, 'Marselina Seo', 'Mitra Pendataan', 'Maubesi RT/RW 009/002 Desa Maubesi, kecamatan Insana Tengah', 'Pr', '+62 821-5517-8801', 530522100116, 'seomarselina96@gmail.com'),
(50, 'Nikodemus kefi', 'Mitra Pendataan', 'BANAIN C Rt 005 Rw 002', 'Lk', '+62 821-4550-6329', 530523030088, 'kefinikodemus1987@gmail.com'),
(51, 'Yohanes Viser Nahak', 'Mitra Pendataan', 'Jalan Raya Pantai Utara, RT 003/RW 001', 'Lk', '+62 822-4755-5380', 530523030100, 'yohanesvnahak7@gmail.com'),
(52, 'Roswita Bani', 'Mitra Pendataan', 'RT 03 RW 01', 'Pr', '+62 821-4405-2744', 530522100296, 'roswitabani005@gmail.com'),
(53, 'Bernadette Esperanza Louiza Maria Lake', 'Mitra Pendataan', 'Jl. Yos Soedarso', 'Pr', '+62 085-3331-63573', 530524100004, 'rossielake@gmail.com'),
(54, 'Graciana Dede Amsikan', 'Mitra Pendataan', 'Oenitas RT 01 RW 01', 'Pr', '+62 082-2368-2002', 530523030102, 'gracianaamsikan@gmail.com'),
(55, 'Gabriel Naitkakin', 'Mitra (Pendataan dan Pengolahan)', 'RT 001 RW 001, Desa Letmafo Timur Kecamatan Insana Tengah', 'Lk', '+62 812-2709-6197', 530522100113, 'gebinaitkakin29@gmail.com'),
(56, 'Mathilda Firianti Bani', 'Mitra Pendataan', 'Oelnitep', 'Pr', '+62 852-6128-5275', 530522030038, 'virabani139@gmail.com'),
(57, 'Ferdinandus Suaun', 'Mitra Pengolahan', 'Jl. Diponegoro koko, kelurahan Bansone, Kecamatan Kota Kefamenanu, Kabupaten Timor Tengah Utara, Provinsi Nusa Tenggara Timur', 'Lk', '+62 082-2367-67569', 0, 'Ferdinandussuaun@gmail.com'),
(58, 'Deni Mantolas', 'Mitra Pendataan', 'Desa Eban Rt/Rw 020/006', 'Lk', '+62 853-1933-7495', 530522100085, 'denimantolas@gmail.com'),
(59, 'Adeodatus Riulaman Berkanis', 'Mitra (Pendataan dan Pengolahan)', 'RT.006/RW.002 Desa Fatumuti, kecamatan Noemuti, Kabupaten TTU', 'Lk', '+62 123-6219-191', 530522100388, 'deoberkanis@gmail.com'),
(60, 'Sesarius Lolomsait', 'Mitra Pendataan', 'Saenam', 'Lk', '+62 812-3873-7052', 530522100195, 'Sesariuslolomsait@gmail.com'),
(61, 'Ferdinandes Filemon Raja', 'Mitra Pendataan', 'Jln. Pisang 2 Rt. 030 Rw 005', 'Lk', '+62 081-3389-16731', 530523030001, 'fedinadesraja@gmail.com'),
(62, 'Rince Eka Boling', 'Mitra Pendataan', 'NEKMATANI,RT/RW:040/007,KEL.KEFAMENANU SELATAN,KEC.KOTA KEFAMENANU', 'Pr', '+62 822-3774-6789', 530522100373, 'bolingrince@gmail.com'),
(63, 'Angelina Naikbiti', 'Mitra Pendataan', 'NAUTUS RT001/RW001', 'Pr', '+62 821-2552-1176', 530522100129, 'Anaikbiti@gmail.com'),
(64, 'Alfridus Naisau', 'Mitra Pendataan', 'Haufo\'o, RT 004 RW 002 DUSUN 1', 'Lk', '+62 813-6324-2018', 0, 'alfridusnaisau1@gmail.com'),
(65, 'Yuli Oktovina Waly', 'Mitra Pendataan', 'RT 040 RW 007, KEFAMENANU SELATAN', 'Pr', '+62 085-3338-35466', 530522020023, 'yuliwaly25@gmail.com'),
(66, 'Kresensia Neonnub', 'Mitra Pendataan', 'Nailiu,RT/RW:008/002', 'Pr', '+62 821-4784-9268', 530522100047, 'kresensianeonnub@gmail.com'),
(67, 'Dalmasius Naibesi', 'Mitra Pendataan', 'FATUHAO RT/RW 004/002, DESA FAFINESU B, KECAMATAN INSANA FAFINESU', 'Lk', '+62 813-3702-0620', 530522100235, 'dallmasiusnaibesi@gmail.com'),
(68, 'MELKIOR ADITYA SERAN', 'Mitra Pendataan', 'Jl.Basuki Rahmat, RT/RW: 003/006, Kel.Bepasi, Kec. Kota Kefamenanu', 'Lk', '+62 081-3392-42671', 530522100055, 'seranmelki789@gmail.com'),
(69, 'Florensia Lake', 'Mitra Pendataan', 'FATUNENO, RT/RW:010/005 Desa Fatuneno, Kecamatan Miomaffo Barat Kabupaten Timor Tengah Utara, Provinsi Nusa Tenggara Timur', 'Pr', '+62 823-5954-3292', 530523080002, 'florensialake1@gmail.com'),
(70, 'Kristina Emanuela Sutal', 'Mitra Pendataan', 'Kuatnana RT/RW 001/001', 'Pr', '+62 878-1541-3626', 530523030027, 'kristinasutal@gmail.com'),
(71, 'Richardus Alexandro Oeleu', 'Mitra Pengolahan', 'Jalan Sonbay Rt 031 Rw 005', 'Lk', '+62 822-6691-4552', 0, 'richardusoeleu@gmail.com'),
(72, 'Elma Roswita Opat', 'Mitra (Pendataan dan Pengolahan)', 'Oeekam, RT 008 / RW 02', 'Pr', '+62 822-6623-0722', 530523060028, 'elmanacho.echan@gmail.com'),
(73, 'Maria Fatima Makun', 'Mitra (Pendataan dan Pengolahan)', 'Desa Sapaen RT 01/RW 01, Kecamatan Biboki Utara, Kabupaten Timor Tengah Utara', 'Pr', '+62 821-4619-6960', 530523110050, 'mariafatimamakun11@gmail.com'),
(74, 'Adelina Armi Asmiati Kefi', 'Mitra Pengolahan', 'Jln. Sisingamangaraja Benpasi RT 09/RW 02', 'Pr', '+62 823-2380-2813', 530522110013, 'adelkefi29@gmail.com'),
(75, 'Abraham Derusel Tethun', 'Mitra Pendataan', 'Jalan mamsena', 'Lk', '+62 821-4498-4146', 530522100054, 'abrahamtethun8@gmail.com'),
(76, 'Bernadus Igo Koten', 'Mitra Pendataan', 'Jl. Gereja Katolik St. Petrus Kanisius Manufui RT 09 RW 04 Desa Upfaon', 'Lk', '+62 812-1081-6313', 530523030148, 'igokoten94@gmail.com'),
(77, 'Theofilus Natun Kolo', 'Mitra Pendataan', 'Jalan Bibis RT/RW 02/01', 'Lk', '+62 822-4596-2897', 530522030033, 'theofiluskolo@gmail.com'),
(78, 'Desiderius Yosef Kaauni', 'Mitra Pendataan', 'Maubesi. Desa Maubesi. Kecamatan Insana Tengah. Kabupaten Timor Tengah Utara', 'Lk', '+62 813-3855-0799', 530523030128, 'dariuskaaunii@gmail.com'),
(79, 'Romualdus Yemarsef Banusu', 'Mitra Pendataan', 'Jalan Timor Raya Nesam  RT 16 RW 04 km 29', 'Lk', '+62 822-7111-8392', 530522020055, 'obybanusus@gmail.com'),
(80, 'Novilinda Naisaban', 'Mitra Pendataan', 'Tainmetan RT 013 Desa Maubesi, Kec Insana Tengah', 'Pr', '+62 813-3879-3916', 530522100111, 'novilindanaisaban491@gmail.com'),
(81, 'Herman Naif', 'Mitra Pendataan', 'Oemasi, RT 004/ RW 002 Desa Fatuana', 'Lk', '+62 821-5855-1863', 530522030014, 'hackyrman6@gmail.com'),
(82, 'Yuliana Missa', 'Mitra Pendataan', 'Haulasi, RT 002 RW 001 Desa Haulasi, Kecamatan Miomaffo Barat, Kabupaten Timor Tengah Utara', 'Pr', '+62 813-6763-2441', 530523030022, 'yulianamissa70@gmail.com'),
(83, 'Radegunda Oeleu', 'Mitra Pendataan', 'Jln.Nekmese,Desa Usapinonot RT/RW 001/001', 'Pr', '+62 813-5333-5410', 530522100067, 'oeleuradegunda@gmail.com'),
(84, 'Cristina Lopes Amaral', 'Mitra Pendataan', 'jalan Timor Raya, Oelurai  desa Tapenpah, kecamatan Insana, RT 008 RW  berapa .', 'Pr', '+62 853-3967-3887', 530523110044, 'resthyamaral@gmail.com'),
(85, 'Florida Primania Ketty Luan', 'Mitra (Pendataan dan Pengolahan)', 'Kilometer Lima', 'Pr', '+62 821-4695-3231', 530622020014, 'kattiluan@gmail.com'),
(86, 'Heribertus Rio Bria', 'Mitra Pendataan', 'Pantae, RT/RW 007/003', 'Lk', '+62 822-3705-5715', 530522100275, 'briario081@gmail.com'),
(87, 'OKTAVIANA FEKA', 'Mitra Pendataan', 'Oelneke RT/RW; 008/004', 'Pr', '+62 822-4797-4192', 530522100138, 'oktavianafeka@gamail.com'),
(88, 'Novyanti Tanik', 'Mitra Pengolahan', 'Tububue, RT/RW : 029/001', 'Pr', '+62 813-3736-7489', 530522110006, 'novhytanik@gmail.com'),
(89, 'Adriana Maria Gorethi Fobia', 'Mitra Pendataan', 'Jl acasia rt 008 Rw 004', 'Pr', '+62 812-6110-3091', 530522030039, 'adrianafobia1980@gmail.com'),
(90, 'Vinsensius Paebesi', 'Mitra Pendataan', 'Aibano,oan ,RT/RW 007/002 Desa Boronubaen', 'Lk', '+62 821-1176-9053', 530522050003, 'Paebesivinsen02@gmail.com'),
(91, 'Vitalis Aliyance Lake', 'Mitra Pendataan', 'Jl. Fatualam, RT 006, RW 002, Desa Lapeom, Kecamatan Insana Barat', 'Lk', '+62 081-3536-14394', 530522100046, 'vitalislake89@gmail.com'),
(92, 'Yosefa Citra Dewi Abi', 'Mitra Pendataan', 'Sainoni,fatunaenu,RT/RW 007/003', 'Pr', '+62 823-2130-7146', 530524100002, 'yosefaabi66@gmail.com'),
(93, 'Elisabeth Lelu Lagamakin', 'Mitra Pendataan', 'Jl. Sisingamangaraja No. 01, RT.019/RW.005', 'Pr', '+62 821-1001-0626', 530523030002, 'elisabethlelu9@gmail.com'),
(94, 'SILVERIOR GONZAGA BERELAKA', 'Mitra Pendataan', 'Jl. Sobay, RT.014/RW.02, Kefamenanu Sel., Kec. Kota Kefamenanu, Kabupaten Timor Tengah Utara, Nusa Tenggara Tim. 85613', 'Lk', '+62 877-6592-8123', 530524100007, 'silveriorberelaka@gmail.com'),
(95, 'Yuliana Luruk Nahak', 'Mitra Pendataan', 'Ekanaktuka,RT/RW : 001/001,Desa. botof, Kecamatan Insana', 'Pr', '+62 822-3649-8514', 0, 'yulianaluruknahak@gmail.com'),
(96, 'Liberius E. Lake', 'Mitra Pendataan', 'Oenenu', 'Lk', '+62 813-5394-0273', 530522020022, 'ericklacke@gmail.com'),
(97, 'Destika Keke', 'Mitra Pendataan', 'Nansean, RT/RW 002/002', 'Pr', '+62 081-3539-89729', 530522030054, 'destikakeke18@gmail.com'),
(98, 'Polykarpus Manek Balibo', 'Mitra Pendataan', 'RT 006 / RW 002', 'Lk', '+62 822-3798-0708', 530522030037, 'olisbalibo@gmail.com'),
(99, 'Wenseslaus Evensius mano', 'Mitra Pendataan', 'Desa Baas RT 004/RW 001', 'Lk', '+62 853-3355-1233', 530522100015, 'wenseslausevenm@gmail.com'),
(100, 'Matheus Mitriji Ola Lake', 'Mitra Pendataan', 'Peboko', 'Lk', '+62 823-5940-7118', 530523030143, 'laketheo8@gmail.com'),
(101, 'Yosefina Funan', 'Mitra Pendataan', 'TUAMAU RT 008/RW 002 DESA BANNAE', 'Pr', '+62 823-3671-5794', 530522100045, 'vivifunan0111@gmail.com'),
(102, 'Cresensia Kefi', 'Mitra Pendataan', 'Jln Diponegoro RT/RW: 017/006', 'Pr', '+62 813-3753-7931', 530523110035, 'cresensiakefi7@gmail.com'),
(103, 'Robertus Torino Banusu', 'Mitra Pendataan', 'OEKOLO RT/RW 006/002', 'Lk', '+62 821-1266-0569', 530522100234, 'Rinobanusu@gmail.com'),
(104, 'Cornelia M M Klau', 'Mitra Pendataan', 'JL NURI RT 003 RW 001', 'Pr', '+62 822-4702-5973', 530522030056, 'nellyklau@yahoo.co'),
(105, 'Graciana Abi', 'Mitra Pendataan', 'RT 005/RW 002, Desa Sainoni, Kecamatan Bikomi Utara Kabupaten Timor Tengah Utara', 'Pr', '+62 821-4530-1490', 530522100011, 'cia160498@gmail.com'),
(106, 'Hendrikus Naben', 'Mitra Pendataan', 'Eban, RT/RW:005/002 Desa Eban, Kecamatan Miomaffo Barat Kabupaten Timor Tengah Utara', 'Lk', '+62 823-1133-4416', 530522100232, 'hendriknaben@gmail.com'),
(107, 'Antonius Tna\'auni', 'Mitra Pendataan', 'Kuanek, RT007 RW003', 'Lk', '+62 821-4708-6875', 530522030036, 'antonius.tnaauni87@yahoo.com'),
(108, 'Frederikus Nia', 'Mitra Pendataan', 'Maurisu Utara', 'Lk', '+62 081-2462-58170', 530523110003, 'frederikusnia@gmail.com'),
(109, 'MARVELIA NOVIRA AMTAHAN', 'Mitra Pendataan', 'Oelolok, RT 001/ RW 001', 'Pr', '+62 853-3737-5097', 0, 'firaamtahan@gmail.com'),
(110, 'Angela Huberti Kono', 'Mitra (Pendataan dan Pengolahan)', 'Kuanek, RT 005/ RW 003', 'Pr', '+62 821-4525-2625', 0, 'angelakono99@gmail.com'),
(111, 'Alexius Sasi', 'Mitra Pendataan', 'Baas RT 002 RW 002', 'Lk', '+62 786-9942-629', 530522100014, 'alexiussasi@gmail.com'),
(112, 'Valentina M Mamo', 'Mitra (Pendataan dan Pengolahan)', 'Desa Noepesu', 'Pr', '+62 812-3647-1730', 530523110027, 'ennymamo23@gmail.com'),
(113, 'Elisabeth Chyntya Rafu Fahik', 'Mitra Pendataan', 'Naesleu RT 022 RW 004', 'Pr', '+62 812-3549-8881', 530522100009, 'ciku130499@gmail.com'),
(114, 'Petronela Dale', 'Mitra Pendataan', 'Jln.Sonbay Tunbakun', 'Pr', '+62 812-9385-9321', 530523110052, 'nonadlle903@gmail.com'),
(115, 'LODIVIKUS BERE MAU', 'Mitra Pendataan', 'Desa Nimasi', 'Lk', '+62 853-3835-9690', 530522100028, 'lodivikusberemau@gmail.com'),
(116, 'Evlyn Paula Klau', 'Mitra Pengolahan', 'Jalan Ahmad Yani RT 027 / RW 004', 'Pr', '+62 822-4772-9603', 530523060024, 'evlynp1310@gmail.com'),
(117, 'Fedelia Maria N. Da Conceicao', 'Mitra (Pendataan dan Pengolahan)', 'Jln.Nuri Fatuteke', 'Pr', '+62 822-4767-6069', 530523070002, 'ila.daconceicao24@gmail.com'),
(118, 'Yerimias Manek', 'Mitra Pendataan', 'Kelurahan Bansone,RT/RW.003/001,Kecamatan Kota Kefamenanu', 'Lk', '+62 812-3800-3099', 530522100069, 'manekyeri@gmail.com'),
(119, 'Winfrida yosefa sani lake', 'Mitra Pendataan', 'Jl.L Lake Nilulat', 'Pr', '+62 822-4788-5466', 530522020019, 'lakewindy173@gmail.com'),
(120, 'Fransiskus Rissaldo Elu', 'Mitra Pendataan', 'JLN. SISINGAMANGARAJA BENPASI', 'Lk', '+62 821-4676-1575', 530522100140, 'risalll1997@gmail.com'),
(121, 'Roderiques Primus Olla', 'Mitra Pendataan', 'RT/RW:004/002', 'Lk', '+62 082-2479-18994', 530522100162, 'primusroderiques@gmail.com'),
(122, 'Antonina kefi', 'Mitra Pendataan', 'Banain, Rt/Rw 003/002', 'Pr', '+62 081-2466-25148', 530522100141, 'antoninakefi4@gmail.com'),
(123, 'Yohana Maria Neo', 'Mitra Pendataan', 'BITAUNI', 'Pr', '+62 812-7823-6661', 530522020068, 'yohananeo8@gmail.com'),
(124, 'Rony Alexander Boling', 'Mitra Pendataan', 'Nekmatani RT/RW: 040/007', 'Lk', '+62 812-8450-3814', 530522020025, 'ronyboling@gmail.com'),
(125, 'Angelina Lawalu', 'Mitra Pendataan', 'Jln. Akasia RT: 008 RW: 004', 'Pr', '+62 821-4412-9165', 530522100357, 'lawaluangelina@gmail.com'),
(126, 'Djulita Thresiani Nahak', 'Mitra Pengolahan', 'Kelurahan Sasi Rt/Rw 027/001', 'Pr', '+62 081-2468-22308', 530523060021, 'djulitanahak78@gmail.com'),
(127, 'Jozina Erlinda Mariati Nara Kaha', 'Mitra Pendataan', 'Jl. Diponegoro, RT 10/RW 04', 'Pr', '+62 812-4630-3515', 530522100356, 'jozina01ge02gei@gmail.com'),
(128, 'Ana Viktoria Ampolo', 'Mitra Pendataan', 'Kampung Baru,Rt009/Rw003', 'Pr', '+62 821-4613-2758', 530522030040, 'annaampolo271@gmail.com'),
(129, 'Bonefasius Hati Sanbein', 'Mitra (Pendataan dan Pengolahan)', 'Jln. Pelajar Lurasik RT/RW 016/004', 'Lk', '+62 812-3704-5160', 530522100038, 'bonefasiussanbein@gmail.com'),
(130, 'Yunita Elenora Kono', 'Mitra Pendataan', 'RT.012, RW.007', 'Pr', '+62 813-4214-9640', 530523030017, 'nitaelfrida03@gmail.com'),
(131, 'Fransiska Monalisa Amaina Oky', 'Mitra Pendataan', 'Upkasen RT/RW 017/006', 'Pr', '+62 821-4745-7399', 530523030015, 'fransiskaoki2602@gmail.com'),
(132, 'Aloysius Damianus Kolo', 'Mitra Pendataan', 'Puaono RT/RW: 001/001 Banain B', 'Lk', '+62 853-3831-5140', 530523030065, 'aloysiusdamianuskolo@gmail.com'),
(133, 'Ronaldo Devino Jeronimo', 'Mitra Pendataan', 'Sasi, Km. 7', 'Lk', '+62 813-3698-8380', 530523060001, 'bynnojr007@gmail.com'),
(134, 'Marselinus Lalus', 'Mitra Pendataan', 'Desa Bijaepasu, Rt 014 / Rw 007', 'Lk', '+62 812-3852-3350', 530522030012, 'madridell59@gmail.com'),
(135, 'Elfrida Maria Manue Funan', 'Mitra Pendataan', 'Jl.Trans Kefa-Kupang', 'Pr', '+62 821-4403-1954', 530522100292, 'elfridamariafunan@gmail.com'),
(136, 'FRANSISKUS XAVERIUS AFANDI NAIMNULE', 'Mitra (Pendataan dan Pengolahan)', 'Desa Buk RT.003 RW.002', 'Lk', '+62 812-3690-9190', 530523060030, 'themasterafandi@gmail.com'),
(137, 'Pius Fenansius Masaubat', 'Mitra Pendataan', 'OINBIT RT 003/ RW 002', 'Lk', '+62 822-1492-1599', 530522100214, 'ravenmasaubat98@gmail.com'),
(138, 'Kanisius yos lake', 'Mitra Pendataan', 'Maumolo,RT/RW:017/006', 'Lk', '+62 813-4753-4618', 530522100335, 'Lakekenz@gmail.com'),
(139, 'Janrino Junus Rivaldi Fanggidae', 'Mitra Pengolahan', 'BTN Naiola Bikomi Selatan RT. 013 / RW. 004', 'Lk', '+62 081-3251-92102', 0, 'janrinofanggidae@gmail.com'),
(140, 'Claudia Nadia. Putri. Yoani Lake', 'Mitra Pendataan', 'Jl seroja Kelurahan Kefamenanu Utara', 'Pr', '+62 821-4698-3936', 530523110007, 'nadyaganzer19@gmail.com'),
(141, 'Fanda Apriani Kolloh', 'Mitra Pendataan', 'Jl. Prof. Dr. W. Z. Yohanes, kilo meter 7', 'Pr', '+62 852-3802-4530', 0, 'fandakolloh@gmail.com'),
(142, 'Margharetha S. Naibobe', 'Mitra (Pendataan dan Pengolahan)', 'Benpasi, RT 009 RW 005', 'Pr', '+62 821-4053-6787_', 530524100005, 'ghegeaplugi@gmail.com'),
(143, 'Silveira Juniati Kolo', 'Mitra Pengolahan', 'Jalan Semangka', 'Pr', '+62 823-3964-5254', 530522110005, 'silveirajuniatikolo@gmail.com'),
(144, 'Anita Carolina Sao Salo', 'Mitra Pendataan', 'Benpasi RT.012 / RW.003', 'Pr', '+62 813-3981-0012', 530522100326, 'anitasaosalo@gmail.com'),
(145, 'Maria Gradiana Misa', 'Mitra Pendataan', 'Jalan Timor Raya', 'Pr', '+62 081-3396-49955', 530523110055, 'derimisa7@gmail.com'),
(146, 'Selviana Hausufa', 'Mitra Pendataan', 'Tuabatan', 'Pr', '+62 812-5399-5826', 530522100121, 'selvianahausufa@gmail.com'),
(147, 'Petronela Unab', 'Mitra Pendataan', 'OELBONAK,RT 001 RW 002', 'Pr', '+62 823-3977-9231', 530522100040, 'petronelaunab@yahoo.com'),
(148, 'Inosensius Nggadas', 'Mitra Pendataan', 'Jl.L.Lake RT003/RW002', 'Lk', '+62 085-3380-89096', 530523030040, 'inosensiusnggadas@gmail.com'),
(149, 'Yohana Palbeno', 'Mitra Pendataan', 'Sunkaen,Desa Sunkaen,Rt/Rw.003,002,Kec.Bikomi Nilulat', 'Pr', '+62 822-7104-1233', 530522030024, 'palbenoyohana@gmail.com'),
(150, 'Kristafora Metan', 'Mitra Pendataan', 'OENENU UTARA, RT/RW : 002/001, KECAMATAN BIKOMI TENGAH KABUPATEN TIMOR TENGAH UTARA', 'Pr', '+62 822-4735-1350', 530522100024, 'istametan09@gmail.com'),
(151, 'Oktaviana kope', 'Mitra Pendataan', 'Desa batnes kecamatan musi', 'Pr', '+62 085-2161-03172', 530522100147, 'Kopeoktaviana33@gmail.com'),
(152, 'Yoseph Benyamin Hati Meo Tulasi', 'Mitra Pendataan', 'Teflopo ,RT 005/ RW 002 DUSUN B', 'Lk', '+62 081-3120-66649', 530522100099, 'hatimeotulasi1986@gmail.com'),
(153, 'Damianus Banusu', 'Mitra (Pendataan dan Pengolahan)', 'Oekolo, RT/RW: 012/003, Desa Humusu Oekolo, Kecamatan Insana Utara', 'Lk', '+62 812-4661-6012', 530522100233, 'damianusbanusu59@gmail.com'),
(154, 'Yohana Albertin Pay', 'Mitra Pendataan', 'RT/RW: 055/006', 'Pr', '+62 813-3797-2445', 530522020052, 'yohanaalbertinpay@gmail.com'),
(155, 'Gradiana olga rafu', 'Mitra Pendataan', 'Motadik, RT 010 RW 01', 'Pr', '+62 822-5391-4901', 530522100059, 'olgarafu04@gmail.com'),
(156, 'Louis Florentino Maria Lake', 'Mitra Pendataan', 'Nifutasi RT/ RW 002/001', 'Lk', '+62 821-4467-3272', 530523040005, 'louisflorentinomarialake@gmail.com'),
(157, 'Ignasia wanty Taena', 'Mitra Pendataan', 'Usapipukan', 'Pr', '+62 813-3798-5467', 0, 'Ignasiawtaena@gmail.com'),
(158, 'Hendrikus Paulus Malelak', 'Mitra Pendataan', 'Jl.akasia Kefamenanu utara', 'Lk', '+62 821-4512-9811', 530522100134, 'hendrikuspaulusmalelak@gmail.com'),
(159, 'Desi Fatima Amloki', 'Mitra Pendataan', 'Oelolok RT/RW 002/001', 'Pr', '+62 082-1457-13415', 530522100170, 'dessiamloki02@gmail.com'),
(160, 'ADRIANUS KOLO', 'Mitra Pendataan', 'BANURU, RT 002/RW 001', 'Lk', '+62 821-4421-8983', 530523110018, 'ardhyadryan87@gmail.com'),
(161, 'Rofina Kolo Tubani Naisoko', 'Mitra (Pendataan dan Pengolahan)', 'Tubuhue, RT 003/ RW 001', 'Pr', '+62 812-3889-3896', 0, 'naisokofhya@gmail.com'),
(162, 'Sofiana Koa', 'Mitra Pendataan', 'Wini', 'Pr', '+62 813-2912-0015', 530523080007, 'Koasofiana@gmail.com'),
(163, 'JEFRIANUS UA ATINI', 'Mitra Pendataan', 'Nansean', 'Lk', '+62 823-4032-1754', 0, 'jhemarshy019@gmail.com'),
(164, 'Kadek Adinda Chintya Divayani', 'Mitra (Pendataan dan Pengolahan)', 'Tainmetan, RT 001, RW 001', 'Pr', '+62 821-5847-6946', 530623110049, 'kadekadinda119@gmail.com'),
(165, 'Claudia Yunita Anggraini Mout', 'Mitra Pendataan', 'Jalan Mutis Rt/Rw: 003/001', 'Pr', '+62 087-8654-00013', 0, 'rarakithly10@icloud.com'),
(166, 'Elfrida Nensiana Kono Foni', 'Mitra (Pendataan dan Pengolahan)', 'Oelninaat, RT/RW: 017/005, Kelurahan Maubeli, Kecamatan Kota Kefamenanu', 'Pr', '+62 813-3792-1798', 530524100003, 'estifoni46@gmail.com'),
(167, 'Thomas Didimus Naitkakin', 'Mitra Pendataan', 'Kiupasan', 'Lk', '+62 821-4550-6329', 530523030104, 'izanaitkakin@gmail.com'),
(168, 'Wilibrodus Thaal', 'Mitra Pendataan', 'Desa saenam RT 001/RW 002', 'Lk', '+62 821-4488-6862', 530523030063, 'thaalwilly@gmail.com'),
(169, 'Yuliana Nono', 'Mitra Pendataan', 'Bakitolas, RT 010/RW 003. Desa Bakitolas. Kecamatan Naibenu', 'Pr', '+62 821-4525-9384', 530523030070, 'yulinono524@gmail.com'),
(170, 'Veronika Alesandra Aleus', 'Mitra Pengolahan', 'DESA TUBUHUE, KM 4, RT 006,RW 002', 'Pr', '+62 823-4027-6220', 0, 'alesandraaleus@gmail.com'),
(171, 'Antonius Mario Fendriano Kefi', 'Mitra Pendataan', 'Jalan Sonbay, Rt/Rw 030/012, Kel. Kefa Tengah', 'Lk', '+62 813-9374-6063', 530522100346, 'kefifendry37@gmail.com'),
(172, 'Godefredus Mariano Naikofi', 'Mitra Pendataan', 'Jalan Ahmad Yani, Km 3 RT 026/ RW 004', 'Lk', '+62 813-3980-9842', 0, 'marionaikofi06@gmail.com'),
(173, 'Sesilia Malaof Fallo', 'Mitra Pendataan', 'Jl.Haekto RT/RW : 001/001', 'Pr', '+62 082-1440-03105', 530522100161, 'liafalo1212@gmail.com'),
(174, 'Marianus Paebesi', 'Mitra Pendataan', 'Boronubaen RT 008/ RW 002', 'Lk', '+62 082-1117-69031', 530523080009, 'marianuspaebesi@gmail.com'),
(175, 'Robertus Fnini', 'Mitra Pendataan', 'NANSEAN, RT 002 RW 002', 'Lk', '+62 813-5385-0201', 530522030047, 'fninirobidistikakeke@gmail.com'),
(176, 'Magdalena Agustina Poto', 'Mitra Pendataan', 'Jalan Kartini RT 038 RW 002', 'Pr', '+62 852-3917-6791', 530522030046, 'magdalenapotoena@gmail.com'),
(177, 'Wilfrid Roberto Suan', 'Mitra Pendataan', 'RT 005 RW 003', 'Lk', '+62 895-2012-2557', 530522100001, 'willmcrmy950@gmail.com'),
(178, 'Yulianus Benediktus To', 'Mitra Pendataan', 'RT 036 / RW 006', 'Lk', '+62 852-5306-1226', 530522030059, 'alvianoto@gmail.com'),
(179, 'Bonevantura Masaubat', 'Mitra Pendataan', 'Oinbit', 'Lk', '+62 812-1008-7994', 530522020046, 'fentmasaubat@gmail.com'),
(180, 'Giovanny Robertho Wolfram Lake', 'Mitra Pendataan', 'Jl. Pattimura RT 008 RW 005', 'Lk', '+62 821-4639-5676', 0, 'giovannyroberthowolframlake@gmail.com'),
(181, 'Lidia Ludgardis Neolaka', 'Mitra Pendataan', 'Bijeli,Rt 005/Rw 002 Desa Bijeli-Kecamatan Noemuti', 'Pr', '+62 822-3633-8251', 530523080006, 'Lidyaneolaka23@gmail.com'),
(182, 'Alexander Funan', 'Mitra (Pendataan dan Pengolahan)', 'Opo, RT/RW: 003/001, Desa Pantae, Kecamatan Biboki Selatan, Kabupaten Timor Tengah Utara', 'Lk', '+62 821-4525-2456', 530523080003, 'alexander.funan@gmail.com'),
(183, 'MARIA AMFOTIS', 'Mitra Pendataan', 'Benpasi Rt 02.Rw 01', 'Pr', '+62 813-3857-1627', 530522030041, 'miaamfotis5@gmail.com'),
(184, 'Priska Vianne Kefi', 'Mitra (Pendataan dan Pengolahan)', 'Benpasi RT 010 RW 005', 'Pr', '+62 852-4520-2928', 530523060016, 'priskakefi@gmail.com'),
(185, 'Maria Gracia Kefi', 'Mitra Pendataan', 'Jln. Sisingamangaraja RT/RW 006/002 Benpasi', 'Pr', '+62 822-4750-2071', 530522020037, 'marianaibukefi@gmail.com'),
(186, 'Florida Hati', 'Mitra Pendataan', 'Fatuhao,RT 005,RW 003, Desa Fafinesu B kecamatan Insana Fafinesu', 'Pr', '+62 851-6711-4428', 530522100005, 'hakkytune@gmail.com'),
(187, 'Frederikus Fransiskus Lake', 'Mitra Pendataan', 'Aplasi RT/RW: 006/003', 'Lk', '+62 812-1742-7838', 530522100359, 'frederikuslake@gmail.com'),
(188, 'Rikardus Tahakae', 'Mitra Pendataan', 'RT 005 / RW 002', 'Lk', '+62 812-3690-1581', 530522030019, 'rikard.tahakae@gmail.com'),
(189, 'Marselina Ketmoen', 'Mitra Pendataan', 'JAK', 'Pr', '+62 812-4640-3417', 530522100249, 'serlyk16@gmail.com'),
(190, 'Yohanes Eban', 'Mitra Pendataan', 'Nefosene,RT/RW 005/003, Desa Sone, Kecamatan Insana Tengah', 'Lk', '+62 812-9984-8738', 530522100042, 'jhonilopes659@gmail.com'),
(191, 'Melkianus Y.M. Ufa', 'Mitra Pendataan', 'Nansean', 'Lk', '+62 823-4104-226', 0, 'yantoufa4@gmail.com'),
(192, 'Bartolomeus sani', 'Mitra Pendataan', 'BENUS RT 03 RW 01', 'Lk', '+62 821-4725-4770', 530522100286, 'bartoteme@gmail.com'),
(193, 'Oktovianus Naikofi', 'Mitra Pendataan', 'Ekanaktuka,RT/RW  :001/001, Desa Botof, Kecamatan Insana, kabupaten Timor Tengah Utara', 'Lk', '+62 082-1385-65132', 530522020031, 'oktonaikofi242@gmail.com'),
(194, 'Petrus sambu Djawa', 'Mitra (Pendataan dan Pengolahan)', 'Oenaek, RT/RW 003/002, Desa Fafinesu, kecamatan Insana Fafinesu', 'Lk', '+62 081-2642-20442', 0, 'petrussambudjawa@gmail.com'),
(195, 'Maria Marsela Ua', 'Mitra Pendataan', 'Bitauni', 'Pr', '+62 852-5398-6176', 530522030013, 'mariamarselaua@gmail.com'),
(196, 'Selestiano Cabreira Do Rosario', 'Mitra Pendataan', 'Jl. Meo Otu Hale', 'Lk', '+62 821-6167-8201', 0, 'selesrosario@gmail.com'),
(197, 'Hildegardis Jeni Tefa', 'Mitra Pendataan', 'Fatuneno', 'Pr', '+62 813-3725-9016', 0, 'jenitefahildegardis@gmail.com'),
(198, 'Petrus Aleus Ninu', 'Mitra Pendataan', 'RT 004 RW 002 DUSUN 01 KOTE', 'Lk', '+62 822-9864-6182', 530522030005, 'petrusninu175@gmail.com'),
(199, 'Valentina Leltakaeb', 'Mitra Pendataan', 'Oeolo RT 005 RW 003', 'Pr', '+62 082-1451-95890', 530523030099, 'valentinaleltakaeb@gmail.com'),
(200, 'Widiana Maria abi', 'Mitra Pendataan', 'Tualene RT RW 009/005 Biboki utara', 'Pr', '+62 081-3395-27962', 530522100035, 'widianamariaabi@gmail.com'),
(201, 'Gonzalves Damian Tabati', 'Mitra Pendataan', 'Jalan Boronubaen', 'Lk', '+62 821-4496-9248', 0, 'gonzalvestabati12@gmail.com'),
(202, 'Yosef Antoin Taus', 'Mitra Pendataan', 'RT 003 / RW 002', 'Lk', '+62 821-3483-6116', 530522030017, 'yosefantoin1@gmail.com'),
(203, 'Fransiskus Usfinit', 'Mitra Pendataan', 'Boentuna Rt/Rw 001/001, Desa Humusu Sainiup', 'Lk', '+62 813-1851-4393', 530522020018, 'fransusfinit86@gmail.com'),
(204, 'Aprianus Oematan', 'Mitra Pendataan', 'Jln. Suan-Sabu,  Desa fatunisuan Kecamatan Miomaffo Barat', 'Lk', '+62 813-5328-8617', 530523030131, 'Itokaunan27@gmail.com'),
(205, 'Elda Suni', 'Mitra Pendataan', 'Haumeni, RT 002/RW 001, Desa Haumeni, Kecamatan Bikomi Utara', 'Pr', '+62 813-3713-5772', 530522050004, 'eeldasuni@gmail.com'),
(206, 'Dolfiana Hartun', 'Mitra Pendataan', 'Kotafoun, RT 008 RW 003', 'Pr', '+62 813-3702-4784', 530522030058, 'dolfianahartun95@gmail.com'),
(207, 'Yustina Sanae Sanak', 'Mitra Pengolahan', 'Jl. Diponeggoro, Koko, Rt/Rw 013/005', 'Pr', '+62 082-1450-57806', 0, 'Novasanak34@gmail.com'),
(208, 'Kondradus Poli Mamulak', 'Mitra Pendataan', 'Berseon,  RT 010/RW 005, Desa Lokomea, Kecamatan Biboki Utara', 'Lk', '+62 853-3743-7782', 530523030005, 'kondradmamulak@gmail.com'),
(209, 'Elfira Evangelista Bala', 'Mitra (Pendataan dan Pengolahan)', 'Jalan Ahmad Yani, RT/RW : 025/004', 'Pr', '+62 823-5122-6341', 0, 'elfirabala04@gmail.com'),
(210, 'Dominggus Taus', 'Mitra Pendataan', 'Bonak', 'Lk', '+62 822-6623-3710', 530523040001, 'tausdominggus@gmail.com'),
(211, 'Emilia Manek Kanmese', 'Mitra (Pendataan dan Pengolahan)', 'Jl. Sisingamangaraja Benpasi B, RT10 RW07', 'Pr', '+62 822-3506-3083', 530523060004, 'emiliamanek@gmail.com'),
(212, 'DONATIANA BARKANIS', 'Mitra Pendataan', 'Banfanu,Rt/Rw 008/004', 'Pr', '+62 081-2393-36235', 0, 'onabarkanis444@gmail.com'),
(213, 'Yuventus Bubun', 'Mitra Pendataan', 'Bakitolas', 'Lk', '+62 813-8010-5617', 530522100206, 'yuvenbubun@gmail.com'),
(214, 'Fransiska Tmaisan', 'Mitra Pendataan', 'Kuanek, RT009, RW004', 'Pr', '+62 082-2664-10536', 530523110030, 'ransytmaisan@gmail.com'),
(215, 'FLEGONI EMANUEL NABEN', 'Mitra Pendataan', 'Fatumnasi,RT/RW :028/006 Desa Eban, kec. Miomaffo Barat', 'Lk', '+62 823-5955-1351', 530523030130, 'gonynaben95@gmail.com'),
(216, 'Regina Elvasani Jalo', 'Mitra Pengolahan', 'Jl.Eltari RT 001 RW 001', 'Pr', '+62 813-3754-1064', 530522110014, 'evajalo14@gmail.com'),
(217, 'Yudith Botha', 'Mitra Pendataan', 'Sap\'an', 'Pr', '+62 822-6692-0847', 530523080001, 'yudithbotha@gmail.com'),
(218, 'DAMIANUS BABU TULU', 'Mitra Pendataan', 'Oekolo, RT/RW:016/004,Desa Humusu Oekolo, Kecamatan Insana Utara', 'Lk', '+62 812-2290-3128', 530523030048, 'tuludamianus@gmail.com'),
(219, 'FREDERIKUS SERAN', 'Mitra Pengolahan', 'JLN JENDRAL A. YANI, RT/RW: 037/007, KECAMATAN KOTA KEFAMENANU, KABUPATEN TIMOR TENGAH UTARA. KELURAHAN KEFAMENANU SELATAN.', 'Lk', '+62 853-6382-8229', 530523030136, 'frederikusseran00@gmail.com'),
(220, 'Ubaldus Taninas', 'Mitra Pendataan', 'Oemofa Rt/Rw:021/008Desa Naekake A Kecamatan Mutis Kab TTU', 'Lk', '+62 823-4286-4276', 530523030142, 'Nintautaninas189@gmail.com'),
(221, 'Dorothea Saijao', 'Mitra Pendataan', 'Benpasi RT 005 RW 006', 'Pr', '+62 852-0545-3598', 530522030045, 'dorotheasaijao@gmail.com'),
(222, 'Apolinaris Tnesi', 'Mitra Pendataan', 'Oenopu, RT 005/ RW 003', 'Lk', '+62 821-4546-0387', 530522100181, 'apolinaristnesi@gmail.com'),
(223, 'Christin Friliani Kleing', 'Mitra Pengolahan', 'Jln. Ahmad yani, AS. kodim 1618 TTU', 'Pr', '+62 812-3865-0731', 530522020012, 'christinkleing@gmail.com'),
(224, 'ROSA DELIMA KOLO', 'Mitra (Pendataan dan Pengolahan)', 'Desa Taekas', 'Pr', '+62 858-4710-3755', 0, 'selikolo11@gmail.com'),
(225, 'Maria Julia Ningsih Omenu', 'Mitra Pendataan', 'OEBKIN, RT.003/RW.001, DESA NAIOLA TIMUR', 'Pr', '+62 082-1445-68952', 530523110005, 'omenuningsih87@gmail.com'),
(226, 'Arnoldus Tulasi', 'Mitra Pendataan', 'Motadik', 'Lk', '+62 821-4490-8078', 530522020049, 'alonatulasi400@gmail.com'),
(227, 'Maria Getriana Taimenas', 'Mitra Pendataan', 'Jalan Nasional Trans Timor RT 002 RW 001', 'Pr', '+62 082-1771-37884', 530522030026, 'getrianataimenas@gmail.com'),
(228, 'Emiliana lake', 'Mitra Pendataan', 'Jl.A.YANI RT 35/RW 007 kelurahan Kefamenanu selatan', 'Pr', '+62 852-3057-0887', 530522100375, 'lakeemiliana@gmail.com'),
(229, 'Stefi Adelina Darsi', 'Mitra Pendataan', 'DESA BOTOF RT 001/RW 001', 'Pr', '+62 853-3830-5977', 530522030015, 'stefidarsi09@gmail.com'),
(230, 'Anita Ch Fandoe', 'Mitra Pendataan', 'RT 002 / RW 001', 'Pr', '+62 822-6189-5685', 530522030016, 'nitafandoe81@gmail.com'),
(231, 'Rosa Da Lima Kosat', 'Mitra Pendataan', 'BES\'ANA RT 012/RW 006', 'Pr', '+62 831-3078-9353', 530523030047, 'rosadalimakosat89@gmail.com'),
(232, 'Osepd Manasye Atonis', 'Mitra Pendataan', 'Matmanas, RT 024 RW 005, Kelurahan Benpasi Kecamatan Kota Kefamenanu', 'Lk', '+62 852-5315-5339', 530522100166, 'osteratonis@gmail.com'),
(233, 'Lidwin Serostiana Koa', 'Mitra Pendataan', 'Sasi, 008/003, kelurahan Sasi, Kota Kefamenanu', 'Pr', '+62 821-3794-2589', 530522020057, 'otykoa14@gmail.com'),
(234, 'Maria Tri Fridolin Hano\'e', 'Mitra (Pendataan dan Pengolahan)', 'Tualeu - Lanaus', 'Pr', '+62 813-1962-1675', 0, 'trifridolin@gmail.com'),
(235, 'Adelina Naimuni', 'Mitra Pendataan', 'Kuatnana RT/RW : 002/001', 'Pr', '+62 852-0594-0996', 530522100213, 'adelinanaimuni1985@gmail.com'),
(236, 'Rosalinda Bukifan', 'Mitra Pendataan', 'SONAF,RT/RW:002/001,Kel/Desa:BILOE,Kec:BIBOKI UTARA', 'Pr', '+62 081-3374-98469', 530523030079, 'rosalindabukifan13@gmail.com'),
(237, 'Apryana Reni Tasuib', 'Mitra Pengolahan', 'Bukit 10 RT 12 RW 002', 'Pr', '+62 822-4787-0755', 530523060040, 'apryanatasuib@gmail.com'),
(238, 'Yovita Librianti Unab', 'Mitra Pendataan', 'Jl. Akasia RT 08 RW 04 Kel. Kefa Utara', 'Pr', '+62 813-3988-5559', 530522030023, 'Unabyovita@gmail.com'),
(239, 'Arrydarsmi Na\'at', 'Mitra Pengolahan', 'Besnaen RT 019 RW 005', 'Lk', '+62 082-3411-95406', 530523110036, 'arrynaat38@gmail.com'),
(240, 'Ferdinandus Wae', 'Mitra Pendataan', 'Jl Nuri Kefamenanu Selatan', 'Lk', '+62 214-5570-831', 530523030141, 'waeferi6@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `superadmin`
--

CREATE TABLE `superadmin` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `superadmin`
--

INSERT INTO `superadmin` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Super Admin', 'superadmin', '$2y$10$tVrznGEOr0tEyMhkqC3oc.02xZ5DBo8A/rJdZTo7VoSDMydzbJvF2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `sub_bagian` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `session_token` varchar(255) DEFAULT NULL,
  `session_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `sub_bagian`, `status`, `session_token`, `session_id`) VALUES
(1, 'Pieter Dikson R. Balukh', 'Pieter Dikson', '$2y$10$XZPvTBZMHpnPeOQ6rDOOKOXxoHmoBHtdzzED/pySkdT2WgSpH6Tzi', 'Kepala Kantor', '', 'aktif', NULL, NULL),
(2, 'Egidius I. Laka', 'Egi Laka', '$2y$10$hoo17z9do0ZfN5oSHtTpsuJRpKwpNd2QdCH2F33SW0m2vjNj6/kOC', 'Kepala Sub Bagian Umum', '', 'aktif', NULL, NULL),
(3, 'Maria V. Makung', 'Maria Makung', '$2y$10$jSkWIAw2NEDMgFGi/e2eFOVx.s60pvt8PmpDYknqD6ZU81CYk8YkW', 'Admin Tim', 'Distribusi', 'aktif', NULL, NULL),
(4, 'Riky V. Balla ', 'Riky Balla', '$2y$10$C4l/D8EsjTQ0TS0hWrmSDeT4dcQACkRM1QbDCfWishvXzuxVDnHXW', 'Admin Tim', 'Produksi', 'aktif', NULL, NULL),
(5, 'Antonius Matutina', 'Antonius', '$2y$10$tqVDIgutQcbisCEaEO1Qie1lsrwGBm8SmJnUrfYQRaJ..iCSsFD.y', 'Admin Tim', 'Sosial', 'aktif', NULL, NULL),
(6, 'Agus Heri Siswanto', 'Agus Siswanto', '$2y$10$OslDrafvEphn5.VQwQZO0uLo0MIfP.Dlr7mOZcyNGopGf3dazk7ma', 'Admin Tim', 'IPDS', 'aktif', NULL, NULL),
(7, 'Maria G. Bani', 'Maria Bani', '$2y$10$.CuSHJUbDBiMcmguUjRuKOzfVe5Q.BItgmHKYpys/KiXp2HMdbdVi', 'Admin Tim', 'Nerwilis', 'aktif', NULL, NULL),
(8, 'Pontianus A. D. Lejap', 'Pontianus', '$2y$10$/MRyfBIeqMieOxT6MzGf1eejL8xVD3ocXiyu1zarfcIrY2vx5MPFS', 'Admin Tim', 'Desa Cantik', 'aktif', NULL, NULL),
(9, 'Katharina K. Tulasi', 'Katharina Tulasi', '$2y$10$6jmGUD.aQepwsNn5LRRkxOJPr/JFBAg1ATwvD97z3PWp0RMmPsc/S', 'Admin Tim', 'PSS', 'aktif', NULL, NULL),
(10, 'I Wayan Chandra Purwatmaja', 'Wayan Chandra', '$2y$10$MN6ysTNrUMjm1iM06tQ3Be6tBbtz1S0y6BJ3A3ShtVy4.NpmBlaHi', 'Admin Tim', 'IPM', 'aktif', NULL, NULL),
(11, 'I Wayan Chandra Purwatmaja', 'Chandra', '$2y$10$4BPZnfxFlNO7z/vas3uNPuVw03wxmCzUCpGbcpt8BmSe3Z6c6bE82', 'Admin Tim', 'ZI', 'aktif', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kegiatan_mitra`
--
ALTER TABLE `kegiatan_mitra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kegiatan_id` (`kegiatan_id`),
  ADD KEY `mitra_id` (`mitra_id`);

--
-- Indeks untuk tabel `komentar_kegiatan`
--
ALTER TABLE `komentar_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kro`
--
ALTER TABLE `kro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kro` (`kode_kro`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_kegiatan`
--
ALTER TABLE `log_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kegiatan_mitra`
--
ALTER TABLE `kegiatan_mitra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `komentar_kegiatan`
--
ALTER TABLE `komentar_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kro`
--
ALTER TABLE `kro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_kegiatan`
--
ALTER TABLE `log_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT untuk tabel `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kegiatan_mitra`
--
ALTER TABLE `kegiatan_mitra`
  ADD CONSTRAINT `kegiatan_mitra_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`),
  ADD CONSTRAINT `kegiatan_mitra_ibfk_2` FOREIGN KEY (`mitra_id`) REFERENCES `mitra` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
