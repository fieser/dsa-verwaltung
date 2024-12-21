<?php
ini_set('max_execution_time', 300); // 300 Sekunden = 5 Minuten
set_time_limit(0);
ignore_user_abort(true); //auch wenn der Nutzer die Sete verlässt, läuft das Skript weiter

$start = microtime(true);



date_default_timezone_set('Europe/Berlin');



if (!isset($pfad_workdir)) {
	$pfad_workdir = "/var/www/html/verwaltung/";
}

include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();




	
include($pfad_workdir."config.php");


// Ist Nutzer angemeldet?
//if (isset($_SESSION['username'])) {



$db->query("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5");

        foreach($select_an as $an) {
            
            $klasse = "";
            $id = trim($an['0']); //Bewer"berdaten
            $id_bil = trim($an['id']); //Bildungsgang
            $md5_bew = trim($an['md5']); //Bewerberdaten md5
            $md5_bil = trim($an['49']); //Bildungsgang md5
            $nachname = trim($an['nachname']);
            $vorname = trim($an['vorname']);
            $geburtsdatum = trim($an['geburtsdatum']);

            include($pfad_workdir."status_setzen.php");
            
           
        }

echo "Status aktualisiert!";


//} //Ende, wenn username
?>

<p>
    <form method="post" action="./index.php">
        <input style='margin-top: 3em;' type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
    </form>
</p>

<?php

include($pfad_workdir."fuss.php");
?>