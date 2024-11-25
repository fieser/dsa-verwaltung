#!/bin/bash

# Verzeichnis, das überprüft werden soll
dir="/var/www/html/verwaltung/daten/"

    /usr/bin/php /var/www/html/verwaltung/import_edoo.php &&
	/usr/bin/php /var/www/html/verwaltung/fehler_ermitteln.php
	/usr/bin/php /var/www/html/verwaltung/import_wl.php


