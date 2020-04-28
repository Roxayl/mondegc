<?php

require_once('../Connections/maconnexion.php');

if(isset($_GET['target'])) {

    $mondegc_config['front-controller'] = array();

    $mondegc_config['front-controller']['enabled'] = true;

    $mondegc_config['front-controller']['path'] = filter_filename($_GET['target']);

    $mondegc_config['front-controller']['path'] = str_replace('.', '/', $mondegc_config['front-controller']['path']);

    $mondegc_config['front-controller']['require'] = DEF_ROOTPATH . $mondegc_config['front-controller']['path'] . '.php';

    if($mondegc_config['env'] !== 'production') {
        getErrorMessage('success',
            "Using front controller.<br>Required path: " . $mondegc_config['front-controller']['require']);
    }

    if(file_exists($mondegc_config['front-controller']['require'])) {
        require($mondegc_config['front-controller']['require']);
    }

}