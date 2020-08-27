
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

            <div id="actualites" class="titre-vert anchor">
                <h1>Créer une organisation</h1>
            </div>

            <div class="well">

            <form method="POST" action="{{route('organisation.store')}}">

                @include('organisation.components.form')

                <div class="control-group">
                    <label for="pays_id">Choisir le propriétaire de l'organisation :</label>
                    <select name="pays_id" id="pays_id">
                        @foreach($pays as $thisPays):
                            <option value="{{$thisPays->ch_pay_id}}"
                                >{{$thisPays->ch_pay_nom}}</option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" class="btn btn-primary" value="Envoyer">

            </form>

            </div>

        </div>

    </div>
    </div>

@endsection