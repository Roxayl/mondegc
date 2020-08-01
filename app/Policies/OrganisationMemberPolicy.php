<?php

namespace App\Policies;

use App\Models\CustomUser;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationMemberPolicy
{
    use HandlesAuthorization;

    public function update(CustomUser $user, OrganisationMember $orgMember) {

        // On ne permet pas de modifier les permissions d'un propriétaire.
        // Les droits d'un propriétaire font l'objet d'une autre méthode de transfert
        // des droits de propriétaire.
        if($orgMember->permissions === Organisation::$permissions['owner']) return false;

        // On vérifie que l'utilisateur est administrateur de l'organisation concernée.
        return $orgMember->organisation->maxPermission($user) >=
                 Organisation::$permissions['administrator'];

    }

    public function quit(CustomUser $user, OrganisationMember $orgMember) {

        // Quitter une organisation.
        // Autorisé si l'utilisateur est administrateur de l'organisation ou
        // si l'utilisateur possède le pays.
        return
            $orgMember->permissions < Organisation::$permissions['owner'] &&
            ( $orgMember->organisation->maxPermission($user) >=
                Organisation::$permissions['administrator'] ||
              in_array($orgMember->pays->ch_pay_id,
                array_column($user->pays()->get()->toArray(), 'ch_pay_id'))
            );

    }

}
