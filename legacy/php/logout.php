<?php

use Illuminate\Session\TokenMismatchException;

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

// *** Recherche de sessions.
$clefSession = isset($_COOKIE['Session_mondeGC']) ? $_COOKIE['Session_mondeGC'] : null;

if ($clefSession != NULL and $clefSession != "") {

$Session_user_query=sprintf("SELECT ch_users_session_dispatch_sessionID, ch_use_acces, ch_use_session_id, ch_use_session_connect, ch_use_id, ch_use_login, ch_use_paysID, ch_use_statut, ch_use_last_log, last_activity, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",GetSQLValueString($clefSession, "text"));
  $Session_user = mysql_query($Session_user_query, $maconnexion) or die(mysql_error());
  $row_Session_user = mysql_fetch_assoc($Session_user);
  $loginFoundUser = mysql_num_rows($Session_user);

//Si le membre est banni
if ($row_Session_user['ch_use_acces'] == NULL) {
// ** Effacement des session sur serveur. **
	if ($clefSession != NULL and $clefSession != "") {
	$deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                       GetSQLValueString($clefSession, "text"));
  
  $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
  $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                       GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

  
  $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
	}

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
  unset($_SESSION['Temp_userID']);
$loginFoundUser = NULL;
unset($loginFoundUser);

}

// ** Si utilisateur trouve ** 
if ($loginFoundUser) {
	//Change the last log date
$now = date("Y-m-d G:i:s");
$_SESSION['user_last_log'] = $now;
$updateSQL = sprintf("UPDATE users SET ch_use_last_log=%s WHERE ch_use_id=%s",
                       GetSQLValueString($_SESSION['user_last_log'], "date"),
                       GetSQLValueString($_SESSION['user_ID'], "int"));
  
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
	$_SESSION['derniere_visite'] = $row_Session_user['last_activity'];
    $_SESSION['errormsgs'] = array();

    $_SESSION['userObject'] = new \GenCity\Monde\User($row_Session_user['ch_use_id']);
}}

// ** Logout the current user. **
$logoutAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ( auth()->check() && isset($_GET['doLogout']) && ($_GET['doLogout'] === "true") ) {

    // Vérification du jeton CSRF lors de la déconnexion
    $logout_csrf_token = isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '';
    if($logout_csrf_token !== csrf_token()) {
        throw new TokenMismatchException('CSRF token mismatch.');
    }

// *** Recherche de sessions.
$clefSession = $_COOKIE['Session_mondeGC'];


$Session_user_query=sprintf("SELECT ch_use_session_id FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",GetSQLValueString($clefSession, "text"));
  $Session_user = mysql_query($Session_user_query, $maconnexion) or die(mysql_error());
  $row_Session_user = mysql_fetch_assoc($Session_user);

// ** Effacement des session sur serveur. **
	$deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                       GetSQLValueString($clefSession, "text"));
  
  $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  
  $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                       GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

  
  $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

  // ** Effacement du cookie. **
  setcookie('Session_mondeGC', '', time() -3600, null, null, false, false);
  // Unset key
  unset($_COOKIE["Session_mondeGC"]);

  //to fully log out a visitor we need to clear the session variables
  unset($_SESSION['login_user']);
  unset($_SESSION['pays_ID']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['fond_ecran']);
  unset($_SESSION['connect']);
  unset($_SESSION['user_ID']);
  unset($_SESSION['user_last_log']);
  unset($_SESSION['statut']);
	
  $logoutGoTo = legacyPage();
  header("Location: $logoutGoTo");
  exit;
}

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);


//Stocke URL dans une variable
$url_en_cours = "https://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];