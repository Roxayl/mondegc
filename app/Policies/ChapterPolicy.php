<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\CustomUser;
use App\Policies\Contracts\VersionablePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class ChapterPolicy implements VersionablePolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si un utilisateur peut gérer les chapitres d'un roleplay.
     *
     * @param CustomUser $user
     * @param Chapter $chapter
     * @return bool
     */
    public function manage(CustomUser $user, Chapter $chapter): bool
    {
        return Gate::allows('manage', $chapter->roleplay);
    }

    /**
     * @inheritDoc
     */
    public function revert(CustomUser|Authenticatable $user, Chapter|Model $model): bool
    {
        return $this->manage($user, $model);
    }
}