<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrganisationMember
 * 
 * @property int $id
 * @property int|null $organisation_id
 * @property int $permissions
 * @property int|null $pays_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Organisation $organisation
 * @property Pays $pays
 *
 * @package App\Models
 */
class OrganisationMember extends Model
{
	protected $table = 'organisation_members';

	protected $casts = [
		'organisation_id' => 'int',
		'permissions' => 'int',
		'pays_id' => 'int'
	];

	protected $fillable = [
		'organisation_id',
		'permissions',
		'pays_id'
	];

	protected $dates = ['created_at', 'updated_at'];

	public function organisation()
	{
		return $this->belongsTo(Organisation::class);
	}

	public function pays()
	{
		return $this->belongsTo(Pays::class, 'pays_id');
	}
}
