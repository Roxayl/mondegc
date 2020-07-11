@inject('navbarProvider', 'App\Services\LegacyPageService')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Monde GC - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Le Monde GC rassemble une communauté de joueurs du site Génération City qui ont souhaité s'unir pour construire leur propre monde et développer une nouvelle expérience de jeu.">
    <link href="{{URL::to('assets/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{URL::to('assets/css/bootstrap-responsive.css')}}" rel="stylesheet">
    <link href="{{URL::to('assets/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::to('assets/css/GenerationCity.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
    <!-- TemplateEndEditable -->
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    <!--[if gte IE 9]>
      <style type="text/css">
        .gradient {
           filter: none;
        }
      </style>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="{{URL::to('assets/ico/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    @yield('styles')
</head>

<body @yield('body_attributes')>
    <!-- Navbar
    ================================================== -->
    {!! $navbarProvider::navbar() !!}

    @yield('content')

    <div class="modal container fade" id="myModal"></div>

    <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
      <div class="pull-center">
        <p class="liens-rapides"><a href="#">haut de page</a></p>
      </div>
      <div>
        <ul class="pull-left liens-rapides">
          <li><a href="back/Haut-Conseil.php">Conseil de l'OCGC</a></li>
          @if (Auth::check())
          <li><a href="{{ url("index.php?doLogout=true") }}">D&eacute;connexion</a></li>
          @else
          <li><a href="{{url('connexion.php')}}">Connexion</a></li>
          @endif

          <li><a href="{{url('participer.php#charte')}}">Charte</a></li>
          <li><a href="https://www.generation-city.com/">G&eacute;n&eacute;ration City</a></li>
          <li><a href="https://www.forum-gc.com/">Forum</a></li>
        </ul>
        <a href="https://www.generation-city.com/"><img src="assets/img/2019/logoGC-small.png"></a>
        <div class="copyright">
          <p>Copyright &copy; G&eacute;n&eacute;ration-City - 2019 </p>
          <p>Tous droits r&eacute;serv&eacute;s - Version 2</p>
        </div>
      </div>
      </div>
    </footer>

    <script src="{{url('assets/js/jquery.js')}}"></script>
    <script src="{{url('assets/js/bootstrap.js')}}"></script>
    <script src="{{url('assets/js/bootstrap-affix.js')}}"></script>
    <script src="{{url('assets/js/application.js')}}"></script>
    <script src="{{url('assets/js/bootstrap-scrollspy.js')}}"></script>
    <script src="{{url('assets/js/bootstrapx-clickover.js')}}"></script>
    <script type="text/javascript">
          $(function() {
              $('[rel="clickover"]').clickover();})
    </script>
    <!-- MODAL -->
    <script src="{{url('assets/js/bootstrap-modalmanager.js')}}"></script>
    <script src="{{url('assets/js/bootstrap-modal.js')}}"></script>

    @yield('scripts')

</body>
</html>