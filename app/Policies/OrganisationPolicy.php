<?php

namespace Roxayl\MondeGC\Policies;

use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Organisation;
use Roxayl\MondeGC\Policies\Traits\ManagesInfrastructures;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
{
    use HandlesAuthorization, ManagesInfrastructures;
    
    /**
     * Determine whether the user can view any organisations.
     *
     * @param CustomUser $user
     * @return bool
     */
    public function viewAny(CustomUser $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function view(CustomUser $user, Organisation $organisation): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create organisations.
     *
     * @param CustomUser $user
     * @return bool
     */
    public function create(CustomUser $user): bool
    {
        return $user->hasMinPermission('member');
    }

    /**
     * Determine whether the user can update the organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function update(CustomUser $user, Organisation $organisation): bool
    {
        if($user->hasMinPermission('admin')) return true;

        return $this->administrate($user, $organisation);
    }

    /**
     * Determine whether the user can delete the organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function delete(CustomUser $user, Organisation $organisation): bool
    {
        if($user->hasMinPermission('admin')) return true;

        return $organisation->maxPermission($user) >=
            Organisation::$permissions['owner'];
    }

    /**
     * Determine whether the user can restore the organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function restore(CustomUser $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function forceDelete(CustomUser $user, Organisation $organisation): bool
    {
        return false;
    }

    /**
     * Détermine si l'utilisateur peut administrer l'organisation.
     *
     * @param CustomUser $user
     * @param Organisation $organisation
     * @return bool
     */
    public function administrate(CustomUser $user, Organisation $organisation): bool
    {
        return $organisation->maxPermission($user) >=
            Organisation::$permissions['administrator'];
    }
}
