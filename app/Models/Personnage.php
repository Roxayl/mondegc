<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Models;

use Database\Factories\PersonnageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Personnage.
 *
 * @property int $id
 * @property string|null $entity
 * @property int|null $entity_id
 * @property string|null $nom_personnage
 * @property string|null $predicat
 * @property string|null $prenom_personnage
 * @property string|null $biographie
 * @property string|null $titre_personnage
 * @property string|null $lien_img
 *
 * @method static PersonnageFactory factory(...$parameters)
 * @method static Builder|Personnage newModelQuery()
 * @method static Builder|Personnage newQuery()
 * @method static Builder|Personnage query()
 * @method static Builder|Personnage whereBiographie($value)
 * @method static Builder|Personnage whereEntity($value)
 * @method static Builder|Personnage whereEntityId($value)
 * @method static Builder|Personnage whereId($value)
 * @method static Builder|Personnage whereLienImg($value)
 * @method static Builder|Personnage whereNomPersonnage($value)
 * @method static Builder|Personnage wherePredicat($value)
 * @method static Builder|Personnage wherePrenomPersonnage($value)
 * @method static Builder|Personnage whereTitrePersonnage($value)
 *
 * @mixin \Eloquent
 */
class Personnage extends Model
{
    use HasFactory;

    protected $table = 'personnage';
    public $timestamps = false;

    protected $casts = [
        'entity_id' => 'int',
    ];

    protected $fillable = [
        'entity',
        'entity_id',
        'nom_personnage',
        'predicat',
        'prenom_personnage',
        'biographie',
        'titre_personnage',
        'lien_img',
    ];
}
