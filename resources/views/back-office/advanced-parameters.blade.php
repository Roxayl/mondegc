
@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Paramètres avancés
@endsection

@section('content')

    @parent

    <div class="container corps-page">

        {!! \Roxayl\MondeGC\Services\LegacyPageService::menuHautConseil() !!}

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

            <form action="{{ route('back-office.advanced-parameters.purge-cache') }}"
                  class="advanced-parameter-form" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Purger le cache
                </button>
            </form>

            <h4>Régénérer les influences</h4>

            <p>Cette opération permet de recalculer les influences générées par les entités (patrimoine, infrastructure, zone de carte...) générant des ressources économiques. Les influences permettent notamment de générer des ressources dans le temps. Vous pouvez forcer le calcul des influences lorsque les règles métier calculant la génération de ressources dans le temps change, ou si la base de données est corrompue.</p>

            <p>Nombre d'enregistrements de la table <code>influence</code> : {{ $influenceTableSize }} enregistrement(s).</p>

            <div class="alert alert-warning">Attention, en fonction du nombre d'entités, cette opération peut prendre plusieurs minutes.</div>

            <form action="{{ route('back-office.advanced-parameters.regenerate-influences') }}"
                  class="advanced-parameter-form" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Régénérer
                </button>
            </form>

        </div>

    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        (function($) {
            $('form.advanced-parameter-form').on('submit', function(ev) {
                $(this).find('button[type="submit"]').html('Opération en cours...').prop('disabled', true);
            });
        })(jQuery);
    </script>
@endsection
