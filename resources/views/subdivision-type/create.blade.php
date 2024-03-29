@extends('layouts.legacy')

@section('title')
    Créer un type de subdivision administrative
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>Créer un type de subdivision administrative</h1>
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
                    <a href="{{ route('pays.show', $subdivisionType->pays->showRouteParameter()) }}">{{ $subdivisionType->pays->ch_pay_nom }}</a>
                    <span class="divider">/</span>
                </li>
                <li>
                    Subdivisions
                    <span class="divider">/</span>
                </li>
                <li class="active">Créer un type de subdivision</li>
            </ul>

            <div class="clearfix"></div>

            <div class="well">
                {!! Roxayl\MondeGC\Services\HelperService::displayAlert() !!}

                <form action="{{ route('subdivision-type.store', ['pays' => $subdivisionType->pays]) }}" method="POST">
                    @include('subdivision-type.components.form')
                </form>
            </div>

        </div>

    </div>
    </div>

@endsection
