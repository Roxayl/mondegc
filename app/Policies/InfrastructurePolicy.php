<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Roxayl\MondeGC\Models\Contracts\Infrastructurable;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Infrastructure;
use Roxayl\MondeGC\Policies\Contracts\VersionablePolicy;

class InfrastructurePolicy implements VersionablePolicy
{
    use HandlesAuthorization;

    /**
     * @param  CustomUser  $user
     * @return bool
     */
    public function viewAny(CustomUser $user): bool
    {
        return $user->hasMinPermission('juge');
    }

    /**
     * @param  CustomUser  $user
     * @return bool
     */
    public function judgeInfrastructure(CustomUser $user): bool
    {
        return $this->viewAny($user);
    }

    /**
     * @inheritDoc
     */
    public function viewDiff(CustomUser|Authenticatable|null $user, Infrastructurable|Model $model): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Infrastructure|Model $model): bool
    {
        return $user->hasMinPermission('ocgc');
    }
}
