<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Database\Factories\VilleFactory;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Infrastructurable;
use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Enums\Resource;
use Roxayl\MondeGC\Models\Presenters\InfrastructurablePresenter;
use Roxayl\MondeGC\Models\Presenters\VillePresenter;
use Roxayl\MondeGC\Models\Traits\Infrastructurable as HasInfrastructures;
use Roxayl\MondeGC\Models\Traits\Resourceable as HasResources;
use Roxayl\MondeGC\Models\Traits\Roleplayable as ParticipatesInRoleplay;
use Roxayl\MondeGC\Models\Traits\Versionable;
use Roxayl\MondeGC\Services\Gameplay\EconomyService;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Ville.
 *
 * @property int $ch_vil_ID
 * @property int $ch_vil_paysID
 * @property int $ch_vil_user
 * @property string $ch_vil_label
 * @property int|null $subdivision_id
 * @property Carbon|null $ch_vil_date_enregistrement
 * @property Carbon|null $ch_vil_mis_jour
 * @property int|null $ch_vil_nb_update
 * @property float|null $ch_vil_coord_X
 * @property float|null $ch_vil_coord_Y
 * @property string|null $ch_vil_type_jeu
 * @property string|null $ch_vil_nom
 * @property string|null $ch_vil_armoiries
 * @property bool $ch_vil_capitale
 * @property int|null $ch_vil_population
 * @property string|null $ch_vil_specialite
 * @property string|null $ch_vil_lien_img1
 * @property string|null $ch_vil_lien_img2
 * @property string|null $ch_vil_lien_img3
 * @property string|null $ch_vil_lien_img4
 * @property string|null $ch_vil_lien_img5
 * @property string|null $ch_vil_legende_img1
 * @property string|null $ch_vil_legende_img2
 * @property string|null $ch_vil_legende_img3
 * @property string|null $ch_vil_legende_img4
 * @property string|null $ch_vil_legende_img5
 * @property string|null $ch_vil_header
 * @property string|null $ch_vil_contenu
 * @property string|null $ch_vil_transports
 * @property string|null $ch_vil_administration
 * @property string|null $ch_vil_culture
 * @property-read Pays|null $pays
 * @property-read Eloquent\Collection|ChapterResourceable[] $chapterResources
 * @property-read int|null $chapter_resources_count
 * @property-read Eloquent\Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @property-read Eloquent\Collection|Infrastructure[] $infrastructuresAll
 * @property-read int|null $infrastructures_all_count
 * @property-read Eloquent\Collection|Patrimoine[] $patrimoines
 * @property-read int|null $patrimoines_count
 * @property-read array<string> $resources
 *
 * @method static Builder|Ville newModelQuery()
 * @method static Builder|Ville newQuery()
 * @method static Builder|Ville query()
 * @method static Builder|Ville whereChVilAdministration($value)
 * @method static Builder|Ville whereChVilArmoiries($value)
 * @method static Builder|Ville whereChVilCapitale($value)
 * @method static Builder|Ville whereChVilContenu($value)
 * @method static Builder|Ville whereChVilCoordX($value)
 * @method static Builder|Ville whereChVilCoordY($value)
 * @method static Builder|Ville whereChVilCulture($value)
 * @method static Builder|Ville whereChVilDateEnregistrement($value)
 * @method static Builder|Ville whereChVilHeader($value)
 * @method static Builder|Ville whereChVilID($value)
 * @method static Builder|Ville whereChVilLabel($value)
 * @method static Builder|Ville whereSubdivisionId($value)
 * @method static Builder|Ville whereChVilLegendeImg1($value)
 * @method static Builder|Ville whereChVilLegendeImg2($value)
 * @method static Builder|Ville whereChVilLegendeImg3($value)
 * @method static Builder|Ville whereChVilLegendeImg4($value)
 * @method static Builder|Ville whereChVilLegendeImg5($value)
 * @method static Builder|Ville whereChVilLienImg1($value)
 * @method static Builder|Ville whereChVilLienImg2($value)
 * @method static Builder|Ville whereChVilLienImg3($value)
 * @method static Builder|Ville whereChVilLienImg4($value)
 * @method static Builder|Ville whereChVilLienImg5($value)
 * @method static Builder|Ville whereChVilMisJour($value)
 * @method static Builder|Ville whereChVilNbUpdate($value)
 * @method static Builder|Ville whereChVilNom($value)
 * @method static Builder|Ville whereChVilPaysID($value)
 * @method static Builder|Ville whereChVilPopulation($value)
 * @method static Builder|Ville whereChVilSpecialite($value)
 * @method static Builder|Ville whereChVilTransports($value)
 * @method static Builder|Ville whereChVilTypeJeu($value)
 * @method static Builder|Ville whereChVilUser($value)
 * @method static VilleFactory factory(...$parameters)
 * @method static Builder|Ville visible()
 *
 * @mixin \Eloquent
 */
class Ville extends Model implements Searchable, Infrastructurable, Resourceable, Roleplayable
{
    use HasFactory, HasInfrastructures, HasResources, ParticipatesInRoleplay;
    use InfrastructurablePresenter, VillePresenter;
    use Versionable;

    protected $table = 'villes';
    protected $primaryKey = 'ch_vil_ID';
    const CREATED_AT = 'ch_vil_date_enregistrement';
    const UPDATED_AT = 'ch_vil_mis_jour';

    protected $casts = [
        'ch_vil_paysID' => 'int',
        'ch_vil_user' => 'int',
        'subdivision_id' => 'int',
        'ch_vil_nb_update' => 'int',
        'ch_vil_coord_X' => 'float',
        'ch_vil_coord_Y' => 'float',
        'ch_vil_capitale' => 'bool',
        'ch_vil_population' => 'int',
        'ch_vil_date_enregistrement' => 'datetime',
        'ch_vil_mis_jour' => 'datetime',
    ];

    protected $fillable = [
        'ch_vil_paysID',
        'ch_vil_user',
        'ch_vil_label',
        'subdivision_id',
        'ch_vil_date_enregistrement',
        'ch_vil_mis_jour',
        'ch_vil_nb_update',
        'ch_vil_coord_X',
        'ch_vil_coord_Y',
        'ch_vil_type_jeu',
        'ch_vil_nom',
        'ch_vil_armoiries',
        'ch_vil_capitale',
        'ch_vil_population',
        'ch_vil_specialite',
        'ch_vil_lien_img1',
        'ch_vil_lien_img2',
        'ch_vil_lien_img3',
        'ch_vil_lien_img4',
        'ch_vil_lien_img5',
        'ch_vil_legende_img1',
        'ch_vil_legende_img2',
        'ch_vil_legende_img3',
        'ch_vil_legende_img4',
        'ch_vil_legende_img5',
        'ch_vil_header',
        'ch_vil_contenu',
        'ch_vil_transports',
        'ch_vil_administration',
        'ch_vil_culture',
    ];

    protected array $dontVersionFields = [
        'ch_vil_ID',
        'ch_vil_paysID',
        'ch_vil_user',
        'ch_vil_label',
        'subdivision_id',
        'ch_vil_date_enregistrement',
        'ch_vil_mis_jour',
        'ch_vil_nb_update',
    ];

    /**
     * @return string
     */
    public static function getNameColumn(): string
    {
        return 'ch_vil_nom';
    }

    /**
     * @return SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $context = null;
        if (! is_null($this->pays)) {
            $context = 'Ville du pays <a href="page-pays.php?ch_pay_id='
                . $this->ch_vil_paysID . '">' . $this->pays->ch_pay_nom . '</a>';
        }

        return new SearchResult(
            $this, $this->ch_vil_nom, $context,
            Str::limit(strip_tags($this->ch_vil_contenu), 150),
            url("page-ville.php?ch_ville_id={$this->ch_vil_ID}&ch_pay_id={$this->ch_vil_paysID}")
        );
    }

    /**
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ch_vil_paysID');
    }

    /**
     * @return HasMany
     */
    public function patrimoines(): HasMany
    {
        return $this->hasMany(Patrimoine::class, 'ch_pat_villeID');
    }

    /**
     * @return BelongsTo
     */
    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }

    /**
     * @return Collection<int, CustomUser>
     */
    public function getUsers(): Collection
    {
        return $this->pays->getUsers();
    }

    /**
     * @inheritDoc
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query;
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
    public function patrimoineResources(): array
    {
        return EconomyService::sumGeneratedResourcesFromInfluencables($this->patrimoines);
    }

    /**
     * @return array<string, float>
     */
    public function resources(): array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        $infrastructureResources = $this->infrastructureResources();
        $patrimoineResources = $this->patrimoineResources();
        $roleplayResources = $this->roleplayResources();

        foreach (Resource::cases() as $resource) {
            $sumResources[$resource->value] += $infrastructureResources[$resource->value]
                + $patrimoineResources[$resource->value]
                + $roleplayResources[$resource->value];
        }

        return $sumResources;
    }

    public function isEnabled(): bool
    {
        return $this->pays->isEnabled();
    }

    public static function boot(): void
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function (Ville $ville): void {
            $ville->deleteAllInfrastructures();
        });
    }
}
