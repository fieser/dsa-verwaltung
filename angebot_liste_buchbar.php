<?php

include("./kopf.php");
include "./config.php";

date_default_timezone_set('Europe/Berlin');



@session_start();


// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



// Verbindung zur Datenbank aufbauen.
include "./verbinden.php";
include "./rechte.php";


?>
<h1>Aktuelle Schulungsangebote</h1>
<br>
<table>
<tr>


<td rowspan='2'><form id='form1' action="./angebot_liste_buchbar.php" method="POST">
<label>
<p><b>Filterung nach Bereich:</b></p>
<select name="bereich" style="width:340px;"size="1" onchange="change1()">
<?php

if (isset($_POST['bereich'])) {
	$filter = $_POST['bereich'];
	$_SESSION['filter_bereich'] = $_POST['bereich'];
} else {
	if ($_SESSION['filter_bereich'] == "") {
		$filter = 0;
	} else {
		$filter = $_SESSION['filter_bereich'];
	}
}


if (isset($filter)){
$select_be_alt = $db->query("SELECT * FROM bereiche WHERE (`id` = $filter)");
}
$select_be = $db->query("SELECT * FROM bereiche");

//Aktuelle Auswahl:



foreach ($select_be_alt as $be_alt) {

		$be_art_alt = $be_alt['art'];
		$be_id_alt = $be_alt['id'];
		$be_bereich_alt = $be_alt['bereich'];
		
		
				echo "<option value='".$be_id_alt."'>".$be_bereich_alt."</option>";
			
		
	}
?>
<option value='0'>alle</option>
<?php
// Auswahlliste

	foreach ($select_be as $be) {

		$be_art = $be['art'];
		$be_id = $be['id'];
		$be_bereich = $be['bereich'];
		


		echo "<option value='".$be_id."'>".$be_bereich."</option>";

			
	}
?>
</select> </label>

</form>
		<script>
		function change1(){
			document.getElementById("form1").submit();
		}
		</script>
</td>
</tr>

</table>

<?php


//Termine auflisten
if (isset($filter) AND $filter != "0") {
		
$select_ag = $db->query("SELECT * FROM angebot WHERE (`bereich`='$filter' AND `veroeffentlicht` = '1') ORDER BY thema, dozent ASC");
$select_ag = $db->query("SELECT * FROM termine LEFT JOIN angebot ON termine.angebot = angebot.id WHERE (angebot.bereich = '$filter' AND angebot.veroeffentlicht = '1') ORDER BY termine.datum, termine.zeit_von, angebot.thema ASC");

} else {

	$select_ag = $db->query("SELECT * FROM termine LEFT JOIN angebot ON termine.angebot = angebot.id WHERE (angebot.veroeffentlicht = '1') ORDER BY termine.datum, termine.zeit_von, angebot.thema ASC");
}






	
	//Start Akkordeon
	echo "<div class='wrap'>";
	echo "<div class='accordion'>";  

//Zeilen ausgeben:
	foreach ($select_ag as $ag) {
		
		//Wenn nicht in der Vergangenheit:
		
		
		
		if (strtotime($ag['datum']." ".$ag['zeit_bis']) > time() AND ($_SESSION['gruppe'] != "schueler" OR $ag['zg_schueler'] == 1)) {
		
		
		/*
		echo "<pre>";
		print_r($ag);
		echo "</pre>";
		*/
		
		$ag_id = $ag['id'];
		$be_id = $ag['bereich'];
		$thema = $ag['thema'];
		$beschreibung = $ag['beschreibung'];
		
		

		
		
		if ($ag['wunsch'] == "1") {
			
			$wunsch = "<b>ja</b>";
		} else {
			$wunsch = "nein";
		}
		//Sucht Bereichsbezeichnung zur BereichtsID:
		$select_be = $db->query("SELECT * FROM bereiche WHERE (id = '$be_id')");
			foreach ($select_be as $be) {
				$bereich = $be['bereich'];
			}
		// Spalte Status
		$id_termin = $ag['0'];
		
		$select_tn = $db->query("SELECT * FROM teilnehmer  WHERE (termin = '$id_termin')");
		$treffer_tn = $select_tn->rowCount();
		
		$frei = ($ag['max_teilnehmer'] - $treffer_tn);
		
		//Wenn noch nicht begonnen:
		if (strtotime($ag['datum']." ".$ag['zeit_von']) > time()) {
		
			if ($frei > 0) {
				if ($frei > 1) {
					$status = "<b><font color='green'>".$frei." freie Plätze</font></b>";
				} else {
					$status = "<b><font color='orange'>".$frei." freier Platz</font></b>";
				}
			} else {
				$status = "<b><font color='red'>ausgebucht</font></b>";
			}
		} else {
			$status = "<b><font color='red'>bereits begonnen</font></b>";
		}
		
				//Datumsformat umbauen
				$time_tmj = strtotime($ag['datum']);
				$datum_tmj = date("d.m.Y", $time_tmj);
				
				$wochentage = array("So", "Mo", "Di", "Mi", "Do", "Fr", "Sa");
				$tag = $wochentage[date("w", $time_tmj)];
	
	
        
    echo "<div>";
       echo "<input type='radio' name='acc' id='".$ag['0']."' checked=''>";
	   
	   //Überschrift
       echo "<label for='".$ag['0']."'>".$tag.", ".$datum_tmj." - <b>".$ag['thema']."</b></label>";
 
	   //Inhalt
       echo "<p class='panel'>";
	   
	   echo "<table>";
	   
	   echo "<tr><td style='padding: 10px;' align='left'><b>Thema:</b></td>";
	   echo "<td colspan='3' style='padding: 10px;' align='left'>".$ag['thema']."</td></tr>";
	   
	   if ($ag['zertifikat_text'] != "") {
		   $zertifikat_text = substr(str_replace("- ", "<br>&bull; ",$ag['zertifikat_text']),4);
		   
		   
	   echo "<tr><td style='padding: 10px;' align='left'><b>Inhalte:</b></td>";
	   echo "<td colspan='3' style='padding: 10px;' align='left'>".$zertifikat_text."</td></tr>";
	   }
	   if ($ag['beschreibung'] != "") {
	   echo "<tr><td style='padding: 10px;' align='left'><b>Infos zur Veranstaltung:</b></td>";
	   echo "<td colspan='3' style='padding: 10px;' align='left'>".$beschreibung."</td></tr>";
	   }
	   
	   echo "<tr><td style='padding: 10px;' align='left'><b>Termin:</b></td>";

		//Termin
		echo "<td colspan='3' style='padding: 10px;' align='left'><nobr>".$tag.", ".$datum_tmj."</nobr>, <nobr>".$ag['zeit_von']." - ".$ag['zeit_bis']." Uhr</nobr></td></tr>";
		
		//Dozenten
		echo "<tr><td style='padding: 10px;' align='left'><b>Dozent/-innen:</b></td>";
	  	$select_dz = $db->query("SELECT * FROM dozent_angebot WHERE (`angebot`='$ag_id') ORDER BY dozent ASC");
		echo "<td colspan='2' style='padding: 10px;' align='left'>";
		foreach($select_dz as $dz) {
			echo $dz['dozent']."<br>";
		}
		echo "</td></tr>";
		
		//Form
		echo "<tr><td style='padding: 10px;' align='left'><b>Form:</b></td>";
		echo "<td colspan='3' style='padding: 10px;' align='left'>".$ag['form']."</td>";
		
		//Ort und Raum
		if ($ag['form'] == "präsent") {
			
			if ($ag['ort'] != "") {
				echo "<tr><td style='padding: 10px;' align='left'><b>Ort:</b></td>";
			echo "<td colspan='3' style='padding: 10px;' align='left'>".$ag['ort']."</td></tr>";
			}
			
			echo "<tr><td style='padding: 10px;' align='left'><b>Raum:</b></td>";
			if ($ag['raum'] != "") {
			if ($ag['raum'] != "B 2") {
					echo "<td colspan='3' style='padding: 10px;' align='left'>".$ag['raum']."</td></tr>";
				} else {
					echo "<td colspan='3' style='padding: 10px;' align='left'>".$ag['raum']." (Aula)</td></tr>";
				}
			} else {
				echo "<td colspan='3' style='padding: 10px;' align='left'><i>noch nicht festgelegt</i></td></tr>";
			}
		} else {
			echo "<tr><td style='padding: 10px;' align='left'><b>Link zum Kurs:</b></td>";
			if ($ag['link'] != "") {
			echo "<td colspan='3' style='padding: 10px;' align='left'><a href='".$ag['link']."' target='_blank'>".$ag['link']."</a></td></tr>";
			} else {
				echo "<td colspan='3' style='padding: 10px;' align='left'><i>noch nicht verfügbar</i></td></tr>";
			}
		}

		echo "</tr>";
		
		//Status
		echo "<tr><td style='padding: 10px;' align='left'><b>Status:</b></td>";
		echo "<td colspan='3' style='padding: 10px;' align='left'>".$status."</td></tr>";
		
		echo "<tr><td></td><td style='padding: 10px;' align='left'>";
		
			//Auf bereits vorhandene Anmeldung prüfen:
	// Lehrerdaten werden aus Temp-Datei geholt (Datei wird täglich aus Webuntis generiert)

		$teachers = unserialize(file_get_contents("../antrag/json/daten/lehrer.ext"));

		$anzahl_2 = count($teachers['result']);
		
		for ($n_2=0; $n_2<$anzahl_2; $n_2++) {

			if ($teachers['result'][$n_2]['name'] == $_SESSION['kuerzel']) {
				
				$l_kuerzel = $teachers['result'][$n_2]["name"];
			$l_vorname = $teachers['result'][$n_2]["foreName"];
			$l_nachname = $teachers['result'][$n_2]["longName"];
			$doz = $l_nachname.", ".$l_vorname;
			$l_id = $teachers['result'][$n_2]["id"];
			
			}
		}
	
	$termin = $ag['0'];
	$select_tn = $db->query("SELECT * FROM teilnehmer WHERE (`termin`='$termin' AND `nachname` = '$l_nachname' AND `vorname` = '$l_vorname')");	
	$treffer_tn	 = $select_tn->rowCount();		

	if ($treffer_tn != 0) {
		echo "<i>Sie sind bereits<br>angemeldet.</i>";
	} else {
		
		//echo "<form id='form3' action='./angebot_teilnehmen.php' method='POST'>";
		//echo "<input type='hidden' name='id' value='".$ag['0']."'>";
		//echo "<input type='hidden' name='ag_id' value='".$ag['id']."'>";
		if ($frei > 0) {
		echo "<input class='btn btn-default btn-sm' type='submit' name='submit_teilnehmen' value='teilnehmen'>";
		echo "<a class='btn btn-default btn-sm' style='width: 6.5em;' href='./angebot_teilnehmen.php?id=".$ag['0']."&ag_id=".$ag['id']."'>anmelden</a>";
		//echo "<a class='btn btn-default btn-sm' formmethod='post' formaction='./angebot_teilnehmen.php' href='./angebot_teilnehmen.php'>anmelden</a>";
		} else {
			if ($warteliste_aktiv == 1 OR $_SESSION['admin'] == "true") {
			echo "<a class='btn btn-default btn-sm' style='width: 6.5em;' href='./warteliste.php?id=".$ag['0']."&ag_id=".$ag['id']."'>Warteliste</a>";
			}
		}
		echo "</form>";
	}
	echo "</td>";
	
		//Kalendereintrag
		if (file_exists($temp_verz."kalenderimport_".$ag['0'].".ics")) {
		echo "<td style='padding: 10px;' align='left'><a class='btn btn-default btn-sm' href='".$temp_verz."kalenderimport_".$ag['0'].".ics' title='exportieren'>Kalendereintrag</a></td>";
		}
	
	echo "</tr><tr><td></td>";
	
		//Änderungslink nur für Admins
		if ($_SESSION['admin'] == "true") {
		echo "<td style='padding: 10px;' align='left'><a class='btn btn-default btn-sm' style='width: 6.5em;' href='./angebot_aendern.php?id=".$ag['id']."&back=angebot_liste_buchbar'>bearbeiten</a></td>";
		}

		//Änderungslink für Dozenten
		$select_dz = $db->query("SELECT * FROM dozent_angebot WHERE (`angebot`='$ag_id' AND `dozent`='$doz') ORDER BY dozent ASC");
		$treffer_dz = $select_dz->rowCount();
		
		if ($_SESSION['admin'] == "false" AND $treffer_dz != 0 AND $doz_ag_aendern == 1) {
		$_SESSION['doz_ag'] = $ag_id; //User erhält temorär eingeschränkte Änderungsrechte für dieses Schulungsangebot
		echo "<td style='padding: 10px;' align='left'><a class='btn btn-default btn-sm' style='width: 6.5em;' href='./angebot_aendern.php?id=".$ag['id']."&back=angebot_liste_buchbar'>bearbeiten</a></td>";
		}
		
		
		
		//Teilnehmerliste nur für Dozenten
		if ($treffer_dz != 0) {
		echo "<td style='padding: 10px;' align='left'><a class='btn btn-default btn-sm' href='./teilnehmer_liste.php?ag_id=".$ag['id']."&te_id=".$ag['0']."&back=angebot_liste_buchbar'>Teilnehmer</a></td>";
		}
		
	echo "</tr>";
		
	   
	   echo "</table>";
	   
	   echo "</p>";
	   
	   
      echo "</div>";
	  

	//Hovereffekt:
	echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='d3d3d3'\" onmouseout=\"this.style.backgroundColor=''\">";

		
	echo "<td style='padding: 10px;' align='left'>";
	

	
	
	//echo "<td style='padding: 10px;' align='left'><a href='./angebot_teilnehmen.php?id=".$ag['0']."&ag_id=".$ag['id']."'>teilnehmen</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	
	
	echo "</tr>";
		
	}
	
	// Vorbereitung für Leerzeile vor neuem Berater:
	$berater_alt = $ag['berater'];
	
	echo "<tr style='border-bottom-style: solid; border-color: f0f0f0; border-width: 5px'>";

	}
	// Ende Akkordeon
	echo "</div></div>";
	

?>
<table>

<tr height="50"></tr>
<tr>
<td>
<form method="post" action="./index.php">
<input class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Menü" />
</form>
</td>
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
   header("Location: ./login_ad.php?back=angebot_liste_buchbar");

}

include("./fuss.php");

?>
