<?php

namespace Tests\Feature\Legacy;

class VisitorAccessesLegacyPageTest extends AccessLegacyPage
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
    }

    /**
     * @param string $page Page legacy à tester ({@see pages}).
     * @param int $assertStatus
     */
    protected function accessLegacyPage(string $page, int $assertStatus = 200): void
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
     * Tester l'accès à la page /index.php du site legacy.
     *
     * @return void
     */
    public function testAccessIndexPage(): void
    {
        $this->accessLegacyPage('index');
    }

    /**
     * Tester l'accès à la page /Page-carte.php du site legacy.
     *
     * @return void
     */
    public function testAccessPageCartePage(): void
    {
        $this->accessLegacyPage('Page-carte');
    }

    /**
     * Tester l'accès à la page de l'OCGC du site legacy.
     *
     * @return void
     */
    public function testAccessOcgcPage(): void
    {
        $this->accessLegacyPage('OCGC');
    }

    /**
     * Tester l'accès à la page des communiqués de l'OCGC du site legacy.
     *
     * @return void
     */
    public function testAccessCommuniquesOcgcPage(): void
    {
        $this->accessLegacyPage('communiques-ocgc');
    }

    /**
     * Tester l'accès à la page /assemblee.php du site legacy.
     *
     * @return void
     */
    public function testAccessAssembleePage(): void
    {
        $this->accessLegacyPage('assemblee');
    }

    /**
     * Tester l'accès à la page /economie.php du site legacy.
     *
     * @return void
     */
    public function testAccessEconomiePage(): void
    {
        $this->accessLegacyPage('economie');
    }

    /**
     * Tester l'accès à la page du Comité Politique du site legacy.
     *
     * @return void
     */
    public function testAccessPolitiquePage(): void
    {
        $this->accessLegacyPage('politique');
    }

    /**
     * Tester l'accès à la page du Comité Culture du site legacy.
     *
     * @return void
     */
    public function testAccessPatrimoinePage(): void
    {
        $this->accessLegacyPage('patrimoine');
    }

    /**
     * Tester l'accès à la page du Comité Histoire du site legacy.
     *
     * @return void
     */
    public function testAccessHistoirePage(): void
    {
        $this->accessLegacyPage('histoire');
    }

    /**
     * Tester l'accès à une page non-existante, dont la requête doit être traitée par le controller legacy.
     *
     * @return void
     */
    public function testAccessNonExistingPage(): void
    {
        $this->accessLegacyPage('page-not-existing', 404);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
