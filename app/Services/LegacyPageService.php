<?php

namespace App\Services;

class LegacyPageService {

    static function navbar() {

        $_GET['target'] = 'php.navbarloader';

        // On charge la page de la navbar.
        $navbar = app('App\Http\Controllers\Legacy\LegacySiteController')
            ->index(request(), 'php/navbarloader.php');

        // On supprime tout ce qui est avant le premier tag HTML '<' ; afin de supprimer
        // l'en-tête de la requête (les headers notamment)
        $pos = strpos($navbar, '<');
        $navbar = substr($navbar, $pos);

        return $navbar;

    }

}