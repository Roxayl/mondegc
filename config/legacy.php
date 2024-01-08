<?php

use Illuminate\Support\Str;

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
     * Sel de hachage des mots de passe.
     */
    'salt' => env('LEGACY_SALT', Str::random(32)),

];
