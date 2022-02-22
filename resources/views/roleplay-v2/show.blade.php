@inject('helperService', 'App\Services\HelperService')

@extends('layouts.legacy')

@section('title')
    {{ $roleplay->name }}
@endsection

@section('styles')
    <style>
        /* Content */
        .content {
            background-color: white;
            padding: 10px;
            margin: 0em auto 1em;
            width: 85%;
            transition: 0.5s ease-in-out;
            border-bottom: solid 2px black;
        }

        .content:hover {
            box-shadow: 0 8px 9px -5px rgba(0, 0, 0, 0.1), 0 15px 22px 2px rgba(0, 0, 0, 0.04), 0 6px 28px 5px rgba(0, 0, 0, 0.1);
            transition: 0.2s ease-in-out;
            height: 100%;
            border-width: 5px;
        }

        /* The "show" class is added to the filtered elements */
        .show {
            display: block;
        }

        /* Style the buttons */
        .btn {
            border: none;
            outline: none;
            padding: 0.2em 1em;
            background-color: white;
            cursor: pointer;
            margin-top: 1em;
            box-shadow: none !important; /* à supprimer dans le ptn de */
            text-shadow: none !important; /* Bootstrap de m*rde ptn */
        }

        .btn:hover {
            background-color: #ddd;
        }

        .btn.active {
            background-color: #fff;
            color: #182b45;
            border-width: 100px;
            font-weight: bolder;
        }

        .collapsible {
            color: black;
            cursor: pointer;
            padding: 1em;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            animation: 5s ease-in-out;
        }

        .active, .collapsible:hover {
            background-color: #c8c8c8;
            animation: 5s ease-in-out;
        }

        .colcontent {
            padding: 0em 1em 1em;
            display: none;
            overflow: hidden;
            margin: -1em 2em 1em;
        }


        .en-cours {
            animation: glow 1s ease-in-out infinite alternate;
            padding: 0.1em 0.4em;
        }

        @-webkit-keyframes glow {
            from {
                background-color: crimson;
            }

            to {
                background-color: black;
            }
        }

        #more {
            display: none;
        }
    </style>
    <style>
        .overlay {
            height: 0;
            width: 100%;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-image: url({{ url('assets/img/fond-header-blanc.jpg') }});
            background-attachment: fixed;
            color: black;
            filter: invert(100%);;
            overflow-y: hidden;
            transition: 0.5s;
        }

        .overlay-content {
            position: relative;
            top: 25%;
            width: 100%;
            text-align: center;
            margin-top: 30px;
        }

        .overlay a {
            padding: 8px;
            text-decoration: none;
            font-size: 36px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .overlay a:hover, .overlay a:focus {
            color: #f1f1f1;
        }

        .overlay .closebtn {
            position: absolute;
            top: 20px;
            right: 45px;
            font-size: 60px;
        }

        @media screen and (max-height: 450px) {
            .overlay {
                overflow-y: auto;
            }

            .overlay a {
                font-size: 20px
            }

            .overlay .closebtn {
                font-size: 40px;
                top: 15px;
                right: 35px;
            }
        }

        /* The "show" class is added to the filtered elements */
        .show {
            display: block;
        }

        /* Clear floats after rows */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
    <style>
        .row {
            margin: 10px -16px;
        }

        /* Add padding BETWEEN each column */
        .row,
        .row > .column {
            padding: 8px;
        }

        /* Create three equal columns that floats next to each  other */
        .column {
            display: none; /* Hide all elements by default */
        }

        /* Clear floats after rows */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }


        /* The "show" class is added to the filtered elements */
        .show {
            display: block;
        }

    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../assets/js/Editeur.js"></script>


    <script>
        filterSelection("all")

        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("column");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                w3RemoveClass(x[i], "show");
                if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
            }
        }

        function w3AddClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {
                    element.className += " " + arr2[i];
                }
            }
        }

        function w3RemoveClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                while (arr1.indexOf(arr2[i]) > -1) {
                    arr1.splice(arr1.indexOf(arr2[i]), 1);
                }
            }
            element.className = arr1.join(" ");
        }


        // Add active class to the current button (highlight it)
        var btnContainer = document.getElementById("myBtnContainer");
        var btns = btnContainer.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function () {
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";
            });
        }
    </script>
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var colcontent = this.nextElementSibling;
                if (colcontent.style.display === "block") {
                    colcontent.style.display = "none";
                } else {
                    colcontent.style.display = "block";
                }
            });
        }
    </script>
@endsection

@section('body_attributes') data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="800" @endsection

@section('content')

    @parent
    <!-- Navbar
        ================================================== -->
    <!-- Subhead
    ================================================== -->
    <header class="jumbotron subhead anchor"
            style="height: 700px; background-image: url(https://www.francetvinfo.fr/image/7611f5nhe-1d6b/1500/843/26418363.jpg);background-attachment:center">
    </header>
    <div class="container">

        <!-- Docs nav
          ================================================== -->
        <div class="row-fluid">
            <div class="span3 bs-docs-sidebar">
                <ul class="nav nav-list bs-docs-sidenav">
                    <li class="row-fluid"><a href="#info-institut">
                            <p><strong>Crise russo-ukrainienne de 2021-2022</strong></p>
                        </a></li>
                    <li><a href="#presentation">Pr&eacute;sentation</a></li>
                    <li><a href="#evenements">Déroulé des événements</a></li>
                    <li><a href="#direct">Suivre en direct</a></li>
                </ul>
            </div>
            <!-- END Docs nav
            ================================================== -->

            <!-- DEBUT DU CONTENU DE LA PAGE-->
            <div class="span9 corps-page" style="margin-top: -10em; z-index: 1; position: relative;">

                <div style="padding: 2em;">
                    <div class="en-cours"
                         style="padding: 0.5em 1em;margin: -3em -0.8em auto;background: crimson;width:95px;color: white;scale: 75%;">
                        En cours
                    </div>
                    <h1 style="color: black;">Crise russo-ukrainienne de 2021-2022<img style="all: initial;"
                                                                                       src="https://i11.servimg.com/u/f11/18/33/87/18/edit-l10.png">
                    </h1>
                    <p>La <b>crise russo-ukrainienne de 2021-2022</b> est une crise internationale provoquée par la
                        Russie
                        qui avive les craintes d'une invasion de l'Ukraine. La Russie déploie depuis novembre 2021
                        d'importantes forces armées près de sa frontière avec l'Ukraine ainsi qu'en Biélorussie et en
                        mer
                        Noire. Les enjeux de cette crise ne sont pas que régionaux puisque la Russie transmet aux
                        États-Unis
                        et à l'OTAN en décembre 2021 un projet d'accord dans lequel elle demande qu'ils s'engagent à ne
                        pas
                        élargir l'OTAN à l'Ukraine et qu'ils retirent leurs forces militaires des pays issus de l'URSS
                        et du
                        bloc de l'Est européen.</p>

                    <div style="border-top: solid 1px #cacaca; padding: 1em; margin-top: 2em; display: flex;">
                        <div class="span6">Responsable du RP :<br>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f3/Flag_of_Russia.svg"
                                 width="18px">
                            Russie
                        </div>
                        <div>En cours depuis le 3 mars 2021
                            <p style="font-size: 12px;">(11 mois et 5 jours)</p>
                        </div>
                    </div>
                    <div class="accordion-group" style="border: none;">
                        <div class="accordion-heading" style=""><a class="accordion-toggle collapsed"
                                                                   data-toggle="collapse"
                                                                   href="#collapseparticipation"
                                                                   style="color: #0d911f;border: 1px solid;margin: auto;">Ce
                                RP est libre à la participation</a>
                            <div id="collapseparticipation" class="accordion-body collapse" style="height: auto;">
                                <div class="accordion-inner"
                                     style="border-left: 1px solid #0d911f;padding-top: 1em; border-top: none;">Chaque
                                    pays
                                    peut ajouter librement ses événements dans ce RP, même si cela n'exclu pas une
                                    modération après coup par les responsables du RP.<br><br>
                                    Aujourd'hui, <b>4 acteurs participent</b> à ce RP :
                                    <ul style="column-count: 4;">
                                        <li>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                 width="18px"> Ukraine
                                        </li>
                                        <li>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f3/Flag_of_Russia.svg"
                                                 width="18px"> Russie
                                        </li>
                                        <li>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b7/Flag_of_Europe.svg"
                                                 width="18px"> UE
                                        </li>
                                        <li>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg?uselang=fr"
                                                 width="18px"> États-Unis
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Déroulé des événements
              ================================================== -->
                <section>
                    <div class="titre-bleu anchor" id="evenements">
                        <h1>Déroulé des événements</h1>
                    </div>
                    <div style="padding: 1em 2em;">
                        Ce RP est découpé pour le moment en 3 chapitres :
                        <div style="padding: 1em 0em;">
                            <a href="#chap1">
                                <div class="infra-well" style="border:solid 1px #cacaca;color: #182b45;"><h4>7 ans après
                                        l'annexion de la Crimée et le début de la guerre au Dombass</h4>
                                    <p style="font-size: 10px;margin-top: -1em;">CHAPITRE 1</p>
                                </div>
                            </a>

                            <a href="#chap2">
                                <div class="infra-well" style="border:solid 1px #cacaca;color: #182b45;"><h4>La course
                                        contre-la-montre diplomatique</h4>
                                    <p style="font-size: 10px;margin-top: -1em;">CHAPITRE 2</p>
                                </div>
                            </a>

                            <div class="infra-well" style="border:solid 1px #cacaca;">
                                <div class="en-cours"
                                     style="padding: 0.5em 1em;margin: -2em -1.2em auto;width: 95px;color: white;scale: 58%;">
                                    En cours
                                </div>
                                <h4>L'engrenage ?</h4>
                                <p style="font-size: 10px;margin-top: -1em;">CHAPITRE 3</p>
                            </div>
                        </div>

                        <div>
                            <button type="button" class="collapsible">Effectuer une recherche filtrée</button>
                            <div class="colcontent">
                                <p></p>

                                <div>
                                    <div id="myBtnContainer">
                                        <button class="btn active" onclick="filterSelection('all')"> Tous les thèmes
                                        </button>
                                        <button class="btn" onclick="filterSelection('militaire')"
                                                style="border-left: solid 5px red;">Conflit militaire
                                        </button>
                                        <button class="btn" onclick="filterSelection('diplomatie')"
                                                style="border-left: solid 5px blue;">Diplomatie
                                        </button>
                                        <button class="btn" onclick="filterSelection('separatisme')"
                                                style="border-left: solid 5px green;">Territoires séparatistes
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <div id="myBtnContainer">
                                        <button class="btn active" onclick="filterSelection('all')"> Tous les acteurs
                                        </button>
                                        <button class="btn" onclick="filterSelection('ukraine')"><img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                    width="18px"> Ukraine
                                        </button>
                                        <button class="btn" onclick="filterSelection('russie')"><img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/f/f3/Flag_of_Russia.svg"
                                                    width="18px"> Russie
                                        </button>
                                        <button class="btn" onclick="filterSelection('ue')"><img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/b/b7/Flag_of_Europe.svg"
                                                    width="18px"> UE
                                        </button>
                                        <button class="btn" onclick="filterSelection('usa')"><img
                                                    src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg"
                                                    width="18px"> États-Unis
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Portfolio Gallery Grid -->
                            <div class="row" style="margin: 0;">

                                <div id="chap2"
                                     style="background: #cacaca;width: 108%;margin: 0px 0px -10px -32px;padding: 1em 2em 1em;position: sticky;top: 21px;">
                                    <div style="background: white;padding: 0.5em;margin: 0em 0em -11px;font-size: 11px;width: 74px;">
                                        CHAPITRE 2
                                    </div>
                                    <h2 style="color: #182b45;">La course contre-la-montre diplomatique</h2>
                                    <div style="margin-top: -10px;">Du 21 décembre 2021 au 17 février 2022</div>
                                </div>
                                <div style="background: #cacaca;width: 108%;margin: 0px 0px -10px -32px;padding: 1em 2em 1em;">
                                    <div>Alors que les menaces militaires se font de plus en plus précises, l'Occident
                                        tente
                                        d'établir une médiation avec le Kremlin pour tenter d'éviter l'affrontement
                                        militaire... Mais les intensions réelles du pouvoir russe questionnent.
                                    </div>
                                </div>
                                <div style="background: #cacaca;width: 108%;margin: 0px 0px -45px -32px; height: 60px;"></div>


                                <div class="column diplomatie ukraine" id="fait1">
                                    <div class="content" style="border-color: blue;">
                                        <div><h4>
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                     width="18px"> Zelensky appelle au calme</h4>
                                            Le président ukrainien Volodymyr Zelensky a appelé l'Occident à ne pas créer
                                            de
                                            « panique » dans son pays face à une éventuelle invasion russe, ajoutant que
                                            les
                                            avertissements constants d'une menace « imminente » d'invasion mettent
                                            l'économie de l'Ukraine en danger. Zelensky a déclaré que « nous ne voyons
                                            pas
                                            une plus grande escalade » qu'au début de 2021, lorsque le renforcement de
                                            l'armée russe a commencé.
                                            <div style="font-size: 11px;margin: 6px 0px 0px;">
                                                <a href="#direct"><img
                                                            title="Cliquez pour copier le lien précis de cet événement"
                                                            src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                            width="10px"></a> • 28 janvier 2022
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column militaire ukraine">
                                    <div class="content" style="border-color: red;">
                                        <h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                 width="18px"> Un obus frappe une école primaire ukrainienne</h4>
                                        <p> À quelques kilomètres de la province du Donbass, tenue par des séparatistes
                                            pro-russes, une école a été bombardée en Ukraine, jeudi 17 février. Ces
                                            derniers
                                            disent avoir riposté à des tirs de l’armée ukrainienne. Cela risque-t-il de
                                            déclencher une guerre ?</p>
                                        <div style="font-size: 11px;margin: 6px 0px 0px;">
                                            <a href="#direct"><img
                                                        title="Cliquez pour copier le lien précis de cet événement"
                                                        src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                        width="10px"></a> • 17 février 2022 • <a
                                                    href="https://www.francetvinfo.fr/monde/europe/manifestations-en-ukraine/crise-en-ukraine-ce-que-l-on-sait-du-bombardement-d-une-ecole-dans-la-region-du-donbass_4968308.html"
                                                    style="color:#101010">Source</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="column russie">
                                    <div class="content">
                                        <div><h4>
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f3/Flag_of_Russia.svg"
                                                     width="18px"> Zelensky appelle au calme</h4>
                                            Le président ukrainien Volodymyr Zelensky a appelé l'Occident à ne pas créer
                                            de
                                            « panique » dans son pays face à une éventuelle invasion russe, ajoutant que
                                            les
                                            avertissements constants d'une menace « imminente » d'invasion mettent
                                            l'économie de l'Ukraine en danger. Zelensky a déclaré que « nous ne voyons
                                            pas
                                            une plus grande escalade » qu'au début de 2021, lorsque le renforcement de
                                            l'armée russe a commencé
                                        </div>
                                        <div style="font-size: 11px;margin: 6px 0px 0px;">
                                            <a href="#direct"><img
                                                        title="Cliquez pour copier le lien précis de cet événement"
                                                        src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                        width="10px"></a> • 17 février 2022
                                        </div>
                                    </div>
                                </div>

                                <div class="column usa militaire">
                                    <div class="content" style="border-color:red;display:flex;">
                                        <div>
                                            <h4>
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg"
                                                     width="18px"> Joe Biden affirme être "convaincu" que Vladimir
                                                Poutine a
                                                "pris la décision" d'attaquer l'Ukraine </h4>
                                            <div class="span7" style="margin: 0px 1em 0em 0em;width:62%;">"Je suis
                                                convaincu
                                                qu'il a pris la décision." Joe Biden s'est dit vendredi 18 février
                                                "convaincu" que le président russe Vladimir Poutine avait "pris la
                                                décision"
                                                d'envahir l'Ukraine, ajoutant cependant qu'il n'était "pas trop tard"
                                                pour
                                                la diplomatie.<br>"Nous avons des raisons de penser que les forces
                                                russes
                                                ont l'intention d'attaquer l'Ukraine (...) dans les prochains jours", a
                                                déclaré le président américain dans une allocution depuis la Maison
                                                Blanche.
                                                "Je suis convaincu qu'il a pris la décision. Nous avons des raisons de
                                                le
                                                penser". "Nous pensons qu'ils prendront Kiev pour cible, une ville de
                                                2,8
                                                millions d'innocents", a-t-il ajouté.<br><br>Tant qu'une invasion ne
                                                s'est
                                                pas produite, "la diplomatie est toujours une possibilité", a-t-il
                                                ajouté,
                                                soulignant que le chef de la diplomatie américaine Antony Blinken devait
                                                rencontrer son homologue russe Sergueï Lavrov jeudi en Europe. Si la
                                                Russie
                                                envahit l'Ukraine d'ici là, elle aura "claqué la porte à la diplomatie",
                                                a
                                                prévenu le président américain. Joe Biden a également accusé Moscou de
                                                mener
                                                une campagne de désinformation, notamment en accusant Kiev de préparer
                                                une
                                                attaque contre la Russie, pour trouver un prétexte d'envahir son voisin.<br><br>"Il
                                                n'y a tout simplement pas de preuve pour corroborer ces assertions et
                                                cela
                                                échappe à toute logique de penser que les Ukrainiens choisiraient le
                                                moment
                                                où ils ont 150 000 soldats [russes] déployés à leurs frontières pour
                                                choisir
                                                l'escalade dans ce conflit qui dure depuis des années", a-t-il noté.
                                                <div style="font-size: 11px;margin: 6px 0px 0px;">
                                                    <a href="#direct"><img
                                                                title="Cliquez pour copier le lien précis de cet événement"
                                                                src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                                width="10px"></a> • 18 février 2022 • <a
                                                            href="https://www.francetvinfo.fr/pictures/3SJpPl3TFpppex0V764zWtzlWc0/0x173:4560x2736/944x531/filters:format(webp)/2022/02/18/phpIU3klU.jpg"
                                                            style="color:#101010">Source</a>
                                                </div>
                                            </div>
                                            <div class="span4" title="Joe Biden. Photo AFP"
                                                 style="background-image:url('https://images.prabhasakshi.com/2022/2/joe-biden_large_1322_122.jpeg'); max-width: 100%; height: 80%; background-size: cover; background-position: center;"></div>
                                        </div>
                                    </div>


                                    <div class="column ukraine separatisme">
                                        <div class="content" style="border-color:green;">
                                            <div><h4>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                         width="18px"> Le Donbass en état d'alerte maximale</h4>
                                                Deux soldats ukrainiens ont perdu la vie dans le cadre du conflit qui
                                                oppose
                                                Kiev à Moscou, samedi 19 février. La tension monte dans le Donbass, où
                                                certains civils pro-russes quittent la zone, tandis que d'autres se
                                                préparent en cas d'affrontements.<br>Les autorités séparatistes ont
                                                annoncé
                                                une évacuation de masse des femmes, enfants et des personnes âgées. Des
                                                bus
                                                ont été spécialement affrétés. Le Donbass est déjà en guerre depuis 8
                                                ans.
                                                Les habitants qui le quittent ne paniquent pas, et vont majoritairement
                                                rejoindre des amis ou de la famille. D'autres font le chemin inverse. À
                                                100
                                                km de là, à Donetsk, des haut-parleurs diffusent des conseils de
                                                prudence.
                                                La mobilisation générale a été annoncée.
                                            </div>
                                            <div style="font-size: 11px;margin: 6px 0px 0px;">
                                                <a href="#direct"><img
                                                            title="Cliquez pour copier le lien précis de cet événement"
                                                            src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                            width="10px"></a> • 19 février 2022 • <a
                                                        href="https://www.francetvinfo.fr/monde/russie/vladimir-poutine/crise-en-ukraine-le-donbass-en-etat-d-alerte-maximale_4970931.html"
                                                        style="color:#101010">Source</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column diplomatie ukraine">
                                        <div class="content" style="border-color:blue;display:flex;">
                                            <div><h4>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                         width="18px"> Le président ukrainien propose une rencontre avec
                                                    Vladimir Poutine</h4>
                                                <div class="span7" style="margin: 0px 1em 0em 0em;width: 62%;">Un appel
                                                    à la
                                                    discussion au moment où les craintes d'une invasion russe en Ukraine
                                                    sont au plus haut. Le président ukrainien Volodymyr Zelensky a
                                                    proposé,
                                                    samedi 19 février, une rencontre avec son homologue russe Vladimir
                                                    Poutine. "Je ne sais pas ce que le président russe veut, voilà
                                                    pourquoi
                                                    je propose qu'on se rencontre", a-t-il déclaré à la Conférence sur
                                                    la
                                                    sécurité de Munich. Quelques heures auparavant, le chef de la
                                                    diplomatie
                                                    avait de son côté déclaré que l'Ukraine se préparait à "tous les
                                                    scénarios possibles".
                                                    <div style="font-size: 11px;margin: 6px 0px 0px;">
                                                        <a href="#direct"><img
                                                                    title="Cliquez pour copier le lien précis de cet événement"
                                                                    src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                                    width="10px"></a> • 19 février 2022 • <a
                                                                href="https://www.francetvinfo.fr/monde/europe/manifestations-en-ukraine/direct-ukraine-kiev-et-les-separatistes-prorusses-s-accusent-de-nouvelles-attaques-le-chef-separatiste-de-donetsk-proclame-la-mobilisation-generale_4970295.html"
                                                                style="color:#101010">Source</a>
                                                    </div>
                                                </div>
                                                <div class="span4"
                                                     title="Le président ukrainien, Volodymyr Zelensky, s'exprime à la Conférence sur la sécurité de Munich (Allemagne), le 19 février 2022. (THOMAS KIENZLE / AFP)"
                                                     style="background-image:url('https://www.francetvinfo.fr/image/7611gcjxg-537b/770/433/26438154.jpg'); max-width: 100%; height: 80%; background-size: cover; background-position: center;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="chap3"
                                         style="background: #cacaca;width: 108%;margin: 0px 0px -10px -32px;padding: 1em 2em 1em;position: sticky;top: 21px;">
                                        <div style="background: white;padding: 0.5em;margin: 0em 0em -11px;font-size: 11px;width: 74px;">
                                            CHAPITRE 3
                                            <div class="en-cours"
                                                 style="padding: 0.5em 1em;margin: -2.3em 7em 0em;background: crimson;width:66px;color: white;/*! scale: 75%; */position: absolute;">
                                                En cours
                                            </div>
                                        </div>
                                        <h2 style="color: #182b45;">L'engrenage ?</h2>
                                        <div style="margin-top: -10px;">Depuis le 17 février 2022</div>
                                    </div>
                                    <div style="background: #cacaca;width: 108%;margin: 0px 0px -10px -32px;padding: 1em 2em 1em;">
                                        <div>Le secrétaire américain à la Défense, Lloyd Austin, a estimé que les
                                            troupes
                                            russes s'apprêtaient à "se déployer" et à "frapper" l'Ukraine. Les
                                            militaires
                                            russes "se dirigent vers les positions adéquates pour être en mesure de
                                            mener
                                            une attaque", avait-il ajouté. L'Allemagne appelle quant à elle à ne "pas
                                            présumer" des décisions de Moscou.
                                        </div>
                                    </div>
                                    <div style="background: #cacaca;width: 108%;margin: 0px 0px -45px -32px; height: 60px;"></div>

                                    <div class="column ukraine separatisme">
                                        <div class="content" style="border-color:green">
                                            <div><h4>
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/49/Flag_of_Ukraine.svg"
                                                         width="18px"> Le Donbass en état d'alerte maximale</h4>
                                                Deux soldats ukrainiens ont perdu la vie dans le cadre du conflit qui
                                                oppose
                                                Kiev à Moscou, samedi 19 février. La tension monte dans le Donbass, où
                                                certains civils pro-russes quittent la zone, tandis que d'autres se
                                                préparent en cas d'affrontements.<br>Les autorités séparatistes ont
                                                annoncé
                                                une évacuation de masse des femmes, enfants et des personnes âgées. Des
                                                bus
                                                ont été spécialement affrétés. Le Donbass est déjà en guerre depuis 8
                                                ans.
                                                Les habitants qui le quittent ne paniquent pas, et vont majoritairement
                                                rejoindre des amis ou de la famille. D'autres font le chemin inverse. À
                                                100
                                                km de là, à Donetsk, des haut-parleurs diffusent des conseils de
                                                prudence.
                                                La mobilisation générale a été annoncée.
                                            </div>
                                            <div style="font-size: 11px;margin: 6px 0px 0px;">
                                                <a href="#direct"><img
                                                            title="Cliquez pour copier le lien précis de cet événement"
                                                            src="https://cdn-icons-png.flaticon.com/512/659/659999.png"
                                                            width="10px"></a> • 19 février 2022 • <a
                                                        href="https://www.francetvinfo.fr/monde/russie/vladimir-poutine/crise-en-ukraine-le-donbass-en-etat-d-alerte-maximale_4970931.html"
                                                        style="color:#101010">Source</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="infra-well"
                                         style="text-align:center;cursor: pointer; box-shadow: none !important;width: 100%;"
                                         onclick="document.getElementById('bflat').play()">
                                        <div style="background:#ff4e00;color: white;height: 40px;width: 40px;border-radius: 20px;font-size: 35px;font-family: initial;padding-top: 9px;margin: auto;">
                                            +
                                        </div>
                                        <div><h4>Ajouter un nouvel événement</h4></div>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </section>

                <!-- Liens vers les autres plateformes
              ================================================== -->
                <section>
                    <div class="titre-bleu anchor" id="direct">
                        <h1>Le RP en direct</h1>
                    </div>

                    <div style="padding: 2em;"><p>Suivez ce RP en temps réel sur les autres plateformes de GC :</p>
                        <div style="display: flex;margin-top: 3em;">
                            <div class="span6"><h4>Le forum</h4></div>
                            <div class="span5">
                                <iframe src="https://squirrel.roxayl.fr/site/export.php?filname=search&filid=Poles&w=400&h=210&b=1&a=small&l=20&head=top&color=gcbleu&desc=&imgprev"
                                        width="393" height="210"
                                        style="border:solid #1d345e; border-width:0 0 1px 0; overflow:hidden;"
                                        scrolling="no"></iframe>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
            <!-- END CONTENT
            ================================================== -->
        </div>
    </div>

    <audio id="bflat"
           src="https://media.radiofrance-podcast.net/podcast09/22129-01.06.2021-ITEMA_22685434-2021X44337E0139-21.mp3"></audio>

@endsection
