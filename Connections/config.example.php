<?php

/*****
 * Site du Monde GC.
 * Fichier de configuration par défaut.
 * Copiez le contenu du fichier et collez-le dans un nouveau fichier config.production.php
 * à placer dans ce dossier, qui sera utilisé dans un environnement de production.
 * Le fichier de configuration sera chargé depuis Connections/maconnexion.php.
 *
 * 2020-08-01 : ce fichier est obsolète. Les informations de configuration sont chargés
 * depuis la configuration Laravel (dans config/legacy.php).
 *****/

$mondegc_config['version'] = "2.x";
$mondegc_config['hide_errors'] = false;
$mondegc_config['enable_csrf_protection'] = true;
$mondegc_config['db'] = array(
    'hostname' => 'localhost',
    'username' => '',
    'password' => '',
    'database' => 'mondegc'
);
$mondegc_config['path'] = '';
