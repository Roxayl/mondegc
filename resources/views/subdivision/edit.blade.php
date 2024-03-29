@extends('layouts.legacy')

@section('title')
    Modifier une subdivision administrative : {{ $subdivision->name }}
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor">
        <div class="container">
            <h1>Modifier une subdivision administrative : {{ $subdivision->name }}</h1>
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
                <li>
                    <a href="{{ route('subdivision.show', $subdivision->showRouteParameter()) }}#subdivision">
                        {{ $subdivision->name }}
                    </a>
                    <span class="divider">/</span>
                </li>
                <li class="active">Modifier</li>
            </ul>

            <div class="clearfix"></div>

            <div class="well">
                {!! Roxayl\MondeGC\Services\HelperService::displayAlert() !!}

                <form action="{{ route('subdivision.edit', $subdivision) }}" method="POST">
                    @method('PUT')
                    @include('subdivision.components.form')
                </form>
            </div>

        </div>

    </div>
    </div>

@endsection
