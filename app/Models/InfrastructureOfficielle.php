<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Enums\Resource;

/**
 * Class InfrastructureOfficielle
 *
 * @property int $ch_inf_off_id
 * @property string $ch_inf_off_label
 * @property Carbon $ch_inf_off_date
 * @property string $ch_inf_off_nom
 * @property string|null $ch_inf_off_desc
 * @property string|null $ch_inf_off_icone
 * @property int|null $ch_inf_off_budget
 * @property int|null $ch_inf_off_Industrie
 * @property int|null $ch_inf_off_Commerce
 * @property int|null $ch_inf_off_Agriculture
 * @property int|null $ch_inf_off_Tourisme
 * @property int|null $ch_inf_off_Recherche
 * @property int|null $ch_inf_off_Environnement
 * @property int|null $ch_inf_off_Education
 * @property-read Collection|InfrastructureGroupe[] $infrastructureGroupe
 * @property-read int|null $infrastructure_groupe_count
 * @property-read Collection|Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @method static Builder|InfrastructureOfficielle newModelQuery()
 * @method static Builder|InfrastructureOfficielle newQuery()
 * @method static Builder|InfrastructureOfficielle query()
 * @method static Builder|InfrastructureOfficielle whereChInfOffAgriculture($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffBudget($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffCommerce($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffDate($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffDesc($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffEducation($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffEnvironnement($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffIcone($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffId($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffIndustrie($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffLabel($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffNom($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffRecherche($value)
 * @method static Builder|InfrastructureOfficielle whereChInfOffTourisme($value)
 * @mixin \Eloquent
 */
class InfrastructureOfficielle extends Model
{
    protected $table = 'infrastructures_officielles';
    protected $primaryKey = 'ch_inf_off_id';
    public $timestamps = false;

    protected $casts = [
        'ch_inf_off_budget' => 'int',
        'ch_inf_off_Industrie' => 'int',
        'ch_inf_off_Commerce' => 'int',
        'ch_inf_off_Agriculture' => 'int',
        'ch_inf_off_Tourisme' => 'int',
        'ch_inf_off_Recherche' => 'int',
        'ch_inf_off_Environnement' => 'int',
        'ch_inf_off_Education' => 'int'
    ];

    protected $dates = [
        'ch_inf_off_date'
    ];

    protected $fillable = [
        'ch_inf_off_label',
        'ch_inf_off_date',
        'ch_inf_off_nom',
        'ch_inf_off_desc',
        'ch_inf_off_icone',
        'ch_inf_off_budget',
        'ch_inf_off_Industrie',
        'ch_inf_off_Commerce',
        'ch_inf_off_Agriculture',
        'ch_inf_off_Tourisme',
        'ch_inf_off_Recherche',
        'ch_inf_off_Environnement',
        'ch_inf_off_Education'
    ];

    /**
     * @return HasMany
     */
    public function infrastructures(): HasMany
    {
        return $this->hasMany(Infrastructure::class, 'ch_inf_off_id');
    }

    /**
     * @return BelongsToMany
     */
    public function infrastructureGroupe(): BelongsToMany
    {
        return $this->belongsToMany(
            InfrastructureGroupe::class,
            'infrastructures_officielles_groupes',
            'ID_infra_officielle',
            'ID_groupes'
        );
    }

    /**
     * @return array
     */
    public function mapResources(): array
    {
        $resources = [];
        foreach(Resource::cases() as $resource) {
            $field = 'ch_inf_off_' . ($resource === Resource::BUDGET
                    ? $resource->value : Str::ucfirst($resource->value));
            $resources[$resource->value] = (int)$this->$field;
        }
        return $resources;
    }

    public static function boot()
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function($infrastructureOfficielle) {
            $infrastructureOfficielle->infrastructures()->each(function($infrastructure) {
                $infrastructure->delete();
            });
        });
    }
}
