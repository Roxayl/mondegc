
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
                <li><a href="#infrastructures">Infrastructures</a></li>
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
                    <h3>{{ trans("organisation.types.$organisation->type") }}</h3>
                    <p>{{ trans("organisation.types.{$organisation->type}-description") }}</p>
                </div>
            </div>
            --}}

            <div id="presentation" class="titre-vert anchor">
                <h1>Présentation</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="well">

            <label>Type d'organisation
                <span class="badge org-{{ $organisation->type }}">
                {{ __("organisation.types.{$organisation->type}") }}
                </span>
            </label>
            <br>

            <form method="POST" action="{{route('organisation.update',
                ['organisation' => $organisation->id])}}">
                @method('patch')

                @include('organisation.components.form')

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>

            </div>


            {!! \App\Services\HelperService::renderLegacyElement(
                'infrastructure/back_list', ['infrastructurable' => $organisation]
            ) !!}
            <div class="modal container fade" id="Modal-Monument"></div>

        </div>

    </div>
    </div>

@endsection
