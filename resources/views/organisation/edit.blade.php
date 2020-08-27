
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
                <li class="row-fluid"><a href="#header">
                    <img src="{{$organisation->logo}}" alt="Logo de {{$organisation->name}}">
                    <p><strong>{{$organisation->name}}</strong></p>
                    <p><em>{{$organisation->members->count()}} membre(s)</em></p>
                    </a></li>
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

            <form method="POST" action="{{route('organisation.update',
                ['organisation' => $organisation->id])}}">
                @method('patch')

                @include('organisation.components.form')

                <input type="submit" class="btn btn-primary" value="Envoyer">
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