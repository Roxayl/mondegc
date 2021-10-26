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
     * @return int
     */
    public function handle(): int
    {
        $this->copyFiles();
        $this->migrateDatabase();
        return 0;
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
        $this->call('monde:override-vendor');
    }
}
