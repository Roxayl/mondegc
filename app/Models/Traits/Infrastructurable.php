<?php

namespace Roxayl\MondeGC\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Roxayl\MondeGC\Models\Infrastructure;

/**
 * @property-read Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read Collection|Infrastructure[] $infrastructuresAll
 * @property-read int|null $infrastructures_all_count
 */
trait Infrastructurable
{
    public function infrastructures(): MorphMany
    {
        return $this->infrastructuresAll()
            ->where('ch_inf_statut', Infrastructure::JUGEMENT_ACCEPTED);
    }

    public function infrastructuresAll(): MorphMany
    {
        return $this->morphMany(Infrastructure::class, 'infrastructurable')
            ->orderByDesc('ch_inf_date');
    }

    public function deleteAllInfrastructures(): void
    {
        $this->infrastructures()->each(function($infrastructure) {
            $infrastructure->delete();
        });
    }
}
