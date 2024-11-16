
<head>
<link rel="stylesheet" type="text/css" href="delos.css" >
<link rel="stylesheet" type="text/css" href="style2.css" >
</head>
<?php

include("./login_inc.php");
@session_start();




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



include "./rechte.php";
include "./config.php";

$id = $_GET['id'];

$select_em = $db->query("SELECT * FROM mail WHERE id_dsa_bewerberdaten = '$id' ORDER BY last_time ASC");	
	foreach($select_em as $em) {		
	
	$em_mailtext = $em['mailtext'];
	$em_last_time = $em['last_time'];
	$em_last_user = $em['last_user'];
	
	}

echo "<p>".nl2br($em_mailtext)."</p>";

}
?>