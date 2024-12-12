 <?php
// Zugangsdaten zur Datenbank
$DB_HOST = "localhost"; // Host-Adresse
 $erstes_jahr = 22;


//$DB_NAME = "anmeldung_www"; // Datenbankname

if (!isset($_SESSION['schuljahr']) OR $_SESSION['schuljahr'] == "") {
		$_SESSION['schuljahr'] = "2025-2026"; //In diese Schuljahr gehen derzeit alle neuen Anmeldungen
}

if (!isset($DB_NAME) OR $DB_NAME == "") {
$DB_NAME = "anmeldung_www_".$_SESSION['schuljahr'][2].$_SESSION['schuljahr'][3].$_SESSION['schuljahr'][7].$_SESSION['schuljahr'][8]; // Datenbankname
}

////////////////////////////////////////////

$DB_BENUTZER = "root"; // Benutzername
$DB_PASSWORT = "GEHEIM"; // Passwort

////////////////////////////////////////////


$OPTION = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
 // Verbindung zur Datenbank aufbauen
 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME,
  $DB_BENUTZER, $DB_PASSWORT, $OPTION);

}
catch (PDOException $e) {
 // Bei einer fehlerhaften Verbindung eine Nachricht ausgeben

 exit("Verbindung fehlgeschlagen! " . $e->getMessage());

}


//Für den Vorjahresvergleich der Statistik:
  if (!isset($DB_NAME_VJ) OR $DB_NAME_VJ == "") {
    $DB_NAME_VJ = "anmeldung_www_".$_SESSION['schuljahr'][2].($_SESSION['schuljahr'][3] - 1).$_SESSION['schuljahr'][7].($_SESSION['schuljahr'][8] - 1); // Datenbankname
    }
    
    try {
    // Verbindung zur Datenbank aufbauen
    $db_vj = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME_VJ,
      $DB_BENUTZER, $DB_PASSWORT, $OPTION);
    
    }
    catch (PDOException $e_vj) {

    }


?> 
