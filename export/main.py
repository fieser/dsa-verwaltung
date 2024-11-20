# codong: utf8



import csv
import xlrd
from xlutils.copy import copy
import os

# Dateinamen und Pfad definieren
csv_file = '".$pfad_workdir."export/csv2edoo.csv'
xls_file = '".$pfad_workdir."export/Bewerber_Import_leer.xls'
xls_file_neu = '".$pfad_workdir."export/Bewerber_Import.xls'

# Workbook aus der Excel-Datei öffnen
workbook = xlrd.open_workbook(xls_file, formatting_info=True)
new_workbook = copy(workbook)

# Worksheet 'Bewerber' auswählen
worksheet = new_workbook.get_sheet(0)
worksheet2 = new_workbook.get_sheet(1)
worksheet3 = new_workbook.get_sheet(2)

# CSV-Datei öffnen
with open(csv_file, 'r', encoding='utf-8') as file:
    # CSV-Datei als DictReader laden
    reader = csv.DictReader(file, delimiter=';')

    # Zeilennummer für das Worksheet initialisieren
    row_number = 3

    # Durch die Zeilen des DictReaders iterieren
    for row in reader:
        # Wert aus Spalte 'lastnameStudent' auslesen
        nummer = row['nummer']
        nachname = row['nachname']
        vorname = row['vorname']
        geschlecht = row['geschlecht']
        geburtsdatum = row['geburtsdatum']
        geburtsort = row['geburtsort']
        religion = row['religion']
        tn_religion = "KE"
        staatsangehoerigkeit = row['staatsangehoerigkeit']
        geburtsland = row['geburtsland']
        muttersprache = row['muttersprache']
        zuzug = row['zuzug']
        sorge1_nachname = row['sorge1_nachname']
        sorge1_vorname = row['sorge1_vorname']
        sorge1_strasse = row['sorge1_strasse']
        sorge1_hausnummer = row['sorge1_hausnummer']
        sorge1_plz = row['sorge1_plz']
        sorge1_wohnort = row['sorge1_wohnort']
        sorge1_telefon1 = row['sorge1_telefon1']
        sorge1_telefon2 = row['sorge1_telefon2']
        sorge1_mail = row['sorge1_mail']

        sorge2_nachname = row['sorge2_nachname']
        sorge2_vorname = row['sorge2_vorname']
        sorge2_strasse = row['sorge2_strasse']
        sorge2_hausnummer = row['sorge2_hausnummer']
        sorge2_plz = row['sorge2_plz']
        sorge2_wohnort = row['sorge2_wohnort']
        sorge2_telefon1 = row['sorge2_telefon1']
        sorge2_telefon2 = row['sorge2_telefon2']
        sorge2_mail = row['sorge2_mail']

        strasse = row['strasse']
        hausnummer = row['hausnummer']
        telefon1 = row['telefon1']
        telefon2 = row['telefon2']
        mail = row['mail']
        jahrgang = row['jahrgang']
        abschluss = row['abschluss']

        fs1 = row['fs1']
        fs1_von = row['fs1_von']
        fs1_bis = row['fs1_bis']

        fs2 = row['fs2']
        fs2_von = row['fs2_von']
        fs2_bis = row['fs2_bis']

        fs3 = row['fs3']
        fs3_von = row['fs3_von']
        fs3_bis = row['fs3_bis']

        bgy_sp1 = row['bgy_sp1']
        bgy_sp2 = row['bgy_sp2']
        bgy_sp3 = row['bgy_sp3']
        bgy_sp4 = row['bgy_sp4']
        bgy_sp5 = row['bgy_sp5']


        # Straße und Hausnummer trennen:
            #last_space_index = strasse_hnr.rfind(" ")
            #strasse = strasse_hnr[:last_space_index]
            #hausnummer = strasse_hnr[last_space_index + 1:]

        plz = row['plz']
        wohnort = row['wohnort']
        schulart = row['schulart']

        # Wert in nächste freie Zeile des Worksheet 'Bewerber' schreiben
        worksheet.write(row_number, 0, nummer)
        worksheet.write(row_number, 1, nachname)
        worksheet.write(row_number, 2, vorname)
        worksheet.write(row_number, 3, geschlecht)
        worksheet.write(row_number, 4, geburtsdatum)
        worksheet.write(row_number, 5, geburtsort)
        worksheet.write(row_number, 6, religion)
        worksheet.write(row_number, 7, tn_religion)
        worksheet.write(row_number, 8, staatsangehoerigkeit)
        worksheet.write(row_number, 9, geburtsland)
        worksheet.write(row_number, 10, muttersprache)
        worksheet.write(row_number, 11, zuzug)
        worksheet.write(row_number, 14, sorge1_nachname)
        worksheet.write(row_number, 15, sorge1_vorname)
        worksheet.write(row_number, 16, sorge1_strasse)
        worksheet.write(row_number, 17, sorge1_hausnummer)
        worksheet.write(row_number, 18, sorge1_plz)
        worksheet.write(row_number, 19, sorge1_wohnort)
        worksheet.write(row_number, 21, sorge1_telefon1)
        worksheet.write(row_number, 22, sorge1_telefon2)
        worksheet.write(row_number, 23, sorge1_mail)
        worksheet.write(row_number, 24, sorge2_nachname)
        worksheet.write(row_number, 25, sorge2_vorname)
        worksheet.write(row_number, 26, sorge2_strasse)
        worksheet.write(row_number, 27, sorge2_hausnummer)
        worksheet.write(row_number, 28, sorge2_plz)
        worksheet.write(row_number, 29, sorge2_wohnort)
        worksheet.write(row_number, 31, sorge2_telefon1)
        worksheet.write(row_number, 32, sorge2_telefon2)
        worksheet.write(row_number, 33, sorge2_mail)
        worksheet.write(row_number, 34, strasse)
        worksheet.write(row_number, 35, hausnummer)
        worksheet.write(row_number, 36, plz)
        worksheet.write(row_number, 37, wohnort)
        worksheet.write(row_number, 39, telefon1)
        worksheet.write(row_number, 40, telefon2)
        worksheet.write(row_number, 41, mail)
        worksheet.write(row_number, 43, schulart)
        worksheet.write(row_number, 45, jahrgang)

        worksheet2.write((row_number -2), 0, nummer)
        worksheet2.write((row_number -2), 1, fs1)
        worksheet2.write((row_number - 2), 2, fs1_von)
        worksheet2.write((row_number - 2), 3, fs1_bis)
        worksheet2.write((row_number - 2), 4, fs2)
        worksheet2.write((row_number - 2), 5, fs2_von)
        worksheet2.write((row_number - 2), 6, fs2_bis)
        worksheet2.write((row_number - 2), 7, fs3)
        worksheet2.write((row_number - 2), 8, fs3_von)
        worksheet2.write((row_number - 2), 9, fs3_bis)

        worksheet3.write((row_number - 2), 0, nummer)
        worksheet3.write((row_number - 2), 1, bgy_sp1)
        worksheet3.write((row_number - 2), 2, bgy_sp2)
        worksheet3.write((row_number - 2), 3, bgy_sp3)
        worksheet3.write((row_number - 2), 4, bgy_sp4)
        worksheet3.write((row_number - 2), 5, bgy_sp5)

        # Zeilennummer um eins erhöhen
        row_number += 1

# Workbook speichern
new_workbook.save(xls_file_neu)

try:
    os.remove(csv_file)
    print(f'Datei "{csv_file}" wurde erfolgreich gelöscht.')
except OSError as e:
    # Fehlerbehandlung, falls das Löschen fehlschlägt
    print(f'Fehler beim Löschen der Datei "{csv_file}": {e}')
