<?php

namespace App\Models;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrganisationMember[] $organisation_members
 * @property-read int|null $organisation_members_count
 * @property-read \App\Models\Pays $pays
 * @property-read \App\Models\CustomUser $users
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays whereIDPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays whereIDUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsersPays wherePermissions($value)
 * @mixin Pivot
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

	public function users(): BelongsTo
    {
        return $this->belongsTo(CustomUser::class, 'ch_use_id', 'ID_user');
    }

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'ch_pay_id', 'ID_pays');
    }

    public function organisation_members(): HasManyThrough
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
