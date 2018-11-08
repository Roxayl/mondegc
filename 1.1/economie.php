<?php
session_start();

require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 5;
mysql_select_db($database_maconnexion, $maconnexion);
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
mysql_select_db($database_maconnexion, $maconnexion);
$query_somme_ressources = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, 
((SELECT SUM(ch_inf_off_budget) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_budget_carte ) AS budget,
(SELECT SUM(ch_inf_off_Industrie) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_industrie_carte AS industrie,
(SELECT SUM(ch_inf_off_Commerce) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_commerce_carte AS commerce,
(SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_agriculture_carte AS agriculture,
(SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_tourisme_carte AS tourisme,
(SELECT SUM(ch_inf_off_Recherche) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_recherche_carte AS recherche,
(SELECT SUM(ch_inf_off_Environnement) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_environnement_carte AS environnement,
(SELECT SUM(ch_inf_off_Education) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_education_carte AS education
FROM pays WHERE ch_pay_publication = 1 ORDER BY $cat DESC");
$query_limit_somme_ressources = sprintf("%s LIMIT %d, %d", $query_somme_ressources, $startRow_somme_ressources, $maxRows_somme_ressources);
$somme_ressources = mysql_query($query_limit_somme_ressources, $maconnexion) or die(mysql_error());
$row_somme_ressources = mysql_fetch_assoc($somme_ressources);

if (isset($_GET['totalRows_somme_ressources'])) {
  $totalRows_somme_ressources = $_GET['totalRows_somme_ressources'];
} else {
  $all_somme_ressources = mysql_query($query_somme_ressources);
  $totalRows_somme_ressources = mysql_num_rows($all_somme_ressources);
}
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
mysql_select_db($database_maconnexion, $maconnexion);
$query_somme_ressources_mondiales = sprintf("SELECT
((SELECT SUM(ch_inf_off_budget) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_budget_carte ) AS budget,
(SELECT SUM(ch_inf_off_Industrie) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_industrie_carte AS industrie,
(SELECT SUM(ch_inf_off_Commerce) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_commerce_carte AS commerce,
(SELECT SUM(ch_inf_off_Agriculture) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_agriculture_carte AS agriculture,
(SELECT SUM(ch_inf_off_Tourisme) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_tourisme_carte AS tourisme,
(SELECT SUM(ch_inf_off_Recherche) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_recherche_carte AS recherche,
(SELECT SUM(ch_inf_off_Environnement) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_environnement_carte AS environnement,
(SELECT SUM(ch_inf_off_Education) FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3 AND ch_inf_statut = 2)+ ch_pay_education_carte AS education
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


?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC- &eacute;conomie</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
	background-image: url('assets/img/bannieres-instituts/IEGC.png');
}
</style>
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('php/cartepays.php'); ?>
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
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut" >
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
      <!-- Presentation
    ================================================== -->
      <section><div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>Responsable actuel de cet Institut : Sakuro</p></div>
        <div class="titre-bleu anchor" id="presentation"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
          <h1>Pr&eacute;sentation</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
              <p><?php echo $row_institut['ch_ins_desc']; ?></p>
            </div>
            <div class="span5"><img src="<?php echo $row_institut['ch_ins_img']; ?>"></div>
          </div>
          
        </div>
        <a href="liste infrastructures.php" class="btn btn-primary">Liste des infrastructures officielles</a>
      </section>
      <!-- Classements ressources
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="ressources"> <img src="assets/img/IconesBDD/Bleu/100/eco.png">
          <h1>Statistiques &eacute;conomiques</h1>
        </div>
        <!-- affichage ressource et somme mondiale en fonction du choix -->
        <div class="span4 pull-right well ressources">
          <p><i class="icon-globe icon-white"></i> Balance mondiale&nbsp;:</p>
          <?php if ($cat =="budget") { ?>
          <a href="#" title="Budget"><img src="assets/img/ressources/Budget.png" alt="icone Budget"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_budget, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="industrie") { ?>
          <a href="#" title="Industrie"><img src="assets/img/ressources/Industrie.png" alt="icone Industrie"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_industrie, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="commerce") { ?>
          <a href="#" title="Commerce"><img src="assets/img/ressources/Bureau.png" alt="icone Commerce"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_commerce, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="agriculture") { ?>
          <a href="#" title="Agriculture"><img src="assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_agriculture, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="tourisme") { ?>
          <a href="#" title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_tourisme, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="recherche") { ?>
          <a href="#" title="Recherche"><img src="assets/img/ressources/Recherche.png" alt="icone Recherche"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_recherche, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="environnement") { ?>
          <a href="#" title="Environnement"><img src="assets/img/ressources/Environnement.png" alt="icone Environnement"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_environnement, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
          <?php if ($cat =="education") { ?>
          <a href="#" title="Education"><img src="assets/img/ressources/Education.png" alt="icone Education"></a>
          <h3><?php $chiffre_francais = number_format($tot_mon_education, 0, ',', ' '); echo $chiffre_francais; ?></h3>
          <?php } ?>
        </div>
        <!-- choix ressources  -->
        <form action="economie.php#ressources" method="GET">
          <select name="cat" id="cat" onchange="this.form.submit()">
            <option value="" <?php if ($colname_somme_ressources == NULL) {?>selected<?php } ?>>S&eacute;lectionnez une ressource</option>            
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
        <div class="clearfix"></div>
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
            <div class="span1 token-list-eco"> <a href="#" title="Budget"><img src="assets/img/ressources/Budget.png" alt="icone Budget"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['budget'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="industrie") AND ($row_somme_ressources['industrie']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Industrie"><img src="assets/img/ressources/Industrie.png" alt="icone Industrie"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['industrie'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="commerce") AND ($row_somme_ressources['commerce']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Commerce"><img src="assets/img/ressources/Bureau.png" alt="icone Commerce"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['commerce'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="agriculture") AND ($row_somme_ressources['agriculture']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Agriculture"><img src="assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['agriculture'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="tourisme") AND ($row_somme_ressources['tourisme']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Tourisme"><img src="assets/img/ressources/tourisme.png" alt="icone Tourisme"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['tourisme'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="recherche") AND ($row_somme_ressources['recherche']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Recherche"><img src="assets/img/ressources/Recherche.png" alt="icone Recherche"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['recherche'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="environnement") AND ($row_somme_ressources['environnement']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Zvironnement"><img src="assets/img/ressources/Environnement.png" alt="icone Environnement"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['environnement'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
            </div>
            <?php } elseif (($cat =="education") AND ($row_somme_ressources['education']!=NULL)) { ?>
            <div class="span1 token-list-eco"> <a href="#" title="Education"><img src="assets/img/ressources/Education.png" alt="icone Education"></a> </div>
            <div class="span3">
              <h3><?php $chiffre_francais = number_format($row_somme_ressources['education'], 0, ',', ' '); echo $chiffre_francais; ?></h3>
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
        <div class="titre-bleu anchor" id="temperance"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
          <h1>Projet Tempérance</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
              <p>Le projet Tempérance est proposé, comme son nom l'indique, pour tempérer les données économiques et générales retranscrites par les membres du forum GC et du site du même nom. Le but est d'annoncer des données économiques et sociales en adéquation avec les villes ou les pays qui s'y rattachent et qui sont présentés. Cette idée permettrait également de lancer une dynamique sur les deux sites en lançant des challenges et des recherches créatives aux membres pour justifier leurs chiffres. En d'autres termes, ce projet n'est pas conçu pour dévaloriser les conceptions des membres et ne remet pas en cause leur choix de création. Il apporte simplement une conception nouvelle créant une homogénéité des chiffres.</p>
              <p><a class="btn btn-primary" href="Projet-temperance.php">En savoir plus</a> <a class="btn btn-primary" href="back/Temperance_jugement.php">Juges</a></p>
            </div>
            <div class="span5"><img src="http://img15.hostingpics.net/pics/725096arbreTemprance.png"></div>
          </div>
        </div>
      </section>
	  	   <!-- Bourses mondiales
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="bourses"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
          <h1>Bourses mondiales</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
              <p>Les Bourses mondiales sont créées dans la continuité du projet Tempérance. Le but est de mettre en valeur vos ressources acquises via la mise en ligne de vos infrastrutures et via l'outil "zoning" du site GC. Mais de quelle manière ? Comment pourrez-vous faire prospérer votre Bourse ? La rubrique "En savoir plus" est à présent en ligne pour vous. Il est temps de se confronter aux défis du monde réel sur le site GC : Saurez-vous vous adapter au développement des autres pays membres ? Quels choix ferez-vous pour placer votre Bourse en tête ? Comment battrez-vous la tendance mondiale ? Bientôt, chers membres gécéens, vos propres résultats pour le projet des Bourses mondiales... </p>
              <p><a class="btn btn-primary" href="projet-bourses.php">En savoir plus</a> <a class="btn btn-primary" href="back/Temperance_jugement.php">Juges</a></p>
            </div>
            <div class="span5"><img src="http://img15.hostingpics.net/pics/116877Bourses.jpg"></div>
          </div>
        </div>
      </section>
      <!-- communique officiel
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="communiques"> <img src="assets/img/IconesBDD/Bleu/100/Communique_bleu.png">
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