<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterEntry;
use App\Models\ChapterResourceable;
use App\Models\CustomUser;
use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Roleplay;
use App\Models\Ville;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleplaySeeder extends Seeder
{
    private Generator $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** Nombre de chapitres par roleplay à générer. */
        $chapterCount = 4;

        /** @var CustomUser $user */
        $user = CustomUser::inRandomOrder()->first();

        DB::transaction(function() use ($user, $chapterCount) {
            Roleplay::factory()
                ->sequence(
                    ['ending_date' => null],
                    ['ending_date' => now()->addDays(rand(7, 30))]
                )
                ->has(
                    Chapter::factory()
                        ->count($chapterCount)
                        ->sequence(function($sequence) use ($user, $chapterCount) {
                            $i = ($sequence->index % $chapterCount) + 1;
                            return [
                                'user_id' => $user,
                                'order' => $i
                            ];
                        })
                        ->has(
                            ChapterResourceable::factory()
                                ->state(function(array $attributes, Chapter $chapter) {
                                    return ['chapter_id' => $chapter->id];
                                })
                                ->count(rand(0, 3))
                                ->sequence(
                                    [
                                      'resourceable_type'
                                        => ChapterResourceable::getActualClassNameForMorph(Pays::class),
                                      'resourceable_id'
                                        => Pays::inRandomOrder()->first(),
                                    ], [
                                      'resourceable_type'
                                        => ChapterResourceable::getActualClassNameForMorph(Organisation::class),
                                      'resourceable_id'
                                        => Organisation::inRandomOrder()->first(),
                                    ], [
                                      'resourceable_type'
                                        => ChapterResourceable::getActualClassNameForMorph(Ville::class),
                                      'resourceable_id'
                                        => Ville::inRandomOrder()->first(),
                                    ]),
                            'resourceables'
                        )
                        ->has(
                            ChapterEntry::factory()
                                ->state(function(array $attributes, Chapter $chapter) {
                                    return ['chapter_id' => $chapter->id];
                                })
                                ->count(rand(0, 3))
                                ->sequence(
                                    [
                                        'media_type' => null,
                                        'media_data' => null,
                                    ], [
                                        'media_type' => 'squirrel.squit',
                                        'media_data' => $this->faker->chapterEntryMediaData('squirrel.squit'),
                                    ], [
                                        'media_type' => 'forum.post',
                                        'media_data' => $this->faker->chapterEntryMediaData('forum.post'),
                                    ]),
                            'entries'
                        ),
                    'chapters')
                ->create([
                    'user_id' => CustomUser::inRandomOrder()->first(),
                ]);
        });
    }
}
