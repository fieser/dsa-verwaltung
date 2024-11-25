<?php
// Fehleranzeige aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

@session_start();
include("./config.php");
include($pfad_workdir . "kopf.php");
include($pfad_workdir . "login_inc.php");




    
    $mig_password = $_SESSION['mig_password'];
    //$datenbankname = "anmeldung_www_2526";

    
    if (isset($_GET['db']) AND $_GET['db'] != "") {
        $datenbankname = $_GET['db'];

    }

    if (strpos($datenbankname, "temp") != false) {

        $tamplate_file = 'db/migrations/db_structure_verwaltung_temp.sql';
    } else {
        $tamplate_file = 'db/migrations/db_structure_verwaltung_www.sql';
    }
        


// Datenbankkonfiguration
$dbConfigs = [
    $datenbankname => [
        'host' => 'localhost',
        'user' => 'root',
        'datenbankname' => $datenbankname,
        'password' => $mig_password,
        'template' => $tamplate_file,
    ],
];

/**
 * MySQL-Verbindung herstellen.
 */
function connectToMySQLDatabase($dbName, $host, $user, $password) {
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    try {
        return new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        
        
        echo "<p>
        <form method='post' action='./setup.php'>
            <input style='margin-top: 3em;' type='submit' class='btn btn-default btn-sm' name='cmd[doStandardAuthentication]' value='zurück' />
        </form>
        </p>";
        die("MySQL-Verbindungsfehler: " . $e->getMessage());
        
    }
}

/**
 * SQL-Datei laden.
 */
function loadSqlFile($filePath) {
    if (!file_exists($filePath)) {
        die("SQL-Datei nicht gefunden: $filePath");
    }
    return file_get_contents($filePath);
}

/**
 * MySQL-spezifische Syntax für SQLite bereinigen.
 */
function cleanMySQLSqlForSQLite($sql) {
    $lines = explode("\n", $sql);
    $cleanedLines = [];
    foreach ($lines as $line) {
        $line = preg_replace('/ENGINE=\w+/', '', $line);
        $line = preg_replace('/AUTO_INCREMENT/', '', $line);
        $line = preg_replace('/CHARSET=\w+/', '', $line);
        $line = preg_replace('/COLLATE\s+\w+/', '', $line);
        $line = preg_replace('/\bunsigned\b/', '', $line);
        $line = preg_replace('/DEFAULT\s+[^,\s]+/', '', $line);
        $line = preg_replace('/CONSTRAINT\s+[`"\']?\w+[`"\']?\s+FOREIGN KEY\s+\([^)]+\)\s+REFERENCES\s+[^\(]+\([^)]+\)/', '', $line);
        $line = preg_replace('/\bTINYINT\b/', 'INTEGER', $line);
        $line = preg_replace('/\bMEDIUMTEXT\b/', 'TEXT', $line);
        $line = preg_replace('/\bDATETIME\b/', 'TEXT', $line);
        $line = preg_replace('/--.*$/', '', $line);
        $line = preg_replace('/\/\*.*?\*\//', '', $line);
        $line = preg_replace('/=\d+/', '', $line);
        $line = trim($line);
        if (!empty($line)) {
            $cleanedLines[] = $line;
        }
    }
    return implode("\n", $cleanedLines);
}

/**
 * SQLite-Datenbank erstellen.
 */
function createSQLiteDatabase($cleanSql) {
    $pdo = new PDO("sqlite::memory:");
    $lines = explode(";", $cleanSql);
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $result = $pdo->exec($line);
            if ($result === false) {
                $errorInfo = $pdo->errorInfo();
                die("SQLite-Fehler: " . $errorInfo[2]);
            }
        }
    }
    return $pdo;
}

/**
 * Tabellen- und Spaltenstruktur aus SQLite abrufen.
 */
function getSQLiteTableStructure($pdo) {
    $tables = [];
    $query = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $tableName = $row['name'];
        $columns = [];
        $columnQuery = $pdo->query("PRAGMA table_info(`$tableName`)");
        while ($column = $columnQuery->fetch(PDO::FETCH_ASSOC)) {
            $columns[$column['name']] = $column;
        }
        $tables[$tableName] = $columns;
    }
    return $tables;
}

/**
 * Tabellen- und Spaltenstruktur aus MySQL abrufen.
 */
function getMySQLTableStructure($pdo) {
    $tables = [];
    $query = $pdo->query("SHOW TABLES");
    while ($row = $query->fetch(PDO::FETCH_NUM)) {
        $tableName = $row[0];
        $columns = [];
        $columnQuery = $pdo->query("DESCRIBE `$tableName`");
        while ($column = $columnQuery->fetch(PDO::FETCH_ASSOC)) {
            $columns[$column['Field']] = $column;
        }
        $tables[$tableName] = $columns;
    }
    return $tables;
}

/**
 * Tabellen- und Spaltenunterschiede vergleichen.
 */
function compareStructures($mysqlStructure, $sqliteStructure) {
    $differences = [
        'missing_tables' => [],
        'missing_columns' => [],
    ];

    foreach ($sqliteStructure as $tableName => $columns) {
        if (!isset($mysqlStructure[$tableName])) {
            $differences['missing_tables'][$tableName] = $columns;
        } else {
            foreach ($columns as $columnName => $definition) {
                if (!isset($mysqlStructure[$tableName][$columnName])) {
                    $differences['missing_columns'][$tableName][$columnName] = $definition;
                }
            }
        }
    }

    return $differences;
}

/**
 * Fehlende Tabelle zu MySQL hinzufügen.
 */
function addTable($mysqlPdo, $tableName, $columns) {
    $columnsSql = [];
    foreach ($columns as $columnName => $definition) {
        $type = $definition['type'] ?? 'TEXT';
        $null = $definition['notnull'] ? 'NOT NULL' : 'NULL';
        $default = isset($definition['dflt_value']) ? "DEFAULT " . $definition['dflt_value'] : '';
        $columnsSql[] = "`$columnName` $type $null $default";
    }
    $sql = "CREATE TABLE `$tableName` (" . implode(", ", $columnsSql) . ")";
    $mysqlPdo->exec($sql);
    echo "<p>Tabelle <b>$tableName</b> wurde erfolgreich hinzugefügt.</p>";
}

/**
 * Fehlende Spalte zu MySQL hinzufügen.
 */
function addColumn($mysqlPdo, $tableName, $columnName, $definition) {
    $type = $definition['type'] ?? 'TEXT';
    $null = $definition['notnull'] ? 'NOT NULL' : 'NULL';
    $default = isset($definition['dflt_value']) ? "DEFAULT " . $definition['dflt_value'] : '';
    $sql = "ALTER TABLE `$tableName` ADD `$columnName` $type $null $default";
    $mysqlPdo->exec($sql);
    echo "<p>Spalte <b>$columnName</b> in Tabelle <b>$tableName</b> wurde erfolgreich hinzugefügt.</p>";
}

/**
 * Unterschiede anzeigen und Schaltflächen zum Ergänzen hinzufügen.
 */
function renderDifferences($differences, $sqliteStructure, $dbKey) {
    if (!empty($differences['missing_tables'])) {
        echo "<h3>Fehlende Tabellen:</h3>";
        foreach ($differences['missing_tables'] as $tableName => $columns) {
            echo "<p>Tabelle <b>$tableName</b> fehlt.";
            echo " <form method='post' style='display:inline;'>
                      <input type='hidden' name='action' value='add_table'>
                      <input type='hidden' name='table_name' value='$tableName'>
                      <input type='hidden' name='db_key' value='$dbKey'>
                      <button type='submit'>Tabelle hinzufügen</button>
                  </form></p>";
        }
    }

    if (!empty($differences['missing_columns'])) {
        echo "<h3>Fehlende Spalten:</h3>";
        foreach ($differences['missing_columns'] as $tableName => $columns) {
            foreach ($columns as $columnName => $definition) {
                echo "<p>Spalte <b>$columnName</b> in Tabelle <b>$tableName</b> fehlt.";
                echo " <form method='post' style='display:inline;'>
                          <input type='hidden' name='action' value='add_column'>
                          <input type='hidden' name='table_name' value='$tableName'>
                          <input type='hidden' name='column_name' value='$columnName'>
                          <input type='hidden' name='column_definition' value='" . htmlspecialchars(json_encode($definition)) . "'>
                          <input type='hidden' name='db_key' value='$dbKey'>
                          <button type='submit'>Spalte hinzufügen</button>
                      </form></p>";
            }
        }
    }
}

// POST-Anfragen verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $tableName = $_POST['table_name'] ?? '';
    $columnName = $_POST['column_name'] ?? '';
    $columnDefinition = $_POST['column_definition'] ?? '';
    $dbKey = $_POST['db_key'] ?? '';

    if (!isset($dbConfigs[$dbKey])) {
        die("Ungültige Datenbankkonfiguration.");
    }

    $config = $dbConfigs[$dbKey];
    $mysqlPdo = connectToMySQLDatabase($config['datenbankname'], $config['host'], $config['user'], $config['password']);

    if ($action === 'add_table' && $tableName) {
        $sqlitePdo = createSQLiteDatabase(loadSqlFile($config['template']));
        $sqliteStructure = getSQLiteTableStructure($sqlitePdo);
        if (isset($sqliteStructure[$tableName])) {
            addTable($mysqlPdo, $tableName, $sqliteStructure[$tableName]);
        }
    }

    if ($action === 'add_column' && $tableName && $columnName && $columnDefinition) {
        $definition = json_decode($columnDefinition, true);
        addColumn($mysqlPdo, $tableName, $columnName, $definition);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Hauptprüfung
function checkDatabase($dbConfigs) {
    $hasDifferences = false; // Variable, um zu verfolgen, ob Abweichungen gefunden wurden

    foreach ($dbConfigs as $dbKey => $config) {
        echo "<h2><b>Datenbank prüfen:</b> $dbKey</h2>";
        $mysqlPdo = connectToMySQLDatabase($config['datenbankname'], $config['host'], $config['user'], $config['password']);
        $rawSql = loadSqlFile($config['template']);
        $cleanSql = cleanMySQLSqlForSQLite($rawSql);
        $sqlitePdo = createSQLiteDatabase($cleanSql);
        $sqliteStructure = getSQLiteTableStructure($sqlitePdo);
        $mysqlStructure = getMySQLTableStructure($mysqlPdo);
        $differences = compareStructures($mysqlStructure, $sqliteStructure);
        
        if (!empty($differences['missing_tables']) || !empty($differences['missing_columns'])) {
            renderDifferences($differences, $sqliteStructure, $dbKey);
            $hasDifferences = true; // Setze die Variable auf true, wenn Abweichungen gefunden wurden
        }
    }

    if (!$hasDifferences) {
        echo "<p style='margin-top: 3em;'>Alles korrekt - keine Abweichungen gefunden!</p>"; // Nachricht, wenn keine Abweichungen gefunden wurden
    }
}

checkDatabase($dbConfigs);
?>

<p>
    <form method="post" action="./setup.php">
        <input style='margin-top: 3em;' type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
    </form>
</p>

<?php
include($pfad_workdir . "fuss.php");
?>
