<?php

namespace Tests\Feature\Legacy;

class VisitorAccessLegacyPageTest extends AccessLegacyPage
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
     * Tester l'accès à la page /index.php du site legacy.
     *
     * @return void
     */
    public function testAccessIndexPage(): void
    {
        $this->assertAccessLegacyPage('index');
    }

    /**
     * Tester l'accès à la page /Page-carte.php du site legacy.
     *
     * @return void
     */
    public function testAccessPageCartePage(): void
    {
        $this->assertAccessLegacyPage('Page-carte');
    }

    /**
     * Tester l'accès à la page de l'OCGC du site legacy.
     *
     * @return void
     */
    public function testAccessOcgcPage(): void
    {
        $this->assertAccessLegacyPage('OCGC');
    }

    /**
     * Tester l'accès à la page des communiqués de l'OCGC du site legacy.
     *
     * @return void
     */
    public function testAccessCommuniquesOcgcPage(): void
    {
        $this->assertAccessLegacyPage('communiques-ocgc');
    }

    /**
     * Tester l'accès à la page /assemblee.php du site legacy.
     *
     * @return void
     */
    public function testAccessAssembleePage(): void
    {
        $this->assertAccessLegacyPage('assemblee');
    }

    /**
     * Tester l'accès à la page /economie.php du site legacy.
     *
     * @return void
     */
    public function testAccessEconomiePage(): void
    {
        $this->assertAccessLegacyPage('economie');
    }

    /**
     * Tester l'accès à la page du Comité Politique du site legacy.
     *
     * @return void
     */
    public function testAccessPolitiquePage(): void
    {
        $this->assertAccessLegacyPage('politique');
    }

    /**
     * Tester l'accès à la page du Comité Culture du site legacy.
     *
     * @return void
     */
    public function testAccessPatrimoinePage(): void
    {
        $this->assertAccessLegacyPage('patrimoine');
    }

    /**
     * Tester l'accès à la page du Comité Histoire du site legacy.
     *
     * @return void
     */
    public function testAccessHistoirePage(): void
    {
        $this->assertAccessLegacyPage('histoire');
    }

    /**
     * Tester l'accès à une page non-existante, dont la requête doit être traitée par le controller legacy.
     *
     * @return void
     */
    public function testAccessNonExistingPage(): void
    {
        $this->assertAccessLegacyPage('page-not-existing', 404);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
