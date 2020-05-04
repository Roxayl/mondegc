<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

require_once('../Connections/maconnexion.php');

if($path === '') {
    $_GET['target'] = 'index';
}

if(!isset($_GET['target'])) {
    throw new NotFoundHttpException("Page non trouvée.");
}

$mondegc_config['front-controller'] = array();

$mondegc_config['front-controller']['enabled'] = true;

$mondegc_config['front-controller']['path'] = str_replace('.', '/',
                                                          filter_filename($_GET['target']));

$mondegc_config['front-controller']['require'] = DEF_ROOTPATH . $mondegc_config['front-controller']['path'] . '.php';

if($mondegc_config['env'] !== 'production') {
    getErrorMessage('info',
        "Using front controller (PHP " . phpversion() . ")<br>Required path: " . $mondegc_config['front-controller']['require']);
}

if(!file_exists($mondegc_config['front-controller']['require'])) {
    throw new NotFoundHttpException("Page non trouvée.");
}

ob_start();
@require($mondegc_config['front-controller']['require']);

return csrf_ob_handler(ob_get_clean(), null);
