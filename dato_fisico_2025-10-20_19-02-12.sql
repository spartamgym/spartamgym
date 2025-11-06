# ************************************************************
# Antares - SQL Client
# Version 0.7.35
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: localhost (mariadb.org binary distribution 10.4.34)
# Database: spartamgym
# Generation time: 2025-10-20T19:03:04-05:00
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table plan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plan`;

CREATE TABLE `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` varchar(255) DEFAULT NULL,
  `detalle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalle`)),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `plan` WRITE;
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;

INSERT INTO `plan` (`id`, `nombre`, `precio`, `tiempo`, `detalle`) VALUES
	(1, "Plan Básico", 8000, "1 mes", "[   \"Acceso completo al gimnasio\",   \"Uso de todas las máquinas y pesas\",   \"WiFi gratuito\",   \"Baños y vestier\",   \"Zona de estiramiento\",   \"Salón de rumba y cardio\" ]"),
	(2, "Plan Semestral", 480000, "6 meses", "[\n  \"Todo lo del plan básico\",\n  \"Todas las clases especializadas\",\n  \"Evaluación física inicial\",\n  \"Plan de entrenamiento básico\",\n  \"Asesoría previa\",\n  \"Descuentos en productos\"\n]\n"),
	(3, "Plan Anual", 700000, "1 año", "[   \"Acceso completo a todas las instalaciones\",   \"Todas las clases y servicios incluidos\",   \"Evaluación corporal completa\",   \"Plan de entrenamiento personalizado\",   \"Asesoría nutricional\",   \"Preparación para competencias\",   \"Beneficios exclusivos de Spartan Gym\",   \"Descuentos especiales en tienda\" ]");

/*!40000 ALTER TABLE `plan` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Dump completed on 2025-10-20T19:03:04-05:00
