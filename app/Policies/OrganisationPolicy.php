<?php

namespace App\Policies;

use App\CustomUser;
use App\Models\Organisation;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any organisations.
     *
     * @param  \App\CustomUser  $user
     * @return mixed
     */
    public function viewAny(CustomUser $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the organisation.
     *
     * @param  \App\CustomUser  $user
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
     * @param  \App\CustomUser  $user
     * @return mixed
     */
    public function create(CustomUser $user)
    {
        return $user->hasMinPermission('member');
    }

    /**
     * Determine whether the user can update the organisation.
     *
     * @param  \App\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function update(CustomUser $user, Organisation $organisation)
    {

        if($user->hasMinPermission('admin')) return true;
        $pays = array_column($user->pays()->get()->toArray(), 'ch_pay_id');
        return (bool)$organisation->members()->whereIn('pays_id', $pays)
            ->where('permissions', '>=', Organisation::$permissions['administrator'])
            ->get()->count();
    }

    /**
     * Determine whether the user can delete the organisation.
     *
     * @param  \App\CustomUser  $user
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
     * @param  \App\CustomUser  $user
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
     * @param  \App\CustomUser  $user
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
     * @param  \App\CustomUser  $user
     * @param  \App\Models\Organisation  $organisation
     * @return mixed
     */
    public function administrate(CustomUser $user, Organisation $organisation) {

        return $organisation->maxPermission($user) >= Organisation::$permissions['administrator'];

    }

}
