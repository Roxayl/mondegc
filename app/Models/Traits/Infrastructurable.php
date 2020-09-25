<?php

namespace App\Models\Traits;

use App\Models\Infrastructure;

trait Infrastructurable
{
    public function infrastructures()
    {
        return $this->infrastructuresAll()
            ->where('ch_inf_statut', Infrastructure::JUGEMENT_ACCEPTED);
    }

    public function infrastructuresAll()
    {
        return $this->morphMany(Infrastructure::class, 'infrastructurable')
            ->orderByDesc('ch_inf_date');
    }

    public function deleteAllInfrastructures() : void
    {
        $this->infrastructures()->each(function($infrastructure) {
            $infrastructure->delete();
        });
    }
}
