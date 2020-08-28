<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaysPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user)
    {
        return true;
    }
}
