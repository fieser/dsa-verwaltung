<?php

$start = microtime(true);

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {

//Weg zurück:
if (isset($_POST['back'])) {
	$_SESSION['back'] = $_POST['back'];
} else {
	$_SESSION['back'] = "index";
}
	


// Verbindung zur Datenbank aufbauen.

include "./rechte.php";
include "./config.php";

//include "./temp2db.php";

//Link direkt zur Klasse:
if (isset($_GET['klasse'])) {
	$_POST['klasse'] = $_GET['klasse'];
}

//Ausgewählete Klasse:
if ($_SESSION['klasse'] != "") {
	$klasse = $_SESSION['klasse'];
}

if (isset($_POST['klasse'])) {
	$klasse = $_POST['klasse'];
	$_SESSION['klasse'] = $klasse;
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

<table width='100%'>
<tr>
<td style='padding-top: 2em; width: 8em;'>
<?php
echo "<form method='post' action='./".$_SESSION['back'].".php'>";
?>
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
	if ($_SESSION['papierkorb'] == 0) {
	echo "<form id='form3' action='./klassenlisten.php' method='POST'>";

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

$schuljahr = "1158_2023";
$schuljahr_ss = (substr($schuljahr,-2) + 1);
		
//Auswahl der Klasse:

$select_kla = $db_www->query("SELECT DISTINCT klasse FROM edoo_schueler_klasse WHERE schuljahr LIKE '$schuljahr' AND (klasse LIKE '%$schuljahr_ss%' OR klasse LIKE '%+%') ORDER BY klasse ASC");


echo "<table>";
echo "<tr><td>&nbsp;</td><tr>";
echo "<tr><td colspan='3'><b>Klasse wählen:</b></td></tr>";
echo "<tr><td>&nbsp;</td><tr>";
	echo "<form id='form_kla' action='".$_SERVER['PHP_SELF']."' method='POST'>";

	echo"<tr><td><select name='klasse' onchange='change_kla()'>";
	
	
		echo "<option>".$_SESSION['klasse']."</option>";
		echo "<option>".$_POST['klasse']."</option>";
		
		foreach ($select_kla as $kla) {
			
		echo "<option>".$kla['klasse']."</option>";
		}
	echo "</select></label></td>";
	echo "<td style='padding-left: 1em;'><small><i>(Es werden nur Klassen mit SuS angezeigt.)</i></small></td>";

	//decho "<td width='5'></td><td><input class='btn btn-default btn-sm' type='submit' name='submit_abt' value='anzeigen'></td></tr>";
		
	echo "</form>";
	
	echo "</tr>";
echo "</table>";
if ($klasse != "") {
echo "<h1 style='margin-top: 2em; margin-bottom: 1em;'>Aktuelle Klassenliste der <b>".$klasse."</b> in <i>edoo.sys</i></h1>";
} else {
echo "<h1 style='margin-top: 2em; margin-bottom: 1em;'>Bitte wählen Sie ein Klasse!</h1>";	
}
?>
<script>
		function change_kla(){
			document.getElementById("form_kla").submit();
		}
		</script>
<?php

echo "<table style='color: black; background-color: #E0E0E0; line-height: 1.5em;'>";
//echo "<p><tr>";

$style_kopf = "padding: 10px; border: 5px solid; border-color: #E0E0E0; background-color: #ffffff;";
if ($klasse != "") {
	echo "<tr class='fest'>";
	echo "<td style='".$style_kopf."'><a href='./klassenlisten.php?sort=nachname&rfg=".$rfg."'><b>Nachname</b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=vorname&rfg=".$rfg."'><b>Vorname</b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=geburtsdat.&rfg=".$rfg."'><b><nobr>Geburtsdatum</nobr></b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=eintritt&rfg=".$rfg."'><b>Eintritt</b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=beruf&rfg=".$rfg."'><b>Ausbildungsberuf</b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=klassengruppe&rfg=".$rfg."'><b>Kl.-Gruppe</b></a></td>";
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=status&rfg=".$rfg."'><b>Status</b></a></td>";
	if ($mail_status == 1 AND ($_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1 OR $_SESSION['sek'] == 1)) {
	echo "<td style='".$style_kopf."' align='left'><a href='./klassenlisten.php?sort=mail&rfg=".$rfg."'><b></b></a></td>";
	}

	echo "</tr>";
}
/*
//Filterzeile


echo "<form id='form2' action='./klassenlisten.php' method='POST'>";
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

echo "</form>";
*/

if (substr($klasse, 0, 7) == "BS AWIS") {
	$suche1 = "AND (beruf_anz LIKE '%Studium%' OR status LIKE 'übertragen')"; 
}

	
		$datum_von = date("Y-m-d");
	

//$klasse = "BS STH 24A";

		if ($_SESSION['papierkorb'] != 1 AND $klasse != "") {
		//$select_an = $db->query("SELECT DISTINCT id_edoo, nachname, vorname, geburtsdatum, eintritt, beruf, schueler_id, klassengruppe FROM edoo_schueler, edoo_schueler_klasse WHERE edoo_schueler_klasse.klasse LIKE '$klasse' AND edoo_schueler.klasse = edoo_schueler_klasse.klasse ORDER BY {$sort} {$rfg}");
	// Erste Abfrage
// Erste Abfrage
$select_an = $db_www->query("SELECT DISTINCT 
    es.id_edoo, 
    es.nachname, 
    es.vorname, 
    es.klasse, 
    es.geburtsdatum, 
    es.eintritt, 
    es.beruf, 
    esk.id, 
    esk.schueler_id, 
    esk.klasse, 
    esk.klassengruppe, 
    esk.schuljahr 
FROM 
    edoo_schueler_klasse esk
JOIN 
    edoo_schueler es 
ON 
    esk.schueler_id = es.id_edoo
WHERE 
    esk.klasse LIKE '$klasse' AND esk.schuljahr NOT LIKE '%statistik%' ORDER BY nachname, vorname ASC");

$result_an = $select_an->fetchAll(PDO::FETCH_ASSOC);

// Zweite Abfrage
$select_berufe = $db_www->query("SELECT DISTINCT beruf FROM edoo_schueler WHERE klasse LIKE '$klasse' ORDER BY nachname, vorname ASC");
$result_berufe = $select_berufe->fetchAll(PDO::FETCH_ASSOC);

$result_an_weiter = [];

foreach($result_berufe as $berufe) {
    $beruf = str_replace("1027_", "", $berufe['beruf']);
    
    $select_ber = $db_temp->query("SELECT langform FROM berufe WHERE schluessel = '$beruf'");    
    $result_ber = $select_ber->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($result_ber)) {
        $beruf_anz = trim($result_ber[0]['langform']);
        $beruf_anz_kurz = substr(trim($result_ber[0]['langform']), 0, 5);

        // Weitere Anmeldungen des Berufes suchen:
        $select_an_weiter_query = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* 
        FROM dsa_bewerberdaten 
        LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5 
        WHERE papierkorb NOT LIKE '1' 
		AND (beruf_anz LIKE '$beruf_anz_kurz%' 
		{$suche1}
		AND beginn > '2024-07-31')
        GROUP BY dsa_bewerberdaten.id 
        ORDER BY nachname, vorname ASC");

        $result_an_weiter = array_merge($result_an_weiter, $select_an_weiter_query->fetchAll(PDO::FETCH_ASSOC));
    }
}

// Kombinierte Ergebnisse
$combined_results = array_merge($result_an, $result_an_weiter);

// Sortieren nach Nachname und Geburtsdatum
usort($combined_results, function($a, $b) {
    return strcmp($a['nachname'] . $a['geburtsdatum'], $b['nachname'] . $b['geburtsdatum']);
});

// Filterung der doppelten Datensätze
$unique_results = [];
$seen = [];

foreach ($combined_results as $row) {
	
    $key = $row['nachname'] . $row['geburtsdatum'];
    if (!isset($seen[$key])) {
        $seen[$key] = $row;
        $unique_results[] = $row;
    } elseif (isset($seen[$key]) && in_array($row, $result_an)) {
        // Ersetze den bestehenden Datensatz mit dem aus select_an
        foreach ($unique_results as &$unique_row) {
            if ($unique_row['nachname'] . $unique_row['geburtsdatum'] === $key) {
                $unique_row = $row;
                break;
            }
        }
    }
}



		
		} 

		
	
	//$treffer_an = $combined_results->rowCount();
	
	
	// Ausgabe der kombinierten und sortierten Ergebnisse
foreach ($unique_results as $row) {



	/*
	echo "<pre>";
	print_r ( $an );
	echo "</pre>";
	*/
	
	//Wenn es noch weitere Anmeldungen gibt:
	$nachname = trim($row['nachname']);
	$vorname = trim($row['vorname']);
	$geburtsdatum = trim($row['geburtsdatum']);
	

	
	
	
	
	//Hovereffekt:
	
	
	echo "<tr bgcolor='' onmouseover=\"this.style.backgroundColor='#d3d3d3'\" onmouseout=\"this.style.backgroundColor=''\">";
	
	
	
	echo "<td style='padding: 10px;' align='left'>".$row['nachname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".$row['vorname']."</td>";
	echo "<td style='padding: 10px;' align='left'>".date("d.m.Y",strtotime($row['geburtsdatum']))."</td>";
	
	
	

	//von / bis
	if ($row['eintritt'] != "") {
	echo "<td style='padding: 10px;' align='left'>".date("d.m.y",strtotime($row['eintritt']))."</td>";
	} else {
		echo "<td style='padding: 10px;' align='left'></td>";
	}
	
	//Beruf/Schwerpunkt

	
		
		//Ermitteln der Berufsbezeichnung:
		$beruf = str_replace("1027_","",$row['beruf']);
		
		$select_ber = $db_temp->query("SELECT langform FROM berufe WHERE schluessel = '$beruf'");	
		foreach($select_ber as $ber) {
		echo "<td style='padding: 10px;' align='left'>".$ber['langform']."</td>";
		}
	
	
		echo "<td style='padding: 10px;' align='left'>".$row['klassengruppe']."</td>";

	
	
		//Abgleich mit edoo.sys:
		$geburtsdatum = trim($row['geburtsdatum']);
		$nachname = trim($row['nachname']);
		$vorname = trim($row['vorname']);
		$geburtsort = trim($row['geburtsort']);
	
		$select_edoo = $db_www->query("SELECT id FROM edoo_bewerber WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND geburtsort = '$geburtsort' AND status_uebernahme = '0'");	
		$treffer_edoo = $select_edoo->rowCount();

			
			
		
		$c_status = "black";
		if ($row['status'] == "eingegangen") {
			$c_status = "blue";
		}
		if ($row['status'] == "unvollständig") {
			$c_status = "blue";
		}
		if ($row['status'] == "wird bearbeitet") {
			$c_status = "red";
		}
		if ($row['status'] == "vollständig") {
			$c_status = "orange";
		}
		if ($row['status'] == "übertragen" OR $an['status'] == "ÜBERTRAGEN") {
			$c_status = "green";
		}
		if ($row['status'] == "reaktivierbar") {
			$c_status = "#04B404";
		}
		
		$select_fe = $db->query("SELECT * FROM fehler WHERE erledigt != '1' AND id_bewerberdaten = '$id'");	
		$treffer_fe = $select_fe->rowCount();

if ($treffer_fe > 0) {
		$select_ig = $db->query("SELECT * FROM ignorieren WHERE id_bewerber = '$id'");	
		$treffer_ig = $select_ig->rowCount();
	if ($treffer_fe > $treffer_ig) { 
		echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i><nobr>".str_replace("übertragen","eingeschult",$row['status'])." <b><a style='color: red;' href='./todo.php?id=".$id."'> &ne; </a></b></nobr></i></td>";
} else {
		
		echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i>".str_replace("übertragen","eingeschult",$row['status'])."</i></td>";
}
		} else {
			echo "<td style='padding: 10px; color:".$c_status.";' align='left'><i>".str_replace("übertragen","<b>eingeschult</b>",$row['status'])."</i></td>";
		}



//Klasse anzeigen:
//echo "<td style='padding: 10px;' align='right'><small><nobr>".$klasse."</nobr></small></td>";


//Debug:
if ($_SESSION['admin'] == 1 AND $debug == 1) {
echo "<td style='padding: 10px;' align='right'><i>".$row['id']."</i></td>";
}

echo "</tr>";

echo "<td style='padding: 10px;' align='right'><i>".$row['0']."</i></td>";

}



//echo "<tr><td>Anzahl Anmeldungen: ".$treffer_an."</td></tr>";

echo "</table>";
if ($klasse != "") {
echo "<p style='padding-left: 10px;'><b>Anzahl:</b> ".count($unique_results)."</p>";
}
$ende = microtime(true);
// Ladezeit mit 4 Nachkommastellen
//echo "<p style='padding-left: 10px;'><b>Ladezeit:</b> ".number_format($ende - $start, 4)." Sekunden</p>";
?>
<table>

<tr height="50"></tr>
<tr>
<td>
<?php
echo "<form method='post' action='./".$_SESSION['back'].".php'>";
?>
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
