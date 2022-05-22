@inject('navbarProvider', 'App\Services\LegacyPageService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Monde GC - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="
        @if(View::hasSection('seodescription'))
            @yield('seodescription')
        @else
            Le Monde GC rassemble une communauté de joueurs du site Génération City qui ont
            souhaité s'unir pour construire leur propre monde et développer une nouvelle
            expérience de jeu.
        @endif ">
    <link href="{{url('assets/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/bootstrap-responsive.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('assets/css/GenerationCity.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
    <!--[if gte IE 9]>
        <style>
            .gradient {
               filter: none;
            }
        </style>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{{url('assets/ico/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('assets/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ url('assets/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ url('assets/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ url('assets/ico/apple-touch-icon-57-precomposed.png') }}">

    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->

    @yield('styles')

    {!! Eventy::action('display.beforeHeadClosingTag') !!}
</head>

<body @yield('body_attributes')>
    <!-- Navbar
    ================================================== -->
    {!! $navbarProvider::navbar() !!}

    @yield('content')

    <div class="modal container fade" id="modal-container"></div>

    <div class="modal container modal-small fade" id="modal-container-small"></div>

    <!-- Footer
    ================================================== -->
    {!! $navbarProvider::footer() !!}

    <script src="{{mix('/js/vendor-compiled.js')}}"></script>
    <script src="{{mix('/js/application-compiled.js')}}"></script>
    <script type="text/javascript">
    (function(window, document, $, undefined) {
        $(function() {
            $('[rel="clickover"]').clickover();
        })

        /** Modal **/
        $(document).on('click', "a[data-toggle=modal]", function (e) {
            var lv_target = $(this).attr('data-target');
            var lv_url = $(this).attr('href');
            $(lv_target).load(lv_url);
        });
        $(document).on('click', '#closemodal', function(ev) {
            $(ev.target).closest('.modal').modal('hide');
        });
    })(window, document, jQuery);
    </script>

    @yield('scripts')

</body>
</html>