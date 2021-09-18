<?php

namespace App\Services;

use App\Http\Controllers\Legacy\LegacySiteController;

class LegacyPageService
{
    /**
     * Renvoie le contenu de la réponse suite à l'appel au controller legacy.
     * @param string $path Chemin d'accès (e.g. " /back/ocgc_proposal_create.php").
     * @return string Réponse sous forme de chaîne.
     */
    static private function callLegacyController(string $path): string
    {
        /** @var LegacySiteController $controller */
        $controller = app(LegacySiteController::class);

        /*
         * Si le chemin est '/back/ocgc_proposal_create', on ajoute comme valeur pour $_GET['target']
         * une version adaptée du chemin en "dot notation" sans l'extension, e.g. 'back.ocgc_proposal_create'.
         */
        $target = str_replace('/', '.', $path);
        if(substr($target, -4) === '.php') {
            $target = substr($target, 0, -4);
        }
        $_GET['target'] = $target;

        /* Appelle le controller avec le chemin adapté et la requête actuelle ! */
        $response = $controller(request(), $path);

        return $response->content();
    }

    static function navbar(?string $navbarContext = null): string
    {
        // On créé dynamiquement la variable permettant d'activer l'élément de menu spécifié.
        if(! is_null($navbarContext) && in_array($navbarContext,
                ['accueil', 'dashboard', 'carte', 'menupays', 'pays',
                 'institut', 'participer', 'generation_city']))
        {
            $$navbarContext = true;
        }

        return self::callLegacyController('php/navbarloader.php');
    }

    static function carteGenerale(): string
    {
        return self::callLegacyController('php/cartegenerale2.php');
    }
}
