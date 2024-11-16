<?php

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



include "./rechte.php";
include "./config.php";



if (isset($_GET['sort'])) {
	
	$_SESSION['sort'] = $_GET['sort'];
}

if (!isset($_SESSION['sort'])) {
	$_SESSION['sort'] = "nachname";
}

$sort = $_SESSION['sort'];

$id = $_GET['id'];


if (isset($_POST['submit_senden'])) {
	
	//Versand der Email dokumentieren:
	
		$id = intval($_GET['id']);
		$mailtext = $_POST['bodytext_final'];
		
		$last_user = $_SESSION['lastname'];
		$last_time = time();
	
	if ($log == "") {
			$log = date('Y-m-d H:m')."_".$last_user."geändert \n";
			}
			
			if ($db->exec("INSERT INTO `mail`
								   SET
									`id_dsa_bewerberdaten` = '$id',
									
									`mailtext` = '$mailtext',
									`last_time` = '$last_time',
									`log` = '$log',
									`last_user` = '$last_user'")) {
					
					$last_id = $db->lastInsertId();
									}
	
	
	
	
	echo "<div class='box-grau' style='background: #7FFFD4;'>Ihre Nachricht wurde versendet</div>";
}

//Änderungen in Datenbank schreiben
if (isset($_POST['bemerkungen'])) {
		$id = intval($_POST['id']);
		$dok_erfahrung = intval($_POST['dok_erfahrung']);
		$dok_zeugnis = intval($_POST['dok_zeugnis']);
		$dok_lebenslauf = intval($_POST['dok_lebenslauf']);
		$dok_ausweis = intval($_POST['dok_ausweis']);
		$bemerkungen = $_POST['bemerkungen'];
		
		$last_user = $_SESSION['lastname'];
		$last_time = time();
	

		//Prüfen ob bereits vorhanden:
		$select_vo1 = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id'");	
		$treffer_vo1 = $select_vo1->rowCount();
		//echo "Treffer: ".$treffer_vo;
		
		if ($treffer_vo1 == 0) {
			
			if ($log == "") {
			$log = date('Y-m-d H:m')."_".$last_user."geändert \n";
			}
			
			if ($db->exec("INSERT INTO `vorgang`
								   SET
									`id_dsa_bewerberdaten` = '$id',
									`dok_erfahrung` = '$dok_erfahrung',
									`dok_ausweis` = '$dok_ausweis',
									`dok_zeugnis` = '$dok_zeugnis',
									`dok_lebenslauf` = '$dok_lebenslauf',
									`bemerkungen` = '$bemerkungen',
									`last_time` = '$last_time',
									`log` = '$log',
									`last_user` = '$last_user'")) {
					
					$last_id = $db->lastInsertId();
									}
			
		} else {
			
			
						// Datensatz aendern.	
			if ($db->exec("UPDATE `vorgang`
						   SET
							`dok_erfahrung` = '$dok_erfahrung',
							`dok_ausweis` = '$dok_ausweis',
							`dok_zeugnis` = '$dok_zeugnis',
							`dok_lebenslauf` = '$dok_lebenslauf',
							`last_time` = '$last_time',
							`bemerkungen` = '$bemerkungen',
							`log` = '$log',
							`last_user` = '$last_user'	 WHERE `id_dsa_bewerberdaten` = '$id'")) {
			//$last = "LAST_INSERT_ID(UserID)";

		
							}
			
		
			
		}
		
		//Falls Prios
		
		if (isset($_POST['id_1'])) {
			
			$id_1 = $_POST['id_1'];
			//echo $id_1;
			//echo $dok_zeugnis;
				
						//Prüfen ob bereits vorhanden:
			$select_vo2 = $db->query("SELECT * FROM vorgang WHERE id_dsa_bewerberdaten = '$id_1'");	
			$treffer_vo2 = $select_vo2->rowCount();
			//echo "Treffer: ".$treffer_vo;
			
			if ($treffer_vo2 == 0) {
				
				if ($log == "") {
				$log = date('Y-m-d H:m')."_".$last_user."geändert \n";
				}
				
				if ($db->exec("INSERT INTO `vorgang`
									   SET
										`id_dsa_bewerberdaten` = '$id_1',
										`dok_erfahrung` = '$dok_erfahrung',
										`dok_ausweis` = '$dok_ausweis',
										`dok_zeugnis` = '$dok_zeugnis',
										`dok_lebenslauf` = '$dok_lebenslauf',
										`bemerkungen` = '$bemerkungen',
										`last_time` = '$last_time',
										`log` = '$log',
										`last_user` = '$last_user'")) {
						
						$last_id = $db->lastInsertId();
										}
				
			} else {
				
				foreach($select_vo2 as $vo2) {
					if ($vo2['dok_erfahrung'] == 1 AND $dok_erfahrung == 0) {
						$dok_erfahrung = 1;
					}
					if ($vo2['dok_ausweis'] == 1 AND $dok_ausweis == 0) {
					//	$dok_ausweis = 1;
					}
					if ($vo2['dok_zeugnis'] == 1 AND $dok_zeugnis == 0) {
					//	$dok_zeugnis = 1;
					}
					if ($vo2['dok_lebenslauf'] == 1 AND $dok_lebenslauf == 0) {
					//	$dok_lebenslauf = 1;
					}

				}					
							
				if ($db->exec("UPDATE `vorgang`
							   SET
								`dok_erfahrung` = '$dok_erfahrung',
								`dok_ausweis` = '$dok_ausweis',
								`dok_zeugnis` = '$dok_zeugnis',
								`dok_lebenslauf` = '$dok_lebenslauf',
								`last_time` = '$last_time',
								`bemerkungen` = '$bemerkungen',
								`log` = '$log',
								`last_user` = '$last_user' WHERE `id_dsa_bewerberdaten` = '$id_1'")) {
				//$last = "LAST_INSERT_ID(UserID)";

			
								}

			}
		}
		
				if (isset($_POST['id_2'])) {
			
			$id_2 = $_POST['id_2'];
			//echo $id_2;
			//echo $dok_zeugnis;
				
						//Prüfen ob bereits vorhanden:
			$select_vo2 = $db->query("SELECT * FROM vorgang WHERE id_dsa_bewerberdaten = '$id_2'");	
			$treffer_vo2 = $select_vo2->rowCount();
			//echo "Treffer: ".$treffer_vo;
			
			if ($treffer_vo2 == 0) {
				
				if ($log == "") {
				$log = date('Y-m-d H:m')."_".$last_user."geändert \n";
				}
				
				if ($db->exec("INSERT INTO `vorgang`
									   SET
										`id_dsa_bewerberdaten` = '$id_2',
										`dok_erfahrung` = '$dok_erfahrung',
										`dok_ausweis` = '$dok_ausweis',
										`dok_zeugnis` = '$dok_zeugnis',
										`dok_lebenslauf` = '$dok_lebenslauf',
										`bemerkungen` = '$bemerkungen',
										`last_time` = '$last_time',
										`log` = '$log',
										`last_user` = '$last_user'")) {
						
						$last_id = $db->lastInsertId();
										}
				
			} else {
				
				foreach($select_vo2 as $vo2) {
					if ($vo2['dok_erfahrung'] == 1 AND $dok_erfahrung == 0) {
						$dok_erfahrung = 1;
					}
					if ($vo2['dok_ausweis'] == 1 AND $dok_ausweis == 0) {
					//	$dok_ausweis = 1;
					}
					if ($vo2['dok_zeugnis'] == 1 AND $dok_zeugnis == 0) {
					//	$dok_zeugnis = 1;
					}
					if ($vo2['dok_lebenslauf'] == 1 AND $dok_lebenslauf == 0) {
					//	$dok_lebenslauf = 1;
					}

				}					
							
				if ($db->exec("UPDATE `vorgang`
							   SET
								`dok_erfahrung` = '$dok_erfahrung',
								`dok_ausweis` = '$dok_ausweis',
								`dok_zeugnis` = '$dok_zeugnis',
								`dok_lebenslauf` = '$dok_lebenslauf',
								`last_time` = '$last_time',
								`bemerkungen` = '$bemerkungen',
								`log` = '$log',
								`last_user` = '$last_user' WHERE `id_dsa_bewerberdaten` = '$id_2'")) {
				//$last = "LAST_INSERT_ID(UserID)";

			
								}

			}
		}
		
			if (isset($_POST['id_3'])) {
			
			$id_3 = $_POST['id_3'];
			echo $id_3;
			echo $dok_zeugnis;
				
						//Prüfen ob bereits vorhanden:
			$select_vo2 = $db->query("SELECT * FROM vorgang WHERE id_dsa_bewerberdaten = '$id_3'");	
			$treffer_vo2 = $select_vo2->rowCount();
			//echo "Treffer: ".$treffer_vo;
			
			if ($treffer_vo2 == 0) {
				
				if ($log == "") {
				$log = date('Y-m-d H:m')."_".$last_user."geändert \n";
				}
				
				if ($db->exec("INSERT INTO `vorgang`
									   SET
										`id_dsa_bewerberdaten` = '$id_3',
										`dok_erfahrung` = '$dok_erfahrung',
										`dok_ausweis` = '$dok_ausweis',
										`dok_zeugnis` = '$dok_zeugnis',
										`dok_lebenslauf` = '$dok_lebenslauf',
										`bemerkungen` = '$bemerkungen',
										`last_time` = '$last_time',
										`log` = '$log',
										`last_user` = '$last_user'")) {
						
						$last_id = $db->lastInsertId();
										}
				
			} else {
				
				foreach($select_vo2 as $vo2) {
					if ($vo2['dok_erfahrung'] == 1 AND $dok_erfahrung == 0) {
						$dok_erfahrung = 1;
					}
					if ($vo2['dok_ausweis'] == 1 AND $dok_ausweis == 0) {
					//	$dok_ausweis = 1;
					}
					if ($vo2['dok_zeugnis'] == 1 AND $dok_zeugnis == 0) {
					//	$dok_zeugnis = 1;
					}
					if ($vo2['dok_lebenslauf'] == 1 AND $dok_lebenslauf == 0) {
					//	$dok_lebenslauf = 1;
					}

				}					
							
				if ($db->exec("UPDATE `vorgang`
							   SET
								`dok_erfahrung` = '$dok_erfahrung',
								`dok_ausweis` = '$dok_ausweis',
								`dok_zeugnis` = '$dok_zeugnis',
								`dok_lebenslauf` = '$dok_lebenslauf',
								`last_time` = '$last_time',
								`bemerkungen` = '$bemerkungen',
								`log` = '$log',
								`last_user` = '$last_user' WHERE `id_dsa_bewerberdaten` = '$id_3'")) {
				//$last = "LAST_INSERT_ID(UserID)";

			
								}

			}
		}
		
		
		
		//Status aktualisieren:
		
		$select_an = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$id'");
	
		$treffer_an = $select_an->rowCount();
			
		foreach($select_an as $an) {
			
			if ($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "dbos" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") {
				//Status bei vollständigen Unterlagen:
			if ($dok_ausweis == 1 AND $dok_lebenslauf == 1 AND $dok_zeugnis == 1 AND $dok_erfahrung == 1) {
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'vollständig' WHERE `id` = '$id'")) { 
										
											
										}			
										$temp_status_voll = 1;
			} else {
				if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'eingegangen' WHERE `id` = '$id'")) { 
										
												
										}		
				
				
			}
			}
		
		if ($an['schulform'] == "bgy" OR $an['schulform'] == "bvj" OR $an['schulform'] == "bf1" OR $an['schulform'] == "hbf" OR $an['schulform'] == "bfp" OR $an['schulform'] == "aph") {
			//Status bei vollständigen Unterlagen:
			
		if ($dok_ausweis == "1" AND $dok_lebenslauf == "1" AND $dok_zeugnis == "1") {
				echo "schulform ok";
				
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'vollständig' WHERE `id` = '$id'")) { 
										
												
										}		
										$temp_status_voll = 1;
			} else {
				if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'eingegangen' WHERE `id` = '$id'")) { 
										
												
										}		
				
				
			}
		}
		
		if ($an['schulform'] == "bs") {
		
				if ($db->exec("UPDATE `dsa_bewerberdaten`
								   SET
									`status` = 'vollständig' WHERE `id` = '$id'")) { 
									
										
									}		
									$temp_status_voll = 1;	
			
		} 
		
		if ($temp_status_voll != 1) {
			//Status bei Bearbeitung der Infos zu Dokomenten anpassen:
			$select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND (dok_lebenslauf = '1' OR dok_ausweis = '1' OR dok_zeugnis = '1' OR dok_erfahrung = '1')");	
			$treffer_vo = $select_vo->rowCount();
			
			if ((($an['schulform'] == "bos1" OR $an['schulform'] == "bos1" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") AND ($dok_lebenslauf + $dok_ausweis + $dok_erfahrung + $do_zeugnis) == 4) OR (($an['schulform'] != "bos1" OR $an['schulform'] != "bos2" OR $an['schulform'] != "fs" OR $an['schulform'] != "fsof") AND ($dok_lebenslauf + $dok_ausweis + $do_zeugnis) == 3)) {
			//if ($treffer_vo > 0) {
				
				
				
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'wird bearbeitet' WHERE `id` = '$id'")) { 
										
											
										}			
			}
		}
		
		//Abgleich mit edoo.sys:
		$geburtsdatum = $an['geburtsdatum'];
		$nachname = $an['nachname'];
		$vorname = $an['vorname'];
	
		$select_edoo = $db->query("SELECT id FROM edoo_bewerber WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname'");	
		$treffer_edoo = $select_edoo->rowCount();
		
		if ($treffer_edoo > 0) {
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'übertragen' WHERE `id` = '$id'")) { 
										
											
										}
			} else { //Falls ergebnislos, Schülerdaten durchsuchen:
		
		
		
			$select_edoo = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname'");	
			$treffer_edoo = $select_edoo->rowCount();
			
			if ($treffer_edoo > 0) {
						if ($db->exec("UPDATE `dsa_bewerberdaten`
										   SET
											`status` = 'übertragen' WHERE `id` = '$id'")) { 
											
												
											}			
				}
			}
		
	
		

		}
		
		
	
}
?>


<style>

.box-grau {
   padding: 10px;
   background-color: #E0E0E0;
   margin-bottom: 20px;
}

* {
  box-sizing: border-box;
}

.flex-container {
  display: flex;
  flex-direction: row;
  text-align: center;
}
      .hidden {
            display: none;
        }  
		
.flex-item-left {
  padding: 10px;
  flex: 50%;
  text-align: left;
}

.flex-item-right {
  padding: 10px;
  flex: 50%;
  text-align: left;
}

.flex-item-drei {
  padding: 10px;
  flex: 33.3%;
  text-align: left;
}

/* Responsive layout - makes a one column-layout instead of two-column layout */
@media (max-width: 800px) {
  .flex-container {
    flex-direction: column;
  }
  

}



/* Tabs mit radio-Buttons */
.tabbed figure { 
   display: block; 
   margin-left: 0; 
   border-bottom: 1px solid silver;
   clear: both;
}

.tabbed > input,
.tabbed figure > div { display: none; }

.tabbed figure>div {
  padding: 20px;
  width: 100%;
  border: 1px solid silver;
  background: #fff;
  line-height: 1.5em;
  letter-spacing: 0.3px;
  color: #444;
}
#tab1:checked ~ figure .tab1,
#tab2:checked ~ figure .tab2,
#tab3:checked ~ figure .tab3,
#tab4:checked ~ figure .tab4,
#tab5:checked ~ figure .tab5,
#tab6:checked ~ figure .tab6 { display: block; }


nav label {
   float: left;
   padding: 5px 10px;
   border-top: 1px solid silver;
   border-right: 1px solid silver;
   background: hsl(214.15,28%,46%);
   color: #eee;
}

nav label:nth-child(1) { border-left: 1px solid silver; }
nav label:hover { background: hsl(214.14,28%,41%); }
nav label:active { background: #ffffff; }

#tab1:checked ~ nav label[for="tab1"],
#tab2:checked ~ nav label[for="tab2"],
#tab3:checked ~ nav label[for="tab3"],
#tab4:checked ~ nav label[for="tab4"],
#tab5:checked ~ nav label[for="tab5"],
#tab6:checked ~ nav label[for="tab6"] {
  background: white;
  color: #111;
  position: relative;
  border-bottom: none;
}

#tab1:checked ~ nav label[for="tab1"]:after,
#tab2:checked ~ nav label[for="tab2"]:after,
#tab3:checked ~ nav label[for="tab3"]:after,
#tab4:checked ~ nav label[for="tab4"]:after,
#tab5:checked ~ nav label[for="tab5"]:after,
#tab6:checked ~ nav label[for="tab6"]:after {
  content: "";
  display: block;
  position: absolute;
  height: 2px;
  width: 100%;
  background: white;
  left: 0;
  bottom: -1px;
}
</style>


<?php



$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$id'");	
		foreach($select_bew as $bew) {
			$bew_md5 = $bew['md5'];

		//$select_bil_0 = $db->query("SELECT * FROM dsa_bildungsgang WHERE id_dsa_bewerberdaten = '$id'");
			$select_bil_0 = $db->query("SELECT sf.name FROM dsa_bildungsgang bg, schulformen sf WHERE bg.md5 = '$bew_md5' AND bg.schulform = sf.kuerzel");
		
		foreach($select_bil_0 as $bil_0) {
		 
		 $sf = $bil_0['name'];
		 
		
		}

echo "<h2 style='padding: 10 0 15 20px;'><b>".$bew['nachname'].", ".$bew['vorname']."</b>  <font style='font-size: 0.8em;'> - ".$sf."</font></h2>";
	 
		 
		 



	

?>
	
<div class="tabbed">
<?php
$selectedTab = isset($_GET['tab']) ? $_GET['tab'] : 1; // Standardmäßig auf Tab 1 setzen, wenn $_GET['tab'] nicht gesetzt ist
for ($tabIndex = 1; $tabIndex <= 6; $tabIndex++) {
    // Prüfen, ob der aktuelle TabIndex dem ausgewählten Tab entspricht
    $isChecked = $selectedTab == $tabIndex ? "checked='checked'" : "";
    echo "<input $isChecked id='tab$tabIndex' type='radio' name='tabs' />";
}
?>

   
   <input id="tab2" type="radio" name="tabs" />
   <input id="tab3" type="radio" name="tabs" />
   <input id="tab4" type="radio" name="tabs" />
   <input id="tab5" type="radio" name="tabs" />
   <input id="tab6" type="radio" name="tabs" />
<?PHP

	

?>



<?PHP

?>



   <nav>
      <label for="tab1">Stammdaten</label>
      <label for="tab2">Anschriften</label>
	  <label for="tab3">Schuljahr</label>
	  <label for="tab4">Ein-/Austritt</label>
	  <?PHP
	  if ($_SESSION['admin'] == 1 OR $_SESSION['sek'] == 1) {
      echo "<label for='tab5'>Status</label>";
	  echo "<label for='tab6'>Kontakt</label>";
	  }
	  ?>
   </nav>
   
   <figure>
      <div class="tab1"> 
	  <?php
	  
	  echo "<table>";
	  echo "<tr'><td style='padding: 5;'><b>Geschlecht:</b></td>
			<td style='padding: 5;'>".$bew['geschlecht']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Familienstand:</b></td>			
			<td style='padding: 5;'> </td></tr>";
			
			
	  echo "<tr><td style='padding: 5;'><b>geboren am:</b></td>			
			<td style='padding: 5;'>".date("d.m.Y",strtotime($bew['geburtsdatum']))."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Religionszugehörigkeit:</b></td>			
			<td style='padding: 5;'>".$bew['religion']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b>Geburtsland:</b></td>			
			<td style='padding: 5;'>".$bew['geburtsland']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Geburtsort:</b></td>			
			<td style='padding: 5;'>".$bew['geburtsort']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b>Familiensprache:</b></td>			
			<td style='padding: 5;'>".$bew['muttersprache']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Staatsangehörigkeit:</b></td>			
			<td style='padding: 5;'>".$bew['staatsangehoerigkeit']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b>Zuzugsdatum:</b></td>	  
			<td style='padding: 5;'>";
			
			if ($bew['zuzug'] != "") {
			echo date("d.m.Y",strtotime($bew['zuzug']));
			}
			
			echo"</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Herkunftsland:</b></td>			
			<td style='padding: 5;'>".$bew['herkuftsland']."</td></tr>";
	  echo "</table>";
	  
	  ?>
	  </div>
      <div class="tab2">
	  <?php
	  //Art Sorgeberechte ermitteln:
	  $sorge1_art = $bew['sorge1_art'];
	  $sorge2_art = $bew['sorge2_art'];

		$select_so1 = $db_temp->query("SELECT anzeigeform FROM sorge WHERE kurzform = '$sorge1_art'");	
		foreach($select_so1 as $so1) {
		$sorge1 = $so1['anzeigeform'];
		}
		$select_so2 = $db_temp->query("SELECT anzeigeform FROM sorge WHERE kurzform = '$sorge2_art'");	
		foreach($select_so2 as $so2) {
		$sorge2 = $so2['anzeigeform'];
		}
	  
	   echo "<table>";
	   
	  echo "<tr'><td style='padding: 5;'><b>Schüler/-in</b></td>
			<td style='padding: 5;'>".$bew['vorname']." ".$bew['nachname']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>".$sorge1."</b></td>			
			<td style='padding: 5;'>".$bew['sorge1_vorname']." ".$bew['sorge1_nachname']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>".$sorge2."</b></td>			
			<td style='padding: 5;'>".$bew['sorge2_vorname']." ".$bew['sorge2_nachname']."</td></tr>";

	   
	  echo "<tr'><td style='padding: 5;'><b></b></td>
			<td style='padding: 5;'>".$bew['strasse']." ".$bew['hausnummer']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge1_strasse']." ".$bew['sorge1_hausnummer']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge2_strasse']." ".$bew['sorge2_hausnummer']."</td></tr>";
			
			
	  echo "<tr><td style='padding: 5;'></td>			
			<td style='padding: 5;'>".$bew['plz']." ".$bew['wohnort']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'></b></td>			
			<td style='padding: 5;'>".$bew['sorge1_plz']." ".$bew['sorge1_wohnort']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'></b></td>			
			<td style='padding: 5;'>".$bew['sorge2_plz']." ".$bew['sorge2_wohnort']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['telefon1']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge1_telefon1']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge2_telefon1']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['telefon2']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge1_telefon2']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'>".$bew['sorge2_telefon2']."</td></tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'><a href='mailto:".$bew['mail']."'>".$bew['mail']."</a></td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'><a href='mailto:".$bew['sorge1_mail']."'>".$bew['sorge1_mail']."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b></b></td>			
			<td style='padding: 5;'><a href='mailto:".$bew['sorge2_mail']."'>".$bew['sorge2_mail']."</td></tr>";
			
	  echo "</table>";
	  ?>
	  
	  </div>
      <div class="tab3">

		<?php
		
		echo "<table>";
		$select_bil = $db->query("SELECT * FROM dsa_bildungsgang WHERE md5 = '$bew_md5'");	
		foreach($select_bil as $bil) {
		
		
			if ($bil['schulform'] == "bs" OR $bil['schulform'] == "bfp") {
	  
			  if ($bil['schulform'] != "bfp") {
			  echo "<tr><td style='padding: 5;'><b>Beschäftigungsdauer:</b></td>
					<td style='padding: 5;'>".$bil['dauer']."</td>
					<td style='padding: 0 20;'></td>";
					
					//Ermitteln der Berufsbezeichnung:
					$beruf = $bil['beruf'];
					$select_ber1 = $db_temp->query("SELECT anzeigeform FROM berufe WHERE schluessel = '$beruf'");	
					foreach($select_ber1 as $ber1) {
						$beruf_anz = $ber1['anzeigeform'];
					}
			
					echo "<td style='padding: 5;'><b>Ausbildungsberuf:</b></td>			
					<td style='padding: 5;'>".$bil['beruf']."<p>".$beruf_anz."</p></td></tr>";
					}
					//Ermitteln der Berufsbezeichnung:
					$betrieb = $bil['betrieb'];
					$select_betr = $db_temp->query("SELECT name1 FROM betriebe WHERE kuerzel LIKE '$betrieb'");	
					foreach($select_betr as $betr) {
						$betrieb_anz = $betr['name1'];
					}
					
					
					echo "<tr><td style='padding: 5;'>";
					if ($bil['schulform'] != "bfp") {
						echo "<b>Ausbildung von</b></td>			
						<td style='padding: 5;'>".date("d.m.Y",strtotime($bil['beginn']))." <b>bis</b> ".date("d.m.Y",strtotime($bil['ende']))."</td>
						<td style='padding: 0 20;'>";
					}
					echo "</td>";
					
					echo "<td style='padding: 5;'><b>Ausbildungsbetrieb:</b></td>			
					<td style='padding: 5;'>".$bil['betrieb']."<p>".$betrieb_anz."</p></td></tr>";
			  
					
					
					
					
					if ($bil['betrieb'] == "sonstiger") {
						
			  echo "<tr'><td style='padding: 5;'><b>Name Ausbildungsbetrieb:</b></td>			
					<td style='padding: 5;'>".$bil['betrieb2']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b></b></td>			
					<td style='padding: 5;'></td></tr>";
					
					
			  echo "<tr'><td style='padding: 5;'><b>Anschrift:</b></td>			
					<td style='padding: 5;'>".$bil['betrieb_strasse']." ".$bil['betrieb_hausnummer']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b>Ort:</b></td>			
					<td style='padding: 5;'>".$bil['betrieb_plz']." ".$bil['betrieb_ort']."</td></tr>";
					
					
			  echo "<tr'><td style='padding: 5;'><b>Name Ausbilder/-in</b></td>			
					<td style='padding: 5;'>".$bil['ausbilder_vorname']." ".$bil['ausbilder_nachname']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b></b></td>			
					<td style='padding: 5;'></td></tr>";
					
				echo "<tr'><td style='padding: 5;'><b>Telefon Ausbilder/-in</b></td>			
					<td style='padding: 5;'>".$bil['ausbilder_telefon']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b>Telefon (Betrieb):</b></td>			
					<td style='padding: 5;'>".$bil['betrieb_telefon']."</td></tr>";
					
				echo "<tr'><td style='padding: 5;'><b>E-Mail Ausbilder/-in</b></td>			
					<td style='padding: 5;'><a href='mailto:".$bil['ausbilder_mail']."'>".$bew['ausbilder_mail']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b>E-Mail (Betrieb):</b></td>			
					<td style='padding: 5;'><a href='mailto:".$bil['betrieb_mail']."'>".$bew['betrieb_mail']."</td></tr>";
					
				//Wenn es noch weitere Anmeldungen gibt:
					$nachname = $bew['nachname'];
					$vorname = $bew['vorname'];
					$geburtsdatum = $bew['geburtsdatum'];
					
					$select_bew_dub = $db->query("SELECT id FROM dsa_bewerberdaten WHERE nachname = '$nachname' AND vorname = '$vorname' AND geburtsdatum = '$geburtsdatum'");	
					$treffer_bew_dub = $select_bew_dub->rowCount();
					
					if ($treffer_bew_dub > 1) {
					
					echo "<tr'><td style='padding: 20 5 5 5; color: red;'><b>Weitere Anmeldungen:</b></td></tr>";
						foreach($select_bew_dub as $dub) {
						$dub_id = $dub['id'];
						$bew_id = $bew['id'];
						//echo "<tr><td style='padding: 5;'></td></tr>";
						
						$select_bil_dub = $db->query("SELECT * FROM dsa_bildungsgang WHERE id_dsa_bewerberdaten = '$dub_id' AND id_dsa_bewerberdaten != '$bew_id'");	
						foreach($select_bil_dub as $bild_dub) {
							if ($bild_dub['prio'] != "") {
								echo "<tr><td style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b> - Priorität <b>".$bild_dub['prio']."</b></td></tr>";
							} else {
								echo "<tr><td style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b></td></tr>";
							}
						}
						}
					}
				}
			}
				
				
			if ($bil['schulform'] != "bs" AND $bil['schulform'] != "bfp" AND $bil['schulform'] != "aph") {
	  
			  if ($bil['schulform'] != "bvj" AND $bil['schulform'] != "aph") {
			  echo "<tr'><td style='padding: 5;'><b>1. Wunsch:</b></td>
					<td style='padding: 5;'>".$bil['bgy_sp1']."</td>
					<td style='padding: 0 20;'></td>
					
					<td style='padding: 5;'><b>1. Fremdsprache:</b></td>			
					<td style='padding: 5;'>".$bil['fs1']."</td>
					<td style='padding: 5;'>(".$bil['fs1_von']." bis ".$bil['fs1_bis'].")</td></tr>";
					
				if ($bil['bgy_sp2'] != "") {	
			  echo "<tr><td style='padding: 5;'><b>2. Wunsch:</b></td>			
					<td style='padding: 5;'>".$bil['bgy_sp2']."</td>
					<td style='padding: 0 20;'></td>";
				}
					
					if ($bil['fs2'] != "") {
					echo "<td style='padding: 5;'><b>2. Fremdsprache:</b></td>			
						<td style='padding: 5;'>".$bil['fs2']."</td>
					<td style='padding: 5;'>(".$bil['fs2_von']." bis ".$bil['fs2_bis'].")</td></tr>";
						}
						if ($bil['bgy_sp3'] != "") {
				echo "<tr><td style='padding: 5;'><b>3. Wunsch:</b></td>			
					<td style='padding: 5;'>".$bil['bgy_sp3']."</td>
					<td style='padding: 0 20;'></td>";
						}
					
					if ($bil['fs3'] != "") {	
					echo "<td style='padding: 5;'><b>3. Fremdsprache:</b></td>			
						<td style='padding: 5;'>".$bil['fs3']."</td>
					<td style='padding: 5;'>(".$bil['fs3_von']." bis ".$bil['fs3_bis'].")</td>";
					}
					echo "</tr>";
			  }
			  
			  if ($bil['schulform'] == "bvj") {
				  echo "<tr'><td style='padding: 5;'><b>Berufsvorbereitungsjahr</b></td></tr>";
			  }
			  
			  
				//Wenn es noch weitere Anmeldungen gibt:
					$nachname = $bew['nachname'];
					$vorname = $bew['vorname'];
					$geburtsdatum = $bew['geburtsdatum'];
					
					$select_bew_dub = $db->query("SELECT id FROM dsa_bewerberdaten WHERE nachname = '$nachname' AND vorname = '$vorname' AND geburtsdatum = '$geburtsdatum'");	
					$treffer_bew_dub = $select_bew_dub->rowCount();
					
					if ($treffer_bew_dub > 1) {
					
					echo "<tr'><td style='padding: 20 5 5 5; color: red;'><b>Weitere Anmeldungen:</b></td></tr>";
						foreach($select_bew_dub as $dub) {
						$dub_id = $dub['id'];
						$bew_id = $bew['id'];
						//echo "<tr><td style='padding: 5;'></td></tr>";
						
						$select_bil_dub = $db->query("SELECT * FROM dsa_bildungsgang WHERE id_dsa_bewerberdaten = '$dub_id' AND id_dsa_bewerberdaten != '$bew_id'");	
						foreach($select_bil_dub as $bild_dub) {
						
						if ($bild_dub['prio'] != "") {
								echo "<tr><td style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."&tab=3'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b> - Priorität <b>".$bild_dub['prio']."</b></td></tr>";
							} else {
								echo "<tr><td style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."&tab=3'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b></td></tr>";
							}
							}
						}
					}
					
			}		
					
					
		}	
	  echo "</table>";
	  
		
	  ?>


	  </div>
	  <div class="tab4">
 <?php
	  
	  echo "<table>";
	  echo "<tr'><td style='padding: 5;'><b>Anmeldung am:</b></td>
			<td style='padding: 5;'>".date("d.m.Y",$bil['time'])."</td>
			<td style='padding: 0 20;'></td>
			
			<td style='padding: 5;'><b>Vor Eintritt besuchte Jgst.:</b></td>			
			<td style='padding: 5;'>".$bew['jahrgang']."</td></tr>";
			
			
	  echo "<tr><td style='padding: 5;'><b>vor Eintritt besuchte Schule:</b></td>			
			<td style='padding: 5;'>".$bew['schulname']."</td>
			<td style='padding: 0 20;'></td>
			
			</tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b>vor Eintritt besuchte Schulart:</b></td>			
			<td style='padding: 5;'>".$bew['schulart']."</td>
			<td style='padding: 0 20;'></td>
			
			</tr>";
			
			
	  echo "<tr'><td style='padding: 5;'><b>Höchster Abschluss:</b></td>			
			<td style='padding: 5;'>".$bew['abschluss']."</td>
			<td style='padding: 0 20;'></td>
			
			</tr>";
	  echo "</table>";
	  ?>




	  </div>
	  <div class="tab5">
	  <?PHP
		 		//Gespeicherte Werte abrufen:
		
					$select_vo = $db->query("SELECT * FROM vorgang WHERE id_dsa_bewerberdaten = '$id'");	
					foreach($select_vo as $vo) {		
					$dok_erfahrung = $vo['dok_erfahrung'];
					$dok_zeugnis = $vo['dok_zeugnis'];
					$dok_lebenslauf = $vo['dok_lebenslauf'];
					$dok_ausweis = $vo['dok_ausweis'];
					$bemerkungen = $vo['bemerkungen'];
					$last_time = $vo['last_time'];
					$last_user = $vo['last_user'];
					
					}
					
					//Für versendete Emails:
					$select_em = $db->query("SELECT * FROM mail WHERE id_dsa_bewerberdaten = '$id' ORDER BY last_time ASC");	
					foreach($select_em as $em) {		
					
					$em_mailtext = $em['mailtext'];
					$em_last_time = $em['last_time'];
					$em_last_user = $em['last_user'];
					
					}
		
		
			

		?>
<div class='flex-container'>
	<div class='flex-item-left'>
	<form id="inputForm1" action="datenblatt.php?id=<?PHP echo "$id" ?>" method="post">
	
	<input type='hidden' name='id' value='<?PHP echo "$id" ?>'>
		<table>
		<tr>
		<td style='padding-bottom: 3px;'><b>Bemerkungen:</b></td><tr>

		<tr>
		<td colspan='3'><label><textarea style='width:100%;' name='bemerkungen' cols='60' rows='6'><?PHP echo "$bemerkungen" ?></textarea></p></label>
		</tr>


		<tr><td><input class='btn btn-default btn-sm' type="submit" id="inputForm1" name="submit" value="speichern" />
		<?PHP
		if ($last_user != "") {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<small><i>Zuletzt ".date('d.m.Y, H:m', $last_time)." Uhr gespeichert von ".$last_user.".</i></small>";
		}
		?>
		</td>
		
		<td>

		</td>
		</tr>
		</table>
		</div>
		
		<div class='flex-item-left' style='padding-left: 20pxf'>
		<?PHP
		if ($bil['schulform'] != "bs") {
		?>
		
		<table>
		<tr>
		<td style='padding: 5px;' colspan='2'><b>Eingereichte Unterlagen:</b></td>
		</tr>



		<tr><td style='padding: 5px;'>
		<?php
			if ($dok_zeugnis == 1) {
				echo "<input style='padding: 10px;' type='checkbox' id='1' name='dok_zeugnis' value='1' checked>";
			} else {
				echo "<input style='padding: 10px;' type='checkbox' id='1' name='dok_zeugnis' value='1'>";
			}
			echo "</td><td style='padding: 5px;'>";
			echo "Zeugnisse";
			?>
			
			</td>
			</tr>
			<?php
			
			echo "<tr><td style='padding: 5px;'>";
			
			if ($dok_lebenslauf == 1) {
				echo "<input style='padding: 10px;' type='checkbox' id='2' name='dok_lebenslauf' value='1' checked>";
			} else {
				echo "<input style='padding: 10px;' type='checkbox' id='2' name='dok_lebenslauf' value='1'>";
			}
			echo "</td><td style='padding: 5px;'>";
			echo "Lebenslauf";
			?>
			
			</td>
			</tr>
			<?php
			
			echo "<tr><td style='padding: 5px;'>";
			
			if ($dok_ausweis == 1) {
				echo "<input style='padding: 10px;' type='checkbox' id='3' name='dok_ausweis' value='1' checked>";
			} else {
				echo "<input style='padding: 10px;' type='checkbox' id='3' name='dok_ausweis' value='1'>";
			}
			echo "</td><td style='padding: 5px;'>";
			echo "Personalausweis / Meldebescheinigung";
			?>
			
			</td>
			</tr>
			<?php
			if ($bil['schulform'] == "bos1" OR $bil['schulform'] == "bos2" OR $bil['schulform'] == "dbos" OR $bil['schulform'] == "fs" OR $bil['schulform'] == "fsof") {
			
			echo "<tr><td style='padding: 5px;'>";
			
			if ($dok_erfahrung == 1) {
				echo "<input style='padding: 10px;' type='checkbox' id='4' name='dok_erfahrung' value='1' checked>";
			} else {
				echo "<input style='padding: 10px;' type='checkbox' id='4' name='dok_erfahrung' value='1'>";
			}
			echo "</td><td style='padding: 5px;'>";
			echo "Berufsabschluss bzw. Berufserfahrung";
			
			
			echo "</td></tr>";
			
			}
		}
		
				//Wenn es noch weitere Anmeldungen gibt:
					$nachname = $bew['nachname'];
					$vorname = $bew['vorname'];
					$geburtsdatum = $bew['geburtsdatum'];
					
					$select_bew_dub = $db->query("SELECT id FROM dsa_bewerberdaten WHERE nachname = '$nachname' AND vorname = '$vorname' AND geburtsdatum = '$geburtsdatum'");	
					$treffer_bew_dub = $select_bew_dub->rowCount();
					
					if ($treffer_bew_dub > 1) {
					
					echo "<tr'><td colspan='2' style='padding: 20 5 5 5; color: red;'><b>Speichern und Unterlagen ggf. auch hier prüfen:</b></td></tr>";
						foreach($select_bew_dub as $dub) {
						$dub_id = $dub['id'];
						$bew_id = $bew['id'];
						//echo "<tr><td style='padding: 5;'></td></tr>";
						
						$select_bil_dub = $db->query("SELECT * FROM dsa_bildungsgang WHERE id_dsa_bewerberdaten = '$dub_id' AND id_dsa_bewerberdaten != '$bew_id'");	
						foreach($select_bil_dub as $bild_dub) {
							if ($bild_dub['prio'] != "") {
								echo "<tr><td colspan='2' style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."&tab=5'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b> - Priorität <b>".$bild_dub['prio']."</b></td></tr>";
							} else {
								echo "<tr><td colspan='2' style='padding: 5;'><a href='./datenblatt.php?id=".$bild_dub['id_dsa_bewerberdaten']."&tab=5'>Anmeldung für <b>".strtoupper($bild_dub['schulform'])."</b></td></tr>";
							}
						//Auch Dokumente in anderen Bildungsgängen markieren:
						echo "<input type='hidden' name='id_".$bild_dub['prio']."' value='".$bild_dub['id_dsa_bewerberdaten']."'>";
						}
						}
					}
?>
		</table>
		
		</form>
		
		<?PHP
		if ($em_last_user != "") {
		//echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		
		echo "<p><small><i><a href='./mail_verlauf.php?id=".$id."' target='fenster' onclick=\"window.open('./mail_verlauf.php', 'fenster', 'width=800,height=450,top=200,left=100,status,resizable,scrollbars')\"><b>Letzte E-Mail</b></a> versendet am ".date('d.m.Y, H:m', $em_last_time)." Uhr von ".$em_last_user.".</i></small></p>";
		//echo "<a href='./mail_verlauf.php' target='fenster' onclick=\"window.open('./mail_verlauf.php', 'fenster', 'width=600,height=450,status,resizable,scrollbars')\">Link</a>";

		
		}
		?>
		</div>
</div>


	  </div>
	  
	  <div class="tab6">
	  <?PHP
if (!isset($_POST['submit_senden'])) {


//Schüler bereits volljährig?
if (strtotime($bew['geburtsdatum']) < (time() - 18*365*24*3600)) {
	$volljaehrig = 1;
} else {
	$volljaehrig = 0;
}


$cc = "";
if ($volljaehrig == 0) {
	if ($bew['sorge1_mail'] != "" AND $bew['sorge2_mail'] == "") {
		$cc = $bew['sorge1_mail'];
	}
	if ($bew['sorge1_mail'] != "" AND $bew['sorge2_mail'] != "") {
		$cc = $bew['sorge1_mail'].";".$bew['sorge2_mail'];
	}
}

if ($bew['geschlecht'] == "W") {
$bodytext = "Sehr geehrte Frau ".trim($bew['nachname']).",\n";
}

if ($bew['geschlecht'] == "M") {
$bodytext = "Sehr geehrter Herr ".trim($bew['nachname']).",\n";
}
if ($bew['geschlecht'] == "D" OR $bew['geschlecht'] == "O") {
$bodytext = "Guten Tag ".$bew['vorname']." ".$bew['nachname'].",\n";
}

$bodytext .= "\n";

if ($bil['schulform'] != "bs" AND ($dok_ausweis != 1 OR $dok_lebenslauf != 1 OR $dok_zeugnis != 1 OR ($dok_erfahrung != 1 AND ($bil['schulform'] == "fs" OR $bil['schulform'] == "fsof" OR $bil['schulform'] == "bos1" OR $bil['schulform'] == "bos2" OR $bil['schulform'] == "dbos")))) {
$bodytext .= "um Ihre Anmeldung bearbeiten zu können fehlen uns noch die folgenden Unterlagen:\n";

$bodytext .= "\n";
	if ($dok_ausweis != 1) {
		$bodytext .= "- Personalausweis oder Meldebescheinigung \n";
	}
	
	if ($dok_lebenslauf != 1) {
		$bodytext .= "- Lebenslauf \n";
	}
	
	if ($dok_zeugnis != 1) {
		$bodytext .= "- Zeugnis \n";
	}
	
	if ($dok_erfahrung != 1 AND ($bil['schulform'] == "fs" OR $bil['schulform'] == "fsof" OR $bil['schulform'] == "bos1" OR $bil['schulform'] == "bos2" OR $bil['schulform'] == "dbos")) {
		$bodytext .= "- Nachweis Ihrer Berufserfahrung \n";
	}
$bodytext .= "\n";
$bodytext .= "Bitte lassen Sie uns die fehlenden Unterlagen umgehend per Post oder persönlich zukommen!\n";

} else {

$bodytext .= "\n";
$bodytext .= "\n";

$bodytext .= "\n";
}
$bodytext .= "\n";
$bodytext .= "\n";
$bodytext .= "Mit freundlichen Grüßen \n";
$bodytext .= "\n";
$bodytext .= "i.A. ".$_SESSION['firstname']." ".$_SESSION['lastname']." \n";
$bodytext .= "\n";
$bodytext .= "_______________________________________________ \n";
$bodytext .= $schule_name_zeile1."\n";
$bodytext .= $schule_name_zeile2."\n";
$bodytext .= "\n";
$bodytext .= $schule_strasse_nr."\n";
$bodytext .= $schule_plz_ort."\n";
$bodytext .= "\n";
$bodytext .= $schule_tel."\n";
$bodytext .= $schule_fax."\n";
$bodytext .= "\n";
$bodytext .= $schule_mail."\n";
$bodytext .= $schule_url."\n";



//Kopfzeilen (optisch):
echo "<table>";
//echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td style='min-width: 3em;'><b>An:</b></td><td style='max-width: 60em;'>".$bew['mail']." <small><i>(Schüler/-in)</i></small></td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td style='min-width: 3em;'><b>Cc:</b></td><td style='max-width: 60em;'>".str_replace(";","; ",$cc)."</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td style='min-width: 3em;'><b>Betreff:&nbsp;</b></td><td style='max-width: 60em;'>Ihre Anmeldung an der Berufsbildenden Schule 1 - Mainz</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "</table>";


} //Ende wenn kein submit

//Mailversand:
	if (isset($_POST['submit_senden'])) {
		
		

		
		
		$bodytext = $_POST['bodytext_final'];
		$empfaenger = $_POST['empfaenger'];
		$cc = $_POST['cc'];
		
		



		$header = [];
		$header[] = 'MIME-Version: 1.0';
		$header[] = 'Content-type: text/plain; charset=utf-8';
		$header[] = 'From: Service BBS1 Mainz <service@bbs1-mainz.de>';
		$header[] = "Reply-To: ".$_SESSION['mail'];
		$header[] = 'Content-Transfer-Encoding: 8bit';
		$header[] = "cc: ".$cc;
		$header[] = "bc: ".$_SESSION['mail'];
		
		$betreff = "Ihre Anmeldung an der Berufsbildenden Schule 1 - Mainz";

		mail($empfaenger, $betreff, $bodytext, implode("\r\n", $header));
		
		//echo "<p><b>Ihre Nachricht wurde versendet!</b></p>";
		
	} else {
	
	
	echo "<form id='form_mail' method='post' action='./datenblatt.php?id=".$id."'>";
	
	//Mailtext:
	echo "<label><textarea name='bodytext_final' cols='100'  rows='15'>".$bodytext."</textarea></p></label>";


	//echo "<input type='hidden' name='bodytext_final' value='".$bodytext_final."'>";
	echo "<input type='hidden' name='empfaenger' value='".$bew['mail']."'>";
	echo "<input type='hidden' name='cc' value='".$cc."'>";

	echo "<br>";
	echo "<input class='btn btn-default btn-sm' name='submit_senden' type='submit' value='E-Mail versenden'>";

echo "</form>";
	}
	  
	  
	  ?>
	  
	  </div>
   </figure>
</div>


<?php
		}

?>


<table>

<tr height="50"></tr>
<tr>

<?php
if ($_GET['back'] == "todo") {

echo "<td>
<form method='post' action='./todo.php?id=".$bew['id']."'>";
?>
<input class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="zurück" />
</form>
</td>
<?php
} else {
?>
<td>
<form method="post" action="./liste.php">
<input class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="zurück" />
</form>
</td>
<?php

}

if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1) {
$bew_md5 = $bew['md5'];	
$select_md5 = $db_temp->query("SELECT * FROM summen WHERE (md5 = '$bew_md5')");
$treffer_md5 = $select_md5->rowCount();

	if ($treffer_md5 > 0) {	
		echo "<td style='padding-left: 8em;'>";
		echo "<form method='post' action='./md5_loeschen.php?id=".$bew['id'].".php'>";
		echo "<input class='btn btn-default btn-sm' type='submit' style='background-color: red; border: 0;' name='cmd[doStandardAuthentication]' value='weitere Anmeldung für ".strtoupper($bil['schulform'])." zulassen' />";
		echo "</form>";
		echo "</td>";
	}
	
	echo "<td style='padding-left: 8em;'>";
echo "<form method='post' action='./pdf.php?id=".$bew['id'].".php'>";
echo "<input class='btn btn-default btn-sm' type='submit' border: 0;' name='cmd[doStandardAuthentication]' value='Anschreiben drucken' />";
echo "</form>";
echo "</td>";
	
}

if ($_SESSION['admin'] == 1) {
echo "<td style='padding-left: 8em;'>";
echo "<form method='post' action='./anmeldung_loeschen.php?id=".$bew['id'].".php'>";
echo "<input class='btn btn-default btn-sm' type='submit' style='background-color: red; border: 0;' name='cmd[doStandardAuthentication]' value='Anmeldung löschen' />";
echo "</form>";
echo "</td>";

}
?>

</tr>
<tr height="20"></tr>
<tr>
<td>
<form method="post" action="./logout_ad.php">
<input type="submit" name="cmd[doStandardAuthentication]" value="Abmelden" />
</form>
</td>
</tr>


</table>

<?php


} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=liste");

}

include("./fuss.php");

?>
