
@extends('layouts.legacy')

@section('title')
    Liste des roleplays
@endsection

@section('content')

    @parent

    <div class="container corps-page">
        <div class="row-fluid">

            <div class="cta-title pull-right-cta">
            <a class="btn btn-cta btn-primary"
               data-toggle="modal" data-target="#modal-container"
               href="{{ route('roleplay.create') }}">
                <i class="icon-bell icon-white"></i>
                Créer un roleplay
                <span class="badge badge-info badge-beta"
                      style="position: absolute; margin-left: -20px; margin-top: -8px;">
                    Bêta
                </span>
            </a>
                </div>
            <div class="titre-bleu">
                <h1>
                    Liste des événements
                    <span class="badge badge-info badge-beta"
                          style="position: absolute; margin-left: -22px; margin-top: -3px;">Bêta</span>
                    <small>Page {{ $roleplays->currentPage() }} sur {{ $roleplays->lastPage() }}</small>
                </h1>
            </div>

            <div class="pull-right">
                {{ $roleplays->links() }}
            </div>

            <ul class="breadcrumb">
              <li class="active">Événements <span class="badge badge-info badge-beta">Bêta</span></li>
            </ul>

            <div class="well">
                <p>Retrouvez tous les événements du monde gécéen sur cette page.</p>
            </div>
            <div class="clearfix"></div>

            <ul class="thumbnails">
            @foreach($roleplays as $roleplay)
                <li style="width: 90%; background-color: #f5f5f5; border-left: 3px solid #727272;">
                    @if($roleplay->banner)
                        <div style="background-image: url('{{ $roleplay->banner }}');background-size: cover;
                                    width: 100%; height: 100px;"></div>
                    @endif
                    <a href="{{ route('roleplay.show', $roleplay) }}">
                        <h3>
                            {{ $roleplay->name }}
                            <small>
                                @if($roleplay->isValid())
                                    Roleplay commençant le {{ $roleplay->starting_date->format('d/m/Y') }}
                                @else
                                    Roleplay du {{ $roleplay->starting_date->format('d/m/Y') }} au {{ $roleplay->ending_date->format('d/m/Y') }}
                                @endif
                            </small>
                        </h3>
                    </a>
                    <div class="well">
                        <p>{{ $roleplay->description }}</p>
                        <p>
                            <strong>Chapitres :</strong>
                            @foreach($roleplay->chapters as $chapter)
                                <a href="{{ route('roleplay.show', $roleplay) }}#chapter-{{ $chapter->identifier }}" style="display: inline-block;">
                                    {{ $chapter->name }}
                                </a>
                                @if(! $loop->last)
                                    >
                                @endif
                            @endforeach
                        </p>
                    </div>
                </li>
            @endforeach
            </ul>

            {{ $roleplays->links() }}

        </div>
    </div>

@endsection
