<?php

namespace Tests\Feature\Legacy;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAccessesIndexPageTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        // Set global variables.
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['target'] = 'index';
        $_SERVER['QUERY_STRING'] = '';
        $_SERVER['IP_ADDRESS'] = '192.168.1.1';
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessIndex()
    {
        $response = $this->get('/index.php');

        $response->assertStatus(200);
    }
}
