<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeGeometriesGroup
 * 
 * @property int $id
 * @property string $intitule
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|TypeGeometry[] $type_geometries
 *
 * @package App\Models
 */
class TypeGeometriesGroup extends Model
{
	protected $table = 'type_geometries_group';

	protected $fillable = [
		'intitule'
	];

	public function type_geometries()
	{
		return $this->hasMany(TypeGeometry::class, 'group_id');
	}
}
