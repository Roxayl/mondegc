
@extends('layouts.legacy')

@section('title')
    Explorer la carte
@endsection

@section('seodescription')
    Explorez la carte du Monde GC, avec les différents calques : carte des climats, carte géographique, zones de ressources Tempérance...
@endsection

@section('styles')
    <link href="Carto/OLdefault.css" rel="stylesheet">
    <style>
    #map {
        width: 100%;
        background: #FFFFFF;
        color: rgba(0,0,0,1);
    }
    img.olTileImage {
        max-width: none;
    }
    @media (max-width: 480px) {
        #map {
            height: 360px;
        }
    }
    div.olControlPanel {
        top: 65px;
        left: 10px;
        position: absolute;
        background: none repeat scroll 0 0 rgba(255, 255, 255, 0.4);
        border-radius: 4px;
        left: 8px;
        padding: 3px;
        margin-left: 1px;
    }
    .olControlPanel div {
        display: block;
        width: 21px;
        height: 21px;
        border-radius: 4px;
        cursor: pointer;
        background-repeat: no-repeat
    }
    .helpButtonItemInactive {
        background-image: url("Carto/images/icon_legend.png");
    }
    .helpButtonItemActive {
        background-image: url("Carto/images/icon_legend_active.png");
    }
    </style>
@endsection

@section('scripts')
    <!-- CARTE -->
    <script src="{{ url('assets/js/OpenLayers.mobile.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/OpenLayers.js') }}" type="text/javascript"></script>
    {!! $mapScript !!}
    <script>
        function updateHeight() {
            var navbar_height = $('.navbar-inner').height() ;
            var window_height = window.innerHeight;
            var set_height = window_height - navbar_height + 5;
            $('#map, #info').height(set_height);
            console.log(navbar_height, window_height, set_height);
        }
        updateHeight();
        $(window).on('resize', updateHeight);
        init();
    </script>
@endsection

@section('body_attributes') 
    style="overflow: hidden;"
@endsection

@section('content')

    @parent

    <div class="corps-page">

        <header class="jumbotron subhead anchor" id="carte-generale">
          <div class="container-fluid container-carte">
            <div class="row-fluid">
              <div class="span9">
                <div id="map"></div>
              </div>
              <div class="" id="info">
                <h1>Carte du Monde</h1>
                <p>&nbsp;</p>
                <h4>Cliquez sur un élément de la carte pour en savoir plus.</h4>
                <div class="well">
                  <a href="<?= url('Page-carte.php') ?>">Afficher la liste des pays</a>
                </div>
              </div>
            </div>
          </div>
        </header>

    </div>

@endsection