@extends('layouts.legacy')

@section('title')
    Créer une subdivision administrative
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>Créer une subdivision administrative</h1>
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

            <ul class="breadcrumb pull-left">
                <li>
                    <a href="{{ route('pays.index') }}">Pays</a>
                    <span class="divider">/</span>
                </li>
                <li>
                    <a href="{{ route('pays.show', $subdivision->pays->showRouteParameter()) }}">{{ $subdivision->pays->ch_pay_nom }}</a>
                    <span class="divider">/</span>
                </li>
                <li>
                    <a href="{{ route('pays.show', $subdivision->pays->showRouteParameter()) }}#subdivision">
                        {{ $subdivision->subdivisionType?->type_name }}
                    </a>
                    <span class="divider">/</span>
                </li>
                <li class="active">Créer</li>
            </ul>

            <div class="well">

                {!! Roxayl\MondeGC\Services\HelperService::displayAlert() !!}



            </div>

        </div>

    </div>
    </div>

@endsection
