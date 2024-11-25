Befehle zum Export der Datenbankstrukturen:

    mysqldump -u root -p --no-data anmeldung_temp_2526 > db_structure_verwaltung_temp.sql && sed -i.bak 's/CHARACTER SET utf8//g' db_structure_verwaltung_www.sql

    mysqldump -u root -p --no-data anmeldung_www_2526 > db_structure_verwaltung_www.sql && sed -i.bak 's/CHARACTER SET utf8//g' db_structure_verwaltung_www.sql
