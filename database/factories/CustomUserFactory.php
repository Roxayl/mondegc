<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\CustomUser;

class CustomUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'ch_use_acces' => 1,
            'ch_use_date' => Carbon::now(),
            'ch_use_last_log' => Carbon::now(),
            'last_activity' => Carbon::now(),
            'ch_use_login' => 'User_' . Str::random(5),
            'ch_use_password' => md5('password' . config('legacy.salt')),
            'ch_use_mail' => $this->faker->email(),
            'ch_use_statut' => CustomUser::MEMBER,
        ];
    }
}
