<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationMemberPolicy
{
    use HandlesAuthorization;

    public function update(CustomUser $user, OrganisationMember $orgMember)
    {
        // On ne permet pas de modifier les permissions d'un propriétaire.
        // Les droits d'un propriétaire font l'objet d'une autre méthode de transfert
        // des droits de propriétaire.
        if($orgMember->permissions === Organisation::$permissions['owner']) {
            return false;
        }

        // L'utilisateur peut accepter ou refuser une demande d'adhésion.
        if( ($orgMember->permissions === Organisation::$permissions['pending'] ||
             $orgMember->permissions === Organisation::$permissions['invited'])
            &&
             $user->ownsPays($orgMember->pays)
          ) {
            return true;
        }

        // On vérifie que l'utilisateur est administrateur de l'organisation concernée.
        return $orgMember->organisation->maxPermission($user) >=
                 Organisation::$permissions['administrator'];
    }

    public function quit(CustomUser $user, OrganisationMember $orgMember)
    {
        // Un propriétaire ne peut pas quitter une organisation.
        // Les droits d'un propriétaire font l'objet d'une autre méthode de transfert
        // des droits de propriétaire.
        if($orgMember->permissions < Organisation::$permissions['owner'])
            return false;

        // Autorisé si l'utilisateur est administrateur de l'organisation ou
        // si l'utilisateur possède le pays.
        return $orgMember->organisation->maxPermission($user) >=
                Organisation::$permissions['administrator']
            ||
              $user->ownsPays($orgMember->pays);
    }
}
