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
        return Gate::allows('display', Roleplay::class)
            && Gate::allows('manage', $chapter->roleplay);
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Chapter|Model $model): bool
    {
        return $this->manage($user, $model);
    }
}
