<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InitializeEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:init-env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Initialise l'application et les variables d'environnement";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Vérifier que les conditions d'exécution du script d'initialisation sont ok.
        if(File::exists('.env')) {
            $this->error("Vous ne pouvez pas initialiser l'application car un fichier .env existe déjà.");
            return 1;
        }

        // Exécuter effectivement la commande d'initialisation.
        $this->copyEnv();
        $this->generateKey();
        $this->generateLegacyHashKey();
        $this->initTestingEnv();
        $this->info("Variables d'environnement initialisées avec succès.");

        return 0;
    }

    private function copyEnv(): void
    {
        $this->line('Copie de .env.exemple --> .env');
        copy(base_path('.env.example'), base_path('.env'));
        $this->info('Fichier .env créé avec succès.');
    }

    private function generateKey(): void
    {
        $this->line("Génération de la clé d'application : key:generate");
        $this->call('key:generate');
    }

    private function generateLegacyHashKey(): void
    {
        $this->line("Génération de la clé de hachage (legacy) : LEGACY_SALT");

        $path = base_path('.env');
        $salt = Str::random(32);

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "\nLEGACY_SALT=",
                "\nLEGACY_SALT=". $salt,
                file_get_contents($path)
            ));
        }
    }

    private function initTestingEnv(): void
    {
        $this->line('Copie de .env --> .env.testing');

        $testingPath = base_path('.env.testing');

        copy(base_path('.env'), $testingPath);

        if (file_exists($testingPath)) {
            $this->line('Base de données modifiée');
            file_put_contents($testingPath, str_replace(
                "\nDB_DATABASE=mondegc",
                "\nDB_DATABASE=mondegc_testing",
                file_get_contents($testingPath)
            ));
        }
    }
}
