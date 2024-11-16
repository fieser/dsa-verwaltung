<?php

include("./kopf.php");

date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



include "./rechte.php";
include "./config.php";
if ($db->query("CREATE TABLE IF NOT EXISTS mail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dsa_bewerberdaten INT(11),
    mailtext LONGTEXT,
	log LONGTEXT,
    last_user VARCHAR(200),
    last_time VARCHAR(200)
) ENGINE = InnoDB")) {
	echo "<p>Tabelle <b>mail</b> angelegt oder bereits vorhanden.</p>";
}

try {
    // Überprüfen, ob die Spalte bereits existiert
    $checkColumnExists = $db_temp->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'anmeldung_temp' AND TABLE_NAME = 'summen' AND COLUMN_NAME = 'prio'")->rowCount();

    if ($checkColumnExists == 0) {
        // Wenn die Spalte nicht existiert, fügen Sie sie hinzu
        if ($db_temp->query("ALTER TABLE summen ADD prio VARCHAR(11)")) {
            echo "<p>Spalte <b>prio</b> zur Tabelle <b>summen</b> hinzugefügt.</p>";
        } else {
            echo "<p>Fehler beim Hinzufügen der Spalte <b>prio</b> zur Tabelle <b>summen</b>.</p>";
        }
    } else {
        // Die Spalte existiert bereits, keine Aktion notwendig
        echo "<p>Die Spalte <b>prio</b> existiert bereits in der Tabelle <b>summen</b>.</p>";
    }
} catch (PDOException $e) {
    die("DB Fehler: " . $e->getMessage());
}



$tabelle = "dsa_bewerberdaten";
$spalte = "papierkorb";
try {
    // Überprüfen, ob die Spalte bereits existiert
    $checkColumnExists = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'anmeldung_www' AND TABLE_NAME = '$tabelle' AND COLUMN_NAME = '$spalte'")->rowCount();

    if ($checkColumnExists == 0) {
        // Wenn die Spalte nicht existiert, fügen Sie sie hinzu
        if ($db->query("ALTER TABLE $tabelle ADD $spalte VARCHAR(11)")) {
            echo "<p>Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b> hinzugefügt.</p>";
        } else {
            echo "<p>Fehler beim Hinzufügen der Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b>.</p>";
        }
    } else {
        // Die Spalte existiert bereits, keine Aktion notwendig
        echo "<p>Die Spalte <b>".$spalte."</b> existiert bereits in der Tabelle <b>".$tabelle."</b>.</p>";
    }
} catch (PDOException $e) {
    die("DB Fehler: " . $e->getMessage());
}

$tabelle = "dsa_bewerberdaten";
$spalte = "pap_user";
try {
    // Überprüfen, ob die Spalte bereits existiert
    $checkColumnExists2 = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'anmeldung_www' AND TABLE_NAME = '$tabelle' AND COLUMN_NAME = '$spalte'")->rowCount();

    if ($checkColumnExists2 == 0) {
        // Wenn die Spalte nicht existiert, fügen Sie sie hinzu
        if ($db->query("ALTER TABLE $tabelle ADD $spalte VARCHAR(200)")) {
            echo "<p>Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b> hinzugefügt.</p>";
        } else {
            echo "<p>Fehler beim Hinzufügen der Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b>.</p>";
        }
    } else {
        // Die Spalte existiert bereits, keine Aktion notwendig
        echo "<p>Die Spalte <b>".$spalte."</b> existiert bereits in der Tabelle <b>".$tabelle."</b>.</p>";
    }
} catch (PDOException $e) {
    die("DB Fehler: " . $e->getMessage());
}


$tabelle = "dsa_bewerberdaten";
$spalte = "pap_time";
try {
    // Überprüfen, ob die Spalte bereits existiert
    $checkColumnExists3 = $db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'anmeldung_www' AND TABLE_NAME = '$tabelle' AND COLUMN_NAME = '$spalte'")->rowCount();

    if ($checkColumnExists3 == 0) {
        // Wenn die Spalte nicht existiert, fügen Sie sie hinzu
        if ($db->query("ALTER TABLE $tabelle ADD $spalte VARCHAR(200)")) {
            echo "<p>Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b> hinzugefügt.</p>";
        } else {
            echo "<p>Fehler beim Hinzufügen der Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b>.</p>";
        }
    } else {
        // Die Spalte existiert bereits, keine Aktion notwendig
        echo "<p>Die Spalte <b>".$spalte."</b> existiert bereits in der Tabelle <b>".$tabelle."</b>.</p>";
    }
} catch (PDOException $e) {
    die("DB Fehler: " . $e->getMessage());
}

$tabelle = "summen";
$spalte = "papierkorb";
try {
    // Überprüfen, ob die Spalte bereits existiert
    $checkColumnExists3 = $db_temp->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'anmeldung_temp' AND TABLE_NAME = '$tabelle' AND COLUMN_NAME = '$spalte'")->rowCount();

    if ($checkColumnExists3 == 0) {
        // Wenn die Spalte nicht existiert, fügen Sie sie hinzu
        if ($db_temp->query("ALTER TABLE $tabelle ADD $spalte VARCHAR(11)")) {
            echo "<p>Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b> hinzugefügt.</p>";
        } else {
            echo "<p>Fehler beim Hinzufügen der Spalte <b>".$spalte."</b> zur Tabelle <b>".$tabelle."</b>.</p>";
        }
    } else {
        // Die Spalte existiert bereits, keine Aktion notwendig
        echo "<p>Die Spalte <b>".$spalte."</b> existiert bereits in der Tabelle <b>".$tabelle."</b>.</p>";
    }
} catch (PDOException $e) {
    die("DB Fehler: " . $e->getMessage());
}

echo "<p>Update der Datenbank abgeschlossen!</p>";




} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=liste");

}

include("./fuss.php");

?>