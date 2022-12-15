<?php

namespace Database\Factories;

use Roxayl\MondeGC\Models\Chapter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'order' => 1,
            'name' => $this->faker->name(),
            'summary' => $this->faker->realText(220),
            'content' => '<p>' . nl2br($this->faker->realText(800)) . '</p>'
                       . '<p>' . nl2br($this->faker->realText(400)) . '</p>',
            'starting_date' => Carbon::now(),
            'ending_date' => null,
        ];
    }
}
