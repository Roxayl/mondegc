<?php

namespace Database\Seeders;

use App\Models\CustomUser;
use App\Models\Pays;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @todo Unfinished implementation.
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function() {

            // Créer un utilisateur admin.
            CustomUser::factory()
                    ->hasAttached(
                        Pays::factory(),
                        ['permissions' => Pays::PERMISSION_DIRIGEANT])
                    ->create();

            // Exécuter les autres seeders.
            $this->call([
                CustomUserSeeder::class,
                RoleplaySeeder::class,
            ]);

        });
    }
}
