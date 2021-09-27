<?php

namespace Database\Factories;

use App\Models\CustomUser;
use App\Models\Roleplay;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleplayFactory extends Factory
{
    private static int $defaultUser = 28; // romu23

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
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => auth()->check() ? auth()->user()->id : self::$defaultUser,
            'starting_date' => now(),
            'ending_date' => null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Roleplay $rp) {
            $user = CustomUser::find(self::$defaultUser);
            $rp->users()->attach($user);
        });
    }
}
