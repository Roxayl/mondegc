<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Geometry
 *
 * @property int $ch_geo_id
 * @property int|null $type_geometrie_id
 * @property string $ch_geo_wkt
 * @property int $ch_geo_pay_id
 * @property int $ch_geo_user
 * @property int $ch_geo_maj_user
 * @property Carbon $ch_geo_date
 * @property Carbon $ch_geo_mis_jour
 * @property string $ch_geo_geometries
 * @property float $ch_geo_mesure
 * @property string|null $ch_geo_type
 * @property string|null $ch_geo_nom
 * @property-read TypeGeometry|null $typeGeometry
 * @property-read Pays $pays
 * @method static Builder|Geometry newModelQuery()
 * @method static Builder|Geometry newQuery()
 * @method static Builder|Geometry query()
 * @method static Builder|Geometry whereChGeoDate($value)
 * @method static Builder|Geometry whereChGeoGeometries($value)
 * @method static Builder|Geometry whereChGeoId($value)
 * @method static Builder|Geometry whereChGeoMajUser($value)
 * @method static Builder|Geometry whereChGeoMesure($value)
 * @method static Builder|Geometry whereChGeoMisJour($value)
 * @method static Builder|Geometry whereChGeoNom($value)
 * @method static Builder|Geometry whereChGeoPayId($value)
 * @method static Builder|Geometry whereChGeoType($value)
 * @method static Builder|Geometry whereChGeoUser($value)
 * @method static Builder|Geometry whereChGeoWkt($value)
 * @method static Builder|Geometry whereTypeGeometrieId($value)
 * @mixin Model
 */
class Geometry extends Model
{
    protected $table = 'geometries';
    protected $primaryKey = 'ch_geo_id';
    public $timestamps = false;

    protected $casts = [
        'type_geometrie_id' => 'int',
        'ch_geo_pay_id' => 'int',
        'ch_geo_user' => 'int',
        'ch_geo_maj_user' => 'int',
        'ch_geo_mesure' => 'float'
    ];

    protected $dates = [
        'ch_geo_date',
        'ch_geo_mis_jour'
    ];

    protected $fillable = [
        'type_geometrie_id',
        'ch_geo_wkt',
        'ch_geo_pay_id',
        'ch_geo_user',
        'ch_geo_maj_user',
        'ch_geo_date',
        'ch_geo_mis_jour',
        'ch_geo_geometries',
        'ch_geo_mesure',
        'ch_geo_type',
        'ch_geo_nom'
    ];

    /**
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ch_geo_pay_id');
    }

    /**
     * @return BelongsTo
     */
    public function typeGeometry(): BelongsTo
    {
        return $this->belongsTo(TypeGeometry::class, 'type_geometrie_id');
    }
}
