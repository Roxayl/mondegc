
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Historique du chapitre {{ $chapter->order }} : {{ $chapter->name }}
@endsection

@section('content')

    @parent
    <div class="container">
    <div class="row-fluid">

        <div class="span12 corps-page">

            <div class="titre-bleu">
                <h1>Historique du chapitre {{ $chapter->order }} : {{ $chapter->name }}</h1>
            </div>

            <ul class="breadcrumb">
                <li>Roleplay <span class="divider">/</span></li>
                <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}">
                        {{ $chapter->roleplay->name }}</a>
                    <span class="divider">/</span></li>
                <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}#chapter-{{ $firstChapter->identifier }}">
                        Chapitres</a>
                    <span class="divider">/</span></li>
                <li><a href="{{ route('roleplay.show', $chapter->roleplay) }}#chapter-{{ $chapter->identifier }}">
                        {{ $chapter->name }}</a>
                    <span class="divider">/</span></li>
                <li class="active">Historique</li>
            </ul>

            <div class="clearfix"></div>

            <div class="well">
                {!! $helperService::displayAlert() !!}
            </div>

            <div class="clearfix"></div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Date de modification</th>
                        <th>Motif de la modification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($versions as $version)
                        <tr>
                            <td>{{ $version->responsible_user?->ch_use_login }}</td>
                            <td>{{ $version->created_at->format('Y/m/d à H:i:s') }}</td>
                            <td>{{ $version->reason }}</td>
                            <td>
                                @if($canRevert)
                                    <form method="POST" action="{{ route('version.revert', $version) }}"
                                          class="form-inline">
                                        @csrf
                                        @if(! $version->isFirst())
                                            <a href="{{ route('chapter.diff', [
                                                    'version1' => $version,
                                                    'version2' => $version->previous()
                                                ]) }}"
                                                class="btn btn-primary"
                                                data-toggle="modal" data-target="#modal-container">
                                                <i class="icon-comment icon-white"></i> Comparer
                                            </a>
                                        @endif

                                        @if(! $version->isLast())
                                            <button class="btn btn-primary">
                                                <i class="icon-time icon-white"></i> Restaurer
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
    </div>

@endsection
