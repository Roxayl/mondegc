<?php

/*************************
 *       Paramètres      *
 *************************/
 
// Variables de configuration.
$mondegc_config = [];

// Environnement.
$mondegc_config['env'] = strtolower(app()->environment());

// Définir la configuration depuis Laravel.
$mondegc_config['version'] = config('legacy.version');
$mondegc_config['hide_errors'] = config('legacy.hide_errors');
$mondegc_config['enable_csrf_protection'] = config('legacy.enable_csrf_protection');
$mondegc_config['db'] = [
    'hostname' => config('database.connections.mysql.host'),
    'port'     => config('database.connections.mysql.port'),
    'username' => config('database.connections.mysql.username'),
    'password' => config('database.connections.mysql.password'),
    'database' => config('database.connections.mysql.database'),
];
$mondegc_config['path'] = ! empty(config('app.directory_path'))
                        ? config('app.directory_path') . '/'
                        : '';

// Chemins
defined("DEF_ROOTPATH") or define("DEF_ROOTPATH",
    base_path() . DIRECTORY_SEPARATOR);
defined("DEF_LEGACYROOTPATH") or define("DEF_LEGACYROOTPATH",
    DEF_ROOTPATH . 'legacy' . DIRECTORY_SEPARATOR);
defined("DEF_URI_PATH") or define("DEF_URI_PATH",
        ( (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
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
require_once(DEF_LEGACYROOTPATH . 'php/init/functions.php');

// Librairie CSRF Magic
if($mondegc_config['enable_csrf_protection'] === true) {
    if(! function_exists('csrf_startup')) {
        function csrf_startup(): void
        {
            csrf_conf('rewrite-js', DEF_URI_PATH . 'lib/csrf-magic/csrf-magic.js');
            csrf_conf('rewrite', true);
            csrf_conf('frame-breaker', false);
        }
    }
    require_once(DEF_ROOTPATH . 'lib/csrf-magic/csrf-magic.php');
}

// Wrapper pour les fonctions MySQL obsolètes (mysql_*), pour PHP 7 ou supérieur.
require_once(DEF_ROOTPATH . 'lib/mysql_wrapper/mysql_wrapper.php');


/*************************
 *    Base de données    *
 *************************/

$maconnexion = mysql_connect(
    $mondegc_config['db']['hostname'] . ':' . $mondegc_config['db']['port'],
    $mondegc_config['db']['username'],
    $mondegc_config['db']['password']
);

if(! $maconnexion) {
    throw new Exception(mysql_error());
}

mysql_set_charset('utf8mb4', $maconnexion);
if(config('legacy.sql_mode_traditional')) {
    mysql_query("SET SESSION sql_mode = 'TRADITIONAL'");
}
mysql_select_db($mondegc_config['db']['database']);


/*************************
 *        Session        *
 *************************/

if(!isset($_SESSION)) {
    session_start();
}
