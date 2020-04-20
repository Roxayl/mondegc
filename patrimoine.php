<?php

require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 3;
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste categories monuments pour pouvoir selectionner la categorie 
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat2 = "SELECT * FROM monument_categories WHERE ch_mon_cat_statut = 1  ORDER BY ch_mon_cat_couleur ASC";
$liste_mon_cat2 = mysql_query($query_liste_mon_cat2, $maconnexion) or die(mysql_error());
$row_liste_mon_cat2 = mysql_fetch_assoc($liste_mon_cat2);
$totalRows_liste_mon_cat2 = mysql_num_rows($liste_mon_cat2);


//requete liste  monuments d'une catégorie 
$maxRows_classer_mon = 12;
$pageNum_classer_mon = 0;
if (isset($_GET['pageNum_classer_mon'])) {
  $pageNum_classer_mon = $_GET['pageNum_classer_mon'];
}
$startRow_classer_mon = $pageNum_classer_mon * $maxRows_classer_mon;

$colname_classer_mon = "-1";
if (isset($_GET['mon_cat_ID'])) {
	if ($_GET['mon_cat_ID'] == "") {
	$colname_classer_mon = NULL;
} else {
  $colname_classer_mon = $_GET['mon_cat_ID'];
} } else {
  $colname_classer_mon = NULL;
} 
mysql_select_db($database_maconnexion, $maconnexion);
$query_classer_mon = sprintf("SELECT monument.ch_disp_id as id, monument.ch_disp_mon_id, ch_pat_nom, ch_pat_description, ch_pat_mis_jour, ch_pat_lien_img1, ch_pat_villeID, ch_pat_paysID, ch_vil_nom, ch_vil_ID, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, (SELECT GROUP_CONCAT(categories.ch_disp_cat_id) FROM dispatch_mon_cat as categories WHERE monument.ch_disp_mon_id = categories.ch_disp_mon_id) AS listcat
FROM dispatch_mon_cat as monument 
INNER JOIN patrimoine ON monument.ch_disp_mon_id = ch_pat_id
INNER JOIN villes ON ch_pat_villeID = ch_vil_ID 
INNER JOIN pays ON ch_pat_paysID = ch_pay_id 
WHERE monument.ch_disp_cat_id = %s OR %s IS NULL AND ch_pat_statut = 1 
GROUP BY monument.ch_disp_mon_id
ORDER BY monument.ch_disp_date DESC", GetSQLValueString($colname_classer_mon, "int"), GetSQLValueString($colname_classer_mon, "int"));
$query_limit_classer_mon = sprintf("%s LIMIT %d, %d", $query_classer_mon, $startRow_classer_mon, $maxRows_classer_mon);
$classer_mon = mysql_query($query_limit_classer_mon, $maconnexion) or die(mysql_error());
$row_classer_mon = mysql_fetch_assoc($classer_mon);

if (isset($_GET['totalRows_classer_mon'])) {
  $totalRows_classer_mon = $_GET['totalRows_classer_mon'];
} else {
  $all_classer_mon = mysql_query($query_classer_mon);
  $totalRows_classer_mon = mysql_num_rows($all_classer_mon);
}
$totalPages_classer_mon = ceil($totalRows_classer_mon/$maxRows_classer_mon)-1;

$queryString_classer_mon = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_classer_mon") == false && 
        stristr($param, "totalRows_classer_mon") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_classer_mon = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_classer_mon = sprintf("&totalRows_classer_mon=%d%s", $totalRows_classer_mon, $queryString_classer_mon);



//requete info sur catégorie
mysql_select_db($database_maconnexion, $maconnexion);
$query_info_cat = sprintf("SELECT ch_mon_cat_ID, ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur
FROM monument_categories
WHERE ch_mon_cat_ID = %s OR %s IS NULL AND ch_mon_cat_statut = 1", GetSQLValueString($colname_classer_mon, "int"), GetSQLValueString($colname_classer_mon, "int"));
$info_cat = mysql_query($query_info_cat, $maconnexion) or die(mysql_error());
$row_info_cat = mysql_fetch_assoc($info_cat);
$totalRows_info_cat = mysql_num_rows($info_cat);
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
    background: linear-gradient(to right, #004eff 0%,#7e00ff 72%);
    background-size: 140%;
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
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script src="assets/js/tiny_mce/tiny_mce.js"></script>
<script src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
        <li><a href="#monument">Monuments index&eacute;s</a></li>
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
      <li class="active">Culture</li>
    </ul>

      <!-- Presentation
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="presentation">
          <h1>Présentation</h1>
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
      </section>
      <!-- Monument indexe
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="monument">
          <h1>Monuments index&eacute;s</h1>
        </div>
        <div class="row-fluid"> 
          <!-- Liste pour choix de la categories -->
          <div id="select-categorie">
            <form action="patrimoine.php#monument" method="GET">
              <select name="mon_cat_ID" id="mon_cat_ID" onchange="this.form.submit()">
                <option value="" <?php if ($colname_classer_mon == NULL) {?>selected<?php } ?>>S&eacute;lectionnez une cat&eacute;gorie&nbsp;</option>
                <?php do { ?>
                <option value="<?php echo $row_liste_mon_cat2['ch_mon_cat_ID']; ?>" <?php if ($colname_classer_mon == $row_liste_mon_cat2['ch_mon_cat_ID']) {?>selected<?php } ?>><?php echo $row_liste_mon_cat2['ch_mon_cat_nom']; ?></option>
                <?php } while ($row_liste_mon_cat2 = mysql_fetch_assoc($liste_mon_cat2)); ?>
              </select>
            </form>
          </div>
          <!-- Affichage si des informations de la catégorie  -->
          <?php if (($colname_classer_mon != NULL) AND ($colname_classer_mon != "")) { // affiche bouton ajouter si une categorie est choisie ?>
              
          <?php
          // *** Ressources patrimoine
        $query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
        WHERE ch_mon_cat_ID = %s", GetSQLValueString($row_info_cat['ch_mon_cat_ID'], "int"));
        $monument_ressources = mysql_query($query_monument_ressources, $maconnexion) or die(mysql_error());
        $row_monument_ressources = mysql_fetch_assoc($monument_ressources);
          ?>
              
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <h3><?php echo $row_info_cat['ch_mon_cat_nom']; ?></h3>
                <p><?php echo $row_info_cat['ch_mon_cat_desc']; ?></p
                <p><strong>Influence sur l'économie de cette catégorie :</strong></p>
                  <?php renderResources($row_monument_ressources); ?>
                  <div class="clearfix"></div>
              </div>
              <div class="span2 icone-categorie icone-large"><img src="<?php echo $row_info_cat['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_info_cat['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_info_cat['ch_mon_cat_couleur']; ?>;"></div>
            </div>
          </div>
          <?php }?>
          <?php if ($row_classer_mon) {?>
          <!-- Liste des monuments de la categorie -->
          <div id="infra-well-container">
        <?php do {

			$listcategories = ($row_classer_mon['listcat']);
			if ($row_classer_mon['listcat']) {
                mysql_select_db($database_maconnexion, $maconnexion);
                $query_liste_mon_cat3 = "SELECT * FROM monument_categories
                    WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
                $liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
                $row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
                $totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			}

            $overlay_text = null;
            if($row_liste_mon_cat3) {
                $overlay_text = '
                <a href="#" rel="clickover" title="' . $row_liste_mon_cat3['ch_mon_cat_nom'] . '"
                   data-placement="top" data-content="' . $row_liste_mon_cat3['ch_mon_cat_desc'] . '">' .
                            $row_liste_mon_cat3['ch_mon_cat_nom'] . '
                </a>';
            }

            $infraData = array(
              'id' => $row_classer_mon['ch_disp_mon_id'],
              'type' => 'patrimoine',
              'overlay_image' => $row_liste_mon_cat3['ch_mon_cat_icon'],
              'overlay_text' => $overlay_text,
              'image' => $row_classer_mon['ch_pat_lien_img1'],
              'nom' => $row_classer_mon['ch_pat_nom'],
              'description' => $row_classer_mon['ch_pat_description']
            );
            renderElement('infrastructure_well', $infraData);

        } while ($row_classer_mon = mysql_fetch_assoc($classer_mon)); ?>
        </div>
          <div class="modal container fade" id="Modal-Monument"></div>
          <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
          <p>&nbsp;</p>
          <!-- Pagination liste des monuments de la categorie -->
          <small class="pull-right">de <?php echo ($startRow_classer_mon + 1) ?> &agrave; <?php echo min($startRow_classer_mon + $maxRows_classer_mon, $totalRows_classer_mon) ?> sur <?php echo $totalRows_classer_mon ?>
            <?php if ($pageNum_classer_mon > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mon=%d%s#classer-monument", $currentPage, max(0, $pageNum_classer_mon - 1), $queryString_classer_mon); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_classer_mon < $totalPages_classer_mon) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_mon=%d%s#classer-monument", $currentPage, min($totalPages_classer_mon, $pageNum_classer_mon + 1), $queryString_classer_mon); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?></small>
          <?php } else { ?>
          <p>Cette cat&eacute;gorie n'as pas encore de monument index&eacute;</p>
          <?php }  ?>
        </div>
      </section>
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
