<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Personnage
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
 * @package App\Models
 * @method static \Database\Factories\PersonnageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereBiographie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereLienImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereNomPersonnage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage wherePredicat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage wherePrenomPersonnage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Personnage whereTitrePersonnage($value)
 * @mixin Model
 */
class Personnage extends Model
{
    use HasFactory;

    protected $table = 'personnage';
    public $timestamps = false;

    protected $casts = [
        'entity_id' => 'int'
    ];

    protected $fillable = [
        'entity',
        'entity_id',
        'nom_personnage',
        'predicat',
        'prenom_personnage',
        'biographie',
        'titre_personnage',
        'lien_img'
    ];
}
