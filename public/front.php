<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

require_once('../Connections/maconnexion.php');

if($path === config('app.directory_path')) {
    $_GET['target'] = 'index';
}

if(!isset($_GET['target'])) {
    throw new NotFoundHttpException("Page non trouvée.");
}

$mondegc_config['front-controller'] = [];

$mondegc_config['front-controller']['enabled'] = true;

$mondegc_config['front-controller']['path'] = str_replace('.', '/',
                                                          filter_filename($_GET['target']));

$mondegc_config['front-controller']['require'] = DEF_ROOTPATH . $mondegc_config['front-controller']['path'] . '.php';

if(isset($mondegc_config['env']) && $mondegc_config['env'] !== 'production') {
    getErrorMessage('info',
        "Using front controller (PHP " . phpversion() . ")<br>Required path: " . $mondegc_config['front-controller']['require']);
}

if(!file_exists($mondegc_config['front-controller']['require'])) {
    throw new NotFoundHttpException("Page non trouvée.");
}

ob_start();
@require($mondegc_config['front-controller']['require']);

if(isset($mondegc_config['enable_csrf_protection']) && $mondegc_config['enable_csrf_protection']) {
    return csrf_ob_handler(ob_get_clean(), null);
} else {
    return ob_get_clean();
}

