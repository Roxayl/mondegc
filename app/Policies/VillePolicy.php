<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Ville;
use App\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class VillePolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user)
    {
        return true;
    }

    public function delete(CustomUser $user, Ville $ville)
    {
        if($user->hasMinPermission('admin')) return true;

        return $ville->getUsers()->contains($user);
    }
}
