<?php

namespace App\Models;

use App\Models\Contracts\Infrastructurable;
use App\Models\Contracts\Resourceable;
use App\Models\Contracts\Roleplayable;
use App\Models\Managers\PaysMapManager;
use App\Models\Presenters\InfrastructurablePresenter;
use App\Models\Presenters\PaysPresenter;
use App\Models\Traits\Infrastructurable as HasInfrastructures;
use App\Models\Traits\Resourceable as HasResources;
use App\Models\Traits\Roleplayable as ParticipatesInRoleplay;
use App\Services\EconomyService;
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
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Pays
 *
 * @property int $ch_pay_id
 * @property string $ch_pay_label
 * @property bool $ch_pay_publication
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
 * @method static Builder|Pays visible()
 * @mixin Model
 */
class Pays extends Model implements Searchable, Infrastructurable, Resourceable, Roleplayable
{
    use HasFactory, HasInfrastructures, HasResources, ParticipatesInRoleplay;
    use InfrastructurablePresenter, PaysPresenter;

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
     * @return array<string, float>
     */
    public function villeResources(): array
    {
        return EconomyService::sumGeneratedResourcesFromResourceables($this->villes);
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

        foreach($this->organisationsAll() as $organisation) {
            $generatedResources = $organisation->infrastructureResources();
            $nbMembers = $organisation->members->count();

            foreach(config('enums.resources') as $resource) {
                $generatedResources[$resource] = (int)($generatedResources[$resource] / $nbMembers);
                $sumResources[$resource] += $generatedResources[$resource];
            }
        }

        return $sumResources;
    }

    /**
     * @param bool $withOrganisation Intègre les ressources de l'organisation dans le calcul des ressources du pays.
     * @return array<string, float>
     */
    public function resources(bool $withOrganisation = true): array
    {
        // Retrieve from cache.
        if(cache()->has($this->resourceCacheKey())) {
            return cache()->get($this->resourceCacheKey());
        }

        $sumResources = EconomyService::resourcesPrefilled();
        $inactivityCoefficient = $this->inactivityCoefficient();

        $villeResources = $this->villeResources();
        $mapResources = $this->getMapManager()->mapResources();
        $infrastructureResources = $this->infrastructureResources();
        $roleplayResources = $this->roleplayResources();

        // Si 'withOrganisation' est mis à false, on n'appelle pas organisationResources().
        // Ce paramètre existe et est mis à true parce que, lorsqu'on veut calculer les
        // statistiques d'une organisation, on veut éviter une référence circulaire entre
        // l'appel à Pays->resources() et Organisation->resources().
        $organisationResources = !$withOrganisation ?
            EconomyService::resourcesPrefilled() : $this->organisationResources();

        foreach(config('enums.resources') as $resource) {
            $sumResources[$resource] += $villeResources[$resource]
                + $mapResources[$resource]
                + $infrastructureResources[$resource]
                + $organisationResources[$resource]
                + $roleplayResources[$resource];

            // Pour toutes les ressources positives, on peut être amené à diminuer la quantité
            // de ressources données si le pays est inactif.
            if($sumResources[$resource] > 0) {
                $sumResources[$resource] = (int)($sumResources[$resource] * $inactivityCoefficient);
            }
        }

        // Cache resources for 20 minutes.
        cache()->put($this->resourceCacheKey(), $sumResources, now()->addMinutes(20));

        return $sumResources;
    }

    public static function boot()
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function ($pays) {
            /** @var Pays $pays */
            $pays->deleteAllInfrastructures();
            $pays->getMapManager()->removeOldInfluenceRows();
        });
    }
}
