<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Pays;

class CustomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function () {
            CustomUser::factory()
                ->hasAttached(
                    Pays::factory(),
                    ['permissions' => Pays::PERMISSION_DIRIGEANT])
                ->create();
        });
    }
}
