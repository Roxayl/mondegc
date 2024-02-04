<?php

namespace Roxayl\MondeGC\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Roxayl\MondeGC\Models\CustomUser;
use Roxayl\MondeGC\Models\Roleplay;
use YlsIdeas\FeatureFlags\Manager as FeatureManager;

class RoleplayPolicy
{
    use HandlesAuthorization;

    public function __construct(private readonly FeatureManager $featureManager)
    {
    }

    /**
     * Détermine si l'utilisateur peut utiliser le système de roleplay.
     *
     * @param CustomUser|null $user
     * @return bool
     */
    public function display(?CustomUser $user): bool
    {
        if(! $this->featureManager->accessible('roleplay')) {
            return false;
        }

        if($this->featureManager->accessible('roleplay-only-restricted')) {
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
