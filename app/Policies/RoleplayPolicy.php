<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Roleplay;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoleplayPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si un utilisateur peut créer des roleplays.
     * @param CustomUser $user
     * @return bool
     */
    public function create(CustomUser $user)
    {
        return $user->hasMinPermission('member') && $user->pays->count() > 0;
    }

    /**
     * Détermine si un utilisateur peut gérer un roleplay.
     *
     * @param CustomUser $user
     * @param Roleplay $roleplay
     * @return bool
     */
    public function manage(CustomUser $user, Roleplay $roleplay)
    {
        return $roleplay->hasOrganizerAmong($user->roleplayables());
    }
}
