-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.6.4-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ciptasejati
CREATE DATABASE IF NOT EXISTS `ciptasejati` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `ciptasejati`;

-- Dumping structure for table ciptasejati.c_footer
DROP TABLE IF EXISTS `c_footer`;
CREATE TABLE IF NOT EXISTS `c_footer` (
  `FOOTER_ID` varchar(50) NOT NULL,
  `FOOTER_KANTOR` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`FOOTER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.c_footer: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_footer_detail
DROP TABLE IF EXISTS `c_footer_detail`;
CREATE TABLE IF NOT EXISTS `c_footer_detail` (
  `FOOTER_ID` varchar(50) DEFAULT NULL,
  `FOOTER_TYPE` varchar(50) DEFAULT NULL COMMENT 'KONTAK / MENU / SOSMED',
  `FOOTER_DESKRIPSI` text DEFAULT NULL,
  KEY `FOOTER_ID` (`FOOTER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.c_footer_detail: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_header
DROP TABLE IF EXISTS `c_header`;
CREATE TABLE IF NOT EXISTS `c_header` (
  `HEADER_ID` varchar(50) NOT NULL,
  `HEADER_LOGO1` text DEFAULT NULL,
  `HEADER_LOGO2` text DEFAULT NULL,
  `HEADER_TELPLOGO` text DEFAULT NULL,
  `HEADER_TELP` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`HEADER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.c_header: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_header_detail
DROP TABLE IF EXISTS `c_header_detail`;
CREATE TABLE IF NOT EXISTS `c_header_detail` (
  `HEADER_ID` varchar(50) DEFAULT NULL,
  `HEADER_SOSMEDLOGO` text DEFAULT NULL,
  `HEADER_SOSMED` text DEFAULT NULL,
  KEY `HEADER_ID` (`HEADER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.c_header_detail: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.m_anggota
DROP TABLE IF EXISTS `m_anggota`;
CREATE TABLE IF NOT EXISTS `m_anggota` (
  `ANGGOTA_ID` varchar(50) NOT NULL,
  `CABANG_ID` varchar(50) DEFAULT NULL COMMENT 'm_cabang',
  `SABUK_ID` varchar(50) DEFAULT NULL,
  `ANGGOTA_KTP` varchar(50) DEFAULT NULL,
  `ANGGOTA_NAMA` varchar(50) DEFAULT NULL,
  `ANGGOTA_ALAMAT` text DEFAULT NULL,
  `ANGGOTA_PEKERJAAN` varchar(50) DEFAULT NULL,
  `ANGGOTA_KELAMIN` varchar(50) DEFAULT NULL,
  `ANGGOTA_TEMPAT_LAHIR` varchar(50) DEFAULT NULL,
  `ANGGOTA_TANGGAL_LAHIR` date DEFAULT NULL,
  `ANGGOTA_HP` varchar(50) DEFAULT NULL,
  `ANGGOTA_EMAIL` text DEFAULT NULL,
  `ANGGOTA_PIC` text DEFAULT NULL,
  `ANGGOTA_JOIN` date DEFAULT NULL,
  `ANGGOTA_RESIGN` date DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ANGGOTA_ID`),
  KEY `SABUK_ID` (`SABUK_ID`),
  KEY `ANGGOTA_KTP` (`ANGGOTA_KTP`),
  KEY `RANTING_ID` (`CABANG_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_anggota: ~1 rows (approximately)
REPLACE INTO `m_anggota` (`ANGGOTA_ID`, `CABANG_ID`, `SABUK_ID`, `ANGGOTA_KTP`, `ANGGOTA_NAMA`, `ANGGOTA_ALAMAT`, `ANGGOTA_PEKERJAAN`, `ANGGOTA_KELAMIN`, `ANGGOTA_TEMPAT_LAHIR`, `ANGGOTA_TANGGAL_LAHIR`, `ANGGOTA_HP`, `ANGGOTA_EMAIL`, `ANGGOTA_PIC`, `ANGGOTA_JOIN`, `ANGGOTA_RESIGN`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('09.03.2023.0001', 'RTG-0923-0001', 'SBK-0923-0002', '1234567891011', 'Husni', 'Surabaya', NULL, 'L', 'Surabaya', '1990-08-21', '087702462220', 'asdas@mail.com', NULL, '2023-09-21', NULL, '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_anggota_log
DROP TABLE IF EXISTS `m_anggota_log`;
CREATE TABLE IF NOT EXISTS `m_anggota_log` (
  `ANGGOTA_ID` varchar(50) NOT NULL,
  `RANTING_ID` varchar(50) DEFAULT NULL COMMENT 'm_ranting',
  `SABUK_ID` varchar(50) DEFAULT NULL,
  `ANGGOTA_NAMA` varchar(50) DEFAULT NULL,
  `ANGGOTA_ALAMAT` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  `LOG_STATUS` varchar(50) DEFAULT NULL,
  `LOG_DATE` datetime DEFAULT NULL,
  KEY `ANGGOTA_ID` (`ANGGOTA_ID`),
  KEY `RANTING_ID` (`RANTING_ID`),
  KEY `SABUK_ID` (`SABUK_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_anggota_log: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.m_cabang
DROP TABLE IF EXISTS `m_cabang`;
CREATE TABLE IF NOT EXISTS `m_cabang` (
  `CABANG_ID` varchar(50) NOT NULL,
  `DAERAH_ID` varchar(50) DEFAULT NULL COMMENT 'm_daerah',
  `CABANG_DESKRIPSI` varchar(50) DEFAULT NULL,
  `CABANG_SEKRETARIAT` varchar(50) DEFAULT NULL,
  `CABANG_PENGURUS` varchar(50) DEFAULT NULL,
  `CABANG_MAP` text DEFAULT NULL,
  `CABANG_LAT` text DEFAULT NULL,
  `CABANG_LONG` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`CABANG_ID`),
  KEY `DAERAH_ID` (`DAERAH_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_cabang: ~1 rows (approximately)
REPLACE INTO `m_cabang` (`CABANG_ID`, `DAERAH_ID`, `CABANG_DESKRIPSI`, `CABANG_SEKRETARIAT`, `CABANG_PENGURUS`, `CABANG_MAP`, `CABANG_LAT`, `CABANG_LONG`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('001.001', '001', 'Banjarmasin', 'Daerah Sekretariat', 'Daerah Pengurus', NULL, NULL, NULL, '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_daerah
DROP TABLE IF EXISTS `m_daerah`;
CREATE TABLE IF NOT EXISTS `m_daerah` (
  `DAERAH_ID` varchar(50) NOT NULL,
  `PUSAT_ID` varchar(50) DEFAULT NULL COMMENT 'm_pusat',
  `DAERAH_DESKRIPSI` varchar(50) DEFAULT NULL,
  `DAERAH_SEKRETARIAT` varchar(50) DEFAULT NULL,
  `DAERAH_PENGURUS` varchar(50) DEFAULT NULL,
  `DAERAH_MAP` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`DAERAH_ID`),
  KEY `PUSAT_ID` (`PUSAT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_daerah: ~1 rows (approximately)
REPLACE INTO `m_daerah` (`DAERAH_ID`, `PUSAT_ID`, `DAERAH_DESKRIPSI`, `DAERAH_SEKRETARIAT`, `DAERAH_PENGURUS`, `DAERAH_MAP`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('DRH-0923-0001', 'PST-0923-0001', 'Jawa Timur', 'Daerah Sekretariat', 'Daerah Pengurus', NULL, '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_menu
DROP TABLE IF EXISTS `m_menu`;
CREATE TABLE IF NOT EXISTS `m_menu` (
  `MENU_ID` varchar(50) NOT NULL,
  `MENU_ICON` varchar(50) DEFAULT NULL,
  `MENU_INDUK` varchar(50) DEFAULT NULL,
  `MENU_NAMA` varchar(50) DEFAULT NULL,
  `MENU_DESKRIPSI` varchar(50) DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT NULL,
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`MENU_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_menu: ~27 rows (approximately)
REPLACE INTO `m_menu` (`MENU_ID`, `MENU_ICON`, `MENU_INDUK`, `MENU_NAMA`, `MENU_DESKRIPSI`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('1', 'ico-home2', '0', 'Dashboard', 'Menu Dashboard', NULL, NULL, NULL),
	('2', 'ico-grid', '0', 'Master', 'Menu Master', NULL, NULL, NULL),
	('2.1', NULL, '2', 'Sabuk', 'Data Master Sabuk', NULL, NULL, NULL),
	('2.2', NULL, '2', 'Anggota', 'Data Master Anggota', NULL, NULL, NULL),
	('2.3', NULL, '2', 'Kantor', 'Data Master Kantor', NULL, NULL, NULL),
	('2.3.1', NULL, '2.3', 'Pusat', 'Data Master Kantor Pusat', NULL, NULL, NULL),
	('2.3.2', NULL, '2.3', 'Daerah', 'Data Master Kantor Daerah', NULL, NULL, NULL),
	('2.3.3', NULL, '2.3', 'Cabang', 'Data Master Kantor Cabang', NULL, NULL, NULL),
	('2.3.4', NULL, '2.3', 'Ranting', 'Data Master Kantor Ranting', NULL, NULL, NULL),
	('3', 'ico-edit', '0', 'Transaksi', 'Menu Transaksi', NULL, NULL, NULL),
	('3.1', NULL, '3', 'PPD', 'Pembukaan Pusat Daya', NULL, NULL, NULL),
	('3.2', NULL, '3', 'UKT', 'Ujian Kenaikan Tingkat', NULL, NULL, NULL),
	('3.3', NULL, '3', 'Latgab', 'Latihan Gabungan', NULL, NULL, NULL),
	('4', 'ico-file-pdf', '0', 'Laporan', 'Menu Laporan', NULL, NULL, NULL),
	('4.1', NULL, '4', 'Daftar Perguruan', 'Laporan Data Perguruan', NULL, NULL, NULL),
	('4.2', NULL, '4', 'Daftar Dewan Guru', 'Laporan Data Dewan Guru', NULL, NULL, NULL),
	('4.3', NULL, '4', 'Daftar Pelatin', 'Laporan Data Pelatih', NULL, NULL, NULL),
	('4.4', NULL, '4', 'Daftar Pengurus', 'Laporan Data Pengurus', NULL, NULL, NULL),
	('4.5', NULL, '4', 'Daftar Anggota', 'Laporan Data Anggota', NULL, NULL, NULL),
	('4.6', NULL, '4', 'ID Keanggotaan', NULL, NULL, NULL, NULL),
	('5', 'ico-settings', '0', 'Admin', 'Menu Admin', NULL, NULL, NULL),
	('5.1', NULL, '5', 'User', 'Data User', NULL, NULL, NULL),
	('5.2', NULL, '5', 'Company Profile', 'Menu Kelola Halaman Web Utama', NULL, NULL, NULL),
	('5.2.1', NULL, '5.2', 'Halaman Beranda', 'Kelola Halaman Beranda', NULL, NULL, NULL),
	('5.2.2', NULL, '5.2', 'Halaman Profil', 'Kelola Halaman Profil', NULL, NULL, NULL),
	('5.2.3', NULL, '5.2', 'Halaman Kegiatan', 'Kelola Halaman Kegiatan', NULL, NULL, NULL),
	('5.2.3.1', NULL, '5.2.3', 'Kegiatan A', 'Kelola Data Kegiatan A', NULL, NULL, NULL),
	('5.2.3.2', NULL, '5.2.3', 'Kegiatan B', 'Kelola Data Kegiatan B', NULL, NULL, NULL);

-- Dumping structure for table ciptasejati.m_menuakses
DROP TABLE IF EXISTS `m_menuakses`;
CREATE TABLE IF NOT EXISTS `m_menuakses` (
  `MENU_ID` varchar(50) DEFAULT NULL COMMENT 'm_menu',
  `USER_AKSES` varchar(50) DEFAULT NULL COMMENT 'm_user',
  `VIEW` varchar(50) DEFAULT NULL,
  `ADD` varchar(50) DEFAULT NULL,
  `EDIT` varchar(50) DEFAULT NULL,
  `DELETE` varchar(50) DEFAULT NULL,
  `APPROVE` varchar(50) DEFAULT NULL,
  `PRINT` varchar(50) DEFAULT NULL,
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  KEY `MENU_ID` (`MENU_ID`),
  KEY `USER_ID` (`USER_AKSES`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_menuakses: ~21 rows (approximately)
REPLACE INTO `m_menuakses` (`MENU_ID`, `USER_AKSES`, `VIEW`, `ADD`, `EDIT`, `DELETE`, `APPROVE`, `PRINT`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('2.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('2.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('2.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('2.5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('3.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('3.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('4.6', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('5.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00'),
	('5.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-09-17 21:29:00');

-- Dumping structure for table ciptasejati.m_pusat
DROP TABLE IF EXISTS `m_pusat`;
CREATE TABLE IF NOT EXISTS `m_pusat` (
  `PUSAT_ID` varchar(50) NOT NULL,
  `PUSAT_DESKRIPSI` varchar(50) DEFAULT NULL,
  `PUSAT_SEKRETARIAT` text DEFAULT NULL,
  `PUSAT_KEPENGURUSAN` varchar(50) DEFAULT NULL,
  `PUSAT_MAP` text DEFAULT NULL,
  `PUSAT_LAT` text DEFAULT NULL,
  `PUSAT_LONG` text DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`PUSAT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_pusat: ~1 rows (approximately)
REPLACE INTO `m_pusat` (`PUSAT_ID`, `PUSAT_DESKRIPSI`, `PUSAT_SEKRETARIAT`, `PUSAT_KEPENGURUSAN`, `PUSAT_MAP`, `PUSAT_LAT`, `PUSAT_LONG`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('001', 'Banjarmasin', 'Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan', NULL, '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698203909709!5m2!1sen!2sid" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', '-3.3063120100780785', '114.56921894055016', '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_ranting
DROP TABLE IF EXISTS `m_ranting`;
CREATE TABLE IF NOT EXISTS `m_ranting` (
  `RANTING_ID` varchar(50) NOT NULL,
  `CABANG_ID` varchar(50) NOT NULL COMMENT 'm_cabang',
  `RANTING_DESKRIPSI` varchar(50) DEFAULT NULL,
  `RANTING_SEKRETARIAT` varchar(50) DEFAULT NULL,
  `RANTING_PENGURUS` varchar(50) DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`RANTING_ID`),
  KEY `CABANG_ID` (`CABANG_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_ranting: ~0 rows (approximately)
REPLACE INTO `m_ranting` (`RANTING_ID`, `CABANG_ID`, `RANTING_DESKRIPSI`, `RANTING_SEKRETARIAT`, `RANTING_PENGURUS`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('RTG-0923-0001', 'CBG-0923-0001', 'Ranting A', 'Ranting Sekretariat', 'Ranting Pengurus', '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_sabuk
DROP TABLE IF EXISTS `m_sabuk`;
CREATE TABLE IF NOT EXISTS `m_sabuk` (
  `SABUK_ID` varchar(50) NOT NULL,
  `SABUK_NAMA` varchar(100) DEFAULT NULL,
  `SABUK_DESKRIPSI` varchar(50) DEFAULT NULL,
  `SABUK_TINGKATAN` varchar(50) DEFAULT NULL,
  `SABUK_AKSES` varchar(50) DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`SABUK_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_sabuk: ~14 rows (approximately)
REPLACE INTO `m_sabuk` (`SABUK_ID`, `SABUK_NAMA`, `SABUK_DESKRIPSI`, `SABUK_TINGKATAN`, `SABUK_AKSES`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('SBK-0923-0001', 'Putih', 'Pra Pemula', '1', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0002', 'Kuning', 'Pemula', '2', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0003', 'Hijau', 'Dasar-I', '3', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0004', 'Biru', 'Dasar-II', '4', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0005', 'Coklat', 'Asisten Pelatih', '5', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0006', 'Hitam-I', 'Pelatih Muda', '6', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0007', 'Hitam-II', 'Pelatih Madya', '7', 'User', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0008', 'Hitam-III', 'Pelatih Utama', '8', 'Pembina', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0009', 'Hitam-IV', 'Guru Muda Junior', '9', 'Dewan Guru', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0010', 'Merah-I', 'Guru Muda', '10', 'Dewan Guru', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0011', 'Merah-II', 'Guru Madya', '11', 'Dewan Guru', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0012', 'Merah-III', 'Guru Utama', '12', 'Dewan Guru', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0013', 'Merah-IV', 'Guru Besar', '13', 'Dewan Guru', '0', 'adminCS01', '2023-09-23 11:24:00'),
	('SBK-0923-0014', 'Pengurus', 'Pengurus', '14', 'Admin', '0', NULL, NULL),
	('SBK-0923-0015', 'Administrator', 'Administrator', '15', 'Administrator', '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_user
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE IF NOT EXISTS `m_user` (
  `ANGGOTA_ID` varchar(50) NOT NULL,
  `USER_PASSWORD` varchar(200) DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT 'SYSTEM',
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ANGGOTA_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_user: ~2 rows (approximately)
REPLACE INTO `m_user` (`ANGGOTA_ID`, `USER_PASSWORD`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('09.03.2023.0001', '$2y$12$HeACI0qKB.2Wv6xVExoGvuZUgJpzrs97qbnNhGSvDU1oNkhyYyEn6', '0', 'SYSTEM', NULL),
	('admincs01', '$2y$12$HeACI0qKB.2Wv6xVExoGvuZUgJpzrs97qbnNhGSvDU1oNkhyYyEn6', '0', 'SYSTEM', '2023-09-17 21:07:59');

-- Dumping structure for table ciptasejati.m_user_log
DROP TABLE IF EXISTS `m_user_log`;
CREATE TABLE IF NOT EXISTS `m_user_log` (
  `ANGGOTA_ID` varchar(50) NOT NULL,
  `USER_PASSWORD` varchar(200) DEFAULT NULL,
  `DELETION_STATUS` varchar(50) DEFAULT '0',
  `INPUT_BY` varchar(50) DEFAULT 'SYSTEM',
  `INPUT_DATE` datetime DEFAULT NULL,
  `LOG_STATUS` varchar(50) DEFAULT NULL,
  KEY `ANGGOTA_ID` (`ANGGOTA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.m_user_log: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.p_param
DROP TABLE IF EXISTS `p_param`;
CREATE TABLE IF NOT EXISTS `p_param` (
  `COMPANY_PROFILE` text DEFAULT NULL,
  `SEJARAH` text DEFAULT NULL,
  `LOGO` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ciptasejati.p_param: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
