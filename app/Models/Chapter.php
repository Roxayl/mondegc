<?php

namespace App\Models;

use App\Models\Traits\Versionable;
use Carbon\Carbon;
use Database\Factories\ChapterFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query;
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
 * @property Carbon|null $deleted_at
 * @property Roleplay $roleplay
 * @property CustomUser $userCreator
 * @property Collection|ChapterResourceable[] $resourceables
 * @property Collection|ChapterEntry[] $entries
 * @property-write mixed $reason
 * @property-read int|null $resourceables_count
 * @property-read int|null $entries_count
 * @property-read Collection|Version[] $versions
 * @property-read int|null $versions_count
 * @method static ChapterFactory factory(...$parameters)
 * @method static Builder|Chapter newModelQuery()
 * @method static Builder|Chapter newQuery()
 * @method static Builder|Chapter query()
 * @method static Builder|Chapter whereContent($value)
 * @method static Builder|Chapter whereCreatedAt($value)
 * @method static Builder|Chapter whereEndingDate($value)
 * @method static Builder|Chapter whereId($value)
 * @method static Builder|Chapter whereName($value)
 * @method static Builder|Chapter whereOrder($value)
 * @method static Builder|Chapter whereRoleplayId($value)
 * @method static Builder|Chapter whereStartingDate($value)
 * @method static Builder|Chapter whereSummary($value)
 * @method static Builder|Chapter whereUpdatedAt($value)
 * @method static Builder|Chapter whereUserId($value)
 * @method static Query\Builder|Chapter onlyTrashed()
 * @method static Builder|Chapter whereDeletedAt($value)
 * @method static Query\Builder|Chapter withTrashed()
 * @method static Query\Builder|Chapter withoutTrashed()
 * @mixin Model
 */
class Chapter extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Versionable;

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

    protected $hidden = [
        'order',
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
    public function entries(): HasMany
    {
        return $this->hasMany(ChapterEntry::class);
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

        static::deleting(function (Chapter $chapter) {
            // Lors de la suppression d'un chapitre, il faut réordonner la séquence des numéros d'ordre (pour éviter
            // les trous dans les numéros de chapitre...).

            /** @var Collection<Chapter> $chapters */
            $chapters = Chapter::query()
                ->whereRoleplayId($chapter->roleplay_id)
                ->where('id', '!=', $chapter->id)
                ->orderBy('order')
                ->get();

            for($i = 0; $i < $chapters->count(); $i++) {
                /** @var Chapter $chapter */
                $chapter = $chapters->get($i);
                $chapter->order = $i + 1;
                $chapter->save();
            }
        });
    }
}
