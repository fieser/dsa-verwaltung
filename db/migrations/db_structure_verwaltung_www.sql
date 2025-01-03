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
  `md5` varchar(200)  COLLATE utf8_bin NOT NULL,
  `code` int(50) NOT NULL,
  `status` varchar(200)  COLLATE utf8_bin NOT NULL,
  `nachname` varchar(200) NOT NULL,
  `vorname` varchar(200) NOT NULL,
  `geschlecht` varchar(100)  COLLATE utf8_bin NOT NULL,
  `geburtsdatum` varchar(100)  COLLATE utf8_bin NOT NULL,
  `geburtsort` varchar(200)  COLLATE utf8_bin NOT NULL,
  `geburtsland` varchar(200)  COLLATE utf8_bin NOT NULL,
  `zuzug` varchar(100)  COLLATE utf8_bin NOT NULL,
  `staatsangehoerigkeit` varchar(200)  COLLATE utf8_bin NOT NULL,
  `muttersprache` varchar(200)  COLLATE utf8_bin NOT NULL,
  `religion` varchar(100)  COLLATE utf8_bin NOT NULL,
  `herkuftsland` varchar(200)  COLLATE utf8_bin NOT NULL,
  `strasse` varchar(200)  COLLATE utf8_bin NOT NULL,
  `plz` varchar(100)  COLLATE utf8_bin NOT NULL,
  `wohnort` varchar(200)  COLLATE utf8_bin NOT NULL,
  `hausnummer` varchar(200)  COLLATE utf8_bin NOT NULL,
  `telefon1` varchar(200)  COLLATE utf8_bin NOT NULL,
  `telefon2` varchar(200)  COLLATE utf8_bin NOT NULL,
  `mail` varchar(200)  COLLATE utf8_bin NOT NULL,
  `schulart` varchar(200)  COLLATE utf8_bin NOT NULL,
  `schulname` varchar(200)  COLLATE utf8_bin NOT NULL,
  `jahrgang` varchar(200)  COLLATE utf8_bin NOT NULL,
  `abschluss` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_nachname` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_vorname` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_anrede` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_art` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_strasse` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_hausnummer` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_plz` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_wohnort` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_telefon1` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_telefon2` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge1_mail` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_vorname` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_nachname` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_anrede` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_art` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_strasse` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_hausnummer` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_plz` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_wohnort` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_telefon1` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_telefon2` varchar(200)  COLLATE utf8_bin NOT NULL,
  `sorge2_mail` varchar(200)  COLLATE utf8_bin NOT NULL,
  `papierkorb` varchar(11) NOT NULL,
  `pap_user` varchar(200) DEFAULT NULL,
  `pap_time` varchar(200) DEFAULT NULL,
  `dok_neu` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dsa_bildungsgang`
--

DROP TABLE IF EXISTS `dsa_bildungsgang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dsa_bildungsgang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(200) COLLATE utf8_bin NOT NULL,
  `prio` varchar(11) COLLATE utf8_bin NOT NULL,
  `id_dsa_bewerberdaten` int(11) NOT NULL,
  `time` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `schulform` varchar(200) COLLATE utf8_bin NOT NULL,
  `beruf` varchar(200) COLLATE utf8_bin NOT NULL,
  `beruf_anz` varchar(500)  NOT NULL,
  `dauer` varchar(200) COLLATE utf8_bin NOT NULL,
  `beginn` varchar(200) COLLATE utf8_bin NOT NULL,
  `ende` varchar(200) COLLATE utf8_bin NOT NULL,
  `beruf2` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb2` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_plz` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_ort` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_strasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_hausnummer` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_telefon` varchar(200) COLLATE utf8_bin NOT NULL,
  `betrieb_mail` varchar(200) COLLATE utf8_bin NOT NULL,
  `ausbilder_nachname` varchar(200) COLLATE utf8_bin NOT NULL,
  `ausbilder_vorname` varchar(200) COLLATE utf8_bin NOT NULL,
  `ausbilder_telefon` varchar(200) COLLATE utf8_bin NOT NULL,
  `ausbilder_telefon2` varchar(200) COLLATE utf8_bin NOT NULL,
  `ausbilder_mail` varchar(200) COLLATE utf8_bin NOT NULL,
  `bgy_sp1` varchar(200)  NOT NULL,
  `bgy_sp2` varchar(200) COLLATE utf8_bin NOT NULL,
  `bgy_sp3` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs1` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs1_von` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs1_bis` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs2` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs2_von` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs2_bis` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs3` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs3_von` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs3_bis` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dsa_bildungsgang_vorjahr`
--

DROP TABLE IF EXISTS `dsa_bildungsgang_vorjahr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dsa_bildungsgang_vorjahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(200) COLLATE utf8_bin NOT NULL,
  `schulform` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1230 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_bewerber`
--

DROP TABLE IF EXISTS `edoo_bewerber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_bewerber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsdatum` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsort` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsland` varchar(50) COLLATE utf8_bin NOT NULL,
  `staatsangehoerigkeit` varchar(50) COLLATE utf8_bin NOT NULL,
  `herkunftsland` varchar(200) COLLATE utf8_bin NOT NULL,
  `zuzugsdatum` varchar(200) COLLATE utf8_bin NOT NULL,
  `geschlecht` varchar(200) COLLATE utf8_bin NOT NULL,
  `religionszugehoerigkeit` varchar(200) COLLATE utf8_bin NOT NULL,
  `strasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `hausnummer` varchar(50) COLLATE utf8_bin NOT NULL,
  `plz` varchar(50) COLLATE utf8_bin NOT NULL,
  `wohnort` varchar(200) COLLATE utf8_bin NOT NULL,
  `mail` varchar(200) COLLATE utf8_bin NOT NULL,
  `telefon1` varchar(200) COLLATE utf8_bin NOT NULL,
  `telefon2` varchar(200) COLLATE utf8_bin NOT NULL,
  `abschluss` varchar(200) COLLATE utf8_bin NOT NULL,
  `bildungsgang` varchar(200) COLLATE utf8_bin NOT NULL,
  `entscheidung` varchar(200) COLLATE utf8_bin NOT NULL,
  `status_uebernahme` varchar(11) COLLATE utf8_bin NOT NULL,
  `create_date` varchar(200) COLLATE utf8_bin NOT NULL,
  `create_user` varchar(200) COLLATE utf8_bin NOT NULL,
  `update_date` varchar(200) COLLATE utf8_bin NOT NULL,
  `update_user` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge1_strasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge1_hausnummer` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge1_plz` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge1_wohnort` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge1_personentyp` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge2_strasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge2_hausnummer` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge2_plz` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge2_wohnort` varchar(200) COLLATE utf8_bin NOT NULL,
  `sorge2_personentyp` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1627 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_bewerbungsziel`
--

DROP TABLE IF EXISTS `edoo_bewerbungsziel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_bewerbungsziel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_edoo` varchar(200) COLLATE utf8_bin NOT NULL,
  `kurzform` varchar(200) COLLATE utf8_bin NOT NULL,
  `anzeigeform` varchar(200) COLLATE utf8_bin NOT NULL,
  `id_bildungsgang` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_fremdsprachen`
--

DROP TABLE IF EXISTS `edoo_fremdsprachen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_fremdsprachen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsdatum` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs_von` varchar(200) COLLATE utf8_bin NOT NULL,
  `fs_bis` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=763 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler`
--

DROP TABLE IF EXISTS `edoo_schueler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nachname` varchar(200) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(200) COLLATE utf8_bin NOT NULL,
  `id_edoo` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsdatum` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsort` varchar(200)  NOT NULL,
  `geschlecht` varchar(11) COLLATE utf8_bin NOT NULL,
  `strasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `hausnummer` varchar(50) COLLATE utf8_bin NOT NULL,
  `plz` varchar(50) COLLATE utf8_bin NOT NULL,
  `wohnort` varchar(200) COLLATE utf8_bin NOT NULL,
  `mail` varchar(200) COLLATE utf8_bin NOT NULL,
  `telefon1` varchar(200) COLLATE utf8_bin NOT NULL,
  `telefon2` varchar(200) COLLATE utf8_bin NOT NULL,
  `bildungsgang` varchar(200) COLLATE utf8_bin NOT NULL,
  `klasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `beruf` varchar(200) COLLATE utf8_bin NOT NULL,
  `create_date` varchar(200) COLLATE utf8_bin NOT NULL,
  `create_user` varchar(200) COLLATE utf8_bin NOT NULL,
  `update_date` varchar(200) COLLATE utf8_bin NOT NULL,
  `update_user` varchar(200) COLLATE utf8_bin NOT NULL,
  `austritt` varchar(100) COLLATE utf8_bin NOT NULL,
  `eintritt` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7012 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler_betrieb`
--

DROP TABLE IF EXISTS `edoo_schueler_betrieb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler_betrieb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_schueler` varchar(200) COLLATE utf8_bin NOT NULL,
  `id_betrieb` varchar(200) COLLATE utf8_bin NOT NULL,
  `nachname` varchar(200) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(200) COLLATE utf8_bin NOT NULL,
  `geburtsdatum` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2788 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `edoo_schueler_klasse`
--

DROP TABLE IF EXISTS `edoo_schueler_klasse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edoo_schueler_klasse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schueler_id` varchar(200) COLLATE utf8_bin NOT NULL,
  `klasse` varchar(200) COLLATE utf8_bin NOT NULL,
  `klassengruppe` varchar(100) COLLATE utf8_bin NOT NULL,
  `schuljahr` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23976 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fehler`
--

DROP TABLE IF EXISTS `fehler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fehler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_edoo` varchar(11) COLLATE utf8_bin NOT NULL,
  `id_bewerberdaten` varchar(11) COLLATE utf8_bin NOT NULL,
  `id_bildungsgang` varchar(11) COLLATE utf8_bin NOT NULL,
  `feld_edoo` varchar(200) COLLATE utf8_bin NOT NULL,
  `feld_dsa` varchar(200) COLLATE utf8_bin NOT NULL,
  `feldname` varchar(200) COLLATE utf8_bin NOT NULL,
  `wo_in_edoo` varchar(11) COLLATE utf8_bin NOT NULL,
  `hinweis` varchar(500) COLLATE utf8_bin NOT NULL,
  `erledigt` varchar(11) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
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
  `wert_edoo` varchar(200) COLLATE utf8_bin NOT NULL,
  `wert_dsa` varchar(200) COLLATE utf8_bin NOT NULL,
  `okay_admin` varchar(11) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
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
  `mailtext` longtext COLLATE utf8_bin DEFAULT NULL,
  `log` longtext COLLATE utf8_bin DEFAULT NULL,
  `last_user` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `last_time` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `senden_texte`
--

DROP TABLE IF EXISTS `senden_texte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `senden_texte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schulform` varchar(200) COLLATE utf8_bin NOT NULL,
  `bezeichnung` varchar(200) COLLATE utf8_bin NOT NULL,
  `feldname` varchar(200) COLLATE utf8_bin NOT NULL,
  `text` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `summen`
--

DROP TABLE IF EXISTS `summen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(200) COLLATE utf8_bin NOT NULL,
  `time` varchar(100) COLLATE utf8_bin NOT NULL,
  `schulform` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vorbildung`
--

DROP TABLE IF EXISTS `vorbildung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vorbildung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schluessel` varchar(200) COLLATE utf8_bin NOT NULL,
  `kurzform` varchar(200) COLLATE utf8_bin NOT NULL,
  `anzeigeform` varchar(500) COLLATE utf8_bin NOT NULL,
  `langform` varchar(500) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
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
  `bemerkungen` varchar(500) COLLATE utf8_bin NOT NULL,
  `dok_zeugnis` varchar(11) COLLATE utf8_bin NOT NULL,
  `dok_lebenslauf` varchar(11) COLLATE utf8_bin NOT NULL,
  `dok_ausweis` varchar(11) COLLATE utf8_bin NOT NULL,
  `dok_erfahrung` varchar(11) COLLATE utf8_bin NOT NULL,
  `last_user` varchar(200) COLLATE utf8_bin NOT NULL,
  `last_time` varchar(200) COLLATE utf8_bin NOT NULL,
  `log` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-27 21:00:37
