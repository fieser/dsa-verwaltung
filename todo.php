<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



include("./config.php");




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	//Balken bei abweichendem Schuljahr:
	include("./abweichendes_sj.php");
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerberdaten</title>
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
</style>
</head>
<body>

<?php
    echo "<h1 style='margin-bottom: 2em;'><b>ToDo-Liste</b> (Anmeldungen ".$periode.")</h1>";


if (!isset($_GET['id']) AND !isset($_POST['id'])) {
echo "<form method='post' action='./index.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Menü' />";
echo "</form>";
} else {
echo "<table><tr>";
echo "<td>";
echo "<form method='post' action='./liste.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='zurück' />";
echo "</form>";
echo "</td>";
echo "<td width='20px'>";
echo "</td>";
echo "<td>";
echo "<form method='post' action='./todo.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='alle ToDos' />";
echo "</form>";
echo "</td>";
echo "</table></tr>";

$id = $_GET['id'];
	if ($_POST['id'] != "") {
		$id = $_POST['id'];
	}
}

if ($_POST['erledigt']) {
	$id_erledigt = $_POST['erledigt'];
	
	$id_bewerber = $_POST['id_bewerber'];
	$feld_dsa = $_POST['feld_dsa'];
	$feld_edoo = $_POST['feld_edoo'];
	
}

//Auswertung post erledigt
	if (isset($_POST['submit_erledigt'])) {
		$id_erledigt = $_POST['erledigt'];
					if ($db->exec("UPDATE `fehler`
									   SET
										`erledigt` = '1' WHERE `id` = '$id_erledigt'")) { 	
										}
	}
	
//Auswertung post ignorieren
	if (isset($_POST['submit_ignorieren'])) {
		$id_erledigt = $_POST['erledigt'];
					if ($db->exec("INSERT INTO `ignorieren`
								   SET
									`id_bewerber` = '$id_bewerber',
									`wert_edoo` = '$feld_edoo',
									`wert_dsa` = '$feld_dsa',
									`okay_admin` = '1'")) {
					
					$last_id = $db->lastInsertId();

					}
	}
	

	//Doppelte Fehler löschen:
	$select = $db->query("CREATE TEMPORARY TABLE temp_einzigartige_ids AS SELECT MIN(id) AS id FROM fehler GROUP BY id_bewerberdaten, feldname");
	$select = $db->query("DELETE FROM fehler WHERE id NOT IN (SELECT id FROM temp_einzigartige_ids)");


$select_fe = $db->query("SELECT * FROM fehler WHERE erledigt != '1' AND id_bewerberdaten LIKE '%$id%'");	
$treffer_fe = $select_fe->rowCount();

if ($treffer_fe == 1) {
	echo "<h2 style='margin-bottom: 2em;'>Abweichung zwischen <font color='red'><b>edoo.sys</b></font> und <font color='green'><b>Anmeldetool</b></font></h2>";
} else {
	echo "<h2 style='margin-bottom: 2em;'>Abweichungen zwischen <font color='red'><b>edoo.sys</b></font> und <font color='green'><b>Anmeldetool</b></font></h2>";
}

if ($treffer_fe = 0) {
	echo "Keine gefunden - Es gib nichts zu tun!";
} else {
	
	
	
	foreach($select_fe as $fe) {
		
		$fe_id_bewerberdaten = $fe['id_bewerberdaten'];
		$fe_id = $fe['id'];
		$feld_dsa = $fe['feld_dsa'];
		
		//Prüfen, ob Fehler evtl. ignoriert werden soll:
		$select_ig = $db->query("SELECT * FROM ignorieren WHERE id_bewerber = '$fe_id_bewerberdaten' AND wert_dsa = '$feld_dsa'");	
		$treffer_ig = $select_ig->rowCount();
		
		$summe_ig = ($summe_ig + $treffer_ig);
		
		if ($treffer_ig == 0) {
		
		$select_be = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$fe_id_bewerberdaten'");	
		$treffer_be = $select_be->rowCount();

		foreach($select_be as $be) {
			$be_nachname = $be['nachname'];
			$be_vorname = $be['vorname'];
		}
		$tab = 2;
		if ($fe['feldname'] == "Religionszugehoerigkeit") {
			$tab = 1;
		}
		if ($fe['feldname'] == "Eintrittsdatum") {
			$tab = 4;
		}
		
		if ($fe['feldname'] == "Als Bewerber übernehmen") {
			$tab = 1;
		}
		
		if ($fe['feldname'] == "Höchst. allg.-bild. Abschluss") {
			$tab = 4;
		}
		
		echo "<table width='100%'><tr>";
			echo "<td width='60%'>";
			echo "<div class='box-grau' bgcolor='' onmouseover=\"this.style.backgroundColor='d3d3d3'\" onclick=\"window.open('./datenblatt.php?id=".$fe_id_bewerberdaten."&back=todo&tab=".$tab."','Fenster')\" onmouseout=\"this.style.backgroundColor=''\">";
			if ($fe['id_edoo'] != "") { //Erforderlich, wenn Namen oder Geburtstag nicht passt.
			echo $be_nachname.", ".$be_vorname;
			}
			echo "<small><i><b> (".strtoupper($fe['wo_in_edoo']).")</b></i></small>:<br>";
			if (($fe['feld_edoo'] != "" OR $fe['feld_dsa'] != "") AND ($fe['feld_edoo'] != "O" AND $fe['feldname'] != "Geschlecht")) {
				
				if ($fe['feldname'] == "Eintrittsdatum") {
					echo $fe['feldname'].": <b><font color='red'>".$fe['feld_edoo']."</font></b> liegt vor Ausbildungsbeginn <b><font color='green'>".$fe['feld_dsa']."</font></b>";
				} else {
					echo $fe['feldname'].": <b><font color='red'>".$fe['feld_edoo']."</font></b> ungleich <b><font color='green'>".$fe['feld_dsa']."</font></b>";
				}
				
			} else {
			echo $fe['hinweis'];
			}
		echo "</div>";
		echo "</td>";
			
		echo "<td width='40px'>";
		echo "</td>";
		
		$feld_dsa = $fe['feld_dsa'];
		$feld_edoo = $fe['feld_edoo'];
		
			echo "<form method='post' action='./todo.php'>";
				echo "<input type='hidden' name='erledigt' value='$fe_id'>";
				echo "<input type='hidden' name='id_bewerber' value='$fe_id_bewerberdaten'>";
				echo "<input type='hidden' name='feld_dsa' value='$feld_dsa'>";
				echo "<input type='hidden' name='feld_edoo' value='$feld_edoo'>";
				
			if (isset($id)) {
				echo "<input type='hidden' name='erledigt' value='$fe_id'>";
				echo "<input type='hidden' name='id' value='$id'>";
			}
			if (trim($fe['feld_edoo']) != "O" AND trim($fe['feld_edoo']) != "D" AND $fe['feldname'] != "Geschlecht") {
			echo "<td style='align: right;'>";
			echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='submit_erledigt' value='erledigt' />";
			$anzahl_toto = ($anzahl_toto + 1);
			echo "</td>";
			}
			
			if ($fe['feld_dsa'] != "" AND $fe['feld_edoo'] != "fehlt" AND (trim($fe['feld_edoo']) != "O" AND trim($fe['feld_edoo']) != "D")) {
			echo "<td style='align: right;'>";
			echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='submit_ignorieren' value='ignorieren' />";
			echo "</td>";
			}
			
			if ((trim($fe['feld_edoo']) == "O" OR trim($fe['feld_edoo']) == "D" ) AND trim($fe['feldname']) == "Geschlecht") {
			echo "<td style='align: right;'>";
			echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='submit_ignorieren' value='ja, ist korrekt' />";
			echo "</td>";
			}
			
			echo "</form>";
			
		echo "</tr>";
		
		echo "</table>";
		
		} //Ende Ignorierungen sind 0
	}
}
//echo "<tr><td>Anzahl der TODOs: ".$anzahl_toto."</td></tr>";

$anzahl_toto = ($anzahl_toto + 1);
echo "<p>Anzahl der TODOs: ".($anzahl_toto -1)."</p>";
	
if (!isset($_GET['id'])) {
echo "<form method='post' action='./index.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Menü' />";
echo "</form>";
} else {
echo "<table>";

echo "<tr>";
echo "<td>";
echo "<form method='post' action='./liste.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='zurück' />";
echo "</form>";
echo "</td>";
echo "<td width='20px'>";
echo "</td>";
echo "<td>";
echo "<form method='post' action='./todo.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='alle ToDos' />";
echo "</form>";
echo "</td>";
echo "</tr>";

echo "</table>";
}



echo "</body>";
echo "</html>";


} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>
