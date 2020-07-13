<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Organisation
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $logo
 * @property string|null $flag
 * @property string|null $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|OrganisationMember[] $organisation_members
 *
 * @package App\Models
 */
class Organisation extends Model
{
	protected $table = 'organisation';

	protected $fillable = [
		'name',
		'logo',
		'flag',
		'text'
	];

	static $permissions = [
	    'owner' => 100,
        'administrator' => 90,
        'member' => 80,
    ];

	public function members()
	{
		return $this->hasMany(OrganisationMember::class);
	}

	public function slug()
    {
        return Str::slug($this->name);
    }

    public function highestPermission()
    {
	    //

    }

}
