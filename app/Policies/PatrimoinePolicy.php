<?php

namespace Roxayl\MondeGC\Policies;

use Roxayl\MondeGC\Models\CustomUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatrimoinePolicy
{
    use HandlesAuthorization;

    public function viewAny(CustomUser $user): bool
    {
        return true;
    }

    public function assignToCategories(CustomUser $user): bool
    {
        return $user->hasMinPermission('ocgc');
    }

    public function manageCategories(CustomUser $user): bool
    {
        return $user->hasMinPermission('ocgc');
    }
}
