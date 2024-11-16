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


$select_bil = $db->query("SELECT * FROM dsa_bildungsgang");	
$treffer_bil = $select_bil->rowCount();

$sum_bilobew = 0;
foreach($select_bil as $bil) {
	$bil_md5 = $bil['md5'];
	
	$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten WHERE md5 = '$bil_md5'");	
	$treffer_bew = $select_bew->rowCount();
	if ($treffer_bew == 0) {
		echo "md5 ".$bil['md5']." nicht gefunden.";
		$sum_bilobew = ($sum_bilobew + 1);
	}
}
echo "<p>Es gibt ".$sum_bilobew." Datensätze in dsa_bildungsgang ohne Datensatz in dsa_bewerberdaten.</p>";


$select_bew = $db->query("SELECT * FROM dsa_bewerberdaten");	
$treffer_bew = $select_bew->rowCount();

$sum_bewobil = 0;
foreach($select_bew as $bew) {
	$bew_md5 = $bew['md5'];
	
	$select_bil = $db->query("SELECT * FROM dsa_bildungsgang WHERE md5 = '$bew_md5'");	
	$treffer_bil = $select_bil->rowCount();
	if ($treffer_bew == 0) {
		echo "md5 ".$bil['md5']." nicht gefunden.";
		$sum_bewobil = ($sum_bewobil + 1);
	}
}
echo "<p>Es gibt ".$sum_bewobil." Datensätze in dsa_bewerberdaten ohne Datensatz in dsa_bildungsgang.</p>";


} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>