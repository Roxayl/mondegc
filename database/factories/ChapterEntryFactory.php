<?php

namespace Database\Factories;

use App\Models\ChapterEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChapterEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->text(),
            'media_type' => $this->faker->chapterEntryMediaType(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (ChapterEntry $entry) {
            if($entry->media_type !== null) {
                $entry->media_data = $this->faker->chapterEntryMediaData($entry->media_type);
            }
        });
    }
}
