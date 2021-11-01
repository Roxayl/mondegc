<?php

namespace Tests\Feature\Legacy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAccessesIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array|string[] Pages legacy à tester.
     */
    private array $pages = [
        'index',
        'assemblee',
    ];

    /**
     * Définit les variables globales.
     */
    public function __construct()
    {
        parent::__construct();

        // Set global variables.
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['IP_ADDRESS'] = '192.168.1.1';
    }

    /**
     * Tester l'accès à diverses pages legacy, en vérifiant simplement qu'une réponse 200 est donnée.
     *
     * @return void
     */
    public function testAccessLegacyPage()
    {
        // Ajouter les données à la base.
        // @TODO : il faudrait finaliser le seeding complet de la base, sinon ça provoque des erreurs.
        // $this->seed();

        // Tester les pages une par une...
        foreach($this->pages as $page) {
            $uri = '/' . str_replace('.', '/', $page) . '.php';

            $_GET['target'] = $page;
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_SERVER['QUERY_STRING'] = '';

            $response = $this->get($uri);

            $response->assertStatus(200);
        }
    }
}
