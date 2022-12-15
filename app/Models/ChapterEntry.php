<?php

namespace Roxayl\MondeGC\Models;

use Database\Factories\ChapterEntryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Roxayl\MondeGC\View\Components;
use Roxayl\MondeGC\View\Components\ChapterEntry\BaseMediaEntry;

/**
 * Class ChapterEntry
 *
 * @property int $id
 * @property int $chapter_id
 * @property string $title
 * @property string $content
 * @property string $roleplayable_type
 * @property int $roleplayable_id
 * @property string|null $media_type
 * @property array|null $media_parameters
 * @property array|null $media_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Chapter $chapter
 * @method static Builder|ChapterEntry newModelQuery()
 * @method static Builder|ChapterEntry newQuery()
 * @method static Builder|ChapterEntry query()
 * @method static Builder|ChapterEntry whereChapterId($value)
 * @method static Builder|ChapterEntry whereRoleplayableId($value)
 * @method static Builder|ChapterEntry whereRoleplayableType($value)
 * @method static Builder|ChapterEntry whereTitle($value)
 * @method static Builder|ChapterEntry whereContent($value)
 * @method static Builder|ChapterEntry whereCreatedAt($value)
 * @method static Builder|ChapterEntry whereId($value)
 * @method static Builder|ChapterEntry whereMediaType($value)
 * @method static Builder|ChapterEntry whereMediaData($value)
 * @method static Builder|ChapterEntry whereMediaParameters($value)
 * @method static Builder|ChapterEntry whereUpdatedAt($value)
 * @method static ChapterEntryFactory factory(...$parameters)
 * @mixin \Eloquent
 */
class ChapterEntry extends Model
{
    use HasFactory;

    protected $table = 'chapter_entries';

    protected $casts = [
        'chapter_id' => 'int',
        'media_data' => 'array',
        'media_parameters' => 'array',
    ];

    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * @var array<string, string>
     */
    protected static array $componentMorphMap = [
        'forum.post' => Components\ChapterEntry\ForumPost::class,
        'monde.communique' => Components\ChapterEntry\MondeCommunique::class,
        'squirrel.squit' => Components\ChapterEntry\SquirrelSquit::class,
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
    public function roleplayable(): MorphTo
    {
        return $this->morphTo('roleplayable');
    }

    /**
     * @param  string|null  $key
     * @return array<string, string>|string
     */
    public static function getComponentMorphMap(?string $key = null): array|string
    {
        if($key === null) {
            return self::$componentMorphMap;
        }
        return self::$componentMorphMap[$key];
    }

    /**
     * @return BaseMediaEntry|null
     */
    public function mediaViewComponent(): ?BaseMediaEntry
    {
        $mediaType = $this->media_type;

        $componentMorphMap = self::getComponentMorphMap();
        if(! array_key_exists($mediaType, $componentMorphMap)) {
            return null;
        }

        $className = $componentMorphMap[$mediaType];
        return new $className($this);
    }

    /**
     * A partir de la valeur de {@see media_type}, définit la valeur de {@see media_data} à partir de ce qui est
     * passé en paramètre (cf. {@see media_parameters}).
     *
     * @param array<string, array> $parameters
     */
    public function generateMediaData(array $parameters = []): void
    {
        $mediaComponent = $this->mediaViewComponent();

        if($mediaComponent === null) {
            $this->media_type = null;
            $this->media_parameters = null;
            $this->media_data = null;
        } else {
            $this->media_parameters = $parameters;
            $this->media_data = $mediaComponent->generateData($parameters);
        }
    }
}
