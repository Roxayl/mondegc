<?php

namespace Database\Seeders;

use App\Models\CustomUser;
use App\Models\Pays;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function() {
            CustomUser::factory()
                ->hasAttached(
                    Pays::factory(),
                    ['permissions' => Pays::PERMISSION_DIRIGEANT])
                ->create();
        });
    }
}
