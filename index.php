<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];


//Wenn das Schuljahr per Dopdown gewechselt wurde:
if (isset($_POST['sj']) AND $_POST['sj'] != "") {
		$_SESSION['schuljahr'] = $_POST['sj'];
}

	//Balken bei abweichendem Schuljahr:
	include("./abweichendes_sj.php");

include("./config.php");

//Wenn in der Session noch kein Schuljahr gewählt wurde:

if (!isset($_SESSION['schuljahr'])) {
	
	/*
	if ($beginn_schuljahr > time()) {
	$_SESSION['schuljahr'] = ($jahr - 1)."-".$jahr;
	} else {
	$_SESSION['schuljahr'] = $jahr."-".($jahr + 1);
	}
	*/
	$_SESSION['schuljahr'] = $schuljahr; //SJ gemäß Eingabe in config.php
	
}


function isPackageInstalled($packageName) {
    // Befehle für verschiedene Paketmanager
    $commands = [
        "dpkg-query -W -f='\${Status}' $packageName 2>/dev/null | grep -q 'install ok installed'", // Debian/Ubuntu
        "rpm -q $packageName >/dev/null 2>&1", // RedHat/CentOS/Fedora
        "pacman -Q $packageName >/dev/null 2>&1", // Arch Linux
        "apk info $packageName >/dev/null 2>&1", // Alpine Linux
        "brew list $packageName >/dev/null 2>&1", // macOS mit Homebrew
    ];

    foreach ($commands as $command) {
        $output = null;
        $returnVar = null;
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            return true; // Paket ist installiert
        }
    }

    // Wenn kein Paketmanager das Paket gefunden hat
    return false;
}



// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";


echo "<table style='width: 71em;'><tr>";
echo "<td>";
echo "<h1><b>Anmeldungen</b> ".$periode."</h1>";
echo "</td>";

echo "<td allign='left' style='padding-top: 1em;' >";

echo "<form id='form_sj' action='".$_SERVER['PHP_SELF']."' method='POST'>";
echo"<select name='sj' onchange='change_sj()'>";
echo "<option value=''>Anmeldeperiode wechseln</option>";



foreach($schuljahre as $sjahr) {
	
	if (!isset($_SESSION['erstes_schuljahr'])) {
		$_SESSION['erstes_schuljahr'] = $sjahr['jahr'];
	}
			
				//Nur auflisten, wenn admin, FT oder Schuljahr in config.php für Lehrkräfte freigegeben ist:
				if ($_SESSION['admin'] == "true" OR $_SESSION['ft'] == "true" OR $sjahr['sichtbar_lk'] == 1) {
					
					echo "<option value='".$sjahr['jahr']."'>Anmeldungen ".$sjahr['periode']."</option>";
				}
				
			}



echo "</select>";
echo "</form>";

echo "</td>";
echo "</tr>";
echo "</table>";



?>

<script>
		function change_sj(){
			document.getElementById("form_sj").submit();
		}
		</script>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerberdaten</title>
    <style>

<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerberdaten</title>
    <style>
        .box-grau {
            padding: 10px;
            background-color: #d3d3d3;
            margin-bottom: 20px;
        }

        * {
            box-sizing: border-box;
        }

        .flex-container {
            display: flex;
            flex-direction: row;
            text-align: center;
        }

        .flex-item-left {
            padding: 10px;
            flex: 30%;
            text-align: left;
        }

        .flex-item-right {
            padding: 10px;
            flex: 70%;
            text-align: left;
        }

        /* Responsive layout - makes a one column-layout instead of two-column layout */
        @media (max-width: 800px) {
            .flex-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <h1 style='margin-bottom: 2em;'> </h1>

    <div class="flex-container">
        <div class="flex-item-left">
            <form method="post" action="./liste.php?pk=0">
                <input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Liste Schüleranmeldungen" />
            </form>
            <?php
            if ($button_einschulung == 1) {
            ?>
            <form method="post" action="./einschulung.php">
                <input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-warning btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Übersicht Einschulung 2024" />
            </form>
            <?php
            }
            if (($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) AND $button_klassenlisten == 1) { ?>
                <form method="post" action="./klassenlisten.php?pk=0">
                    <input style="width: 23.3em; margin-bottom: 2em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Aktuelle Klassenlisten" />
                </form>
            <?php }
            if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { ?>
                <form class='flex-container' method="post" action="./todo.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ToDo-Liste" />
                </form>
            <?php } else { ?>
                <form class='flex-container' method="post" action="./todo.php">
                    <input style="width: 23.3em;" class='btn btn-defau	lt btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="ToDo-Liste" disabled />
                </form>
            <?php }

            if ($_SESSION['admin'] == 1) { ?>
                <form class='flex-container' method="post" action="./import_edoo.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="edoo.sys-Daten aktualisieren" />
                </form>
            <?php }
            if ($_SESSION['admin'] == 1) { ?>
                <form class='flex-container' method="post" action="./fehler_ermitteln.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Abweichungen ermitteln" />
                </form>

                <form class='flex-container' method="post" action="./setup.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Setup" />
                </form>

            <?php }


            if ($_SESSION['sek'] == 1 OR $_SESSION['admin'] == 1 OR $_SESSION['ft'] == 1) { ?>
                <form class='flex-container' method="post" action="./senden_texte.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hinweistexte anpassen" />
                </form>
                <?php
                if ($button_querliste == 1) {
                ?>

                <form class='flex-container' method="post" action="../querliste/index.php">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Querliste 2.0" />
                </form>
            <?php 
                }
            } else { ?>
                <form class='flex-container' method="post" action="./todosenden_textephp">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Hinweistexte anpassen" disabled />
                </form>
            <?php }
            if ($_SESSION['admin'] == 1 OR $_SESSION['sek'] == 1) { ?>
                <form class='flex-container' method="post" action="./liste.php?pk=1">
                    <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Papierkorb" />
                </form>
            <?php }

            //if ($_SESSION['admin'] == 1) { ?>
            <form class='flex-container' method="post" action="./statistik.php">
                <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Statistik" />
            </form>
            <?php //} ?>

<?php
            echo "<form class='flex-container' method='post' target='-blank' action='".$url_anmeldung."'>";
                ?>
                <input style="width: 23.3em;" class='btn btn-default btn-sm' type="submit" name="cmd[doStandardAuthentication]" value="Formularseite auf Website" />
            </form>

            <form method="post" action="./logout_ad.php">
                <input style='margin-top: 20px;' type="submit" name="cmd[doStandardAuthentication]" value="Abmelden" />
            </form>
        </div>
        <div class="flex-item-right">
          <?php
            if ($_SESSION['admin'] == 1) {
            echo "<div class='box-grau' style='padding: 10px; background-color: #d3d3d3;'>";
              

                echo "<h2 style='padding: 0px 0 0 25px;'><b>Konfiguration</b></h2>";
                echo "<ul>";

                //LDAP-Einstellungen:
                if ($ldap_aktiviert != 1) {
                echo "<li><font color='red'><b>Authentifizierung per LDAP einrichten!</b></font><br>";
                echo "Konfigurieren Sie in der Datei <i>login_ad.php</i> IP und DN Ihres LDAP-Servers bzw. Windows-AD.<br>
                Durch Aktivierung der Variable <code>\$ldap_aktiviert</code> <i>config.php</i> werden die Einstellunge wirksam.</li>";
                }

                 //Origin Repo auf Aktualisierungen prüfen
                if ($hinweise_conf_anzeigen == 1 AND file_exists("./.git/index")) {

                    echo "<li><b>Github auf Aktualisierungen prüfen</b><br>";

                    
                    // Verzeichnis des lokalen Git-Repositories
                    $repoPath = './';
                    
                    // Sicherstellen, dass das Skript in das Repository-Verzeichnis wechselt
                    chdir($repoPath);
                    
                    // Schritt 1: Git Fetch ausführen
                    exec('git fetch 2>&1', $outputFetch, $returnFetch);
                    if ($returnFetch !== 0) {
                        echo "Fehler beim Abrufen der Änderungen auf Github: " . implode("\n", $outputFetch);
                        exit;
                    }
                    
                    // Schritt 2: Status prüfen
                    exec('git status -uno 2>&1', $outputStatus, $returnStatus);
                    if ($returnStatus !== 0) {
                        echo "Fehler beim Überprüfen des Status: " . implode("\n", $outputStatus);
                        exit;
                    }

                    
                    
                    // Ausgabe des Status analysieren
                    $status = implode("\n", $outputStatus);
                    if (strpos($status, 'Your branch is behind') !== false) {
                        echo "Es gibt Updates auf Github!<br>";
                        echo "Im Webverzeichnis können Sie mit dem Befehl <code>git pull</code> dieses Tool aktualisieren.<br>";
                        echo "Prüfen Sie anschließend über die Schaltfläche <i>Setup</i> die Datenbankstruckturen.</li>";
 
                    } else {
                        echo "Ihr System ist aktuell.";
                    }

                    echo "</li>";
                }

                //Datenbankupdate:

                
                    // Aktuellen Git-Tag oder Commit abrufen
                    $gitTag = trim(shell_exec('git describe --tags --abbrev=0 2>/dev/null')) ?: trim(shell_exec('git rev-parse --short HEAD'));

                    if (!$gitTag) {
                        die("<p>Git-Version konnte nicht ermittelt werden. Stellen Sie sicher, dass 'git' verfügbar ist.</p>");
                    }

                    
                        // Transaktionen starten
                        if ($db) {
                            $db->beginTransaction();
                            
                        }

                        if ($db_temp) {
                            $db_temp->beginTransaction();
                            
                        }

                        // Tabelle für Migrationen erstellen
                        if ($db->query("CREATE TABLE IF NOT EXISTS migration_versions (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            version VARCHAR(50) NOT NULL,
                            migration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ) ENGINE = InnoDB")) {
                            
                        }

                        // Prüfen, ob die aktuelle Version bereits eingetragen ist
                        $checkVersion = $db->prepare("SELECT COUNT(*) FROM migration_versions WHERE version = ?");
                        $checkVersion->execute([$gitTag]);

                        if ($checkVersion->fetchColumn() == 0) {
                        // Datenbankmigrationen erforderlich
                            echo "<li>";
                            echo "<b>Ihre Datenbank benötigt ein Update!</b><br>";
                            echo "<form class='flex-container' method='post' action='./db_migration.php'>";
                                echo "<input style='width: 23.3em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Datenbank aktualisieren' />";
                                echo "</form>";
                            echo "</li>"; 
                        }
                

                if ($upload_documents == 1 && !file_exists("./dokumente/unpacked")) {
                    echo "<li><font color='red'><b>Kein Uploadverzeichnis vorhanden!</b></font><br>";
                    echo "Bitte legen Sie auf dem internen Webserver das Verzeichnis <i>/var/www/html".$workdir."dokumente/unpacked</i> an.</li>";
                }

                //Anmeldezeiträume konfigurieren:
                if ($hinweise_conf_anzeigen == 1) {
                    echo "<li><b>Anmeldeperioden konfigurieren</b><br>";
                    echo "In der Datei <i>config.php</i> lassen sich weitere Anmeldeperioden konfigurieren.<br>";
                    echo "Beachten Sie dort den Kommentar.</li>";
                    }

                //Exportverzeichnis für Transferfunktion prüfen:
                if ($hinweise_conf_anzeigen == 1) {
                    echo "<li><b>Berechtigungen im Webverzeichnis prüfen</b><br>";
                $directory = './';

                // Überprüfen, ob das Verzeichnis existiert
                if (is_dir($directory)) {
                    // Alle Dateien im Verzeichnis abrufen
                    $files = scandir($directory);
                    $allPermissionsCorrect = true;

                    foreach ($files as $file) {
                        // Ignoriere '.' und '..'
                        if ($file !== '.' && $file !== '..') {
                            $filePath = $directory . '/' . $file;

                            // Überprüfen, ob es sich um eine Datei handelt
                            if (is_file($filePath)) {
                                // Berechtigungen überprüfen
                                $permissions = substr(sprintf('%o', fileperms($filePath)), -3);
                                
                                if ($permissions !== '777' AND $permissions !== '775') {
                                    echo "<font color='red'>Die Datei $file hat nicht die Berechtigung 775</font>, sondern $permissions.";
                                    $allPermissionsCorrect = false;
                                }
                            }
                        }
                    }

                    // Überprüfen der Berechtigungen für das Verzeichnis selbst
                    $directoryPermissions = substr(sprintf('%o', fileperms($directory)), -3);
                    if ($directoryPermissions !== '777' AND $directoryPermissions !== '775') {
                        echo "<font color='red'>Das Verzeichnis hat nicht die Berechtigung 775</font>, sondern $directoryPermissions.";
                        $allPermissionsCorrect = false;
                    }

                    if ($allPermissionsCorrect) {
                        echo "Berechtigungen sind OK!";
                    }
                } else {
                    echo "<font color='red'>Das Verzeichnis $directory existiert nicht.</font>";
                }

                    ///Prüfung, ob Eigentümer oder Gruppe root
                    function checkRootOwnership($path) {
                        $rootUserId = 0; // ID für root-Eigentümer
                        $rootGroupId = 0; // ID für root-Gruppe
                    
                        // Initialisiere Directory-Iterator
                        $iterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
                        );
                        $anzahl_root = 0;
                        foreach ($iterator as $fileInfo) {
                            $filePath = $fileInfo->getPathname();
                    
                            // Überspringe .git-Verzeichnis
                            if (strpos($filePath, DIRECTORY_SEPARATOR . '.git') !== false
                             OR strpos($filePath, DIRECTORY_SEPARATOR . 'dokumente/unpacked') !== false
                             OR strpos($filePath, DIRECTORY_SEPARATOR . 'dokumente') !== false
                             OR strpos($filePath, DIRECTORY_SEPARATOR . 'dokumente/unpacked') !== false) {
                                continue;
                            }
                    
                            // Hole Besitzer- und Gruppen-IDs
                            $ownerId = $fileInfo->getOwner();
                            $groupId = $fileInfo->getGroup();
                    
                            // Überprüfe auf root-Eigentum
                            if ($ownerId === $rootUserId AND $groupId === $rootGroupId) {
                                //echo "Root-Eigentum gefunden: {$filePath}\n";
                                $anzahl_root++;
                            }
                        }
                        if ($anzahl_root > 0) {
                            echo "<br><font color='red'>Ihr Webverzeichnis enthält Dateien, auf die momentan nur <i>root</i> zugriff hat!</font>";
                        }
                    }
                    
                    // Starte Prüfung im aktuellen Verzeichnis
                    $startPath = __DIR__;
                    checkRootOwnership($startPath);



                echo "</li>";


                $packages = ['php-imagick', 'php-sqlite3', 'imagemagick', 'unzip', 'wget', 'git'];
foreach ($packages as $package) {
    if (isPackageInstalled($package)) {
        //echo "Das Paket '$package' ist installiert.\n";
    } else {
        echo "<li><font color='red'><b>Das Paket '$package' ist NICHT installiert.</b></font><br>";
        echo "</li>";
    }
}
                

               

            }
            
           
                echo "</ul>";
              
                
            echo"</div>";
              }
            ?>
        </div>
    </div>
</body>
</html>
<?php

echo "Berechtigungen: ".$rechte;

} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>
