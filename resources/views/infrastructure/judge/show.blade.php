
@inject('helperService', 'App\Services\HelperService')

@extends('layouts.popup')

@section('modal_header')
    <h3 id="myModalLabel">
        Juger l'infrastructure {{ $infrastructure->nom_infra }}
        <small>
            {{ $infrastructure->infrastructure_officielle->ch_inf_off_nom }}
        </small>
    </h3>
@endsection

@section('modal_body')

    <div class="row-fluid">
        <div class="span6">
            <h2>{{ $infrastructure->nom_infra }}</h2>
            <p>{{ $infrastructure->ch_inf_commentaire }}</p>
            <p>
                <div class="external-link-icon"
                      style="background-image:url('https://www.generation-city.com/forum/new/favicon.png');">
                </div>
                <a href="{{ $infrastructure->ch_inf_lien_forum }}">Forum</a>
                @if(!empty($infrastructure->lien_wiki))
                    &#183;
                    <div class="external-link-icon"
                          style="background-image:url('https://romukulot.fr/kaleera/images/h4FQp.png');">
                    </div>
                    <a href="{{ $infrastructure->lien_wiki }}">Wiki GC</a>
                @endif
            </p>
        </div>
        <div class="span6">
            <div class="well">
                <small>
                    <img src="{{ $infrastructure->infrastructure_officielle->ch_inf_off_icone }}"
                         alt="Icône" style="height: 28px;">
                    {{ $infrastructure->infrastructure_officielle->ch_inf_off_nom }}
                </small>
                <br>
                <em>
                    {!! $infrastructure->infrastructure_officielle->ch_inf_off_desc !!}
                </em>
                <br>
                {!! $helperService::renderLegacyElement('temperance/resources_small', [
                    'resources' => $infrastructure->infrastructure_officielle->mapResources()
                ]) !!}
            </div>

            <div class="well">
                @include('infrastructure.judge.components.infrastructurable-snippet')
            </div>
        </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading">
        <a class="accordion-toggle" data-toggle="collapse"
           href="#collapse_images">Images</a>
    </div>
    <div id="collapse_images" class="accordion-body collapse">
        <div class="accordion-inner">
            <img src="{{ $infrastructure->ch_inf_lien_image }}" alt="Image 1">
            @if(!empty($infrastructure->ch_inf_lien_image2))
            <br><br>
            <img src="{{ $infrastructure->ch_inf_lien_image2 }}" alt="Image 2">
            @endif
            @if(!empty($infrastructure->ch_inf_lien_image3))
            <br><br>
            <img src="{{ $infrastructure->ch_inf_lien_image3 }}" alt="Image 3">
            @endif
        </div>
    </div>
    </div>

@endsection

@section('modal_footer')

    <div class="pull-left">
        <p>
            Statut :
            <strong style="color: {{ $infrastructure->getStatusData()->color }}">
                {{ $infrastructure->getStatusData()->text }}
            </strong>
        </p>
    </div>

    <label for="radio-accepted" style="display: inline-block;">
        <input type="radio" id="radio-accepted" name="ch_inf_statut"
               value="{{ \App\Models\Infrastructure::JUGEMENT_ACCEPTED }}">
        <span style="display: inline-block;" type="submit" class="btn btn-large btn-success">
            <i class="icon-jugement icon-white"></i> Accepter
        </span>
    </label>

    <label for="radio-rejected" style="display: inline-block;">
        <input type="radio" id="radio-rejected" name="ch_inf_statut"
               value="{{ \App\Models\Infrastructure::JUGEMENT_REJECTED }}">
        <span style="display: inline-block;" type="submit" class="btn btn-large btn-danger">
            <i class="icon-jugement icon-white"></i> Rejeter
        </span>
    </label>

    <label for="radio-pending" style="display: inline-block;">
        <input type="radio" id="radio-pending" name="ch_inf_statut"
               value="{{ \App\Models\Infrastructure::JUGEMENT_PENDING }}">
        <span style="display: inline-block;" type="submit" class="btn btn-large btn-info">
            <i class="icon-time icon-white"></i> Définir en attente de jugement
        </span>
    </label>

    <div id="textarea-reject-container" style="display: none;">
        <br>
        <label for="ch_inf_commentaire_juge"> Expliquer la raison de votre refus</label>
        <textarea name="ch_inf_commentaire_juge" id="ch_inf_commentaire_juge"
                  style="width: 60%; height: 80px;">{{ old('ch_inf_commentaire_juge',
                    $infrastructure->ch_inf_commentaire_juge) }}</textarea>
    </div>

    <br><br>
    <button type="submit" class="btn btn-primary"
            id="submit-button">Soumettre le jugement</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
@endsection

@section('popup_start')
    <form method="POST" action="{{ route('infrastructure-judge.judge',
        ['infrastructure' => $infrastructure->ch_inf_id]) }}">
    @method('PATCH')
    @csrf
@endsection

@section('popup_end')
    </form>

    <script>
    var executedAlready = false;
    (function($, document, window, undefined) {
        var selected;

        if (executedAlready) {
            return false;
        } else {
            executedAlready = true;
        }

        var evaluateRadio = function() {
            selected = $('input[name=ch_inf_statut]:checked');
            if(parseInt(selected.val()) === {{ \App\Models\Infrastructure::JUGEMENT_REJECTED }}) {
                $('#textarea-reject-container').show();
            } else {
                $('#textarea-reject-container').hide();
            }

            if(selected.val()) {
                $('#submit-button').show();
            } else {
                $('#submit-button').hide();
            }
        };

        $(document).on('change', 'input[name=ch_inf_statut]', evaluateRadio);
        evaluateRadio();
    })(jQuery, document, window);
    </script>
@endsection
