<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UsersPays
 * 
 * @property int $id
 * @property int $ID_pays
 * @property int $ID_user
 * @property int $permissions
 *
 * @package App\Models
 */
class UsersPays extends Pivot
{
	protected $table = 'users_pays';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'ID_pays' => 'int',
		'ID_user' => 'int',
		'permissions' => 'int'
	];

	protected $fillable = [
		'ID_pays',
		'ID_user',
		'permissions'
	];

	public function users()
    {
        return $this->belongsTo(CustomUser::class, 'ch_use_id', 'ID_user');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'ch_pay_id', 'ID_pays');
    }

    public function organisation_members()
    {
        return $this->hasManyThrough(
            OrganisationMember::class,
            Pays::class,
            'ch_pay_id',
            'pays_id',
            'ID_pays',
            'ch_pay_id'
        );
    }

}
