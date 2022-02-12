
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
                @foreach($chapters as $chapter)
                    <li><a href="#chapter-{{ $chapter->identifier }}">
                        {{ $chapter->title }}
                        @if($chapter->isCurrent())
                            <span class="badge badge-info inline">En cours</span>
                        @endif
                    </a></li>
                @endforeach
                <li><a href="#roleplay-next">Écrire la suite</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb pull-left">
                <li>Roleplay
                    <span class="divider">/</span></li>
                <li class="active">{{ $roleplay->name }}</li>
            </ul>

            <div class="pull-right">
                @can('manage', $roleplay)
                    <a href="{{ route('roleplay.edit', $roleplay) }}" class="btn btn-primary"
                       data-toggle="modal" data-target="#modal-container-small">
                        <i class="icon-edit icon-white"></i> Modifier
                    </a>
                @endcan
            </div>

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <div class="clearfix"></div>

            @if(! $roleplay->isValid())
                <div class="well">
                    <div class="alert alert-info">
                        <i class="icon-ok"></i> Allez, ce roleplay est terminé !
                    </div>
                </div>
            @endif

            <x-roleplay.organizers :roleplay="$roleplay" />

            @foreach($chapters as $chapter)
                <x-roleplay.chapter :chapter="$chapter"/>
            @endforeach

            @can('createChapters', $roleplay)
                <div class="titre-bleu" id="roleplay-next">
                    <h1>
                        Écrire la suite
                    </h1>
                </div>

                <div class="component-block" id="chapter-create">
                    <x-chapter.create-button :roleplay="$roleplay" />
                </div>
            @endcan

        </div>

    </div>
    </div>

@endsection
