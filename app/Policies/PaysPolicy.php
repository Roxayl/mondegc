<?php

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;

class PaysPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user): bool
    {
        return true;
    }
}
