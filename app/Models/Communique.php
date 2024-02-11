<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * Class Communique.
 *
 * @property int $ch_com_ID
 * @property string $ch_com_label
 * @property int $ch_com_statut
 * @property string|null $ch_com_categorie
 * @property int $ch_com_element_id
 * @property int $ch_com_user_id
 * @property Carbon|null $ch_com_date
 * @property Carbon|null $ch_com_date_mis_jour
 * @property string|null $ch_com_titre
 * @property string $ch_com_contenu
 * @property int|null $ch_com_pays_id
 * @property CustomUser $user
 *
 * @method static Builder|Communique newModelQuery()
 * @method static Builder|Communique newQuery()
 * @method static Builder|Communique query()
 * @method static Builder|Communique whereChComCategorie($value)
 * @method static Builder|Communique whereChComContenu($value)
 * @method static Builder|Communique whereChComDate($value)
 * @method static Builder|Communique whereChComDateMisJour($value)
 * @method static Builder|Communique whereChComElementId($value)
 * @method static Builder|Communique whereChComID($value)
 * @method static Builder|Communique whereChComLabel($value)
 * @method static Builder|Communique whereChComPaysId($value)
 * @method static Builder|Communique whereChComStatut($value)
 * @method static Builder|Communique whereChComTitre($value)
 * @method static Builder|Communique whereChComUserId($value)
 * @method static Builder|Communique mainPosts()
 *
 * @mixin \Eloquent
 */
class Communique extends Model implements Searchable
{
    protected $table = 'communiques';
    protected $primaryKey = 'ch_com_ID';

    protected $casts = [
        'ch_com_statut' => 'int',
        'ch_com_element_id' => 'int',
        'ch_com_user_id' => 'int',
        'ch_com_pays_id' => 'int',
    ];

    const CREATED_AT = 'ch_com_date';
    const UPDATED_AT = 'ch_com_date_mis_jour';

    protected $dates = [
        'ch_com_date',
        'ch_com_date_mis_jour',
    ];

    protected $fillable = [
        'ch_com_statut',
        'ch_com_categorie',
        'ch_com_element_id',
        'ch_com_titre',
        'ch_com_contenu',
        'ch_com_pays_id',
    ];

    public const STATUS_PUBLISHED = 1;
    public const STATUS_DRAFT = 2;

    /**
     * @var array<string, class-string<Roleplayable>|null>
     */
    private static array $publisherMorphMap = [
        'com_communique' => Pays::class,
        'com_pays' => Pays::class,
        'com_ville' => Pays::class,
        'com_monument' => Pays::class,
        'com_fait_his' => Pays::class,
        'institut' => null,
        'organisation' => Organisation::class,
        'pays' => Pays::class,
        'ville' => Ville::class,
    ];

    public string $searchableType = 'Communiqués';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'ch_com_user_id');
    }

    /**
     * Donne l'entité à l'origine du communiqué.
     *
     * @return Roleplayable|null
     */
    public function publisher(): ?Roleplayable
    {
        $class = self::$publisherMorphMap[$this->ch_com_categorie];

        if ($class === null) {
            throw new \InvalidArgumentException("Pas de classe assignée pour {$this->ch_com_categorie}.");
        }

        return $class::find($this->ch_com_element_id);
    }

    /**
     * Donne les communiqués principaux, e.g. qui ne sont pas des réponses à d'autres communiqués.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeMainPosts(Builder $query): Builder
    {
        return $query->where('ch_com_categorie', 'NOT LIKE', 'com_%');
    }

    /**
     * @return SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $context = 'Publié le ' . $this->ch_com_date->format('d/m/Y à H:i');

        try {
            $publisher = $this->publisher();
            if ($publisher !== null) {
                $context .= ' par <a href="' . e($publisher->accessorUrl()) . '">' . e($publisher->getName()) . '</a>';
            }
        } catch (\InvalidArgumentException $ex) {
        }

        return new SearchResult(
            $this, (string) $this->ch_com_titre, $context,
            Str::limit(strip_tags($this->ch_com_contenu), 150),
            url("page-communique.php?com_id=$this->ch_com_ID")
        );
    }
}
