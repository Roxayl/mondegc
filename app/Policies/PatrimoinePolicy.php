<?php

namespace App\Policies;

use App\Models\CustomUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatrimoinePolicy
{
    use HandlesAuthorization;

    public function viewAny(CustomUser $user)
    {
        return true;
    }

    public function assignToCategories(CustomUser $user)
    {
        return $user->hasMinPermission('ocgc');
    }

    public function manageCategories(CustomUser $user)
    {
        return $user->hasMinPermission('ocgc');
    }
}
