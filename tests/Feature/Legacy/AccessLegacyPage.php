<?php

namespace Tests\Feature\Legacy;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AccessLegacyPage extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected bool $seed = true;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Définit les variables globales.
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['IP_ADDRESS'] = '192.168.1.1';
    }

    /**
     * @param string $page Page legacy à tester ({@see pages}).
     * @param int $assertStatus
     * @param array $query Paramètres passés par l'URL.
     */
    protected function assertAccessLegacyPage(string $page, int $assertStatus = 200, array $query = []): void
    {
        $this->getLegacyPage($page, $query)->assertStatus($assertStatus);
    }

    /**
     * @param string $page
     * @param array $query
     * @return TestResponse
     */
    protected function getLegacyPage(string $page, array $query = []): TestResponse
    {
        $queryString = http_build_query($query);

        $uri = '/' . str_replace('.', '/', $page) . '.php';
        $fullUri = $uri . '?' .  $queryString;

        // Initialiser l'application legacy.
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = $queryString;
        $_GET['target'] = $page;
        $_GET = $_GET + $query;
        $_REQUEST = $_GET + $_POST;

        // Accéder à la page legacy et vérifier la réponse.
        return $this->get($fullUri, $query);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
