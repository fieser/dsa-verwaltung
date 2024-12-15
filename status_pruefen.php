<?php
ini_set('max_execution_time', 300); // 300 Sekunden = 5 Minuten
set_time_limit(0);
ignore_user_abort(true); //auch wenn der Nutzer die Sete verlässt, läuft das Skript weiter

$start = microtime(true);



date_default_timezone_set('Europe/Berlin');



if (!isset($pfad_workdir)) {
	$pfad_workdir = "/var/www/html/verwaltung/";
}

include($pfad_workdir."kopf.php");


include($pfad_workdir."login_inc.php");
@session_start();




	
include($pfad_workdir."config.php");


// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {



$db->query("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

		$select_an = $db->query("SELECT DISTINCT dsa_bewerberdaten.*, dsa_bildungsgang.* FROM dsa_bewerberdaten LEFT JOIN dsa_bildungsgang ON dsa_bildungsgang.md5 = dsa_bewerberdaten.md5");

        foreach($select_an as $an) {
            
            $klasse = "";
            $id = trim($an['0']); //Bewer"berdaten
            $id_bil = trim($an['id']); //Bildungsgang
            $md5_bew = trim($an['md5']); //Bewerberdaten md5
            $md5_bil = trim($an['49']); //Bildungsgang md5
            $nachname = trim($an['nachname']);
            $vorname = trim($an['vorname']);
            $geburtsdatum = trim($an['geburtsdatum']);
            
            //Merker für vollständige Unterlagen zurücksetzen:
            $temp_status_voll = 0;



                //Status bei Ankunft neuer Anmeldung anpassen.
                if (($an['status'] == "" OR $an['status'] == "gesendet") AND $an['schulform'] != "bs") {
                        if ($db->exec("UPDATE `dsa_bewerberdaten`
                                           SET
                                            `status` = 'unvollständig' WHERE `id` = '$id'")) { 
                                            
                                                
                                            }
                }
                
                if ($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "dbos" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") {
                    //Status bei vollständigen Unterlagen:
                    $select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND dok_lebenslauf = '1' AND dok_ausweis = '1' AND dok_zeugnis = '1' AND dok_erfahrung = '1'");	
                    $treffer_vo = $select_vo->rowCount();
                    if ($treffer_vo > 0) {
                            if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'vollständig' WHERE `id` = '$id'")) { 
                                                
                                                    
                                                }			
                                                $temp_status_voll = 1;
                    } else {
                        if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'unvollständig' WHERE `id` = '$id'")) { 
                                                
                                                        
                                                }		
                        
                        
                    }
                }
                
                if ($an['schulform'] == "bgy" OR $an['schulform'] == "bvj" OR $an['schulform'] == "bf1" OR $an['schulform'] == "bf2" OR $an['schulform'] == "bfp" OR $an['schulform'] == "aph" OR $an['schulform'] == "hbf") {
                    //Status bei vollständigen Unterlagen:
                    $select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND dok_lebenslauf = '1' AND dok_ausweis = '1' AND dok_zeugnis = '1'");	
                    $treffer_vo = $select_vo->rowCount();
                    if ($treffer_vo > 0) {
                            if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'vollständig' WHERE `id` = '$id'")) { 
                                                
                                                        
                                                }		
                                                $temp_status_voll = 1;
                    } else {
                        if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'unvollständig' WHERE `id` = '$id'")) { 
                                                
                                                        
                                                }		
                        
                        
                    }
                }
                
                if ($an['schulform'] == "bs") {
                
                        if ($db->exec("UPDATE `dsa_bewerberdaten`
                                           SET
                                            `status` = 'vollständig' WHERE `id` = '$id'")) { 
                                            
                                                
                                            }		
                                            $temp_status_voll = 1;	
                    
                } 
                
                if ($temp_status_voll != 1) {
                    //Status bei Bearbeitung der Infos zu Dokomenten anpassen:
                    $select_vo = $db->query("SELECT id FROM vorgang WHERE id_dsa_bewerberdaten = '$id' AND (dok_lebenslauf = '1' OR dok_ausweis = '1' OR dok_zeugnis = '1' OR dok_erfahrung = '1')");	
                    $treffer_vo = $select_vo->rowCount();
                    
                    if ((($an['schulform'] == "bos1" OR $an['schulform'] == "bos2" OR $an['schulform'] == "fs" OR $an['schulform'] == "fsof") AND ($dok_lebenslauf + $dok_ausweis + $dok_erfahrung + $do_zeugnis) == 4) OR (($an['schulform'] != "bos1" OR $an['schulform'] != "bos2" OR $an['schulform'] != "fs" OR $an['schulform'] != "fsof") AND ($dok_lebenslauf + $dok_ausweis + $do_zeugnis) == 3)) {
                    //if ($treffer_vo > 0) {
                        
                        
                        
                            if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'wird bearbeitet' WHERE `id` = '$id'")) { 
                                                
                                                    
                                                }			
                    }
                }
                
                //Abgleich mit edoo.sys:
                $geburtsdatum = trim($an['geburtsdatum']);
                $nachname = trim($an['nachname']);
                $vorname = trim($an['vorname']);
                $geburtsort = trim($an['geburtsort']);
        
                // Datum aufsplitten
                list($jahr, $monat, $tag) = explode('-', $geburtsdatum);
                $vg_monat = "-".$monat."-";
                $vg_jahr = $jahr."-";
                $vg_tag = "-".$tag;
        
                //Für die Nachnamen:
                $namensteile = explode(' ', $nachname); // Beispiel: "Maximilian Mustermann" → ['Maximilian', 'Mustermann']
                $whereClauses = [];
                foreach ($namensteile as $teil) {
                    $whereClauses[] = "(nachname LIKE '%$teil%' OR vorname LIKE '%$teil%')";
                }
        
                $whereSQL = implode(' OR ', $whereClauses);
        
                //Für die Vornamen:
                $namensteile_V = explode(' ', $vorname); // Beispiel: "Maximilian Mustermann" → ['Maximilian', 'Mustermann']
                $whereClauses_v = [];
                foreach ($namensteile_V as $teil_v) {
                    $whereClauses_v[] = "(nachname LIKE '%$teil_v%' OR vorname LIKE '%$teil_v%')";
                }
        
                $whereSQL_v = implode(' OR ', $whereClauses_v);
                //echo $whereSQL;
            
                //$select_edoo = $db_www->query("SELECT id FROM edoo_bewerber WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND geburtsort = '$geburtsort' AND status_uebernahme = '0'");	
                $select_edoo = $db_www->query("SELECT id FROM edoo_bewerber 
                    WHERE (geburtsdatum = '$geburtsdatum' 
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '{$vg_jahr}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '{$vg_jahr}%')
                    )
                    AND (nachname = '$nachname' OR $whereSQL)
                    AND (vorname = '$vorname' OR $whereSQL_v) 
                    AND status_uebernahme = '0'");
                $treffer_edoo = $select_edoo->rowCount();
                
                if ($treffer_edoo > 0 AND $temp_status_voll == 1) {
                            if ($db->exec("UPDATE `dsa_bewerberdaten`
                                               SET
                                                `status` = 'übertragen' WHERE `id` = '$id'")) { 
                                                
                                                    
                                                }
                    } else { //Falls ergebnislos, Schülerdaten durchsuchen:
                
            
        
                    //Ohne Austritt:
                    $select_edoo = $db_www->query("SELECT id, klasse FROM edoo_schueler 
                    WHERE (geburtsdatum = '$geburtsdatum' 
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '{$vg_jahr}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '{$vg_jahr}%')
                    )
                    AND (nachname = '$nachname' OR $whereSQL) 
                    AND (vorname = '$vorname' OR $whereSQL_v)
                    ");
                    $treffer_edoo = $select_edoo->rowCount();
                    //echo $an['nachname']." ".$temp_status_voll." ".$treffer_edoo."<br>";
                    if ($treffer_edoo > 0 AND trim($temp_status_voll) == 1) {
                        //echo $an['nachname']."<br>";
                                if ($db->exec("UPDATE `dsa_bewerberdaten`
                                                   SET
                                                    `status` = 'übertragen' WHERE `id` = '$id'")) {
                                                    
                                                        
                                                    }
                                
                                //Anzeige Klasse vorbereiten:
                                foreach($select_edoo as $ed) {
                                $klasse = $ed['klasse'];
                                }
                    }
                                
                    
                    //Mit Austritt:
                    //$select_edoo_a = $db_www->query("SELECT id FROM edoo_schueler WHERE geburtsdatum = '$geburtsdatum' AND nachname = '$nachname' AND geburtsort = '$geburtsort' AND austritt > '2000-01-01'");	
                    $select_edoo_a = $db_www->query("SELECT id FROM edoo_schueler 
                    WHERE (geburtsdatum = '$geburtsdatum' 
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '{$vg_jahr}%' AND geburtsdatum LIKE '%{$vg_tag}')
                    OR (geburtsdatum LIKE '%{$vg_monat}%' AND geburtsdatum LIKE '{$vg_jahr}%')
                    )
                    AND (nachname = '$nachname' OR $whereSQL) 
                    AND (vorname = '$vorname' OR $whereSQL_v)
                    AND austritt > '2000-01-01'");
                    
                    $treffer_edoo_a = $select_edoo_a->rowCount();
                    
                        if ($treffer_edoo_a > 0 AND $temp_status_voll == 1) {
                            //echo $an['nachname']."<br>";
                                if ($db->exec("UPDATE `dsa_bewerberdaten`
                                                   SET
                                                    `status` = 'reaktivierbar' WHERE `id` = '$id'")) { 
                                                    
                                                        
                                                    }
        
                                                    
                        }
        
                    }
                    

        }

echo "Status aktualisiert!";


} //Ende, wenn username
?>

<p>
    <form method="post" action="./index.php">
        <input style='margin-top: 3em;' type="submit" class='btn btn-default btn-sm' name="cmd[doStandardAuthentication]" value="zurück" />
    </form>
</p>

<?php

include($pfad_workdir."fuss.php");
?>