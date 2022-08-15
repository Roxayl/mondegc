<?php

namespace App\Models\Factories;

use App\Models\ChapterResourceable;
use App\Models\Contracts\Influencable;
use App\Models\Infrastructure;
use App\Models\Managers\PaysMapManager;
use App\Models\Patrimoine;
use App\Models\Pays;
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
