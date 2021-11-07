<?php

namespace Database\Factories;

use App\Models\CustomUser;
use App\Models\Pays;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VilleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ville::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'ch_vil_paysID' => Pays::inRandomOrder()->first()->ch_pay_id,
            'ch_vil_user'   => CustomUser::inRandomOrder()->first()->ch_use_id,
            'ch_vil_label'  => 'ville',
            'ch_vil_date_enregistrement' => Carbon::now(),
            'ch_vil_mis_jour' => Carbon::now(),
            'ch_vil_nb_update' => 0,
            'ch_vil_coord_X' => rand(-30, 30),
            'ch_vil_coord_Y' => rand(-30, 30),
            'ch_vil_type_jeu' => $this->faker->randomElement(['CXL', 'CL', 'SC4']),
            'ch_vil_nom' => $this->faker->name(),
            'ch_vil_armoiries' => null,
        ];
    }
}
