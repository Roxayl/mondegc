<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OverrideVendorFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:override-vendor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remplace les fichiers dans le dossier /vendor par les overrides définis dans '
        . 'app/Overrides';

    /**
     * Indique l'emplacement source => destination des fichiers à override.
     * @var string[]
     */
    protected $fileCopies = [
        'app/Overrides/Searchable/SearchResult.php' => 'vendor/spatie/laravel-searchable/src/SearchResult.php'
    ];

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
        $this->copyFiles();
        return 0;
    }

    private function copyFiles(): void
    {
        $this->line("\r\nOverride des fichiers de dépendances");

        try {
            $backupPath = storage_path('app/copy-backup/' . Str::random(6));
            mkdir($backupPath, 0777, true);
        } catch(\Exception $e) {
            $this->error("Impossible de créer le dossier.");
            return;
        }

        try {
            foreach($this->fileCopies as $origin => $destination) {
                $this->line("Copie de $origin ==> $destination");

                $fileBackupPath = $backupPath . '/' . basename($destination);
                $destinationPath = base_path($destination);
                $originPath = base_path($origin);
                copy($destinationPath, $fileBackupPath);
                copy($originPath, $destinationPath);
            }
            $this->info("Copies réalisées avec succès.");
        } catch(\Exception $e) {
            $this->error("Une des copies a échoué.");
        }
    }
}
