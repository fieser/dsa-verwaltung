<h2>Interner Webserver</h2>
<p>Kern des Sytems ist der interne Webserver. Das kann z.B., wie bei uns, eine VM mit einem Linux sein.</p>

> [!TIP]
> [Installationsskript auf Ubuntu 24.04.1 LTS](https://github.com/fieser/dsa-verwaltung/wiki/Installationsskript-auf-Ubuntu-Server-24.04.1-LTS)<br>(Dieses Installationsskript erledigt die folgenden Schritte 1 bis 12 in wenigen Sekunden) 




1. Installieren und konfigurieren Sie einen Webserver (Apache, PHP und MySQL)
2. Installieren Sie optional das Tool <i>phpMyAdmin</i>, das die Verwaltung der Datenbank deutlich erleichtert.
3. Clonen Sie das Github-Repository <i>dsa-verwaltung</i> in das Webverzeichnis Ihres Servers:

```
mkdir /var/www/html/verwaltung
cd /var/www/html/verwaltung
git clone https://github.com/fieser/dsa-verwaltung.git
```

4. Laden Sie sich die Datenbankdateien <i>``./db/migrations/db_structure_verwaltung_www.sql``</i> und <i>``./db/migrations/db_structure_verwaltung_temp.sql``</i> herunter und importieren Sie sie in zwei separate MySQL-Datenbanken <i>anmeldung_www_2425</i>
(für aktuelles Anmeldeschuljahr) und <i>anmeldung_temp</i>.

> <b>Zwei separate Datenbanken, weil...</b><br>
...der öffentliche Server nicht auf alle Schülerdaten zugreifen können soll. Von außerhalb des Verwaltungsnetzes kann man nur auf die Datenbank <i>anmeldung_temp</i>
 zugreifen. Das öffentliche Anmeldeformular schreibt in die Datenbank <i>anmeldung_temp</i>. Der interne Webserver nutzt aber grundsätzlich die Datenbank <i>anmeldung_www</i>.
 Wenn das Sekretariat auf ihm die Anmeldeliste aufruft, werden alle neu eingegangenen Anmeldungen von der Datenbank <i>anmeldung_temp</i> in die Datenbank <i>anmeldung_www</i>
verschoben.
	
5. Verschieben oder Kopieren Sie die php-Datein im Verzeichnis ./systemumgebung/DB-Verbindungen... außerhalb Ihres Webverzeichnises.</i>
6. Legen Sie für beide Datenbanken die Nutzernamen und Zugriffsberechtigungen fest.
7. Tragen Sie die Zugangsdaten zu den beiden MySQL-Datenbanken in die Dateien <i>``verbinden.php``</i>, <i>``verbinden_www.php``</i> und <i>``verbinden_temp.php``</i> ein.
8. In der Datei <i>config.php</i> können Sie die Email-Signatur der Schule konfigurieren.
9. In der Datei <i>login_ad.php</i> können Sie die Verbindung zu Ihrem LDAP-Server (z.B. Windows ActivDirectory) konfigurieren.<br>
Alternativ können Sie eine Benutzerverwaltung per MySQL-Datenbank einrichten.
10. In der Datei <i>rechte.php</i> können Sie den Gruppen Admins, Sekretariatskräften und Lehrkräften Nutzernamen zuordnen.
11. Das Layout (Stylesheet) wird in der Datei kopf.php geladen. Dort wird auch das Schullogo eingebunden.
12. Installieren Sie über die Linux-Paketverwaltung das Tool <i>ImageMagick</i>, damit PHP Vorschaubilder generieren kann.
13. Optional: Installieren Sie Python3, um das Skript zur Generierung der Excel-Importdatei für edoo.sys RLP direkt auf dem Server generieren zu können. 
Dieses Skript befindet sich im Verzeichnis ./export/. Testen Sie es zunächst in der Linux-Konsole, um evtl. fehlende Python-Module zu bemerken und nachinstallieren zu können.<br>
