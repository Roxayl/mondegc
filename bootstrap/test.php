<?php

require_once(__DIR__ . '/../vendor/autoload.php');

/**
 * Il faut nécessairement qu'un fichier .env.testing existe.
 * Dans le cas contraire, PHPUnit utilise la base de données définie dans .env pour l'environnement de test,
 * ce qui peut provoquer la perte de données.
 */
if(! file_exists(__DIR__ . '/../.env.testing')) {
    throw new LogicException("Le fichier .env.testing n'existe pas. Vous devez peut-être exécuter la "
        . "commande 'php artisan monde:init-testing'.");
}
