<?php

use Roxayl\MondeGC\Services\EconomyService;
use Illuminate\Support\Facades\Gate;

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 5;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion);
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

$selectedResource = 'budget';
if(isset($_GET['cat']) && in_array($_GET['cat'], config('enums.resources'), true)) {
    $selectedResource = $_GET['cat'];
}

// Ressources
$paysList = EconomyService::getPaysResources($selectedResource);

$graph_ressources = array();
$graph_country = array();
$graph_colors = array();

foreach($paysList as $thisPays) {
    $graph_ressources[] = $thisPays['resources'][$selectedResource];
    $graph_country[] = $thisPays['ch_pay_nom'];
    $graph_colors[] = '';
}

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
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; require('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-medium jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1><?= e($row_institut['ch_ins_nom']) ?></h1>
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
          <img src="<?= e($row_institut['ch_ins_logo']) ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?= e($row_institut['ch_ins_sigle']) ?></strong></p>
          <p><em><?= e($row_institut['ch_ins_nom']) ?></em></p>
          </a></li>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <li><a href="#ressources">Statistiques &eacute;conomiques</a></li>
        <li style="padding-left: 20px;"><a href="#ressources-instantane">Instantané</a></li>
        <li style="padding-left: 20px;"><a href="#ressources-historique">Historique</a></li>
        <li><a href="#temperance">Projet Tempérance</a></li>
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
      <div class="clearfix"></div>


      <!-- Classements ressources
    ================================================== -->
      <div class="cta-title pull-right-cta dropdown">
        <a href="#" class="btn btn-primary btn-cta" data-toggle="dropdown">
            <i class="icon-white icon-arrow-down"></i>
            Exporter</a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="<?= route('data-export.temperance-pays') ?>">Statistiques des pays (existants)<br><small>Fichier CSV - Inclut toutes les ressources</small></a></li>
        </ul>
      </div>
      <section>
        <div class="titre-bleu anchor" id="ressources">
          <h1>Statistiques &eacute;conomiques</h1>
        </div>

        <?php renderElement('institut/economy_stats',
            compact(['paysList', 'selectedResource', 'graph_ressources', 'graph_country', 'graph_colors'])
        ); ?>

        <div class="clearfix"></div>
      </section>


      <!-- Temperance
    ================================================== -->
      <section>
        <div class="titre-bleu" id="temperance">
          <h1>Projet Tempérance</h1>
        </div>
        <div class="well">
          <p>Le projet Tempérance est proposé, comme son nom l'indique, pour tempérer les données économiques et générales retranscrites par les membres du forum GC et du site du même nom. Le but est d'annoncer des données économiques et sociales en adéquation avec les villes ou les pays qui s'y rattachent et qui sont présentés. Cette idée permettrait également de lancer une dynamique sur les deux sites en lançant des challenges et des recherches créatives aux membres pour justifier leurs chiffres. En d'autres termes, ce projet n'est pas conçu pour dévaloriser les conceptions des membres et ne remet pas en cause leur choix de création. Il apporte simplement une conception nouvelle créant une homogénéité des chiffres.</p>
          <p>
            <a class="btn btn-primary" href="Projet-temperance.php">En savoir plus</a>
            <?php if(Gate::check('judgeInfrastructure',
              \Roxayl\MondeGC\Models\Infrastructure::class)): ?>
            <a class="btn btn-primary" href="<?= route('infrastructure-judge.index') ?>"
                >Salle de jugement des infrastructures</a>
            <?php endif; ?>
          </p>
        </div>
      </section>
      <div class="clearfix"></div>

      <!-- communique officiel
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="communiques">
          <h1>Communiqu&eacute;s officiels</h1>
        </div>
        <?php 
	 $ch_com_categorie = 'institut';
	  $ch_com_element_id = $institut_id;
	  require('php/communiques.php'); ?>
      </section>

    </div>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</body>
</html>