<?php

namespace App\Models;

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
        'chapter_id' => 'int'
    ];

    protected $fillable = [
        'content',
        'media_type',
        'media_data'
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
