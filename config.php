 <?php

 //ob_start();
// Liste der erlaubten IP-Adressbereiche (lokales Netzwerk)
//$allowed_ips = ['172.0.0.0/8'];
$allowed_ips = ['217.198.244.140','172.22.100.18','127.0.0.1','172.22.100.17'];


// Funktion, um zu prüfen, ob eine IP-Adresse in einem der erlaubten Bereiche liegt
function ip_is_allowed($ip, $allowed_ips) {
    foreach ($allowed_ips as $range) {
        if (strpos($range, '/') == false) {
            $range .= '/32';
        }
        // Prüfen, ob die IP im Bereich liegt
        list($range, $netmask) = explode('/', $range, 2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($ip);
        $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
        $netmask_decimal = ~ $wildcard_decimal;
        
        if (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal)) {
            return true;
        }
    }
    return false;
}

// Die IP-Adresse des Benutzers abrufen

// Nutzt X-Forwarded-For, um die ursprüngliche Benutzer-IP zu erhalten
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $user_ip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
} else {
    $user_ip = $_SERVER['REMOTE_ADDR'];
}

// Ihre IP-Überprüfungslogik hier


//echo "Adresse: ".$user_ip;

// Überprüfen, ob die Benutzer-IP in der Liste der erlaubten IPs liegt
if (!ip_is_allowed($user_ip, $allowed_ips) AND $user_ip != "" AND time() > strtotime("2024-08-31") AND (time() < strtotime("2024-09-22") AND time() > strtotime("2024-09-24"))) {
    // Zugriff verweigern, wenn die IP nicht erlaubt ist
    header("HTTP/1.1 403 Forbidden");
	
    exit('Zugriff verweigert!');
	
}



//Schuljahr der aktuellen Planung: 
if (isset($_SESSION['schuljahr'])) {
	$schuljahr = $_SESSION['schuljahr'];
}
	
	if (!isset($_SESSION['schuljahr']) OR $schuljahr == "") {
		$schuljahr = "2025-2026"; //In diese Schuljahr gehen derzeit alle neuen Anmeldungen und gelangt man nach dem Login
	}
 
	$schuljahre = array();
 
/*
Wie legt man im Herbst eine neue Anmeldeperiode an?

1. 	In phpMyAdmin die beiden aktuellen Datenbanken www und temp kopieren:
	(z.B. anmeldung_www_2526 und anmeldung_temp_2526 zu anmeldung_www_2627 und anmeldung_temp_2627)
2.	Die mitkopierten Einträge in den Tabellen des neuen Schuljahrs (bewerberdaten, bildungsgang usw.) löschen.
3.	Hier in config.php die Variable $schuljahr auf das neue SJ einstellen.
4.	Hier in conifg.php nachfolgend im array schuljahre das neue Schuljahr anlegen.
5.	Und dann auch mal ein älteres Schuljahr löschen...

*/
 
//EXCLUDE INSTALL START
	
$schuljahre["24-25"]["jahr"] = "2024-2025";
	$schuljahre["24-25"]["erster_tag"] = "12.08.2024";
	$schuljahre["24-25"]["letzter_tag"] = "04.07.2025";
	$schuljahre["24-25"]["start_periode"] = "01.11.2023";
	$schuljahre["24-25"]["stichtag_statistik"] = "02.10.2025";
	$schuljahre["24-25"]["sichtbar_lk"] = 1; //Bei 0 erscheint das SJ für Lehrkräfte nicht im Drop
	$schuljahre["24-25"]["periode"] = "ab November 2023";

//EXCLUDE INSTALL END
	
$schuljahre["25-26"]["jahr"] = "2025-2026";
	$schuljahre["25-26"]["erster_tag"] = "";
	$schuljahre["25-26"]["letzter_tag"] = "";
	$schuljahre["24-25"]["start_periode"] = "01.10.2024";
	$schuljahre["25-26"]["stichtag_statistik"] = "";
	$schuljahre["25-26"]["sichtbar_lk"] = 1; //Bei 0 erscheint das SJ für Lehrkräfte nicht im Drop
	$schuljahre["25-26"]["periode"] = "ab Oktober 2024";	



//Aktuelles Schuljahr: 

	foreach($schuljahre as $perioden) {
		if ($perioden['jahr'] == $schuljahr) {
	$periode = $perioden['periode'];
		}
	}

// Verbindung zur Datenbank aufbauen.
include "/var/www/verbinden.php";
include "/var/www/verbinden_temp.php";
include "/var/www/verbinden_www.php";



function config($e) {

	global $db_temp;
	$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = '$e')");
	foreach($select_conf as $conf) {
		return $conf['wert'];
	}
	
}

//Schuldaten
$_SESSION['schule_name_zeile1'] = config("schule_name1");
$_SESSION['schule_name_zeile2'] = config("schule_name2");

$_SESSION['schule_kurz'] = config("schule_kurz"); //Bitte nur Kleinbuchstaben
$_SESSION['schule_kurzname'] = config("schule_kurzname");

$_SESSION['schule_strasse_nr'] = config("schule_strasse_nr");
$_SESSION['schule_plz_ort'] = config("schule_plz_ort");

$_SESSION['schule_tel'] = config("schule_tel");
$_SESSION['schule_fax'] = config("schule_fax");

$_SESSION['schule_mail'] = config("schule_mail");
$_SESSION['schule_url'] = config("website");
 


//Pfade:
$url = "https://service.bbs1-mainz.de/verwaltung/"; // BITTE mit / abschließen!
$url_anmeldung = config("url_formular"); // BITTE mit / abschließen!
$website = config("website");
$workdir = "/verwaltung/"; // BITTE mit / beginnen und abschließen!
$pfad_workdir = "/var/www/html/verwaltung/";
$url_impressum ="http://ilias.bbs1-mainz.de/ilias/goto.php?target=impr_0&client_id=bbs1";
$url_impressum = config("url_impressum");

	

 



//Einstellungen:
$xls_download = config("xls_download");
$fremdsprachen_export_deaktivieren = 0;
$debug = 0; //Anzeige zusätzlicher Infos zur Fehlersuche
$mail_status = 1; //Anzeige Spalte E-Mail in Liste
$upload_documents = 1; //Die Einstellung $upload_dokumente o.ä. muss auch auf dem öffentlichen Server entsprechend in config.php eingestellt sein!
$ldap_aktiviert = 1; //Passen Sie die Datei login_ad.php an, bevor Sie LDAP aktivieren!
$hinweise_conf_anzeigen = 1;//Anzeige der Hinweise auf der Startseite ein-/ausblenden
$button_querliste = 1; //Anzeige des Button Querliste im Menü ein-/ausblenden
$button_einschulung = 1; //Anzeige des Button Einschulung im Menü ein-/ausblenden
$button_klassenlisten = 0; //Anzeige des Button Klassenlisten im Menü ein-/ausblenden
$status_pruefung_in_liste = 0; //Wenn 1, wird der Status der Prüfung in liste.php durchgeführt. Wenn dies per Cronjob mit status_pruefung.php, bitte deaktivieren.
$temp2db_in_liste_php = 0; //Wenn temp2db.php nicht per crontab aufgerufen wird, dann hier auf 1 setzen.

// FUNKTIONEN:

function umlauteumwandeln($str){
 $tempstr = Array("Ä" => "AE", "Ö" => "OE", "Ü" => "UE", "ä" => "ae", "ö" => "oe", "ü" => "ue", "ß" => "ss"); 
 return strtr($str, $tempstr);
 }

function dateivergleich($a,$b) {
	$ah = md5_file($a);
	$bh = md5_file($b);
	
	if ($ah == $bh) {
		return "true";
	} else {
		return "false";
	}	
} //Ende Funktion


//Diese Zeilen ermöglichen den initialen Zugriff vor der LDAP-Konfiguration
if ($ldap_aktiviert != 1) {
	$_SESSION['username'] = "Fieser"; //Adminnutzer gemäß rechte.php
}
/*
function getPdfPageCount($pdfFilePath) {
    try {
        $imagick = new Imagick($pdfFilePath);
        $pageCount = $imagick->getNumberImages();
        return $pageCount;
    } catch (Exception $e) {
        return 'Fehler: ' . $e->getMessage();
    }
}
*/
//ob_end_flush();
?>