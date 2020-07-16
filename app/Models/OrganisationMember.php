<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\CustomUser;
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

	public function organisation()
	{
		return $this->belongsTo(Organisation::class, 'organisation_id');
	}

	public function pays()
	{
		return $this->belongsTo(Pays::class, 'pays_id');
	}

	public function getPermissionLabel()
    {
        switch($this->permissions) {
            case Organisation::$permissions['owner']: $label = 'Propriétaire'; break;
            case Organisation::$permissions['administrator']: $label = 'Administrateur'; break;
            case Organisation::$permissions['member']: $label = 'Membre'; break;
            case Organisation::$permissions['pending']: $label = 'En attente de validation'; break;
            default: throw new \InvalidArgumentException("Mauvais type de permission.");
        }

        return $label;
    }
}
