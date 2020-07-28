
@extends('layouts.legacy')

@section('title')
    {{$title}}
@endsection

@section('seodescription')
    {{$seo_description}}
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('styles')
<style>
.jumbotron {
    background-image: url('{{$organisation->flag}}');
}
</style>
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container">
            <h1>{{$page_title}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><a href="#header">
                    <img src="{{$organisation->logo}}" alt="Logo de {{$organisation->name}}">
                    <p><strong>{{$organisation->name}}</strong></p>
                        <p><em>{{$organisation->members->count()}}
                            {{ \Illuminate\Support\Str::plural('membre',  $organisation->members->count()) }}</em></p>
                    </a></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#presentation">Présentation</a></li>
                @if($organisation->allow_temperance)
                <li><a href="#economie">Économie</a></li>
                @endif
                <li>
                    <a href="#membres">Membres
                    @can('administrate', $organisation)
                        @if($organisation->membersPending->count())
                            <div class="badge badge-warning" style="display: inline-block;">
                                {{ $organisation->membersPending->count() }}
                                {{ \Illuminate\Support\Str::plural('demande',
                                    $organisation->membersPending->count()) }} d'adhésion
                            </div>
                        @endif
                    @endcan
                    @if($members_invited->count())
                        <div class="badge badge-warning" style="display: inline-block;">
                            {{ $members_invited->count() }}
                            {{ \Illuminate\Support\Str::plural('invitation',
                                $organisation->membersPending->count()) }} en attente
                        </div>
                    @endif

                    </a>
                </li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb pull-left">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a> <span class="divider">/</span></li>
                <li class="active">{{$organisation->name}}</li>
            </ul>

            <div class="pull-right">
                @can('update', $organisation)
                <a class="btn btn-primary"
                   href="{{route('organisation.edit', $organisation->id)}}">
                    <i class="icon-pencil icon-white"></i> Modifier l'organisation
                </a>
                @endcan
            </div>

            <div class="clearfix"></div>

            <div class="well">
                {!! App\Services\HelperService::displayAlert() !!}
            </div>


            @can('administrate', $organisation)
                <div class="cta-title pull-right-cta" style="margin-top: 20px;">
                    <a href="<?= url('back/communique_ajouter.php?userID='.
                        auth()->user()->ch_use_id . '&cat=organisation&com_element_id=' .
                        $organisation->id) ?>" class="btn btn-primary btn-cta">
                        <i class="icon-white icon-plus-sign"></i>
                        Publier un nouveau communiqué</a>
                </div>
            @endcan
            <div id="actualites" class="titre-vert">
                <h1>Actualités</h1>
            </div>
            @if($organisation->communiques->count())
            <h3>Communiqués</h3>
            <table class="table table-hover" cellspacing="1" width="100%">
            <thead>
                <tr class="tablehead2">
                    <th><i class="icon-globe"></i></th>
                    <th>Titre</th>
                    <th>Date de publication</th>
                </tr>
                @foreach($organisation->communiques as $communique)
                <tr>
                    <td></td>
                    <td>
                <a href="{{ url('page-communique.php?com_id=' . $communique->ch_com_ID) }}">
                    {{ $communique->ch_com_titre }}</a>
                    </td>
                    <td>{{ $communique->ch_com_date->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </thead>
            </table>
            @else
            <div class="alert alert-tips">
                Cette organisation n'a pas d'actualités pour le moment !
                Allez hopop, on se motive les membres !
            </div>
            @endif


            <div id="presentation" class="titre-vert anchor">
                <h1>Présentation</h1>
            </div>
            <div class="well">
                {!!$content!!}
            </div>

            @if($organisation->allow_temperance)
            <div id="economie" class="titre-vert anchor">
                <h1>Économie</h1>
            </div>
            @php
            $temperance = $organisation->temperance()->get()->first()->toArray();
            @endphp
            {!! \App\Services\HelperService::renderLegacyElement('Temperance/resources', [
                'resources' => $temperance
            ]) !!}
            <div class="clearfix"></div>

            <div class="well">
            <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#economie-pays">
                    Balance économique par pays membre
                </a>
            </div>
            <div id="economie-pays" class="accordion-body collapse">
                <div class="accordion-inner">

                @php
                $temperancePays = $organisation->membersWithTemperance()->toArray();
                @endphp
                @foreach($temperancePays as $thisPays)
                    @php
                        $paysResources = $thisPays['temperance'][0];
                    @endphp
                    <div>
                        <img src="{{ $thisPays['pays']['ch_pay_lien_imgdrapeau'] }}"
                             class="img-menu-drapeau"
                             alt="{{ $thisPays['pays']['ch_pay_nom'] }}">
                        <a href="{{ url('page-pays.php?ch_pay_id=' . $thisPays['pays_id']) }}">
                            {{ $thisPays['pays']['ch_pay_nom'] }}</a>
                        {!! \App\Services\HelperService::renderLegacyElement(
                            'Temperance/resources_small', [
                                'resources' => [
                                     'budget' => $paysResources['budget'],
                                     'industrie' => $paysResources['industrie'],
                                     'commerce' => $paysResources['commerce'],
                                     'agriculture' => $paysResources['agriculture'],
                                     'tourisme' => $paysResources['tourisme'],
                                     'recherche' => $paysResources['recherche'],
                                     'environnement' => $paysResources['environnement'],
                                     'education' => $paysResources['education'],
                                ]
                            ]) !!}
                    </div>
                @endforeach

                </div>
            </div>
            </div>
            </div>
            @endif


            <div class="cta-container"
                 style="position: relative; top: 20px; margin-right: -15px;">
            @can('administrate', $organisation)
                <a href="<?= route('organisation-member.invite',
                             ['organisation_id' => $organisation->id]) ?>"
                   class="btn btn-primary btn-cta pull-right"
                   data-toggle="modal" data-target="#modal-container">
                <i class="icon-white icon-envelope"></i> Inviter...</a>
            @endcan
            @if(auth()->check())
                <a href="<?= route('organisation-member.join',
                             ['organisation_id' => $organisation->id]) ?>"
                   class="btn btn-primary btn-cta pull-right" style="margin-right: 8px;"
                   data-toggle="modal" data-target="#modal-container">
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
                $day_diff = $member->created_at->diffInDays(\Carbon\Carbon::now());
                $description = "
                    {$member->getPermissionLabel()}<br>
                    Membre depuis le {$member->created_at->format('d/m/Y')}
                    (depuis {$day_diff} " .
                    \Illuminate\Support\Str::plural('jour', $day_diff) . ")";
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
                                 'permissions' => \App\Models\Organisation::$permissions['member'],
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
                                 'permissions' => \App\Models\Organisation::$permissions['member'],
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

        </div>

    </div>
    </div>

@endsection