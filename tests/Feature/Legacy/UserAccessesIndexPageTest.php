<?php

namespace Tests\Feature\Legacy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAccessesIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Définit les variables globales.
     */
    public function __construct()
    {
        parent::__construct();

        $this->seeded = false;

        // Set global variables.
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['IP_ADDRESS'] = '192.168.1.1';
    }

    /**
     * @param string $page Page legacy à tester ({@see pages}).
     * @param int $assertStatus
     */
    private function accessLegacyPage(string $page, int $assertStatus = 200): void
    {
        $uri = '/' . str_replace('.', '/', $page) . '.php';

        $_GET['target'] = $page;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';

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
     * Tester l'accès à la page /Page-carte.php du site legacy.
     *
     * @return void
     */
    public function testAccessPageCartePage(): void
    {
        $this->accessLegacyPage('Page-carte');
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
}
