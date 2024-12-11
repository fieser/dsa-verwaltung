<?php

set_time_limit(0);
ignore_user_abort(true); //auch wenn der Nutzer die Sete verlässt, läuft das Skript weiter

if (!isset($pfad_workdir)) {
	$pfad_workdir = "/var/www/html/verwaltung/";
}

include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();




	
include($pfad_workdir."config.php");



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



$jgst_1 = intval($schuljahr[2].$schuljahr[3]);
$jgst_1 = trim($jgst_1);
$jgst_2 = trim($jgst_1 - 1);
$jgst_3 = trim($jgst_1 - 2);
$jgst_4 = trim($jgst_1 - 3);








// Schüler aus Klassen importieren:

$schueler_neu = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_schueler.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_www->exec("TRUNCATE TABLE `edoo_schueler`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_dsa_schueler.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $id_edoo = $teilung[0];
			  $nachname = trim($teilung[1]);
			  $vorname = $teilung[2];
			  $geburtsdatum = trim($teilung[3]);
			  $strasse = $teilung[4];
			  $hausnummer = $teilung[5];
			  $plz = $teilung[6];
			  $wohnort = $teilung[7];
			  $telefon1 = $teilung[8];
			  $telefon2 = $teilung[9];
			  $mail = $teilung[10];
			  $create_user = $teilung[11];
			  $create_date = $teilung[12];
			  $update_user = $teilung[13];
			  $update_date = $teilung[14];
			  $austritt = $teilung[15];
			  $bildungsgang = $teilung[16];
			  $eintritt = $teilung[17];
			  $geschlecht = $teilung[18];
			  
			  				$geschlecht = str_replace("1099_1","M",$geschlecht);
				$geschlecht = str_replace("1099_2","W",$geschlecht);
				$geschlecht = str_replace("1099_3","D",$geschlecht);
				$geschlecht = str_replace("1099_4","O",$geschlecht);
			  
				
			$geburtsort = $teilung[19];
			$kom_typ = $teilung[20];
			
			$klasse = $teilung[21];
			$beruf = $teilung[22];
			$anschrift_typ = trim($teilung[28]);
			

			
			
//if ($anschrift_typ == "1016_08" OR $anschrift_typ == "1016_03") {
if ($anschrift_typ == "1016_08") {
			  
			  				// Datensatz neu in DB schreiben:

					if ($db_www->exec("INSERT INTO `edoo_schueler`
								   SET
									`nachname` = '$nachname',
									`vorname` = '$vorname',
									`id_edoo` = '$id_edoo',
									`geburtsdatum` = '$geburtsdatum',
									`strasse` = '$strasse',
									`hausnummer` = '$hausnummer',
									`plz` = '$plz',
									`wohnort` = '$wohnort',
									`geburtsort` = '$geburtsort',
									`geschlecht` = '$geschlecht',
									`austritt` = '$austritt',
									`eintritt` = '$eintritt',
									`bildungsgang` = '$bildungsgang',
									`klasse` = '$klasse',
									`beruf` = '$beruf',
									`create_user` = '$create_user',
									`create_date` = '$create_date',
									`update_user` = '$update_user',
									`update_date` = '$update_date',
									`telefon1` = '$telefon1',
									`telefon2` = '$telefon2',
									`mail` = '$mail'")) {
					
					$last_id = $db_www->lastInsertId();
					
					
					$schueler_neu = ($schueler_neu + 1);
					
						
					}
}
			}	

		fclose($file_handle);

echo $schueler_neu." Schülerdatensätze importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}





//Bewerber aus edoo.sys importieren

$bewerber_neu = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_bewerber.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_www->exec("TRUNCATE TABLE `edoo_bewerber`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_dsa_bewerber.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $nachname = $teilung[0];
			  $vorname = $teilung[1];
			  $geburtsdatum = $teilung[2];
			  $geburtsort = $teilung[3];
			  $geburtsland = $teilung[4];
			  $staatsangehoerigkeit = $teilung[5];
			  $herkunftsland = $teilung[6];
			  $zuzugsdatum = $teilung[7];
			  $geschlecht = $teilung[8];
			  $religionszugehoerigkeit = $teilung[9];
			  $religion_ethik_id = $teilung[10];
			  $abschluss	 = $teilung[11];
			  
				$abschluss = str_replace("5020_15","S1",$abschluss);
				
				$abschluss = str_replace("5020_17","GH",$abschluss);
				$abschluss = str_replace("5020_18","HO",$abschluss);
				$abschluss = str_replace("5020_19","NV",$abschluss);
				$abschluss = str_replace("5020_3","AO",$abschluss);
				$abschluss = str_replace("5020_4","FÖ",$abschluss);
				$abschluss = str_replace("5020_7","HS",$abschluss);
				$abschluss = str_replace("5020_43","FHST",$abschluss);
				$abschluss = str_replace("5020_44","FHSPT",$abschluss);
				$abschluss = str_replace("5020_1","OB",$abschluss);
				
			  
			  $wl_berufsabschluss_id = $teilung[12];
			  $entscheidung = $teilung[13];
			  
			  $create_user = $teilung[14];
			  $create_date = $teilung[15];
			  $update_user = $teilung[16];
			  $update_date = $teilung[17];
			  
			  $strasse = $teilung[18];
			  $hausnummer = $teilung[19];
			  $plz = $teilung[20];
			  $wohnort = $teilung[21];
			  $bildungsgang = $teilung[22];
			  $anschriftstyp = $teilung[23];
			  $personentyp = $teilung[24];
			  $reiter = trim($teilung[25]);
			  $schueler_stamm_id = trim($teilung[26]);
			  $status_uebernahme = trim($teilung[27]);
			  
			  
				$geschlecht = str_replace("1099_1","M",$geschlecht);
				$geschlecht = str_replace("1099_2","W",$geschlecht);
				$geschlecht = str_replace("1099_3","D",$geschlecht);
				$geschlecht = str_replace("1099_4","O",$geschlecht);
				
				$religionszugehoerigkeit = str_replace("1037_1","RK",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("RK0","FR",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_2","EV",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_3","JU",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_4","ISL",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_5","SO",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_6","KE",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_7","AL",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_9","ME",$religionszugehoerigkeit);
				$religionszugehoerigkeit = str_replace("1037_10","FR",$religionszugehoerigkeit);
				
				
				
					$select_gbl = $db_temp->query("SELECT anzeigeform FROM staaten WHERE schluessel = '$geburtsland'");	
					
					foreach($select_gbl as $gbl) {
						$geburtsland = $gbl['anzeigeform'];
					}			
					
					$select_sta = $db_temp->query("SELECT anzeigeform FROM staaten WHERE schluessel = '$staatsangehoerigkeit'");	
					
					foreach($select_sta as $sta) {
						$staatsangehoerigkeit = $sta['anzeigeform'];
					}	

					$select_hk = $db_temp->query("SELECT anzeigeform FROM staaten WHERE schluessel = '$herkunftsland'");	
					
					foreach($select_hk as $hk) {
						$herkunftsland = $hk['anzeigeform'];
					}			
							
					//Nur, wenn die Anschrift, die eigenen Anschrift ist:
					if ($reiter == "3") {
		
			  				// Datensatz neu in DB schreiben:

					if ($db_www->exec("INSERT INTO `edoo_bewerber`
								   SET
									`nachname` = '$nachname',
									`vorname` = '$vorname',
									`geburtsdatum` = '$geburtsdatum',
									`geburtsort` = '$geburtsort',
									`geburtsland` = '$geburtsland',
									`staatsangehoerigkeit` = '$staatsangehoerigkeit',
									`herkunftsland` = '$herkunftsland',
									`geschlecht` = '$geschlecht',
									`religionszugehoerigkeit` = '$religionszugehoerigkeit',
									`strasse` = '$strasse',
									`hausnummer` = '$hausnummer',
									`plz` = '$plz',
									`wohnort` = '$wohnort',
									`abschluss` = '$abschluss',
									
									`telefon1` = '',
									`telefon2` = '',
									`mail` = '',
									`bildungsgang` = '$bildungsgang',
									
									`sorge1_strasse` = '',
									`sorge1_hausnummer` = '',
									`sorge1_plz` = '',
									`sorge1_wohnort` = '',
									`sorge1_personentyp` = '',
									
									`sorge2_strasse` = '',
									`sorge2_hausnummer` = '',
									`sorge2_plz` = '',
									`sorge2_wohnort` = '',
									`sorge2_personentyp` = '',
									
									`entscheidung` = '$entscheidung',
									`status_uebernahme` = '$status_uebernahme',
									`create_user` = '$create_user',
									`create_date` = '$create_date',
									`update_user` = '$update_user',
									`update_date` = '$update_date',
									`zuzugsdatum` = '$zuzugsdatum'")) {
					
					$last_id = $db_www->lastInsertId();
					//echo $line2."<br>";
					
					$bewerber_neu = ($bewerber_neu + 1);
					
						
					}
		}
			}	

		fclose($file_handle);

echo "<br>".$bewerber_neu." Bewerberdatensätze importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}






//Bewerber um Sorgeberechtigte ergänzen:

$bewerber_neu2 = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_bewerber.csv")) {
			

		 
		$file_an = $pfad_workdir."daten/svp_dsa_bewerber.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $nachname = $teilung[0];
			  $vorname = $teilung[1];
			  $geburtsdatum = $teilung[2];

			  
			  $sorge_strasse = $teilung[18];
			  $sorge_hausnummer = $teilung[19];
			  $sorge_plz = $teilung[20];
			  $sorge_wohnort = $teilung[21];
			  $sorge_personentyp = $teilung[24];
			  $reiter = trim($teilung[25]);
			  

				
				
			
							
					//Nur, wenn die Anschrift, die eigenen Anschrift ist:
					if ($reiter == "1") {
		
			  		
										if ($db_www->exec("UPDATE `edoo_bewerber`
									   SET
									   `sorge1_strasse` = '$sorge_strasse',
									   `sorge1_hausnummer` = '$sorge_hausnummer',
									   `sorge1_plz` = '$sorge_plz',
									   `sorge1_wohnort` = '$sorge_wohnort',
										`sorge1_personentyp` = '$sorge_personentyp' WHERE `nachname` = '$nachname' AND `geburtsdatum` = '$geburtsdatum'")) { 
										
											$kommunikation_neu = ($kommunikation_neu + 1);
										}
					
					
					$bewerber_neu2 = ($bewerber_neu2 + 1);
					
						
					}
					
					if ($reiter == "2") {
		
			  		
										if ($db_www->exec("UPDATE `edoo_bewerber`
									   SET
									   `sorge2_strasse` = '$sorge_strasse',
									   `sorge2_hausnummer` = '$sorge_hausnummer',
									   `sorge2_plz` = '$sorge_plz',
									   `sorge2_wohnort` = '$sorge_wohnort',
										`sorge2_personentyp` = '$sorge_personentyp' WHERE `nachname` = '$nachname' AND `geburtsdatum` = '$geburtsdatum'")) { 
										
											$kommunikation_neu = ($kommunikation_neu + 1);
										}
					
					
					$bewerber_neu2 = ($bewerber_neu2 + 1);
					
						
					}
			}		

		fclose($file_handle);

echo "<br>".$bewerber_neu2." Bewerberdatensätze mit Sorgeberechtigten ergänzt."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}










// Bewerberziele importieren:

$bildungsgang_neu = 0;

if (file_exists($pfad_workdir."daten/svp_bewerbungsziel.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `edoo_bewerbungsziel`")) {
		
						echo "<font color='orange'>Alle Bewerbungsziele gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_bewerbungsziel.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $id_edoo = $teilung[0];
			  $kurzform = $teilung[1];
			  $anzeigeform = $teilung[2];
			  $id_bildungsgang = $teilung[3];

			  
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `edoo_bewerbungsziel`
								   SET
									`id_edoo` = '$id_edoo',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`id_bildungsgang` = '$id_bildungsgang'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					
					$bildungsgang_neu = ($bildungsgang_neu + 1);
					
						
					}

			}	

		fclose($file_handle);

echo "<br>".$bildungsgang_neu." Bewerberziele importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}


//Email und Telefon für Bewerber ergänzen:


$kommunikation_neu = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_bewerber_kommunikation.csv")) {
			


		 
		$file_an = $pfad_workdir."daten/svp_dsa_bewerber_kommunikation.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $nachname = $teilung[0];
			  $vorname = $teilung[1];
			  $geburtsdatum = $teilung[2];
			  $geburtsort = $teilung[3];
			  
			  $typ = $teilung[4];
			  $adresse = $teilung[5];
			  

			  
			  
				if ($typ == "1113_04") { //wenn Email
				  
								// Datensatz ergänzen:

					if ($db_www->exec("UPDATE `edoo_bewerber`
									   SET
										`mail` = '$adresse' WHERE `nachname` = '$nachname' AND `geburtsdatum` = '$geburtsdatum'")) { 
										
											$kommunikation_neu = ($kommunikation_neu + 1);
										}
				}
				
				if ($typ == "1113_01") { //wenn Telefon 1
				  
								// Datensatz ergänzen:

					if ($db_www->exec("UPDATE `edoo_bewerber`
									   SET
										`telefon1` = '$adresse' WHERE `nachname` = '$nachname' AND `geburtsdatum` = '$geburtsdatum'")) { 
										
											$kommunikation_neu = ($kommunikation_neu + 1);
										}
				}
				
				if ($typ == "1113_02") { //wenn Telefon 2
				  
								// Datensatz:

					if ($db_www->exec("UPDATE `edoo_bewerber`
									   SET
										`telefon2` = '$adresse' WHERE `nachname` = '$nachname' AND `geburtsdatum` = '$geburtsdatum'")) { 
										
											$kommunikation_neu = ($kommunikation_neu + 1);
										}
				}

			}	

		fclose($file_handle);

echo "<br>".$kommunikation_neu." Kommunikantionsadressen für Bewerber ergänzt."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}




//Fremdsprachen für Bewerber ergänzen:


$fremdsprechen_neu = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_bewerber_fremdsprachen.csv")) {
			
		// Tabelle leeren:
				
				
					if ($db_www->exec("TRUNCATE TABLE `edoo_fremdsprachen`")) {
		
						echo "<font color='orange'>Alle Fremdsprachen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_dsa_bewerber_fremdsprachen.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  $line2 = str_replace("1104_1001","Klasse 1",$line2);
			  $line2 = str_replace("1104_2001","Klasse 2",$line2);
			  $line2 = str_replace("1104_3001","Klasse 3",$line2);
			  $line2 = str_replace("1104_4001","Klasse 4",$line2);
			  $line2 = str_replace("1104_5001","Klasse 5",$line2);
			  $line2 = str_replace("1104_6","Klasse 6",$line2);
			  $line2 = str_replace("1104_7","Klasse 7",$line2);
			  $line2 = str_replace("1104_8","Klasse 8",$line2);
			  $line2 = str_replace("1104_9","Klasse 9",$line2);
			  $line2 = str_replace("1104_10","Klasse 10",$line2);
			  $line2 = str_replace("1104_11001","Klasse 11",$line2);
			  $line2 = str_replace("1104_12001","Klasse 12",$line2);
			  $line2 = str_replace("1104_13001","Klasse 13",$line2);
			  
			  $line2 = str_replace("1073_7","Englisch",$line2);
			  $line2 = str_replace("1073_25","Russisch",$line2);
			  $line2 = str_replace("1073_8","Französisch",$line2);
			  $line2 = str_replace("1073_11","Arabbisch",$line2);
			  $line2 = str_replace("1073_13","Chinesisch",$line2);
			  $line2 = str_replace("1073_17","Italienisch",$line2);
			  $line2 = str_replace("1073_23","Portugiesisch",$line2);
			  $line2 = str_replace("1073_30","Spanisch",$line2);
			  $line2 = str_replace("1073_31","Türkisch",$line2);
			  $line2 = str_replace("1073_9","Latein",$line2);

			  
			  
			  $teilung = explode(";", $line2);
			  
			  $nachname = $teilung[0];
			  $vorname = $teilung[1];
			  $geburtsdatum = $teilung[2];
			  $geburtsort = $teilung[3];
			  
			  $fs = $teilung[4];
			  $fs_von = $teilung[5];
			  $fs_bis = $teilung[6];
			  
				$line2 = str_replace("1104_5001","Klasse 5",$line2);
			  
			  

			  
			  				if ($db_www->exec("INSERT INTO `edoo_fremdsprachen`
								   SET
									`nachname` = '$nachname',
									`vorname` = '$vorname',
									`geburtsdatum` = '$geburtsdatum',
									`fs` = '$fs',
									`fs_von` = '$fs_von',
									`fs_bis` = '$fs_bis'")) {
					
					$last_id = $db_www->lastInsertId();
					
					
					$fremdsprechen_neu = ($fremdsprechen_neu + 1);
					
						
					}

			}	

		fclose($file_handle);

echo "<br>".$fremdsprechen_neu." Fremdsprachen für Bewerber importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		


//Betriebs-ID für BS-SuS importieren:


$betrieb_id_neu = 0;

if (file_exists($pfad_workdir."daten/svp_dsa_schueler_m_betrieb.csv")) {
			
		// Tabelle leeren:
				
				
					if ($db_www->exec("TRUNCATE TABLE `edoo_schueler_betrieb`")) {
		
						echo "<font color='orange'>Alle Bietriebs-IDs zu Azubis gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_dsa_schueler_m_betrieb.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  
			  
			  
			  $teilung = explode(";", $line2);
			  
			  $id_schueler = $teilung[0];
			  $nachname = $teilung[1];
			  $vorname = $teilung[2];
			  $geburtsdatum = $teilung[3];
			  $id_betrieb = $teilung[4];


			  
			  				if ($db_www->exec("INSERT INTO `edoo_schueler_betrieb`
								   SET
									`nachname` = '$nachname',
									`vorname` = '$vorname',
									`geburtsdatum` = '$geburtsdatum',
									`id_schueler` = '$id_schueler',
									`id_betrieb` = '$id_betrieb'")) {
					
					$last_id = $db_www->lastInsertId();
					
					
					$betrieb_id_neu = ($betrieb_id_neu + 1);
					
						
					}

			}	

		fclose($file_handle);

echo "<br>".$betrieb_id_neu." Betriebs-IDs für BS-SuS importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}


//Schüler-Klassen-Kombi importieren:


$sus_klasse_id_neu = 0;

if (file_exists($pfad_workdir."daten/svp_schueler_klasse.csv")) {
			
		// Tabelle leeren:
				
				
					if ($db_www->exec("TRUNCATE TABLE `edoo_schueler_klasse`")) {
		
						echo "<font color='orange'>Alle Schüler-Klasse-Kombis gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_schueler_klasse.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  
			  
			  
			  $teilung = explode(";", $line2);
			  
			  $id_schueler = $teilung[0];
			  $klasse = $teilung[1];
			  $klassengruppe = $teilung[2];
			  $schuljahr = trim($teilung[3]);
			 


			  
			  				if ($db_www->exec("INSERT INTO `edoo_schueler_klasse`
								   SET
									`schueler_id` = '$id_schueler',
									`klasse` = '$klasse',
									`klassengruppe` = '$klassengruppe',
									`schuljahr` = '$schuljahr'")) {
					
					$last_id = $db_www->lastInsertId();
					
					
					$sus_klasse_id_neu = ($sus_klasse_id_neu + 1);
					
						
					}

			}	

		fclose($file_handle);

echo "<br>".$sus_klasse_id_neu." Schüler-Klassen-Kombis importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}





?>

<p>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
?>