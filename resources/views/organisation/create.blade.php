
@extends('layouts.legacy')

@section('title')
    Créer une organisation
@endsection

@section('seodescription')
    Créer une nouvelle organisation de pays au sein du Monde GC.
@endsection

@include('organisation.components.assets')

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>Créer une organisation</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li><a href="#creer">Créer</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a>
                    <span class="divider">/</span></li>
                <li class="active">Créer</li>
            </ul>

            <div class="well">
                {!! App\Services\HelperService::displayAlert() !!}
            </div>

            <div class="well">

            @if(is_null($type))
                @include('organisation.components.select-type')
            @else
                @include('organisation.components.create-form')
            @endif

            </div>

        </div>

    </div>
    </div>

@endsection
