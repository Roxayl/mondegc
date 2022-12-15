<?php

namespace Roxayl\MondeGC\Services;

use Roxayl\MondeGC\Http\Controllers\Legacy\LegacySiteController;

class LegacyPageService
{
    /**
     * Renvoie le contenu de la réponse suite à l'appel au controller legacy.
     * @param string $path Chemin d'accès (e.g. " /back/ocgc_proposal_create.php").
     * @return string Réponse sous forme de chaîne.
     */
    private static function callLegacyController(string $path): string
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

    /**
     * Contenu HTML de la barre de navigation.
     * @param string|null $navbarContext Elément du menu à lmarquer comme actif.
     * @return string
     */
    public static function navbar(?string $navbarContext = null): string
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

    /**
     * Contenu HTML du footer.
     * @return string
     */
    public static function footer(): string
    {
        return self::callLegacyController('php/footer.php');
    }

    /**
     * Contenu HTML de la carte générale.
     * @return string
     */
    public static function carteGenerale(): string
    {
        return self::callLegacyController('php/cartegenerale2.php');
    }

    /**
     * Contenu HTML du menu dédié au Conseil de l'OCGC.
     * @return string
     */
    public static function menuHautConseil(): string
    {
        return self::callLegacyController('php/menu-haut-conseil.php');
    }
}
