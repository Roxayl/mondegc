<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class Chapter
 *
 * @property int $id
 * @property int $roleplay_id
 * @property int $order
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $identifier
 * @property string $summary
 * @property string $content
 * @property Carbon $starting_date
 * @property Carbon|null $ending_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Roleplay $roleplay
 * @property CustomUser $user
 * @property Collection|ChapterResourceable[] $resourceables
 * @package App\Models
 * @property-read int|null $resourceables_count
 * @property-read \App\Models\CustomUser $userCreator
 * @method static \Database\Factories\ChapterFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereEndingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereRoleplayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereStartingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereUserId($value)
 * @mixin Model
 */
class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';

    protected $casts = [
        'roleplay_id' => 'int',
        'order' => 'int',
        'user_id' => 'int'
    ];

    protected $dates = [
        'starting_date',
        'ending_date'
    ];

    protected $fillable = [
        'roleplay_id',
        'order',
        'user_id',
        'name',
        'summary',
        'content',
        'starting_date',
        'ending_date'
    ];

    public function roleplay(): BelongsTo
    {
        return $this->belongsTo(Roleplay::class);
    }

    public function userCreator(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'user_id', 'ch_use_id');
    }

    public function resourceables(): HasMany
    {
        return $this->hasMany(ChapterResourceable::class);
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }

    public function getTitleAttribute(): string
    {
        return "Chapitre " . $this->order . " : " . $this->name;
    }

    public function getIdentifierAttribute(): string
    {
        return $this->order . '-' . $this->slug;
    }
}
