Das folgende Installationsskript wird direkt nach der Basisinstallation von Ubuntu Server 24.04.1 LTS ausgeführt und ermöglicht die automatische Ausführung folgender Installationsschritte:
1. Update des Linux-Systems
2. Installation aller erforderlichen Pakete (u.a. Apache, PHP, Mysql, Git)
3. Vorbereitung der Datenbankverbindung
4. Einrichten des Webverzeichnisses (Download per Git, Setzen der Dateiberechtigungen)

> [!IMPORTANT]
> Nach der Installation per Skript muss noch die Datenbankverbindung zum internen Webserver (dsa-verwaltung) und SSL eingerichtet werden:
> - Auf dem internen Webserver (dsa-verwaltung) muss der Datenbanknutzer remote_temp das Recht erhalten vom Formularserver zuzugreifen.
> - Auf dem Formularserver (dsa-formular) muss in /var/www/verbinden_temp.php die IP-Adresse des internen Webserbers (dsa-verwaltung) angegeben werden.
> - Die Wertelisten im Formular werden erst gefüllt sein, wenn der interne Webserver die WL vom edoo.sys DSS geladen hat.
> - Stellen Sie den Formularserver auf SSL (Port 443) um.

### Installationsscript
Kopieren Sie den Code auf der Linux-Konsole in eine Datei. 
Passen Sie die drei Passwörter _IhrStarkesPasswort_ in den ersten Zeilen des Skriptes an oder nach der Installation an. 
Machen Sie die Datei ausführbar mit `chmod +x <Dateiname>` und starten Sie sie.

```
#!/bin/bash

# Installationsskript für dsa_formular (externer Webserver) auf Ubuntu 24.04.1 LTS

# System aktualisieren
sudo apt update && sudo apt upgrade -y

# Apache, MySQL und PHP installieren
sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php composer unzip wget git -y

# MySQL konfigurieren
MYSQL_ROOT_PASSWORD="IhrStarkesPasswort"
USER_TEMP="remote_temp"
USER_TEMP_PASSWORD="IhrStarkesPasswort"
USER_WWW="user_www"
USER_WWW_PASSWORD="IhrStarkesPasswort"
IP_VERWALTUNG="192.168.50.14"

# Website-Dateien bereitstellen
sudo rm -rf /var/www/html//*
sudo git clone https://github.com/fieser/dsa-formular.git /var/www/html/anmeldung/

# Demodaten bereitstellen
sudo cp /var/www/html/anmeldung/daten_demo/svp_* /var/www/html/anmeldung/daten/

sudo chown -R www-data:www-data /var/www/html/anmeldung/
sudo chmod -R 755 /var/www/html/anmeldung/
sudo cp /var/www/html/anmeldung/systemumgebung/DB-Verbindungen_oberhalb_webroot/* /var/www/

# Platzhalter 'GEHEIM' ersetzen
sudo find /var/www/ -type f -exec sed -i "s/GEHEIM/$MYSQL_ROOT_PASSWORD/g" {} \;

# Benutzer in Verbindungsdateien ersetzen
sudo sed -i "s/root/$USER_TEMP/g" /var/www/verbinden_temp.php
sudo sed -i "s/192.168.50.14/$IP_VERWALTUNG/g" /var/www/verbinden_temp.php

# Einstellungen in config.php anpassen:
sudo sed -i "s/\$ldap_aktiviert = 1/\$ldap_aktiviert = 0/g" /var/www/html/anmeldung/config.php

# Zeilen aus config.php löschen
CONFIG_FILE="/var/www/html/anmeldung/config.php"
sudo sed -i '/\$schuljahre\["24-25"\]/,/;/d' "$CONFIG_FILE"
sudo sed -i "s/\$ldap_aktiviert = 1/\$ldap_aktiviert = 0/g" /var/www/html/anmeldung/config.php


# Lokale IP ermitteln
LOCAL_IP=$(hostname -I | awk '{print $1}')

# Apache neu starten
sudo systemctl restart apache2

# Abschlussmeldung
echo "Installation abgeschlossen. Besuchen Sie http://$LOCAL_IP/anmeldung, um Ihre Website zu sehen."

```


