<?php





    // Abfrage, um alle Datenbanken aufzulisten


    $statement = $db->query('SHOW DATABASES');
	$treffer_datenbanken = $statement->rowCount();

    // Überprüfen, ob Datenbanken gefunden wurden
    if ($treffer_datenbanken > 0) {
        echo "<p style='margin-top: 2em;'>Vorhandene relevante Datenbanken:</p><ul>";
        // Ausgeben der Datenbanken

        $treffer_temp = 0;
        $treffer_www = 0;

       foreach($statement as $row) {

            if (strpos($row['Database'],"nmeldung_www_2") != false OR strpos($row['Database'],"nmeldung_www_3") != false OR strpos($row['Database'],"nmeldung_www_2") != false) {
                echo "<li>" . $row['Database'] . " <a href='./db_status.php?db=".$row['Database']."' class='btn btn-default btn-sm' style='width: 20em; display: inline-block; margin: 0 0 4em 0;'>Tabellen prüfen</a></li>";
                $treffer_www = ($treffer_www + 1);
            }
            
            if ($row['Database'] == "anmeldung_temp") {
                echo "<li>" . $row['Database'] . " <a href='./db_status.php?db=anmeldung_temp' class='btn btn-default btn-sm' style='width: 20em; display: inline-block; margin: 0 0 4em 0;'>Tabellen prüfen</a></li>";
                $treffer_temp = ($treffer_temp + 1);
            }


        }
        echo "</ul>";
    } else {
        echo "Keine Datenbanken gefunden.";
    }





?>