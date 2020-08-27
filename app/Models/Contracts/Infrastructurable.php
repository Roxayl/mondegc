<?php

namespace App\Models\Contracts;

use App\Models\InfrastructureGroupe;
use App\Models\InfrastructureOfficielle;

interface Infrastructurable
{
    /* Défini dans le trait Infrastructurable. */

    public function infrastructures();

    /* Défini dans le trait InfrastructurablePresenter. */

    public function accessorUrl();

    public function selectGroupRouteParameter();

    public function createRouteParameter(
        InfrastructureGroupe $infrastructureGroupe,
        ?InfrastructureOfficielle $infrastructureOfficielle);
}
