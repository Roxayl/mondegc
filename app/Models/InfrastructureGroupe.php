<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfrastructureGroupe
 * 
 * @property int $id
 * @property string|null $nom_groupe
 * @property string $url_image
 * @property int $order
 * @property Carbon $created
 *
 * @package App\Models
 */
class InfrastructureGroupe extends Model
{
    protected $table = 'infrastructures_groupes';
    public $timestamps = false;

    protected $casts = [
        'order' => 'int'
    ];

    protected $dates = [
        'created'
    ];

    protected $fillable = [
        'nom_groupe',
        'url_image',
        'order',
        'created'
    ];

    public function infrastructures_officielles()
    {
        return $this->belongsToMany(
            InfrastructureOfficielle::class,
            'infrastructures_officielles_groupes',
            'ID_groupes',
            'ID_infra_officielle');
    }
}
