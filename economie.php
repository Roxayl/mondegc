<?php
if(!isset($mondegc_config['front-controller'])) require_once('Connections/maconnexion.php');


//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 5;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete somme ressources pour chaque pays 
$maxRows_somme_ressources = 10;
$pageNum_somme_ressources = 0;
if (isset($_GET['pageNum_somme_ressources'])) {
  $pageNum_somme_ressources = $_GET['pageNum_somme_ressources'];
}
$startRow_somme_ressources = $pageNum_somme_ressources * $maxRows_somme_ressources;

$cat = "";
if (isset($_GET['cat'])) {
	if ($_GET['cat'] == "") {
	$cat = 'commerce';
} else {
  $cat = $_GET['cat'];
} } else {
  $cat = 'commerce';
} 

$query_somme_ressources = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, 
(SELECT SUM(ch_inf_off_budget) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_budget_carte 
+ COALESCE((SELECT SUM(ch_mon_cat_budget) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
AS budget,
(SELECT SUM(ch_inf_off_Industrie) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_industrie_carte
+ COALESCE((SELECT SUM(ch_mon_cat_industrie) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
AS industrie,
(SELECT SUM(ch_inf_off_Commerce) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_commerce_carte
+ COALESCE((SELECT SUM(ch_mon_cat_commerce) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS commerce,
(SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_agriculture_carte
+ COALESCE((SELECT SUM(ch_mon_cat_agriculture) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS agriculture,
(SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_tourisme_carte
+ COALESCE((SELECT SUM(ch_mon_cat_tourisme) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS tourisme,
(SELECT SUM(ch_inf_off_Recherche) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_recherche_carte
+ COALESCE((SELECT SUM(ch_mon_cat_recherche) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS recherche,
(SELECT SUM(ch_inf_off_Environnement) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_environnement_carte
+ COALESCE((SELECT SUM(ch_mon_cat_environnement) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS environnement,
(SELECT SUM(ch_inf_off_Education) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_education_carte
+ COALESCE((SELECT SUM(ch_mon_cat_education) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS education
FROM pays WHERE ch_pay_publication = 1 ORDER BY $cat DESC");
//echo $query_somme_ressources; exit;
$query_limit_somme_ressources = sprintf("%s LIMIT %d, %d", $query_somme_ressources, $startRow_somme_ressources, $maxRows_somme_ressources);
$somme_ressources = mysql_query($query_limit_somme_ressources);
$row_somme_ressources = mysql_fetch_assoc($somme_ressources);

$all_somme_ressources = mysql_query($query_somme_ressources);
$row_all_somme_ressources = mysql_fetch_assoc($all_somme_ressources);
$totalRows_somme_ressources = mysql_num_rows($all_somme_ressources);
$totalPages_somme_ressources = ceil($totalRows_somme_ressources/$maxRows_somme_ressources)-1;

$queryString_somme_ressources = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_somme_ressources") == false && 
        stristr($param, "totalRows_somme_ressources") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_somme_ressources = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_somme_ressources = sprintf("&totalRows_somme_ressources=%d%s", $totalRows_somme_ressources, $queryString_somme_ressources);



//calcul ressources mondiales 

$query_somme_ressources_mondiales = sprintf("SELECT
(SELECT SUM(ch_inf_off_budget) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_budget_carte 
+ COALESCE((SELECT SUM(ch_mon_cat_budget) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
AS budget,
(SELECT SUM(ch_inf_off_Industrie) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_industrie_carte
+ COALESCE((SELECT SUM(ch_mon_cat_industrie) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
AS industrie,
(SELECT SUM(ch_inf_off_Commerce) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_commerce_carte
+ COALESCE((SELECT SUM(ch_mon_cat_commerce) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS commerce,
(SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_agriculture_carte
+ COALESCE((SELECT SUM(ch_mon_cat_agriculture) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS agriculture,
(SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_tourisme_carte
+ COALESCE((SELECT SUM(ch_mon_cat_tourisme) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS tourisme,
(SELECT SUM(ch_inf_off_Recherche) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_recherche_carte
+ COALESCE((SELECT SUM(ch_mon_cat_recherche) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS recherche,
(SELECT SUM(ch_inf_off_Environnement) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_environnement_carte
+ COALESCE((SELECT SUM(ch_mon_cat_environnement) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS environnement,
(SELECT SUM(ch_inf_off_Education) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)
+ ch_pay_education_carte
+ COALESCE((SELECT SUM(ch_mon_cat_education) FROM monument_categories
   INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
   INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_paysID = ch_pay_id), 0)
 AS education
FROM pays WHERE ch_pay_publication = 1 GROUP BY ch_pay_id ORDER BY %s DESC", GetSQLValueString($cat, "text"));
$somme_ressources_mondiales = mysql_query($query_somme_ressources_mondiales, $maconnexion) or die(mysql_error());
$row_somme_ressources_mondiales = mysql_fetch_assoc($somme_ressources_mondiales);
$totalRows_somme_ressources_mondiales = mysql_num_rows($somme_ressources_mondiales);

do {
$tot_mon_budget = $tot_mon_budget + $row_somme_ressources_mondiales['budget'];
$tot_mon_industrie = $tot_mon_industrie + $row_somme_ressources_mondiales['industrie'];
$tot_mon_commerce = $tot_mon_commerce + $row_somme_ressources_mondiales['commerce'];
$tot_mon_agriculture = $tot_mon_agriculture + $row_somme_ressources_mondiales['agriculture'];
$tot_mon_tourisme = $tot_mon_tourisme + $row_somme_ressources_mondiales['tourisme'];
$tot_mon_recherche = $tot_mon_recherche + $row_somme_ressources_mondiales['recherche'];
$tot_mon_environnement = $tot_mon_environnement + $row_somme_ressources_mondiales['environnement'];
$tot_mon_education = $tot_mon_education + $row_somme_ressources_mondiales['education'];
} while ($row_somme_ressources_mondiales = mysql_fetch_assoc($somme_ressources_mondiales));



$graph_ressources = array();
$graph_country = array();
$graph_colors = array();

do {
    $graph_ressources[] = $row_all_somme_ressources[$cat];
    $graph_country[] = $row_all_somme_ressources['ch_pay_nom'];
    $graph_colors[] = '';
} while($row_all_somme_ressources = mysql_fetch_assoc($all_somme_ressources));
mysql_data_seek($all_somme_ressources, 0);

$row_all_somme_ressources = mysql_fetch_assoc($all_somme_ressources);


?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - <?= __s($row_institut['ch_ins_nom']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
    background: linear-gradient(to right, #ffe300 0%,#ff5c00 72%);
    background-size: 200%;
}
</style>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
</script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<!-- Chart.js : génération de graphes -->
<script src="assets/js/Chart.2.7.3.bundle.js"></script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-medium jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1><?php echo $row_institut['ch_ins_nom']; ?></h1>
  </div>
</header>
<div class="container"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-institut">
          <?php if ($row_institut['ch_ins_logo']) { ?>
          <img src="<?php echo $row_institut['ch_ins_logo']; ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?php echo $row_institut['ch_ins_sigle']; ?></strong></p>
          <p><em><?php echo $row_institut['ch_ins_nom']; ?></em></p>
          </a></li>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <li><a href="#ressources">Statistiques &eacute;conomiques</a></li>
        <li><a href="#temperance">Projet Tempérance</a></li>
		<li><a href="#bourses">Bourses mondiales</a></li>
        <li><a href="#communiques">Communiqu&eacute;s officiels</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">

    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li class="active">Économie</li>
    </ul>

      <!-- Presentation
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="presentation">
          <h1>Pr&eacute;sentation</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span12">
              <?php if(!empty($row_institut['ch_ins_img'])): ?>
                <img alt="Icône de l'institut" class="pull-right" style="width: 35%; margin-left: 15px;"
                     src="<?= __s($row_institut['ch_ins_img']) ?>">
              <?php endif; ?>
              <?php echo $row_institut['ch_ins_desc'] ?>
            </div>
          </div>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span12">
          <h4>Ressources du Comité :</h4>
        <a href="liste infrastructures.php" class="btn btn-primary">Liste des infrastructures officielles</a>
            </div>
          </div>
        </div>
      </section>
      <!-- Classements ressources
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="ressources">
          <h1>Statistiques &eacute;conomiques</h1>
        </div>
       <div class="row-fluid">
       <div class="span8 well">

        <img class="token-list-eco pull-left" id="main-token-icon-eco" src="assets/img/ressources/<?= __s($cat) ?>.png" alt="icone <?= __s($cat) ?>" style="width: 50px;">
        <form id="resources-form" action="<?= DEF_URI_PATH ?>economie.php#ressources" method="GET">
          <select class="btn-large" name="cat" id="cat" onchange="$('#main-token-icon-eco').attr('src', 'https://squirrel.romukulot.fr/media/icons/ajax-loader2.gif'); setTimeout(function() { $('#resources-form').submit()}, 100);">
            <option value="">S&eacute;lectionnez une ressource</option>
            <option value="commerce" <?php if ($cat == 'commerce') {?>selected<?php } ?>>Commerce</option>
			<option value="industrie" <?php if ($cat == 'industrie') {?>selected<?php } ?>>Industrie</option>
            <option value="agriculture" <?php if ($cat == 'agriculture') {?>selected<?php } ?>>Agriculture</option>
            <option value="tourisme" <?php if ($cat == 'tourisme') {?>selected<?php } ?>>Tourisme</option>
            <option value="recherche" <?php if ($cat == 'recherche') {?>selected<?php } ?>>Recherche</option>
            <option value="environnement" <?php if ($cat == 'environnement') {?>selected<?php } ?>>Environnement</option>
            <option value="education" <?php if ($cat == 'education') {?>selected<?php } ?>>Education</option>
			<option value="budget" <?php if ($cat == 'budget') {?>selected<?php } ?>>Budget</option>
          </select>
        </form>

           <div class="chart-container">
              <canvas id="eco-chart" width="600" height="320"></canvas>
          </div>

          <script type="text/javascript">

          <?php
          $graph_colors_list = array();
          $graph_color_start = -0.250;
          for($i = 0; $i < $totalRows_somme_ressources; $i++) {
            $graph_colors_list[] = adjustBrightness(getResourceColor($cat),
                $graph_color_start);
            $graph_color_start += 0.016;
          }
          ?>

          (function($, window, Chart, document, undefined) {

            var chartColors = <?= json_encode($graph_colors_list) ?>;
            var i = 0;

            var getColor = function() {

                var length = chartColors.length;
                i++;
                var returnValue = chartColors[i];
                if(i + 1 >= length)
                    i = 0;
                return returnValue;

            };

            var colorArray = [];
            for(var j = 0; j < <?= $totalRows_somme_ressources ?>; j++){
                colorArray.push(getColor());
            }

            var ctx = $("#eco-chart");
            var ecoChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    datasets: [{
                        data: <?= json_encode($graph_ressources); ?>,
                        backgroundColor: colorArray,
                        label: "<?= $cat ?>"
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: <?= json_encode($graph_country); ?>
                },
                options: {
                    scales: {
                        xAxes: [{
                            gridLines: {
                                offsetGridLines: true
                            },
                            ticks: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            });

          })(jQuery, window, Chart, document);



          </script>

       </div>
          
        <!-- affichage ressource et somme mondiale en fonction du choix -->
        <div class="span4 well ressources">
          <p><i class="icon-globe"></i> Balance mondiale&nbsp;:</p>
          <?php if ($cat =="budget") { ?>
          <a href="#" title="Budget"><img src="assets/img/ressources/budget.png" alt="icone Budget"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_budget, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="industrie") { ?>
          <a href="#" title="Industrie"><img src="assets/img/ressources/industrie.png" alt="icone Industrie"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_industrie, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="commerce") { ?>
          <a href="#" title="Commerce"><img src="assets/img/ressources/bureau.png" alt="icone Commerce"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_commerce, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="agriculture") { ?>
          <a href="#" title="Agriculture"><img src="assets/img/ressources/agriculture.png" alt="icone Agriculture"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_agriculture, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="tourisme") { ?>
          <a href="#" title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_tourisme, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="recherche") { ?>
          <a href="#" title="Recherche"><img src="assets/img/ressources/recherche.png" alt="icone Recherche"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_recherche, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="environnement") { ?>
          <a href="#" title="Environnement"><img src="assets/img/ressources/environnement.png" alt="icone Environnement"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_environnement, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="education") { ?>
          <a href="#" title="Education"><img src="assets/img/ressources/education.png" alt="icone Education"></a>
          <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($tot_mon_education, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
        </div>
       </div>
        <!-- choix ressources  -->
        <?php  
		$rank= $startRow_somme_ressources; 
		do { 
		 $rank= $rank + 1; ?>
        <!-- Pagination liste des pays et somme des ressources en fonction du choix  -->
        <div class="well liste-ressources">
          <div class="row-fluid">
            <div class="span1">
              <h3><?php echo $rank; ?></h3>
            </div>
            <div class="span1"><a href="page-pays.php?ch_pay_id=<?php echo $row_somme_ressources['ch_pay_id']; ?>" title="lien vers la page du pays"><img src="<?php 
			if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_somme_ressources['ch_pay_lien_imgdrapeau']))
					{
					$row_somme_ressources['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_somme_ressources['ch_pay_lien_imgdrapeau']);
					}
			echo $row_somme_ressources['ch_pay_lien_imgdrapeau']; ?>"></a></div>
            <div class="span6">
              <h4><?php echo $row_somme_ressources['ch_pay_nom']; ?></h4>
            </div>
            <?php if (($cat =="budget") AND ($row_somme_ressources['budget']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Budget"><img src="assets/img/ressources/budget.png" alt="icone Budget"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['budget'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="industrie") AND ($row_somme_ressources['industrie']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Industrie"><img src="assets/img/ressources/industrie.png" alt="icone Industrie"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['industrie'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="commerce") AND ($row_somme_ressources['commerce']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Commerce"><img src="assets/img/ressources/bureau.png" alt="icone Commerce"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['commerce'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="agriculture") AND ($row_somme_ressources['agriculture']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Agriculture"><img src="assets/img/ressources/agriculture.png" alt="icone Agriculture"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['agriculture'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="tourisme") AND ($row_somme_ressources['tourisme']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['tourisme'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="recherche") AND ($row_somme_ressources['recherche']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Recherche"><img src="assets/img/ressources/recherche.png" alt="icone Recherche"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['recherche'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="environnement") AND ($row_somme_ressources['environnement']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Environnement"><img src="assets/img/ressources/environnement.png" alt="icone Environnement"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['environnement'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="education") AND ($row_somme_ressources['education']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Education"><img src="assets/img/ressources/education.png" alt="icone Education"></a> </div>
            <div class="span3">
              <h3 class="token-<?= __s($cat) ?>"><?php $chiffre_francais = number_format($row_somme_ressources['education'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } else {  ?>
            <div class="span4"><p>NC</p></div>
            <?php } ?>
          </div>
        </div>
        <?php } while ($row_somme_ressources = mysql_fetch_assoc($somme_ressources)); ?>
        <!-- Pagination liste des monuments de la categorie -->
       <small class="pull-right">de <?php echo ($startRow_somme_ressources + 1) ?> &agrave; <?php echo min($startRow_somme_ressources + $maxRows_somme_ressources, $totalRows_somme_ressources) ?> sur <?php echo $totalRows_somme_ressources ?>
            <?php if ($pageNum_somme_ressources > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_somme_ressources=%d%s#ressources", $currentPage, max(0, $pageNum_somme_ressources - 1), $queryString_somme_ressources); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_somme_ressources < $totalPages_somme_ressources) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_somme_ressources=%d%s#ressources", $currentPage, min($totalPages_somme_ressources, $pageNum_somme_ressources + 1), $queryString_somme_ressources); ?>"> <i class="icon-forward"></i></a>
        <?php } // Show if not last page ?></small>
            <div class="clearfix"></div>
      </section>
            <!-- Temperance
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="temperance">
          <h1>Projet Tempérance</h1>
        </div>
        <div class="well">
              <p>Le projet Tempérance est proposé, comme son nom l'indique, pour tempérer les données économiques et générales retranscrites par les membres du forum GC et du site du même nom. Le but est d'annoncer des données économiques et sociales en adéquation avec les villes ou les pays qui s'y rattachent et qui sont présentés. Cette idée permettrait également de lancer une dynamique sur les deux sites en lançant des challenges et des recherches créatives aux membres pour justifier leurs chiffres. En d'autres termes, ce projet n'est pas conçu pour dévaloriser les conceptions des membres et ne remet pas en cause leur choix de création. Il apporte simplement une conception nouvelle créant une homogénéité des chiffres.</p>
              <p><a class="btn btn-primary" href="Projet-temperance.php">En savoir plus</a> <a class="btn btn-primary" href="back/Temperance_jugement.php">Salle de jugement</a></p>
        </div>
      </section>
	  <!-- Bourses mondiales
    ================================================== -->
        <!--
      <section>
        <div class="titre-bleu anchor" id="bourses">
          <h1>Bourses mondiales</h1>
        </div>
        <div class="well">
              <p>Les Bourses mondiales sont créées dans la continuité du projet Tempérance. Le but est de mettre en valeur vos ressources acquises via la mise en ligne de vos infrastrutures et via l'outil "zoning" du site GC. Mais de quelle manière ? Comment pourrez-vous faire prospérer votre Bourse ? La rubrique "En savoir plus" est à présent en ligne pour vous. Il est temps de se confronter aux défis du monde réel sur le site GC : Saurez-vous vous adapter au développement des autres pays membres ? Quels choix ferez-vous pour placer votre Bourse en tête ? Comment battrez-vous la tendance mondiale ? Bientôt, chers membres gécéens, vos propres résultats pour le projet des Bourses mondiales... </p>
              <p><a class="btn btn-primary" href="projet-bourses.php">À propos du Projet Bourses</a></p>
        </div>
      </section>
      -->
      <!-- communique officiel
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="communiques">
          <h1>Communiqu&eacute;s officiels</h1>
        </div>
        <?php 
	 $ch_com_categorie = 'institut';
	  $ch_com_element_id = $institut_id;
	  include('php/communiques.php'); ?>
      </section>
    </div>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($somme_ressources);
mysql_free_result($somme_ressources_mondiales);
?>