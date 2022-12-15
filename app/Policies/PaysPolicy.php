<?php

namespace Roxayl\MondeGC\Policies;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaysPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user): bool
    {
        return true;
    }
}
