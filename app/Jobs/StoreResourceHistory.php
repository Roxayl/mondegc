<?php

namespace App\Jobs;

use App\Models\Contracts;
use App\Models\Repositories\Resourceable;
use App\Models\ResourceHistory;
use App\Models\Traits\GeneratesResourceHistory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
            /** @var \App\Models\Contracts\Resourceable&GeneratesResourceHistory $resourceable */
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
        $now = now();

        /** @var Carbon|null $lastSuccessfulExecution */
        $lastSuccessfulExecution = ResourceHistory::latest()->first()?->created_at;

        if($lastSuccessfulExecution !== null) {
            // On évite toute exécution si la précédente a moins de 7 jours.
            if($lastSuccessfulExecution < $now->subDays(7)) {
                return false;
            }

            // On exécute seulement si nous sommes le 1er ou le 14ème de chaque mois.
            if($now->day !== 1 && $now->day !== 14) {
                return false;
            }
        }

        return true;
    }
}
