<?php

namespace App\Models\Traits;

use App\Models\Influence;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait Influencable
{
    public function influences()
    {
        return $this->morphMany(Influence::class, 'influencable');
    }

    public function getGeneratedResources() : Collection
    {
        $influences = $this->influences()
            ->where('generates_influence_at', '<', Carbon::now());

        return $this->filterInfluences($influences);
    }

    public function getFinalResources() : Collection
    {
        $influences = $this->influences();

        return $this->filterInfluences($influences);
    }

    private function filterInfluences($influences) : Collection
    {
        $select = '';
        $resources = config('enums.resources');

        $arrayKeys = array_keys($resources);
        foreach($resources as $key => $resource) {
            $select .= "COALESCE(SUM($resource), 0) AS $resource";
            if (end($arrayKeys) !== $key) $select .= ', ';
        }

        $influences = $influences->selectRaw($select);

        return collect($influences->get()->first()->toArray());
    }

    public function efficiencyRate() : int
    {
        $avgRate = [];
        $currentResources = $this->getGeneratedResources();
        $finalResources   = $this->getFinalResources();

        foreach($currentResources as $key => $resource) {
            $currentValue = abs($currentResources[$key]);
            $finalValue   = abs($finalResources[$key]);
            $thisRate = (float)($finalValue === 0 ? 0 : $currentValue / $finalValue);
            $avgRate[] = $thisRate;
        }

        // Supprime les valeurs de taux égales à 0.
        $avgRate = array_filter($avgRate);

        if(!count($avgRate)) return 0;
        return (int)round(array_sum($avgRate) / count($avgRate) * 100);
    }

    /**
     * Supprime les anciennes entrées dans la table 'influences', lorsqu'on veut générer
     * à nouveau l'influence d'un influencable, par exemple.
     * @param \Closure $f Fonction de vérification.
     * @return bool Renvoie <code>true</code> lorsque les influences ont été supprimées ;
     *              <code>false</code> sinon.
     */
    protected function removeOldInfluenceRows(\Closure $f = null) : bool
    {
        $delete = false;

        if($f !== null) {
            $delete = $f();
        }
        if(!$delete) {
            $delete = !empty($this->influences());
        }

        // Supprime les entrées d'influence s'il existait déjà des entrées dans la BDD,
        // ou si la fonction de vérification renvoie 'true'.
        if($delete) {
            foreach($this->influences as $influence) {
                $influence->delete();
            }
        }

        return $delete;
    }
}
