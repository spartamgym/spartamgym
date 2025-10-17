# ************************************************************
# Antares - SQL Client
# Version 0.7.35
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: localhost (mariadb.org binary distribution 10.4.34)
# Database: spartamgym
# Generation time: 2025-10-17T07:29:48-05:00
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table card
# ------------------------------------------------------------

DROP TABLE IF EXISTS `card`;

CREATE TABLE `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;

INSERT INTO `card` (`id`, `code`, `create_at`, `update_at`, `active`) VALUES
	(1, "1234", NULL, NULL, NULL);

/*!40000 ALTER TABLE `card` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table cards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cards`;

CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;

INSERT INTO `cards` (`id`, `code`, `create_at`, `update_at`, `active`) VALUES
	(1, "1234", NULL, NULL, NULL),
	(2, "12345", NULL, NULL, NULL);

/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table datofisico
# ------------------------------------------------------------

DROP TABLE IF EXISTS `datofisico`;

CREATE TABLE `datofisico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL,
  `cintura` int(11) DEFAULT NULL,
  `gluteos` int(11) DEFAULT NULL,
  `brazo` int(11) DEFAULT NULL,
  `pecho` int(11) DEFAULT NULL,
  `pierna` int(11) DEFAULT NULL,
  `pantorrilla` int(11) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C2A4B8F6DB38439E` (`usuario_id`),
  CONSTRAINT `FK_C2A4B8F6DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `datofisico` WRITE;
/*!40000 ALTER TABLE `datofisico` DISABLE KEYS */;

INSERT INTO `datofisico` (`id`, `usuario_id`, `peso`, `cintura`, `gluteos`, `brazo`, `pecho`, `pierna`, `pantorrilla`, `create_at`, `update_at`, `active`) VALUES
	(1, 1, 56, 56, 56, 56, 56, 56, 56, "2024-10-20 18:59:28", "2024-10-20 18:59:28", 1),
	(2, 1, 67, 67, 67, 67, 67, 67, 67, "2024-10-20 18:59:28", "2024-10-20 18:59:28", 1);

/*!40000 ALTER TABLE `datofisico` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table usuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;

INSERT INTO `usuario` (`id`, `nombre`, `cedula`, `celular`, `direccion`, `fecha_nacimiento`, `eps`, `correo`, `code`, `img`, `create_at`, `update_at`, `active`) VALUES
	(1, "Daniel ", "123456", "1234567", "calle 48 c 44-18", "2024-10-20 18:59:28", "nueva eps", "nuevo@gmailcom", "1234", "img/profile-img.jpeg", "2024-10-20 18:59:28", "2024-10-20 18:59:28", NULL);

/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of views
# ------------------------------------------------------------

# Creating temporary tables to overcome VIEW dependency errors


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Dump completed on 2025-10-17T07:29:48-05:00
