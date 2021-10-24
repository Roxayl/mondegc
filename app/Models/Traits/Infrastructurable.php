<?php

namespace App\Models\Traits;

use App\Models\Infrastructure;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
