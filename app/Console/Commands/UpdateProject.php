<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:update';

    protected $fileCopies = [
        'app/Overrides/Searchable/SearchResult.php' => 'vendor/spatie/laravel-searchable/src/SearchResult.php'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Exécute les routines dans le cadre d'une mise à jour du site";

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
     * @return void
     */
    public function handle(): void
    {
        $this->migrateDatabase();
        $this->copyFiles();
    }

    private function migrateDatabase(): void
    {
        $this->line("\r\nMigration de la base de données");

        try {
            $this->call('migrate');
        } catch(Exception $ex) {
            $this->error("Une erreur s'est produite lors de la migration.");
        }
    }

    private function copyFiles(): void
    {
        $this->line("\r\nCopie des fichiers");

        try {
            $backupPath = storage_path('app/copy-backup/' . Str::random(6));
            mkdir($backupPath, 0777, true);
        } catch(Exception $e) {
            $this->error("Impossible de créer le dossier.");
            return;
        }

        try {
            foreach($this->fileCopies as $origin => $destination) {
                $this->line("Copie de $origin ==> $destination");

                $backupPath = $backupPath . '/' . basename($destination);
                $destinationPath = base_path($destination);
                $originPath = base_path($origin);
                copy($destinationPath, $backupPath);
                copy($originPath, $destinationPath);
            }
            $this->info("Copies réalisées avec succès.");
        } catch(Exception $e) {
            $this->error("Une des copies a échoué.");
        }
    }
}
