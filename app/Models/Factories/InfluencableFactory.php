<?php

namespace Roxayl\MondeGC\Models\Factories;

use Roxayl\MondeGC\Models\ChapterResourceable;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Models\Managers\PaysMapManager;
use Roxayl\MondeGC\Models\Patrimoine;
use Roxayl\MondeGC\Models\Pays;
use Illuminate\Support\Collection;

class InfluencableFactory
{
    /**
     * Interface implémentée par les modèles influençables.
     */
    public const contract = Influencable::class;

    /**
     * Ensemble des modèles influençables actifs.
     *
     * @return Collection<int, Influencable>
     */
    public function listEnabled(): Collection
    {
        return collect()
            ->merge(
                ChapterResourceable::query()
                    ->with('chapter')->has('chapter')
                    ->whereRelation('chapter', 'deleted_at', 'IS NULL')
                    ->get()
            )
            ->merge(
                Infrastructure::query()->with('infrastructurable')->whereChInfStatut(Infrastructure::JUGEMENT_ACCEPTED)->get()
            )
            ->merge(
                Patrimoine::query()->whereChPatStatut(Patrimoine::STATUS_ENABLED)->get()
            )
            ->merge(
                Pays::query()->whereChPayPublication(Pays::STATUS_ACTIVE)->get()->map(
                    fn(Pays $pays): PaysMapManager => new PaysMapManager($pays)
                )
            )
            ->filter(function(Influencable $influencable): bool {
                return $influencable->isEnabled();
            });
    }
}
