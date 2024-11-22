<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();


include("./config.php");




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";


$select_sum = $db_temp->query("SELECT md5, prio FROM summen WHERE prio NOT LIKE ''");	
$treffer_sum = $select_sum->rowCount();

foreach($select_sum as $sum) {
	$sum_md5 = $sum['md5'];
	$sum_prio = $sum['prio'];
	
	
	if ($db->exec("UPDATE `dsa_bildungsgang`
		   SET
			`prio` = '$sum_prio' WHERE `md5` = '$sum_md5' AND `prio` NOT LIKE '$sum_prio'")) { 
			echo "Prio für ".$sum_md5." auf ".$sum_prio." korrigiert.<br>";
					
			}	
			
			

}




} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>