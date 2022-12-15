<?php

namespace Database\Factories;

use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Personnage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaysFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pays::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'ch_pay_label'       => 'pays',
            'ch_pay_publication' => 1,
            'ch_pay_emplacement' => $this->fetchFreeSpot(),
            'ch_pay_continent'   => $this->faker->randomElement(['Oceania', 'Philicie', 'Aurinea', 'Volcania']),
            'ch_pay_lien_forum'  => 'https://www.forum-gc.com/',
            'ch_pay_nom'         => $this->faker->country(),
            'ch_pay_devise'      => 'Ici, tout est à construire...',
            'ch_pay_date'        => now(),
            'ch_pay_mis_jour'    => now(),
            'ch_pay_nb_update'   => 0,
        ];
    }

    /**
     * Renvoie un numéro d'emplacement disponible aléatoirement, en fonction des emplacements déjà occupés.
     *
     * @return int Un numéro d'emplacement disponible.
     */
    private function fetchFreeSpot(): int
    {
        $slotRange = Pays::getSlotRange();
        $allSlots = collect(range($slotRange[0], $slotRange[1]));

        $occupiedSlots = Pays::select('ch_pay_emplacement')
            ->get()
            ->pluck('ch_pay_emplacement');

        $availableSlots = $allSlots->diff($occupiedSlots);

        return $availableSlots->random();
    }

    public function configure()
    {
        return $this->afterCreating(function (Pays $pays) {
            Personnage::factory()->create([
                'entity' => 'pays',
                'entity_id' => $pays->ch_pay_id,
            ]);
        });
    }
}
