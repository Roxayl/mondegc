<?php

namespace Roxayl\MondeGC\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UsersPays
 *
 * @property int $id
 * @property int $ID_pays
 * @property int $ID_user
 * @property int $permissions
 * @property-read Collection|OrganisationMember[] $organisationMembers
 * @property-read int|null $organisation_members_count
 * @property-read Pays $pays
 * @property-read CustomUser $users
 * @method static Builder|UsersPays newModelQuery()
 * @method static Builder|UsersPays newQuery()
 * @method static Builder|UsersPays query()
 * @method static Builder|UsersPays whereIDPays($value)
 * @method static Builder|UsersPays whereIDUser($value)
 * @method static Builder|UsersPays whereId($value)
 * @method static Builder|UsersPays wherePermissions($value)
 * @mixin Pivot
 * @mixin \Eloquent
 */
class UsersPays extends Pivot
{
    protected $table = 'users_pays';
    public $incrementing = true;
    public $timestamps = false;

    protected $casts = [
        'ID_pays' => 'int',
        'ID_user' => 'int',
        'permissions' => 'int'
    ];

    protected $fillable = [
        'ID_pays',
        'ID_user',
        'permissions'
    ];

    /**
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'ch_use_id', 'ID_user');
    }

    /**
     * @return BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ch_pay_id', 'ID_pays');
    }

    /**
     * @return HasManyThrough
     */
    public function organisationMembers(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrganisationMember::class,
            Pays::class,
            'ch_pay_id',
            'pays_id',
            'ID_pays',
            'ch_pay_id'
        );
    }
}
