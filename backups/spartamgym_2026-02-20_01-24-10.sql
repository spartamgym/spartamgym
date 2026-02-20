/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.15-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: spartamgym
-- ------------------------------------------------------
-- Server version	10.11.15-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES
(1,'8084495','2025-11-06 22:11:48','2026-02-11 16:33:11',1),
(2,'37838184','2026-02-04 20:47:00','2026-02-11 16:32:22',1),
(3,'91474229','2026-02-04 20:57:04','2026-02-11 16:32:55',1),
(4,'1043138004','2026-02-12 13:32:00','2026-02-12 13:32:00',1),
(5,'37544435','2026-02-12 13:32:18','2026-02-12 13:32:18',1),
(6,'1193106990','2026-02-12 13:32:42','2026-02-12 13:32:42',1),
(7,'1005330705','2026-02-12 13:33:34','2026-02-12 13:33:34',1),
(8,'1104184562','2026-02-12 13:34:13','2026-02-12 13:34:13',1),
(9,'11021383889','2026-02-12 13:34:39','2026-02-12 13:34:39',1),
(10,'1102367506','2026-02-12 13:35:06','2026-02-12 13:35:06',1),
(11,'63492745','2026-02-12 13:35:33','2026-02-12 13:35:33',1),
(12,'1052087870','2026-02-12 13:35:52','2026-02-12 13:35:52',1),
(13,'1096949901','2026-02-12 13:36:21','2026-02-12 13:36:21',1),
(14,'63323929','2026-02-12 13:38:04','2026-02-12 13:38:04',1),
(15,'1098817304','2026-02-12 13:38:20','2026-02-12 13:38:20',1),
(16,'1809671','2026-02-12 13:38:43','2026-02-12 13:38:43',1),
(17,'1829300','2026-02-12 13:39:04','2026-02-12 13:39:04',1),
(18,'1098260298','2026-02-12 13:39:20','2026-02-12 13:39:20',1),
(19,'1101623366','2026-02-12 13:39:43','2026-02-12 13:39:43',1),
(20,'1097638015','2026-02-12 13:40:01','2026-02-12 13:40:01',1),
(21,'1096618341','2026-02-12 13:42:35','2026-02-12 13:42:35',1),
(22,'1098738580','2026-02-12 13:42:52','2026-02-12 13:42:52',1),
(23,'1098357105','2026-02-12 13:43:10','2026-02-12 13:43:10',1),
(24,'1098357106','2026-02-12 13:44:03','2026-02-12 13:44:03',1),
(25,'1095794616','2026-02-12 13:44:16','2026-02-12 13:44:16',1),
(26,'1095810855','2026-02-12 13:44:31','2026-02-12 13:44:31',1),
(27,'1005234630','2026-02-12 13:45:08','2026-02-12 13:45:08',1),
(28,'1095918883','2026-02-12 13:45:35','2026-02-12 13:45:35',1),
(29,'1270513','2026-02-12 13:45:48','2026-02-12 13:45:48',1),
(30,'1005485568','2026-02-12 13:46:06','2026-02-12 13:46:06',1),
(31,'6455843','2026-02-12 13:46:27','2026-02-12 13:46:27',1),
(32,'1101520387','2026-02-12 13:46:54','2026-02-12 13:46:54',1),
(33,'1007406922','2026-02-12 13:47:13','2026-02-12 13:47:13',1),
(34,'1098662115','2026-02-12 13:47:37','2026-02-12 13:47:37',1),
(35,'1102358679','2026-02-12 13:47:59','2026-02-12 13:47:59',1),
(36,'36455951','2026-02-12 13:48:09','2026-02-12 13:48:09',1),
(37,'1098705818','2026-02-12 13:48:40','2026-02-12 13:48:40',1),
(38,'1005188325','2026-02-12 13:51:26','2026-02-12 13:51:26',1),
(39,'1005540365','2026-02-12 13:51:41','2026-02-12 13:51:41',1),
(40,'26235462','2026-02-12 13:51:57','2026-02-12 13:51:57',1),
(41,'1005447635','2026-02-12 13:52:28','2026-02-12 13:52:28',1),
(42,'1192743234','2026-02-12 13:52:53','2026-02-12 13:52:53',1),
(43,'1017153219','2026-02-12 14:15:50','2026-02-12 14:15:50',1),
(44,'1102360022','2026-02-12 14:15:59','2026-02-12 14:15:59',1),
(45,'123456','2026-02-12 14:56:25','2026-02-12 14:56:25',1),
(46,'123457','2026-02-12 14:56:33','2026-02-12 14:56:33',1),
(47,'63447442','2026-02-12 14:56:50','2026-02-12 14:56:50',1),
(48,'1097498961','2026-02-12 14:57:31','2026-02-12 14:57:31',1),
(49,'33466083','2026-02-12 14:57:41','2026-02-12 14:57:41',1),
(50,'1100950601','2026-02-12 14:57:52','2026-02-12 14:57:52',1),
(51,'1092731717','2026-02-12 15:25:25','2026-02-12 15:25:25',1),
(52,'3125488273','2026-02-17 12:08:35','2026-02-17 12:08:35',1),
(53,'1102377170','2026-02-17 12:19:09','2026-02-17 12:19:09',1),
(54,'1102354868','2026-02-17 12:19:22','2026-02-17 12:19:22',1),
(55,'1102368144','2026-02-17 12:19:46','2026-02-17 12:19:46',1),
(56,'1102388299','2026-02-18 01:00:59','2026-02-18 01:00:59',1),
(57,'1018477445','2026-02-19 22:50:20','2026-02-19 22:50:20',1);
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cola_cards`
--

DROP TABLE IF EXISTS `cola_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cola_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `ingreso` tinyint(1) NOT NULL,
  `verificado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF3D7E17DB38439E` (`usuario_id`),
  CONSTRAINT `FK_CF3D7E17DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cola_cards`
--

LOCK TABLES `cola_cards` WRITE;
/*!40000 ALTER TABLE `cola_cards` DISABLE KEYS */;
INSERT INTO `cola_cards` VALUES
(2,3,'12345','2026-01-27 13:26:38','2026-02-20 03:00:30',1,0,1),
(3,2,'123456','2026-02-04 20:55:32','2026-02-20 03:08:06',1,0,1),
(4,1,'123457','2026-02-04 20:59:37','2026-02-20 03:51:53',1,0,1),
(5,3,'8084495','2026-02-20 03:00:30','2026-02-20 03:51:51',1,0,1),
(6,2,'37838184','2026-02-20 03:08:06','2026-02-20 03:08:06',1,0,1),
(7,43,'1043138004','2026-02-20 03:08:21','2026-02-20 03:08:21',1,0,1),
(8,2,'37838184','2026-02-20 03:32:38','2026-02-20 03:32:44',1,0,1),
(9,2,'37838184','2026-02-20 03:33:07','2026-02-20 03:33:08',1,0,1),
(11,3,'8084495','2026-02-20 05:34:09','2026-02-20 05:34:11',1,0,1),
(12,43,'1043138004','2026-02-20 05:48:45','2026-02-20 05:48:48',1,0,1),
(13,3,'8084495','2026-02-20 00:52:43','2026-02-20 00:52:47',1,0,1),
(14,9,'123457','2026-02-20 00:52:43','2026-02-20 00:52:57',1,0,1);
/*!40000 ALTER TABLE `cola_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dato_fisico`
--

DROP TABLE IF EXISTS `dato_fisico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dato_fisico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `cintura` int(11) DEFAULT NULL,
  `gluteos` int(11) DEFAULT NULL,
  `brazo` int(11) DEFAULT NULL,
  `pecho` int(11) DEFAULT NULL,
  `pierna` int(11) DEFAULT NULL,
  `pantorrilla` int(11) DEFAULT NULL,
  `altura` double DEFAULT NULL,
  `imc` double DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2368C083DB38439E` (`usuario_id`),
  CONSTRAINT `FK_2368C083DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dato_fisico`
--

LOCK TABLES `dato_fisico` WRITE;
/*!40000 ALTER TABLE `dato_fisico` DISABLE KEYS */;
INSERT INTO `dato_fisico` VALUES
(1,5,1,12,1,1,1,1,1,1,1,'#FF0000','2025-12-06 22:43:22','2025-12-06 22:44:26',1),
(2,3,64,1,1,1,1,1,1,1,1,'#FF0000','2025-12-06 22:43:59','2025-12-06 22:45:21',1),
(3,8,58,77,98,29,59,55,34,1,22.8,'#FF0000','2026-01-12 23:22:10','2026-01-12 23:22:10',1),
(4,9,55,73,103,26,82,54,36,1,21.4,'#FF0000','2026-01-13 00:43:11','2026-01-13 00:43:11',1),
(5,1,1,1,1,1,1,1,11,1,2,'#0EA5E9','2026-02-20 04:03:39','2026-02-20 04:29:20',1);
/*!40000 ALTER TABLE `dato_fisico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20260220010000','2026-02-20 02:41:45',112),
('DoctrineMigrations\\Version20260220013000','2026-02-20 02:42:54',38),
('DoctrineMigrations\\Version20260220020000','2026-02-20 02:57:34',22),
('DoctrineMigrations\\Version20260220021000','2026-02-20 02:58:05',18),
('DoctrineMigrations\\Version20260220022000','2026-02-20 02:58:45',30),
('DoctrineMigrations\\Version20260220030000','2026-02-20 03:21:16',70),
('DoctrineMigrations\\Version20260220100000','2026-02-20 03:48:13',41),
('DoctrineMigrations\\Version20260220110000','2026-02-20 03:54:26',9),
('DoctrineMigrations\\Version20260220120000','2026-02-20 05:07:40',18),
('DoctrineMigrations\\Version20260220130000','2026-02-20 05:21:17',49);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ia_solicitud_log`
--

DROP TABLE IF EXISTS `ia_solicitud_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ia_solicitud_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nombre` varchar(255) DEFAULT NULL,
  `usuario_cedula` varchar(255) DEFAULT NULL,
  `estado` varchar(40) NOT NULL,
  `payload_json` longtext DEFAULT NULL,
  `response_hash` varchar(64) DEFAULT NULL,
  `error_mensaje` longtext DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ia_solicitud_log`
--

LOCK TABLES `ia_solicitud_log` WRITE;
/*!40000 ALTER TABLE `ia_solicitud_log` DISABLE KEYS */;
INSERT INTO `ia_solicitud_log` VALUES
(1,'Audit Test','999','error_empty','{\"fullName\":\"Audit Test\",\"cedula\":\"999\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":70,\"hasDisabilities\":false}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 02:59:00'),
(2,'Prueba QA','123','error_empty','{\"fullName\":\"Prueba QA\",\"cedula\":\"123\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":72,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:33'),
(3,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:40'),
(4,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:42'),
(5,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:44'),
(6,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:46'),
(7,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:48'),
(8,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:50'),
(9,'Rate Test','555','error_empty','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:07:52'),
(10,'Rate Test','555','error_rate_limit','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'Límite de solicitudes IA por IP excedido.','172.22.0.1','2026-02-20 03:07:52'),
(11,'Rate Test','555','error_rate_limit','{\"fullName\":\"Rate Test\",\"cedula\":\"555\",\"availableDays\":3,\"timePerSessionMin\":45,\"weight\":70,\"currentLevel\":\"intermedio\"}',NULL,'Límite de solicitudes IA por IP excedido.','172.22.0.1','2026-02-20 03:07:52'),
(12,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:10:50'),
(13,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:11:02'),
(14,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:11:14'),
(15,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:15:06'),
(16,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:21:58'),
(17,'Isa','37838184','error_limite_mensual','{\"fullName\":\"Isa\",\"cedula\":\"37838184\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":70}',NULL,'Se alcanzó el máximo de rutinas por mes.','172.22.0.1','2026-02-20 03:23:01'),
(18,'Isa','37838184','error_payload','{\"fullName\":\"Isa\",\"cedula\":\"37838184\"}',NULL,'Falta el campo obligatorio \"availableDays\".','172.22.0.1','2026-02-20 03:24:02'),
(19,'Isa','37838184','error_empty','{\"fullName\":\"Isa\",\"cedula\":\"37838184\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":70,\"hasDisabilities\":false}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:24:18'),
(20,'Prueba Endpoint','37838184','error_empty','{\"fullName\":\"Prueba Endpoint\",\"cedula\":\"37838184\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":70,\"hasDisabilities\":false,\"disabilityDescription\":false,\"additionalInfo\":\"objetivo prueba\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:25:55'),
(21,'Nikol Cruces','91474229','error_empty','{\"fullName\":\"Nikol Cruces\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":65,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"91474229\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:29:16'),
(22,'Daysa Meza','10868547','error_empty','{\"fullName\":\"Daysa Meza\",\"availableDays\":3,\"currentLevel\":\"intermedio\",\"timePerSessionMin\":45,\"weight\":64,\"disabilityDescription\":false,\"additionalInfo\":false,\"hasDisabilities\":false,\"cedula\":\"10868547\"}',NULL,'El servicio IA respondió vacío.','172.22.0.1','2026-02-20 03:36:42');
/*!40000 ALTER TABLE `ia_solicitud_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan`
--

DROP TABLE IF EXISTS `plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `detalle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalle`)),
  `max_beneficiarios` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan`
--

LOCK TABLES `plan` WRITE;
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;
INSERT INTO `plan` VALUES
(1,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',1),
(2,'Plan 2X1',130000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',2),
(3,'Plan trimestral',180000,30,'[\"Acceso a las instalaciones del gimnasio\",\"sin limite de tiempo\",\"entrenamiento en rutinas o rutinas generadas autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',1),
(4,'Personal Training Básico',270000,30,'[\"Acceso a las instalaciones del gimnasio\",\"sin limite de tiempo\",\"horario\",\"disposici\\u00f3n a tiempo completo de la rutina con el entrenador\",\"dispone de rutinas personalizadas.\"]',1),
(5,'Personal Training 2X1',330000,60,'[\"Acceso a las instalaciones del gimnasio\",\"sin limite de tiempo\",\"horario\",\"disposici\\u00f3n a tiempo completo de la rutina con el entrenador por 2 meses\",\"tambi\\u00e9n dispone de rutinas personalizadas.\"]',2),
(6,'Plan por día',8000,1,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento y seguimiento de rutina\"]',1),
(7,'Plan por dia + personal training',20000,1,'[\"Acceso a las instalaciones del  gimnasio sin limite de tiempo\",\"se da el entrenamiento por parte del personal training m\\u00e1ximo 2 horas\"]',1),
(8,'Plan 2X1',65000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',2),
(9,'Plan 3X1',60000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',3),
(10,'Plan Básico especial ',50000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',1),
(11,'Plan Semanal',20000,7,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',1),
(12,'Plan quincenal',40000,15,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]',1);
/*!40000 ALTER TABLE `plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_usuario`
--

DROP TABLE IF EXISTS `plan_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `contabiliza_ingreso` tinyint(1) NOT NULL DEFAULT 1,
  `grupo_compartido` varchar(64) DEFAULT NULL,
  `orden_beneficiario` int(11) NOT NULL DEFAULT 1,
  `total_beneficiarios_grupo` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `IDX_9CDAF855DB38439E` (`usuario_id`),
  CONSTRAINT `FK_9CDAF855DB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_usuario`
--

LOCK TABLES `plan_usuario` WRITE;
/*!40000 ALTER TABLE `plan_usuario` DISABLE KEYS */;
INSERT INTO `plan_usuario` VALUES
(1,1,'2025-10-28 21:44:48','2025-11-28 00:00:00',1,'Plan Básico',80000,30,'[\"test\"]','2025-11-06 22:14:44','2026-02-20 04:48:38',1,1,NULL,1,1),
(2,3,'2026-01-26 20:56:19','2026-02-25 20:56:19',1,'Plan Básico',70000,30,'[\"test\"]','2026-01-26 20:56:19','2026-01-26 20:56:34',1,1,NULL,1,1),
(56,51,'2026-02-14 13:16:02','2026-02-21 13:16:02',0,'Plan Semanal',20000,7,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:16:02','2026-02-14 13:16:02',1,1,NULL,1,1),
(57,42,'2026-02-14 13:17:39','2026-04-15 13:17:39',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:17:39','2026-02-14 13:17:39',1,1,NULL,1,1),
(58,43,'2026-02-14 13:17:52','2026-04-15 13:17:52',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:17:52','2026-02-14 13:17:52',1,1,NULL,1,1),
(59,22,'2026-02-14 13:18:24','2026-03-16 13:18:24',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:18:24','2026-02-14 13:18:24',1,1,NULL,1,1),
(60,11,'2026-02-14 13:18:36','2026-03-16 13:18:36',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:18:36','2026-02-14 13:18:36',1,1,NULL,1,1),
(61,23,'2026-02-14 13:19:09','2026-03-16 13:19:09',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:19:09','2026-02-14 13:19:09',1,1,NULL,1,1),
(62,24,'2026-02-14 13:19:32','2026-03-16 13:19:32',0,'Personal Training Básico',270000,30,'[\"Acceso a las instalaciones del gimnasio\",\"sin limite de tiempo\",\"horario\",\"disposici\\u00f3n a tiempo completo de la rutina con el entrenador\",\"dispone de rutinas personalizadas.\"]','2026-02-14 13:19:32','2026-02-14 13:19:32',1,1,NULL,1,1),
(63,25,'2026-02-14 13:20:09','2026-03-16 13:20:09',0,'Plan 2X1',65000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:20:09','2026-02-14 13:20:09',1,1,NULL,1,1),
(64,26,'2026-02-14 13:20:27','2026-03-16 13:20:27',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:20:27','2026-02-14 13:20:27',1,1,NULL,1,1),
(65,27,'2026-02-14 13:21:05','2026-03-16 13:21:05',0,'Plan 3X1',60000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:21:05','2026-02-14 13:21:05',1,1,NULL,1,1),
(66,28,'2026-02-14 13:21:26','2026-03-16 13:21:26',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:21:26','2026-02-14 13:21:26',1,1,NULL,1,1),
(67,29,'2026-02-14 13:21:40','2026-04-15 13:21:40',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:21:40','2026-02-14 13:21:40',1,1,NULL,1,1),
(68,30,'2026-02-14 13:21:51','2026-04-15 13:21:51',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:21:51','2026-02-14 13:21:51',1,1,NULL,1,1),
(69,31,'2026-02-14 13:22:05','2026-04-15 13:22:05',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:22:05','2026-02-14 13:22:05',1,1,NULL,1,1),
(70,33,'2026-02-14 13:22:19','2026-03-16 13:22:19',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:22:19','2026-02-14 13:22:19',1,1,NULL,1,1),
(71,34,'2026-02-14 13:22:40','2026-03-16 13:22:40',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:22:40','2026-02-14 13:22:40',1,1,NULL,1,1),
(72,35,'2026-02-14 13:22:53','2026-03-16 13:22:53',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:22:53','2026-02-14 13:22:53',1,1,NULL,1,1),
(73,36,'2026-02-14 13:23:13','2026-05-15 13:23:13',0,'Plan trimestral',180000,90,'[\"Acceso a las instalaciones del gimnasio\",\"sin limite de tiempo\",\"entrenamiento en rutinas o rutinas generadas autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:23:13','2026-02-14 13:23:13',1,1,NULL,1,1),
(74,37,'2026-02-14 13:24:23','2026-03-16 13:24:23',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:24:23','2026-02-14 13:24:23',1,1,NULL,1,1),
(75,38,'2026-02-14 13:25:10','2026-04-15 13:25:10',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:25:10','2026-02-14 13:25:10',1,1,NULL,1,1),
(76,39,'2026-02-14 13:25:21','2026-04-15 13:25:21',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:25:21','2026-02-14 13:25:21',1,1,NULL,1,1),
(77,41,'2026-02-14 13:25:51','2026-03-16 13:25:51',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:25:51','2026-02-14 13:25:51',1,1,NULL,1,1),
(78,40,'2026-02-14 13:39:33','2026-04-15 13:39:33',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:39:33','2026-02-14 13:39:33',1,1,NULL,1,1),
(79,8,'2026-02-14 13:40:18','2026-04-15 13:40:18',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:40:18','2026-02-14 13:40:18',1,1,NULL,1,1),
(80,9,'2026-02-14 13:40:31','2026-04-15 13:40:31',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:40:31','2026-02-14 13:40:31',1,1,NULL,1,1),
(81,6,'2026-02-14 13:40:49','2026-03-16 13:40:49',0,'Plan Básico especial ',50000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:40:49','2026-02-14 13:40:49',1,1,NULL,1,1),
(82,7,'2026-02-14 13:41:07','2026-03-16 13:41:07',0,'Plan 3X1',60000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:41:07','2026-02-14 13:41:07',1,1,NULL,1,1),
(83,20,'2026-02-14 13:41:35','2026-03-16 13:41:35',0,'Plan Básico especial ',50000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:41:35','2026-02-14 13:41:35',1,1,NULL,1,1),
(84,10,'2026-02-14 13:44:36','2026-03-16 13:44:36',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:44:36','2026-02-14 13:44:36',1,1,NULL,1,1),
(85,4,'2026-02-14 13:48:48','2026-03-01 13:48:48',0,'Plan quincenal',40000,15,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-14 13:48:48','2026-02-14 13:48:48',1,1,NULL,1,1),
(86,52,'2026-02-17 12:09:12','2026-03-19 12:09:12',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-17 12:09:12','2026-02-20 01:02:19',1,1,NULL,1,1),
(87,55,'2026-02-17 12:22:11','2026-03-19 12:22:11',0,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-17 12:22:11','2026-02-17 12:22:11',1,1,NULL,1,1),
(88,52,'2026-02-17 12:38:09','2026-03-19 12:38:09',1,'Plan Básico',70000,30,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-17 12:38:09','2026-02-20 01:02:19',1,1,NULL,1,1),
(89,51,'2026-02-17 12:40:24','2026-02-24 12:40:24',0,'Plan Semanal',20000,7,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-17 12:40:24','2026-02-17 12:40:24',1,1,NULL,1,1),
(90,51,'2026-02-17 13:21:35','2026-02-24 13:21:35',0,'Plan Semanal',20000,7,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-17 13:21:35','2026-02-17 13:22:02',1,1,NULL,1,1),
(91,56,'2026-02-18 01:01:39','2026-04-19 01:01:39',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-18 01:01:39','2026-02-18 01:01:39',1,1,NULL,1,1),
(92,54,'2026-02-19 23:08:18','2026-04-20 23:08:18',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-19 23:08:18','2026-02-19 23:08:18',1,1,NULL,1,1),
(93,53,'2026-02-19 23:08:39','2026-04-20 23:08:39',0,'Plan 2X1',130000,60,'[\"Acceso a las instalaciones del gimnasio sin limite de tiempo\",\"entrenamiento de rutinas\",\"o se entrega rutina generada autom\\u00e1ticamente al inscribirse\",\"horarios accesibles.\"]','2026-02-19 23:08:39','2026-02-19 23:08:39',1,1,NULL,1,1);
/*!40000 ALTER TABLE `plan_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rutina_generada`
--

DROP TABLE IF EXISTS `rutina_generada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rutina_generada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `usuario_nombre` varchar(255) DEFAULT NULL,
  `usuario_cedula` varchar(64) NOT NULL,
  `payload_json` longtext NOT NULL,
  `contenido_texto` longtext NOT NULL,
  `contenido_html` longtext NOT NULL,
  `response_hash` varchar(64) DEFAULT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `pdf_filename` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_RUTINA_CEDULA` (`usuario_cedula`),
  KEY `IDX_RUTINA_CREATED` (`created_at`),
  KEY `IDX_RUTINA_USUARIO` (`usuario_id`),
  CONSTRAINT `FK_RUTINA_USUARIO` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rutina_generada`
--

LOCK TABLES `rutina_generada` WRITE;
/*!40000 ALTER TABLE `rutina_generada` DISABLE KEYS */;
/*!40000 ALTER TABLE `rutina_generada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2265B05D4ACC9A20` (`card_id`),
  CONSTRAINT `FK_2265B05D4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES
(1,3,'Nikol Cruces','91474229','3138120191','Barrio Villa Josefina','1999-11-06 00:00:00','F','sisben','nicolcruces1@gmail.com','img/033ae88a5c1eb89ecd383efdf35817a8.png','2025-11-06 22:11:34','2026-02-20 05:11:44',1),
(2,2,'Isa','37838184','3154508495','Barrio Villa Josefina','1957-07-08 00:00:00','F','SISBEN','isavenezuela@gmail.com','img/profile-img.jpeg','2025-11-21 22:07:00','2026-02-04 20:55:21',1),
(3,1,'Daysa Meza','10868547','3045315668','Alto de las Piñas','1972-04-10 00:00:00','F','sanita','daysamezal@gmail.com','img/profile-img.jpeg','2025-11-21 22:17:51','2026-02-04 20:52:09',1),
(4,41,'Luisa Fernanda Chavarro','1005447635','3177112173','Eden','1999-09-23 00:00:00','F','Salud total','fernandaojeda236@gmail.com','img/profile-img.jpeg','2025-12-02 00:04:04','2026-02-14 13:48:01',1),
(5,47,'Olga Lipez','63447442','3026141473','Barrio Villa Valentina','1972-10-20 00:00:00','F','Salud Total','olgalipez122@gmail.com','img/profile-img.jpeg','2025-12-02 13:03:34','2026-02-12 14:58:28',1),
(6,48,'Alejandra ','1097498961','3219865726','Villa Valentina I','2008-05-03 00:00:00','F','Salud Total','malejap1303@gmail.com','img/profile-img.jpeg','2025-12-02 13:48:26','2026-02-12 14:58:40',1),
(7,49,'Luis Daniel ','33466083','3134209763','Villa Valentina I','2006-06-21 00:00:00','M','Sisven','luisdanielbello888@gmail.com','img/profile-img.jpeg','2025-12-02 13:50:13','2026-02-12 14:58:55',1),
(8,45,'Vanesa Gualdron','123456','3138386207','Senderos del Valle ','2005-08-27 00:00:00','F','Sanitas','vanesagualdron139@gmail.com','img/profile-img.jpeg','2025-12-02 13:56:41','2026-02-12 14:58:06',1),
(9,46,'Marcela Gualdron','1234567','3138386207','Senderos del Valle','2003-07-17 00:00:00','F','Sanitas','vanesagualdron139@gmail.com','img/profile-img.jpeg','2025-12-02 13:58:13','2026-02-12 14:58:15',1),
(10,42,'Juli Andrea Peña Arenas','1192743234','3152998616','Barrio Villa Valentina','2002-11-25 00:00:00','F','Nueva EPS','daysamezal@gmail.com','img/profile-img.jpeg','2025-12-03 12:43:52','2026-02-12 14:33:05',1),
(11,7,'Frank Toloza','1005330705','3235754745','Barrio Villa Valentina','2003-02-23 00:00:00','M','Sanitas','franktoloza0223@gmail.com','img/profile-img.jpeg','2025-12-04 15:30:17','2026-02-12 13:57:23',1),
(12,40,'David Trejo','26235462','3212744723','villa Valentina','1996-06-19 00:00:00','M','Nueva EPS','davidjosetrejo23@gmail.com','img/profile-img.jpeg','2026-01-06 00:07:51','2026-02-12 14:32:15',1),
(13,38,'Mariluz Hernandez ','1005188325','3112264080','Villa Josefina','2000-10-31 00:00:00','F','Sanitas','mariluzhernandezcorzo@gmail.com','img/profile-img.jpeg','2026-01-06 00:38:20','2026-02-12 14:31:15',1),
(14,50,'Sergio Contreras','1100950601','3334309550','Villa Josefina 227301','1987-03-28 00:00:00','M','Sura','contacto@sergiocmgmail.com','img/profile-img.jpeg','2026-01-06 00:51:53','2026-02-12 14:59:03',1),
(15,35,'Lina Torres','1102358679','3011263689','Barrio Villa Josefina ','1989-01-15 00:00:00','F','Nva, EPS','linatatianatorressanchez@gmail.com','img/profile-img.jpeg','2026-01-13 01:31:51','2026-02-12 14:29:53',1),
(16,36,'Blanca Ariza','36455951','3151414556','Barrio Villa Josefina ','1961-12-21 00:00:00','F','Famisanar','rafaelsolano040@gmail.com','img/profile-img.jpeg','2026-01-13 01:34:29','2026-02-12 14:30:05',1),
(17,32,'Luzmary Barajas','1101520387','3172621363','Barrio Villa Josefina ','1988-11-28 00:00:00','F','Salud total','maryluz_28@hotmail.com','img/profile-img.jpeg','2026-01-30 23:47:55','2026-02-12 14:28:01',1),
(18,31,'Mariangel','6455843','3218552045','Eden','2007-10-18 00:00:00','F','Sanitas','perezmariangel520@gmail.com','img/profile-img.jpeg','2026-01-30 23:57:26','2026-02-12 14:23:21',1),
(19,30,'Angie Vargas ','1005485568','3115600795','Barrio Villa Josefina ','2000-06-13 00:00:00','F','Coosalud','angievargasjerez@gmail.com','img/profile-img.jpeg','2026-01-31 00:05:08','2026-02-12 14:23:00',1),
(20,27,'Juan Diego Biancha Garces ','1005234630','3181065467','Barrio Villa Valentina','2001-11-23 00:00:00','M','EPS Sura','juanbianchee@gmail.com','img/profile-img.jpeg','2026-01-31 00:28:55','2026-02-12 14:21:15',1),
(21,29,'Samuel Orellana','1270513','3144671869','Eden','2004-02-10 00:00:00','M','Contributivo','orellanasamuelcanelones@gmail.com','img/profile-img.jpeg','2026-02-01 00:01:11','2026-02-12 14:22:32',1),
(22,6,'Yury Serrano Pabon','1193106990','3114127073','Barrio Villa Josefina ','1990-04-03 00:00:00','F','Nva, EPS','yuriserranopabon@gmail.com','img/profile-img.jpeg','2026-02-11 13:24:58','2026-02-12 13:56:18',1),
(23,8,'Lizeth Romero','1104184562','3113468042','Barrio Villa Josefina ','1991-07-12 00:00:00','F','Nueva EPS','lizromero040@gmail.com','img/profile-img.jpeg','2026-02-11 14:17:30','2026-02-12 13:58:02',1),
(24,9,'Bryan Leal','11021383889','3023182188','Barrio Villa Valentina','1997-07-28 00:00:00','M','Nueva EPS','brypel784@gmail.com','img/profile-img.jpeg','2026-02-11 14:24:01','2026-02-12 13:58:28',1),
(25,10,'Johana Barrera ','1102367506','3184022062','Barrio Villa Josefina ','1991-09-07 00:00:00','F','Salud total','johanabarrerarestrepo@gmail.com','img/profile-img.jpeg','2026-02-11 14:26:24','2026-02-12 13:58:50',1),
(26,11,'Astrid Hernandez ','12345678','3226633461','Barrio Villa Josefina ','1973-01-21 00:00:00','F','Nueva EPS','astridhernandez8@gmail.com','img/profile-img.jpeg','2026-02-11 14:32:55','2026-02-12 13:59:18',1),
(27,12,'Karina Martinez','1052087870','3158018616','Barrio Villa Josefina 215','1999-09-27 00:00:00','F','Sanitas','karinamartinezjulio812@gmail.com','img/profile-img.jpeg','2026-02-11 14:34:53','2026-02-12 13:59:40',1),
(28,13,'Jorge Eliecer Moreno','1096949901','3213837210','Barrio Villa Valentina I','1989-04-03 00:00:00','M','Salud mia','alexander.spartamgym@gmail.com','img/profile-img.jpeg','2026-02-11 15:15:10','2026-02-12 13:59:57',1),
(29,15,'David Bueno','1098817304','3158363033','Nueva Colombia','1999-03-11 00:00:00','M','Salud mia ','davidbueno0311@gmail.com','img/profile-img.jpeg','2026-02-11 15:22:30','2026-02-12 14:00:39',1),
(30,14,'Isabel Bueno','63323929','3158363033','Nueva Colombia','1965-09-27 00:00:00','F','Salud mia','davidbueno0311@gmail.com','img/profile-img.jpeg','2026-02-11 15:24:54','2026-02-12 14:00:21',1),
(31,16,'Mailyn Quiñones','1809671','3144054088','Barrio Villa Valentina I','2001-01-12 00:00:00','F','Nueva EPS','rosmaryinfante127@gmail.com','img/profile-img.jpeg','2026-02-11 15:28:03','2026-02-12 14:01:07',1),
(32,17,'Norys Monasterio','1829300','3202195426','Barrio Villa Valentina I','1964-02-12 00:00:00','F','Nueva EPS','norismonasterio@gmail.com','img/profile-img.jpeg','2026-02-11 15:30:04','2026-02-12 14:01:21',1),
(33,18,'Paula Moura','1098260298','3229514234','Barrio Villa Valentina','2005-04-17 00:00:00','F','Sanitas','pacalderonpedraza@gmail.com','img/profile-img.jpeg','2026-02-11 15:31:55','2026-02-12 14:01:42',1),
(34,19,'Juan Diego Caballero','1101623366','3157356589','Barrio Villa Josefina ','2005-05-31 00:00:00','M','Nueva EPS','juanchocaballero0531@gmail.com','img/profile-img.jpeg','2026-02-11 15:33:40','2026-02-12 14:17:44',1),
(35,20,'Diana Carolina Gomez','1097638015','3222860501','Barrio Villa Josefina ','2003-12-26 00:00:00','F','Salud mia','gomezdiana2314@gmail.com','img/profile-img.jpeg','2026-02-11 15:35:53','2026-02-12 14:18:13',1),
(36,21,'Natalia Gonzalez','1096618341','3142211535','Barrio Villa Valentina','2003-07-06 00:00:00','F','Coopsalud','yanigonzalez654@gmail.com','img/profile-img.jpeg','2026-02-11 15:41:21','2026-02-12 14:18:37',1),
(37,22,'Laura Mejia','1098738580','3014892422','Barrio Villa Valentina','1993-05-17 00:00:00','F','Sura','laumarcelamejia@gmail.com','img/profile-img.jpeg','2026-02-11 15:43:22','2026-02-12 14:18:59',1),
(38,23,'Jorge Moreno','1098357105','3133871243','Barrio Villa Josefina ','2008-03-19 00:00:00','M','Salud total','jorgeaza188@gmail.com','img/profile-img.jpeg','2026-02-11 15:46:00','2026-02-12 14:19:19',1),
(39,24,'Andres Rojas ','1098357106','3133871243','Barrio Villa Josefina ','2006-11-24 00:00:00','M','Coosalud','andresr0182@gmail.com','img/profile-img.jpeg','2026-02-11 15:48:26','2026-02-12 14:19:37',1),
(40,26,'Elka Figueroa ','1095810855','3142709592','Barrio Villa Valentina I','1991-04-22 00:00:00','F','Nueva EPS','johanafigueroachaparro1991@gmail.com','img/profile-img.jpeg','2026-02-12 11:50:07','2026-02-12 14:20:25',1),
(41,25,'Reina Mora','1095794616','3138859053','La virgen','1981-01-06 00:00:00','F','Salud total','nikolmedina89@gmail.com','img/profile-img.jpeg','2026-02-12 11:54:35','2026-02-12 14:20:08',1),
(42,5,'Sandra Lopez','37544435','3174990210','Mata de caña','1979-01-30 00:00:00','F','Sisven','|sandragomezmendez2016@gmail.com','img/profile-img.jpeg','2026-02-12 12:20:55','2026-02-12 13:55:53',1),
(43,4,'Selenis Meza','1043138004','3114316014','Mata de caña','1987-09-25 00:00:00','F','Sisven','selenismeza25@gmail.com','img/profile-img.jpeg','2026-02-12 12:27:14','2026-02-12 13:55:30',1),
(44,33,'Luisa Fernanda Sepulveda','1007406922','3202690874','Barrio Villa Valentina','1996-01-01 00:00:00','F','Sisven','luisasepulveda1895@gmail.com','img/profile-img.jpeg','2026-02-12 13:12:38','2026-02-12 14:28:29',1),
(45,34,'Maria del Pilar Perez','1098662115','3152222117','Barrio Villa Valentina II','1986-06-26 00:00:00','F','Sisven','alexander.spartamgym@gmail.com','img/profile-img.jpeg','2026-02-12 13:14:17','2026-02-12 14:29:28',1),
(46,37,'Nolvis Ruiz','1098705818','311388559','Barrio Villa Valentina','1990-08-22 00:00:00','F','Nueva EPS','nolvisruizlaguna@gmail.com','img/profile-img.jpeg','2026-02-12 13:21:09','2026-02-12 14:30:24',1),
(47,39,'Paula Palacios','1005540365','3002776670','Refugio','2001-11-19 00:00:00','F','Nueva EPS','paulapalacios79@gmail.com','img/profile-img.jpeg','2026-02-12 13:26:44','2026-02-12 14:31:56',1),
(48,43,'Shirle Angelica Bohorquez','1017153219','3107367605','Barrio Villa Valentina','1987-04-21 00:00:00','F','Nueva EPS','shirleangelica@gmail.com','img/profile-img.jpeg','2026-02-12 14:12:49','2026-02-12 14:24:17',1),
(49,44,'Dina Vega','1102360022','3172189925','Barrio Villa Valentina','1987-08-25 00:00:00','F','Nueva EPS','shirleangelica@gmail.com','img/profile-img.jpeg','2026-02-12 14:14:50','2026-02-12 14:24:38',1),
(50,28,'yuly Paola JUlio','1095918883','3205767608','Vega de San Roque','1989-07-05 00:00:00','F','Salud total','jyulypao@hotmail.com','img/profile-img.jpeg','2026-02-12 14:40:56','2026-02-12 14:51:24',1),
(51,51,'Yeisy Guerrero','1092731717','3115905139','Nueva Colombia','1993-08-23 00:00:00','F','Ecosalud','yeisyguerrero@gmail.com','img/profile-img.jpeg','2026-02-12 15:19:05','2026-02-17 13:21:04',1),
(52,52,'Samy Vega','164576011','3125488273','La Vega de San Roque ','1988-10-23 00:00:00','F','Nueva EPS','leo2.weva@hotmail.com','img/profile-img.jpeg','2026-02-17 12:01:49','2026-02-17 12:08:47',1),
(53,53,'Diana Paola Vargas','1102377170','3238590303','Villa Josefina','1995-06-03 00:00:00','F','Nueva EPS','paolavargas0395@gmail.com','img/profile-img.jpeg','2026-02-17 12:11:32','2026-02-17 12:20:27',1),
(54,54,'Fabian Forero','1102354868','3238590303','Villa Josefina','1987-11-04 00:00:00','M','Nueva EPS','paolavargas0395@gmail.com','img/profile-img.jpeg','2026-02-17 12:13:09','2026-02-19 23:11:03',1),
(55,55,'Claudia Espinoza','1102368144','3143524957','La Vega de San Roque ','1992-01-27 00:00:00','F','Salud total','clami.2705@gmail.com','img/profile-img.jpeg','2026-02-17 12:17:18','2026-02-17 12:21:04',1),
(56,56,'Martin Velandia','1102388299','3104212159','Barrio Villa Valentina','2001-11-25 00:00:00','M','Sanidad Policia','martinvelandi15@gmail.com','img/profile-img.jpeg','2026-02-18 00:57:48','2026-02-18 01:01:13',1),
(57,57,'Sheymy Esparza','1018477445','3125417092','Barrio Villa Josefina ','1996-07-09 00:00:00','F','Nueva EPS','alexander.spartamgym@gmail.com','img/profile-img.jpeg','2026-02-19 22:46:08','2026-02-19 22:50:35',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_medida_estandar`
--

DROP TABLE IF EXISTS `usuario_medida_estandar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_medida_estandar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nombre_referencia` varchar(255) NOT NULL,
  `peso` double DEFAULT NULL,
  `cintura` double DEFAULT NULL,
  `gluteos` double DEFAULT NULL,
  `brazo` double DEFAULT NULL,
  `pecho` double DEFAULT NULL,
  `pierna` double DEFAULT NULL,
  `pantorrilla` double DEFAULT NULL,
  `altura` double DEFAULT NULL,
  `imc` double DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ABFBC876DB38439E` (`usuario_id`),
  CONSTRAINT `FK_UME_USUARIO` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_medida_estandar`
--

LOCK TABLES `usuario_medida_estandar` WRITE;
/*!40000 ALTER TABLE `usuario_medida_estandar` DISABLE KEYS */;
INSERT INTO `usuario_medida_estandar` VALUES
(1,3,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(2,2,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(3,1,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(4,43,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(5,42,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(6,22,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(7,11,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(8,23,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(9,24,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(10,25,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(11,26,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(12,27,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(13,28,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(14,30,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(15,29,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(16,31,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(17,32,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(18,33,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(19,34,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(20,35,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(21,36,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(22,37,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(23,38,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(24,39,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(25,41,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(26,40,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(27,20,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(28,50,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(29,21,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(30,19,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(31,18,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(32,17,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(33,44,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(34,45,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(35,15,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(36,16,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(37,46,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(38,13,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(39,47,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(40,12,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(41,4,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(42,10,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(43,48,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(44,49,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(45,8,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(46,9,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(47,5,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(48,6,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(49,7,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:20',0),
(50,14,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(51,51,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(52,52,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(53,53,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(54,54,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(55,55,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(56,56,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(57,57,'Referencia Inicial General',65,80,95,30,90,55,35,1.7,22.5,'2026-02-20 02:41:45','2026-02-20 05:21:21',0),
(58,1,'Objetivo automático (Mujer, 26 años)',9.3,18.6,24.4,7.2,21.6,13.4,14.6,1,9.3,'2026-02-20 05:21:20','2026-02-20 05:21:20',1),
(59,3,'Objetivo automático (Mujer, 53 años)',47.4,19.2,24.6,7.1,21.8,13.3,8.5,1,47.4,'2026-02-20 05:21:20','2026-02-20 05:21:20',1),
(60,5,'Objetivo automático (Mujer, 53 años)',9.6,25.8,24.6,7.1,21.8,13.3,8.5,1,9.6,'2026-02-20 05:21:20','2026-02-20 05:21:20',1),
(61,8,'Objetivo automático (Mujer, 20 años)',43.5,64.2,82.6,24,56.4,45.8,28.4,1,43.5,'2026-02-20 05:21:21','2026-02-20 05:21:21',1),
(62,9,'Objetivo automático (Mujer, 22 años)',41.7,61.8,85.6,22.2,70.2,45.2,29.6,1,41.7,'2026-02-20 05:21:21','2026-02-20 05:21:21',1);
/*!40000 ALTER TABLE `usuario_medida_estandar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'spartamgym'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-20  1:24:10
