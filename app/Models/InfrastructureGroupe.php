<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class InfrastructureGroupe
 *
 * @property int $id
 * @property string|null $nom_groupe
 * @property string $url_image
 * @property int $order
 * @property Carbon $created
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InfrastructureOfficielle[] $infrastructures_officielles
 * @property-read int|null $infrastructures_officielles_count
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe query()
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe whereNomGroupe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InfrastructureGroupe whereUrlImage($value)
 * @mixin Model
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

    public function infrastructures_officielles(): BelongsToMany
    {
        return $this->belongsToMany(
            InfrastructureOfficielle::class,
            'infrastructures_officielles_groupes',
            'ID_groupes',
            'ID_infra_officielle');
    }
}
