-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2022 at 10:19 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `satria_eqm`
--

-- --------------------------------------------------------

--
-- Table structure for table `apreciation_program`
--

CREATE TABLE `apreciation_program` (
  `id_apreciation_program` int(11) NOT NULL,
  `id_users` int(20) NOT NULL,
  `created_by` int(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(20) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id_document` int(11) NOT NULL,
  `nama_document` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `document` varchar(200) NOT NULL,
  `detail_document` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `id_jenis_document` int(11) NOT NULL,
  `id_jenis_berkas` int(11) DEFAULT NULL,
  `id_jenis_internal_audit` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id_document`, `nama_document`, `created_at`, `updated_at`, `document`, `detail_document`, `created_by`, `updated_by`, `id_jenis_document`, `id_jenis_berkas`, `id_jenis_internal_audit`, `department`, `division`) VALUES
(32, 'Artikel QM', '2022-09-15 14:30:45', '2022-09-15 14:30:52', '1663227045_11711734.pdf', 'Artike QM knowladge', 228, 228, 1, 1, NULL, NULL, NULL),
(33, 'Testing', '2022-09-15 14:31:21', '2022-09-15 14:31:59', '1663227119_11711734.pdf', 'Testing lanmark', 228, 228, 1, 2, NULL, NULL, NULL),
(34, 'Milestone', '2022-09-15 14:32:25', NULL, '1663227145_11711734.pdf', 'testing', 228, NULL, 1, 3, NULL, NULL, NULL),
(35, 'Artikel Checklist', '2022-09-15 14:32:51', '2022-09-15 14:33:56', '1663227236_contoh artikel (1)_63103ea8073cb (1).pdf', 'update testing', 228, 228, 7, NULL, 1, NULL, NULL),
(36, 'Jadwal Internal', '2022-09-15 14:33:37', NULL, '1663227217_contoh artikel (1)_63103ea8073cb.pdf', 'test', 228, NULL, 7, NULL, 2, NULL, NULL),
(37, 'laporan', '2022-09-15 14:34:12', NULL, '1663227252_contoh artikel.pdf', 'test', 228, NULL, 7, NULL, 3, NULL, NULL),
(39, 'Temuan Divisi Update', '2022-09-15 14:35:16', '2022-09-15 14:35:39', '1663227339_11711734.pdf', 'Testing Division Finnace', 228, 228, 10, NULL, 5, 0, 'Finance'),
(40, 'temuan department', '2022-09-15 14:36:03', '2022-09-15 14:36:32', '1663227384_11711734.pdf', 'testing', 228, 228, 10, NULL, 4, 7006, '0'),
(41, 'jenis Checklist', '2022-09-15 14:37:01', '2022-09-15 14:37:19', '1663227421_11711734.pdf', 'testing', 228, 228, 9, NULL, 1, NULL, NULL),
(42, 'Jadwal Terbaru', '2022-09-15 14:37:41', '2022-09-15 14:42:06', '1663227461_contoh artikel (1).pdf', 'update jadwal', 228, 228, 9, NULL, 2, NULL, NULL),
(43, 'update laporan', '2022-09-15 14:42:36', '2022-09-15 14:42:56', '1663227776_contoh artikel.pdf', 'laporan', 228, 228, 9, NULL, 3, NULL, NULL),
(44, 'Temuan Divisi', '2022-09-15 14:43:23', NULL, '1663227803_11711734.pdf', 'test', 228, NULL, 11, NULL, 5, 0, 'Corporate Digitalization & Command Center Function'),
(45, 'Temuan department', '2022-09-15 14:43:49', '2022-09-15 14:48:19', '1663227829_artikel.pdf', 'artikel update', 228, 228, 11, NULL, 4, 7006, '0'),
(47, 'Event dalam bulan ini hadirilah', '2022-09-15 15:03:14', '2022-09-15 15:03:27', '1663228994_event.png', 'testing hadirilah event pada bulan ini ya', 228, 228, 6, NULL, NULL, NULL, NULL),
(49, 'News berita terbaru', '2022-09-15 15:06:40', NULL, '1663229200_news.jpg', 'ada berita terbaru lho silahkan di cek aja', 228, NULL, 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `id_file` int(11) NOT NULL,
  `id_folder` int(11) DEFAULT NULL,
  `parent_folder` int(11) DEFAULT NULL,
  `nama_file` varchar(200) NOT NULL,
  `file` varchar(200) NOT NULL,
  `level` varchar(5) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`id_file`, `id_folder`, `parent_folder`, `nama_file`, `file`, `level`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(15, NULL, NULL, '11711734.pdf', 'file/11711734_6322d98c44db9.pdf', '0', '2022-09-15 14:51:40', 228, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE `folder` (
  `id_folder` int(11) NOT NULL,
  `nama_folder` varchar(100) NOT NULL,
  `parent_folder` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`id_folder`, `nama_folder`, `parent_folder`, `created_by`, `created_at`, `updated_by`, `updated_at`, `level`) VALUES
(39, 'Level 0', NULL, 228, '2022-09-15 14:49:21', 228, '2022-09-15 14:51:25', 0),
(40, 'Level 1', NULL, 228, '2022-09-15 14:52:12', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_berkas`
--

CREATE TABLE `jenis_berkas` (
  `id_jenis_berkas` int(11) NOT NULL,
  `jenis_berkas` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_berkas`
--

INSERT INTO `jenis_berkas` (`id_jenis_berkas`, `jenis_berkas`) VALUES
(1, 'Qm Policy'),
(2, 'Landmark'),
(3, 'Milestone'),
(4, 'Quality Management'),
(5, 'Iso Procedure');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_document`
--

CREATE TABLE `jenis_document` (
  `id_jenis_document` int(11) NOT NULL,
  `jenis_document` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_document`
--

INSERT INTO `jenis_document` (`id_jenis_document`, `jenis_document`) VALUES
(1, 'QM Knowladge'),
(2, 'News Update'),
(3, 'List SOP'),
(4, 'General Info Internal'),
(5, 'General Info External'),
(6, 'Event '),
(7, 'Audit Internal ISO Preparation'),
(8, 'Apreciation Program'),
(9, 'Audit External ISO Preparation '),
(10, 'Audit Internal ISO Findings'),
(11, 'Audit External ISO Findings');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_internal_audit`
--

CREATE TABLE `jenis_internal_audit` (
  `id_jenis_internal_audit` int(11) NOT NULL,
  `jenis_internal_audit` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jenis_internal_audit`
--

INSERT INTO `jenis_internal_audit` (`id_jenis_internal_audit`, `jenis_internal_audit`) VALUES
(1, 'Checklist'),
(2, 'Jadwal Audit'),
(3, 'Laporan Audit'),
(4, 'Department'),
(5, 'Division');

-- --------------------------------------------------------

--
-- Table structure for table `sub_folder`
--

CREATE TABLE `sub_folder` (
  `id_sub_folder` int(11) NOT NULL,
  `sub_folder` varchar(100) DEFAULT NULL,
  `sub_level` int(10) NOT NULL,
  `id_folder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_folder`
--

INSERT INTO `sub_folder` (`id_sub_folder`, `sub_folder`, `sub_level`, `id_folder`) VALUES
(1, 'test', 1, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_apreciation_program`
-- (See below for the actual view)
--
CREATE TABLE `vw_apreciation_program` (
`id_apreciation_program` int(11)
,`id_users` int(20)
,`nama` varchar(255)
,`photo` varchar(255)
,`created_by` int(20)
,`updated_by` int(20)
,`created` varchar(255)
,`updated` varchar(255)
,`created_at` datetime
,`updated_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_document`
-- (See below for the actual view)
--
CREATE TABLE `vw_document` (
`id_document` int(11)
,`nama_document` varchar(100)
,`created_at` datetime
,`created_by` int(11)
,`detail_document` text
,`document` varchar(200)
,`updated_at` datetime
,`updated_by` int(11)
,`id_jenis_document` int(11)
,`id_jenis_berkas` int(11)
,`jenis_berkas` varchar(200)
,`jenis_document` varchar(200)
,`created` varchar(255)
,`updated` varchar(255)
,`id_jenis_internal_audit` int(11)
,`jenis_internal_audit` varchar(200)
,`department` varchar(100)
,`division` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_file`
-- (See below for the actual view)
--
CREATE TABLE `vw_file` (
`id_file` int(11)
,`id_folder` int(11)
,`parent_folder` int(11)
,`nama_file` varchar(200)
,`file` varchar(200)
,`level` varchar(5)
,`created_at` datetime
,`created_by` int(11)
,`updated_at` datetime
,`updated_by` int(11)
,`created` varchar(255)
,`updated` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_folder`
-- (See below for the actual view)
--
CREATE TABLE `vw_folder` (
`id_folder` int(11)
,`nama_folder` varchar(100)
,`parent_folder` int(11)
,`created_by` int(11)
,`created_at` datetime
,`updated_by` int(11)
,`updated_at` datetime
,`level` int(11)
,`created` varchar(255)
,`updated` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_apreciation_program`
--
DROP TABLE IF EXISTS `vw_apreciation_program`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_apreciation_program`  AS SELECT `a`.`id_apreciation_program` AS `id_apreciation_program`, `a`.`id_users` AS `id_users`, `u`.`name` AS `nama`, `u`.`photo` AS `photo`, `a`.`created_by` AS `created_by`, `a`.`updated_by` AS `updated_by`, `us`.`name` AS `created`, `uss`.`name` AS `updated`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at` FROM (((`apreciation_program` `a` join `satria`.`users` `u` on(`u`.`id` = `a`.`id_users`)) join `satria`.`users` `us` on(`us`.`id` = `a`.`created_by`)) left join `satria`.`users` `uss` on(`uss`.`id` = `a`.`updated_by`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_document`
--
DROP TABLE IF EXISTS `vw_document`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_document`  AS SELECT `d`.`id_document` AS `id_document`, `d`.`nama_document` AS `nama_document`, `d`.`created_at` AS `created_at`, `d`.`created_by` AS `created_by`, `d`.`detail_document` AS `detail_document`, `d`.`document` AS `document`, `d`.`updated_at` AS `updated_at`, `d`.`updated_by` AS `updated_by`, `jd`.`id_jenis_document` AS `id_jenis_document`, `d`.`id_jenis_berkas` AS `id_jenis_berkas`, `j`.`jenis_berkas` AS `jenis_berkas`, `jd`.`jenis_document` AS `jenis_document`, `u`.`name` AS `created`, `us`.`name` AS `updated`, `jia`.`id_jenis_internal_audit` AS `id_jenis_internal_audit`, `jia`.`jenis_internal_audit` AS `jenis_internal_audit`, `dep`.`nama` AS `department`, `d`.`division` AS `division` FROM ((((((`document` `d` left join `jenis_berkas` `j` on(`j`.`id_jenis_berkas` = `d`.`id_jenis_berkas`)) join `jenis_document` `jd` on(`jd`.`id_jenis_document` = `d`.`id_jenis_document`)) join `satria`.`users` `u` on(`u`.`id` = `d`.`created_by`)) left join `satria`.`users` `us` on(`us`.`id` = `d`.`updated_by`)) left join `jenis_internal_audit` `jia` on(`jia`.`id_jenis_internal_audit` = `d`.`id_jenis_internal_audit`)) left join `satria`.`mst_dept` `dep` on(`dep`.`id` = `d`.`department`)) GROUP BY `d`.`id_document` ORDER BY `d`.`id_document` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_file`
--
DROP TABLE IF EXISTS `vw_file`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_file`  AS SELECT `f`.`id_file` AS `id_file`, `f`.`id_folder` AS `id_folder`, `f`.`parent_folder` AS `parent_folder`, `f`.`nama_file` AS `nama_file`, `f`.`file` AS `file`, `f`.`level` AS `level`, `f`.`created_at` AS `created_at`, `f`.`created_by` AS `created_by`, `f`.`updated_at` AS `updated_at`, `f`.`updated_by` AS `updated_by`, `u`.`name` AS `created`, `us`.`name` AS `updated` FROM ((`file` `f` join `satria`.`users` `u` on(`f`.`created_by` = `u`.`id`)) left join `satria`.`users` `us` on(`f`.`updated_by` = `us`.`id`)) ORDER BY `f`.`nama_file` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_folder`
--
DROP TABLE IF EXISTS `vw_folder`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_folder`  AS SELECT `f`.`id_folder` AS `id_folder`, `f`.`nama_folder` AS `nama_folder`, `f`.`parent_folder` AS `parent_folder`, `f`.`created_by` AS `created_by`, `f`.`created_at` AS `created_at`, `f`.`updated_by` AS `updated_by`, `f`.`updated_at` AS `updated_at`, `f`.`level` AS `level`, `u`.`name` AS `created`, `us`.`name` AS `updated` FROM ((`folder` `f` join `satria`.`users` `u` on(`f`.`created_by` = `u`.`id`)) left join `satria`.`users` `us` on(`f`.`updated_by` = `us`.`id`)) ORDER BY `f`.`nama_folder` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apreciation_program`
--
ALTER TABLE `apreciation_program`
  ADD PRIMARY KEY (`id_apreciation_program`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id_document`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id_file`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id_folder`);

--
-- Indexes for table `jenis_berkas`
--
ALTER TABLE `jenis_berkas`
  ADD PRIMARY KEY (`id_jenis_berkas`);

--
-- Indexes for table `jenis_document`
--
ALTER TABLE `jenis_document`
  ADD PRIMARY KEY (`id_jenis_document`);

--
-- Indexes for table `jenis_internal_audit`
--
ALTER TABLE `jenis_internal_audit`
  ADD PRIMARY KEY (`id_jenis_internal_audit`);

--
-- Indexes for table `sub_folder`
--
ALTER TABLE `sub_folder`
  ADD PRIMARY KEY (`id_sub_folder`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apreciation_program`
--
ALTER TABLE `apreciation_program`
  MODIFY `id_apreciation_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id_file` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `id_folder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `jenis_berkas`
--
ALTER TABLE `jenis_berkas`
  MODIFY `id_jenis_berkas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jenis_document`
--
ALTER TABLE `jenis_document`
  MODIFY `id_jenis_document` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jenis_internal_audit`
--
ALTER TABLE `jenis_internal_audit`
  MODIFY `id_jenis_internal_audit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_folder`
--
ALTER TABLE `sub_folder`
  MODIFY `id_sub_folder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
