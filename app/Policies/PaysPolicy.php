<?php

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;

class PaysPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user): bool
    {
        return true;
    }

    public function update(CustomUser $user, Pays $pays): bool
    {
        if($user->hasMinPermission('ocgc')) return true;

        return $pays->users->contains($user);
    }

    public function revert(CustomUser $user, Pays $pays): bool
    {
        return $this->update($user, $pays);
    }
}
