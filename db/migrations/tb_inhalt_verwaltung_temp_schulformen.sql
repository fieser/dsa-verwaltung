-- MySQL dump 10.19  Distrib 10.2.44-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: anmeldung_temp_2526
-- ------------------------------------------------------
-- Server version	10.2.44-MariaDB

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
-- Table structure for table `schulformen`
--

DROP TABLE IF EXISTS `schulformen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schulformen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuerzel` varchar(50) COLLATE utf8_bin NOT NULL,
  `name` varchar(200) COLLATE utf8_bin NOT NULL,
  `aktiv` varchar(11) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schulformen`
--

LOCK TABLES `schulformen` WRITE;
/*!40000 ALTER TABLE `schulformen` DISABLE KEYS */;
INSERT INTO `schulformen` VALUES (1,'bs','Berufsschule',''),(2,'bgy','Berufliches Gymnasium (BGY)',''),(3,'bvj','Berufsvorbereitungsjahr (BVJ)',''),(4,'bf1','Berufsfachschule 1 (BF 1)',''),(5,'bf2','Berufsfachschule 2 (BF 2)',''),(6,'hbf','Höhere Berufsfachschule (HBF)',''),(7,'bos1','Berufsoberschule 1 (BOS 1)',''),(8,'bos2','Berufsoberschule 2 (BOS 2)',''),(9,'dbos','Duale Berufsoberschule (DBOS)',''),(10,'fs','Fachschule (FS)',''),(11,'bfp','Berufsfachschule Pflege (BFP)',''),(12,'aph','Fachschule Altenpflegehilfe (FS APH)',''),(13,'fsof','FS Organisation und Führung (FSOF)\n','');
/*!40000 ALTER TABLE `schulformen` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-27 16:18:04
