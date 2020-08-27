<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Infrastructure;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfrastructurePolicy
{
    use HandlesAuthorization;

    public function viewAny(CustomUser $user)
    {
        return $user->hasMinPermission('juge');
    }

    public function judgeInfrastructure(CustomUser $user)
    {
        return $this->viewAny($user);
    }
}
