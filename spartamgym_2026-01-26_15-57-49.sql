# ************************************************************
# Antares - SQL Client
# Version 0.7.35
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: 127.0.0.1 (mariadb.org binary distribution 10.4.34)
# Database: spartamgym
# Generation time: 2026-01-26T15:57:52-05:00
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
  `usuario` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;

INSERT INTO `card` (`id`, `code`, `usuario`, `create_at`, `update_at`, `active`) VALUES
	(1, "12345", "91474229", NULL, "2025-11-06 22:17:43", 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;

INSERT INTO `cards` (`id`, `code`, `create_at`, `update_at`, `active`) VALUES
	(1, "12345", "2025-11-06 22:11:48", "2025-11-06 22:11:48", 1);

/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table cola_cards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cola_cards`;

CREATE TABLE `cola_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF3D7E17DB38439E` (`usuario_id`),
  CONSTRAINT `FK_CF3D7E17DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `cola_cards` WRITE;
/*!40000 ALTER TABLE `cola_cards` DISABLE KEYS */;

INSERT INTO `cola_cards` (`id`, `usuario_id`, `code`, `create_at`, `update_at`, `active`) VALUES
	(1, 1, "12345", "2025-11-06 22:17:43", "2025-11-06 22:17:43", 1);

/*!40000 ALTER TABLE `cola_cards` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table dato_fisico
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dato_fisico`;

CREATE TABLE `dato_fisico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `peso` int(11) DEFAULT NULL,
  `cintura` int(11) DEFAULT NULL,
  `gluteos` int(11) DEFAULT NULL,
  `brazo` int(11) DEFAULT NULL,
  `pecho` int(11) DEFAULT NULL,
  `pierna` int(11) DEFAULT NULL,
  `pantorrilla` int(11) DEFAULT NULL,
  `altura` int(11) DEFAULT NULL,
  `imc` double DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2368C083DB38439E` (`usuario_id`),
  CONSTRAINT `FK_2368C083DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `dato_fisico` WRITE;
/*!40000 ALTER TABLE `dato_fisico` DISABLE KEYS */;

INSERT INTO `dato_fisico` (`id`, `usuario_id`, `peso`, `cintura`, `gluteos`, `brazo`, `pecho`, `pierna`, `pantorrilla`, `altura`, `imc`, `color`, `create_at`, `update_at`, `active`) VALUES
	(1, 5, 1, 12, 1, 1, 1, 1, 1, 1, 1, "#FF0000", "2025-12-06 22:43:22", "2025-12-06 22:44:26", 1),
	(2, 3, 64, 1, 1, 1, 1, 1, 1, 1, 1, "#FF0000", "2025-12-06 22:43:59", "2025-12-06 22:45:21", 1),
	(3, 8, 58, 77, 98, 29, 59, 55, 34, 1, 22.8, "#FF0000", "2026-01-12 23:22:10", "2026-01-12 23:22:10", 1),
	(4, 9, 55, 73, 103, 26, 82, 54, 36, 1, 21.4, "#FF0000", "2026-01-13 00:43:11", "2026-01-13 00:43:11", 1);

/*!40000 ALTER TABLE `dato_fisico` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table plan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plan`;

CREATE TABLE `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `detalle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalle`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `plan` WRITE;
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;

INSERT INTO `plan` (`id`, `nombre`, `precio`, `tiempo`, `detalle`) VALUES
	(1, "Plan Básico", 70000, 30, "[\"test\"]");

/*!40000 ALTER TABLE `plan` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table plan_usuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plan_usuario`;

CREATE TABLE `plan_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `predefinido` tinyint(1) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `detalle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalle`)),
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9CDAF855DB38439E` (`usuario_id`),
  CONSTRAINT `FK_9CDAF855DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `plan_usuario` WRITE;
/*!40000 ALTER TABLE `plan_usuario` DISABLE KEYS */;

INSERT INTO `plan_usuario` (`id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `predefinido`, `nombre`, `precio`, `tiempo`, `detalle`, `create_at`, `update_at`, `active`) VALUES
	(1, 1, "2025-10-28 21:44:48", "2025-11-28 00:00:00", 1, "Plan Básico", 80000, 30, "[\"test\"]", "2025-11-06 22:14:44", "2025-11-06 22:14:53", 1),
	(2, 3, "2026-01-26 20:56:19", "2026-02-25 20:56:19", 1, "Plan Básico", 70000, 30, "[\"test\"]", "2026-01-26 20:56:19", "2026-01-26 20:56:34", 1);

/*!40000 ALTER TABLE `plan_usuario` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table usuario
# ------------------------------------------------------------

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2265B05D4ACC9A20` (`card_id`),
  CONSTRAINT `FK_2265B05D4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;

INSERT INTO `usuario` (`id`, `card_id`, `nombre`, `cedula`, `celular`, `direccion`, `fecha_nacimiento`, `eps`, `correo`, `img`, `create_at`, `update_at`, `active`) VALUES
	(1, NULL, "Nikol Cruces", "91474229", "3138120191", "Barrio Villa Josefina ", "1999-11-06 00:00:00", "sisben", "nicolcruces1@gmail.com", "img/033ae88a5c1eb89ecd383efdf35817a8.png", "2025-11-06 22:11:34", "2025-12-06 22:44:08", 1),
	(2, NULL, "Isa", "37838184", "3154508495", "Barrio Villa Josefina", "1957-07-08 00:00:00", "SISBEN", "isavenezuela@gmail.com", "img/profile-img.jpeg", "2025-11-21 22:07:00", "2025-11-21 22:14:05", 1),
	(3, 1, "Daysa Meza", "10868547", "3045315668", "Alto de las Piñas", "1972-04-10 00:00:00", "sanita", "daysamezal@gmail.com", "img/profile-img.jpeg", "2025-11-21 22:17:51", "2026-01-26 20:55:21", 1),
	(4, NULL, "Luisa Fernanda Ojeda", "1005447635", "3177112173", "Eden", "1999-09-23 00:00:00", "Salud total", "fernandaojeda236@gmail.com", "img/profile-img.jpeg", "2025-12-02 00:04:04", "2025-12-02 00:12:43", 1),
	(5, NULL, "Olga Lipez", "63447442", "3026141473", "Barrio Villa Valentina", "1972-10-20 00:00:00", "Salud Total", "olgalipez122@gmail.com", "img/profile-img.jpeg", "2025-12-02 13:03:34", "2025-12-02 13:03:34", 1),
	(6, NULL, "Alejandra ", "1097498961", "3219865726", "Villa Valentina I", "2008-05-03 00:00:00", "Salud Total", "malejap1303@gmail.com", "img/profile-img.jpeg", "2025-12-02 13:48:26", "2025-12-02 13:48:26", 1),
	(7, NULL, "Luis Daniel ", "33466083", "3134209763", "Villa Valentina I", "2006-06-21 00:00:00", "Sisven", "luisdanielbello888@gmail.com", "img/profile-img.jpeg", "2025-12-02 13:50:13", "2025-12-02 13:50:13", 1),
	(8, NULL, "Vanesa Gualdron", "123456", "3138386207", "Senderos del Valle ", "2005-08-27 00:00:00", "Sanitas", "vanesagualdron139@gmail.com", "img/profile-img.jpeg", "2025-12-02 13:56:41", "2025-12-02 14:39:24", 1),
	(9, NULL, "Marcela Gualdron", "1234567", "3138386207", "Senderos del Valle", "2003-07-17 00:00:00", "Sanitas", "vanesagualdron139@gmail.com", "img/profile-img.jpeg", "2025-12-02 13:58:13", "2025-12-02 14:39:43", 1),
	(10, NULL, "Juli Andrea Peña Arenas", "1192743234", "3152998616", "Barrio Villa Valentina", "2002-11-25 00:00:00", "Nueva EPS", "daysamezal@gmail.com", "img/profile-img.jpeg", "2025-12-03 12:43:52", "2025-12-03 12:43:52", 1),
	(11, NULL, "Frank Toloza", "1005330705", "3235754745", "Barrio Villa Valentina", "2003-02-23 00:00:00", "Sanitas", "franktoloza0223@gmail.com", "img/profile-img.jpeg", "2025-12-04 15:30:17", "2025-12-04 15:30:17", 1),
	(12, NULL, "David Trejo", "26235462", "3212744723", "villa Valentina", "1996-06-19 00:00:00", "Nueva EPS", "davidjosetrejo23@gmail.com", "img/profile-img.jpeg", "2026-01-06 00:07:51", "2026-01-06 00:07:51", 1),
	(13, NULL, "Mariluz Hernandez ", "1005188325", "3112264080", "Villa Josefina", "2000-10-31 00:00:00", "Sanitas", "mariluzhernandezcorzo@gmail.com", "img/profile-img.jpeg", "2026-01-06 00:38:20", "2026-01-06 00:38:20", 1),
	(14, NULL, "Sergio Contreras", "1100950601", "3334309550", "Villa Josefina 227301", "1987-03-28 00:00:00", "Sura", "contacto@sergiocmgmail.com", "img/profile-img.jpeg", "2026-01-06 00:51:53", "2026-01-06 00:51:53", 1),
	(15, NULL, "Lina Torres", "1102358679", "3011263689", "Barrio Villa Josefina ", "1989-01-15 00:00:00", "Nva, EPS", "linatatianatorressanchez@gmail.com", "img/profile-img.jpeg", "2026-01-13 01:31:51", "2026-01-13 01:31:51", 1),
	(16, NULL, "Blanca Ariza", "36455951", "3151414556", "Barrio Villa Josefina ", "1961-12-21 00:00:00", "Famisanar", "rafaelsolano040@gmail.com", "img/profile-img.jpeg", "2026-01-13 01:34:29", "2026-01-13 01:34:29", 1);

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

# Dump completed on 2026-01-26T15:57:52-05:00
