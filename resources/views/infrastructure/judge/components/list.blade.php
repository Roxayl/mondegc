
    {{ $infrastructures->appends(['type' => $type])->links() }}

    <ul class="listes">
    @foreach($infrastructures as $infrastructure)
        <li>
            <div class="row-fluid">
                <div class="span2">
                    <img src="{{ $infrastructure->ch_inf_lien_image }}"
                         alt="Illustration de l'infra {{ $infrastructure->nom_infra }}">
                </div>
                <div class="span10">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('infrastructure-judge.show',
                            ['infrastructure' => $infrastructure->ch_inf_id]) }}"
                           data-toggle="modal" data-target="#modal-container">
                            <i class="icon-jugement icon-white"></i>
                            @if($type === 'pending') Juger
                            @else Modifier le jugement @endif
                        </a>
                    </div>

                    <h2>{{ $infrastructure->nom_infra }}</h2>
                    <small>
                        <img src="{{ $infrastructure->infrastructure_officielle
                            ->ch_inf_off_icone }}" alt="Icône : " style="height: 26px;">
                        {{ $infrastructure->infrastructure_officielle
                            ->ch_inf_off_nom }}
                    </small>
                    <br>

                    @if(!is_null($infrastructure->infrastructurable))
                        @include('infrastructure.judge.components.infrastructurable-snippet')
                    @else
                        <small style="color: red;">
                            L'élément auquel appartient cette infrastructure a été supprimé.
                        </small>
                    @endif
                </div>
            </div>
        </li>
    @endforeach
    </ul>

    {{ $infrastructures->appends(['type' => $type])->links() }}
