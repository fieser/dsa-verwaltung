<?php



include("./kopf.php");


include("./login_inc.php");
@session_start();


// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	//Balken bei abweichendem Schuljahr:
	include("./abweichendes_sj.php");

	
include("./config.php");

?>
<style>
.box-grau {
   padding: 10px;
   background-color: #E0E0E0;
   margin-bottom: 20px;
   width: 100%;
}
</style>

<?php

$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];


//Änderungen in Datenbank schreiben
if (isset($_POST['submit'])) {
		
		$text_bs = $_POST['text_bs'];
		$text_bvj = $_POST['text_bvj'];
		$text_bgy = $_POST['text_bgy'];
		$text_bf1 = $_POST['text_bf1'];
		$text_bfp = $_POST['text_bfp'];
		$text_bos1 = $_POST['text_bos1'];
		$text_bos2 = $_POST['text_bos2'];
		$text_dbos = $_POST['text_dbos'];
		$text_bf2 = $_POST['text_bf2'];
		$text_fs = $_POST['text_fs'];
		$text_hbf = $_POST['text_hbf'];
		
		
		if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bs'	 WHERE `schulform` = 'bs'")) {
				
							}
							
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bvj'	 WHERE `schulform` = 'bvj'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bf1'	 WHERE `schulform` = 'bf1'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bf2'	 WHERE `schulform` = 'bf2'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bos1'	 WHERE `schulform` = 'bos1'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bos2'	 WHERE `schulform` = 'bos2'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_dbos'	 WHERE `schulform` = 'dbos'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_fs'	 WHERE `schulform` = 'fs'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bgy'	 WHERE `schulform` = 'bgy'")) {
				
							}
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_hbf'	 WHERE `schulform` = 'hbf'")) {
				
							}
							
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_bfp'	 WHERE `schulform` = 'bfp'")) {
				
							}
							
							if ($db_temp->exec("UPDATE `senden_texte`
						   SET
							`text` = '$text_aph'	 WHERE `schulform` = 'aph'")) {
				
							}
		
}


echo "<h1><b>Hinweistexte für Nutzer</b></h1>";
echo "<p style='margin-bottom: 3em;'>Diese Texte werden den Nutzern direkt nach dem Versenden des Anmeldeformulars angezeigt.</p>";
?>
<p>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php
//Anmeldebögen auflisten:
$select_tex = $db_temp->query("SELECT * FROM senden_texte ORDER BY bezeichnung ASC");
$treffer_tex = $select_tex->rowCount();


echo "<form id='inputForm1' action='senden_texte.php' method='post'>";
echo "<table>";

foreach($select_tex as $tex) {
echo "<tr><td style='padding-bottom: 3px;'><h2>".$tex['bezeichnung'].":</h2></td></tr>";

	
echo "<tr><td colspan='3'><label><textarea style='width:100%; margin-bottom: 2em;' name='".$tex['feldname']."' cols='150' rows='12'>".$tex['text']."</textarea></p></label><td></tr>";

echo "</table>";

echo "<div class='box-grau' style='margin-bottom: 4em;'>";
echo $tex['text'];
echo "</div>";

}

echo "<p><input class='btn btn-default btn-sm' type='submit' id='inputForm1' name='submit' value='speichern' /></p>";	

echo "</form>";





	
		
		
		
		
		
	} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=senden_texte");

}	
		
		
	

?>

<p><br>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include("./fuss.php");
?>