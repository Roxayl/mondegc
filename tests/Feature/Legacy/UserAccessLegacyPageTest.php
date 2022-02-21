<?php

namespace Tests\Feature\Legacy;

use App\Models\CustomUser;
use App\Services\AuthenticatorService;

class UserAccessLegacyPageTest extends AccessLegacyPage
{
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
        $user = CustomUser::first();
        $this->actingAs($user);
        $authService = new AuthenticatorService();
        $authService->login($user);
    }

    /**
     * @param string $page Page legacy à tester en tant qu'utilisateur authentifié.
     * @param int $assertStatus
     */
    protected function accessLegacyPageLogged(string $page, int $assertStatus = 200): void
    {
        $uri = '/' . str_replace('.', '/', $page) . '.php';

        // Initialiser l'application legacy.
        $_GET['target'] = $page;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

        // Accéder à la page legacy et vérifier la réponse.
        $response = $this->get($uri);
        $response->assertStatus($assertStatus);
    }

    /**
     * Accède à la page d'accueil en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessIndexPage(): void
    {
        $this->accessLegacyPageLogged('index');
    }

    /**
     * Accède au tableau de bord en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessDashboardPage(): void
    {
        $this->accessLegacyPageLogged('dashboard');
    }

    /**
     * Accède à la page de création de propositions en tant qu'un utilisateur authentifié.
     *
     * @return void
     */
    public function testAccessOcgcProposalCreatePage(): void
    {
        $this->accessLegacyPageLogged('back.ocgc_proposal_create');
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
