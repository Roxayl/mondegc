<?php

namespace App\Models\Presenters;

use App\Models\Contracts\Infrastructurable;
use App\Models\Infrastructure;
use App\Models\InfrastructureGroupe;
use App\Models\InfrastructureOfficielle;

trait InfrastructurablePresenter
{
    protected function getInfrastructurableData() : array
    {
        $fieldPrimaryKey = $this->primaryKey;
        return [
            'infrastructurable_type' => Infrastructure::
                getUrlParameterFromMorph(self::class),
            'infrastructurable_id' => $this->$fieldPrimaryKey,
        ];
    }

    /**
     * Donne le lien d'accès vers l'infrastructurable désigné.
     * @return string URL vers la page de présentation de l'infrastructurable.
     */
    public function accessorUrl() : string
    {
        $infrastructurableData = $this->getInfrastructurableData();
        $url = '';
        switch($infrastructurableData['infrastructurable_type']) {
            case 'ville':
                $url = url("page-ville.php?villeID=" .
                    $infrastructurableData['infrastructurable_id']);
                break;
            case 'organisation':
                $url = route('organisation.showslug',
                    $this->showRouteParameter());
                break;
            case 'pays':
                $url = url('page-pays.php?ch_pay_id=' .
                    $infrastructurableData['infrastructurable_id']);
                break;
        }
        return $url;
    }

    /**
     * Donne les paramètres dans l'URL pour la route 'infrastructure.select_group'
     * pour un modèle {@see \App\Models\Contracts\Infrastructurable} donné.
     * @return array Un array contenant les paramètres de la route.
     */
    public function selectGroupRouteParameter() : array
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
        ?InfrastructureOfficielle $infrastructureOfficielle) : array
    {
        $fieldPrimaryKey = $this->primaryKey;
        $params = $this->getInfrastructurableData();
        $params['infrastructure_groupe_id'] = $infrastructureGroupe->id;
        if(!is_null($infrastructureOfficielle)) {
            $params['infrastructure_officielle_id'] = $infrastructureOfficielle->id;
        }
        return $params;
    }
}
