<?php
$fertig = 0;






error_reporting(0);

date_default_timezone_set('Europe/Berlin');

@session_start();
include "./config.php";

// Ist Nutzer angemeldet?
if (isset($_SESSION['username']) AND $_SESSION['admin'] == 1) {

include("./kopf.php");
include("./rechte.php");




$id = $_GET['id'];


// Teilnahme stornieren
if (isset($_POST['tn_id'])) {
	
	$tn_id = $_POST['tn_id'];
	$bew_id = $_POST['bew_id'];
	$md5_o_sf = $_POST['md5_o_sf'];

	$select = $db->query("DELETE FROM dsa_bewerberdaten WHERE (id = '$bew_id')");
	$select = $db->query("DELETE FROM dsa_bildungsgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	$select = $db_temp->query("DELETE FROM summen WHERE (md5 = '$tn_id')");
	$select = $db->query("DELETE FROM vorgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	$select = $db->query("DELETE FROM mail WHERE (id_dsa_bewerberdaten = '$bew_id')");


	if ($md5_o_sf != "") {
		
		$directory = 'dokumente/unpacked'; // Verzeichnis, in dem die Dateien gespeichert sind.

		// Dateien im Verzeichnis auflisten
		$files = scandir($directory);
		foreach ($files as $file) {
			// Überprüfen, ob die Datei den spezifischen Namensteil vor dem ersten Punkt im Namen hat.
			if (pathinfo($file, PATHINFO_EXTENSION) && explode('.', $file)[0] === $md5_o_sf) {
				// Vollständigen Pfad zur Datei generieren
				$filePath = $directory . '/' . $file;

				// Datei löschen
				if (unlink($filePath)) {
					echo "Datei '$file' erfolgreich gelöscht.<br>";
					
				} else {
					echo "Fehler beim Löschen der Datei '$file'.<br>";
				}
			}
		}
		
	} 

	header("Location: ./liste.php");
	
}



$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$id'");	
		foreach($select_bew as $bew) {
			
			$bew_vorname = $bew['vorname'];
			$bew_nachname = $bew['nachname'];
			$bew_md5 = $bew['md5'];
			$bew_id = $bew['id'];
		}
		
$select_sum = $db_temp->query("SELECT md5_o_sf FROM summen WHERE md5 = '$bew_md5'");
foreach($select_sum as $sum) {
	$md5_o_sf = $sum['md5_o_sf'];

	//Gibt es mehrere?
	$select_sum2 = $db_temp->query("SELECT id FROM summen WHERE md5_o_sf = '$md5_o_sf'");
	$treffer_sum2 = $select_sum2->rowCount();
	if ($treffer_sum2 != 1) {
		$md5_o_sf = "";
	}
}



?>




<h1 style='padding-bottom: 2em;'>Möchten Sie diese Anmeldung wirklich löschen?</h1>
<table>

<?php


echo "<td style='padding: 10px;' align='left'>";
	echo "<form id='form3' action='./anmeldung_loeschen.php' method='POST'>";
	echo "<input type='hidden' name='tn_id' value='".$bew_md5."'>";
	echo "<input type='hidden' name='bew_id' value='".$bew_id."'>";
	echo "<input type='hidden' name='md5_o_sf' value='".$md5_o_sf."'>";
	//echo "<input type='hidden' name='te_id' value='".$te['id']."'>";
	echo "<input class='btn btn-default btn-sm' type='submit' style='width: 18em;' name='submit_storno' value='löschen'>";
	echo "</form>";
echo "<p>&nbsp;&nbsp;</p>";

?>
<form method="post" action="./liste.php">
<input class="btn btn-default btn-sm" type="submit" name='submit' style='width: 18em;' formmethod="post" formaction="./liste.php" value="abbrechen">
<?php

?>
</td></form>
</tr>
<tr><td>&nbsp;&nbsp;</td></tr>

</table>





<table>

<tr height="50"></tr>
<tr>
<td>
<form method="post" action="./index.php">
<input type="submit" name="cmd[doStandardAuthentication]" value="Menü" />
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


<table>
<tr height="30"></tr>

</table>
<?php


include("./fuss.php");

} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");
}
?>
