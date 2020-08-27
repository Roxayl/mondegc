<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class VillePolicy
{
    use HandlesAuthorization, ManagesInfrastructures;

    public function viewAny(CustomUser $user)
    {
        return true;
    }
}
