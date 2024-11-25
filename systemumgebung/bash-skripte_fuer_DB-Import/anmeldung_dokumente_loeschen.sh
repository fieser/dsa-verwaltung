#!/bin/bash

# Pfad zum Zielverzeichnis definieren
DIRECTORY="/var/www/html/anmeldung/dokumente/"

# Sicherstellen, dass das Verzeichnis nicht leer ist, um irrtümliche Nutzung des rm-Befehls zu vermeiden
if [ -d "$DIRECTORY" ]; then
    # Löschen aller Dateien im Verzeichnis ohne Rückfragen
    rm -rf "$DIRECTORY"/*
else
    echo "Das angegebene Verzeichnis existiert nicht."
fi

