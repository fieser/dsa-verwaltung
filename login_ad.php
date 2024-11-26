<?php 
if(isset($_POST['username']) && isset($_POST['password'])){

include("./login_inc.php");
@session_start();


//Aktivieren Sie LDAP in der config.php, 
//nachdem Sie diese Datei konfiguriert haben!


// Daten des zu authentifizierenden Benutzers (z.B. aus Formular)
$domain = "bbs-t1";
$username = $_POST['username'];
$password = $_POST['password'];
// LDAP Daten
$ldap_address = "ldap://172.22.100.2";
$ldap_port = 389;
//$ldap_port = 636;

//putenv('LDAPTLS_REQCERT=never');

if ($connect = ldap_connect($ldap_address, $ldap_port)) {
   //echo "Verbinduig erfolgreich";
   ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
   ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

   // Authentifizierung des Benutzers
   if ($bind = ldap_bind($connect, $domain . "\\" . $username, $password)) {
      // echo "Logon erfolgreich";
      // echo "Benutzerdaten abfragen:";
      $dn = "OU=Schule,DC=bbs-t1,DC=mainz";
      $person = "$username";
      $fields = "(|(samaccountname=$person))";

      $search = ldap_search($connect, $dn, $fields);
      $res = ldap_get_entries($connect, $search);

      $ldap_username = $res[0]['samaccountname'][0];
      $ldap_first_name = $res[0]['givenname'][0];
      $ldap_last_name = $res[0]['sn'][0];
      $ldap_status = $res[0]['useraccountcontrol'][0];
	$ldap_name_long = $res[0]['distinguishedname'][0];
	$ldap_mail = $res[0]['mail'][0];
	$ldap_memberof = $res[0]['memberof'][0];
	$ldap_initials = $res[0]['initials'][0];
	
      ldap_close($connect);
      	
	
      // Prüfen ob Konto gesperrt ist

      if ($ldap_status == True) {

         echo "Willkommen " . $ldap_first_name . " " . $ldap_last_name . "!";
		 
		 

			
         $_SESSION['username'] = $ldap_username;
		
		 $_SESSION['lastname'] = $ldap_last_name;
		 $_SESSION['firstname'] = $ldap_first_name;
		 $_SESSION['mail'] = $ldap_mail;
		 $_SESSION['memberof'] = $ldap_memberof;
		 $_SESSION['ldap_name_long'] = $ldap_name_long;
		 $_SESSION['ldap_initials'] = $ldap_initials;
         $_SESSION['loggedin'] = true; 

			$back = $_GET['back'];
		 			 if (isset($back)) {
						 
			echo "<meta http-equiv='refresh' content=\"0; URL=".$back.".php\">";
			
			 }
         return true;
		 
	
      } else {
          echo "Gesperrt";
          $_SESSION['loggedin'] = false;
          return false;
	echo $ldap_first_name;

      }
   } else {
      echo "Login fehlgeschlagen: Benutzer nicht vorhanden oder Passwort falsch!";
	 // header("Location: ./login_ad.php?back=liste&fehler=true");
	 $back = $_GET['back'];
	  echo "<meta http-equiv='refresh' content=\"0; URL='./login_ad.php?back=".$back."&fehler=true\">";
   }

} else {
   echo "Verbindung fehlgeschlagen";

}} else {
	include "./kopf.php";
?>
<h1>Verwaltungsnetzwerk</h1><br>

<?php
if (isset($_GET['fehler'])) {
	echo "<p><font color='red'>Login fehlgeschlagen: Benutzer nicht vorhanden oder Passwort falsch!</font></p><br>";
}
?>
    <form action="#" method="POST">
	<table>
        <tr height="40"><td><label for="username">Nutzername:&nbsp;&nbsp;</label></td><td><input id="username" type="text" name="username" /></td></tr>
        <tr height="40"><td><label for="password">Passwort: </label></td><td><input id="password" type="password" name="password" />  </td>  </tr>   
		<tr height="40"><td></td><td><input type="submit" name="submit" value="Anmelden" /></td></tr>
    </form>
	</table>
<?php
include "./fuss.php";
}
?>
