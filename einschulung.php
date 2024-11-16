<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();

$_SESSION['test_lk'] = 1;

$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



include("./config.php");




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
  echo "<title>Einschulung ins Schuljahr ".$schuljahr."</title>";
	
?>
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
  flex: 30%;
  text-align: left;
  
  	  .center p {
	  vertical-align: middle;
	}

}



.flex-item-center {
  padding: 10px;
  flex: 30%;
  text-align: left;
  
    .center p {
	  vertical-align: middle;
  }
}

.flex-item-right {
  padding: 10px;
  flex: 30%;
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
    echo "<h1 style='margin-bottom: 2em;'>Einschulung ins Schuljahr ".$schuljahr."</h1>"
	?>

<div class='box-grau' style='background-color: #A9D0F5;'>
<p>Diese Seite ist <b>nur in der Einschulungswoche und am 23.09.2024 verfügbar</b>. <br>Sie ist für die Klassenleitungen und nicht für Schülernnen und 
Schüler bestimmt.</p>
</div>


<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>1. WLAN-Zugang</b></p>
<img src='./images/wifi.svg' width='75px'>
<p>An den ersten Schultagen können die Schülerinnen und Schüler das freie WLAN <i>BYOD</i> nutzen.
</p>
<p>Danach kann nur noch das WLAN <i>WLAN BBS1</i> mit den jeweiligen persönlichen Zugangsdaten genutzt werden.
</p>
</div>



<div class='flex-item-center' style='text-align: center;'>
		<img src='./einschulung/qr_byod.jpg' width='200px'>
		
</div>
<div class='flex-item-right'>
<table>
<tr>
<td>SSID:</td><td style='padding-left: 0.5em;'><b>BYOD</b></td>
</tr>
<tr>
<td>KEY:</td><td style='padding-left: 0.5em;'><b>bbs1mainz</b></td>
</tr>
</table>
</div>
</div>

</div>


<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='font-size: 1.2em; margin:0 0 10 0;'><b>2. Anmeldungen</b></p>
<img src='./images/add-user.svg' width='75px'>' 
<p>SuS, die in der Liste <b><i>"Aktuelle digitale Anmeldungen"</i></b> oder in der <b><i>"Papierliste"</i> im Klassenordner</b> erscheinen, 
sind bereits angemeldet.<br>
Lassen Sie SuS, die in keiner der Listen stehen, das digitale Anmeldeformular ausfüllen!<br>
Den Eingang der Anmeldung können Sie direkt anschließend unter <i>"Aktuelle digitale Anmeldungen"</i> prüfen.</p>
<p>Speichern Sie, wie in den letzten Jahren, die E-Mail-Adressen der SuS in der Schülerkontenverwaltung 
und senden Sie ihnen anschließend die Zugangsdaten zu.</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="./liste.php?pk=0">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aktuelle digitale Anmeldungen" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	<form class='flex-container' method="post" action="./einschulung/schulbezirke.pdf" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Schulbezirke der BBS 1" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>
<div class='flex-item-right'>
	<form class='flex-container' method="post" action="https://add.rlp.de/themen/schule-und-bildung/schuelerinnen-und-eltern/wahl-und-pflichtschulbereiche-berufsbildende-schulen" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Überweisungantrag" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>

	<form class='flex-container' method="post" action="https://anmeldung.bbs1-mainz.de" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Anmeldeformular" />
</form>
</div>


<div class='flex-item-center'>
<div style='font-size: 1.5em'><b>anmeldung</b>.bbs1-mainz.de</div>
</div>

<div class='flex-item-right'>
</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>

	<form class='flex-container' method="post" action="https://ilias.bbs1-mainz.de/skv/" target="_blank">
<input style="width: 100%;" class='btn btn-warning btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Schülerkontenverwaltung (SKV)" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>

</div>

<div class='flex-item-center'>
</div>

<div class='flex-item-right'>
</div>

</div>
</div>


<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>3. Dokumente für Schülerinnen und Schüler</b></p>
<img src='./images/pdf2.svg' width='60px'>
<p>Händigen Sie den SuS das Begrüßngsschreiben mit den rückseitigen Infos zur digitalen 
Grundausstattung und die Hausordnung aus und besprechen Sie die diese Dokumente.</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="./einschulung/Infoschreiben.pdf">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Begrüßungsschreiben" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	<form class='flex-container' method="post" action="./einschulung/hausordnung_bbs1_mainz.pdf" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hausordnung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>
<div class='flex-item-right'>
	<form class='flex-container' method="post" action="./einschulung/DigitaleGrundausstattungBBS1.pdf" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Digitale Grundausstattung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>

	<form class='flex-container' method="post" action="https://ilias.bbs1-mainz.de/ausweis" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Digitaler Schülerausweis" />
</form>
</div>


<div class='flex-item-center'>
<div style='font-size: 1.5em'><b>ausweis</b>.bbs1-mainz.de</div>
</div>

<div class='flex-item-right'>
</div>
</div>


</div>


<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>4. Verhalten im Brandfall</b></p>
<img src='./images/fire.svg' width='75px'>
<p>Besprechen Sie mit den Schülerinnen und Schülern das Verhalten im Brandfall, gemäß dem im Raum ausgehängten Informationsblatt und dem Fluchtwegeplan auf dem Flur.</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="./einschulung/brandfall.pdf" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aushang im Klassenraum" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	
</div>
<div class='flex-item-right'>
	
</div>
</div>




</div>

<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>5. Stunden-, Block- und Vertretungsplan</b></p>
<img src='./images/kalender.svg' width='75px'>
<p>Zeigen Sie den SuS den Weg zu den Stunden- und Blockplänen.</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="https://www.bbs1-mainz.com/stundenplaene/aktuelle-stundenplaene/" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Stundenplan" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	<div style='font-size: 1.5em'><b>www</b>.bbs1-mainz.de</div>
</div>
<div class='flex-item-right'>
	
</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>

	<form class='flex-container' method="post" action="https://www.bbs1-mainz.com/stundenplaene/block-und-teilzeitplaene/" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Blockplan" />
</form>
</div>


<div class='flex-item-center'>
<div style='font-size: 1.5em'><b>www</b>.bbs1-mainz.de</div>
</div>

<div class='flex-item-right'>
</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>
<form class='flex-container' method="post" action="http://webuntis.bbs1-mainz.de" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Webuntis" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>

</div>

<div class='flex-item-center'>
<div style='font-size: 1.5em'>webuntis.bbs1-mainz.de</div>
</div>

<div class='flex-item-right'>
</div>

</div>
<div class='flex-container'>
<div class='flex-item-left'>
<form class='flex-container' method="post" action="https://ilias.bbs1-mainz.de/abwesenheit" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Abwesenheitsmeldung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>

</div>

<div class='flex-item-center'>
<div style='font-size: 1.5em'>(Über Webuntis erreichbar.)</div>
</div>

<div class='flex-item-right'>
</div>

</div>
</div>


<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>6. Lernplattformen</b></p>
<img src='./images/book.svg' width='75px'>
<p>Zeigen Sie den SuS wie sie MS-Office und/oder ILIAS nutzen können.</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="https://www.office.com" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="MS-Office/Teams/OneNote" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	<div style='font-size: 1.5em'>www.office.com</div>
</div>
<div class='flex-item-right'>

</div>
</div>

<div class='flex-container'>
<div class='flex-item-left'>
<form class='flex-container' method="post" action="https://ilias.bbs1-mainz.de/" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ILIAS-Server" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
	
</div>


<div class='flex-item-center'>
<div style='font-size: 1.5em'><b>ilias</b>.bbs1-mainz.de</div>
</div>

<div class='flex-item-right'>
</div>
</div>
</div>

<div class='box-grau'>
<div class='flex-container'>
<div class='flex-item-left'>
<p style='margin:0 0 10 0; font-size: 1.2em;'><b>7. Terminbuchung Schülerfotos</b></p>
<img src='./images/camera.svg' width='75px'>
<p>Bitte vereinbaren Sie für neue Klassen einen Fototermin. Die Schüler müssen dann aber der Aufnahme von Portraitfotos beim Freischalten der Schüleraccounts zugestimmt haben. Die Fotos erscheinen auf dem Schülerausweis und für Lehrkräfte in Webuntis.
Die Terminvereinbarung mit dem Sekretariat erfolgt ab Montag über nachfolgende Schaltfläche oder den Link <i>Termine Schülerfotos</i> auf der Serviceseite (Kachel „Prozesse“).
Suchen Sie sich in der Liste einen passenden freien Termin und tragen Sie sich über die Schaltflächen <i>Aktionen &#10140; Bearbeiten</i> mit der Klassenbezeichnung und Ihrem Nachnamen ein. 
<b>Es erfolgt keine weitere Bestätigung des Termins.</b> Dieses Jahr werden die Fotos im Raum B122a aufgenommen.
</p>
</div>

</div>

<div class='flex-container'>
<div class='flex-item-left'>

<form class='flex-container' method="post" action="https://service.bbs1-mainz.de/ilias/goto.php?target=dcl_1521&client_id=verwaltung" target="_blank">
<input style="width: 100%;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Termine Schülerfotos" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</div>

<div class='flex-item-center'>
	
</div>
<div class='flex-item-right'>
	
</div>
</div>

</div>




<form method="post" action="./logout_ad.php">
<input style='margin-top: 20px;' type="submit" name="cmd[doStandardAuthentication]" value="Abmelden" />
</form>
</body>
</html>
<?php

echo "Berechtigungen: ".$rechte;

} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=einschulung");

}

include("./fuss.php");

?>
