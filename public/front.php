<?php

require_once('../legacy/php/init/legacy_init.php');

if($path === config('app.directory_path')) {
    $_GET['target'] = 'index';
}

if(!isset($_GET['target'])) {
    abort(404);
}

$mondegc_config['front-controller'] = [];

$mondegc_config['front-controller']['enabled'] = true;

$mondegc_config_uri = str_replace('.', '/', filter_filename($_GET['target']));

$mondegc_config['front-controller']['path'] = 'legacy/' . $mondegc_config_uri;

$mondegc_config['front-controller']['require'] = DEF_ROOTPATH . $mondegc_config['front-controller']['path'] . '.php';

$mondegc_config['front-controller']['url'] = DEF_URI_PATH . $mondegc_config_uri . '.php?' . $_SERVER['QUERY_STRING'];

if(!file_exists($mondegc_config['front-controller']['require'])) {
    abort(404);
}

ob_start();
@require($mondegc_config['front-controller']['require']);

if(isset($mondegc_config['enable_csrf_protection']) && $mondegc_config['enable_csrf_protection']) {
    return csrf_ob_handler(ob_get_clean(), null);
} else {
    return ob_get_clean();
}
