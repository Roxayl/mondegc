<?php

use Roxayl\MondeGC\Services\AuthenticationService;
use Illuminate\Session\TokenMismatchException;

$editFormAction = DEF_URI_PATH.$mondegc_config['front-controller']['uri'].'.php';
appendQueryString($editFormAction);

// *** Recherche de sessions.
$clefSession = isset($_COOKIE['Session_mondeGC']) ? $_COOKIE['Session_mondeGC'] : null;

if ($clefSession != null and $clefSession != "") {
    $Session_user_query = sprintf("SELECT ch_users_session_dispatch_sessionID, ch_use_acces, ch_use_session_id, ch_use_session_connect, ch_use_id, ch_use_login, ch_use_paysID, ch_use_statut, ch_use_last_log, last_activity, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",
        GetSQLValueString($clefSession, "text"));
    $Session_user = mysql_query($Session_user_query, $maconnexion) or die(mysql_error());
    $row_Session_user = mysql_fetch_assoc($Session_user);
    $loginFoundUser = mysql_num_rows($Session_user);

//Si le membre est banni
    if ($row_Session_user['ch_use_acces'] == null) {
// ** Effacement des session sur serveur. **
        if ($clefSession != null and $clefSession != "") {
            $deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                GetSQLValueString($clefSession, "text"));

            $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

            $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

            $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
        }

        $authService = new AuthenticationService();
        $authService->logout();

        $loginFoundUser = null;
        unset($loginFoundUser);
    }

// ** Si utilisateur trouve ** 
    if ($loginFoundUser) {
        $authService = new AuthenticationService();
        $authService->loginUsingId($row_Session_user['ch_use_id']);
    }
}

// ** Logout the current user. **
$logoutAction = DEF_URI_PATH.$mondegc_config['front-controller']['uri']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&".htmlentities($_SERVER['QUERY_STRING']);
}

if (auth()->check() && isset($_GET['doLogout']) && ($_GET['doLogout'] === "true")) {
    // Vérification du jeton CSRF lors de la déconnexion
    $logout_csrf_token = isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '';
    if ($logout_csrf_token !== csrf_token()) {
        throw new TokenMismatchException('CSRF token mismatch.');
    }

// *** Recherche de sessions.
    $clefSession = $_COOKIE['Session_mondeGC'];


    $Session_user_query = sprintf("SELECT ch_use_session_id FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",
        GetSQLValueString($clefSession, "text"));
    $Session_user = mysql_query($Session_user_query, $maconnexion) or die(mysql_error());
    $row_Session_user = mysql_fetch_assoc($Session_user);

// ** Effacement des session sur serveur. **
    $deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
        GetSQLValueString($clefSession, "text"));

    $Result4 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

    $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
        GetSQLValueString($row_Session_user['ch_use_session_id'], "int"));

    $Result5 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());

    $authService = new AuthenticationService();
    $authService->logout();

    $logoutGoTo = legacyPage();
    header("Location: $logoutGoTo");
    exit;
}

$editFormAction = DEF_URI_PATH.$mondegc_config['front-controller']['uri'].'.php';
appendQueryString($editFormAction);

//Stocke URL dans une variable
$url_en_cours = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
