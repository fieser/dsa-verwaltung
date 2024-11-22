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