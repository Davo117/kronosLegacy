-- MySQL dump 10.13  Distrib 5.7.42, for Linux (x86_64)
--
-- Host: localhost    Database: saturno
-- ------------------------------------------------------
-- Server version	5.7.42-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accesos`
--

DROP TABLE IF EXISTS `accesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdgmodulo` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `permiso` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1828 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accesos`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `accesos` WRITE;
/*!40000 ALTER TABLE `accesos` DISABLE KEYS */;
INSERT INTO `accesos` VALUES (1,'PF0','1','rwx'),(2,'PF0','2','rwx'),(3,'PF0','3','rwx'),(4,'PF0','4','rwx'),(5,'PF0','5','rwx'),(6,'PF0','6','rwx'),(7,'PF0','7','rwx'),(8,'PF0','8','rwx'),(9,'PF0','9','rwx'),(10,'PF0','32','rwx'),(11,'PF0','10','rwx'),(12,'PF0','11','rwx'),(13,'PF0','12','rwx'),(14,'PF0','13','rwx'),(15,'PF0','14','rwx'),(16,'PF0','15','rwx'),(17,'PF0','16','rwx'),(18,'PF0','17','rwx'),(19,'PF0','18','rwx'),(20,'PF0','19','rwx'),(21,'PF0','20','rwx'),(22,'PF0','21','rwx'),(23,'PF0','22','rwx'),(24,'PF0','23','rwx'),(25,'PF0','24','rwx'),(26,'PF0','25','rwx'),(27,'PF0','26','rwx'),(28,'PF0','27','rwx'),(29,'PF0','28','rwx'),(30,'PF0','29','rwx'),(31,'PF0','30','rwx'),(32,'PF0','31','rwx'),(33,'PF1','1','rwx'),(34,'PF1','2','rwx'),(35,'PF1','3','rwx'),(36,'PF1','4','rwx'),(37,'PF1','5','rwx'),(38,'PF1','6','rwx'),(39,'PF1','7','rwx'),(40,'PF1','8','rwx'),(41,'PF1','9','rwx'),(42,'PF1','32','rwx'),(43,'PF1','10','rwx'),(44,'PF1','11','rwx'),(45,'PF1','12','rwx'),(46,'PF1','13','rwx'),(47,'PF1','14','rwx'),(48,'PF1','15','rwx'),(49,'PF1','16','rwx'),(50,'PF1','17','rwx');
/*!40000 ALTER TABLE `accesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `am_proveedores`
--

DROP TABLE IF EXISTS `am_proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `am_proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `rfc` varchar(30) CHARACTER SET latin1 NOT NULL,
  `fechaAlta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `am_proveedores`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `am_proveedores` WRITE;
/*!40000 ALTER TABLE `am_proveedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `am_proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anillox`
--

DROP TABLE IF EXISTS `anillox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anillox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorAnillox` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `num_lineas` int(11) NOT NULL,
  `bcm` int(11) NOT NULL,
  `proceso` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificadorAnillox` (`identificadorAnillox`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anillox`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `anillox` WRITE;
/*!40000 ALTER TABLE `anillox` DISABLE KEYS */;
INSERT INTO `anillox` VALUES (5,'AN560',30,234,'impresion-flexografica',1);
/*!40000 ALTER TABLE `anillox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `baja_BS`
--

DROP TABLE IF EXISTS `baja_BS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `baja_BS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(40) CHARACTER SET latin1 NOT NULL,
  `tipo` varchar(40) CHARACTER SET latin1 NOT NULL,
  `longitud` double NOT NULL,
  `producto` varchar(70) CHARACTER SET latin1 NOT NULL,
  `unidades` double NOT NULL,
  `proceso` varchar(70) CHARACTER SET latin1 NOT NULL,
  `empleado` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `baja_BS`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `baja_BS` WRITE;
/*!40000 ALTER TABLE `baja_BS` DISABLE KEYS */;
/*!40000 ALTER TABLE `baja_BS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bandaseguridad`
--

DROP TABLE IF EXISTS `bandaseguridad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bandaseguridad` (
  `IDBanda` int(11) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBanda` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anchura` int(11) NOT NULL,
  `necesidad` float NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IDBanda`),
  UNIQUE KEY `identificador` (`identificador`),
  UNIQUE KEY `nombreBanda` (`nombreBanda`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bandaseguridad`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `bandaseguridad` WRITE;
/*!40000 ALTER TABLE `bandaseguridad` DISABLE KEYS */;
INSERT INTO `bandaseguridad` VALUES (1,'electroPura','BS electroPura',10,0,0),(2,'ePura','BS ePura',12,0,0),(3,'Cristal Gota','BS Cristal Gota',12,0,1),(4,'Santorini','BS Santorini',12,0,0),(5,'coffe01','bandacseguridadcofee',15,0,0),(6,'01BandaValle','BandaSegValle',12,0,0),(7,'Generico Gepp','BS Generico Gepp',12,0,1),(8,'Cristal Estrella','BS Cristal Estrella',12,0,1),(9,'Generico Gepp3','BS GenÃ©rico Gepp3',8,0,0),(10,'CIEL','BS Ciel',12,0,1),(12,'Generico','Holograma Generico',7,0,1),(13,'Generico BS','BS Generico',7,0,1),(14,'Gentilax','BS Gentilax',10,0,1),(15,'John Walker','BS John Walker',12,0,1),(16,'GEPP','TH Gepp',10,0,1),(17,'SBL Pharmaceuticals ','BS SBL',8,0,1);
/*!40000 ALTER TABLE `bandaseguridad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bandaspp`
--

DROP TABLE IF EXISTS `bandaspp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bandaspp` (
  `IdBSPP` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorBS` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `identificadorBSPP` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBSPP` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `anchuraLaminado` float NOT NULL,
  `sustrato` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `preEmbosado` int(1) NOT NULL DEFAULT '0',
  `repeticiones` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdBSPP`),
  UNIQUE KEY `identificadorBSPP` (`identificadorBSPP`),
  UNIQUE KEY `nombreBSPP` (`nombreBSPP`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bandaspp`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `bandaspp` WRITE;
/*!40000 ALTER TABLE `bandaspp` DISABLE KEYS */;
INSERT INTO `bandaspp` VALUES (1,'1','Electropura','Electropura Pre-Embozado640',160,'2',0,'',1),(2,'2','Epura Pre-Embozado1','Epura Pre-Embozado640',160,'5',0,'',1),(3,'2','ePura.PreE','Epura Pre-Embozado320',160,'4',0,'',0),(4,'3','Gota PreEmbozado4','Gota Pre-Embozado320',160,'8',0,'24',1),(5,'4','Santorini3','Santorini Pre- Embozado320',160,'9',0,'',1),(7,'6','01234','BandaSegValle',160,'8',0,'',1),(8,'7','Generico Gepp','Generico Gepp Embozado170',160,'21',0,'',1),(9,'8','Estrella Pre-Embosado4','Estrella Pre-Embozado320',160,'22',0,'24',1),(11,'9','Generico Gepp3','Generico Embozado 320',320,'23',0,'',1),(20,'10','Ciel','Ciel Pre-Embozado 320mm',320,'45',0,'24',1);
/*!40000 ALTER TABLE `bandaspp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloquesmateriaprima`
--

DROP TABLE IF EXISTS `bloquesmateriaprima`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloquesmateriaprima` (
  `idBloque` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorBloque` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreBloque` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sustrato` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `longitud` double NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idBloque`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloquesmateriaprima`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `bloquesmateriaprima` WRITE;
/*!40000 ALTER TABLE `bloquesmateriaprima` DISABLE KEYS */;
INSERT INTO `bloquesmateriaprima` VALUES (1,'PVC 450 mm IMPORTADO','PVC IMPORTADO','PVC termoencogible C40 E50/0 450mm',911,15720,1),(2,'OC00005.17  320 mm','O.C. 00005.17 320','Polyester embosado C20 e-pura 320mm',484,69700,1),(3,'PVC 400 mm IMPORTADO','PVC 400 mm CHINO','PVC termoencogible C40 E50/0 400mm',340,37332,1),(4,'OC00006.17 455 mm','O.C. 00006.17 455 mm','PTG transparente C37 455 mm',408,13200,1),(5,'OC 00017.320','O.C. 00017 320 mm','Polyester embosado C30 gota 320 mm',172,24000,1);
/*!40000 ALTER TABLE `bloquesmateriaprima` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dato` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `identifier` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES (1,'115',1),(41,'Comex',1);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `noElementos` int(11) NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `fechamov` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23596 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
INSERT INTO `caja` VALUES (1,'C1','14',36,18.000,'0143780001','20190517001',16.32,'2019-05-14 17:09:52','',3),(2,'C2','14',36,18.000,'0143780002','20190517001',16.3,'2019-05-14 17:25:26','',3),(3,'C1','30',36,18.000,'0303780003','20190517003',12.96,'2019-05-15 08:09:21','',3),(4,'C2','30',36,18.000,'0303780004','20190517003',12.83,'2019-05-15 08:13:05','',3),(5,'C3','30',36,18.000,'0303780005','20190517003',12.83,'2019-05-15 08:32:06','',3),(6,'C4','30',36,18.000,'0303780006','20190517003',12.68,'2019-05-15 08:47:57','',3),(7,'C5','30',36,18.000,'0303780007','20190517003',12.57,'2019-05-15 08:51:43','',3),(8,'C6','30',36,18.000,'0303780008','20190517003',12.84,'2019-05-15 08:54:55','',3),(9,'C7','30',36,18.000,'0303780009','20190517003',12.87,'2019-05-15 09:18:33','',3),(10,'C8','30',36,18.000,'0303780010','20190517003',12.78,'2019-05-15 09:31:09','',3),(11,'C9','30',36,18.000,'0303780011','20190517003',12.62,'2019-05-15 09:40:09','',3),(12,'C10','30',36,18.000,'0303780012','20190517003',12.84,'2019-05-15 09:44:59','',3),(13,'C11','30',36,18.000,'0303780013','20190517003',12.87,'2019-05-15 11:14:34','',3),(14,'C12','30',36,18.000,'0303780014','20190517003',12.89,'2019-05-15 11:17:07','',3),(15,'C13','30',36,18.000,'0303780015','20190517003',12.81,'2019-05-15 11:18:51','',3),(16,'C14','30',36,18.000,'0303780016','20190517003',12.84,'2019-05-15 11:20:30','',3),(17,'C15','30',36,18.000,'0303780017','20190517003',12.8,'2019-05-15 11:26:07','',3),(18,'C16','30',36,18.000,'0303780018','20190517003',12.85,'2019-05-15 12:58:46','',3),(19,'C17','30',36,18.000,'0303780019','20190517003',12.7,'2019-05-15 13:02:13','',3),(20,'C3','14',36,18.000,'0143780020','20190517001',12.83,'2019-05-15 13:10:16','',3),(21,'C4','14',36,18.000,'0143780021','20190517001',15.19,'2019-05-15 13:15:29','',3),(22,'C5','14',36,18.000,'0143780022','20190517001',15.88,'2019-05-15 13:18:53','',3),(23,'C6','14',36,18.000,'0143780023','20190517006',15.3,'2019-05-15 13:26:15','',3),(24,'C7','14',36,18.000,'0143780024','20190517006',15.69,'2019-05-15 13:30:36','',3),(25,'C8','14',5,2.500,'0143780025','20190515001',2.14,'2019-05-15 14:45:08','',3),(26,'C9','14',36,18.000,'0143780026','20190517006',12.62,'2019-05-15 15:32:33','',3),(27,'C10','14',36,18.000,'0143780027','20190517006',15.93,'2019-05-15 15:44:23','',3),(28,'C11','14',36,18.000,'0143780028','20190517006',16,'2019-05-15 15:46:27','',3),(29,'C18','30',36,18.000,'0303780029','20190517005',12.54,'2019-05-15 15:55:02','',3),(30,'C19','30',36,18.000,'0303780030','20190517005',12.97,'2019-05-15 16:26:12','',3),(31,'C20','30',36,18.000,'0303780031','20190517005',12.78,'2019-05-16 11:04:15','',3),(32,'C21','30',36,18.000,'0303780032','20190517005',12.77,'2019-05-16 11:06:34','',3),(33,'C22','30',36,18.000,'0303780033','20190517005',12.93,'2019-05-16 11:14:10','',3),(34,'C23','30',36,18.000,'0303780034','20190517005',12.61,'2019-05-16 11:23:21','',3),(35,'C12','14',36,18.000,'0143780035','20190517006',12.51,'2019-05-16 11:36:50','',3),(36,'C13','14',36,18.000,'0143780036','20190517006',14.04,'2019-05-16 11:40:02','',3),(37,'C14','14',36,18.000,'0143780037','20190517006',13.87,'2019-05-16 12:10:51','',3),(38,'C15','14',36,18.000,'0143780038','20190517006',13.62,'2019-05-16 12:17:58','',3),(39,'C16','14',36,18.000,'0143780039','20190517006',13.98,'2019-05-16 14:41:00','',3),(40,'C17','14',36,18.000,'0143780040','20190517006',13.88,'2019-05-16 14:43:10','',3),(41,'C18','14',36,18.000,'0143780041','20190517006',13.94,'2019-05-16 14:45:39','',3),(42,'C19','14',36,18.000,'0143780042','20190517006',13.9,'2019-05-16 14:49:02','',3),(43,'C20','14',36,18.000,'0143780043','20190517006',14.36,'2019-05-16 14:51:45','',3),(44,'C21','14',36,18.000,'0143780044','20190517006',14.15,'2019-05-16 15:02:58','',3),(45,'C22','14',36,18.000,'0143780045','20190517006',14.01,'2019-05-16 15:05:54','',3),(46,'C23','14',36,18.000,'0143780046','20190517006',14.17,'2019-05-16 15:12:15','',3),(47,'C24','30',36,18.000,'0303780047','20190517005',12.84,'2019-05-16 15:15:02','',3),(48,'C25','30',36,18.000,'0303780048','20190528006',12.33,'2019-05-16 15:17:18','',3),(49,'C24','14',36,18.000,'0143780049','20190517006',14.17,'2019-05-16 15:37:11','',3),(50,'C25','14',36,18.000,'0143780050','20190517006',13.97,'2019-05-16 15:39:16','',3);
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciudad`
--

DROP TABLE IF EXISTS `ciudad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreCity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreCity` (`nombreCity`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudad`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `ciudad` WRITE;
/*!40000 ALTER TABLE `ciudad` DISABLE KEYS */;
INSERT INTO `ciudad` VALUES (1,'MÃƒÂ©rida','33',0),(2,'Villahermosa','29',1),(3,'Leon','13',1),(4,'Armenia','37',1),(5,'Buenavista','37',1),(6,'GÃ©nova','37',1),(7,'Tlalnepantla','17',1),(8,'Queretaro','25',1),(9,'Silao','13',1),(10,'Salamanca','13',1),(11,'Cuajimalpa de Morelos ','17',1),(12,'Acapulco ','12',1),(13,'Aguascalientes ','1',1),(14,'Altamira Tamaulipas ','30',1),(15,'Apodaca','21',1),(16,'San Francisco de Campeche ','4',1),(17,'CancÃºn','24',1),(18,'Chihuahua ','5',1),(19,'Azcapotzalco','10',1),(20,'Jiutepec','19',1),(21,'GÃ³mez Palacio ','11',1),(22,'Calera de Victor Rosales Zacatecas ','34',1),(23,'Celaya ','13',1),(24,'Chiapa de Corzo ','6',1),(25,'Colima ','9',1),(26,'Ixtlahuacan de los Membrillos ','16',1),(27,'Lagos de Moreno ','16',1),(28,'LÃ¡zaro CÃ¡rdenas ','38',1),(29,'Minatitlan ','39',1),(30,'Morelia ','38',1),(31,'Orizaba ','39',1),(32,'Hermosillo ','28',1),(33,'Iguala ','12',1),(34,'Puebla','23',1),(35,'Puerto Vallarta','16',1),(36,'Tepic','20',1),(37,'Uruapan ','38',1),(38,'Veracruz ','39',1),(39,'Villaparilla','29',1),(40,'Zamora','38',1),(41,'Zitacuaro','38',1),(42,'Chetumal ','24',1),(43,'Cd. del Carmen ','4',1),(44,'Oaxaca','22',1),(45,'Campeche','4',1),(46,'Merida ','33',1),(47,'Tuxtla Gutierrez ','6',1),(48,'Coatzacoalcos','39',1),(49,'Cardenas ','29',1),(50,'La Paz','3',1);
/*!40000 ALTER TABLE `ciudad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clasificacion`
--

DROP TABLE IF EXISTS `clasificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clasificacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(6) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificador` (`identificador`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clasificacion`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `clasificacion` WRITE;
/*!40000 ALTER TABLE `clasificacion` DISABLE KEYS */;
INSERT INTO `clasificacion` VALUES (1,'ADH','Adhesivos',1),(2,'COM','Componentes para tintas',1),(3,'DIL','Diluyentes',1);
/*!40000 ALTER TABLE `clasificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codigos_baja`
--

DROP TABLE IF EXISTS `codigos_baja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `codigos_baja` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `longitud` double NOT NULL,
  `producto` varchar(70) NOT NULL,
  `unidades` double NOT NULL,
  `fecha_baja` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `proceso` varchar(30) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39490 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codigos_baja`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `codigos_baja` WRITE;
/*!40000 ALTER TABLE `codigos_baja` DISABLE KEYS */;
INSERT INTO `codigos_baja` VALUES (4,'01214511','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(5,'02115559','30',0,'14',0.5,'2019-05-30 13:20:00','corte'),(6,'0231157','30',0,'14',0.5,'2019-05-30 13:20:00','corte'),(7,'01316515','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(8,'01116517','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(9,'03416512','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(10,'02418517','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(11,'03210515','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(12,'00338515','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(13,'00251515','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(14,'02212512','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(15,'001415170','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(16,'022155170','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(17,'003535172','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(18,'022185170','30',0,'30',0.5,'2019-05-30 13:20:00','corte'),(19,'013135324','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(20,'024155313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(21,'024145313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(22,'012135313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(23,'012155313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(24,'024115313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(25,'003525318','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(26,'025135313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(27,'025115313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(28,'032155313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(29,'033145313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(30,'024125313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(31,'024105313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(32,'015125145','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(33,'022145158','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(34,'031105313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(35,'001585313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(36,'001425323','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(37,'035145313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(38,'023165323','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(39,'013105317','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(40,'013165313','30',0,'21',0.5,'2019-05-30 13:20:00','corte'),(41,'021135555','30',0,'48',0.5,'2019-05-30 13:20:00','corte'),(42,'014135540','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(43,'002125546','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(44,'031125550','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(45,'031165549','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(46,'001355549','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(47,'031105546','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(48,'001125540','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(49,'003315540','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(50,'014165547','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(51,'001485547','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(52,'001465547','30',0,'49',0.5,'2019-05-30 13:20:00','corte'),(53,'001445547','30',0,'49',0.5,'2019-05-30 13:20:00','corte');
/*!40000 ALTER TABLE `codigos_baja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `codigosbarras`
--

DROP TABLE IF EXISTS `codigosbarras`;
/*!50001 DROP VIEW IF EXISTS `codigosbarras`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `codigosbarras` AS SELECT 
 1 AS `id`,
 1 AS `codigo`,
 1 AS `producto`,
 1 AS `proceso`,
 1 AS `lote`,
 1 AS `noProceso`,
 1 AS `noop`,
 1 AS `tipo`,
 1 AS `baja`,
 1 AS `divisiones`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `confirmarprod`
--

DROP TABLE IF EXISTS `confirmarprod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirmarprod` (
  `idConfi` int(11) NOT NULL AUTO_INCREMENT,
  `ordenConfi` int(11) NOT NULL,
  `prodConfi` int(11) NOT NULL,
  `empaqueConfi` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `cantidadConfi` decimal(9,3) NOT NULL,
  `surtido` decimal(9,3) NOT NULL DEFAULT '0.000',
  `referenciaConfi` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `embarqueConfi` date NOT NULL,
  `entregaConfi` date NOT NULL,
  `bajaConfi` int(1) NOT NULL DEFAULT '1',
  `enlaceEmbarque` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idConfi`)
) ENGINE=InnoDB AUTO_INCREMENT=3762 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `confirmarprod`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `confirmarprod` WRITE;
/*!40000 ALTER TABLE `confirmarprod` DISABLE KEYS */;
INSERT INTO `confirmarprod` VALUES (1,45,14,'rollo',268.977,268.977,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-26','2019-07-27',2,356),(2,2,14,'caja',2.500,2.500,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-15','2019-05-16',2,1),(3,4,31,'caja',200.000,200.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',0,36),(4,3,21,'rollo',156.055,156.055,'rollo (ORDEN DE COMPRA DADA DE BAJA)','2019-05-22','2019-05-02',0,20),(5,5,21,'caja',16.000,16.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,44),(6,5,21,'rollo',114.730,111.323,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,42),(7,6,21,'caja',144.000,0.000,'','2019-07-25','2019-07-26',0,0),(8,6,14,'rollo',287.960,284.315,'BOBINA ','2019-05-01','2019-05-02',0,23),(9,6,21,'rollo',91.544,91.544,'BOBINA ','2019-05-01','2019-05-02',0,32),(10,7,30,'caja',198.000,198.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,8),(11,45,21,'rollo',200.000,0.000,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-26','2019-07-27',2,0),(12,9,31,'caja',12.000,12.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-16','2019-05-17',2,174),(13,10,30,'rollo',1209.990,1209.990,'BOBINA  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',0,28),(14,10,30,'caja',82.000,72.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',0,26),(15,11,21,'caja',80.000,0.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-07-22','2019-07-23',2,0),(16,11,21,'rollo',100.727,0.000,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-22','2019-07-23',2,0),(17,11,14,'rollo',110.103,110.103,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-22','2019-07-23',2,323),(18,12,30,'rollo',182.000,179.396,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,11),(19,12,30,'caja',18.000,18.000,'(ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,10),(20,12,14,'caja',18.000,18.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,12),(21,12,14,'rollo',242.000,240.377,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,13),(22,13,14,'caja',576.000,574.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-07','2019-05-08',2,9),(23,44,14,'caja',162.000,54.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-07-25','2019-07-26',2,373),(24,14,14,'caja',70.000,0.000,'prueba','2019-05-16','2019-05-17',0,2),(25,15,14,'caja',90.000,90.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-17','2019-05-18',2,4),(26,15,14,'rollo',611.512,611.512,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-17','2019-05-18',2,5),(27,15,31,'caja',320.000,320.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-17','2019-05-18',2,64),(28,16,30,'caja',306.000,306.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-17','2019-05-18',2,6),(29,44,14,'rollo',865.000,245.595,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-25','2019-07-26',2,369),(30,45,21,'caja',0.000,0.000,'CORTE ','2019-07-26','2019-07-27',0,0),(31,11,32,'rollo',283.474,283.474,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-01','2019-05-02',2,83),(32,11,32,'caja',80.000,80.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-07-05','2019-07-06',2,259),(33,18,14,'caja',180.000,180.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-14','2019-05-15',2,15),(34,19,31,'caja',200.000,200.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-16','2019-05-17',2,21),(35,20,30,'rollo',367.845,367.845,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-16','2019-05-17',2,173),(36,20,30,'caja',504.000,504.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-16','2019-05-17',2,27),(37,21,37,'caja',297.000,261.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-21','2019-05-22',2,172),(38,22,31,'rollo',141.700,141.700,'BOBINA  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-23','2019-05-24',2,33),(39,22,14,'caja',349.000,311.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-23','2019-05-24',2,30),(40,44,21,'rollo',730.960,0.000,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-07-25','2019-07-26',2,0),(41,24,32,'caja',400.000,0.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-27','2019-05-28',2,0),(42,24,32,'rollo',1200.000,0.000,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-27','2019-05-28',2,0),(43,26,31,'rollo',70.850,70.850,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-29','2019-05-30',2,85),(44,25,49,'caja',28.000,28.000,'CORTE  (ORDEN DE COMPRA DADA DE BAJA)','2019-05-28','2019-05-29',2,40),(45,27,30,'caja',250.000,250.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-29','2019-05-30',2,14),(46,28,30,'caja',540.000,108.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-07-24','2019-07-25',2,393),(47,30,31,'caja',112.000,112.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-30','2019-05-31',2,132),(48,30,30,'rollo',604.769,604.769,'BOBINA (ORDEN DE COMPRA DADA DE BAJA)','2019-05-30','2019-05-31',2,130),(49,30,30,'caja',234.000,234.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-05-30','2019-05-31',2,131),(50,31,30,'caja',155.000,155.000,'CORTE (ORDEN DE COMPRA DADA DE BAJA)','2019-06-17','2019-06-18',2,295);
/*!40000 ALTER TABLE `confirmarprod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consumos`
--

DROP TABLE IF EXISTS `consumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumos` (
  `IDConsumo` int(11) NOT NULL AUTO_INCREMENT,
  `subProceso` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `elemento` int(11) NOT NULL,
  `consumo` float NOT NULL,
  `producto` int(11) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IDConsumo`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumos`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `consumos` WRITE;
/*!40000 ALTER TABLE `consumos` DISABLE KEYS */;
INSERT INTO `consumos` VALUES (6,'revision',3,34,5,0),(13,'refilado',10,42,5,0),(14,'revision',104,0.2,5,0),(15,'impresion',199,0.2,5,0),(16,'laminado',344,0.56,5,0),(17,'revision',1,0.6,5,0),(18,'refilado',92,0.9,5,0),(19,'impresion',96,0.056,5,0),(20,'rollo',111,1,5,0),(21,'laminado',134,0.098,5,0),(22,'refilado',384,1.4,5,0),(23,'laminado',380,0.006,5,0),(30,'laminado',132,0.5,33,1),(31,'impresion',397,0.5,30,1),(32,'impresion',377,0.5,14,1),(33,'impresion',1,0.003,14,1),(34,'impresion',346,0.5,89,1),(35,'impresion',107,5,89,1),(36,'caja',622,5,48,1),(37,'revision',15,5,48,1),(38,'caja',744,9,70,1),(39,'revision',1,5,44,1),(40,'laminado',708,0,107,0);
/*!40000 ALTER TABLE `consumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamento`
--

DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamento`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `departamento` WRITE;
/*!40000 ALTER TABLE `departamento` DISABLE KEYS */;
INSERT INTO `departamento` VALUES (3,'RevisiÃ³n',1),(4,'Refilado',1),(5,'FusiÃ³n',1),(6,'LogÃ­stica',1),(7,'ProducciÃ³n',1),(8,'TecnologÃ­as de la informaciÃ³n',1),(9,'Corte',1),(10,'ImpresiÃ³n',1),(11,'Compras',1),(12,'DirecciÃ³n',1),(13,'Mantenimiento',1),(14,'Recursos Humanos',1),(15,'DiseÃ±o',1),(16,'RD',1),(17,'Ventas',1),(18,'ContadurÃ­a',1),(19,'RecepciÃ³n',1),(21,'Limpieza',1),(22,'Vigilancia',1),(23,'MensajerÃ­a',1),(25,'Seguridad e Higiene',1),(26,'FlexografÃ­a',1),(27,'Almacen ',1);
/*!40000 ALTER TABLE `departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devoluciones`
--

DROP TABLE IF EXISTS `devoluciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folio` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `fechaDev` date NOT NULL,
  `tipo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `sucursal` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8_unicode_ci NOT NULL,
  `idresponsable` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `folio` (`folio`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devoluciones`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `devoluciones` WRITE;
/*!40000 ALTER TABLE `devoluciones` DISABLE KEYS */;
INSERT INTO `devoluciones` VALUES (1,'1905001','2019-05-31','Embarque','20190524005','36','30','Paqueteria entrega mercancia con olores a condimentos','6',0),(2,'1905002','2019-05-31','Embarque','20190524006','36','30','Paqueteria entrego mercancia con olores a condimentos ','6',0),(3,'1907001','2019-07-12','Embarque','20190524002','23','14','FUERA DE REGISTRO (Q57 R2772/Q73 R2964)','7',2),(4,'1908001','2019-08-30','Embarque','20190802001','45','14','','',0),(5,'2105001','2021-05-14','Embarque','20191015002','51','48','calibre delgado','3',2),(6,'2205001','2022-05-09','Embarque','2022031201','127','252','Sello pegado , se troza','No Asignado',2),(7,'2208001','2022-08-25','Embarque','2022012701','101','236','Sello pegado , se troza se despinta','2',2),(8,'2501001','2025-01-21','Embarque','2025010202','173','358','DISEÃ‘O INCORRECTO ','No Asignado',2),(9,'2501002','2025-01-21','Embarque','2025010903','173','358','DISEÃ‘O INCORRECTO ','No Asignado',2);
/*!40000 ALTER TABLE `devoluciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `elementosconsumo`
--

DROP TABLE IF EXISTS `elementosconsumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `elementosconsumo` (
  `idElemento` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorElemento` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreElemento` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `impuesto` float NOT NULL,
  `precio` float NOT NULL,
  `clasificacion1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clasificacion4` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave_sat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `foto` longtext COLLATE utf8_unicode_ci NOT NULL,
  `unidad` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idElemento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `elementosconsumo`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `elementosconsumo` WRITE;
/*!40000 ALTER TABLE `elementosconsumo` DISABLE KEYS */;
/*!40000 ALTER TABLE `elementosconsumo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `embarque`
--

DROP TABLE IF EXISTS `embarque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `embarque` (
  `idEmbarque` int(11) NOT NULL AUTO_INCREMENT,
  `numEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `transpEmb` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `diaEmb` date NOT NULL,
  `observaEmb` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `bajaEmb` int(1) NOT NULL DEFAULT '1',
  `sucEmbFK` int(11) NOT NULL,
  `prodEmbFK` int(11) NOT NULL,
  `empaque` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `registrado` date NOT NULL,
  `cantidad` decimal(9,3) NOT NULL,
  `producto` int(11) NOT NULL,
  `idorden` int(11) NOT NULL,
  `cerrar` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idEmbarque`),
  UNIQUE KEY `numEmbarque` (`numEmbarque`),
  KEY `sucEmbFK` (`sucEmbFK`)
) ENGINE=InnoDB AUTO_INCREMENT=3137 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `embarque`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `embarque` WRITE;
/*!40000 ALTER TABLE `embarque` DISABLE KEYS */;
INSERT INTO `embarque` VALUES (1,'20190515001','Tres guerras ','caja','2019-05-15','REMISION 620',0,23,14,'caja','2019-05-15',2.500,14,2,0),(4,'20190517001','Tres guerras ','caja','2019-05-17','4618 /R58829',0,15,14,'caja','2019-05-17',180.000,14,25,1),(5,'20190517002','Tres guerras ','rollo','2019-05-17','4618 /R58829',0,15,14,'rollo','2019-05-17',820.000,14,26,1),(6,'20190517003','Tres guerras ','caja','2019-05-17','4617/R15706',0,24,30,'caja','2019-05-17',306.000,30,28,1),(8,'20190517005','Tres Guerras','caja','2019-05-17','4616/R7327',0,40,30,'caja','2019-05-17',198.000,30,10,1),(9,'20190517006','Tres guerras ','caja','2019-05-17','4615/R46689',0,30,14,'caja','2019-05-17',576.000,14,22,1),(10,'20190520001','Tres guerras ','caja','2019-05-20','4619/R321',0,44,30,'caja','2019-05-20',18.000,30,19,1),(11,'20190520002','Tres guerras ','rollo','2019-05-20','4619/R321',0,44,30,'rollo','2019-05-20',182.000,30,18,1),(12,'20190520003','Tres guerras ','caja','2019-05-20','4619/R321',0,44,14,'caja','2019-05-20',18.000,14,20,1),(13,'20190520004','Tres guerras ','rollo','2019-05-20','4619/R321',0,44,14,'rollo','2019-05-20',242.000,14,21,1),(14,'20190520005','Tres guerras ','caja','2019-05-20','4620/R25795',0,14,30,'caja','2019-05-20',250.000,30,45,1),(15,'20190521001','Tres guerras ','caja','2019-05-21','4623/R17433',0,29,14,'caja','2019-05-21',180.000,14,33,1),(16,'20190521002','Tres guerras ','caja','2019-05-21','REMISION 623',0,29,14,'caja','2019-05-21',4.000,14,62,1),(18,'20190521004','Tres guerras ','caja','2019-05-21','4624/R7921',0,10,14,'caja','2019-05-21',450.000,14,73,1),(19,'20190521003','Tres guerras ','rollo','2019-05-21','4625/R36061',0,26,30,'rollo','2019-05-21',500.000,30,76,1),(20,'20190522001','Tres guerras ','rollo','2019-05-22','Sin Observaciones (ORDEN DE COMPRA DADA DE BAJA)',0,35,21,'rollo','2019-05-22',282.278,21,4,1),(21,'20190522002','Tres guerras ','caja','2019-05-22','4634 / R34663',0,18,31,'caja','2019-05-22',436.000,31,34,1),(23,'20190522003','Tres guerras ','rollo','2019-05-22','4627/R28829',0,41,14,'rollo','2019-05-22',287.960,14,8,1),(24,'20190523001','Tres guerras ','caja','2019-05-23','4631/R7892',0,10,14,'caja','2019-05-23',270.000,14,74,1),(26,'20190523002','Almex','caja','2019-05-23','Sin  (ORDEN DE COMPRA DADA DE BAJA)',0,18,30,'caja','2019-05-23',82.000,30,14,0),(27,'20190523003','Almex','caja','2019-05-23','4635/R34664',0,18,30,'caja','2019-05-23',500.000,30,36,1),(28,'20190523004','Almex','rollo','2019-05-23',' (ORDEN DE COMPRA DADA DE BAJA)',0,18,30,'rollo','2019-05-23',1100.000,30,13,0),(29,'20190523005','Almex','caja','2019-05-23','4634 / R34663',0,18,31,'caja','2019-05-23',236.000,31,100,1),(30,'20190524001','Tres guerras ','caja','2019-05-24','4637/R8964',0,25,14,'caja','2019-05-24',349.000,14,39,0),(31,'20190524002','Tres guerras ','rollo','2019-05-24','4636/ R7925',0,23,14,'rollo','2019-05-24',534.000,14,91,1),(32,'20190524003','Tres guerras ','rollo','2019-05-24','4639/R28773',0,41,21,'rollo','2019-05-24',472.510,21,9,1),(33,'20190524004','Tres guerras ','rollo','2019-05-24','4637/R8964',0,25,31,'rollo','2019-05-24',75.000,31,38,0),(34,'20190524005','Tres guerras ','rollo','2019-05-24',' Sin Observaciones Devolucion Q68-Q75 4656/R13578',0,36,30,'rollo','2019-05-24',682.000,30,87,1),(35,'20190524006','Tres guerras ','caja','2019-05-24','Sin Observaciones (Devolucion Total C77)4656/R13578',0,36,30,'caja','2019-05-24',18.000,30,86,1),(36,'20190527001','Tres guerras ','caja','2019-05-27','4642/R13013',0,21,31,'caja','2019-05-27',139.000,31,3,1),(38,'20190527003','Tres guerras ','rollo','2019-05-27','4645-4647/ R 14349-14350',0,19,21,'rollo','2019-05-27',500.000,21,103,1),(39,'20190527004','Tres guerras ','caja','2019-05-27','4644/R3353',0,17,21,'caja','2019-05-27',160.000,21,109,1),(40,'20190527005','DHL','caja','2019-05-27','Sin Observaciones (ORDEN DE COMPRA DADA DE BAJA)',0,1,49,'caja','2019-05-27',196.000,49,44,1),(41,'20190528001','Tres guerras ','caja','2019-05-28','4651/R9407',0,31,21,'caja','2019-05-28',112.000,21,122,1),(42,'20190528002','Tres guerras ','rollo','2019-05-28','4650/R7810',0,23,21,'rollo','2019-05-28',114.730,21,6,1),(43,'20190528003','Tres guerras ','rollo','2019-05-28','4648/R17225',0,42,21,'rollo','2019-05-28',500.000,21,82,0),(44,'20190528004','Tres guerras ','caja','2019-05-28','4650/R7810',0,23,21,'caja','2019-05-28',112.000,21,5,1),(45,'20190528005','Tres guerras ','rollo','2019-05-28','4649/R12172',0,39,30,'rollo','2019-05-28',308.000,30,81,1),(46,'20190528006','Tres guerras ','caja','2019-05-28','4649/R12172',0,39,30,'caja','2019-05-28',72.000,30,80,1),(47,'20190528007','Tres guerras ','rollo','2019-05-28','4655/R13552',0,36,30,'rollo','2019-05-28',100.000,30,123,1),(48,'20190528008','Tres guerras ','caja','2019-05-28','4655/R13552',0,36,30,'caja','2019-05-28',18.000,30,124,1),(49,'20190528009','Tres guerras ','rollo','2019-05-28','4652-4653/R37917-37916',0,6,21,'rollo','2019-05-28',126.223,21,96,1),(52,'20190529001','Tres guerras ','caja','2019-05-29','D215',0,1,49,'caja','2019-05-29',168.000,49,127,1),(53,'20190529002','Tres guerras ','caja','2019-05-29','D220',0,1,49,'caja','2019-05-29',1162.000,49,59,1),(54,'20190529003','Tres guerras ','rollo','2019-05-29','4654/R14396',0,19,21,'rollo','2019-05-29',200.781,21,128,1),(55,'20190529004','Tres guerras ','caja','2019-05-29','D212',0,2,48,'caja','2019-05-29',181.500,48,56,1),(57,'20190530001','Tres guerras ','caja','2019-05-30','D221',0,1,49,'caja','2019-05-30',912.500,49,131,1),(58,'20190530002','Tres guerras ','caja','2019-05-30','D294',0,2,48,'caja','2019-05-30',153.500,48,130,1),(61,'20190530003','Tres guerras ','rollo','2019-05-30','4657/R29097',0,41,21,'rollo','2019-05-30',380.966,21,112,1),(62,'20190531001','Tres guerras ','rollo','2019-05-31','4660/R12173',0,39,30,'rollo','2019-05-31',161.665,30,139,1);
/*!40000 ALTER TABLE `embarque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empaque`
--

DROP TABLE IF EXISTS `empaque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empaque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameEm` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empaque`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `empaque` WRITE;
/*!40000 ALTER TABLE `empaque` DISABLE KEYS */;
INSERT INTO `empaque` VALUES (1,'caja'),(2,'rollo');
/*!40000 ALTER TABLE `empaque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `numemple` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `Nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Telefono` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `puesto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` int(11) NOT NULL,
  `usuario` int(1) NOT NULL DEFAULT '0',
  `Baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `1` (`ID`),
  UNIQUE KEY `numemple` (`numemple`),
  KEY `ID` (`ID`),
  KEY `ID_2` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,'986','Cristian Alberto','Valadez Lira','4776312349','Ã¡aÃ±iÃ³jeÃ©iÃ­',8,1,0),(2,'979','Erik Martin','Castillo Araiza','477000','PRUEBA',7,1,0),(3,'996','Juan Jose','Zurita','4773456789','PRUEBA',7,0,0),(4,'997','Felipe','Fuentes','0','PRUEBA',7,1,0),(9,'983','Erik','Castillo','8976755','PRUEBA',8,0,0),(10,'976','Mauro','Rangel','6754','PRUEBA',7,0,0),(11,'984','SebastiÃ¡n','Rodriguez','243','PRUEBA',9,0,0),(12,'999','Marlenne','SuÃ±iga','1234687','PRUEBA',11,0,0),(13,'998','John','Connor','6756434','PRUEBA',6,0,0),(14,'982','ÃºrsÃ©lÃ¡','strs','344643','PRUEBA',7,0,0),(15,'991','Brayan','Ponce','3546','PRUEBA',3,0,0),(16,'987','Esther','Gordillo','57643','PRUEBA',4,0,0),(17,'985','Laura Elena','Garcia Aguilera','0','PRUEBA',8,0,0),(18,'990','Alfonso','Guerrero','7724391','PRUEBA',8,0,0),(19,'995','Elviss','Pressley','5674420','PRUEBA',7,1,0),(20,'994','James','Bond','4772355875','PRUEBA',7,0,0),(21,'981','Ã©ste men','sno','24','PRUEBA',8,0,0),(22,'978','Ana Lilia','RamÃ­rez SaldaÃ±a ','0','PRUEBA',11,1,0),(23,'977','Araceli','Belmonte Lozano ','044 477 113 3471','PRUEBA',6,1,0),(24,'980','JosÃ© Antonio','RodrÃ­guez HernÃ¡ndez ','0','PRUEBA',7,1,0),(25,'993','Arturo','Trejo Carrillo','000','ProducciÃ³n',7,1,0),(26,'989','Christian Abraham','Mancilla Ramos','sudo systemctl start sshd','PRUEBA',7,1,0),(27,'992','Sandra','Baeza','sudo apt-get install openssh-server','PRUEBA',1,1,0),(28,'001','Ivonn Ramona','AguiÃ±aga Tavares','000','Cortadora',9,0,0),(29,'002','Efren','Alvarado GuzmÃ¡n','000','TECNICO',7,0,0),(30,'003','Anay','Alvarez Becerra','000','COORD. RH',14,0,0),(31,'004','Carmen','Araujo Rocha','000','Recepcionista',19,1,0),(32,'005','Juan Victor','Armendariz Valdez','000','Contador',18,0,0),(33,'006','Juan Carlos','Barcenas GarcÃ­a','000','Refilador',4,1,0),(34,'007','Angel Ricardo','Barrera Dupont','000','Auxiliar de RD',16,1,0),(35,'008','Maria Elene','Barrientos Carpio','000','Cortadora',9,0,0),(36,'009','Araceli','Belmonte Lozano','000','Logistica',6,1,1),(37,'010','Juan Carlos','Ortiz Belmonte','000','Impresor',10,0,0),(38,'011','Erik','Castillo Araiza','000','Soporte',8,0,0),(39,'012','Cesar Ivan','Castillo Jimenez','000','Tecnico MTTO',13,0,0),(40,'013','Juana Imelda','Chavez','000','Limpieza',21,0,1),(41,'014','Juan JosÃ©','Cortes X','000','Auxiliar MTTO',13,0,1),(42,'015','Fatima','Cruz Chavez','000','Revisador',3,0,0),(43,'016','Luis Francisco','Cruz Ibarra','000','Revisador',3,0,0),(44,'017','Alejandro','Cruz Torres','000','Supervisor',5,0,0),(45,'018','Cynthia ','Torres','000','REP SGC',16,0,0),(46,'019','Dulce Esmeralda','Delgado Ovalle','000','Revisador',3,0,0),(47,'020','FÃ¡tima Montserrat','Espinoza Gonzalez','000','Coordinador de planta',7,1,1),(48,'021','Victor Manuel','Estrada Martinez','000','Vigilante',22,1,0),(49,'022','Almeida','Fortino','000','Mensajero',23,0,1),(50,'023','Alejandro','Fuentes','000','Almacenista',2,0,0),(51,'024','Llared Enedelia','Fuentes Gomez','000','Cortadora',9,0,0),(52,'025','Luis Felipe','Fuentes Medina','000','Supervisor de Calidad',1,0,0),(53,'026','Luis David','Gomez Banderas','000','Refilador',4,0,0),(54,'027','MarÃ­a Guadalupe','Gomez Reyes','000','Cortadora',9,0,0);
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ensambleempaques`
--

DROP TABLE IF EXISTS `ensambleempaques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ensambleempaques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `codEmpaque` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` int(5) NOT NULL,
  `longitud` float NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `refEnsamble` int(11) NOT NULL,
  `tipoEmpaque` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=856285 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ensambleempaques`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `ensambleempaques` WRITE;
/*!40000 ALTER TABLE `ensambleempaques` DISABLE KEYS */;
INSERT INTO `ensambleempaques` VALUES (1,'Q1','0143790001',14,512,7.050,'0003546',0,'rollo','20190517002','',1),(2,'Q1','0143790001',14,492,6.775,'0001446',1,'rollo','20190517002','',1),(3,'Q1','0143790001',14,485,6.678,'0003544',2,'rollo','20190517002','',1),(4,'Q2','0143790002',14,595,8.193,'0001146',3,'rollo','20190517002','',1),(5,'Q2','0143790002',14,569,7.835,'0003245',4,'rollo','20190517002','',1),(6,'Q2','0143790002',14,574,7.904,'0003445',5,'rollo','20190517002','',1),(7,'Q3','0143790003',14,597,8.221,'0003344',6,'rollo','20190517002','',1),(8,'Q3','0143790003',14,600,8.262,'0003444',7,'rollo','20190517002','',1),(9,'Q3','0143790003',14,579,7.973,'0002144',8,'rollo','20190517002','',1),(10,'Q4','0143790004',14,587,8.083,'0001444',9,'rollo','20190517002','',1),(11,'Q4','0143790004',14,579,7.973,'0001344',10,'rollo','20190517002','',1),(12,'Q4','0143790004',14,589,8.110,'0001144',11,'rollo','20190517002','',1),(13,'Q5','0143790005',14,571,7.863,'0001543',12,'rollo','20190517002','',1),(14,'Q5','0143790005',14,539,7.422,'0002543',13,'rollo','20190517002','',1),(15,'Q5','0143790005',14,535,7.367,'0003548',14,'rollo','20190517002','',1),(16,'Q6','0143790006',14,574,7.904,'0003144',15,'rollo','20190517002','',1),(17,'Q6','0143790006',14,590,8.124,'0001244',16,'rollo','20190517002','',1),(18,'Q6','0143790006',14,555,7.642,'0001148',17,'rollo','20190517002','',1),(19,'Q7','0143790007',14,594,8.179,'0003146',18,'rollo','20190517002','',1),(20,'Q7','0143790007',14,582,8.014,'0002145',19,'rollo','20190517002','',1),(21,'Q7','0143790007',14,555,7.642,'0003148',20,'rollo','20190517002','',1),(22,'Q8','0143790008',14,570,7.849,'0001546',21,'rollo','20190517002','',1),(23,'Q8','0143790008',14,600,8.262,'0002446',22,'rollo','20190517002','',1),(24,'Q8','0143790008',14,548,7.546,'0001348',23,'rollo','20190517002','',1),(25,'Q9','0143790009',14,553,7.615,'0002248',24,'rollo','20190517002','',1),(26,'Q9','0143790009',14,544,7.491,'0001548',25,'rollo','20190517002','',1),(27,'Q9','0143790009',14,575,7.918,'0003243',26,'rollo','20190517002','',1),(28,'Q10','0143790010',14,542,7.463,'0002243',27,'rollo','20190517002','',1),(29,'Q10','0143790010',14,543,7.477,'0002443',28,'rollo','20190517002','',1),(30,'Q10','0143790010',14,549,7.560,'0001448',29,'rollo','20190517002','',1),(31,'Q11','0143790011',14,542,7.463,'0001443',30,'rollo','20190517002','',1),(32,'Q11','0143790011',14,537,7.394,'0002143',31,'rollo','20190517002','',1),(33,'Q11','0143790011',14,537,7.394,'0002343',32,'rollo','20190517002','',1),(34,'Q12','0143790012',14,586,8.069,'0003446',33,'rollo','20190517002','',1),(35,'Q12','0143790012',14,579,7.973,'0001246',34,'rollo','20190517002','',1),(36,'Q12','0143790012',14,562,7.739,'0001346',35,'rollo','20190517002','',1),(37,'Q13','0143790013',14,530,7.298,'0001544',36,'rollo','20190517002','',1),(38,'Q13','0143790013',14,542,7.463,'0003547',37,'rollo','20190517002','',1),(39,'Q13','0143790013',14,564,7.766,'0003247',38,'rollo','20190517002','',1),(40,'Q14','0143790014',14,505,6.954,'00022421',39,'rollo','20190517002','',1),(41,'Q14','0143790014',14,494,6.802,'00024421',40,'rollo','20190517002','',1),(42,'Q14','0143790014',14,510,7.023,'00025425',41,'rollo','20190517002','',1),(43,'Q15','0143790015',14,521,7.174,'00012421',42,'rollo','20190517002','',1),(44,'Q15','0143790015',14,520,7.160,'00011421',43,'rollo','20190517002','',1),(45,'Q15','0143790015',14,492,6.775,'00035421',44,'rollo','20190517002','',1),(46,'Q16','0143790016',14,563,7.752,'0003447',45,'rollo','20190517002','',1),(47,'Q16','0143790016',14,552,7.601,'0001547',46,'rollo','20190517002','',1),(48,'Q16','0143790016',14,549,7.560,'0001147',47,'rollo','20190517002','',1),(49,'Q17','0143790017',14,609,8.386,'0003346',48,'rollo','20190517002','',1),(50,'Q17','0143790017',14,596,8.207,'0002346',49,'rollo','20190517002','',1);
/*!40000 ALTER TABLE `ensambleempaques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abreviatura` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreEstado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreEstado` (`nombreEstado`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (1,'Ags.','Aguascalientes','MÃ©xico',1),(2,'BC','Baja California','MÃ©xico',0),(3,'BCS','Baja California Sur','MÃ©xico',1),(4,'Camp.','Campeche','MÃ©xico',1),(5,'Chih.','Chihuahua','MÃ©xico',1),(6,'Chis.','Chiapas','MÃ©xico',1),(7,'Colombia','Colombia','',1),(8,'Coah.','Coahuila de Zaragoza','MÃ©xico',1),(9,'Col.','Colima','MÃ©xico',1),(10,'DF','Distrito Federal','MÃ©xico',1),(11,'Dgo.','Durango','MÃ©xico',1),(12,'Gro.','Guerrero','MÃ©xico',1),(13,'Gto.','Guanajuato','MÃ©xico',1),(14,'Guatemala','Guatemala','',1),(15,'Hgo.','Hidalgo','MÃ©xico',0),(16,'Jal.','Jalisco','MÃ©xico',1),(17,'Mex.','MÃ©xico','MÃ©xico',1),(18,'Mich.','MichoacÃ¡n de Ocampo','MÃ©xico',0),(19,'Mor.','Morelos','MÃ©xico',1),(20,'Nay.','Nayarit','MÃ©xico',1),(21,'NL','Nuevo LeÃ³n','MÃ©xico',1),(22,'Oax.','Oaxaca','MÃ©xico',1),(23,'Pue.','Puebla','MÃ©xico',1),(24,'Q. Roo','Quintana Roo','MÃ©xico',1),(25,'Qro.','QuerÃ©taro','MÃ©xico',1),(26,'Sin.','Sinaloa','MÃ©xico',1),(27,'SLP','San Luis PotosÃƒÂ­','MÃ©xico',1),(28,'Son.','Sonora','MÃ©xico',1),(29,'Tab.','Tabasco','MÃ©xico',1),(30,'Tamps.','Tamaulipas','MÃ©xico',1),(31,'Tlax.','Tlaxcala','MÃ©xico',1),(32,'Ver.','Veracruz de Ignacio de la Llave','MÃ©xico',0),(33,'Yuc.','YucatÃƒÂ¡n','MÃ©xico',1),(34,'Zac.','Zacatecas','MÃ©xico',1),(35,'Sin definicion','Romita','Romita',0),(36,'GTO','Guanajuto','MÃ©xico',0),(37,'QUND','Quindio','Colombia',1),(38,'Mich','Michoacan','MÃ©xico',1),(39,'Ver.','Veracruz ','MÃ©xico',1),(40,'izta.','Iztacalco','MÃ©xico',1),(41,'Q.Roo','Playa del Carmen ','MÃ©xico',1),(42,'Q.Roo','Quintana,Roo','MÃ©xico',1),(43,'C.A.','Centro AmÃ©rica ','MÃ©xico ',1),(44,'can ','cancÃºn ','Q.Roo',1),(45,'Yuc.','Ticul,Yucatan','Mexico',0),(46,'Apizaco','Apizaco,Tlaxcala','Mexico',1),(47,'Edo.Mex','Estado de Mexico','Mexico',1),(48,'Tianguistengo','Tianguistengo de Galeana','Mexico',1),(49,'Bog.','Bogota,Colombia','Colombia',0),(52,'Colomb.','Bogota','Colombia',0);
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ficha_salud`
--

DROP TABLE IF EXISTS `ficha_salud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ficha_salud` (
  `no_empleado` int(11) NOT NULL,
  `sangre` varchar(3) NOT NULL,
  `edad` varchar(2) NOT NULL,
  `diabetes` varchar(1) NOT NULL,
  `presion` varchar(1) NOT NULL,
  `gastritis` varchar(1) NOT NULL,
  `colitis` varchar(1) NOT NULL,
  `asma` varchar(1) NOT NULL,
  `vertigo` varchar(1) NOT NULL,
  `gota` varchar(1) NOT NULL,
  `migrana` varchar(1) NOT NULL,
  `epilepsia` varchar(1) NOT NULL,
  `rinones` varchar(1) NOT NULL,
  `corazon` varchar(1) NOT NULL,
  `otro` varchar(1) NOT NULL,
  `medicamento` varchar(100) NOT NULL,
  `dosis` varchar(70) NOT NULL,
  `observaciones` varchar(100) NOT NULL,
  `padecimientos` varchar(70) NOT NULL,
  `fracturas` varchar(100) NOT NULL,
  `operaciones` varchar(70) NOT NULL,
  `alergias` varchar(1) NOT NULL,
  `cuales` varchar(100) NOT NULL,
  `medim_aler` varchar(50) NOT NULL,
  `alerg_alim` varchar(1) NOT NULL,
  `cuales_alim_alerg` varchar(50) NOT NULL,
  `medim_alerg_dosis` varchar(70) NOT NULL,
  `otro_factor` varchar(1) NOT NULL,
  `cual_factor` varchar(50) NOT NULL,
  `info_add` varchar(100) NOT NULL,
  `comentarios` varchar(100) NOT NULL,
  PRIMARY KEY (`no_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ficha_salud`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `ficha_salud` WRITE;
/*!40000 ALTER TABLE `ficha_salud` DISABLE KEYS */;
INSERT INTO `ficha_salud` VALUES (1,'A+','29','X','X','','','','','','','','','','','Metformina, Losartan','1 pastilla c/12 hrs','','','','','','','','','','','','','','Chequeo cada mes y anÃ¡lisis cada 2 meses dependiendo del estado de salud'),(2,'O+','33','','','','','','','','','','','','','','','','','Fractura en brazo izquierdo','','','','','','','','','','',''),(9,'O+','44','','X','X','X','','','','','','','','','Omeprazol','','Por episodios de gastritis y colitis','','DislocaciÃ³n de hombro derecho, cadera, tobillo derecho por accidente en auto','ExtracciÃ³n de vesÃ­cula por piedras y extracciÃ³n de quiste','','','','','','','','','',''),(11,'O+','23','','','','','','','','','','','','','','','','','Esguince en el tobillo y rodilla','','','','','','','','','','',''),(13,'A+','56','X','X','','','X','','','','','','','','Metformina, Losartan','1 pastilla c/12 hrs','','','Esguince en el pie','OperaciÃ³n de visicula','','','','','','','','','','Chequeo mensual  por diabetes y presiÃ³n alta'),(14,'B+','47','','','X','','','','','','','','','','','','','','Fractura','Picadura de pulmÃ³n ','','','','','','','','','',''),(15,'O+','22','','','X','X','','','','','X','','','','Carbamazepina, Cloratodina','1 pastilla c/12 hrs','','','Fractura en el tobillo y muÃ±eca','OperaciÃ³n en la ingle para retirar berruga','','','','X','Mariscos y fresas','','','','',''),(20,'O+','24','','','','','','','','','','','','','','','','','','','','','','','','','','','','Medicamento gentageno \"Tumores\"'),(21,'O+','47','','','','','','','','','','','','','','','','','','Hernia inguinal','','','','','','','','','',''),(28,'O+','45','','','','','','','','','','','','','','','','LesiÃ³n de columna','','','','','','','','','','','','Toma una paracetamol cada que comienza el dolor de columna'),(29,'O+','37','','X','','X','','X','','','','','','','Temerit kox','1/2 pastilla c/24 hrs','','Lumbalgia ocasional','','Rinoplastia','','','','','','','','','','En caso de vertigo toma Vontrol 1 pastilla c/24 hrs, constantemete tiene colesterol y trigliceridos '),(31,'A+','23','','','X','','','','','','','','','','','','','','Esguince pulgar derecho','Parto','','','','','','','','','',''),(35,'O+','24','','','','','','','','','','','','','','','','','Esguince, clavicula rota y desgarre de cuello','','','','','','','','','','',''),(38,'O+','32','','','','','','','','','','','','','','','','','','','','','','','','','','','',''),(40,'O+','53','','','','','','','','','','','','','','','','','LuxaciÃ³n en el hombro y codo','','','','','','','','','','',''),(42,'O+','41','','X','X','X','','','','X','','','','','','','','','','CesÃ¡rea','','','','','','','','','',''),(43,'A+','48','','','X','','','','','','','','','','','','','','Fractura en el pie izquierdo, LuxaciÃ³n de hombro derecho e izquierdo, Esguince en el cuello','Peritonitis','','','','','','','','','',''),(50,'B+','47','','','','','','','','','','','','','','','','','','CesÃ¡rea','','','','','','','','','',''),(55,'A+','26','','','','','','','','','','','','','','','','','','','X','Penicilina','','','','','','','',''),(56,'O+','36','','','X','','','','','X','','','','','Metoprozol, Impramina','','Para migraÃ±a (impramina)','','Fractura en el radio','Hernia y fractura','','','','','','','','','',''),(59,'B+','24','','','','','','','','','','','','','','','','','','','X','Clorofenamina Comp','','','','','','','',''),(60,'O+','40','','','','','','','','','','','','','','','','','','CesÃ¡rea','','','','','','','','','',''),(70,'O+','31','','X','X','X','','','','','','','','','','','','','Esguince en la rodilla izquierda','CesÃ¡rea','X','Ampicilina','','','','','','','','Se le suelen hinchar los pies si esta mucho tiempo de pie'),(74,'A+','22','','','','X','','','','','','','','','','','','','','','','','','','','','','','',''),(77,'O+','23','','','X','','','','','','','','','D','Progesterona','1  pastilla c/24 hrs','','','Esguinces en el tobillo derecho y fractura en la rodilla y brazo ','','','','','','','','X','','','RevisiÃ³n de ciclos menstruales'),(83,'A+','60','','','','','','','','','','','','','','','','','','Hernia inguinal','','','','','','','','','',''),(90,'A+','24','','','','','','','','','','','','','','','','','','','X','Penicilina','','','','','X','','',''),(93,'O+','36','','','','','','','','','','','','A','','','','','Esguince en el tobillo','','','','','','','','','','','Tratamiento para artritis una vez al mes con mÃ©dico familiar'),(101,'O+','24','','','','','','','','','','','','','','','','','','Pendisitis aguda','','','','','','','','','',''),(102,'O+','52','','X','X','','','','','','','','','','Losartan','1 pastilla c/24hrs','','','Esguince en el tobillo','','X','Penicilina','','','','','','','',''),(103,'A+','26','','','X','','','','','X','','','','','','','','MigraÃ±a','','Hernia testicular','X','Flunarizina, cinaricina, difenidol, metroclopromida','','X','Chile morrÃ³n','','','','',''),(104,'O+','26','','','','','','','','','','','','','','','','','Esguince en rodilla izquierda ','','','','','','','','','','','');
/*!40000 ALTER TABLE `ficha_salud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hlogpproducto`
--

DROP TABLE IF EXISTS `hlogpproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hlogpproducto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) NOT NULL,
  `consumo` double NOT NULL,
  `impresion` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hlogpproducto`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `hlogpproducto` WRITE;
/*!40000 ALTER TABLE `hlogpproducto` DISABLE KEYS */;
INSERT INTO `hlogpproducto` VALUES (1,'hologrami',0.7,'Mas Humano'),(2,'holi',0.5,'jajjjdfg'),(3,'holi',0,'cuindio'),(4,'holi',0,'cuindio'),(5,'holi',0,'Kindio'),(6,'hologrami',0.3,'A pruebis loko'),(7,'hologrami',0,'Gen.Gepp3 97x74mm Bco 2019'),(8,'hologrami',0,'Cristal Estrella2 107x70mm'),(9,'hologrami',0,'Gen.Gepp3 108x75mm Bco 2019'),(10,'hologrami',0,'Cristal Gota3 107x70mm'),(11,'hologrami',0,'Cristal Gota2 107x70mm'),(12,'hologrami',0,'Cristal Gota2 107x70mm'),(13,'hologrami',0,'Cristal Gota3 107x70mm'),(14,'hologrami',0,'Cristal Gota3 107x70mm'),(15,'hologrami',0,'Cristal Estrella3 107x70mm'),(16,'hologrami',0,'Cristal Estrella3 C/Leyen.107x70mm'),(17,'hologrami',0,'Ciel 107x70mm '),(18,'hologrami',0,'Ciel2 107x70mm '),(19,'Holograma Personalizado',0,'Infasa 15x10mm'),(20,'hologrami',0,'Cristal Gota3 115x70mm'),(21,'Holograma Personalizado',0,'Sanofi-aventis Guatemala  22x22mm'),(22,'Holograma Personalizado',0,'Grupo Labro 21x16mm'),(23,'Holograma Personalizado',0,'Naturcol 20x12mm'),(24,'hologrami',0,'Cristal Estrella3 107x70mm'),(25,'hologrami',0,'Cristal Estrella PV3 C/Leyen. 107x70mm'),(26,'hologrami',0,'Cristal GotaPV3 107x70mm'),(27,'Holograma Personalizado',0,'Sanofi-aventis 22x22mm'),(28,'Holograma Personalizado',0,'Generix 25x15mm'),(29,'hologrami',0,'Cristal Estrella2 C/Ley 107x70mm'),(30,'Holograma Generico',0,'Holograma Antivet generico , foliado'),(31,'hologrami',0,'Etiq.Hol.Adiolo Tramadol 100mg');
/*!40000 ALTER TABLE `hlogpproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impresiones`
--

DROP TABLE IF EXISTS `impresiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impresiones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionDisenio` int(5) DEFAULT NULL,
  `anchoPelicula` float DEFAULT NULL,
  `anchoEtiqueta` float DEFAULT NULL,
  `millaresPorRollo` float DEFAULT NULL,
  `codigoImpresion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alturaEtiqueta` float DEFAULT NULL,
  `espacioFusion` float DEFAULT NULL,
  `porcentajeMPR` float DEFAULT NULL,
  `descripcionImpresion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `millaresPorPaquete` float DEFAULT NULL,
  `tintas` int(2) DEFAULT NULL,
  `sustrato` int(6) DEFAULT NULL,
  `nombreBanda` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigoCliente` int(6) DEFAULT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  `alturaImpresion` float DEFAULT NULL,
  `espacioregistro` float NOT NULL,
  `holograma` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mostrar` int(1) NOT NULL DEFAULT '1',
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `refParcial` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `logproveedor` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `haslote` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `descripcionImpresion` (`descripcionImpresion`),
  UNIQUE KEY `codigoImpresion` (`codigoImpresion`),
  KEY `descripcionDisenio` (`descripcionDisenio`),
  KEY `descripcionImpresion_2` (`descripcionImpresion`),
  KEY `codigoImpresion_2` (`codigoImpresion`),
  KEY `descripcionDisenio_2` (`descripcionDisenio`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impresiones`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `impresiones` WRITE;
/*!40000 ALTER TABLE `impresiones` DISABLE KEYS */;
INSERT INTO `impresiones` VALUES (2,3,127,NULL,11,'903294',274.5,NULL,20,'Comex prueba',0,1,6,'',1,0,NULL,19,'',1,'2019-02-12 12:31:35','1','','',''),(6,5,190,NULL,4,'23436',78,NULL,7,'A pruebis loko',6,3,6,'',4,0,NULL,20,'hologrami',1,'2019-02-11 12:31:35','1','','',''),(12,15,190,NULL,1.5,'5353',70,NULL,1,'Pegatina Cuindio 4x5',0.8,2,6,'',4,1,NULL,20,'',1,'2019-02-04 12:31:35','1','','',''),(13,3,220,NULL,0,'vhgvh',90,NULL,0,'Comex prueba2',0,5,1,'',1,0,NULL,10,'',1,'2019-02-05 12:31:35','1','','',''),(14,16,200,97,7.5,'M341-1',74,6,30,'Gen.Gepp3 97x74mm Bco 2019',0.5,7,24,'',7,0,NULL,1,'hologrami',1,'2019-02-19 10:51:52','1','','',''),(15,19,225,107,7.5,'M0723C45-01',70,11,20,'Cristal Estrella2 107x70mm',0.5,4,199,'',9,0,NULL,10,'hologrami',1,'2019-02-19 12:17:15','1','','','0'),(21,6,222,108,7.5,'M340-1',75,6,20,'Gen.Gepp3 108x75mm Bco 2019',0.5,7,26,'',10,0,NULL,1,'',1,'2019-02-26 08:47:29','1','','',''),(29,18,220,105,7.5,'775',70,10,10,'otikiss 3x4',0.8,3,1,'',1,1,NULL,10,'',1,'2019-02-27 11:25:57','1','','',''),(30,6,200,97,7.5,'M342-1',68,6,20,'Gen.Gepp3 97x68mm Bco 2019',0.5,7,24,'',11,0,NULL,1,'',1,'2019-02-28 10:12:18','1','','',''),(31,6,194,88,14.9,'M06091B39-01',37,18,50,'Epura3 87x37mm 10.1lt ',1,2,200,'',3,1,NULL,1,'',1,'2019-02-28 12:51:11','1','','','0'),(32,6,194,88,7.5,'M06091B39-02',37,18,20,'Epura3 88x37mm 10.1lt 2019 Sin/Punteado',1,2,27,'',3,0,NULL,1,'',1,'2019-02-28 12:55:30','1','','',''),(33,3,127,NULL,11,'E0506C25-03',274.5,NULL,6,'Thinner Meridian 960ml',0,5,30,'',17,0,NULL,72,'',1,'2019-03-14 15:11:56','1','','',''),(36,19,225,107,7.5,'M0831C49-01',70,11,20,'Cristal Gota2 107x70mm',0.5,4,44,'',19,0,NULL,5,'hologrami',1,'2019-03-26 16:11:17','1','','','0'),(37,6,200,97,7.5,'M341-2',74,6,20,'Gen.Gepp3 97x74mm Bco 2019 C/Punteado',0.5,7,24,'',7,0,NULL,1,'',1,'2019-04-06 15:29:09','1','','',''),(38,6,194,88,7.5,'M0609B39-02.',37,18,20,'Epura2 88x37mm 10.1lt P2147 2019 Sin Punteado',1,2,32,'',3,0,NULL,2,'',1,'2019-04-10 17:26:53','1','','',''),(39,6,194,88,7.5,'M0609B39-01.',37,18,20,'Epura2 88x37mm 10.1lt P2147 2019 Con Punteado',1,2,32,'',3,0,NULL,2,'',1,'2019-04-11 13:48:55','1','','',''),(40,3,85,NULL,11,'E354-00-1',230,NULL,6,'Solvente para Poliuretano 500ml',0.5,6,35,'',26,0,NULL,90,'',1,'2019-04-26 10:20:48','1','','',''),(41,3,85,NULL,11,'E343-05-24',230,NULL,6,'Aguarras Sintetico 500ml',0.5,6,35,'',28,0,NULL,90,'',1,'2019-04-26 10:35:24','1','','',''),(42,3,85,NULL,11,'E0506C25-04',230,NULL,6,'Thinner Estandar 500ml',0.5,5,35,'',30,1,NULL,90,'',1,'2019-04-26 10:43:47','1','','',''),(43,3,127,NULL,11,'E0506C25-02',274.5,NULL,6,'Thinner Americano 960ml',0.5,6,35,'',24,1,NULL,92,'',1,'2019-04-26 11:10:38','1','','',''),(44,3,127,NULL,11,'E0420C18-04',274.5,NULL,6,'Thinner Estandar 960ml',0.5,5,35,'',2,1,NULL,92,'',1,'2019-04-26 12:10:47','1','','',''),(45,3,127,NULL,11,'E0506C25-05',274.5,NULL,6,'Gasolina Blanca 960ml',0.5,6,35,'',18,1,NULL,92,'',1,'2019-04-26 12:22:57','1','','',''),(46,3,85,NULL,11,'E1406C28-01',230,NULL,6,'Tinta al Alcohol 480ml',0.5,6,36,'',22,0,NULL,35,'',1,'2019-04-29 15:30:51','1','','',''),(48,19,222,107,7.5,'M0831C49-01b',70,8,20,'Cristal Gota3 107x70mm',0.5,4,40,'',13,0,NULL,1,'hologrami',1,'2019-05-14 10:29:14','1','','El producto requiere de un cuidado especial para su embalaje.','0'),(49,19,222,107,7.5,'M0723C45-01b',70,8,20,'Cristal Estrella3 107x70mm',0.5,4,40,'',15,0,NULL,1,'hologrami',1,'2019-05-14 10:32:30','1','','','0'),(50,19,222,107,7.5,'M0723C45-02b',70,8,20,'Cristal Estrella3 C/Leyen.107x70mm',0.5,4,75,'8',16,0,NULL,1,'hologrami',1,'2019-05-14 10:37:47','1','','','0'),(51,6,200,97,7.5,'M384-00-1',68,6,20,'Gepp Promo El Porton 97x68mm 2019',0.5,7,24,'',32,0,NULL,1,'',1,'2019-05-23 11:07:13','1','','',''),(52,6,200,97,7.5,'M385-00-1',74,6,20,'Gepp Promo El Porton 97x74mm 2019',0.5,7,24,'',32,0,NULL,1,'',1,'2019-05-23 11:13:04','1','','',''),(53,6,222,108,7.5,'M383-00-1',75,6,20,'Gepp Promo El Porton 108x75mm 2019',0.5,7,26,'',32,0,NULL,1,'',1,'2019-05-23 11:18:32','1','','',''),(58,22,222,107,7.5,'M357-10-3',70,8,50,'Ciel3 107x70mm ',0.5,4,40,'',34,1,NULL,1,'hologrami',1,'2019-05-30 16:37:19','1','','EL punteado  quedo igual que cristal 2 lineas , sin tanta profundidad','0'),(59,22,225,107,7.5,'M357-10-3/1',70,11,20,'Ciel2 107x70mm ',0.5,4,129,'10',34,0,NULL,5,'hologrami',1,'2019-05-30 17:18:40','1','','','0'),(60,6,200,97,7.5,'M384-00-1/1',68,6,20,'Gepp2 Promo El Porton 97x68mm 2019',0.5,7,6,'',32,0,NULL,0,'',1,'2019-05-31 09:19:37','1','','',''),(61,25,170,NULL,3,'E378-15-1',85,NULL,5,'Crema Natural Grande 85x85mm',0,5,48,'',35,0,NULL,30,'',1,'2019-06-14 16:33:52','1','','',''),(62,25,170,NULL,3,'E374-15-1',85,NULL,5,'Queso Fresco 85x85mm',0,4,48,'',35,1,NULL,30,'',1,'2019-06-14 16:52:36','1','prueba','',''),(63,25,240,NULL,3,'E377-15-1',60,NULL,5,'Crema Natural Chica 60x60mm',0,5,49,'',35,0,NULL,60,'',1,'2019-06-14 17:08:04','1','','',''),(64,25,240,NULL,3,'E373-15-1',60,NULL,5,'Queso Asadero 60x60mm',0,5,49,'',35,0,NULL,60,'',1,'2019-06-14 17:19:22','1','','',''),(65,27,154,70.5,7.5,'M349-00-3/2im',50,13,20,'Truper2 30ml Aceite Multi-usos',0.5,4,50,'',36,0,NULL,12,'',1,'2019-07-10 10:44:14','1','','',''),(67,27,147,70.5,7.5,'M349-00-3/1im',45,6,20,'Truper1 30ml Aceite Multi-usos',0.75,4,52,'',36,1,NULL,13,'',1,'2019-07-31 10:32:06','1','','',''),(68,27,147,70.5,7.5,'M391TRUPER/1im',105,6,20,'Truper1 90ml Aceite Multi-usos',0.5,4,52,'',36,1,NULL,13,'',1,'2019-07-31 11:28:02','1','','',''),(69,32,15,NULL,10,'H1107A43-01',10,NULL,20,'Infasa 15x10mm',0,NULL,56,'',38,1,NULL,0,'Holograma Personalizado',1,'2019-08-29 09:02:05','1','','CADA BOBINA DEBE CONTENER ETIQUETA CON (TARA,PESO NETO Y PESO BRUTO ) ',''),(70,19,238,115,7.5,'M388BEPENSA',70,8,20,'Cristal Gota3 115x70mm',0.5,4,55,'3',39,0,NULL,1,'hologrami',1,'2019-08-31 13:18:15','1','','',''),(71,33,22,NULL,1,'H0609A37-01',22,NULL,5,'Sanofi-aventis Guatemala  22x22mm',0,NULL,56,'',40,0,NULL,0,'Holograma Personalizado',1,'2019-09-06 09:20:50','1','','',''),(72,29,120,NULL,0.5,'E0419C19-01',313.5,NULL,5,'Cristal 20L 2Grande Blanca Autoadh.',0,4,60,'',41,0,NULL,25,'',1,'2019-09-12 15:12:28','1','','',''),(74,35,25,NULL,10,'H0622A41-01',15,NULL,5,'Generix 25x15mm',0,NULL,56,'',42,0,NULL,0,'',1,'2019-09-23 09:31:05','1','','',''),(75,36,20,NULL,2,'H0622A42-01',30,NULL,5,'Antivet 20x30 Generico foliado',0,NULL,56,'',43,0,NULL,0,'',1,'2019-09-23 09:48:21','1','','',''),(76,29,120,NULL,0.5,'E0419C19-01-1',313.5,NULL,20,'Cristal Gota Gde.Im/Bco Autoadh.',0,4,61,'',41,1,NULL,50,'',1,'2019-09-23 11:34:14','2','','',''),(77,37,21,NULL,10,'H425LABRO',16,NULL,20,'Grupo Labro 21x16mm',0,NULL,56,'',45,0,NULL,0,'Holograma Personalizado',1,'2019-10-17 14:52:26','1','','Core 3\" pulg. ',''),(80,39,120,NULL,0.5,'E417BEPENSA',313.5,NULL,20,'Cristal 20L Estrella Grande Blanca Autoadh.',0,3,83,'',49,1,NULL,30,'',1,'2019-12-05 13:05:39','1','','',''),(81,41,60,NULL,1,'E668BEPENSA',473.5,NULL,20,'Cristal 20L Transp.AutoAdher.Liston 473.5x52mm',0,3,66,'',37,1,NULL,0,'',1,'2019-12-16 11:16:21','2','','','0'),(82,42,120,NULL,0.5,'E417BEPENSA.',313.5,NULL,20,'Cristal Estrella Gde.Im/Trn Autoadh.R',0,3,67,'',50,1,NULL,50,'',1,'2020-01-08 17:00:43','2','','','');
/*!40000 ALTER TABLE `impresiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juegoparametros`
--

DROP TABLE IF EXISTS `juegoparametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juegoparametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorJuego` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `nombreparametro` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `numParametro` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  `requerido` int(1) NOT NULL DEFAULT '0',
  `leyenda` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `identificadorJuego` (`identificadorJuego`),
  KEY `nombreparametro` (`nombreparametro`)
) ENGINE=InnoDB AUTO_INCREMENT=1067 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juegoparametros`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `juegoparametros` WRITE;
/*!40000 ALTER TABLE `juegoparametros` DISABLE KEYS */;
INSERT INTO `juegoparametros` VALUES (1,'JPMTERMOENCOGIBLE','codigoImpresion','PAM1',1,1,'CÃ³digo','codigo de impresiÃ³n'),(2,'JPMTERMOENCOGIBLE','descripcionImpresion','PAM2',1,1,'DescripciÃ³n','descripciÃ³n'),(3,'JPMTERMOENCOGIBLE','anchoPelicula','PAM3',1,1,'Ancho pelicula','mm'),(4,'JPMTERMOENCOGIBLE','alturaEtiqueta','PAM4',1,1,'Altura etiqueta','mm'),(5,'JPMTERMOENCOGIBLE','tintas','PAM5',1,0,'Tintas','numero de tintas'),(6,'JPMTERMOENCOGIBLE','anchoEtiqueta','PAM6',1,1,'Ancho etiqueta','mm'),(7,'JPMTERMOENCOGIBLE','espacioFusion','PAM7',1,0,'Espacio fusiÃ³n','mm'),(8,'JPMTERMOENCOGIBLE','millaresPorRollo','PAM8',1,1,'Millares por rollo','millares'),(9,'JPMTERMOENCOGIBLE','porcentajeMPR','PAM9',1,0,'% +/- por rollo','% +/-'),(10,'JPMTERMOENCOGIBLE','millaresPorPaquete','PAM10',1,0,'Millares PP','millares '),(18,'JPSPoster05','tintas','',1,1,'Tintas','nÃƒÂºmero de tintas'),(21,'JPSVolantes07','tintas','',1,1,'Tintas','nÃƒÂºmero de tintas'),(22,'JPSVolantes07','alturaImpresion','',1,1,'Altura de la impresiÃ³n','mm'),(23,'JPSVolantes07','millaresPorPaquete','',1,1,'Millares PP','millares'),(24,'JPSVolantes07','codigoImpresion','',1,1,'codigo','codigo corto'),(25,'JPSVolantes07','descripcionImpresion','',1,1,'ImpresiÃƒÂ³n','descripciÃ³n'),(26,'JPSPoster05','codigoImpresion','',1,1,'codigo','codigo'),(27,'JPSPoster05','descripcionImpresion','',1,1,'DescripciÃ³n','C:'),(28,'PKPIMP','operador','PIMP1',1,1,'Operador','nombre'),(29,'PKPIMP','maquina','PIMP2',1,1,'Maquina','identificador'),(30,'PKPIMP','juegoCilindros','PIMP3',1,1,'Juego de Cilindros','identificador'),(31,'PKPIMP','lote','C',1,1,'Lote','referencia'),(32,'PKPIMP','total','PIMP5',0,0,'',''),(33,'PKPIMP','producto','PIMP6',0,0,'',''),(34,'PKPIMP','fecha','PIMP7',0,0,'',''),(35,'JPSFlexografia05','codigoImpresion','',1,1,'CÃ³digo','identificador'),(37,'JPSFlexografia05','anchoPelicula','',1,1,'Ancho pelicula','mm'),(38,'JPSFlexografia05','alturaEtiqueta','',1,1,'Altura de etiqueta','mm'),(39,'JPSFlexografia05','tintas','',1,1,'NÃºmero de tintas','numero'),(40,'JPSFlexografia05','anchoEtiqueta','',1,1,'Ancho etiqueta','mm'),(41,'JPSFlexografia05','espacioFusion','',1,1,'Espacio de fusiÃ³n','mm'),(42,'JPSFlexografia05','millaresPorRollo','',1,1,'Millares por rollo','millares'),(43,'JPSFlexografia05','porcentajeMPR','',1,1,'% Millares por rollo','%+/-'),(44,'JPSFlexografia05','millaresPorPaquete','',1,1,'MPP','millares'),(45,'JPSFlexografia05','descripcionImpresion','',1,1,'DescripciÃ³n','descripciÃ³n detallada'),(49,'PKPFUS','operador','0',1,1,'Operador','nombre'),(50,'PKPFUS','maquina','0',1,1,'Maquina','identificador'),(51,'PKPFUS','bobina','C',1,1,'Bobina','codigo'),(52,'PKPFUS','disco','0',1,1,'Disco(Referencia)','disco'),(57,'PKPDes','Lotesillo','C',1,0,'Lote','haha'),(58,'PKPIMP','longitud','G',1,1,'Longitud','mts'),(59,'PKPIMP','peso','G',1,1,'Peso','kilogramos'),(60,'PKPIMP','noop','PIMP8',0,0,'NOOP','haah'),(61,'PKPREF','operador','0',1,1,'Operador','nombre'),(62,'PKPREF','maquina','0',1,1,'Maquina','identificador'),(63,'PKPREF','lote','C',1,1,'Lote','codigo'),(64,'PKPREF','longitud','G',1,1,'Longitud','metros'),(65,'PKPREF','amplitud','G',1,1,'Amplitud','milimetros'),(66,'PKPREF','peso','G',1,1,'Peso','kilogramos'),(67,'PKPREF','noop','PAM2',0,0,'noop','noop');
/*!40000 ALTER TABLE `juegoparametros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juegoprocesos`
--

DROP TABLE IF EXISTS `juegoprocesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juegoprocesos` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `identificadorJuego` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionProceso` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `numeroProceso` int(2) NOT NULL,
  `referenciaProceso` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tablero` int(1) NOT NULL DEFAULT '1',
  `registro` int(1) NOT NULL DEFAULT '1',
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `descripcionProceso` (`descripcionProceso`),
  KEY `identificadorJuego` (`identificadorJuego`)
) ENGINE=InnoDB AUTO_INCREMENT=669 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juegoprocesos`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `juegoprocesos` WRITE;
/*!40000 ALTER TABLE `juegoprocesos` DISABLE KEYS */;
INSERT INTO `juegoprocesos` VALUES (20,'JPPManga01','programado',0,'PCSTER0',1,0,1),(21,'JPPManga01','impresion',1,'PCSTER1',1,1,1),(22,'JPPManga01','refilado',2,'PCSTER2',1,1,1),(23,'JPPManga01','fusion',3,'PCSTER3',1,1,1),(24,'JPPManga01','revision',4,'PCSTER4',1,1,1),(25,'JPPManga01','corte',5,'PCSTER5',1,1,1),(26,'JPPManga01','caja',0,'PCSTER6',1,0,1),(27,'JPPManga01','rollo',0,'PCSTER7',1,0,1),(45,'JPFlexografia05','programado',0,'PCS0',1,0,1),(46,'JPFlexografia05','impresion-flexografica',1,'PCS1',1,1,1),(48,'JPFlexografia05','fusion',3,'PCS3',1,1,1),(49,'JPFlexografia05','revision',4,'PCS4',1,1,1),(50,'JPFlexografia05','corte',5,'PCS5',1,1,1),(51,'JPFlexografia05','caja',0,'PCS6',1,0,1),(52,'JPFlexografia05','rollo',0,'PCS7',1,0,1),(53,'JPFlexografia05','Embarque',0,'PCS8',1,0,0),(54,'JPFlexografia05','refilado',2,'',1,1,1),(55,'JPPManga01','Embarque',0,'',1,0,0),(59,'JPBS08','programado',0,'PCS0',1,0,1),(60,'JPBS08','laminado',1,'PCS1',1,1,1),(61,'JPBS08','sliteo',2,'PCS2',1,1,1),(68,'JPPB08','programado',1,'PCS0',1,1,1),(69,'JPPB08','',2,'PCS1',1,1,1),(70,'JPPB08','',3,'PCS2',1,1,1),(71,'JPPB08','',4,'PCS3',1,1,1),(72,'JPPB08','',5,'PCS4',1,1,1),(73,'JPPB08','',6,'PCS5',1,1,1),(74,'JPawsda08','programado',0,'PCS0',1,1,1),(75,'JPawsda08','impresion',1,'PCS1',1,1,1),(76,'JPawsda08','laminado',2,'PCS2',1,1,1),(77,'JPawsda08','embosado',3,'PCS3',1,1,1),(78,'JPawsda08','revision',4,'PCS4',1,1,1),(79,'JPawsda08','rollo',0,'PCS5',1,1,1),(80,'JPawsda08','caja',0,'PCS6',1,1,1),(81,'JPAdilub10','programado',0,'PCS0',1,1,1),(82,'JPAdilub10','impresion',1,'PCS1',1,1,1),(83,'JPAdilub10','refilado',2,'PCS2',1,1,1),(84,'JPAdilub10','revision',3,'PCS3',1,1,1),(85,'JPQuindio10','programado',0,'PCS0',1,1,1),(86,'JPQuindio10','',1,'PCS1',1,1,1),(87,'JPQuindio10','',2,'PCS2',1,1,1),(88,'JPQuindio10','',3,'PCS3',1,1,1),(89,'JPQuindio10','',4,'PCS4',1,1,1),(90,'JPQuindio10','',5,'PCS5',1,1,1),(91,'JPQuindio10','',6,'PCS6',1,1,1),(92,'JPQuindio10','',7,'PCS7',1,1,1),(93,'JPPT11','programado',0,'PCS0',1,0,1),(94,'JPPT11','caja',0,'PCS0',1,0,1),(95,'JPPT11','rollo',0,'PCS0',1,0,1),(96,'JPPT11','corte',1,'PCS1',1,1,1);
/*!40000 ALTER TABLE `juegoprocesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juegoscilindros`
--

DROP TABLE IF EXISTS `juegoscilindros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juegoscilindros` (
  `IDCilindro` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionImpresion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identificadorCilindro` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `proveedor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaRecepcion` date NOT NULL,
  `diametro` float NOT NULL,
  `tabla` float NOT NULL,
  `registro` float NOT NULL,
  `repAlPaso` int(11) NOT NULL,
  `repAlGiro` float NOT NULL,
  `girosGarantizados` float NOT NULL,
  `viscosidad` float DEFAULT NULL,
  `velocidad` float DEFAULT NULL,
  `presionCilindro` float DEFAULT NULL,
  `presionGoma` float DEFAULT NULL,
  `presionRasqueta` float DEFAULT NULL,
  `tolViscosidad` float DEFAULT NULL,
  `tolVelocidad` float DEFAULT NULL,
  `tolCilindro` float DEFAULT NULL,
  `tolTemperatura` float DEFAULT NULL,
  `temperatura` float DEFAULT NULL,
  `tolGoma` float DEFAULT NULL,
  `tolRasqueta` float DEFAULT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `alturaReal` float NOT NULL,
  `anchuraReal` float DEFAULT NULL,
  `desgaste` float NOT NULL,
  `grupo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IDCilindro`),
  UNIQUE KEY `identificadorCilindro` (`identificadorCilindro`),
  KEY `identificadorCilindro_2` (`identificadorCilindro`),
  KEY `descripcionImpresion` (`descripcionImpresion`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juegoscilindros`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `juegoscilindros` WRITE;
/*!40000 ALTER TABLE `juegoscilindros` DISABLE KEYS */;
INSERT INTO `juegoscilindros` VALUES (3,'1','HTYPLAYRIO','Importados','2018-05-29',133,400,0,3,3,1000,2,2,2,2,2,2,2,2,2,2,2,2,1,139.277,133.333,0,''),(6,'7','WR8102335-A','Importados','2018-12-08',144.42,450,0,2,3,1000000,1,2,3,4,5,6,7,8,9,11,12,13,1,151.236,225,0,''),(7,'14','pruebs','Importados','2019-02-12',144.42,601,0,3,7,4,22,80,80,80,40,4,20,20,3,40,20,20,1,64.8155,200.333,0,''),(8,'14','W9011138','Importados','2019-02-19',138.7,601,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,72.6232,200.333,0,''),(9,'21','W9011140','Importados','2019-01-25',139.53,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,73.0577,222.333,0,''),(11,'29','ost-jeguin','Importados','2019-02-20',144.42,450,0,2,4,1000,0,0,0,0,0,0,0,0,0,0,0,0,1,113.427,220,0,''),(12,'33','WR8062736','Importados','2019-03-14',175,580,0,5,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,274.889,116,0,''),(13,'30','W9011139','Importados','2019-03-15',148.32,601,0,3,7,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,66.5659,200.333,0,''),(14,'31','W 8113037 - 1','Importados','2019-03-30',141.33,583,0,3,12,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,37.0001,194.333,0,''),(15,'32','W8113037-1','Importado','2019-03-30',141.52,583,0,3,12,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,37.0499,194.333,0,''),(16,'37','W 9011138','Importados','2019-04-06',138.7,601,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,72.6232,200.333,0,''),(17,'15','Z26225','IMPORTADOS','2019-04-22',133.83,455,0,2,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.0732,227.5,0,''),(18,'40','W9040206','IMPORTADOS','2019-04-29',146.72,600,0,7,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,230.467,85.7143,0,''),(19,'41','W9040205','IMPORTADOS','2019-04-29',147.16,600,0,7,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,231.158,85.7143,0,''),(20,'42','W8111034R','IMPORTADOS','2019-04-29',146.47,600,0,7,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,230.075,85.7143,0,''),(21,'43','W8111035','Importado','2019-04-29',175,600,0,5,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,274.889,120,0,''),(22,'44','W8111033R','IMPORTADOS','2019-04-29',175.3,600,0,5,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,275.361,120,0,''),(23,'45','WR8062735-1','Importados','2019-04-29',175,600,0,5,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,274.889,120,0,''),(24,'30','W9011139-1 ','Importado','2019-05-09',148.32,601,0,3,7,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,66.5659,200.333,0,''),(25,'49','Z26227-S','IMPORTADO','2019-05-23',133.96,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.1413,222.333,0,''),(26,'51','W9050903','IMPORTADO','2019-05-27',147.99,601,0,3,7,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,66.4178,200,0,''),(27,'52','W9050904','Importado','2019-05-27',138.67,601,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,72.6074,200.333,0,''),(28,'53','W9050905','IM PORTADO','2019-05-27',139.27,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,72.9216,222.333,0,''),(29,'52','Z26226','Importado','2019-05-28',133.81,601,0,3,6,1000000,0,0,0,0,0,0,0,0,0,0,0,0,0,70.0628,200,0,''),(30,'50','Z26226-1','Importado','2019-05-28',133.81,667,0,3,6,1000000,0,0,0,0,0,0,0,0,0,0,0,0,0,70.0628,222,0,''),(31,'48','Z26226 1','Importado','2019-05-28',133.81,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.0628,222.333,0,''),(32,'58','W9052207','IMPORTADO','2019-05-31',134.79,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.5759,222.333,0,''),(33,'59','W9052206','Importado','2019-05-31',133.72,455,0,2,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.0156,227.5,0,''),(34,'60','W9050903 2','IMPORTADO','2019-05-31',147.89,400,0,2,7,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,66.3729,200,0,''),(35,'50','Z26227-C','IMPORTADOS','2019-06-18',133.96,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.1413,222.333,0,''),(36,'38','W7072203','IMPORTADOS','2019-06-19',141.16,390,0,2,2,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,221.734,194,0,''),(37,'39','W7072203-1','IMPORTADOS','2019-06-19',141.16,390,0,2,12,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,36.9556,195,0,''),(39,'36','W7061205','Importado','2019-07-31',134.2,455,0,2,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.267,227.5,0,''),(40,'36','pruebacristal','importado','2019-09-23',34.5,455,0,2,7,1000000,0,0,0,0,0,0,0,0,0,0,0,0,0,15.4836,225,0,''),(41,'70','w9061307','IMPORTADOS','2019-07-02',133.96,715,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.1413,238.333,0,''),(43,'72','W8111431','Importado','2019-09-23',295.106,265,0,2,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,463.551,132.5,0,''),(44,'76','W8111432','IMPORTADO','2019-09-23',202,530,0,4,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,317.301,132.5,0,''),(45,'58','pruebaciel22','Importados','1997-07-14',134.79,667,0,3,6,10000000,0,0,0,0,0,0,0,0,0,0,0,0,0,70.5759,222,0,''),(48,'58','W9052207-1','IMPORTADOS','2020-01-06',134.79,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.5759,222.333,0,''),(49,'82','W9121206','IMPORTACION','2020-01-06',202,530,0,4,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,317.301,132.5,0,''),(50,'83','W9121205','IMPORTACION','2020-01-06',202,530,0,4,2,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,317.301,120,0,''),(52,'86','W9120914','Importados','2020-01-07',165,680,0,2,2,1000000,1,0,0,0,0,0,0,0,0,0,0,0,1,259.181,340,0,''),(53,'88','w9082804','IMPORTACION','2019-09-05',166,680,0,2,2,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,260.752,340,0,''),(54,'89','9120913','IMPORTACION','2020-01-07',166,680,0,2,2,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,260.752,321,0,''),(55,'48','Z26228C1','IMPORTACION','2020-01-07',133.81,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.0628,222.333,0,''),(58,'92','Z26227- S','ImportaciÃ³n','2019-12-19',133.96,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.1413,222.333,0,''),(62,'81','WR8071436','IMPORTACION','2019-12-16',151.83,600,0,10,1,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,476.988,60,0,''),(63,'93','Z26227 - S','IMPORTACION','2019-05-23',133.96,667,0,3,6,1000000,22,80,80,80,40,4,20,20,3,40,20,20,1,70.1413,222.333,0,''),(64,'90','W9120915','IMPORTACION','2020-01-07',166,680,0,2,2,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,260.752,340,0,''),(65,'87','W9121204','IMPORTACION','2020-02-28',165,680,0,2,2,1000000,0,0,0,0,0,0,0,0,0,0,0,0,1,259.181,321,0,'');
/*!40000 ALTER TABLE `juegoscilindros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juegoscireles`
--

DROP TABLE IF EXISTS `juegoscireles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juegoscireles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(50) NOT NULL,
  `identificadorJuego` varchar(40) NOT NULL,
  `num_dientes` float NOT NULL,
  `ancho_plano` float NOT NULL,
  `repeticiones` varchar(11) NOT NULL,
  `no_placa` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `alturaReal` float NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `descripcionImpresion` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificadorJuego` (`identificadorJuego`),
  KEY `identificadorJuego_2` (`identificadorJuego`),
  KEY `producto` (`producto`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juegoscireles`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `juegoscireles` WRITE;
/*!40000 ALTER TABLE `juegoscireles` DISABLE KEYS */;
INSERT INTO `juegoscireles` VALUES (1,'12','65677',5,400,'2',2,'2019-01-18','no sirve',72,1,'Pegatina Cuindio 4x5'),(3,'61','G00416',120,200,'1',0,'2019-06-17','N/A',63,0,''),(4,'62','G00419',120,200,'1',0,'2019-06-17','N/A',88,1,''),(6,'61','G00419-1',120,200,'1',0,'2019-06-17','N/A',88,1,''),(7,'63','G00416-1',120,300,'1',0,'2019-06-17','N/A',63,1,''),(9,'64','G00416-2',120,300,'1',0,'2019-06-17','N/A',63,1,''),(10,'65','G00556',80,320,'2',0,'2019-07-05','0',52,1,''),(18,'96','E46BABYLAM',60,165,'1',0,'2020-04-07','n a',141.28,1,''),(19,'97','5-4520',60,194,'1.114942528',0,'2020-05-20','n a',95,1,''),(21,'80',' FC150845',100,270,'2',0,'2020-06-01','estrella cristal 20 lts',313.5,1,''),(22,'103','FC150845',100,270,'2',0,'2020-06-01','estrella cristal 20L',313.5,1,''),(23,'105','7-6213',73,265,'1',0,'2020-07-22','gases',111,1,''),(26,'106','FC150845-1',150,295,'7',0,'2020-07-24','SELLOS GENOMMA LAB',40,1,''),(29,'107','7-6578',66,131,'1',0,'2020-07-31','Helado de cafÃ©',105,1,''),(30,'108','7-6577',66,131,'1',0,'2020-07-31','n a',105,1,''),(31,'109','7-6581',66,131,'1',0,'2020-07-31','n a',105,1,''),(32,'110','7-6580',66,131,'1',0,'2020-07-31','n a',105,1,''),(33,'111','7-6583',66,131,'1',0,'2020-07-31','n a',105,1,''),(34,'112','7-6576',66,131,'1',0,'2020-07-31','n a',105,1,''),(35,'113','7-6579',66,131,'1',0,'2020-07-31','n a',105,1,''),(36,'114','7-6582',66,131,'1',0,'2020-07-31','n a',105,1,''),(37,'115','7-6575',66,131,'1',0,'2020-07-31','n a',105,1,''),(40,'124','9-8434',66,131,'2',0,'2020-09-21','N/A',40,1,''),(41,'125','9-8435',66,145,'3',0,'2020-09-21','N/A',40,1,''),(42,'126','10-9148',66,131,'2',0,'2020-10-05','N/A',30,1,''),(43,'130','10-9235',66,70,'3',0,'2020-10-07','N/A',20.95,1,''),(44,'131','10-9234',66,70,'3',0,'2020-10-07','N/A',20.95,1,''),(45,'132','109233',66,70,'3',0,'2020-10-07','N/A',20.95,1,''),(48,'135','FC150845-2',150,285,'7',0,'2020-10-26','SELLOS GENOMA LAB',40,1,''),(49,'138','6-5435',66,131,'1',0,'2020-06-17','N/A',100,1,''),(50,'137','6-5437',66,131,'1',0,'2020-06-17','N/A',100,1,''),(51,'140','6-5436',66,131,'1',0,'2020-06-17','N/A',100,1,''),(52,'139','6-5434',66,131,'1',0,'2020-06-17','N/A',105,1,''),(53,'153','G01036',73,120,'1',0,'2020-12-04','N/A',57.94,1,''),(54,'154','G01037',73,120,'1',0,'2020-12-04','N/A',57.94,1,''),(55,'187','W262-009-0',66,340,'1',0,'2021-04-29','N/A',209.6,1,''),(66,'200','7-6235',80,314,'1',0,'2021-07-02','sae diesel 50',84.66,1,''),(67,'201','76235-',80,314,'1',0,'2021-07-02','SAE 40 DIESEL',81.5,1,''),(68,'202','W262-015-0',60,310,'1',0,'2021-06-10','CIREL KLINTEK CREMA LUSTRADORA',68,0,''),(69,'202','W262-15-0',60,310,'1',0,'2021-06-10','CIREL KLINTEK CREMA LUSTRADORA',95.25,1,''),(71,'204','W262-016-0',80,277,'1',0,'2021-06-10','CIREL KLINTEKCREMA DESENGRASANTE',110,1,''),(72,'205','M615IMPREV',68,270,'1',0,'2021-08-05','CIREL YPENSA CLOROFILA 500ml',215.9,1,''),(73,'206','M618IMPREV',68,270,'1',0,'2021-08-05','CIREL YPEFAR CLOROFILA 500ml',215.9,1,''),(74,'207','M617IMPREV',68,270,'1',0,'2021-08-05','CIREL L CARITINA 500 ml',215.9,1,''),(75,'208','M616IMPREV',68,270,'1',0,'2021-08-05','CIREL AMINOACIDOS  500ml',215.9,1,''),(76,'209','W262-011-0',73,210,'1',0,'2021-05-12','CIRELES B-WATER MANGA SELLO ',77,1,''),(77,'210','86024',94,310,'1',0,'2021-09-08','cirel coronado manga esp',298.5,1,''),(80,'216','85945',96,113,'1',0,'2021-09-09','CIREL TRUPER EXPERT 4oz',76.2,1,''),(84,'217','85946',66,206,'1',0,'2021-09-09','CIREL TRUPER EXPERT 16 oz',104.775,1,''),(85,'220','210921FONU',66,330,'1',0,'2021-09-21','N/A',52.3875,1,'');
/*!40000 ALTER TABLE `juegoscireles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!50001 DROP VIEW IF EXISTS `lotes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lotes` AS SELECT 
 1 AS `idLote`,
 1 AS `bloque`,
 1 AS `referenciaLote`,
 1 AS `fecha_alta`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `tarima`,
 1 AS `estado`,
 1 AS `shower`,
 1 AS `noop`,
 1 AS `ancho`,
 1 AS `espesor`,
 1 AS `idBloque`,
 1 AS `encogimiento`,
 1 AS `numeroLote`,
 1 AS `juegoLote`,
 1 AS `noLote`,
 1 AS `unidades`,
 1 AS `anchuraBloque`,
 1 AS `tipo`,
 1 AS `baja`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `lotestemporales`
--

DROP TABLE IF EXISTS `lotestemporales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotestemporales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noLote` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `longitud` float NOT NULL,
  `unidades` float NOT NULL,
  `anchura` float NOT NULL,
  `peso` float NOT NULL,
  `referencia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `noLote` (`noLote`),
  KEY `id` (`id`),
  KEY `referencia` (`referencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotestemporales`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `lotestemporales` WRITE;
/*!40000 ALTER TABLE `lotestemporales` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotestemporales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maquinas`
--

DROP TABLE IF EXISTS `maquinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maquinas` (
  `idMaq` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionMaq` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subproceso` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipoProducto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idMaq`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `descripcionMaq` (`descripcionMaq`),
  KEY `idMaq` (`idMaq`),
  KEY `codigo_2` (`codigo`),
  KEY `descripcionMaq_2` (`descripcionMaq`),
  KEY `subproceso` (`subproceso`),
  KEY `tipoProducto` (`tipoProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maquinas`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `maquinas` WRITE;
/*!40000 ALTER TABLE `maquinas` DISABLE KEYS */;
INSERT INTO `maquinas` VALUES (1,'MTIM03','Impresora Huecograbado (7 tintas/gris)','impresion','',1),(2,'MTRF01','Refiladora 01','refilado','',1),(3,'MTFU01','MÃ¡quina fusionadora 01','fusion','',1),(4,'MTRE01','Revisadora 01','revision','',1),(5,'MTCO01','Cortadora 01','corte','',1),(6,'MTLA01','MÃ¡quina laminadora','laminado','',1),(7,'MTSL01','Sliteo 01','sliteo','',1),(9,'MTFL01','Impresora con FlexografÃ­a 01','impresion-flexografica','',1),(10,'fds2452','astroboy','revision','',0),(11,'MTTR001','Troqueladora 01','troquelado','',1),(12,'MTSJ004','Suajadora 04','suajado','',1),(13,'MTFOL45','foliadora con diseÃ±o 1','foliado','',1),(14,'MTFL02','Impresora Flexografica 02','impresion-flexografica','',1),(15,'MTEM01','Embosadora 01','embosado','',1),(16,'MTIM01','Impresora Huecograbado (10 tintas)','impresion','',1),(17,'MTIM02','Impresora Huecograbado (7 tintas/crema)','impresion','',1),(18,'MTRF02','Refiladora 2','refilado','',1),(19,'MTRF03','Refiladora 3','refilado','',1),(20,'MTRF04','Refiladora 4','refilado','',1),(21,'MTRF05','Refiladora 5','refilado','',1),(22,'MTRF06','Refiladora 6','refilado','',1),(23,'MTRF07','Refiladora 7','refilado','',1),(24,'MTFU08','MÃ¡quina fusionadora 08','fusion','',1),(25,'MTRE09','Revisadora 09','revision','',1),(26,'MTRE04','Revisadora 04','revision','',1),(27,'MTRE03','Revisadora 03','revision','',1),(28,'MTFU07','Fusionadora 7','fusion','',1),(29,'MTRE08','Revisadora 08','revision','',1),(30,'MTCO08','Cortadora 08','corte','',1),(31,'MTFU03','Fusionadora 3','fusion','',1),(32,'MTFU04','Fusionadora 4','fusion','',1),(33,'MTFU05','Fusionadora 5','fusion','',1),(34,'MTFU06','Fusionadora 6','fusion','',1),(35,'MTCO09','Cortadora 09','corte','',1),(36,'MTCO07','Cortadora 07','corte','',1),(37,'MTCO06','Cortadora 06','corte','',1),(38,'MTRE02','Revisadora 02','revision','',1),(39,'MTCO05','Cortadora 05','corte','',1),(40,'MTRE07','Revisadora 07','revision','',1),(41,'MTRE05','Revisadora 05','revision','',1),(42,'MTCO03','Cortadora 03','corte','',1),(43,'MTCO10','Cortadora 10','corte','',1),(44,'MTCO02','Cortadora 02','corte','',1),(45,'MTRE06','Revisadora 06','revision','',1),(46,'MTCO04','Cortadora 04','corte','',1),(47,'MTRE10','Revisadora 10','revision','',1),(48,'MTRF08','Refiladora 8','refilado','',1),(49,'MTCO12','Cortadora 12','corte','',1),(50,'MTCO11','Cortadora 11','corte','',1),(51,'\"jejejjje','\'juijiiui','corte','',0);
/*!40000 ALTER TABLE `maquinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `merma`
--

DROP TABLE IF EXISTS `merma`;
/*!50001 DROP VIEW IF EXISTS `merma`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `merma` AS SELECT 
 1 AS `id`,
 1 AS `hora`,
 1 AS `codigo`,
 1 AS `unidadesIn`,
 1 AS `unidadesOut`,
 1 AS `longIn`,
 1 AS `longOut`,
 1 AS `banderas`,
 1 AS `producto`,
 1 AS `proceso`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Empleados','10001',1),(2,'Departamentos','10002',1),(3,'DiseÃ±os','20001',1),(4,'Impresiones','20002',1),(5,'Consumos','20003',1),(6,'Juego de Cilindros','20004',1),(7,'Banda de Seguridad','20005',1),(8,'Banda de Seguridad por Proceso','20006',1),(9,'Productos Cliente','20007',1),(10,'Bloques','30001',1),(11,'Lotes','30002',1),(12,'Sustrato','30003',1),(13,'Pantones','30004',1),(14,'Elementos','30005',1),(15,'Unidades de Medida','30006',1),(16,'Explosion de Materiales','30007',1),(17,'Clientes','40001',1),(18,'Contacto Clientes','40002',1),(19,'Sucursales','40003',1),(20,'Contacto Sucursales','40004',1),(21,'Orden Compra','40005',1),(22,'Requerimientos de Orden Compra','40006',1),(23,'Confirmaciones de Orden Compra','40007',1),(24,'Embarques','40008',1),(25,'Surtido de Embarques','40009',1),(26,'Devoluciones','40010',1),(27,'BitÃ¡cora','80001',1),(28,'Cambio de ContraseÃ±a','80002',1),(29,'Agregar Usuario','80003',1),(30,'Privilegios','80004',1),(31,'MiscelÃ¡neos','80005',1),(32,'Mapa','8006',1);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordencompra`
--

DROP TABLE IF EXISTS `ordencompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordencompra` (
  `idorden` int(11) NOT NULL AUTO_INCREMENT,
  `orden` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `documento` date NOT NULL,
  `recepcion` date NOT NULL,
  `bajaOrden` int(1) NOT NULL DEFAULT '1',
  `sucFK` int(11) NOT NULL,
  PRIMARY KEY (`idorden`),
  UNIQUE KEY `orden` (`orden`),
  KEY `sucFK` (`sucFK`)
) ENGINE=InnoDB AUTO_INCREMENT=1941 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordencompra`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `ordencompra` WRITE;
/*!40000 ALTER TABLE `ordencompra` DISABLE KEYS */;
INSERT INTO `ordencompra` VALUES (1,'ABRIL 163864','2019-05-01','2019-05-02',0,19),(2,'REPOSICION ','2019-05-15','2019-05-16',0,23),(3,'MAYO 166658','2019-05-16','2019-05-17',0,35),(4,'MAYO 166558','2019-05-16','2019-05-17',0,21),(5,'MAYO 169092','2019-05-16','2019-05-17',0,23),(6,'MAYO 168598','2019-05-16','2019-05-17',0,41),(7,'MAYO 170100','2019-05-16','2019-05-17',0,40),(8,'MAYO 163374','2019-05-01','2019-05-02',0,18),(9,'MAYO 166820','2019-05-16','2019-05-17',0,18),(10,'MAYO 166821','2019-05-16','2019-05-17',0,18),(11,'AGOSTO  171132','2019-05-16','2019-05-17',0,43),(12,'MAYO 172565','2019-05-16','2019-05-17',0,44),(13,'MAYO 167744','2019-05-16','2019-05-17',0,30),(15,'MAYO 165609','2019-05-17','2019-05-18',0,15),(16,'MAYO 170516','2019-05-17','2019-05-18',0,24),(18,'MAYO 169405','2019-05-20','2019-05-21',0,29),(19,'MAYO 10LTS 166820','2019-05-20','2019-05-21',0,18),(20,'MAYO 2DA 166821','2019-05-20','2019-05-21',0,18),(21,'JUNIO 173161','2019-05-21','2019-05-22',0,20),(22,'MAYO 169753','2019-05-20','2019-05-21',0,25),(24,'AGOSTO 10LTS ','2019-05-20','2019-05-21',0,43),(25,'JUNIO 470998','2019-05-20','2019-05-21',0,1),(26,'MAYO COMPLE 170890','2019-05-20','2019-05-21',0,6),(27,'JUNIO 170735','2019-05-20','2019-05-21',0,14),(28,'AGOSTO 170517','2019-05-20','2019-05-21',0,24),(29,'JUNIO 471189','2019-05-20','2019-05-21',0,2),(30,'JUNIO. 171198','2019-05-20','2019-05-21',0,18),(31,'JUNIO 2DA  171198','2019-05-20','2019-05-21',0,18),(32,'13014 METALIZADAS ','2019-05-20','2019-05-21',0,7),(33,'MAYO 14046','2019-05-20','2019-05-21',0,7),(34,'JUNIO 14008','2019-05-20','2019-05-21',0,7),(35,'JUNIO 471192','2019-05-20','2019-05-21',0,2),(36,'MAYO 465739','2019-05-20','2019-05-21',0,2),(37,'JUNIO 471267','2019-05-20','2019-05-21',0,46),(38,'JUNIO 471443','2019-05-20','2019-05-21',0,1),(39,'JUNIO  14158','2019-05-20','2019-05-21',0,7),(40,'JUNIO 14159','2019-05-20','2019-05-21',0,7),(41,'REPOSICION.','2019-05-21','2019-05-22',0,29),(42,'COMPLEMENTO 172401','2019-05-21','2019-05-22',0,26),(44,'AGOSTO 168598','2019-05-21','2019-05-22',0,41),(45,'AGOSTO 173301','2019-05-21','2019-05-22',0,43),(46,'JUNIO 172283','2019-05-21','2019-05-22',0,15),(47,'MAYO 170947','2019-05-21','2019-05-22',0,10),(49,'JUNIO 10LTS 171612','2019-05-21','2019-05-22',0,25),(50,'JULIO 182385','2019-05-21','2019-05-22',0,45),(51,'MAYO 170869','2019-05-21','2019-05-22',0,39),(52,'JUNIO 171070','2019-05-21','2019-05-22',0,42),(53,'JUNIO 171203','2019-05-21','2019-05-23',0,40),(54,'JUNIO 171207','2019-05-21','2019-05-22',0,34),(55,'JUNIO 171169','2019-05-21','2019-05-22',0,36);
/*!40000 ALTER TABLE `ordencompra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pantone`
--

DROP TABLE IF EXISTS `pantone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pantone` (
  `idPantone` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionPantone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigoPantone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `codigoHTML` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1',
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idPantone`),
  UNIQUE KEY `descripcionPantone_2` (`descripcionPantone`),
  KEY `descripcionPantone` (`descripcionPantone`),
  KEY `codigoPantone` (`codigoPantone`)
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantone`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `pantone` WRITE;
/*!40000 ALTER TABLE `pantone` DISABLE KEYS */;
INSERT INTO `pantone` VALUES (1,'PANTONE 2395 C','C800A1','C800A1',1,1),(2,'PANTONE 805 C','FF7276','FF7276',1,1),(4,'Pantone white C','FFF','FFF',1,1),(5,'pantone 133 C','6C571B','6C571B',1,1),(6,'PANTONE 180 C','BE3A34','BE3A34',1,1),(7,'Pantone 288 C','002D72','002D72',1,1),(8,'PANTONE 569 C','00816D','00816D',1,1),(9,'PANTONE 305 C','59CBE8','59CBE8',1,1),(10,'PANTONE 186 C','C8102E','C8102E',1,1),(11,'PANTONE 573 C','B5E3D8','B5E3D8',1,1),(12,'PANTONE 555 C','28724F','28724F',1,1),(13,'PANTONE 295 C','002855','002855',1,1),(14,'PANTONE 659 C','7BA4DB','7BA4DB',1,1),(15,'PANTONE 257 C','C6A1CF','C6A1CF',1,1),(16,'PANTONE 5145 C','9B7793','9B7793',1,1),(17,'PANTONE 7647 C','A83D72','A83D72',1,1),(18,'PANTONE 238 C','E45DBF','E45DBF',1,1),(19,'PANTONE 494 C','E9A2B2','E9A2B2',1,1),(21,'PANTONE 191 C','EF426F','EF426F',1,1),(22,'Pantone 123 C','FFC72C','FFC72C',1,1),(23,'Pantone 361 C','43B02A','43B02A',1,1),(24,'Process Black C','2D2926','2D2926',1,1),(25,'Process Cian C','009FDF','009FDF',1,1),(26,'Process Magenta c','D40F7D','D40F7D',1,1),(27,'Process Yellow C','FEDD00','FEDD00',1,1),(28,'Pantone 109 C','FFD100','FFD100',1,1),(29,'Pantone 334 C','009775','009775',1,1),(30,'Pantone 375 C','97D700','97D700',1,1),(31,'Pantone 368 C','78BE20','78BE20',1,1),(32,'Pantone Warm Red C','F9423A','F9423A',1,1),(33,'Pantone Process Blue C','0085CA','0085CA',1,1),(35,'Pantone 364 C','4A7729','4A7729',1,1),(37,'Pantone 534 C','1B365D','1B365D',1,1),(38,'Pantone 2945 C','004C97','004C97',1,1),(39,'Pantone 185 C','E4002B','E4002B',1,1),(40,'Process White C Alto C','FFFF','FFFF',1,1),(41,'Pantone 7712 C','00859B','00859B',1,1),(42,'Pantone 187 C','A6192E','A6192E',1,1),(43,'Pantone Red 032 C','EF3340','EF3340',1,1),(44,'Pantone 400 C','C4BFB6','C4BFB6',1,1),(45,'Pantone 7483','275D38','275D38',1,1),(46,'Pantone Warm Grey 4C','B6ADA5','B6ADA5',1,1),(47,'Pantone 286C','0033A0','0033A0',1,1),(48,'Pantone 2935C','0057B8','0057B8',1,1),(49,'PANTONE 2147C','002677','002677',1,1),(50,'Pantone 689 C','893B67','893B67',1,1),(52,'Pantone 617 C','C0B561','C0B561',1,1),(53,'Pantone 294 C','002F6C','002F6C',1,1),(54,'PANTONE 432 C','333F48','333F48',1,1),(55,'Pantone Cool Gray 5 C','B1B3B3','B1B3B3',1,1);
/*!40000 ALTER TABLE `pantone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pantonepcapa`
--

DROP TABLE IF EXISTS `pantonepcapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pantonepcapa` (
  `idPantonePCapa` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionPantone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `consumoPantone` float DEFAULT NULL,
  `disolvente` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigoImpresion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` int(1) DEFAULT '1',
  `codigoCapa` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idPantonePCapa`),
  KEY `descripcionPantone` (`descripcionPantone`),
  KEY `codigoImpresion` (`codigoImpresion`),
  KEY `codigoCapa` (`codigoCapa`)
) ENGINE=InnoDB AUTO_INCREMENT=1844 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pantonepcapa`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `pantonepcapa` WRITE;
/*!40000 ALTER TABLE `pantonepcapa` DISABLE KEYS */;
INSERT INTO `pantonepcapa` VALUES (23,'PANTONE 191 C',0.07,'70/30','23436',1,'C234360'),(24,'PANTONE 5145 C',0.9,'70/30','23436',1,'C234361'),(25,'Process Black C',0.66,'70/30','23436',1,'C234362'),(26,'Pantone white C',0.1,'70/30','M0503C22-01',1,'CM0503C22-010'),(27,'Pantone 288 C',0.2,'70/30','M0503C22-01',1,'CM0503C22-011'),(28,'Pantone 2945 C',0.3,'70/30','M0503C22-01',1,'CM0503C22-012'),(29,'PANTONE 569 C',0.4,'70/30','M0503C22-01',1,'CM0503C22-013'),(30,'Pantone 7712 C',0.5,'70/30','M0503C22-01',1,'CM0503C22-014'),(31,'Pantone Process Blue C',0.6,'70/30','M0503C22-01',1,'CM0503C22-015'),(32,'PANTONE 7647 C',0.1,'70/30','5353',1,'C53530'),(33,'PANTONE 555 C',0.2,'70/30','5353',1,'C53531'),(34,'PANTONE 2395 C',0,'70/30','vhgvh',1,'Cvhgvh0'),(35,'Pantone 7712 C',0,'70/30','vhgvh',1,'Cvhgvh1'),(36,'Pantone 361 C',0,'70/30','vhgvh',1,'Cvhgvh2'),(37,'Pantone 288 C',0,'70/30','vhgvh',1,'Cvhgvh3'),(38,'Pantone 187 C',0,'70/30','vhgvh',1,'Cvhgvh4'),(39,'Pantone 2945 C',0.061,'70/30','M341-1',1,'CM341-10'),(40,'Process Cian C',0.028,'70/30','M341-1',1,'CM341-11'),(41,'Pantone 7483',0.003,'70/30','M341-1',1,'CM341-12'),(42,'Pantone 185 C',0.003,'70/30','M341-1',1,'CM341-13'),(43,'Process Magenta c',0.016,'70/30','M341-1',1,'CM341-14'),(44,'Process Yellow C',0.006,'70/30','M341-1',1,'CM341-15'),(45,'Process White C Alto C',0.061,'R33','M341-1',1,'CM341-16'),(46,'Pantone Warm Grey 4C',0.018,'70/30','M0723C45-01',1,'CM0723C45-010'),(47,'Pantone 286C',0.02,'70/30','M0723C45-01',1,'CM0723C45-011'),(48,'Pantone 2935C',0.067,'70/30','M0723C45-01',1,'CM0723C45-012'),(49,'Process White C Alto C',0.07,'R33','M0723C45-01',1,'CM0723C45-013'),(71,'PANTONE WHITE C',0,'70/30','5353',1,'C53530'),(72,'PANTONE WHITE C',0,'70/30','5353',1,'C53531'),(73,'PANTONE WHITE C',0,'70/30','5353',1,'C53532'),(74,'PANTONE WHITE C',0,'70/30','5353',1,'C53533'),(75,'PANTONE WHITE C',0,'70/30','875233',1,'C8752330'),(76,'PANTONE WHITE C',0,'70/30','875233',1,'C8752331'),(77,'Pantone 2945 C',0.022,'70/30','M340-1',1,'CM340-10'),(78,'Process Cian C',0.028,'70/30','M340-1',1,'CM340-11'),(79,'Pantone 7483',0.003,'70/30','M340-1',1,'CM340-12'),(80,'Pantone 185 C',0.003,'70/30','M340-1',1,'CM340-13'),(81,'Process Magenta c',0.016,'70/30','M340-1',1,'CM340-14'),(82,'Process Yellow C',0.006,'70/30','M340-1',1,'CM340-15'),(83,'Process White C Alto C',0.061,'70/30','M340-1',1,'CM340-16'),(104,'PANTONE 180 C',0,'70/30','775',1,'C7750'),(105,'PANTONE 494 C',0,'70/30','775',1,'C7751'),(106,'Process Magenta c',0,'70/30','775',1,'C7752'),(107,'Pantone 2945 C',0.022,'70/30','M342-1',1,'CM342-10'),(108,'Process Cian C',0.003,'70/30','M342-1',1,'CM342-11'),(109,'Pantone 7483',0.003,'70/30','M342-1',1,'CM342-12'),(110,'Pantone 185 C',0.003,'70/30','M342-1',1,'CM342-13'),(111,'Process Magenta c',0.016,'70/30','M342-1',1,'CM342-14'),(112,'Process Yellow C',0.006,'70/30','M342-1',1,'CM342-15'),(113,'Process White C Alto C',0.061,'70/30','M342-1',1,'CM342-16');
/*!40000 ALTER TABLE `pantonepcapa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametros`
--

DROP TABLE IF EXISTS `parametros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreParametro` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `nombreParametro` (`nombreParametro`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametros`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `parametros` WRITE;
/*!40000 ALTER TABLE `parametros` DISABLE KEYS */;
INSERT INTO `parametros` VALUES (3,'operador','varchar(50)',1),(4,'bobina','varchar(50)',1),(5,'disco','varchar(50)',1),(6,'maquina','varchar(50)',1),(7,'longitud','float',1),(8,'peso','float',1),(9,'noop','int',1),(10,'lote','varchar(50)',1),(11,'amplitud','float',1),(12,'bandera','int',1),(13,'cdgDisco','varchar(50)',1),(14,'rollo','varchar(50)',1),(15,'unidades','float',1);
/*!40000 ALTER TABLE `parametros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procesos`
--

DROP TABLE IF EXISTS `procesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcionProceso` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `packParametros` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `abreviacion` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `process` int(1) NOT NULL DEFAULT '0',
  `divisiones` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `merma_p` float NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `descripcionProceso` (`descripcionProceso`),
  KEY `packParametros` (`packParametros`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procesos`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `procesos` WRITE;
/*!40000 ALTER TABLE `procesos` DISABLE KEYS */;
INSERT INTO `procesos` VALUES (2,'refilado','PKPREF','REF',0,'1',0,1),(3,'fusion','PKPFUS','FS',0,'1',0,1),(4,'sliteo','PKPSLIT','SLT',0,'1',0,1),(5,'revision','PKPREV','RVS',0,'0',0,1),(6,'corte','PKPCOR','CRT',0,'1',0,1),(7,'impresion','PKPIMP','IMP',0,'0',0,1),(8,'laminado','PKPLAM','LAM',0,'0',0,1),(9,'embosado','PKPEMB','EMB',0,'0',0,1),(10,'programado','PKPPGM','PGM',1,'0',0,1),(11,'caja','PKPC','C(Empaque)',1,'0',0,1),(12,'rollo','PKPR(Empaque)','R(Empaque)',1,'0',0,1),(14,'troquelado','PKPtro','TROQ',0,'0',0,1),(15,'suajado','PKPsua','SJD',0,'0',0,1),(16,'foliado','PKPfol','FLD',0,'1',0,1),(17,'revision 2','PKPrev2','RV2',0,'0',0,1),(18,'impresion-flexografica','PKPIMPFL','IMPFL',0,'0',0,1),(19,'laminado 2','PKPLAM2','LAM2',0,'',0,1);
/*!40000 ALTER TABLE `procesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `procorte`
--

DROP TABLE IF EXISTS `procorte`;
/*!50001 DROP VIEW IF EXISTS `procorte`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `procorte` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo`,
 1 AS `rollo_padre`,
 1 AS `tipo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!50001 DROP VIEW IF EXISTS `produccion`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `produccion` AS SELECT 
 1 AS `id`,
 1 AS `idProducto`,
 1 AS `fechamov`,
 1 AS `empleado`,
 1 AS `nombreProducto`,
 1 AS `juegoLotes`,
 1 AS `cantLotes`,
 1 AS `juegoCilindros`,
 1 AS `disenio`,
 1 AS `maquina`,
 1 AS `fechaProduccion`,
 1 AS `tipo`,
 1 AS `idtipo`,
 1 AS `unidades`,
 1 AS `suaje`,
 1 AS `juegoCireles`,
 1 AS `estado`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `consPre` int(11) NOT NULL DEFAULT '0',
  `baja` int(1) NOT NULL DEFAULT '1',
  `cilindros` int(11) NOT NULL DEFAULT '0',
  `cireles` int(11) NOT NULL DEFAULT '0',
  `suaje` int(11) NOT NULL DEFAULT '0',
  `refil` int(11) NOT NULL,
  `fus` int(11) NOT NULL,
  `holograma` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `descripcion` (`descripcion`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `tipo` (`tipo`),
  KEY `codigo_2` (`codigo`),
  KEY `descripcion_2` (`descripcion`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (3,'0014','Comex',31,1,1,1,0,0,1,0,0),(6,'0013','Generico Gepp',30,1,1,1,0,0,1,1,0),(16,'M341-1','Gen.Gepp3 97x74mm Bco 2019',30,0,0,1,0,0,1,1,1),(17,'M0723C45-01','Cristal Estrella2 107x70mm',30,0,0,1,0,0,1,1,1),(19,'0010','Cristal',30,0,1,1,0,0,1,1,1),(22,'0017','Ciel',30,0,1,1,0,0,1,1,1),(25,'0018','LÃ¡cteos Trujillo',35,0,0,0,1,1,1,0,0),(27,'040','Truper',40,0,1,0,1,1,1,1,0),(29,'041','Cristal Autoadherible 20L',41,0,1,1,0,1,1,0,0),(32,'042','Infasa',43,0,0,0,0,1,0,0,1),(33,'043','Sanofi-aventis Guatemala',43,0,0,0,0,1,0,0,1),(34,'','',0,0,0,0,0,0,0,0,0),(35,'044','Generix',43,0,0,0,0,1,0,0,1),(36,'045','Holograma Generico Foliado',43,0,0,0,0,1,0,0,1),(37,'046','Grupo Labro',43,0,0,0,0,1,0,0,1),(38,'047','Grupo Labro2',46,0,1,0,0,1,0,0,0),(39,'048','Cristal 20L Autoadh.Estrella',47,0,1,0,1,1,1,0,0),(41,'049','Cristal 20L Autoad.Transp.Liston',41,0,1,1,0,1,1,0,0),(42,'050','Cristal 20L Autoadher.Estrella Roto',41,0,1,1,0,1,1,0,0),(43,'051','Cristal 20L Autoadh.Gota Flexo',47,0,1,0,1,1,1,0,0),(44,'052','Raloy',30,0,0,1,0,0,1,1,0),(45,'053','Naturcol',43,0,1,0,0,1,0,0,1),(46,'45345','productin',40,1,0,0,1,1,1,1,1),(47,'054','SUEROX',30,0,1,1,0,0,1,1,0),(48,'055','BABY LAMB',35,0,0,0,1,1,1,0,0),(49,'056','KUNSTOFF',40,0,0,0,1,1,1,1,0),(51,'057','CARBONA/CYPA',30,0,0,1,0,0,1,1,0),(52,'059','Cristal 20L Autoadher.Estrella FX',48,0,1,0,1,1,0,0,0),(53,'060','MARINE PROV',40,0,1,0,1,1,1,1,0),(55,'061','GENOMMA LAB',53,0,1,0,1,1,1,0,0),(56,'062','BOLONIA',48,0,0,0,1,1,0,0,0),(57,'063','LABORATORIOS PISA',30,0,0,1,0,0,1,1,0),(58,'064','BOLONIA OCTAGONOS',54,0,1,0,1,1,1,0,0),(60,'065','OCTAVIO PADILLA',56,0,1,0,1,1,0,0,0),(61,'066','GENOMMA',57,0,1,0,0,0,0,0,0),(62,'067','GENOMMAL',59,0,1,0,1,1,0,0,0),(63,'068','GENOMMALB',60,0,1,0,1,1,0,0,0),(64,'069','LECHE LEON',30,0,0,1,0,0,1,1,0),(65,'070','IMPRESSIONE/TOSCANO',30,0,1,1,0,0,1,1,0),(66,'071','BOLONIA QZO',61,0,0,0,1,1,0,0,0),(67,'072','ARCACONTAL',30,0,1,1,0,0,1,1,0),(68,'073','IMPRESSIONE/TOSCANO FLEXO',40,0,0,0,1,1,1,1,0),(70,'075','B-WATER',62,0,1,0,1,1,1,1,0),(71,'079','QUAKER STATE',64,0,1,0,0,0,0,0,0),(72,'080','QUAKERSTATE',68,0,1,0,1,1,0,0,0),(73,'081','KUNSTOFF FLEXO',68,0,1,0,1,1,0,0,0),(74,'082','CORONADO',62,0,1,0,1,1,1,1,0),(75,'089','HERBAL PLANTS',62,0,1,0,1,1,1,1,0),(76,'090','ADILUB',68,0,1,0,1,1,0,0,0),(77,'091','PHYSIS',62,0,1,0,1,1,1,1,0);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productoscliente`
--

DROP TABLE IF EXISTS `productoscliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productoscliente` (
  `IdProdCliente` int(11) NOT NULL AUTO_INCREMENT,
  `IdentificadorCliente` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdProdCliente`),
  KEY `IdentificadorCliente` (`IdentificadorCliente`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productoscliente`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `productoscliente` WRITE;
/*!40000 ALTER TABLE `productoscliente` DISABLE KEYS */;
INSERT INTO `productoscliente` VALUES (1,'.0014','Comex',0),(2,'Comex-012','Etiqueta thinner estandar 960ml',0),(3,'1175071-032','Banda de garantia s/hol epura 88x37mm',1),(4,'0677443','Cuindio Pr',0),(5,'Gepp Generico-0018','Sello de garantia Hol.Generico Gepp3 97x68mm 2019',0),(6,'Gepp Generico-0018-2','Sello de garantia Hol.Gen.Gepp3 97x68mm 2019',0),(7,'Gepp Generico-0018-2','Sello de garantia Hol.Gen.Gepp3 97x74mm 2019',0),(8,'Bepensa-001-1','Sello de garantÃ­a termo-holografico2 Cristal Estrella107x70',0),(9,'Bepensa-001-1','Sello de garantÃ­a termo-holog.2 Cristal Estrella107x70',0),(10,'Gepp Generico-0018-3','Sello de garantia Hol.Gen.Gepp3 108x75mm 2019',0),(11,'Gepp Generico-0018','Sello de garantia Hol.Gen.Gepp3 97x68mm 2019',0),(12,'Bepensa-001-2','Sello de garantÃ­a termo-hol.3 Cristal Estrella107x70',0),(13,'Bepensa-014','Sello de Gar.Hol.Cristal Gota3 107x70mm',1),(14,'Bepensa-001-2','Sello Cristal Estrella3 107x70mm',0),(15,'Bepensa-001-2','Sello de Gar.Hol.Cristal Estrella3 107x70mm',1),(16,'Bepensa-001-3','Sello de Gar.Hol.Cristal Estrella3 107x70mm C/Ley.',1),(18,'Comex-012-3','Etiqueta Gasolina Blanca 960ml ',0),(19,'Bepensa-002','	Sello de garantÃ­a termo-holog.2 Cristal gota 107x70',0),(22,'Comex-012-1','Etiqueta Tinta al Alcohol 480 ml 230x85mm',0),(24,'Comex-012-4','Etiqueta Thinner Americano 960ml',0),(26,'Comex-012-8','Etiqueta Solvente para Poliuretano 500ml',0),(28,'Comex-012-9','Etiqueta Aguarras Sintetico 500ml',0),(29,'Comex-012-7','Etiqueta Thinner Meridian 960ml',0),(30,'Comex-012-6','Etiqueta Thinner Estandar 500ml',0),(32,'GEPP PROMOCION ','Manga Termoencogible promocion GEPP',0),(33,'TRUPER','Manga Termoencogible Truper',0),(34,'CIEL','Manga Termoencogible CIEL',1),(35,'LACTEOS TRUJILLO','Etiqueta Autoadherible',0),(36,'TRUPER','Manga Termoencogible Truper',1),(37,'BEPENSA','Cristal 20L Liston Autoadherible transparente',0),(38,'INFASA','Holograma Infasa Autoadherible 15x10mm',0),(39,'Bepensa-015','Sello de Gar.Hol.Cristal Gota3 115x70mm',0),(40,'SANOFI','Holograma Sanofi-aventis Guatemala auto-Adherible 22x22mm',0),(41,'BEPENSA-020','Etiqueta Cristal 20L Grande Blanca Autoadherible',0),(42,'GENERIX','Holograma Generix Autoadherible 25x10mm',0),(43,'ANTIVET','Holograma Generico Antivet 20x30mm',0),(44,'RALOY','Manga termoencogible Raloy',1),(45,'LABRO','Holograma Grupo Labro 21x16mm',0),(46,'LABRO2','Etiqueta Autoadherible 50x25mm',0),(47,'LABRO3','Etiqueta Autoadherible 90x50mm',0),(48,'LABRO4','Etiqueta Autoadherible 100x15mm',0),(49,'BEPENSA-021','Cristal 20L Estrella Autoadh.Flexo-Lam',1),(50,'BEPENSA-022','Cristal 20L Estrella Autoadh.Roto',1),(51,'BEPENSA-023','Cristal 20L Gota Autoadh.Flexo-Lam',1),(52,'NATURCOL','Holograma Naturcol Autoadherible 20x12mm',0),(53,'SUEROX','Manga Termoencogible Suerox',0),(54,'BABY LAMB','Etiqueta autoadherible Flx',0),(55,'KUNSTOFF','Manga termoencogible Sayer',0),(56,'CARBONA/CYPA','Manga termoencogible Carbona',0),(57,'MARINE PROV','Manga Termoencogible Flexo',1);
/*!40000 ALTER TABLE `productoscliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `proembosado`
--

DROP TABLE IF EXISTS `proembosado`;
/*!50001 DROP VIEW IF EXISTS `proembosado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `proembosado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `lote`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `peso`,
 1 AS `longitud`,
 1 AS `amplitud`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `profoliado`
--

DROP TABLE IF EXISTS `profoliado`;
/*!50001 DROP VIEW IF EXISTS `profoliado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `profoliado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `bobina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `peso`,
 1 AS `longitud`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `profusion`
--

DROP TABLE IF EXISTS `profusion`;
/*!50001 DROP VIEW IF EXISTS `profusion`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `profusion` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `bobina`,
 1 AS `longitud`,
 1 AS `amplitud`,
 1 AS `bandera`,
 1 AS `disco`,
 1 AS `cdgDisco`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `proimpresion`
--

DROP TABLE IF EXISTS `proimpresion`;
/*!50001 DROP VIEW IF EXISTS `proimpresion`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `proimpresion` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `juegoCilindros`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `proimpresion-flexografica`
--

DROP TABLE IF EXISTS `proimpresion-flexografica`;
/*!50001 DROP VIEW IF EXISTS `proimpresion-flexografica`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `proimpresion-flexografica` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `juegoCireles`,
 1 AS `suaje`,
 1 AS `anillox`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prolaminado`
--

DROP TABLE IF EXISTS `prolaminado`;
/*!50001 DROP VIEW IF EXISTS `prolaminado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prolaminado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `amplitud`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prolaminado 2`
--

DROP TABLE IF EXISTS `prolaminado 2`;
/*!50001 DROP VIEW IF EXISTS `prolaminado 2`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prolaminado 2` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `amplitud`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prorefilado`
--

DROP TABLE IF EXISTS `prorefilado`;
/*!50001 DROP VIEW IF EXISTS `prorefilado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prorefilado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `amplitud`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prorevision`
--

DROP TABLE IF EXISTS `prorevision`;
/*!50001 DROP VIEW IF EXISTS `prorevision`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prorevision` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `rollo`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prorevision 2`
--

DROP TABLE IF EXISTS `prorevision 2`;
/*!50001 DROP VIEW IF EXISTS `prorevision 2`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prorevision 2` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `rollo`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prosliteo`
--

DROP TABLE IF EXISTS `prosliteo`;
/*!50001 DROP VIEW IF EXISTS `prosliteo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prosliteo` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `lote`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `amplitud`,
 1 AS `bandera`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `prosuajado`
--

DROP TABLE IF EXISTS `prosuajado`;
/*!50001 DROP VIEW IF EXISTS `prosuajado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `prosuajado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `rollo`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `suaje`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `protroquelado`
--

DROP TABLE IF EXISTS `protroquelado`;
/*!50001 DROP VIEW IF EXISTS `protroquelado`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `protroquelado` AS SELECT 
 1 AS `id`,
 1 AS `total`,
 1 AS `producto`,
 1 AS `fecha`,
 1 AS `noop`,
 1 AS `unidades`,
 1 AS `operador`,
 1 AS `maquina`,
 1 AS `rollo_padre`,
 1 AS `tipo`,
 1 AS `rollo`,
 1 AS `longitud`,
 1 AS `peso`,
 1 AS `suaje`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `pruebas`
--

DROP TABLE IF EXISTS `pruebas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pruebas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dato` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `entero` int(11) NOT NULL,
  `contrasenia` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pruebas`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `pruebas` WRITE;
/*!40000 ALTER TABLE `pruebas` DISABLE KEYS */;
INSERT INTO `pruebas` VALUES (1,'jhjjhfjh',56,'');
/*!40000 ALTER TABLE `pruebas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporte`
--

DROP TABLE IF EXISTS `reporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `registro` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90510 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporte`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `reporte` WRITE;
/*!40000 ALTER TABLE `reporte` DISABLE KEYS */;
INSERT INTO `reporte` VALUES (1,'fmedina','Agrego la mÃ¡quina:  MTCO04','MÃ¡quinas','ProducciÃ³n','2019-05-03 16:03:54'),(2,'aalvarez','Agrego al empleado Num:  072','Personal','Recursos Humanos','2019-05-06 10:26:04'),(3,'aalvarez','Agrego al empleado Num:  073','Personal','Recursos Humanos','2019-05-06 10:26:30'),(4,'aalvarez','Actualizo al empleado Num: 073','Personal','Recursos Humanos','2019-05-06 10:27:14'),(5,'core','Agrego un lote al bloque:  2','Lotes','Materia Prima','2019-05-08 17:22:39'),(6,'core','Agrego un lote al bloque:  2','Lotes','Materia Prima','2019-05-08 17:22:56'),(7,'mmendoza','Agrego el juego de cilindro:  W9011139-1 ','Juegos Cilindro','Productos','2019-05-09 08:58:48'),(8,'liraTI','Agrego un lote al bloque:  2','Lotes','Materia Prima','2019-05-09 11:07:04'),(9,'liraTI','Agrego un lote al bloque:  2','Lotes','Materia Prima','2019-05-09 11:07:46'),(10,'abelmonte','Actualizo al cliente con RFC: GEP150516R23','Clientes','Logistica','2019-05-09 11:29:05'),(11,'abelmonte','Desactivo la orden: 13896','Orden Compra','Logistica','2019-05-09 11:31:00'),(12,'abelmonte','Desactivo la orden: 454602','Orden Compra','Logistica','2019-05-09 11:31:03'),(13,'abelmonte','Desactivo la orden: 19022019','Orden Compra','Logistica','2019-05-09 11:31:06'),(14,'abelmonte','Desactivo la orden: PRue30','Orden Compra','Logistica','2019-05-09 11:31:09'),(15,'abelmonte','Desactivo la orden: 977','Orden Compra','Logistica','2019-05-09 11:31:10'),(16,'abelmonte','Desactivo la orden: 12916','Orden Compra','Logistica','2019-05-09 11:31:11'),(17,'abelmonte','Desactivo la orden: 12845','Orden Compra','Logistica','2019-05-09 11:31:12'),(18,'abelmonte','Activo la orden: 13896','Orden Compra','Logistica','2019-05-09 12:18:17'),(19,'abelmonte','Actualizo un requerimiento con la orden de compra: 7','Requerimiento Producto','Logistica','2019-05-09 12:18:40'),(20,'abelmonte','Desactivo un requerimiento con la orden de compra: 7','Requerimiento Producto','Logistica','2019-05-09 12:18:42'),(21,'abelmonte','Desactivo la orden: 13896','Orden Compra','Logistica','2019-05-09 12:19:31'),(22,'abelmonte','Activo la orden: 13896','Orden Compra','Logistica','2019-05-09 12:21:10'),(23,'abelmonte','Desactivo la orden: 13896','Orden Compra','Logistica','2019-05-09 12:21:24'),(24,'abelmonte','Elimino la orden: 13896','Orden Compra','Logistica','2019-05-09 12:21:31'),(25,'abelmonte','Activo la orden: 454602','Orden Compra','Logistica','2019-05-09 12:21:36'),(26,'abelmonte','Activo la orden: PRue30','Orden Compra','Logistica','2019-05-09 12:21:39'),(27,'abelmonte','Activo la orden: 19022019','Orden Compra','Logistica','2019-05-09 12:21:44'),(28,'abelmonte','Activo la orden: 977','Orden Compra','Logistica','2019-05-09 12:21:48'),(29,'abelmonte','Activo la orden: 12916','Orden Compra','Logistica','2019-05-09 12:21:52'),(30,'abelmonte','Activo la orden: 12845','Orden Compra','Logistica','2019-05-09 12:21:56'),(31,'abelmonte','Actualizo un requerimiento con la orden de compra: 6','Requerimiento Producto','Logistica','2019-05-09 12:22:17'),(32,'abelmonte','Desactivo un requerimiento con la orden de compra: 6','Requerimiento Producto','Logistica','2019-05-09 12:22:19'),(33,'abelmonte','Desactivo la orden: 454602','Orden Compra','Logistica','2019-05-09 12:23:23'),(34,'abelmonte','Elimino la orden: 454602','Orden Compra','Logistica','2019-05-09 12:23:29'),(35,'abelmonte','Actualizo un requerimiento con la orden de compra: 5','Requerimiento Producto','Logistica','2019-05-09 12:23:57'),(36,'abelmonte','Desactivo un requerimiento con la orden de compra: 5','Requerimiento Producto','Logistica','2019-05-09 12:24:07'),(37,'abelmonte','Desactivo la orden: 19022019','Orden Compra','Logistica','2019-05-09 12:24:19'),(38,'abelmonte','Elimino la orden: 19022019','Orden Compra','Logistica','2019-05-09 12:24:22'),(39,'abelmonte','Desactivo el embarque: 20190327002','Embarque','Logistica','2019-05-09 12:35:43'),(40,'abelmonte','Desactivo el embarque: 20190327001','Embarque','Logistica','2019-05-09 12:35:57'),(41,'abelmonte','Elimino el embarque: 20190327002','Embarque','Logistica','2019-05-09 12:36:03'),(42,'abelmonte','Elimino el embarque: 20190327001','Embarque','Logistica','2019-05-09 12:36:07'),(43,'abelmonte','Desactivo el embarque: 20190201001','Embarque','Logistica','2019-05-09 12:37:11'),(44,'abelmonte','Desactivo el embarque: 20190105001','Embarque','Logistica','2019-05-09 12:37:14'),(45,'abelmonte','Desactivo el embarque: 20190102001','Embarque','Logistica','2019-05-09 12:37:15'),(46,'abelmonte','Elimino el embarque: 20190201001','Embarque','Logistica','2019-05-09 12:37:20'),(47,'abelmonte','Elimino el embarque: 20190105001','Embarque','Logistica','2019-05-09 12:37:24'),(48,'abelmonte','Elimino el embarque: 20190102001','Embarque','Logistica','2019-05-09 12:37:28'),(49,'abelmonte','Desactivo un requerimiento con la orden de compra: 4','Requerimiento Producto','Logistica','2019-05-09 12:38:07'),(50,'abelmonte','Desactivo la orden: PRue30','Orden Compra','Logistica','2019-05-09 12:38:31');
/*!40000 ALTER TABLE `reporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requerimientoprod`
--

DROP TABLE IF EXISTS `requerimientoprod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `requerimientoprod` (
  `idReq` int(11) NOT NULL AUTO_INCREMENT,
  `cantReq` decimal(9,3) NOT NULL,
  `refeReq` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaReq` int(1) NOT NULL DEFAULT '1',
  `ordenReqFK` int(11) NOT NULL,
  `prodcliReqFK` int(11) NOT NULL,
  PRIMARY KEY (`idReq`)
) ENGINE=InnoDB AUTO_INCREMENT=2101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requerimientoprod`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `requerimientoprod` WRITE;
/*!40000 ALTER TABLE `requerimientoprod` DISABLE KEYS */;
INSERT INTO `requerimientoprod` VALUES (1,56.720,'BOBINA ',1,1,10),(2,2.500,'CORTE ',1,2,7),(3,325.970,'BOBINA ',0,3,10),(4,200.000,'CORTE ',1,4,3),(5,226.730,'',1,5,10),(6,616.510,'',1,6,10),(7,287.960,'',1,6,7),(8,198.000,'',1,7,11),(9,0.000,'',1,8,3),(10,276.000,'',1,9,3),(11,1182.000,'',1,10,11),(12,0.000,'',0,11,3),(13,1117.410,'',1,11,3),(14,180.730,'',1,11,10),(15,536.560,'',1,11,7),(16,200.000,'',1,12,11),(17,260.000,'',1,12,7),(18,576.000,'CORTE ',1,13,7),(19,70.000,'',0,14,7),(20,701.512,'',1,15,7),(21,320.000,'',1,15,3),(22,306.000,'',0,16,7),(23,306.000,'',0,16,7),(24,306.000,'',1,16,11),(25,282.278,'BOBINA ',1,3,10),(26,8.000,'',1,124,7),(27,0.000,'',1,123,7),(28,180.000,'CORTE ',1,18,7),(29,436.000,'',1,19,3),(30,1600.000,'',1,20,11),(31,297.000,'',1,21,7),(32,75.000,'BOBINA ',1,22,3),(33,349.000,'',1,22,7),(34,14.000,'',1,122,3),(35,1600.000,'',1,24,3),(36,124.176,'BOBINA ',1,26,3),(37,28.000,'',0,25,15),(38,0.000,'',0,27,11),(39,540.000,'',1,28,11),(40,490.000,'',0,29,19),(41,1600.000,'',1,30,11),(42,240.000,'',1,30,3),(43,1600.000,'',1,31,11),(44,152.000,'',1,31,3),(45,22.030,'',1,33,26),(46,0.000,'',0,34,2),(47,490.000,'',1,29,13),(48,490.000,'',1,35,13),(49,181.500,'',1,36,13),(50,420.000,'',1,37,13);
/*!40000 ALTER TABLE `requerimientoprod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rollo`
--

DROP TABLE IF EXISTS `rollo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rollo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `noElementos` int(100) NOT NULL,
  `longitud` float NOT NULL,
  `piezas` decimal(9,3) NOT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cdgEmbarque` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `peso` float NOT NULL,
  `cdgDev` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `fechamov` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `referencia` (`referencia`),
  KEY `producto` (`producto`),
  KEY `codigo` (`codigo`),
  KEY `cdgEmbarque` (`cdgEmbarque`)
) ENGINE=InnoDB AUTO_INCREMENT=41567 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rollo`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `rollo` WRITE;
/*!40000 ALTER TABLE `rollo` DISABLE KEYS */;
INSERT INTO `rollo` VALUES (1,'Q1','14',3,1489,20.503,'0143790001','20190517002',18.98,'','2019-05-14 07:57:30',3),(2,'Q2','14',3,1738,23.932,'0143790002','20190517002',21.8,'','2019-05-14 07:57:58',3),(3,'Q3','14',3,1776,24.455,'0143790003','20190517002',21.9,'','2019-05-14 08:15:16',3),(4,'Q4','14',3,1755,24.166,'0143790004','20190517002',21.88,'','2019-05-14 08:15:51',3),(5,'Q5','14',3,1645,22.651,'0143790005','20190517002',20.54,'','2019-05-14 08:53:38',3),(6,'Q6','14',3,1719,23.670,'0143790006','20190517002',21.4,'','2019-05-14 09:19:37',3),(7,'Q7','14',3,1731,23.835,'0143790007','20190517002',21.62,'','2019-05-14 09:28:36',3),(8,'Q8','14',3,1718,23.656,'0143790008','20190517002',22.22,'','2019-05-14 09:28:52',3),(9,'Q9','14',3,1672,23.023,'0143790009','20190517002',20.49,'','2019-05-14 09:29:08',3),(10,'Q10','14',3,1634,22.500,'0143790010','20190517002',20.41,'','2019-05-14 09:54:17',3),(11,'Q11','14',3,1616,22.252,'0143790011','20190517002',20.09,'','2019-05-14 09:57:12',3),(12,'Q12','14',3,1727,23.780,'0143790012','20190517002',21.46,'','2019-05-14 09:57:25',3),(13,'Q13','14',3,1636,22.527,'0143790013','20190517002',20.5,'','2019-05-14 14:43:52',3),(14,'Q14','14',3,1509,20.778,'0143790014','20190517002',16.63,'','2019-05-14 15:20:30',3),(15,'Q15','14',3,1533,21.109,'0143790015','20190517002',16.79,'','2019-05-14 15:37:02',3),(16,'Q16','14',3,1664,22.913,'0143790016','20190517002',20.7,'','2019-05-14 16:29:50',3),(17,'Q17','14',3,1793,24.689,'0143790017','20190517002',22.31,'','2019-05-14 16:44:54',3),(18,'Q18','14',3,1748,24.069,'0143790018','20190517002',21.88,'','2019-05-14 16:45:10',3),(19,'Q19','14',3,1720,23.684,'0143790019','20190517002',21.32,'','2019-05-14 17:10:14',3),(20,'Q20','14',3,1669,22.982,'0143790020','20190517002',20.81,'','2019-05-15 08:06:42',3),(21,'Q21','14',3,1600,22.032,'0143790021','20190517002',17.68,'','2019-05-15 08:31:24',3),(22,'Q22','14',3,1680,23.133,'0143790022','20190517002',20.75,'','2019-05-15 08:39:07',3),(23,'Q23','14',3,1538,21.178,'0143790023','20190517002',17.4,'','2019-05-15 08:51:34',3),(24,'Q24','14',3,1569,21.605,'0143790024','20190517002',17.3,'','2019-05-15 08:52:03',3),(25,'Q25','14',3,1486,20.462,'0143790025','20190517002',16.06,'','2019-05-15 08:52:16',3),(26,'Q26','14',3,1527,21.026,'0143790026','20190517002',16.46,'','2019-05-15 09:44:28',3),(27,'Q27','14',3,1518,20.902,'0143790027','20190517002',16.51,'','2019-05-16 08:33:59',3),(28,'Q28','14',3,1547,21.302,'0143790028','20190520004',16.82,'','2019-05-16 08:34:13',3),(29,'Q29','14',3,1659,22.844,'0143790029','20190520004',17.53,'','2019-05-16 08:46:47',3),(30,'Q30','14',3,1579,21.742,'0143790030','20190520004',17.08,'','2019-05-16 08:54:10',3),(31,'Q31','14',3,1541,21.219,'0143790031','20190520004',16.92,'','2019-05-16 09:10:03',3),(32,'Q32','14',3,1674,23.050,'0143790032','20190520004',17.78,'','2019-05-16 09:18:17',3),(33,'Q33','14',3,1506,20.737,'0143790033','20190520004',16.13,'','2019-05-16 10:02:24',3),(34,'Q34','14',3,1599,22.018,'0143790034','20190520004',17.4,'','2019-05-16 10:02:36',3),(35,'Q35','14',3,1548,21.316,'0143790035','20190520004',16.68,'','2019-05-16 10:24:42',3),(36,'Q36','14',3,1619,22.293,'0143790036','20190520004',17.33,'','2019-05-16 10:24:55',3),(37,'Q37','14',3,1535,21.136,'0143790037','20190520004',16.66,'','2019-05-16 10:25:11',3),(38,'Q38','14',3,1650,22.720,'0143790038','20190520004',17.66,'','2019-05-16 11:11:34',3),(39,'Q39','14',3,1541,21.219,'0143790039','20190522003',16.69,'','2019-05-16 11:19:57',3),(40,'Q40','14',3,1563,21.522,'0143790040','20190522003',16.96,'','2019-05-16 11:21:52',3),(41,'Q41','14',3,1599,22.018,'0143790041','20190522003',17.04,'','2019-05-16 12:08:07',3),(42,'Q42','14',3,1527,21.026,'0143790042','20190522003',16.5,'','2019-05-16 12:11:44',3),(43,'Q43','14',3,1557,21.439,'0143790043','20190522003',16.78,'','2019-05-16 12:12:13',3),(44,'Q44','14',3,1635,22.513,'0143790044','20190522003',20.45,'','2019-05-17 16:22:34',3),(45,'Q45','14',3,1592,21.921,'0143790045','20190522003',16.97,'','2019-05-17 16:22:53',3),(46,'Q46','14',3,1548,21.316,'0143790046','20190522003',16.58,'','2019-05-17 16:51:56',3),(47,'Q47','14',3,1753,24.138,'0143790047','20190522003',16.89,'','2019-05-17 16:52:19',3),(48,'Q48','14',3,1567,21.577,'0143790048','20190522003',16.45,'','2019-05-17 16:52:33',3),(50,'Q50','14',3,1523,20.971,'0143790050','20190522003',16.76,'','2019-05-20 07:55:47',3),(51,'Q51','14',3,1634,22.500,'0143790051','20190522003',17.84,'','2019-05-20 07:56:21',3);
/*!40000 ALTER TABLE `rollo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `shoTableroBS`
--

DROP TABLE IF EXISTS `shoTableroBS`;
/*!50001 DROP VIEW IF EXISTS `shoTableroBS`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `shoTableroBS` AS SELECT 
 1 AS `productos`,
 1 AS `descripcionImpresion`,
 1 AS `idImpresion`,
 1 AS `bajaBSPP`,
 1 AS `baja`,
 1 AS `id`,
 1 AS `necesidad`,
 1 AS `sustrato`,
 1 AS `nombreBanda`,
 1 AS `anchura`,
 1 AS `alturaEtiqueta`,
 1 AS `tintas`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `showTablero`
--

DROP TABLE IF EXISTS `showTablero`;
/*!50001 DROP VIEW IF EXISTS `showTablero`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `showTablero` AS SELECT 
 1 AS `idtipo`,
 1 AS `fecha_alta`,
 1 AS `codigoImpresion`,
 1 AS `observaciones`,
 1 AS `tipo`,
 1 AS `productos`,
 1 AS `descripcionImpresion`,
 1 AS `holograma`,
 1 AS `idImpresion`,
 1 AS `idsustrato`,
 1 AS `sustrato`,
 1 AS `nombreBanda`,
 1 AS `anchoPelicula`,
 1 AS `alturaEtiqueta`,
 1 AS `tintas`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `suaje`
--

DROP TABLE IF EXISTS `suaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suaje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorSuaje` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `proveedor` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `alturaImpresion` float NOT NULL,
  `anchuraImpresion` float NOT NULL,
  `piezas` int(11) NOT NULL,
  `alturaReal` float NOT NULL,
  `corteSeguridad` float NOT NULL,
  `descripcionImpresion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `reguardo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `tipoherr` int(1) NOT NULL DEFAULT '1' COMMENT '1 es suaje,2 es master',
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificadorSuaje` (`identificadorSuaje`),
  UNIQUE KEY `codigo_2` (`codigo`),
  KEY `identificadorSuaje_2` (`identificadorSuaje`),
  KEY `codigo` (`codigo`),
  KEY `descripcionImpresion` (`descripcionImpresion`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suaje`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `suaje` WRITE;
/*!40000 ALTER TABLE `suaje` DISABLE KEYS */;
INSERT INTO `suaje` VALUES (2,'S - CNG - 01','KOLOR GRAPHS','G00419-1',85,170,1,94,0,'61','N/A','FLEXOGRAFIA','<br />\r\n<b>Notice</b>:  Undefined index:',0,1),(3,'S -QF - 01','KOLOR GRAPHS','G00419',85,170,1,94,0,'62','N/A','FLEXOGRAFIA','<br />\r\n<b>Notice</b>:  Undefined index:',0,1),(4,'S - CNC - 01','KOLOR GRAPHS','G00416-1',60,240,1,64,0,'63','N/A','FLEXOGRAFIA','<br />\r\n<b>Notice</b>:  Undefined index:',0,1),(5,'S - QA - 01','KOLOR GRAPHS','G00416-2',60,240,1,64,0,'64','N/A','FLEXOGRAFIA','<br />\r\n<b>Notice</b>:  Undefined index:',0,1),(7,'SANOFISUAJE1109','Importado','N',22,22,8,22,0,'71','N/A','Produccion','14',0,1),(8,'INFASASUAJE1109','Importado','N/A',10,15,10,20.1,0,'69','N/A','Produccion','<br />\r\n<b>Notice</b>:  Undefined index:',0,1),(9,'655','6756','657567',313.5,120,2,463.551,0,'72','thg','gvbv','<br />\r\n<b>Notice</b>:  Undefined index:',0,0),(10,'SUJC20L-1','Importados','SUJC20L-1',313.5,120,2,463.551,0,'72','N/A','N/A','15',0,1),(12,'GENSUJ-01','Importados','GENSUJ-01',15,25,6,34,0,'74','N/A','N/A','14',1,1),(13,'suaje cristal gota gde autoad','IMPORTADOS','sjc01',313.5,120,2,317.301,0,'83','cristal Gota Blanca','holografia','15',1,1),(23,'suaje estrella ancho','IMPORTACION','SUJCEGDEA-01',313.5,120,2,317.301,0,'82','estrella cristal 20 lts','holografia','15',1,1),(24,'suaje naturcol','Apple Die',': T21',12,20,8,26.5,0,'91','n a','holografia','15',1,0),(34,'suaje naturcol1','IMPORTACION','T21',12,20,8,26.5,0,'91','n a','produccion','14',1,1),(35,'BABYLAMB','flexible cutting systems','FC134446',180,145,1,190.5,0,'96','n/a','holografia','15',1,1),(38,'suaje aventis sanofia ','IMPORTACION','T-27',22,22,7,22,0,'98','SUAJE DE SANOFI ADVENTIS ','holografia','15',1,1),(50,'FC150845-1','Importacion','FC150845-1',313.5,120,2,317.9,0,'103','Cristal Estrella Grande','Holografia','18',1,1),(51,'FC160503','FLEXIBLE CUTTINGS SYSTEM','FC160503',35,280,7,40,0,'135','SELLOS GENOMMA LAB','Holografia','15',1,1),(54,'Suaje Bolonia','Flexible Custom Systems','FC159431',100,100,1,105,0,'107','Suajes Bolonia','HolografÃ­a','15',1,1),(59,'SUAJE BOLONIA-1','flexible cutting systems','FC159431-1',100,100,1,105,0,'108','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(60,'SUAJE BOLONIA-2','flexible cutting systems','FC159431-2',100,100,1,105,0,'109','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(61,'SUAJE BOLONIA-3','flexible cutting systems','FC159431-3',100,100,1,105,0,'110','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(62,'SUAJE BOLONIA-4','flexible cutting systems','FC159431-4',100,100,1,105,0,'111','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(63,'SUAJE BOLONIA-5','flexible cutting systems','FC159431-5',100,100,1,105,0,'112','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(64,'SUAJE BOLONIA-6','flexible cutting systems','FC159431-6',100,100,1,105,0,'113','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(65,'SUAJE BOLONIA-7','flexible cutting systems','FC159431-7',100,100,1,105,0,'114','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(66,'SUAJE BOLONIA-8','flexible cutting systems','FC159431-8',100,100,1,105,0,'115','SUAJE HELADOS DE BOLONIA','holografia','15',1,1),(69,'FC165967','FLEXIBLE CUTTING SYSTEMS','FC165967',40,29,3,40,0,'125','N/A','HOLOGRAFIA','15',1,1),(70,'FC165968','FLEXIBLE CUTTING SYSTEMS','FC165968',40,53,2,40,0,'124','N/A','HOLOGRAFIA','15',1,1),(71,'E533BOLONIA','IMPORTADOS','FC166789',30,53,2,30,0,'126','N/A','FLEXO','15',1,1),(72,'E534BOLONIA','IMPORTADOS','FC166791',20.95,16,3,20.95,0,'130','N/A','FLEXO','15',1,1),(76,'E534BOLONIA-1','IMPORTADOS','FC166791-1',20.95,16,3,20.95,0,'131','N/A','FLEXO','15',1,1),(77,'E534BOLONIA-2','IMPORTADOS','FC166791-2',20.95,16,3,20.95,0,'132','N/A','FLEXO','15',1,1),(78,'FC-159431-10','FLEXIBLE CUTTING SYSTEMS','FC-159431-10',100,100,1,105,0,'137','N/A','HOLOGRAFIA','15',1,1),(79,'FC-159431-9','FLEXIBLE CUTTING SYSTEMS','FC-159431-9',100,100,1,105,0,'138','N/A','HOLOGRAFIA','15',1,1),(80,'FC-159431-11','FLEXIBLE CUTTING SYSTEMS','FC-159431-11',100,100,1,105,0,'140','N/A','HOLOGRAFIA','15',1,1),(81,'FC-159431-12','FLEXIBLE CUTTING SYSTEMS','FC-159431-12',100,100,1,105,0,'139','N/A','HOLOGRAFIA','15',1,1),(82,'SG00993','FLEXIBLE CUTTING SYSTEMS','SG00993',57,105,1,57.94,0,'153','N/A','FLEXO','15',1,1),(83,'SG01037','FLEXIBLE CUTTING SYSTEMS','SG01037',57,105,1,57.94,0,'154','N/A','FLEXO','15',1,1),(85,'SUAJE QUAKER 946Ml','FLEXIBLE CUTTING SYSTEMS','FC184573',81.5,294,1,84.66,0,'200','sae 50 diesel','HOLOGRAFIA','15',1,1),(88,'SUAJE QUAKER 945ML SAE40','FLEXIBLE CUTTING SYSTEM','FC184573-1-',81.5,294,1,84.66,0,'201','SAE40','HOLOGRAFIA','15',1,1),(91,'SUAJE KLINTEK CREMA LUSTRADORA','FLEXIBLE CUTTING SYSTEMS','FC184379',68,274,1,95.25,0,'202','CIREL KLINTEK CREMA LUSTRADORA','HOLOGRAFIA','15',1,1),(93,'suaje klintek crema desengrasante','FLEXIBLE CUTTING SYSTEMS','FC184375',110,224,1,127,0,'204','N/A','HOLOGRAFIA','15',1,1),(94,'truper expert 4oz','FLEXIBLE CUTTING SYSTEMS','FC190411',70,86,1,76.2,0,'216','N/A','HOLOGRAFIA','15',1,1),(95,'truper expert 16 oz','FLEXIBLE CUTTING SYSTEMS','FC190409',100,178,1,104.775,0,'217','CIREL TRUPER EXPERT 16 oz','HOLOGRAFIA','15',1,1),(97,'adilub hidraulico tractor','apple die','493316',80,256,1,84.66,0,'221','hidraulico para tractor 303 950 ml','HOLOGRAFIA','15',1,1),(98,'adilub aceite sae 50','apple die','493316-',80,256,1,84.66,0,'222','aceite adilub sae 50','HOLOGRAFIA','15',1,1),(100,'suaje liston','IMPORTADOS','fc142499',473.5,60,5,476,0,'81','suaje liston 20lts','HOLOGRAFIA','15',1,1),(102,'SUAJE SELANUSA ACEITE MULTI','FLEXIBLE CUTTING SYSTEMS','FC198951',136,63,4,149.225,0,'235','SUAJE SELANUSA ACEITE MULTI','HOLOGRAFIA','15',1,1),(103,'SUAJE KLINTEK LIQUIDO DESTAPACAÃ‘OS','FLEXIBLE CUTTING SYSTEMS','FC1843761',80,284,1,95.25,0,'237','SUAJE KLINTEK LIQUIDO','HOLOGRAFIA','15',1,1),(105,'suaje san patricio agua desm','FLEXIBLE CUTTING SYSTEMS','FC145307',77,103,1,84.66,0,'271','suaje agua desmineralizada','HOLOGRAFIA','15',1,1);
/*!40000 ALTER TABLE `suaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sustrato`
--

DROP TABLE IF EXISTS `sustrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sustrato` (
  `idSustrato` int(11) NOT NULL AUTO_INCREMENT,
  `codigoSustrato` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descripcionSustrato` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `anchura` double NOT NULL,
  `rendimiento` double NOT NULL,
  `PreEmbosado` int(11) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idSustrato`),
  UNIQUE KEY `descripcionSustrato` (`descripcionSustrato`),
  UNIQUE KEY `codigoSustrato` (`codigoSustrato`),
  KEY `descripcionSustrato_2` (`descripcionSustrato`),
  KEY `codigoSustrato_2` (`codigoSustrato`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sustrato`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `sustrato` WRITE;
/*!40000 ALTER TABLE `sustrato` DISABLE KEYS */;
INSERT INTO `sustrato` VALUES (1,'SUPVTRCH3545000','PVC termoencogible C40 E50/0 450mm',450,0.8,0,1),(2,'SUBPEMEL6400000','Polyester embosado C20 electropura 640mm',640,64.5,0,1),(4,'SUBPEMEP3200000','Polyester embosado C20 e-pura 320mm',320,34.35,0,0),(5,'SUBPEMEP6400000','Polyester embosado C20 e-pura 640mm',640,64.5,0,1),(6,'SUPVTRCH3540000','PVC termoencogible C40 E50/0 400mm',400,20,0,1),(7,'SUPTGTRNC37470','PTG transparente C37 455 mm',455,0.21,0,1),(8,'SUBPEMGOT3200000','Polyester embosado C30 gota 320 mm',320,32.6,0,1),(9,'SUBPEMSA3200000','Polyester embosado C20 santorini 320mm',320,34.5,0,1),(10,'SUPTGTRNC50308','PTG transparente C50 308 mm',308,15,0,1),(11,'5u649ndfjg','sustrto metalizado',620,45,0,1),(12,'sustocoffe','sustratocoffebillante 500mm',500,100,0,0),(13,'A050418','bopp bco autoadherible',230,13.8,0,1),(14,'PEL030','Bopp blanco 35 micras',400,41,0,1),(15,'PEL026','PET METALIZADO 400 MM',400,57.44,0,0),(16,'PEL030445','Bopp blanco 35 micras 445 MM',445,41,0,1),(19,'PEL026400','Pet metalizado 12 mic 400 mm',400,57.44,0,1),(20,'666','A sustratini',566,34,0,0),(21,'SUPETTPLT130000','PET PLATA C38 170 mm',170,0,0,1),(22,'SUBPEMEST3200000','Polyester embosado C30 estrella 320 mm',320,32.7,0,1),(23,'SUBGEPGEN3200000','Polyester embosado C27 GenÃ©rico Gepp 320mm',320,34.35,0,1),(24,'SUPVTRPPC3560100','PVC termoencogible C35 E50/0 601mm ',601,0,0,1),(25,'SUPETGC50667','PET G C50 667mm',667,0.0615,0,1),(26,'SUPVTRPP3566700','PVC Termoencogible C35 E50/0 667mm',667,0.0615,0,1),(27,'SUPVTRCH4038000','PVC termoencogible C40 E50/0 583',583,0,0,1),(28,'SUBOPPBLC251040','BOPP blanco C25 1040 mm',560,0,0,0),(29,'SUBOPPBLC25560','BOPP blanco C25 560 mm',560,0,0,0),(30,'SUBOPPBLC25580','BOPP blanco C25 580 mm',580,0,0,1),(31,'SUPVTROO4029400','PVC Termoencogible C 40 294',294,0,0,1),(32,'SUPVTRCH4039000','PVC termoencogible C40 E50/0 390mm',390,0,0,1),(33,'SUBOPPC35580','Bopp Blanco 35 micras 580',600,0,0,1),(34,'SUBOPPBLC27600','BOPP blanco C27 600 mm',600,0,0,1),(35,'SUBOPPBLC25600','BOPP blanco C25 600 mm',600,0,0,1),(36,'SUBOPPBLC25460','BOPP blanco C25 460 mm',460,0,0,1),(37,'SUBOPPBLADC6020','BOPP Blanco AutoAd.C60 20mm',20,0,0,1),(39,'SUBOPPBLADC6030','BOPP Blanco AutoAd.C60 30mm',30,0,0,1),(40,'	SUPETGC45667','PET G C45 667mm',667,0.0615,0,1),(41,'SUPETGC45601','PET G C45 601mm',601,0,0,1),(42,'SUPVTRCH3532000','PVC termoencogible C35 E50/0 320mm ',320,0,0,0),(43,'SUPVTRCH4032000','PVC termoencogible C40 E50/0 320mm',320,0,0,1),(44,'SUPETGC45455','PET G C45 455mm',455,0,0,1),(45,'SUPOEMB320','Poliester Embosado C35 Ciel 320',320,34.35,0,1),(46,'SUPVCC45667','PVC C45 667',667,0.0615,0,1),(48,'SUBOPPBLADC80200','BOPP Blanco AutoAd.C80 200mm',200,0,0,1),(49,'SUBOPPBLADC80300','	BOPP Blanco AutoAd.C80 300mm',300,0,0,1),(50,'SUPVCC320','PVC 320 C35',320,0,0,1),(51,'SUSBOPPAUT600','BOPP AUTOADHERIBLE C60 600MM',600,0,0,1),(52,'SUSPVCC35160','PVC C35 160MM',160,0,0,1),(53,'PACOUCHE80GR','PAPEL COUCHE 80GR 320MM',320,0,0,1),(54,'PACOUCHE80GR2','PAPEL COUCHE 80GR 230MM',230,0,0,1),(55,'SUPETGC45715','PET G C45 715mm',715,0,0,1);
/*!40000 ALTER TABLE `sustrato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablacliente`
--

DROP TABLE IF EXISTS `tablacliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablacliente` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `rfccli` varchar(100) NOT NULL,
  `nombrecli` varchar(100) NOT NULL,
  `domiciliocli` varchar(100) NOT NULL,
  `coloniacli` varchar(100) NOT NULL,
  `ciudadcli` varchar(100) NOT NULL,
  `cpcli` int(5) NOT NULL,
  `telcli` varchar(100) NOT NULL,
  `bajacli` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `nombrecli` (`nombrecli`),
  UNIQUE KEY `rfccli` (`rfccli`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1 COMMENT='Tabla cliente para modulo de logÃƒÂ­stica';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablacliente`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tablacliente` WRITE;
/*!40000 ALTER TABLE `tablacliente` DISABLE KEYS */;
INSERT INTO `tablacliente` VALUES (1,'EBE7711037Y5','Embotelladoras Bepensa','Calle 29 No.340 por 122 y 124 ','Nueva Yucalpetin','Progreso ,Yucatan Mex, Yuc.',97320,'999 9302626',1),(2,'0122222','jugos','Betania','San Felipe de Jesus','Leon, Gto.',37250,'4777724391',0),(3,'876543','Quindio si para ti','Tenazas #123','Villas bellas','Armenia, QUND',78453,'56786534',0),(4,'IRE820805HA3','INDUSTRIA DE REFRECOS','Avenida Santa Fe  485-PISO 4','Cruz manca ','Cuajimalpa de Morelos , Mex.',5349,'018006374377',1),(5,'MEM900806QZ5','Mega Empack , SA de CV','Calle 60 # 479 ','Parque industrial Yucatan ','MÃƒÂ©rida, Yuc.',97300,'019999822850',1),(6,'01','Imprenta Patito SA de CV','Babel','San Felipe de Jesus','Leon, Gto.',37270,'01',0),(7,'EBE7711037Y5.','Embotelladora Bepensa','29 No.340 por 122 y 124 ','Progreso','Progreso ,Yucatan Mex, Yuc.',97320,'1',1),(8,'KTE120905D96','Kunstoff Technologie','AV. GUAYAQUIRI #624 NAVE 11','LOMA BONITA','Queretaro, Qro.',76118,'4422183847',1),(9,'GCE120726CPA','GRUPO LABRO ','BARRIO DE GUADALUPE, SAN PEDRO DE LOS HERNANDEZ','SAN PEDRO DE LOS HERNANDEZ','Leon, Gto.',37280,'1',1),(10,'ENA831219N64','Embotellado del Nayar','Av. Insurgentes 1100 Ote ','Los Llanitos ','Tepic, Nay.',63170,'311-2119700',1),(11,'MAPO860120I79','OCTAVIO MARTIN PADILLA','Juan Pablo ll 172','El Rosario ','san juan de los Lagos , Jal.',47095,'1',1),(12,'XEXX010101000','Industria Farmaceutica S.A ','Km 15.5 Carretera Roosevelt ','0-80 zona 2 Mixco ','C.A., Guatemala',1057,'2411-5454',1),(13,'XEXX01010100','Sanofi-aventis de Guatemala SA ','Km 15.5 Carretera Roosevelt ','zona 7, Mixco ','Guatemala , Guatemala',1901,'0150224368000',1),(14,'DEN090220EC7','Dentalvet','Manuel Doblado Norte no. 336','Zona Centro ','Irapuato , Gto.',36520,'4626261586',1),(18,'XEXX010101000..','Laboratorios Generix Sa de Cv ','8a calle PTE 8a Av sur #6','El carmen santa tecla ','Salvado C.a., C.A.',1501,'25259006',1),(19,'ECO820331KB5','Embotelladora colima SA de CV','AV.Tecoman Sur No.99','El Moralete','Colima , Col.',28060,'013123134400',0),(20,'RLU840130J27','Raloy Lubricantes SA deCV','Av.Del covento 11','Parque Industrial Tianguistengo ','Tianguistengo, Edo.Mex',52600,'017131351900',0),(24,'XEXX010101000.','Laboratorios Naturcol, SA ','Calle 17A No. 68D 60','Zona Industrial Montevideo','Bogota., Colombia',110931,'005714110232',1),(25,'CPA8003075Q1','Cepillos y Productos de Aseo , SA de CV','Calzada San Lorenzo # 279 Bodega 32-A','Cerro de la Estrella ','Deleg.Iztapalapa ,Mexico, Mexico DF',9850,'015556123044',0),(26,'GEM180717P62','GASES ENVASADOS MPV','CARRETERA CARMEN PTO REAL KM 10.3 S/N ','18 DE MARZO','Cd. del Carmen , Camp.',24157,'9381111374',1),(27,'GLI961030TU5','GENOMMA LAB INTERNACIONAL ','ANTONIO DOVALI JAIME NO. EXT 70 TORRE C PISO 2 , DESPACHO A','SANTA FE','Deleg.Alvaro Obregon ,Mexico, Mexico cdmx',1210,'0',1),(28,'ACO0510202G0','AAA COSMETICA SA DE CV','RECURSO HIDRAULICOS SECCION A #1','LOMA INDUSTRIAL','Tlalnepantla, Mex.',54060,'5526281125',0),(29,'AAA000613UW0','AAA TEC SA DE CV','RECURSO HIDRAULICOS SECCION A #1','LOMA INDUSTRIAL','Tlalnepantla, Mex.',54060,'5526281125',0),(30,'OLN9503161H5','OLNATURA SA DE V','40 SUR MANZANA 8 PLANTA BAJA','CIVAC','Jiutepec, Mor.',62550,'7773202342',0),(31,'LMD880513832','LIFERPAL MD SA DE CV','INDUSTRIA DEL VESTIDO 2356','INDUSTRIAL ZAPOPAN NORTE','El Salto,Jalisco, Jal.',45130,'3332843120',0),(32,'PRO910905RS5','PROFILATEX SA DE CV','TATAVASCO # 79','SANTA CATARINA','COYOACAN,CDMX, CDMX',4010,'5559751572',0),(33,'CSE1509087V5','Comercializadora Selecta Esparo SA de CV','Murales # 203','Industrial la Capilla','Leon, Gto.',37297,'0',0),(34,'TOMJ960903H66','JESUS DANIEL TOSCANO MEDINA','Aurora Boreal # 3622','Arboledas 1ra. SecciÃ³n','Zapopan,Jalisco, Jal.',45070,'3312045856',1),(35,'GLM0211278J1','GENOMMA LABORATORIES MEXICO','ANTONIO DOVALI JAIME NO. EXT 70 TORRE C PISO 2 , DESPACHO A','SANTA FE','Deleg.Alvaro Obregon ,Mexico, Mexico cdmx',1210,'0',1),(36,'PLE650922NP1','Pasteurizadora de LeÃ³n','Blvd.Aeropuerto # 3102','Predio Los Lopez','Leon, Gto.',37680,'4771522100',1),(37,'BMU8605134I8-Gdl','Bebidas Mundiales Las Fuentes GDL','Av.Lopez Mateos Sur #6285','Las Fuentes','Tlaquepaque,Jal, Jal.',45500,'3332843120',1),(40,'DJB850527F30-Mexicali','Distribuidora Arca Continental, S de RL de CV Mexicali','Blvd.Lazaro Cardenas # 2400','Plutarco Elias Calles','Mexicali,BC, BC',21376,'6865628800',0),(41,'DJB850527F30-GPE.NL','Distribuidora Arca Continental, S de RL de CV Guadalupe NL','Av.Lazaro Cardenas # 1213','El Sabino','Guadalupe,NL, NL',67150,'8717492560',0),(42,'DJB850527F30-Matamoros','Distribuidora Arca Continental, S de RL de CV Matamoros','Prol.Calixto Ayala # 200','San Rafael','Matamoros,Tamaulipas, Tamps.',87350,'0',0),(43,'DJB850527F30-AGS','Distribuidora Arca Continental, S de RL de CV Trojes','Av.NiÃ±os Heroes # 200','Trojes de Alonso','Aguascalientes , Ags.',20110,'0',0),(44,'DJB850527F30-Culiacan','Distribuidora Arca Continental, S de RL de CV Culiacan','Calzada Aeropuerto # 5501','Bachigualato','Culiacan,Sinaloa, Sin.',80140,'6677585600',0),(45,'DJB850527F30-Nava,Coah','Distribuidora Arca Continental, S de RL de CV  Nava,Coah.','Carretera 57 KM 13 ','Tramo Piedras Negras','Nava,Coahuila, Coah.',26170,'0',0),(46,'DJB850527F30-Juarez','Distribuidora Arca Continental, S de RL de CV Cd.Juarez','Blvd.Oscar Flores Sanchez # 9755','Puente Alto','CD.Juarez,Chihuahua, Chih.',32695,'0',0),(47,'DJB850527F30-Chihuahua','Distribuidora Arca Continental, S de RL de CV Chihuahua','Av.Cristobal Colon # 18701','Las Carolinas','Chihuahua , Chih.',31146,'6144422820',0),(48,'.','Laboratorios Pisa','Carretera San Isidro Mazatepec No. 7000','Santa Cruz de las Flores','Tlajomulco de ZuÃ±iga,Jal, Jal.',45640,'3336781600',1),(49,'BMU8605134I8-Saltillo','Bebidas Mundiales Saltillo','Blvd.de los Fundadores # 6659','San Jose de los Cerritos','Saltillo , Coahuila, Coah.',255016,'8444130000',1),(50,'BMU8605134I8-AGS.','Bebidas Mundiales Aguascalientes','Camino a San Bartolo #100','Ejido Coyotes','Aguascalientes , Ags.',20394,'4499103520',1),(51,'000','Alejandra Campero / B-Water','Calle Santa Fe #34 Coto5 ','Fracc.Villa California','Tlajomulco de ZuÃ±iga,Jal, Jal.',45645,'000',1),(52,'BMU8605134I8-Pied.Neg','Bebidas Mundiales  Piedras Neg','Carretera 57 KM 13 S/N','Tramo Piedras Negras-Nava Coah','Nava,Coahuila, Coah.',26170,'8787826800',1),(53,'BMU8605134I8-Dgo','Bebidas Mundiales Durango','Carretera Durango - El Mezquital Km3.0','.','Durango,Dgo, Dgo.',34199,'6181689085',1),(54,'BMU8605134I8-Chih','Bebidas Mundiales Chihuahua','Av.Cristobal Colon # 18701','Las Carolinas','Chihuahua , Chih.',31109,'6144422820',1),(55,'CHA1401072I5','Corporativo Herbal & Plants','Honorio III 209-A','San Francisco de Asis 1ra.Seccion','Leon, Gto.',37295,'0',1),(56,'ADI980123DU1','ADILUB SA DE CV','Carretera LeÃ³n-San Francisco Km.7','Predio Buena Vista','San Francisco Del Rincon, Gto.',36340,'4767578800',1),(57,'CEL470228G64','Comercializadora Eloro','KM 12.5 Antigua Carretera Mexico-Pachuca','Xalostoc','Ecatepec de Morelos, Mor.',55340,'0',1),(58,'RINF8809119J7','Fatima del Carmen Rivera Navarro','Guatemala # 152','Tabachines','Irapuato , Gto.',36615,'0',1);
/*!40000 ALTER TABLE `tablacliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablaconsuc`
--

DROP TABLE IF EXISTS `tablaconsuc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablaconsuc` (
  `idconsuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombreconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `puestoconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `movilconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `emailconsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaconsuc` int(1) NOT NULL DEFAULT '1',
  `sucFK` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idconsuc`),
  KEY `FKCliente` (`sucFK`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablaconsuc`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tablaconsuc` WRITE;
/*!40000 ALTER TABLE `tablaconsuc` DISABLE KEYS */;
INSERT INTO `tablaconsuc` VALUES (1,'Enrique Torres','Encargado TI','56767','997322','elkikelokoteprincess@gmail.com',1,'4'),(2,'Jaime Torres','Almacenista','01 55 7894632','0','jaime.torres@gepp.com',1,'5'),(3,'Raul Quezada','Jefe Alamacen','01 999 9429990','0','rquezadaa@bepensa.com',1,'2'),(4,'Belem Gonzalez','Almacen','01 55 53423847','0','belem.gonzalez@gepp.com',1,'5'),(5,'Juan Pancho','Lider','827772','827772','jatzune@maicol.com',1,'7'),(6,'Andri Melgoza','Jefe Alamacen','.','.','andri.melgoza@gepp.com',1,'36'),(7,'Leslie Villa','Inspector de Calidad','5553423857','0','leslie.villa@gepp.com',1,'23'),(8,'Diana Valeria','Calidad bacteriologa','6181524004','6181524004','ana.osorio@gepp.com',1,'45'),(9,'Juan Carlos Martinez','0','0','0','0',1,'62'),(10,'GEORDINA MIRIAM','ALMACEN','0','0','cyglaboratorios@hotmail.com',1,'80'),(11,'EMILIANO BARRON','ALMACEN DE MATERIALES','5526281125','','almacenme@aaate.com.mx',1,'81'),(12,'Eduardo Torres','Almacen','5559756060','0','0',1,'89'),(13,'Maricruz Correa','Compras','4771522100','0','mcorrea@lecheleon.com',1,'99'),(14,'Christian Pacheco','COMPRAS','4767578800','0','0',1,'126'),(15,'Edgar Yael Flores Gonzalez','Inspector de Calidad','5558369999','0','eyfloresg@jumex.com.mx',1,'127'),(16,'Ismael Davila ','Compras','0','0','.',1,'138'),(17,'Edgar Ayala Cota','Jefe de almacen MP','0','6121317516','edgar.ayalacota@gepp.com',1,'189');
/*!40000 ALTER TABLE `tablaconsuc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablacontcli`
--

DROP TABLE IF EXISTS `tablacontcli`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablacontcli` (
  `idconcli` int(11) NOT NULL AUTO_INCREMENT,
  `nombreconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `puestoconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telefonoconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `movilcl` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `emailconcli` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajaconcli` int(1) NOT NULL DEFAULT '1',
  `idcliFK` int(11) NOT NULL,
  PRIMARY KEY (`idconcli`),
  KEY `idcliFK` (`idcliFK`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablacontcli`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tablacontcli` WRITE;
/*!40000 ALTER TABLE `tablacontcli` DISABLE KEYS */;
INSERT INTO `tablacontcli` VALUES (1,'DANIEL RODRIGUEZ REYNA','COMPRADOR','01 999 930 26 03','0','drodriguezr@bepensa.com',1,1),(2,'Estevan','Gerente','68762757','1324356','estevanius@gmail.com ',1,3),(3,'Lidia GÃ³mez','Compras Jr','01 55 7987645','0','lidia.gomez@gepp.com',1,4),(4,'Lira','LIDER','377291','828873','Claroqueno',1,1),(5,'MARIA BEJARANO','JEFE ABASTO Y COSTOS ','013123134400','0453123145808','mbejarano@gocsa.com.mx',1,19),(6,'Angelicas Gonzalez','Compras','0','0','agonzalez',1,20),(7,'Maria SAndoval','Jefe e Compras','005714110232','0','maria.sandoval@naturcol.com',1,24),(8,'Martha Estrada','Compras','015556123044','5566317021','marthacypa@prodigy.net.mx',1,25),(9,'CAP.ANGEL ACEVEDO','CAPITAN','9381121374','0','aacevedo@marineprov.com',1,26),(10,'ANTONIO DE JESUS EVANGELISTA','COMPRAS','5526281125','','comprasme@aaacosmetica.com.mx',1,28),(11,'IRAIS GUTIERREZ','COMPRAS','7773202342','','irais.gutierrez@olnatura.com',1,30),(12,'GERARDO GOMEZ','COMPRAS','3332843120','.','.',1,31),(13,'Alfonso Rangel','JEFE DE COMPRAS','.','5532321462','.',1,27),(14,'JESUS TOSCANO/CESAR TOSCANO','VENTAS','3316696473....3316096773','0','impressione_ventas@hotmail.com/ ventas.impressione@hotmail.com',1,34),(15,'Maricruz Correa','Compras','4771522100','0','mcorrea@lecheleon.com',1,36),(16,'DAVID AVILA VARELA ','COMPRAS','3332843120','0','.',1,48),(17,'Edgar Yael Flores Gonzalez','Inspector de Calidad','5558369999','0','eyfloresg@jumex.com.mx',1,57),(18,'Jhonatan Antonio Reyez','Compras','0','0','jreyes01@madrilena.com.mx',1,64),(19,'Olivia Coronado','Gerente Abastecimientos Distribucion','5561074332','5544201389','olivia.coronado@kof.com.mx',1,69),(20,'Jorge Rodriguez Villanueva','Materiales Retornables','2225634484','2225634484','jorge.rodriguezvillanueva@gepp.com',1,73),(21,'Leonardo Meneses','Almacen','5553217500','170','imeneses@gpocetto.com',1,75);
/*!40000 ALTER TABLE `tablacontcli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablasuc`
--

DROP TABLE IF EXISTS `tablasuc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablasuc` (
  `idsuc` int(11) NOT NULL AUTO_INCREMENT,
  `nombresuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `domiciliosuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `coloniasuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ciudadsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cpsuc` int(8) NOT NULL,
  `telefonosuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `transpsuc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bajasuc` int(1) NOT NULL DEFAULT '1',
  `idcliFKS` int(11) NOT NULL,
  PRIMARY KEY (`idsuc`),
  UNIQUE KEY `nombresuc` (`nombresuc`),
  KEY `idcliFKS` (`idcliFKS`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablasuc`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tablasuc` WRITE;
/*!40000 ALTER TABLE `tablasuc` DISABLE KEYS */;
INSERT INTO `tablasuc` VALUES (1,'Planta Villahermosa Bepensa','Av. Universidad 339','Fracc Framboyanes','Villahermosa, Tab.',86020,'9939580088','Tres Guerras',0,1),(2,'Planta Merida Bepensa','Predio Rustico # 13345','Jacinto Canek','Merida , Yuc.',97227,'9999429990','Tres Guerras',1,1),(3,'Sucursal 1','Betania ','San Felipe de Jesus','Leon, Gto.',37250,'4772355875','Tres guerras',1,2),(4,'Sucursal buenavista','vientos negros #450','Desastresss','Buenavista, QUND',136346,'75889965','DHL',1,3),(5,'Claveria','Poniente 44 # 2840','Salvador Xochimanca delega. Azcapotzalco ','Azcapotzalco, DF',2870,'015553423857','Tres Guerras',0,4),(6,'Planta Merida','Calle 60 parque industrial #501','Parque Industrial ','Merida , Yuc.',97300,'01 999 9425200','Tres Guerras',0,4),(7,'Comex Queretaro','Acceso III, No. 9 entre acceso IV y ProlongaciÃ³n Bernardo Quintana','Parque Industrial Benito Juarez','Queretaro, Qro.',76120,'999 9822850 ','Tres Guerras',1,5),(8,'Planta Acapulco ','Carr. Puerto Marquez - Las Cruces Km. 1','Rinconada del Diamante ','Acapulco , Gro.',39810,'01-744-466-2850','Tres guerras ',0,4),(9,'ghjtgjf','ghjkhg','657465','San Francisco de Campeche , Camp.',65757,'65786','tyrty',0,6),(10,'Planta Aguascalientes ','Julio Diaz Torre #205','Industrial ','Aguascalientes , Ags.',20290,'01-449-971-0088','Tres guerras ',0,4),(11,'Planta Calera ','ProlongaciÃ³n 5 de Mayo # 642 ','Calera de Victor Rosales ','Calera de Victor Rosales Zacatecas , Zac.',98500,'01-478-985-0077','Tres guerras ',0,4),(12,'Planta Celaya ','Carr. MÃ©xico-CD. Juarez km. 367','0','Celaya , Gto.',38020,'01-461-598-6800','Tres guerras ',0,4),(13,'Planta Altamira ','Carr. Tampico Mante km 12.5# 2000','.','Altamira Tamaulipas , Tamps.',89600,'01-833-226-5950','Tres guerras ',0,4),(14,'Planta Chiapas','Carr. Tuxtla a la Angostura #800','Rivera de Cupia ','Chiapa de Corzo , Chis.',29169,'01-961-618-7660','Tres guerras ',0,4),(15,'Planta Apodaca ','Blvd. Parque Industrial #400','Parque Industrial ','Apodaca, NL',66600,'01-818-127-0050','Tres guerras ',0,4),(16,'Planta Colima ','Blvd. Paseo de los Doctores # 299','Los Sauces ','Colima , Col.',28640,'01-312-314-3529','Tres guerras ',0,4),(17,'Planta Campeche ','Av. Lopez Portillo ','Las Flores ','San Francisco de Campeche , Camp.',24097,'01-981-813-1554','Tres guerras ',0,4),(18,'Planta Ixtlahuacan ','Carr. Guadalajara-Chapala #2300','Fracc. Los Laureles ','Ixtlahuacan de los Membrillos , Jal.',45850,'01-333-284-1500','Almex',0,4),(19,'Planta CancÃºn ','Carr. MÃ©rida Puerto JÃºarez Km 311.5 Lote 4 ','RegiÃ³n 104','CancÃºn, Q. Roo',77500,'01-998-193-0060','Tres guerras ',0,4),(20,'Planta Lagos de Moreno ','Camino Real a la Laguna # 163','El Bajio ','Lagos de Moreno , Jal.',47430,'01-474-741-1002','Tres guerras ',0,4),(21,'Planta Chihuahua ','Av. Homero # 390','Complejo Industrial ','Chihuahua , Chih.',31109,'01-998-193-0060','Tres guerras ',0,4),(22,'Planta Lazaro Cardenas ','Cerro de las Barcenas Lote 7 y8 ','Parque Industrial de la pequeÃ±a y mediana Industrial ','LÃ¡zaro CÃ¡rdenas , Mich',60950,'01-753-532-0030','Fletes del Sur ',0,4),(23,'Planta Claveria ','Poniente 44 #2840','Salvador xochimanca delega. Azcapotzalco ','Azcapotzalco, DF',2870,'01-555-342-3857','Tres guerras ',0,4),(24,'Planta Minatitlan ','Carr. Transismica Km 264 ','Tlalcualoya ','Minatitlan , Ver.',96760,'01-922-221-0601','Tres guerras ',0,4),(25,'Planta Cuernavaca','Carr. Federal Cuernavaca-Cuautla K.m 6.5','Tejalpa de Civac ','Jiutepec, Mor.',62570,'01-777-320-8580','Tres guerras ',0,4),(26,'Planta Morelia ','Periferico Paseo de la Republica # 3051','Mariano Michelena ','Morelia , Mich',58195,'01-443-322-4418','Tres guerras ',0,4),(27,'Planta GÃ³mez Palacio ','Blvd. Miguel AlemÃ¡n ','Valle de Nazas','GÃ³mez Palacio , Dgo.',35070,'01871-750-1429','Tres guerras ',0,4),(28,'Planta Orizaba ','Carr. Nacional KM. 320','Escamela Ixtaczoquitlan','Orizaba , Ver.',94450,'01-272-721-0200','Tres guerras ',0,4),(29,'Planta Hermosillo ','Periferico Poniente entre rosales y seri ','Las Praderas ','Hermosillo , Son.',83280,'01-662-109-1100','Tres guerras ',0,4),(30,'Planta Puebla ','Camino ResurrecciÃ³n # 5425','Resureccion Norte ','Puebla, Pue.',72227,'01-222-229-1100','Tres guerras ',0,4),(31,'Planta Iguala ','Carr. Naconal MÃ©xico -Acapulco km. 196','Centro ','Iguala , Gro.',40000,'01-733-332-0056','Tres guerras ',0,4),(32,'Planta Puerto Vallarta ','Carr. Las Juntas-Ixtapa #439','Las Juntas ','Puerto Vallarta, Jal.',48291,'01-322-290-0695','Tres guerras ',0,4),(33,'Planta La Paz ','Carr. a San Juan de los Planes Km 1 ','Industrial ','La Paz, BCS',23050,'01-612-121-0735','Tres guerras ',0,4),(34,'Planta Tepic ','Av. Aguamilpa # 197','Cd. Industrial ','Tepic, Nay.',63173,'01-311-219-3371','Tres guerras ',0,4),(35,'Planta MÃ©rida ','60 Diagonal # 501','Parque Industrial ','Merida , Yuc.',97300,'01-999-942-5200','Tres guerras ',0,4),(36,'Planta Uruapan ','Paseo General Lazaro Cardenas # 60170','La Joyita ','Uruapan , Mich',60170,'01-452-524-2600','Tres guerras ',0,4),(37,'Planta Veracruz ','Tuxtepec ','Paso del Toro ','Veracruz , Ver.',94277,'01-585-971-2517','Tres guerras ',0,4),(38,'Planta Villahermosa ','Carr. Villahermosa-Teapa Km 12.5','Parrilla Uno ','Villaparilla, Tab.',86280,'01-993-140-8741','Tres guerras ',0,4),(39,'Planta Zamora','Carr. Zamora la Barca Km. 3.5','.','Zamora, Mich',59610,'01-351-517-0022','Tres guerras ',0,4),(40,'Planta Zitacuaro ','Circuito sur ote manzana Vlll lote 14 y 15','Parque Industrial ','Zitacuaro, Mich',61500,'01-715-156-8155','Tres guerras ',0,4),(41,'Planta Reyes ','Km 23.9 Carr. Mexico-Texcoco ','Carlos Hank Gonzalez ','Los Reyes La Paz , Mex.',56510,'01-552-613-8506','Tres guerras ',0,4),(42,'Planta San Luis Potosi ','Eje 114 # 235 ','Zona Industrial ','San Luis Potosi , SLP',78395,'01-444-824-7271','Tres guerras ',0,4),(43,'Planta Tlalnepantla ','Recursos Hidraulicos #8','La Loma ','Tlalnepantla, Mex.',54060,'01-551-106-1500','Tres guerras ',0,4),(44,'Planta Toluca ','Carr. Toluca-Naucalpan Km. 1','Guadalupe ','Toluca , Mex.',50010,'01-722-272-3420','Tres guerras ',0,4),(45,'Planta Queretaro ','Carr. estatal 11 libramiento ser pte parcela 99 ','Tlacote el bajo z-3 ','Queretaro, Qro.',76226,'1','Tres guerras ',0,4),(46,'Planta Chetumal Bepensa ','Av. Insurgentes # 740','Miraflores Othon p. Blanco ','Chetumal , Q. Roo',77027,'9838371115','Tres guerras ',1,1),(47,'Planta Cd del Carmen Bepensa ','Km. 8.5 Carr. Carmen Puente Real ','San Antonio  de Limon ','Cd. del Carmen , Camp.',24199,'9383831772','Tres guerras ',1,1),(48,'Planta Oaxaca Bepensa ','Carr. Internacional # 1001-B','Fracc. Los Tulipanes','Santa Rosa Panzacola,Oaxaca de Juarez, Oax.',68010,'9511320855','Tres guerras ',0,1),(49,'Planta Minatitlan Bepensa ','Buenos Aires esq. Roma Lote 1a Mza. 16','Nueva Mina ','Minatitlan , Ver.',96760,'9222233870','Tres guerras ',1,1),(50,'Planta Coatzacoalcos Bepensa ','Transistmica # 1203-B','Las Americas ','Coatzacoalcos, Ver.',96480,'9212141000','Tres guerras ',1,1);
/*!40000 ALTER TABLE `tablasuc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcodigosbarras`
--

DROP TABLE IF EXISTS `tbcodigosbarras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbcodigosbarras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `noProceso` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  `divisiones` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `codigo_2` (`codigo`),
  KEY `producto` (`producto`),
  KEY `proceso` (`proceso`),
  KEY `noop` (`noop`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1133573 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcodigosbarras`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbcodigosbarras` WRITE;
/*!40000 ALTER TABLE `tbcodigosbarras` DISABLE KEYS */;
INSERT INTO `tbcodigosbarras` VALUES (700001,'0026558284','342','6','43MA230826A131B113-2','5','23636-2-6-5','30',1,'0'),(700002,'0026658284','342','6','43MA230826A131B113-2','5','23636-2-6-6','30',1,'0'),(700003,'0026758284','342','6','43MA230826A131B113-2','5','23636-2-6-7','30',1,'0'),(700004,'0026858284','342','6','43MA230826A131B113-2','5','23636-2-6-8','30',1,'0'),(700005,'0026958284','342','6','43MA230826A131B113-2','5','23636-2-6-9','30',1,'0'),(700006,'0261058284','342','6','43MA230826A131B113-2','5','23636-2-6-10','30',1,'0'),(700007,'0261158284','342','6','43MA230826A131B113-2','5','23636-2-6-11','30',1,'0'),(700008,'0261258284','342','6','43MA230826A131B113-2','5','23636-2-6-12','30',1,'0'),(700009,'0261358284','342','6','43MA230826A131B113-2','5','23636-2-6-13','30',1,'0'),(700010,'0261458284','342','6','43MA230826A131B113-2','5','23636-2-6-14','30',1,'0'),(700011,'0261558284','342','6','43MA230826A131B113-2','5','23636-2-6-15','30',1,'0'),(700012,'0261658284','342','6','43MA230826A131B113-2','5','23636-2-6-16','30',1,'0'),(700013,'0261758284','342','6','43MA230826A131B113-2','5','23636-2-6-17','30',1,'0'),(700014,'0261858284','342','6','43MA230826A131B113-2','5','23636-2-6-18','30',1,'0'),(700015,'0001638289','342','3','43MA230826B113B413-8','3','23641-1-6','30',1,'1'),(700016,'0001538289','342','3','43MA230826B113B413-8','3','23641-1-5','30',1,'1'),(700017,'0001438289','342','3','43MA230826B113B413-8','3','23641-1-4','30',1,'1'),(700018,'0001338289','342','3','43MA230826B113B413-8','3','23641-1-3','30',1,'1'),(700019,'0001238289','342','3','43MA230826B113B413-8','3','23641-1-2','30',1,'1'),(700020,'0001138289','342','3','43MA230826B113B413-8','3','23641-1-1','30',1,'1'),(700021,'0000007370','20','10','R9002642EMBCI320-3','0','2315','1',1,'1'),(700025,'0003348287','342','5','43MA230826B113B213-4','4','23639-3-3','30',1,'19'),(700026,'0002448261','342','5','43MA230826A142A513-9','4','23616-2-4','30',1,'19'),(700027,'0002348261','342','5','43MA230826A142A513-9','4','23616-2-3','30',1,'19'),(700028,'0002248261','342','5','43MA230826A142A513-9','4','23616-2-2','30',1,'19'),(700029,'0002548284','342','5','43MA230826A131B113-2','4','23636-2-5','30',1,'19'),(700030,'0025158284','342','6','43MA230826A131B113-2','5','23636-2-5-1','30',1,'0'),(700031,'0025258284','342','6','43MA230826A131B113-2','5','23636-2-5-2','30',1,'0'),(700032,'0025358284','342','6','43MA230826A131B113-2','5','23636-2-5-3','30',1,'0'),(700033,'0025458284','342','6','43MA230826A131B113-2','5','23636-2-5-4','30',1,'0'),(700034,'0025558284','342','6','43MA230826A131B113-2','5','23636-2-5-5','30',1,'0'),(700035,'0025658284','342','6','43MA230826A131B113-2','5','23636-2-5-6','30',1,'0'),(700036,'0025758284','342','6','43MA230826A131B113-2','5','23636-2-5-7','30',1,'0'),(700037,'0025858284','342','6','43MA230826A131B113-2','5','23636-2-5-8','30',1,'0'),(700038,'0025958284','342','6','43MA230826A131B113-2','5','23636-2-5-9','30',1,'0'),(700039,'0251058284','342','6','43MA230826A131B113-2','5','23636-2-5-10','30',1,'0'),(700040,'0251158284','342','6','43MA230826A131B113-2','5','23636-2-5-11','30',1,'0'),(700041,'0251258284','342','6','43MA230826A131B113-2','5','23636-2-5-12','30',1,'0'),(700042,'0251358284','342','6','43MA230826A131B113-2','5','23636-2-5-13','30',1,'0'),(700043,'0251458284','342','6','43MA230826A131B113-2','5','23636-2-5-14','30',1,'0'),(700044,'0251558284','342','6','43MA230826A131B113-2','5','23636-2-5-15','30',1,'0'),(700045,'0251658284','342','6','43MA230826A131B113-2','5','23636-2-5-16','30',0,'0'),(700046,'0251758284','342','6','43MA230826A131B113-2','5','23636-2-5-17','30',1,'0'),(700047,'0251858284','342','6','43MA230826A131B113-2','5','23636-2-5-18','30',1,'0'),(700048,'0251958284','342','6','43MA230826A131B113-2','5','23636-2-5-19','30',1,'0'),(700049,'3423780020','342','11','C20','0','','30',1,'1'),(700050,'0001248264','342','5','43MA230826A131B213-4','4','23632-1-2','30',1,'18'),(700051,'0012158264','342','6','43MA230826A131B213-4','5','23632-1-2-1','30',1,'0'),(700052,'0012258264','342','6','43MA230826A131B213-4','5','23632-1-2-2','30',1,'0'),(700053,'0012358264','342','6','43MA230826A131B213-4','5','23632-1-2-3','30',1,'0');
/*!40000 ALTER TABLE `tbcodigosbarras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcodigosbarrasbu250213`
--

DROP TABLE IF EXISTS `tbcodigosbarrasbu250213`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbcodigosbarrasbu250213` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `producto` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `noProceso` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(1) NOT NULL DEFAULT '1',
  `divisiones` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `codigo_2` (`codigo`),
  KEY `producto` (`producto`),
  KEY `proceso` (`proceso`),
  KEY `noop` (`noop`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=957284 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcodigosbarrasbu250213`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbcodigosbarrasbu250213` WRITE;
/*!40000 ALTER TABLE `tbcodigosbarrasbu250213` DISABLE KEYS */;
INSERT INTO `tbcodigosbarrasbu250213` VALUES (2,'0000003','14','10','070518OVER601-1','0','192','30',1,'1'),(3,'0000004','14','10','070518OVER601-2','0','193','30',1,'1'),(4,'0000005','14','10','070518OVER601-3','0','194','30',1,'1'),(5,'0000006','14','10','070518OVER601-4','0','195','30',1,'1'),(6,'0000007','14','10','070518OVER601-5','0','196','30',1,'1'),(7,'0000008','14','10','070518OVER601-6','0','197','30',1,'1'),(20,'0000013','14','7','070518OVER601-1','1','192','30',1,'3'),(21,'0000014','14','7','070518OVER601-2','1','193','30',1,'3'),(22,'0000015','14','7','070518OVER601-3','1','194','30',1,'3'),(25,'0000323','14','2','070518OVER601-1','2','192-3','30',1,'5'),(26,'0000223','14','2','070518OVER601-1','2','192-2','30',1,'5'),(27,'0000123','14','2','070518OVER601-1','2','192-1','30',1,'5'),(28,'0000016','14','7','070518OVER601-4','1','195','30',1,'3'),(29,'0000017','14','7','070518OVER601-5','1','196','30',1,'3'),(30,'0000018','14','7','070518OVER601-6','1','197','30',1,'3'),(31,'0000324','14','2','070518OVER601-2','2','193-3','30',1,'6'),(32,'0000224','14','2','070518OVER601-2','2','193-2','30',1,'6'),(33,'0000124','14','2','070518OVER601-2','2','193-1','30',1,'6'),(34,'0000326','14','2','070518OVER601-4','2','195-3','30',1,'6'),(35,'0000226','14','2','070518OVER601-4','2','195-2','30',1,'6'),(36,'0000126','14','2','070518OVER601-4','2','195-1','30',1,'6'),(37,'0000325','14','2','070518OVER601-3','2','194-3','30',1,'6'),(38,'0000225','14','2','070518OVER601-3','2','194-2','30',1,'6'),(39,'0000125','14','2','070518OVER601-3','2','194-1','30',1,'6'),(40,'0000327','14','2','070518OVER601-5','2','196-3','30',1,'6'),(41,'0000227','14','2','070518OVER601-5','2','196-2','30',1,'6'),(42,'0000127','14','2','070518OVER601-5','2','196-1','30',1,'6'),(43,'0002533','14','3','070518OVER601-1','3','192-2-5','30',1,'1'),(44,'0002433','14','3','070518OVER601-1','3','192-2-4','30',1,'1'),(45,'0002333','14','3','070518OVER601-1','3','192-2-3','30',1,'1'),(46,'0002233','14','3','070518OVER601-1','3','192-2-2','30',1,'1'),(47,'0002133','14','3','070518OVER601-1','3','192-2-1','30',1,'1'),(48,'0001533','14','3','070518OVER601-1','3','192-1-5','30',1,'1'),(49,'0001433','14','3','070518OVER601-1','3','192-1-4','30',1,'1'),(50,'0001333','14','3','070518OVER601-1','3','192-1-3','30',1,'1'),(51,'0001233','14','3','070518OVER601-1','3','192-1-2','30',1,'1'),(52,'0001133','14','3','070518OVER601-1','3','192-1-1','30',1,'1'),(53,'0000328','14','2','070518OVER601-6','2','197-3','30',1,'6'),(54,'0000228','14','2','070518OVER601-6','2','197-2','30',1,'6'),(55,'0000128','14','2','070518OVER601-6','2','197-1','30',1,'6'),(56,'0003533','14','3','070518OVER601-1','3','192-3-5','30',1,'1'),(57,'0003433','14','3','070518OVER601-1','3','192-3-4','30',1,'1'),(58,'0003333','14','3','070518OVER601-1','3','192-3-3','30',1,'1'),(59,'0003233','14','3','070518OVER601-1','3','192-3-2','30',1,'1'),(60,'0003133','14','3','070518OVER601-1','3','192-3-1','30',1,'1'),(61,'00000011','30','10','196439299PVC60135-1','0','1910','30',1,'1'),(62,'00000012','30','10','196439299PVC60135-2','0','1911','30',1,'1'),(63,'00000013','30','10','196439299PVC60135-3','0','1912','30',1,'1'),(64,'00000014','30','10','196439299PVC60135-4','0','1913','30',1,'1'),(65,'00000015','30','10','196439299PVC60135-5','0','1914','30',1,'1');
/*!40000 ALTER TABLE `tbcodigosbarrasbu250213` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblotes`
--

DROP TABLE IF EXISTS `tblotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblotes` (
  `idLote` int(11) NOT NULL AUTO_INCREMENT,
  `bloque` int(11) NOT NULL,
  `loteGral` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `referenciaLote` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `longitud` float NOT NULL,
  `peso` float DEFAULT NULL,
  `tarima` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '0',
  `shower` int(11) NOT NULL DEFAULT '1',
  `noop` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `ancho` float NOT NULL,
  `espesor` float NOT NULL,
  `encogimiento` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `numeroLote` int(11) NOT NULL,
  `juegoLote` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `noLote` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` double NOT NULL,
  `anchuraBloque` double NOT NULL,
  `tipo` int(11) NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLote`),
  UNIQUE KEY `referenciaLote` (`referenciaLote`),
  KEY `idLote` (`idLote`),
  KEY `referenciaLote_2` (`referenciaLote`),
  KEY `bloque` (`bloque`),
  KEY `noop` (`noop`),
  KEY `juegoLote` (`juegoLote`)
) ENGINE=InnoDB AUTO_INCREMENT=11107 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblotes`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tblotes` WRITE;
/*!40000 ALTER TABLE `tblotes` DISABLE KEYS */;
INSERT INTO `tblotes` VALUES (3,24,'070518OVER601','070518OVER601-1',3000,90,'070518OVER60140-1',5,1,'192',601,50,'50+/-%',1,'JL302019-05-091557422509','070518OVER60140-1 | 1',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(4,24,'070518OVER601','070518OVER601-2',3000,90,'070518OVER60140-1',5,1,'193',601,50,'50+/-%',2,'JL302019-05-091557422509','070518OVER60140-1 | 2',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(5,24,'070518OVER601','070518OVER601-3',3000,90,'070518OVER60140-1',5,1,'194',601,50,'50+/-%',3,'JL302019-05-091557422509','070518OVER60140-1 | 3',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(6,24,'070518OVER601','070518OVER601-4',3000,90,'070518OVER60140-1',5,1,'195',601,50,'50+/-%',4,'JL302019-05-091557422509','070518OVER60140-1 | 4',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(7,24,'070518OVER601','070518OVER601-5',3000,90,'070518OVER60140-1',5,1,'196',601,50,'50+/-%',5,'JL302019-05-091557422509','070518OVER60140-1 | 5',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(8,24,'070518OVER601','070518OVER601-6',3000,90,'070518OVER60140-1',5,1,'197',601,50,'50+/-%',6,'JL302019-05-091557422509','070518OVER60140-1 | 6',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(9,24,'070518OVER601','070518OVER601-7',3000,90,'070518OVER60140-1',5,1,'19232',601,50,'50+/-%',7,'JL302019-05-301559233221','070518OVER60140-1 | 7',123.92734002357,601,30,1,'2019-05-09 12:12:37'),(10,24,'070518OVER601','070518OVER601-8',3000,90,'070518OVER60140-1',5,1,'19184',601,50,'50+/-%',8,'JL302019-05-271559051772','070518OVER60140-1 | 8',123.95430768765,601,30,1,'2019-05-09 12:12:37'),(11,24,'196439299','196439299PVC60135-1',2800,88,'196439299.PVC.601.35-1',5,1,'1910',601,35,'50+/-%',1,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 1',126.19073729943,601,30,1,'2019-05-09 12:14:41'),(12,24,'196439299','196439299PVC60135-2',2800,88,'196439299.PVC.601.35-1',5,1,'1911',601,35,'50+/-%',2,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 2',126.19073729943,601,30,1,'2019-05-09 12:14:41'),(13,24,'196439299','196439299PVC60135-3',2800,88,'196439299.PVC.601.35-1',5,1,'1912',601,35,'50+/-%',3,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 3',126.19073729943,601,30,1,'2019-05-09 12:14:41'),(14,24,'196439299','196439299PVC60135-4',2800,88,'196439299.PVC.601.35-1',5,1,'1913',601,35,'50+/-%',4,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 4',126.19073729943,601,30,1,'2019-05-09 12:14:41'),(15,24,'196439299','196439299PVC60135-5',2800,88,'196439299.PVC.601.35-1',5,1,'1914',601,35,'50+/-%',5,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 5',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(16,24,'196439299','196439299PVC60135-6',2800,88,'196439299.PVC.601.35-1',5,1,'1915',601,35,'50+/-%',6,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 6',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(17,24,'196439299','196439299PVC60135-7',2800,88,'196439299.PVC.601.35-1',5,1,'1916',601,35,'50+/-%',7,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 7',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(18,24,'196439299','196439299PVC60135-8',2800,88,'196439299.PVC.601.35-1',5,1,'1917',601,35,'50+/-%',8,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 8',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(19,24,'196439299','196439299PVC60135-9',2800,88,'196439299.PVC.601.35-1',5,1,'1918',601,35,'50+/-%',9,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 9',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(20,24,'196439299','196439299PVC60135-10',2800,88,'196439299.PVC.601.35-1',5,1,'1919',601,35,'50+/-%',10,'JL302019-05-101557503249','196439299.PVC.601.35-1 | 10',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(21,24,'196439299','196439299PVC60135-11',2800,88,'196439299.PVC.601.35-1',5,1,'1920',601,35,'50+/-%',11,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 11',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(22,24,'196439299','196439299PVC60135-12',2800,88,'196439299.PVC.601.35-1',5,1,'1921',601,35,'50+/-%',12,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 12',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(23,24,'196439299','196439299PVC60135-13',2800,88,'196439299.PVC.601.35-1',5,1,'1922',601,35,'50+/-%',13,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 13',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(24,24,'196439299','196439299PVC60135-14',2800,88,'196439299.PVC.601.35-1',5,1,'1923',601,35,'50+/-%',14,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 14',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(25,24,'196439299','196439299PVC60135-15',2800,88,'196439299.PVC.601.35-1',5,1,'1924',601,35,'50+/-%',15,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 15',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(26,24,'196439299','196439299PVC60135-16',2800,88,'196439299.PVC.601.35-1',5,1,'1925',601,35,'50+/-%',16,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 16',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(27,24,'196439299','196439299PVC60135-17',2800,88,'196439299.PVC.601.35-1',5,1,'1926',601,35,'50+/-%',17,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 17',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(28,24,'196439299','196439299PVC60135-18',2800,88,'196439299.PVC.601.35-1',5,1,'1927',601,35,'50+/-%',18,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 18',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(29,24,'196439299','196439299PVC60135-19',2800,88,'196439299.PVC.601.35-1',5,1,'1928',601,35,'50+/-%',19,'JL302019-05-101557511348','196439299.PVC.601.35-1 | 19',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(30,24,'196439299','196439299PVC60135-20',2800,88,'196439299.PVC.601.35-1',5,1,'1929',601,35,'50+/-%',20,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 20',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(31,24,'196439299','196439299PVC60135-21',2800,88,'196439299.PVC.601.35-1',5,1,'1930',601,35,'50+/-%',21,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 21',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(32,24,'196439299','196439299PVC60135-22',2800,88,'196439299.PVC.601.35-1',5,1,'1931',601,35,'50+/-%',22,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 22',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(33,24,'196439299','196439299PVC60135-23',2800,88,'196439299.PVC.601.35-1',5,1,'1932',601,35,'50+/-%',23,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 23',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(34,24,'196439299','196439299PVC60135-24',2800,88,'196439299.PVC.601.35-1',5,1,'1933',601,35,'50+/-%',24,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 24',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(35,24,'196439299','196439299PVC60135-25',2800,88,'196439299.PVC.601.35-1',5,1,'1934',601,35,'50+/-%',25,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 25',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(36,24,'196439299','196439299PVC60135-26',2800,88,'196439299.PVC.601.35-1',5,1,'1935',601,35,'50+/-%',26,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 26',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(37,24,'196439299','196439299PVC60135-27',2800,88,'196439299.PVC.601.35-1',5,1,'1936',601,35,'50+/-%',27,'JL302019-05-131557764775','196439299.PVC.601.35-1 | 27',126.19073729943,601,30,1,'2019-05-09 12:14:42'),(38,24,'196439299','196439299PVC60135-28',2050,88,'196439299.PVC.601.35-1',5,1,'1937',601,35,'50+/-%',28,'JL302019-05-131558034047','196439299.PVC.601.35-1 | 28',92.389646951367,601,30,1,'2019-05-09 12:14:42'),(39,24,'196439299','196439299PVC60135-29',2800,88,'196439299.PVC.601.35-1',5,1,'1938',601,35,'50+/-%',29,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 29',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(40,24,'196439299','196439299PVC60135-30',2800,88,'196439299.PVC.601.35-1',5,1,'1939',601,35,'50+/-%',30,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 30',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(41,24,'196439299','196439299PVC60135-31',2800,88,'196439299.PVC.601.35-1',5,1,'1940',601,35,'50+/-%',31,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 31',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(42,24,'196439299','196439299PVC60135-32',2800,88,'196439299.PVC.601.35-1',5,1,'1941',601,35,'50+/-%',32,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 32',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(43,24,'196439299','196439299PVC60135-33',2800,88,'196439299.PVC.601.35-1',5,1,'1942',601,35,'50+/-%',33,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 33',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(44,24,'196439299','196439299PVC60135-34',2800,88,'196439299.PVC.601.35-1',5,1,'1943',601,35,'50+/-%',34,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 34',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(45,24,'196439299','196439299PVC60135-35',2800,88,'196439299.PVC.601.35-1',5,1,'1944',601,35,'50+/-%',35,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 35',115.66551735534,601,30,1,'2019-05-09 12:14:42'),(46,24,'196439299','196439299PVC60135-36',2800,88,'196439299.PVC.601.35-1',5,1,'1945',601,35,'50+/-%',36,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 36',115.66551735534,601,30,1,'2019-05-09 12:14:43'),(47,24,'196439299','196439299PVC60135-37',2800,88,'196439299.PVC.601.35-1',5,1,'1946',601,35,'50+/-%',37,'JL302019-05-131557772334','196439299.PVC.601.35-1 | 37',115.66551735534,601,30,1,'2019-05-09 12:14:43'),(48,24,'196439299','196439299PVC60135-38',2800,88,'196439299.PVC.601.35-1',5,1,'1947',601,35,'50+/-%',38,'JL302019-05-141557845609','196439299.PVC.601.35-1 | 38',126.19073729943,601,30,1,'2019-05-09 12:14:43'),(49,24,'196439299','196439299PVC60135-39',2500,88,'196439299.PVC.601.35-1',5,1,'1977',601,35,'50+/-%',39,'JL302019-05-141558115835','196439299.PVC.601.35-1 | 39',112.6703011602,601,30,1,'2019-05-09 12:14:43'),(50,24,'196439299','196439299PVC60135-40',2800,88,'196439299.PVC.601.35-1',5,1,'1949',601,35,'50+/-%',40,'JL302019-05-141557845609','196439299.PVC.601.35-1 | 40',126.19073729943,601,30,1,'2019-05-09 12:14:43'),(51,24,'196439299','196439299PVC60135-41',2800,88,'196439299.PVC.601.35-1',5,1,'1950',601,35,'50+/-%',41,'JL302019-05-141557845609','196439299.PVC.601.35-1 | 41',126.19073729943,601,30,1,'2019-05-09 12:14:43'),(52,24,'196439299','196439299PVC60135-42',2800,88,'196439299.PVC.601.35-1',5,1,'1951',601,35,'50+/-%',42,'JL302019-05-141557845609','196439299.PVC.601.35-1 | 42',126.19073729943,601,30,1,'2019-05-09 12:14:43');
/*!40000 ALTER TABLE `tblotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbmerma`
--

DROP TABLE IF EXISTS `tbmerma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbmerma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hora` datetime DEFAULT CURRENT_TIMESTAMP,
  `codigo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidadesIn` float DEFAULT NULL,
  `unidadesOut` float DEFAULT NULL,
  `longIn` float DEFAULT NULL,
  `longOut` float DEFAULT NULL,
  `banderas` int(2) DEFAULT NULL,
  `producto` int(5) DEFAULT NULL,
  `proceso` int(2) DEFAULT NULL,
  `tipo` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_codigo` (`codigo`),
  KEY `codigo` (`codigo`),
  KEY `producto` (`producto`),
  KEY `proceso` (`proceso`)
) ENGINE=InnoDB AUTO_INCREMENT=182818 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbmerma`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbmerma` WRITE;
/*!40000 ALTER TABLE `tbmerma` DISABLE KEYS */;
INSERT INTO `tbmerma` VALUES (1,'2019-05-09 12:55:22','0000003',123.927,123.225,3000,2983,NULL,14,7,'30'),(2,'2019-05-09 12:56:02','0000004',123.927,136.733,3000,3310,NULL,14,7,'30'),(3,'2019-05-09 12:56:48','0000005',123.927,120.168,3000,2909,NULL,14,7,'30'),(4,'2019-05-09 13:37:34','0000013',123.225,110.878,2983,2735,NULL,14,2,'30'),(5,'2019-05-09 14:53:49','0000006',123.927,125.58,3000,3040,NULL,14,7,'30'),(6,'2019-05-09 14:55:03','0000007',123.927,123.927,3000,3000,NULL,14,7,'30'),(7,'2019-05-09 16:02:48','0000008',123.927,118.557,3000,2870,NULL,14,7,'30'),(8,'2019-05-09 16:42:00','0000014',136.733,117.568,3310,2900,NULL,14,2,'30'),(9,'2019-05-09 16:45:52','0000016',125.58,117.568,3040,2900,NULL,14,2,'30'),(11,'2019-05-10 08:39:42','0000015',120.168,117.568,2909,2900,NULL,14,2,'30'),(14,'2019-05-10 08:58:01','0000017',123.927,114.73,3000,2830,NULL,14,2,'30'),(15,'2019-05-10 09:19:30','0000223',36.9595,36.6351,2735,2711,0,14,3,'30'),(16,'2019-05-10 09:59:33','0000123',36.9595,37.2703,2735,2758,0,14,3,'30'),(17,'2019-05-10 10:16:37','0000018',118.557,114.324,2870,2820,NULL,14,2,'30'),(18,'2019-05-10 10:42:52','0000323',36.9595,36.5676,2735,2706,0,14,3,'30'),(19,'2019-05-10 11:00:20','0003533',7.04054,7.10517,521,516,1,14,5,'30'),(20,'2019-05-10 11:03:41','0002433',7.2973,7.47695,540,543,0,14,5,'30'),(21,'2019-05-10 11:04:10','0002333',7.2973,7.39433,540,537,1,14,5,'30'),(22,'2019-05-10 11:04:27','0002533',7.44595,7.42187,551,539,2,14,5,'30'),(23,'2019-05-10 11:06:26','00000011',126.191,126.191,2800,2800,NULL,30,7,'30'),(24,'2019-05-10 11:07:42','00000012',126.191,128.444,2800,2850,NULL,30,7,'30'),(25,'2019-05-10 11:08:44','00000013',126.191,126.191,2800,2800,NULL,30,7,'30'),(31,'2019-05-10 11:28:29','0000124',39.1892,39.3243,2900,2910,0,14,3,'30'),(32,'2019-05-10 11:42:36','0002133',7.2973,7.39433,540,537,0,14,5,'30'),(33,'2019-05-10 11:43:16','0002233',7.2973,7.46318,540,542,1,14,5,'30'),(34,'2019-05-10 11:59:36','00000014',126.191,126.191,2800,2800,NULL,30,7,'30'),(35,'2019-05-10 12:24:25','0000324',39.1892,39.0541,2900,2890,0,14,3,'30'),(36,'2019-05-10 12:38:33','0001234',8.02703,8.12413,594,590,0,14,5,'30'),(37,'2019-05-10 12:39:04','0001434',7.97297,8.08282,590,587,0,14,5,'30'),(38,'2019-05-10 12:39:34','0001534',7.32432,7.29794,542,530,4,14,5,'30'),(39,'2019-05-10 12:39:58','0001334',8,7.97266,592,579,2,14,5,'30'),(40,'2019-05-10 12:51:38','0001433',7.40541,7.46318,548,542,0,14,5,'30'),(41,'2019-05-10 12:52:15','0001533',7.72973,7.8625,572,571,2,14,5,'30'),(43,'2019-05-10 12:53:35','0003233',7.43243,7.91758,550,575,2,14,5,'30'),(44,'2019-05-10 13:06:25','0001134',8,8.11036,592,589,0,14,5,'30'),(45,'2019-05-10 13:06:58','0003134',7.97297,7.90381,590,574,0,14,5,'30'),(46,'2019-05-10 13:47:35','0000224',39.1892,39.0676,2900,2891,0,14,3,'30'),(47,'2019-05-10 13:55:39','0001233',7.39189,7.42187,547,539,1,14,5,'30'),(48,'2019-05-10 14:19:43','00000015',126.191,121.684,2800,2700,NULL,30,7,'30'),(49,'2019-05-10 14:21:00','00000016',126.191,128.444,2800,2850,NULL,30,7,'30'),(50,'2019-05-10 14:25:39','00000017',126.191,123.937,2800,2750,NULL,30,7,'30'),(51,'2019-05-10 14:40:08','00000024',115.666,116.533,2800,2821,NULL,14,7,'30'),(52,'2019-05-10 14:42:07','0000126',39.1892,39.6351,2900,2933,0,14,3,'30'),(54,'2019-05-10 14:44:58','0001333',7.37838,7.53203,546,547,0,14,5,'30'),(55,'2019-05-10 14:45:44','0003133',7.36486,7.43564,545,540,0,14,5,'30'),(56,'2019-05-10 14:46:20','0002134',7.97297,7.97266,590,579,0,14,5,'30'),(57,'2019-05-10 14:46:43','0003434',8.17568,8.26182,605,600,0,14,5,'30'),(58,'2019-05-10 14:47:08','0003334',8.13513,8.22051,602,597,1,14,5,'30'),(59,'2019-05-10 14:47:35','0003234',7.97297,8.08282,590,587,0,14,5,'30'),(60,'2019-05-10 14:47:57','0003534',6.7973,6.67831,503,485,4,14,5,'30');
/*!40000 ALTER TABLE `tbmerma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprocorte`
--

DROP TABLE IF EXISTS `tbprocorte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprocorte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `operador` int(5) DEFAULT NULL,
  `maquina` int(5) DEFAULT NULL,
  `rollo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  `tipo` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `noop` (`noop`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `fecha` (`fecha`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=887792 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprocorte`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprocorte` WRITE;
/*!40000 ALTER TABLE `tbprocorte` DISABLE KEYS */;
INSERT INTO `tbprocorte` VALUES (575812,0,92,'2024-01-02 08:14:51','23802-3-2',8.98187,104,36,'0003248465',1,30),(575813,0,92,'2024-01-02 08:14:51','23802-3-2-1',0.5,104,36,'0032158465',0,30),(575814,0,92,'2024-01-02 08:14:51','23802-3-2-2',0.5,104,36,'0032258465',0,30),(575815,0,92,'2024-01-02 08:14:51','23802-3-2-3',0.5,104,36,'0032358465',0,30),(575816,0,92,'2024-01-02 08:14:51','23802-3-2-4',0.5,104,36,'0032458465',0,30),(575817,0,92,'2024-01-02 08:14:51','23802-3-2-5',0.5,104,36,'0032558465',0,30),(575818,0,92,'2024-01-02 08:14:51','23802-3-2-6',0.5,104,36,'0032658465',0,30),(575819,0,92,'2024-01-02 08:14:51','23802-3-2-7',0.5,104,36,'0032758465',0,30),(575820,0,92,'2024-01-02 08:14:51','23802-3-2-8',0.5,104,36,'0032858465',0,30),(575821,0,92,'2024-01-02 08:14:51','23802-3-2-9',0.5,104,36,'0032958465',0,30),(575822,0,92,'2024-01-02 08:14:51','23802-3-2-10',0.5,104,36,'0321058465',0,30),(575823,0,92,'2024-01-02 08:14:51','23802-3-2-11',0.5,104,36,'0321158465',0,30),(575824,0,92,'2024-01-02 08:14:51','23802-3-2-12',0.5,104,36,'0321258465',0,30),(575825,0,92,'2024-01-02 08:14:51','23802-3-2-13',0.5,104,36,'0321358465',0,30),(575826,0,92,'2024-01-02 08:14:51','23802-3-2-14',0.5,104,36,'0321458465',0,30),(575827,0,92,'2024-01-02 08:14:51','23802-3-2-15',0.5,104,36,'0321558465',0,30),(575828,0,92,'2024-01-02 08:14:51','23802-3-2-16',0.5,104,36,'0321658465',0,30),(575829,0,92,'2024-01-02 08:14:51','23802-3-2-17',0.5,104,36,'0321758465',0,30),(575830,0,92,'2024-01-02 08:14:51','23802-3-2-18',0.5,104,36,'0321858465',0,30),(575831,0,92,'2024-01-02 08:19:40','23621-2-5',9.26701,40,30,'0002548274',1,30),(575832,0,92,'2024-01-02 08:19:40','23621-2-5-1',0.5,40,30,'0025158274',0,30),(575833,0,92,'2024-01-02 08:19:40','23621-2-5-2',0.5,40,30,'0025258274',0,30),(575834,0,92,'2024-01-02 08:19:40','23621-2-5-3',0.5,40,30,'0025358274',0,30),(575835,0,92,'2024-01-02 08:19:40','23621-2-5-4',0.5,40,30,'0025458274',0,30),(575836,0,92,'2024-01-02 08:19:40','23621-2-5-5',0.5,40,30,'0025558274',0,30),(575837,0,92,'2024-01-02 08:19:40','23621-2-5-6',0.5,40,30,'0025658274',0,30),(575838,0,92,'2024-01-02 08:19:40','23621-2-5-7',0.5,40,30,'0025758274',0,30),(575839,0,92,'2024-01-02 08:19:40','23621-2-5-8',0.5,40,30,'0025858274',0,30),(575840,0,92,'2024-01-02 08:19:40','23621-2-5-9',0.5,40,30,'0025958274',0,30),(575841,0,92,'2024-01-02 08:19:40','23621-2-5-10',0.5,40,30,'0251058274',0,30),(575842,0,92,'2024-01-02 08:19:40','23621-2-5-11',0.5,40,30,'0251158274',0,30),(575843,0,92,'2024-01-02 08:19:40','23621-2-5-12',0.5,40,30,'0251258274',0,30),(575844,0,92,'2024-01-02 08:19:40','23621-2-5-13',0.5,40,30,'0251358274',0,30),(575845,0,92,'2024-01-02 08:19:40','23621-2-5-14',0.5,40,30,'0251458274',0,30),(575846,0,92,'2024-01-02 08:19:40','23621-2-5-15',0.5,40,30,'0251558274',0,30),(575847,0,92,'2024-01-02 08:19:40','23621-2-5-16',0.5,40,30,'0251658274',0,30),(575848,0,92,'2024-01-02 08:19:40','23621-2-5-17',0.5,40,30,'0251758274',0,30),(575849,0,92,'2024-01-02 08:19:40','23621-2-5-18',0.5,40,30,'0251858274',0,30),(575850,0,92,'2024-01-02 08:19:40','23621-2-5-19',0.5,40,30,'0251958274',0,30),(575851,0,92,'2024-01-02 08:26:39','23800-2-5',10.0654,159,39,'0002548463',1,30),(575852,0,92,'2024-01-02 08:26:39','23800-2-5-1',0.5,159,39,'0025158463',0,30),(575853,0,92,'2024-01-02 08:26:39','23800-2-5-2',0.5,159,39,'0025258463',0,30),(575854,0,92,'2024-01-02 08:26:39','23800-2-5-3',0.5,159,39,'0025358463',0,30),(575855,0,92,'2024-01-02 08:26:39','23800-2-5-4',0.5,159,39,'0025458463',0,30),(575856,0,92,'2024-01-02 08:26:39','23800-2-5-5',0.5,159,39,'0025558463',0,30),(575857,0,92,'2024-01-02 08:26:39','23800-2-5-6',0.5,159,39,'0025658463',0,30),(575858,0,92,'2024-01-02 08:26:39','23800-2-5-7',0.5,159,39,'0025758463',0,30),(575859,0,92,'2024-01-02 08:26:39','23800-2-5-8',0.5,159,39,'0025858463',0,30),(575860,0,92,'2024-01-02 08:26:39','23800-2-5-9',0.5,159,39,'0025958463',0,30),(575861,0,92,'2024-01-02 08:26:39','23800-2-5-10',0.5,159,39,'0251058463',0,30);
/*!40000 ALTER TABLE `tbprocorte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprocortebu250213`
--

DROP TABLE IF EXISTS `tbprocortebu250213`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprocortebu250213` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `operador` int(5) DEFAULT NULL,
  `maquina` int(5) DEFAULT NULL,
  `rollo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  `tipo` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `noop` (`noop`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `fecha` (`fecha`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=749880 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprocortebu250213`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprocortebu250213` WRITE;
/*!40000 ALTER TABLE `tbprocortebu250213` DISABLE KEYS */;
INSERT INTO `tbprocortebu250213` VALUES (1,0,14,'2019-05-13 16:25:38','192-3-1',7.43564,73,35,'0003143',1,30),(2,0,14,'2019-05-13 16:25:38','192-3-1-1',0.5,73,35,'0031153',0,30),(3,0,14,'2019-05-13 16:25:38','192-3-1-2',0.5,73,35,'0031253',0,30),(4,0,14,'2019-05-13 16:25:38','192-3-1-3',0.5,73,35,'0031353',0,30),(5,0,14,'2019-05-13 16:25:38','192-3-1-4',0.5,73,35,'0031453',0,30),(6,0,14,'2019-05-13 16:25:38','192-3-1-5',0.5,73,35,'0031553',0,30),(7,0,14,'2019-05-13 16:25:38','192-3-1-6',0.5,73,35,'0031653',0,30),(8,0,14,'2019-05-13 16:25:38','192-3-1-7',0.5,73,35,'0031753',0,30),(9,0,14,'2019-05-13 16:25:38','192-3-1-8',0.5,73,35,'0031853',0,30),(10,0,14,'2019-05-13 16:25:38','192-3-1-9',0.5,73,35,'0031953',0,30),(11,0,14,'2019-05-13 16:25:38','192-3-1-10',0.5,73,35,'0311053',0,30),(12,0,14,'2019-05-13 16:25:38','192-3-1-11',0.5,73,35,'0311153',0,30),(13,0,14,'2019-05-13 16:25:38','192-3-1-12',0.5,73,35,'0311253',0,30),(14,0,14,'2019-05-13 16:25:38','192-3-1-13',0.5,73,35,'0311353',0,30),(15,0,14,'2019-05-13 16:25:38','192-3-1-14',0.5,73,35,'0311453',0,30),(16,0,14,'2019-05-13 16:25:38','192-3-1-15',0.5,73,35,'0311553',0,30),(17,0,30,'2019-05-13 16:29:47','1910-1-5',7.81181,28,46,'00015411',1,30),(18,0,30,'2019-05-13 16:29:47','1910-1-5-1',0.5,28,46,'00151511',0,30),(19,0,30,'2019-05-13 16:29:47','1910-1-5-2',0.5,28,46,'00152511',0,30),(20,0,30,'2019-05-13 16:29:47','1910-1-5-3',0.5,28,46,'00153511',0,30),(21,0,30,'2019-05-13 16:29:47','1910-1-5-4',0.5,28,46,'00154511',0,30),(22,0,30,'2019-05-13 16:29:47','1910-1-5-5',0.5,28,46,'00155511',0,30),(23,0,30,'2019-05-13 16:29:47','1910-1-5-6',0.5,28,46,'00156511',0,30),(24,0,30,'2019-05-13 16:29:47','1910-1-5-7',0.5,28,46,'00157511',0,30),(25,0,30,'2019-05-13 16:29:47','1910-1-5-8',0.5,28,46,'00158511',0,30),(26,0,30,'2019-05-13 16:29:47','1910-1-5-9',0.5,28,46,'00159511',0,30),(27,0,30,'2019-05-13 16:29:47','1910-1-5-10',0.5,28,46,'01510511',0,30),(28,0,30,'2019-05-13 16:29:47','1910-1-5-11',0.5,28,46,'01511511',0,30),(29,0,30,'2019-05-13 16:29:47','1910-1-5-12',0.5,28,46,'01512511',0,30),(30,0,30,'2019-05-13 16:29:47','1910-1-5-13',0.5,28,46,'01513511',0,30),(31,0,30,'2019-05-13 16:29:47','1910-1-5-14',0.5,28,46,'01514511',0,30),(32,0,30,'2019-05-13 16:29:47','1910-1-5-15',0.5,28,46,'01515511',0,30),(33,0,30,'2019-05-13 16:29:47','1910-1-5-16',0.5,28,46,'01516511',0,30),(34,0,30,'2019-05-13 16:36:33','1910-3-2',7.81181,42,36,'00032411',1,30),(35,0,30,'2019-05-13 16:36:33','1910-3-2-1',0.5,42,36,'00321511',0,30),(36,0,30,'2019-05-13 16:36:33','1910-3-2-2',0.5,42,36,'00322511',0,30),(37,0,30,'2019-05-13 16:36:33','1910-3-2-3',0.5,42,36,'00323511',0,30),(38,0,30,'2019-05-13 16:36:33','1910-3-2-4',0.5,42,36,'00324511',0,30),(39,0,30,'2019-05-13 16:36:33','1910-3-2-5',0.5,42,36,'00325511',0,30),(40,0,30,'2019-05-13 16:36:33','1910-3-2-6',0.5,42,36,'00326511',0,30),(41,0,30,'2019-05-13 16:36:33','1910-3-2-7',0.5,42,36,'00327511',0,30),(42,0,30,'2019-05-13 16:36:33','1910-3-2-8',0.5,42,36,'00328511',0,30),(43,0,30,'2019-05-13 16:36:33','1910-3-2-9',0.5,42,36,'00329511',0,30),(44,0,30,'2019-05-13 16:36:33','1910-3-2-10',0.5,42,36,'03210511',0,30),(45,0,30,'2019-05-13 16:36:33','1910-3-2-11',0.5,42,36,'03211511',0,30),(46,0,30,'2019-05-13 16:36:33','1910-3-2-12',0.5,42,36,'03212511',0,30),(47,0,30,'2019-05-13 16:36:33','1910-3-2-13',0.5,42,36,'03213511',0,30),(48,0,30,'2019-05-13 16:36:33','1910-3-2-14',0.5,42,36,'03214511',0,30),(49,0,30,'2019-05-13 16:36:33','1910-3-2-15',0.5,42,36,'03215511',0,30),(50,0,30,'2019-05-13 16:36:33','1910-3-2-16',0.5,42,36,'03216511',0,30);
/*!40000 ALTER TABLE `tbprocortebu250213` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbproduccion`
--

DROP TABLE IF EXISTS `tbproduccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbproduccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreProducto` int(6) NOT NULL,
  `juegoLotes` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `cantLotes` int(3) NOT NULL,
  `juegoCilindros` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `disenio` int(4) NOT NULL,
  `maquina` int(4) NOT NULL,
  `fechaProduccion` date NOT NULL,
  `tipo` int(5) NOT NULL,
  `unidades` float NOT NULL,
  `suaje` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `juegoCireles` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `cdgempleado` int(5) NOT NULL,
  `fechamov` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `juegoLotes` (`juegoLotes`),
  KEY `id` (`id`),
  KEY `nombreProducto` (`nombreProducto`),
  KEY `juegoLotes_2` (`juegoLotes`),
  KEY `fechaProduccion` (`fechaProduccion`)
) ENGINE=InnoDB AUTO_INCREMENT=1724 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbproduccion`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbproduccion` WRITE;
/*!40000 ALTER TABLE `tbproduccion` DISABLE KEYS */;
INSERT INTO `tbproduccion` VALUES (3,14,'JL302019-05-091557422509',6,'W9011138',16,16,'2019-05-09',30,743.564,'','',91,'2019-05-09 12:21:49',0),(6,30,'JL302019-05-101557503249',10,'W9011139-1 ',6,17,'2019-05-10',30,1261.91,'','',91,'2019-05-10 10:47:29',0),(7,14,'JL302019-05-101557511348',9,'W9011138',16,16,'2019-05-10',30,1040.99,'','',91,'2019-05-10 13:02:28',0),(8,30,'JL302019-05-131557764775',8,'W9011139-1 ',6,1,'2019-05-13',30,1009.53,'','',91,'2019-05-13 11:26:15',0),(9,14,'JL302019-05-131557772334',9,'W9011138',16,16,'2019-05-13',30,1040.99,'','',91,'2019-05-13 13:32:14',0),(10,30,'JL302019-05-141557845609',9,'W9011139-1 ',6,17,'2019-05-14',30,1135.72,'','',91,'2019-05-14 09:53:29',0),(11,32,'JL302019-05-141557872502',1,'W8113037-1',6,1,'2019-05-14',30,226.721,'','',91,'2019-05-14 17:21:42',0),(12,14,'JL302019-05-151557925547',10,'W9011138',16,16,'2019-05-15',30,1156.66,'','',91,'2019-05-15 08:05:47',0),(13,31,'JL302019-05-151557948510',2,'W 8113037 - 1',6,1,'2019-05-15',30,453.443,'','',91,'2019-05-15 14:28:30',0),(14,31,'JL302019-05-161558017882',7,'W 8113037 - 1',6,1,'2019-05-16',30,1587.05,'','',91,'2019-05-16 09:44:42',0),(16,30,'JL302019-05-131558034047',1,'W9011139-1 ',6,1,'2019-05-13',30,92.3896,'','',91,'2019-05-16 14:14:07',0),(17,21,'JL302019-05-161558034752',9,'W9011140',6,16,'2019-05-16',30,1034.8,'','',91,'2019-05-16 14:25:52',0),(20,30,'JL302019-05-141558115835',1,'W9011139-1 ',6,17,'2019-05-14',30,112.67,'','',91,'2019-05-17 12:57:15',0),(21,31,'JL302019-05-161558116126',1,'W 8113037 - 1',6,1,'2019-05-16',30,202.43,'','',91,'2019-05-17 13:02:06',0),(22,32,'JL302019-05-201558358757',11,'W8113037-1',6,1,'2019-05-20',30,2493.93,'','',91,'2019-05-20 08:25:57',0),(23,30,'JL302019-05-201558381588',10,'W9011139-1 ',6,17,'2019-05-20',30,1261.91,'','',91,'2019-05-20 14:46:28',0),(24,21,'JL302019-05-201558381686',8,'W9011140',6,16,'2019-05-20',30,919.821,'','',91,'2019-05-20 14:48:06',0),(25,30,'JL302019-05-221558537159',9,'W9011139-1 ',6,17,'2019-05-22',30,1157.98,'','',91,'2019-05-22 09:59:19',0),(26,21,'JL302019-05-221558537281',10,'W9011140',6,16,'2019-05-22',30,1168.01,'','',91,'2019-05-22 10:01:21',0),(27,21,'JL302019-05-221558553117',3,'W9011140',6,16,'2019-05-22',30,353.926,'','',91,'2019-05-22 14:25:17',0),(28,49,'JL302019-05-231558647466',8,'Z26227-S',19,16,'2019-05-23',30,752.766,'','',91,'2019-05-23 16:37:46',0),(29,51,'JL302019-05-271558974763',5,'W9050903',6,1,'2019-05-27',30,648.892,'','',91,'2019-05-27 11:32:43',0),(30,52,'JL302019-05-271558975156',8,'W9050904',6,17,'2019-05-27',30,940.937,'','',91,'2019-05-27 11:39:16',0),(32,32,'JL302019-05-271558990129',2,'W8113037-1',6,1,'2019-05-27',30,429.151,'','',91,'2019-05-27 15:48:49',0),(33,49,'JL302019-05-231558990338',1,'Z26227-S',19,16,'2019-05-23',30,85.5416,'','',91,'2019-05-27 15:52:18',0),(35,49,'JL302019-05-271558995458',9,'Z26227-S',19,16,'2019-05-27',30,846.862,'','',91,'2019-05-27 17:17:38',0),(38,52,'JL302019-05-271559051772',10,'W9050904',6,17,'2019-05-27',30,1195.46,'','',91,'2019-05-28 08:56:12',0),(39,32,'JL302019-05-281559064329',1,'W8113037-1',6,1,'2019-05-28',30,217.005,'','',91,'2019-05-28 12:25:29',0),(41,52,'JL302019-05-281559073010',8,'W9050904',6,17,'2019-05-28',30,940.07,'','',91,'2019-05-28 14:50:10',0),(42,48,'JL302019-05-281559075225',9,'Z26226 1',19,16,'2019-05-28',30,847.811,'','',91,'2019-05-28 15:27:05',0),(43,44,'JL312019-05-291559138858',1,'W8111033R',3,16,'2019-05-29',31,32.6844,'','',91,'2019-05-29 09:07:38',2),(44,52,'JL302019-05-291559153526',3,'W9050904',6,17,'2019-05-29',30,343.023,'','',91,'2019-05-29 13:12:06',0),(45,48,'JL302019-05-291559157777',8,'Z26226 1',19,16,'2019-05-29',30,753.61,'','',91,'2019-05-29 14:22:57',0),(46,48,'JL302019-05-301559232200',3,'Z26226 1',19,16,'2019-05-30',30,282.604,'','',61,'2019-05-30 11:03:20',0),(49,37,'JL302019-05-301559233221',10,'W 9011138',6,17,'2019-05-30',30,1183.38,'','',61,'2019-05-30 11:20:21',0),(50,14,'JL302019-05-311559308462',8,'W9011138',16,17,'2019-05-31',30,939.576,'','',91,'2019-05-31 08:14:22',0),(53,53,'JL302019-05-311559309462',6,'W9050905',6,16,'2019-05-31',30,700.327,'','',91,'2019-05-31 08:31:02',0),(54,14,'JL302019-05-311559334590',11,'W9011138',16,17,'2019-05-31',30,1291.9,'','',91,'2019-05-31 15:29:50',0),(55,60,'JL302019-06-031559567770',6,'W9050903 2',6,1,'2019-06-03',30,509.093,'','',91,'2019-06-03 08:16:10',0),(56,53,'JL302019-06-031559591639',7,'W9050905',6,16,'2019-06-03',30,818.194,'','',91,'2019-06-03 14:53:59',0),(58,14,'JL302019-06-041559663585',8,'W9011138',16,17,'2019-06-04',30,925.737,'','',91,'2019-06-04 10:53:05',0),(59,14,'JL302019-06-041559676164',9,'W9011138',16,17,'2019-06-04',30,1053.88,'','',91,'2019-06-04 14:22:44',0),(60,60,'JL302019-06-051559748669',9,'W9050903 2',6,1,'2019-06-05',30,759.346,'','',91,'2019-06-05 10:31:09',0),(62,30,'JL302019-06-051559767375',7,'W9011139',6,17,'2019-06-05',30,907.852,'','',91,'2019-06-05 15:42:55',0),(65,60,'JL302019-06-051559835411',2,'W9050903 2',6,1,'2019-06-05',30,129.571,'','',61,'2019-06-06 10:36:51',0),(67,60,'JL302019-06-061559839438',12,'W9050903 2',6,1,'2019-06-06',30,1002.13,'','',61,'2019-06-06 11:43:58',0),(68,58,'JL302019-06-061559839525',9,'W9052207',22,16,'2019-06-06',30,841.647,'','',61,'2019-06-06 11:45:25',0),(71,58,'JL302019-06-061559858895',10,'W9052207',22,16,'2019-06-06',30,955.269,'','',91,'2019-06-06 17:08:15',0),(72,53,'JL302019-05-311559923452',1,'W9050905',6,16,'2019-05-31',30,69.9381,'','',61,'2019-06-07 11:04:12',0),(73,30,'JL302019-06-051559929130',2,'W9011139-1 ',6,17,'2019-06-05',30,230.614,'','',61,'2019-06-07 12:38:50',0);
/*!40000 ALTER TABLE `tbproduccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbproembosado`
--

DROP TABLE IF EXISTS `tbproembosado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbproembosado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noop` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` int(11) NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `producto` int(11) NOT NULL,
  `operador` int(11) DEFAULT NULL,
  `maquina` int(11) DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `noop` (`noop`),
  KEY `id` (`id`),
  KEY `fecha` (`fecha`),
  KEY `producto` (`producto`),
  KEY `operador` (`operador`),
  KEY `lote` (`lote`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbproembosado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbproembosado` WRITE;
/*!40000 ALTER TABLE `tbproembosado` DISABLE KEYS */;
INSERT INTO `tbproembosado` VALUES (7,'205','2020-02-13 16:50:10',0,540.377,91,29,54,'0000002613',1790,167,15,0,43,0),(8,'204','2020-02-17 09:11:30',0,223.881,69,38,15,'0000003174',450,160,12,1,43,0),(9,'2026','2020-09-08 08:27:32',0,547.264,69,42,15,'0000003694',1100,160,8.9,0,43,0),(10,'2027','2020-09-08 08:28:11',0,547.264,69,42,15,'0000003695',1100,160,8.9,0,43,0),(11,'2028','2020-09-08 08:28:38',0,547.264,69,42,15,'0000003696',1100,160,8.9,0,43,0),(12,'2029','2020-09-08 08:29:08',0,547.264,69,42,15,'0000003697',1100,160,8.9,0,43,0);
/*!40000 ALTER TABLE `tbproembosado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprofoliado`
--

DROP TABLE IF EXISTS `tbprofoliado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprofoliado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` int(11) NOT NULL,
  `maquina` int(11) NOT NULL,
  `bobina` varchar(50) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `tipo` int(11) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `id` (`id`),
  KEY `fecha` (`fecha`),
  KEY `operador` (`operador`),
  KEY `bobina` (`bobina`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprofoliado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprofoliado` WRITE;
/*!40000 ALTER TABLE `tbprofoliado` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbprofoliado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprofusion`
--

DROP TABLE IF EXISTS `tbprofusion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprofusion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` int(5) DEFAULT NULL,
  `maquina` int(5) DEFAULT NULL,
  `bobina` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disco` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `amplitud` float DEFAULT NULL,
  `bandera` int(2) DEFAULT NULL,
  `cdgDisco` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int(5) NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `operador` (`operador`),
  KEY `bobina` (`bobina`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=129909 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprofusion`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprofusion` WRITE;
/*!40000 ALTER TABLE `tbprofusion` DISABLE KEYS */;
INSERT INTO `tbprofusion` VALUES (1,1,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',0,'192-2',NULL,NULL,NULL,30,0,1),(2,0,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',540,'192-2-1',97,0,NULL,30,7.2973,0),(3,0,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',540,'192-2-2',97,0,NULL,30,7.2973,0),(4,0,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',540,'192-2-3',97,0,NULL,30,7.2973,0),(5,0,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',540,'192-2-4',97,0,NULL,30,7.2973,0),(6,0,14,'2019-05-10 09:19:30',74,33,'0000223','003340861010',551,'192-2-5',97,0,NULL,30,7.44595,0),(7,1,14,'2019-05-10 09:59:32',74,33,'0000123','000',0,'192-1',NULL,NULL,NULL,30,0,1),(8,0,14,'2019-05-10 09:59:32',74,33,'0000123','000',545,'192-1-1',97,0,NULL,30,7.36486,0),(9,0,14,'2019-05-10 09:59:32',74,33,'0000123','000',547,'192-1-2',97,0,NULL,30,7.39189,0),(10,0,14,'2019-05-10 09:59:32',74,33,'0000123','000',546,'192-1-3',97,0,NULL,30,7.37838,0),(11,0,14,'2019-05-10 09:59:32',74,33,'0000123','000',548,'192-1-4',97,0,NULL,30,7.40541,0),(12,0,14,'2019-05-10 09:59:32',74,33,'0000123','000',572,'192-1-5',97,0,NULL,30,7.72973,0),(13,1,14,'2019-05-10 10:42:52',74,33,'0000323','00',0,'192-3',NULL,NULL,NULL,30,0,1),(14,0,14,'2019-05-10 10:42:52',74,33,'0000323','00',545,'192-3-1',97,0,NULL,30,7.36486,0),(15,0,14,'2019-05-10 10:42:52',74,33,'0000323','00',550,'192-3-2',97,0,NULL,30,7.43243,0),(16,0,14,'2019-05-10 10:42:52',74,33,'0000323','00',545,'192-3-3',97,0,NULL,30,7.36486,0),(17,0,14,'2019-05-10 10:42:52',74,33,'0000323','00',545,'192-3-4',97,0,NULL,30,7.36486,0),(18,0,14,'2019-05-10 10:42:52',74,33,'0000323','00',521,'192-3-5',97,0,NULL,30,7.04054,0),(19,1,14,'2019-05-10 11:28:29',74,33,'0000124','000',0,'193-1',NULL,NULL,NULL,30,0,1),(20,0,14,'2019-05-10 11:28:29',74,33,'0000124','000',592,'193-1-1',97,0,NULL,30,8,0),(21,0,14,'2019-05-10 11:28:29',74,33,'0000124','000',594,'193-1-2',97,0,NULL,30,8.02703,0),(22,0,14,'2019-05-10 11:28:29',74,33,'0000124','000',592,'193-1-3',97,0,NULL,30,8,0),(23,0,14,'2019-05-10 11:28:29',74,33,'0000124','000',590,'193-1-4',97,0,NULL,30,7.97297,0),(24,0,14,'2019-05-10 11:28:29',74,33,'0000124','000',542,'193-1-5',97,0,NULL,30,7.32432,0),(25,1,14,'2019-05-10 11:28:29',74,33,'0000124','000',0,'193-1-6',0,0,NULL,30,0,0),(26,1,14,'2019-05-10 12:24:25',74,33,'0000324','00',0,'193-3',NULL,NULL,NULL,30,0,1),(27,0,14,'2019-05-10 12:24:25',74,33,'0000324','00',590,'193-3-1',97,0,NULL,30,7.97297,0),(28,0,14,'2019-05-10 12:24:25',74,33,'0000324','00',590,'193-3-2',97,0,NULL,30,7.97297,0),(29,0,14,'2019-05-10 12:24:25',74,33,'0000324','00',602,'193-3-3',97,0,NULL,30,8.13513,0),(30,0,14,'2019-05-10 12:24:25',74,33,'0000324','00',605,'193-3-4',97,0,NULL,30,8.17568,0),(31,0,14,'2019-05-10 12:24:25',74,33,'0000324','00',503,'193-3-5',97,0,NULL,30,6.7973,0),(32,1,14,'2019-05-10 12:24:25',74,33,'0000324','00',0,'193-3-6',0,0,NULL,30,0,0),(33,1,14,'2019-05-10 13:47:35',74,33,'0000224','000',0,'193-2',NULL,NULL,NULL,30,0,1),(34,0,14,'2019-05-10 13:47:35',74,33,'0000224','000',590,'193-2-1',97,0,NULL,30,7.97297,0),(35,0,14,'2019-05-10 13:47:35',74,33,'0000224','000',587,'193-2-2',97,0,NULL,30,7.93243,0),(36,0,14,'2019-05-10 13:47:35',74,33,'0000224','000',590,'193-2-3',97,0,NULL,30,7.97297,0),(37,0,14,'2019-05-10 13:47:35',74,33,'0000224','000',580,'193-2-4',97,0,NULL,30,7.83784,0),(38,0,14,'2019-05-10 13:47:35',74,33,'0000224','000',544,'193-2-5',97,0,NULL,30,7.35135,0),(39,1,14,'2019-05-10 13:47:35',74,33,'0000224','000',0,'193-2-6',0,0,NULL,30,0,0),(40,1,14,'2019-05-10 14:42:06',74,33,'0000126','000',0,'195-1',NULL,NULL,NULL,30,0,1),(41,0,14,'2019-05-10 14:42:06',74,33,'0000126','000',613,'195-1-1',97,0,NULL,30,8.28378,0),(42,0,14,'2019-05-10 14:42:06',74,33,'0000126','000',580,'195-1-2',97,0,NULL,30,7.83784,0),(43,0,14,'2019-05-10 14:42:06',74,33,'0000126','000',580,'195-1-3',97,0,NULL,30,7.83784,0),(44,0,14,'2019-05-10 14:42:06',74,33,'0000126','000',580,'195-1-4',97,0,NULL,30,7.83784,0),(45,0,14,'2019-05-10 14:42:06',74,33,'0000126','000',580,'195-1-5',97,0,NULL,30,7.83784,0),(46,1,14,'2019-05-10 14:42:06',74,33,'0000126','000',0,'195-1-6',0,0,NULL,30,0,0),(47,1,14,'2019-05-13 08:42:07',74,33,'0000226','0000',0,'195-2',NULL,NULL,NULL,30,0,1),(48,0,14,'2019-05-13 08:42:07',74,33,'0000226','0000',590,'195-2-1',97,0,NULL,30,8.12413,0),(49,0,14,'2019-05-13 08:42:07',74,33,'0000226','0000',590,'195-2-2',97,0,NULL,30,8.12413,0),(50,0,14,'2019-05-13 08:42:07',74,33,'0000226','0000',590,'195-2-3',97,0,NULL,30,8.12413,0);
/*!40000 ALTER TABLE `tbprofusion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbproimpresion`
--

DROP TABLE IF EXISTS `tbproimpresion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbproimpresion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operador` int(5) NOT NULL,
  `maquina` int(5) NOT NULL,
  `juegoCilindros` int(5) NOT NULL,
  `lote` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int(5) NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `operador` (`operador`),
  KEY `juegoCilindros` (`juegoCilindros`),
  KEY `lote` (`lote`),
  KEY `producto` (`producto`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=6807 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbproimpresion`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbproimpresion` WRITE;
/*!40000 ALTER TABLE `tbproimpresion` DISABLE KEYS */;
INSERT INTO `tbproimpresion` VALUES (1,83,16,8,'0000003',0,14,'2019-05-09 12:55:22',2983,93.2,'192',30,123.225,0),(2,83,16,8,'0000004',0,14,'2019-05-09 12:56:02',3310,95.3,'193',30,136.733,0),(3,83,16,8,'0000005',0,14,'2019-05-09 12:56:48',2909,90.3,'194',30,120.168,0),(4,93,16,8,'0000006',0,14,'2019-05-09 14:53:49',3040,91.9,'195',30,125.58,0),(5,93,16,8,'0000007',0,14,'2019-05-09 14:55:03',3000,92.3,'196',30,123.927,0),(6,93,16,8,'0000008',0,14,'2019-05-09 16:02:48',2870,88.5,'197',30,118.557,0),(7,62,17,24,'00000011',0,30,'2019-05-10 11:06:26',2800,90,'1910',30,126.191,0),(8,89,17,24,'00000012',0,30,'2019-05-10 11:07:42',2850,91,'1911',30,128.444,0),(9,62,17,24,'00000013',0,30,'2019-05-10 11:08:44',2800,90,'1912',30,126.191,0),(10,89,17,24,'00000014',0,30,'2019-05-10 11:59:36',2800,90,'1913',30,126.191,0),(11,62,17,24,'00000015',0,30,'2019-05-10 14:19:43',2700,89,'1914',30,121.684,0),(12,89,17,24,'00000016',0,30,'2019-05-10 14:21:00',2850,91,'1915',30,128.444,0),(13,62,17,24,'00000017',0,30,'2019-05-10 14:25:39',2750,89.3,'1916',30,123.937,0),(14,93,16,8,'00000024',0,14,'2019-05-10 14:40:08',2821,89,'1923',30,116.533,0),(15,83,16,8,'00000021',0,14,'2019-05-10 14:48:54',2788,85.6,'1920',30,115.17,0),(16,83,16,8,'00000022',0,14,'2019-05-10 14:50:01',2774,85,'1921',30,114.591,0),(17,83,16,8,'00000023',0,14,'2019-05-10 14:50:47',2806,86.9,'1922',30,115.913,0),(18,89,17,24,'00000018',0,30,'2019-05-13 10:25:25',2850,91,'1917',30,128.444,0),(19,93,16,8,'00000025',0,14,'2019-05-13 10:33:58',2835,90.1,'1924',30,117.111,0),(20,87,17,24,'00000019',0,30,'2019-05-13 11:34:20',2800,90,'1918',30,126.191,0),(21,89,17,24,'00000030',0,30,'2019-05-13 12:24:33',2850,91,'1929',30,128.444,0),(22,83,16,8,'00000026',0,14,'2019-05-13 12:30:44',2800,90,'1925',30,115.666,0),(23,93,16,8,'00000027',0,14,'2019-05-13 12:31:52',2775,85,'1926',30,114.633,0),(24,93,16,8,'00000028',0,14,'2019-05-13 13:37:31',2800,89.8,'1927',30,115.666,0),(25,83,16,8,'00000029',0,14,'2019-05-13 14:17:05',2700,83,'1928',30,111.535,0),(26,87,17,24,'00000031',0,30,'2019-05-13 14:34:33',2750,89,'1930',30,123.937,0),(27,89,17,24,'00000032',0,30,'2019-05-13 14:35:33',2750,89,'1931',30,123.937,0),(28,87,17,24,'00000020',0,30,'2019-05-13 14:36:55',2750,89,'1919',30,123.937,0),(29,89,17,24,'00000033',0,30,'2019-05-13 15:16:28',2730,88.8,'1932',30,123.036,0),(30,83,16,8,'00000039',0,14,'2019-05-13 15:19:40',2800,89.9,'1938',30,115.666,0),(31,87,17,24,'00000034',0,30,'2019-05-13 16:02:16',2850,91,'1933',30,128.444,0),(32,93,16,8,'00000040',0,14,'2019-05-13 16:18:41',2750,88,'1939',30,113.6,0),(33,89,17,24,'00000035',0,30,'2019-05-13 16:48:05',2750,92,'1934',30,123.937,0),(34,83,16,8,'00000041',0,14,'2019-05-13 17:02:43',2700,87.5,'1940',30,111.535,0),(35,89,17,24,'00000036',0,30,'2019-05-13 17:13:11',2750,89.5,'1935',30,123.937,0),(36,87,17,24,'00000037',0,30,'2019-05-13 17:29:52',2800,90.5,'1936',30,126.191,0),(37,93,16,8,'00000042',0,14,'2019-05-14 11:03:40',2800,89,'1941',30,115.666,0),(38,93,16,8,'00000043',0,14,'2019-05-14 11:54:53',2600,88,'1942',30,107.404,0),(39,93,16,8,'00000044',0,14,'2019-05-14 12:51:13',3000,90,'1943',30,123.927,0),(40,93,16,8,'00000045',0,14,'2019-05-14 13:39:16',2746,87,'1944',30,113.435,0),(41,93,16,8,'00000047',0,14,'2019-05-14 15:14:44',2740,87,'1946',30,113.187,0),(42,93,16,8,'00000046',0,14,'2019-05-14 17:34:32',2800,88,'1945',30,115.666,0),(43,83,16,8,'00000058',0,14,'2019-05-15 08:18:52',2800,89.9,'1960',30,115.666,0),(44,83,16,8,'00000059',0,14,'2019-05-15 08:20:09',2800,89.6,'1961',30,115.666,0),(45,93,16,8,'00000060',0,14,'2019-05-15 08:26:41',2751,87.8,'1962',30,113.641,0),(46,86,1,15,'000000120',0,32,'2019-05-15 09:43:26',2800,88,'1957',30,226.721,0),(47,93,16,8,'00000061',0,14,'2019-05-15 09:44:29',2800,90,'1963',30,115.666,0),(48,83,16,8,'00000063',0,14,'2019-05-15 10:12:41',2800,89.5,'1965',30,115.666,0),(49,93,16,8,'00000062',0,14,'2019-05-15 10:49:59',2700,87,'1964',30,111.535,0),(50,83,16,8,'00000064',0,14,'2019-05-15 11:24:37',2700,87.4,'1966',30,109.459,0);
/*!40000 ALTER TABLE `tbproimpresion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbproimpresion-flexografica`
--

DROP TABLE IF EXISTS `tbproimpresion-flexografica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbproimpresion-flexografica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operador` int(5) NOT NULL,
  `maquina` int(5) NOT NULL,
  `juegoCireles` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `suaje` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lote` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `peso` float NOT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` int(5) NOT NULL,
  `unidades` float NOT NULL,
  `anillox` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `operador` (`operador`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=1364 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbproimpresion-flexografica`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbproimpresion-flexografica` WRITE;
/*!40000 ALTER TABLE `tbproimpresion-flexografica` DISABLE KEYS */;
INSERT INTO `tbproimpresion-flexografica` VALUES (2,87,9,'15','','0000002406',0,68,'2019-08-15 11:14:02',1415,15,'1932',40,13.4762,'',0),(3,87,14,'13','','0000002410',0,67,'2019-08-16 12:18:27',850,8,'1934',40,17,'',0),(4,87,14,'13','','0000002407',0,67,'2019-08-19 11:15:31',800,7.5,'1933',40,16,'',0),(5,87,14,'13','','0000002408',0,67,'2019-08-19 13:10:48',1100,10.5,'1935',40,22,'',0),(6,87,14,'13','','0000002409',0,67,'2019-08-19 16:52:05',1600,15.8,'1936',40,32,'',0),(7,87,14,'15','','0000002485',0,68,'2019-08-22 15:32:01',1900,12,'1937',40,18.0952,'',0),(8,87,1,'13','','0000002487',0,67,'2019-09-03 16:09:04',1300,10,'1938',40,26,'',0),(9,87,9,'15','','0000002484',0,68,'2019-09-10 15:44:23',1350,11,'1939',40,12.8571,'',0),(10,87,14,'13','','0000002486',0,67,'2019-09-13 11:22:29',1000,12,'1940',40,20,'',0),(11,87,14,'15','','0000002507',0,68,'2019-09-17 15:06:25',2200,22,'1941',40,20.9524,'',0),(12,87,14,'15','','0000002563',0,68,'2019-09-19 11:20:27',800,20,'1942',40,7.61905,'',0),(13,87,14,'15','','0000002508',0,68,'2019-09-19 11:21:59',900,22,'1943',40,8.57143,'',0),(14,87,14,'15','','0000002509',0,68,'2019-09-19 11:22:34',500,10,'1944',40,4.7619,'',0),(16,87,14,'15','','0000002564',0,68,'2019-09-19 12:25:39',800,20,'1945',40,7.61905,'',0),(20,87,14,'13','','0000002569',0,67,'2019-09-20 10:26:05',500,12,'1946',40,10,'',0),(25,87,14,'13','','0000002595',0,67,'2019-09-26 07:53:24',500,12,'1947',40,10,'',0),(26,87,14,'13','','0000002596',0,67,'2019-09-26 07:55:19',500,12.5,'1948',40,10,'',0),(27,87,14,'13','','0000002597',0,67,'2019-09-26 07:55:55',500,11.9,'1949',40,10,'',0),(28,87,14,'13','','0000002598',0,67,'2019-09-26 07:56:49',500,12,'1950',40,10,'',0),(29,87,14,'13','','0000002599',0,67,'2019-09-26 07:57:34',500,12,'1951',40,10,'',0),(30,87,14,'13','','0000002600',0,67,'2019-09-26 07:58:26',500,12.3,'1952',40,10,'',0),(32,87,9,'6','','0000002570',0,61,'2019-09-26 10:12:49',225,5,'1943',35,2.55682,'',0),(33,87,9,'6','','0000002571',0,61,'2019-09-26 10:14:21',225,5,'1944',35,2.55682,'',0),(35,87,14,'13','','0000002601',0,67,'2019-09-26 15:48:02',500,12,'1953',40,10,'',0),(36,87,14,'13','','0000002602',0,67,'2019-09-26 15:49:03',500,12,'1954',40,10,'',0),(37,87,14,'13','','0000002603',0,67,'2019-09-26 15:52:24',500,12,'1955',40,10,'',0),(38,87,14,'13','','0000002604',0,67,'2019-09-26 16:51:34',450,10,'1956',40,9,'',0),(39,87,9,'7','','0000002612',0,63,'2019-09-27 09:14:04',160,5,'1952',35,2.53968,'',0),(40,87,9,'7','','0000002611',0,63,'2019-09-27 09:15:57',160,4.5,'1951',35,2.53968,'',0),(41,87,9,'7','','0000002610',0,63,'2019-09-27 09:16:40',158,5,'1950',35,2.50794,'',0),(42,87,9,'7','','0000002609',0,63,'2019-09-27 09:17:46',157.5,5,'1945',35,2.5,'',0),(43,87,9,'6','','0000002565',0,61,'2019-09-27 15:45:01',190,5,'1953',35,2.15909,'',0),(44,87,9,'6','','0000002566',0,61,'2019-09-27 15:46:03',212.5,5,'1939',35,2.41477,'',0),(45,87,14,'15','','0000002680',0,68,'2019-10-01 11:25:30',500,8.5,'1957',40,4.7619,'',0),(46,87,14,'15','','0000002681',0,68,'2019-10-01 11:26:04',500,8.8,'1958',40,4.7619,'',0),(47,87,14,'15','','0000002682',0,68,'2019-10-01 12:19:37',500,8.5,'1959',40,4.7619,'',0),(48,89,14,'15','','0000002683',0,68,'2019-10-01 14:13:50',500,8.8,'1960',40,4.7619,'',0),(49,89,14,'15','','0000002684',0,68,'2019-10-01 14:14:30',500,8.5,'1961',40,4.7619,'',0),(50,87,14,'15','','0000002685',0,68,'2019-10-02 07:51:04',500,8.5,'1962',40,4.7619,'',0),(51,87,14,'15','','0000002686',0,68,'2019-10-02 07:51:55',500,8.8,'1963',40,4.7619,'',0),(52,87,14,'15','','0000002687',0,68,'2019-10-02 07:52:22',500,8.5,'1964',40,4.7619,'',0),(53,87,14,'15','','0000002688',0,68,'2019-10-02 09:55:15',500,9,'1965',40,4.7619,'',0),(54,87,14,'15','','0000002689',0,68,'2019-10-02 09:56:00',500,8.5,'1966',40,4.7619,'',0),(55,87,14,'15','','0000002690',0,68,'2019-10-02 11:28:45',500,10,'1967',40,4.7619,'',0),(56,89,14,'15','','0000002691',0,68,'2019-10-02 11:29:27',500,8.8,'1968',40,4.7619,'',0),(57,87,16,'4','','0000002568',0,62,'2019-10-02 12:42:16',220,5,'1940',35,2.5,'',0),(58,87,14,'15','','0000002692',0,68,'2019-10-02 14:05:40',500,8.5,'1969',40,4.7619,'',0),(59,89,14,'15','','0000002693',0,68,'2019-10-02 14:06:09',500,9,'1970',40,4.7619,'',0),(60,87,9,'4','','0000002573',0,62,'2019-10-02 15:00:01',220,7,'1942',35,2.5,'',0),(61,87,9,'9','','0000002700',0,64,'2019-10-02 15:04:20',220,7,'1947',35,3.49206,'',0);
/*!40000 ALTER TABLE `tbproimpresion-flexografica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprolaminado`
--

DROP TABLE IF EXISTS `tbprolaminado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprolaminado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `operador` int(11) DEFAULT NULL,
  `maquina` int(11) DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `bandera` int(11) DEFAULT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `noop` (`noop`),
  KEY `operador` (`operador`),
  KEY `lote` (`lote`)
) ENGINE=InnoDB AUTO_INCREMENT=317 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprolaminado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprolaminado` WRITE;
/*!40000 ALTER TABLE `tbprolaminado` DISABLE KEYS */;
INSERT INTO `tbprolaminado` VALUES (1,44,'2019-05-29 09:33:52','194',0,32.6844,87,6,'000002805',1800,127,70,31,3,0),(15,44,'2019-06-21 12:00:47','1917',0,34.5002,87,6,'0000021232',1900,127,70,31,3,0),(16,44,'2019-06-21 12:40:35','1919',0,32.6844,87,6,'0000021233',1800,127,70,31,3,0),(33,44,'2019-07-30 16:25:26','1933',0,32.6844,87,6,'0000021262',1800,127,25,31,3,0),(34,42,'2019-07-30 16:43:16','1940',0,57.8072,87,6,'0000021321',1900,127,7.5,31,3,0),(35,43,'2019-07-30 16:52:33','1944',1,32.7405,87,6,'0000021373',1800,127,7.5,31,3,0),(36,45,'2019-07-30 17:12:34','1943',0,34.5594,87,6,'0000021372',1900,127,9,31,3,0),(39,45,'2019-08-01 12:37:17','1945',0,32.7405,53,6,'0000021374',1800,381,25,31,3,0),(70,20,'2019-10-28 11:03:59','191',0,0,38,6,'0000002923',3890,320,35.99,1,2,0),(71,20,'2019-10-28 09:17:14','192',0,0,55,6,'0000002924',3900,320,35.42,1,2,0),(75,9,'2019-10-28 13:53:18','193',0,15600,38,6,'0000002925',3900,320,2,1,1,0),(76,9,'2019-10-29 15:43:48','197',0,15600,55,6,'0000002926',3900,320,34.9,1,2,0),(77,4,'2019-10-29 15:46:09','195',0,15740,55,6,'0000002929',3935,320,35.5,1,2,0),(78,4,'2019-10-29 15:47:00','196',0,15840,55,6,'0000002930',3960,320,35.79,1,2,0),(79,20,'2019-11-01 09:13:49','198',0,8040,38,6,'0000002942',4020,320,2,1,0,0),(85,20,'2019-11-14 10:04:25','199',0,108000,38,6,'0000002993',4000,320,20,1,1,0),(100,83,'2020-01-15 09:34:52','201',0,16.1865,83,6,'0000023030',1284,530,80,41,0,0),(101,83,'2020-01-15 13:16:43','204',0,30.2552,62,6,'0000023033',2400,530,89,41,3,0),(103,4,'2020-01-23 13:15:11','205',0,0,55,6,'0000003075',2920,320,27.3,1,2,0),(104,4,'2020-01-23 13:16:16','204',0,0,55,6,'0000003074',3532,320,32.58,1,2,0),(105,4,'2020-01-23 13:17:23','207',0,0,55,6,'0000003073',3888,320,35.26,1,2,0),(108,82,'2020-01-24 17:03:36','207',0,22.6914,38,6,'0000023077',1800,530,120,41,0,0),(109,83,'2020-01-27 15:22:29','202',0,27.7339,87,6,'0000023031',2200,530,120,41,2,0),(110,82,'2020-01-31 08:45:29','208',0,27.1036,87,6,'0000023078',2150,530,80,41,0,0),(111,83,'2020-02-10 17:37:54','205',0,18.9095,87,6,'0000023034',1500,530,120,41,0,0),(112,91,'2020-02-13 16:51:43','205',1,540.377,29,6,'0000012613',1790,167,28,43,0,0),(115,69,'2020-02-17 09:13:35','204',1,201.493,38,6,'0000013174',405,160,11,43,1,0),(116,81,'2020-02-18 12:55:15','2011',0,16.5623,83,6,'0000023169',790,600,45,41,3,0),(117,83,'2020-02-20 12:21:28','2012',0,30.2552,83,6,'0000023166',2400,530,33,41,1,0),(118,83,'2020-02-21 13:05:03','2017',0,31.5158,87,6,'0000023185',2500,530,180,41,1,0),(119,83,'2020-02-21 16:48:55','2024',0,30.2552,87,6,'0000023182',2400,530,160,41,3,0),(120,83,'2020-02-24 11:44:45','2018',0,32.7764,87,6,'0000023186',2600,530,180,41,2,0),(121,83,'2020-02-24 14:52:13','2016',0,34.0371,87,6,'0000023184',2700,530,180,41,2,0),(122,83,'2020-02-24 17:25:01','2025',0,30.2552,87,6,'0000023194',2400,530,170,41,2,0),(123,81,'2020-02-25 11:31:14','2010',0,45.7035,38,6,'0000023168',2180,530,110,41,0,0),(124,83,'2020-02-25 13:52:44','2019',0,33.0286,83,6,'0000023193',2620,530,120,41,2,0),(125,82,'2020-02-25 16:08:47','2023',0,34.4153,62,6,'0000023192',2730,530,120,41,2,0),(126,82,'2020-02-26 09:54:24','2021',0,30.2678,83,6,'0000023189',2401,530,125,41,0,0),(127,83,'2020-02-26 12:28:04','2014',0,32.9403,62,6,'0000023187',2613,530,125,41,1,0),(128,83,'2020-03-09 10:10:07','2028',0,30.2552,87,6,'0000023198',2400,530,180,41,0,0),(129,83,'2020-03-09 12:35:45','2015',0,31.5158,87,6,'0000023183',2500,530,180,41,0,0),(130,83,'2020-03-09 15:04:36','2026',0,31.5158,87,6,'0000023195',2500,530,185,41,0,0),(131,83,'2020-03-09 17:07:12','2029',0,32.1461,87,6,'0000023199',2550,530,185,41,0,0),(132,82,'2020-03-11 16:07:38','2020',0,31.5158,87,6,'0000023188',2500,530,170,41,0,0),(133,81,'2020-03-25 08:03:45','2032',0,83.8596,87,6,'0000023228',4000,600,270,41,4,0),(134,83,'2020-03-26 09:07:50','209',0,25.9564,83,6,'0000023196',2059,530,88,41,0,0),(135,4,'2020-05-13 15:43:21','2012',0,0,55,6,'0000003329',3890,320,35.7,1,2,0),(136,4,'2020-05-13 15:44:33','2013',0,0,55,6,'0000003330',3930,320,35.37,1,2,0),(137,4,'2020-05-13 15:46:08','2014',0,0,55,6,'0000003331',3940,320,36.67,1,2,0),(138,4,'2020-05-13 15:46:59','209',0,0,55,6,'0000003332',3890,320,35.29,1,2,0);
/*!40000 ALTER TABLE `tbprolaminado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprolaminado 2`
--

DROP TABLE IF EXISTS `tbprolaminado 2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprolaminado 2` (
  `id` int(11) NOT NULL,
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL DEFAULT '1',
  `unidades` float NOT NULL,
  `operador` int(11) DEFAULT NULL,
  `maquina` int(11) DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `bandera` int(11) DEFAULT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `noop` (`noop`),
  KEY `operador` (`operador`),
  KEY `lote` (`lote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprolaminado 2`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprolaminado 2` WRITE;
/*!40000 ALTER TABLE `tbprolaminado 2` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbprolaminado 2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprorefilado`
--

DROP TABLE IF EXISTS `tbprorefilado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprorefilado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` int(5) DEFAULT NULL,
  `maquina` int(5) DEFAULT NULL,
  `lote` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int(5) NOT NULL,
  `unidades` float NOT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `operador` (`operador`),
  KEY `lote` (`lote`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=26195 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprorefilado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprorefilado` WRITE;
/*!40000 ALTER TABLE `tbprorefilado` DISABLE KEYS */;
INSERT INTO `tbprorefilado` VALUES (1,1,14,'2019-05-09 13:37:34',63,18,'0000013',NULL,NULL,NULL,'192',30,0,1),(2,0,14,'2019-05-09 13:37:34',63,18,'0000013',2735,200,28,'192-1',30,36.9595,0),(3,0,14,'2019-05-09 13:37:34',63,18,'0000013',2735,200,28,'192-2',30,36.9595,0),(4,0,14,'2019-05-09 13:37:34',63,18,'0000013',2735,200,28,'192-3',30,36.9595,0),(5,1,14,'2019-05-09 16:41:59',57,18,'0000014',NULL,NULL,NULL,'193',30,0,1),(6,0,14,'2019-05-09 16:41:59',57,18,'0000014',2900,200,28,'193-1',30,39.1892,0),(7,0,14,'2019-05-09 16:41:59',57,18,'0000014',2900,202,28,'193-2',30,39.1892,0),(8,0,14,'2019-05-09 16:41:59',57,18,'0000014',2900,202,28,'193-3',30,39.1892,0),(9,1,14,'2019-05-09 16:45:52',57,18,'0000016',NULL,NULL,NULL,'195',30,0,1),(10,0,14,'2019-05-09 16:45:52',57,18,'0000016',1820,200,23,'195-1',30,39.1892,0),(11,0,14,'2019-05-09 16:45:52',57,18,'0000016',1800,200,12.5,'195-2',30,39.1892,0),(12,0,14,'2019-05-09 16:45:52',57,18,'0000016',2900,200,28,'195-3',30,39.1892,0),(13,1,14,'2019-05-10 08:56:50',57,18,'0000015',NULL,NULL,NULL,'194',30,0,1),(14,0,14,'2019-05-10 08:56:50',57,18,'0000015',2900,200,28,'194-1',30,39.1892,0),(15,0,14,'2019-05-10 08:56:50',57,18,'0000015',2900,202,28,'194-2',30,39.1892,0),(16,0,14,'2019-05-10 08:56:50',57,18,'0000015',2900,200,28,'194-3',30,39.1892,0),(17,1,14,'2019-05-10 08:58:01',57,18,'0000017',NULL,NULL,NULL,'196',30,0,1),(18,0,14,'2019-05-10 08:58:01',57,18,'0000017',2830,200,27.5,'196-1',30,38.2432,0),(19,0,14,'2019-05-10 08:58:01',57,18,'0000017',2830,200,27.5,'196-2',30,38.2432,0),(20,0,14,'2019-05-10 08:58:01',57,18,'0000017',2830,200,27.5,'196-3',30,38.2432,0),(21,1,14,'2019-05-10 10:16:37',57,18,'0000018',NULL,NULL,NULL,'197',30,0,1),(22,0,14,'2019-05-10 10:16:37',57,18,'0000018',2820,200,27,'197-1',30,38.1081,0),(23,0,14,'2019-05-10 10:16:37',57,18,'0000018',2820,200,27,'197-2',30,38.1081,0),(24,0,14,'2019-05-10 10:16:37',57,18,'0000018',2820,2000,27,'197-3',30,38.1081,0),(25,1,14,'2019-05-13 07:55:44',57,18,'00000121',NULL,NULL,NULL,'1920',30,0,1),(26,0,14,'2019-05-13 07:55:44',57,18,'00000121',2645,202,25.5,'1920-1',30,35.7432,0),(27,0,14,'2019-05-13 07:55:44',57,18,'00000121',2645,202,25.5,'1920-2',30,35.7432,0),(28,0,14,'2019-05-13 07:55:44',57,18,'00000121',2645,202,25.5,'1920-3',30,35.7432,0),(29,1,30,'2019-05-13 07:57:57',57,18,'00000111',NULL,NULL,NULL,'1910',30,0,1),(30,0,30,'2019-05-13 07:57:57',57,18,'00000111',2630,202,26,'1910-1',30,39.5097,0),(31,0,30,'2019-05-13 07:57:57',57,18,'00000111',2630,202,26,'1910-2',30,39.5097,0),(32,0,30,'2019-05-13 07:57:57',57,18,'00000111',2630,202,26,'1910-3',30,39.5097,0),(33,1,30,'2019-05-13 09:21:26',63,23,'00000112',NULL,NULL,NULL,'1911',30,0,1),(34,0,30,'2019-05-13 09:21:26',63,23,'00000112',2640,202,27,'1911-1',30,38.8235,0),(35,0,30,'2019-05-13 09:21:26',63,23,'00000112',2640,202,27,'1911-2',30,38.8235,0),(36,0,30,'2019-05-13 09:21:26',63,23,'00000112',2640,200,27,'1911-3',30,38.8235,0),(37,1,30,'2019-05-13 09:25:20',53,22,'00000115',NULL,NULL,NULL,'1914',30,0,1),(38,0,30,'2019-05-13 09:25:20',53,22,'00000115',1380,260,22,'1914-1',30,39,0),(39,0,30,'2019-05-13 09:25:20',53,22,'00000115',1381,260,15,'1914-2',30,39,0),(40,0,30,'2019-05-13 09:25:20',53,22,'00000115',1400,132.5,14,'1914-3',30,39,0),(41,1,30,'2019-05-13 10:36:07',53,22,'00000113',NULL,NULL,NULL,'1912',30,0,1),(42,0,30,'2019-05-13 10:36:07',53,22,'00000113',2706,200,28,'1912-1',30,39.7941,0),(43,0,30,'2019-05-13 10:36:07',53,22,'00000113',2706,202,28,'1912-2',30,39.7941,0),(44,0,30,'2019-05-13 10:36:07',53,22,'00000113',2706,201,28,'1912-3',30,39.7941,0),(45,1,30,'2019-05-13 10:54:24',63,22,'00000114',NULL,NULL,NULL,'1913',30,0,1),(46,0,30,'2019-05-13 10:54:24',63,22,'00000114',1500,240,65,'1913-1',30,35.8088,0),(47,0,30,'2019-05-13 10:54:24',63,22,'00000114',1500,240,65,'1913-2',30,35.8088,0),(48,0,30,'2019-05-13 10:54:24',63,22,'00000114',0,0,0,'1913-3',30,35.8088,0),(49,1,30,'2019-05-13 11:47:54',53,22,'00000116',NULL,NULL,NULL,'1915',30,0,1),(50,0,30,'2019-05-13 11:47:54',53,22,'00000116',2722,200,28.2,'1915-1',30,40.0294,0);
/*!40000 ALTER TABLE `tbprorefilado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprorevision`
--

DROP TABLE IF EXISTS `tbprorevision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprorevision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operador` int(5) NOT NULL,
  `maquina` int(5) NOT NULL,
  `rollo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(1) NOT NULL DEFAULT '1',
  `producto` int(5) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `noop` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(2) DEFAULT NULL,
  `tipo` int(5) NOT NULL,
  `rollo_padre` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `producto` (`producto`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=88940 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprorevision`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprorevision` WRITE;
/*!40000 ALTER TABLE `tbprorevision` DISABLE KEYS */;
INSERT INTO `tbprorevision` VALUES (1,95,27,'0003533',0,14,'2019-05-10 12:52:50',516,'192-3-5',7.10517,6.09,1,30,0),(2,95,27,'0002433',0,14,'2019-05-10 11:20:04',543,'192-2-4',7.47695,6.42,0,30,0),(3,95,27,'0002333',0,14,'2019-05-10 11:21:07',537,'192-2-3',7.39433,6.34,1,30,0),(4,95,27,'0002533',0,14,'2019-05-10 11:18:45',539,'192-2-5',7.42187,6.37,2,30,0),(5,95,26,'0002133',0,14,'2019-05-10 11:42:36',537,'192-2-1',7.39433,6.28,0,30,0),(6,95,27,'0002233',0,14,'2019-05-10 11:43:16',542,'192-2-2',7.46318,6.32,1,30,0),(7,75,40,'0001234',0,14,'2019-05-10 12:38:33',590,'193-1-2',8.12413,6.91,0,30,0),(8,75,40,'0001434',0,14,'2019-05-10 12:39:04',587,'193-1-4',8.08282,6.97,0,30,0),(9,75,40,'0001534',0,14,'2019-05-10 12:39:33',530,'193-1-5',7.29794,6.36,4,30,0),(10,75,40,'0001334',0,14,'2019-05-10 12:39:58',579,'193-1-3',7.97266,6.81,2,30,0),(11,95,27,'0001433',0,14,'2019-05-10 12:51:38',542,'192-1-4',7.46318,6.36,0,30,0),(12,95,27,'0001533',0,14,'2019-05-10 12:52:15',571,'192-1-5',7.8625,6.73,2,30,0),(13,95,27,'0003233',0,14,'2019-05-10 12:53:35',575,'192-3-2',7.91758,6.52,2,30,0),(14,75,40,'0001134',0,14,'2019-05-10 13:06:25',589,'193-1-1',8.11036,6.95,0,30,0),(15,75,40,'0003134',0,14,'2019-05-10 13:06:58',574,'193-3-1',7.90381,6.74,0,30,0),(16,95,27,'0001233',0,14,'2019-05-10 13:55:39',539,'192-1-2',7.42187,6.39,1,30,0),(17,95,4,'0001333',0,14,'2019-05-10 14:44:58',547,'192-1-3',7.53203,6.47,0,30,0),(18,95,4,'0003133',0,14,'2019-05-10 14:45:44',540,'192-3-1',7.43564,6.34,0,30,0),(19,75,40,'0002134',0,14,'2019-05-10 14:46:20',579,'193-2-1',7.97266,6.77,0,30,0),(20,75,40,'0003434',0,14,'2019-05-10 14:46:43',600,'193-3-4',8.26182,7.03,0,30,0),(21,75,40,'0003334',0,14,'2019-05-10 14:47:08',597,'193-3-3',8.22051,6.94,1,30,0),(22,75,40,'0003234',0,14,'2019-05-10 14:47:35',587,'193-3-2',8.08282,6.91,0,30,0),(23,75,40,'0003534',0,14,'2019-05-10 14:47:57',485,'193-3-5',6.67831,5.82,4,30,0),(24,75,40,'0002534',0,14,'2019-05-13 08:34:22',538,'193-2-5',7.4081,6.3,3,30,0),(25,75,40,'0002334',0,14,'2019-05-13 08:34:52',572,'193-2-3',7.87627,6.55,1,30,0),(26,75,40,'0002434',0,14,'2019-05-13 08:35:14',573,'193-2-4',7.89004,6.71,1,30,0),(27,75,40,'0002234',0,14,'2019-05-13 08:35:41',584,'193-2-2',8.04151,6.83,0,30,0),(28,95,4,'0003333',0,14,'2019-05-13 08:53:17',548,'192-3-3',7.5458,6.51,0,30,0),(29,95,4,'0001133',0,14,'2019-05-13 08:53:55',539,'192-1-1',7.42187,6.4,0,30,0),(30,95,4,'0003433',0,14,'2019-05-13 08:54:38',540,'192-3-4',7.43564,6.37,0,30,0),(31,75,40,'0002536',0,14,'2019-05-13 09:47:10',507,'195-2-5',6.98124,6.01,0,30,0),(32,75,40,'0002136',0,14,'2019-05-13 09:47:39',538,'195-2-1',7.4081,6.44,15,30,0),(33,75,40,'0002436',0,14,'2019-05-13 09:48:13',600,'195-2-4',8.26182,6.99,0,30,0),(34,77,4,'0001136',0,14,'2019-05-13 09:57:17',595,'195-1-1',8.19297,7.11,2,30,0),(35,95,4,'0001536',0,14,'2019-05-13 09:57:51',570,'195-1-5',7.84873,7.52,1,30,0),(36,95,4,'0001336',0,14,'2019-05-13 09:58:28',562,'195-1-3',7.73857,6.65,1,30,0),(37,95,4,'0001436',0,14,'2019-05-13 09:59:06',492,'195-1-4',6.77469,5.85,0,30,0),(38,75,40,'0002336',0,14,'2019-05-13 10:51:01',596,'195-2-3',8.20674,7,3,30,0),(39,75,40,'0003538',0,14,'2019-05-13 10:51:27',535,'197-3-5',7.36679,6.26,0,30,0),(40,75,40,'0002236',0,14,'2019-05-13 10:51:56',583,'195-2-2',8.02774,6.89,0,30,0),(41,75,40,'0003438',0,14,'2019-05-13 10:52:26',560,'197-3-4',7.71103,6.54,0,30,0),(42,95,4,'0003436',0,14,'2019-05-13 10:56:45',586,'195-3-4',8.06905,6.91,1,30,0),(43,95,4,'0001236',0,14,'2019-05-13 10:57:20',579,'195-1-2',7.97266,6.74,1,30,0),(44,95,4,'0003536',0,14,'2019-05-13 10:58:18',512,'195-3-5',7.05009,6.19,0,30,0),(45,77,41,'0001538',0,14,'2019-05-13 11:44:07',544,'197-1-5',7.49072,6.39,2,30,0),(46,75,40,'0003338',0,14,'2019-05-13 11:45:41',556,'197-3-3',7.65596,6.56,0,30,0),(47,75,40,'0002538',0,14,'2019-05-13 11:46:07',542,'197-2-5',7.46318,6.32,0,30,0),(48,75,40,'0002438',0,14,'2019-05-13 11:46:29',561,'197-2-4',7.7248,6.43,0,30,0),(49,75,40,'0002338',0,14,'2019-05-13 11:46:51',563,'197-2-3',7.75234,6.51,0,30,0),(50,77,27,'0003336',0,14,'2019-05-13 11:58:47',609,'195-3-3',8.38575,7.13,1,30,0);
/*!40000 ALTER TABLE `tbprorevision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprorevision 2`
--

DROP TABLE IF EXISTS `tbprorevision 2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprorevision 2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operador` int(11) NOT NULL,
  `maquina` int(11) NOT NULL,
  `rollo` varchar(70) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `bandera` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprorevision 2`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprorevision 2` WRITE;
/*!40000 ALTER TABLE `tbprorevision 2` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbprorevision 2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprosliteo`
--

DROP TABLE IF EXISTS `tbprosliteo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprosliteo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` float NOT NULL DEFAULT '1',
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noop` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unidades` float NOT NULL,
  `operador` int(11) DEFAULT NULL,
  `maquina` int(11) DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `amplitud` float DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `bandera` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `noop` (`noop`),
  KEY `operador` (`operador`),
  KEY `lote` (`lote`)
) ENGINE=InnoDB AUTO_INCREMENT=18692 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprosliteo`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprosliteo` WRITE;
/*!40000 ALTER TABLE `tbprosliteo` DISABLE KEYS */;
INSERT INTO `tbprosliteo` VALUES (1261,0,69,'2019-09-18 08:52:56','1939-11',0,38,7,'0000042489',4250,12,1.56,2,43,0),(1262,0,69,'2019-09-18 08:52:56','1939-12',0,38,7,'0000042489',4250,12,1.56,2,43,0),(1439,0,74,'2019-10-15 08:33:11','1944',0,99,7,'0000042724',NULL,NULL,NULL,NULL,43,1),(1440,0,74,'2019-10-15 08:33:11','1944-1',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1441,0,74,'2019-10-15 08:33:11','1944-2',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1442,0,74,'2019-10-15 08:33:11','1944-3',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1443,0,74,'2019-10-15 08:33:11','1944-4',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1444,0,74,'2019-10-15 08:33:11','1944-5',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1445,0,74,'2019-10-15 08:33:11','1944-6',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1446,0,74,'2019-10-15 08:33:11','1944-7',0,99,7,'0000042724',3700,12,1.28,2,43,0),(1450,0,72,'2019-10-15 11:34:54','1939-2-3',0,38,7,'0000252506',0,0,0,0,41,0),(1591,0,20,'2019-10-28 14:21:27','191',0,55,7,'0000012923',NULL,NULL,NULL,NULL,1,1),(1592,0,20,'2019-10-28 14:21:27','191-1',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1593,0,20,'2019-10-28 14:21:27','191-2',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1594,0,20,'2019-10-28 14:21:27','191-3',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1595,0,20,'2019-10-28 14:21:27','191-4',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1596,0,20,'2019-10-28 14:21:27','191-5',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1597,0,20,'2019-10-28 14:21:27','191-6',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1598,0,20,'2019-10-28 14:21:27','191-7',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1599,0,20,'2019-10-28 14:21:27','191-8',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1600,0,20,'2019-10-28 14:21:27','191-9',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1601,0,20,'2019-10-28 14:21:27','191-10',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1602,0,20,'2019-10-28 14:21:27','191-11',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1603,0,20,'2019-10-28 14:21:27','191-12',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1604,0,20,'2019-10-28 14:21:27','191-13',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1605,0,20,'2019-10-28 14:21:27','191-14',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1606,0,20,'2019-10-28 14:21:27','191-15',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1607,0,20,'2019-10-28 14:21:27','191-16',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1608,0,20,'2019-10-28 14:21:27','191-17',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1609,0,20,'2019-10-28 14:21:27','191-18',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1610,0,20,'2019-10-28 14:21:27','191-19',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1611,0,20,'2019-10-28 14:21:27','191-20',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1612,0,20,'2019-10-28 14:21:27','191-21',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1613,0,20,'2019-10-28 14:21:27','191-22',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1614,0,20,'2019-10-28 14:21:27','191-23',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1615,0,20,'2019-10-28 14:21:27','191-24',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1616,0,20,'2019-10-28 14:21:27','191-25',3688.3,55,7,'0000012923',3850,12,1.34,2,1,0),(1617,0,20,'2019-10-28 14:21:27','191-26',0,55,7,'0000012923',0,0,0,0,1,0),(1618,0,20,'2019-10-28 14:21:27','191-27',0,55,7,'0000012923',0,0,0,0,1,0),(1619,0,20,'2019-10-30 12:37:36','192',0,55,7,'0000012924',NULL,NULL,NULL,NULL,1,1),(1620,1,20,'2019-10-30 12:37:36','192-1',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1621,1,20,'2019-10-30 12:37:36','192-2',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1622,0,20,'2019-10-30 12:37:36','192-3',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1623,0,20,'2019-10-30 12:37:36','192-4',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1624,1,20,'2019-10-30 12:37:36','192-5',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1625,1,20,'2019-10-30 12:37:36','192-6',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1626,1,20,'2019-10-30 12:37:36','192-7',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1627,1,20,'2019-10-30 12:37:36','192-8',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1628,1,20,'2019-10-30 12:37:36','192-9',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0),(1629,1,20,'2019-10-30 12:37:36','192-10',3640.4,55,7,'0000012924',3800,12,1.31,0,1,0);
/*!40000 ALTER TABLE `tbprosliteo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprosuajado`
--

DROP TABLE IF EXISTS `tbprosuajado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprosuajado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) DEFAULT '1',
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` int(11) NOT NULL,
  `maquina` int(11) NOT NULL,
  `rollo` varchar(50) NOT NULL,
  `suaje` varchar(40) NOT NULL,
  `longitud` float NOT NULL,
  `peso` float NOT NULL,
  `unidades` float NOT NULL,
  `tipo` int(11) NOT NULL,
  `noop` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=756 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprosuajado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprosuajado` WRITE;
/*!40000 ALTER TABLE `tbprosuajado` DISABLE KEYS */;
INSERT INTO `tbprosuajado` VALUES (9,0,83,'2020-01-15 11:00:05',29,12,'0000143030','suaje cristal gota gde autoad',1200,35,7.5638,41,'201-1',0),(10,0,83,'2020-01-15 12:04:02',29,12,'0000243030','suaje cristal gota gde autoad',1280,35,8.06805,41,'201-2',0),(11,0,83,'2020-01-15 13:19:24',29,12,'0000143033','suaje cristal gota gde autoad',2300,40,14.4973,41,'204-1',0),(14,0,83,'2020-01-16 12:38:27',29,12,'0000243033','suaje cristal gota gde autoad',2000,38,12.6063,41,'204-2',0),(15,0,82,'2020-01-27 11:30:46',29,12,'0000143077','suaje estrella ancho',1710,22,10.7784,41,'207-1',0),(16,0,82,'2020-01-27 11:35:17',29,12,'0000243077','suaje estrella ancho',1790,22,11.2827,41,'207-2',0),(22,0,83,'2020-01-29 16:36:10',87,12,'0000143031','suaje cristal gota gde autoad',2200,28,13.867,41,'202-1',0),(23,0,82,'2020-01-31 08:47:27',29,12,'0000143078','suaje estrella ancho',2100,35,13.2366,41,'208-1',0),(24,0,82,'2020-02-04 11:49:57',29,12,'0000243078','suaje estrella ancho',2000,35,12.6063,41,'208-2',0),(25,0,83,'2020-02-12 13:11:32',89,12,'0000243034','suaje cristal gota gde autoad',1500,50,9.45475,41,'205-2',0),(27,0,83,'2020-02-12 13:26:46',89,12,'0000143034','suaje cristal gota gde autoad',1520,50,9.58081,41,'205-1',0),(28,0,81,'2020-02-18 12:58:38',89,12,'0000143169','suaje liston',730,20,3.82609,41,'2011-1',0),(29,0,81,'2020-02-18 13:00:04',89,12,'0000243169','suaje liston',730,20,3.82609,41,'2011-2',0),(30,0,83,'2020-02-20 12:23:44',89,12,'0000143166','suaje cristal gota gde autoad',2400,33,7.5638,41,'2012-1',0),(31,0,83,'2020-02-20 12:26:14',89,12,'0000243166','suaje cristal gota gde autoad',2400,33,7.5638,41,'2012-2',0),(34,0,83,'2020-02-25 11:24:45',89,12,'0000143185','suaje cristal gota gde autoad',2380,27,15.0015,41,'2017-1',0),(35,0,81,'2020-02-25 11:32:37',38,12,'0000243168','suaje liston',2180,55,22.8517,41,'2010-2',0),(36,0,81,'2020-02-25 11:33:06',38,12,'0000143168','suaje liston',2180,55,22.8517,41,'2010-1',0),(37,1,83,'2020-02-25 15:35:43',89,12,'0000243185','suaje cristal gota gde autoad',2470,28.5,15.5688,41,'2017-2',0),(38,0,83,'2020-02-26 08:03:37',87,12,'0000143182','suaje cristal gota gde autoad',2250,27,14.1821,41,'2024-1',0),(39,0,83,'2020-02-26 10:51:22',89,12,'0000143186','suaje cristal gota gde autoad',2530,120,15.947,41,'2018-1',0),(40,0,83,'2020-02-26 12:12:53',87,12,'0000243182','suaje cristal gota gde autoad',2200,27,13.867,41,'2024-2',0),(41,0,83,'2020-02-26 15:32:32',89,12,'0000243186','suaje cristal gota gde autoad',2520,110,15.884,41,'2018-2',0),(42,0,82,'2020-02-26 17:29:22',87,12,'0000243192','suaje estrella ancho',2500,98,15.7579,41,'2023-2',0),(43,1,83,'2020-02-27 11:05:20',89,12,'0000143193','suaje cristal gota gde autoad',2570,95,16.1991,41,'2019-1',0),(44,0,82,'2020-02-27 14:57:20',87,12,'0000143192','suaje estrella ancho',2500,98,15.7579,41,'2023-1',0),(45,0,83,'2020-02-27 17:29:34',89,12,'0000243193','suaje cristal gota gde autoad',2560,95,16.1361,41,'2019-2',0),(46,0,82,'2020-02-28 13:13:20',87,12,'0000143189','suaje estrella ancho',2300,95,14.4973,41,'2021-1',0),(47,1,83,'2020-02-28 14:38:07',89,12,'0000143187','suaje cristal gota gde autoad',2500,95,15.7579,41,'2014-1',0),(48,0,82,'2020-02-28 17:02:40',87,12,'0000243189','suaje estrella ancho',2300,95,14.4973,41,'2021-2',0),(49,0,83,'2020-02-29 09:23:55',89,12,'0000243187','suaje cristal gota gde autoad',2500,95,15.7579,41,'2014-2',0),(50,0,83,'2020-03-10 14:38:27',89,12,'0000143198','suaje cristal gota gde autoad',2280,95,14.3712,41,'2028-1',0),(51,0,83,'2020-03-11 08:28:35',89,12,'0000243198','suaje cristal gota gde autoad',2300,95,14.4973,41,'2028-2',0),(52,0,83,'2020-03-11 12:16:25',89,12,'0000143195','suaje cristal gota gde autoad',2470,96,15.5688,41,'2026-1',0),(53,0,83,'2020-03-11 16:45:35',89,12,'0000243195','suaje cristal gota gde autoad',2480,95,15.6318,41,'2026-2',0),(54,0,83,'2020-03-12 12:17:09',89,12,'0000143199','suaje cristal gota gde autoad',2480,95,15.6318,41,'2029-1',0),(55,0,83,'2020-03-12 14:54:38',87,12,'0000143183','suaje cristal gota gde autoad',2300,95,14.4973,41,'2015-1',0),(56,0,83,'2020-03-12 17:10:07',89,12,'0000243199','suaje cristal gota gde autoad',2480,95,15.6318,41,'2029-2',0),(57,0,83,'2020-03-13 11:45:47',87,12,'0000243183','suaje cristal gota gde autoad',2350,95,14.8124,41,'2015-2',0),(58,0,83,'2020-03-13 14:52:38',87,12,'0000243184','suaje cristal gota gde autoad',2500,95,15.7579,41,'2016-2',0),(59,0,82,'2020-03-13 15:40:28',89,12,'0000243188','suaje estrella ancho',2450,95,15.4427,41,'2020-2',0),(60,0,82,'2020-03-17 11:17:24',87,12,'0000143188','suaje estrella ancho',2350,95,14.8124,41,'2020-1',0),(61,0,81,'2020-03-26 12:06:04',87,12,'0000143228','suaje liston',3750,120,39.3092,41,'2032-1',0),(62,0,83,'2020-03-30 13:05:07',87,12,'0000143196','suaje cristal gota gde autoad',1900,52,11.976,41,'209-1',0),(63,0,83,'2020-03-30 15:46:52',89,12,'0000243196','suaje cristal gota gde autoad',1850,50,11.6609,41,'209-2',0),(64,0,81,'2020-03-31 09:06:27',87,12,'0000243228','suaje liston',3600,110,37.7368,41,'2032-2',0),(67,0,76,'2020-06-11 15:52:28',89,12,'0000143407','GOTAAUTBCOGDE',1360,95,8.5723,41,'2038-1',0),(68,0,76,'2020-06-11 16:18:55',89,12,'0000243407','GOTAAUTBCOGDE',1340,95,8.44624,41,'2038-2',0),(69,0,76,'2020-06-11 16:36:37',89,12,'0000243394','GOTAAUTBCOGDE',1150,90,7.24864,41,'2033-2',0),(70,0,76,'2020-06-11 17:03:16',89,12,'0000143394','GOTAAUTBCOGDE',1150,90,7.24864,41,'2033-1',0);
/*!40000 ALTER TABLE `tbprosuajado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprotroquelado`
--

DROP TABLE IF EXISTS `tbprotroquelado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbprotroquelado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) NOT NULL DEFAULT '1',
  `producto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `operador` int(11) NOT NULL,
  `maquina` int(11) NOT NULL,
  `rollo` varchar(50) NOT NULL,
  `unidades` float NOT NULL,
  `peso` float NOT NULL,
  `longitud` float NOT NULL,
  `noop` varchar(30) NOT NULL,
  `tipo` int(11) NOT NULL,
  `suaje` varchar(30) NOT NULL,
  `rollo_padre` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `producto` (`producto`),
  KEY `fecha` (`fecha`),
  KEY `operador` (`operador`),
  KEY `rollo` (`rollo`),
  KEY `noop` (`noop`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprotroquelado`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tbprotroquelado` WRITE;
/*!40000 ALTER TABLE `tbprotroquelado` DISABLE KEYS */;
INSERT INTO `tbprotroquelado` VALUES (1,0,69,'2020-09-08 08:33:32',29,11,'0000023694',527,9.5,494.062,'2026',43,'INFASASUAJE1109',0),(2,0,69,'2020-09-08 08:35:47',29,11,'0000023695',527,9.5,494.062,'2027',43,'INFASASUAJE1109',0),(3,0,69,'2020-09-08 08:36:38',29,11,'0000023696',527,9.5,494.062,'2028',43,'INFASASUAJE1109',0),(4,0,69,'2020-09-08 08:37:08',29,11,'0000023697',527,9.5,494.062,'2029',43,'INFASASUAJE1109',0);
/*!40000 ALTER TABLE `tbprotroquelado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoproducto`
--

DROP TABLE IF EXISTS `tipoproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipoproducto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `juegoParametros` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `juegoProcesos` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `presentacion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo` (`tipo`),
  KEY `id` (`id`),
  KEY `tipo_2` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoproducto`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `tipoproducto` WRITE;
/*!40000 ALTER TABLE `tipoproducto` DISABLE KEYS */;
INSERT INTO `tipoproducto` VALUES (1,'BS','BS','JPSBS08','JPBS08','Predeterminados',1),(2,'Termoencogible','Termoencogible','JPMTERMOENCOGIBLE','JPPManga01','Predeterminados',0),(26,'hologrami','hologrami','JPShologrami09','JPhologrami09','holograma',0),(27,'Quindio 2018','Quindio 2018','JPSQuindio 201810','JPQuindio 201810','EtiqAbierta',0),(28,'Chiva cola','Chiva cola','JPSChiva cola08','JPChiva cola08','EtiqAbierta',0),(29,'Chiva Cola Cola','Chiva Cola Cola','JPSChiva Cola Cola09','JPChiva Cola Cola09','EtiqAbierta',0),(30,'Manga Termoencogible','Manga Termoencogible','JPSManga Termoencogible09','JPManga Termoencogible09','Predeterminados',1),(31,'Etiqueta abierta','Etiqueta abierta','JPSEtiqueta abierta12','JPEtiqueta abierta12','EtiqAbierta',1),(32,'Fail','Fail','JPSFail12','JPFail12','EtiqAbierta',0),(35,'Etiqueta Autoadherible','Etiqueta Autoadherible','JPSEtiqueta Autoadherible10','JPEtiqueta Autoadherible10','EtiqAbierta',0),(40,'Manga Termoencogible Flexo','Manga Termoencogible Flexo','JPSManga Termoencogible Flexo09','JPManga Termoencogible Flexo09','Predeterminados',1),(41,'Etiqueta Autoadherible Roto','Etiqueta Autoadherible Roto','JPSEtiqueta Autoadherible Roto03','JPEtiqueta Autoadherible Roto03','EtiqAbierta',1),(43,'Holograma Personalizado','Holograma Personalizado','JPSHolograma Personalizado04','JPHolograma Personalizado04','holograma',1),(44,'Manga Termoencogible Roto','Manga Termoencogible Roto','JPSManga Termoencogible Roto10','JPManga Termoencogible Roto10','Predeterminados',1),(46,'Etiqueta autoadherible interna','Etiqueta autoadherible interna','JPSEtiqueta autoadherible interna05','JPEtiqueta autoadherible interna05','EtiqAbierta',0),(48,'Etiqueta Autoadherible Flexo-Lam','Etiqueta Autoadherible Flexo-Lam','JPSEtiqueta Autoadherible Flexo-Lam12','JPEtiqueta Autoadherible Flexo-Lam12','EtiqAbierta',0),(53,'Etiqueta autoadherible sin impres.flexo','Etiqueta autoadherible sin impres.flexo','JPSEtiqueta autoadherible sin impres.flexo05','JPEtiqueta autoadherible sin impres.flexo05','EtiqAbierta',0),(54,'Etiqueta Autoadherible Flexo octagonos','Etiqueta Autoadherible Flexo octagonos','JPSEtiqueta Autoadherible Flexo octagonos05','JPEtiqueta Autoadherible Flexo octagonos05','EtiqAbierta',0),(56,'Etiqueta Autoadherible Flexo circulos co','Etiqueta Autoadherible Flexo circulos color','JPSEtiqueta Autoadherible Flexo circulos color10','JPEtiqueta Autoadherible Flexo circulos color10','EtiqAbierta',0),(60,'Etiqueta autoad.S/Impresion flexo','Etiqueta autoad.S/Impresion flexo','JPSEtiqueta autoad.S/Impresion flexo03','JPEtiqueta autoad.S/Impresion flexo03','EtiqAbierta',0),(61,'Etiqueta Autoadherible Flexo-Barn','Etiqueta Autoadherible Flexo-Barn','JPSEtiqueta Autoadherible Flexo-Barn09','JPEtiqueta Autoadherible Flexo-Barn09','EtiqAbierta',0),(62,'Manga Termo-flexografia','Manga Termo-flexografia','JPSManga Termo-flexografia11','JPManga Termo-flexografia11','Predeterminados',1),(63,'Holograma Generico','Holograma Generico','JPSHolograma Generico10','JPHolograma Generico10','holograma',0),(68,'Etiqueta Autoadh.Flx / Frente-Vuelta','Etiqueta Autoadh.Flx / Frente-Vuelta','JPSEtiqueta Autoadh.Flx / Frente-Vuelta03','JPEtiqueta Autoadh.Flx / Frente-Vuelta03','EtiqAbierta',1),(69,'Etiqueta Autoadherible flexografia','Etiqueta Autoadherible flexografia','JPSEtiqueta Autoadherible flexografia10','JPEtiqueta Autoadherible flexografia10','EtiqAbierta',1),(72,'Etiqueta abierta Sobre Flx','Etiqueta abierta Sobre Flx','JPSEtiqueta abierta Sobre Flx12','JPEtiqueta abierta Sobre Flx12','EtiqAbierta',1),(73,'Etiqueta Abierta.','Etiqueta Abierta.','JPSEtiqueta Abierta.12','JPEtiqueta Abierta.12','EtiqAbierta',1),(74,'Caja','Caja','JPSCaja01','JPCaja01','EtiqAbierta',1),(75,'Etiqueta abierta Corte','Etiqueta abierta Corte','JPSEtiqueta abierta Corte03','JPEtiqueta abierta Corte03','EtiqAbierta',0);
/*!40000 ALTER TABLE `tipoproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidades` (
  `idUnidad` int(11) NOT NULL AUTO_INCREMENT,
  `identificadorUnidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombreUnidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `baja` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idUnidad`),
  KEY `identificadorUnidad` (`identificadorUnidad`),
  KEY `nombreUnidad` (`nombreUnidad`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES (1,'kg','Kilogramos',1),(3,'lt','Litros',1),(4,'pz','piezas',1),(6,'m2','Metro cuadrado',1),(7,'m3','Metro cÃºbico',1),(8,'Lb','Libra',1),(9,'gl','galon',1),(10,'cb','cubeta',1),(11,'pq','paquete',1),(12,'Mts','Metros',1);
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contrasenia` blob NOT NULL,
  `perfil` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `perfil` (`perfil`),
  KEY `usuario_2` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--
-- WHERE:  1 LIMIT 50

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Cristian Alberto','liraTI',_binary 'v�\�\�(\�i��y5���','PF1','Programador'),(2,'Erik Martin','castillo66',_binary 'r�\�}(�zY�n/jh�','PF2','Programador'),(4,'Felipe','Felipe',_binary 'W��0n�\'\�\n\�_\�z�','PF4','ProducciÃƒÂ³n'),(9,'Elviss','elvistek',_binary '\�\��^�(\�Ệ�\n�a','PF19','Cantante de Rock & Roll'),(10,'Ana Lilia','asaldaÃ±a',_binary 'W��0n�\'\�\n\�_\�z�','PF22','Jefe de Compras'),(11,'Araceli','alozano',_binary 'W��0n�\'\�\n\�_\�z�','PF23','Jefe de Logistica'),(13,'Arturo','fmedina',_binary 'W��0n�\'\�\n\�_\�z�','PF25','Supervisor de revisiÃƒÂ³n y corte'),(14,'JosÃ© Antonio','jhernandez',_binary 'W��0n�\'\�\n\�_\�z�','PF24','Gerente de Operaciones'),(15,'Christian Abraham','aramos',_binary 'W��0n�\'\�\n\�_\�z�','PF26','Coordinador de calidad'),(17,'Erik Castillo','core',_binary '��-{@\�\�s�Ϊr\�','PF0','Administrador'),(18,'Sandra','rdsandra',_binary 'W��0n�\'\�\n\�_\�z�','PF27','Jefe de RD'),(19,'Araceli','abelmonte',_binary 'W��0n�\'\�\n\�_\�z�','PF36','Jefe de LogÃ­stica y AtenciÃ³n a Clientes'),(22,'Christian','cmancilla',_binary '\rه�G�tm�\�\0��I\n','PF180','Jefe de calidad'),(23,'Erick de JesÃºs','equiroz',_binary '\�IONa��\�~�\Z]��c\�','PF78','Jefe de producciÃ³n'),(24,'Nestor Daniel ','dvillegas',_binary 'W��0n�\'\�\n\�_\�z�','PF91','Auxiliar de producciÃ³n'),(25,'Javier','julloa',_binary 'W��0n�\'\�\n\�_\�z�','PF59','Encargado de embarque'),(26,'Juan Carlos','cbelmonte',_binary 'W��0n�\'\�\n\�_\�z�','PF33','Embarque'),(27,'Angel Ricardo','adupont',_binary 'l$\�3�/�\�{�\\','PF34','Administrativa'),(28,'Miriam','mserrano',_binary 'W��0n�\'\�\n\�_\�z�','PF82','Coord. de logÃ­stica y embarque'),(29,'Alejandro','afuentes',_binary '���@\�f8/\�X�\�Nw�','PF50','Almacenista'),(32,'Carmen','crocha',_binary '{E�\�t\�C�\�@\�l�ZE�','PF31','Recepcionista'),(33,'FÃ¡tima Montserrat','fespinoza',_binary '[Ɠ�S�v��o����','PF47','Coordinadora de planta'),(35,'Fernando','fgomez',_binary 'ȓ je\�F�e�#\n���','PF116','Jefe de compras'),(38,'Fatima','magaÃ±a',_binary '��\�\�\�룥�\�t�','PF119','Supervisor calidad/producciÃ³n'),(39,'Victor Manuel','estradav',_binary '9\�\�K�\�y\�FUH','PF48','Almacenista'),(41,'Ana Rosa','argutierrez',_binary '�\�\�\�\�����r�\�D','PF56','Dir. Admon & Ventas'),(51,'Fatima','olivaf',_binary '�\0\�0�\�4�\��\\b��','PF123','Jefe de compras'),(52,'Juan Manuel','jmhernandez',_binary '~8E\�BkI\�:\�Mb��iw','PF124','Supervisor de planta'),(54,'Rosalba','RosalbaAguilar',_binary '�r:�\\$u�u!RZ�v�','PF120','Jefe de RH'),(55,'RamsÃ©s','rperez',_binary '9\�\�K�\�y\�FUH','PF131','Almacenista'),(56,'Alexa','ajaime',_binary '\�Z.g鑏�A5��ȦP','PF58','Sup. SS&GA'),(60,'Fatima','emercado',_binary '�B�G�\�=�j$NlTW�','PF139','Jefe de producciÃ³n'),(61,'Carlos Alberto','amartinez',_binary '\�W�6\�\��ܗ���\�\0','PF144','Almacenista'),(62,'Alma Monserrat','AlmapatiÃ±o',_binary '��.\�}\�?�	�s�','PF193','Calidad'),(63,'David','damador',_binary 'W��0n�\'\�\n\�_\�z�','PF200','Programador');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `codigosbarras`
--

/*!50001 DROP VIEW IF EXISTS `codigosbarras`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `codigosbarras` AS select `c`.`id` AS `id`,`c`.`codigo` AS `codigo`,if((`c`.`tipo` = 1),(select `bandaspp`.`nombreBSPP` from `bandaspp` where (`bandaspp`.`IdBSPP` = `c`.`producto`)),(select `impresiones`.`descripcionImpresion` from `impresiones` where (`impresiones`.`id` = `c`.`producto`))) AS `producto`,`p`.`descripcionProceso` AS `proceso`,`c`.`lote` AS `lote`,`c`.`noProceso` AS `noProceso`,`c`.`noop` AS `noop`,`t`.`tipo` AS `tipo`,`c`.`baja` AS `baja`,`c`.`divisiones` AS `divisiones` from ((`tbcodigosbarras` `c` join `procesos` `p` on((`c`.`proceso` = `p`.`id`))) join `tipoproducto` `t` on((`c`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lotes`
--

/*!50001 DROP VIEW IF EXISTS `lotes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lotes` AS select `l`.`idLote` AS `idLote`,`s`.`descripcionSustrato` AS `bloque`,`l`.`referenciaLote` AS `referenciaLote`,`l`.`fecha_alta` AS `fecha_alta`,`l`.`longitud` AS `longitud`,`l`.`peso` AS `peso`,`l`.`tarima` AS `tarima`,`l`.`estado` AS `estado`,`l`.`shower` AS `shower`,`l`.`noop` AS `noop`,`l`.`ancho` AS `ancho`,`l`.`espesor` AS `espesor`,`l`.`bloque` AS `idBloque`,`l`.`encogimiento` AS `encogimiento`,`l`.`numeroLote` AS `numeroLote`,`l`.`juegoLote` AS `juegoLote`,`l`.`noLote` AS `noLote`,`l`.`unidades` AS `unidades`,`l`.`anchuraBloque` AS `anchuraBloque`,`t`.`tipo` AS `tipo`,`l`.`baja` AS `baja` from ((`tblotes` `l` join `sustrato` `s` on((`s`.`idSustrato` = `l`.`bloque`))) left join `tipoproducto` `t` on((`t`.`id` = `l`.`tipo`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `merma`
--

/*!50001 DROP VIEW IF EXISTS `merma`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `merma` AS select `m`.`id` AS `id`,`m`.`hora` AS `hora`,`m`.`codigo` AS `codigo`,`m`.`unidadesIn` AS `unidadesIn`,`m`.`unidadesOut` AS `unidadesOut`,`m`.`longIn` AS `longIn`,`m`.`longOut` AS `longOut`,`m`.`banderas` AS `banderas`,`i`.`descripcionImpresion` AS `producto`,`p`.`descripcionProceso` AS `proceso` from ((`tbmerma` `m` join `impresiones` `i` on((`m`.`producto` = `i`.`id`))) join `procesos` `p` on((`m`.`proceso` = `p`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `procorte`
--

/*!50001 DROP VIEW IF EXISTS `procorte`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `procorte` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo` AS `rollo`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo` from ((((`tbprocorte` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `produccion`
--

/*!50001 DROP VIEW IF EXISTS `produccion`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `produccion` AS select `p`.`id` AS `id`,`p`.`nombreProducto` AS `idProducto`,`p`.`fechamov` AS `fechamov`,(select concat(`empleado`.`Nombre`,' ',`empleado`.`apellido`) from `empleado` where (`empleado`.`ID` = `p`.`cdgempleado`)) AS `empleado`,if((`t`.`id` = 1),(select `bandaspp`.`nombreBSPP` from `bandaspp` where (`bandaspp`.`IdBSPP` = `p`.`nombreProducto`)),(select `impresiones`.`descripcionImpresion` from `impresiones` where (`impresiones`.`id` = `p`.`nombreProducto`))) AS `nombreProducto`,`p`.`juegoLotes` AS `juegoLotes`,`p`.`cantLotes` AS `cantLotes`,`p`.`juegoCilindros` AS `juegoCilindros`,if((`t`.`id` = 1),(select `bandaseguridad`.`nombreBanda` from `bandaseguridad` where (`bandaseguridad`.`IDBanda` = `p`.`disenio`)),(select `producto`.`descripcion` from `producto` where (`producto`.`ID` = `p`.`disenio`))) AS `disenio`,`m`.`descripcionMaq` AS `maquina`,`p`.`fechaProduccion` AS `fechaProduccion`,`t`.`tipo` AS `tipo`,`t`.`id` AS `idtipo`,`p`.`unidades` AS `unidades`,`p`.`suaje` AS `suaje`,`p`.`juegoCireles` AS `juegoCireles`,`p`.`estado` AS `estado` from ((`tbproduccion` `p` join `maquinas` `m` on((`p`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`p`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `proembosado`
--

/*!50001 DROP VIEW IF EXISTS `proembosado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `proembosado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`lote` AS `lote`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`peso` AS `peso`,`pr`.`longitud` AS `longitud`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera` from ((((`tbproembosado` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `profoliado`
--

/*!50001 DROP VIEW IF EXISTS `profoliado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `profoliado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`bobina` AS `bobina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`peso` AS `peso`,`pr`.`longitud` AS `longitud` from ((((`tbprofoliado` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `profusion`
--

/*!50001 DROP VIEW IF EXISTS `profusion`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `profusion` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`bobina` AS `bobina`,`pr`.`longitud` AS `longitud`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera`,`pr`.`disco` AS `disco`,`pr`.`cdgDisco` AS `cdgDisco` from ((((`tbprofusion` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `proimpresion`
--

/*!50001 DROP VIEW IF EXISTS `proimpresion`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `proimpresion` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,(select concat(`juegoscilindros`.`identificadorCilindro`,' | ',`juegoscilindros`.`proveedor`) from `juegoscilindros` where (`juegoscilindros`.`IDCilindro` = `pr`.`juegoCilindros`)) AS `juegoCilindros` from ((((`tbproimpresion` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `proimpresion-flexografica`
--

/*!50001 DROP VIEW IF EXISTS `proimpresion-flexografica`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `proimpresion-flexografica` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,(select concat(`juegoscireles`.`identificadorJuego`,' | ',`juegoscireles`.`num_dientes`,' D.') from `juegoscireles` where (`juegoscireles`.`id` = `pr`.`juegoCireles`)) AS `juegoCireles`,`pr`.`suaje` AS `suaje`,`pr`.`anillox` AS `anillox` from ((((`tbproimpresion-flexografica` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prolaminado`
--

/*!50001 DROP VIEW IF EXISTS `prolaminado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prolaminado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,if((`pr`.`tipo` = 1),(select `bandaspp`.`nombreBSPP` from `bandaspp` where (`bandaspp`.`IdBSPP` = `pr`.`producto`)),(select `impresiones`.`descripcionImpresion` from `impresiones` where (`impresiones`.`id` = `pr`.`producto`))) AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera` from (((`tbprolaminado` `pr` join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prolaminado 2`
--

/*!50001 DROP VIEW IF EXISTS `prolaminado 2`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prolaminado 2` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera` from ((((`tbprolaminado 2` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prorefilado`
--

/*!50001 DROP VIEW IF EXISTS `prorefilado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prorefilado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`amplitud` AS `amplitud` from ((((`tbprorefilado` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prorevision`
--

/*!50001 DROP VIEW IF EXISTS `prorevision`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prorevision` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`rollo` AS `rollo`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`bandera` AS `bandera` from ((((`tbprorevision` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prorevision 2`
--

/*!50001 DROP VIEW IF EXISTS `prorevision 2`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prorevision 2` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`rollo` AS `rollo`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`bandera` AS `bandera` from ((((`tbprorevision 2` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prosliteo`
--

/*!50001 DROP VIEW IF EXISTS `prosliteo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prosliteo` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,if((`pr`.`tipo` = 1),(select `bandaspp`.`nombreBSPP` from `bandaspp` where (`bandaspp`.`IdBSPP` = `pr`.`producto`)),(select `impresiones`.`descripcionImpresion` from `impresiones` where (`impresiones`.`id` = `pr`.`producto`))) AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`lote` AS `lote`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`amplitud` AS `amplitud`,`pr`.`bandera` AS `bandera` from (((`tbprosliteo` `pr` join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `prosuajado`
--

/*!50001 DROP VIEW IF EXISTS `prosuajado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `prosuajado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`rollo` AS `rollo`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`suaje` AS `suaje` from ((((`tbprosuajado` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `protroquelado`
--

/*!50001 DROP VIEW IF EXISTS `protroquelado`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kronos`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `protroquelado` AS select `pr`.`id` AS `id`,`pr`.`total` AS `total`,`i`.`descripcionImpresion` AS `producto`,`pr`.`fecha` AS `fecha`,`pr`.`noop` AS `noop`,`pr`.`unidades` AS `unidades`,concat(`e`.`Nombre`,' ',`e`.`apellido`,' [',`e`.`numemple`,']') AS `operador`,`m`.`descripcionMaq` AS `maquina`,`pr`.`rollo_padre` AS `rollo_padre`,`t`.`tipo` AS `tipo`,`pr`.`rollo` AS `rollo`,`pr`.`longitud` AS `longitud`,`pr`.`peso` AS `peso`,`pr`.`suaje` AS `suaje` from ((((`tbprotroquelado` `pr` join `impresiones` `i` on((`pr`.`producto` = `i`.`id`))) join `empleado` `e` on((`pr`.`operador` = `e`.`ID`))) join `maquinas` `m` on((`pr`.`maquina` = `m`.`idMaq`))) join `tipoproducto` `t` on((`pr`.`tipo` = `t`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `shoTableroBS`
--

/*!50001 DROP VIEW IF EXISTS `shoTableroBS`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `shoTableroBS` AS select concat(`bandaseguridad`.`nombreBanda`,'|',`bandaspp`.`nombreBSPP`) AS `productos`,`bandaspp`.`nombreBSPP` AS `descripcionImpresion`,`bandaspp`.`IdBSPP` AS `idImpresion`,`bandaspp`.`baja` AS `bajaBSPP`,`bandaseguridad`.`baja` AS `baja`,`bandaspp`.`IdBSPP` AS `id`,`bandaseguridad`.`necesidad` AS `necesidad`,`bandaspp`.`sustrato` AS `sustrato`,concat('') AS `nombreBanda`,`bandaseguridad`.`anchura` AS `anchura`,`bandaspp`.`anchuraLaminado` AS `alturaEtiqueta`,concat('') AS `tintas` from (`bandaseguridad` left join `bandaspp` on((`bandaseguridad`.`IDBanda` = `bandaspp`.`identificadorBS`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `showTablero`
--

/*!50001 DROP VIEW IF EXISTS `showTablero`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `showTablero` AS select `producto`.`tipo` AS `idtipo`,`impresiones`.`fecha_alta` AS `fecha_alta`,`impresiones`.`codigoImpresion` AS `codigoImpresion`,`impresiones`.`observaciones` AS `observaciones`,(select `tipoproducto`.`tipo` from `tipoproducto` where (`tipoproducto`.`id` = `producto`.`tipo`)) AS `tipo`,concat(`producto`.`descripcion`,' | ',`impresiones`.`descripcionImpresion`) AS `productos`,`impresiones`.`descripcionImpresion` AS `descripcionImpresion`,`impresiones`.`holograma` AS `holograma`,`impresiones`.`id` AS `idImpresion`,`impresiones`.`sustrato` AS `idsustrato`,(select `sustrato`.`descripcionSustrato` from `sustrato` where (`sustrato`.`idSustrato` = `impresiones`.`sustrato`)) AS `sustrato`,(select `bandaseguridad`.`nombreBanda` from `bandaseguridad` where (`bandaseguridad`.`IDBanda` = `impresiones`.`nombreBanda`)) AS `nombreBanda`,`impresiones`.`anchoPelicula` AS `anchoPelicula`,`impresiones`.`alturaEtiqueta` AS `alturaEtiqueta`,`impresiones`.`tintas` AS `tintas` from (`producto` left join `impresiones` on((`producto`.`ID` = `impresiones`.`descripcionDisenio`))) where ((`impresiones`.`baja` = 1) and (`producto`.`baja` = 1)) order by `impresiones`.`fecha_alta` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-18 12:17:34
