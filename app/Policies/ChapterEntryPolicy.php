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
     * Détermine si l'utilisateur peut créer une entrée de chapitre.
     *
     * @param  CustomUser  $user
     * @param  ChapterEntry  $entry
     * @return bool
     */
    public function create(CustomUser $user, ChapterEntry $entry): bool
    {
        return Gate::allows('createEntries', $entry->chapter);
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
        return $user->hasMinPermission('ocgc')
            || $user->hasRoleplayable($entry->roleplayable)
            || Gate::allows('manage', $entry->chapter);
    }
}
