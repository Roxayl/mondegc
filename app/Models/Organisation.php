<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Database\Factories\OrganisationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query;
use Illuminate\Support;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Infrastructurable;
use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Models\Presenters\InfrastructurablePresenter;
use Roxayl\MondeGC\Models\Presenters\OrganisationPresenter;
use Roxayl\MondeGC\Models\Traits\Infrastructurable as HasInfrastructures;
use Roxayl\MondeGC\Models\Traits\Resourceable as HasResources;
use Roxayl\MondeGC\Models\Traits\Roleplayable as ParticipatesInRoleplay;
use Roxayl\MondeGC\Services\EconomyService;
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
 * @property Carbon|null $deleted_at
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
 * @property-read array<string> $resources
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
 * @method static OrganisationFactory factory(...$parameters)
 * @method static Builder|Organisation visible()
 * @method static Query\Builder|Organisation onlyTrashed()
 * @method static Builder|Organisation whereDeletedAt($value)
 * @method static Query\Builder|Organisation withTrashed()
 * @method static Query\Builder|Organisation withoutTrashed()
 * @mixin \Eloquent
 */
class Organisation extends Model implements Searchable, Infrastructurable, Resourceable, Roleplayable
{
    use SoftDeletes;
    use HasFactory, HasInfrastructures, HasResources, ParticipatesInRoleplay;
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

    /**
     * @inheritDoc
     */
    public static function getNameColumn(): string
    {
        return 'name';
    }

    /**
     * @inheritDoc
     */
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
     * @inheritDoc
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query;
    }

    /**
     * @param ?scalar $permission
     * @return Support\Collection
     */
    public function getUsers(float|bool|int|string $permission = null): Support\Collection
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
        return EconomyService::sumGeneratedResourcesFromInfluencables($this->infrastructures);
    }

    /**
     * @return array<string, float>
     */
    public function organisationResources(): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        $infrastructureResources = $this->infrastructureResources();
        $roleplayResources = $this->roleplayResources();

        foreach(Resource::cases() as $resource) {
            $sumResources[$resource->value] += $infrastructureResources[$resource->value]
                + $roleplayResources[$resource->value];
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
        if($this->membersGenerateResources()) {
            $paysMembers = $this->members;

            foreach($paysMembers as $members) {
                $thisPaysResources = $members->pays->withoutAllianceResources();
                foreach(Resource::cases() as $resource) {
                    $sumResources[$resource->value] += $thisPaysResources[$resource->value];
                }
            }
        }

        return $sumResources;
    }

    /**
     * @return array<string, float>
     */
    public function perCountryResources(): array
    {
        $memberCount = $this->members->count();

        return array_map(fn(float $value) => (int) ($value / $memberCount), $this->organisationResources());
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

            $organisationResources = $this->organisationResources();
            $paysResources = $this->paysResources();

            foreach(Resource::cases() as $resource) {
                $sumResources[$resource->value] += $organisationResources[$resource->value]
                    + $paysResources[$resource->value];
            }
        }

        return $sumResources;
    }

    public function isEnabled(): bool
    {
        return ! $this->trashed();
    }
}
