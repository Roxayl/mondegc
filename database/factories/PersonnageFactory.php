<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Roxayl\MondeGC\Models\Personnage;

class PersonnageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Personnage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'nom_personnage' => $this->faker->lastName(),
            'predicat' => $this->faker->randomElement(['M.', 'Président', 'Chancelier', 'Son Altesse']),
            'prenom_personnage' => $this->faker->firstName(),
            'titre_personnage' => 'Dirigeant du pays',
        ];
    }
}
