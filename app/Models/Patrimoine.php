<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\Influencable;
use App\Models\Traits\DeletesInfluences;
use App\Models\Traits\Influencable as GeneratesInfluence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Patrimoine
 * 
 * @property int $ch_pat_id
 * @property string $ch_pat_label
 * @property int $ch_pat_statut
 * @property int $ch_pat_paysID
 * @property int $ch_pat_villeID
 * @property Carbon|null $ch_pat_date
 * @property Carbon|null $ch_pat_mis_jour
 * @property int|null $ch_pat_nb_update
 * @property float|null $ch_pat_coord_X
 * @property float|null $ch_pat_coord_Y
 * @property string|null $ch_pat_nom
 * @property string|null $ch_pat_lien_img1
 * @property string|null $ch_pat_lien_img2
 * @property string|null $ch_pat_lien_img3
 * @property string|null $ch_pat_lien_img4
 * @property string|null $ch_pat_lien_img5
 * @property string|null $ch_pat_legende_img1
 * @property string|null $ch_pat_legende_img2
 * @property string|null $ch_pat_legende_img3
 * @property string|null $ch_pat_legende_img4
 * @property string|null $ch_pat_legende_img5
 * @property string|null $ch_pat_description
 * @property string|null $ch_pat_commentaire
 * @property int|null $ch_pat_juge
 * @property string|null $ch_pat_commentaire_juge
 *
 * @package App\Models
 */
class Patrimoine extends Model implements Influencable
{
    use GeneratesInfluence, DeletesInfluences;

    protected $table = 'patrimoine';
    protected $primaryKey = 'ch_pat_id';
    public $timestamps = false;

    const CREATED_AT = 'ch_pat_date';
    const UPDATED_AT =  'ch_pat_mis_jour';

    protected $casts = [
        'ch_pat_statut' => 'int',
        'ch_pat_paysID' => 'int',
        'ch_pat_villeID' => 'int',
        'ch_pat_nb_update' => 'int',
        'ch_pat_coord_X' => 'float',
        'ch_pat_coord_Y' => 'float',
        'ch_pat_juge' => 'int'
    ];

    protected $dates = [
        'ch_pat_date',
        'ch_pat_mis_jour'
    ];

    protected $fillable = [
        'ch_pat_villeID',
        'ch_pat_coord_X',
        'ch_pat_coord_Y',
        'ch_pat_nom',
        'ch_pat_lien_img1',
        'ch_pat_lien_img2',
        'ch_pat_lien_img3',
        'ch_pat_lien_img4',
        'ch_pat_lien_img5',
        'ch_pat_legende_img1',
        'ch_pat_legende_img2',
        'ch_pat_legende_img3',
        'ch_pat_legende_img4',
        'ch_pat_legende_img5',
        'ch_pat_description',
        'ch_pat_commentaire',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ch_pat_villeID');
    }

    public function monumentCategories()
    {
        return $this->belongsToMany(
            MonumentCategory::class,
            'dispatch_mon_cat',
            'ch_disp_mon_id',
            'ch_disp_cat_id');
    }

    public function generateInfluence() : void
    {
        $this->removeOldInfluenceRows();

        $categories = collect($this->monumentCategories()->get()->toArray());

        if(!$categories->count()) {
            return;
        }

        $resources = config('enums.resources');

        $resources = $categories->pipe(function($categories) use($resources) {
            $return = [];
            foreach($resources as $resource) {
                $return[$resource] = $categories->sum("ch_mon_cat_$resource");
            }
            return $return;
        });

        $influence = new Influence;
        $influence->influencable_type = Influence::getActualClassNameForMorph(get_class());
        $influence->influencable_id = $this->ch_pat_id;
        $influence->generates_influence_at = $this->ch_pat_date;

        $influence->fill($resources);

        $influence->save();
    }

    public static function boot() {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function($patrimoine) {
            $patrimoine->deleteInfluences();
        });
    }
}
