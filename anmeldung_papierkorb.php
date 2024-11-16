<?php
$fertig = 0;
include "./config.php";





error_reporting(0);

date_default_timezone_set('Europe/Berlin');

@session_start();


// Ist Nutzer angemeldet?
if (isset($_SESSION['username']) AND ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1)) {

include("./kopf.php");
include("./rechte.php");




$id = $_GET['id'];


// Anmeldung in Papierkorb
if (isset($_POST['tn_id']) AND $_POST['pap_rein'] == 1) {
	
	$tn_id = $_POST['tn_id'];
	$bew_id = $_POST['bew_id'];
	$pap_time = time();
	$pap_user = $_SESSION['username'];

	//$select = $db->query("DELETE FROM dsa_bewerberdaten WHERE (id = '$bew_id')");
	//$select = $db->query("DELETE FROM dsa_bildungsgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	//$select = $db_temp->query("DELETE FROM summen WHERE (md5 = '$tn_id')");
	
		if ($db_temp->exec("UPDATE `summen`
			   SET
				`papierkorb` = '1' WHERE `md5` = '$tn_id'")) { 
				

			header("Location: ./liste.php");
		}
		
	//$select = $db->query("DELETE FROM vorgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	//$select = $db->query("DELETE FROM mail WHERE (id_dsa_bewerberdaten = '$bew_id')");
	
		if ($db->exec("UPDATE `dsa_bewerberdaten`
			   SET
			   `pap_user` = '$pap_user',
			   `pap_time` = '$pap_time',
				`papierkorb` = '1' WHERE `id` = '$bew_id'")) { 
				

			header("Location: ./liste.php");
		}
}

// Anmeldung aus Papierkorb nehmen / wiederherstellen
if (isset($_POST['tn_id']) AND $_POST['pap_rein'] == 0) {
	
	$tn_id = $_POST['tn_id'];
	$bew_id = $_POST['bew_id'];
	$pap_time = time();
	$pap_user = $_SESSION['username'];

	//$select = $db->query("DELETE FROM dsa_bewerberdaten WHERE (id = '$bew_id')");
	//$select = $db->query("DELETE FROM dsa_bildungsgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	//$select = $db_temp->query("DELETE FROM summen WHERE (md5 = '$tn_id')");
	
	if ($db_temp->exec("UPDATE `summen`
			   SET
				`papierkorb` = '0' WHERE `md5` = '$tn_id'")) { 
				

			header("Location: ./liste.php");
		}
	//$select = $db->query("DELETE FROM vorgang WHERE (id_dsa_bewerberdaten = '$bew_id')");
	//$select = $db->query("DELETE FROM mail WHERE (id_dsa_bewerberdaten = '$bew_id')");
	
	
		if ($db->exec("UPDATE `dsa_bewerberdaten`
			   SET
			   `pap_user` = '$pap_user',
			   `pap_time` = '$pap_time',
				`papierkorb` = '0' WHERE `id` = '$bew_id'")) { 
				

			header("Location: ./liste.php");
		}
}


$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten WHERE id = '$id'");	
		foreach($select_bew as $bew) {
			
			$bew_vorname = $bew['vorname'];
			$bew_nachname = $bew['nachname'];
			$bew_md5 = $bew['md5'];
			$bew_id = $bew['id'];
		}



if ($_POST['pap_rein'] == 0) {
?>

<h1 style='padding-bottom: 2em;'>Möchten Sie diese Anmeldung wiederherstellen?</h1>

<?php
} else {
	?>

<h1 style='padding-bottom: 2em;'>Möchten Sie diese Anmeldung in den Papierkob verschieben?</h1>

<?php
}

echo "<table>";

echo "<td style='padding: 10px;' align='left'>";
	echo "<form id='form3' action='./anmeldung_papierkorb.php' method='POST'>";
	echo "<input type='hidden' name='tn_id' value='".$bew_md5."'>";
	echo "<input type='hidden' name='bew_id' value='".$bew_id."'>";
	
	echo "<input type='hidden' name='pap_rein' value='".$_POST['pap_rein']."'>";
	//echo "<input type='hidden' name='te_id' value='".$te['id']."'>";
	if ($_POST['pap_rein'] == 1) {
		echo "<input class='btn btn-default btn-sm' type='submit' style='width: 18em;' name='submit_storno' value='in den Papierkorb'>";
	} else {
		echo "<input class='btn btn-default btn-sm' type='submit' style='width: 18em;' name='submit_storno' value='wiederherstellen'>";
	
	}
	
	echo "</form>";
echo "<p>&nbsp;&nbsp;</p>";

?>
<form method="post" action="./liste.php">
<input class="btn btn-default btn-sm" type="submit" name='submit' style='width: 18em;' formmethod="post" formaction="./liste.php" value="abbrechen">

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
