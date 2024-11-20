<?php



include($pfad_workdir."kopf.php");
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








// Betiebe importieren:

$betriebe_neu = 0;

if (file_exists($pfad_workdir."daten/svp_betrieb.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `betriebe`")) {
		
						

					}

		 
		$file_an = $pfad_workdir."daten/svp_betrieb.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("\"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $id_edoo = $teilung[0];
			  $kuerzel = $teilung[1];
			  $name1 = $teilung[2];
			  $name2 = $teilung[3];
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `betriebe`
								   SET
									`id_edoo` = '$id_edoo',
									`kuerzel` = '$kuerzel',
									`name1` = '$name1',
									`name2` = '$name2'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='blue'>Datensatz <b>".$name1." ".$name2."</b> wurde neu angelegt!<br>";
					$betriebe_neu = ($betriebe_neu + 1);
					
						
					}

			}	

		fclose($file_handle);
		
		echo $betriebe_neu." Betriebe importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}








// Berufe importieren:

$berufe_neu = 0;

if (file_exists($pfad_workdir."daten/svp_wl_ausbildungsberuf.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `berufe`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_wl_ausbildungsberuf.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[0];
			  $kurzform = $teilung[1];
			  $anzeigeform = $teilung[2];
			  $langform = $teilung[3];
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `berufe`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='blue'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$berufe_neu = ($berufe_neu + 1);
					
						
					}

			}	

		fclose($file_handle);
		
		echo $berufe_neu." Berufe importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}

//Ausbilungsberuf mit Betrieben:
// Berufe importieren:

$berufe_bet = 0;

if (file_exists($pfad_workdir."daten/svp_betrieb_berufe.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `berufe_angebot_betriebe`")) {
		
						echo "<font color='orange'>Alle Betrieb-Beruf-Kombis gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_betrieb_berufe.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $betrieb_kuerzel = $teilung[1];
			  $betrieb_name1 = $teilung[2];
			  $betrieb_name2 = $teilung[3];
			  $schluessel = $teilung[4];
			  $langform = $teilung[5];
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `berufe_angebot_betriebe`
								   SET
									`betrieb_kuerzel` = '$betrieb_kuerzel',
									`betrieb_name1` = '$betrieb_name1',
									`betrieb_name2` = '$betrieb_name2',
									`schluessel` = '$schluessel',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='blue'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$berufe_bet = ($berufe_bet + 1);
					
						
					}

			}	

		fclose($file_handle);
		
		echo $berufe_bet." Berufe mit Betrieben importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		
		
// Berufsangebot aus edoo.sys importieren importieren:

     //Dazu in edoo.sys alle SuS mit ihrem Beruf (Schlüssel) in eine CSV exportieren. Es darf Dopplungen geben!

$beruf_schule = 0;

if (file_exists($pfad_workdir."daten/svp_berufe_ist.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `berufe_angebot`")) {
		
						echo "<font color='orange'>Alle Berufe der Schule gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_berufe_ist.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  $line2 = str_replace("1027_", "", $line2); 
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[0];
			  $langform = $teilung[2];
			  $schueler = $teilung[3];

					

						// Datensatz neu in DB schreiben:

						if ($db_temp->exec("INSERT INTO `berufe_angebot`
									   SET
									   `langform` = '$langform',
									   `schueler` = '$schueler',
										`schluessel` = '$schluessel'")) {
						
						$last_id = $db_temp->lastInsertId();
						
						//echo "<font color='blue'>Datensatz <b>".$schluessel."</b> wurde neu angelegt!<br>";
						$beruf_schule = ($beruf_schule + 1);
						
							
						}
					

			}	

		fclose($file_handle);
		
		echo $beruf_schule." Berufe der Schule importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		
		
		
// Staaten importieren:

$neu_staaten = 0;

if (file_exists($pfad_workdir."daten/svp_staaten.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `staaten`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_staaten.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[0];
			  $kurzform = $teilung[1];
			  $anzeigeform = $teilung[2];
			  $langform = $teilung[3];
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `staaten`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_staaten = ($neu_staaten + 1);
					
						
					}

			}	

		fclose($file_handle);
		
		echo $neu_staaten." Staaten importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		
		
// Sprachen importieren:

$neu_sprachen = 0;

if (file_exists($pfad_workdir."daten/svp_sprachen.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `sprachen`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_sprachen.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[0];
			  $kurzform = $teilung[1];
			  $anzeigeform = $teilung[2];
			  $langform = $teilung[3];
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `sprachen`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_sprachen = ($neu_sprachen + 1);
					
						
					}

			}	

		fclose($file_handle);
		
		echo $neu_sprachen." Sprachen importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		
// Vorbildung importieren:

$neu_vorbildung = 0;

if (file_exists($pfad_workdir."daten/svp_wl_vorbildung.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `vorbildung`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_wl_vorbildung.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[1];
			  $kurzform = $teilung[2];
			  $anzeigeform = $teilung[3];
			  $langform = $teilung[4];
			  
				
			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `vorbildung`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
				//	echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_vorbildung = ($neu_vorbildung + 1);
					
						
					}
				
			}	

		fclose($file_handle);
		
		echo $neu_vorbildung." Vorbildungen importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
//ALIAS für Vorbildung setzen:
if ($db_temp->exec("UPDATE `vorbildung`
									   SET
										`langform` = 'Qualifizierter Sek. I (ehemals Realschulabschluss)' WHERE `kurzform` = 'S1'")) { 
										
											
										}			


// Schularten importieren:

$neu_schularten = 0;

if (file_exists($pfad_workdir."daten/svp_schularten.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `schularten`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_schularten.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[1];
			  $kurzform = $teilung[2];
			  $anzeigeform = $teilung[3];
			  $langform = $teilung[4];
			  
				if ($kurzform != "kurzform") {
			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `schularten`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_schularten = ($neu_schularten + 1);
					
						
					}
				}
			}	

		fclose($file_handle);
		
		echo $neu_schularten." Schularten importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}
		
		
	// Arten der Sorgeberechtigten importieren:
	// (WL in edoo.sys anpassbar)

$neu_sorgeart = 0;

if (file_exists($pfad_workdir."daten/svp_sorge.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `sorge`")) {
		
						echo "<font color='orange'>Alle Soll-Einträge zu Unterrichtsgruppen gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/svp_sorge.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $schluessel = $teilung[1];
			  $kurzform = $teilung[2];
			  $anzeigeform = $teilung[3];
			  $langform = $teilung[4];
			  
				if ($kurzform != "kurzform") {
			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `sorge`
								   SET
									`schluessel` = '$schluessel',
									`kurzform` = '$kurzform',
									`anzeigeform` = '$anzeigeform',
									`langform` = '$langform'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_sorgeart = ($neu_sorgeart + 1);
					
						
					}
				}
			}	

		fclose($file_handle);
		
		echo $neu_sorgeart." Sorgearten importiert.<br>";

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}	


		$neu_ort = 0;

		if (file_exists($pfad_workdir."daten/svp_wl_ort_gemeinde.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db_temp->exec("TRUNCATE TABLE `plz_ort`")) {
		
						echo "<font color='orange'>Alle Orte gelöscht!<br>";

					}

		$file_an = $pfad_workdir."daten/svp_wl_ort_gemeinde.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("§"", "", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  
			  $teilung = explode(";", $line2);
			  
			  $plz = $teilung[0];
			  $ort = $teilung[1];
			  
			  
			  				// Datensatz neu in DB schreiben:

					if ($db_temp->exec("INSERT INTO `plz_ort`
								   SET
									`plz` = '$plz',
									`ort` = '$ort'")) {
					
					$last_id = $db_temp->lastInsertId();
					
					//echo "<font color='black'>Datensatz <b>".$anzeigeform."</b> wurde neu angelegt!<br>";
					$neu_ort = ($neu_ort + 1);
					
						
					}
				
			}	

		fclose($file_handle);
		
		echo $neu_ort." Orte importiert.<br>";

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