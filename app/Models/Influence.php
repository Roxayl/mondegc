<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Influence
 *
 * @property int $id
 * @property string $influencable_type
 * @property int|null $influencable_id
 * @property int $budget
 * @property int $agriculture
 * @property int $commerce
 * @property int $education
 * @property int $environnement
 * @property int $industrie
 * @property int $recherche
 * @property int $tourisme
 * @property Carbon $generates_influence_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model|\Eloquent $influencable
 * @method static Builder|Influence newModelQuery()
 * @method static Builder|Influence newQuery()
 * @method static Builder|Influence query()
 * @method static Builder|Influence whereAgriculture($value)
 * @method static Builder|Influence whereBudget($value)
 * @method static Builder|Influence whereCommerce($value)
 * @method static Builder|Influence whereCreatedAt($value)
 * @method static Builder|Influence whereEducation($value)
 * @method static Builder|Influence whereEnvironnement($value)
 * @method static Builder|Influence whereGeneratesInfluenceAt($value)
 * @method static Builder|Influence whereId($value)
 * @method static Builder|Influence whereIndustrie($value)
 * @method static Builder|Influence whereInfluencableId($value)
 * @method static Builder|Influence whereInfluencableType($value)
 * @method static Builder|Influence whereRecherche($value)
 * @method static Builder|Influence whereTourisme($value)
 * @method static Builder|Influence whereUpdatedAt($value)
 * @mixin Model
 */
class Influence extends Model
{
    protected $table = 'influence';

    protected $casts = [
        'budget' => 'int',
        'agriculture' => 'int',
        'commerce' => 'int',
        'education' => 'int',
        'environnement' => 'int',
        'industrie' => 'int',
        'recherche' => 'int',
        'tourisme' => 'int'
    ];

    protected $dates = [
        'generates_influence_at'
    ];

    protected $fillable = [
        'budget',
        'agriculture',
        'commerce',
        'education',
        'environnement',
        'industrie',
        'recherche',
        'tourisme'
    ];

    /**
     * @return MorphTo
     */
    public function influencable(): MorphTo
    {
        return $this->morphTo();
    }
}
