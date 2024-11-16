<?php
$fertig = 0;
include "./config.php";





error_reporting(0);

date_default_timezone_set('Europe/Berlin');

@session_start();


// Ist Nutzer angemeldet?
if (isset($_SESSION['username']) AND $_SESSION['admin'] == 1) {

include("./kopf.php");
include("./rechte.php");




$id = $_GET['id'];


// Teilnahme stornieren
if (isset($_POST['tn_id'])) {
	
	$tn_id = $_POST['tn_id'];
	$bew_id = $_POST['bew_id'];


	$select3 = $db_temp->query("DELETE FROM summen WHERE (md5 = '$tn_id')");
	
	

	
	
	header("Location: ./liste.php");
}



$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$id'");	
		foreach($select_bew as $bew) {
			
			$bew_vorname = $bew['vorname'];
			$bew_nachname = $bew['nachname'];
			$bew_md5 = $bew['md5'];
			$bew_id = $bew['id'];
		}






echo "<h1 style='padding-bottom: 2em;'>Möchten Sie für ".$bew_vorname." ".$bew_nachname." wirkliche eine weitere Anmeldung für den selben Bildungsgang zulassen?</h1>";


echo "<table>";



echo "<td style='padding: 10px;' align='left'>";
	echo "<form id='form3' action='./md5_loeschen.php' method='POST'>";
	echo "<input type='hidden' name='tn_id' value='".$bew_md5."'>";
	echo "<input type='hidden' name='bew_id' value='".$bew_id."'>";
	//echo "<input type='hidden' name='te_id' value='".$te['id']."'>";
	echo "<input class='btn btn-default btn-sm' type='submit' style='width: 18em;' name='submit_storno' value='ja'>";
	echo "</form>";
echo "<p>&nbsp;&nbsp;</p>";

?>
<form method="post" action="./liste.php">
<input class="btn btn-default btn-sm" type="submit" name='submit' style='width: 18em;' formmethod="post" formaction="./liste.php" value="nein">
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
