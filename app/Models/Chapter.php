<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Mpociot\Versionable\VersionableTrait;

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
    use VersionableTrait;

    protected $table = 'chapters';

    protected $casts = [
        'roleplay_id' => 'int',
        'order' => 'int',
        'user_id' => 'int',
    ];

    protected $dates = [
        'starting_date',
        'ending_date',
    ];

    protected $fillable = [
        'name',
        'summary',
        'content',
    ];

    const validationRules = [
        'name' => ['min:2', 'max:191', 'required'],
        'summary' => ['min:2', 'required']
    ];

    /**
     * @return BelongsTo
     */
    public function roleplay(): BelongsTo
    {
        return $this->belongsTo(Roleplay::class);
    }

    /**
     * @return BelongsTo
     */
    public function userCreator(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'user_id', 'ch_use_id');
    }

    /**
     * @return HasMany
     */
    public function resourceables(): HasMany
    {
        return $this->hasMany(ChapterResourceable::class);
    }

    /**
     * Détermine si le chapitre en question est le chapitre courant.
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->is($this->roleplay->currentChapter());
    }

    /**
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }

    /**
     * @return string
     */
    public function getTitleAttribute(): string
    {
        return "Chapitre " . $this->order . " : " . $this->name;
    }

    /**
     * @return string
     */
    public function getIdentifierAttribute(): string
    {
        return $this->order . '-' . $this->slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Chapter $chapter) {
            /** @var int $maxOrder Dernier numéro d'ordre de chapitre pour un roleplay donné. */
            $maxOrder = Chapter::whereRoleplayId($chapter->roleplay_id)->max('order') ?? 0;

            // Lors de la création d'un nouveau chapitre, il faut marquer le chapitre précédent comme "terminé".
            /** @var Chapter|null $previousChapter Chapitre précédent. */
            $previousChapter = Chapter::whereRoleplayId($chapter->roleplay_id)->latest('order')->first();

            // Evidemment, il faut que le chapitre existe au préalable ($previousChapter vaut 'null' lorsqu'on créé
            // le tout premier chapitre du roleplay).
            if($previousChapter) {
                $previousChapter->ending_date = now();
                $previousChapter->save();
            }

            $chapter->order = $maxOrder + 1;
            $chapter->starting_date = now();
            $chapter->ending_date = null;
        });
    }
}
