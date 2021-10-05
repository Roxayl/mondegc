<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\Influencable;
use App\Models\Contracts\Resourceable;
use App\Models\Traits\Influencable as GeneratesInfluence;
use App\Services\EconomyService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class ChapterResourceable
 * 
 * @property int $id
 * @property int $chapter_id
 * @property string $resourceable_type
 * @property int $resourceable_id
 * @property float $budget
 * @property float $commerce
 * @property float $industrie
 * @property float $agriculture
 * @property float $tourisme
 * @property float $recherche
 * @property float $environnement
 * @property float $education
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Chapter $chapter
 * @property Resourceable $resourceable
 *
 * @package App\Models
 */
class ChapterResourceable extends Model implements Influencable
{
    use GeneratesInfluence;

    protected $table = 'chapter_resourceable';

    protected $casts = [
        'chapter_id' => 'int',
        'resourceable_id' => 'int',
        'budget' => 'float',
        'commerce' => 'float',
        'industrie' => 'float',
        'agriculture' => 'float',
        'tourisme' => 'float',
        'recherche' => 'float',
        'environnement' => 'float',
        'education' => 'float'
    ];

    protected $fillable = [
        'chapter_id',
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
     * @return BelongsTo
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * @return MorphTo
     */
    public function resourceable(): MorphTo
    {
        return $this->morphTo('resourceable');
    }

    /**
     * @inheritDoc
     */
    public function generateInfluence(): void
    {
        $influencableType = Influence::getActualClassNameForMorph(get_class());

        $this->removeOldInfluenceRows();

        $sumResources = EconomyService::resourcesPrefilled();
        foreach(config('enums.resources') as $resource) {
            $sumResources[$resource] += $this->$resource;
        }

        $influence = new Influence;
        $influence->influencable_type      = $influencableType;
        $influence->influencable_id        = $this->id;
        $influence->generates_influence_at = $this->created_at;
        $influence->fill($sumResources)
            ->save();
    }
}
