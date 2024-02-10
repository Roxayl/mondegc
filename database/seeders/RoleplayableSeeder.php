<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Ville;

class RoleplayableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function () {
            Pays::factory()
                ->create();

            $pays = Pays::inRandomOrder()->first();
            $user = CustomUser::inRandomOrder()->first();
            Ville::factory()
                ->count(2)
                ->create([
                    'ch_vil_paysID' => $pays->ch_pay_id,
                    'ch_vil_user' => $user->ch_use_id,
                ]);

            Organisation::factory()
                ->create();
        });
    }
}
