<?php


@session_start();
include("./config.php");
/*
try {
    //$db_www = new PDO('mysql:host=localhost;dbname=deine_datenbank', 'benutzername', 'passwort');

    // Überprüfen, ob der Index bereits existiert
    $query = "SELECT COUNT(*) 
              FROM information_schema.STATISTICS 
              WHERE table_schema = 'anmeldung_www_2526' 
              AND table_name = 'edoo_schueler' 
              AND index_name = 'idx_fulltext_vorname_nachname'";
    $stmt = $db_www->query($query);
    $indexExists = $stmt->fetchColumn();

    if ($indexExists == 0) {
        // Erstelle den Index nur, wenn er noch nicht existiert
        $db_www->exec("CREATE FULLTEXT INDEX idx_fulltext_vorname_nachname ON edoo_schueler (vorname, nachname)");
        echo "<p>Suchindex für Vor- und Nachnamen angelegt.</p>";
    } else {
        echo "<p>Suchindex existiert bereits.</p>";
    }

} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage();
}
*/
try {
$query = "SELECT COUNT(*) 
          FROM information_schema.STATISTICS 
          WHERE table_schema = 'anmeldung_www_2526' 
            AND table_name = 'edoo_schueler' 
            AND index_name = 'idx_fulltext_vorname_nachname'";

$stmt = $db_www->query($query);
$indexExists = $stmt->fetchColumn();

    if ($indexExists == 0) {
        $db_www->exec("CREATE FULLTEXT INDEX idx_fulltext_vorname_nachname ON edoo_schueler (vorname, nachname)");
        echo "<p>Suchindex für Vor- und Nachnamen angelegt.</p>";
        } else {
        echo "<p>Suchindex existiert bereits.</p>";
    }
} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage();
}
?>