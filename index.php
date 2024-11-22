<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];


//Wenn das Schuljahr per Dopdown gewechselt wurde:
if (isset($_POST['sj']) AND $_POST['sj'] != "") {
		$_SESSION['schuljahr'] = $_POST['sj'];
}

	//Balken bei abweichendem Schuljahr:
	include("./abweichendes_sj.php");

include("./config.php");

//Wenn in der Session noch kein Schuljahr gewählt wurde:

if (!isset($_SESSION['schuljahr'])) {
	
	/*
	if ($beginn_schuljahr > time()) {
	$_SESSION['schuljahr'] = ($jahr - 1)."-".$jahr;
	} else {
	$_SESSION['schuljahr'] = $jahr."-".($jahr + 1);
	}
	*/
	$_SESSION['schuljahr'] = $schuljahr; //SJ gemäß Eingabe in config.php
	
}






// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";


echo "<table style='width: 71em;'><tr>";
echo "<td>";
echo "<h1><b>Anmeldungen</b> ".$periode."</h1>";
echo "</td>";

echo "<td allign='left' style='padding-top: 1em;' >";

echo "<form id='form_sj' action='".$_SERVER['PHP_SELF']."' method='POST'>";
echo"<select name='sj' onchange='change_sj()'>";
echo "<option value=''>Anmeldeperiode wechseln</option>";



foreach($schuljahre as $sjahr) {
	
	if (!isset($_SESSION['erstes_schuljahr'])) {
		$_SESSION['erstes_schuljahr'] = $sjahr['jahr'];
	}
			
				//Nur auflisten, wenn admin, FT oder Schuljahr in config.php für Lehrkräfte freigegeben ist:
				if ($_SESSION['admin'] == "true" OR $_SESSION['ft'] == "true" OR $sjahr['sichtbar_lk'] == 1) {
					
					echo "<option value='".$sjahr['jahr']."'>Anmeldungen ".$sjahr['periode']."</option>";
				}
				
			}



echo "</select>";
echo "</form>";

echo "</td>";
echo "</tr>";
echo "</table>";



?>

<script>
		function change_sj(){
			document.getElementById("form_sj").submit();
		}
		</script>

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
    <h1 style='margin-bottom: 2em;'> </h1>
	
	<form method="post" action="./liste.php?pk=0">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Liste Schüleranmeldungen" />
</form>

	<form method="post" action="./einschulung.php">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-warning btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Übersicht Einschulung 2024" />
</form>
<?php

if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) {	
?>
	<form method="post" action="./klassenlisten.php?pk=0">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aktuelle Klassenlisten" />
</form>
<?php
}
if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) {	
?>
<form class='flex-container' method="post" action="./todo.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ToDo-Liste" />
</form>
<?php
} else {
?>
<form class='flex-container' method="post" action="./todo.php">
<input style="width: 23.3em;" class='btn btn-defau	lt btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ToDo-Liste" disabled />
</form>
<?php
}

if ($_SESSION['admin'] == 1) {	
?>
<form  class='flex-container' method="post" action="./import_edoo.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="edoo.sys-Daten aktualisieren"  />
</form>
<?php
} 

if ($_SESSION['admin'] == 1) {	
?>
<form  class='flex-container' method="post" action="./fehler_ermitteln.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Abweichungen ermitteln"  />
</form>

<form  class='flex-container' method="post" action="./setup.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Setup"  />
</form>

<?php
}


if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) {	
?>
<form class='flex-container' method="post" action="./senden_texte.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hinweistexte anpassen" />
</form>

<form class='flex-container' method="post" action="../querliste/index.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Querliste 2.0" />
</form>
<?php
} else {
?>
<form class='flex-container' method="post" action="./todosenden_textephp">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hinweistexte anpassen" disabled />
</form>
<?php
}
if ($_SESSION['admin'] == 1 OR $_SESSION['sek'] == 1) {	
?>
<form  class='flex-container' method="post" action="./liste.php?pk=1">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Papierkorb" />
</form>
<?php
}

//if ($_SESSION['admin'] == 1) {	
?>
<form  class='flex-container' method="post" action="./statistik.php">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Statistik" />
</form>
<?php
//}
?>

<form class='flex-container' method="post" target='-blank' action="https://anmeldung.bbs1-mainz.de">
<input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Formularseite auf Website" />
</form>

<form method="post" action="./logout_ad.php">
<input style='margin-top: 20px;' type="submit" name="cmd[doStandardAuthentication]" value="Abmelden" />
</form>
</body>
</html>
<?php

echo "Berechtigungen: ".$rechte;

} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>
