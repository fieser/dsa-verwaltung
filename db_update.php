<?php
include("./config.php");



include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();



$host = 'localhost';
$dbname_temp = 'anmeldung_temp_leer';
$dbname_www = 'anmeldung_www_leer';
$user = 'root';
$password = $_SESSION['mig_password'];



try {
    // Verbindung zur MySQL-Datenbank herstellen
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Überprüfen, ob die Datenbank existiert
    $databaseName = $dbname_temp;
    $stmt = $pdo->query("SHOW DATABASES LIKE '$databaseName'");

    if ($stmt->rowCount() > 0) {
        echo "Die Datenbank '$databaseName' existiert bereits.\n <br>";
    } else {
        echo "Die Datenbank '$databaseName' existiert nicht.\n <br>";

            // Initiale Struktur importieren
    $sql = file_get_contents($pfad_workdir.'db/migrations/db_structure_verwaltung_temp.sql');
    $pdo->exec($sql);
    echo "Initiale Datenbankstruktur erfolgreich importiert.\n <br>";

    // Migrationen anwenden
    $migrationsDir = $pfad_workdir.'db/migrations/';
    $appliedMigrations = []; // Migrations-ID hier speichern (z. B. aus einer Tabelle)

    foreach (glob("$migrationsDir*.sql") as $migrationFile) {
        $migrationId = basename($migrationFile);
        if (!in_array($migrationId, $appliedMigrations)) {
            $migrationSql = file_get_contents($migrationFile);
            $pdo->exec($migrationSql);
            echo "Migration $migrationId erfolgreich angewendet.\n <br>";

            // Migration als angewendet markieren
            $appliedMigrations[] = $migrationId; 
            // Optional: In einer DB-Tabelle speichern
        }
    }

    echo "Alle Migrationen erfolgreich angewendet.\n <br>";
    }
} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage() . "\n";
}

echo "<br>";

try {
    // Verbindung zur MySQL-Datenbank herstellen
    //$pdo = new PDO("mysql:host=$host;charset=utf8", $user, $password);
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Überprüfen, ob die Datenbank existiert
    $databaseName2 = $dbname_www;
    $stmt = $pdo->query("SHOW DATABASES LIKE '$databaseName2'");

    if ($stmt->rowCount() > 0) {
        echo "Die Datenbank '$databaseName2' existiert bereits.\n <br>";
    } else {
        echo "Die Datenbank '$databaseName2' existiert nicht.\n <br>";

            // Initiale Struktur importieren
    $sql = file_get_contents($pfad_workdir.'db/migrations/db_structure_verwaltung_www.sql');
    $pdo->exec($sql);
    echo "Initiale Datenbankstruktur erfolgreich importiert.\n <br>";

    // Migrationen anwenden
    $migrationsDir = $pfad_workdir.'db/migrations/';
    $appliedMigrations = []; // Migrations-ID hier speichern (z. B. aus einer Tabelle)

    foreach (glob("$migrationsDir*.sql") as $migrationFile) {
        $migrationId = basename($migrationFile);
        if (!in_array($migrationId, $appliedMigrations)) {
            $migrationSql = file_get_contents($migrationFile);
            $pdo->exec($migrationSql);
            echo "Migration $migrationId erfolgreich angewendet.\n <br>";

            // Migration als angewendet markieren
            $appliedMigrations[] = $migrationId; 
            // Optional: In einer DB-Tabelle speichern
        }
    }

    echo "Alle Migrationen erfolgreich angewendet.\n <br>";
    }
} catch (PDOException $e) {
    echo "Fehler: " . $e->getMessage() . "\n";
}




?>

<p>
<form method="post" action="./setup.php">
<input type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
</form></p>
<?php

	include($pfad_workdir."fuss.php");
?>