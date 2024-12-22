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

![systemskizze](https://github.com/user-attachments/assets/7dd94a88-8aa2-4066-8c28-7fc9a35485b2)
