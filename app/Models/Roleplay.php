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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(CustomUser::class, 'roleplay_users',
            'roleplay_id', 'user_id')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    /**
     * Renvoie la liste des roleplays actuels, en cours.
     * @param $query
     * @return Builder
     */
    public function scopeCurrent($query): Builder
    {
        return $query
            ->where('starting_date',  '<=', now()->addDay())
            ->where(function (Builder $query) {
                $query->whereNull('ending_date')
                    ->orWhere('ending_date', '>=', now()->subDays(2));
            });
    }
}
