<?php

namespace App\Services;

use App\Models\Pays;
use Illuminate\Database\Eloquent\Collection;

class EconomyService
{
    /**
     * @return array<string, float>
     */
    public static function resourcesPrefilled(): array
    {
        $return = [];

        foreach(config('enums.resources') as $resource) {
            $return[$resource] = 0;
        }

        return $return;
    }

    /**
     * @param string|null $sortBy Clé dans laquelle trier les ressources.
     * @return array<int, array<string, mixed>>
     */
    public static function getPaysResources(?string $sortBy = null): array
    {
        /** @var Collection<Pays> $allPays */
        $allPays = Pays::visible()->get();

        $paysResources = [];

        /** @var Pays $pays */
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
