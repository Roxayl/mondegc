
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $roleplay->name }}
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" @endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container">
            <h1>{{ $roleplay->name }}</h1>
        </div>
    </header>

    <div class="container">
    <div class="row-fluid">

        <div class="span3 bs-docs-sidebar">
            <ul class="nav nav-list bs-docs-sidenav">
                <li><a href="#">{{ $roleplay->name }}</a></li>
                @foreach($roleplay->chapters as $chapter)
                    <li><a href="#{{ $chapter->identifier }}">
                            {{ $chapter->title }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb">
                <li>Roleplay
                    <span class="divider">/</span></li>
                <li class="active">{{ $roleplay->name }}</li>
            </ul>

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <div class="clearfix"></div>

            <x-roleplay.organizers :roleplay="$roleplay" />

            @foreach($roleplay->chapters as $chapter)
                <x-roleplay.chapter :chapter="$chapter"/>
            @endforeach

        </div>

    </div>
    </div>

@endsection