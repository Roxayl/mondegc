<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Database\Factories\ChapterResourceableFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Roxayl\MondeGC\Models\Contracts\Influencable;
use Roxayl\MondeGC\Models\Contracts\Resourceable;
use Roxayl\MondeGC\Models\Contracts\Roleplayable;
use Roxayl\MondeGC\Models\Traits\DeletesInfluences;
use Roxayl\MondeGC\Models\Traits\Influencable as GeneratesInfluence;
use Roxayl\MondeGC\Services\EconomyService;

/**
 * Class ChapterResourceable
 *
 * @property int $id
 * @property int $chapter_id
 * @property string $resourceable_type
 * @property int $resourceable_id
 * @property string $description
 * @property float $budget
 * @property float $commerce
 * @property float $industrie
 * @property float $agriculture
 * @property float $tourisme
 * @property float $recherche
 * @property float $environnement
 * @property float $education
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Chapter $chapter
 * @property Resourceable $resourceable
 * @property-read Collection|Influence[] $influences
 * @property-read int|null $influences_count
 * @method static ChapterResourceableFactory factory(...$parameters)
 * @method static Builder|ChapterResourceable newModelQuery()
 * @method static Builder|ChapterResourceable newQuery()
 * @method static Builder|ChapterResourceable query()
 * @method static Builder|ChapterResourceable whereAgriculture($value)
 * @method static Builder|ChapterResourceable whereBudget($value)
 * @method static Builder|ChapterResourceable whereChapterId($value)
 * @method static Builder|ChapterResourceable whereCommerce($value)
 * @method static Builder|ChapterResourceable whereCreatedAt($value)
 * @method static Builder|ChapterResourceable whereEducation($value)
 * @method static Builder|ChapterResourceable whereEnvironnement($value)
 * @method static Builder|ChapterResourceable whereId($value)
 * @method static Builder|ChapterResourceable whereIndustrie($value)
 * @method static Builder|ChapterResourceable whereRecherche($value)
 * @method static Builder|ChapterResourceable whereResourceableId($value)
 * @method static Builder|ChapterResourceable whereResourceableType($value)
 * @method static Builder|ChapterResourceable whereDescription($value)
 * @method static Builder|ChapterResourceable whereTourisme($value)
 * @method static Builder|ChapterResourceable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChapterResourceable extends Model implements Influencable
{
    use HasFactory, GeneratesInfluence, DeletesInfluences;

    protected $table = 'chapter_resourceable';

    protected $casts = [
        'chapter_id' => 'int',
        'resourceable_id' => 'int',
        'budget' => 'float',
        'commerce' => 'float',
        'industrie' => 'float',
        'agriculture' => 'float',
        'tourisme' => 'float',
        'recherche' => 'float',
        'environnement' => 'float',
        'education' => 'float',
    ];

    protected $fillable = [
        'description',
        'budget',
        'commerce',
        'industrie',
        'agriculture',
        'tourisme',
        'recherche',
        'environnement',
        'education',
    ];

    /**
     * @return BelongsTo
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * @return MorphTo
     */
    public function resourceable(): MorphTo
    {
        return $this->morphTo('resourceable');
    }

    /**
     * @return Roleplay
     */
    public function roleplay(): Roleplay
    {
        return $this->chapter->roleplay;
    }

    /**
     * @return array<string, float>
     */
    public function resources(): array
    {
        $sumResources = EconomyService::resourcesPrefilled();
        foreach(config('enums.resources') as $resource) {
            $sumResources[$resource] += $this->$resource;
        }

        return $sumResources;
    }

    /**
     * @param Chapter $chapter
     */
    public function setChapter(Chapter $chapter): void
    {
        $this->setRelation('chapter', $chapter);
        $this->chapter_id = $chapter->getKey();
    }

    /**
     * @param Resourceable|Roleplayable $resourceable
     * @todo Ségréger les interfaces Resourceable et Roleplayable, car pour le moment, ça n'a pas bcp de sens...
     */
    public function setResourceable(Resourceable|Roleplayable $resourceable): void
    {
        $this->setRelation('resourceable', $resourceable);
        $this->resourceable_type = get_class($resourceable);
        $this->resourceable_id = $resourceable->getKey();
    }

    /**
     * @inheritDoc
     */
    public function generateInfluence(): void
    {
        $influencableType = Influence::getActualClassNameForMorph(get_class());

        $this->removeOldInfluenceRows();

        $resources = $this->resources();

        $influence = new Influence;
        $influence->influencable_type      = $influencableType;
        $influence->influencable_id        = $this->id;
        $influence->generates_influence_at = $this->created_at;
        $influence->fill($resources)
            ->save();
    }

    public static function boot()
    {
        parent::boot();

        // Générer les influences à la création du modèle.
        static::created(function(ChapterResourceable $chapterResourceable) {
            $chapterResourceable->generateInfluence();
        });

        // Regénérer les influences à la modification du modèle.
        static::updated(function(ChapterResourceable $chapterResourceable) {
            $chapterResourceable->generateInfluence();
        });

        // Appelle la méthode ci-dessous avant d'appeler la méthode delete() sur ce modèle.
        static::deleting(function(ChapterResourceable $chapterResourceable) {
            $chapterResourceable->deleteInfluences();
        });
    }
}
