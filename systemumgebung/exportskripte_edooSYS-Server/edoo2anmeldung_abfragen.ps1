#Jahrgangsstufen
$ziel = $localDirectory + 'svp_wl_jahrgangsstufen.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT jg.id, jg.kurzform, jg.anzeigeform, jg.langform FROM asv.asv.svp_wl_jahrgangsstufe jg, asv.asv.svp_wl_jahrgangsstufe_schulart jgsa WHERE jg.id = jgsa.jahrgangsstufe_id AND jgsa.wl_schulart_asv_id = '1152_80') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"
#& 'C:\Program Files\PostgreSQL\10\bin\pg_dump.exe' -h localhost -U postgres -d asv -t asv.svp_wl_jahrgangsstufe -t asv.svp_wl_jahrgangsstufe_schulart --data-only --inserts --file='C:\Service\edoo2ad\daten\asv.svp_wl_jahrgangsstufen.sql'

#plz-Ort
$ziel = $localDirectory + 'svp_wl_ort_gemeinde.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT postleitzahl, ortsbezeichnung, id FROM asv.svp_wl_ort_gemeinde) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Ausbildungsberufe
$ziel = $localDirectory + 'svp_wl_ausbildungsberuf.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT schluessel, kurzform, anzeigeform, langform, id FROM asv.svp_wl_ausbildungsberuf) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Betriebe
$ziel = $localDirectory + 'svp_betrieb.csv'

& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (
    WITH RankedEntries AS (
        SELECT 
            btr.id AS betrieb_id,
            btr.kuerzel,
            btr.name1,
            btr.name2,
            a.strasse,
            a.nummer,
            a.postleitzahl,
            a.ortsbezeichnung,
            a.gemeinde_id,
            k.kommunikationsadresse,
            k.wl_kommunikationstyp_id,
            k.update_date,
            ROW_NUMBER() OVER (
                PARTITION BY btr.id, k.wl_kommunikationstyp_id
                ORDER BY k.update_date DESC
            ) AS rn
        FROM 
             asv.svp_betrieb btr
        JOIN asv.svp_anschrift a ON a.id = btr.anschrift_id
        JOIN asv.svp_kommunikation k ON a.id = k.anschrift_id
    )
    SELECT 
        betrieb_id,
        kuerzel,
        name1,
        name2,
        strasse,
        nummer,
        postleitzahl,
        ortsbezeichnung,
        gemeinde_id,
        kommunikationsadresse,
        wl_kommunikationstyp_id,
        update_date
    FROM RankedEntries
    WHERE rn = 1
    ORDER BY betrieb_id ASC, wl_kommunikationstyp_id ASC
) TO '$ziel' with (format csv, header false, delimiter ';', encoding 'UTF8');"


#Ausbilder
$ziel = $localDirectory + 'svp_ausbilder.csv'

& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (
WITH RankedEntries AS (
    SELECT 
        btr.id AS betrieb_id,
        btr.kuerzel,
        btr.name1,
        btr.name2,
        a.strasse,
        a.nummer,
        a.postleitzahl,
        a.ortsbezeichnung,
        a.gemeinde_id,
        per.familienname AS betreuer_familienname,
        per.vornamen AS betreuer_vornamen,
        k.kommunikationsadresse,
        k.wl_kommunikationstyp_id,
        k.update_date,
        ROW_NUMBER() OVER (
            PARTITION BY btr.id, k.wl_kommunikationstyp_id, k.kommunikationsadresse
            ORDER BY k.update_date DESC
        ) AS rn
    FROM 
         asv.svp_schueler_stamm s
    JOIN asv.svp_schueler_schuljahr ssj ON s.id = ssj.schueler_stamm_id
    JOIN asv.svp_ausbildung au ON ssj.id = au.schueler_schuljahr_id
    JOIN asv.svp_betrieb_beruf_person bbp ON bbp.id = au.betrieb_beruf_person_id
    JOIN asv.svp_schueler_anschrift sa ON s.id = sa.schueler_stamm_id
    JOIN asv.svp_person_kommunikation pk ON bbp.person_id = pk.person_id
    JOIN asv.svp_kommunikation k ON pk.kommunikation_id = k.id
    JOIN asv.svp_betrieb btr ON bbp.betrieb_id = btr.id
    JOIN asv.svp_anschrift a ON btr.anschrift_id = a.id
    JOIN asv.svp_person per ON bbp.person_id = per.id
    WHERE 
        a.postleitzahl != '' 
        AND k.wl_kommunikationstyp_id IN ('1113_04', '1113_05', '1113_01', '1113_03')
)
SELECT 
    betrieb_id,
    kuerzel,
    name1,
    name2,
    strasse,
    nummer,
    postleitzahl,
    ortsbezeichnung,
    gemeinde_id,
    betreuer_familienname,
    betreuer_vornamen,
    kommunikationsadresse,
    wl_kommunikationstyp_id,
    update_date
FROM RankedEntries
WHERE rn = 1
ORDER BY betrieb_id ASC, wl_kommunikationstyp_id ASC
) TO '$ziel' with (format csv, header false, delimiter ';', encoding 'UTF8');"

#Berufe, die tatsächlich zugeordnet wurden:
$ziel = $localDirectory + 'svp_berufe_ist.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT bg.ausbildungsberuf_id, ab.schluessel, ab.langform, bg.schueler_stamm_id FROM asv.svp_schueler_bildungsgang_ein_austritt bg, asv.svp_wl_ausbildungsberuf ab WHERE ab.id = bg.ausbildungsberuf_id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Berufe-Betrieb-Kombis:
$ziel = $localDirectory + 'svp_betrieb_berufe.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT bt.id, bt.kuerzel, bt.name1, bt.name2, ab.schluessel, ab.langform FROM asv.svp_betrieb bt, asv.svp_betrieb_beruf_person bbp, asv.svp_betrieb_beruf_person_ausbildungsberuf bbpa, asv.svp_wl_ausbildungsberuf ab WHERE ab.id = bbpa.ausbildungsberuf_id AND bbpa.betrieb_beruf_person_id = bbp.id AND bbp.betrieb_id = bt.id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Schüler-Klasse-Kombis:
$ziel = $localDirectory + 'svp_schueler_klasse.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT ssj.schueler_stamm_id, kl.klassenname, kg.kennung, ssj.schuljahr_id FROM asv.svp_schueler_schuljahr ssj, asv.svp_klassengruppe kg, asv.svp_klasse kl WHERE kg.id = ssj.klassengruppe_id AND kg.klasse_id = kl.id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"


#Staaten:
$ziel = $localDirectory + 'svp_staaten.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM asv.svp_wl_wert WHERE id LIKE '1166_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Sprachen:
$ziel = $localDirectory + 'svp_sprachen.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM asv.svp_wl_wert WHERE id LIKE '1186_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Schularten:
$ziel = $localDirectory + 'svp_schularten.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM asv.svp_wl_wert WHERE id LIKE '5018___' AND (schluessel = '15' OR schluessel = '24' OR schluessel = '31' OR schluessel = '35' OR schluessel = '36' OR schluessel = '37' OR schluessel = '70' OR schluessel = '80' OR schluessel = '90' OR schluessel = '99')) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Vorbildung:
$ziel = $localDirectory + 'svp_wl_vorbildung.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM asv.svp_wl_wert WHERE id LIKE '5020_%') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Art Sorgeberchtigte:
$ziel = $localDirectory + 'svp_sorge.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, schluessel, kurzform, anzeigeform, langform FROM asv.svp_wl_wert WHERE id LIKE '1135_%' AND id NOT LIKE '1135_99') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

#Datei Schülerdaten aus Schülermodul:
$ziel = $localDirectory + 'svp_dsa_schueler.csv'


& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (
    WITH neueste_bildungsgang AS (
    SELECT
        bg1.*
    FROM
        asv.svp_schueler_bildungsgang_ein_austritt bg1
    WHERE
        bg1.create_date = (
            SELECT
                MAX(bg2.create_date)
            FROM
                asv.svp_schueler_bildungsgang_ein_austritt bg2
            WHERE
                bg2.schueler_stamm_id = bg1.schueler_stamm_id
        )
        OR (bg1.update_date IS NOT NULL AND bg1.update_date = (
            SELECT
                MAX(bg3.update_date)
            FROM
                asv.svp_schueler_bildungsgang_ein_austritt bg3
            WHERE
                bg3.schueler_stamm_id = bg1.schueler_stamm_id
        ))
)
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
        s.create_user AS schueler_create_user,
        s.create_date AS schueler_create_date,
        s.update_user AS schueler_update_user,
        s.update_date AS schueler_update_date,
        bg.austrittsdatum,
        bg.bildungsgang_id,
        bg.eintrittsdatum,
        s.wl_geschlecht_id,
        s.geburtsort,
        bg.eintritt_schuljahr_id,
        bg.klassenname_statistik,
        bg.ausbildungsberuf_id,
        bg.wl_schulform_id,
        bg.create_date AS bg_create_date,
        bg.create_user AS bg_create_user,
        bg.update_date AS bg_update_date,
        bg.update_user AS bg_update_user,
        a.wl_anschriftstyp_id
    FROM
        asv.svp_schueler_stamm s
        JOIN asv.svp_schueler_anschrift sa ON s.id = sa.schueler_stamm_id
        JOIN asv.svp_anschrift a ON sa.anschrift_id = a.id
        LEFT JOIN asv.svp_schueler_kommunikation sk ON s.id = sk.schueler_stamm_id
        LEFT JOIN asv.svp_kommunikation k ON sk.kommunikation_id = k.id
        LEFT JOIN neueste_bildungsgang bg ON s.id = bg.schueler_stamm_id
    WHERE
        a.postleitzahl != ''
    GROUP BY
        s.id, s.familienname, s.vornamen, s.geburtsdatum, a.strasse, a.nummer,
        a.postleitzahl, a.ortsbezeichnung, a.wl_anschriftstyp_id, schueler_create_user, schueler_create_date,
        schueler_update_user, schueler_update_date, bg.austrittsdatum, bg.bildungsgang_id,
        bg.eintrittsdatum, bg.eintritt_schuljahr_id, bg.klassenname_statistik, bg.ausbildungsberuf_id,
        bg.wl_schulform_id, bg_create_date, bg_create_user, bg_update_date, bg_update_user,
        s.wl_geschlecht_id, s.geburtsort
) TO '$ziel' WITH (FORMAT csv, HEADER false, DELIMITER ';', ENCODING 'UTF8');"





#Nur SuS mit Betrieb:
$ziel = $localDirectory + 'svp_dsa_schueler_m_betrieb.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT s.id, s.familienname, s.vornamen, s.geburtsdatum, btr.id, s.geburtsort FROM asv.svp_schueler_stamm s, asv.svp_schueler_anschrift sa, asv.svp_anschrift a, asv.svp_kommunikation k, asv.svp_schueler_kommunikation sk, asv.svp_betrieb_beruf_person bbp, asv.svp_schueler_schuljahr ssj, asv.svp_ausbildung au, asv.svp_betrieb btr, asv.svp_schueler_bildungsgang_ein_austritt bg WHERE bbp.betrieb_id = btr.id AND s.id = ssj.schueler_stamm_id AND ssj.id = au.schueler_schuljahr_id AND bbp.id = au.betrieb_beruf_person_id AND s.id = bg.schueler_stamm_id AND s.id = sa.schueler_stamm_id AND sa.anschrift_id = a.id AND a.postleitzahl != '' AND s.id = sk.schueler_stamm_id AND sk.kommunikation_id = k.id AND k.wl_kommunikationstyp_id = '1113_04') TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"





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
        asv.svp_bewerber b
        LEFT JOIN asv.svp_bewerber_anschrift ba ON b.id = ba.bewerber_id
        LEFT JOIN asv.svp_anschrift a ON a.id = ba.anschrift_id
        LEFT JOIN asv.svp_bewerberziele bz ON b.id = bz.bewerber_id
        LEFT JOIN asv.svp_bewerbungsziel bze ON bz.bewerbungsziel_id = bze.id
    WHERE
        b.status_uebernahme = '0' OR b.status_uebernahme = '1'
    ORDER BY
        b.name, ba.reiter_nr, ba.wl_anschriftstyp_id
) TO '$ziel' WITH (FORMAT csv, HEADER false, DELIMITER ';', ENCODING 'UTF8');"






$ziel = $localDirectory + 'svp_dsa_bewerber_fremdsprachen.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT b.name, b.vornamen, b.geburtsdatum, b.geburtsort, bf.unterrichtsfach_id, bf.von_jahrgangsstufe_id, bf.bis_jahrgangsstufe_id FROM asv.svp_bewerber b, asv.svp_bewerber_fremdsprache bf WHERE b.id = bf.bewerber_id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"
$ziel = $localDirectory + 'svp_dsa_bewerber_kommunikation.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT DISTINCT b.name, b.vornamen, b.geburtsdatum, b.geburtsort, k.wl_kommunikationstyp_id, k.kommunikationsadresse FROM asv.svp_bewerber b, asv.svp_bewerber_kommunikation bk, asv.svp_kommunikation k WHERE b.id = bk.bewerber_id AND bk.kommunikation_id = k.id) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"


#Bewerbungsziele:
$ziel = $localDirectory + 'svp_bewerbungsziel.csv'
& 'C:\Program Files\PostgreSQL\10\bin\psql.exe' -h localhost -U postgres -d asv -c "\copy (SELECT id, kurzform, anzeigeform, bildungsgang_id FROM asv.svp_bewerbungsziel) TO '$ziel' with (format csv,header false, delimiter ';',encoding 'UTF8');"

