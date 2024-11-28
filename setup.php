<?php
// Fehleranzeige aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

@session_start();
include("./config.php");

include($pfad_workdir."kopf.php");

include($pfad_workdir."login_inc.php");


if (isset($_POST['mig_password'])) {
    $_SESSION['mig_password'] = $_POST['mig_password'];
} else {
    if (!isset($_SESSION['mig_password'])) {
    $_SESSION['mig_password'] = "";
    }
}



echo "<h1>Aktualisierung der Datenbanken</h1>";


echo "<p style='margin-top: 2em;'>";
echo "Geben Sie Ihr Datenpasswort ein!";
echo "<form style='margin: 0 0 4em 0';' method='post' action='./setup.php'>";
echo "<input style='width: 20em; margin-bottom: 1em;' type='password' class='form-control' name='mig_password' value='".$_SESSION['mig_password']."' placeholder='Passwort eingeben' required>";
echo "<input style='width: 20em;' type='submit' class='btn btn-default btn-sm' name='cmd[changePassword]' value='Passwort temporär speichern' />";
echo "</form>";
echo "</p>";

if ((isset($treffer_temp) OR isset($treffer_www)) AND ($treffer_temp == 0 OR $treffer_www == 0)) {
echo "<p>
<a href='./db_update.php' class='btn btn-default btn-sm' style='width: 20em; display: inline-block; margin: 0 0 1em 0;'>Datenbank Migration</a>
</p>";
}
/*
echo "<p>
<a href='./db_status.php' class='btn btn-default btn-sm' style='width: 20em; display: inline-block; margin: 0 0 4em 0;'>Tabellen prüfen</a>
</p>";
*/

include("./db_suchen_opw.php");




?>

<p>
<form name='form_x' method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
?>