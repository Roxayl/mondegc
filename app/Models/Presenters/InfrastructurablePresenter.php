<?php

namespace App\Models\Presenters;

use App\Models\Infrastructure;
use App\Models\InfrastructureGroupe;
use App\Models\InfrastructureOfficielle;

trait InfrastructurablePresenter
{
    protected function getInfrastructurableData(): array
    {
        $fieldPrimaryKey = $this->primaryKey;
        return [
            'infrastructurable_type' => Infrastructure::
                getUrlParameterFromMorph(self::class),
            'infrastructurable_id' => $this->$fieldPrimaryKey,
        ];
    }

    /**
     * Donne les paramètres dans l'URL pour la route 'infrastructure.select_group'
     * pour un modèle {@see \App\Models\Contracts\Infrastructurable} donné.
     * @return array Un array contenant les paramètres de la route.
     */
    public function selectGroupRouteParameter(): array
    {
        return $this->getInfrastructurableData();
    }

    /**
     * Donne les paramètres dans l'URL pour la route 'infrastructure.create'
     * pour un modèle {@see \App\Models\Contracts\Infrastructurable} donné.
     * Il est nécessaire de spécifier un groupe d'infrastructure {@see InfrastructureGroupe},
     * et il est possible de définir l'infrastructure officielle
     * {@see InfrastructureOfficielle} sélectionnée, le cas échéant.
     * @param InfrastructureGroupe $infrastructureGroupe Groupe d'infrastructure choisi.
     * @param InfrastructureOfficielle|null $infrastructureOfficielle Infrastructure
     *        officielle sélectionnée. Facultatif si l'utilisateur n'a pas choisi d'infra
     *        officielle.
     * @return array Un array contenant les paramètres de la route.
     */
    public function createRouteParameter(
        InfrastructureGroupe $infrastructureGroupe,
        ?InfrastructureOfficielle $infrastructureOfficielle): array
    {
        $fieldPrimaryKey = $this->primaryKey;
        $params = $this->getInfrastructurableData();
        $params['infrastructure_groupe_id'] = $infrastructureGroupe->id;
        if(!is_null($infrastructureOfficielle)) {
            $params['infrastructure_officielle_id'] = $infrastructureOfficielle->id;
        }
        return $params;
    }

    public function getType(): string
    {
        return Infrastructure::getUrlParameterFromMorph(self::class);
    }
}
