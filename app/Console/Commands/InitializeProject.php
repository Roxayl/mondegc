<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InitializeProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:init';

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
        $this->copyEnv();
        $this->generateKey();
        $this->generateLegacyHashKey();
        $this->call('monde:update');
        $this->info("Projet initialisé avec succès.");
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
}
