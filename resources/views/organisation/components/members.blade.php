
<div class="cta-title pull-right-cta">
@can('administrate', $organisation)
    <a href="<?= route('organisation-member.invite',
                 ['organisation_id' => $organisation->id]) ?>"
       class="btn btn-primary btn-cta pull-right"
       data-toggle="modal" data-target="#modal-container-small">
    <i class="icon-white icon-envelope"></i> Inviter...</a>
@endcan
@if(auth()->check())
    <a href="<?= route('organisation-member.join',
                 ['organisation_id' => $organisation->id]) ?>"
       class="btn btn-primary btn-cta pull-right" style="margin-right: 8px;"
       data-toggle="modal" data-target="#modal-container-small">
    <i class="icon-white icon-plus-sign"></i> Rejoindre...</a>
@endif
</div>

<div id="membres" class="titre-vert">
    <h1>Membres</h1>
</div>
@foreach($organisation->members as $member)

    @php
    $dropdown = [];
    @endphp
    @can('update', $member)
        @php
        $dropdown[] = [
            'type'  => 'link',
            'url'   => route('organisation-member.edit', ['id' => $member->id]),
            'text'  => 'Modifier',
            'popup' => true,
        ];
        @endphp
    @endcan
    @can('quit', $member)
        @php
        $dropdown[] = [
            'type'  => 'link',
            'url'   => route('organisation-member.delete', ['id' => $member->id]),
            'text'  => 'Quitter',
            'popup' => true,
        ];
        @endphp
    @endcan

    @php
    $description = "
        {$member->getPermissionLabel()}<br>
        Membre depuis le {$member->created_at->format('d/m/Y')}
        (" . $member->created_at->diffForHumans() . ")";
    @endphp

    @include('blocks.infra_well', ['data' => [
        'type' => 'members',
        'overlay_text' => '',
        'image' => $member->pays->ch_pay_lien_imgdrapeau,
        'nom' => $member->pays->ch_pay_nom,
        'url' => url('page-pays.php?ch_pay_id=' . $member->pays->ch_pay_id),
        'description' => $description,
        'dropdown' => $dropdown,
        'description_escape' => false
    ]])

@endforeach

@if($members_invited->count())
    <h3>
        Invitations reçues
        <span class="label label-warning">{{$members_invited->count()}}</span>
    </h3>
    @foreach($members_invited as $member)

        @php
        $dropdown = [
            [
                'type' => 'form',
                'method' => 'PUT',
                'action' => route('organisation-member.update', ['id' => $member->id]),
                'data' => [
                     'permissions' => \Roxayl\MondeGC\Models\Organisation::$permissions['member'],
                 ],
                'button' => "Accepter l'invitation",
            ],
            [
                'type' => 'form',
                'method' => 'DELETE',
                'action' => route('organisation-member.destroy', ['id' => $member->id]),
                'data' => [],
                'button' => "Rejeter l'invitation"
            ],
        ];
        @endphp

        @include('blocks.infra_well', ['data' => [
            'type' => 'members',
            'overlay_text' => '',
            'image' => $member->pays->ch_pay_lien_imgdrapeau,
            'nom' => $member->pays->ch_pay_nom,
            'url' => url('page-pays.php?ch_pay_id=' . $member->pays->ch_pay_id),
            'description' => "Invitation reçue",
            'dropdown' => $dropdown,
        ]])

    @endforeach
@endif

@can('administrate', $organisation)
    <h3>Membres en attente
        @if($organisation->membersPending->count())
            <span class="label label-warning">
            {{ $organisation->membersPending->count() }}
            </span>
        @endif
    </h3>
    @foreach($organisation->membersPending as $member)

        @php
        $dropdown = [
            [
                'type' => 'form',
                'method' => 'PUT',
                'action' => route('organisation-member.update', ['id' => $member->id]),
                'data' => [
                     'permissions' => \Roxayl\MondeGC\Models\Organisation::$permissions['member'],
                 ],
                'button' => 'Accepter',
            ],
            [
                'type' => 'form',
                'method' => 'DELETE',
                'action' => route('organisation-member.destroy', ['id' => $member->id]),
                'data' => [],
                'button' => 'Rejeter'
            ],
        ];
        @endphp

        @include('blocks.infra_well', ['data' => [
            'type' => 'members',
            'overlay_text' => '',
            'image' => $member->pays->ch_pay_lien_imgdrapeau,
            'nom' => $member->pays->ch_pay_nom,
            'url' => url('page-pays.php?ch_pay_id=' . $member->pays->ch_pay_id),
            'description' => "En attente de validation",
            'dropdown' => $dropdown,
        ]])

    @endforeach

    @if(!count($organisation->membersPending))
        <div class="alert alert-tips">Il n'y a pas de membres en attente de validation.</div>
    @endif
@endcan

<div class="clearfix"></div>