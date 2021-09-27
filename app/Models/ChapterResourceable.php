<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 *
 * @package App\Models
 */
class ChapterResourceable extends Model
{
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

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
