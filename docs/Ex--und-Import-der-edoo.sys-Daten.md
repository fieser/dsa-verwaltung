<p>Unser edoo.sys-Server (DSS) läuft auf einem Windows-System.</p>

1. Laden Sie sich die Dateien <i>./systemumgebung/exportskripte_edooSYS-Server/edoo2anmeldung_start.ps1</i> und <i>./systemumgebung/exportskripte_edooSYS-Server/edoo2anmeldung_abfragen.ps1</i> herunter speichern Sie diese PowerShell-Skripte in einem Verzeichnis auf dem edoo.sys-Server.
2. Im Skript <i>edoo2anmeldung_start.ps1</i> müssen einige Variablen konfiguriert werden.
3. Das Skript <i>edoo2anmeldung_start.ps1</i> exportiert die Wertelisten und Schülerdaten und kopiert sie auf den internen Webserver. Erstellen Sie (mit <i>Puttygen</i>) ein Zertifikat, mit dem sich das Skript per SSH-Verbindung am Server authentifizieren kann. Testen Sie das Skript und starten Sie es regelmäßig über die Windows-Aufgabenplanung. Das Skript <i>edoo2anmeldung_start.ps1</i> wird enthält die Select-Abfragen und wird vom Skript <i>edoo2anmeldung_start.ps1</i> aufgerufen bzw. eingebunden.
4. Im Verzeichnis <i>./systemumgebung/bash-skripte_fuer_DB-Import/</i> finden Sie mehrer Dateien. Kopieren oder verschieben Sie diese Dateien in ein auf dem internen Webserver in ein Verzeichnis außerhalb des Webverzeichnisses.
<br>In den Shellskripten müssen ggf. noch Pfade angepasst werden.
5. Richten Sie auf dem internen Werserver zwei Cronjobs ein, die regelmäßig die Dateien <i>check_upload_edoo2anmeldung.sh</i> und vorschaubilder.sh ausführen.<br>In der Datei <i>./systemumgebung/bash-skripte_fuer_DB-Import/Beispiele_crontab-Einträge.txt</i> finden Sie Beispiele zur Einrichtung der Cronjobs.
6. Sie importieren die edoo.sys-Daten und generieren Vorschaubilder für eingereichte Nachweisdokumente.
	