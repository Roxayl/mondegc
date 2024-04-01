<?php

declare(strict_types=1);

namespace Tests\Feature\Legacy;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Services\AuthenticationService;

class UserAccessLegacyPageTest extends AccessLegacyPage
{
    private ?CustomUser $user = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        // Initialiser l'application legacy, depuis la page d'accueil.
        $_GET['target'] = 'index';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

        // Se connecter avec le premier utilisateur, administrateur.
        require_once base_path('legacy/php/init/legacy_init.php');
        $this->user = CustomUser::query()->first();
        $this->assertNotNull($this->user);
        $this->actingAs($this->user);
        $authService = new AuthenticationService();
        $authService->login($this->user);
    }

    /**
     * Accède à la page d'accueil en tant qu'un utilisateur authentifié.
     */
    public function testAccessIndexPage(): void
    {
        $this->actingAs($this->user)->assertAuthenticated()->assertAccessLegacyPage('index');
    }

    /**
     * Accède au tableau de bord en tant qu'un utilisateur authentifié.
     */
    public function testAccessDashboardPage(): void
    {
        $this->actingAs($this->user)->assertAuthenticated()->assertAccessLegacyPage('dashboard');
    }

    /**
     * Accède à la page de création de propositions en tant qu'un utilisateur authentifié.
     */
    public function testAccessOcgcProposalCreatePage(): void
    {
        $this->actingAs($this->user)->assertAuthenticated()->assertAccessLegacyPage('back.ocgc_proposal_create');
    }

    /**
     * Accède à la page de gestion de pays en tant qu'utilisateur authentifié.
     */
    public function testPagePaysBackPage(): void
    {
        $pays = $this->user->pays->first();

        $this->assertNotNull($pays);

        $this->actingAs($this->user)->assertAuthenticated()->assertAccessLegacyPage(
            page: 'back.page_pays_back',
            query: ['paysID' => $pays->getKey()]
        );
    }
}
