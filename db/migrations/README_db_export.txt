Befehle zum Export der Datenbankstrukturen:

    mysqldump -u root -p --no-data anmeldung_temp_2526 > db_structure_verwaltung_temp.sql && sed -i.bak -e 's/ COLLATE utf8_bin DEFAULT NULL//g' -e 's/CHARACTER SET utf8//g' -e 's/ COLLATE utf8_bin NOT NULL//g' -e 's/ COLLATE=utf8_bin//g' db_structure_verwaltung_temp.sql


    mysqldump -u root -p --no-data anmeldung_www_2526 > db_structure_verwaltung_www.sql && sed -i.bak -e 's/ COLLATE utf8_bin DEFAULT NULL//g' -e 's/CHARACTER SET utf8//g' -e 's/ COLLATE utf8_bin NOT NULL//g' -e 's/ COLLATE=utf8_bin//g' db_structure_verwaltung_www.sql


    mysqldump -u root -p anmeldung_temp_2526 senden_texte > tb_inhalt_verwaltung_temp_senden_texte.sql

    mysqldump -u root -p anmeldung_temp_2526 schulformen > tb_inhalt_verwaltung_temp_schulformen.sql

