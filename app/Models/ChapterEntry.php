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
 * @property string|null $media_type
 * @property string|null $media_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Chapter $chapter
 * @method static Builder|ChapterEntry newModelQuery()
 * @method static Builder|ChapterEntry newQuery()
 * @method static Builder|ChapterEntry query()
 * @method static Builder|ChapterEntry whereChapterId($value)
 * @method static Builder|ChapterEntry whereContent($value)
 * @method static Builder|ChapterEntry whereCreatedAt($value)
 * @method static Builder|ChapterEntry whereId($value)
 * @method static Builder|ChapterEntry whereMediaData($value)
 * @method static Builder|ChapterEntry whereMediaType($value)
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
    ];

    protected $fillable = [
        'content',
        'media_type',
        'media_data',
    ];

    /**
     * @var array<string, string>
     */
    protected array $componentMorphMap = [
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
     * @return array<string, string>
     */
    public function getComponentMorphMap(): array
    {
        return $this->componentMorphMap;
    }

    /**
     * @return BaseMediaEntry|null
     */
    public function mediaViewComponent(): ?BaseMediaEntry
    {
        $mediaType = $this->media_type;

        $componentMorphMap = $this->getComponentMorphMap();
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
            $this->media_data = null;
        } else {
            $this->media_data = $mediaComponent->generateData($parameters);
        }
    }
}
