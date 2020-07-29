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
	public $timestamps = false;

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
		'ch_inf_label',
		'ch_inf_off_id',
		'ch_inf_villeid',
		'ch_inf_date',
		'ch_inf_statut',
		'nom_infra',
		'ch_inf_lien_image',
		'ch_inf_lien_image2',
		'ch_inf_lien_image3',
		'ch_inf_lien_image4',
		'ch_inf_lien_image5',
		'ch_inf_lien_forum',
		'lien_wiki',
		'user_creator',
		'ch_inf_commentaire',
		'ch_inf_juge',
		'ch_inf_commentaire_juge'
	];

	public function ville()
    {
        return $this->belongsTo(Ville::class, 'ch_inf_villeid');
    }

	public function user()
	{
		return $this->belongsTo(CustomUser::class, 'user_creator');
	}
}
