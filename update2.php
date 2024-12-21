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

            // Spalten hinzufügen
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'prio', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'papierkorb', 'VARCHAR(11)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'pap_user', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'pap_time', 'VARCHAR(200)', $logFile);
            addColumnIfNotExists($db_temp, 'anmeldung_temp', 'summen', 'papierkorb', 'VARCHAR(11)', $logFile);

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

include("./fuss.php");

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
