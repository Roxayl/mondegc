<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Roxayl\MondeGC\Models\Presenters\SubdivisionPresenter;

/**
 * Class Subdivision.
 *
 * @property int $id
 * @property int|null $subdivision_type_id
 * @property string $name
 * @property string|null $summary
 * @property string|null $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property SubdivisionType|null $subdivision_type
 * @property-read SubdivisionType|null $subdivisionType
 * @property-read Pays|null $pays
 *
 * @method static Builder|Subdivision newModelQuery()
 * @method static Builder|Subdivision newQuery()
 * @method static Builder|Subdivision query()
 * @method static Builder|Subdivision whereContent($value)
 * @method static Builder|Subdivision whereCreatedAt($value)
 * @method static Builder|Subdivision whereId($value)
 * @method static Builder|Subdivision whereName($value)
 * @method static Builder|Subdivision whereSubdivisionTypeId($value)
 * @method static Builder|Subdivision whereSummary($value)
 * @method static Builder|Subdivision whereUpdatedAt($value)
 * @method static Builder|Subdivision onlyTrashed()
 * @method static Builder|Subdivision whereDeletedAt($value)
 * @method static Builder|Subdivision withTrashed()
 * @method static Builder|Subdivision withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Subdivision extends Model
{
    use SubdivisionPresenter;
    use SoftDeletes;

    protected $table = 'subdivisions';

    protected $casts = [
        'subdivision_type_id' => 'int',
    ];

    protected $fillable = [
        'subdivision_type_id',
        'name',
        'summary',
        'content',
    ];

    /**
     * @return BelongsTo
     */
    public function subdivisionType(): BelongsTo
    {
        return $this->belongsTo(SubdivisionType::class);
    }

    /**
     * @return HasOneThrough
     */
    public function pays(): HasOneThrough
    {
        return $this->hasOneThrough(
            Pays::class,
            SubdivisionType::class,
            'id',
            'ch_pay_id',
            'subdivision_type_id',
            'pays_id',
        );
    }
}
