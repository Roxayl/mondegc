<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InitializeEnvironment extends Initializer
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
        $this->initScribeToken();
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

        $this->saveEnvValueToFile($path, 'LEGACY_SALT', $salt);
    }

    private function initScribeToken(): void
    {
        $this->line("Génération du jeton pour la génération de l'API");

        $path = base_path('.env');
        $token = Str::random(60);

        $this->saveEnvValueToFile($path, 'SCRIBE_AUTH_KEY', $token);
    }
}
