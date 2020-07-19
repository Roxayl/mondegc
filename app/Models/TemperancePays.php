<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemperancePays
 * 
 * @property int $id
 * @property string|null $nom
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
class TemperancePays extends Model
{
	protected $table = 'temperance_pays';
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
		'nom',
		'budget',
		'agriculture',
		'commerce',
		'education',
		'environnement',
		'industrie',
		'recherche',
		'tourisme'
	];

	public function pays()
    {
        return $this->belongsTo(Pays::class, 'ch_pay_id', 'id');
    }

}
