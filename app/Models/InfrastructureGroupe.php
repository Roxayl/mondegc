<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property-read Collection|InfrastructureOfficielle[] $infrastructures_officielles
 * @property-read int|null $infrastructures_officielles_count
 * @method static Builder|InfrastructureGroupe newModelQuery()
 * @method static Builder|InfrastructureGroupe newQuery()
 * @method static Builder|InfrastructureGroupe query()
 * @method static Builder|InfrastructureGroupe whereCreated($value)
 * @method static Builder|InfrastructureGroupe whereId($value)
 * @method static Builder|InfrastructureGroupe whereNomGroupe($value)
 * @method static Builder|InfrastructureGroupe whereOrder($value)
 * @method static Builder|InfrastructureGroupe whereUrlImage($value)
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

    /**
     * @return BelongsToMany
     */
    public function infrastructuresOfficielles(): BelongsToMany
    {
        return $this->belongsToMany(
            InfrastructureOfficielle::class,
            'infrastructures_officielles_groupes',
            'ID_groupes',
            'ID_infra_officielle');
    }
}
