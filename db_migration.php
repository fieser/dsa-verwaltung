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

            // Spalten hinzufügen
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'prio', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'papierkorb', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'pap_user', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'pap_time', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'papierkorb', 'VARCHAR(200)', $logFile);

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
                                                        `einstellung` = 'bs_aktiv',
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