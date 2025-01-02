<?php

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');

include("./login_inc.php");
@session_start();

// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {

    include "./rechte.php";
    include "./config.php";

    // Logdatei definieren
    $logFile = 'migration.log';
    file_put_contents($logFile, "\n" . date('Y-m-d H:i:s') . " - Datenbankmigration gestartet.\n", FILE_APPEND);

    // Aktuellen Git-Tag oder Commit abrufen
    $gitTag = trim(shell_exec('git describe --tags 2>/dev/null')) ?: trim(shell_exec('git rev-parse --short HEAD'));

    if (!$gitTag) {
        die("<p>Git-Version konnte nicht ermittelt werden. Stellen Sie sicher, dass 'git' verfügbar ist.</p>");
    }

    try {
        // Transaktionen starten
        if ($db) {
            $db->beginTransaction();
            file_put_contents($logFile, "Transaktion für \$db gestartet.\n", FILE_APPEND);
        }

        if ($db_temp) {
            $db_temp->beginTransaction();
            file_put_contents($logFile, "Transaktion für \$db_temp gestartet.\n", FILE_APPEND);
        }

        // Tabelle für Migrationen erstellen
        if ($db->query("CREATE TABLE IF NOT EXISTS migration_versions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            version VARCHAR(50) NOT NULL,
            migration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = InnoDB")) {
            echo "<p>Tabelle <b>migration_versions</b> angelegt oder bereits vorhanden.</p>";
            file_put_contents($logFile, "Tabelle 'migration_versions' angelegt oder bereits vorhanden.\n", FILE_APPEND);
        }

        // Prüfen, ob die aktuelle Version bereits eingetragen ist
        $checkVersion = $db->prepare("SELECT COUNT(*) FROM migration_versions WHERE version = ?");
        $checkVersion->execute([$gitTag]);

        if ($checkVersion->fetchColumn() == 0) {
            // Datenbankmigrationen durchführen

            // Tabelle mail erstellen
            if ($db->query("CREATE TABLE IF NOT EXISTS mail (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_dsa_bewerberdaten INT(11),
                mailtext LONGTEXT,
                log LONGTEXT,
                last_user VARCHAR(200),
                last_time VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>mail</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'mail' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }

            // Tabelle ausbilder erstellen
            if ($db_temp->query("CREATE TABLE IF NOT EXISTS ausbilder (
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

            // Tabelle config erstellen
            if ($db_temp->query("CREATE TABLE IF NOT EXISTS config (
                id INT AUTO_INCREMENT PRIMARY KEY,
                wert VARCHAR(200),
                einstellung VARCHAR(200),
                bereich VARCHAR(200),
                server VARCHAR(200),
                text VARCHAR(300),
                typ VARCHAR(200)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>config</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'config' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }
			
			// Tabelle config erstellen
            if ($db_temp->query("CREATE TABLE IF NOT EXISTS anfrage (
                id INT AUTO_INCREMENT PRIMARY KEY,
                mail_anfrage VARCHAR(200),
                zufall VARCHAR(200),
                id_betrieb VARCHAR(200),
                verifiziert VARCHAR(100)
            ) ENGINE = InnoDB")) {
                echo "<p>Tabelle <b>anfrage</b> angelegt oder bereits vorhanden.</p>";
                file_put_contents($logFile, "Tabelle 'anfrage' angelegt oder bereits vorhanden.\n", FILE_APPEND);
            }

            // Spalten hinzufügen
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'prio', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www_2526', 'dsa_bewerberdaten', 'papierkorb', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www_2526', 'dsa_bewerberdaten', 'pap_user', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www_2526', 'dsa_bewerberdaten', 'pap_time', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'papierkorb', 'VARCHAR(200)', $logFile);

            //Neue Spalten für Tabelle betriebe
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_plz', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_ort', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_strasse', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_hausnummer', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_telefon', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'betriebe', 'betrieb_mail', 'VARCHAR(200)', $logFile);
    

            //Datensätze hinzufügen (z.B. in Tabelle config)
            // Nur wenn Datensatz noch nicht existiert:
            
            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'xls_download')");
            $treffer = $select_conf->rowCount();
                if ($treffer == 0) {

                    // Datensatz in DB schreiben:
                    if ($db_temp->exec("INSERT INTO `config`
                                SET
                                    `einstellung` = 'xls_download',
                                    `bereich` = 'Export',
                                    `text` = 'Transfer direkt per xls statt csv',
                                    `typ` = 'radio',
                                    `wert` = '1',
                                    `server` = 'v'")) {
                    
                    $last_id = $db->lastInsertId();
                    
                    echo "Datensatz ergänzt!<br>";
                    $u_neu = ($u_neu + 1);
                    }					
                } //Ende - wenn noch nicht vorhanden

                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'fremdsprachen_export_deaktivieren')");
                $treffer = $select_conf->rowCount();
                    if ($treffer == 0) {
    
                        // Datensatz in DB schreiben:
                        if ($db_temp->exec("INSERT INTO `config`
                                    SET
                                        `einstellung` = 'fremdsprachen_export_deaktivieren',
                                        `bereich` = 'Export',
                                        `text` = 'Export Fremdsprachen deaktiviert',
                                        `typ` = 'radio',
                                        `wert` = '0',
                                        `server` = 'v'")) {
                        
                        $last_id = $db->lastInsertId();
                        
                        echo "Datensatz ergänzt!<br>";
                        $u_neu = ($u_neu + 1);
                        }					
                    } //Ende - wenn noch nicht vorhanden
                
                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'upload_documents')");
                $treffer = $select_conf->rowCount();
                    if ($treffer == 0) {
    
                        // Datensatz in DB schreiben:
                        if ($db_temp->exec("INSERT INTO `config`
                                    SET
                                        `einstellung` = 'upload_documents',
                                        `bereich` = 'Dokumente',
                                        `text` = 'Upload von Dokumenten aktiv',
                                        `typ` = 'radio',
                                        `wert` = '0',
                                        `server` = 'f'")) {
                        
                        $last_id = $db->lastInsertId();
                        
                        echo "Datensatz ergänzt!<br>";
                        $u_neu = ($u_neu + 1);
                        }					
                    } //Ende - wenn noch nicht vorhanden

                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'popup_anzeigen_sj')");
                    $treffer = $select_conf->rowCount();
                        if ($treffer == 0) {
        
                            // Datensatz in DB schreiben:
                            if ($db_temp->exec("INSERT INTO `config`
                                        SET
                                            `einstellung` = 'popup_anzeigen_sj',
                                            `bereich` = 'Dokumente',
                                            `text` = 'Hinweis zum Schuljahr anzeigen',
                                            `typ` = 'radio',
                                            `wert` = '1',
                                            `server` = 'f'")) {
                            
                            $last_id = $db->lastInsertId();
                            
                            echo "Datensatz ergänzt!<br>";
                            $u_neu = ($u_neu + 1);
                            }					
                        } //Ende - wenn noch nicht vorhanden

                    //Schulformen aktivieren/deaktivieren
                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bs_aktiv')");
                    $treffer = $select_conf->rowCount();
                        if ($treffer == 0) {
        
                            // Datensatz in DB schreiben:
                            if ($db_temp->exec("INSERT INTO `config`
                                        SET
                                            `einstellung` = 'bs_aktiv',
                                            `bereich` = 'Schulformen',
                                            `text` = 'Berufsschule (BS)',
                                            `typ` = 'radio',
                                            `wert` = '1',
                                            `server` = 'f'")) {
                            
                            $last_id = $db->lastInsertId();
                            
                            echo "Datensatz ergänzt!<br>";
                            $u_neu = ($u_neu + 1);
                            }					
                        } //Ende - wenn noch nicht vorhanden
                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bs_aktiv')");
                        $treffer = $select_conf->rowCount();
                            if ($treffer == 0) {
            
                                // Datensatz in DB schreiben:
                                if ($db_temp->exec("INSERT INTO `config`
                                            SET
                                                `einstellung` = 'bs_aktiv',
                                                `bereich` = 'Schulformen',
                                                `text` = 'Berufsschule (BS)',
                                                `typ` = 'radio',
                                                `wert` = '1',
                                                `server` = 'f'")) {
                                
                                $last_id = $db->lastInsertId();
                                
                                echo "Datensatz ergänzt!<br>";
                                $u_neu = ($u_neu + 1);
                                }					
                            } //Ende - wenn noch nicht vorhanden
                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bvj_aktiv')");
                            $treffer = $select_conf->rowCount();
                                if ($treffer == 0) {
                
                                    // Datensatz in DB schreiben:
                                    if ($db_temp->exec("INSERT INTO `config`
                                                SET
                                                    `einstellung` = 'bvj_aktiv',
                                                    `bereich` = 'Schulformen',
                                                    `text` = 'Berufsvorbereitungsjahr (BVJ)',
                                                    `typ` = 'radio',
                                                    `wert` = '1',
                                                    `server` = 'f'")) {
                                    
                                    $last_id = $db->lastInsertId();
                                    
                                    echo "Datensatz ergänzt!<br>";
                                    $u_neu = ($u_neu + 1);
                                    }					
                                } //Ende - wenn noch nicht vorhanden
                                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'aph_aktiv')");
                                $treffer = $select_conf->rowCount();
                                    if ($treffer == 0) {
                    
                                        // Datensatz in DB schreiben:
                                        if ($db_temp->exec("INSERT INTO `config`
                                                    SET
                                                        `einstellung` = 'aph_aktiv',
                                                        `bereich` = 'Schulformen',
                                                        `text` = 'FS Altenpflegehilfe (FS APH)',
                                                        `typ` = 'radio',
                                                        `wert` = '1',
                                                        `server` = 'f'")) {
                                        
                                        $last_id = $db->lastInsertId();
                                        
                                        echo "Datensatz ergänzt!<br>";
                                        $u_neu = ($u_neu + 1);
                                        }					
                                    } //Ende - wenn noch nicht vorhanden
                                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bf1_aktiv')");
                                    $treffer = $select_conf->rowCount();
                                        if ($treffer == 0) {
                        
                                            // Datensatz in DB schreiben:
                                            if ($db_temp->exec("INSERT INTO `config`
                                                        SET
                                                            `einstellung` = 'bf1_aktiv',
                                                            `bereich` = 'Schulformen',
                                                            `text` = 'Berufsfachschule 1 (BF 1)',
                                                            `typ` = 'radio',
                                                            `wert` = '1',
                                                            `server` = 'f'")) {
                                            
                                            $last_id = $db->lastInsertId();
                                            
                                            echo "Datensatz ergänzt!<br>";
                                            $u_neu = ($u_neu + 1);
                                            }					
                                        } //Ende - wenn noch nicht vorhanden
                                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bf2_aktiv')");
                                        $treffer = $select_conf->rowCount();
                                            if ($treffer == 0) {
                            
                                                // Datensatz in DB schreiben:
                                                if ($db_temp->exec("INSERT INTO `config`
                                                            SET
                                                                `einstellung` = 'bf2_aktiv',
                                                                `bereich` = 'Schulformen',
                                                                `text` = 'Berufsfachschule 2 (BF 2)',
                                                                `typ` = 'radio',
                                                                `wert` = '1',
                                                                `server` = 'f'")) {
                                                
                                                $last_id = $db->lastInsertId();
                                                
                                                echo "Datensatz ergänzt!<br>";
                                                $u_neu = ($u_neu + 1);
                                                }					
                                            } //Ende - wenn noch nicht vorhanden
                                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bfp_aktiv')");
                                            $treffer = $select_conf->rowCount();
                                                if ($treffer == 0) {
                                
                                                    // Datensatz in DB schreiben:
                                                    if ($db_temp->exec("INSERT INTO `config`
                                                                SET
                                                                    `einstellung` = 'bfp_aktiv',
                                                                    `bereich` = 'Schulformen',
                                                                    `text` = 'Berufsfachschule Pflege (BFP)',
                                                                    `typ` = 'radio',
                                                                    `wert` = '1',
                                                                    `server` = 'f'")) {
                                                    
                                                    $last_id = $db->lastInsertId();
                                                    
                                                    echo "Datensatz ergänzt!<br>";
                                                    $u_neu = ($u_neu + 1);
                                                    }					
                                                } //Ende - wenn noch nicht vorhanden

                                                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bos1_aktiv')");
                                                $treffer = $select_conf->rowCount();
                                                    if ($treffer == 0) {
                                    
                                                        // Datensatz in DB schreiben:
                                                        if ($db_temp->exec("INSERT INTO `config`
                                                                    SET
                                                                        `einstellung` = 'bos1_aktiv',
                                                                        `bereich` = 'Schulformen',
                                                                        `text` = 'Berufsoberschule 1 (BOS 1)',
                                                                        `typ` = 'radio',
                                                                        `wert` = '1',
                                                                        `server` = 'f'")) {
                                                        
                                                        $last_id = $db->lastInsertId();
                                                        
                                                        echo "Datensatz ergänzt!<br>";
                                                        $u_neu = ($u_neu + 1);
                                                        }					
                                                    } //Ende - wenn noch nicht vorhanden

                                                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bos2_aktiv')");
                                                    $treffer = $select_conf->rowCount();
                                                        if ($treffer == 0) {
                                        
                                                            // Datensatz in DB schreiben:
                                                            if ($db_temp->exec("INSERT INTO `config`
                                                                        SET
                                                                            `einstellung` = 'bos2_aktiv',
                                                                            `bereich` = 'Schulformen',
                                                                            `text` = 'Berufsoberschule (BOS 2)',
                                                                            `typ` = 'radio',
                                                                            `wert` = '1',
                                                                            `server` = 'f'")) {
                                                            
                                                            $last_id = $db->lastInsertId();
                                                            
                                                            echo "Datensatz ergänzt!<br>";
                                                            $u_neu = ($u_neu + 1);
                                                            }					
                                                        } //Ende - wenn noch nicht vorhanden

                                                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'dbos_aktiv')");
                                                        $treffer = $select_conf->rowCount();
                                                            if ($treffer == 0) {
                                            
                                                                // Datensatz in DB schreiben:
                                                                if ($db_temp->exec("INSERT INTO `config`
                                                                            SET
                                                                                `einstellung` = 'dbos_aktiv',
                                                                                `bereich` = 'Schulformen',
                                                                                `text` = 'Duale Berufsoberschule (DBOS)',
                                                                                `typ` = 'radio',
                                                                                `wert` = '1',
                                                                                `server` = 'f'")) {
                                                                
                                                                $last_id = $db->lastInsertId();
                                                                
                                                                echo "Datensatz ergänzt!<br>";
                                                                $u_neu = ($u_neu + 1);
                                                                }					
                                                            } //Ende - wenn noch nicht vorhanden

                                                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'bgy_aktiv')");
                                                            $treffer = $select_conf->rowCount();
                                                                if ($treffer == 0) {
                                                
                                                                    // Datensatz in DB schreiben:
                                                                    if ($db_temp->exec("INSERT INTO `config`
                                                                                SET
                                                                                    `einstellung` = 'bgy_aktiv',
                                                                                    `bereich` = 'Schulformen',
                                                                                    `text` = 'Berufliches Gymnasium (BGY)',
                                                                                    `typ` = 'radio',
                                                                                    `wert` = '1',
                                                                                    `server` = 'f'")) {
                                                                    
                                                                    $last_id = $db->lastInsertId();
                                                                    
                                                                    echo "Datensatz ergänzt!<br>";
                                                                    $u_neu = ($u_neu + 1);
                                                                    }					
                                                                } //Ende - wenn noch nicht vorhanden

                                                                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'fs_aktiv')");
                                                                $treffer = $select_conf->rowCount();
                                                                    if ($treffer == 0) {
                                                    
                                                                        // Datensatz in DB schreiben:
                                                                        if ($db_temp->exec("INSERT INTO `config`
                                                                                    SET
                                                                                        `einstellung` = 'fs_aktiv',
                                                                                        `bereich` = 'Schulformen',
                                                                                        `text` = 'Fachschule (FS)',
                                                                                        `typ` = 'radio',
                                                                                        `wert` = '1',
                                                                                        `server` = 'f'")) {
                                                                        
                                                                        $last_id = $db->lastInsertId();
                                                                        
                                                                        echo "Datensatz ergänzt!<br>";
                                                                        $u_neu = ($u_neu + 1);
                                                                        }					
                                                                    } //Ende - wenn noch nicht vorhanden

                                                                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'fsof_aktiv')");
                                                                        $treffer = $select_conf->rowCount();
                                                                            if ($treffer == 0) {
                                                            
                                                                                // Datensatz in DB schreiben:
                                                                                if ($db_temp->exec("INSERT INTO `config`
                                                                                            SET
                                                                                                `einstellung` = 'fsof_aktiv',
                                                                                                `bereich` = 'Schulformen',
                                                                                                `text` = 'FS Organisation und Führung (FSOF)',
                                                                                                `typ` = 'radio',
                                                                                                `wert` = '1',
                                                                                                `server` = 'f'")) {
                                                                                
                                                                                $last_id = $db->lastInsertId();
                                                                                
                                                                                echo "Datensatz ergänzt!<br>";
                                                                                $u_neu = ($u_neu + 1);
                                                                                }					
                                                                            } //Ende - wenn noch nicht vorhanden

                                                                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'hbf_aktiv')");
                                                                            $treffer = $select_conf->rowCount();
                                                                                if ($treffer == 0) {
                                                                
                                                                                    // Datensatz in DB schreiben:
                                                                                    if ($db_temp->exec("INSERT INTO `config`
                                                                                                SET
                                                                                                    `einstellung` = 'hbf_aktiv',
                                                                                                    `bereich` = 'Schulformen',
                                                                                                    `text` = 'Höhere Berufsfachschule (HBF)',
                                                                                                    `typ` = 'radio',
                                                                                                    `wert` = '1',
                                                                                                    `server` = 'f'")) {
                                                                                    
                                                                                    $last_id = $db->lastInsertId();
                                                                                    
                                                                                    echo "Datensatz ergänzt!<br>";
                                                                                    $u_neu = ($u_neu + 1);
                                                                                    }					
                                                                                } //Ende - wenn noch nicht vorhanden
        
$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'umfrage')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'umfrage',
                                `bereich` = 'Formular',
                                `text` = 'Umfrage Wirksamkeit Werbung',
                                `typ` = 'radio',
                                `wert` = '0',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden
			
$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'login_betriebe')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'login_betriebe',
                                `bereich` = 'Formular',
                                `text` = 'Login für Einsicht Betriebsdaten aktiv',
                                `typ` = 'radio',
                                `wert` = '0',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden
			
$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'mail_betriebe')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'mail_betriebe',
                                `bereich` = 'Formular',
                                `text` = 'Email für Änderung Betriebsdaten aktiv',
                                `typ` = 'radio',
                                `wert` = '1',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden
			
$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'wartungsmodus')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'wartungsmodus',
                                `bereich` = 'Formular',
                                `text` = 'Wartungsmodus aktiv',
                                `typ` = 'radio',
                                `wert` = '0',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden
			
$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'wartungsmodus_ende')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'wartungsmodus_ende',
                                `bereich` = 'Formular',
                                `text` = 'Wartungsmodus bis einschließlich',
                                `typ` = 'datum',
                                `wert` = '',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden

$select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'wartungsmodus_ausnahme')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'wartungsmodus_ausnahme',
                                `bereich` = 'Formular',
                                `text` = 'Wartungsmodus Ausnahme (IP-Adresse)',
                                `typ` = 'textfeld',
                                `wert` = '',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden

        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'min_anzahl_betriebe')");
        $treffer = $select_conf->rowCount();
            if ($treffer == 0) {

                // Datensatz in DB schreiben:
                if ($db_temp->exec("INSERT INTO `config`
                            SET
                                `einstellung` = 'min_anzahl_betriebe',
                                `bereich` = 'Formular',
                                `text` = 'Mindestanzahl Betriebe im Auswahlmenü',
                                `typ` = 'number',
                                `wert` = '0',
                                `server` = 'f'")) {
                
                $last_id = $db->lastInsertId();
                
                echo "Datensatz ergänzt!<br>";
                $u_neu = ($u_neu + 1);
                }					
            } //Ende - wenn noch nicht vorhanden

            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'url_formular')");
            $treffer = $select_conf->rowCount();
                if ($treffer == 0) {
    
                    // Datensatz in DB schreiben:
                    if ($db_temp->exec("INSERT INTO `config`
                                SET
                                    `einstellung` = 'url_formular',
                                    `bereich` = 'Formular',
                                    `text` = 'URL Formularseite',
                                    `typ` = 'url',
                                    `wert` = 'https://anmeldung.bbs1-mainz.de/',
                                    `server` = 'f'")) {
                    
                    $last_id = $db->lastInsertId();
                    
                    echo "Datensatz ergänzt!<br>";
                    $u_neu = ($u_neu + 1);
                    }					
                } //Ende - wenn noch nicht vorhanden
    
                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'website')");
                $treffer = $select_conf->rowCount();
                    if ($treffer == 0) {
        
                        // Datensatz in DB schreiben:
                        if ($db_temp->exec("INSERT INTO `config`
                                    SET
                                        `einstellung` = 'website',
                                        `bereich` = 'Formular',
                                        `text` = 'URL Website Schule',
                                        `typ` = 'url',
                                        `wert` = 'https://www.bbs1-mainz.com',
                                        `server` = 'f'")) {
                        
                        $last_id = $db->lastInsertId();
                        
                        echo "Datensatz ergänzt!<br>";
                        $u_neu = ($u_neu + 1);
                        }					
                    } //Ende - wenn noch nicht vorhanden

                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'url_impressum')");
                    $treffer = $select_conf->rowCount();
                        if ($treffer == 0) {
            
                            // Datensatz in DB schreiben:
                            if ($db_temp->exec("INSERT INTO `config`
                                        SET
                                            `einstellung` = 'url_impressum',
                                            `bereich` = 'Formular',
                                            `text` = 'URL Impressum',
                                            `typ` = 'textfeld',
                                            `wert` = 'https://www.meine-scule.de/impressum.html',
                                            `server` = 'f'")) {
                            
                            $last_id = $db->lastInsertId();
                            
                            echo "Datensatz ergänzt!<br>";
                            $u_neu = ($u_neu + 1);
                            }					
                        } //Ende - wenn noch nicht vorhanden

                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_name1')");
                    $treffer = $select_conf->rowCount();
                        if ($treffer == 0) {
            
                            // Datensatz in DB schreiben:
                            if ($db_temp->exec("INSERT INTO `config`
                                        SET
                                            `einstellung` = 'schule_name1',
                                            `bereich` = 'Anschrift1',
                                            `text` = 'Schulname Zeile 1',
                                            `typ` = 'textfeld',
                                            `wert` = 'Berufsbildende Schule 1',
                                            `server` = 'f'")) {
                            
                            $last_id = $db->lastInsertId();
                            
                            echo "Datensatz ergänzt!<br>";
                            $u_neu = ($u_neu + 1);
                            }					
                        } //Ende - wenn noch nicht vorhanden
                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_name2')");
                        $treffer = $select_conf->rowCount();
                            if ($treffer == 0) {
                
                                // Datensatz in DB schreiben:
                                if ($db_temp->exec("INSERT INTO `config`
                                            SET
                                                `einstellung` = 'schule_name2',
                                                `bereich` = 'Anschrift2',
                                                `text` = 'Schulname Zeile 2',
                                                `typ` = 'textfeld',
                                                `wert` = '- Gewerbe und Technik -',
                                                `server` = 'f'")) {
                                
                                $last_id = $db->lastInsertId();
                                
                                echo "Datensatz ergänzt!<br>";
                                $u_neu = ($u_neu + 1);
                                }					
                            } //Ende - wenn noch nicht vorhanden                    

                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_strasse_nr')");
            $treffer = $select_conf->rowCount();
                if ($treffer == 0) {
    
                    // Datensatz in DB schreiben:
                    if ($db_temp->exec("INSERT INTO `config`
                                SET
                                    `einstellung` = 'schule_strasse_nr',
                                    `bereich` = 'Anschrift3',
                                    `text` = 'Straße und Hausnummer',
                                    `typ` = 'textfeld',
                                    `wert` = 'Am Judensand 12',
                                    `server` = 'f'")) {
                    
                    $last_id = $db->lastInsertId();
                    
                    echo "Datensatz ergänzt!<br>";
                    $u_neu = ($u_neu + 1);
                    }					
                } //Ende - wenn noch nicht vorhanden

                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_plz_ort')");
                $treffer = $select_conf->rowCount();
                    if ($treffer == 0) {
        
                        // Datensatz in DB schreiben:
                        if ($db_temp->exec("INSERT INTO `config`
                                    SET
                                        `einstellung` = 'schule_plz_ort',
                                        `bereich` = 'Anschrift4',
                                        `text` = 'PLZ und Ort',
                                        `typ` = 'textfeld',
                                        `wert` = '55122 Mainz',
                                        `server` = 'f'")) {
                        
                        $last_id = $db->lastInsertId();
                        
                        echo "Datensatz ergänzt!<br>";
                        $u_neu = ($u_neu + 1);
                        }					
                    } //Ende - wenn noch nicht vorhanden
    
                    $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_kurz')");
                    $treffer = $select_conf->rowCount();
                        if ($treffer == 0) {
            
                            // Datensatz in DB schreiben:
                            if ($db_temp->exec("INSERT INTO `config`
                                        SET
                                            `einstellung` = 'schule_kurz',
                                            `bereich` = 'Anschrift8',
                                            `text` = 'Schulkürzel',
                                            `typ` = 'textfeld',
                                            `wert` = 'bbs1',
                                            `server` = 'f'")) {
                            
                            $last_id = $db->lastInsertId();
                            
                            echo "Datensatz ergänzt!<br>";
                            $u_neu = ($u_neu + 1);
                            }					
                        } //Ende - wenn noch nicht vorhanden
            
                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_kurzname')");
                        $treffer = $select_conf->rowCount();
                            if ($treffer == 0) {
                
                                // Datensatz in DB schreiben:
                                if ($db_temp->exec("INSERT INTO `config`
                                            SET
                                                `einstellung` = 'schule_kurzname',
                                                `bereich` = 'Anschrift9',
                                                `text` = 'Schulname kurz',
                                                `typ` = 'textfeld',
                                                `wert` = 'BBS1-Mainz',
                                                `server` = 'f'")) {
                                
                                $last_id = $db->lastInsertId();
                                
                                echo "Datensatz ergänzt!<br>";
                                $u_neu = ($u_neu + 1);
                                }					
                            } //Ende - wenn noch nicht vorhanden
                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_mail')");
                            $treffer = $select_conf->rowCount();
                                if ($treffer == 0) {
                    
                                    // Datensatz in DB schreiben:
                                    if ($db_temp->exec("INSERT INTO `config`
                                                SET
                                                    `einstellung` = 'schule_mail',
                                                    `bereich` = 'Anschrift5',
                                                    `text` = 'E-Mail-Adresse Sekretariat',
                                                    `typ` = 'textfeld',
                                                    `wert` = 'sekretariat@bbs1-mainz.de',
                                                    `server` = 'f'")) {
                                    
                                    $last_id = $db->lastInsertId();
                                    
                                    echo "Datensatz ergänzt!<br>";
                                    $u_neu = ($u_neu + 1);
                                    }					
                                } //Ende - wenn noch nicht vorhanden


                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_tel')");
                            $treffer = $select_conf->rowCount();
                                if ($treffer == 0) {
                    
                                    // Datensatz in DB schreiben:
                                    if ($db_temp->exec("INSERT INTO `config`
                                                SET
                                                    `einstellung` = 'schule_tel',
                                                    `bereich` = 'Anschrift6',
                                                    `text` = 'Telefon',
                                                    `typ` = 'textfeld',
                                                    `wert` = '06131-90603-0',
                                                    `server` = 'f'")) {
                                    
                                    $last_id = $db->lastInsertId();
                                    
                                    echo "Datensatz ergänzt!<br>";
                                    $u_neu = ($u_neu + 1);
                                    }					
                                } //Ende - wenn noch nicht vorhanden

                                $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'schule_fax')");
                                $treffer = $select_conf->rowCount();
                                    if ($treffer == 0) {
                        
                                        // Datensatz in DB schreiben:
                                        if ($db_temp->exec("INSERT INTO `config`
                                                    SET
                                                        `einstellung` = 'schule_fax',
                                                        `bereich` = 'Anschrift7',
                                                        `text` = 'Fax',
                                                        `typ` = 'textfeld',
                                                        `wert` = '06131-90603-99',
                                                        `server` = 'f'")) {
                                        
                                        $last_id = $db->lastInsertId();
                                        
                                        echo "Datensatz ergänzt!<br>";
                                        $u_neu = ($u_neu + 1);
                                        }					
                                    } //Ende - wenn noch nicht vorhanden
                        $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'vorbelegen_sorge1')");
                        $treffer = $select_conf->rowCount();
                            if ($treffer == 0) {
                
                                // Datensatz in DB schreiben:
                                if ($db_temp->exec("INSERT INTO `config`
                                            SET
                                                `einstellung` = 'vorbelegen_sorge1',
                                                `bereich` = 'Formular',
                                                `text` = 'Vorbelegung Adresse Sorgeberechtigte',
                                                `typ` = 'radio',
                                                `wert` = '1',
                                                `server` = 'f'")) {
                                
                                $last_id = $db->lastInsertId();
                                
                                echo "Datensatz ergänzt!<br>";
                                $u_neu = ($u_neu + 1);
                                }					
                            } //Ende - wenn noch nicht vorhanden
                            $select_conf = $db_temp->query("SELECT * FROM config WHERE (einstellung = 'strasse_pruefen')");
                            $treffer = $select_conf->rowCount();
                                if ($treffer == 0) {
                    
                                    // Datensatz in DB schreiben:
                                    if ($db_temp->exec("INSERT INTO `config`
                                                SET
                                                    `einstellung` = 'strasse_pruefen',
                                                    `bereich` = 'Formular',
                                                    `text` = 'Überprüfung Straßenname',
                                                    `typ` = 'radio',
                                                    `wert` = '1',
                                                    `server` = 'f'")) {
                                    
                                    $last_id = $db->lastInsertId();
                                    
                                    echo "Datensatz ergänzt!<br>";
                                    $u_neu = ($u_neu + 1);
                                    }					
                                } //Ende - wenn noch nicht vorhanden
    

            // Aktuelle Version in die Datenbank eintragen
            $insertVersion = $db->prepare("INSERT INTO migration_versions (version) VALUES (?)");
            $insertVersion->execute([$gitTag]);

            if ($db) {
                $db->commit();
                file_put_contents($logFile, "Transaktion für \$db abgeschlossen.\n", FILE_APPEND);
            }

            if ($db_temp) {
                $db_temp->commit();
                file_put_contents($logFile, "Transaktion für \$db_temp abgeschlossen.\n", FILE_APPEND);
            }

            echo "<p>Migration und Version $gitTag erfolgreich abgeschlossen.</p>";
            file_put_contents($logFile, "Git-Version '$gitTag' in die Datenbank eingetragen.\n", FILE_APPEND);
        } else {
            echo "<p>Version $gitTag ist bereits in der Datenbank eingetragen. Keine Migration erforderlich.</p>";
        }

    } catch (PDOException $e) {
        // Rollback bei Fehler
        if ($db && $db->inTransaction()) {
            $db->rollBack();
            file_put_contents($logFile, "Rollback für \$db durchgeführt.\n", FILE_APPEND);
        }

        if ($db_temp && $db_temp->inTransaction()) {
            $db_temp->rollBack();
            file_put_contents($logFile, "Rollback für \$db_temp durchgeführt.\n", FILE_APPEND);
        }

        $errorMessage = "DB Fehler: " . $e->getMessage();
        file_put_contents($logFile, "Fehler: $errorMessage\n", FILE_APPEND);
        die($errorMessage);
    }

} else {
    echo "Bitte erst einloggen!";
    header("Location: ./login_ad.php?back=liste");
}



/**
 * Fügt eine Spalte hinzu, falls sie nicht existiert.
 *
 * @param PDO $db Die PDO-Instanz
 * @param string $schema Der Name des Schemas
 * @param string $tabelle Der Name der Tabelle
 * @param string $spalte Der Name der Spalte
 * @param string $definition Die Spaltendefinition
 * @param string $logFile Der Pfad zur Logdatei
 */
function addColumnIfNotExists($db, $schema, $tabelle, $spalte, $definition, $logFile)
{
    try {
        // Überprüfen, ob die Spalte existiert
        $checkColumnExists = $db->prepare("SHOW COLUMNS FROM `$tabelle` LIKE ?");
        $checkColumnExists->execute([$spalte]);

        if ($checkColumnExists->rowCount() == 0) {
            $stmt = $db->prepare("ALTER TABLE `$tabelle` ADD `$spalte` $definition");
            $stmt->execute();

            echo "<p>Spalte <b>$spalte</b> zur Tabelle <b>$tabelle</b> hinzugefügt.</p>";
            file_put_contents($logFile, "Spalte '$spalte' zur Tabelle '$tabelle' hinzugefügt.\n", FILE_APPEND);
        } else {
            echo "<p>Die Spalte <b>$spalte</b> existiert bereits in der Tabelle <b>$tabelle</b>.</p>";
            file_put_contents($logFile, "Spalte '$spalte' existiert bereits in der Tabelle '$tabelle'.\n", FILE_APPEND);
        }
    } catch (PDOException $e) {
        $sqlError = $e->getMessage();
        file_put_contents($logFile, "SQL Fehler bei Spalte '$spalte': $sqlError\n", FILE_APPEND);
        throw new PDOException("Fehler beim Überprüfen oder Hinzufügen der Spalte '$spalte' zur Tabelle '$tabelle': $sqlError");
    }
}

?>

<p>
<form name='form_x' method="post" action="./index.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
?>