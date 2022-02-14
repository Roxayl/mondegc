<?php

use Illuminate\Support\Str;

return [

    /*
     * Version de l'application.
     * Ce paramètre est notamment ajouté à la fin des URLs d'assets (fichiers CSS, JS...) afin de forcer le navigateur
     * à ignorer le cache.
     */
    'version' => env('LEGACY_VERSION', '2.x'),

    /*
     * Masque les erreurs PHP, en exécutant <code>error_reporting(0)</code>.
     */
    'hide_errors' => env('LEGACY_HIDE_ERRORS', false),

    /*
     * Active la protection CSRF via CSRF-Magic.
     */
    'enable_csrf_protection' => env('LEGACY_CSRF', true),

    /*
     * Sel de hachage des mots de passe.
     */
    'salt' => env('LEGACY_SALT', Str::random(32)),

    /*
     * Permet de spécifier s'il faut utilise le mode SQL 'traditionnel', qui permet notamment d'exécuter des requêtes
     * dont la clause GROUP BY ne contient pas l'ensemble des champs de la clause SELECT, par exemple.
     * Ce paramètre doit être mis à <code>true</code> dans l'environnement de développement, sinon, certaines requêtes
     * du site legacy ne fonctionneront pas.
     */
    'sql_mode_traditional' => env('LEGACY_SQL_MODE_TRAD', false),

];
