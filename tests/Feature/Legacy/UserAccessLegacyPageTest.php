<?php

namespace Tests\Feature\Legacy;

use App\Models\CustomUser;
use App\Services\AuthenticationService;

class UserAccessLegacyPageTest extends AccessLegacyPage
{
    private ?CustomUser $user = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Initialiser l'application legacy, depuis la page d'accueil.
        $_GET['target'] = 'index';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

        // Se connecter avec le premier utilisateur, administrateur.
        require_once(base_path('legacy/php/init/legacy_init.php'));
        $this->user = CustomUser::first();
        $this->assertNotNull($this->user);
        $this->actingAs($this->user);
        $authService = new AuthenticationService();
        $authService->login($this->user);
    }

    /**
     * Accède à la page d'accueil en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessIndexPage(): void
    {
        $this->assertAuthenticated();
        $this->assertAccessLegacyPage('index');
    }

    /**
     * Accède au tableau de bord en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessDashboardPage(): void
    {
        $this->assertAuthenticated();
        $this->assertAccessLegacyPage('dashboard');
    }

    /**
     * Accède à la page de création de propositions en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessOcgcProposalCreatePage(): void
    {
        $this->assertAuthenticated();
        $this->assertAccessLegacyPage('back.ocgc_proposal_create');
    }

    /**
     * Accède à la page de gestion de pays en tant qu'utilisateur authentifié.
     */
    public function testPagePaysBackPage(): void
    {
        $pays = $this->user->pays->first();

        $this->assertAuthenticated();
        $this->markTestIncomplete("Ce test ne fonctionne pas pour le moment.");

        $this->assertAccessLegacyPage(
            page: 'back.page_pays_back',
            query: ['paysID' => $pays->getKey()]
        );
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
