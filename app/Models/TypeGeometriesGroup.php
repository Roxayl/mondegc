<?php

namespace App\Models;

use Carbon\Carbon;
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
 * @property Collection|TypeGeometry[] $type_geometries
 * @package App\Models
 * @property-read int|null $type_geometries_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup whereIntitule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometriesGroup whereUpdatedAt($value)
 * @mixin Model
 */
class TypeGeometriesGroup extends Model
{
	protected $table = 'type_geometries_group';

	protected $fillable = [
		'intitule'
	];

	public function type_geometries(): HasMany
	{
		return $this->hasMany(TypeGeometry::class, 'group_id');
	}
}
