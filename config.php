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

	$schuljahr = $_SESSION['schuljahr'];
	
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
 
	
$schuljahre["24-25"]["jahr"] = "2024-2025";
	$schuljahre["24-25"]["erster_tag"] = "12.08.2024";
	$schuljahre["24-25"]["letzter_tag"] = "04.07.2025";
	$schuljahre["24-25"]["start_periode"] = "01.11.2023";
	$schuljahre["24-25"]["stichtag_statistik"] = "02.10.2025";
	$schuljahre["24-25"]["sichtbar_lk"] = 1; //Bei 0 erscheint das SJ für Lehrkräfte nicht im Drop
	$schuljahre["24-25"]["periode"] = "ab November 2023";
	
$schuljahre["25-26"]["jahr"] = "2025-2026";
	$schuljahre["25-26"]["erster_tag"] = "";
	$schuljahre["25-26"]["letzter_tag"] = "";
	$schuljahre["24-25"]["start_periode"] = "01.10.2024";
	$schuljahre["25-26"]["stichtag_statistik"] = "";
	$schuljahre["25-26"]["sichtbar_lk"] = 1; //Bei 0 erscheint das SJ für Lehrkräfte nicht im Drop
	$schuljahre["25-26"]["periode"] = "ab Oktober 2024";	



//Aktuelles Schuljahr: 

	$schuljahr = $_SESSION['schuljahr'];
	foreach($schuljahre as $perioden) {
		if ($perioden['jahr'] == $schuljahr) {
	$periode = $perioden['periode'];
		}
	}



//Schuldaten
$_SESSION['schule_name_zeile1'] = "Berufsbildende Schule 1";
$_SESSION['schule_name_zeile2'] = "- Gewerbe und Technik -";

$_SESSION['schule_kurz'] = 'bbs1'; //Bitte nur Kleinbuchstaben

$_SESSION['schule_strasse_nr'] = "Am Judensand 12";
$_SESSION['schule_plz_ort'] = "55122 Mainz";

$_SESSION['schule_tel'] = "06131-90603-0";
$_SESSION['schule_fax'] = "06131-90603-99";

$_SESSION['schule_mail'] = "sekretariat@bbs1-mainz.de";
$_SESSION['schule_url'] = "https://www.bbs1-mainz.de";
 
 

//Pfade:
$url = "https://service.bbs1-mainz.de/verwaltung/"; // BITTE mit / abschließen!
$website = "https://www.bbs1-mainz.com";
$workdir = "/verwaltung/"; // BITTE mit / beginnen und abschließen!
$pfad_workdir = "/var/www/html/verwaltung/";

	

 
// Verbindung zur Datenbank aufbauen.
include "/var/www/verbinden.php";
include "/var/www/verbinden_temp.php";
include "/var/www/verbinden_www.php";


//Einstellungen:
$xls_download = 1;
$fremdsprachen_export_deaktivieren = 0;
$debug = 0; //Anzeige zusätzlicher Infos zur Fehlersuche
$mail_status = 1; //Anzeige Spalte E-Mail in Liste
$upload_documents = 1; //Die Einstellung $upload_dokumente o.ä. muss auch auf dem öffentlichen Server entsprechend in config.php eingestellt sein!


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



	

//ob_end_flush();
?>