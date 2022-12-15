<?php

namespace Roxayl\MondeGC\Models;

use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Contracts\SimpleResourceable;
use Roxayl\MondeGC\Models\Traits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

/**
 * Class ResourceHistory
 *
 * @property int $id
 * @property string $resourceable_type
 * @property int $resourceable_id
 * @property int $budget
 * @property int $commerce
 * @property int $industrie
 * @property int $agriculture
 * @property int $tourisme
 * @property int $recherche
 * @property int $environnement
 * @property int $education
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array<string> $resources
 * @property-read Model|\Eloquent $resourceable
 * @method static Builder|ResourceHistory chartSelect()
 * @method static Builder|ResourceHistory forResourceable(Resourceable $resourceable)
 * @method static Builder|ResourceHistory forResourceables(Collection $resourceables)
 * @method static Builder|ResourceHistory newModelQuery()
 * @method static Builder|ResourceHistory newQuery()
 * @method static Builder|ResourceHistory query()
 * @method static Builder|ResourceHistory whereAgriculture($value)
 * @method static Builder|ResourceHistory whereBudget($value)
 * @method static Builder|ResourceHistory whereCommerce($value)
 * @method static Builder|ResourceHistory whereCreatedAt($value)
 * @method static Builder|ResourceHistory whereEducation($value)
 * @method static Builder|ResourceHistory whereEnvironnement($value)
 * @method static Builder|ResourceHistory whereId($value)
 * @method static Builder|ResourceHistory whereIndustrie($value)
 * @method static Builder|ResourceHistory whereRecherche($value)
 * @method static Builder|ResourceHistory whereResourceableId($value)
 * @method static Builder|ResourceHistory whereResourceableType($value)
 * @method static Builder|ResourceHistory whereTourisme($value)
 * @method static Builder|ResourceHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @package Roxayl\MondeGC\Models
 */
class ResourceHistory extends Model implements SimpleResourceable
{
    use Traits\SimpleResourceable;

    protected $table = 'resource_history';

    protected $casts = [
        'resourceable_id' => 'int',
        'budget' => 'int',
        'commerce' => 'int',
        'industrie' => 'int',
        'agriculture' => 'int',
        'tourisme' => 'int',
        'recherche' => 'int',
        'environnement' => 'int',
        'education' => 'int'
    ];

    protected $fillable = [
        'resourceable_type',
        'resourceable_id',
        'budget',
        'commerce',
        'industrie',
        'agriculture',
        'tourisme',
        'recherche',
        'environnement',
        'education'
    ];

    /**
     * @return MorphTo
     */
    public function resourceable(): MorphTo
    {
        return $this->morphTo('resourceable');
    }

    /**
     * @return array
     */
    public function resources(): array
    {
        $data = [];

        foreach(config('enums.resources') as $resource) {
            $data[] = $this->$resource;
        }

        return $data;
    }

    /**
     * @param Builder $query
     * @param Collection $resourceables
     * @return Builder
     */
    public function scopeForResourceables(Builder $query, Collection $resourceables): Builder
    {
        return $query->where(function (Builder $query) use ($resourceables) {
            /** @var Model&Resourceable $resourceable */
            foreach($resourceables as $resourceable) {
                $query = $query->orWhere(function (Builder $query) use ($resourceable) {
                    $this->scopeForResourceable($query, $resourceable);
                });
            }
        });
    }

    /**
     * @param Builder $query
     * @param Resourceable $resourceable
     * @return Builder
     */
    public function scopeForResourceable(Builder $query, Resourceable $resourceable): Builder
    {
        return $query->where('resourceable_type', $resourceable->getMorphClass())
                     ->where('resourceable_id', $resourceable->getKey());
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeChartSelect(Builder $query): Builder
    {
        return $query
            ->select()
            ->selectRaw('UNIX_TIMESTAMP(DATE(created_at)) AS created_timestamp')
            ->selectRaw('DATE(created_at) AS created_date')
            ->orderBy('created_at');
    }
}
