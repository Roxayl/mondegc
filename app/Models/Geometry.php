<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 * 
 * @property TypeGeometry $type_geometry
 *
 * @package App\Models
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

	public function pays()
    {
        return $this->belongsTo(Pays::class, 'ch_geo_pay_id');
    }

	public function type_geometry()
	{
		return $this->belongsTo(TypeGeometry::class, 'type_geometrie_id');
	}
}
