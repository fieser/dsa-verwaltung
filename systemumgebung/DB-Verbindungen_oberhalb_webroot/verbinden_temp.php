 <?php
// Zugangsdaten zur Datenbank
$DB_HOST = "localhost"; // Host-Adresse
$erstes_jahr = 22;

$DB_NAME = "anmeldung_temp"; // Datenbankname



/////////////////////////////////////////////

$DB_BENUTZER = "local_temp"; // Benutzername
$DB_PASSWORT = "GEHEIM"; // Passwort

////////////////////////////////////////////





$OPTION = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
 // Verbindung zur Datenbank aufbauen
 $db_temp = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME,
  $DB_BENUTZER, $DB_PASSWORT, $OPTION);

}
catch (PDOException $e) {
 // Bei einer fehlerhaften Verbindung eine Nachricht ausgeben
 exit("Verbindung fehlgeschlagen! " . $e->getMessage());
}


?> 
