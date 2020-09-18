<?php

namespace App\Services;

use App\Models\Pays;

class EconomyService
{
    public static function resourcesPrefilled() : array
    {
        $return = [];

        foreach(config('enums.resources') as $resource) {
            $return[$resource] = 0;
        }

        return $return;
    }

    public static function getPaysResources($sortBy = null) : array
    {
        $allPays = Pays::where('ch_pay_publication', Pays::STATUS_ACTIVE)->get();

        $paysResources = [];

        foreach($allPays as $pays) {
            $paysResources[$pays['ch_pay_id']]['ch_pay_id'] = $pays->ch_pay_id;
            $paysResources[$pays['ch_pay_id']]['ch_pay_nom'] = $pays->ch_pay_nom;
            $paysResources[$pays['ch_pay_id']]['ch_pay_lien_imgdrapeau'] = $pays->ch_pay_lien_imgdrapeau;
            $paysResources[$pays['ch_pay_id']]['resources'] = $pays->resources();
            $paysResources[$pays['ch_pay_id']]['alliance'] = $pays->alliance();
        }

        if($sortBy !== null) {
            usort($paysResources, function($a, $b) use($sortBy) {
                return $a['resources'][$sortBy] > $b['resources'][$sortBy] ? -1 : 1;
            });
        }

        return $paysResources;
    }
}
