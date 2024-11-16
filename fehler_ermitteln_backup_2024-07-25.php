<?php

		function levenshteinPerc($str1, $str2) {
        $len = strlen($str1);
			if ($len===0 AND strlen($str2)===0) {
				return 0;
			} else {
				return ($len>0 ? levenshtein($str1, $str2) / $len : 1);
			}
		}

include("/var/www/html/verwaltung/kopf.php");

include("./login_inc.php");
@session_start();




	
include("/var/www/html/verwaltung/config.php");



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];


echo "<h1>Abweichungen ermitteln</h1>";

$fehler = 0;

// Tabelle leeren:
				
				
					if ($db->exec("TRUNCATE TABLE `fehler`")) {
		
						

					}

//Anmeldebögen auflisten:
$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.id_dsa_bewerberdaten = dsa_bewerberdaten.id WHERE status = 'übertragen' OR status = 'reaktivierbar' GROUP BY dsa_bewerberdaten.id ORDER BY schulform, beruf, bgy_sp1 ASC");
$treffer_an = $select_an->rowCount();

	
	foreach ($select_an as $an) {
		
		$geburtsdatum = $an['geburtsdatum'];
		$nachname = trim($an['nachname']);
		$vorname = $an['vorname'];
		$geburtsort = $an['geburtsort'];

		$schueler_vergleich = 1;
		
					//Bewerberdaten durchsuchen:
			
			$select_edoo_b = $db->query("SELECT * FROM edoo_bewerber WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND status_uebernahme = '0'");	
			$treffer_edoo_b = $select_edoo_b->rowCount();
			
			foreach($select_edoo_b as $edoo) {
				
				$id_edoo = $edoo['id'];
				$id_stamm = $edoo['id_edoo'];
				$id_bewerberdaten = $an['id_dsa_bewerberdaten'];
				$id_bildungsgang = '';
				

				//if (trim($an['vorname']) != trim($edoo['vorname'])) {
					
					
				//if (strpos(trim($edoo['vorname']),trim($an['vorname'])) == false) {
					

				if (levenshteinPerc($edoo['vorname'],$an['vorname']) > 0.7) {
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['vorname'];
					$feld_dsa = $an['vorname'];
					if ($debug == 1) {
						$feldname = "Vorname <i>(Abweichung: ".number_format(levenshteinPerc($edoo['vorname'],$an['vorname']),1,",",".").")</i>";
					} else {
						$feldname = "Vorname";
					}
					
					if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = '$feldname',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				
				if (trim($an['geburtsort']) != trim($edoo['geburtsort'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['geburtsort'];
					$feld_dsa = $an['geburtsort'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Geburtsort',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				if (trim($an['geburtsland']) != trim($edoo['geburtsland'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['geburtsland'];
					$feld_dsa = $an['geburtsland'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Geburtsland',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				
				if (trim($an['staatsangehoerigkeit']) != trim($edoo['staatsangehoerigkeit'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['staatsangehoerigkeit'];
					$feld_dsa = $an['staatsangehoerigkeit'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Staatsangehörigkeit',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				/*
				if (trim($an['herkunftsland']) != trim($edoo['herkunftsland'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['herkunftsland'];
					$feld_dsa = $an['herkunftsland'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Herkunftsland',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				*/
				
				
				if (trim($an['zuzug']) != trim($edoo['zuzugsdatum'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['zuzugsdatum'];
					$feld_dsa = $an['zuzug'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Zuzugsdatum',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				if (trim($an['geschlecht']) != trim($edoo['geschlecht']) AND trim($edoo['geschlecht']) != "O" AND trim($edoo['geschlecht']) != "D") {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['geschlecht'];
					$feld_dsa = $an['geschlecht'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Geschlecht',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				if (trim($an['religion']) != trim($edoo['religionszugehoerigkeit'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['religionszugehoerigkeit'];
					$feld_dsa = $an['religion'];
					
					$select_fehler_dup = $db->query("SELECT * FROM fehler WHERE id_bewerberdaten = '$id_bewerberdaten' AND feld_edoo = '$feld_edoo'");	
					$treffer_fehler_dup = $select_fehler_dup->rowCount();
											
						if ($treffer_fehler_dup == 0) {

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Religionszugehoerigkeit',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				}
				$an_str = str_replace("Str.","Straße",trim($an['strasse']));
				$edoo_str = str_replace("Str.","Straße",trim($edoo['strasse']));
				
				$an_str = str_replace("str.","straße",trim($an_str));
				$edoo_str = str_replace("str.","straße",trim($edoo_str));
				
				if (trim($an_str) != trim($edoo_str)) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo_str;
					$feld_dsa = $an_str;
					
					$select_fehler_dup = $db->query("SELECT * FROM fehler WHERE id_bewerberdaten = '$id_bewerberdaten' AND feld_edoo = '$feld_edoo'");	
					$treffer_fehler_dup = $select_fehler_dup->rowCount();
											
						if ($treffer_fehler_dup == 0) {
							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Straße',
											`wo_in_edoo` = 'bw',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
							$fehler = ($fehler + 1);
						}
				}
				
				if (trim($an['plz']) != trim($edoo['plz'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['plz'];
					$feld_dsa = $an['plz'];
					
					$select_fehler_dup = $db->query("SELECT * FROM fehler WHERE id_bewerberdaten = '$id_bewerberdaten' AND feld_edoo = '$feld_edoo'");	
					$treffer_fehler_dup = $select_fehler_dup->rowCount();
											
							if ($treffer_fehler_dup == 0) {

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Postleitzahl',
											`wo_in_edoo` = 'bw',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
				}
				if (trim($an['hausnummer']) != trim($edoo['hausnummer'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['hausnummer'];
					$feld_dsa = $an['hausnummer'];
					
					$select_fehler_dup = $db->query("SELECT * FROM fehler WHERE id_bewerberdaten = '$id_bewerberdaten' AND feld_edoo = '$feld_edoo'");	
					$treffer_fehler_dup = $select_fehler_dup->rowCount();
											
						if ($treffer_fehler_dup == 0) {

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Hausnummer',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				}
				
				if (trim($an['wohnort']) != trim($edoo['wohnort'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['wohnort'];
					$feld_dsa = $an['wohnort'];
					
					$select_fehler_dup = $db->query("SELECT * FROM fehler WHERE id_bewerberdaten = '$id_bewerberdaten' AND feld_edoo = '$feld_edoo'");	
					$treffer_fehler_dup = $select_fehler_dup->rowCount();
											
						if ($treffer_fehler_dup == 0) {

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Wohnort',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				}
				//if ($an['mail'] != $edoo['mail']) {
				if (strcasecmp (trim($an['mail']), trim($edoo['mail'])) != 0) {
					
				
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					
					$feld_edoo = $edoo['mail'];
					$feld_dsa = $an['mail'];
					
					if ($feld_edoo == "") {
						$feld_edoo = "fehlt";
					}

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Email-Adresse',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
				
				if (strcasecmp (trim($an['abschluss']), trim($edoo['abschluss'])) != 0) {
					
				
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					
					$feld_edoo = str_replace("HS","BR",$edoo['abschluss']);
					$feld_dsa = str_replace("HS","BR",$an['abschluss']);
					$feld_dsa = str_replace("","OB",$an['abschluss']);
					
					if ($feld_edoo == "") {
						$feld_edoo = "fehlt";
					}

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Höchst. allg.-bild. Abschluss',
										`wo_in_edoo` = 'bw',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
				
			//Geschlecht o oder d korrekt?
						
			if (trim($edoo['geschlecht']) == "O") {
				
						
						$hinweis = "Prüfen Sie, ob im <b>Personalausweis</b> tatsächlich <i>geschlechtslos</i> eingetragen ist!";
						$feld_edoo = $edoo['geschlecht'];


							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '',
											`feldname` = 'Geschlecht',
											`wo_in_edoo` = 'bw',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
							
					$fehler = ($fehler + 1);
			}
			
			if (trim($edoo['geschlecht']) == "D") {
				
						
						$hinweis = "Prüfen Sie, ob im <b>Personalausweis</b> tatsächlich <i>divers</i> eingetragen ist!";
						$feld_edoo = $edoo['geschlecht'];


							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '',
											`feldname` = 'Geschlecht',
											`wo_in_edoo` = 'bw',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
							
					$fehler = ($fehler + 1);
			}
				
			}
					
		if ($schueler_vergleich == 1) {
			
			if ($treffer_edoo_b == 0) { //Wenn keine Bwerberdaten vorliegen
					
			//Schülerdaten durchsehen:
			
			$select_edoo_s = $db->query("SELECT * FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname'");	
			$treffer_edoo_s = $select_edoo_s->rowCount();
			
				foreach($select_edoo_s as $edoo) {
					
					$id_edoo = $edoo['id'];
					$id_bewerberdaten = $an['id_dsa_bewerberdaten'];
					$id_bildungsgang = '';
					$bildungsgang = $edoo['bildungsgang'];
					

					if (trim($an['vorname']) != trim($edoo['vorname'])) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo['vorname'];
						$feld_dsa = $an['vorname'];

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Vorname',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					if (trim($an['geschlecht']) != trim($edoo['geschlecht']) AND trim($edoo['geschlecht']) != "O" AND trim($edoo['geschlecht']) != "D") {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['geschlecht'];
					$feld_dsa = $an['geschlecht'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Geschlecht',
										`wo_in_edoo` = 'sc',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
					$an_str = str_replace("Str.","Straße",trim($an['strasse']));
					$edoo_str = str_replace("Str.","Straße",trim($edoo['strasse']));
					
					$an_str = str_replace("str.","straße",trim($an_str));
					$edoo_str = str_replace("str.","straße",trim($edoo_str));
					
					if (trim($an_str) != trim($edoo_str)) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo_str;
						$feld_dsa = $an_str;

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Straße',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					if (trim($an['hausnummer']) != trim($edoo['hausnummer'])) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo['hausnummer'];
						$feld_dsa = $an['hausnummer'];

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Hausnummer',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					
					if (trim($an['plz']) != trim($edoo['plz'])) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo['plz'];
						$feld_dsa = $an['plz'];

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'PLZ',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					
					if (trim($an['wohnort']) != trim($edoo['wohnort'])) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo['wohnort'];
						$feld_dsa = $an['wohnort'];

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Wohnort',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					if (trim($an['herkunftsland']) != trim($edoo['herkunftsland'])) {
					
					$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
					$feld_edoo = $edoo['herkunftsland'];
					$feld_dsa = $an['herkunftsland'];

						if ($db->exec("INSERT INTO `fehler`
									   SET
										`id_edoo` = '$id_edoo',
										`id_bewerberdaten` = '$id_bewerberdaten',
										`id_bildungsgang` = '$id_bildungsgang',
										`feld_edoo` = '$feld_edoo',
										`feld_dsa` = '$feld_dsa',
										`feldname` = 'Herkunftsland',
										`wo_in_edoo` = 'sc',
										`hinweis` = '$hinweis',
										`erledigt` = '0'")) {
						}
				$fehler = ($fehler + 1);
				}
					
					
					if (trim($an['beginn']) >	 trim($edoo['eintritt']) AND trim($an['beginn'] != "")) {
						
						$hinweis = "Eintrittsdatum vor Ausbildungsbeginn laut Anmeldeformular!";
						$feld_edoo = $edoo['eintritt'];
						$feld_dsa = $an['beginn'];

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'Eintrittsdatum',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
					
					//if ($an['mail'] != $edoo['mail']) {
					if (strcasecmp (trim($an['mail']), trim($edoo['mail'])) != 0) {
						
						$hinweis = "Wert in edoo.sys stimmt nicht mit Anmeldeformular überein!";
						$feld_edoo = $edoo['mail'];
						$feld_dsa = $an['mail'];
						
						if ($feld_edoo == "") {
							$feld_edoo = "fehlt";
						}

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '$feld_dsa',
											`feldname` = 'E-Mail',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
			//Betrieb zugeornet?
						
			$select_edoo_sb = $db->query("SELECT * FROM edoo_schueler_betrieb WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname'");	
			$treffer_edoo_sb = $select_edoo_sb->rowCount();
					
					if ($an['schulform'] == "bs" AND ($an['betrieb'] != "" OR $an['betrieb'] != "") AND $treffer_edoo_sb == 0) {
						
						$hinweis = "Ausbildungsbetrieb fehlt in edoo.sys!";
						

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '',
											`feld_dsa` = '',
											`feldname` = 'Ausbildungsbetrieb',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
			//Beruf zugeornet?		
			$select_edoo_bet = $db_temp->query("SELECT * FROM berufe_angebot WHERE schueler = '$id_stamm'");	
			$treffer_edoo_bet = $select_edoo_bet->rowCount();
					
					if ($an['schulform'] == "bs" AND $an['beruf'] != "" AND $treffer_edoo_bet < 1) {
						
						$hinweis = "Ausbildungsberuf fehlt in edoo.sys!";
						

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '',
											`feld_dsa` = '',
											`feldname` = 'Ausbildungsberuf',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
					}
					
			
			//Geschlecht o oder d korrekt?
						
			if (trim($edoo['geschlecht']) == "O") {	
						$hinweis = "Prüfen Sie, ob im <b>Personalausweis</b> tatsächlich <font color=\'blue\'><b><i>geschlechtslos</i></b></font> eingetragen ist!<br>Ändern Sie anderenfalls den Wert in edoo.sys!";
						$feld_edoo = $edoo['geschlecht'];


							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '',
											`feldname` = 'Geschlecht',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
							
					$fehler = ($fehler + 1);
			}
			
						if (trim($edoo['geschlecht']) == "D") {	
						$hinweis = "Prüfen Sie, ob im <b>Personalausweis</b> tatsächlich <font color=\'blue\'><b><i>divers</i></b></font> eingetragen ist!<br>Ändern Sie anderenfalls den Wert in edoo.sys!";
						$feld_edoo = $edoo['geschlecht'];


							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '$feld_edoo',
											`feld_dsa` = '',
											`feldname` = 'Geschlecht',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
							
					$fehler = ($fehler + 1);
			}
			

			
				//Wenn Schüler/-in aktiv und Bildungsgang abweicht:
				if (($an['austritt'] == "" OR $an['austritt'] > date("Y-m-d"))
					AND (($an['schulform'] == "bs" AND strpos($bildungsgang,"8105000") == false)
					OR ($an['schulform'] == "bos2" AND strpos($bildungsgang,"87020") == false)
					OR ($an['schulform'] == "bf1" AND strpos($bildungsgang,"8206030") == false)
					OR ($an['schulform'] == "bf2" AND strpos($bildungsgang,"86070") == false)
					OR ($an['schulform'] == "bgy" AND strpos($bildungsgang,"85020") == false)
					OR ($an['schulform'] == "bos1" AND strpos($bildungsgang,"870101") == false)
					OR ($an['schulform'] == "bvj" AND strpos($bildungsgang,"81010") == false)
					OR ($an['schulform'] == "dbos" AND strpos($bildungsgang,"8800000") == false)
					OR ($an['schulform'] == "aph" AND strpos($bildungsgang,"8609010") == false)
					OR ($an['schulform'] == "bfp" AND strpos($bildungsgang,"8208000") == false)
					OR ($an['schulform'] == "bfp" AND strpos($bildungsgang,"8204") == false)
					OR ($an['schulform'] == "fs" AND strpos($bildungsgang,"860601") == false) )) {
					
				$hinweis = "Wurde noch nicht als Bewerber/-in übernommen!";
						

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '',
											`feld_dsa` = '',
											`feldname` = 'Als Bewerber übernehmen',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
				}
				
				
				//Wenn der Schwerpunkt sich ändert, muss sie/er noch als Bewerber übernommern werden:
				
								
				if (($an['austritt'] == "" OR $an['austritt'] > date("Y-m-d"))
					AND (($an['bgy_sp1'] == "Gastronomie" AND strpos($bildungsgang,"8204990") == false)
					OR ($an['bgy_sp1'] == "Informationstechnik" AND strpos($bildungsgang,"8204910") == false)

					OR ($an['bgy_sp1'] == "Mediendesign" AND strpos($bildungsgang,"8204980") == false) )) {
					
				$hinweis = "Wurde noch nicht als Bewerber/-in übernommen!";
						

							if ($db->exec("INSERT INTO `fehler`
										   SET
											`id_edoo` = '$id_edoo',
											`id_bewerberdaten` = '$id_bewerberdaten',
											`id_bildungsgang` = '$id_bildungsgang',
											`feld_edoo` = '',
											`feld_dsa` = '',
											`feldname` = 'Als Bewerber übernehmen',
											`wo_in_edoo` = 'sc',
											`hinweis` = '$hinweis',
											`erledigt` = '0'")) {
							}
					$fehler = ($fehler + 1);
				}
					
					
				} //Ende Auflistung SuS in edoo
			
		}
		
		
		
		

		}
	
	
	}
	



if ($fehler == 0) {
echo "Keine Abweichungen zwischen den Bewerberdaten und edoo.sys gefunden. <p style='margin-bottom: 20px;'><b>Es gibts nichts zu tun!</b></p>";
} else {
echo $fehler." Abweichungen in edoo.sys gefunden!";
}

?>

<p>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include("/var/www/html/verwaltung/fuss.php");
?>