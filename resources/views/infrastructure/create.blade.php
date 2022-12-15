@php
$viewDirectory = \Roxayl\MondeGC\Models\Infrastructure
                ::getUrlParameterFromMorph(get_class($infrastructure->infrastructurable));
$viewActionVerb = 'Ajouter';
@endphp

@extends('layouts.legacy')

@section('title')
    Créer une infrastructure
@endsection

@section('seodescription')
    Créez une nouvelle infrastructure dans votre {{ $viewDirectory }}.
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
              {{ $infrastructureGroupe->nom_groupe }}
          </h3>

          <br><br>

            <!-- choix infrastructure -->
          <form action="{{ route('infrastructure.create', $infrastructure->infrastructurable->createRouteParameter($infrastructureGroupe, $infrastructureOfficielle)) }}" method="GET" id="form-infra-list"
                  class="form-inline">
            <input type="hidden" name="infrastructure_groupe_id"
                   value="{{ $infrastructureGroupe->id }}">
            <div id="spryselect1" class="control-group">
              <div class="control-label">
                  <h4 style="display: inline-block;">Choisissez votre infrastructure</h4>
                  <a href="#" rel="clickover"
                     title="Infrastructures de la liste officielle" data-content="Vous devez choisir une infrastructure dans la liste officielle. Chaque nouvelle infrastructure va modifier les valeurs de votre économie"><i class="icon-info-sign"></i></a>
              </div>
              <div class="controls">
                  <select name="infrastructure_officielle_id" id="infrastructure_officielle_id" placeholder="Rechercher une infrastructure...">
                    <option value=""></option>
                @foreach($infrastructureGroupe->infrastructuresOfficielles as $thisInfOff)
                <option value="{{ $thisInfOff->ch_inf_off_id }}"
                    @if(!is_null($infrastructureOfficielle) && $infrastructureOfficielle->ch_inf_off_id === $thisInfOff->ch_inf_off_id) selected @endif>
                    {{ $thisInfOff->ch_inf_off_nom }}
                </option>
                @endforeach
                  </select>
                <span class="selectRequiredMsg">Sélectionnez un élément.</span>
              </div>
            </div>
          </form>
        <!-- Debut formulaire -->
        <form action="{{ route('infrastructure.store') }}" method="POST">
          @csrf

          <input type="hidden" name="infrastructurable_type"
                 value="{{ request()->infrastructurable_type }}">
          <input type="hidden" name="infrastructurable_id"
                 value="{{ request()->infrastructurable_id }}">
          @if(!is_null($infrastructureOfficielle))
            <input type="hidden" name="ch_inf_off_id"
                   value="{{ $infrastructureOfficielle->ch_inf_off_id }}">
          @endif

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