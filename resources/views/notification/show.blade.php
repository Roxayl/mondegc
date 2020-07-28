@php

use App\Models\Infrastructure;
use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\Pays;

@endphp

@if($unread->count())
<div class="pull-right" style="margin-right: 10px; margin-top: -3px;">
    <form method="POST" action="{{ route('notification.mark-as-read') }}"
          class="notification-markasread">
        <input type="hidden" name="mark_unread" value="1">
        <button class="btn btn-primary" type="submit">Tout marquer comme lu</button>
    </form>
</div>
@endif

<h4 class="btn-margin-left">Notifications </h4>

@forelse($notifications as $notification)

    @php

    $continue = false;

    switch($notification->type) {

        case 'App\Notifications\PaysRegistered':
            $element = Pays::find($notification->data['pays_id']);
            if(empty($element)) { $continue = true; }
            else {
                $header = "NOUVEAU PAYS";
                $text = "Un nouveau pays, <strong>"
                      . htmlspecialchars($element->ch_pay_nom) . "</strong>"
                      . ", a rejoint le concert des nations gécéennes. "
                      . "Souhaitez-lui la bienvenue au sein du Monde GC.";
                $link = url('page-pays.php?ch_pay_id='
                      . __s($element->ch_pay_id) . "#commentaires");
                $style = "background-color: #ff4e00;";
            }
            break;

        case 'App\Notifications\InfrastructureJudged':
            $element = Infrastructure::with('ville')
                        ->find($notification->data['infrastructure_id']);
            if(empty($element)) { $continue = true; }

            elseif($notification->data['accepted']) {
                $header = "BIEN OUEJ !";
                $text = "Votre infrastructure <strong>"
                      . htmlspecialchars($element->nom_infra) . "</strong> à "
                      . htmlspecialchars($element->ville->ch_vil_nom)
                      . " a été acceptée par les juges tempérants.";
                $style = "background: linear-gradient(120deg, #ffe300 0%,#ff5c00 72%);";
                $link = url("back/ville_modifier.php?ville-ID="
                      . urlencode($element->ch_inf_villeid)
                      . "#mes-infrastructures");
            }
            else {
                $header = "TRY AGAIN...";
                $text = "Votre infrastructure <strong>"
                      . htmlspecialchars($element->nom_infra) . "</strong> à "
                      . htmlspecialchars($element->ville->ch_vil_nom)
                      . " a été refusée par les juges tempérants pour la raison suivante :"
                      . "<br><i>"
                      . htmlspecialchars($element->ch_inf_commentaire_juge) . "</i>";
                $style = "background: linear-gradient(120deg, #ffe300 0%,#ff5c00 72%);";
                $link = url("back/ville_modifier.php?ville-ID="
                      . urlencode($element->ch_inf_villeid)
                      . "#mes-infrastructures");
            }
            break;

        case 'App\Notifications\OrganisationMemberJoined':
            $element = OrganisationMember::with(['pays', 'organisation'])
                ->find($notification->data['organisation_member_id']);
            if(empty($element)) { $continue = true; }

            else {
                $header = "DEMANDE D'ADMISSION REÇUE";
                $text = "<strong>" . htmlspecialchars($element->pays->ch_pay_nom) . "</strong>"
                      . " a demandé à intégrer l'organisation <strong>"
                      . htmlspecialchars($element->organisation->name) . "</strong>. "
                      . "Vous pouvez accepter ou refuser sa candidature.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
            }
            break;

        case 'App\Notifications\OrganisationMemberInvited':
            $element = OrganisationMember::with(['pays', 'organisation'])
                ->find($notification->data['organisation_member_id']);
            if(empty($element)) { $continue = true; }

            else {
                $header = "INVITATION REÇUE";
                $text = "<strong>" . htmlspecialchars($element->pays->ch_pay_nom) . "</strong>"
                      . " a été invité à rejoindre l'organisation <strong>"
                      . htmlspecialchars($element->organisation->name) . "</strong>. "
                      . "Vous pouvez accepter ou refuser cette invitation.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
            }
            break;

        case 'App\Notifications\OrganisationMemberPermissionChanged':
            $element = OrganisationMember::with(['pays', 'organisation'])
                ->find($notification->data['organisation_member_id']);
            if(empty($element)) { $continue = true; }

            elseif($notification->data['action'] === 'promotedAdministrator') {
                $header = "PROMOTION !";
                $text = "<strong>" . htmlspecialchars($element->pays->ch_pay_nom) . "</strong>"
                      . " a été promu Administrateur de l'organisation <strong>"
                      . htmlspecialchars($element->organisation->name) . "</strong>.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
            }
            elseif($notification->data['action'] === 'accepted') {
                $header = "UN NOUVEAU !";
                $text = "<strong>" . htmlspecialchars($element->pays->ch_pay_nom) . "</strong>"
                      . " a rejoint l'organisation <strong>"
                      . htmlspecialchars($element->organisation->name) . "</strong>.";
                $link = route('organisation.show', ['organisation' => $element->organisation_id]);
                $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
            }
            else {
                $continue = true;
            }
            break;

        case 'App\Notifications\OrganisationMemberQuit':
            $pays = Pays::find($notification->data['pays_id']);
            $organisation = Organisation::find($notification->data['organisation_id']);
            $header = "BYE.";
            $text = "<strong>" . htmlspecialchars($pays->ch_pay_nom) . "</strong>"
                  . " a quitté l'organisation <strong>"
                  . htmlspecialchars($organisation->name) . "</strong>.";
            $link = route('organisation.show', ['organisation' => $organisation->id]);
            $style = "background: linear-gradient(120deg, #EA0A0A 0%,#BE0FDC 72%);";
            break;

        default:
            $continue = true;

    }

    if($continue) continue;

    @endphp

    <li class="@empty($notification->read_at) notification-unread @endempty">
        <a href="{{$link}}">
            <div class="row-fluid">
                <div class="pull-left">
                    <div class="notification-styler" style="{{ $style }}"></div>
                </div>
                <div style="margin-left: 5px;">
                    <div class="pull-right">
                        <div class="notification-unread-pastille"></div>
                    </div>
                    <h4>{{ $header }}</h4>
                    <p>
                        <small class="inline" style="margin: 0; padding: 0; color: #0a0a0a;">
                            {{ $notification->created_at->format('d-m-Y') }}</small>
                        {!! $text !!}
                    </p>
                </div>
            </div>
        </a>
    </li>

@empty

    <div class="well">
        <p>Vous n'avez pas de notifications récentes. :)</p>
    </div>

@endforelse