<?php

namespace Roxayl\MondeGC\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Roxayl\MondeGC\Models\Contracts;
use Roxayl\MondeGC\Models\Repositories\Resourceable;
use Roxayl\MondeGC\Models\ResourceHistory;
use Roxayl\MondeGC\Models\Traits\GeneratesResourceHistory;

class StoreResourceHistory implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection<int, Contracts\Resourceable>
     */
    private Collection $resourceable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Resourceable $resourceableRepository)
    {
        $this->checkShouldRun();

        $this->resourceable = $resourceableRepository->query()->all()->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkShouldRun();

        DB::transaction(function() {
            /** @var Contracts\Resourceable&GeneratesResourceHistory $resourceable */
            foreach($this->resourceable as $resourceable) {
                $requiredTrait = GeneratesResourceHistory::class;
                if(! in_array($requiredTrait, class_uses_recursive($resourceable))) {
                    $this->outputToConsole('Ignoring: '
                        . $resourceable->getName() . '=' . $resourceable->getKey());
                    continue;
                }

                $resourceable->generateResourceHistory();
                $this->outputToConsole('Stored: ' . $resourceable->getName() . '=' . $resourceable->getKey());
            }
        }, 2);

    }

    /**
     * Affiche du texte en sortie, lorsque la tâche est exécutée en CLI.
     * @param string $text
     */
    private function outputToConsole(string $text): void
    {
        if(! app()->runningInConsole()) {
            return;
        }

        error_log($text);
    }

    /**
     * Vérifie si la tâche doit être exécutée.
     */
    private function checkShouldRun(): void
    {
        if(! $this->shouldRun()) {
            throw new \LogicException(
                "La tâche d'historisation des ressources générées ne devrait pas être exécutée.");
        }
    }

    /**
     * Vérifie que la tâche peut être exécutée.
     * @return bool
     */
    public function shouldRun(): bool
    {
        /** @var Carbon|null $lastSuccessfulExecution */
        $lastSuccessfulExecution = ResourceHistory::latest()->first()?->created_at;

        // On évite toute exécution si la précédente a moins de 7 jours.
        if($lastSuccessfulExecution !== null && $lastSuccessfulExecution > now()->subDays(7)) {
            return false;
        }

        // On exécute seulement si nous sommes :
        //  - entre le 1er et le 2ème jour de chaque mois ; ou
        //  - entre le 14ème et 16ème jour de chaque mois.
        if(! in_array(now()->day, [1, 2, 14, 15, 16], true)) {
            return false;
        }

        return true;
    }
}
