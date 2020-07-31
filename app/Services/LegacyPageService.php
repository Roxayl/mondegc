<?php

namespace App\Services;

use App\Http\Controllers\Legacy\LegacySiteController;

class LegacyPageService {

    static private function callLegacyController($path)
    {
        $html = app(LegacySiteController::class)
            ->index(request(), $path);

        // On supprime tout ce qui est avant le premier tag HTML '<' ; afin de supprimer
        // l'en-tête de la requête (les headers notamment)
        $pos = strpos($html, '<');
        return substr($html, $pos);
    }

    static function navbar($navbar_context = null)
    {
        // On créé la variable permettant d'activer l'élément de menu spécifié.
        if(!is_null($navbar_context) && in_array($navbar_context,
                ['accueil', 'dashboard', 'carte', 'menupays', 'pays',
                 'institut', 'participer', 'generation_city']))
        {
            $$navbar_context = true;
        }

        $_GET['target'] = 'php.navbarloader';
        return self::callLegacyController('php/navbarloader.php');
    }

    static function carteGenerale()
    {
        $_GET['target'] = 'php.cartegenerale2';
        return self::callLegacyController('php/cartegenerale2.php');
    }

}