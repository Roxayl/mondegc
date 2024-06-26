<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Services\Legacy;

use Roxayl\MondeGC\Http\Controllers\Legacy\LegacySiteController;

class LegacyPageService
{
    /**
     * Renvoie le contenu de la réponse suite à l'appel au controller legacy.
     *
     * @param  string  $path  Chemin d'accès (e.g. " /back/ocgc_proposal_create.php").
     * @return string Réponse sous forme de chaîne.
     */
    private function callLegacyController(string $path): string
    {
        /** @var LegacySiteController $controller */
        $controller = app(LegacySiteController::class);

        /*
         * Si le chemin est '/back/ocgc_proposal_create', on ajoute comme valeur pour $_GET['target']
         * une version adaptée du chemin en "dot notation" sans l'extension, e.g. 'back.ocgc_proposal_create'.
         */
        $target = str_replace('/', '.', $path);
        if (str_ends_with($target, '.php')) {
            $target = substr($target, 0, -4);
        }
        $_GET['target'] = $target;

        /* Appelle le controller avec le chemin adapté et la requête actuelle ! */
        $response = $controller(request(), $path);

        return $response->content();
    }

    /**
     * Contenu HTML de la barre de navigation.
     *
     * @param  string|null  $navbarContext  Elément du menu à lmarquer comme actif.
     * @return string
     */
    public function navbar(?string $navbarContext = null): string
    {
        // On créé dynamiquement la variable permettant d'activer l'élément de menu spécifié.
        if (in_array(
            $navbarContext,
            ['accueil', 'dashboard', 'carte', 'menupays', 'pays', 'institut', 'participer', 'generation_city'],
            true)
        ) {
            $$navbarContext = true;
        }

        return $this->callLegacyController('php/navbarloader.php');
    }

    /**
     * Contenu HTML du footer.
     *
     * @return string
     */
    public function footer(): string
    {
        return $this->callLegacyController('php/footer.php');
    }

    /**
     * Contenu HTML de la carte générale.
     *
     * @return string
     */
    public function carteGenerale(): string
    {
        return $this->callLegacyController('php/cartegenerale2.php');
    }

    /**
     * Contenu HTML du menu dédié au Conseil de l'OCGC.
     *
     * @return string
     */
    public function menuHautConseil(): string
    {
        return $this->callLegacyController('php/menu-haut-conseil.php');
    }
}
