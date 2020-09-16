<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\AggregatesInfluences;
use App\Models\Contracts\Infrastructurable;
use App\Models\Managers\PaysMapManager;
use App\Models\Presenters\InfrastructurablePresenter;
use App\Models\Presenters\PaysPresenter;
use App\Models\Traits\Infrastructurable as HasInfrastructures;
use App\Services\EconomyService;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
 * 
 * @property Collection|OrganisationMember[] $organisation_members
 *
 * @package App\Models
 */
class Pays extends Model implements Searchable, Infrastructurable, AggregatesInfluences
{
    use InfrastructurablePresenter, PaysPresenter, HasInfrastructures;

    /**
     * @var array|int[]
     */
    protected $table = 'pays';
	protected $primaryKey = 'ch_pay_id';
    const CREATED_AT = 'ch_pay_date';
    const UPDATED_AT = 'ch_pay_mis_jour';

	protected $casts = [
		'ch_pay_publication' => 'bool',
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

	private ?PaysMapManager $mapManager = null;

    public function getMapManager()
    {
        if(is_null($this->mapManager)) {
            $this->mapManager = new PaysMapManager($this);
        }
        return $this->mapManager;
    }

	public function getSearchResult() : SearchResult
    {
	    return new SearchResult(
	        $this, $this->ch_pay_nom, url('page-pays.php?ch_pay_id=' . $this->ch_pay_id)
        );
    }

	public function organisation_members()
	{
		return $this->hasMany(OrganisationMember::class, 'pays_id');
	}

	private function getOrganisationMembership(Closure $f)
    {
        $query = $this->organisation_members()
            ->join('organisation', 'organisation.id', 'organisation_id')
            ->where('permissions', '>=', Organisation::PERMISSION_MEMBER);

        return $f($query);
    }

	public function organisationsAll()
    {
        return $this->getOrganisationMembership(function($query) {
            return $query->get()
                         ->pluck('organisation');
        });
    }

    public function alliance()
    {
        return $this->getOrganisationMembership(function($query) {
            return $query->where('type', Organisation::TYPE_ALLIANCE)
                         ->get()
                         ->pluck('organisation')->first();
        });
    }

    public function otherOrganisations()
    {
        return $this->getOrganisationMembership(function($query) {
            return $query->where('type', '!=', Organisation::TYPE_ALLIANCE)
                         ->get()
                         ->pluck('organisation');
        });
    }

	public function users()
    {
        return $this->belongsToMany(CustomUser::class, 'users_pays', 'ID_pays', 'ID_user');
    }

    public function villes()
    {
        return $this->hasMany(Ville::class, 'ch_vil_paysID');
    }

    public function geometries()
    {
        return $this->hasMany(Geometry::class, 'ch_geo_pay_id');
    }

    public function temperance()
    {
        return $this->hasOne(TemperancePays::class, 'id', 'ch_pay_id');
    }

    public function getUsers()
    {
        return $this->users()->get();
    }

    public function villeResources() : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        foreach($this->villes as $ville) {
            $generatedResources = $ville->resources();
            foreach(config('enums.resources') as $resource) {
                $sumResources[$resource] += $generatedResources[$resource];
            }
        }

        return $sumResources;
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

    public function organisationResources() : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        foreach($this->organisationsAll() as $organisation) {
            $generatedResources = $organisation->infrastructureResources();
            $nbMembers = $organisation->members->count();

            foreach(config('enums.resources') as $resource) {
                $generatedResources[$resource] = (int)$generatedResources[$resource] / $nbMembers;
                $sumResources[$resource] += $generatedResources[$resource];
            }
        }

        return $sumResources;
    }

    public function resources($withOrganisation = true) : array
    {
        $sumResources = EconomyService::resourcesPrefilled();

        $villeResources = $this->villeResources();
        $mapResources = $this->getMapManager()->mapResources();
        $infrastructureResources = $this->infrastructureResources();

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
                                      + $organisationResources[$resource];
        }

        return $sumResources;
    }
}
