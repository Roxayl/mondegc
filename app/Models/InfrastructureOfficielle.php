<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 *
 * @package App\Models
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

    public function infrastructures()
    {
        return $this->hasMany(Infrastructure::class, 'ch_inf_off_id');
    }

    public function infrastructure_groupe()
    {
        return $this->belongsToMany(
            InfrastructureGroupe::class,
            'infrastructures_officielles_groupes',
            'ID_infra_officielle',
            'ID_groupes'
        );
    }
}
