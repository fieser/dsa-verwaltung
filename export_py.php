<?php



include("./kopf.php");


include("./login_inc.php");
@session_start();




	
include("./config.php");



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



$jgst_1 = intval($schuljahr[2].$schuljahr[3]);
$jgst_1 = trim($jgst_1);
$jgst_2 = trim($jgst_1 - 1);
$jgst_3 = trim($jgst_1 - 2);
$jgst_4 = trim($jgst_1 - 3);

//Filter vorbereiten:
if (isset($_POST['f_nachname'])) {
$f_nachname = $_POST['f_nachname'];
}
if (isset($_POST['f_vorname'])) {
$f_vorname = $_POST['f_vorname'];
}
if (isset($_POST['f_geburtsdatum'])) {
$f_geburtsdatum = $_POST['f_geburtsdatum'];
}
if (isset($_POST['f_schulform'])) {
$f_schulform = strtolower($_POST['f_schulform']);
}
if (isset($_POST['f_beruf'])) {
$f_beruf = $_POST['f_beruf'];
}



$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 WHERE papierkorb NOT LIKE '1' AND schulform LIKE '%$f_schulform%' AND status LIKE 'vollständig' AND nachname LIKE '%$f_nachname%' AND vorname LIKE '%$f_vorname%' AND ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs')) GROUP BY dsa_bewerberdaten.id ORDER BY prio, nachname ASC");
	
	$treffer_an = $select_an->rowCount();
	

$kopf = "nummer;nachname;vorname;geschlecht;geburtsdatum;geburtsort;geburtsland;zuzug;staatsangehoerigkeit;muttersprache;religion;herkuftsland;strasse;plz;wohnort;hausnummer;telefon1;telefon2;mail;schulart;jahrgang;abschluss;sorge1_vorname;sorge1_nachname;sorge1_strasse;sorge1_hausnummer;sorge1_plz;sorge1_wohnort;sorge1_telefon1;sorge1_telefon2;sorge1_mail;sorge2_vorname;sorge2_nachname;sorge2_strasse;sorge2_hausnummer;sorge2_plz;sorge2_wohnort;sorge2_telefon1;sorge2_telefon2;sorge2_mail;schulform;dauer;beginn;ende;beruf;betrieb;bgy_sp1;bgy_sp2;bgy_sp3;bgy_sp4;bgy_sp5;fs1;fs1_von;fs1_bis;fs2;fs2_von;fs2_bis;fs3;fs3_von;fs3_bis; \n";

if ($treffer_an > 0) {
	if (file_exists("./export/csv2edoo.csv")) {
	unlink("./export/csv2edoo.csv");
	}
}

file_put_contents("./export/csv2edoo.csv", $kopf);

$nummer = 0;

foreach($select_an as $an) {
	
	//Schwerpunktwünsche aus Variablen löschen:
	$bgy_sp1 = "";
	$bgy_sp2 = "";
	$bgy_sp3 = "";
	$bgy_sp4 = "";
	$bgy_sp5 = "";
	
$nummer = ($nummer + 1);
if ($an['zuzug'] != "") {
	$zuzug = date("d.m.Y",strtotime($an['zuzug']));
} else {
	$zuzug = "";
}

//Klasse durch Klassenstufe ersetzen:
$fs1_von = str_replace("Klasse ","",$an['fs1_von']);
$fs1_bis = str_replace("Klasse ","",$an['fs1_bis']);

$fs2_von = str_replace("Klasse ","",$an['fs2_von']);
$fs2_bis = str_replace("Klasse ","",$an['fs2_bis']);

$fs3_von = str_replace("Klasse ","",$an['fs3_von']);
$fs3_bis = str_replace("Klasse ","",$an['fs3_bis']);

//Fremdsprache Anzeigeform durch Fremdsprache Kurzform ersetzen.
$fs1 = $an['fs1'];
$fs2 = $an['fs2'];
$fs3 = $an['fs3'];

	//Optionale deaktivierung des Fremdsprachenexports, um bug beim Bewerberimport in edoo.sys Version 11.0.444 zu umgehen:
	if ($fremdsprachen_export_deaktivieren == 1) {
	$fs1 = "";
	$fs2 = "";
	$fs3 = "";
	}

$fs1 = str_replace("Englisch","E",$fs1);
$fs1 = str_replace("Spanisch","S",$fs1);
$fs1 = str_replace("Französisch","F",$fs1);
$fs1 = str_replace("Latein","L",$fs1);
$fs1 = str_replace("Italienisch","I",$fs1);
$fs1 = str_replace("Russisch","Ru",$fs1);

$fs2 = str_replace("Englisch","E",$fs2);
$fs2 = str_replace("Spanisch","S",$fs2);
$fs2 = str_replace("Französisch","F",$fs2);
$fs2 = str_replace("Latein","L",$fs2);
$fs2 = str_replace("Italienisch","I",$fs2);
$fs2 = str_replace("Russisch","Ru",$fs2);

$fs3 = str_replace("Englisch","E",$fs3);
$fs3 = str_replace("Spanisch","S",$fs3);
$fs3 = str_replace("Französisch","F",$fs3);
$fs3 = str_replace("Latein","L",$fs3);
$fs3 = str_replace("Italienisch","I",$fs3);
$fs3 = str_replace("Russisch","Ru",$fs3);
 
$bgy_sp1 = $an['bgy_sp1'];
$bgy_sp2 = $an['bgy_sp2'];
$bgy_sp3 = $an['bgy_sp3'];

//Für Schulfromen (außer BS) bei denen keine Schwerpunkte zu wählen wahren:
if (trim($bgy_sp1) == "") {
	if ($an['schulform'] == "bvj") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_810105%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	if ($an['schulform'] == "dbos") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8800000%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	
	if ($an['schulform'] == "bf2") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8207000T%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	
	if ($an['schulform'] == "bos2") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8702030%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	
	if ($an['schulform'] == "bfp") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8208000%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
				
			}
	}
			
	if ($an['schulform'] == "aph") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8609010%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	
		if ($an['schulform'] == "fsof") {
		$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_860100%' ORDER BY anzeigeform ASC");
			foreach($select_bz as $bz) {
				$bgy_sp1 = trim($bz['kurzform']);
			}
	
	}
	
}
/*
	echo "<pre>";
	print_r ( $an );
	echo "</pre>";
*/
//Zuordung Bewerberziele gemäß edoo.sys:

					//Wenn es noch weitere Anmeldungen gibt:
					$nachname = $an['nachname'];
					$vorname = $an['vorname'];
					$geburtsdatum = $an['geburtsdatum'];
					
					$select_bew_dub = $db->query("SELECT id FROM dsa_bewerberdaten WHERE nachname = '$nachname' AND vorname = '$vorname' AND geburtsdatum = '$geburtsdatum'");	
					$treffer_bew_dub = $select_bew_dub->rowCount();
					
					if ($treffer_bew_dub > 1) {
					
						//Doppelte durchgehen:
						foreach($select_bew_dub as $dub) {
						$dub_id = $dub['id'];
						$bew_id = $an['0'];
						//echo "<tr><td style='padding: 5;'></td></tr>";
						
						$select_bil_dub = $db->query("SELECT * FROM dsa_bildungsgang WHERE id_dsa_bewerberdaten = '$dub_id' AND id_dsa_bewerberdaten != '$bew_id'");	
						foreach($select_bil_dub as $bild_dub) {
							/*
								echo "<pre>";
								print_r ( $bild_dub );
								echo "</pre>";
							*/
							$dup_sp1 = $bild_dub['bgy_sp1'];
							$dup_sp2 = $bild_dub['bgy_sp2'];
							$dup_sp3 = $bild_dub['bgy_sp3'];
							
							//Für Schulfromen (außer BS) bei denen keine Schwerpunkte zu wählen wahren:
								if ($dup_sp1 == "") {
									if ($bild_dub['schulform'] == "bvj") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_810105%' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									if ($bild_dub['schulform'] == "dbos") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8800000' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									if ($bild_dub['schulform'] == "bf2") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8207000T' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									
									if ($bild_dub['schulform'] == "bos2") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8702030' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									
									if ($bild_dub['schulform'] == "bfp") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8208000' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									}
											
									if ($bild_dub['schulform'] == "aph") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_8609010' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									
									if ($bild_dub['schulform'] == "fsof") {
										$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE id_bildungsgang LIKE '1058_860100%' ORDER BY anzeigeform ASC");
											foreach($select_bz as $bz) {
												$dup_sp1 = trim($bz['kurzform']);
											}
									
									}
									
								}
							
							if ($bild_dub['prio'] == 1 OR $bild_dub['prio'] == "") {
								
								
								
								//Wieviel prios sind schon belegt:
								if ($bgy_sp1 == "") { //keiner
								
									$bgy_sp1 = $dup_sp1;
									$bgy_sp2 = $dup_sp2;
									$bgy_sp3 = $dup_sp3;
									
								} else {
										if ($bgy_sp1 != "" AND $bgy_sp2 == "") { //einer
											
											$bgy_sp1 = $dup_sp1;
											$bgy_sp2 = $dup_sp2;
											$bgy_sp3 = $dup_sp3;
											$bgy_sp4 = $an['bgy_sp1'];
										} else {
												if ($bgy_sp2 != "" AND $bgy_sp3 == "") { //zwei
													
													$bgy_sp1 = $dup_sp1;
													$bgy_sp2 = $dup_sp2;
													$bgy_sp3 = $dup_sp3;
													$bgy_sp4 = $an['bgy_sp1'];
													$bgy_sp5 = $an['bgy_sp2'];
												} else {
														if ($bgy_sp3 != "" AND $bgy_sp4 == "") { //drei (wie zwei)
															
															$bgy_sp1 = $dup_sp1;
															$bgy_sp2 = $dup_sp2;
															$bgy_sp3 = $dup_sp3;
															$bgy_sp4 = $an['bgy_sp1'];
															$bgy_sp5 = $an['bgy_sp2'];
														} else {
																if ($bgy_sp4 != "" AND $bgy_sp5 == "") { //vier (wie zwei)
																	
																	$bgy_sp1 = $dup_sp1;
																	$bgy_sp2 = $dup_sp2;
																	$bgy_sp3 = $dup_sp3;
																	$bgy_sp4 = $an['bgy_sp1'];
																	$bgy_sp5 = $an['bgy_sp2'];
																}
														}
												}
										}
								}
							}
								
								if ($bild_dub['prio'] == 2) {
								
									//Wieviel prios sind schon belegt:
									if ($bgy_sp1 == "") { //keiner
									
										$bgy_sp1 = $dup_sp1;
										$bgy_sp2 = $dup_sp2;
										$bgy_sp3 = $dup_sp3;
										
									} else {
										if ($bgy_sp1 != "" AND $bgy_sp2 == "") { //einer
											
											$bgy_sp2 = $dup_sp1;
											$bgy_sp3 = $dup_sp2;
											$bgy_sp4 = $dup_sp3;
											$bgy_sp1 = $an['bgy_sp1'];
										} else {
												if ($bgy_sp2 != "" AND $bgy_sp3 == "") { //zwei
													
													$bgy_sp3 = $dup_sp1;
													$bgy_sp4 = $dup_sp2;
													$bgy_sp5 = $dup_sp3;
													$bgy_sp1 = $an['bgy_sp1'];
													$bgy_sp2 = $an['bgy_sp2'];
												} else {
														if ($bgy_sp3 != "" AND $bgy_sp4 == "") { //drei
															
															$bgy_sp4 = $dup_sp1;
															$bgy_sp5 = $dup_sp2;
															
															$bgy_sp1 = $an['bgy_sp1'];
															$bgy_sp2 = $an['bgy_sp2'];
															$bgy_sp3 = $an['bgy_sp2'];
														} else {
																if ($bgy_sp4 != "" AND $bgy_sp5 == "") { //vier
																	
																	$bgy_sp5 = $dup_sp1;
																	
																	$bgy_sp1 = $an['bgy_sp1'];
																	$bgy_sp2 = $an['bgy_sp2'];
																	$bgy_sp3 = $an['bgy_sp1'];
																	$bgy_sp4 = $an['bgy_sp2'];
																}
														}
												}
										}
									}
								}
								
						
						
						}
						}
					}



$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE anzeigeform LIKE '$bgy_sp1' ORDER BY anzeigeform ASC");
foreach($select_bz as $bz) {
	$bgy_sp1 = $bz['kurzform'];
}
$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE anzeigeform LIKE '$bgy_sp2' ORDER BY anzeigeform ASC");
foreach($select_bz as $bz) {
	$bgy_sp2 = $bz['kurzform'];
}
$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE anzeigeform LIKE '$bgy_sp3' ORDER BY anzeigeform ASC");
foreach($select_bz as $bz) {
	$bgy_sp3 = $bz['kurzform'];
}
$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE anzeigeform LIKE '$bgy_sp4' ORDER BY anzeigeform ASC");
foreach($select_bz as $bz) {
	$bgy_sp4 = $bz['kurzform'];
}
$select_bz = $db_temp->query("SELECT kurzform FROM edoo_bewerbungsziel WHERE anzeigeform LIKE '$bgy_sp5' ORDER BY anzeigeform ASC");
foreach($select_bz as $bz) {
	$bgy_sp5 = $bz['kurzform'];
}



	$zeile = $nummer.";".$an['nachname'].";".$an['vorname'].";".$an['geschlecht'].";".date("d.m.Y",strtotime($an['geburtsdatum'])).";".$an['geburtsort'].";".$an['geburtsland'].";".$zuzug.";".$an['staatsangehoerigkeit'].";".$an['muttersprache'].";".$an['religion'].";".$an['herkuftsland'].";".$an['strasse'].";".$an['plz'].";".trim($an['wohnort']).";".$an['hausnummer'].";".$an['telefon1'].";".$an['telefon2'].";".$an['mail'].";".$an['schulart'].";".$an['jahrgang'].";".$an['abschluss'].";".$an['sorge1_vorname'].";".$an['sorge1_nachname'].";".$an['sorge1_strasse'].";".$an['sorge1_hausnummer'].";".$an['sorge1_plz'].";".trim($an['sorge1_wohnort']).";".$an['sorge1_telefon1'].";".$an['sorge1_telefon2'].";".$an['sorge1_mail'].";".$an['sorge2_vorname'].";".$an['sorge2_nachname'].";".$an['sorge2_strasse'].";".$an['sorge2_hausnummer'].";".$an['sorge2_plz'].";".trim($an['sorge2_wohnort']).";".$an['sorge2_telefon1'].";".$an['sorge2_telefon2'].";".$an['sorge2_mail'].";".$an['schulform'].";".$an['dauer'].";".$an['beginn'].";".$an['ende'].";".$an['beruf'].";".$an['betrieb'].";".$bgy_sp1.";".$bgy_sp2.";".$bgy_sp3.";".$bgy_sp4.";".$bgy_sp5.";".$fs1.";".$fs1_von.";".$fs1_bis.";".$fs2.";".$fs2_von.";".$fs2_bis.";".$fs3.";".$fs3_von.";".$fs3_bis."; \n";

// Existens Datei mail-list.csv prüfen, anlegen und ergänzen:

	//	if (file_exists("./export/csv2edoo.csv")) {
			
			file_put_contents("./export/csv2edoo.csv", $zeile, FILE_APPEND);
	//	} else {
	//		file_put_contents("./export/csv2edoo.csv", $kopf);
			//file_put_contents("./export/csv2edoo.csv", $zeile);
	//	}

}

if ($treffer_an == 0) { 
echo "<p><b><font color='red'>".$treffer_an." Schülerdatensätze</font></b> in xls-Datei geschrieben.<br></p>";
} else {
echo "<p><b>".$treffer_an." Schülerdatensätze</b> in xls-Datei geschrieben.<br></p>";
}

//xls-Datei per python erzeugen:


// Pfad zum Python-Skript
$scriptPath = '/var/www/html/verwaltung/export/main.py';
// Argumente für das Python-Skript, falls benötigt
$args = '/var/www/html/verwaltung/export/csv2xls.csv /var/www/html/verwaltung/export/Bewerber_Import.xls';
// Ausführen des Python-Skripts
//exec("/usr/bin/python3 $scriptPath $args", $output, $return_var);
// $output enthält die Ausgabe des Skripts, $return_var den Statuscode

$output = shell_exec("/usr/bin/python3 $scriptPath $args 2>&1");
//echo $output;


// angegebenen Verzeichnis oeffnen
$myDirectory = opendir("./export/");
// Objekte lesen und in Array schreiben
while($entryName = readdir($myDirectory)) {
$dirArray[] = $entryName;
}
//Array sortieren - neuestes Objekt zuerst
sort($dirArray);
// Verzeichnis schliessen
closedir($myDirectory);
// Objekte im Array zaehlen
$indexCount = count($dirArray);


echo "<h3 style='margin-top: 2em;'>Laden Sie sich die soeben generierte Ecxel-Datei herunter:</h3>";

// Array durchlaufen und in einer Liste ausgeben
for($index=0; $index < $indexCount; $index++) {
$extension = substr($dirArray[$index], -5);
	if ($extension == 't.xls'){ // nur csv Dateien ausgeben
		

	//Dateien, die innerhalb der letzten 10 min aktualisiert wurden, werden blau angezeigt:	
	if ((time() - filemtime("./export/".$dirArray[$index])) < 10*60) {
		$farbe = "blue";
	} else {
		$farbe = "black";
	}
		
	echo  "<p style='margin-top: 2em; margin-bottom: 2em;'><font color='".$farbe."'><b><a href='./export/".$dirArray[$index]."'>".$dirArray[$index]."</a></b></font> &nbsp;&nbsp;&nbsp;&nbsp;".date ('d.m.Y H:i', filemtime("./export/".$dirArray[$index]))."</p>";
	}
	
}



echo "<p>Importieren Sie die Datei <i>Bewerber_Import.xls</i> über die entsprechende Funktion im Menü <i>Modulbezogene Funktionen</i> is Bewerbermodul von edoo.sys.</p>";

?>

<p>
<form method="post" action="./liste.php">
<input type="submit" class='btn btn-default btn-sm' style='margin-top: 4em;' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include("./fuss.php");
?>