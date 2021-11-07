<?php

namespace Database\Seeders;

use App\Models\CustomUser;
use App\Models\Organisation;
use App\Models\Pays;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleplayableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function() {

            Pays::factory()
                ->create();

            $pays = Pays::inRandomOrder()->first();
            $user = CustomUser::inRandomOrder()->first();
            Ville::factory()
                ->count(2)
                ->create([
                    'ch_vil_paysID' => $pays->ch_pay_id,
                    'ch_vil_user'   => $user->ch_use_id,
                ]);

            Organisation::factory()
                ->create();

        });
    }
}
