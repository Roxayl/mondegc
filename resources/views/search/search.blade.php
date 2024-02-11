@inject('helperService', 'Roxayl\MondeGC\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    Recherche @isset($query) pour {{ $query }} @endisset
@endsection

@section('seodescription')
    @isset($query)  Résultats de recherche pour "{{ $query }}".
    @else Recherchez des informations sur le site du Monde GC. Page de recherche.
    @endif
@endsection

@section('styles')
<link href="{{url('SpryAssets/SpryValidationTextField.css')}}" rel="stylesheet" type="text/css">
<link href="{{url('SpryAssets/SpryValidationTextarea.css')}}" rel="stylesheet" type="text/css">
<link href="{{url('SpryAssets/SpryValidationRadio.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
<!-- SPRY ASSETS -->
<script src="{{url('SpryAssets/SpryValidationTextField.js')}}" type="text/javascript"></script>
<script src="{{url('SpryAssets/SpryValidationTextarea.js')}}" type="text/javascript"></script>
<script src="{{url('SpryAssets/SpryValidationRadio.js')}}" type="text/javascript"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="{{url('assets/js/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/Editeur.js')}}"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:50, validateOn:["change"]});

// Tabs
$('#search-tabs-container a:first').tab('show');
$('#search-tabs-container a').click(function (ev) {
    ev.preventDefault();
    $(this).tab('show');
});
</script>
@endsection

@section('content')

    @parent

    <div class="container">
    <div class="row-fluid">

        <div class="span12 corps-page">

            <div class="titre-vert">
                <h1>
                    Recherche @isset($query) pour {{ $query }} @endisset
                    @isset($results)
                        <small>{{ $results->count() }}
                            {{ \Illuminate\Support\Str::plural('résultat', $results->count()) }}
                        </small>
                    @endisset
                </h1>
            </div>

            <form action="{{ route('search') }}" method="GET">

            <div class="well"
                 style="text-align: center;
                 background: rgb(249,249,249);
                 background: linear-gradient(90deg, rgba(249,249,249,1) 0%, rgba(241,241,241,1) 10%, rgba(241,241,241,1) 90%, rgb(231, 231, 231) 100%);
                 padding: 10px;">
                <div id="sprytextfield1" class="control-group">
                    <label class="control-label" for="query">Termes de recherche</label>
                    <div class="controls">
                        <input class="input-xlarge" name="query" type="text" id="query"
                               value="@isset($query){{ $query }}@endisset" maxlength="50">
                        <span class="textfieldMaxCharsMsg">50 caractères max.</span>
                    </div>
                    </div>
                <input type="submit" class="btn btn-primary" value="Rechercher...">
            </div>

            </form>

            {!! $helperService::displayAlert() !!}

            @isset($results)
                <ul class="nav nav-tabs" id="search-tabs-container">
                @foreach($results->groupByType() as $type => $modelSearchResults)
                    <li><a href="#search_{{ $type }}">
                            {{ \Illuminate\Support\Str::ucfirst($type) }}
                            <span class="badge badge-info">{{ $modelSearchResults->count() }}</span>
                        </a></li>
                @endforeach
                </ul>
            @endisset

            @isset($results)
                <div class="tab-content">
                @foreach($results->groupByType() as $type => $modelSearchResults)
                    <div class="tab-pane" id="search_{{ $type }}">
                        <div class="well search-results">
                        <ul>
                        @foreach($modelSearchResults as $searchResult)
                            <li>
                                <a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a><br>
                                @if(!empty($searchResult->context))
                                    <small><i>{!! $searchResult->context !!}</i></small><br>
                                @endif
                                @if(!empty($searchResult->description))
                                    <small>{!! $searchResult->description !!}</small>
                                @endif
                            </li>
                        @endforeach
                        </ul>
                        </div>
                    </div>
                @endforeach
                </div>
            @endisset

            @if(isset($results) && !$results->count() && isset($query))
                <div class="well">
                    <div class="alert alert-tips">Aucun résultat trouvé dans la base de données.</div>
                </div>
            @endif

            <br>

        </div>

    </div>
    </div>

@endsection