<?php

namespace Roxayl\MondeGC\Policies;

use Roxayl\MondeGC\Models\ChapterResourceable;
use Roxayl\MondeGC\Models\CustomUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ChapterResourceablePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut assigner des ressources à une entité ressourçeable dans le cadre d'un roleplay.
     *
     * @param CustomUser $user
     * @param ChapterResourceable $resourceable
     * @return bool
     */
    public function create(CustomUser $user, ChapterResourceable $resourceable): bool
    {
        return Gate::allows('createResourceables', $resourceable->chapter);
    }

    /**
     * Détermine si l'utilisateur peut modifier ou supprimer l'assignation de ressources à une entité ressourçeable
     * dans le cadre d'un roleplay.
     *
     * @param CustomUser $user
     * @param ChapterResourceable $resourceable
     * @return bool
     */
    public function manage(CustomUser $user, ChapterResourceable $resourceable): bool
    {
        return $user->hasMinPermission('ocgc')
            || Gate::allows('create', $resourceable);
    }
}
