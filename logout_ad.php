<?php 

include("./login_inc.php");
@session_start();

if (isset($_SESSION['username'])) {
	session_destroy();
	header("Refresh:1; url=logout_ad.php");

} else {
	include "./kopf.php";
?>
<h1>Abmeldung</h1><br>

<?php
if (!isset($_SESSION['username'])) {
?>
<p>Sie wurden abgemeldet!</p>

<table>
<tr>
<td>
<form method="post" action="https://service.bbs1-mainz.de">
<input class="btn btn-default btn-sm" type="submit" name="cmd[doStandardAuthentication]" value="Service-Seite" />
</form>
</td>
<td width= 20>
</td>
<td>
<form method="post" action="./index.php">
<input class="btn btn-default btn-sm" type="submit" name="cmd[doStandardAuthentication]" value="wieder anmelden" />
</form>
</td>
</tr>
</table>
<?php
}
?>

<?php
include "./fuss.php";
}
?>
