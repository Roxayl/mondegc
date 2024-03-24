<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TypeGeometry.
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
 * @property Collection|Geometry[] $geometries
 * @property-read TypeGeometriesGroup $typeGeometriesGroup
 * @property-read int|null $geometries_count
 *
 * @method static Builder|TypeGeometry newModelQuery()
 * @method static Builder|TypeGeometry newQuery()
 * @method static Builder|TypeGeometry query()
 * @method static Builder|TypeGeometry whereCoefAgriculture($value)
 * @method static Builder|TypeGeometry whereCoefBudget($value)
 * @method static Builder|TypeGeometry whereCoefCommerce($value)
 * @method static Builder|TypeGeometry whereCoefEducation($value)
 * @method static Builder|TypeGeometry whereCoefEnvironnement($value)
 * @method static Builder|TypeGeometry whereCoefIndustrie($value)
 * @method static Builder|TypeGeometry whereCoefPopulation($value)
 * @method static Builder|TypeGeometry whereCoefRecherche($value)
 * @method static Builder|TypeGeometry whereCoefTourisme($value)
 * @method static Builder|TypeGeometry whereCreatedAt($value)
 * @method static Builder|TypeGeometry whereGroupId($value)
 * @method static Builder|TypeGeometry whereId($value)
 * @method static Builder|TypeGeometry whereLabel($value)
 * @method static Builder|TypeGeometry whereTypeGeometrie($value)
 * @method static Builder|TypeGeometry whereUpdatedAt($value)
 *
 * @mixin \Eloquent
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
        'coef_population' => 'float',
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
        'coef_population',
    ];

    /**
     * @return BelongsTo
     */
    public function typeGeometriesGroup(): BelongsTo
    {
        return $this->belongsTo(TypeGeometriesGroup::class, 'group_id');
    }

    /**
     * @return HasMany
     */
    public function geometries(): HasMany
    {
        return $this->hasMany(Geometry::class, 'type_geometrie_id');
    }
}
