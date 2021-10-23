<?php

/**
 * Created by Reliese Model.
 */

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
 *
 * @package App\Models
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
