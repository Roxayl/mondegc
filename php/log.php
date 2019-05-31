<?php
require_once('Connections/maconnexion.php');

// *** Validate request to login to this site.
if (!isset($_SESSION)) {

}

// *** Recherche de sessions.
$clefSession = isset($_COOKIE['Session_mondeGC']) ? $_COOKIE['Session_mondeGC'] : NULL;

// Il existe une clé de session dans Session_mondeGC.
if ($clefSession != NULL and $clefSession != "" and !isset($_SESSION['login_user'])) {

    mysql_select_db($database_maconnexion, $maconnexion);
    $Session_user_query=sprintf("SELECT ch_users_session_dispatch_sessionID, ch_use_session_id, ch_use_session_connect, ch_use_id, ch_use_login, ch_use_paysID, ch_use_statut, ch_use_acces, ch_use_last_log, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",GetSQLValueString($clefSession, "text"));
    $Session_user = mysql_query($Session_user_query, $maconnexion) or die(mysql_error());
    $row_Session_user = mysql_fetch_assoc($Session_user);
    $loginFoundUser = mysql_num_rows($Session_user);

    //Si le membre est banni
    if ($row_Session_user['ch_use_acces'] == NULL) {

        // ** Effacement des session sur serveur. **
        if ($clefSession != NULL and $clefSession != "") {
            $deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                               GetSQLValueString($clefSession, "text"));
            mysql_select_db($database_maconnexion, $maconnexion);
            $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

            $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                               GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

            mysql_select_db($database_maconnexion, $maconnexion);
            $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

            // ** Effacement du cookie. **
            setcookie('Session_mondeGC', '', time() -3600, null, null, false, false);

            //to fully log out a visitor we need to clear the session varialbles
            unset($_SESSION['login_user']);
            unset($_SESSION['pays_ID']);
            unset($_SESSION['PrevUrl']);
            unset($_SESSION['fond_ecran']);
            unset($_SESSION['connect']);
            unset($_SESSION['user_ID']);
            unset($_SESSION['user_last_log']);
            unset($_SESSION['statut']);
            unset($_SESSION['userObject']);
            unset($loginFoundUser);
        }
    }

    // ** Si utilisateur trouve **
    if ($loginFoundUser) {
        //Change the last log date
        $now = date("Y-m-d G:i:s");
        $_SESSION['user_last_log'] = $now;
        $updateSQL = sprintf("UPDATE users SET ch_use_last_log=%s WHERE ch_use_id=%s",
                               GetSQLValueString($_SESSION['user_last_log'], "date"),
                               GetSQLValueString($_SESSION['user_ID'], "int"));
        mysql_select_db($database_maconnexion, $maconnexion);
        $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

        //declare session variables and assign them
        $_SESSION['login_user'] = $row_Session_user['ch_use_login'];
        $_SESSION['pays_ID'] = $row_Session_user['ch_use_paysID'];
        $_SESSION['connect'] = true;
        $_SESSION['user_ID'] = $row_Session_user['ch_use_id'];
        $_SESSION['user_last_log'] = $row_Session_user['ch_use_last_log'];
        $_SESSION['statut'] = $row_Session_user['ch_use_statut'];
        $_SESSION['img_dirigeant'] = $row_Session_user['ch_use_lien_imgpersonnage'];
        $_SESSION['predicat_dirigeant'] = $row_Session_user['ch_use_predicat_dirigeant'];
        $_SESSION['titre_dirigeant'] = $row_Session_user['ch_use_titre_dirigeant'];
        $_SESSION['nom_dirigeant'] = $row_Session_user['ch_use_nom_dirigeant'];
        $_SESSION['prenom_dirigeant'] = $row_Session_user['ch_use_prenom_dirigeant'];
        $_SESSION['derniere_visite'] = $row_Session_user['ch_use_last_log'];
        $_SESSION['errormsgs'] = array();
        /**
         * @var \GenCity\Monde\User
         */
        $_SESSION['userObject'] = new \GenCity\Monde\User($row_Session_user['ch_use_id']);

    }

}



// *** Si l'utilisateur veut se connecter
if (isset($_POST['identifiant'])) {
	include("php/config.php");
  $loginUsername = $_POST['identifiant'];
  $password = md5($_POST['mot_de_passe'].$salt);
  $MM_fldUserAuthorization = "ch_use_paysID";
  $MM_redirectLoginFailed = "connexion.php";
  mysql_select_db($database_maconnexion, $maconnexion); 
  
  $LoginRS__query=sprintf("SELECT ch_use_login, ch_use_password, ch_use_paysID, ch_use_id, ch_use_last_log, ch_use_statut, ch_use_acces, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users WHERE ch_use_login=%s AND ch_use_password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));
  $LoginRS = mysql_query($LoginRS__query, $maconnexion) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  $row_LoginRS = mysql_fetch_assoc($LoginRS);

// *** Si utilisateur banni.
if ($row_LoginRS['ch_use_acces'] == NULL) {
	$loginFoundUser == NULL;
    unset($loginFoundUser);
}


// *** Si utilisateur trouve.
if ($loginFoundUser) {
    
	$now = date("Y-m-d G:i:s");
	$connect = true;
	$user_ID = $row_LoginRS['ch_use_id'];
	$user_login = $row_LoginRS['ch_use_login'];
	$user_pays = $row_LoginRS['ch_use_paysID'];

	///fonction clef activation
	$characts    = 'abcdefghijklmnopqrstuvwxyz';
    $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
	$characts   .= '1234567890'; 
	$code_aleatoire      = ''; 

	for($i=0;$i < 20;$i++)    //10 est le nombre de caractères
	{ 
        $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
	}
	$clef_session_kryptee = md5($code_aleatoire.$salt);



	// *** Creation session BDD.
	$insertSQL = sprintf("INSERT INTO users_session (ch_use_session_login_user, ch_use_session_user_ID, ch_use_session_connect, ch_use_session_date) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($user_login, "text"),
                       GetSQLValueString($user_ID, "int"),
                       GetSQLValueString($connect, "int"),
                       GetSQLValueString($now, "date"));

	mysql_select_db($database_maconnexion, $maconnexion);
	$Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
  
	$Id_Session = mysql_insert_id();
	$insertSQL = sprintf("INSERT INTO users_dispatch_session (ch_users_session_dispatch_Key, ch_users_session_dispatch_sessionID) VALUES (%s, %s)",
                       GetSQLValueString($code_aleatoire, "text"),
                       GetSQLValueString($Id_Session, "int"));

	mysql_select_db($database_maconnexion, $maconnexion);
	$Result2 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
    
	// *** Creation du cookie
	setcookie('Session_mondeGC', $code_aleatoire, time() + 30*24*3600, null, null, false, true);

	//Change the last log date
	$updateSQL = sprintf("UPDATE users SET ch_use_last_log=%s WHERE ch_use_id=%s",
                       GetSQLValueString($now, "date"),
                       GetSQLValueString($row_LoginRS['ch_use_id'], "int"));
  	mysql_select_db($database_maconnexion, $maconnexion);
  	$Result3 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

    //declare session variables and assign them
    $_SESSION['login_user'] = $loginUsername;
    $_SESSION['pays_ID'] = $user_pays;
	$_SESSION['connect'] = true;
	$_SESSION['user_ID'] = $row_LoginRS['ch_use_id'];
	$_SESSION['user_last_log'] = $row_LoginRS['ch_use_last_log'];
	$_SESSION['statut'] = $row_LoginRS['ch_use_statut'];
	$_SESSION['img_dirigeant'] = $row_LoginRS['ch_use_lien_imgpersonnage'];
	$_SESSION['predicat_dirigeant'] = $row_LoginRS['ch_use_predicat_dirigeant'];
	$_SESSION['titre_dirigeant'] = $row_LoginRS['ch_use_titre_dirigeant'];
	$_SESSION['nom_dirigeant'] = $row_LoginRS['ch_use_nom_dirigeant'];
	$_SESSION['prenom_dirigeant'] = $row_LoginRS['ch_use_prenom_dirigeant'];
	$_SESSION['derniere_visite'] = $row_LoginRS['ch_use_last_log'];
    $_SESSION['errormsgs'] = array();
    /**
     * @var \GenCity\Monde\User
     */
    $_SESSION['userObject'] = new \GenCity\Monde\User($row_LoginRS['ch_use_id']);

	header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
	die;
  	}
  else {
    header("Location: ". $MM_redirectLoginFailed );
	die;
  }
}


  //Booleen for display or not connection menu 
if (isset($_SESSION['connect']) && $_SESSION['connect']) {
    $_SESSION['menu_connexion'] = 'hidden';
	$_SESSION['menu_gestion'] = 'show';
  }
else {
    $_SESSION['menu_connexion'] = 'show';
	$_SESSION['menu_gestion'] = 'hidden';
  }


// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
	
// ** Effacement des session sur serveur. **
	if ($clefSession != NULL and $clefSession != "") {
	$deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                       GetSQLValueString($clefSession, "text"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
  $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                       GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
	}

// ** Effacement du cookie. **
setcookie('Session_mondeGC', '', time() -3600, null, null, false, false);
	// Unset key
unset($_COOKIE["Session_mondeGC"]);
	
  //to fully log out a visitor we need to clear the session varialbles
  unset($_SESSION['login_user']);
  unset($_SESSION['pays_ID']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['fond_ecran']);
  unset($_SESSION['connect']);
  unset($_SESSION['user_ID']);
  unset($_SESSION['user_last_log']);
  unset($_SESSION['statut']);
  unset($_SESSION['userObject']);
  if ($_SERVER['QUERY_STRING'] != "doLogout=true") {
  $variables = preg_replace('#doLogout=true&(.+)#i', '$1', $_SERVER['QUERY_STRING']);
  } else {
  $variables  = "";
  }
  
  $logoutGoTo = $_SERVER['PHP_SELF'].'?'.$variables;
  
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}



// *** Enregistrement commentaires.

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_communique")) {
  $insertSQL = sprintf("INSERT INTO communiques (ch_com_label, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_com_label'], "text"),
                       GetSQLValueString($_POST['ch_com_statut'], "int"),
                       GetSQLValueString($_POST['ch_com_categorie'], "text"),
                       GetSQLValueString($_POST['ch_com_element_id'], "int"),
                       GetSQLValueString($_POST['ch_com_user_id'], "int"),
                       GetSQLValueString($_POST['ch_com_date'], "date"),
                       GetSQLValueString($_POST['ch_com_date_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_com_titre'], "text"),
                       GetSQLValueString($_POST['ch_com_contenu'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result3 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = $url_en_cours;
    $adresse = $insertGoTo .'#commentaires';
  header(sprintf("Location: %s", $adresse));
}

// *** Recherche et effacement des sessions de plus d'un mois .

$date_expiration = time();
$Session_expire_query=sprintf("SELECT ch_use_session_id FROM users_session WHERE ch_use_session_date < CURDATE()-30");
  $Session_expire = mysql_query($Session_expire_query, $maconnexion) or die(mysql_error());
  $row_Session_expire = mysql_fetch_assoc($Session_expire);
  
  if ($row_Session_expire != NULL and $row_Session_expire != "") {
	$ID_session_expire = $row_Session_expire['ch_use_session_id'];
	$deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                       GetSQLValueString($ID_session_expire, "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result6 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
  $deleteSQL1 = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_sessionID=%s",
                       GetSQLValueString($ID_session_expire, "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result7 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
	}
	
	
	//Stocke URL dans une variable
$url_en_cours = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


mysql_select_db($database_maconnexion, $maconnexion);
$query_pays = "SELECT ch_pay_id FROM pays";
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);

do { 
//recherche des mesures des zones de la carte pour calcul ressources
mysql_select_db($database_maconnexion, $maconnexion);
$query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries", GetSQLValueString($row_pays['ch_pay_id'], "int"));
$geometries = mysql_query($query_geometries, $maconnexion) or die(mysql_error());
$row_geometries = mysql_fetch_assoc($geometries);

//Calcul total des ressources de la carte.

    $tot_budget = 0;
    $tot_industrie = 0;
    $tot_commerce = 0;
    $tot_agriculture = 0;
    $tot_tourisme = 0;
    $tot_recherche = 0;
    $tot_environnement = 0;
    $tot_education = 0;
    $tot_population = 0;
    $tot_emploi = 0;
     do { 
		$surface = $row_geometries['mesure'];
		$typeZone = $row_geometries['ch_geo_type'];
		ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
		$tot_budget += $budget;
		$tot_industrie += $industrie;
		$tot_commerce += $commerce;
		$tot_agriculture += $agriculture;
		$tot_tourisme += $tourisme;
		$tot_recherche += $recherche;
		$tot_environnement += $environnement;
		$tot_education += $education;
		$tot_population += $population;
		$tot_emploi += $emploi;
		 } while ($row_geometries = mysql_fetch_assoc($geometries));

//Enregistrement du total des ressources de la carte.
$updateSQL = sprintf("UPDATE pays SET ch_pay_budget_carte=%s, ch_pay_industrie_carte=%s, ch_pay_commerce_carte=%s, ch_pay_agriculture_carte=%s, ch_pay_tourisme_carte=%s, ch_pay_recherche_carte=%s, ch_pay_environnement_carte=%s, ch_pay_education_carte=%s, ch_pay_population_carte=%s, ch_pay_emploi_carte = %s WHERE ch_pay_id=%s",
                       GetSQLValueString($tot_budget, "int"),
					   GetSQLValueString($tot_industrie, "int"),
					   GetSQLValueString($tot_commerce, "int"),
					   GetSQLValueString($tot_agriculture, "int"),
                       GetSQLValueString($tot_tourisme, "int"),
                       GetSQLValueString($tot_recherche, "int"),
                       GetSQLValueString($tot_environnement, "int"),
                       GetSQLValueString($tot_education, "int"),
                       GetSQLValueString($tot_population, "int"),
                       GetSQLValueString($tot_emploi, "int"),
					   GetSQLValueString($row_pays['ch_pay_id'], "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result2 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
  mysql_free_result($geometries);
  
  $surface = 0;
		$typeZone = 0;
		$tot_budget = 0;
		$tot_industrie = 0;
		$tot_commerce = 0;
		$tot_agriculture = 0;
		$tot_tourisme = 0;
		$tot_recherche = 0;
		$tot_environnement = 0;
		$tot_education = 0;
		$tot_population = 0;
		$tot_emploi = 0;
} while ($row_pays = mysql_fetch_assoc($pays));
?>
