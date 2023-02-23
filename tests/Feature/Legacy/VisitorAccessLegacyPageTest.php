<?php

namespace Tests\Feature\Legacy;

class VisitorAccessLegacyPageTest extends AccessLegacyPage
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Tester l'accès à la page /index.php du site legacy.
     */
    public function testAccessIndexPage(): void
    {
        $this->assertAccessLegacyPage('index');
    }

    /**
     * Tester l'accès à la page /Page-carte.php du site legacy.
     */
    public function testAccessPageCartePage(): void
    {
        $this->assertAccessLegacyPage('Page-carte');
    }

    /**
     * Tester l'accès à la page de l'OCGC du site legacy.
     */
    public function testAccessOcgcPage(): void
    {
        $this->assertAccessLegacyPage('OCGC');
    }

    /**
     * Tester l'accès à la page des communiqués de l'OCGC du site legacy.
     */
    public function testAccessCommuniquesOcgcPage(): void
    {
        $this->assertAccessLegacyPage('communiques-ocgc');
    }

    /**
     * Tester l'accès à la page /assemblee.php du site legacy.
     */
    public function testAccessAssembleePage(): void
    {
        $this->assertAccessLegacyPage('assemblee');
    }

    /**
     * Tester l'accès à la page /economie.php du site legacy.
     */
    public function testAccessEconomiePage(): void
    {
        $this->assertAccessLegacyPage('economie');
    }

    /**
     * Tester l'accès à la page du Comité Politique du site legacy.
     */
    public function testAccessPolitiquePage(): void
    {
        $this->assertAccessLegacyPage('politique');
    }

    /**
     * Tester l'accès à la page du Comité Culture du site legacy.
     */
    public function testAccessPatrimoinePage(): void
    {
        $this->assertAccessLegacyPage('patrimoine');
    }

    /**
     * Tester l'accès à la page du Comité Histoire du site legacy.
     */
    public function testAccessHistoirePage(): void
    {
        $this->assertAccessLegacyPage('histoire');
    }

    /**
     * Tester l'accès à une page non-existante, dont la requête doit être traitée par le controller legacy.
     */
    public function testAccessNonExistingPage(): void
    {
        $this->assertAccessLegacyPage('page-not-existing', 404);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}
