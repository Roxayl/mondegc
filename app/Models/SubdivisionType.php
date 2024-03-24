<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubdivisionType.
 *
 * @property int $id
 * @property int|null $pays_id
 * @property string $type_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property Pays|null $pays
 * @property Collection|Subdivision[] $subdivisions
 * @property-read int|null $subdivisions_count
 *
 * @method static Builder|SubdivisionType newModelQuery()
 * @method static Builder|SubdivisionType newQuery()
 * @method static Builder|SubdivisionType query()
 * @method static Builder|SubdivisionType whereCreatedAt($value)
 * @method static Builder|SubdivisionType whereId($value)
 * @method static Builder|SubdivisionType wherePaysId($value)
 * @method static Builder|SubdivisionType whereTypeName($value)
 * @method static Builder|SubdivisionType whereUpdatedAt($value)
 * @method static Builder|SubdivisionType onlyTrashed()
 * @method static Builder|SubdivisionType whereDeletedAt($value)
 * @method static Builder|SubdivisionType withTrashed()
 * @method static Builder|SubdivisionType withoutTrashed()
 *
 * @mixin \Eloquent
 */
class SubdivisionType extends Model
{
    use SoftDeletes;

    protected $table = 'subdivision_types';

    protected $casts = [
        'pays_id' => 'int',
    ];

    protected $fillable = [
        'pays_id',
        'type_name',
    ];

    /**
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id', 'ch_pay_id');
    }

    /**
     * @return HasMany
     */
    public function subdivisions(): HasMany
    {
        return $this->hasMany(Subdivision::class);
    }
}
