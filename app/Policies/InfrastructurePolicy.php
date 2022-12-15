<?php

namespace Roxayl\MondeGC\Policies;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Infrastructure;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfrastructurePolicy
{
    use HandlesAuthorization;

    /**
     * @param CustomUser $user
     * @return bool
     */
    public function viewAny(CustomUser $user): bool
    {
        return $user->hasMinPermission('juge');
    }

    /**
     * @param CustomUser $user
     * @return bool
     */
    public function judgeInfrastructure(CustomUser $user): bool
    {
        return $this->viewAny($user);
    }
}
