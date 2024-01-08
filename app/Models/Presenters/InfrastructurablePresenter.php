<?php

namespace Roxayl\MondeGC\Models\Presenters;

use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Models\InfrastructureGroupe;
use Roxayl\MondeGC\Models\InfrastructureOfficielle;

trait InfrastructurablePresenter
{
    /**
     * @return array
     */
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
     * @inheritDoc
     */
    public function selectGroupRouteParameter(): array
    {
        $data = $this->getInfrastructurableData();
        $routeParameters = [];

        foreach($data as $key => $value) {
            $routeParameters[Str::camel($key)] = $value;
        }

        return $routeParameters;
    }

    /**
     * @inheritDoc
     */
    public function createRouteParameter(
        InfrastructureGroupe $infrastructureGroupe,
        ?InfrastructureOfficielle $infrastructureOfficielle): array
    {
        $params = $this->selectGroupRouteParameter();
        $params['infrastructure_groupe_id'] = $infrastructureGroupe->id;
        if(! is_null($infrastructureOfficielle)) {
            $params['infrastructure_officielle_id'] = $infrastructureOfficielle->id;
        }
        return $params;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return Infrastructure::getUrlParameterFromMorph(self::class);
    }
}
