<?php

namespace Roxayl\MondeGC\Console\Commands;

class InitializeTesting extends Initializer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:init-testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Initialise l'environnement de test";

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
    public function handle()
    {
        $this->initTestingEnv();

        $this->info("Variables d'environnement de test initialisées avec succès.");

        return 0;
    }
}
