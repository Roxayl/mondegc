<?php

namespace App\Models;

use Carbon\Carbon;
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
 * @method static \Illuminate\Database\Eloquent\Builder|Influence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Influence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Influence query()
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereAgriculture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereCommerce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereEnvironnement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereGeneratesInfluenceAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereIndustrie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereInfluencableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereInfluencableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereRecherche($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereTourisme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Influence whereUpdatedAt($value)
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

    public function influencable(): MorphTo
    {
        return $this->morphTo();
    }
}
