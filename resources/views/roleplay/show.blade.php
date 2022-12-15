
@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $roleplay->name }}
@endsection

@section('styles')
    <link href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css" rel="stylesheet">

    <style>
        .corps-page, .bs-docs-sidebar {
            position: relative;
            z-index: 140;
        }
        .corps-page {
            margin-top: -80px;
        }
        @if($roleplay->banner)
        .jumbotron {
            background-image: url('{{ $roleplay->banner }}');
            background-position: 0 -240px;
            background-attachment: fixed;
            height: 360px;
            z-index: 120;
            position: relative;
        }
        @endif
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../assets/js/Editeur.js"></script>

    <script type="text/javascript">
        (function($, document, window) {

            let addContainerSelector = '.chapter-entry-media-container';

            let parentSelector = '.chapter-entry-add-container';

            let triggerButtonSelector = '.chapter-entry-media-trigger';

            let removeMedia = function($parent) {
                $parent.find('input[type=text]').val('');
                $parent.find('input[name=media_type][value="none"]').prop('checked', true);
                $parent.find('.media-parameters-container').hide();
            };

            $(document).on('click', triggerButtonSelector, function(ev) {
                ev.preventDefault();

                let $parent = $(ev.target).closest(parentSelector);

                let $container = $parent.find(addContainerSelector);

                if($container.is(':visible')) {
                    removeMedia($parent);
                    $container.hide();
                    $parent.find(triggerButtonSelector).find('span').text('Ajouter un média');
                } else {
                    $container.show();
                    $parent.find(triggerButtonSelector).find('span').text('Retirer un média');
                }
            });

            $(document).on('change', 'input[name=media_type]', function(ev) {
                let $parent = $(ev.target).closest(parentSelector);

                let value = $parent.find('input[name=media_type]:checked').val();

                console.log('selected value:', value);

                $parent.find('.media-parameters-container').hide();
                $parent.find('.media-parameters-container[data-media-type="' + value + '"]').show();
            });

        })(jQuery, document, window);
    </script>
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="200" @endsection

@section('content')

    @parent

    <header class="jumbotron subhead anchor" id="header">
        <div class="container"></div>
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
                            <span class="badge badge-warning inline">En cours</span>
                        @endif
                    </a></li>
                @endforeach
                <li><a href="#roleplay-next">Écrire la suite</a></li>
            </ul>
        </div>

        <div class="span9 corps-page">

            <ul class="breadcrumb pull-left">
                <li>Roleplay <span class="badge badge-info badge-beta">Bêta</span>
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

            {!! $helperService::displayAlert() !!}

            <div class="well">
                <h1>{{ $roleplay->name }}</h1>
                <p>{{ $roleplay->description }}</p>
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

            @can('createChapters', $roleplay)
                <h3 id="roleplay-next">
                    Écrire la suite
                </h3>

                <div class="component-block" id="chapter-create">
                    <x-chapter.create-button :roleplay="$roleplay" />
                </div>
            @endcan

            @foreach($chapters as $chapter)
                <x-chapter.chapter :chapter="$chapter"/>
            @endforeach

        </div>

    </div>
    </div>

@endsection
