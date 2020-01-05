-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.7-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for cmstoolkit
CREATE DATABASE IF NOT EXISTS `cmstoolkit` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cmstoolkit`;

-- Dumping structure for table cmstoolkit.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `id_login` int(11) NOT NULL,
  `name_admin` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_admin`),
  KEY `FK_admin_login` (`id_login`),
  CONSTRAINT `FK_admin_login` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table cmstoolkit.admin: ~0 rows (approximately)
DELETE FROM `admin`;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`id_admin`, `id_login`, `name_admin`) VALUES
	(1, 1, 'Admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Dumping structure for table cmstoolkit.login
CREATE TABLE IF NOT EXISTS `login` (
  `id_login` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(60) NOT NULL DEFAULT '',
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_login`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table cmstoolkit.login: ~2 rows (approximately)
DELETE FROM `login`;
/*!40000 ALTER TABLE `login` DISABLE KEYS */;
INSERT INTO `login` (`id_login`, `username`, `password`, `role`) VALUES
	(1, 'admin', '$2y$10$wVerUNh0IGO0QOkKyrSMTeicyfhTC/TRtEfTwFKPlPoCHIw9VHkf.', 'admin'),
	(2, 'user', '$2y$10$gq7my7kh9wj/6ewnEKAkDeqTMhR5EvJO9jpTJZ75zjZ945sIEUcpC', 'user');
/*!40000 ALTER TABLE `login` ENABLE KEYS */;

-- Dumping structure for table cmstoolkit.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_login` int(11) NOT NULL,
  `name_user` varchar(255) NOT NULL DEFAULT '',
  `email_user` varchar(255) NOT NULL DEFAULT '',
  `avatar_user` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_user`),
  KEY `FK_user_login` (`id_login`),
  CONSTRAINT `FK_user_login` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table cmstoolkit.user: ~1 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id_user`, `id_login`, `name_user`, `email_user`, `avatar_user`) VALUES
	(1, 2, 'User', 'user@example.com', '');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
