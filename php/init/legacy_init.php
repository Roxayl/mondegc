<?php

/*************************
 *       Paramètres      *
 *************************/
 
// Variables de configuration.
$mondegc_config = array();

// Environnement.
if(\Illuminate\Support\Facades\App::environment() === 'local') {
    if($_SERVER['HTTP_HOST'] === 'localhost') {
        $mondegc_config['env'] = 'localhost';
    } else {
        $mondegc_config['env'] = 'vagrant';
    }
} else {
    $mondegc_config['env'] = 'production';
}

// Définir la configuration depuis Laravel.
$mondegc_config['version'] = config('legacy.version');
$mondegc_config['hide_errors'] = config('legacy.hide_errors');
$mondegc_config['enable_csrf_protection'] = config('legacy.enable_csrf_protection');
$mondegc_config['db'] = array(
    'hostname' => config('database.connections.mysql.host'),
    'username' => config('database.connections.mysql.username'),
    'password' => config('database.connections.mysql.password'),
    'database' => config('database.connections.mysql.database'),
);
$mondegc_config['path'] = !empty(config('app.directory_path'))
                        ? config('app.directory_path') . '/'
                        : '';

// Chemins
defined("DEF_ROOTPATH") or define("DEF_ROOTPATH", base_path() . DIRECTORY_SEPARATOR);
define("DEF_URI_PATH",
        ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
            ? 'https' : 'http' )
        . '://' . $_SERVER['HTTP_HOST'] . '/'
        . $mondegc_config['path']
    );

// Masquer les messages d'erreur.
if($mondegc_config['hide_errors'] === true) {
    error_reporting(0);
}


/*************************
 *  Librairies diverses  *
 *************************/

// Fonctions diverses
require(DEF_ROOTPATH . 'php/init/functions.php');

// Librairie CSRF Magic
if($mondegc_config['enable_csrf_protection'] === true) {
    if(!function_exists('csrf_startup')) {
        function csrf_startup() {
            csrf_conf('rewrite-js', DEF_URI_PATH . 'lib/csrf-magic/csrf-magic.js');
            csrf_conf('rewrite', true);
            csrf_conf('frame-breaker', false);
        }
    }
    require_once DEF_ROOTPATH . 'lib/csrf-magic/csrf-magic.php';
}

// wrapper mysql_ :
// wrapper pour les fonctions MySQL obsolètes, pour PHP7
if(version_compare(phpversion(), '7.0.0', '>=')) {
    require_once(DEF_ROOTPATH . 'lib/mysql_wrapper/mysql_wrapper.php');
}


/*************************
 *    Base de données    *
 *************************/

$maconnexion = @mysql_pconnect($mondegc_config['db']['hostname'], $mondegc_config['db']['username'], $mondegc_config['db']['password']) or trigger_error(mysql_error(), E_USER_ERROR);

if($mondegc_config['env'] !== 'production') {
    mysql_set_charset('utf8mb4', $maconnexion);
    mysql_query("SET SESSION sql_mode = 'TRADITIONAL'");
}
mysql_select_db($mondegc_config['db']['database']);


/*************************
 *        Session        *
 *************************/

if(!isset($_SESSION))
    session_start();