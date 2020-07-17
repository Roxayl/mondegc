<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Ville
 * 
 * @property int $ch_vil_ID
 * @property int $ch_vil_paysID
 * @property int $ch_vil_user
 * @property string $ch_vil_label
 * @property Carbon|null $ch_vil_date_enregistrement
 * @property Carbon|null $ch_vil_mis_jour
 * @property int|null $ch_vil_nb_update
 * @property float|null $ch_vil_coord_X
 * @property float|null $ch_vil_coord_Y
 * @property string|null $ch_vil_type_jeu
 * @property string|null $ch_vil_nom
 * @property string|null $ch_vil_armoiries
 * @property bool $ch_vil_capitale
 * @property int|null $ch_vil_population
 * @property string|null $ch_vil_specialite
 * @property string|null $ch_vil_lien_img1
 * @property string|null $ch_vil_lien_img2
 * @property string|null $ch_vil_lien_img3
 * @property string|null $ch_vil_lien_img4
 * @property string|null $ch_vil_lien_img5
 * @property string|null $ch_vil_legende_img1
 * @property string|null $ch_vil_legende_img2
 * @property string|null $ch_vil_legende_img3
 * @property string|null $ch_vil_legende_img4
 * @property string|null $ch_vil_legende_img5
 * @property string|null $ch_vil_header
 * @property string|null $ch_vil_contenu
 * @property string|null $ch_vil_transports
 * @property string|null $ch_vil_administration
 * @property string|null $ch_vil_culture
 * 
 * @property Pays $pays
 *
 * @package App\Models
 */
class Ville extends Model implements Searchable
{
	protected $table = 'villes';
	protected $primaryKey = 'ch_vil_ID';
    const CREATED_AT = 'ch_vil_date_enregistrement';
    const UPDATED_AT = 'ch_vil_mis_jour';

	protected $casts = [
		'ch_vil_paysID' => 'int',
		'ch_vil_user' => 'int',
		'ch_vil_nb_update' => 'int',
		'ch_vil_coord_X' => 'float',
		'ch_vil_coord_Y' => 'float',
		'ch_vil_capitale' => 'bool',
		'ch_vil_population' => 'int'
	];

	protected $dates = [
		'ch_vil_date_enregistrement',
		'ch_vil_mis_jour'
	];

	protected $fillable = [
		'ch_vil_paysID',
		'ch_vil_user',
		'ch_vil_label',
		'ch_vil_date_enregistrement',
		'ch_vil_mis_jour',
		'ch_vil_nb_update',
		'ch_vil_coord_X',
		'ch_vil_coord_Y',
		'ch_vil_type_jeu',
		'ch_vil_nom',
		'ch_vil_armoiries',
		'ch_vil_capitale',
		'ch_vil_population',
		'ch_vil_specialite',
		'ch_vil_lien_img1',
		'ch_vil_lien_img2',
		'ch_vil_lien_img3',
		'ch_vil_lien_img4',
		'ch_vil_lien_img5',
		'ch_vil_legende_img1',
		'ch_vil_legende_img2',
		'ch_vil_legende_img3',
		'ch_vil_legende_img4',
		'ch_vil_legende_img5',
		'ch_vil_header',
		'ch_vil_contenu',
		'ch_vil_transports',
		'ch_vil_administration',
		'ch_vil_culture'
	];

	public function getSearchResult() : SearchResult
    {
	    return new SearchResult(
	        $this, $this->ch_vil_nom,
            url("page-ville.php?ch_ville_id={$this->ch_vil_ID}&ch_pay_id={$this->ch_vil_paysID}")
        );
    }

	public function pays()
	{
		return $this->belongsTo(Pays::class, 'ch_vil_paysID');
	}
}
