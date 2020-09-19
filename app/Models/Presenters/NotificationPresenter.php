<?php

namespace App\Models\Presenters;

use App\Models\Infrastructure;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\Pays;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPresenter
{
    /**
     * Génère les données à afficher dans le Centre de notifications pour une notification
     * donnée.
     * @todo Refactor: Implémenter l'affichage au niveau de chaque objet de notification.
     * @param DatabaseNotification $notification
     * @return bool|object Objet contenant les données à afficher ; false s'il n'existe
     *                     aucun affichage existant.
     */
    public static function getDisplayData(DatabaseNotification $notification)
    {
        $continue = false;

        switch($notification->type) {

            /*
            |---------------------------------------------------------------------
            | Un pays s'est enregistré dans le Monde GC.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\PaysRegistered':
                $element = Pays::find($notification->data['pays_id']);

                if(empty($element)) {
                    $continue = true; break;
                }
                $header = "NOUVEAU PAYS";
                $text = "Un nouveau pays, <strong>"
                      . e($element->ch_pay_nom) . "</strong>"
                      . ", a rejoint le concert des nations gécéennes. "
                      . "Souhaitez-lui la bienvenue au sein du Monde GC.";
                $link = url('page-pays.php?ch_pay_id='
                      . __s($element->ch_pay_id) . "#commentaires");
                $style = "background-color: #ff4e00;";
                break;

            /*
            |---------------------------------------------------------------------
            | Une infrastructure a été jugée.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\InfrastructureJudged':
                $element = Infrastructure::find($notification->data['infrastructure_id']);
                if(empty($element)) {
                    $continue = true; break;
                }

                $style = "background: linear-gradient(120deg, #ffe300 0%,#ff5c00 72%);";
                $link = $element->infrastructurable->accessorUrl();

                if($notification->data['accepted']) {
                    $header = "BIEN OUEJ !";
                    $text = "Votre infrastructure <strong>"
                          . e($element->nom_infra) . "</strong> à "
                          . e($element->infrastructurable->getName())
                          . " a été acceptée par les juges tempérants.";
                }
                else {
                    $header = "TRY AGAIN...";
                    $text = "Votre infrastructure <strong>"
                          . e($element->nom_infra) . "</strong> à "
                          . e($element->infrastructurable->getName())
                          . " a été refusée par les juges tempérants pour la raison suivante :"
                          . "<br><i>"
                          . e($element->ch_inf_commentaire_juge) . "</i>";
                }
                break;

            /*
            |---------------------------------------------------------------------
            | Un pays a rejoint une organisation.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\OrganisationMemberJoined':
                $element = OrganisationMember::with(['pays', 'organisation'])
                    ->find($notification->data['organisation_member_id']);

                if(empty($element)) {
                    $continue = true; break;
                }
                $header = "DEMANDE D'ADMISSION REÇUE";
                $text = "<strong>" . e($element->pays->ch_pay_nom)
                      . "</strong> a demandé à intégrer l'organisation <strong>"
                      . e($element->organisation->name) . "</strong>. "
                      . "Vous pouvez accepter ou refuser sa candidature.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                break;

            /*
            |---------------------------------------------------------------------
            | Un pays a été invité à rejoindre une organisation
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\OrganisationMemberInvited':
                $element = OrganisationMember::with(['pays', 'organisation'])
                    ->find($notification->data['organisation_member_id']);

                if(empty($element)) {
                    $continue = true; break;
                }
                $header = "INVITATION REÇUE";
                $text = "<strong>" . e($element->pays->ch_pay_nom)
                      . "</strong> a été invité à rejoindre l'organisation <strong>"
                      . e($element->organisation->name) . "</strong>. "
                      . "Vous pouvez accepter ou refuser cette invitation.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                break;

            /*
            |---------------------------------------------------------------------
            | Les permissions d'un pays dans une organisation ont été modifiées.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\OrganisationMemberPermissionChanged':
                $element = OrganisationMember::with(['pays', 'organisation'])
                    ->find($notification->data['organisation_member_id']);

                if(empty($element)) {
                    $continue = true; break;
                }

                if($notification->data['action'] === 'promotedAdministrator') {
                    $header = "PROMOTION !";
                    $text = "<strong>" . e($element->pays->ch_pay_nom)
                          . "</strong> a été promu Administrateur de l'organisation <strong>"
                          . e($element->organisation->name) . "</strong>.";
                    $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                    $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                }
                elseif($notification->data['action'] === 'accepted') {
                    $header = "UN NOUVEAU !";
                    $text = "<strong>" . e($element->pays->ch_pay_nom)
                          . "</strong> a rejoint l'organisation <strong>"
                          . e($element->organisation->name) . "</strong>.";
                    $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                    $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                }
                else {
                    $continue = true;
                }
                break;

            /*
            |---------------------------------------------------------------------
            | Un utilisateur a quitté une organisation.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\OrganisationMemberQuit':
                $pays = Pays::find($notification->data['pays_id']);
                $organisation = Organisation::find($notification->data['organisation_id']);
                $header = "BYE.";
                $text = "<strong>" . e($pays->ch_pay_nom) . "</strong>"
                      . " a quitté l'organisation <strong>"
                      . e($organisation->name) . "</strong>.";
                $link = route('organisation.show', ['organisation' => $organisation->id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                break;

            /*
            |---------------------------------------------------------------------
            | Une organisation a changé de type.
            |---------------------------------------------------------------------
            */
            case 'App\Notifications\OrganisationTypeMigrated':
                $organisation = Organisation::find($notification->data['organisation_id']);
                $header = "VOTRE ORGANISATION CHANGE";
                $text = "<strong>" . e($organisation->name) . "</strong> est devenue "
                      . "une " . e(__("organisation.types.{$notification->data['type']}")) . ".";
                $link = route('organisation.show', ['organisation' => $organisation->id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
                break;

            default:
                $continue = true;

        }

        if($continue) return false;

        return (object)compact(['header', 'text', 'link', 'style']);
    }
}
