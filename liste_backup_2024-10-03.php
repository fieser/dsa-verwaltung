<?php

$start = microtime(true);

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();


//$_SESSION['zusatz'] = 1;

// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



// Verbindung zur Datenbank aufbauen.

include "./rechte.php";
include "./config.php";

//include "./temp2db.php"; (wird inzwischen per Cronjob mit Datei /vorschaubilder.sh ausgeführt.

//Für Lehkräfte keine besondere Sortierung für SuS mit neuen Dokumenten:
if ($_SESSION['sek'] != 1 AND $_SESSION['admin'] != 1 AND $_SESSION['ft'] != 1) {
	$sort_dok_neu = "";
} else {
	$sort_dok_neu = "dok_neu DESC,";
}

if ($_GET['pk'] == 1 OR (!isset($_GET['pk']) AND $_SESSION['papierkorb'] == 1)) {
$_SESSION['papierkorb'] = 1;
$_SESSION['erledigte'] = 0;
$f_papierkorb = 1;
}
if ($_GET['pk'] == "0" OR (!isset($_GET['pk']) AND $_SESSION['papierkorb'] == 0)) {
$_SESSION['papierkorb'] = 0;
$f_papierkorb = "1";
}

//Für Lehrkräfte auch immer erledigte Anmeldungen und zusätzliche Spalten anzeigen:
if ($_SESSION['sek'] != 1 AND $_SESSION['admin'] != 1 AND $_SESSION['ft'] != 1) {
	$_SESSION['erledigte'] = 1;
	$_SESSION['zusatz'] = 1;
}


//Sortierung vorbereiten:
if (isset($_GET['sort'])) {
	
	$_SESSION['sort'] = $_GET['sort'];
}

if (!isset($_SESSION['sort'])) {
	$_SESSION['sort'] = "time";
	$_SESSION['rfg'] = "DESC";
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
if (isset($_POST['f_abschluss'])) {
$_SESSION['f_abschluss'] = $_POST['f_abschluss'];
}

$f_nachname = $_SESSION['f_nachname'];
$f_vorname = $_SESSION['f_vorname'];
$f_geburtsdatum = $_SESSION['f_geburtsdatum'];
$f_schulform = strtolower($_SESSION['f_schulform']);
$f_beruf = $_SESSION['f_beruf'];
$f_status = $_SESSION['f_status'];
$f_abschluss = $_SESSION['f_abschluss'];

if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
	if ($_SESSION['papierkorb'] != 1) {
		echo "<h1>Anmeldungen Bewerberinnen und Bewerber</h1>";
	} else {
		echo "<h1>Papierkorb</h1>";
	}
} else {
	echo "<h1>Aktuelle Anmeldungen</h1>";
}
?>

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

if (($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1) AND $_SESSION['papierkorb'] != 1) {

//Nur aktivieren, wenn ausreichende Filterung gesetzt ist:
if (($_SESSION['f_schulform'] != "" AND $_SESSION['f_schulform'] != "BS" AND $_SESSION['f_schulform'] != "B" AND $_SESSION['f_schulform'] != "bs") OR $_SESSION['f_beruf'] != "" OR strlen($_SESSION['f_nachname']) > 3) {
echo "<input style='width: 100%' class='btn btn-default btn-sm' type='submit' name='submit_filter' value='Transfer'>";
} else {
	echo "<div class='tooltip_bbs'><input style='width: 100%' class='btn btn-default btn-sm' type='submit' name='submit_filter' value='Transfer' disabled><span class='tooltiptext'>Setzen Sie zunächst einen ausreichenden Filter!</span></div>";
}



}



echo "<input type='hidden' name='f_nachname' value='".$_SESSION['f_nachname']."'>";


echo "<input type='hidden' name='f_vorname' value='".$_SESSION['f_vorname']."'>";


echo "<input type='hidden' name='f_geburtsdatum' value='".$_SESSION['f_geburtsdatum']."' disabled>";


echo "<input type='hidden' name='f_schulform' value='".$_SESSION['f_schulform']."'>";

echo "<input type='hidden' name='f_beruf' value='".$_SESSION['f_beruf']."'>";

echo "<input type='hidden' name='f_status' value='".$_SESSION['f_status']."'>";



echo "</form>";
echo "</td>";

//Menüpunkt: Zusätzliche Spalten anzeigen:
if (isset($_POST['submit_zusatz_1'])) {
	$_SESSION['zusatz'] = 1;
}
	
if (isset($_POST['submit_zusatz_0'])) {
	$_SESSION['zusatz'] = 0;
}

echo "<td style='padding-top: 2em; padding-left: 2em;' align='right'>";
	if ($_SESSION['papierkorb'] == 0 AND ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1)) {
	echo "<form id='form3' action='./liste.php' method='POST'>";
		if ($_SESSION['zusatz'] == 1) {
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_zusatz_0' value='weniger Spalten einblenden'>";
		} else {
			
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_zusatz_1' value='alle Spalten einblenden'>";
		}
	}
echo "</form>";
echo "</td>";



//Menüpunkt erledigte anzeigen:
if (isset($_POST['submit_erledigte_1'])) {
	$_SESSION['erledigte'] = 1;
}
	
if (isset($_POST['submit_erledigte_0'])) {
	$_SESSION['erledigte'] = 0;
}

echo "<td style='padding-top: 2em; padding-left: 2em;' align='right'>";
	if ($_SESSION['papierkorb'] == 0 AND ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1)) {
	echo "<form id='form3' action='./liste.php' method='POST'>";
		if ($_SESSION['erledigte'] == 1) {
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_erledigte_0' value='erledigte ausblenden'>";
		} else {
			
		echo "<input style='align: right;' class='btn btn-default btn-sm' type='submit' name='submit_erledigte_1' value='auch erledigte einblenden'>";
		}
	}
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
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php'><b><nobr>Geburtsdatum</nobr></b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=schulform&rfg=".$rfg."'><b>Schulf.</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php'><b>Beginn</b></a></td>";
	if ($_SESSION['zusatz'] == 1) {
	echo "<td style='".$style_kopf."' align='left'><a href='./liste.php'><b>Ende</b></a></td>";
	if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
		echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=abschluss&rfg=".$rfg."'><b>Abschluss</b></a></td>";
	}
	echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=betrieb&rfg=".$rfg."'><b>Betrieb</b></a></td>";
	
}
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=beruf&rfg=".$rfg."'><b>Beruf/Schwerpunkt</b></a></td>";
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=status&rfg=".$rfg."'><b>Status</b></a></td>";
if ($mail_status == 1 AND ($_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1 OR $_SESSION['sek'] == 1)) {
echo "<td style='".$style_kopf."' align='left'><a href='./liste.php?sort=mail&rfg=".$rfg."'><b></b></a></td>";
}
//echo "<td style='".$style_kopf."'><a href='./liste.php?sort=time&rfg=".$rfg."'><b></b></a></td>";
//echo "<td style='".$style_kopf."'><a href='./liste.php?sort=time&rfg=".$rfg."'><b>Klasse<br><small>(aktuell)</small></b></a></td>";
echo "<td style='".$style_kopf."'><a href='./liste.php'><b>Klasse<br><small>(aktuell)</small></b></a></td>";

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
if ($_SESSION['zusatz'] == 1) {
	echo "<td style='padding: 0px;' align='left'>";
	echo "<input style='width: 100%' type='text' name='f_bis' value='".$_SESSION['f_bis']."' disabled>";
	echo "</td>";
	if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
		echo "<td style='padding: 0px;' align='left'>";
		echo "<input style='width: 100%' type='text' name='f_abschluss' value='".$_SESSION['f_abschluss']."'>";
		echo "</td>";
	}
	echo "<td style='padding: 0px;' align='left'>";
	echo "<input style='width: 100%' type='text' name='f_betrieb' value='".$_SESSION['f_betrieb']."' disabled>";
	echo "</td>";
}
echo "<td style='padding: 0px;' align='left'>";
if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { 
	echo "<input style='width: 100%' type='text' name='f_beruf' value='".$_SESSION['f_beruf']."'>";
} else {
	echo "<input style='width: 100%; background-color: #EEE8AA;' placeholder='Wir empfehlen hier zu filtern!' type='text' name='f_beruf' value='".$_SESSION['f_beruf']."'>";
}
echo "</td>";
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_status' value='".$_SESSION['f_status']."'>";
echo "</td>";
if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { 
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_dok' value='' disabled>";
echo "</td>";
}
echo "<td style='padding: 0px;' align='left'>";
echo "<input style='width: 100%' type='text' name='f_klasse' value='".$_SESSION['f_klasse']."' disabled>";
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


if ($_SESSION['papierkorb'] != 1) {
$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* 
FROM dsa_bewerberdaten 
LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 
LEFT JOIN fehler ON dsa_bewerberdaten.id = fehler.id_bewerberdaten 
LEFT JOIN ignorieren ON dsa_bewerberdaten.id = ignorieren.id_bewerber 
LEFT JOIN (SELECT id_bewerberdaten, COUNT(*) AS fehler_count FROM fehler GROUP BY id_bewerberdaten) fehler_count ON dsa_bewerberdaten.id = fehler_count.id_bewerberdaten
LEFT JOIN (SELECT id_bewerber, COUNT(*) AS ignorieren_count FROM ignorieren GROUP BY id_bewerber) ignorieren_count ON dsa_bewerberdaten.id = ignorieren_count.id_bewerber
WHERE (schulform LIKE '%$f_schulform%' AND 
       status LIKE '$f_status%' AND 
       nachname LIKE '%$f_nachname%' AND 
       vorname LIKE '%$f_vorname%' AND
	   abschluss LIKE '%$f_abschluss%' AND
		papierkorb NOT LIKE '$f_papierkorb' AND
       ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR 
       (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR 
       (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs'))) 
       AND NOT (dsa_bewerberdaten.status = 'übertragen' AND (fehler.id_bewerberdaten IS NULL OR (ignorieren_count.ignorieren_count IS NOT NULL AND fehler_count.fehler_count <= ignorieren_count.ignorieren_count)))
GROUP BY dsa_bewerberdaten.id
ORDER BY {$sort_dok_neu}{$sort} {$rfg}
");

} else {
	$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* 
FROM dsa_bewerberdaten 
LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 
LEFT JOIN fehler ON dsa_bewerberdaten.id = fehler.id_bewerberdaten 
LEFT JOIN ignorieren ON dsa_bewerberdaten.id = ignorieren.id_bewerber 
LEFT JOIN (SELECT id_bewerberdaten, COUNT(*) AS fehler_count FROM fehler GROUP BY id_bewerberdaten) fehler_count ON dsa_bewerberdaten.id = fehler_count.id_bewerberdaten
LEFT JOIN (SELECT id_bewerber, COUNT(*) AS ignorieren_count FROM ignorieren GROUP BY id_bewerber) ignorieren_count ON dsa_bewerberdaten.id = ignorieren_count.id_bewerber
WHERE (schulform LIKE '%$f_schulform%' AND 
       status LIKE '$f_status%' AND 
       nachname LIKE '%$f_nachname%' AND 
       vorname LIKE '%$f_vorname%' AND
	   abschluss LIKE '%$f_abschluss%' AND
		papierkorb = '1' AND
       ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR 
       (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR 
       (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs'))) 
       AND NOT (dsa_bewerberdaten.status = 'übertragen' AND (fehler.id_bewerberdaten IS NULL OR (ignorieren_count.ignorieren_count IS NOT NULL AND fehler_count.fehler_count <= ignorieren_count.ignorieren_count)))
GROUP BY dsa_bewerberdaten.id 
ORDER BY {$sort_dok_neu}{$sort} {$rfg}
");
}




		
		
		
		} else {
		//alle anzeigen:
		$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 WHERE papierkorb NOT LIKE '1' AND schulform LIKE '%$f_schulform%' AND status LIKE '%$f_status%' AND nachname LIKE '%$f_nachname%' AND vorname LIKE '%$f_vorname%' AND abschluss LIKE '%$f_abschluss%' AND ((bgy_sp1 LIKE '%$f_beruf%' AND schulform NOT LIKE 'bs') OR (beruf2 LIKE '%$f_beruf%' AND schulform = 'bs') OR (beruf_anz LIKE '%$f_beruf%' AND schulform = 'bs')) GROUP BY dsa_bewerberdaten.id ORDER BY {$sort_dok_neu}{$sort} {$rfg}");
	}
	
	$treffer_an = $select_an->rowCount();
	



foreach($select_an as $an) {
	$klasse = "";
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
	
	//echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='d3d3d3'\" onclick=\"window.open('./datenblatt.php?id=".$id."&md=".$md5_bew."','Fenster')\" onmouseout=\"this.style.backgroundColor=''\">";
	
	//Wenn keine neuen Dokumente und nicht vor dem 01.08. des Jahres
	if (($an['dok_neu'] != 1 AND ($an['beginn'] == "" OR strtotime($an['beginn']) >= strtotime(date("Y",time())."-08-01") OR ((strtotime($an['beginn']) < strtotime(date("Y",time())."-08-01") AND trim($an['status']) == "reaktivierbar")) OR $an['status'] == "übertragen"))
		OR ($_SESSION['sek'] != 1 AND $_SESSION['admin'] != 1 AND $_SESSION['ft'] != 1)) {
			if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //kein Link für Lehrkräfte
				echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='#d3d3d3'\" onclick=\"window.location.href='./datenblatt.php?id=".$id."&md=".$md5_bew."'\" onmouseout=\"this.style.backgroundColor=''\">";
			} else {
				echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='#d3d3d3'\" onmouseout=\"this.style.backgroundColor=''\">";
		
			}
	} else {
		if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //kein Link für Lehrkräfte
		echo "<tr bgcolor='#FFB6C1' onmouseover=\"this.style.backgroundColor='#FF82AB'\" onclick=\"window.location.href='./datenblatt.php?id=".$id."&md=".$md5_bew."'\" onmouseout=\"this.style.backgroundColor=''\">";
		} else {
				echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='#d3d3d3'\" onmouseout=\"this.style.backgroundColor=''\">";
		
			}
	}
	
	echo "<td style='padding: 10px;' align='left'>".date("d.m.Y",$an['time'])."</td>";
	echo "<td style='padding: 10px;' align='left'>".$an['nachname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".$an['vorname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".date("d.m.Y",strtotime($an['geburtsdatum']))."</td>";
	
	$an_md5 = $an['md5'];
	
	if ($treffer_bew_dub > 1 AND $an['prio'] != "") {
		$select_sum = $db_temp->query("SELECT * FROM summen WHERE md5 = '$an_md5'");	
	$treffer_sum = $select_sum->rowCount();
	foreach($select_sum as $sum) {
	$sum_prio = $sum['prio'];
	}
	
	if ($sum_prio == "" AND $an['prio'] != "") {
		$sum_prio = $an['prio'];
	}
	
		echo "<td style='padding: 10px;' align='left'><nobr>".strtoupper($an['schulform'])." <small><font color='grey'>(".$sum_prio.")</font></small></nobr></td>";
	} else {
		echo "<td style='padding: 10px;' align='left'>".strtoupper($an['schulform'])."</td>";
	}
	//von / bis
	if ($an['beginn'] != "") {
	echo "<td style='padding: 10px;' align='left'>".date("d.m.y",strtotime($an['beginn']))."</td>";
	} else {
		echo "<td style='padding: 10px;' align='left'></td>";
	}
	
	if ($an['ende'] != "" AND $_SESSION['zusatz'] == 1) {
		echo "<td style='padding: 10px;' align='left'>".date("d.m.y",strtotime($an['ende']))."</td>";
	} else {
		if ($_SESSION['zusatz'] == 1) {
		echo "<td style='padding: 10px;' align='left'></td>";
		}
	}
	
	if ($_SESSION['zusatz'] == 1) {
		
		if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
		//Anzeige Abschluss:
		echo "<td style='padding: 10px;' align='left'>".$an['abschluss']."</td>";
		}
		
		//Anzeige: Betrieb:
		$betrieb = $an['betrieb'];
					$select_betr = $db_temp->query("SELECT name1 FROM betriebe WHERE kuerzel LIKE '$betrieb'");	
					foreach($select_betr as $betr) {
						if ($betr['name1'] != "") {
							$betrieb_anz = $betr['name1'];
						} else {
							$betrieb_anz = "";
						}
					}
		echo "<td style='padding: 10px;' align='left'>$betrieb_anz</td>";
	}
	
	//Beruf/Schwerpunkt
	if ($an['schulform'] == "bs" AND $an['beruf'] == "sonstiger") {
		echo "<td style='padding: 10px;' align='left'>".$an['beruf2']."</td>";
	}
	if ($an['schulform'] == "bs" AND $an['beruf'] != "sonstiger"){
		
		//Ermitteln der Berufsbezeichnung:
		$beruf = $an['beruf'];
		
		$select_ber = $db_temp->query("SELECT anzeigeform, langform FROM berufe WHERE schluessel = '$beruf'");	
		foreach($select_ber as $ber) {
		echo "<td style='padding: 10px;' align='left'>".$ber['langform']."</td>";
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
		$geburtsdatum = trim($an['geburtsdatum']);
		$nachname = trim($an['nachname']);
		$vorname = trim($an['vorname']);
		$geburtsort = trim($an['geburtsort']);
	
		$select_edoo = $db->query("SELECT id FROM edoo_bewerber WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND geburtsort = '$geburtsort' AND status_uebernahme = '0'");	
		$treffer_edoo = $select_edoo->rowCount();
		
		if ($treffer_edoo > 0 AND $temp_status_voll == 1) {
					if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
										`status` = 'übertragen' WHERE `id` = '$id'")) { 
										
											
										}
			} else { //Falls ergebnislos, Schülerdaten durchsuchen:
		
		
			//Ohne Austritt:
			$select_edoo = $db->query("SELECT id, klasse FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname'");	
			$treffer_edoo = $select_edoo->rowCount();
			//echo $an['nachname']." ".$temp_status_voll." ".$treffer_edoo."<br>";
			if ($treffer_edoo > 0 AND trim($temp_status_voll) == 1) {
				//echo $an['nachname']."<br>";
						if ($db->exec("UPDATE `dsa_bewerberdaten`
										   SET
											`status` = 'übertragen' WHERE `id` = '$id'")) {
											
												
											}
						
						//Anzeige Klasse vorbereiten:
						foreach($select_edoo as $ed) {
						$klasse = $ed['klasse'];
						}
			}
			
			
			//Mit Austritt:
			$select_edoo_a = $db->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND geburtsort = '$geburtsort' AND austritt > '2000-01-01'");	
			$treffer_edoo_a = $select_edoo_a->rowCount();
			
				if ($treffer_edoo_a > 0 AND $temp_status_voll == 1) {
					//echo $an['nachname']."<br>";
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
		
		if ($_SESSION['sek'] != 1 AND $_SESSION['admin'] != 1 AND $_SESSION['ft'] != 1) {
			$an['status'] = str_replace("unvollständig","<small><nobr>in Bearbeitung</nobr></small>",$an['status']);
			$an['status'] = str_replace("übertragen","<small><nobr>in edoo.sys</nobr></small>",$an['status']);
			$an['status'] = str_replace("vollständig","<small><nobr>in Bearbeitung</nobr></small>",$an['status']);
			$an['status'] = str_replace("reaktivierbar","<small><nobr>in Bearbeitung</nobr></small>",$an['status']);
		}
		
		
		$select_fe = $db->query("SELECT * FROM fehler WHERE erledigt != '1' AND id_bewerberdaten = '$id'");	
		$treffer_fe = $select_fe->rowCount();

if ($treffer_fe > 0) {
		$select_ig = $db->query("SELECT * FROM ignorieren WHERE id_bewerber = '$id'");	
		$treffer_ig = $select_ig->rowCount();
	if ($treffer_fe > $treffer_ig AND ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1)) { 
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
//ICONs Mail oder Dokument:
if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
echo "<td style='padding: 10px; align='left'>";
}
if ($mail_status == 1 AND ($_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1 OR $_SESSION['sek'] == 1) AND $an['status'] != "übertragen" AND $an['status'] != "vollständig") {
	//$select_mail = $db->query("SELECT DISTINCT id_dsa_bewerberdaten, last_time FROM mail WHERE id_dsa_bewerberdaten = '$id'");	
	
	$select_mail = $db->query("SELECT id_dsa_bewerberdaten, MIN(last_time) AS highest_last_time FROM mail WHERE id_dsa_bewerberdaten = '$id' GROUP BY id_dsa_bewerberdaten");

	if ($an['dok_neu'] == 1) {
		echo "<img width='20px' src='./images/pdf.svg'>";
	} else {
		$treffer_mail = $select_mail->rowCount();
		if ($treffer_mail > 0) {
			foreach($select_mail as $mail) {
				//echo "<tr>".$mail['highest_last_time']."</tr>";
				
				
				
					if ($mail['highest_last_time'] < (time() - 24*3600*10)) {
						echo "<img width='20px' src='./images/mail_2.svg'>";
					} else {
						echo "<img width='20px' src='./images/mail_1.svg'>";
					}
				
			}
	/*	} else {
			if ($an['dok_neu'] == 1) {
							echo "<td style='padding: 10px; align='left'><img width='20px' src='./images/pdf.svg'></td>";
					} 
					*/
		} else {
			//echo "<td style='padding: 10px; align='left'></td>";
		}
	
	}
}
if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { //wenn keine Lehrkraft
	echo "</td>";
}
//Klasse anzeigen:
echo "<td style='padding: 10px;' align='left'><small><nobr>".$klasse."</nobr></small></td>";


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

$ende = microtime(true);
// Ladezeit mit 4 Nachkommastellen
if ($_SESSION['admin'] == 1) {
echo "<p style='padding-left: 10px;'><b>Ladezeit:</b> ".number_format($ende - $start, 4)." Sekunden</p>";
}
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
