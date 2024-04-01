<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Roxayl\MondeGC\Models\ChapterResourceable;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Services\Gameplay\EconomyService;

class ChapterResourceableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChapterResourceable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $resources = EconomyService::resourcesPrefilled();

        foreach ($resources as $resource => $value) {
            $resources[$resource] = rand(10, 1000);
        }

        return $resources + [
            'resourceable_type' => ChapterResourceable::getActualClassNameForMorph(Pays::class),
            'resourceable_id' => Pays::inRandomOrder()->first()->ch_pay_id,
        ];
    }
}
