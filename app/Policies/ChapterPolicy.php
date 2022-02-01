<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\CustomUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si un utilisateur peut gérer les chapitres d'un roleplay.
     * @param CustomUser $user
     * @param Chapter $chapter
     * @return bool
     */
    public function manage(CustomUser $user, Chapter $chapter): bool
    {
        return $chapter->roleplay->hasOrganizerAmong($user->roleplayables());
    }
}
