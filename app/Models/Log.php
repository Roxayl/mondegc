<?php

namespace Roxayl\MondeGC\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Log
 *
 * @property int $id
 * @property string $target
 * @property int|null $target_id
 * @property string $type_action
 * @property int|null $user_id
 * @property string|null $data_changes
 * @property Carbon $created
 * @property CustomUser $user
 * @method static Builder|Log newModelQuery()
 * @method static Builder|Log newQuery()
 * @method static Builder|Log query()
 * @method static Builder|Log whereCreated($value)
 * @method static Builder|Log whereDataChanges($value)
 * @method static Builder|Log whereId($value)
 * @method static Builder|Log whereTarget($value)
 * @method static Builder|Log whereTargetId($value)
 * @method static Builder|Log whereTypeAction($value)
 * @method static Builder|Log whereUserId($value)
 * @mixin Model
 */
class Log extends Model
{
    protected $table = 'log';
    public $timestamps = false;

    protected $casts = [
        'target_id' => 'int',
        'user_id' => 'int'
    ];

    protected $dates = [
        'created'
    ];

    protected $fillable = [
        'target',
        'target_id',
        'type_action',
        'user_id',
        'data_changes',
        'created'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class);
    }
}
