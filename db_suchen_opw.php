<?php


@session_start();


    // Abfrage, um alle Datenbanken aufzulisten


    $statement = $db->query('SHOW DATABASES');
	$treffer_datenbanken = $statement->rowCount();

    // Überprüfen, ob Datenbanken gefunden wurden
    if ($treffer_datenbanken > 0) {
        echo "<p style='margin: 2em 0 2em 0;'>Vorhandene relevante Datenbanken:</p><ul>";
        // Ausgeben der Datenbanken

        $treffer_temp = 0;
        $treffer_www = 0;

        echo "<table>";
       foreach($statement as $row) {

            if (strpos($row['Database'],"nmeldung_www_2") != false OR strpos($row['Database'],"nmeldung_www_3") != false OR strpos($row['Database'],"nmeldung_www_2") != false) {
                echo "<tr>";
                echo "<td>";
                echo $row['Database'] . " </td><td style='padding-right: 2em;'> <a href='./db_status.php?db=".$row['Database']."' class='btn btn-default btn-sm' style='width: 10em; display: inline-block; margin: 0 0 4em 2em;'>Tabellen prüfen</a>";
                echo "</td>";
                echo "</tr>";
                $treffer_www = ($treffer_www + 1);
            }
            
            if ($row['Database'] == "anmeldung_temp") {
                echo "<tr>";
                echo "<td>";
                echo $row['Database'] . " </td><td> <a href='./db_status.php?db=anmeldung_temp' class='btn btn-default btn-sm' style='width: 10em; display: inline-block; margin: 0 0 4em 2em;'>Tabellen prüfen</a>";
                echo "</td>";
                echo "</tr>";
                $treffer_temp = ($treffer_temp + 1);
            }


        }

        echo "</table>";
    } else {
        echo "Keine Datenbanken gefunden.";
    }





?>