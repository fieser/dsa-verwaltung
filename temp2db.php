<?php
set_time_limit(0);
ignore_user_abort(true); //auch wenn der Nutzer die Sete verlässt, läuft das Skript weiter

/*
include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();

*/

if (!isset($pfad_workdir)) {
	$pfad_workdir = "/var/www/html/verwaltung/";
}


include($pfad_workdir."config.php");


// Überprüfen, ob die Verbindungen erfolgreich waren
if ($db->connect_error || $db_temp->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error . " / " . $db_temp->connect_error);
}

// SELECT-Abfrage auf $db_temp ausführen
$select_bew_temp = $db_temp->query("SELECT * FROM dsa_bewerberdaten");

// Durch die Ergebnisse der SELECT-Abfrage iterieren
	foreach($select_bew_temp as $bew) {
    // Überprüfen, ob der Datensatz bereits in $db existiert
    $check_existing_query = $db->query("SELECT id FROM dsa_bewerberdaten WHERE md5 = '" . $bew['md5'] . "'");
    $treffer_exist = $check_existing_query->rowCount();
	
    if ($treffer_exist == 0) {
        // Der Datensatz existiert noch nicht in $db, also kopieren
		$md5 = $bew['md5'];
		$code = $bew['code'];
		
		$nachname = $bew['nachname'];
		$vorname = $bew['vorname'];
		$geschlecht = $bew['geschlecht'];
		$geburtsdatum = $bew['geburtsdatum'];
		$geburtsort = $bew['geburtsort'];
		$geburtsland = $bew['geburtsland'];
		$zuzug = $bew['zuzug'];
		$staatsangehoerigkeit = $bew['staatsangehoerigkeit'];
		$muttersprache = $bew['muttersprache'];
		$religion = $bew['religion'];
		$herkuftsland = $bew['herkuftsland'];
		$strasse = $bew['strasse'];
		$plz = $bew['plz'];
		$wohnort = $bew['wohnort'];
		$hausnummer = $bew['hausnummer'];
		$telefon1 = $bew['telefon1'];
		$telefon2 = $bew['telefon2'];
		$mail = $bew['mail'];
		$schulart = $bew['schulart'];
		$schulname = $bew['schulname'];
		$jahrgang = $bew['jahrgang'];
		$abschluss = $bew['abschluss'];

		$sorge1_vorname = $bew['sorge1_vorname'];
		$sorge1_nachname = $bew['sorge1_nachname'];
		$sorge1_anrede = $bew['sorge1_anrede'];
		$sorge1_art = $bew['sorge1_art'];
		$sorge1_strasse = $bew['sorge1_strasse'];
		$sorge1_hausnummer = $bew['sorge1_hausnummer'];
		$sorge1_plz = $bew['sorge1_plz'];
		$sorge1_wohnort = $bew['sorge1_wohnort'];
		$sorge1_telefon1 = $bew['sorge1_telefon1'];
		$sorge1_telefon2 = $bew['sorge1_telefon2'];
		$sorge1_mail = $bew['sorge1_mail'];

		$sorge2_vorname = $bew['sorge2_vorname'];
		$sorge2_nachname = $bew['sorge2_nachname'];
		$sorge2_anrede = $bew['sorge2_anrede'];
		$sorge2_art = $bew['sorge2_art'];
		$sorge2_strasse = $bew['sorge2_strasse'];
		$sorge2_hausnummer = $bew['sorge2_hausnummer'];
		$sorge2_plz = $bew['sorge2_plz'];
		$sorge2_wohnort = $bew['sorge2_wohnort'];
		$sorge2_telefon1 = $bew['sorge2_telefon1'];
		$sorge2_telefon2 = $bew['sorge2_telefon2'];
		$sorge2_mail = $bew['sorge2_mail'];
		
		if ($db->exec("INSERT INTO `dsa_bewerberdaten`
								   SET
									`code` = '$code',
									`md5` = '$md5',
									`status` = 'gesendet',
									`nachname` = '$nachname',
									`vorname` = '$vorname',
									`geschlecht` = '$geschlecht',
									`geburtsdatum` = '$geburtsdatum',
									`geburtsort` = '$geburtsort',
									`geburtsland` = '$geburtsland',
									`zuzug` = '$zuzug',
									`staatsangehoerigkeit` = '$staatsangehoerigkeit',
									`muttersprache` = '$muttersprache',
									`religion` = '$religion',
									`herkuftsland` = '$herkuftsland',
									`strasse` = '$strasse',
									`plz` = '$plz',
									`wohnort` = '$wohnort',
									`hausnummer` = '$hausnummer',
									`telefon1` = '$telefon1',
									`telefon2` = '$telefon2',
									`mail` = '$mail',
									`schulart` = '$schulart',
									`schulname` = '$schulname',
									`jahrgang` = '$jahrgang',
									`abschluss` = '$abschluss',
									
									`sorge1_vorname` = '$sorge1_vorname',
									`sorge1_nachname` = '$sorge1_nachname',
									`sorge1_anrede` = '$sorge1_anrede',
									`sorge1_art` = '$sorge1_art',
									`sorge1_strasse` = '$sorge1_strasse',
									`sorge1_hausnummer` = '$sorge1_hausnummer',
									`sorge1_plz` = '$sorge1_plz',
									`sorge1_wohnort` = '$sorge1_wohnort',
									`sorge1_telefon1` = '$sorge1_telefon1',
									`sorge1_telefon2` = '$sorge1_telefon2',
									`sorge1_mail` = '$sorge1_mail',
									
									`sorge2_vorname` = '$sorge2_vorname',
									`sorge2_nachname` = '$sorge2_nachname',
									`sorge2_anrede` = '$sorge2_anrede',
									`sorge2_art` = '$sorge2_art',
									`sorge2_strasse` = '$sorge2_strasse',
									`sorge2_hausnummer` = '$sorge2_hausnummer',
									`sorge2_plz` = '$sorge2_plz',
									`sorge2_wohnort` = '$sorge2_wohnort',
									`sorge2_mail` = '$sorge2_mail',
									`sorge2_telefon1` = '$sorge2_telefon1',
									`sorge2_telefon2` = '$sorge2_telefon2',
									`dok_neu` = '',
									`papierkorb` = ''")) {
										
										$last_id = $db->lastInsertId();
										
									// Erfolgreich kopiert, jetzt aus $db_temp löschen
									   $delete_query = $db_temp->query("DELETE FROM dsa_bewerberdaten WHERE id = " . $bew['id']);
										
										if (!$delete_query) {
											// Fehler beim Löschen aus $db_temp
											echo "Fehler beim Löschen.";
										}
																	
																} else {
										// Fehler beim Einfügen in $db
										echo "Fehler beim Einfügen.";
									}
		
        

    }
	
	// BILDUNGSGANG:



// SELECT-Abfrage auf $db_temp ausführen
$select_bil_temp = $db_temp->query("SELECT * FROM dsa_bildungsgang");

// Durch die Ergebnisse der SELECT-Abfrage iterieren
	foreach($select_bil_temp as $bil) {
    // Überprüfen, ob der Datensatz bereits in $db existiert
    $check_existing_bil = $db->query("SELECT * FROM dsa_bildungsgang WHERE md5 = '$md5'");
    $treffer_exist_bil = $check_existing_bil->rowCount();
	
    if ($treffer_exist_bil == 0) {
        // Der Datensatz existiert noch nicht in $db, also kopieren
		$md5 = $bil['md5'];
		$prio = $bil['prio'];
	
		$schulform = $bil['schulform'];
	$prio_akt = $bil['prio_akt'];
	$dauer = $bil['dauer'];
	$beginn = $bil['beginn'];
	$ende = $bil['ende'];
	$beruf = $bil['beruf'];
	$beruf_anz = $bil['beruf_anz'];
	$beruf2 = $bil['beruf2'];
	$betrieb = $bil['betrieb'];
	$betrieb2 = $bil['betrieb2'];
	$betrieb_plz = $bil['betrieb_plz'];
	$betrieb_ort = $bil['betrieb_ort'];
	$betrieb_strasse = $bil['betrieb_strasse'];
	$betrieb_hausnummer = $bil['betrieb_hausnummer'];
	$betrieb_telefon = $bil['betrieb_telefon'];
	$betrieb_mail = $bil['betrieb_mail'];
	$ausbilder_nachname = $bil['ausbilder_nachname'];
	$ausbilder_vorname = $bil['ausbilder_vorname'];
	$ausbilder_telefon = $bil['ausbilder_telefon'];
	$ausbilder_telefon2 = $bil['ausbilder_telefon2'];
	$ausbilder_mail = $bil['ausbilder_mail'];
	
	$bgy_sp1 = $bil['bgy_sp1'];
	$bgy_sp2 = $bil['bgy_sp2'];
	$bgy_sp3 = $bil['bgy_sp3'];
	
	$fs1 = $bil['fs1'];
	$fs1_von = $bil['fs1_von'];
	$fs1_bis = $bil['fs1_bis'];
	
	$fs2 = $bil['fs2'];
	$fs2_von = $bil['fs2_von'];
	$fs2_bis = $bil['fs2_bis'];
	
	$fs3 = $bil['fs3'];
	$fs3_von = $bil['fs3_von'];
	$fs3_bis = $bil['fs3_bis'];
	
	$time = $bil['time'];
	
	if (!isset($last_id)) {
		$last_id = 0;
	}

		
		if ($db->exec("INSERT INTO `dsa_bildungsgang`
								   SET
									`id_dsa_bewerberdaten` = '$last_id',
									`md5` = '$md5',
									`prio` = '$prio',
									`time` = '$time',
									`schulform` = '$schulform',
									`dauer` = '$dauer',
									`beginn` = '$beginn',
									`ende` = '$ende',
									`beruf` = '$beruf',
									`beruf_anz` = '$beruf_anz',
									`beruf2` = '$beruf2',
									`betrieb` = '$betrieb',
									`betrieb2` = '$betrieb2',
									`betrieb_plz` = '$betrieb_plz',
									`betrieb_ort` = '$betrieb_ort',
									`betrieb_strasse` = '$betrieb_strasse',
									`betrieb_hausnummer` = '$betrieb_hausnummer',
									`betrieb_telefon` = '$betrieb_telefon',
									`betrieb_mail` = '$betrieb_mail',
									`ausbilder_nachname` = '$ausbilder_nachname',
									`ausbilder_vorname` = '$ausbilder_vorname',
									`ausbilder_telefon` = '$ausbilder_telefon',
									`ausbilder_telefon2` = '$ausbilder_telefon2',
									`ausbilder_mail` = '$ausbilder_mail',
									`bgy_sp1` = '$bgy_sp1',
									`bgy_sp2` = '$bgy_sp2',
									`bgy_sp3` = '$bgy_sp3',
									`fs1` = '$fs1',
									`fs1_von` = '$fs1_von',
									`fs1_bis` = '$fs1_bis',
									`fs2` = '$fs2',
									`fs2_von` = '$fs2_von',
									`fs2_bis` = '$fs2_bis',
									`fs3` = '$fs3',
									`fs3_von` = '$fs3_von',
									`fs3_bis` = '$fs3_bis'")) {								
									// Erfolgreich kopiert, jetzt aus $db_temp löschen
									   $delete_query = $db_temp->query("DELETE FROM dsa_bildungsgang WHERE id = " . $bil['id']);
										
										if (!$delete_query) {
											// Fehler beim Löschen aus $db_temp
											echo "Fehler beim Löschen.";
										}
																	
																} else {
										// Fehler beim Einfügen in $db
										echo "Fehler beim Einfügen.";
									}
		
        

    }
}

}

//ID Bewerberdaten bei gleicher md5 in Tabelle bildungsgang schreiben:
$select_bewk = $db->query("SELECT id, md5 FROM dsa_bewerberdaten");

foreach($select_bewk as $bewk) {
	$bewk_id = $bewk['id'];
	$bewk_md5 = $bewk['md5'];

						// Datensatz aendern
			if ($db->exec("UPDATE `dsa_bildungsgang`
						   SET
							`id_dsa_bewerberdaten` = '$bewk_id'	 WHERE `md5` = '$bewk_md5'")) {
			//$last = "LAST_INSERT_ID(UserID)";

		
							}
}

if ($upload_documents == 1) {
	include($pfad_workdir."download_and_decrypt.php");
}


/*
?>

<p>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
	*/
?>