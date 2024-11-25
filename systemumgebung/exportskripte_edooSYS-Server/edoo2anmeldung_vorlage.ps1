

############### BITTE ANPASSEN ######################

Set-Location 'C:\Program Files\PostgreSQL\10\bin\';


#Passwort der edoo.sys-Datenbank:
$env:PGPASSWORD = 'GEHEIM';

#Verzeichnis auf edoo.sys-Server zur Zwischenspeicherung der aus edoo.sys exportierten Daten:
$localDirectory = "C:\Service\edoo2ad\daten_server_verwaltung\"

#Zielverzeichnis auf dem internen Webserver:
$remoteDirectory = "/var/www/html/verwaltung/daten"

#Daten für SCP-Verbindung zum internen Webserver:
$server = "172.22.100.17"
$username = "root"
$port = "22"

#Privater Schlüssel - mit PUTTYgen erzeugt:
$keyFile = "C:\service\edoo2ad\.ssh\key_www_verwaltung.ppk"
$timestampFile = "C:\service\edoo2ad\lastSync_www_verwaltung.txt"


############## ENDE BITTE ANPASSEN ####################


#Export Wertelisten:

#Jahrgangsstufen
$ziel = $localDirectory + 'svp_wl_jahrgangsstufen.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT jg.id, jg.kurzform, jg.anzeigeform, jg.langform FROM svp_wl_jahrgangsstufe jg, svp_wl_jahrgangsstufe_schulart jgsa WHERE jg.id = jgsa.jahrgangsstufe_id AND jgsa.wl_schulart_asv_id = '1152_80') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"
#& 'C:\Program Files\PostgreSQL\10\bin\pg_dump.exe' -h localhost -U postgres -d asv -t svp_wl_jahrgangsstufe -t svp_wl_jahrgangsstufe_schulart --data-only --inserts --file='C:\Service\edoo2ad\daten\svp_wl_jahrgangsstufen.sql'

#plz-Ort
$ziel = $localDirectory + 'svp_wl_ort_gemeinde.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT postleitzahl, ortsbezeichnung, id FROM svp_wl_ort_gemeinde) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Ausbildungsberufe
$ziel = $localDirectory + 'svp_wl_ausbildungsberuf.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT schluessel, kurzform, anzeigeform, langform, id FROM svp_wl_ausbildungsberuf) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Betriebe
$ziel = $localDirectory + 'svp_betrieb.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT id, kuerzel, name1, name2 FROM svp_betrieb) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Berufe, die tatsächlich zugeordnet wurden:
$ziel = $localDirectory + 'svp_berufe_ist.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT bg.ausbildungsberuf_id, ab.schluessel, ab.langform, bg.schueler_stamm_id FROM svp_schueler_bildungsgang_ein_austritt bg, svp_wl_ausbildungsberuf ab WHERE ab.id = bg.ausbildungsberuf_id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Berufe-Betrieb-Kombis:
$ziel = $localDirectory + 'svp_betrieb_berufe.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT bt.id, bt.kuerzel, bt.name1, bt.name2, ab.schluessel, ab.langform FROM svp_betrieb bt, svp_betrieb_beruf_person bbp, svp_betrieb_beruf_person_ausbildungsberuf bbpa, svp_wl_ausbildungsberuf ab WHERE ab.id = bbpa.ausbildungsberuf_id AND bbpa.betrieb_beruf_person_id = bbp.id AND bbp.betrieb_id = bt.id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"


#Staaten:
$ziel = $localDirectory + 'svp_staaten.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM svp_wl_wert WHERE id LIKE '1166_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Sprachen:
$ziel = $localDirectory + 'svp_sprachen.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM svp_wl_wert WHERE id LIKE '1186_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Schularten:
$ziel = $localDirectory + 'svp_schularten.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM svp_wl_wert WHERE id LIKE '5018___' AND (schluessel = '15' OR schluessel = '24' OR schluessel = '31' OR schluessel = '35' OR schluessel = '36' OR schluessel = '37' OR schluessel = '70' OR schluessel = '80' OR schluessel = '90' OR schluessel = '99')) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Vorbildung:
$ziel = $localDirectory + 'svp_wl_vorbildung.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM svp_wl_wert WHERE id LIKE '5020_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Art Sorgeberchtigte:
$ziel = $localDirectory + 'svp_sorge.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM svp_wl_wert WHERE id LIKE '1135_%' AND id NOT LIKE '1135_99') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Datei Schülerdaten aus Schülermodul:
$ziel = $localDirectory + 'svp_dsa_schueler.csv'

& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (
    SELECT DISTINCT
        s.id,
        s.familienname,
        s.vornamen,
        s.geburtsdatum,
        a.strasse,
        a.nummer,
        a.postleitzahl,
        a.ortsbezeichnung,
        MAX(CASE WHEN k.wl_kommunikationstyp_id = '1113_01' THEN k.kommunikationsadresse ELSE NULL END) AS adresse_typ_1113_01,
        MAX(CASE WHEN k.wl_kommunikationstyp_id = '1113_02' THEN k.kommunikationsadresse ELSE NULL END) AS adresse_typ_1113_02,
        MAX(CASE WHEN k.wl_kommunikationstyp_id = '1113_04' THEN k.kommunikationsadresse ELSE NULL END) AS adresse_typ_1113_04,
        s.create_user,
        s.create_date,
        s.update_user,
        s.update_date,
        bg.austrittsdatum,
        bg.bildungsgang_id,
        bg.eintrittsdatum,
        s.wl_geschlecht_id,
        s.geburtsort
    FROM
        svp_schueler_stamm s
        JOIN svp_schueler_anschrift sa ON s.id = sa.schueler_stamm_id
        JOIN svp_anschrift a ON sa.anschrift_id = a.id
        LEFT JOIN svp_schueler_kommunikation sk ON s.id = sk.schueler_stamm_id
        LEFT JOIN svp_kommunikation k ON sk.kommunikation_id = k.id
        JOIN svp_schueler_bildungsgang_ein_austritt bg ON s.id = bg.schueler_stamm_id
    WHERE
        a.postleitzahl != ''
    GROUP BY
        s.id, s.familienname, s.vornamen, s.geburtsdatum, a.strasse, a.nummer,
        a.postleitzahl, a.ortsbezeichnung, s.create_user, s.create_date,
        s.update_user, s.update_date, bg.austrittsdatum, bg.bildungsgang_id,
        bg.eintrittsdatum, s.wl_geschlecht_id, s.geburtsort
) TO '$ziel' WITH (FORMAT csv, HEADER false, DELIMITER ';', ENCODING 'UTF8');"





#Nur SuS mit Betrieb:
$ziel = $localDirectory + 'svp_dsa_schueler_m_betrieb.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT s.id, s.familienname, s.vornamen, s.geburtsdatum, btr.id, s.geburtsort FROM svp_schueler_stamm s, svp_schueler_anschrift sa, svp_anschrift a, svp_kommunikation k, svp_schueler_kommunikation sk, svp_betrieb_beruf_person bbp, svp_schueler_schuljahr ssj, svp_ausbildung au, svp_betrieb btr, svp_schueler_bildungsgang_ein_austritt bg WHERE bbp.betrieb_id = btr.id AND s.id = ssj.schueler_stamm_id AND ssj.id = au.schueler_schuljahr_id AND bbp.id = au.betrieb_beruf_person_id AND s.id = bg.schueler_stamm_id AND s.id = sa.schueler_stamm_id AND sa.anschrift_id = a.id AND a.postleitzahl != '' AND s.id = sk.schueler_stamm_id AND sk.kommunikation_id = k.id AND k.wl_kommunikationstyp_id = '1113_04') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"





$ziel = $localDirectory + 'svp_dsa_bewerber.csv'

& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (
    SELECT DISTINCT
        b.name,
        b.vornamen,
        b.geburtsdatum,
        b.geburtsort,
        b.wl_geburtsland_id,
        b.wl_staatsangehoerigkeit_id,
        b.wl_herkunftsland_id,
        b.zuzugsdatum,
        b.wl_geschlecht_id,
        b.wl_religionszugehoerigkeit_id,
        b.religion_ethik_id,
        b.wl_schulabschluss_id,
        b.wl_berufsabschluss_id,
        b.entscheidung,
        b.create_user,
        b.create_date,
        b.update_user,
        b.update_date,
        a.strasse,
        a.nummer,
        a.postleitzahl,
        a.ortsbezeichnung,
        bze.bildungsgang_id,
        ba.wl_anschriftstyp_id,
        ba.wl_personentyp_id,
        ba.reiter_nr,
        b.schueler_stamm_id,
        b.status_uebernahme
    FROM
        svp_bewerber b
        LEFT JOIN svp_bewerber_anschrift ba ON b.id = ba.bewerber_id
        LEFT JOIN svp_anschrift a ON a.id = ba.anschrift_id
        LEFT JOIN svp_bewerberziele bz ON b.id = bz.bewerber_id
        LEFT JOIN svp_bewerbungsziel bze ON bz.bewerbungsziel_id = bze.id
    WHERE
        b.status_uebernahme = '0' OR b.status_uebernahme = '1'
    ORDER BY
        b.name, ba.reiter_nr, ba.wl_anschriftstyp_id
) TO '$ziel' WITH (FORMAT csv, HEADER false, DELIMITER ';', ENCODING 'UTF8');"



#Starten des Uploadskripts:



# Check if timestamp file exists
if (!(Test-Path $timestampFile)) {
    # If it doesn't exist, create it and set the current date/time as the initial timestamp
    New-Item -ItemType File -Path $timestampFile
    # save current timestamp as string in the file
    (Get-Date).ToString() | Out-File -FilePath $timestampFile
}

# Read the timestamp from the file
$lastSync = Get-Content $timestampFile

# Convert the timestamp string to a DateTime object
$lastSync = [datetime]::ParseExact($lastSync, "dd/MM/yyyy HH:mm:ss", $null)

# Get the most recent file in the local directory
$mostRecentFile = Get-ChildItem $localDirectory | Sort-Object LastWriteTime -Descending | Select-Object -First 1

# Compare the last write time of the most recent file to the timestamp
if ($mostRecentFile.LastWriteTime -gt $lastSync) {
    'Daten werden auf Webserver Verwaltung kopiert!'
    # If the file has been modified more recently than the timestamp, copy the files

    pscp -P $port -i $keyFile -r $localDirectory $intern_www
    
    # Update the timestamp in the file
    (Get-Date).ToString() | Out-File -FilePath $timestampFile
} else {
'Keine neuen Daten, die kopiert werden müssten.'
}