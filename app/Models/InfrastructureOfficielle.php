<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InfrastructureGroupe[] $infrastructure_groupe
 * @property-read int|null $infrastructure_groupe_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Infrastructure[] $infrastructures
 * @property-read int|null $infrastructures_count
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle query()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffAgriculture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffCommerce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffEnvironnement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffIcone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffIndustrie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffRecherche($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureOfficielle whereChInfOffTourisme($value)
 * @mixin Model
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

    public function infrastructures(): HasMany
    {
        return $this->hasMany(Infrastructure::class, 'ch_inf_off_id');
    }

    public function infrastructure_groupe(): BelongsToMany
    {
        return $this->belongsToMany(
            InfrastructureGroupe::class,
            'infrastructures_officielles_groupes',
            'ID_infra_officielle',
            'ID_groupes'
        );
    }

    public function mapResources(): array
    {
        $resources = [];
        foreach(config('enums.resources') as $resource) {
            $field = 'ch_inf_off_' . ($resource === 'budget'
                    ? $resource : Str::ucfirst($resource));
            $resources[$resource] = (int)$this->$field;
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
