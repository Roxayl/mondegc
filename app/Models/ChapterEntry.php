<?php

namespace App\Models;

use App\View\Components;
use App\View\Components\ChapterEntry\BaseMediaEntry;
use Database\Factories\ChapterEntryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class ChapterEntry
 *
 * @property int $id
 * @property int $chapter_id
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
        'content',
    ];

    /**
     * @var array<string, string>
     */
    protected static array $componentMorphMap = [
        'squirrel.squit' => Components\ChapterEntry\SquirrelSquit::class,
        'forum.post' => Components\ChapterEntry\ForumPost::class,
    ];

    /**
     * @return BelongsTo
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
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
