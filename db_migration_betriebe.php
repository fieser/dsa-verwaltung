<?php


            // Tabelle betriebe erstellen
            if ($db->query("CREATE TABLE IF NOT EXISTS betriebe (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_edoo VARCHAR(200),
                kuerzel VARCHAR(200),
                name1 VARCHAR(200),
                name2 VARCHAR(200),
                betrieb_plz VARCHAR(200),
                betrieb_ort VARCHAR(200),
                betrieb_strasse VARCHAR(200),
                betrieb_hausnummer VARCHAR(200),
                betrieb_telefon VARCHAR(200),
                betrieb_mail VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>betrieb</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'betrieb' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }


// Tabelle ausbilder erstellen
            if ($db->query("CREATE TABLE IF NOT EXISTS ausbilder (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ausbilder_vorname VARCHAR(200),
                ausbilder_nachname VARCHAR(200),
                ausbilder_mail VARCHAR(200),
                ausbilder_telefon VARCHAR(200),
                ausbilder_telefon2 VARCHAR(200),
                ausbilder_betrieb_id VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>ausbilder</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'ausbilder' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }