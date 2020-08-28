<?php

namespace App\Models\Contracts;

use App\Models\InfrastructureGroupe;
use App\Models\InfrastructureOfficielle;

interface Infrastructurable
{
    /* Défini dans le trait Infrastructurable. */

    public function infrastructures();

    public function infrastructuresAll();


    /* Défini dans le trait InfrastructurablePresenter. */

    public function selectGroupRouteParameter();

    public function createRouteParameter(
        InfrastructureGroupe $infrastructureGroupe,
        ?InfrastructureOfficielle $infrastructureOfficielle);

    public function getType() : string;


    /* Défini dans les presenters des modèles. */

    /**
     * Donne le lien d'accès vers l'infrastructurable désigné.
     * @return string URL vers la page de présentation de l'infrastructurable.
     */
    public function accessorUrl() : string;

    public function backAccessorUrl() : string;

    public function getFlag() : string;

    public function getName() : string;


    /* Défini dans les modèles. */

    public function getUsers();
}
