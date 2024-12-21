<?php

// Aktuellen Git-Tag oder Commit abrufen
$gitTag = trim(shell_exec('git describe --tags 2>/dev/null')) ?: trim(shell_exec('git rev-parse --short HEAD'));

if (!$gitTag) {
    die("<p>Git-Version konnte nicht ermittelt werden. Stellen Sie sicher, dass 'git' verfügbar ist.</p>");
}

// Version prüfen und in die Datenbank eintragen
$checkVersion = $db->prepare("SELECT COUNT(*) FROM migration_versions WHERE version = ?");
$checkVersion->execute([$gitTag]);

if ($checkVersion->fetchColumn() == 0) {
    try {
        $db->beginTransaction();
        
        // Datenbankmigrationen hier durchführen
        addColumnIfNotExists($db, 'anmeldung_www', 'dsa_bewerberdaten', 'papierkorb', 'VARCHAR(11)', $logFile);



        // Version speichern
        $insertVersion = $db->prepare("INSERT INTO migration_versions (version) VALUES (?)");
        $insertVersion->execute([$gitTag]);

        $db->commit();
        echo "<p>Migration und Version $gitTag erfolgreich abgeschlossen.</p>";
    } catch (PDOException $e) {
        $db->rollBack();
        die("<p>Fehler bei der Migration: " . $e->getMessage() . "</p>");
    }
} else {
    echo "<p>Version $gitTag ist bereits in der Datenbank eingetragen. Keine Migration erforderlich.</p>";
}
?>