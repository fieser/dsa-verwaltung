        $betrieb
        $betrieb2
        $betrieb_plz
        $betrieb_ort
        $betrieb_strasse
        $betrieb_hausnummer
        $betrieb_telefon
        $betrieb_mail
        $ausbilder_nachname
        $ausbilder_vorname
        $ausbilder_telefon
        $ausbilder_telefon2
        $ausbilder_mail

            // Tabelle mail erstellen
            if ($db->query("CREATE TABLE IF NOT EXISTS betriebe (
                id INT AUTO_INCREMENT PRIMARY KEY,
                betrieb VARCHAR(200),
betrieb_plz VARCHAR(200),
betrieb_ort VARCHAR(200),
betrieb_strasse VARCHAR(200),
betrieb_hausnummer VARCHAR(200),
betrieb_telefon VARCHAR(200),
betrieb_mail VARCHAR(200),
betrieb VARCHAR(200),
betrieb VARCHAR(200),
betrieb VARCHAR(200),
                betrieb2 VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>betrieb</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'betrieb' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }


// Tabelle mail erstellen
            if ($db->query("CREATE TABLE IF NOT EXISTS ausbilder (
                id INT AUTO_INCREMENT PRIMARY KEY,
                betrieb VARCHAR(200),
ausbilder_vorname VARCHAR(200),
ausbilder_nachname VARCHAR(200),
ausbilder_mail VARCHAR(200),
ausbilder_telefon VARCHAR(200),
ausbilder_telefon2 VARCHAR(200),
ausbilder_betrieb_id VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>betrieb</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'betrieb' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }