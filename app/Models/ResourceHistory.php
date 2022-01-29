<?php

namespace App\Models;

use App\Models\Contracts\SimpleResourceable;
use App\Models\Traits;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
 *
 * @package App\Models
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
}
