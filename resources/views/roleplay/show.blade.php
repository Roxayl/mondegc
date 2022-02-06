
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $roleplay->name }}
@endsection

@section('styles')
    <link href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../assets/js/Editeur.js"></script>
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
                <li><a href="#roleplay-organizers">Organisateurs</a></li>
                @foreach($roleplay->chapters as $chapter)
                    <li><a href="#chapter-{{ $chapter->identifier }}">
                        {{ $chapter->title }}
                        @if($chapter->isCurrent())
                            <span class="badge badge-info inline">En cours</span>
                        @endif
                    </a></li>
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

            <div class="component-block" id="chapter-create">
                <x-chapter.create-button :roleplay="$roleplay" />
            </div>

        </div>

    </div>
    </div>

@endsection
