<?php

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



// Verbindung zur Datenbank aufbauen.

include "./rechte.php";
include "./config.php";

include "./temp2db.php";


//Sortierung vorbereiten:
if (isset($_GET['sort'])) {
	
	$_SESSION['sort'] = $_GET['sort'];
}

if (!isset($_SESSION['sort'])) {
	$_SESSION['sort'] = "nachname";
}

$sort = $_SESSION['sort'];

//Filter vorbereiten:
if (isset($_POST['f_nachname'])) {
$_SESSION['f_nachname'] = $_POST['f_nachname'];
}
if (isset($_POST['f_vorname'])) {
$_SESSION['f_vorname'] = $_POST['f_vorname'];
}
if (isset($_POST['f_geburtsdatum'])) {
$_SESSION['f_geburtsdatum'] = $_POST['f_geburtsdatum'];
}
if (isset($_POST['f_schulform'])) {
$_SESSION['f_schulform'] = $_POST['f_schulform'];
}
if (isset($_POST['f_beruf'])) {
$_SESSION['f_beruf'] = $_POST['f_beruf'];
}
if (isset($_POST['f_status'])) {
$_SESSION['f_status'] = $_POST['f_status'];
}

$f_nachname = $_SESSION['f_nachname'];
$f_vorname = $_SESSION['f_vorname'];
$f_geburtsdatum = $_SESSION['f_geburtsdatum'];
$f_schulform = strtolower($_SESSION['f_schulform']);
$f_beruf = $_SESSION['f_beruf'];
$f_status = $_SESSION['f_status'];

?>
<h1>Anmeldungen Bewerberinnen und Bewerber</h1>
<table>
<tr>
<td style='padding-top: 2em;'>
<form method="post" action="./index.php">
<input class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Menü" />
</form>
<td style='padding-top: 2em; padding-left: 2em;'>
<?php
echo "<form id='form2' action='./export_csv.php' method='POST'>";

if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1) {
echo "<input style='width: 100%' class='btn btn-default btn-sm' type='submit' name='submit_filter' value='Transfer'>";
}

echo "<input type='hidden' name='f_nachname' value='".$_SESSION['f_nachname']."'>";


echo "<input type='hidden' name='f_vorname' value='".$_SESSION['f_vorname']."'>";


echo "<input type='hidden' name='f_geburtsdatum' value='".$_SESSION['f_geburtsdatum']."' disabled>";


echo "<input type='hidden' name='f_schulform' value='".$_SESSION['f_schulform']."'>";

echo "<input type='hidden' name='f_beruf' value='".$_SESSION['f_beruf']."'>";

echo "<input type='hidden' name='f_status' value='".$_SESSION['f_status']."'>";



echo "</form>";

?>
</td>
</tr>


		<script>
		function change1(){
			document.getElementById("form1").submit();
		}
		</script>

<?php
		 
@session_start();

		

	


echo "<table style='color: black; background-color: #E0E0E0; line-height: 1.5em;'>";
//echo "<p><tr>";

$style_kopf = "padding: 10px; border: 5px solid; border-color: #E0E0E0; background-color: #ffffff;";

echo "<tr class='fest'>";
echo "<td style='".$style_kopf."'><a href='./liste.php?sort=time'><b>Anmeldung</b></a></td>";
echo "<td style='".$style_kopf."'><a href='./liste.php?sort=nachname'><b>Nachname</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=vorname'><b>Vorname</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=geburtsdatum'><b><nobr>Geburtsdatum</nobr></b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=schulform'><b>Schulform</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=beginn'><b>ab</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=beruf'><b>Beruf/Schwerpunkt</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=status'><b>Status</b></a></td>";


echo "</tr>";


//Filterzeile


echo "<form id='form2' action='./liste.php' method='POST'>";
echo "<tr>";

echo "<td style='padding: 0 3 0 3px;' align='left'><input style='width: 100%' class='btn btn-default btn-sm' type='submit' name='submit_filter' value='filtern'></td>";

echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_nachname' value='".$_SESSION['f_nachname']."'>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_vorname' value='".$_SESSION['f_vorname']."'>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_geburtsdatum' value='".$_SESSION['f_geburtsdatum']."' disabled>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_schulform' value='".$_SESSION['f_schulform']."'>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_ab' value='".$_SESSION['f_ab']."' disabled>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_beruf' value='".$_SESSION['f_beruf']."'>";
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_status' value='".$_SESSION['f_status']."'>";
echo "</td>";


echo "</tr>";
/*
<label>

<select name='bereich' style='width:340px;'size='1' onchange='change1()'>
</select>
*/
echo "</form>";


	
		$datum_von = date("Y-m-d");

	//Mit Filter
	$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 WHERE schulform LIKE '%$f_schulform%' AND status LIKE '%$f_status%' AND nachname LIKE '%$f_nachname%' AND vorname LIKE '%$f_vorname%' AND ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs')) GROUP BY dsa_bewerberdaten.id ORDER BY {$sort} ASC");
	
	$treffer_an = $select_an->rowCount();
	



foreach($select_an as $an) {
	$id = $an['0']; //Bewerberdaten
	$id_bil = $an['id']; //Bildungsgang
	$md5_bew = $an['md5']; //Bewerberdaten md5
	$md5_bil = $an['49']; //Bildungsgang md5
	
	//Verknüpfung über ID reparieren (notwendig, wegen db_temp zu db)
	if ($an['id_dsa_bewerberdaten'] == 0) {
						if ($db->exec("UPDATE `dsa_bildungsgang`
								   SET
									`id_dsa_bewerberdaten` = '$id' WHERE `md5` = '$md5_bil'")) { 
									
										
									}
	}
	
	
	
	/*
	echo "<pre>";
	print_r ( $an );
	echo "</pre>";
	*/
	
	//Wenn es noch weitere Anmeldungen gibt:
	$nachname = trim($an['nachname']);
	$vorname = trim($an['vorname']);
	$geburtsdatum = trim($an['geburtsdatum']);
	
	$select_bew_dub = $db->query("SELECT id FROM dsa_bewerberdaten WHERE nachname = '$nachname' AND vorname = '$vorname' AND geburtsdatum = '$geburtsdatum'");	
	$treffer_bew_dub = $select_bew_dub->rowCount();
	
	
	
	
	//Hovereffekt:
	
	echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='d3d3d3'\" onclick=\"window.open('./datenblatt.php?id=".$id."&md=".$md5_bew."','Fenster')\" onmouseout=\"this.style.backgroundColor=''\">";
	echo "<td style='padding: 10px;' align='left'>".date("d.m.Y",$an['time'])."</td>";
	echo "<td style='padding: 10px;' align='left'>".$an['nachname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".$an['vorname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".date("d.m.Y",strtotime($an['geburtsdatum']))."</td>";
	
	if ($treffer_bew_dub > 1 AND $an['prio'] != "") {
		echo "<td style='padding: 10px;' align='left'>".strtoupper($an['schulform'])." <small><font color='grey'>(".$an['prio'].")</font></small></td>";
	} else {
		echo "<td style='padding: 10px;' align='left'>".strtoupper($an['schulform'])."</td>";
	}
	//von / bis
	if ($an['beginn'] != "") {
	echo "<td style='padding: 10px;' align='left'>".date("d.m.y",strtotime($an['beginn']))."</td>";
	} else {
		echo "<td style='padding: 10px;' align='left'></td>";
	}
	
	//Beruf/Schwerpunkt
	if ($an['schulform'] == "bs" AND $an['beruf'] == "sonstiger") {
		echo "<td style='padding: 10px;' align='left'>".$an['beruf2']."</td>";
	}
	if ($an['schulform'] == "bs" AND $an['beruf'] != "sonstiger"){
		
		//Ermitteln der Berufsbezeichnung:
		$beruf = $an['beruf'];
		
		$select_ber = $db_temp->query("SELECT anzeigeform FROM berufe WHERE schluessel = '$beruf'");	
		foreach($select_ber as $ber) {
		echo "<td style='padding: 10px;' align='left'>".$ber['anzeigeform']."</td>";
		}
	}
	
	if ($an['schulform'] != "bs") {
		echo "<td style='padding: 10px;' align='left'>".$an['bgy_sp1']."</td>";
	}
		//Status bei Ankunft neuer Anmeldung anpassen.
		if (($an['status'] == "" OR $an['status'] == "gesendet") AND $an['schulform'] != "bs") {
				if ($db->exec("UPDATE `dsa_bewerberdaten`
								   SET
									`status` = 'eingegangen' WHERE `id` = '$id'")) { 
									
										
									}
		}
		
		if ($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "dbos" OR $an['schulform'] == "fs") {
			//Status bei vollständigen Unterlagen:
			$select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND dok_lebenslauf = '1' AND dok_ausweis = '1' AND dok_zeugnis = '1' AND dok_erfahrung = '1'");	
			$treffer_vo = $select_vo->rowCount();
			if ($treffer_vo > 0) {
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
		
		if ($an['schulform'] == "bgy" OR $an['schulform'] == "bvj" OR $an['schulform'] == "bf1" OR $an['schulform'] == "hbf") {
			//Status bei vollständigen Unterlagen:
			$select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND dok_lebenslauf = '1' AND dok_ausweis = '1' AND dok_zeugnis = '1'");	
			$treffer_vo = $select_vo->rowCount();
			if ($treffer_vo > 0) {
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
			
			if ((($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "fs") AND ($dok_lebenslauf + $dok_ausweis + $dok_erfahrung + $do_zeugnis) == 4) OR (($an['schulform'] != "bos1" OR $an['schulform'] != "bos2" OR $an['schulform'] != "fs") AND ($dok_lebenslauf + $dok_ausweis + $do_zeugnis) == 3)) {
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
		
		
			//Ohne Austritt:
			$select_edoo = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname' AND austritt < '2000-01-01'");	
			$treffer_edoo = $select_edoo->rowCount();
			
			if ($treffer_edoo > 0) {
						if ($db->exec("UPDATE `dsa_bewerberdaten`
										   SET
											`status` = 'übertragen' WHERE `id` = '$id'")) { 
											
												
											}
			}
			//Mit Austritt:
			$select_edoo_a = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname' AND austritt > '2000-01-01'");	
			$treffer_edoo_a = $select_edoo_a->rowCount();
			
			if ($treffer_edoo_a > 0) {
						if ($db->exec("UPDATE `dsa_bewerberdaten`
										   SET
											`status` = 'reaktivierbar' WHERE `id` = '$id'")) { 
											
												
											}

											
				}
			}
		
		$c_status = "black";
		if ($an['status'] == "eingegangen") {
			$c_status = "blue";
		}
		if ($an['status'] == "wird bearbeitet") {
			$c_status = "red";
		}
		if ($an['status'] == "vollständig") {
			$c_status = "orange";
		}
		if ($an['status'] == "übertragen" OR $an['status'] == "ÜBERTRAGEN") {
			$c_status = "green";
		}
		if ($an['status'] == "reaktivierbar") {
			$c_status = "#04B404";
		}
		
		$select_fe = $db->query("SELECT * FROM fehler WHERE erledigt != '1' AND id_bewerberdaten = '$id'");	
		$treffer_fe = $select_fe->rowCount();

if ($treffer_fe > 0) {
		$select_ig = $db->query("SELECT * FROM ignorieren WHERE id_bewerber = '$id'");	
		$treffer_ig = $select_ig->rowCount();
	if ($treffer_fe > $treffer_ig) { 
		echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i><nobr>".$an['status']." <b><a style='color: red;' href='./to	do.php?id=".$id."'>&ne;</a></b></nobr></i></td>";
} else {
		
		echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i>".$an['status']."</i></td>";
}
		} else {
			echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i>".$an['status']."</i></td>";
		}
/*
$summe = md5($an['mail'].$an['geburtsdatum'].$an['schulform']);


if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`md5` = '$summe' WHERE `id` = '$id'")) { 
										
											
										}
										
										if ($db->exec("UPDATE `dsa_bildungsgang`
									   SET
										`md5` = '$summe' WHERE `id_dsa_bewerberdaten` = '$id'")) { 
										
											
										}
*/
//Debug:
if ($_SESSION['admin'] == 1 AND $debug == 1) {
echo "<td style='padding: 10px;' align='right'><i>".$an['0']."</i></td>";
echo "<td style='padding: 10px;' align='right'><i>".$an['id']."</i></td>";
}

echo "</tr>";


}



//echo "<tr><td>Anzahl Anmeldungen: ".$treffer_an."</td></tr>";

echo "</table>";

echo "<p style='padding-left: 10px;'><b>Anzahl:</b> ".$treffer_an."</p>";

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
   header("Location: ./login_ad.php?back=liste");

}

include("./fuss.php");

?>
