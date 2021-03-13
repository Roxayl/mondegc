
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $organisation->name }}
@endsection

@section('seodescription')
    {{ \Illuminate\Support\Str::limit(
            trim( @strip_tags($organisation->text) ),
            250
    ) }}
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

    <header class="jumbotron subhead anchor" id="header">
        <div class="container">
            <h1>{{ $organisation->name }}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                @include('organisation.components.sidebar-header')
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="#presentation">Présentation</a></li>
                @if($organisation->hasEconomy())
                <li><a href="#economie">Économie</a></li>
                @endif
                <li>
                    <a href="#membres">Membres
                    @can('administrate', $organisation)
                        @if($organisation->membersPending->count())
                            <div class="badge badge-warning" style="display: inline-block;">
                                {{ $organisation->membersPending->count() }}
                                {{ \Illuminate\Support\Str::plural('demande',
                                    $organisation->membersPending->count()) }} d'adhésion
                            </div>
                        @endif
                    @endcan
                    @if($members_invited->count())
                        <div class="badge badge-warning" style="display: inline-block;">
                            {{ $members_invited->count() }}
                            {{ \Illuminate\Support\Str::plural('invitation',
                                $organisation->membersPending->count()) }} en attente
                        </div>
                    @endif

                    </a>
                </li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb pull-left">
                <li><a href="{{url('politique.php#organisations')}}">Organisations</a>
                    <span class="divider">/</span></li>
                <li class="active">{{$organisation->name}}</li>
            </ul>

            <div class="pull-right">
                @can('update', $organisation)
                <a class="btn btn-primary"
                   href="{{route('organisation.edit', $organisation->id)}}">
                    <i class="icon-pencil icon-white"></i> Modifier l'organisation
                </a>
                @endcan
            </div>

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <div class="clearfix"></div>

            @include('organisation.components.actualites')

            @include('organisation.components.presentation')

            @include('organisation.components.economie')

            @include('organisation.components.members')

        </div>

    </div>
    </div>

@endsection