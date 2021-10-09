<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Contracts\Roleplayable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class Roleplay
 * 
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property Carbon $starting_date
 * @property Carbon|null $ending_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CustomUser $user
 * @property Collection|Chapter[] $chapters
 * @property Collection|CustomUser[] $users
 *
 * @method static Builder current()
 *
 * @package App\Models
 */
class Roleplay extends Model
{
    use HasFactory;

    protected $table = 'roleplay';

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $dates = [
        'starting_date',
        'ending_date'
    ];

    protected $fillable = [
        'name',
        'user_id',
        'starting_date',
        'ending_date'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'user_id', 'ch_use_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Renvoie la liste des roleplays actuels, en cours.
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query
            ->where('starting_date',  '<=', now()->addDay())
            ->where(function (Builder $query) {
                $query->whereNull('ending_date')
                    ->orWhere('ending_date', '>=', now()->subDays(2));
            });
    }

    /**
     * Donne une collection des {@link Roleplayable organisateurs du roleplay}.
     * @return \Illuminate\Support\Collection<int, Roleplayable>
     */
    public function organizers(): \Illuminate\Support\Collection
    {
        $query = DB::table('roleplay_organizers')
            ->where('roleplay_id', $this->id)
            ->get();

        $roleplayOrganizers = collect();

        foreach($query as $row) {

            // Créé une instance d'un organisateur de roleplay.
            // e.g. : \App\Models\Pays::find(28);
            // soit {nom de la classe}::find( {paramètre} )
            // soit {organizer_type}::find( {organizer_id} )

            /** @var \Illuminate\Database\Eloquent\Collection $organizer */
            $organizer = call_user_func(
                [$row->organizer_type, 'find'],
                [$row->organizer_id]
            );

            $organizer = $organizer->first();

            /** @var Roleplayable $organizer */
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
        $result = DB::table('roleplay_organizers')
            ->where('organizer_type', self::getActualClassNameForMorph(get_class($model)))
            ->where('organizer_id', $model->getKey())
            ->where('roleplay_id', $this->id)
            ->get();

        return $result->isNotEmpty();
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
            'roleplay_id'    => $this->id,
            'organizer_type' => self::getActualClassNameForMorph(get_class($model)),
            'organizer_id'   => $model->getKey(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
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
}
