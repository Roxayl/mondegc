<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemperanceOrganisation
 * 
 * @property int $id
 * @property string|null $name
 * @property float|null $budget
 * @property float|null $agriculture
 * @property float|null $commerce
 * @property float|null $education
 * @property float|null $environnement
 * @property float|null $industrie
 * @property float|null $recherche
 * @property float|null $tourisme
 *
 * @package App\Models
 */
class TemperanceOrganisation extends Model
{
	protected $table = 'temperance_organisation';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'budget' => 'float',
		'agriculture' => 'float',
		'commerce' => 'float',
		'education' => 'float',
		'environnement' => 'float',
		'industrie' => 'float',
		'recherche' => 'float',
		'tourisme' => 'float'
	];

	protected $fillable = [
		'id',
		'name',
		'budget',
		'agriculture',
		'commerce',
		'education',
		'environnement',
		'industrie',
		'recherche',
		'tourisme'
	];

	public function organisation() {

	    return $this->belongsTo(Organisation::class, 'id', 'id');

    }

}
