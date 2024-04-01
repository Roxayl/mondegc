<?php

declare(strict_types=1);

namespace Tests\Feature\Legacy;

use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

class VisitorAccessLegacyPageTest extends AccessLegacyPage
{
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
     * Tester l'accès à la page des dernières MAJs.
     */
    public function testAccessLastMAJPage(): void
    {
        $this->assertAccessLegacyPage('last_MAJ');
    }

    /**
     * Tester l'accès à la page des dernières MAJ sous forme d'iframe.
     */
    public function testAccessLastMAJIframePage(): void
    {
        $this->assertAccessLegacyPage('last_MAJ_iframe');
    }

    /**
     * Tester l'accès à la page d'un pays du site legacy.
     */
    public function testAccessPaysPage(): void
    {
        $pays = Pays::query()->first();

        $this->assertNotNull($pays);

        $this->assertAccessLegacyPage(page: 'page-pays', query: ['ch_pay_id' => $pays->getKey()]);
    }

    /**
     * Tester l'accès à la page d'une ville du site legacy.
     */
    public function testAccessVillePage(): void
    {
        $ville = Ville::query()->first();

        $this->assertNotNull($ville);

        $this->assertAccessLegacyPage(page: 'page-ville', query: ['ch_ville_id' => $ville->getKey()]);
    }

    /**
     * Tester l'accès à la page de la liste des infrastructures du site legacy.
     */
    public function testAccessListeInfrastructuresPage(): void
    {
        $this->assertAccessLegacyPage('liste infrastructures');
    }

    /**
     * Tester l'accès à une page non-existante, dont la requête doit être traitée par le controller legacy.
     */
    public function testAccessInexistentPage(): void
    {
        $this->assertAccessLegacyPage('page-not-found', 404);
    }
}
