<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Organisation;
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
    }

    /**
     * Accède à la popup des notifications en tant qu'un utilisateur authentifié.
     */
    public function testAccessNotificationIndexPage(): void
    {
        $this->markTestIncomplete('WIP');
        /* $this->actingAs($this->user)
            ->assertAuthenticated()
            ->get(route('notification'))
            ->assertStatus(200); */
    }

    public function testAccessOrganisationIndexPage(): void
    {
        $organisation = Organisation::query()->first();
        $this->assertNotNull($organisation);

        $this->actingAs($this->user)
            ->assertAuthenticated()
            ->get(route('organisation.showslug', $organisation->showRouteParameter()))
            ->assertStatus(200);
    }
}
