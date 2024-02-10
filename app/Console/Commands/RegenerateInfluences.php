<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Console\Commands;

use Illuminate\Console\Command;
use Roxayl\MondeGC\Services\RegenerateInfluenceService;

class RegenerateInfluences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monde:regenerate-influences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Régénère les influences générées par les entités influençables.';

    /**
     * Execute the console command.
     *
     * @param  RegenerateInfluenceService  $regenerateInfluenceService
     * @return int
     */
    public function handle(RegenerateInfluenceService $regenerateInfluenceService): int
    {
        try {
            $this->line('Régénération des influences ('
                . $regenerateInfluenceService->influenceCount() . ' influence(s) actuellement dans la base de données)...');
            $regenerateInfluenceService->regenerate();
            $this->info('Influences regénérées avec succès ('
                . $regenerateInfluenceService->influenceCount() . ' influence(s) actuellement dans la base de données).');
        } catch (\Throwable $ex) {
            $this->error("Une erreur s'est produite : " . $ex->getMessage());

            return 1;
        }

        return 0;
    }
}
