<?php

namespace Roxayl\MondeGC\Services;

use Illuminate\Database\Eloquent\Collection;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Contracts\Resourceable;

class EconomyService
{
    /**
     * @return array<string, float>
     */
    public static function resourcesPrefilled(): array
    {
        $return = [];

        foreach(Resource::cases() as $resource) {
            $return[$resource->value] = 0;
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

    /**
     * Génère la somme des ressources générées par un ensemble d'influençables.
     *
     * @param Influencable[] $influencables
     * @return float[] Un tableau de ressources contenant la somme  les ressources générées un
     *                 ensemble d'influençables.
     */
    public static function sumGeneratedResourcesFromInfluencables(iterable $influencables): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        foreach($influencables as $chapterResource) {
            $generatedResources = $chapterResource->getGeneratedResources();
            foreach(Resource::cases() as $resource) {
                $sumResources[$resource->value] += $generatedResources[$resource->value];
            }
        }

        return $sumResources;
    }

    /**
     * Génère la somme des ressources générées par un ensemble de ressourceables.
     *
     * @param Resourceable[] $resourceables
     * @return float[] Un tableau de ressources contenant la somme des ressources générées par un ensemble
     *                 de ressourceables.
     */
    public static function sumGeneratedResourcesFromResourceables(iterable $resourceables): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        foreach($resourceables as $chapterResource) {
            $generatedResources = $chapterResource->resources();
            foreach(Resource::cases() as $resource) {
                $sumResources[$resource->value] += $generatedResources[$resource->value];
            }
        }

        return $sumResources;
    }
}
