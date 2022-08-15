
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Paramètres avancés
@endsection

@section('content')

    @parent

    <div class="container corps-page">

        {!! \App\Services\LegacyPageService::menuHautConseil() !!}

        <div class="titre-bleu anchor">
          <h1>Paramètres avancés</h1>
        </div>

        <div class="well">
            {!! $helperService::displayAlert() !!}
        </div>

        <div class="well">

            <h4>Purger le cache</h4>

            <p>Le cache peut être vidé via le bouton ci-dessous. Le cache est utilisé pour enregistrer les ressources
               générées par les entités, afin d'améliorer les performances.</p>

            <p>Taille du cache : {{ $cacheSize }}</p>

            @if(! $cacheEnabled)
                <div class="alert alert-info">Le cache est désactivé.</div>
            @endif

            <form action="{{ route('back-office.advanced-parameters.purge-cache') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Purger le cache
                </button>
            </form>

            <h4>Régénérer les influences</h4>

            <form action="{{ route('back-office.advanced-parameters.regenerate-influences') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Regénérer
                </button>
            </form>

        </div>

    </div>

@endsection