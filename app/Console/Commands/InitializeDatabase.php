<?php

namespace Roxayl\MondeGC\Console\Commands;

use Illuminate\Support\Facades\File;

class InitializeDatabase extends Initializer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:init-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Initialise la base de données en créant le schéma de la base et les données";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Vérifier que les conditions d'exécution du script d'initialisation de la DB sont ok.
        if(! File::exists('.env')) {
            $this->error("Vous devez initialiser l'environnement au préalable.");
            return 1;
        }

        // Eviter l'exécution en prod.
        if(app()->environment() === 'production') {
            $this->error("Vous ne pouvez pas initialiser la base de données dans un environnement "
                . "de production.");
            return 1;
        }

        // Exécuter effectivement la commande d'initialisation de la DB.
        $this->call('monde:update');
        $this->call('db:seed');
        $this->info("Base de données initialisée avec succès.");

        return 0;
    }
}
