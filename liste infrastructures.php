<?php
require_once('Connections/maconnexion.php');


//Connexion et deconnexion
include('php/log.php');

//requete liste  inffrastructures officielles

$type_classement = 'ch_inf_off_nom ASC';
if (isset($_GET['type_classement_inf'])) {
  $type_classement = $_GET['type_classement_inf'];
} 


$maxRows_liste_infra_officielles = 10;
$pageNum_liste_infra_officielles = 0;
if (isset($_GET['pageNum_liste_infra_officielles'])) {
  $pageNum_liste_infra_officielles = $_GET['pageNum_liste_infra_officielles'];
}
$startRow_liste_infra_officielles = $pageNum_liste_infra_officielles * $maxRows_liste_infra_officielles;

mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_infra_officielles = sprintf("SELECT * FROM infrastructures_officielles ORDER BY $type_classement");
$query_limit_liste_infra_officielles = sprintf("%s LIMIT %d, %d", $query_liste_infra_officielles, $startRow_liste_infra_officielles, $maxRows_liste_infra_officielles);
$liste_infra_officielles = mysql_query($query_limit_liste_infra_officielles, $maconnexion) or die(mysql_error());
$row_liste_infra_officielles = mysql_fetch_assoc($liste_infra_officielles);

if (isset($_GET['totalRows_liste_infra_officielles'])) {
  $totalRows_liste_infra_officielles = $_GET['totalRows_liste_infra_officielles'];
} else {
  $all_liste_infra_officielles = mysql_query($query_liste_infra_officielles);
  $totalRows_liste_infra_officielles = mysql_num_rows($all_liste_infra_officielles);
}
$totalPages_liste_infra_officielles = ceil($totalRows_liste_infra_officielles/$maxRows_liste_infra_officielles)-1;

$queryString_liste_infra_officielles = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_liste_infra_officielles") == false && 
        stristr($param, "totalRows_liste_infra_officielles") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_liste_infra_officielles = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_liste_infra_officielles = sprintf("&totalRows_liste_infra_officielles=%d%s", $totalRows_liste_infra_officielles, $queryString_liste_infra_officielles);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Liste des infrastructures officielles</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
	background-image: url('');
}
</style>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<!-- Color Picker  -->
<script src="assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<?php include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page">

  <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li><a href="economie.php">Économie</a> <span class="divider">/</span></li>
      <li class="active">Liste des infrastructures</li>
    </ul>

  <!-- Titre page
        ================================================== -->
  <div id="titre_institut" class="titre-bleu anchor"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
    <h1>Liste des infrastructures officielles</h1>
  </div>

<!-- Liste des infrastructures officielles
     ================================================== -->
<div class="row-fluid">
  <div class="span12">
    <!-- Bouton retour -->
  <div class="pull-right"><a href="economie.php" class="btn btn-primary">Institut d'Economie</a>
</div>
    <!-- Liste pour choix de classement -->
    <div id="select-categorie">
      <form action="liste infrastructures.php#liste-infrastructures-officielles" method="GET">
        <select name="type_classement_inf" id="type_classement_inf" onchange="this.form.submit()" class="span3">
          <option value="ch_inf_off_nom ASC" <?php if ($type_classement == 'ch_inf_off_nom ASC') {?>selected<?php } ?>>Classer par ordre alphab&eacute;tique</option>
          <option value="ch_inf_off_date DESC" <?php if ($type_classement == 'ch_inf_off_date DESC') {?>selected<?php } ?>>Classer par date de cr&eacute;ation</option>
          <option value="ch_inf_off_budget DESC" <?php if ($type_classement == 'ch_inf_off_budget DESC') {?>selected<?php } ?>>Classer par budget</option>
          <option value="ch_inf_off_Industrie DESC" <?php if ($type_classement == 'ch_inf_off_Industrie DESC') {?>selected<?php } ?>>Classer par Industrie</option>
          <option value="ch_inf_off_Commerce DESC" <?php if ($type_classement == 'ch_inf_off_Commerce DESC') {?>selected<?php } ?>>Classer par Commerce</option>
          <option value="ch_inf_off_Agriculture DESC" <?php if ($type_classement == 'ch_inf_off_Agriculture DESC') {?>selected<?php } ?>>Classer par Agriculture</option>
          <option value="ch_inf_off_Tourisme DESC" <?php if ($type_classement == 'ch_inf_off_Tourisme DESC') {?>selected<?php } ?>>Classer par Tourisme</option>
          <option value="ch_inf_off_Recherche DESC" <?php if ($type_classement == 'ch_inf_off_Recherche DESC') {?>selected<?php } ?>>Classer par Recherche</option>
          <option value="ch_inf_off_Environnement DESC" <?php if ($type_classement == 'ch_inf_off_Environnement DESC') {?>selected<?php } ?>>Classer par Environnement</option>
          <option value="ch_inf_off_Education DESC" <?php if ($type_classement == 'ch_inf_off_Education DESC') {?>selected<?php } ?>>Classer par Education</option>
        </select>
      </form>
    </div>
    <ul class="listes">
      <?php do { ?>
        <li class="row-fluid"> 
          <!-- ICONE infrastructures -->
          <div class="span2"><img src="<?php echo $row_liste_infra_officielles['ch_inf_off_icone']; ?>" alt="icone <?php echo $row_liste_infra_officielles['ch_inf_off_nom']; ?>"></div>
          <!-- contenu categorie -->
          <div class="span10 info-listes"> 
            <!-- Desc categorie -->
            <h4><?php echo $row_liste_infra_officielles['ch_inf_off_nom']; ?></h4>
            <p><?php echo $row_liste_infra_officielles['ch_inf_off_desc']; ?></p>
            <div class="row-fluid">
              <div class="span3 icone-ressources">
                <img src="assets/img/ressources/budget.png" alt="icone Budget"><p>Budget&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_budget']; ?></strong></p>
                <img src="assets/img/ressources/industrie.png" alt="icone Industrie"><p>Industrie&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Industrie']; ?></strong></p>
              </div>
              <div class="span3 icone-ressources">
                <img src="assets/img/ressources/bureau.png" alt="icone Commerce"><p>Commerce&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Commerce']; ?></strong></p>
                <img src="assets/img/ressources/agriculture.png" alt="icone Agriculture"><p>Agriculture&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Agriculture']; ?></strong></p>
              </div>
              <div class="span3 icone-ressources">
                <img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"><p>Tourisme&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Tourisme']; ?></strong></p>
                <img src="assets/img/ressources/recherche.png" alt="icone Recherche"><p>Recherche&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Recherche']; ?></strong></p>
              </div>
              <div class="span3 icone-ressources">
                <img src="assets/img/ressources/environnement.png" alt="icone Evironnement"><p>Environnement&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Environnement']; ?></strong></p>
                <img src="assets/img/ressources/education.png" alt="icone Education"><p>Education&nbsp;: <strong><?php echo $row_liste_infra_officielles['ch_inf_off_Education']; ?></strong></p>
              </div>
            </div>
          </div>
        </li>
        <?php } while ($row_liste_infra_officielles = mysql_fetch_assoc($liste_infra_officielles)); ?>
    </ul>
    <!-- Pagination de la liste -->
    <p>&nbsp;</p>
    <p class="pull-right"><small class="pull-right">de <?php echo ($startRow_liste_infra_officielles + 1) ?> &agrave; <?php echo min($startRow_liste_infra_officielles + $maxRows_liste_infra_officielles, $totalRows_liste_infra_officielles) ?> sur <?php echo $totalRows_liste_infra_officielles ?>
      <?php if ($pageNum_liste_infra_officielles > 0) { // Show if not first page ?>
        <a class="btn" href="<?php printf("%s?pageNum_liste_infra_officielles=%d%s#liste-categories", $currentPage, max(0, $pageNum_liste_infra_officielles - 1), $queryString_liste_infra_officielles); ?>"><i class=" icon-backward"></i></a>
        <?php } // Show if not first page ?>
      <?php if ($pageNum_liste_infra_officielles < $totalPages_liste_infra_officielles) { // Show if not last page ?>
        <a class="btn" href="<?php printf("%s?pageNum_liste_infra_officielles=%d%s#liste-categories", $currentPage, min($totalPages_liste_infra_officielles, $pageNum_liste_infra_officielles + 1), $queryString_liste_infra_officielles); ?>"> <i class="icon-forward"></i></a>
        <?php } // Show if not last page ?>
      </small> </p>
  <p>&nbsp;</p>
</div>
</section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($liste_infra_officielles);
?>