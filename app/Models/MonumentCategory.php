<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class MonumentCategory
 * 
 * @property int $ch_mon_cat_ID
 * @property string|null $ch_mon_cat_label
 * @property int $ch_mon_cat_statut
 * @property Carbon $ch_mon_cat_date
 * @property Carbon $ch_mon_cat_mis_jour
 * @property int $ch_mon_cat_nb_update
 * @property string|null $ch_mon_cat_nom
 * @property string|null $ch_mon_cat_desc
 * @property string|null $ch_mon_cat_icon
 * @property string|null $ch_mon_cat_couleur
 * @property string|null $ch_mon_cat_fond
 * @property int|null $ch_mon_cat_industrie
 * @property int|null $ch_mon_cat_commerce
 * @property int|null $ch_mon_cat_agriculture
 * @property int|null $ch_mon_cat_tourisme
 * @property int|null $ch_mon_cat_recherche
 * @property int|null $ch_mon_cat_environnement
 * @property int|null $ch_mon_cat_education
 * @property int|null $ch_mon_cat_budget
 * @property string|null $bg_image_url
 *
 * @package App\Models
 */
class MonumentCategory extends Model
{
    protected $table = 'monument_categories';
    protected $primaryKey = 'ch_mon_cat_ID';

    const CREATED_AT = 'ch_mon_cat_date';
    const UPDATED_AT = 'ch_mon_cat_mis_jour';

    protected $casts = [
        'ch_mon_cat_statut' => 'int',
        'ch_mon_cat_nb_update' => 'int',
        'ch_mon_cat_industrie' => 'int',
        'ch_mon_cat_commerce' => 'int',
        'ch_mon_cat_agriculture' => 'int',
        'ch_mon_cat_tourisme' => 'int',
        'ch_mon_cat_recherche' => 'int',
        'ch_mon_cat_environnement' => 'int',
        'ch_mon_cat_education' => 'int',
        'ch_mon_cat_budget' => 'int'
    ];

    protected $fillable = [
        'ch_mon_cat_nom',
        'ch_mon_cat_desc',
        'ch_mon_cat_icon',
        'ch_mon_cat_couleur',
        'ch_mon_cat_fond',
        'ch_mon_cat_industrie',
        'ch_mon_cat_commerce',
        'ch_mon_cat_agriculture',
        'ch_mon_cat_tourisme',
        'ch_mon_cat_recherche',
        'ch_mon_cat_environnement',
        'ch_mon_cat_education',
        'ch_mon_cat_budget'
    ];

    public function patrimoine()
    {
        return $this->belongsToMany(
            Patrimoine::class,
            'dispatch_mon_cat',
            'ch_disp_cat_id',
            'ch_disp_mon_id');
    }

    public static function boot() {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function($monumentCategory) {
            // Supprime les entrées liées à la catégorie dans la table pivot.
            DB::delete('DELETE FROM dispatch_mon_cat WHERE ch_disp_cat_id = ?',
                [$monumentCategory->ch_disp_cat_id]);
        });
    }
}
