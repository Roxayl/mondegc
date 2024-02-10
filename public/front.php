<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Str;

global $_DEBUGBAR_ENABLED;

$_DEBUGBAR_ENABLED = class_exists(Debugbar::class);

if ($_DEBUGBAR_ENABLED) {
    $_DEBUGBAR_PATHNAME = isset($path) ? Str::limit(' ' . $path, 20) : '';
    Debugbar::startMeasure("Legacy application$_DEBUGBAR_PATHNAME");
    Debugbar::startMeasure("Booting legacy$_DEBUGBAR_PATHNAME");
}

require_once base_path('legacy/php/init/legacy_init.php');

if (isset($path) && $path === config('app.directory_path')) {
    $_GET['target'] = 'index';
}

if (! isset($_GET['target'])) {
    abort(404);
}

$mondegc_config['front-controller'] = [];

/**
 * Indiquer que la requête est traitée via le front controller legacy.
 */
$mondegc_config['front-controller']['enabled'] = true;

/**
 * Indique l'URL à partir de la racine du site, sans l'extension.
 * (e.g. a pour valeur 'back/ocgc_proposal_create' pour 'https://monde.com/back/ocgc_proposal_create')
 * Type : URI/URL.
 */
$mondegc_config['front-controller']['uri'] = str_replace('.', '/', filter_filename($_GET['target']));

/**
 * Donne l'URL complet, avec les paramètres passés via l'URL (GET).
 * Type : URI/URL.
 */
$mondegc_config['front-controller']['url'] = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php'
    . (empty($_SERVER['QUERY_STRING']) ? '' : '?') . $_SERVER['QUERY_STRING'];

/**
 * Donne le chemin dans le système de fichiers, à partir du répertoire racine, sans l'extension.
 * (e.g. a pour valeur 'legacy/back/ocgc_proposal_create' pour le fichier
 *   'html/RACINE/legacy/back/ocgc_proposal_create.php')
 * Type : Chemin du système de fichiers.
 */
$mondegc_config['front-controller']['path'] = 'legacy/' . $mondegc_config['front-controller']['uri'];

/**
 * Donne le chemin complet dans le système de fichiers.
 * (e.g. a pour valeur 'html/RACINE/legacy/back/ocgc_proposal_create.php')
 * Type : Chemin du système de fichiers.
 */
$mondegc_config['front-controller']['require'] = DEF_ROOTPATH . $mondegc_config['front-controller']['path'] . '.php';

if ($_DEBUGBAR_ENABLED) {
    Debugbar::stopMeasure("Booting legacy$_DEBUGBAR_PATHNAME");
}

if (! file_exists($mondegc_config['front-controller']['require'])) {
    abort(404);
}

ob_start();

try {
    @require $mondegc_config['front-controller']['require'];

    if (isset($mondegc_config['enable_csrf_protection']) && $mondegc_config['enable_csrf_protection']) {
        return csrf_ob_handler(ob_get_clean(), null);
    } else {
        return ob_get_clean();
    }
} catch(\Throwable $throwable) {
    ob_get_clean();
    throw $throwable;
} finally {
    if ($_DEBUGBAR_ENABLED) {
        Debugbar::stopMeasure("Legacy application$_DEBUGBAR_PATHNAME");
    }
}
