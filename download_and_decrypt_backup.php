<?php
function decryptFile($sourceContent, $destFile, $passphrase) {
    $key = openssl_digest($passphrase, 'SHA256', TRUE);
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    
    $iv = substr($sourceContent, 0, $iv_length);
    $encryptedContent = substr($sourceContent, $iv_length);

    $decrypted = openssl_decrypt($encryptedContent, 'aes-256-cbc', $key, 0, $iv);
    if ($decrypted === false) {
        return false;
    }

    return file_put_contents($destFile, $decrypted);
}

// Dateiname, der aus der Datenbank geholt wird (hier statisch für das Beispiel)
$filename = 'ausweis_schik.pdf.enc'; // Name der verschlüsselten Datei auf dem ersten Server
$password = "mein_sicheres_passwort"; // Das gleiche Passwort, das zum Verschlüsseln verwendet wurde
$remoteUrl = "https://anmeldung.bbs1-mainz.de/dokumente/" . $filename; // URL, wo die Datei gespeichert ist
$localSavePath = 'dokumente/' . basename($filename, '.enc'); // Lokaler Speicherpfad ohne .enc

// Stellen Sie sicher, dass das Verzeichnis existiert
if (!file_exists('downloads/')) {
    mkdir('downloads/', 0777, true);
}

// Datei vom ersten Server herunterladen
$fileContent = file_get_contents($remoteUrl);
if ($fileContent === false) {
    die("Fehler beim Herunterladen der Datei.");
}

// Datei entschlüsseln und speichern
if (decryptFile($fileContent, $localSavePath, $password)) {
    echo "Datei erfolgreich heruntergeladen, entschlüsselt und gespeichert.";
} else {
    echo "Fehler bei der Entschlüsselung der Datei.";
}
?>
