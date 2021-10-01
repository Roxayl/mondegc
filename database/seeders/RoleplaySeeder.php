<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Roleplay;
use Illuminate\Database\Seeder;

class RoleplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** Nombre de chapitres par roleplay à générer. */
        $chapterCount = 4;

        Roleplay::factory()
            ->count(3)
            ->sequence(
                ['ending_date' => null],
                ['ending_date' => now()->addDays(rand(7, 30))]
            )
            ->has(
                Chapter::factory()
                    ->count($chapterCount)
                    ->sequence(function($sequence) use ($chapterCount) {
                        $i = ($sequence->index % $chapterCount) + 1;
                        return ['order' => $i];
                    }),
                'chapters')
            ->create();
    }
}
