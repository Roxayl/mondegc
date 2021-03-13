
@extends('layouts.legacy')

@section('title')
    Modifier {{ $organisation->name }}
@endsection

@section('seodescription')
    Modifier la page de l'organisation {{ $organisation->name }}.
@endsection

@include('organisation.components.assets')

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container">
            <h1>{{$organisation->name}}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                @include('organisation.components.sidebar-header')
                <li><a href="#presentation">Présentation</a></li>
                @if($organisation->hasEconomy())
                    <li><a href="#infrastructures">Infrastructures</a></li>
                @endif
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a>
                    <span class="divider">/</span></li>
                <li>
                    <a href="{{ route('organisation.showslug',
                                 $organisation->showRouteParameter()) }}"
                        >{{$organisation->name}}</a>
                    <span class="divider">/</span></li>
                <li class="active">Modifier</li>
            </ul>

            <div class="well">
                {!! App\Services\HelperService::displayAlert() !!}
            </div>

            {{-- S'il faut afficher un en-tête sur le type d'orga ?
            <div class="well">
                <div class="org-container org-{{ $organisation->type }}">
                    <h3>{{ __("organisation.types.$organisation->type") }}</h3>
                    <p>{{ __("organisation.types.{$organisation->type}-description") }}</p>
                </div>
            </div>
            --}}

            <div id="presentation" class="titre-vert anchor">
                <h1>Présentation</h1>
            </div>

            <div class="well">

            <label>Type d'organisation
                <span class="badge org-{{ $organisation->type }}">
                {{ __("organisation.types.{$organisation->type}") }}</span>
                <a href="{{ route('organisation.migrate',
                    ['organisation' => $organisation->id]) }}"
                   data-toggle="modal" data-target="#modal-container">
                    Migrer vers un nouveau type...
                </a>
            </label>
            <br>

            <form method="POST" action="{{route('organisation.update',
                ['organisation' => $organisation->id])}}">
                @method('patch')

                @include('organisation.components.form')

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>

            </div>

            <div class="clearfix"></div>

            @if($organisation->hasEconomy())

                {!! \App\Services\HelperService::renderLegacyElement(
                    'infrastructure/back_list', ['infrastructurable' => $organisation]
                ) !!}
                <div class="modal container fade" id="Modal-Monument"></div>

            @endif

        </div>

    </div>
    </div>

@endsection
