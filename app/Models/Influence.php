<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 *
 * @package App\Models
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

    public function influencable()
    {
        $this->morphTo();
    }
}
