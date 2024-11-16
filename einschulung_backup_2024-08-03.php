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
    echo "<h1 style='margin-bottom: 2em;'>Einschulung ins Schuljahr ".$schuljahr."</h1>"
	?>

<div class='box-grau' style='background-color: #A9D0F5;'>
<p>Diese Seite ist <b>nur in der Einschulungswoche und am 23.09.2024 verfügbar</b>. Sie ist für die Klassenleitungen und nicht für Schülernnen und 
Schüler bestimmt. <b>Wir empfehlen die Nutzung der Funktion <i>einfrieren</i> des Beamers.</p>
</div>

<div class='box-grau'>
<table>
<tr><td colspan='3'><p style='margin:0 0 10 0; font-size: 1.2em;'><b>1. Anmeldungen</b></p>
<p>SuS, die in der Liste <i>"Aktuelle digitale Anmeldungen"</i> oder in der <i>"Papierliste"</i> im Klassenordner erscheinen, 
sind bereits angemeldet.<br>
Lassen Sie SuS, die in keiner der Listen stehen, das digitale Anmeldeformular ausfüllen!<br>
Den Eingang der Anmeldung können Sie direkt anschließend unter <i>"Aktuelle digitale Anmeldungen"</i> prüfen.</p>
<p>Speichern Sie, wie in den letzten Jahren, die E-Mail-Adressen der SuS in der Schülerkontenverwaltung 
und senden Sie ihnen anschließend die Zugangsdaten zu.</p>
</td></tr>


<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="./liste.php?pk=0">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aktuelle digitale Anmeldungen" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="./einschulung/schulbezirke.pdf" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Schulbezirke der BBS 1" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://add.rlp.de/themen/schule-und-bildung/schuelerinnen-und-eltern/wahl-und-pflichtschulbereiche-berufsbildende-schulen" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Überweisungantrag" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
</tr>
<tr>
<td>
	<form method="post" action="https://anmeldung.bbs1-mainz.de" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Anmeldeformular" />
</form>
</td>
<td colspan='2' style='padding: 0 10 5 0;'>
<div style='font-size: 1.5em'><b>anmeldung</b>.bbs1-mainz.de</div>
</td>
</tr>
<tr>
<td style='padding: 5 10 5 0;' colspan='3'>

	<form method="post" action="https://ilias.bbs1-mainz.de/skv/" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Schülerkontenverwaltung (SKV)" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>

</td>
</tr>

</table>
</div>

<div class='box-grau'>
<table>
<tr><td colspan='3'><p style='margin:0 0 10 0; font-size: 1.2em;'><b>2. Dokumente für Schülerinnen und Schüler</b></p>
<p>Händigen Sie den SuS das Begrüßngsschreiben mit den rückseitigen Infos zur digitalen Grundausstattung und die Hausordnung aus und besprechen Sie die diese Dokumente.</p></td></tr>
<tr>


<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="./einschulung/Infoschreiben.pdf">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Begrüßungsschreiben" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="./einschulung/hausordnung_bbs1_mainz.pdf" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hausordnung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="./einschulung/DigitaleGrundausstattungBBS1.pdf" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Digitale Grundausstattung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
</tr>

<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://ilias.bbs1-mainz.de/ausweis" target="_blank"">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Digitaler Schülerausweis" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'><b>ausweis</b>.bbs1-mainz.de</div>
</td>
</tr>
</table>
</div>



<div class='box-grau'>
<table>
<tr><td colspan='3'><p style='margin:0 0 10 0; font-size: 1.2em;'><b>3. Verhalten im Brandfall</b></p>
<p style='margin: 0 0 20 0;'>Zeigen Sie den SuS wie sie MS-Office und/oder ILIAS nutzen können.</p></td></tr>
<tr>

<td style='padding: 5 10 5 0;'>
	<form method="post" action="./dokumente/brandfall.pdf">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aushang im Klassenraum" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>

</td>

</tr>






</table>
</div>


<div class='box-grau'>
<table>
<tr><td colspan='3'><p style='margin:0 0 10 0; font-size: 1.2em;'><b>4. Stunden-, Block- und Vertretungsplan</b></p>
<p style='margin: 0 0 20 0;'>Zeigen Sie den SuS den Weg zu den Stunden- und Blockplänen.</p></td></tr>
<tr>

<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://www.bbs1-mainz.com/stundenplaene/aktuelle-stundenplaene/">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Stundenplan" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'><b>www</b>.bbs1-mainz.de</div>
</td>

</tr>
<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://www.bbs1-mainz.com/stundenplaene/block-und-teilzeitplaene/" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Blockplan" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'><b>www</b>.bbs1-mainz.de</div>
</td>

</tr>
<tr>

<td style='padding: 5 10 5 0;'>
	<form method="post" action="http://webuntis.bbs1-mainz.de" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Webuntis" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'><b>webuntis</b>.bbs1-mainz.de</div>
</td>

</tr>
<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://ilias.bbs1-mainz.de/abwesenheit" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Abwesenheitsmeldung" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td><p>(Über Webuntis erreichbar.)</p></td>
</tr>




</table>
</div>


<div class='box-grau'>
<table>
<tr><td colspan='3'><p style='margin:0 0 10 0; font-size: 1.2em;'><b>5. Lernplattformen</b></p>
<p style='margin: 0 0 20 0;'>Zeigen Sie den SuS wie sie MS-Office und/oder ILIAS nutzen können.</p></td></tr>
<tr>

<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://www.office.com">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="MS-Office/Teams/OneNote" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
</td>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'>www.office.com</div>
</td>

</tr>
<tr>
<td style='padding: 5 10 5 0;'>
	<form method="post" action="https://ilias.bbs1-mainz.de/" target="_blank">
<input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ILIAS-Server" />
<?php
echo "<input type='hidden' name='back' value='einschulung'>";
?>
</form>
<td style='padding: 5 10 5 0;'>
<div style='font-size: 1.5em'><b>ilias</b>.bbs1-mainz.de</div>
</td>

</tr>





</table>
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
