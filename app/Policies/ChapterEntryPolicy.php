<?php

namespace App\Policies;

use App\Models\ChapterEntry;
use App\Models\CustomUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ChapterEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur permet de créer une actualité dans un chapitre.
     *
     * @param  CustomUser  $user
     * @return bool
     */
    public function create(CustomUser $user): bool
    {
        return $user->roleplayables()->count() > 0;
    }

    /**
     * Détermine si l'utilisateur peut modifier ou supprimer une actualité dans un chapitre.
     *
     * @param  CustomUser  $user
     * @param  ChapterEntry  $entry
     * @return bool
     */
    public function manage(CustomUser $user, ChapterEntry $entry): bool
    {
        return Gate::allows('manage', $entry->chapter);
    }
}
