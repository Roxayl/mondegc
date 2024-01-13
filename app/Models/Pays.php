<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Closure;
use Database\Factories\PaysFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Infrastructurable;
use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Models\Managers\PaysMapManager;
use Roxayl\MondeGC\Models\Presenters\InfrastructurablePresenter;
use Roxayl\MondeGC\Models\Presenters\PaysPresenter;
use Roxayl\MondeGC\Models\Traits\Infrastructurable as HasInfrastructures;
use Roxayl\MondeGC\Models\Traits\Resourceable as HasResources;
use Roxayl\MondeGC\Models\Traits\Roleplayable as ParticipatesInRoleplay;
use Roxayl\MondeGC\Models\Traits\Versionable;
use Roxayl\MondeGC\Services\EconomyService;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use YlsIdeas\FeatureFlags\Facades\Features;

/**
 * Class Pays
 *
 * @property int $ch_pay_id
 * @property string $ch_pay_label
 * @property int $ch_pay_publication
 * @property string $ch_pay_continent
 * @property int|null $ch_pay_emplacement
 * @property string|null $ch_pay_lien_forum
 * @property string|null $lien_wiki
 * @property string|null $ch_pay_nom
 * @property string|null $ch_pay_devise
 * @property string|null $ch_pay_lien_imgheader
 * @property string|null $ch_pay_lien_imgdrapeau
 * @property Carbon|null $ch_pay_date
 * @property Carbon $ch_pay_mis_jour
 * @property int $ch_pay_nb_update
 * @property string|null $ch_pay_forme_etat
 * @property string|null $ch_pay_capitale
 * @property string|null $ch_pay_langue_officielle
 * @property string|null $ch_pay_monnaie
 * @property string|null $ch_pay_header_presentation
 * @property string|null $ch_pay_text_presentation
 * @property string|null $ch_pay_header_geographie
 * @property string|null $ch_pay_text_geographie
 * @property string|null $ch_pay_header_politique
 * @property string|null $ch_pay_text_politique
 * @property string|null $ch_pay_header_histoire
 * @property string|null $ch_pay_text_histoire
 * @property string|null $ch_pay_header_economie
 * @property string|null $ch_pay_text_economie
 * @property string|null $ch_pay_header_transport
 * @property string|null $ch_pay_text_transport
 * @property string|null $ch_pay_header_sport
 * @property string|null $ch_pay_text_sport
 * @property string|null $ch_pay_header_culture
 * @property string|null $ch_pay_text_culture
 * @property string|null $ch_pay_header_patrimoine
 * @property string|null $ch_pay_text_patrimoine
 * @property int|null $ch_pay_budget_carte
 * @property int|null $ch_pay_industrie_carte
 * @property int|null $ch_pay_commerce_carte
 * @property int|null $ch_pay_agriculture_carte
 * @property int|null $ch_pay_tourisme_carte
 * @property int|null $ch_pay_recherche_carte
 * @property int|null $ch_pay_environnement_carte
 * @property int|null $ch_pay_education_carte
 * @property int|null $ch_pay_population_carte
 * @property int|null $ch_pay_emploi_carte
 * @property-read Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read Collection|Infrastructure[] $infrastructuresAll
 * @property-read int|null $infrastructures_all_count
 * @property-read Collection|OrganisationMember[] $organisationMembers
 * @property-read int|null $organisation_members_count
 * @property-read Collection|ChapterResourceable[] $chapterResources
 * @property-read int|null $chapter_resources_count
 * @property-read Collection|Geometry[] $geometries
 * @property-read int|null $geometries_count
 * @property-read Collection|OcgcProposal[] $proposals
 * @property-read int|null $proposals_count
 * @property-read Collection|CustomUser[] $users
 * @property-read int|null $users_count
 * @property-read Collection|Ville[] $villes
 * @property-read int|null $villes_count
 * @property-read array<string> $resources
 * @method static PaysFactory factory(...$parameters)
 * @method static Builder|Pays newModelQuery()
 * @method static Builder|Pays newQuery()
 * @method static Builder|Pays query()
 * @method static Builder|Pays whereChPayAgricultureCarte($value)
 * @method static Builder|Pays whereChPayBudgetCarte($value)
 * @method static Builder|Pays whereChPayCapitale($value)
 * @method static Builder|Pays whereChPayCommerceCarte($value)
 * @method static Builder|Pays whereChPayContinent($value)
 * @method static Builder|Pays whereChPayDate($value)
 * @method static Builder|Pays whereChPayDevise($value)
 * @method static Builder|Pays whereChPayEducationCarte($value)
 * @method static Builder|Pays whereChPayEmplacement($value)
 * @method static Builder|Pays whereChPayEmploiCarte($value)
 * @method static Builder|Pays whereChPayEnvironnementCarte($value)
 * @method static Builder|Pays whereChPayFormeEtat($value)
 * @method static Builder|Pays whereChPayHeaderCulture($value)
 * @method static Builder|Pays whereChPayHeaderEconomie($value)
 * @method static Builder|Pays whereChPayHeaderGeographie($value)
 * @method static Builder|Pays whereChPayHeaderHistoire($value)
 * @method static Builder|Pays whereChPayHeaderPatrimoine($value)
 * @method static Builder|Pays whereChPayHeaderPolitique($value)
 * @method static Builder|Pays whereChPayHeaderPresentation($value)
 * @method static Builder|Pays whereChPayHeaderSport($value)
 * @method static Builder|Pays whereChPayHeaderTransport($value)
 * @method static Builder|Pays whereChPayId($value)
 * @method static Builder|Pays whereChPayIndustrieCarte($value)
 * @method static Builder|Pays whereChPayLabel($value)
 * @method static Builder|Pays whereChPayLangueOfficielle($value)
 * @method static Builder|Pays whereChPayLienForum($value)
 * @method static Builder|Pays whereChPayLienImgdrapeau($value)
 * @method static Builder|Pays whereChPayLienImgheader($value)
 * @method static Builder|Pays whereChPayMisJour($value)
 * @method static Builder|Pays whereChPayMonnaie($value)
 * @method static Builder|Pays whereChPayNbUpdate($value)
 * @method static Builder|Pays whereChPayNom($value)
 * @method static Builder|Pays whereChPayPopulationCarte($value)
 * @method static Builder|Pays whereChPayPublication($value)
 * @method static Builder|Pays whereChPayRechercheCarte($value)
 * @method static Builder|Pays whereChPayTextCulture($value)
 * @method static Builder|Pays whereChPayTextEconomie($value)
 * @method static Builder|Pays whereChPayTextGeographie($value)
 * @method static Builder|Pays whereChPayTextHistoire($value)
 * @method static Builder|Pays whereChPayTextPatrimoine($value)
 * @method static Builder|Pays whereChPayTextPolitique($value)
 * @method static Builder|Pays whereChPayTextPresentation($value)
 * @method static Builder|Pays whereChPayTextSport($value)
 * @method static Builder|Pays whereChPayTextTransport($value)
 * @method static Builder|Pays whereChPayTourismeCarte($value)
 * @method static Builder|Pays whereLienWiki($value)
 * @method static Builder|Pays active()
 * @method static Builder|Pays visible()
 * @mixin \Eloquent
 */
class Pays extends Model implements Searchable, Infrastructurable, Resourceable, Roleplayable
{
    use HasFactory, HasInfrastructures, HasResources, ParticipatesInRoleplay;
    use InfrastructurablePresenter, PaysPresenter;
    use Versionable;

    protected $table = 'pays';
    protected $primaryKey = 'ch_pay_id';
    const CREATED_AT = 'ch_pay_date';
    const UPDATED_AT = 'ch_pay_mis_jour';

    protected $casts = [
        'ch_pay_publication' => 'int',
        'ch_pay_emplacement' => 'int',
        'ch_pay_nb_update' => 'int',
        'ch_pay_budget_carte' => 'int',
        'ch_pay_industrie_carte' => 'int',
        'ch_pay_commerce_carte' => 'int',
        'ch_pay_agriculture_carte' => 'int',
        'ch_pay_tourisme_carte' => 'int',
        'ch_pay_recherche_carte' => 'int',
        'ch_pay_environnement_carte' => 'int',
        'ch_pay_education_carte' => 'int',
        'ch_pay_population_carte' => 'int',
        'ch_pay_emploi_carte' => 'int'
    ];

    protected $dates = [
        'ch_pay_date',
        'ch_pay_mis_jour'
    ];

    protected $fillable = [
        'ch_pay_lien_forum',
        'lien_wiki',
        'ch_pay_nom',
        'ch_pay_devise',
        'ch_pay_lien_imgheader',
        'ch_pay_lien_imgdrapeau',
        'ch_pay_date',
        'ch_pay_mis_jour',
        'ch_pay_forme_etat',
        'ch_pay_capitale',
        'ch_pay_langue_officielle',
        'ch_pay_monnaie',
        'ch_pay_header_presentation',
        'ch_pay_text_presentation',
        'ch_pay_header_geographie',
        'ch_pay_text_geographie',
        'ch_pay_header_politique',
        'ch_pay_text_politique',
        'ch_pay_header_histoire',
        'ch_pay_text_histoire',
        'ch_pay_header_economie',
        'ch_pay_text_economie',
        'ch_pay_header_transport',
        'ch_pay_text_transport',
        'ch_pay_header_sport',
        'ch_pay_text_sport',
        'ch_pay_header_culture',
        'ch_pay_text_culture',
        'ch_pay_header_patrimoine',
        'ch_pay_text_patrimoine',
    ];

    protected $dontVersionFields = [
        'ch_pay_label',
        'ch_pay_publication',
        'ch_pay_date',
        'ch_pay_nb_update',
        'ch_pay_mis_jour',
        'ch_pay_budget_carte',
        'ch_pay_industrie_carte',
        'ch_pay_commerce_carte',
        'ch_pay_agriculture_carte',
        'ch_pay_tourisme_carte',
        'ch_pay_recherche_carte',
        'ch_pay_environnement_carte',
        'ch_pay_education_carte',
        'ch_pay_population_carte',
        'ch_pay_emploi_carte',
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_ARCHIVED = 2;

    public static array $statut = [
        'active' => self::STATUS_ACTIVE,
        'archived' => self::STATUS_ARCHIVED,
    ];

    public const PERMISSION_DIRIGEANT = 10;
    public const PERMISSION_CODIRIGEANT = 5;

    /**
     * @var array|int[] Donne un intervalle valide d'emplacements de pays.
     */
    private static array $slotRange = [1, 59];

    /**
     * @var PaysMapManager|null Instance du gestionnaire de carte de pays.
     */
    private ?PaysMapManager $mapManager = null;

    /**
     * @return PaysMapManager
     */
    public function getMapManager(): PaysMapManager
    {
        if(is_null($this->mapManager)) {
            $this->mapManager = new PaysMapManager($this);
        }
        return $this->mapManager;
    }

    /**
     * @return string
     */
    public static function getNameColumn(): string
    {
        return 'ch_pay_nom';
    }

    /**
     * @return SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $context = "Continent " . $this->ch_pay_continent
            . ((int)$this->ch_pay_publication === self::STATUS_ARCHIVED ? ' - Pays archivé' : '');

        return new SearchResult(
            $this, $this->ch_pay_nom, $context,
            Str::limit(strip_tags($this->ch_pay_text_presentation), 150),
            url('page-pays.php?ch_pay_id=' . $this->ch_pay_id)
        );
    }

    /**
     * @return HasMany
     */
    public function organisationMembers(): HasMany
    {
        return $this->hasMany(OrganisationMember::class, 'pays_id');
    }

    private function getOrganisationMembership(Closure $f, $permissions = Organisation::PERMISSION_MEMBER)
    {
        $query = $this->organisationMembers()
            ->join('organisation', 'organisation.id', 'organisation_id')
            ->whereNull('organisation.deleted_at')
            ->where('permissions', '>=', $permissions);

        return $f($query);
    }

    /**
     * @return Collection<Organisation>
     */
    public function organisationsAll(): \Illuminate\Support\Collection
    {
        return $this->getOrganisationMembership(function ($query) {
            return $query->get()
                ->pluck('organisation');
        });
    }

    /**
     * @return Organisation|null
     */
    public function alliance(): ?Organisation
    {
        return $this->getOrganisationMembership(function ($query) {
            return $query->where('type', Organisation::TYPE_ALLIANCE)
                ->get()
                ->pluck('organisation')->first();
        });
    }

    /**
     * @return Support\Collection<int, Organisation>
     */
    public function otherOrganisations(): Support\Collection
    {
        return $this->getOrganisationMembership(function ($query) {
            return $query->where('type', '!=', Organisation::TYPE_ALLIANCE)
                ->get()
                ->pluck('organisation');
        });
    }

    /**
     * Donne les organisations gérées par le pays (donc sur lesquelles le pays a un niveau de permission d'au
     * moins administrateur).
     * @return Support\Collection<int, Organisation>
     */
    public function managedOrganisations(): Support\Collection
    {
        return $this->getOrganisationMembership(
            fn ($query) => $query,
            Organisation::PERMISSION_ADMINISTRATOR)
            ->get()
            ->pluck('organisation');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(CustomUser::class, 'users_pays',
            'ID_pays', 'ID_user');
    }

    /**
     * @return HasMany
     */
    public function villes(): HasMany
    {
        return $this->hasMany(Ville::class, 'ch_vil_paysID');
    }

    /**
     * @return HasMany
     */
    public function geometries(): HasMany
    {
        return $this->hasMany(Geometry::class, 'ch_geo_pay_id');
    }

    /**
     * @return HasMany
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(OcgcProposal::class, 'ID_pays');
    }

    /**
     * @return Personnage|null
     */
    public function personnage(): ?Personnage
    {
        return Personnage::where('entity', 'pays')->where('entity_id', $this->ch_pay_id)->first();
    }

    /**
     * @return \Illuminate\Support\Collection<int, CustomUser>
     */
    public function getUsers(): \Illuminate\Support\Collection
    {
        return $this->users()->get();
    }

    /**
     * @inheritDoc
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('ch_pay_publication', Pays::STATUS_ACTIVE);
    }

    /**
     * Récupère les pays actifs.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        $inactivityMonths = config('gameplay.country_inactivity_months');
        $columns = array_merge([$this->getKeyName()], $this->getFillable(), $this->getDates());
        
        return $query->visible()
            ->select($columns)
            ->join('users_pays', 'users_pays.ID_pays', '=', 'ch_pay_id')
            ->join('users', 'users_pays.ID_user', '=', 'users.ch_use_id')
            ->groupBy($columns)
            ->havingRaw('MAX(COALESCE(ch_use_last_log, ch_use_date)) '
                . '> DATE_SUB(NOW(), INTERVAL ' . $inactivityMonths . ' MONTH)');
    }

    /**
     * @return array|int[]
     */
    public static function getSlotRange(): array
    {
        return self::$slotRange;
    }

    /**
     * @return Carbon
     */
    public function getLastActivity(): Carbon
    {
        $result = DB::select(
            'SELECT MAX(COALESCE(ch_use_last_log, ch_use_date)) AS last_date FROM users
             JOIN users_pays ON users_pays.ID_user = users.ch_use_id
             WHERE users_pays.ID_pays = ?', [$this->ch_pay_id]);

        if(isset($result[0])) {
            $date = new Carbon($result[0]->last_date);
        } else {
            // Si ce pays n'a pas d'utilisateur, on génère une date de dernière activité bien ancienne pour "simuler"
            // le fait que ce pays est inactif depuis longtemps.
            $date = Carbon::now()->subYears(2);
        }

        return $date;
    }

    /**
     * @return float
     */
    public function inactivityCoefficient(): float
    {
        $lastActivity = $this->getLastActivity();
        $coefficient = 1;

        if($lastActivity < Carbon::now()->subMonths(6)) {
            $coefficient = 0.5;
        } elseif($lastActivity < Carbon::now()->subMonths(5)) {
            $coefficient = 0.6;
        } elseif($lastActivity < Carbon::now()->subMonths(4)) {
            $coefficient = 0.7;
        } elseif($lastActivity < Carbon::now()->subMonths(3)) {
            $coefficient = 0.8;
        } elseif($lastActivity < Carbon::now()->subMonths(2)) {
            $coefficient = 0.9;
        }

        return (float)$coefficient;
    }

    /**
     * Donne les ressources générées par les villes au pays.
     *
     * @return array<string, float>
     */
    public function villeResources(): array
    {
        return EconomyService::sumGeneratedResourcesFromResourceables($this->villes);
    }

    /**
     * Donne les ressources générées par les infrastructures affectées au pays.
     *
     * @return array<string, float>
     */
    public function infrastructureResources(): array
    {
        return EconomyService::sumGeneratedResourcesFromInfluencables($this->infrastructures);
    }

    /**
     * Donne les ressources générées par les organisations du pays.
     *
     * @param bool $includeAlliance Inclut les statistiques générées par l'alliance.
     * @return array<string, float>
     */
    public function organisationResources(bool $includeAlliance = true): array
    {
        $resourceCacheParameters = ['includeAlliance' => $includeAlliance];

        // Récupérer les données depuis le cache, si disponible.
        if(Features::accessible('cache') && cache()->has($this->resourceCacheKey($resourceCacheParameters))) {
            return cache()->get($this->resourceCacheKey($resourceCacheParameters));
        }

        $sumResources = EconomyService::resourcesPrefilled();

        foreach($this->organisationsAll() as $organisation) {
            if(! $includeAlliance && $organisation->membersGenerateResources()) {
                continue;
            }
            $generatedResources = $organisation->organisationResources();
            $nbMembers = $organisation->members->count();

            foreach(Resource::cases() as $resource) {
                $generatedResources[$resource->value] = (int)($generatedResources[$resource->value] / $nbMembers);
                $sumResources[$resource->value] += $generatedResources[$resource->value];
            }
        }

        // Mettre en cache les ressources durant une durée aléatoire.
        if(Features::accessible('cache')) {
            $cacheTtl = random_int(config('cache.ttl_lower_bound'), config('cache.ttl_higher_bound'));
            cache()->put($this->resourceCacheKey($resourceCacheParameters), $sumResources, now()->addMinutes($cacheTtl));
        }

        return $sumResources;
    }

    /**
     * Donne toutes les ressources du pays, sans les ressources générées par leur alliance.
     *
     * Ceci peut être utile pour obtenir les ressources générées par les alliances, qui bénéficient des ressources
     * générées par leurs pays membres, afin d'éviter une récursion infinie.
     *
     * @return array<string, float>
     */
    public function withoutAllianceResources(): array
    {
        $resourceCacheParameters = ['withOrganisations' => 'withoutAlliance'];

        // Récupérer les données depuis le cache, si disponible.
        if(Features::accessible('cache') && cache()->has($this->resourceCacheKey($resourceCacheParameters))) {
            return cache()->get($this->resourceCacheKey($resourceCacheParameters));
        }

        $sumResources = EconomyService::resourcesPrefilled();
        $inactivityCoefficient = $this->inactivityCoefficient();

        $baseResources = $this->baseResources();
        $withoutAllianceResources =  $this->organisationResources(false);

        foreach(Resource::cases() as $resource) {
            // Pour toutes les ressources positives, on peut être amené à diminuer la quantité
            // de ressources données si le pays est inactif.
            if($withoutAllianceResources[$resource->value] > 0) {
                $withoutAllianceResources[$resource->value] =
                    (int)($withoutAllianceResources[$resource->value] * $inactivityCoefficient);
            }
            $sumResources[$resource->value] += $baseResources[$resource->value]
                + $withoutAllianceResources[$resource->value];
        }

        // Mettre en cache les ressources durant une durée aléatoire.
        if(Features::accessible('cache')) {
            $cacheTtl = random_int(config('cache.ttl_lower_bound'), config('cache.ttl_higher_bound'));
            cache()->put($this->resourceCacheKey($resourceCacheParameters), $sumResources, now()->addMinutes($cacheTtl));
        }

        return $sumResources;
    }

    /**
     * Donne toutes les ressources "de base" générées directement par le pays : elle exclut donc les organisations.
     *
     * @return array<string, float>
     */
    public function baseResources(): array
    {
        $resourceCacheParameters = ['withOrganisations' => false];

        // Récupérer les données depuis le cache, si disponible.
        if(Features::accessible('cache') && cache()->has($this->resourceCacheKey($resourceCacheParameters))) {
            return cache()->get($this->resourceCacheKey($resourceCacheParameters));
        }

        $sumResources = EconomyService::resourcesPrefilled();
        $inactivityCoefficient = $this->inactivityCoefficient();

        $villeResources = $this->villeResources();
        $mapResources = $this->getMapManager()->mapResources();
        $infrastructureResources = $this->infrastructureResources();
        $roleplayResources = $this->roleplayResources();

        foreach(Resource::cases() as $resource) {
            $sumResources[$resource->value] += $villeResources[$resource->value]
                + $mapResources[$resource->value]
                + $infrastructureResources[$resource->value]
                + $roleplayResources[$resource->value];

            // Pour toutes les ressources positives, on peut être amené à diminuer la quantité
            // de ressources données si le pays est inactif.
            if($sumResources[$resource->value] > 0) {
                $sumResources[$resource->value] = (int)($sumResources[$resource->value] * $inactivityCoefficient);
            }
        }

        // Mettre en cache les ressources durant une durée aléatoire.
        if(Features::accessible('cache')) {
            $cacheTtl = random_int(config('cache.ttl_lower_bound'), config('cache.ttl_higher_bound'));
            cache()->put($this->resourceCacheKey($resourceCacheParameters), $sumResources, now()->addMinutes($cacheTtl));
        }

        return $sumResources;
    }

    /**
     * Donne les ressources du pays, avec les statistiques générées des organisations.
     *
     * Le calcul prend en compte les ressources de base ({@see self::baseResources()}), auquel sont ajoutées les
     * ressources des organisations ({@see self::organisationResources()}).
     *
     * @return array<string, float>
     */
    public function resources(): array
    {
        $resourceCacheParameters = ['withOrganisations' => true];

        // Récupérer les données depuis le cache, si disponible.
        if(Features::accessible('cache') && cache()->has($this->resourceCacheKey($resourceCacheParameters))) {
            return cache()->get($this->resourceCacheKey($resourceCacheParameters));
        }

        $sumResources = EconomyService::resourcesPrefilled();
        $inactivityCoefficient = $this->inactivityCoefficient();

        $withoutOrganisationResources = $this->baseResources();
        $organisationResources =  $this->organisationResources();

        foreach(Resource::cases() as $resource) {
            // Pour toutes les ressources positives, on peut être amené à diminuer la quantité
            // de ressources données si le pays est inactif.
            if($organisationResources[$resource->value] > 0) {
                $organisationResources[$resource->value] = (int)($organisationResources[$resource->value] * $inactivityCoefficient);
            }

            $sumResources[$resource->value] += $withoutOrganisationResources[$resource->value]
                + $organisationResources[$resource->value];
        }

        // Mettre en cache les ressources durant une durée aléatoire.
        if(Features::accessible('cache')) {
            $cacheTtl = random_int(config('cache.ttl_lower_bound'), config('cache.ttl_higher_bound'));
            cache()->put($this->resourceCacheKey($resourceCacheParameters), $sumResources, now()->addMinutes($cacheTtl));
        }

        return $sumResources;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->ch_pay_publication === Pays::STATUS_ACTIVE;
    }

    public static function boot(): void
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function(Pays $pays): void {
            $pays->deleteAllInfrastructures();
            $pays->getMapManager()->removeOldInfluenceRows();
        });
    }
}
