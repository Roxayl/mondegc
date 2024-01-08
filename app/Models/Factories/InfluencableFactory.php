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
     * @return Collection<int, Influencable>
     */
    public static function list(): Collection
    {
        $models = ChapterResourceable::all()->merge(Infrastructure::all())->merge(Patrimoine::all());

        $paysList = Pays::all();
        foreach($paysList as $pays) {
            $models = $models->add(new PaysMapManager($pays));
        }

        return $models;
    }
}
