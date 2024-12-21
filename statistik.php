<?php

include("./kopf.php");


date_default_timezone_set('Europe/Berlin');


include("./login_inc.php");
@session_start();



$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];



include("./config.php");




// Ist Nutzer angemeldet?
if (isset($_SESSION['username'])) {
	
	// Admin- und Sekretariats-Rechte:
	include "./rechte.php";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerberdaten</title>
    <style>


.box-grau {
   padding: 10px;
   background-color: #E0E0E0;
   margin-bottom: 20px;
}

* {
  box-sizing: border-box;
}

.flex-container {
  display: flex;
  flex-direction: row;
  text-align: center;
}
      .hidden {
            display: none;
        }  
		
.flex-item-left {
  padding: 10px;
  flex: 50%;
  text-align: left;
}

.flex-item-right {
  padding: 10px;
  flex: 50%;
  text-align: left;
}

.flex-item-drei {
  padding: 10px;
  flex: 33.3%;
  text-align: left;
}

/* Responsive layout - makes a one column-layout instead of two-column layout */
@media (max-width: 800px) {
  .flex-container {
    flex-direction: column;
  }
  

}
</style>
</head>
<body>
<?php
    echo "<h1 style='margin-bottom: 2em;'>Anmeldungen ".$_SESSION['schuljahr']." im Vergleich zum Vorjahr</h1>";


if (!isset($_GET['id']) AND !isset($_POST['id'])) {
echo "<table><tr>";
echo "<td>";
echo "<form method='post' action='./index.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='Menü' />";
echo "</form>";
echo "</td>";
echo "<td width='20px'>";
echo "</td>";
echo "<td>";
echo "<p style='margin-top: 0px;'>Es werden ausschließlich Anmeldungen für das Schuljahr ".$_SESSION['schuljahr']." berücksichtigt.</p>";
echo "<p>Klicken Sie auf die Legende, um Schulformen aus- und einzublenden.</p>";
echo "</td>";
echo "</table></tr>";
} else {
echo "<table><tr>";
echo "<td>";
echo "<form method='post' action='./liste.php'>";
echo "<input style='width: 10em; margin-bottom: 2em;' class='btn btn-default btn-sm' type='submit' name='cmd[doStandardAuthentication]' value='zurück' />";
echo "</form>";
echo "</td>";
echo "<td width='20px'>";
echo "</td>";
echo "</table></tr>";


}



//$select_bg = $db->query("SELECT time, schulform FROM dsa_bildungsgang WHERE (prio = '1' OR prio = '')");	
//$treffer_bg = $select_bg->rowCount();



$neujahr = strtotime(date('Year')."-01-01");



// Datenbankabfrage
$query = "SELECT DISTINCT bg.time, bg.schulform FROM dsa_bildungsgang bg, dsa_bewerberdaten bw WHERE bg.md5 = bw.md5 AND bw.papierkorb NOT LIKE 1 AND (bg.time >= '$neujahr' AND (bg.prio <= 1) AND (bg.schulform NOT LIKE 'bs' OR bg.beginn > '2024-07-31'))";
$stmt = $db->query($query);



// START Vorjahr
$query_vj = "SELECT DISTINCT bg.time, bg.schulform FROM dsa_bildungsgang bg, dsa_bewerberdaten bw WHERE bg.md5 = bw.md5 AND bw.papierkorb NOT LIKE 1 AND (bg.time < '$neujahr' AND (bg.prio <= 1) AND (bg.schulform NOT LIKE 'bs' OR bg.beginn > '2024-07-31'))";
//$query_vj = "SELECT time, schulform FROM dsa_bildungsgang WHERE (time < '$neujahr' AND (prio = '1' OR prio = ''))";
$stmt_vj = $db->query($query_vj);

// Daten aufbereiten
$data_vj = [];
while ($row_vj = $stmt_vj->fetch(PDO::FETCH_ASSOC)) {
    $week_vj = date("W", $row_vj['time']);
    $schulform_vj = $row_vj['schulform'];
	/*
		echo "<pre>";
	print_r ( $row );
	echo "</pre>";
*/
    if (!isset($data_vj[$schulform_vj])) {
        $data_vj[$schulform_vj] = [];
    }
    if (!isset($data_vj[$schulform_vj][$week_vj])) {
        $data_vj[$schulform_vj][$week_vj] = 0;
    }


    $data_vj[$schulform_vj][$week_vj]++;
}

// Daten für das Diagramm vorbereiten
$chartData_vj = [];
foreach ($data_vj as $schulform_vj => $weeks_vj) {
    ksort($weeks_vj); // Sortiert nach Wochen
    //$cumulativeSum = 0;
    foreach ($weeks_vj as $week_vj => $count_vj) {
        $summe[$schulform_vj] = ($summe[$schulform_vj] + $count_vj);
        //$chartData[$schulform_vj][] = ['x' => $week_vj, 'y' => $cumulativeSum];
    }
}

//ENDE Vorjahr


// Daten aufbereiten
$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	
	
    $week = intval(date("W", $row['time']));
	
    $schulform = $row['schulform'];
	/*
		echo "<pre>";
	print_r ( $row );
	echo "</pre>";
*/
    if (!isset($data[$schulform])) {
        $data[$schulform] = [];
    }

    if (!isset($data[$schulform][$week])) {
        $data[$schulform][$week] = 0;
    }

    $data[$schulform][$week]++;
}


// Funktion zur Erstellung der gewünschten Wochenreihenfolge
function erstelleWochenReihenfolge() {
    $wochen = array_merge(range(1, (intval(date("W", time()) - 1))), range(1, (intval(date("W", time()) - 1))));

//$wochen = array_merge(range(1, 12), range(1, 12));
	//echo date("W", time())."<br>";
    return $wochen;
	
}


$gewuenschteWochen = erstelleWochenReihenfolge();


//Start Vorjahrsvergleich:

// Vorjahresdaten abfragen
$neujahr_vj = ($neujahr - 3600*24*365);

$next_weeks = (time() - 3600*24*365 + 3600*24*7*2);

$query_vorjahr = "SELECT time, schulform FROM dsa_bildungsgang WHERE (time >= '$neujahr_vj' AND time <= '$next_weeks')";
$stmt_vorjahr = $db_vj->query($query_vorjahr);

// START Vor_Vorjahr

$query_vj2 = "SELECT time, schulform FROM dsa_bildungsgang WHERE (time < '$neujahr_vj')";
$stmt_vj2 = $db_vj->query($query_vj2);

// Daten aufbereiten
$data_vj2 = [];
while ($row_vj2 = $stmt_vj2->fetch(PDO::FETCH_ASSOC)) {
    $week_vj2 = date("W", intval($row_vj2['time']));
    $schulform_vj2 = trim($row_vj2['schulform']);
	/*
		echo "<pre>";
	print_r ( $row_vj2 );
	echo "</pre>";
*/
    if (!isset($data_vj[$schulform_vj2])) {
        $data_vj2[$schulform_vj2] = [];
    }

    if (!isset($data_vj2[$schulform_vj2][$week_vj2])) {
        $data_vj2[$schulform_vj2][$week_vj2] = 0;
    }

    $data_vj2[$schulform_vj2][$week_vj2]++;
}

// Daten für das Diagramm vorbereiten
$chartData_vj2 = [];
foreach ($data_vj2 as $schulform_vj2 => $weeks_vj2) {
    ksort($weeks_vj); // Sortiert nach Wochen
    //$cumulativeSum = 0;
    foreach ($weeks_vj2 as $week_vj2 => $count_vj2) {
        $summe2[$schulform_vj2] = $summe2[intval($schulform_vj2) + $count_vj2];
        //$chartData[$schulform_vj][] = ['x' => $week_vj, 'y' => $cumulativeSum];
    }
}

//ENDE VOR_Vorjahr

// Daten des Vorjahres aufbereiten
$data_vorjahr = [];
while ($row_vorjahr = $stmt_vorjahr->fetch(PDO::FETCH_ASSOC)) {
    $week_vorjahr = date("W", $row_vorjahr['time']);
    $schulform_vorjahr = trim($row_vorjahr['schulform']);

    if (!isset($data_vorjahr[$schulform_vorjahr])) {
        $data_vorjahr[$schulform_vorjahr] = [];
    }

    if (!isset($data_vorjahr[$schulform_vorjahr][$week_vorjahr])) {
        $data_vorjahr[$schulform_vorjahr][$week_vorjahr] = 0;
    }

    $data_vorjahr[$schulform_vorjahr][$week_vorjahr]++;
}

	/*
		echo "<pre>";
	print_r ( $summe2 );
	echo "</pre>";
*/

// Daten des Vorjahres für das Diagramm vorbereiten
$chartData_vorjahr = [];
foreach ($data_vorjahr as $schulform_vorjahr => $weeks_vorjahr) {
    ksort($weeks_vorjahr); // Sortiert nach Wochen
    $cumulativeSum_vorjahr = $summe2[$schulform];
    foreach ($weeks_vorjahr as $week_vorjahr => $count_vorjahr) {
        $cumulativeSum_vorjahr += $count_vorjahr;
        $chartData_vorjahr[$schulform_vorjahr][] = ['x' => trim($week_vorjahr), 'y' => trim($cumulativeSum_vorjahr)];
    }
}
/*
$chartData_vorjahr = [];
foreach ($data_vorjahr as $schulform => $weeks) {
    $schulformData = array_fill_keys($gewuenschteWochen, 0); // Initiale Daten mit Nullen füllen
    foreach ($weeks as $week => $count) {
        if (in_array($week, $gewuenschteWochen)) {
            $schulformData[$week] = $count;
        }
    }
    $cumulativeSum_vorjahr = $summe[$schulform];
    foreach ($schulformData as $week => $count) {
		if ($count != 0) {
        $cumulativeSum_vorjahr += $count;
        $chartData_vorjahr[$schulform][] = ['x' => $week, 'y' => $cumulativeSum_vorjahr];
		}
    }
}
*/


/*
		echo "<pre>";
	print_r ( $gewuenschteWochen );
	echo "</pre>";
*/
//ENDE Vorjahrsvergleich



//aktuelles Jahr:

// Anpassen der Daten für das Diagramm
$angepassteChartData = [];
foreach ($data as $schulform => $weeks) {
    $schulformData = array_fill_keys($gewuenschteWochen, 0); // Initiale Daten mit Nullen füllen
    foreach ($weeks as $week => $count) {
        if (in_array($week, $gewuenschteWochen)) {
            $schulformData[$week] = $count;
        }
    }
    $cumulativeSum = $summe[$schulform];
    foreach ($schulformData as $week => $count) {
		if ($count != 0) {
        $cumulativeSum += $count;
        $angepassteChartData[$schulform][] = ['x' => $week, 'y' => $cumulativeSum];
		}
    }
}
	/*
	echo "<pre>";
	print_r ( $angepassteChartData );
	echo "</pre>";
	*/
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kurvendiagramm</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="myChart" width="100%"></canvas>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [
                    <?php foreach ($angepassteChartData as $schulform => $dataPoints): ?>
                    {
                        label: '<?php echo $schulform.$jahr2; ?>',
                        data: <?php echo json_encode($dataPoints); ?>,
                        fill: false,
						tension: 0.4, // Glättet auch diese Linie
                        borderColor: '<?php echo '#' . substr(md5($schulform), 0, 6); ?>', // Zufällige Farbe für jede Schulform
                    },
					   <?php if(isset($chartData_vorjahr[$schulform])): ?>
					   {
						
							
							
							label: '<?php echo $schulform.($jahr2 - 1); ?>',
							data: <?php echo json_encode($chartData_vorjahr[$schulform]); ?>,
							fill: false,
							borderColor: '#808080', // Graue Linie für Vorjahresdaten
							borderColor: '<?php echo '#' . substr(md5($schulform), 0, 6); ?>', 
							borderDash: [5, 5], // Gestrichelte Linie
							borderWidth: 1,
							tension: 0.4 // Glättet auch diese Linie
							
					   },
					<?php endif; ?>
					
                    <?php endforeach; ?>
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
						min: 1,
						max: '<?php echo (date("W",time()) + 3); ?>', // Setzt den Startpunkt der X-Achse auf Woche 43
						

                        title: {
                            display: true,
                            text: 'Kalenderwoche'
                        },
						ticks: {
                stepSize: 1, // Stellt sicher, dass die Schritte zwischen den Ticks genau 1 betragen
                callback: function(value, index, values) {
                    // Stellt sicher, dass nur ganze Zahlen angezeigt werden
                    if (Math.floor(value) === value) {
                        return value;
                    }
                }
            }

                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Anmeldungen'
							
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>


<?php


echo "</body>";
echo "</html>";


} else {
   echo "Bitte erst einloggen!";
   header("Location: ./login_ad.php?back=index");

}

include("./fuss.php");

?>
