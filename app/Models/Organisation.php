<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\Infrastructurable;
use App\Models\Presenters\InfrastructurablePresenter;
use App\Models\Presenters\OrganisationPresenter;
use App\Models\Traits\Infrastructurable as HasInfrastructures;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
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
 * @property bool $allow_temperance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|OrganisationMember[] $organisation_members
 *
 * @package App\Models
 */
class Organisation extends Model implements Searchable, Infrastructurable
{
    use OrganisationPresenter, InfrastructurablePresenter, HasInfrastructures;

	protected $table = 'organisation';

	protected $fillable = [
		'name',
		'logo',
		'flag',
		'text',
        'allow_temperance'
	];

	protected $casts = [
        'allow_temperance' => 'boolean',
    ];

	protected $attributes = [
        'allow_temperance' => false,
    ];

	static $permissions = [
	    'owner' => 100,
        'administrator' => 50,
        'member' => 10,
        'pending' => 5,
        'invited' => 2,
    ];

	public function getSearchResult() : SearchResult
    {
	    return new SearchResult(
	        $this, $this->name, route('organisation.showslug',
                $this->showRouteParameter())
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
            ->where('permissions', '=', Organisation::$permissions['pending']);
    }

    public function membersInvited(?Authenticatable $user)
    {
        if(is_null($user)) {
    	    return $this->hasMany(OrganisationMember::class)
                ->where('permissions', '=', Organisation::$permissions['invited']);
        }
        else {
            return $this->hasMany(OrganisationMember::class)
                ->join('users_pays', 'pays_id', '=', 'ID_pays')
                ->select('organisation_members.*')
                ->where('organisation_members.permissions', '=',
                        Organisation::$permissions['invited'])
                ->where('ID_user', '=', $user->ch_use_id);
        }
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

    public function communiques()
    {
        // TODO: https://laravel.com/docs/6.x/eloquent-relationships#one-to-many-polymorphic-relations
        $query = $this->hasMany(Communique::class,
            'ch_com_element_id', 'id')
            ->where('ch_com_categorie', '=', 'organisation')
            ->orderByDesc('ch_com_statut')
            ->orderByDesc('ch_com_date');

        // Affiche seulement les communiqués publiés, si l'utilisateur n'a pas les
        // permissions pour administrer l'organisation.
        if(Gate::denies('administrate', $this)) {
            $query = $query->where('ch_com_statut', Communique::STATUS_PUBLISHED);
        }

        return $query;
    }

    public function getUsers($permission = null)
    {
	    if($permission === null)
	        $permission = self::$permissions['administrator'];

        $members = $this->hasMany(OrganisationMember::class)
            ->where('permissions', '>=', $permission)
            ->get();

        $pays = [];
        $users = [];
        foreach($members as $member) {
            if(!in_array($member->pays_id, $pays)) {
                $pays[] = $member->pays_id;
                $users_pays = UsersPays::where('ID_pays', '=', $member->pays_id)->get();
                foreach($users_pays as $user_pays) {
                    if(!in_array($user_pays->ID_user, $users)) {
                        $users[] = $user_pays->ID_user;
                    }
                }
            }
        }

        $query = CustomUser::whereIn('ch_use_id', $users)->get();
        return $query;
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

    public function maxPermission(CustomUser $user)
    {
        $pays = array_column($user->pays()->get()->toArray(), 'ch_pay_id');
        $permission = $this->membersAll()->whereIn('pays_id', $pays)->max('permissions');
        return $permission;
    }
}
