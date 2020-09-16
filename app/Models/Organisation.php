<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\AggregatesInfluences;
use App\Models\Contracts\Infrastructurable;
use App\Models\Presenters\InfrastructurablePresenter;
use App\Models\Presenters\OrganisationPresenter;
use App\Models\Traits\Infrastructurable as HasInfrastructures;
use App\Services\EconomyService;
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
 * @property string $type
 * @property bool $allow_temperance
 * @property Carbon|null $type_migrated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|OrganisationMember[] $organisation_members
 *
 * @package App\Models
 */
class Organisation extends Model implements Searchable, Infrastructurable, AggregatesInfluences
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

	protected $dates = [
	    'type_changed_at',
    ];

	protected $attributes = [
        'allow_temperance' => false,
    ];

	public const PERMISSION_OWNER = 100;
	public const PERMISSION_ADMINISTRATOR = 50;
	public const PERMISSION_MEMBER = 10;
	public const PERMISSION_PENDING = 5;
	public const PERMISSION_INVITED = 2;

    public static array $permissions = [
        'owner' => self::PERMISSION_OWNER,
        'administrator' => self::PERMISSION_ADMINISTRATOR,
        'member' => self::PERMISSION_MEMBER,
        'pending' => self::PERMISSION_PENDING,
        'invited' => self::PERMISSION_INVITED,
    ];

	public const TYPE_ALLIANCE = 'alliance';
	public const TYPE_ORGANISATION = 'organisation';
	public const TYPE_GROUP = 'group';

	public static array $types = [
	    'alliance' => self::TYPE_ALLIANCE,
        'organisation' => self::TYPE_ORGANISATION,
        'group' => self::TYPE_GROUP,
    ];

	public static array $typesCreatable = [
	    self::TYPE_ORGANISATION, self::TYPE_GROUP
    ];

	public static array $typesWithEconomy = [
	    self::TYPE_ALLIANCE, self::TYPE_ORGANISATION
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
        return $this->hasMany(Communique::class,
            'ch_com_element_id', 'id')
            ->where('ch_com_categorie', '=', 'organisation')
            ->orderByDesc('ch_com_statut')
            ->orderByDesc('ch_com_date');
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

    public static function allOrdered()
    {
        return self::with('members')
            ->orderByRaw("CASE type
                WHEN 'alliance' THEN 1 WHEN 'organisation' THEN 2 WHEN 'group' THEN 3
                ELSE 4 END")
            ->orderByDesc('created_at')
            ->orderByDesc('allow_temperance');
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

    public function hasEconomy() : bool
    {
        return in_array($this->type, self::$typesWithEconomy, true);
    }

    public function membersGenerateResources() : bool
    {
        return $this->type === self::TYPE_ALLIANCE;
    }

    public function infrastructureResources() : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        foreach($this->infrastructures as $infrastructure) {
            $generatedResources = $infrastructure->getGeneratedResources();
            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] += $generatedResources[$resource];
            }
        }

        return $sumResources;
    }

    public function paysResources() : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        // Les alliances bénéficient les ressources de leurs pays ; on les calcule
        // le cas échéant.
        if($this->type === self::TYPE_ALLIANCE) {

            $paysMembers = $this->members;

            foreach($paysMembers as $members) {
                $thisPaysResources = $members->pays->resources(false);
                foreach(config('enums.resources') as $resource) {
                    $sumResources[$resource] += $thisPaysResources[$resource];
                }
            }
        }

        return $sumResources;
    }

    public function resources() : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        // Les groupes d'États ne génèrent pas de ressources ; on ne calcule pas les ressources
        // le cas échéant et on renvoie directement un tableau de ressources à zéro.
        if($this->type !== self::TYPE_GROUP) {

            $infrastructureResources = $this->infrastructureResources();
            $paysResources = $this->paysResources();

            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] += $infrastructureResources[$resource]
                                         + $paysResources[$resource];
            }
        }

        return $sumResources;
    }
}
