#!/bin/bash

# Pfad zum Verzeichnis mit den PDF-Dateien
DIRECTORY="/var/www/html/verwaltung/dokumente/unpacked"

# Durchlaufen aller PDF-Dateien im Verzeichnis
for pdf in "$DIRECTORY"/*.pdf; do
    # Erstellen des Dateinamens für das Vorschaubild
    thumbnail="${pdf%.pdf}.jpg"

# Prüfen, ob das Thumbnail bereits existiert und gegebenenfalls löschen
#    if [ -f "$thumbnail" ]; then
#        echo "Das Vorschaubild $thumbnail existiert bereits und wird gelöscht."
#        rm "$thumbnail"
#    fi
	

    # Erstellen des Vorschaubildes der ersten Seite des PDFs
    convert -thumbnail x300 -background white -alpha remove "$pdf[0]" "$thumbnail"
done

/usr/bin/php /var/www/html/verwaltung/temp2db.php

