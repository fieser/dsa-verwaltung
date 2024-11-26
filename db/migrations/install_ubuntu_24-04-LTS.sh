#!/bin/bash

# Skript zur automatischen Installation der PHP-Website mit MySQL-Datenbanken auf Ubuntu 24.04 LTS

# System aktualisieren
sudo apt update && sudo apt upgrade -y

# Apache, MySQL und PHP installieren
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql imagemagick php-imagick git -y

# MySQL sichern und konfigurieren
MYSQL_ROOT_PASSWORD="IhrStarkesPasswort"
USER_TEMP="local_temp"
USER_TEMP_PASSWORD="IhrStarkesPasswort"
USER_WWW="user_www"
USER_WWW_PASSWORD="IhrStarkesPasswort"

sudo mysql_secure_installation <<EOF

y
0
$MYSQL_ROOT_PASSWORD
$MYSQL_ROOT_PASSWORD
y
y
y
y
EOF

# MySQL-Datenbanken und -Benutzer erstellen
sudo mysql -u root -p$MYSQL_ROOT_PASSWORD <<EOF
CREATE DATABASE anmeldung_temp;
CREATE DATABASE anmeldung_www_2526;
CREATE USER '$USER_TEMP'@'localhost' IDENTIFIED BY '$USER_TEMP_PASSWORD';
CREATE USER '$USER_WWW'@'localhost' IDENTIFIED BY '$USER_WWW_PASSWORD';
GRANT ALL PRIVILEGES ON anmeldung_temp.* TO '$USER_TEMP'@'localhost';
GRANT ALL PRIVILEGES ON anmeldung_www_2526.* TO '$USER_WWW'@'localhost';
FLUSH PRIVILEGES;
EXIT;
EOF

# Website-Dateien bereitstellen
sudo rm -rf /var/www/html/verwaltung/*
sudo git clone https://github.com/fieser/dsa-verwaltung.git /var/www/html/verwaltung/

# Demodaten bereitstellen:
sudo cp /var/www/html/verwaltung/daten_demo/svp_* /var/www/html/verwaltung/daten/

sudo chown -R www-data:www-data /var/www/html/verwaltung/
sudo chmod -R 755 /var/www/html/verwaltung/
sudo cp /var/www/html/verwaltung/systemumgebung/DB-Verbindungen_oberhalb_webroot/* /var/www/

# Platzhalter 'GEHEIM' in den kopierten Dateien ersetzen
sudo find /var/www/ -type f -exec sed -i "s/GEHEIM/$MYSQL_ROOT_PASSWORD/g" {} \;

# Benutzer 'root' in den Verbindungsdateien ersetzen
sudo sed -i "s/root/$USER_WWW/g" /var/www/verbinden.php
sudo sed -i "s/root/$USER_WWW/g" /var/www/verbinden_www.php
sudo sed -i "s/root/$USER_TEMP/g" /var/www/verbinden_temp.php
sudo sed -i "s/$ldap_aktiviert = 1/$ldap_aktiviert = 0/g" /var/www/html/verwaltung/config.php

# SQL-Dateien in die Datenbanken importieren
sudo mysql -u root -p$MYSQL_ROOT_PASSWORD anmeldung_temp < /var/www/html/verwaltung/db/migrations/db_structure_verwaltung_temp.sql
sudo mysql -u root -p$MYSQL_ROOT_PASSWORD anmeldung_www_2526 < /var/www/html/verwaltung/db/migrations/db_structure_verwaltung_www.sql

# Zeilen aus der Datei config.php löschen
CONFIG_FILE="/var/www/html/verwaltung/config.php"
sudo sed -i '/\$schuljahre\["24-25"\]/,/;/d' "$CONFIG_FILE"


# Lokale IP-Adresse ermitteln
LOCAL_IP=$(hostname -I | awk '{print $1}')

# Apache neu starten
sudo systemctl restart apache2

# Abschlussmeldung mit IP-Adresse
echo "Installation abgeschlossen. Besuchen Sie http://$LOCAL_IP/verwaltung, um Ihre Website zu sehen."
