<?php

namespace Roxayl\MondeGC\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Roxayl\MondeGC\Models\InfrastructureGroupe;
use Roxayl\MondeGC\Models\InfrastructureOfficielle;

interface Infrastructurable extends Enable
{
    /* ===================================
     *  Méthodes définies dans le trait Infrastructurable.
     * =================================== */

    /**
     * Relation donnant les infrastructures acceptées pour l'infrastructurable.
     *
     * @return MorphMany
     */
    public function infrastructures(): MorphMany;

    /**
     * Relation donnant toutes les infrastructures, y compris refusées ou en attente de
     * validation, pour l'infrastructurable.
     *
     * @return MorphMany
     */
    public function infrastructuresAll(): MorphMany;

    /**
     * Supprime toutes les infrastructures de l'infrastructurable concerné.
     */
    public function deleteAllInfrastructures(): void;


    /* ===================================
     *  Méthodes définies dans le trait InfrastructurablePresenter.
     * =================================== */

    /**
     * Donne les paramètres dans l'URL pour la route 'infrastructure.select_group'
     * pour un modèle {@see Infrastructurable} donné.
     *
     * @return array Un array contenant les paramètres de la route.
     */
    public function selectGroupRouteParameter(): array;

    /**
     * Donne les paramètres dans l'URL pour la route 'infrastructure.create' pour un modèle
     * {@see Infrastructurable} donné.
     * Il est nécessaire de spécifier un groupe d'infrastructure {@see InfrastructureGroupe},
     * et il est possible de définir l'infrastructure officielle
     *
     * {@see InfrastructureOfficielle} sélectionnée, le cas échéant.
     * @param InfrastructureGroupe $infrastructureGroupe Groupe d'infrastructure choisi.
     * @param InfrastructureOfficielle|null $infrastructureOfficielle Infrastructure
     *        officielle sélectionnée. Facultatif si l'utilisateur n'a pas choisi d'infra
     *        officielle.
     * @return array Un array contenant les paramètres de la route.
     */
    public function createRouteParameter(
        InfrastructureGroupe $infrastructureGroupe,
        ?InfrastructureOfficielle $infrastructureOfficielle
    ): array;

    public function getType(): string;


    /* ===================================
     *  Méthodes définies dans les presenters des modèles.
     * =================================== */

    /**
     * Donne le lien d'accès vers l'infrastructurable désigné.
     * @return string URL vers la page de présentation de l'infrastructurable.
     */
    public function accessorUrl(): string;

    public function backAccessorUrl(): string;

    public function getFlag(): string;

    public function getName(): string;


    /* ===================================
     *  Méthodes définies dans les modèles.
     * =================================== */

    public function getUsers(): Collection;
}
