<?php

/**
 * Created by Reliese Model.
 */

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
 * @property Carbon $starting_date
 * @property Carbon|null $ending_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Roleplay $roleplay
 * @property CustomUser $user
 * @property Collection|ChapterResourceable[] $chapter_resourceables
 *
 * @package App\Models
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
}
