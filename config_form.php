<?php

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');



@session_start();
include("./config.php");




 

// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {






//Formular auswerten:

if (isset($_POST['submit'])) {
	
	$select_sps = $db_temp->query("SELECT id FROM config");
		$treffer_sps = $select_sps->rowCount();

	foreach($select_sps as $sps) {
		$sps_id = $sps['id'];
        
		
		if (isset($_POST['status'][$sps_id])) {

            
			$status = $_POST['status'][$sps_id];
            
		} else {
        	$status = 0;
		}


	//Datensatz schreiben:
	if ($db_temp->exec("UPDATE `config`
									SET
									`wert` = '$status' WHERE `id` = '$sps_id'")) {

					}
	}
/*
echo "<pre>";
print_r ($_POST['status']);
echo "</pre>";
*/
}




echo "<h1><b>Konfiguration</b></h1>für Verwaltungstool und Formaularserver<br>"; //Gemäß config.php




echo "<table>";
echo "<p>";

// Schaltfläche Menü:

?>
<tr>
<td colspan='1' style='vertical-align: middel;'>
<form method='post' action='./index.php'>
<input class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Menü' />
</form></td>

</tr>
<?php


echo "<tr><td></td><td >&nbsp;</td></tr>";
echo "<table style='color: black; background-color: #E0E0E0; line-height: 1.5em;'>";

echo "<form id='form1' method='post' action='./config_form.php'>";
//Tabellenkopf:

$style_kopf = "padding: 10px; border: 5px solid; border-color: #E0E0E0; background-color: #ffffff;";

echo "<tr class='fest'>";
echo "<td style='".$style_kopf."'><b>Einstellung</b></td>";
echo "<td style='".$style_kopf."' align='center'><b></b></td>";;


echo "<td style='padding: 5px; vertical-align: middel;' align='right'><input class='btn btn-default btn-sm' name='submit' type='submit' value='speichern'></td>";


echo "</tr>";

        //Konfiguration Schulformen abfragen:
            $select_cosf = $db_temp->query("SELECT * FROM config WHERE bereich LIKE 'Schulformen' ORDER BY server ASC, text ASC");
            $treffer_cosf = $select_cosf->rowCount();

            echo "<tr style='".$style."'>";
            echo "<td style='padding: 10px;' align='left'><br><b><i>Formularserver</i></b></td>";
            echo "</tr>";

            echo "<tr style='".$style."'>";
                echo "<td style='padding: 10px;' align='left'><i>Aktivierung der Schulformen</i></td>";
                echo "</tr>";

                foreach ($select_cosf as $cosf) {

                echo "<tr style='".$style."'>";
                

                if ($cosf['text'] != "") {
                echo "<td style='padding: 10 10 0 30px;' align='left'>".$cosf['text']."</td>";
                } else {
                    echo "<td style='padding: 10px;' align='left'>".$cosf['einstellung']."</td>";
                }
            
                if ($cosf['typ'] == "radio") {	
                    if ($cosf['wert'] == 1) {
                        echo "<td style='padding: 10px;' align='left'><input type='checkbox' id='".$cosf['id']."' name='status[".$cosf['id']."]' value='1' checked></td>";
                    } else {
                        echo "<td style='padding: 10px;' align='left'><input type='checkbox' id='".$cosf['id']."' name='status[".$cosf['id']."]' value='1'></td>";
                    }
                }	
                $server_alt = $server;
                    echo "</tr>";	

            }
			
		//Restliche Konfiguration abfragen
		
		$select_co = $db_temp->query("SELECT * FROM config WHERE bereich NOT LIKE 'Schulformen' ORDER BY server ASC, bereich ASC");
		$treffer_co = $select_co->rowCount();
		$server = "";
        $n = 0;




			
			foreach ($select_co as $co) {
				$einstellung = $co['einstellung'];
				$wert = $co['wert'];
				$id = $co['id'];
				$typ_co = $co['typ'];
				$bereich = $co['bereich'];
				$text = $co['text'];

                
                $server = $co['server'];
				
			
		

		
                //Zeilen:

                if ($server != $server_alt AND $n != 0) {
                    echo "<tr style='border-top-style: solid; border-color: f0f0f0; border-width: 5px'>";
                    }
                $n = ($n + 1);

                if ($server != $server_alt) {
                
                if ($server == "f") {
                $server_anzeige = "<br>";
                }
                if ($server == "v") {
                $server_anzeige = "<br>Verwaltungsserver";
                }



                echo "<tr style='".$style."'>";
                echo "<td style='padding: 10px;' align='left'><b><i>".$server_anzeige."</i></b></td>";
                echo "</tr>";
                }

                echo "<tr style='".$style."'>";
                

                        if ($text != "") {
                        echo "<td style='padding: 10px;' align='left'>".$text."</td>";
                        } else {
                            echo "<td style='padding: 10px;' align='left'>".$einstellung."</td>";
                        }
                    
                        if ($typ_co == "radio") {	
                            if ($wert == 1) {
                                echo "<td style='padding: 10px;' align='left'><input type='checkbox' id='".$id."' name='status[".$id."]' value='1' checked></td>";
                            } else {
                                echo "<td style='padding: 10px;' align='left'><input type='checkbox' id='".$id."' name='status[".$id."]' value='1'></td>";
                            }
                        }

                        if ($typ_co == "number") {	
                            echo "<td style='padding: 10px;' align='left'><input style='width: 4em;' id='status[".$id."]' name='status[".$id."]' type='number' value='".$wert."'></td>";
                        }

                        if ($typ_co == "url") {	
                            echo "<td style='padding: 10px;' align='left'><input style='width: 27em;' placeholder='https://' id='status[".$id."]' name='status[".$id."]' pattern='.*\/'' type='url' value='".$wert."'>
                            <br><small><i>Mit abschließendem \"/\" !</i></small></td>";
                        }

                        if ($typ_co == "textfeld") {	
                            echo "<td style='padding: 10px;' align='left'><input style='width: 27em;' id='status[".$id."]' name='status[".$id."]' type='text' value='".$wert."'>
                            </td>";
                        }
                            
                        
                        
			$server_alt = $server;
                echo "</tr>";	

			} //Ende foreach
		
		
		
		
		
	
		
		
		
		
		
		
	echo "</form>";
	
	
	
	
echo "</table>";

echo "<p>&nbsp;</p>";

?>
<tr>
<td colspan='1' style='vertical-align: middel;'>
<form method='post' action='./index.php'>
<input class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Menü' />
</form></td>
</tr></table>
<?php


/*
echo "<pre>";
print_r ( $sum );
echo "</pre>";
*/



} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=liste");

}

include("./fuss.php");

?>
