<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Infrastructure
 * 
 * @property int $ch_inf_id
 * @property string $ch_inf_label
 * @property int $ch_inf_off_id
 * @property int $ch_inf_villeid
 * @property Carbon $ch_inf_date
 * @property int $ch_inf_statut
 * @property string $nom_infra
 * @property string|null $ch_inf_lien_image
 * @property string|null $ch_inf_lien_image2
 * @property string|null $ch_inf_lien_image3
 * @property string|null $ch_inf_lien_image4
 * @property string|null $ch_inf_lien_image5
 * @property string|null $ch_inf_lien_forum
 * @property string|null $lien_wiki
 * @property int|null $user_creator
 * @property string|null $ch_inf_commentaire
 * @property int|null $ch_inf_juge
 * @property string|null $ch_inf_commentaire_juge
 * 
 * @property CustomUser $user
 *
 * @package App\Models
 */
class Infrastructure extends Model
{
	protected $table = 'infrastructures';
	protected $primaryKey = 'ch_inf_id';

	public const CREATED_AT = 'ch_inf_date';
	public const UPDATED_AT = null;

	protected $casts = [
		'ch_inf_off_id' => 'int',
		'ch_inf_villeid' => 'int',
		'ch_inf_statut' => 'int',
		'user_creator' => 'int',
		'ch_inf_juge' => 'int'
	];

	protected $dates = [
		'ch_inf_date'
	];

	protected $fillable = [
		'ch_inf_off_id',
		'ch_inf_villeid',
		'nom_infra',
		'ch_inf_lien_image',
		'ch_inf_lien_image2',
		'ch_inf_lien_image3',
		'ch_inf_lien_image4',
		'ch_inf_lien_image5',
		'ch_inf_lien_forum',
        'ch_inf_commentaire',
		'lien_wiki',
        'infrastructurable_type',
        'infrastructurable_id',
	];

	public const JUGEMENT_PENDING  = 1;
	public const JUGEMENT_ACCEPTED = 2;
	public const JUGEMENT_REJECTED = 3;

	public function infrastructurable()
    {
	    return $this->morphTo();
    }

    public function infrastructure_officielle()
    {
        return $this->belongsTo(InfrastructureOfficielle::class, 'ch_inf_off_id');
    }

	public function user()
	{
		return $this->belongsTo(CustomUser::class, 'user_creator');
	}

	public static function getMorphFromUrlParameter($parameter)
    {
	    switch($parameter) {
            case 'ville': $class = Ville::class; break;
            case 'pays': $class = Pays::class; break;
            case 'organisation': $class = Organisation::class; break;
            default: throw new \InvalidArgumentException("Mauvais type de modèle.");
        }
        return self::getActualClassNameForMorph($class);
    }

    public static function getUrlParameterFromMorph($morphType)
    {
        $morph = explode("\\", $morphType);
        return strtolower(end($morph));
    }
}
