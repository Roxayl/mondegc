<?php

return [

    /*
     * Masque les erreurs PHP, en exécutant <code>error_reporting(0)</code>.
     */
    'hide_errors' => env('LEGACY_HIDE_ERRORS', false),

    /*
     * Active la protection CSRF via CSRF-Magic.
     */
    'enable_csrf_protection' => env('LEGACY_CSRF', true),

    /*
     * Sel de hachage des mots de passe. Sans valeur par défaut : une valeur aléatoire changerait à
     * chaque reconstruction du cache de configuration et invaliderait tous les mots de passe.
     * L'absence de sel est signalée au démarrage du site legacy.
     */
    'salt' => env('LEGACY_SALT'),

];
