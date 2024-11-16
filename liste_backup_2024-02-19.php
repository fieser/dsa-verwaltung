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


//Sortierreihenfolge vorbereiten:
if (isset($_GET['rfg']) AND $_GET['rfg'] == "ASC") {
	
	$_SESSION['rfg'] = "DESC";
}

if (isset($_GET['rfg']) AND $_GET['rfg'] == "DESC") {
	
	$_SESSION['rfg'] = "ASC";
}

if (!isset($_SESSION['rfg'])) {
	$_SESSION['rfg'] = "ASC";
}

$rfg = $_SESSION['rfg'];




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
<table width='100%'>
<tr>
<td style='padding-top: 2em; width: 8em;'>
<form method="post" action="./index.php">
<input class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Menü" />
</form>
</td>
<td style='padding-top: 2em; padding-left: 2em; width: 8em;	'>
<?php
if ($xls_download == 1) {
	echo "<form id='form2' action='./export_py.php' method='POST'>";
} else {
	echo "<form id='form2' action='./export_csv.php' method='POST'>";
}

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
echo "</td>";


if (isset($_POST['submit_erledigte_1'])) {
	$_SESSION['erledigte'] = 1;
}
	
if (isset($_POST['submit_erledigte_0'])) {
	$_SESSION['erledigte'] = 0;
}

echo "<td style='padding-top: 2em; padding-left: 2em;' align='right'>";
	//if ($_SESSION['admin'] == 1 OR $_SESSION['sek'] == 1) {
	echo "<form id='form3' action='./liste.php' method='POST'>";
		if ($_SESSION['erledigte'] == 1) {
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_erledigte_0' value='erledigte ausblenden'>";
		} else {
			
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_erledigte_1' value='auch erledigte einblenden'>";
		}
	//}
echo "</form>";
echo "</td>";
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
echo "<td style='".$style_kopf."'><a href='./liste.php?sort=time&rfg=".$rfg."'><b>Anmeldung</b></a></td>";
echo "<td style='".$style_kopf."'><a href='./liste.php?sort=nachname&rfg=".$rfg."'><b>Nachname</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=vorname&rfg=".$rfg."'><b>Vorname</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=geburtsdatum&rfg=".$rfg."'><b><nobr>Geburtsdatum</nobr></b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=schulform&rfg=".$rfg."'><b>Schulform</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=beginn&rfg=".$rfg."'><b>ab</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=beruf&rfg=".$rfg."'><b>Beruf/Schwerpunkt</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=status&rfg=".$rfg."'><b>Status</b></a></td>";


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
	if ((!isset($_SESSION['erledigte'])) OR (isset($_SESSION['erledigte']) AND $_SESSION['erledigte'] == 0)) {
		//nur unerledigte anzeigen:
/*	ALTE VARIANTE ohne internes SELECT für ignorierte Fehler
	
$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* 
FROM dsa_bewerberdaten 
LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 
LEFT JOIN fehler ON dsa_bewerberdaten.id = fehler.id_bewerberdaten 
LEFT JOIN ignorieren ON dsa_bewerberdaten.id = ignorieren.id_bewerber 
WHERE (schulform LIKE '%$f_schulform%' AND 
       status LIKE '%$f_status%' AND 
       nachname LIKE '%$f_nachname%' AND 
       vorname LIKE '%$f_vorname%' AND 
       ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR 
       (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR 
       (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs'))) 
       AND NOT (dsa_bewerberdaten.status = 'übertragen' AND (fehler.id_bewerberdaten IS NULL OR ignorieren.id_bewerber IS NOT NULL))
GROUP BY dsa_bewerberdaten.id 
ORDER BY {$sort} {$rfg}");
*/

$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* 
FROM dsa_bewerberdaten 
LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 
LEFT JOIN fehler ON dsa_bewerberdaten.id = fehler.id_bewerberdaten 
LEFT JOIN ignorieren ON dsa_bewerberdaten.id = ignorieren.id_bewerber 
LEFT JOIN (SELECT id_bewerberdaten, COUNT(*) AS fehler_count FROM fehler GROUP BY id_bewerberdaten) fehler_count ON dsa_bewerberdaten.id = fehler_count.id_bewerberdaten
LEFT JOIN (SELECT id_bewerber, COUNT(*) AS ignorieren_count FROM ignorieren GROUP BY id_bewerber) ignorieren_count ON dsa_bewerberdaten.id = ignorieren_count.id_bewerber
WHERE (schulform LIKE '%$f_schulform%' AND 
       status LIKE '%$f_status%' AND 
       nachname LIKE '%$f_nachname%' AND 
       vorname LIKE '%$f_vorname%' AND 
       ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR 
       (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR 
       (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs'))) 
       AND NOT (dsa_bewerberdaten.status = 'übertragen' AND (fehler.id_bewerberdaten IS NULL OR (ignorieren_count.ignorieren_count IS NOT NULL AND fehler_count.fehler_count <= ignorieren_count.ignorieren_count)))
GROUP BY dsa_bewerberdaten.id 
ORDER BY {$sort} {$rfg}
");






		
		
		
		} else {
		//alle anzeigen:
		$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 WHERE schulform LIKE '%$f_schulform%' AND status LIKE '%$f_status%' AND nachname LIKE '%$f_nachname%' AND vorname LIKE '%$f_vorname%' AND ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs')) GROUP BY dsa_bewerberdaten.id ORDER BY {$sort} {$rfg}");
	}
	
	$treffer_an = $select_an->rowCount();
	



foreach($select_an as $an) {
	$id = trim($an['0']); //Bewerberdaten
	$id_bil = trim($an['id']); //Bildungsgang
	$md5_bew = trim($an['md5']); //Bewerberdaten md5
	$md5_bil = trim($an['49']); //Bildungsgang md5
	
	//Merker für vollständige Unterlagen zurücksetzen:
	$temp_status_voll = 0;
	
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
									`status` = 'unvollständig' WHERE `id` = '$id'")) { 
									
										
									}
		}
		
		if ($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "dbos" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") {
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
										`status` = 'unvollständig' WHERE `id` = '$id'")) { 
										
												
										}		
				
				
			}
		}
		
		if ($an['schulform'] == "bgy" OR $an['schulform'] == "bvj" OR $an['schulform'] == "bf1" OR $an['schulform'] == "bf2" OR $an['schulform'] == "bfp" OR $an['schulform'] == "aph" OR $an['schulform'] == "hbf") {
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
										`status` = 'unvollständig' WHERE `id` = '$id'")) { 
										
												
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
			
			if ((($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") AND ($dok_lebenslauf + $dok_ausweis + $dok_erfahrung + $do_zeugnis) == 4) OR (($an['schulform'] != "bos1" OR $an['schulform'] != "bos2" OR $an['schulform'] != "fs" OR $an['schulform'] != "fsof") AND ($dok_lebenslauf + $dok_ausweis + $do_zeugnis) == 3)) {
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
		
		if ($treffer_edoo > 0 AND $temp_status_voll == 1) {
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'übertragen' WHERE `id` = '$id'")) { 
										
											
										}
			} else { //Falls ergebnislos, Schülerdaten durchsuchen:
		
		
			//Ohne Austritt:
			$select_edoo = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname' AND austritt < '2000-01-01'");	
			$treffer_edoo = $select_edoo->rowCount();
			
			if ($treffer_edoo > 0 AND $temp_status_voll == 1) {
						if ($db->exec("UPDATE `dsa_bewerberdaten`
										   SET
											`status` = 'übertragen' WHERE `id` = '$id'")) {
											
												
											}
			}
			//Mit Austritt:
			$select_edoo_a = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND vorname = '$vorname' AND austritt > '2000-01-01'");	
			$treffer_edoo_a = $select_edoo_a->rowCount();
			
			if ($treffer_edoo_a > 0 AND $temp_status_voll == 1) {
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
		if ($an['status'] == "unvollständig") {
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
		echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i><nobr>".$an['status']." <b><a style='color: red;' href='./todo.php?id=".$id."'> &ne; </a></b></nobr></i></td>";
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
