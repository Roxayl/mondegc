<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Organisation;
use App\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;
    
    /**
     * Determine whether the user can view any organisations.
     *
     * @param  \App\Models\CustomUser  $user
     * @return mixed
     */
    public function viewAny(CustomUser $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function view(CustomUser $user, Organisation $organisation)
    {
        return true;
    }

    /**
     * Determine whether the user can create organisations.
     *
     * @param  \App\Models\CustomUser  $user
     * @return mixed
     */
    public function create(CustomUser $user)
    {
        return $user->hasMinPermission('member');
    }

    /**
     * Determine whether the user can update the organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function update(CustomUser $user, Organisation $organisation)
    {
        if($user->hasMinPermission('admin')) return true;
        return $this->administrate($user, $organisation);
    }

    /**
     * Determine whether the user can delete the organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function delete(CustomUser $user, Organisation $organisation)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function restore(CustomUser $user, Organisation $organisation)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function forceDelete(CustomUser $user, Organisation $organisation)
    {
        return false;
    }

    /**
     * Détermine si l'utilisateur peut administrer l'organisation.
     *
     * @param  \App\Models\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function administrate(CustomUser $user, Organisation $organisation)
    {
        return $organisation->maxPermission($user) >=
            Organisation::$permissions['administrator'];
    }
}
