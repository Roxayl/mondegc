<?php

use Roxayl\MondeGC\Services\AuthenticationService;
use Illuminate\Session\TokenMismatchException;

// *** Recherche de sessions.
$clefSession = isset($_COOKIE['Session_mondeGC']) ? $_COOKIE['Session_mondeGC'] : null;

// Il existe une clé de session dans Session_mondeGC.
if ($clefSession != null and $clefSession != "" and !isset($_SESSION['login_user'])) {
    $Session_user_query = sprintf("SELECT ch_users_session_dispatch_sessionID, ch_use_session_id, ch_use_session_connect, ch_use_id, ch_use_login, ch_use_paysID, ch_use_statut, ch_use_acces, ch_use_last_log, last_activity, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users_dispatch_session INNER JOIN users_session ON ch_users_session_dispatch_sessionID = ch_use_session_id INNER JOIN users ON ch_use_session_user_ID = ch_use_id WHERE ch_users_session_dispatch_Key =%s",
        escape_sql($clefSession, "text"));
    $Session_user = mysql_query($Session_user_query, $maconnexion);
    $row_Session_user = mysql_fetch_assoc($Session_user);
    $loginFoundUser = mysql_num_rows($Session_user);

    //Si le membre est banni
    if ($row_Session_user['ch_use_acces'] == null) {
        // ** Effacement des session sur serveur. **
        if ($clefSession != null and $clefSession != "") {
            $deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
                escape_sql($clefSession, "text"));

            $Result4 = mysql_query($deleteSQL, $maconnexion);

            $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
                escape_sql($row_Session_user['ch_use_session_id'], "int"));

            $Result5 = mysql_query($deleteSQL, $maconnexion);

            $authService = new AuthenticationService();
            $authService->logout();
        }
    }

    // ** Si utilisateur trouve **
    if ($loginFoundUser) {
        $authService = new AuthenticationService();
        $authService->loginUsingId($row_Session_user['ch_use_id']);

        // Redirige pour "actualiser" le statut de connexion dans l'application Laravel.
        redirect(url()->full());
    }
}


// *** Si l'utilisateur veut se connecter
if (isset($_POST['identifiant'])) {
    $salt = config('legacy.salt');
    $loginUsername = $_POST['identifiant'];
    $password = md5($_POST['mot_de_passe'].$salt);
    $MM_fldUserAuthorization = "ch_use_paysID";
    $MM_redirectLoginFailed = DEF_URI_PATH."connexion.php";

    $LoginRS__query = sprintf("SELECT ch_use_login, ch_use_password, ch_use_paysID, ch_use_id, ch_use_last_log, last_activity, ch_use_statut, ch_use_acces, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users WHERE ch_use_login=%s AND ch_use_password=%s",
        escape_sql($loginUsername, "text"), escape_sql($password, "text"));
    $LoginRS = mysql_query($LoginRS__query, $maconnexion);
    $loginFoundUser = mysql_num_rows($LoginRS);
    $row_LoginRS = mysql_fetch_assoc($LoginRS);

// *** Si utilisateur banni.
    if ($row_LoginRS['ch_use_acces'] == null) {
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
        $characts = 'abcdefghijklmnopqrstuvwxyz';
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts .= '1234567890';
        $code_aleatoire = '';

        for ($i = 0; $i < 20; $i++) {    //10 est le nombre de caractères
            $code_aleatoire .= substr($characts, rand() % (strlen($characts)), 1);
        }
        $clef_session_kryptee = md5($code_aleatoire.$salt);

        // *** Creation session BDD.
        $insertSQL = sprintf("INSERT INTO users_session (ch_use_session_login_user, ch_use_session_user_ID, ch_use_session_connect, ch_use_session_date) VALUES (%s, %s, %s, %s)",
            escape_sql($user_login, "text"),
            escape_sql($user_ID, "int"),
            escape_sql($connect, "int"),
            escape_sql($now, "date"));

        $Result1 = mysql_query($insertSQL, $maconnexion);

        $Id_Session = mysql_insert_id();
        $insertSQL = sprintf("INSERT INTO users_dispatch_session (ch_users_session_dispatch_Key, ch_users_session_dispatch_sessionID) VALUES (%s, %s)",
            escape_sql($code_aleatoire, "text"),
            escape_sql($Id_Session, "int"));

        $Result2 = mysql_query($insertSQL, $maconnexion);

        // *** Creation du cookie
        $dureeCookieJours = 30;
        setcookie('Session_mondeGC', $code_aleatoire, time() + $dureeCookieJours*24*3600, null, null, false, true);

        $authService = new AuthenticationService();
        $authService->loginUsingId($row_LoginRS['ch_use_id']);

        getErrorMessage('success',
            "Bienvenue ".$_SESSION['userObject']->get('ch_use_login').' !');

        header('Location:'.$mondegc_config['front-controller']['url']);
        die;
    } else {
        getErrorMessage('error',
            "Votre identifiant ou mot de passe est erroné.");
        header("Location: ".$MM_redirectLoginFailed);
        die;
    }
}


//Booleen for display or not connection menu
if (isset($_SESSION['connect']) && $_SESSION['connect']) {
    $_SESSION['menu_connexion'] = 'hidden';
    $_SESSION['menu_gestion'] = 'show';
} else {
    $_SESSION['menu_connexion'] = 'show';
    $_SESSION['menu_gestion'] = 'hidden';
}

// On met à jour la date de dernière activité.
if (isset($_SESSION['userObject'])) {
    mysql_query(sprintf('UPDATE users SET last_activity = NOW() WHERE ch_use_id = %s',
        escape_sql($_SESSION['userObject']->get('ch_use_id'))));
}

// ** Logout the current user. **
$logoutAction = DEF_URI_PATH."index.php?doLogout=true&csrf_token=".csrf_token();
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&".htmlentities($_SERVER['QUERY_STRING']);
}

if (auth()->check() && isset($_GET['doLogout']) && ($_GET['doLogout'] === "true")) {
    // Vérification du jeton CSRF lors de la déconnexion
    $logout_csrf_token = isset($_GET['csrf_token']) ? $_GET['csrf_token'] : '';
    if ($logout_csrf_token !== csrf_token()) {
        throw new TokenMismatchException('CSRF token mismatch.');
    }

// ** Effacement des session sur serveur. **
    if ($clefSession != null and $clefSession != "") {
        $deleteSQL = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_Key=%s",
            escape_sql($clefSession, "text"));

        $Result4 = mysql_query($deleteSQL, $maconnexion);

        $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
            escape_sql($row_Session_user['ch_use_session_id'], "int"));

        $Result5 = mysql_query($deleteSQL, $maconnexion);
    }

    $authService = new AuthenticationService();
    $authService->logout();

    $logoutGoTo = legacyPage();

    getErrorMessage('success', "Vous vous êtes déconnecté.");

    header("Location: $logoutGoTo");
    exit;
}

//Stocke URL dans une variable
$url_en_cours = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// *** Enregistrement commentaires.
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_communique")) {
    $insertSQL = sprintf("INSERT INTO communiques (ch_com_label, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_com_pays_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        escape_sql($_POST['ch_com_label'], "text"),
        escape_sql($_POST['ch_com_statut'], "int"),
        escape_sql($_POST['ch_com_categorie'], "text"),
        escape_sql($_POST['ch_com_element_id'], "int"),
        escape_sql($_POST['ch_com_user_id'], "int"),
        escape_sql($_POST['ch_com_date'], "date"),
        escape_sql($_POST['ch_com_date_mis_jour'], "date"),
        escape_sql($_POST['ch_com_titre'], "text"),
        escape_sql($_POST['ch_com_contenu'], "text"),
        escape_sql($_POST['ch_com_pays_id'], 'int'));

    $Result3 = mysql_query($insertSQL, $maconnexion);

    $insertGoTo = $url_en_cours;
    $adresse = $insertGoTo.'#commentaires';
    header(sprintf("Location: %s", $adresse));
    exit;
}

// *** Recherche et effacement des sessions de plus d'un mois .
$date_expiration = time();
$Session_expire_query = sprintf("SELECT ch_use_session_id FROM users_session WHERE ch_use_session_date < CURDATE()-30");
$Session_expire = mysql_query($Session_expire_query, $maconnexion);
$row_Session_expire = mysql_fetch_assoc($Session_expire);

if ($row_Session_expire != null and $row_Session_expire != "") {
    $ID_session_expire = $row_Session_expire['ch_use_session_id'];
    $deleteSQL = sprintf("DELETE FROM users_session WHERE ch_use_session_id=%s",
        escape_sql($ID_session_expire, "int"));

    $Result6 = mysql_query($deleteSQL, $maconnexion);

    $deleteSQL1 = sprintf("DELETE FROM users_dispatch_session WHERE ch_users_session_dispatch_sessionID=%s",
        escape_sql($ID_session_expire, "int"));

    $Result7 = mysql_query($deleteSQL, $maconnexion);
}

$query_pays = "SELECT ch_pay_id FROM pays";
$pays = mysql_query($query_pays, $maconnexion);
$row_pays = mysql_fetch_assoc($pays);


/********
 * LARAVEL
 * Adaptation des sessions Laravel, pour synchroniser l'auth entre Laravel et Legacy.
 * La synchronisation est gérée par le middleware \Roxayl\MondeGC\Http\Middleware\SynchronizeAuthentication.
 ********/

// Sessions Laravel
if (isset($_SESSION['userObject'])) {
    session()->put('userLegacyId', $_SESSION['userObject']->get('ch_use_id'));
}
if (!isset($_SESSION['userObject'])) {
    session()->forget('userLegacyId');
}


/********
 * ROUTINES
 * Ces instructions sont exécutées à chaque chargement d'une page.
 ********/

/* Calcul ressources */

do {
//recherche des mesures des zones de la carte pour calcul ressources

    $query_geometries = sprintf("SELECT SUM(ch_geo_mesure) as mesure, ch_geo_type FROM geometries WHERE ch_geo_pay_id = %s AND ch_geo_type != 'maritime' AND ch_geo_type != 'region' GROUP BY ch_geo_type ORDER BY ch_geo_geometries",
        escape_sql($row_pays['ch_pay_id'], "int"));
    $geometries = mysql_query($query_geometries, $maconnexion);

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
    while ($row_geometries = mysql_fetch_assoc($geometries)) {
        $surface = $row_geometries['mesure'];
        $typeZone = $row_geometries['ch_geo_type'];
        ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche,
            $environnement, $education, $label, $population, $emploi);
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
    }

//Enregistrement du total des ressources de la carte.
    $updateSQL = sprintf("UPDATE pays SET ch_pay_budget_carte=%s, ch_pay_industrie_carte=%s, ch_pay_commerce_carte=%s, ch_pay_agriculture_carte=%s, ch_pay_tourisme_carte=%s, ch_pay_recherche_carte=%s, ch_pay_environnement_carte=%s, ch_pay_education_carte=%s, ch_pay_population_carte=%s, ch_pay_emploi_carte = %s WHERE ch_pay_id=%s",
        escape_sql($tot_budget, "int"),
        escape_sql($tot_industrie, "int"),
        escape_sql($tot_commerce, "int"),
        escape_sql($tot_agriculture, "int"),
        escape_sql($tot_tourisme, "int"),
        escape_sql($tot_recherche, "int"),
        escape_sql($tot_environnement, "int"),
        escape_sql($tot_education, "int"),
        escape_sql($tot_population, "int"),
        escape_sql($tot_emploi, "int"),
        escape_sql($row_pays['ch_pay_id'], "int"));

    $Result2 = mysql_query($updateSQL, $maconnexion);
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


/* Vérification des propositions */
(new \GenCity\Proposal\ProposalRoutine(new \GenCity\Proposal\ProposalList()))->runRoutine();
