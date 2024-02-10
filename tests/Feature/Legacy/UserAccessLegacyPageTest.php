<?php

namespace Tests\Feature\Legacy;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Services\AuthenticationService;

class UserAccessLegacyPageTest extends AccessLegacyPage
{
    private ?CustomUser $user = null;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

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
        $this->assertAuthenticated();
        $this->markTestIncomplete('Ce test ne fonctionne pas pour le moment.');

        // $this->assertAccessLegacyPage('index');
    }

    /**
     * Accède au tableau de bord en tant qu'un utilisateur authentifié.
     */
    public function testAccessDashboardPage(): void
    {
        $this->assertAuthenticated();
        $this->markTestIncomplete('Ce test ne fonctionne pas pour le moment.');

        // $this->assertAccessLegacyPage('dashboard');
    }

    /**
     * Accède à la page de création de propositions en tant qu'un utilisateur authentifié.
     */
    public function testAccessOcgcProposalCreatePage(): void
    {
        $this->assertAuthenticated();
        $this->markTestIncomplete('Ce test ne fonctionne pas pour le moment.');

        // $this->assertAccessLegacyPage('back.ocgc_proposal_create');
    }

    /**
     * Accède à la page de gestion de pays en tant qu'utilisateur authentifié.
     */
    public function testPagePaysBackPage(): void
    {
        $pays = $this->user->pays->first();

        $this->assertAuthenticated();
        $this->markTestIncomplete('Ce test ne fonctionne pas pour le moment.');

        /* $this->assertAccessLegacyPage(
            page: 'back.page_pays_back',
            query: ['paysID' => $pays->getKey()]
        ); */
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}
