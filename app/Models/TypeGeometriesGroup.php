<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TypeGeometriesGroup
 *
 * @property int $id
 * @property string $intitule
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read int|null $type_geometries_count
 * @property-read Collection|TypeGeometry[] $typeGeometries
 * @method static Builder|TypeGeometriesGroup newModelQuery()
 * @method static Builder|TypeGeometriesGroup newQuery()
 * @method static Builder|TypeGeometriesGroup query()
 * @method static Builder|TypeGeometriesGroup whereCreatedAt($value)
 * @method static Builder|TypeGeometriesGroup whereId($value)
 * @method static Builder|TypeGeometriesGroup whereIntitule($value)
 * @method static Builder|TypeGeometriesGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TypeGeometriesGroup extends Model
{
    protected $table = 'type_geometries_group';

    protected $fillable = [
        'intitule'
    ];

    /**
     * @return HasMany
     */
    public function typeGeometries(): HasMany
    {
        return $this->hasMany(TypeGeometry::class, 'group_id');
    }
}
