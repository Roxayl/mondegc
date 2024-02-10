<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Roxayl\MondeGC\Models\CustomUser;

class PatrimoinePolicy
{
    use HandlesAuthorization;

    public function viewAny(?CustomUser $user): bool
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
