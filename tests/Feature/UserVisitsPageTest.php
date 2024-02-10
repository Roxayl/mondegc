<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Roxayl\MondeGC\Models\CustomUser;
use Tests\TestCase;

class UserVisitsPageTest extends TestCase
{
    use RefreshDatabase;

    private ?CustomUser $user = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = CustomUser::query()->first();
        $this->assertNotNull($this->user);
        $this->actingAs($this->user);
    }

    /**
     * Accède à la popup des notifications en tant qu'un utilisateur authentifié.
     */
    public function testAccessNotificationIndexPage(): void
    {
        $this->assertAuthenticated();
        $this->markTestIncomplete('Ce test ne fonctionne pas pour le moment.');

        // $this->get(route('notification'))->assertStatus(200);
    }
}
