<h2>Öffentlicher Webserver</h2>
<p>Dieser Webserver befindet sich außerhalb des Verwaltungsnetzes.<br>Wir (BBS1-Mainz) haben einen VServer bei <i>Strato</i> - 11,- Euro/M.) - angemietet. Alternativ können Sie außerhalb des Verwaltungsnetzes einen eigenen Webserver in einem abgeschotteten Bereich (DMZ) betreiben.</p>
<p>Den externen Formularserver können sich auch mehrere Schulen oder alle Schulen eines Trägers teilen. Wir nutzen unseren Server gemeinsam mit der BBS2-Mainz.</p>

> [!TIP]
> [Installationsskript für den Formularserver auf Ubuntu 24.04.1 LTS](https://github.com/fieser/dsa-verwaltung/wiki/Installationsskript-f%C3%BCr-den-Formularserver-%28Ubuntu-Server-24.04.1-LTS%29)<br>(Dieses Installationsskript erledigt die folgenden Schritte 1 bis 3 in wenigen Sekunden) 
	
1. Installieren und konfigurieren Sie auch hier einen Webserver (Apache mit PHP).
2. Eine Datenbank wird nicht benötigt.
3. Clonen Sie das Github-Repository <i>dsa-anmeldung</i> in das Webverzeichnis Ihres Servers:

```
mkdir /var/www/html/anmeldung
cd /var/www/html/anmeldung
git clone https://github.com/fieser/dsa-anmeldung.git
```

4. In der Datei <i>config.php</i> können Sie Ihre Schulformen aktivieren und deaktivieren.
5. Die Schwerpunkte/Fachrichtungen der einzelnen Schulformen werden nicht auf diesem Server, sondern direkt in edoo.sys über die definierten Bewerbungsziele konfiguriert.
6. Speichern und konfigurieren Sie in der Datei <i>verbinden_temp.php</i> die Verbindung zur Datenbank <i>anmeldung_temp</i> des internen Webservers.<br>
Sichern Sie die Verbindung zur Datenbank <i>anmeldung_temp</i> des internen Servers bestmöglich ab.
7. Passen Sie die Datei <i>.htaccess</i> im Unterverzeichnis <i>dokumente</i> an.<br>
Dort muss zumindest die öffentliche IP des internen Webservers freigegeben sein.

<div class='box-grau' style='margin-top: 5px;'>
<b>Sicherheitskonzept bezüglich Upload von Zeugnissen und Ausweisdokumenten:</b><br>
<ul>
<li>Übertragung per SSL</li>
<li>Verschlüsselte Speicherung auf dem öffentlichen Server (AES-256)</li>
<li>Dynamische Passwörter je Datensatz</li>
<li>Regelmäßige "Abholung" durch (ausschließlich) den internen Webserver</li>
<li>Lediglich temporäre Speicherung (max. 40 min) auf dem öffentlichen Webserver</li>
<li>Langzeitspeicherung nur auf dem internen Webserver</li>
</ul>
</div>
