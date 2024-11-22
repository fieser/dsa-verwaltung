# dsa-verwaltung
Verwaltungstool zur Verwaltung digitaler Schüleranmeldungen an BBS für edoo.sys RLP

<h2>Umgesetzte Funktionen</h2>

<ul>
<li>Anmeldeformular mit edoo.sys-Wertelisten</li>
<li>Auswahl des Ausbildungsbetriebes</li>
<li>Automatische Generierung der Liste an der Schule verfügbarer Ausbildungsberufe</li>
<li>Automatische Generierung der Liste der den Beruf ausbildenden Ausbildungsbetriebe</li>
<li>Vorausgefüllte Felder bei weiterenn Anmeldungen</li>
<li>Plausibilisierungen von höchst. all. Abschluss, Schulform, Geburtsdatum usw.</li>
<li>Vermeidung Doppelter Anmeldungen für eine Schulform</li>
<li>Priorisierung mehrerer Anmeldungen für unterschiedlicher Schulformen</li>
<li>Generierung eines Anschreibens als PDF-Datei zur Zusendung der Bewerbungsunterlagen</li>
<li><b>NEU:</b> Upload von Bewerbungsdokumenten (bei der Anmeldung und zu einem späteren Zeitpunkt)</li>

<li>Liste eingegangener Anmeldungen</li>
<li>Filterung und Sortierung</li>
<li>Ein-/Ausblenden fertig bearbeiteter und fehlerfreier Datensätze</li>
<li>Datensatzansicht (Registerkarten analog zu edoo.sys RLP)</li>
<li>Anzeige hochgeladener Bewerbungsunterlagen</li>
<li>Regelmäßiger Abgleich mit edoo.sys-Daten</li>
<li>Generierung einer ToDo-Liste mit fehlenden Einträgen und Eingabefehlern</li>
<li>Automatische Anpassung des Anmeldestatus</li>

<li>Automatische Verlinkung von Anmeldungen an mehreren Schulformen</li>
<li>Registrierung fehlender Anmeldeunterlagen</li>
<li>Notizmöglichkeit für jede eingegangene Anmeldung</li>
<li>Einfache Kontaktaufnahem per E-Mail</li>

<li>Generierung der Import-Excel-Datei für edoo.sys</li>
<ul>
	<li>Es werden jeweils die aktuell gefiltereten Datensätze mit Status <i>vollständig</i> exportiert.</li>
	<li>Weil die eingegangenen Anmeldungen regelmäßig mit edoo.sys verglichen werden, werden niemals bereits erfasste Daten exportiert.
		<br>Außerdem verhindert die Import-Schnittstelle von edoo.sys den Import doppelter SuS.</li>
	<li>Die Bewerber werden den in edoo.sys angelegten Bewerbungszielen zugeordnet.<br>
		Aus diesen gerneriert sich auch automatisch die Auswahlliste im Anmeldeformular.</li>
	<li>Leider können über die für edoo.sys vorgegebene Excel-Datei nicht alle Daten importiert werden.<br>
		Alle Daten betreffend des Ausbildungsverhältnisses müssen händisch ergänzt werden.<br>
		Dabei unterstützt und überprfüft aber die Funkton <i>ToDo-Liste</i></li>
	</ul>

</ul>

<h2>Geplante Funktionen</h2>

<ul>
<li>Abschluss der Bewerbungsphase (mit Löschung der Daten nicht eingeschulter SuS)</li>
<li>Einrichten der nächsten Bewerbungsphase</li>
<li>Abfrage des Bearbeitungsstatus für Schüler, Eltern und Betriebe</li>
<li>Schulformabhängige Auswahlliste für höchst. allg. Abschluss</li>
</ul>

<h2>Infrastruktur</h2>
<ul>
<li><b>edoo.sys DSS</b> (bereits vorhanden)</li>
	<ul>
	<li>Zeitgesteuerter Export von Wertelisten und Schülerdaten</li>
	<li>Zeitgesteuerter Transfer von Wertelisten zum öffentlichen Webserver</li>
	<li>Zeitgesteuerter Transfer von Schülerdaten zum internen Webserver</li>
	</ul>

<li><b>Öffentlicher Webserver</b> (Apache/MySQL/PHP) mit Anmeldeformular</li>
	<ul>
	<li>Bereitstellung des Formulars</li>
	<li>Liest und beschreibt Datenbank des internen Servers</li>
	</ul>

<li><b>Interner Webserver</b> (Apache/MySQL/PHP) für Sekretariatszugriff</li>
	<ul>
	<li>Weboberfläche für die Schulverwaltung</li>
	<li>Speicherung der Anmeldedaten</li>
	<li>Zeitgesteuerte Abgleich der Anmeldedaten mit den Daten vom DSS erhaltenen Schülerdaten</li>
	</ul>

</ul>

<img src='./images/systemskizze.jpg'>

<h1 style='margin-top: 2em;'>Hinweise zur Installation</h1>



<h2>Interner Webserver</h2>
<p>Kern des Sytems ist der interne Webserver. Das kann z.B., wie bei uns, eine VM mit einem Linux sein.</p>

	<ol>
	<li>Installieren und konfigurieren Sie einen Webserver (Apache, PHP und MySQL)</li>
	<li>Installieren Sie optional das Tool <i>phpMyAdmin</i>, das die Verwaltung der Datenbank deutlich erleichtert.</li>
	<li>Laden Sie sich die Datei <i>Dateien_interner_Webserver.zip</i> herunter speichern Sie den Inhalt im Webverzeichnis Ihres Servers.</li>
	<li>Laden Sie sich die Datenbankdateien <i>anmeldung_www_leer.sql</i> und <i>anmeldung_temp_leer.sql</i> herunter und importieren Sie sie in zwei separate MySQL-Datenbanken <i>anmeldung_www</i> und <i>anmeldung_temp</i>.
	<div class='box-grau' style='margin-top: 5px;'>
	<b>Zwei separate Datenbanken, weil...</b><br>
	...der öffentliche Server nicht auf alle Schülerdaten zugreifen können soll. Von außerhalb des Verwaltungsnetzes kann man nur auf die Datenbank <i>anmeldung_temp</i>
	 zugreifen. Das öffentliche Anmeldeformular schreibt in die Datenbank <i>anmeldung_temp</i>. Der interne Webserver nutzt aber grundsätzlich die Datenbank <i>anmeldung_www</i>.
	 Wenn das Sekretariat auf ihm die Anmeldeliste aufruft, werden alle neu eingegangenen Anmeldungen von der Datenbank <i>anmeldung_temp</i> in die Datenbank <i>anmeldung_www</i>
 verschoben.</div>
	</li>
	<li>Laden Sie sich die Datei <i>verbinden.zip</i> herunter und speichern Sie die darin enthaltenen php-Datein außerhalb Ihres Webverzeichnises.</i></li>
	<li>Legen Sie für beide Datenbanken die Nutzernamen und Zugriffsberechtigungen fest.</li>
	<li>Tragen Sie die Zugangsdaten zu den beiden MySQL-Datenbanken in die Dateien <i>verbinden_www.php</i> und <i>verbinden_temp.php</i> ein.</li>
	<li>In der Datei <i>config.php</i> können Sie die Email-Signatur der Schule konfigurieren.</li>
	<li>In der Datei <i>login_ad.php</i> können Sie die Verbindung zu Ihrem LDAP-Server (z.B. Windows ActivDirectory) konfigurieren.<br>
	Alternativ können Sie eine Benutzerverwaltung per MySQL-Datenbank einrichten.</li>
	<li>In der Datei <i>rechte.php</i> können Sie den Gruppen Admins, Sekretariatskräften und Lehrkräften Nutzernamen zuordnen.</li>
	<li>Das Layout (Stylesheet) wird in der Datei kopf.php geladen. Dort wird auch das Schullogo eingebunden.</li>
	<li>Installieren Sie über die Linux-Paketverwaltung das Tool <i>ImageMagick</i>, damit PHP Vorschaubilder generieren kann.</li>
	<li>Optional: Installieren Sie Python3, um das Skript zur Generierung der Excel-Importdatei für edoo.sys RLP direkt auf dem Server generieren zu können. 
	Dieses Skript befindet sich im Verzeichnis ./export/. Testen Sie es zunächst in der Linux-Konsole, um evtl. fehlende Python-Module zu bemerken und nachinstallieren zu können.<br></li>
	</ol>


<h2>Öffentlicher Webserver</h2>
<p>Dieser Webserver befindet sich außerhalb des Verwaltungsnetzes.<br>Wir haben einen VServer bei <i>Strato</i> - 11,- Euro/M.) - angemietet.</p>

	<ol>
	<li>Installieren und konfigurieren Sie auch hier einen Webserver (Apache mit PHP).</li>
	<li>Eine Datenbank wird nicht benötigt.</li>
	<li>Laden Sie sich die Datei <i>Dateien_öffentlicher_Webserver.zip</i> herunter speichern Sie den Inhalt im Webverzeichnis Ihres Servers.</li>
	<li>In der Datei <i>config.php</i> können Sie Ihre Schulformen aktivieren und deaktivieren.</li>
	<li>Die Schwerpunkte/Fachrichtungen der einzelnen Schulformen werden nicht auf diesem Server, sondern direkt in edoo.sys über die definierten Bewerbungsziele konfiguriert.</li>
	<li>Speichern und konfigurieren Sie in der Datei <i>verbinden_temp.php</i> die Verbindung zur Datenbank <i>anmeldung_temp</i> des internen Webservers.<br>
	Sichern Sie die Verbindung zur Datenbank <i>anmeldung_temp</i> des internen Servers bestmöglich ab.</li>
	<li>Passen Sie die Datei <i>.htaccess</i> im Unterverzeichnis <i>dokumente</i> an.<br>
	Dort muss zumindest die öffentliche IP des internen Webservers freigegeben sein.</li>
	
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
	
	</ol>
	
	<h2>Ex- und Import der edoo.sys-Daten</h2>
	<p>Unser edoo.sys-Server (DSS) läuft auf einem Windows-System.</p>

	<ol>
	<li>Laden Sie sich die Datei <i>export-skript_edoosys.zip</i> herunter speichern Sie die sich darin befindende PowerShell-Skript in einem Verzeichnis auf dem edoo.sys-Server.</li>
	<li>Im Skript müssen einige Variablen konfiguriert werden.</li>
	<li>Das Skript exportiert die Wertelisten und Schülerdaten und kopiert sie auf den internen Webserver. Erstellen Sie (mit <i>Puttygen</i>) ein Zertifikat, mit dem sich das Skript per SSH-Verbindung am Server authentifizieren kann. Testen Sie das Skript und starten Sie es regelmäßig über die Windows-Aufgabenplanung.</li>
	<li>Entpacken Sie die Datei <i>bash-skript_fuer_DB-Import.zip</i> und kopieren Sie die beiden Dateien auf den internen Webserver (außerhalb des Webverzeichnisses).
	<br>In den beiden Shellskripten müssen ggf. noch Pfade angepasst werden.</li>
	<li>Richten Sie auf dem internen Werserver zwei Cronjobs ein, die regelmäßig die Dateien <i>check_upload_edoo2anmeldung.sh</i> und <i>vorschaubilder.sh</i> ausführen.<br>
	Sie importieren die edoo.sys-Daten und generieren Vorschaubilder für eingereichte Nachweisdokumente.</li>
	</ol>


<p>Alle hier zum Download bereitgestellten Dateien dürfen beliebig angegepasst, weiterentwickelt und weitergereicht werden.
Die Verwendung erfolgt jedoch auf Ihre eingene Verantwortung. Wir übernehmen keine Haftung für durch deren Nutzung entstandener Schäden.</p>
