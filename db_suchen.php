<?php
include("./kopf.php");

// Überprüfen, ob das Formular gesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Benutzereingaben abrufen
    $servername = "localhost"; // oder die IP-Adresse des MySQL-Servers
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verbindung zur MySQL-Datenbank herstellen
    $conn = new mysqli($servername, $username, $password);

    // Überprüfen, ob die Verbindung erfolgreich war
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Abfrage, um alle Datenbanken aufzulisten
    $sql = "SHOW DATABASES";
    $result = $conn->query($sql);

    // Überprüfen, ob Datenbanken gefunden wurden
    if ($result->num_rows > 0) {
        echo "<h2>Vorhandene Datenbanken:</h2><ul>";
        // Ausgeben der Datenbanken
        while ($row = $result->fetch_assoc()) {

            if (strpos($row['Database'],"nmeldung_www_2") != false OR strpos($row['Database'],"nmeldung_www_3") != false OR strpos($row['Database'],"nmeldung_www_2") != false OR $row['Database'] == "anmeldung_temp") {
            echo "<li>" . $row['Database'] . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "Keine Datenbanken gefunden.";
    }

    // Verbindung schließen
    $conn->close();
} else {
    // Formular anzeigen
    echo '<form method="post" action="">
            <label for="username">Benutzername:</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Passwort:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Datenbanken anzeigen">
          </form>';
}

include("./fuss.php");
?>