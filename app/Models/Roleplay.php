<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @package App\Models
 */
class Roleplay extends Model
{
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
            'user_id', 'roleplay_id')
                    ->withPivot('id')
                    ->withTimestamps();
    }
}
