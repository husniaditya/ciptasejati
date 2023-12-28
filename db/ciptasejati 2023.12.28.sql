-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
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
CREATE DATABASE IF NOT EXISTS `ciptasejati` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ciptasejati`;

-- Dumping structure for table ciptasejati.c_footer
DROP TABLE IF EXISTS `c_footer`;
CREATE TABLE IF NOT EXISTS `c_footer` (
  `FOOTER_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `FOOTER_KANTOR` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`FOOTER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.c_footer: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_footer_detail
DROP TABLE IF EXISTS `c_footer_detail`;
CREATE TABLE IF NOT EXISTS `c_footer_detail` (
  `FOOTER_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FOOTER_TYPE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'KONTAK / MENU / SOSMED',
  `FOOTER_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  KEY `FOOTER_ID` (`FOOTER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.c_footer_detail: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_header
DROP TABLE IF EXISTS `c_header`;
CREATE TABLE IF NOT EXISTS `c_header` (
  `HEADER_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `HEADER_LOGO1` text COLLATE utf8mb4_general_ci,
  `HEADER_LOGO2` text COLLATE utf8mb4_general_ci,
  `HEADER_TELPLOGO` text COLLATE utf8mb4_general_ci,
  `HEADER_TELP` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`HEADER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.c_header: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.c_header_detail
DROP TABLE IF EXISTS `c_header_detail`;
CREATE TABLE IF NOT EXISTS `c_header_detail` (
  `HEADER_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `HEADER_SOSMEDLOGO` text COLLATE utf8mb4_general_ci,
  `HEADER_SOSMED` text COLLATE utf8mb4_general_ci,
  KEY `HEADER_ID` (`HEADER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.c_header_detail: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.d_idsertifikat
DROP TABLE IF EXISTS `d_idsertifikat`;
CREATE TABLE IF NOT EXISTS `d_idsertifikat` (
  `IDSERTIFIKAT_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `FILE` text COLLATE utf8mb4_general_ci,
  KEY `IDSERTIFIKAT_ID` (`IDSERTIFIKAT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.d_idsertifikat: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.d_pusatdata
DROP TABLE IF EXISTS `d_pusatdata`;
CREATE TABLE IF NOT EXISTS `d_pusatdata` (
  `PUSATDATA_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSATDATA_FILE` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  KEY `PUSATDATA_ID` (`PUSATDATA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.d_pusatdata: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.m_anggota
DROP TABLE IF EXISTS `m_anggota`;
CREATE TABLE IF NOT EXISTS `m_anggota` (
  `ANGGOTA_KEY` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ANGGOTA_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_cabang',
  `TINGKATAN_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KTP` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_NAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_ALAMAT` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_AGAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_PEKERJAAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KELAMIN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_TEMPAT_LAHIR` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_TANGGAL_LAHIR` date DEFAULT NULL,
  `ANGGOTA_HP` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_EMAIL` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_PIC` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_JOIN` date DEFAULT NULL,
  `ANGGOTA_RESIGN` date DEFAULT NULL,
  `ANGGOTA_AKSES` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0' COMMENT '0: Aktif / 1: Non Aktif / 2: Mutasi',
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ANGGOTA_KEY`),
  UNIQUE KEY `ANGGOTA_ID` (`ANGGOTA_ID`),
  KEY `ANGGOTA_KTP` (`ANGGOTA_KTP`),
  KEY `SABUK_ID` (`TINGKATAN_ID`) USING BTREE,
  KEY `RANTING_ID` (`CABANG_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_anggota: ~5 rows (approximately)
REPLACE INTO `m_anggota` (`ANGGOTA_KEY`, `ANGGOTA_ID`, `CABANG_KEY`, `TINGKATAN_ID`, `ANGGOTA_KTP`, `ANGGOTA_NAMA`, `ANGGOTA_ALAMAT`, `ANGGOTA_AGAMA`, `ANGGOTA_PEKERJAAN`, `ANGGOTA_KELAMIN`, `ANGGOTA_TEMPAT_LAHIR`, `ANGGOTA_TANGGAL_LAHIR`, `ANGGOTA_HP`, `ANGGOTA_EMAIL`, `ANGGOTA_PIC`, `ANGGOTA_JOIN`, `ANGGOTA_RESIGN`, `ANGGOTA_AKSES`, `ANGGOTA_STATUS`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '65741a93-9640-11ee-9fd3-e86a64d05f05', 'bef4c735-8459-11ee-a3a1-e86a64d05f05', '321123321123', 'Naura Marieline', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'adityahusni90@yahoo.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, 'User', '0', '0', '09.03.2023.0001', '2023-12-18 08:29:34'),
	('9d82e92a-928d-11ee-9020-e86a64d05f05', '09.03.2023.0001', '65741a93-9640-11ee-9fd3-e86a64d05f05', 'b19a6e57-98dd-11ee-bdd6-e86a64d05f05', '1234567891011', 'Husni Aditya', 'Sidoarjo', 'Islam', 'test', 'L', 'Surabaya', '1990-08-21', '087702462220', 'adityahusni90@gmail.com', './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg', '2023-09-21', NULL, 'Administrator', '0', '0', '09.03.2023.0001', '2023-12-13 07:27:44'),
	('9d83086f-928d-11ee-9020-e86a64d05f05', 'admincs01', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'b19a6e57-98dd-11ee-bdd6-e86a64d05f05', '0987654321', 'Admin CS', NULL, NULL, NULL, 'L', NULL, NULL, '087702462220', NULL, './assets/images/daftaranggota/Surabaya/test_123/avatar.png', '2023-09-21', NULL, 'Administrator', '0', '0', NULL, NULL),
	('a263a10a-998f-11ee-bfb0-e86a64d05f05', '003.003.2008.006', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '6202061711820008', 'Agus Riyanto', 'Jl. Bahtera RT.16 RW.04 Parenggean \r\nKab. Kotawaringin Timur', NULL, 'Swasta', 'L', 'Riau', '1982-11-17', '081370230355', 'agus.riyanto.ckp@gmail.com', './assets/images/daftaranggota/Kotawaringin Timur/Agus Riyanto_006/My.png', '2008-10-10', NULL, 'Koordinator', '0', '0', 'admincs01', '2023-12-15 15:44:30'),
	('e9c39154-9b25-11ee-8519-e86a64d05f05', '003.003.2010.003', '657413ba-9640-11ee-9fd3-e86a64d05f05', '159c472b-8459-11ee-a3a1-e86a64d05f05', '62xx', 'Slamet', 'Jl. HM. Arsyad KM.17 Desa Bapeang\r\nKotawaringin Timur', 'Islam', 'Swasta', 'L', 'Banjar', '1977-08-23', '082149365014', 'xx.cs@gmail.com', './assets/images/daftaranggota/default/avatar.png', '2010-02-24', NULL, 'User', '0', '0', 'admincs01', '2023-12-15 15:42:43');

-- Dumping structure for table ciptasejati.m_anggotaakses
DROP TABLE IF EXISTS `m_anggotaakses`;
CREATE TABLE IF NOT EXISTS `m_anggotaakses` (
  `MENU_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_menu',
  `ANGGOTA_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_user',
  `VIEW` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ADD` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EDIT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `APPROVE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRINT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  KEY `MENU_ID` (`MENU_ID`) USING BTREE,
  KEY `USER_ID` (`ANGGOTA_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_anggotaakses: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.m_anggota_log
DROP TABLE IF EXISTS `m_anggota_log`;
CREATE TABLE IF NOT EXISTS `m_anggota_log` (
  `ANGGOTALOG_KEY` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ANGGOTA_KEY` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ANGGOTA_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_cabang',
  `TINGKATAN_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KTP` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_NAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_ALAMAT` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_AGAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_PEKERJAAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KELAMIN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_TEMPAT_LAHIR` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_TANGGAL_LAHIR` date DEFAULT NULL,
  `ANGGOTA_HP` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_EMAIL` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_PIC` text COLLATE utf8mb4_general_ci,
  `ANGGOTA_JOIN` date DEFAULT NULL,
  `ANGGOTA_RESIGN` date DEFAULT NULL,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `LOG_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ANGGOTALOG_KEY`),
  KEY `ANGGOTA_KTP` (`ANGGOTA_KTP`) USING BTREE,
  KEY `SABUK_ID` (`TINGKATAN_ID`) USING BTREE,
  KEY `RANTING_ID` (`CABANG_KEY`) USING BTREE,
  KEY `ANGGOTA_KEY` (`ANGGOTA_KEY`),
  KEY `ANGGOTA_ID` (`ANGGOTA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_anggota_log: ~39 rows (approximately)
REPLACE INTO `m_anggota_log` (`ANGGOTALOG_KEY`, `ANGGOTA_KEY`, `ANGGOTA_ID`, `CABANG_KEY`, `TINGKATAN_ID`, `ANGGOTA_KTP`, `ANGGOTA_NAMA`, `ANGGOTA_ALAMAT`, `ANGGOTA_AGAMA`, `ANGGOTA_PEKERJAAN`, `ANGGOTA_KELAMIN`, `ANGGOTA_TEMPAT_LAHIR`, `ANGGOTA_TANGGAL_LAHIR`, `ANGGOTA_HP`, `ANGGOTA_EMAIL`, `ANGGOTA_PIC`, `ANGGOTA_JOIN`, `ANGGOTA_RESIGN`, `DELETION_STATUS`, `LOG_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('043bcb27-99c2-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', NULL, '0', 'I', '09.03.2023.0001', '2023-12-13 21:15:06'),
	('0b7950ea-996b-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 10:52:32'),
	('25532367-9d44-11ee-85bb-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '', '0e0fbf18-8394-11ee-9d33-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'U', '09.03.2023.0001', '2023-12-18 08:24:10'),
	('29f5ac8f-9b26-11ee-8519-e86a64d05f05', 'a263a10a-998f-11ee-bfb0-e86a64d05f05', '003.003.2008.006', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '6202061711820008', 'Agus Riyanto', 'Jl. Bahtera RT.16 RW.04 Parenggean \r\nKab. Kotawaringin Timur', NULL, 'Swasta', 'L', 'Riau', '1982-11-17', '081370230355', 'agus.riyanto.ckp@gmail.com', './assets/images/daftaranggota/Kotawaringin Timur/Agus Riyanto_006/My.png', '2008-10-10', NULL, '0', 'U', 'admincs01', '2023-12-15 15:44:31'),
	('2d9445e2-996b-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-12', '0', 'U', '09.03.2023.0001', '2023-12-13 10:53:30'),
	('3a5a2d1a-9d44-11ee-85bb-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '657413ba-9640-11ee-9fd3-e86a64d05f05', '0e0fbf18-8394-11ee-9d33-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'U', '09.03.2023.0001', '2023-12-18 08:24:45'),
	('67cf8961-9965-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 10:12:10'),
	('6ee6b83e-994e-11ee-bfb0-e86a64d05f05', '9d82e92a-928d-11ee-9020-e86a64d05f05', '09.03.2023.0001', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'b19a6e57-98dd-11ee-bdd6-e86a64d05f05', '1234567891011', 'Husni Aditya', 'Sidoarjo', NULL, 'test', 'L', 'Surabaya', '1990-08-21', '087702462220', 'asdas@mail.com', './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg', '2023-09-21', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 07:27:44'),
	('70b9975c-99c3-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 21:25:18'),
	('7359323d-98dc-11ee-bdd6-e86a64d05f05', '65783b4505f0f', '009.001.2016.666', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '45aa0b01-8459-11ee-a3a1-e86a64d05f05', '11111111111111', 'tos', 'tos', NULL, 'tos', 'L', 'tos', '1986-04-09', '121212121212', 'tos@mail.com', './assets/images/daftaranggota/default/avatar.png', '2016-09-13', NULL, '0', 'I', '09.03.2023.0001', '2023-12-12 17:51:49'),
	('768dfeae-994e-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 07:27:57'),
	('77d2d629-9970-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:31:22'),
	('7c441626-9970-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-13 11:31:29'),
	('8113fd7e-9970-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-13 11:31:37'),
	('8605aaa7-9973-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:53:14'),
	('88910492-98dc-11ee-bdd6-e86a64d05f05', '65783b4505f0f', '009.001.2016.666', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '45aa0b01-8459-11ee-a3a1-e86a64d05f05', '11111111111111', 'tos', 'tos', NULL, 'tos', 'L', 'tos', '1986-04-09', '121212121212', 'tos@mail.com', './assets/images/daftaranggota/default/avatar.png', '2016-09-13', NULL, '0', 'D', '09.03.2023.0001', '2023-12-12 17:52:24'),
	('8dc6bd31-9973-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-14', '0', 'U', '09.03.2023.0001', '2023-12-13 11:53:27'),
	('9407ea3c-9970-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:32:09'),
	('973a6305-9973-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:53:43'),
	('984acead-9a51-11ee-90cd-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '65741a93-9640-11ee-9fd3-e86a64d05f05', 'bef4c735-8459-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'I', '09.03.2023.0001', '2023-12-14 14:22:53'),
	('9a38a232-99c3-11ee-b3f4-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', 'Islam', 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-13 21:26:28'),
	('9ed197a2-9965-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 10:13:43'),
	('9eec98b7-9a1d-11ee-90cd-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', 'Islam', 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-14 08:10:50'),
	('a12b3b3e-996f-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:25:21'),
	('a2699ece-998f-11ee-bfb0-e86a64d05f05', 'a263a10a-998f-11ee-bfb0-e86a64d05f05', '003.003.2008.006', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '6202061711820008', 'Agus Riyanto', 'Jl. Bahtera RT.16 RW.04 Parenggean \r\nKab. Kotawaringin Timur', NULL, 'Swasta', 'L', 'Riau', '1982-11-17', '081370230355', 'agus.riyanto.ckp@gmail.com', './assets/images/daftaranggota/Kotawaringin Timur/Agus Riyanto_006/My.png', '2008-10-10', NULL, '0', 'I', 'admincs01', '2023-12-13 15:14:27'),
	('a46b7b13-9d44-11ee-85bb-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '657413ba-9640-11ee-9fd3-e86a64d05f05', '159c472b-8459-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'U', '09.03.2023.0001', '2023-12-18 08:27:43'),
	('b86647d9-98db-11ee-bdd6-e86a64d05f05', '9d82e92a-928d-11ee-9020-e86a64d05f05', '09.03.2023.0001', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '1234567891011', 'Husni Aditya', 'Sidoarjo', NULL, 'test', 'L', 'Surabaya', '1990-08-21', '087702462220', 'asdas@mail.com', './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg', '2023-09-21', NULL, '0', 'U', '09.03.2023.0001', '2023-12-12 17:46:35'),
	('c89ae443-98dc-11ee-bdd6-e86a64d05f05', 'c89a0b32-98dc-11ee-bdd6-e86a64d05f05', '009.001.2018.666', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '45aa0b01-8459-11ee-a3a1-e86a64d05f05', '11111111111111', 'tos', 'tos', NULL, 'tos', 'L', 'tos', '1983-01-04', '123321123321', 'tos@mail.com', './assets/images/daftaranggota/default/avatar.png', '2018-08-10', NULL, '0', 'I', '09.03.2023.0001', '2023-12-12 17:54:12'),
	('cc4f28dd-98db-11ee-bdd6-e86a64d05f05', '9d82e92a-928d-11ee-9020-e86a64d05f05', '09.03.2023.0001', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '1234567891011', 'Husni Aditya', 'Surabaya', NULL, 'test', 'L', 'Surabaya', '1990-08-21', '087702462220', 'asdas@mail.com', './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg', '2023-09-21', NULL, '0', 'U', '09.03.2023.0001', '2023-12-12 17:47:08'),
	('d083b8c2-99c2-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 21:20:49'),
	('d640f9ec-98db-11ee-bdd6-e86a64d05f05', '9d82e92a-928d-11ee-9020-e86a64d05f05', '09.03.2023.0001', '657413ba-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '1234567891011', 'Husni Aditya', 'Sidoarjo', NULL, 'test', 'L', 'Surabaya', '1990-08-21', '087702462220', 'asdas@mail.com', './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg', '2023-09-21', NULL, '0', 'U', '09.03.2023.0001', '2023-12-12 17:47:25'),
	('d9e316c1-99c2-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-13 21:21:05'),
	('e26a87fd-99c2-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', '2023-12-13', '0', 'U', '09.03.2023.0001', '2023-12-13 21:21:19'),
	('e63b3e14-9d44-11ee-85bb-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '65741a93-9640-11ee-9fd3-e86a64d05f05', 'bef4c735-8459-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'U', '09.03.2023.0001', '2023-12-18 08:29:34'),
	('e942778e-99c2-11ee-b3f4-e86a64d05f05', '043a1734-99c2-11ee-b3f4-e86a64d05f05', '009.001.2022.111', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', '321321321321', 'test', 'test', 'Islam', 'test', 'L', 'Surabaya', '1991-06-04', '123321123321', 'test@mail.com', './assets/images/daftaranggota/default/avatar.png', '2022-12-13', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 21:21:31'),
	('e9d0670a-9b25-11ee-8519-e86a64d05f05', 'e9c39154-9b25-11ee-8519-e86a64d05f05', '003.003.2010.003', '657413ba-9640-11ee-9fd3-e86a64d05f05', '159c472b-8459-11ee-a3a1-e86a64d05f05', '62xx', 'Slamet', 'Jl. HM. Arsyad KM.17 Desa Bapeang\r\nKotawaringin Timur', 'Islam', 'Swasta', 'L', 'Banjar', '1977-08-23', '082149365014', 'xx.cs@gmail.com', './assets/images/daftaranggota/default/avatar.png', '2010-02-24', NULL, '0', 'I', 'admincs01', '2023-12-15 15:42:43'),
	('eb06cbf7-98dc-11ee-bdd6-e86a64d05f05', 'c89a0b32-98dc-11ee-bdd6-e86a64d05f05', '009.001.2018.666', '6574bfee-9640-11ee-9fd3-e86a64d05f05', '45aa0b01-8459-11ee-a3a1-e86a64d05f05', '11111111111111', 'tos', 'tos', NULL, 'tos', 'L', 'tos', '1983-01-04', '123321123321', 'tos@mail.com', './assets/images/daftaranggota/default/avatar.png', '2018-08-10', NULL, '0', 'D', '09.03.2023.0001', '2023-12-12 17:55:09'),
	('f0bb00bb-9d43-11ee-85bb-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '003.007.2017.123', '', '0e0fbf18-8394-11ee-9d33-e86a64d05f05', '321123321123', 'anu', 'Di sana', 'Islam', 'anu', 'P', 'Surabaya', '1994-04-12', '121212121212', 'anu@mail.com', './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png', '2017-07-12', NULL, '0', 'U', '09.03.2023.0001', '2023-12-18 08:22:42'),
	('f308a31c-996e-11ee-bfb0-e86a64d05f05', 'f4ed1c0d-9816-11ee-b39f-e86a64d05f05', '003.001.2021.222', '65741153-9640-11ee-9fd3-e86a64d05f05', '2ee61c8c-845a-11ee-a3a1-e86a64d05f05', '321123321123', 'anu', 'anu', NULL, 'anu', 'P', 'anu', '1994-07-05', '123321123321', 'anu@mail.com', './assets/images/daftaranggota/Palangkaraya/anu_222/Screenshot 2023-11-01 114133.png', '2021-05-04', NULL, '0', 'U', '09.03.2023.0001', '2023-12-13 11:20:29');

-- Dumping structure for table ciptasejati.m_cabang
DROP TABLE IF EXISTS `m_cabang`;
CREATE TABLE IF NOT EXISTS `m_cabang` (
  `CABANG_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DAERAH_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_daerah',
  `CABANG_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_DESKRIPSI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_SEKRETARIAT` text COLLATE utf8mb4_general_ci,
  `CABANG_PENGURUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_MAP` text COLLATE utf8mb4_general_ci,
  `CABANG_LAT` text COLLATE utf8mb4_general_ci,
  `CABANG_LONG` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`CABANG_KEY`),
  UNIQUE KEY `CABANG_ID` (`CABANG_ID`),
  KEY `DAERAH_ID` (`DAERAH_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_cabang: ~62 rows (approximately)
REPLACE INTO `m_cabang` (`CABANG_KEY`, `DAERAH_KEY`, `CABANG_ID`, `CABANG_DESKRIPSI`, `CABANG_SEKRETARIAT`, `CABANG_PENGURUS`, `CABANG_MAP`, `CABANG_LAT`, `CABANG_LONG`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('65686ae2-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.001', 'Banjarmasin', 'Jl. Pembanguan Ujung RT.34 No.10 Banjarmasin\r\nKalimantan Selatan', 'PM. Mansyah / HP. 08125055122', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d67520.0144019436!2d114.5244806357267!3d-3.31302038741112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sid!2sid!4v1701740126321!5m2!1sid!2sid', '-3.313020', '114.5244806', '0', 'admincs01', '2023-12-05 09:52:25'),
	('656d439c-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.002', 'Barito Kuala', 'Jl. Kunyit Ds. Karang Dukuh  Kec. Belawang Kab. Barito Kuala\r\nKalimantan Selatan', 'PMy. Suraji / HP. 08125161071', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:53:16'),
	('65731924-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.003', 'Banjar Baru', 'Jl. Meranti No.17 RT.11 RW.03 Banjar Baru\r\nKalimantan Selatan', 'Aspel. Taufik / HP. 0511777338', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:53:26'),
	('65731e76-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.004', 'Tanah Laut', 'Ds. Durian Bungkuk Kec. Batu Ampar Kab. Tanah Laut\r\nKalimantan Selatan', 'PU. Misransyah, Spt. / HP. 0512-22504', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:53:39'),
	('6573214d-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.005', 'Tapin', 'Nes 12 RT.03 No.369 Ds. Pualan Sari Kec. Binuang\r\nKalimantan Selatan', 'PMy. Kafi / HP. 081348331381', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:53:49'),
	('65732398-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.006', 'Hulu Sungai Tengah', 'Jl. Sari Gading Ds. Hilir Banua RT.02 Kec. Pandawan\r\nKalimantan Selatan', 'PM. Hambali', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:53:59'),
	('6573261d-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.007', 'Balangan', 'Dusun Sumber Rejeki RT.08 RW.03 Kec. Juai Kab. Balangan\r\nKalimantan Selatan', 'PM. Tohari / HP. 081348491820', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:55:09'),
	('65732868-9640-11ee-9fd3-e86a64d05f05', 'b977a52b-9641-11ee-9fd3-e86a64d05f05', '001.001.008', 'Tanah Bumbu', 'Jl. Provinsi KM.190 Desa Angsana Kec. Angsana Kab. Tanah Bumbu\r\nKalimantan Selatan', 'PMy. Sofiyan Hadi / HP. 08511561568', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:58:58'),
	('65732ad6-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.001', 'Surabaya', 'Jl. Nambangan Gg. Kudu II Kedinding Lor. Surabaya\r\nJawa Timur', 'PU. Sugeng / HP. 081330526683', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:55:21'),
	('65732f65-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.002', 'Mojokerto', 'Dusun Terusan RT.15 RW.02 Ds. Kemantren Kec. Gedek Kab. Mojokerto\r\nJawa Timur', 'PM. Yudi / HP. 085731959577', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:55:42'),
	('65733422-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.003', 'Nganjuk', 'Jl. Gajahmada II Desa Patihan RT.05 RW.03 Kec. Loceret Kab. Ngajuk\r\nJawa Timur', 'PU. Pardi / HP.  081259542351', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d506107.73566158756!2d111.58536561392714!3d-7.686724213259744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784ddd2ff5c9e9%3A0xdd784325d5fb9960!2sCipta%20Sejati%20Cabang%20Nganjuk!5e0!3m2!1sid!2sid!4v1701743393377!5m2!1sid!2sid', '-7.686724', '111.585365', '0', 'admincs01', '2023-12-05 09:55:51'),
	('657336a4-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.004', 'Jombang', 'Perak Jombang - Jawa Timur', 'PM. Hendro / HP . ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', '09.03.2023.0001', '2023-12-09 11:25:03'),
	('6573390a-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.005', 'Jember', 'Jl. Mawar 2B Jember Lor Kec. Patrang Kab. Jember\r\nJawa Timur', 'PU. Marno / HP.  085749417306', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d57943.42534958516!2d113.6728615309812!3d-8.179032781610882!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695e6c3cfc221%3A0xfa4cadc0b790ff8c!2sSekretariat%20Cipta%20Sejati%20Cabang%20Jember!5e0!3m2!1sid!2sid!4v1701682478615!5m2!1sid!2sid', '-8.179032', '113.6728615', '0', 'admincs01', '2023-12-05 09:56:01'),
	('65733b3b-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.006', 'Tuban', 'Jl. Wahidin Sudirohusodo 66 Kab. Tuban\r\nJawa Timur', 'PU. ', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:12'),
	('65733d63-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.007', 'Lamongan', 'Jl. Mendalan 28 Kab. Lamongan\r\nJawa Timur', 'PMy. Kusnul / HP. 081330480922', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:21'),
	('65733fb3-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.008', 'Gresik', 'Balong Panggang Kampung Pucung RT.01 RW.01 Kab. Gresik\r\nJawa Timur', 'PM. Wagiran / HP. .......', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:30'),
	('6573f9db-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.009', 'Sumenep', 'Jl. Gresik Putih Timur 42B Kalianget Sumenep Madura\r\nJawa Timur', 'PU. Gofur / HP.  085232435621', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:40'),
	('6573fd18-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.010', 'Situbondo', 'Curah Jeru Tengah RT.01 RW.07 Panji Situbondo\r\nJawa Timur', 'PM. Nur / HP. 0338676383', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:49'),
	('6573ff02-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.011', 'Bondowoso', 'Perum Taman Mutiara Block B-2 No. 16 Bondowoso\r\nJawa Timur', 'PM. Achmad / HP. .....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:56:59'),
	('6574008b-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.012', 'Sidoarjo', 'Ds. Banjar Kemantren Kec. Bunduran Kab. Sidoarjo\r\nJawa Timur', 'PU. Suhardi / HP. 08113464674', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:57:07'),
	('65740211-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.013', 'Blitar', 'Dsn. Ungaran Ds. Tawang Rejo Kec. Binangun Kab. Blitar\r\nJawa Timur', 'PM. M. Anam / HP. 08575137809', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5751.001349654853!2d112.2090140169463!3d-8.019084901983414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78f35fedced869%3A0x184c9aa0cb98f763!2sSEKRETARIAT%20CIPTA%20SEJATI%20BLITAR!5e0!3m2!1sid!2sid!4v1701681728813!5m2!1sid!2sid', '-8.019084', '112.209014', '0', 'admincs01', '2023-12-05 09:57:17'),
	('657403b1-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.014', 'Kabupaten Kediri', 'Ds. Gampeng Rejo Kec. Ponggok Kab. Keciri\r\nJawa Timur', '', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:57:25'),
	('65740584-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.015', 'Kota Kediri', 'Jl. KH. Abdul Karim No.4 RT.01 Kel. Lir Boyo Kab. Kediri\r\nJawa Timur', 'PM. Darsono / HP. 085259533052', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:57:35'),
	('65740771-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.016', 'Tulungagung', 'Ds. Notorejo Kec. Gondang Kab. Tulungagung\r\nJawa Timur', 'PM. Ibnu Arifin / HP. 082330880565', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d505619.0028438109!2d111.5169128333577!3d-8.086338293881704!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e791dfb27ae13af%3A0x13f837b25cf243bb!2sSekretariat%20Cipta%20Sejati%20Cabang%20Tulungagung!5e0!3m2!1sid!2sid!4v1701681841535!5m2!1sid!2sid', '-8.086338', '111.5169128', '0', 'admincs01', '2023-12-05 09:57:43'),
	('65740c65-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.017', 'Trenggalek', 'Ds. Gembleb Kec. Pogalan Kab. Trenggalek\r\nJawa Timur', 'PMy. Suwandy / HP. ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:57:53'),
	('65740f24-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.018', 'Banyuwangi', 'Kampung Baru RT.03 RW.01 Panjen Jambewangi\r\nBanyuwangi - Jawa Timur', 'PMy. ...', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:58:03'),
	('65741040-9640-11ee-9fd3-e86a64d05f05', 'b977aa31-9641-11ee-9fd3-e86a64d05f05', '001.002.019', 'Pacitan', 'Desa Montongan Slaung, Kab. Pacitan\r\nJawa Timur', 'PM. Junaidi / HP. ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:54:25'),
	('65741153-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.001', 'Palangkaraya', 'Jl. Iskandar No.09 Asrama Tonsik Rem Palangkaraya\r\nKalimantan Tengah', 'PU. Suyono / HP. 081352845553', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.8420292911105!2d113.92954927352878!3d-2.213226037338766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dfcb28a8357a70d%3A0x704e423a3148e34!2sSekretariat%20Institut%20Seni%20Bela%20Diri%20Silat%20Cipta%20Sejati!5e0!3m2!1sid!2sid!4v1701680118849!5m2!1sid!', '-2.213226', '113.929549', '0', 'admincs01', '2023-12-05 09:58:29'),
	('6574125d-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.002', 'Kotawaringin Barat', 'Jl. Pangkalan Lima Ds. Natai Raya RT.01 RW.01 Kab. Pangkalan Bun\r\nKalimantan Tengah', 'PU. Sutariono / HP. 082159081220', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10951.720406588769!2d111.70425552277405!3d-2.6731367441297182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e08f196586bebd7%3A0xea6dd75ca4212c15!2sISBDS%20CIPTA%20SEJATI!5e0!3m2!1sid!2sid!4v1701680987526!5m2!1sid!2sid', '-2.673136', '111.7042555', '0', 'admincs01', '2023-12-05 09:58:42'),
	('657413ba-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.003', 'Kotawaringin Timur', 'Jl. HM. Arsad Gg. Meranti-I RT.03 RW.01 Ds. Bapeang. Kec. Mentawa Baru Ketapang Kab. Kotawaringin Timur \r\nKalimantan Tengah', 'PM. Agus Riyanto / HP. 081370230355', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:58:52'),
	('65741602-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.004', 'Kapuas', 'Jl. Kalimantan Gg. III No.02 Kel. Selat Hilir Kab. Kapuas\r\nKalimantan Tengah', 'PU. M. Samiun Ragil / HP. 081349561875', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:59:01'),
	('65741764-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.005', 'Muara Tewah', 'Jl. Brg Katamso RT.30 Km.3  Kab. Muara Tewah\r\nKalimantan Tengah', 'PM. Joko Prasetyo A. / HP. 08125036838', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:59:10'),
	('65741925-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.006', 'Pulang Pisau', 'Jl. Panunjung Tarung Kel. Pulpis Kec. K. Hilir Kab. Pulang Pisau\r\nKalimantan Tengah', 'PMy. Suntoro / HP. 081351426399', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:59:20'),
	('65741a93-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.007', 'Seruyan', 'Jl Kali Seruyan Kampung Gelora Pembuang Hulu I Kab. Seruyan\r\nKalimantan Tengah', 'PM. Sunardi. M / HP.085651170016', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:59:30'),
	('65741bc2-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.008', 'Sukamara', 'Jl. Akau RT.02 Kel. Mendawai Kab. Sukamara\r\nKalimantan Tengah', 'Aspel Anang / HP. 081250867501', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:59:38'),
	('65741d97-9640-11ee-9fd3-e86a64d05f05', 'b977ab83-9641-11ee-9fd3-e86a64d05f05', '001.003.009', 'Katingan', 'Gedung LPTQ  Kab. Katingan Jl. Sukarno Hatta. Kab. Katingan\r\nKalimantan Tengah', 'PM. H. Kariansyah / HP. 081254881978', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:53:08'),
	('65741eba-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.001', 'Kudus', 'RM. Sosrokartono No.129 Kab. Kudus\r\nJawa Tengah', 'GM. Tejo Handoyo / HP.  ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:00:05'),
	('65741fbc-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.002', 'Wonosobo', 'Ds. Waringin Anom Kec. Kertek Kab. Wonosoboso\r\nJawa Tengah', 'PU. Misrun / HP. 081391888811', 'https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d247.30093671736373!2d109.89596402565756!3d-7.374622509230678!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1scipta%20sejati%20wonosobo!5e0!3m2!1sid!2sid!4v1701681338865!5m2!1sid!2sid', '-7.374622', '109.8959640', '0', 'admincs01', '2023-12-05 10:00:16'),
	('65742181-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.003', 'Wonogiri', 'Dsn. Wates Wetan desa Mojo Rejo Kec. Sidoharjo Kab. Wonogiri\r\nJawa Tengah', 'PU. Timan / HP. .......', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:00:26'),
	('6574228b-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.004', 'Temanggung', 'Ds. Ploso Gaden Candiroto Kab. Temanggung\r\nJawa Tengah', 'PM. Iskak / HP. 085643380598', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:00:36'),
	('657423a7-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.005', 'Rembang', 'Jl. Banyurip No.30 Gegersimo Kec. Pamotan Kab. Rembang\r\nJawa Tengah', 'PM. M. Nurcholis / HP. 08225461600', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:00:46'),
	('6574a937-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.006', 'Magelang', 'Jl. Kalijoso RT.01 RW.04 Secang Kab. Magelang\r\nJawa Tengah', 'PMy. Hermanto', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4604.648808824693!2d112.9345273716095!3d-2.668494583514127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2932387c9e82f%3A0xd776431bbaf915fa!2sSekretariat%20ISBDS%20Cipta%20Sejati%20Cab.%20Kotim!5e0!3m2!1sid!2sid!4v1700785873372!5m2!1sid!2sid', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:00:58'),
	('6574ab93-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.007', 'Solo', '-', '-', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid', '-2.583598', '112.50304162', '0', 'admincs01', '2023-12-05 09:52:57'),
	('6574acbd-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.008', 'Demak', 'Desa Wonokerto RT.01 RW.04 Kec. Karang Tengah Kab. Demak\r\nJawa Tengah', 'PM. Sakti Edy P / HP.  089606433896', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.8420292911105!2d113.92954927352878!3d-2.213226037338766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dfcb28a8357a70d%3A0x704e423a3148e34!2sSekretariat%20Institut%20Seni%20Bela%20Diri%20Silat%20Cipta%20Sejati!5e0!3m2!1sid!2sid!4v1701680118849!5m2!1sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 09:58:15'),
	('6574ae9d-9640-11ee-9fd3-e86a64d05f05', 'b977de99-9641-11ee-9fd3-e86a64d05f05', '001.004.009', 'Jogjakarta', 'Kretek Glagah Temon Kulon Progo Jogjakarta\r\nDIY Yogyakarta', 'PU. Panji. S / HP. 085292211649', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.213226', ' 112.9343172', '0', 'admincs01', '2023-12-05 10:01:08'),
	('6574af97-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.001', 'Medan', 'Komp. Rumdis. Dosen IAIN Jl. W. Iskandar Medan\r\nSumatra Utara', 'PMY. Hanafiah Sufi / HP ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:13:23'),
	('6574b0eb-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.002', 'Binjai', 'Jl. Kompor 27 Binjai 20742\r\nSumatra Utara', 'PU. Muktar / HP. 085337379341', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:15:12'),
	('6574b29e-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.003', 'Palembang/Muba', 'Ds. Berlian Makmur Sei. Lilin Kab. Muba \r\nSematra Selatan', 'PM. Mujiono / HP. ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:18:12'),
	('6574b38a-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.004', 'Jambi', '-', '-', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:19:07'),
	('6574b56c-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.005', 'Lampung Timur', 'Kec. Bandar Sri Bawono Kab. Lampung Timur\r\nSumatra', 'PU. Siswanto / HP. 085380947760', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:20:41'),
	('6574b661-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.006', 'Lampung Selatan', 'RW.01 Desa pemanggilan Kec. Ntar Kab. Lampung Selatan\r\nSumatra', 'PM. Sugianto / HP. ....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:22:20'),
	('6574b7fb-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.007', 'Kodya Bandar Lampung', 'Jl. Sukarno Hatta No.18 Kirhan Rajabasa Kab. Bandar Lampung\r\nSumatra', 'PMy. Sulaiman / HP. ...', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:24:03'),
	('6574b92f-9640-11ee-9fd3-e86a64d05f05', 'b977e121-9641-11ee-9fd3-e86a64d05f05', '001.005.008', 'Tulang Bawang', '-', '-', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:24:38'),
	('6574baca-9640-11ee-9fd3-e86a64d05f05', 'b977e237-9641-11ee-9fd3-e86a64d05f05', '001.006.001', 'Jakarta Timur', 'Jl. raya Tengah Gg. Dana Kel. Gedong Ps. Rebo\r\nJakarta Timur', 'PM. Supardi / HP. 085213514434', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:26:30'),
	('6574bbaa-9640-11ee-9fd3-e86a64d05f05', 'b977e237-9641-11ee-9fd3-e86a64d05f05', '001.006.002', 'Jakarta Barat', 'Jl. Angke Barat RT.02 RW.01 No.19 Kel. Angke Tambora\r\nJakarta Barat', 'Pmy. Luluk Lutvianto / HP. 081280672252', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:30:05'),
	('6574bd46-9640-11ee-9fd3-e86a64d05f05', 'b977e31e-9641-11ee-9fd3-e86a64d05f05', '001.007.001', 'Pontianak', 'Jl. Budi Karya Plamboyan di Penyu 08981398435\r\nKalimantan Barat', 'Aspel Tarmin / HP. 085386734767', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:33:14'),
	('6574be2f-9640-11ee-9fd3-e86a64d05f05', 'b977e4ab-9641-11ee-9fd3-e86a64d05f05', '001.008.001', 'Irian Jaya', 'Kampung Swakarya RT.02 Kel. Koya Barat Distrik Muara Tami Kab. Jayapura\r\nIrian Jaya', 'PU. Sunyoto / HP. 085264130441', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:35:26'),
	('6574bfee-9640-11ee-9fd3-e86a64d05f05', 'b977e5df-9641-11ee-9fd3-e86a64d05f05', '001.009.001', 'Pangandaran', 'Bojong Jati RT.04 RW.06 Pananjung Pangandaran\r\nJawa Barat', 'PM. Yudiyanto / HP.  ..', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:37:18'),
	('6574c104-9640-11ee-9fd3-e86a64d05f05', 'b977e5df-9641-11ee-9fd3-e86a64d05f05', '001.009.002', 'Cirebon', 'Jl. Raya Sunan Gunung Jati Desa Kerta Sura Block 3 Gg. Nyilimun RT.02 RW.17 No.15 Kec. Kapetakan Kab. Cirebon\r\nJawa Barat', 'PM. A. Syarif / HP.  089691637112', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:40:07'),
	('6574c2b0-9640-11ee-9fd3-e86a64d05f05', 'b977e5df-9641-11ee-9fd3-e86a64d05f05', '001.009.010', 'Purwakarta', 'Kampung Pangupukan RT.02 Menjul Jaya Purwakarta\r\nJawa Barat', 'PMy. Eko Yuni P. / HP. .....', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:09:39'),
	('6574c450-9640-11ee-9fd3-e86a64d05f05', 'b977e728-9641-11ee-9fd3-e86a64d05f05', '001.010.001', 'Bali', 'Dusun Batu Dawa Kelog Ds. Tulamben Kec. Kubu Kab. Karang Asem.\r\nBali', 'Aspel Supri / HP. 082183121313', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:50:22'),
	('6574c5ec-9640-11ee-9fd3-e86a64d05f05', 'b977e8fd-9641-11ee-9fd3-e86a64d05f05', '001.011.001', 'Baturaja', 'Desa Tanjung Makmur Batu Marta Unit 16 Block K. Kec. Sinar Peninjau Kab. Oku/Batu Raja\r\nSumatra Selatan', 'PM. Nor Qomaruding / HP. 08233137088', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7971.525454358503!2d112.50304162502287!3d-2.583598692115665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e082362a5d47a15%3A0x88abf78244a59e32!2sISBDS%20Cipta%20Sejati%20-%20Ranting%20Mentaya%20Hilir%20Utara!5e0!3m2!1sid!2sid!4v1701334264501!5m2!1sid!2sid!', '-2.668103', '112.9343172', '0', 'admincs01', '2023-12-05 10:56:59');

-- Dumping structure for table ciptasejati.m_daerah
DROP TABLE IF EXISTS `m_daerah`;
CREATE TABLE IF NOT EXISTS `m_daerah` (
  `DAERAH_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PUSAT_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_pusat',
  `DAERAH_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DAERAH_DESKRIPSI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DAERAH_SEKRETARIAT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DAERAH_PENGURUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DAERAH_MAP` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`DAERAH_KEY`),
  UNIQUE KEY `DAERAH_ID` (`DAERAH_ID`),
  KEY `PUSAT_ID` (`PUSAT_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_daerah: ~11 rows (approximately)
REPLACE INTO `m_daerah` (`DAERAH_KEY`, `PUSAT_KEY`, `DAERAH_ID`, `DAERAH_DESKRIPSI`, `DAERAH_SEKRETARIAT`, `DAERAH_PENGURUS`, `DAERAH_MAP`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('b977a52b-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.001', 'Kalimantan Selatan', NULL, NULL, NULL, '0', '09.03.2023.0001', '2023-12-09 10:44:21'),
	('b977aa31-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.002', 'Jawa Timur', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:49:36'),
	('b977ab83-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.003', 'Kalimantan Tengah', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:49:45'),
	('b977de99-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.004', 'Jawa Tengah', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:49:52'),
	('b977e121-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.005', 'Sumatra', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:00'),
	('b977e237-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.006', 'Jakarta', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:09'),
	('b977e31e-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.007', 'Kalimantan Barat', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:17'),
	('b977e4ab-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.008', 'Papua', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:29'),
	('b977e5df-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.009', 'Jawa Barat', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:37'),
	('b977e728-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.010', 'Bali', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:50:50'),
	('b977e8fd-9641-11ee-9fd3-e86a64d05f05', 'e3818c55-9641-11ee-9fd3-e86a64d05f05', '001.011', 'Sumatra Selatan', NULL, NULL, NULL, '0', 'admincs01', '2023-12-05 09:51:06');

-- Dumping structure for table ciptasejati.m_data
DROP TABLE IF EXISTS `m_data`;
CREATE TABLE IF NOT EXISTS `m_data` (
  `DATA_ID` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `DATA_KATEGORI` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DATA_JUDUL` text COLLATE utf8mb4_general_ci,
  `DATA_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `DATA_FILE` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`DATA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_data: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.m_idsertifikat
DROP TABLE IF EXISTS `m_idsertifikat`;
CREATE TABLE IF NOT EXISTS `m_idsertifikat` (
  `IDSERTIFIKAT_ID` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `TINGKATAN_ID` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `IDSERTIFIKAT_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `IDSERTIFIKAT_IDFILE` text COLLATE utf8mb4_general_ci,
  `IDSERTIFIKAT_IDNAMA` text COLLATE utf8mb4_general_ci,
  `IDSERTIFIKAT_SERTIFIKATFILE` text COLLATE utf8mb4_general_ci,
  `IDSERTIFIKAT_SERTIFIKATNAMA` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`IDSERTIFIKAT_ID`),
  KEY `TINGKATAN_ID` (`TINGKATAN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_idsertifikat: ~7 rows (approximately)
REPLACE INTO `m_idsertifikat` (`IDSERTIFIKAT_ID`, `TINGKATAN_ID`, `IDSERTIFIKAT_DESKRIPSI`, `IDSERTIFIKAT_IDFILE`, `IDSERTIFIKAT_IDNAMA`, `IDSERTIFIKAT_SERTIFIKATFILE`, `IDSERTIFIKAT_SERTIFIKATNAMA`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('0d32f0b2-8f5a-11ee-b940-e86a64d05f05', '7139de6a-8459-11ee-a3a1-e86a64d05f05', 'Pelatih Utama', './images/idsertifikat/Pamflet CS Mentaya.jpg', 'Pamflet CS Mentaya.jpg', './images/idsertifikat/Sertifikat CS-Hitam 3.png', 'Sertifikat CS-Hitam 3.png', '0', 'admincs01', '2023-11-30 15:25:42'),
	('521567bb-8f31-11ee-b940-e86a64d05f05', '2a834e41-8459-11ee-a3a1-e86a64d05f05', 'Pelatih Muda', './images/idsertifikat/Pamflet CS Mentaya.jpg', '', './images/idsertifikat/Sertifikat CS-Hitam 1.png', 'Template Sertifikat CS - Biru.png', '0', 'admincs01', '2023-11-30 15:23:41'),
	('5fef7221-8f31-11ee-b940-e86a64d05f05', '159c472b-8459-11ee-a3a1-e86a64d05f05', 'Asisten Pelatih', './images/idsertifikat/Pamflet CS Mentaya.jpg', '', './images/idsertifikat/Sertifikat CS-Coklat.png', 'Template Sertifikat CS - Biru.png', '0', 'admincs01', '2023-11-30 15:09:54'),
	('665d7b1f-8f31-11ee-b940-e86a64d05f05', '221919eb-8427-11ee-a3a1-e86a64d05f05', 'Dasar II', './images/idsertifikat/Pamflet CS Mentaya.jpg', 'Template Sertifikat CS - Biru.png', './images/idsertifikat/Sertifikat CS-Biru.png', 'Template Sertifikat CS - Biru.png', '0', 'admincs01', '2023-11-30 15:10:06'),
	('6db59c9e-8b7a-11ee-885f-025041000001', '0e0fbf18-8394-11ee-9d33-e86a64d05f05', 'Dasar I', './images/idsertifikat/idcard.png', 'idcard.png', './images/idsertifikat/Sertifikat CS-Hijau.png', 'Hijau.png', '0', 'admincs01', '2023-11-30 15:10:19'),
	('adade7c3-8a9a-11ee-b444-e86a64d05f05', '7fdee137-8392-11ee-9d33-e86a64d05f05', 'Pemula', NULL, NULL, './images/idsertifikat/Sertifikat CS-Kuning.png', NULL, '0', 'admincs01', '2023-11-30 15:10:38'),
	('e7d545ed-8f59-11ee-b940-e86a64d05f05', '45aa0b01-8459-11ee-a3a1-e86a64d05f05', 'Pelatih Madya', './images/idsertifikat/Pamflet CS Mentaya.jpg', 'Pamflet CS Mentaya.jpg', './images/idsertifikat/Sertifikat CS-Hitam 2.png', 'Sertifikat CS-Hitam 2.png', '0', 'admincs01', '2023-11-30 15:24:39');

-- Dumping structure for table ciptasejati.m_institut
DROP TABLE IF EXISTS `m_institut`;
CREATE TABLE IF NOT EXISTS `m_institut` (
  `INST_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `INST_LOGO` text COLLATE utf8mb4_general_ci,
  `INST_NAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INST_TELP` text COLLATE utf8mb4_general_ci,
  `INST_SEJARAH` text COLLATE utf8mb4_general_ci,
  `INST_VISI` text COLLATE utf8mb4_general_ci,
  `INST_MISI` text COLLATE utf8mb4_general_ci,
  `INST_LAMBANG` text COLLATE utf8mb4_general_ci,
  `INST_WARNA` text COLLATE utf8mb4_general_ci,
  `INST_MAKNA` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`INST_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_institut: ~0 rows (approximately)
REPLACE INTO `m_institut` (`INST_ID`, `INST_LOGO`, `INST_NAMA`, `INST_TELP`, `INST_SEJARAH`, `INST_VISI`, `INST_MISI`, `INST_LAMBANG`, `INST_WARNA`, `INST_MAKNA`) VALUES
	('INST-102023-0001', 'img/demos/renewable-energy/generic/generic-1.png', 'Cipta Sejati Indonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table ciptasejati.m_menu
DROP TABLE IF EXISTS `m_menu`;
CREATE TABLE IF NOT EXISTS `m_menu` (
  `MENU_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `MENU_ICON` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_INDUK` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_NAMA` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_DESKRIPSI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_LEVEL` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_URL` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MENU_TARGET` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`MENU_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_menu: ~53 rows (approximately)
REPLACE INTO `m_menu` (`MENU_ID`, `MENU_ICON`, `MENU_INDUK`, `MENU_NAMA`, `MENU_DESKRIPSI`, `MENU_LEVEL`, `MENU_URL`, `MENU_TARGET`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('1', 'ico-home2', '0', 'Dashboard', 'Menu Dashboard', 'level1', 'dashboard.php', NULL, '0', NULL, NULL),
	('2', 'ico-grid', '0', 'Master', 'Menu Master', 'level1', NULL, 'master', '0', NULL, NULL),
	('2.1', NULL, '2', 'Tingkatan dan Gelar', 'Data Master Tingkatan dan Gelar', 'level2', 'tingkatgelar.php', NULL, '0', NULL, NULL),
	('2.2', NULL, '2', 'Lokasi Institut', 'Data Master Lokasi Institut', 'level2', NULL, 'lokasi', '0', NULL, NULL),
	('2.2.1', NULL, '2.2', 'Lokasi Pusat', 'Data Master Kantor', 'level3', 'lokasipusat.php', NULL, '0', NULL, NULL),
	('2.2.2', NULL, '2.2', 'Lokasi Daerah', 'Data Master Kantor Pusat', 'level3', 'lokasidaerah.php', NULL, '0', NULL, NULL),
	('2.2.3', NULL, '2.2', 'Lokasi Cabang', 'Data Master Kantor Daerah', 'level3', 'lokasicabang.php', NULL, '0', NULL, NULL),
	('2.3', NULL, '2', 'ID dan Sertifikat', 'Data Master Kantor Cabang', 'level2', 'idsertifikat.php', NULL, '0', NULL, NULL),
	('2.4', NULL, '2', 'Data Terpusat', 'Data Master Kantor Ranting', 'level2', 'dataterpusat.php', NULL, '0', NULL, NULL),
	('3', 'ico-edit', '0', 'Transaksi', 'Menu Transaksi', 'level1', NULL, 'transaksi', '0', NULL, NULL),
	('3.1', NULL, '3', 'Kepengurusan', 'Pembukaan Pusat Daya', 'level2', 'kepengurusan.php', NULL, '0', NULL, NULL),
	('3.2', NULL, '3', 'Anggota', 'Ujian Kenaikan Tingkat', 'level2', NULL, 'anggota', '0', NULL, NULL),
	('3.2.1', NULL, '3.2', 'Daftar Anggota', 'Latihan Gabungan', 'level3', 'anggota.php', NULL, '0', NULL, NULL),
	('3.2.2', NULL, '3.2', 'Mutasi Anggota', 'Latihan Gabungan', 'level3', 'mutasianggota.php', NULL, '0', NULL, NULL),
	('3.2.3', NULL, '3.2', 'Kas Anggota', 'Latihan Gabungan', 'level3', 'kasanggota.php', NULL, '0', NULL, NULL),
	('3.3', NULL, '3', 'Aktivitas', 'Latihan Gabungan', 'level2', NULL, 'aktivitas', '0', NULL, NULL),
	('3.3.1', NULL, '3.3', 'Pembukaan Pusat Daya', 'Latihan Gabungan', 'level3', 'pusatdaya.php', NULL, '0', NULL, NULL),
	('3.3.2', NULL, '3.3', 'Ujian Kenaikan Tingkat', 'Latihan Gabungan', 'level3', 'ujinaiktingkat.php', NULL, '0', NULL, NULL),
	('3.3.3', NULL, '3.3', 'Latihan Gabungan', 'Latihan Gabungan', 'level3', 'latihangabungan.php', NULL, '0', NULL, NULL),
	('3.3.4', NULL, '3.3', 'Pendidikan dan Latihan', 'Latihan Gabungan', 'level3', 'pendidikanlatihan.php', NULL, '0', NULL, NULL),
	('4', 'ico-file-pdf', '0', 'Laporan', 'Menu Laporan', 'level1', NULL, 'laporan', '0', NULL, NULL),
	('4.1', NULL, '4', 'Daftar Cabang', 'Laporan Data Perguruan', 'level2', 'lapdaftarcabang.php', NULL, '0', NULL, NULL),
	('4.2', NULL, '4', 'Laporan Kepengurusan', 'Laporan Data Dewan Guru', 'level2', NULL, 'lappengurus', '0', NULL, NULL),
	('4.2.1', NULL, '4.2', 'Daftar Dewan Guru', 'Laporan Data Dewan Guru', 'level3', 'lapdaftarguru.php', NULL, '0', NULL, NULL),
	('4.2.2', NULL, '4.2', 'Daftar Pelatin', 'Laporan Data Pelatih', 'level3', 'lapdaftarpelatih.php', NULL, '0', NULL, NULL),
	('4.2.3', NULL, '4.2', 'Daftar Pengurus', 'Laporan Data Pengurus', 'level3', 'lapdaftarpengurus.php', NULL, '0', NULL, NULL),
	('4.3', NULL, '4', 'Laporan Anggota', 'Laporan Data Anggota', 'level2', NULL, 'lapanggota', '0', NULL, NULL),
	('4.3.1', NULL, '4.3', 'Daftar Anggota', 'Laporan Data Anggota', 'level3', 'lapdaftaranggota.php', NULL, '0', NULL, NULL),
	('4.3.2', NULL, '4.3', 'ID Keanggotaan', NULL, 'level3', 'lapidanggota.php', NULL, '0', NULL, NULL),
	('4.3.3', NULL, '4.3', 'Kas Anggota', NULL, 'level3', 'lapkasanggota.php', NULL, '0', NULL, NULL),
	('4.4', NULL, '4', 'Format Standar', NULL, 'level2', 'lapformatstandar.php', NULL, '0', NULL, NULL),
	('5', 'ico-settings', '0', 'Admin', 'Menu Admin', 'level1', NULL, 'admin', '0', NULL, NULL),
	('5.1', NULL, '5', 'Profil Institut', 'Data User', 'level2', NULL, 'profil', '0', NULL, NULL),
	('5.1.1', NULL, '5.1', 'Profil dan Sejarah', 'Data User', 'level3', 'profil.php', NULL, '0', NULL, NULL),
	('5.1.2', NULL, '5.1', 'Visi dan Misi', 'Data User', 'level3', 'visimisi.php', NULL, '0', NULL, NULL),
	('5.1.3', NULL, '5.1', 'Arti Warna dan Lambang', 'Data User', 'level3', 'warnalambang.php', NULL, '0', NULL, NULL),
	('5.2', NULL, '5', 'Media Sosial', 'Menu Kelola Halaman Web Utama', 'level2', 'mediasosial.php', NULL, '0', NULL, NULL),
	('5.3', NULL, '5', 'User', 'Kelola Halaman Beranda', 'level2', 'user.php', NULL, '0', NULL, NULL),
	('5.4', NULL, '5', 'Menu', 'Kelola Halaman Profil', 'level2', 'menu.php', NULL, '0', NULL, NULL),
	('5.5', NULL, '5', 'Manajemen Konten Web', 'Kelola Halaman Kegiatan', 'level2', NULL, 'manajemenkonten', '0', NULL, NULL),
	('5.5.1', NULL, '5.5', 'Header', 'Kelola Data Kegiatan A', 'level3', 'kontenheader.php', NULL, '0', NULL, NULL),
	('5.5.2', NULL, '5.5', 'Footer', 'Kelola Data Kegiatan B', 'level3', 'kontenfooter.php', NULL, '0', NULL, NULL),
	('5.5.3', NULL, '5.5', 'Halaman Beranda', 'Kelola Data Kegiatan B', 'level3', NULL, 'beranda', '0', NULL, NULL),
	('5.5.3.1', NULL, '5.5.3', 'Bagian Poster', 'Kelola Data Kegiatan B', 'level4', 'berandaposter.php', NULL, '0', NULL, NULL),
	('5.5.3.2', NULL, '5.5.3', 'Bagian Kegiatan', 'Kelola Data Kegiatan B', 'level4', 'berandakegiatan.php', NULL, '0', NULL, NULL),
	('5.5.3.3', NULL, '5.5.3', 'Bagian Informasi', NULL, 'level4', 'berandainformasi.php', NULL, '0', NULL, NULL),
	('5.5.4', NULL, '5.5', 'Halaman Tentang Kami', NULL, 'level3', NULL, 'tentangkami', '0', NULL, NULL),
	('5.5.4.1', NULL, '5.5.4', 'Bagian Sejarah', NULL, 'level4', 'tentangsejarah.php', NULL, '0', NULL, NULL),
	('5.5.4.2', NULL, '5.5.4', 'Bagian Visi dan Misi', NULL, 'level4', 'tentangvisimisi.php', NULL, '0', NULL, NULL),
	('5.5.5', NULL, '5.5', 'Halaman Daftar Cabang', NULL, 'level3', 'daftarcabang.php', NULL, '0', NULL, NULL),
	('5.5.6', NULL, '5.5', 'Halaman Koordinator Cabang', NULL, 'level3', 'koordinatorcabang.php', NULL, '0', NULL, NULL),
	('5.5.7', NULL, '5.5', 'Halaman Blog', NULL, 'level3', 'kontenblog.php', NULL, '0', NULL, NULL),
	('5.5.8', NULL, '5.5', 'Halaman Hubungi Kami', NULL, 'level3', 'kontenhubungi.php', NULL, '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_menuakses
DROP TABLE IF EXISTS `m_menuakses`;
CREATE TABLE IF NOT EXISTS `m_menuakses` (
  `MENU_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `MENU_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_menu',
  `USER_AKSES` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'm_user',
  `VIEW` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ADD` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EDIT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `APPROVE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRINT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`MENU_KEY`),
  KEY `MENU_ID` (`MENU_ID`),
  KEY `USER_ID` (`USER_AKSES`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_menuakses: ~212 rows (approximately)
REPLACE INTO `m_menuakses` (`MENU_KEY`, `MENU_ID`, `USER_AKSES`, `VIEW`, `ADD`, `EDIT`, `DELETE`, `APPROVE`, `PRINT`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('e5dde6a6-9c18-11ee-9151-e86a64d05f05', '1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddee66-9c18-11ee-9151-e86a64d05f05', '2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddefd1-9c18-11ee-9151-e86a64d05f05', '2.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf0d9-9c18-11ee-9151-e86a64d05f05', '2.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf20e-9c18-11ee-9151-e86a64d05f05', '2.2.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf3a7-9c18-11ee-9151-e86a64d05f05', '2.2.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf4b2-9c18-11ee-9151-e86a64d05f05', '2.2.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf5bf-9c18-11ee-9151-e86a64d05f05', '2.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ddf6cd-9c18-11ee-9151-e86a64d05f05', '2.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0512-9c18-11ee-9151-e86a64d05f05', '3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0679-9c18-11ee-9151-e86a64d05f05', '3.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de073e-9c18-11ee-9151-e86a64d05f05', '3.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de07f2-9c18-11ee-9151-e86a64d05f05', '3.2.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de08bb-9c18-11ee-9151-e86a64d05f05', '3.2.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0982-9c18-11ee-9151-e86a64d05f05', '3.2.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0a35-9c18-11ee-9151-e86a64d05f05', '3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0b25-9c18-11ee-9151-e86a64d05f05', '3.3.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0bd8-9c18-11ee-9151-e86a64d05f05', '3.3.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0c84-9c18-11ee-9151-e86a64d05f05', '3.3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0d2f-9c18-11ee-9151-e86a64d05f05', '3.3.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0e13-9c18-11ee-9151-e86a64d05f05', '4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0ed7-9c18-11ee-9151-e86a64d05f05', '4.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de0f7f-9c18-11ee-9151-e86a64d05f05', '4.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1023-9c18-11ee-9151-e86a64d05f05', '4.2.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de10ca-9c18-11ee-9151-e86a64d05f05', '4.2.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1171-9c18-11ee-9151-e86a64d05f05', '4.2.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1213-9c18-11ee-9151-e86a64d05f05', '4.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de12c9-9c18-11ee-9151-e86a64d05f05', '4.3.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de136f-9c18-11ee-9151-e86a64d05f05', '4.3.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de140d-9c18-11ee-9151-e86a64d05f05', '4.3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de14af-9c18-11ee-9151-e86a64d05f05', '4.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1550-9c18-11ee-9151-e86a64d05f05', '5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1619-9c18-11ee-9151-e86a64d05f05', '5.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de16b9-9c18-11ee-9151-e86a64d05f05', '5.1.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1768-9c18-11ee-9151-e86a64d05f05', '5.1.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1808-9c18-11ee-9151-e86a64d05f05', '5.1.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de18c2-9c18-11ee-9151-e86a64d05f05', '5.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1965-9c18-11ee-9151-e86a64d05f05', '5.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1a04-9c18-11ee-9151-e86a64d05f05', '5.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1aa5-9c18-11ee-9151-e86a64d05f05', '5.5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1b43-9c18-11ee-9151-e86a64d05f05', '5.5.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1bfd-9c18-11ee-9151-e86a64d05f05', '5.5.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1ca5-9c18-11ee-9151-e86a64d05f05', '5.5.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1d46-9c18-11ee-9151-e86a64d05f05', '5.5.3.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1de8-9c18-11ee-9151-e86a64d05f05', '5.5.3.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1e89-9c18-11ee-9151-e86a64d05f05', '5.5.3.3', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1f2a-9c18-11ee-9151-e86a64d05f05', '5.5.4', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de1fc8-9c18-11ee-9151-e86a64d05f05', '5.5.4.1', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2068-9c18-11ee-9151-e86a64d05f05', '5.5.4.2', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2106-9c18-11ee-9151-e86a64d05f05', '5.5.5', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de21a7-9c18-11ee-9151-e86a64d05f05', '5.5.6', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de226f-9c18-11ee-9151-e86a64d05f05', '5.5.7', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2311-9c18-11ee-9151-e86a64d05f05', '5.5.8', 'Administrator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de23b4-9c18-11ee-9151-e86a64d05f05', '1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2597-9c18-11ee-9151-e86a64d05f05', '2', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2659-9c18-11ee-9151-e86a64d05f05', '2.1', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2700-9c18-11ee-9151-e86a64d05f05', '2.2', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de27a0-9c18-11ee-9151-e86a64d05f05', '2.2.1', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de283f-9c18-11ee-9151-e86a64d05f05', '2.2.2', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de28e1-9c18-11ee-9151-e86a64d05f05', '2.2.3', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de29f2-9c18-11ee-9151-e86a64d05f05', '2.3', 'Koordinator', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2a9b-9c18-11ee-9151-e86a64d05f05', '2.4', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2b3b-9c18-11ee-9151-e86a64d05f05', '3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2bdb-9c18-11ee-9151-e86a64d05f05', '3.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2c7e-9c18-11ee-9151-e86a64d05f05', '3.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2d23-9c18-11ee-9151-e86a64d05f05', '3.2.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2dc3-9c18-11ee-9151-e86a64d05f05', '3.2.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2e95-9c18-11ee-9151-e86a64d05f05', '3.2.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2f40-9c18-11ee-9151-e86a64d05f05', '3.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de2fe4-9c18-11ee-9151-e86a64d05f05', '3.3.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3085-9c18-11ee-9151-e86a64d05f05', '3.3.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3124-9c18-11ee-9151-e86a64d05f05', '3.3.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de31c5-9c18-11ee-9151-e86a64d05f05', '3.3.4', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3267-9c18-11ee-9151-e86a64d05f05', '4', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3316-9c18-11ee-9151-e86a64d05f05', '4.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de33e3-9c18-11ee-9151-e86a64d05f05', '4.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3484-9c18-11ee-9151-e86a64d05f05', '4.2.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de377f-9c18-11ee-9151-e86a64d05f05', '4.2.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de382a-9c18-11ee-9151-e86a64d05f05', '4.2.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de38ce-9c18-11ee-9151-e86a64d05f05', '4.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de396d-9c18-11ee-9151-e86a64d05f05', '4.3.1', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3a4f-9c18-11ee-9151-e86a64d05f05', '4.3.2', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3af6-9c18-11ee-9151-e86a64d05f05', '4.3.3', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3bbc-9c18-11ee-9151-e86a64d05f05', '4.4', 'Koordinator', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3c63-9c18-11ee-9151-e86a64d05f05', '5', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3dd3-9c18-11ee-9151-e86a64d05f05', '5.1', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3eb2-9c18-11ee-9151-e86a64d05f05', '5.1.1', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de3f73-9c18-11ee-9151-e86a64d05f05', '5.1.2', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de406f-9c18-11ee-9151-e86a64d05f05', '5.1.3', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de4120-9c18-11ee-9151-e86a64d05f05', '5.2', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de41eb-9c18-11ee-9151-e86a64d05f05', '5.3', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de42b1-9c18-11ee-9151-e86a64d05f05', '5.4', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de43ed-9c18-11ee-9151-e86a64d05f05', '5.5', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de4543-9c18-11ee-9151-e86a64d05f05', '5.5.1', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de46f7-9c18-11ee-9151-e86a64d05f05', '5.5.2', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de49f9-9c18-11ee-9151-e86a64d05f05', '5.5.3', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de4db7-9c18-11ee-9151-e86a64d05f05', '5.5.3.1', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de51ae-9c18-11ee-9151-e86a64d05f05', '5.5.3.2', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de551d-9c18-11ee-9151-e86a64d05f05', '5.5.3.3', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de590f-9c18-11ee-9151-e86a64d05f05', '5.5.4', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de5a36-9c18-11ee-9151-e86a64d05f05', '5.5.4.1', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de5b45-9c18-11ee-9151-e86a64d05f05', '5.5.4.2', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de5c65-9c18-11ee-9151-e86a64d05f05', '5.5.5', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de5da4-9c18-11ee-9151-e86a64d05f05', '5.5.6', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de5ef3-9c18-11ee-9151-e86a64d05f05', '5.5.7', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de603f-9c18-11ee-9151-e86a64d05f05', '5.5.8', 'Koordinator', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de6197-9c18-11ee-9151-e86a64d05f05', '1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de62e0-9c18-11ee-9151-e86a64d05f05', '2', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de659e-9c18-11ee-9151-e86a64d05f05', '2.1', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de66e8-9c18-11ee-9151-e86a64d05f05', '2.2', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de679f-9c18-11ee-9151-e86a64d05f05', '2.2.1', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de6927-9c18-11ee-9151-e86a64d05f05', '2.2.2', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de6d0a-9c18-11ee-9151-e86a64d05f05', '2.2.3', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de6f48-9c18-11ee-9151-e86a64d05f05', '2.3', 'Pengurus', 'Y', 'N', 'N', 'N', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de7224-9c18-11ee-9151-e86a64d05f05', '2.4', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de74ef-9c18-11ee-9151-e86a64d05f05', '3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de77cb-9c18-11ee-9151-e86a64d05f05', '3.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de7a6c-9c18-11ee-9151-e86a64d05f05', '3.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de7cbb-9c18-11ee-9151-e86a64d05f05', '3.2.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de7f1b-9c18-11ee-9151-e86a64d05f05', '3.2.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de81a4-9c18-11ee-9151-e86a64d05f05', '3.2.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8456-9c18-11ee-9151-e86a64d05f05', '3.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de892a-9c18-11ee-9151-e86a64d05f05', '3.3.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8b30-9c18-11ee-9151-e86a64d05f05', '3.3.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8cdd-9c18-11ee-9151-e86a64d05f05', '3.3.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8dd8-9c18-11ee-9151-e86a64d05f05', '3.3.4', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8efa-9c18-11ee-9151-e86a64d05f05', '4', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de8fc8-9c18-11ee-9151-e86a64d05f05', '4.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de907f-9c18-11ee-9151-e86a64d05f05', '4.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de917d-9c18-11ee-9151-e86a64d05f05', '4.2.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9275-9c18-11ee-9151-e86a64d05f05', '4.2.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de932a-9c18-11ee-9151-e86a64d05f05', '4.2.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de955b-9c18-11ee-9151-e86a64d05f05', '4.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de960d-9c18-11ee-9151-e86a64d05f05', '4.3.1', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de96bb-9c18-11ee-9151-e86a64d05f05', '4.3.2', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de976b-9c18-11ee-9151-e86a64d05f05', '4.3.3', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de982a-9c18-11ee-9151-e86a64d05f05', '4.4', 'Pengurus', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de98ee-9c18-11ee-9151-e86a64d05f05', '5', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de99b1-9c18-11ee-9151-e86a64d05f05', '5.1', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9a66-9c18-11ee-9151-e86a64d05f05', '5.1.1', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9b1e-9c18-11ee-9151-e86a64d05f05', '5.1.2', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9bcb-9c18-11ee-9151-e86a64d05f05', '5.1.3', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9c79-9c18-11ee-9151-e86a64d05f05', '5.2', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9d2a-9c18-11ee-9151-e86a64d05f05', '5.3', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9e1b-9c18-11ee-9151-e86a64d05f05', '5.4', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9ed9-9c18-11ee-9151-e86a64d05f05', '5.5', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5de9fa5-9c18-11ee-9151-e86a64d05f05', '5.5.1', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea05b-9c18-11ee-9151-e86a64d05f05', '5.5.2', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea10b-9c18-11ee-9151-e86a64d05f05', '5.5.3', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea1bc-9c18-11ee-9151-e86a64d05f05', '5.5.3.1', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea26e-9c18-11ee-9151-e86a64d05f05', '5.5.3.2', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea32f-9c18-11ee-9151-e86a64d05f05', '5.5.3.3', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea63f-9c18-11ee-9151-e86a64d05f05', '5.5.4', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea706-9c18-11ee-9151-e86a64d05f05', '5.5.4.1', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea7ba-9c18-11ee-9151-e86a64d05f05', '5.5.4.2', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea868-9c18-11ee-9151-e86a64d05f05', '5.5.5', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea91d-9c18-11ee-9151-e86a64d05f05', '5.5.6', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dea9cd-9c18-11ee-9151-e86a64d05f05', '5.5.7', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deaa7b-9c18-11ee-9151-e86a64d05f05', '5.5.8', 'Pengurus', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deab26-9c18-11ee-9151-e86a64d05f05', '1', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deaf55-9c18-11ee-9151-e86a64d05f05', '2', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb008-9c18-11ee-9151-e86a64d05f05', '2.1', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb0d6-9c18-11ee-9151-e86a64d05f05', '2.2', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb184-9c18-11ee-9151-e86a64d05f05', '2.2.1', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb237-9c18-11ee-9151-e86a64d05f05', '2.2.2', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb37e-9c18-11ee-9151-e86a64d05f05', '2.2.3', 'User', 'Y', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb437-9c18-11ee-9151-e86a64d05f05', '2.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb4e3-9c18-11ee-9151-e86a64d05f05', '2.4', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb593-9c18-11ee-9151-e86a64d05f05', '3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb63d-9c18-11ee-9151-e86a64d05f05', '3.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb6e9-9c18-11ee-9151-e86a64d05f05', '3.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb794-9c18-11ee-9151-e86a64d05f05', '3.2.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb84c-9c18-11ee-9151-e86a64d05f05', '3.2.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb8f9-9c18-11ee-9151-e86a64d05f05', '3.2.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deb9a4-9c18-11ee-9151-e86a64d05f05', '3.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deba4e-9c18-11ee-9151-e86a64d05f05', '3.3.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debafc-9c18-11ee-9151-e86a64d05f05', '3.3.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debba5-9c18-11ee-9151-e86a64d05f05', '3.3.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debc6b-9c18-11ee-9151-e86a64d05f05', '3.3.4', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debd1a-9c18-11ee-9151-e86a64d05f05', '4', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debdd5-9c18-11ee-9151-e86a64d05f05', '4.1', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debe92-9c18-11ee-9151-e86a64d05f05', '4.2', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debf41-9c18-11ee-9151-e86a64d05f05', '4.2.1', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5debfed-9c18-11ee-9151-e86a64d05f05', '4.2.2', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec09a-9c18-11ee-9151-e86a64d05f05', '4.2.3', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec144-9c18-11ee-9151-e86a64d05f05', '4.3', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec1f0-9c18-11ee-9151-e86a64d05f05', '4.3.1', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec2a8-9c18-11ee-9151-e86a64d05f05', '4.3.2', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec357-9c18-11ee-9151-e86a64d05f05', '4.3.3', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec3ff-9c18-11ee-9151-e86a64d05f05', '4.4', 'User', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec4b4-9c18-11ee-9151-e86a64d05f05', '5', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec591-9c18-11ee-9151-e86a64d05f05', '5.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec640-9c18-11ee-9151-e86a64d05f05', '5.1.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec6ea-9c18-11ee-9151-e86a64d05f05', '5.1.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec7ae-9c18-11ee-9151-e86a64d05f05', '5.1.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec869-9c18-11ee-9151-e86a64d05f05', '5.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec918-9c18-11ee-9151-e86a64d05f05', '5.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dec9c2-9c18-11ee-9151-e86a64d05f05', '5.4', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5deca6d-9c18-11ee-9151-e86a64d05f05', '5.5', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5decb23-9c18-11ee-9151-e86a64d05f05', '5.5.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5decbcf-9c18-11ee-9151-e86a64d05f05', '5.5.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5decc7a-9c18-11ee-9151-e86a64d05f05', '5.5.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5decd26-9c18-11ee-9151-e86a64d05f05', '5.5.3.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5decdd1-9c18-11ee-9151-e86a64d05f05', '5.5.3.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5dece7d-9c18-11ee-9151-e86a64d05f05', '5.5.3.3', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded26b-9c18-11ee-9151-e86a64d05f05', '5.5.4', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded31d-9c18-11ee-9151-e86a64d05f05', '5.5.4.1', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded3d2-9c18-11ee-9151-e86a64d05f05', '5.5.4.2', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded47e-9c18-11ee-9151-e86a64d05f05', '5.5.5', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded52a-9c18-11ee-9151-e86a64d05f05', '5.5.6', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded5fb-9c18-11ee-9151-e86a64d05f05', '5.5.7', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r'),
	('e5ded6aa-9c18-11ee-9151-e86a64d05f05', '5.5.8', 'User', 'N', 'N', 'N', 'N', 'N', 'N', 'SYSTEM', '2023-12-16 08:26\r');

-- Dumping structure for table ciptasejati.m_pusat
DROP TABLE IF EXISTS `m_pusat`;
CREATE TABLE IF NOT EXISTS `m_pusat` (
  `PUSAT_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PUSAT_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PUSAT_DESKRIPSI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSAT_SEKRETARIAT` text COLLATE utf8mb4_general_ci,
  `PUSAT_KEPENGURUSAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSAT_MAP` text COLLATE utf8mb4_general_ci,
  `PUSAT_LAT` text COLLATE utf8mb4_general_ci,
  `PUSAT_LONG` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`PUSAT_KEY`),
  UNIQUE KEY `PUSAT_ID` (`PUSAT_ID`),
  KEY `PUSAT_DESKRIPSI` (`PUSAT_DESKRIPSI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_pusat: ~0 rows (approximately)
REPLACE INTO `m_pusat` (`PUSAT_KEY`, `PUSAT_ID`, `PUSAT_DESKRIPSI`, `PUSAT_SEKRETARIAT`, `PUSAT_KEPENGURUSAN`, `PUSAT_MAP`, `PUSAT_LAT`, `PUSAT_LONG`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('e3818c55-9641-11ee-9fd3-e86a64d05f05', '001', 'Kalimantan Selatan', 'Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan', '-', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698203909709!5m2!1sen!2sid', '-3.3063120100780785', '114.56921894055016', '0', '09.03.2023.0001', '2023-12-09 10:25:15');

-- Dumping structure for table ciptasejati.m_pusatdata
DROP TABLE IF EXISTS `m_pusatdata`;
CREATE TABLE IF NOT EXISTS `m_pusatdata` (
  `PUSATDATA_ID` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_KEY` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSATDATA_KATEGORI` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSATDATA_JUDUL` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PUSATDATA_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `PUSATDATA_FILE` text COLLATE utf8mb4_general_ci,
  `PUSATDATA_FILENAMA` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`PUSATDATA_ID`),
  KEY `CABANG_ID` (`CABANG_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_pusatdata: ~6 rows (approximately)
REPLACE INTO `m_pusatdata` (`PUSATDATA_ID`, `CABANG_KEY`, `PUSATDATA_KATEGORI`, `PUSATDATA_JUDUL`, `PUSATDATA_DESKRIPSI`, `PUSATDATA_FILE`, `PUSATDATA_FILENAMA`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('2dc54cb1-930c-11ee-a8dd-e86a64d05f05', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'Materi', 'Mater Excel', 'Test Excel', './assets/dataterpusat/Kotawaringin Timur/Materi/template_master_block_mundianto.xlsx', 'template_master_block_mundianto.xlsx', '0', '09.03.2023.0001', '2023-12-12 16:04:48'),
	('779dc3ef-8bb8-11ee-885f-025041000001', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'Penghargaan', 'Juara 1 Provinsi Kalimantan Tengah', 'Penghargaan acara gubernur di kota Sampit', './assets/dataterpusat/Sampit/Penghargaan/Hijau.png', 'Hijau.png', '0', '09.03.2023.0001', '2023-12-01 07:13:48'),
	('c8e71d5c-8bb7-11ee-885f-025041000001', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'Materi', 'Pelatihan Pra Pemula Tahap I', 'Materi pelatihan dasar untuk pra pemula tahap I', './assets/dataterpusat/Sampit/Materi/contentfieldInsReportsummaryMonthly.pdf', 'contentfieldInsReportsummaryMonthly.pdf', '0', '09.03.2023.0001', '2023-11-26 02:11:47'),
	('cc5c9326-9c2a-11ee-9151-e86a64d05f05', '65741a93-9640-11ee-9fd3-e86a64d05f05', 'Materi', 'Pelatihan Pra Pemula Tahap II', 'Materi pelatihan dasar untuk pra pemula tahap II', './assets/dataterpusat/Kotawaringin Timur/Materi/Screenshot 2023-11-14 160649.png', 'Screenshot 2023-11-14 160649.png', '0', '09.03.2023.0001', '2023-12-16 22:50:12'),
	('ceea4974-94d2-11ee-9837-e86a64d05f05', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'test', 'test', 'test', './assets/dataterpusat/Surabaya/test/cat2.jpg', 'cat2.jpg', '0', '09.03.2023.0001', '2023-12-07 14:32:43'),
	('cfdeb644-8bb8-11ee-885f-025041000001', '657413ba-9640-11ee-9fd3-e86a64d05f05', 'Legalitas', 'Surat Ijin Institusi Cabang Sampit', 'Dokumen legal surat ijin institusi Cipta Sejati Cabang Sampit', './assets/dataterpusat/Sampit/Legalitas/Cipta Sejati.pdf', 'Cipta Sejati.pdf', '0', '09.03.2023.0001', '2023-11-26 00:33:57');

-- Dumping structure for table ciptasejati.m_ranting
DROP TABLE IF EXISTS `m_ranting`;
CREATE TABLE IF NOT EXISTS `m_ranting` (
  `RANTING_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'm_cabang',
  `RANTING_DESKRIPSI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `RANTING_SEKRETARIAT` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `RANTING_PENGURUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`RANTING_ID`),
  KEY `CABANG_ID` (`CABANG_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_ranting: ~0 rows (approximately)
REPLACE INTO `m_ranting` (`RANTING_ID`, `CABANG_ID`, `RANTING_DESKRIPSI`, `RANTING_SEKRETARIAT`, `RANTING_PENGURUS`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('RTG-0923-0001', 'CBG-0923-0001', 'Ranting A', 'Ranting Sekretariat', 'Ranting Pengurus', '0', NULL, NULL);

-- Dumping structure for table ciptasejati.m_tingkatan
DROP TABLE IF EXISTS `m_tingkatan`;
CREATE TABLE IF NOT EXISTS `m_tingkatan` (
  `TINGKATAN_ID` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `TINGKATAN_NAMA` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `TINGKATAN_GELAR` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `TINGKATAN_SEBUTAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `TINGKATAN_LEVEL` int DEFAULT NULL,
  `TINGKATAN_AKSES` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`TINGKATAN_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_tingkatan: ~13 rows (approximately)
REPLACE INTO `m_tingkatan` (`TINGKATAN_ID`, `TINGKATAN_NAMA`, `TINGKATAN_GELAR`, `TINGKATAN_SEBUTAN`, `TINGKATAN_LEVEL`, `TINGKATAN_AKSES`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('0e0fbf18-8394-11ee-9d33-e86a64d05f05', 'Hijau', '-', 'Dasar-I', 3, NULL, '0', 'admincs01', '2023-11-16 15:17:03'),
	('1029ad73-845a-11ee-a3a1-e86a64d05f05', 'Merah-II', 'GMy', 'Guru Madya', 11, NULL, '0', '09.03.2023.0001', '2023-11-28 08:55:51'),
	('159c472b-8459-11ee-a3a1-e86a64d05f05', 'Coklat', '-', 'Asisten Pelatih', 5, NULL, '0', 'admincs01', '2023-11-16 15:21:04'),
	('1f884a25-845a-11ee-a3a1-e86a64d05f05', 'Merah-III', 'GU', 'Guru Utama', 12, NULL, '0', 'admincs01', '2023-11-16 15:28:30'),
	('221919eb-8427-11ee-a3a1-e86a64d05f05', 'Biru', '-', 'Dasar-II', 4, NULL, '0', 'admincs01', '2023-11-16 15:17:47'),
	('2a834e41-8459-11ee-a3a1-e86a64d05f05', 'Hitam-I', 'PM', 'Pelatih Muda', 6, NULL, '0', 'admincs01', '2023-11-16 15:21:39'),
	('2ee61c8c-845a-11ee-a3a1-e86a64d05f05', 'Merah-IV', 'GB', 'Guru Besar', 13, NULL, '0', 'admincs01', '2023-11-16 15:28:56'),
	('45aa0b01-8459-11ee-a3a1-e86a64d05f05', 'Hitam-II', 'PMy', 'Pelatih Madya', 7, NULL, '0', 'admincs01', '2023-11-16 15:22:25'),
	('7139de6a-8459-11ee-a3a1-e86a64d05f05', 'Hitam-III', 'PU', 'Pelatih Utama', 8, NULL, '0', 'admincs01', '2023-11-16 15:24:02'),
	('7fdee137-8392-11ee-9d33-e86a64d05f05', 'Kuning', '-', 'Pemula', 2, NULL, '0', 'admincs01', '2023-11-16 15:16:22'),
	('abdf9bdf-8459-11ee-a3a1-e86a64d05f05', 'Hitam-IV', 'PU', 'Pelatih Utama', 9, NULL, '0', 'admincs01', '2023-11-16 15:25:16'),
	('bef4c735-8459-11ee-a3a1-e86a64d05f05', 'Merah-I', 'GM', 'Guru Muda', 10, NULL, '0', 'admincs01', '2023-11-16 15:25:48'),
	('cf7f4290-8391-11ee-9d33-e86a64d05f05', 'Putih', '-', 'Pra Pemula', 1, NULL, '0', 'admincs01', '2023-11-30 10:25:29');

-- Dumping structure for table ciptasejati.m_user
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE IF NOT EXISTS `m_user` (
  `ANGGOTA_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `USER_PASSWORD` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `USER_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'SYSTEM',
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ANGGOTA_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_user: ~4 rows (approximately)
REPLACE INTO `m_user` (`ANGGOTA_KEY`, `USER_PASSWORD`, `USER_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('98436ea1-9a51-11ee-90cd-e86a64d05f05', '$2y$12$K89pOSXwJUv1vrbUu/MRduIm0JlBncCDH1TZLy3PYAuMtCHiq2WBa', '0', 'SYSTEM', '2023-12-28 10:37:04'),
	('9d82e92a-928d-11ee-9020-e86a64d05f05', '$2y$12$K89pOSXwJUv1vrbUu/MRduIm0JlBncCDH1TZLy3PYAuMtCHiq2WBa', '0', 'SYSTEM', NULL),
	('9d83086f-928d-11ee-9020-e86a64d05f05', '$2y$12$/euSbZR2DLniY7zpjJkztOnG72rMolrscK2N3UxSykgjJ0CNjPU0y', '0', 'SYSTEM', '2023-09-17 21:07:59'),
	('a263a10a-998f-11ee-bfb0-e86a64d05f05', '$2y$12$/euSbZR2DLniY7zpjJkztOnG72rMolrscK2N3UxSykgjJ0CNjPU0y', '0', 'SYSTEM', '2023-09-17 21:07:59');

-- Dumping structure for table ciptasejati.m_user_log
DROP TABLE IF EXISTS `m_user_log`;
CREATE TABLE IF NOT EXISTS `m_user_log` (
  `ANGGOTA_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `USER_PASSWORD` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'SYSTEM',
  `INPUT_DATE` datetime DEFAULT NULL,
  `LOG_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  KEY `ANGGOTA_ID` (`ANGGOTA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.m_user_log: ~0 rows (approximately)

-- Dumping structure for table ciptasejati.p_param
DROP TABLE IF EXISTS `p_param`;
CREATE TABLE IF NOT EXISTS `p_param` (
  `KATEGORI` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CODE` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DESK` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.p_param: ~2 rows (approximately)
REPLACE INTO `p_param` (`KATEGORI`, `CODE`, `DESK`) VALUES
	('STATUS', '0', 'Aktif'),
	('STATUS', '1', 'Tidak Aktif');

-- Dumping structure for table ciptasejati.t_kas
DROP TABLE IF EXISTS `t_kas`;
CREATE TABLE IF NOT EXISTS `t_kas` (
  `KAS_ID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ANGGOTA_KEY` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `KAS_TANGGAL` date DEFAULT NULL,
  `KAS_DK` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `KAS_JUMLAH` int DEFAULT NULL,
  `KAS_DESKRIPSI` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`KAS_ID`),
  KEY `ANGGOTA_ID` (`ANGGOTA_KEY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.t_kas: ~3 rows (approximately)
REPLACE INTO `t_kas` (`KAS_ID`, `ANGGOTA_KEY`, `KAS_TANGGAL`, `KAS_DK`, `KAS_JUMLAH`, `KAS_DESKRIPSI`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('KAS-122023-001', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '2023-12-26', 'D', 500000, 'Saldo Awal', '0', '09.03.2023.0001', '2023-12-26 07:38:17'),
	('KAS-122023-002', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '2023-12-27', 'D', 100000, 'test', '0', '09.03.2023.0001', '2023-12-27 07:38:17'),
	('KAS-122023-003', '98436ea1-9a51-11ee-90cd-e86a64d05f05', '2023-12-28', 'K', -200000, 'test', '0', '09.03.2023.0001', '2023-12-28 07:38:17');

-- Dumping structure for table ciptasejati.t_mutasi
DROP TABLE IF EXISTS `t_mutasi`;
CREATE TABLE IF NOT EXISTS `t_mutasi` (
  `MUTASI_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_AWAL` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_TUJUAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `MUTASI_TANGGAL` date DEFAULT NULL,
  `MUTASI_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_STATUS_TANGGAL` datetime DEFAULT NULL,
  `MUTASI_APPROVE_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_APPROVE_TANGGAL` datetime DEFAULT NULL,
  `MUTASI_FILE` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`MUTASI_ID`),
  KEY `CABANG_AWAL` (`CABANG_AWAL`),
  KEY `CABANG_TUJUAN` (`CABANG_TUJUAN`),
  KEY `ANGGOTA_KEY` (`ANGGOTA_KEY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.t_mutasi: ~1 rows (approximately)
REPLACE INTO `t_mutasi` (`MUTASI_ID`, `CABANG_AWAL`, `CABANG_TUJUAN`, `ANGGOTA_KEY`, `MUTASI_DESKRIPSI`, `MUTASI_TANGGAL`, `MUTASI_STATUS`, `MUTASI_STATUS_TANGGAL`, `MUTASI_APPROVE_BY`, `MUTASI_APPROVE_TANGGAL`, `MUTASI_FILE`, `DELETION_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-28 13:48:51', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota Naura Marieline  24 January 2024.pdf', '0', '09.03.2023.0001', '2023-12-27 23:23:24');

-- Dumping structure for table ciptasejati.t_mutasi_log
DROP TABLE IF EXISTS `t_mutasi_log`;
CREATE TABLE IF NOT EXISTS `t_mutasi_log` (
  `MUTASILOG_KEY` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `MUTASI_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_AWAL` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_TUJUAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ANGGOTA_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `MUTASI_TANGGAL` date DEFAULT NULL,
  `MUTASI_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_STATUS_TANGGAL` datetime DEFAULT NULL,
  `MUTASI_APPROVE_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MUTASI_APPROVE_TANGGAL` datetime DEFAULT NULL,
  `MUTASI_FILE` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `LOG_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`MUTASILOG_KEY`),
  KEY `CABANG_AWAL` (`CABANG_AWAL`) USING BTREE,
  KEY `CABANG_TUJUAN` (`CABANG_TUJUAN`) USING BTREE,
  KEY `ANGGOTA_KEY` (`ANGGOTA_KEY`) USING BTREE,
  KEY `MUTASI_ID` (`MUTASI_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.t_mutasi_log: ~10 rows (approximately)
REPLACE INTO `t_mutasi_log` (`MUTASILOG_KEY`, `MUTASI_ID`, `CABANG_AWAL`, `CABANG_TUJUAN`, `ANGGOTA_KEY`, `MUTASI_DESKRIPSI`, `MUTASI_TANGGAL`, `MUTASI_STATUS`, `MUTASI_STATUS_TANGGAL`, `MUTASI_APPROVE_BY`, `MUTASI_APPROVE_TANGGAL`, `MUTASI_FILE`, `DELETION_STATUS`, `LOG_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('28dbd074-a54d-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-28 13:48:51', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 13:48:51'),
	('42681d6b-a4d4-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, '', '0', 'I', '09.03.2023.0001', '2023-12-27 23:23:24'),
	('6856c908-a4d4-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-27 23:24:28', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-27 23:24:28'),
	('6ca94a09-a4d6-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-27 23:38:54'),
	('70a8a677-a4d6-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-27 23:39:01', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-27 23:39:01'),
	('7ef8eb84-a4d8-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-27 23:53:44'),
	('84404087-a4d8-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-27 23:53:53', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-27 23:53:53'),
	('867ba7f2-a51f-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 08:22:11'),
	('868e39a3-a532-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 10:38:11'),
	('8b8c1dd9-a51f-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-28 08:22:19', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 08:22:19'),
	('db3e5650-a515-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 07:12:58'),
	('df13ad01-a515-11ee-a359-e86a64d05f05', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-28 07:13:05', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 07:13:05'),
	('e33b2213-a4d9-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '0', '2023-12-27 23:23:24', NULL, NULL, './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 00:03:42'),
	('ec6318d7-a4d9-11ee-a71b-025041000001', 'MTS-202312-001', '65741a93-9640-11ee-9fd3-e86a64d05f05', '6574008b-9640-11ee-9fd3-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'Anggota bersangkutan telah berpindah domisili daerah Sidoarjo', '2024-01-24', '1', '2023-12-27 23:23:24', '09.03.2023.0001', '2023-12-28 00:03:57', './assets/report/mutasi/Seruyan/MTS-202312-001 Mutasi Anggota anu  24 January 2024.pdf', '0', 'U', '09.03.2023.0001', '2023-12-28 00:03:57');

-- Dumping structure for table ciptasejati.t_notifikasi
DROP TABLE IF EXISTS `t_notifikasi`;
CREATE TABLE IF NOT EXISTS `t_notifikasi` (
  `NOTIFIKASI_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `NOTIFIKASI_USER` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DOKUMEN_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CABANG_AWAL` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_TUJUAN` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `KATEGORI` text COLLATE utf8mb4_general_ci,
  `HREF` text COLLATE utf8mb4_general_ci,
  `TOGGLE` text COLLATE utf8mb4_general_ci,
  `SUBJECT` text COLLATE utf8mb4_general_ci,
  `BODY` text COLLATE utf8mb4_general_ci,
  `APPROVE_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `READ_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '0: Unread / 1: Read',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`NOTIFIKASI_ID`),
  KEY `CABANG_AWAL` (`CABANG_AWAL`),
  KEY `CABANG_TUJUAN` (`CABANG_TUJUAN`),
  KEY `NOTIFIKASI_USER` (`NOTIFIKASI_USER`),
  KEY `ID_DOKUMEN` (`DOKUMEN_ID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.t_notifikasi: ~3 rows (approximately)
REPLACE INTO `t_notifikasi` (`NOTIFIKASI_ID`, `NOTIFIKASI_USER`, `DOKUMEN_ID`, `CABANG_AWAL`, `CABANG_TUJUAN`, `KATEGORI`, `HREF`, `TOGGLE`, `SUBJECT`, `BODY`, `APPROVE_STATUS`, `READ_STATUS`, `INPUT_BY`, `INPUT_DATE`) VALUES
	('28dd733b-a54d-11ee-a359-e86a64d05f05', '98436ea1-9a51-11ee-90cd-e86a64d05f05', 'MTS-202312-001', 'Seruyan', 'Sidoarjo', 'Mutasi', 'ViewNotifMutasi', 'open-ViewNotifMutasi', 'Persetujuan Mutasi Anggota', 'Mutasi a.n Naura Marieline dari cabang Seruyan', '1', '1', '09.03.2023.0001', '2023-12-28 13:48:51'),
	('28dd9c9c-a54d-11ee-a359-e86a64d05f05', '9d82e92a-928d-11ee-9020-e86a64d05f05', 'MTS-202312-001', 'Seruyan', 'Sidoarjo', 'Mutasi', 'ViewNotifMutasi', 'open-ViewNotifMutasi', 'Persetujuan Mutasi Anggota', 'Mutasi a.n Naura Marieline dari cabang Seruyan', '1', '0', '09.03.2023.0001', '2023-12-28 13:48:51'),
	('28ddbfcf-a54d-11ee-a359-e86a64d05f05', '9d83086f-928d-11ee-9020-e86a64d05f05', 'MTS-202312-001', 'Seruyan', 'Sidoarjo', 'Mutasi', 'ViewNotifMutasi', 'open-ViewNotifMutasi', 'Persetujuan Mutasi Anggota', 'Mutasi a.n Naura Marieline dari cabang Seruyan', '1', '0', '09.03.2023.0001', '2023-12-28 13:48:51');

-- Dumping structure for table ciptasejati.t_ppd
DROP TABLE IF EXISTS `t_ppd`;
CREATE TABLE IF NOT EXISTS `t_ppd` (
  `PPD_ID` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ANGGOTA_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CABANG_KEY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `SABUK_ID` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PPD_TANGGAL` date DEFAULT NULL,
  `PPD_DESKRIPSI` text COLLATE utf8mb4_general_ci,
  `DELETION_STATUS` varchar(50) COLLATE utf8mb4_general_ci DEFAULT '0',
  `INPUT_BY` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `INPUT_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`PPD_ID`),
  KEY `ANGGOTA_KEY` (`ANGGOTA_KEY`),
  KEY `CABANG_KEY` (`CABANG_KEY`),
  KEY `SABUK_ID` (`SABUK_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ciptasejati.t_ppd: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
