-- MySQL dump 10.19  Distrib 10.2.44-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: anmeldung_www_2526
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
-- Table structure for table `dsa_bewerberdaten`
--

DROP TABLE IF EXISTS `dsa_bewerberdaten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dsa_bewerberdaten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(200) ,
  `code` int(50) NOT NULL,
  `status` varchar(200) ,
  `nachname` varchar(200) NOT NULL,
  `vorname` varchar(200) NOT NULL,
  `geschlecht` varchar(100) ,
  `geburtsdatum` varchar(100) ,
  `geburtsort` varchar(200) ,
  `geburtsland` varchar(200) ,
  `zuzug` varchar(100) ,
  `staatsangehoerigkeit` varchar(200) ,
  `muttersprache` varchar(200) ,
  `religion` varchar(100) ,
  `herkuftsland` varchar(200) ,
  `strasse` varchar(200) ,
  `plz` varchar(100) ,
  `wohnort` varchar(200) ,
  `hausnummer` varchar(200) ,
  `telefon1` varchar(200) ,
  `telefon2` varchar(200) ,
  `mail` varchar(200) ,
  `schulart` varchar(200) ,
  `schulname` varchar(200) ,
  `jahrgang` varchar(200) ,
  `abschluss` varchar(200) ,
  `sorge1_nachname` varchar(200) ,
  `sorge1_vorname` varchar(200) ,
  `sorge1_anrede` varchar(200) ,
  `sorge1_art` varchar(200) ,
  `sorge1_strasse` varchar(200) ,
  `sorge1_hausnummer` varchar(200) ,
  `sorge1_plz` varchar(200) ,
  `sorge1_wohnort` varchar(200) ,
  `sorge1_telefon1` varchar(200) ,
  `sorge1_telefon2` varchar(200) ,
  `sorge1_mail` varchar(200) ,
  `sorge2_vorname` varchar(200) ,
  `sorge2_nachname` varchar(200) ,
  `sorge2_anrede` varchar(200) ,
  `sorge2_art` varchar(200) ,
  `sorge2_strasse` varchar(200) ,
  `sorge2_hausnummer` varchar(200) ,
  `sorge2_plz` varchar(200) ,
  `sorge2_wohnort` varchar(200) ,
  `sorge2_telefon1` varchar(200) ,
  `sorge2_telefon2` varchar(200) ,
  `sorge2_mail` varchar(200) ,
  `papierkorb` varchar(11) NOT NULL,
  `pap_user` varchar(200) DEFAULT NULL,
  `pap_time` varchar(200) DEFAULT NULL,
  `dok_neu` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dsa_bildungsgang`
--

DROP TABLE IF EXISTS `dsa_bildungsgang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dsa_bildungsgang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(200),
  `prio` varchar(11),
  `id_dsa_bewerberdaten` int(11) NOT NULL,
  `time` varchar(200),
  `schulform` varchar(200),
  `beruf` varchar(200),
  `beruf_anz` varchar(500)  NOT NULL,
  `dauer` varchar(200),
  `beginn` varchar(200),
  `ende` varchar(200),
  `beruf2` varchar(200),
  `betrieb` varchar(200),
  `betrieb2` varchar(200),
  `betrieb_plz` varchar(200),
  `betrieb_ort` varchar(200),
  `betrieb_strasse` varchar(200),
  `betrieb_hausnummer` varchar(200),
  `betrieb_telefon` varchar(200),
  `betrieb_mail` varchar(200),
  `ausbilder_nachname` varchar(200),
  `ausbilder_vorname` varchar(200),
  `ausbilder_telefon` varchar(200),
  `ausbilder_telefon2` varchar(200),
  `ausbilder_mail` varchar(200),
  `bgy_sp1` varchar(200)  NOT NULL,
  `bgy_sp2` varchar(200),
  `bgy_sp3` varchar(200),
  `fs1` varchar(200),
  `fs1_von` varchar(200),
  `fs1_bis` varchar(200),
  `fs2` varchar(200),
  `fs2_von` varchar(200),
  `fs2_bis` varchar(200),
  `fs3` varchar(200),
  `fs3_von` varchar(200),
  `fs3_bis` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dsa_bildungsgang_vorjahr`
--

DROP TABLE IF EXISTS `dsa_bildungsgang_vorjahr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dsa_bildungsgang_vorjahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(200),
  `schulform` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1230 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_bewerber`
--

DROP TABLE IF EXISTS `edoo_bewerber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_bewerber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200),
  `vorname` varchar(200),
  `geburtsdatum` varchar(200),
  `geburtsort` varchar(200),
  `geburtsland` varchar(50),
  `staatsangehoerigkeit` varchar(50),
  `herkunftsland` varchar(200),
  `zuzugsdatum` varchar(200),
  `geschlecht` varchar(200),
  `religionszugehoerigkeit` varchar(200),
  `strasse` varchar(200),
  `hausnummer` varchar(50),
  `plz` varchar(50),
  `wohnort` varchar(200),
  `mail` varchar(200),
  `telefon1` varchar(200),
  `telefon2` varchar(200),
  `abschluss` varchar(200),
  `bildungsgang` varchar(200),
  `entscheidung` varchar(200),
  `status_uebernahme` varchar(11),
  `create_date` varchar(200),
  `create_user` varchar(200),
  `update_date` varchar(200),
  `update_user` varchar(200),
  `sorge1_strasse` varchar(200),
  `sorge1_hausnummer` varchar(200),
  `sorge1_plz` varchar(200),
  `sorge1_wohnort` varchar(200),
  `sorge1_personentyp` varchar(200),
  `sorge2_strasse` varchar(200),
  `sorge2_hausnummer` varchar(200),
  `sorge2_plz` varchar(200),
  `sorge2_wohnort` varchar(200),
  `sorge2_personentyp` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1627 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_bewerbungsziel`
--

DROP TABLE IF EXISTS `edoo_bewerbungsziel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_bewerbungsziel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_edoo` varchar(200),
  `kurzform` varchar(200),
  `anzeigeform` varchar(200),
  `id_bildungsgang` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_fremdsprachen`
--

DROP TABLE IF EXISTS `edoo_fremdsprachen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_fremdsprachen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200),
  `vorname` varchar(200),
  `geburtsdatum` varchar(200),
  `fs` varchar(200),
  `fs_von` varchar(200),
  `fs_bis` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=763 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler`
--

DROP TABLE IF EXISTS `edoo_schueler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200),
  `vorname` varchar(200),
  `id_edoo` varchar(200),
  `geburtsdatum` varchar(200),
  `geburtsort` varchar(200)  NOT NULL,
  `geschlecht` varchar(11),
  `strasse` varchar(200),
  `hausnummer` varchar(50),
  `plz` varchar(50),
  `wohnort` varchar(200),
  `mail` varchar(200),
  `telefon1` varchar(200),
  `telefon2` varchar(200),
  `bildungsgang` varchar(200),
  `klasse` varchar(200),
  `beruf` varchar(200),
  `create_date` varchar(200),
  `create_user` varchar(200),
  `update_date` varchar(200),
  `update_user` varchar(200),
  `austritt` varchar(100),
  `eintritt` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7012 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler_betrieb`
--

DROP TABLE IF EXISTS `edoo_schueler_betrieb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler_betrieb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_schueler` varchar(200),
  `id_betrieb` varchar(200),
  `nachname` varchar(200),
  `vorname` varchar(200),
  `geburtsdatum` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2788 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler_klasse`
--

DROP TABLE IF EXISTS `edoo_schueler_klasse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler_klasse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schueler_id` varchar(200),
  `klasse` varchar(200),
  `klassengruppe` varchar(100),
  `schuljahr` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23976 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fehler`
--

DROP TABLE IF EXISTS `fehler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fehler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_edoo` varchar(11),
  `id_bewerberdaten` varchar(11),
  `id_bildungsgang` varchar(11),
  `feld_edoo` varchar(200),
  `feld_dsa` varchar(200),
  `feldname` varchar(200),
  `wo_in_edoo` varchar(11),
  `hinweis` varchar(500),
  `erledigt` varchar(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ignorieren`
--

DROP TABLE IF EXISTS `ignorieren`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ignorieren` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bewerber` int(11) NOT NULL,
  `wert_edoo` varchar(200),
  `wert_dsa` varchar(200),
  `okay_admin` varchar(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dsa_bewerberdaten` int(11) DEFAULT NULL,
  `mailtext` longtext,
  `log` longtext,
  `last_user` varchar(200),
  `last_time` varchar(200),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schulformen`
--

DROP TABLE IF EXISTS `schulformen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schulformen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuerzel` varchar(50),
  `name` varchar(200),
  `aktiv` varchar(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `senden_texte`
--

DROP TABLE IF EXISTS `senden_texte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `senden_texte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schulform` varchar(200),
  `bezeichnung` varchar(200),
  `feldname` varchar(200),
  `text` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summen`
--

DROP TABLE IF EXISTS `summen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(200),
  `time` varchar(100),
  `schulform` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vorbildung`
--

DROP TABLE IF EXISTS `vorbildung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vorbildung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schluessel` varchar(200),
  `kurzform` varchar(200),
  `anzeigeform` varchar(500),
  `langform` varchar(500),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vorgang`
--

DROP TABLE IF EXISTS `vorgang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vorgang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_dsa_bewerberdaten` int(11) NOT NULL,
  `bemerkungen` varchar(500),
  `dok_zeugnis` varchar(11),
  `dok_lebenslauf` varchar(11),
  `dok_ausweis` varchar(11),
  `dok_erfahrung` varchar(11),
  `last_user` varchar(200),
  `last_time` varchar(200),
  `log` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-03 21:43:23
