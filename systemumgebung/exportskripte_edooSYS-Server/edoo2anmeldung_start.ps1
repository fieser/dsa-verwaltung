

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
$port = "6022"


$intern_www = "root@172.22.100.17:/var/www/html/verwaltung/daten/"

#Privater Schlüssel - mit PUTTYgen erzeugt:
$keyFile = "C:\service\edoo2ad\.ssh\key_www_verwaltung.ppk"
$timestampFile = "C:\service\edoo2ad\lastSync_www_verwaltung.txt"


############## ENDE BITTE ANPASSEN ####################






#Datenbankabfragen einbinden:

. "C:\service\edoo2ad\edoo2anmeldung_abfragen.ps1"




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

# Erstmalig, damit das Zertifikat akzeptiert wird, bitte in der Shell bitte folgendes ausführen:
# pscp -P 6022 -i C:\service\edoo2ad\.ssh\key_www_verwaltung.ppk -r C:\Service\edoo2ad\daten_server_verwaltung\ root@172.22.100.17:/var/www/html/verwaltung/daten/