
@extends('layouts.legacy')

@section('title')
    Liste des roleplays
@endsection

@section('content')

    @parent

    <div class="container corps-page">
        <div class="row-fluid">
            <div class="titre-bleu">
                <h1>Liste des événements</h1>
            </div>

            <ul class="thumbnails">
            @foreach($roleplays as $roleplay)
                <li>
                    <div style="background-image: url('{{ $roleplay->banner }}'); background-size: cover; width: 100%; height: 100px;"
                         alt="Bannière de {{ $roleplay->name }}">
                    </div>
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
        </div>
    </div>

@endsection
