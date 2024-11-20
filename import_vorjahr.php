<?php



include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();




	
include($pfad_workdir."config.php");



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



$jgst_1 = intval($schuljahr[2].$schuljahr[3]);
$jgst_1 = trim($jgst_1);
$jgst_2 = trim($jgst_1 - 1);
$jgst_3 = trim($jgst_1 - 2);
$jgst_4 = trim($jgst_1 - 3);








// Schüler aus Klassen importieren:

$schueler_neu = 0;

if (file_exists($pfad_workdir."daten/anmeldungen_vorjahr.csv")) {
			
				// Tabelle leeren:
				
				
					if ($db->exec("TRUNCATE TABLE `dsa_bildungsgang_vorjahr`")) {
		
						echo "<font color='orange'>Alle Vorjahresdaten gelöscht!<br>";

					}

		 
		$file_an = $pfad_workdir."daten/anmeldungen_vorjahr.csv";
		 
		$file_handle = fopen($file_an, 'r');
								 
			while (!feof($file_handle)) {
				

			  $line2 = fgets($file_handle);
			  
			  $line2 = str_replace("'"," ", $line2); // Entfernt die Ausführungszeichen
			  $line2 = str_replace("\"","", $line2); // Entfernt Sonderzeichen, weil Skript sonst abbrach.
			  //$line2 = str_replace("2023","2024", $line2); // Vorjahrsjahr in aktuelles Jahr wandeln.
			  
			  $teilung = explode(";", $line2);
			  
			  $time = $teilung[0];
			  $schulform = $teilung[1];
			  
			  $time = strtotime($time);
			  

			  
			  				// Datensatz neu in DB schreiben:

					if ($db->exec("INSERT INTO `dsa_bildungsgang_vorjahr`
								   SET
									`time` = '$time',
									`schulform` = '$schulform'")) {
					
					$last_id = $db->lastInsertId();
					
					
					$schueler_neu = ($schueler_neu + 1);
					
						
					}

			}	

		fclose($file_handle);

echo $schueler_neu." Schülerdatensätze importiert."; 

		} else {
			echo "<p>CSV-Datei nicht gefunden!</p>";
		}







?>

<p>
<form method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
?>