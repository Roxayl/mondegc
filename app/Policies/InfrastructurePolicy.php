<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Infrastructure;
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
