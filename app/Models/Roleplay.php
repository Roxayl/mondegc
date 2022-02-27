<?php

namespace App\Models;

use App\Models\Contracts\Roleplayable;
use App\Models\Factories\RoleplayableFactory;
use Carbon\Carbon;
use Database\Factories\RoleplayFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query;
use Illuminate\Support;
use Illuminate\Support\Facades\DB;

/**
 * Class Roleplay
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string|null $banner
 * @property string $description
 * @property Carbon $starting_date
 * @property Carbon|null $ending_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property CustomUser $owner
 * @property Collection|Chapter[] $chapters
 * @property Support\Carbon|null $deleted_at
 * @method static Builder|Roleplay current() Filtre sur la liste des roleplays actuels, en cours.
 * @property-read int|null $chapters_count
 * @method static RoleplayFactory factory(...$parameters)
 * @method static Builder|Roleplay newModelQuery()
 * @method static Builder|Roleplay newQuery()
 * @method static Builder|Roleplay query()
 * @method static Builder|Roleplay whereCreatedAt($value)
 * @method static Builder|Roleplay whereEndingDate($value)
 * @method static Builder|Roleplay whereId($value)
 * @method static Builder|Roleplay whereName($value)
 * @method static Builder|Roleplay whereStartingDate($value)
 * @method static Builder|Roleplay whereUpdatedAt($value)
 * @method static Builder|Roleplay whereUserId($value)
 * @method static Builder|Roleplay whereBanner($value)
 * @method static Builder|Roleplay whereDescription($value)
 * @method static Query\Builder|Roleplay onlyTrashed()
 * @method static Builder|Roleplay whereDeletedAt($value)
 * @method static Query\Builder|Roleplay withTrashed()
 * @method static Query\Builder|Roleplay withoutTrashed()
 * @mixin Model
 */
class Roleplay extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'roleplay';

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $dates = [
        'starting_date',
        'ending_date',
    ];

    protected $fillable = [
        'name',
        'banner',
        'description',
    ];

    public const validationRules = [
        'name' => 'required|min:2|max:191',
    ];

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'user_id', 'ch_use_id');
    }

    /**
     * @return HasMany
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Filtre sur la liste des roleplays actuels, en cours.
     * Ce filtre va considérer que les roleplays terminés il y a moins de 2 jours sont toujours "actuels".
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query
            ->where('starting_date', '<=', now()->addDay())
            ->where(function (Builder $query) {
                $query->whereNull('ending_date')
                    ->orWhere('ending_date', '>=', now()->subDays(2));
            });
    }

    /**
     * Détermine si le roleplay est toujours en cours (ou clôturé).
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->ending_date === null || $this->ending_date > now();
    }

    /**
     * Renvoie le chapitre en cours pour ce roleplay.
     *
     * @return Chapter|null
     */
    public function currentChapter(): ?Chapter
    {
        if($this->isValid()) {
            /** @var Chapter|null $chapter */
            $chapter = $this->chapters()->orderBy('order', 'desc')->first();
            return $chapter;
        }
        return null;
    }

    /**
     * Marque un roleplay comme terminé.
     */
    public function close(): void
    {
        $this->ending_date = now();
    }

    /**
     * Donne une collection des {@link Roleplayable organisateurs du roleplay}.
     *
     * @return Collection<int, Roleplayable>
     */
    public function organizers(): Collection
    {
        $query = DB::table('roleplay_organizers')
            ->where('roleplay_id', $this->id)
            ->get();

        $roleplayOrganizers = new Collection();

        foreach($query as $row) {
            /** @var Roleplayable|null $organizer */
            $organizer = RoleplayableFactory::find($row->organizer_type, $row->organizer_id);

            if($organizer === null) continue;

            if(! $roleplayOrganizers->contains($organizer)) {
                $roleplayOrganizers->add($organizer);
            }
        }

        return $roleplayOrganizers;
    }

    /**
     * @param Roleplayable $model
     * @return bool
     */
    public function hasOrganizer(Roleplayable $model): bool
    {
        return $this->hasOrganizerAmong(collect([$model]));
    }

    /**
     * @param Support\Collection $roleplayables
     * @return bool
     */
    public function hasOrganizerAmong(Support\Collection $roleplayables): bool
    {
        $query = DB::table('roleplay_organizers')
            ->where('roleplay_id', $this->id)
            ->where(function (Query\Builder $query) use ($roleplayables) {
                /** @var Model&Roleplayable $roleplayable */
                foreach($roleplayables as $roleplayable) {
                    $query->orWhere(function(Query\Builder $query) use ($roleplayable) {
                        $query->where('organizer_type', $roleplayable->getMorphClass())
                              ->where('organizer_id', $roleplayable->getKey());
                    });
                }
            });

        return $query->get()->isNotEmpty();
    }

    /**
     * @param Roleplayable $model
     * @return bool
     */
    public function addOrganizer(Roleplayable $model): bool
    {
        if($this->hasOrganizer($model)) {
            return false;
        }

        DB::table('roleplay_organizers')->insert([
            'roleplay_id' => $this->id,
            'organizer_type' => self::getActualClassNameForMorph(get_class($model)),
            'organizer_id' => $model->getKey(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return true;
    }

    /**
     * @param Roleplayable $model
     * @return bool
     */
    public function removeOrganizer(Roleplayable $model): bool
    {
        if(! $this->hasOrganizer($model)) {
            return false;
        }

        DB::table('roleplay_organizers')
            ->where('organizer_type', self::getActualClassNameForMorph(get_class($model)))
            ->where('organizer_id', $model->getKey())
            ->where('roleplay_id', $this->id)
            ->delete();

        return true;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Roleplay $roleplay) {
            $roleplay->starting_date = now();
            $roleplay->ending_date = null;
        });
    }
}
