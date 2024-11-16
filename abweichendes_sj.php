<?php

$beginn_schuljahr = strtotime("01.08.".date("Y"));
$jahr = date("Y");
$jahr2 = $jahr[2].$jahr[3];
/*
	if (isset($_SESSION['schuljahr']) AND ((substr($_SESSION['schuljahr'],0,-5) != ($jahr - 1) AND $beginn_schuljahr > time()) OR (substr($_SESSION['schuljahr'],0,-5) != $jahr AND $beginn_schuljahr < time()))) {
	?>
	<div class="ilMainHeader" style='background-color: #E30613; height: 1.3em; width: 100%;'>
			<p style='color: ffffff; padding: 0em 0.1em 0em 5em;'><small><b><i>!! Sie sind nicht im aktuellen Schuljahr !!</i></b></small></p>
	</div>
	<?php
	}
*/
?>
