@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $subdivision->name }}
@endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container"></div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li><a href="#">{{ $subdivision->name }}</a></li>
                <li><a href="#summary">Résumé</a></li>
                <li><a href="#content">Présentation</a></li>
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
                <li class="active">{{ $subdivision->name }}</li>
            </ul>

            <div class="pull-right">
                {{-- TODO: Actions --}}
            </div>

            <div class="clearfix"></div>

            {!! $helperService::displayAlert() !!}

            <div class="well">
                <h1>{{ $subdivision->name }}</h1>
            </div>
            <div class="well" id="summary">
                {!! $helperService::purifyHtml($subdivision->summary) !!}
            </div>
            <div class="well" id="content">
                {!! $helperService::purifyHtml($subdivision->content) !!}
            </div>

            <div class="clearfix"></div>
        </div>

    </div>
    </div>

@endsection
