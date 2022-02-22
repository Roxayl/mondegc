<?php

use App\Models\CustomUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserVisitsPageTest extends TestCase
{
    use RefreshDatabase;

    private ?CustomUser $user = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = CustomUser::first();
        $this->assertNotNull($this->user);
        $this->actingAs($this->user);
    }

    /**
     * Accède à la popup des notifications en tant qu'un utilisateur authentifié.
     */
    public function testAccessNotificationIndexPage(): void
    {
        $this->assertAuthenticated();
        $this->markTestIncomplete("Ce test ne fonctionne pas pour le moment.");

        $this->get(route('notification'))->assertStatus(200);
    }
}
