<?php

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Ville;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;

class VillePolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user): bool
    {
        return true;
    }

    public function delete(CustomUser $user, Ville $ville): bool
    {
        if($user->hasMinPermission('admin')) return true;

        return $ville->getUsers()->contains($user);
    }
}
