<?php

namespace App\Models;

use App\Models\Contracts\Infrastructurable;
use App\Models\Contracts\Resourceable;
use App\Models\Contracts\Roleplayable;
use App\Models\Presenters\InfrastructurablePresenter;
use App\Models\Presenters\OrganisationPresenter;
use App\Models\Traits\Infrastructurable as HasInfrastructures;
use App\Models\Traits\Resourceable as HasResources;
use App\Services\EconomyService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use YlsIdeas\FeatureFlags\Facades\Features;

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
 * @property-read string $slug
 * @property-read Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read Collection|ChapterResourceable[] $chapterResources
 * @property-read int|null $chapter_resources_count
 * @property-read Collection|Communique[] $communiques
 * @property-read int|null $communiques_count
 * @property-read Collection|Infrastructure[] $infrastructuresAll
 * @property-read int|null $infrastructures_all_count
 * @property-read Collection|OrganisationMember[] $members
 * @property-read int|null $members_count
 * @property-read Collection|OrganisationMember[] $membersAll
 * @property-read int|null $members_all_count
 * @property-read Collection|OrganisationMember[] $membersPending
 * @property-read int|null $members_pending_count
 * @method static Builder|Organisation newModelQuery()
 * @method static Builder|Organisation newQuery()
 * @method static Builder|Organisation query()
 * @method static Builder|Organisation whereAllowTemperance($value)
 * @method static Builder|Organisation whereCreatedAt($value)
 * @method static Builder|Organisation whereFlag($value)
 * @method static Builder|Organisation whereId($value)
 * @method static Builder|Organisation whereLogo($value)
 * @method static Builder|Organisation whereName($value)
 * @method static Builder|Organisation whereText($value)
 * @method static Builder|Organisation whereType($value)
 * @method static Builder|Organisation whereTypeMigratedAt($value)
 * @method static Builder|Organisation whereUpdatedAt($value)
 * @mixin Model
 */
class Organisation extends Model implements Searchable, Infrastructurable, Resourceable, Roleplayable
{
    use HasFactory, HasInfrastructures, HasResources;
    use InfrastructurablePresenter, OrganisationPresenter;

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

    public const TYPE_AGENCY = 'agency';
    public const TYPE_ALLIANCE = 'alliance';
    public const TYPE_ORGANISATION = 'organisation';
    public const TYPE_GROUP = 'group';

    public static array $types = [
        'agency' => self::TYPE_AGENCY,
        'alliance' => self::TYPE_ALLIANCE,
        'organisation' => self::TYPE_ORGANISATION,
        'group' => self::TYPE_GROUP,
    ];

    public static array $typesVisible = [
        self::TYPE_ALLIANCE, self::TYPE_ORGANISATION, self::TYPE_GROUP
    ];

    public static array $typesCreatable = [
        self::TYPE_ORGANISATION, self::TYPE_GROUP
    ];

    public static array $typesWithEconomy = [
        self::TYPE_AGENCY, self::TYPE_ALLIANCE, self::TYPE_ORGANISATION
    ];

    public static function getNameColumn(): string
    {
        return 'name';
    }

    public function getSearchResult(): SearchResult
    {
        $context = "{$this->members->count()} membres";

        return new SearchResult(
            $this, $this->name, $context,
            Str::limit(strip_tags($this->text), 150),
            route('organisation.showslug', $this->showRouteParameter())
        );
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(OrganisationMember::class)
            ->where('permissions', '>=', Organisation::$permissions['member']);
    }

    /**
     * @return HasMany
     */
    public function membersPending(): HasMany
    {
        return $this->hasMany(OrganisationMember::class)
            ->where('permissions', '=', Organisation::$permissions['pending']);
    }

    /**
     * @param CustomUser|null $user
     * @return HasMany
     */
    public function membersInvited(?CustomUser $user): HasMany
    {
        if(is_null($user)) {
            return $this->hasMany(OrganisationMember::class)
                ->where('permissions', '=', Organisation::$permissions['invited']);
        } else {
            return $this->hasMany(OrganisationMember::class)
                ->join('users_pays', 'pays_id', '=', 'ID_pays')
                ->select('organisation_members.*')
                ->where('organisation_members.permissions', '=',
                    Organisation::$permissions['invited'])
                ->where('ID_user', '=', $user->ch_use_id);
        }
    }

    /**
     * @return HasMany
     */
    public function membersAll(): HasMany
    {
        return $this->hasMany(OrganisationMember::class);
    }

    /**
     * @return HasMany
     */
    public function communiques(): HasMany
    {
        // TODO: https://laravel.com/docs/6.x/eloquent-relationships#one-to-many-polymorphic-relations
        return $this->hasMany(Communique::class,
            'ch_com_element_id', 'id')
            ->where('ch_com_categorie', '=', 'organisation')
            ->orderByDesc('ch_com_statut')
            ->orderByDesc('ch_com_date');
    }

    /**
     * @return MorphMany
     */
    public function chapterResources(): MorphMany
    {
        return $this->morphMany(ChapterResourceable::class, 'resourceable');
    }

    /**
     * @param ?scalar $permission
     * @return \Illuminate\Support\Collection
     */
    public function getUsers($permission = null): \Illuminate\Support\Collection
    {
        if($permission === null) {
            $permission = self::$permissions['administrator'];
        }

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

        return CustomUser::whereIn('ch_use_id', $users)->get();
    }

    /**
     * @return Builder
     */
    public static function allOrdered(): Builder
    {
        return self::with('members')
            ->orderByRaw("CASE type
                WHEN 'agency'       THEN 1       WHEN 'alliance' THEN 2
                WHEN 'organisation' THEN 3       WHEN 'group' THEN 4
                ELSE 4 END")
            ->orderByDesc('created_at');
    }

    /**
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }

    /**
     * @param CustomUser $user
     * @return int
     */
    public function maxPermission(CustomUser $user): int
    {
        $pays = array_column($user->pays()->get()->toArray(), 'ch_pay_id');
        $permission = (int)$this->membersAll()->whereIn('pays_id', $pays)->max('permissions');
        return $permission;
    }

    /**
     * @return bool
     */
    public function hasEconomy(): bool
    {
        return in_array($this->type, self::$typesWithEconomy, true);
    }

    /**
     * @return bool
     */
    public function membersGenerateResources(): bool
    {
        return $this->type === self::TYPE_ALLIANCE;
    }

    /**
     * @return array<string, float>
     */
    public function infrastructureResources(): array
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

    /**
     * @return array<string, float>
     */
    public function paysResources(): array
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

    /**
     * @return array<string, float>
     */
    public function roleplayResources(): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        if(! Features::accessible('roleplay')) {
            return $sumResources;
        }

        foreach($this->chapterResources as $chapterResource) {
            $generatedResources = $chapterResource->getGeneratedResources();
            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] = $generatedResources[$resource];
            }
        }

        return $sumResources;
    }

    /**
     * @return array<string, float>
     */
    public function resources(): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        // Les groupes d'États ne génèrent pas de ressources ; on ne calcule pas les ressources
        // le cas échéant et on renvoie directement un tableau de ressources à zéro.
        if($this->type !== self::TYPE_GROUP) {

            $infrastructureResources = $this->infrastructureResources();
            $paysResources = $this->paysResources();
            $roleplayResources = $this->roleplayResources();

            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] += $infrastructureResources[$resource]
                    + $paysResources[$resource]
                    + $roleplayResources[$resource];
            }
        }

        return $sumResources;
    }
}
