<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Roleplay;

class RoleplayFactory extends Factory
{
    /**
     * @var string Nom qualifié de la classe d'un organisateur de roleplay ({@see Roleplayable}) par défaut.
     */
    private static string $defaultOrganizerType = Pays::class;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Roleplay::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->eventName(),
            'description' => $this->faker->text(),
            'starting_date' => now(),
            'ending_date' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Roleplay $rp) {

            $type = self::$defaultOrganizerType;

            // Lie ce roleplay à un roleplayable par défaut.
            /** @var Roleplayable $organizer */
            $organizer = call_user_func([$type, 'inRandomOrder'])->first();
            $rp->addOrganizer($organizer);

            // Lie ce roleplay à une organisation par défaut.
            /** @var Organisation|null $organisation */
            if( ($organisation = Organisation::inRandomOrder()->first()) !== null) {
                $rp->addOrganizer($organisation);
            }

        });
    }
}
