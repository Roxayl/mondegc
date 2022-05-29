<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Roleplay;
use Illuminate\Auth\Access\HandlesAuthorization;
use YlsIdeas\FeatureFlags\Facades\Features;

class RoleplayPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut utiliser le système de roleplay.
     *
     * @param CustomUser|null $user
     * @return bool
     */
    public function display(?CustomUser $user): bool
    {
        if(! Features::accessible('roleplay')) {
            return false;
        }

        if(Features::accessible('roleplay-only-restricted')) {
            if($user === null || ! $user->hasMinPermission('ocgc')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifie si un utilisateur peut créer des roleplays.
     * @param CustomUser $user
     * @return bool
     */
    public function create(CustomUser $user): bool
    {
        return $this->display($user)
            && $user->hasMinPermission('member')
            && $user->roleplayables()->count() > 0;
    }

    /**
     * Détermine s'il est possible de créer des chapitres.
     *
     * @param CustomUser $user
     * @param Roleplay $roleplay
     * @return bool
     */
    public function createChapters(CustomUser $user, Roleplay $roleplay): bool
    {
        return $this->display($user)
            && $roleplay->isValid()
            && $this->manage($user, $roleplay);
    }

    /**
     * Détermine si un utilisateur peut gérer un roleplay.
     *
     * @param CustomUser $user
     * @param Roleplay $roleplay
     * @return bool
     */
    public function manage(CustomUser $user, Roleplay $roleplay): bool
    {
        return $user->hasMinPermission('ocgc')
            || ($this->display($user) && $roleplay->hasOrganizerAmong($user->roleplayables()));
    }
}
