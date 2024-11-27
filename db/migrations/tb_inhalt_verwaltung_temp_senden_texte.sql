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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `senden_texte`
--

LOCK TABLES `senden_texte` WRITE;
/*!40000 ALTER TABLE `senden_texte` DISABLE KEYS */;
INSERT INTO `senden_texte` VALUES (1,'bs','Berufsschule','text_bs','<p>Bitte legen Sie an Ihrem ersten Schultag der Klassenlehrerin bzw. dem Klassenlehrer eine <b>Kopie Ihres Ausbildungsvertrages</b> vor.</p>\r\n	<p>Den <b>Tag der Einschulung</b> und die Unterrichtstage im Schuljahr sind abhängig vom Ausbildungsberuf und entnehmen Sie bitte den i.d.R. ab den Osterferien auf unserer Website veröffentlichten Block- und Teilzeitplänen (www.bbs1-mainz.com/stundenplaene/block-und-teilzeitplaene/).</p> Bitte beachten Sie, dass sich die die Block- und Teilzeitpläne bis zu Beginn des Schuljahres eventuell noch ändern können.</p>\r\n	<p>Am Einschulungstag - i.d.R. der 26.08.2024 - informieren wir Sie ab 7:30 Uhr im Eingangsbereich unserer Schule über den Weg in Ihren Klassenraum.<br>\r\n	Die <b>Einschulung beginnt um 8:00 Uhr</b>. </p>\r\n<p>Sollten Sie Ihre Ausbildung bereits im laufenden Schuljahr beginnen oder an unsere Schule wechseln, dann erscheinen Sie bitte am - gemäß dem entsprechenden Block- oder Teilzeitplan - nächsten Unterrichtstag.</p>\r\n'),(2,'bvj','Berufsvorbereitungsjahr (BVJ)','text_bvj','Gewerbe und Technik\r\n		<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n		<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n		<li>Kopie Personalausweis (Vorder- und Rückseite) oder Meldebescheinigung</li>\r\n		<li>Lebenslauf</li>\r\n		<li><b>beglaubigte Kopie</b> letztes Halbjahreszeugnis</li>\r\n		<p>Das Abgang- bzw. Abschlusszeugnis ist bis zum 1. August nachzureichen, ebenfalls in <b>beglaubigter Form.</b> Falls Ihnen kein Zeugnis vorliegen sollte und sie zur Zeit an einer Berufsbildenden Schule das Berufsvorbereitungsjahr Sprachförderung besuchen, reichen Sie uns bitte eine aktuelle Schulbescheinigung ein.</p>\r\n				\r\n		<p>Die Einschulung zum Schuljahr 2024/2025 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>\r\n'),(3,'bf1','Berufsfachschule 1 (BF1)','text_bf1','Gewerbe und Technik\r\n		<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n		<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n		<li>Kopie Personalausweis (Vorder- und Rückseite) oder Meldebescheinigung</li>\r\n		<li>Lebenslauf</li>\r\n		<li><b>beglaubigte Kopie</b> letztes Halbjahreszeugnis</li>\r\n		<p>Das Abgang- bzw. Abschlusszeugnis ist bis zum 1. August nachzureichen, ebenfalls in <b>beglaubigter Form.</b></p>\r\n				\r\n		<p>Die Einschulung zum Schuljahr 2024/2025 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>\r\n'),(4,'bf2','Berufsfachschule 2 (BF2)','text_bf2','Gewerbe und Technik\r\n		<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n		<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n		<li>Kopie Personalausweis (Vorder- und Rückseite) oder Meldebescheinigung</li>\r\n		<li>Lebenslauf</li>\r\n		<li><b>beglaubigte Kopie</b> letztes Halbjahreszeugnis</li>\r\n		<p>Das Abgang- bzw. Abschlusszeugnis ist bis zum 1. August nachzureichen, ebenfalls in <b>beglaubigter Form.</b></p>\r\n				\r\n		<p>Die Einschulung zum Schuljahr 2024/2025 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>\r\n'),(5,'bos2','Berufsoberschule 2 (BOS2)','text_bos2',''),(6,'bos1','Berufsoberschule 1 (BOS1)','text_bos1','<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n	<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n	<li>Personalausweis</li>\r\n	<li>Lebenslauf</li>\r\n	<li>Qual. Sek. I Abschluss</li>\r\n	<li>letztes Jahreszeugnis der Berufsschule</li> \r\n	<p>Das Abschlusszeugnis der Berufsschule sowie das Prüfungszeugnis des Ausbildungsberufes ist bis zum 1. August nachzureichen.</p>\r\n	<p>Die Einschulung zum Schuljahr 2024/2025 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>\r\n'),(7,'dbos','Duale Berufsoberschule (DBOS)','text_dbos','<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n	<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n	<li>Personalausweis</li>\r\n	<li>Lebenslauf</li>\r\n	<li>Qual. Sek. I Abschluss</li>\r\n	<li>letztes Jahreszeugnis der Berufsschule</li>\r\n	<li>Ausbildungsvertrag, sofern die Ausbildung nicht bis zum 31. Juli beendet wird.</li> \r\n	<p>Das Abschlusszeugnis der Berufsschule sowie das Prüfungszeugnis des Ausbildungsberufes ist bis zum 1. August nachzureichen.</p>\r\n'),(8,'fs','Fachschule (FS)','text_fs','<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n	<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n	<li>Personalausweis (Vorder- und Rückseite)</li>\r\n	<li>Lebenslauf</li>\r\n	<li>Abschlusszeugnis der allgemeinbildenden Schule</li>\r\n	<li>Abslusszeugnis der Berufsschule</li>\r\n	<li>Prüfungszeugnis des Ausbildungsberufes</li>\r\n\r\n<p>Falls Ihnen keine beglaubigte Kopie vorliegen sollte, kommen Sie bitte mit Ihren Originalzeugnissen vorbei.'),(9,'bgy','Berufliches Gymnasium (BGY)','text_bgy','<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen von Ihnen erhalten haben.</p>\r\n\r\n<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n<li>Kopie Personalausweis (Vorder- und Rückseite).<br>Falls noch kein Personalausweis vorliegt, dann eine Kopie der Meldebescheinigung.</li>\r\n<li>Lebenslauf</li>\r\n<li>letztes Halbjahreszeugnis in beglaubigter Kopie</li> \r\n<li>Jahres-/Abschlusszeugnis mit Qual. Sek. I in beglaubigter Kopie (ist bis zum 1. August nachzureichen)</li>\r\n\r\n<p>Die Einschulung zum Schuljahr 2025/2026 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>'),(10,'hbf','Höhere Berufsfachschule','text_hbf','<p>Ihre Anmeldung ist eingegangen und wird bearbeitet nachdem wir folgende Unterlagen <b>im Original oder als beglaubigte Kopie</b> von Ihnen erhalten haben.</p>\r\n	<p>Senden Sie uns die Unterlagen an unsere Postanschrift oder geben Sie sie während der Öffnungszeiten in unserem Sekretariat ab.</p>\r\n\r\n	<li>Personalausweis</li>\r\n	<li>Lebenslauf</li>\r\n	<li>letztes Halbjahreszeugnis</li>\r\n	<li>Jahres-/Abschlusszeugnis mit Qual. Sek. I (ist bis zum 1. August nachzureichen)</li>\r\n	<p>Die Einschulung zum Schuljahr 2024/2025 erfolgt am <b>Montag, 26.08.2024 um 8:00 Uhr</b>. Im Eingangsbereich unserer Schule informieren wir Sie ab 7:30 Uhr über den Weg in Ihren Klassenraum.</p>\r\n\r\n'),(11,'bfp','Berufsfachschule Pflege (BFP)','text_bfp',''),(12,'aph','Fachschule Altenpflegehilfe (FS APH)','text_aph',''),(13,'fsof','FS Organisation und Führung','text_fsof','');
/*!40000 ALTER TABLE `senden_texte` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-27 15:54:41
