<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Traits\DeletesInfluences;
use Roxayl\MondeGC\Models\Traits\Influencable as GeneratesInfluence;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

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
 * @property-read Collection|Influence[] $influences
 * @property-read int|null $influences_count
 * @property-read Collection|MonumentCategory[] $monumentCategories
 * @property-read int|null $monument_categories_count
 * @property-read Ville $ville
 * @method static Builder|Patrimoine newModelQuery()
 * @method static Builder|Patrimoine newQuery()
 * @method static Builder|Patrimoine query()
 * @method static Builder|Patrimoine whereChPatCommentaire($value)
 * @method static Builder|Patrimoine whereChPatCommentaireJuge($value)
 * @method static Builder|Patrimoine whereChPatCoordX($value)
 * @method static Builder|Patrimoine whereChPatCoordY($value)
 * @method static Builder|Patrimoine whereChPatDate($value)
 * @method static Builder|Patrimoine whereChPatDescription($value)
 * @method static Builder|Patrimoine whereChPatId($value)
 * @method static Builder|Patrimoine whereChPatJuge($value)
 * @method static Builder|Patrimoine whereChPatLabel($value)
 * @method static Builder|Patrimoine whereChPatLegendeImg1($value)
 * @method static Builder|Patrimoine whereChPatLegendeImg2($value)
 * @method static Builder|Patrimoine whereChPatLegendeImg3($value)
 * @method static Builder|Patrimoine whereChPatLegendeImg4($value)
 * @method static Builder|Patrimoine whereChPatLegendeImg5($value)
 * @method static Builder|Patrimoine whereChPatLienImg1($value)
 * @method static Builder|Patrimoine whereChPatLienImg2($value)
 * @method static Builder|Patrimoine whereChPatLienImg3($value)
 * @method static Builder|Patrimoine whereChPatLienImg4($value)
 * @method static Builder|Patrimoine whereChPatLienImg5($value)
 * @method static Builder|Patrimoine whereChPatMisJour($value)
 * @method static Builder|Patrimoine whereChPatNbUpdate($value)
 * @method static Builder|Patrimoine whereChPatNom($value)
 * @method static Builder|Patrimoine whereChPatPaysID($value)
 * @method static Builder|Patrimoine whereChPatStatut($value)
 * @method static Builder|Patrimoine whereChPatVilleID($value)
 * @mixin Model
 */
class Patrimoine extends Model implements Influencable, Searchable
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

    public string $searchableType = 'Quêtes';

    public function getSearchResult(): SearchResult
    {
        $context = null;
        if(!is_null($this->ville)) {
            $context = 'Quête basée dans la ville <a href="page-pays.php?ch_pay_id='
                . $this->ch_pat_villeID . '">' . $this->ville->ch_vil_nom . '</a>';
        }

        return new SearchResult(
            $this, $this->ch_pat_nom, $context,
            Str::limit(strip_tags($this->ch_pat_description), 150),
            url("page-monument.php?ch_pat_id={$this->ch_pat_id}")
        );
    }

    /**
     * @return BelongsTo
     */
    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ch_pat_villeID');
    }

    /**
     * @return BelongsToMany
     */
    public function monumentCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            MonumentCategory::class,
            'dispatch_mon_cat',
            'ch_disp_mon_id',
            'ch_disp_cat_id');
    }

    public function generateInfluence(): void
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

    public static function boot()
    {
        parent::boot();

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function($patrimoine) {
            $patrimoine->deleteInfluences();
        });
    }
}
