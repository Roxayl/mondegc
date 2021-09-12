<?php

namespace App\Services;

use App\Http\Controllers\Legacy\LegacySiteController;
use Illuminate\Http\Response;

class LegacyPageService
{
    static private function callLegacyController($path): string
    {
        /** @var LegacySiteController $controller */
        $controller = app(LegacySiteController::class);

        /** @var Response $html */
        $html = $controller->index(request(), $path);

        return $html->content();
    }

    static function navbar($navbar_context = null): string
    {
        // On créé la variable permettant d'activer l'élément de menu spécifié.
        if(! is_null($navbar_context) && in_array($navbar_context,
                ['accueil', 'dashboard', 'carte', 'menupays', 'pays',
                 'institut', 'participer', 'generation_city']))
        {
            $$navbar_context = true;
        }

        $_GET['target'] = 'php.navbarloader';
        return self::callLegacyController('php/navbarloader.php');
    }

    static function carteGenerale(): string
    {
        $_GET['target'] = 'php.cartegenerale2';
        return self::callLegacyController('php/cartegenerale2.php');
    }
}
