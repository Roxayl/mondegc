<?php

namespace Database\Seeders;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Pays;
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

            // Créer un premier utilisateur admin.
            $adminUser = CustomUser::factory()
                    ->hasAttached(
                        Pays::factory(),
                        ['permissions' => Pays::PERMISSION_DIRIGEANT])
                    ->create([
                        'ch_use_login' => 'Admin',
                        'ch_use_mail'  => 'contact@generation-city.com',
                    ]);

            if($adminUser) {
                $this->command->info("Successfully created admin user.");
                $this->command->table(['Username', 'Password'], [[$adminUser->ch_use_login, 'password']]);
            } else {
                $this->command->error("Unable to create admin user. Skipping...");
            }


            // Exécuter les autres seeders.
            $this->call([
                CustomUserSeeder::class,
                RoleplayableSeeder::class,
                RoleplaySeeder::class,
            ]);

        });
    }
}
