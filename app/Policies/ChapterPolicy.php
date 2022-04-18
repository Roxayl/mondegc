<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\CustomUser;
use App\Models\Roleplay;
use App\Policies\Contracts\VersionablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class ChapterPolicy implements VersionablePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut afficher les chapitres et utiliser le système de roleplay.
     *
     * @param CustomUser|null $user
     * @return bool
     */
    public function display(?CustomUser $user): bool
    {
        return Gate::allows('display', Roleplay::class);
    }

    /**
     * Vérifie si un utilisateur peut gérer les chapitres d'un roleplay.
     *
     * @param CustomUser $user
     * @param Chapter $chapter
     * @return bool
     */
    public function manage(CustomUser $user, Chapter $chapter): bool
    {
        return $this->display($user)
            && Gate::allows('manage', $chapter->roleplay);
    }

    /**
     * Détermine si l'utilisateur peut créer une entrée de chapitre.
     *
     * @param  CustomUser  $user
     * @param  Chapter  $chapter
     * @return bool
     */
    public function createEntries(CustomUser $user, Chapter $chapter): bool
    {
        return $chapter->isCurrent() && $user->roleplayables()->count() > 0;
    }

    /**
     * Détermine si l'utilisateur peut assigner des ressources à une entité ressourçeable dans le cadre d'un roleplay.
     *
     * @param CustomUser $user
     * @param Chapter $chapter
     * @return bool
     */
    public function createResourceables(CustomUser $user, Chapter $chapter): bool
    {
        return $chapter->roleplay->isValid() && Gate::allows('manage', $chapter->roleplay);
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Chapter|Model $model): bool
    {
        return $this->display($user) && $this->manage($user, $model);
    }
}
