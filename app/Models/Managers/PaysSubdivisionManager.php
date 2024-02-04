<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models\Managers;

use Illuminate\Support\Facades\DB;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Models\Subdivision;
use Roxayl\MondeGC\Models\SubdivisionType;

class PaysSubdivisionManager
{
    /**
     * @param Pays $pays
     */
    public function enable(Pays $pays): void
    {
        DB::transaction(function() use ($pays) {
            $typeIds = collect();
            SubdivisionType::withTrashed()->wherePaysId($pays->ch_pay_id)
                ->each(function(SubdivisionType $subdivisionType) use ($typeIds): void {
                    $typeIds->push($subdivisionType->id);
                    $subdivisionType->restore();
                });
            Subdivision::withTrashed()->whereIn('subdivision_type_id', $typeIds)
                ->each(function(Subdivision $subdivision): void {
                    $subdivision->restore();
                });
            $pays->update(['use_subdivisions' => true]);
        });
    }

    /**
     * @param Pays $pays
     */
    public function disable(Pays $pays): void
    {
        DB::transaction(function() use ($pays) {
            $pays->subdivisionTypes->each(function(SubdivisionType $subdivisionType): void {
                $subdivisionType->delete();
            });
            $pays->subdivisions->each(function(Subdivision $subdivisionType): void {
                $subdivisionType->delete();
            });
            $pays->update(['use_subdivisions' => false]);
        });
    }
}
