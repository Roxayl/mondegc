<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TypeGeometry
 *
 * @property int $id
 * @property int $group_id
 * @property string $label
 * @property string|null $type_geometrie
 * @property float $coef_budget
 * @property float $coef_industrie
 * @property float $coef_commerce
 * @property float $coef_agriculture
 * @property float $coef_tourisme
 * @property float $coef_recherche
 * @property float $coef_environnement
 * @property float $coef_education
 * @property float $coef_population
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property TypeGeometriesGroup $type_geometries_group
 * @property Collection|Geometry[] $geometries
 * @property-read int|null $geometries_count
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefAgriculture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefCommerce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefEnvironnement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefIndustrie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefPopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefRecherche($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCoefTourisme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereTypeGeometrie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeGeometry whereUpdatedAt($value)
 * @mixin Model
 */
class TypeGeometry extends Model
{
	protected $table = 'type_geometries';

	protected $casts = [
		'group_id' => 'int',
		'coef_budget' => 'float',
		'coef_industrie' => 'float',
		'coef_commerce' => 'float',
		'coef_agriculture' => 'float',
		'coef_tourisme' => 'float',
		'coef_recherche' => 'float',
		'coef_environnement' => 'float',
		'coef_education' => 'float',
		'coef_population' => 'float'
	];

	protected $fillable = [
		'group_id',
		'label',
		'type_geometrie',
		'coef_budget',
		'coef_industrie',
		'coef_commerce',
		'coef_agriculture',
		'coef_tourisme',
		'coef_recherche',
		'coef_environnement',
		'coef_education',
		'coef_population'
	];

	public function type_geometries_group(): BelongsTo
	{
		return $this->belongsTo(TypeGeometriesGroup::class, 'group_id');
	}

	public function geometries(): HasMany
	{
		return $this->hasMany(Geometry::class, 'type_geometrie_id');
	}
}
