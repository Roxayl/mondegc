<div class="titre-bleu no-bg anchor">
    <div class="span6">
        <h1 style="font-size: 26px; padding-left: 16px; margin-top: 16px;">
            Événements
            <span class="badge badge-info badge-beta"
                  style="position: absolute; margin-left: -14px; margin-top: -4px;">
                Bêta
            </span>
        </h1>
    </div>

    <div class="span6">
        <div class="pull-right">
            @can('create', \Roxayl\MondeGC\Models\Roleplay::class)
                <a class="btn btn-primary" style="margin-top: 18px; margin-right:10px;"
                   data-toggle="modal" data-target="#myModal"
                   href="{{ urlFromLegacy(route('roleplay.create')) }}">
                    <i class="icon-bell icon-white"></i>
                    Créer un roleplay
                </a>
            @endcan
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div style="margin: 10px;">

    @if($rps->isEmpty())
        <div class="row-fluid">
            <div class="span3" style="text-align: center;">
                <img src="{{ urlFromLegacy('assets/img/Icones/evenement.png') }}">
            </div>
            <div class="span9">
                <i class="icon-info-sign"></i>
                Aucun événement n'est en cours pour le moment. Des idées pour animer le Monde GC ?
                Lancez votre roleplay dès à présent !
            </div>
        </div>
    @endif

    @foreach($rps as $rp)

        <div class="thumbnails">
            <div class="span12">
                <h4>
                    <a href="{{ urlFromLegacy(route('roleplay.show', $rp)) }}">
                        {{ $rp->name }}
                    </a>
                </h4>

                <small style="margin: 0;">
                    @empty($rp->ending_date)
                        @if($rp->currentChapter())
                            Chapitre {{ $rp->currentChapter()->order }} -
                            <strong>{{ $rp->currentChapter()->name }}</strong><br>
                        @else
                            Aucun chapitre démarré.<br>
                        @endif
                        Événement en cours commençant le {{ $rp->starting_date->format('d/m/Y') }}
                    @else
                        Événement du {{ $rp->starting_date->format('d/m/Y') }}
                        au {{ $rp->ending_date->format('d/m/Y') }}
                    @endempty
                </small>
            </div>
        </div>

    @endforeach

    <p style="margin: 10px;">
        <a class="btn btn-primary" href="{{ urlFromLegacy(route('roleplay.index')) }}">Voir tous les événements</a>
    </p>

</div>

<br>
<hr>
