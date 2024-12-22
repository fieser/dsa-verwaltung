Das folgende Installationsskript wird direkt nach der Basisinstallation von Ubuntu Server 24.04.1 LTS ausgeführt und ermöglicht die automatische Ausführung folgender Installationsschritte:
1. Update des Linux-Systems
2. Installation aller erforderlichen Pakete (u.a. Apache, PHP, Mysql, Git)
3. MySQL sichern und konfigurieren
4. MySQL-Datenbanken und -Benutzer erstellen
5. Einrichten des Webverzeichnisses (Download per Git, Setzen der Dateiberechtigungen)

### Installationsscript
Kopieren Sie den Code auf der Linux-Konsole in eine Datei. 
Passen Sie die drei Passwörter _IhrStarkesPasswort_ in den ersten Zeilen des Skriptes an oder nach der Installation an. 
Machen Sie die Datei ausführbar mit `chmod +x <Dateiname>` und starten Sie sie.

```
#!/bin/bash

# System aktualisieren
sudo apt update && sudo apt upgrade -y

# Apache, MySQL und PHP installieren
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-sqlite3 imagemagick php-imagick git -y

# MySQL konfigurieren
MYSQL_ROOT_PASSWORD="IhrStarkesPasswort"
USER_TEMP="local_temp"
USER_TEMP_PASSWORD="IhrStarkesPasswort"
USER_WWW="user_www"
USER_WWW_PASSWORD="IhrStarkesPasswort"
USER_REMOTE_TEMP="remote_temp"
USER_REMOTE_TEMP_PASSWORD="IhrStarkesPasswort"
IP_SERVER_REMOTE="192.168.50.13"

# Root-Authentifizierung auf Passwort umstellen
sudo mysql -u root <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$MYSQL_ROOT_PASSWORD';
FLUSH PRIVILEGES;
EOF

# MySQL-Datenbanken und -Benutzer erstellen
sudo mysql -u root -p$MYSQL_ROOT_PASSWORD <<EOF
CREATE DATABASE anmeldung_temp;
CREATE DATABASE anmeldung_www_2526;
CREATE USER '$USER_TEMP'@'localhost' IDENTIFIED BY '$USER_TEMP_PASSWORD';
CREATE USER '$USER_WWW'@'localhost' IDENTIFIED BY '$USER_WWW_PASSWORD';
CREATE USER '$USER_REMOTE'@'$IP_SERVER_REMOTE' IDENTIFIED BY '$USER_REMOTE_TEMP_PASSWORD';

GRANT ALL PRIVILEGES ON anmeldung_temp.* TO '$USER_TEMP'@'localhost';
GRANT ALL PRIVILEGES ON anmeldung_temp.* TO '$USER_REMOTE'@'$IP_SERVER_REMOTE';
GRANT ALL PRIVILEGES ON anmeldung_www_2526.* TO '$USER_WWW'@'localhost';
FLUSH PRIVILEGES;
EOF

# Website-Dateien bereitstellen
sudo rm -rf /var/www/html/verwaltung/*
sudo git clone https://github.com/fieser/dsa-verwaltung.git /var/www/html/verwaltung/

# Demodaten bereitstellen
sudo cp /var/www/html/verwaltung/daten_demo/svp_* /var/www/html/verwaltung/daten/

sudo chown -R www-data:www-data /var/www/html/verwaltung/
sudo chmod -R 755 /var/www/html/verwaltung/
sudo chmod -R 777 /var/www/html/verwaltung/export/

sudo cp /var/www/html/verwaltung/systemumgebung/DB-Verbindungen_oberhalb_webroot/* /var/www/

# Platzhalter 'GEHEIM' ersetzen
sudo find /var/www/ -type f -exec sed -i "s/GEHEIM/$MYSQL_ROOT_PASSWORD/g" {} \;

# Benutzer in Verbindungsdateien ersetzen
sudo sed -i "s/root/$USER_WWW/g" /var/www/verbinden.php
sudo sed -i "s/root/$USER_WWW/g" /var/www/verbinden_www.php
sudo sed -i "s/root/$USER_TEMP/g" /var/www/verbinden_temp.php
sudo sed -i "s/\$ldap_aktiviert = 1/\$ldap_aktiviert = 0/g" /var/www/html/verwaltung/config.php

# MySQL-Konfiguration anpassen:
sudo sed -i "s/bind-address\s*=\s*127\.0\.0\.1/bind-address = 0.0.0.0/g" /etc/mysql/mysql.conf.d/mysqld.cnf


# SQL-Dateien in die Datenbanken importieren
if [ -f "/var/www/html/verwaltung/db/migrations/db_structure_verwaltung_temp.sql" ]; then
    sudo mysql -u root -p$MYSQL_ROOT_PASSWORD anmeldung_temp < /var/www/html/verwaltung/db/migrations/db_structure_verwaltung_temp.sql
else
    echo "SQL-Datei für anmeldung_temp nicht gefunden. Überspringe Import."
fi

if [ -f "/var/www/html/verwaltung/db/migrations/db_structure_verwaltung_www.sql" ]; then
    sudo mysql -u root -p$MYSQL_ROOT_PASSWORD anmeldung_www_2526 < /var/www/html/verwaltung/db/migrations/db_structure_verwaltung_www.sql
else
    echo "SQL-Datei für anmeldung_www_2526 nicht gefunden. Überspringe Import."
fi

# Datenimport auf Zielserver
sudo mysql -u root -p$MYSQL_ROOT_PASSWORD anmeldung_temp < /var/www/html/verwaltung/db/migrations/tb_inhalt_verwaltung_temp_senden_texte.sql

# Zeilen aus config.php löschen
CONFIG_FILE="/var/www/html/verwaltung/config.php"
sed -i '/\/\/EXCLUDE INSTALL START/,/\/\/EXCLUDE INSTALL END/d' "$CONFIG_FILE"
sudo sed -i "s/\$ldap_aktiviert = 1/\$ldap_aktiviert = 0/g" /var/www/html/verwaltung/config.php


# Lokale IP ermitteln
LOCAL_IP=$(hostname -I | awk '{print $1}')

# Apache und MySQL neu starten
sudo systemctl restart apache2
sudo systemctl restart mysql

# Abschlussmeldung
echo "Installation abgeschlossen. Besuchen Sie http://$LOCAL_IP/verwaltung, um Ihre Website zu sehen."

```


