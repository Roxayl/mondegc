<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\CustomUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

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
class Organisation extends Model implements Searchable
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
        'administrator' => 50,
        'member' => 10,
        'pending' => 5,
    ];

	public function getSearchResult() : SearchResult
    {
	    return new SearchResult(
	        $this, $this->name, route('organisation.showslug',
                ['id' => $this->id, 'slug' => Str::slug($this->name)])
        );
    }

	public function members()
	{
		return $this->hasMany(OrganisationMember::class)
            ->where('permissions', '>=', Organisation::$permissions['member']);
	}

	public function membersPending()
    {
	    return $this->hasMany(OrganisationMember::class)
            ->where('permissions', '<', Organisation::$permissions['member']);
    }

    public function membersAll()
    {
	    return $this->hasMany(OrganisationMember::class);
    }

    public function temperance()
    {
	    return $this->hasOne(TemperanceOrganisation::class, 'id', 'id');
    }

    public function membersWithTemperance()
    {
        return $this->members()
            ->with(['pays', 'temperance'])
            ->join('temperance_pays', 'organisation_members.pays_id', '=',
                   'temperance_pays.id')
            ->orderBy('temperance_pays.budget', 'DESC')
            ->get();
    }

	public function slug()
    {
        return Str::slug($this->name);
    }

    public function maxPermission(CustomUser $user) {

        $pays = array_column($user->pays()->get()->toArray(), 'ch_pay_id');
        $permission = $this->membersAll()->whereIn('pays_id', $pays)->max('permissions');
        return $permission;

    }

}
