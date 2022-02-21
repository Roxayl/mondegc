<?php

namespace Tests\Feature\Legacy;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessLegacyPage extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

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
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
