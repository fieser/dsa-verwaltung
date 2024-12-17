<?php
set_time_limit(0);
ignore_user_abort(true); //auch wenn der Nutzer die Sete verlässt, läuft das Skript weiter

@session_start();



include_once($pfad_workdir."config.php");
	

function decryptFile($sourcePath, $destPath, $passphrase) {
    $key = openssl_digest($passphrase, 'SHA256', TRUE);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');

    $fileContent = file_get_contents($sourcePath);
    $iv = substr($fileContent, 0, $iv_length);
    $encryptedContent = substr($fileContent, $iv_length);

    $decrypted = openssl_decrypt($encryptedContent, 'aes-256-cbc', $key, 0, $iv);
    if ($decrypted === false) {
        return false;
    }

    return file_put_contents($destPath, $decrypted);
}

$remoteZipUrl = $url_anmeldung."dokumente/dokumente.zip"; // URL der ZIP-Datei
$localZipPath = $pfad_workdir."dokumente/dokumente.zip"; // Lokaler Pfad für die heruntergeladene ZIP-Datei
$extractPath =  $pfad_workdir."dokumente/unpacked/"; // Verzeichnis, in dem die Dateien entpackt werden sollen

// Löschen der alten ZIP-Datei, falls vorhanden
if (file_exists($localZipPath)) {
    unlink($localZipPath);
}

// ZIP-Datei herunterladen
if (!file_exists($localZipPath)) {
    file_put_contents($localZipPath, fopen($remoteZipUrl, 'r'));
	
	//Downlaod in DB zeitlich vermerken:
	$time = time();
					if ($db_temp->exec("UPDATE `log`
						   SET `wert` = '$time' WHERE `name` = 'last_download'")) {

							}
}

$zip = new ZipArchive;
if ($zip->open($localZipPath) === true) {
    // Entpacken aller Dateien im Archiv
    $zip->extractTo($extractPath);
    $zip->close();

    // Durchlaufen aller entpackten Dateien
    $files = new DirectoryIterator($extractPath);
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'enc') {
            $newFilePath = $extractPath . $file->getBasename('.enc'); // Entfernt die .enc Endung

            // Überspringen, wenn die entschlüsselte Datei bereits existiert
            if (!file_exists($newFilePath)) {
                $md5_datei = explode('.', $file->getBasename('.pdf.enc'))[0]; // Passwort aus dem Dateinamen extrahieren
				
				//Passwort ermitteln:
				$select_sum = $db_temp->query("SELECT md5 FROM summen WHERE md5_o_sf = '$md5_datei'");	
					$treffer_sum = $select_sum->rowCount();
					//echo "Treffer Summen: ".$treffer_sum."<br>";
					//echo "md5 Dateiname: ".$md5_datei."<br>";
					//echo "md5 DB: ".$md5_datei."<br>";

					foreach($select_sum as $sum) {
						$sum_md5 = trim($sum['md5']);
						//echo "md5 DB: ".$sum_md5."<br>";
						$select_bew = $db->query("SELECT geburtsdatum, plz, mail FROM dsa_bewerberdaten WHERE md5 = '$sum_md5'");
						foreach($select_bew as $bew) {
							$password = md5(md5($bew['mail'].$bew['geburtsdatum']).$bew['plz']);
							//echo "PW: ".$password."<br>";
							
							//Ungelesene Dokumente für Bewerber registrieren:
							if ($db->exec("UPDATE `dsa_bewerberdaten`
									   SET
									   	`dok_neu` = '1' WHERE `md5` = '$sum_md5'")) { 
										
											
										}
						}
					}
					
					
				
                if (!decryptFile($file->getPathname(), $newFilePath, $password)) {
					if ($debug == 1) {
                    echo "Fehler bei der Entschlüsselung der Datei: " . $file->getFilename() . "<br>";
					}
                } else {
					if ($debug == 1) {
                    echo "Datei erfolgreich entschlüsselt: " . $newFilePath . "<br>";
					
					}
					
                }
            }
        }
		// Löschen der verschlüsselten Datei, falls vorhanden
					if (file_exists($newFilePath.".enc")) {
						unlink($newFilePath.".enc");
					}
    }
	
	
	
} else {
    echo "Konnte ZIP-Datei nicht öffnen.";
}



//include("./fuss.php");
?>
