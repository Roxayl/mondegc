@php
$viewDirectory = \Roxayl\MondeGC\Models\Infrastructure
                ::getUrlParameterFromMorph(get_class($infrastructure->infrastructurable));
$viewActionVerb = 'Modifier';
@endphp

@extends('layouts.legacy')

@section('title')
    Modifier une infrastructure
@endsection

@section('seodescription')
    Modifiez une infrastructure.
@endsection

@include('infrastructure.components.assets')

@section('content')

    <div class="corps-page container">

        @include("infrastructure.components.$viewDirectory.header")

        @include("infrastructure.components.$viewDirectory.breadcrumb")

        @include('infrastructure.components.description')

        <div class="row-fluid">
          <div class="span6 well">

          <h3 style="margin: 0;">
              {{ $infrastructure->infrastructureOfficielle
                    ->infrastructureGroupe->first()->nom_groupe }}
          </h3>

          <br><br>

          <!-- choix infrastructure -->
          <div id="spryselect1" class="control-group">
              <div class="control-label">
                  <h4 style="display: inline-block;">Choisissez votre infrastructure</h4>
                  <a href="#" rel="clickover"
                     title="Infrastructures de la liste officielle" data-content="Vous devez choisir une infrastructure dans la liste officielle. Chaque nouvelle infrastructure va modifier les valeurs de votre économie"><i class="icon-info-sign"></i></a>
              </div>
              <div class="controls">
                  <select name="infrastructure_officielle_id" id="infrastructure_officielle_id" placeholder="Rechercher une infrastructure..." disabled>
                @foreach($infrastructureGroupe->infrastructuresOfficielles as $thisInfOff)
                <option value="{{ $thisInfOff->ch_inf_off_id }}"
                    @if($infrastructure->infrastructureOfficielle->ch_inf_off_id === $thisInfOff->ch_inf_off_id) selected @endif>
                    {{ $thisInfOff->ch_inf_off_nom }}
                </option>
                @endforeach
                  </select>
                <span class="selectRequiredMsg">Sélectionnez un élément.</span>
              </div>
          </div>
        <!-- Debut formulaire -->
        <form action="{{ route('infrastructure.update', ['infrastructure_id' => $infrastructure->ch_inf_id]) }}" method="POST">
          @method('PUT')
          @csrf

          <input type="hidden" name="infrastructure_id"
                 value="{{ $infrastructure->ch_inf_id }}">

          @include('infrastructure.components.form')

        </form>
        <p>&nbsp;</p>
      </div>
      <div class="span6 well">
          @include('infrastructure.components.infobox')
      </div>

    </div>

    </div>

@endsection
