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
        $this->initScribeToken();

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

    private function initTestingEnv(): void
    {
        $this->line('Copie de .env --> .env.testing');

        $testingPath = base_path('.env.testing');

        copy(base_path('.env'), $testingPath);

        if (file_exists($testingPath)) {
            $this->saveEnvValueToFile($testingPath, 'DB_DATABASE', 'mondegc_testing', 'mondegc');
            $this->line('Base de données dans .env.testing modifiée');
        }
    }

    private function initScribeToken(): void
    {
        $this->line("Génération du jeton pour la génération de l'API");

        $path = base_path('.env');
        $token = Str::random(60);

        $this->saveEnvValueToFile($path, 'SCRIBE_AUTH_KEY', $token);
    }

    /**
     * Modifie la valeur d'une clé dans un fichier .env.
     *
     * @param string $path
     * @param string $key
     * @param string $newValue
     * @param string|null $oldValue
     * @return bool Renvoie <code>true</code> en cas de succès lors de l'écriture de la nouvelle valeur,
     *              <code>false</code> sinon.
     */
    private function saveEnvValueToFile(string $path, string $key, string $newValue, ?string $oldValue = null): bool
    {
        if($oldValue === null) {
            $oldValue = '';
        }

        if(!file_exists($path)) {
            return false;
        }

        $success = file_put_contents($path, str_replace(
            "\n$key=$oldValue",
            "\n$key=" . $newValue,
            file_get_contents($path)
        ));

        return !($success === false);
    }
}
