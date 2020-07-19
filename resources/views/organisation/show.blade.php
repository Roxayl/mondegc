
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

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>{{$page_title}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li class="row-fluid"><img src="{{$organisation->logo}}">
                    <p><strong>{{$organisation->name}}</strong></p>
                    <p><em>{{$organisation->members->count()}} membre(s)</em></p></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#presentation">Présentation</a></li>
                <li><a href="#membres">Membres</a></li>
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
                   href="{{route('organisation.edit', ['id' => $organisation->id])}}">
                    <i class="icon-pencil icon-white"></i> Modifier l'organisation
                </a>
                @endcan
            </div>

            <div class="clearfix"></div>

            <div class="well">
                {!! App\Services\HelperService::displayAlert() !!}
            </div>

            <div id="actualites" class="titre-vert anchor">
                <h1>Actualités</h1>
            </div>
            <div class="well">
                <div class="alert alert-info">
                    En cours !
                </div>
            </div>

            <div id="presentation" class="titre-vert anchor">
                <h1>Présentation</h1>
            </div>
            <div class="well">
                {!!$content!!}
            </div>

            @if(auth()->check())
                <div class="cta-title pull-right-cta" style="margin-top: 30px;">
                    <a href="<?= route('organisation-member.join', ['organisation_id' => $organisation->id]) ?>"
                       class="btn btn-primary btn-cta"
                       data-toggle="modal" data-target="#modal-container">
                    <i class="icon-white icon-plus-sign"></i> Rejoindre...</a>
                </div>
            @endif
            <div id="membres" class="titre-vert anchor">
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

            @can('administrate', $organisation)
                <h3>Membres en attente</h3>
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