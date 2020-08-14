<?php

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 4;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste categories fait_hists pour pouvoir selectionner la categorie 

$query_liste_fai_cat2 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_statut = 1 ORDER BY ch_fai_cat_mis_jour DESC";
$liste_fai_cat2 = mysql_query($query_liste_fai_cat2, $maconnexion) or die(mysql_error());
$row_liste_fai_cat2 = mysql_fetch_assoc($liste_fai_cat2);
$totalRows_liste_fai_cat2 = mysql_num_rows($liste_fai_cat2);


//requete liste  faits  d'une catégorie 
$maxRows_classer_fait_hist = 10;
$pageNum_classer_fait_hist = 0;
if (isset($_GET['pageNum_classer_fait_hist'])) {
  $pageNum_classer_fait_hist = $_GET['pageNum_classer_fait_hist'];
}
$startRow_classer_fait_hist = $pageNum_classer_fait_hist * $maxRows_classer_fait_hist;

$colname_classer_fait_hist = "-1";
if (isset($_GET['fai_catID'])) {
	if ($_GET['fai_catID'] == "") {
	$colname_classer_fait_hist = NULL;
} else {
  $colname_classer_fait_hist = $_GET['fai_catID'];
} } else {
  $colname_classer_fait_hist = NULL;
} 

$query_classer_fait_hist = sprintf("SELECT fait.ch_disp_FH_id as id, fait.ch_disp_fait_hist_id, ch_his_nom, ch_his_mis_jour, ch_his_date_fait, ch_his_lien_img1, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, (SELECT GROUP_CONCAT(categories.ch_disp_fait_hist_cat_id) FROM dispatch_fait_his_cat as categories WHERE fait.ch_disp_fait_hist_id = categories.ch_disp_fait_hist_id) AS listcat
FROM dispatch_fait_his_cat as fait 
INNER JOIN histoire ON fait.ch_disp_fait_hist_id = ch_his_id 
INNER JOIN pays ON ch_pay_id = ch_his_paysID
WHERE fait.ch_disp_fait_hist_cat_id = %s OR %s IS NULL AND ch_his_statut = 1 
GROUP BY fait.ch_disp_fait_hist_id
ORDER BY ch_his_date_fait ASC", GetSQLValueString($colname_classer_fait_hist, "int"), GetSQLValueString($colname_classer_fait_hist, "int"));
$query_limit_classer_fait_hist = sprintf("%s LIMIT %d, %d", $query_classer_fait_hist, $startRow_classer_fait_hist, $maxRows_classer_fait_hist);
$classer_fait_hist = mysql_query($query_limit_classer_fait_hist, $maconnexion) or die(mysql_error());
$row_classer_fait_hist = mysql_fetch_assoc($classer_fait_hist);

if (isset($_GET['totalRows_classer_fait_hist'])) {
  $totalRows_classer_fait_hist = $_GET['totalRows_classer_fait_hist'];
} else {
  $all_classer_fait_hist = mysql_query($query_classer_fait_hist);
  $totalRows_classer_fait_hist = mysql_num_rows($all_classer_fait_hist);
}
$totalPages_classer_fait_hist = ceil($totalRows_classer_fait_hist/$maxRows_classer_fait_hist)-1;

$queryString_classer_fait_hist = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_classer_fait_hist") == false && 
        stristr($param, "totalRows_classer_fait_hist") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_classer_fait_hist = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_classer_fait_hist = sprintf("&totalRows_classer_fait_hist=%d%s", $totalRows_classer_fait_hist, $queryString_classer_fait_hist);

//requete info sur catégorie

$query_info_cat = sprintf("SELECT ch_fai_cat_nom, ch_fai_cat_desc, ch_fai_cat_icon, ch_fai_cat_couleur
FROM faithist_categories
WHERE ch_fai_cat_ID = %s OR %s IS NULL AND ch_fai_cat_statut = 1", GetSQLValueString($colname_classer_fait_hist, "int"), GetSQLValueString($colname_classer_fait_hist, "int"));
$info_cat = mysql_query($query_info_cat, $maconnexion) or die(mysql_error());
$row_info_cat = mysql_fetch_assoc($info_cat);
$totalRows_info_cat = mysql_num_rows($info_cat);

//requete pays archives

$maxRows_pays_arch = 10;
$pageNum_pays_arch = 0;
if (isset($_GET['pageNum_pays_arch'])) {
  $pageNum_pays_arch = $_GET['pageNum_pays_arch'];
}
$startRow_pays_arch = $pageNum_pays_arch * $maxRows_pays_arch;


$query_pays_arch = "SELECT ch_pay_id, ch_pay_mis_jour, ch_pay_nom, ch_pay_devise, ch_pay_lien_imgdrapeau, ch_use_prenom_dirigeant, ch_use_nom_dirigeant, Sum(villes.ch_vil_population) AS ch_pay_population 
FROM pays LEFT OUTER JOIN villes ON ch_pay_id = ch_vil_paysID AND ch_vil_capitale != 3 LEFT OUTER JOIN users ON ch_use_paysID = ch_pay_id WHERE ch_pay_publication = 2 GROUP BY ch_pay_id ORDER BY ch_pay_mis_jour DESC";
$query_limit_pays_arch = sprintf("%s LIMIT %d, %d", $query_pays_arch, $startRow_pays_arch, $maxRows_pays_arch);
$pays_arch = mysql_query($query_limit_pays_arch, $maconnexion) or die(mysql_error());
$row_pays_arch = mysql_fetch_assoc($pays_arch);

if (isset($_GET['totalRows_pays_arch'])) {
  $totalRows_pays_arch = $_GET['totalRows_pays_arch'];
} else {
  $all_pays_arch = mysql_query($query_pays_arch);
  $totalRows_pays_arch = mysql_num_rows($all_pays_arch);
}
$totalPages_pays_arch = ceil($totalRows_pays_arch/$maxRows_pays_arch)-1;

$queryString_pays_arch = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_pays_arch") == false && 
        stristr($param, "totalRows_pays_arch") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_pays_arch = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_pays_arch = sprintf("&totalRows_pays_arch=%d%s", $totalRows_pays_arch, $queryString_pays_arch);
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
    background: linear-gradient(to right, #00a2ff 0%,#1eff00 72%);
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
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
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
        <li><a href="#fait_hist">&Eacute;l&eacute;ments historiques index&eacute;s</a></li>
        <li><a href="#pays-archives">Pays archiv&eacute;s</a></li>
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
      <li class="active">Histoire</li>
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
      <!-- Faits historiques indexe
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="fait_hist">
          <h1>&Eacute;l&eacute;ments historiques index&eacute;s</h1>
        </div>
        <div class="row-fluid"> 
          <!-- Liste pour choix de la categories -->
          <div id="select-categorie">
            <form action="<?= DEF_URI_PATH ?>histoire.php#fait_hist" method="GET">
              <select name="fai_catID" id="fai_catID" onchange="this.form.submit()">
                <option value="" <?php if ($colname_classer_fait_hist == NULL) {?>selected<?php } ?>>S&eacute;lectionnez une cat&eacute;gorie&nbsp;</option>
                <?php do { ?>
                <option value="<?php echo $row_liste_fai_cat2['ch_fai_cat_ID']; ?>" <?php if ($colname_classer_fait_hist == $row_liste_fai_cat2['ch_fai_cat_ID']) {?>selected<?php } ?>><?php echo $row_liste_fai_cat2['ch_fai_cat_nom']; ?></option>
                <?php } while ($row_liste_fai_cat2 = mysql_fetch_assoc($liste_fai_cat2)); ?>
              </select>
            </form>
          </div>
          <!-- Affichage si des informations de la catégorie  -->
          <?php if (($colname_classer_fait_hist != NULL) AND ($colname_classer_fait_hist != "")) { // affiche bouton ajouter si une categorie est choisie ?>
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <p><strong><?= e($row_info_cat['ch_fai_cat_nom']) ?></strong></p>
                <p><?= e($row_info_cat['ch_fai_cat_desc']) ?></p>
              </div>
              <div class="span2 icone-categorie icone-large"><img src="<?= e($row_info_cat['ch_fai_cat_icon']) ?>" alt="icone <?= e($row_info_cat['ch_fai_cat_nom']) ?>" style="background-color:<?= e($row_info_cat['ch_fai_cat_couleur']) ?>;"></div>
            </div>
          </div>
          <?php }?>
          <?php if ($row_classer_fait_hist) {?>
          <!-- Liste des faits historiques de la categorie -->
          <ul class="listes">
            <!-- Requetes pour infos et icones des catégories des faits historiques -->
            <?php do { 
	  
			$listcategories = $row_classer_fait_hist['listcat'];
			if ($row_classer_fait_hist['listcat']) {
          

$query_liste_fai_cat3 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_ID In ($listcategories) AND ch_fai_cat_statut=1";
$liste_fai_cat3 = mysql_query($query_liste_fai_cat3, $maconnexion) or die(mysql_error());
$row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3);
$totalRows_liste_fai_cat3 = mysql_num_rows($liste_fai_cat3);
			 } ?>
            <?php if ($row_classer_fait_hist) {?>
            <!-- Item monument -->
            <li class="row-fluid"> 
              <!-- Image du monument -->
              <div class="span2 img-listes">
                <?php if ($row_classer_fait_hist['ch_his_lien_img1']) {?>
                <img src="<?php echo $row_classer_fait_hist['ch_his_lien_img1']; ?>" alt="image <?= e($row_classer_fait_hist['ch_his_nom']) ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="monument">
                <?php } ?>
                <?php if ($row_classer_fait_hist['ch_pay_id']) {
					if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_classer_fait_hist['ch_pay_lien_imgdrapeau']))
					{
					$row_classer_fait_hist['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_classer_fait_hist['ch_pay_lien_imgdrapeau']);
					} 
					?>
                <a href="page-pays.php?ch_pay_id=<?= e($row_classer_fait_hist['ch_pay_id']) ?>"><img class="img-drapeau-hist" src="<?= e($row_classer_fait_hist['ch_pay_lien_imgdrapeau']) ?>" alt="<?= e($row_classer_fait_hist['ch_pay_nom']) ?>" title="<?= e($row_classer_fait_hist['ch_pay_nom']) ?>"></a>
                <?php } ?>
              </div>
              <!-- Nom, date et lien vers la page du fait historique -->
              <div class="span6 info-listes">
                <h4>Le <?php echo affDate($row_classer_fait_hist['ch_his_date_fait']); ?>&nbsp;:</h4>
                <h4><?= e($row_classer_fait_hist['ch_his_nom']) ?></h4>
                <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
                  <?php  echo date("d/m/Y", strtotime($row_classer_fait_hist['ch_his_mis_jour'])); ?>
                  &agrave; <?php echo date("G:i:s", strtotime($row_classer_fait_hist['ch_his_mis_jour'])); ?> </p>
                <a class="btn btn-primary" href="php/fait-his-modal.php?ch_his_id=<?= e($row_classer_fait_hist['ch_disp_fait_hist_id']) ?>" data-toggle="modal" data-target="#Modal-Monument">Consulter</a> </div>
              <!-- Affichage des categories du fait historique -->
              <?php if ($row_liste_fai_cat3) {?>
              <div class="span4 icone-categorie">
                <?php do { ?>
                  <!-- Icone et popover de la categorie -->
                  <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_fai_cat3['ch_fai_cat_desc']; ?>"><img src="<?php echo $row_liste_fai_cat3['ch_fai_cat_icon']; ?>" alt="icone <?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?>" style="background-color:<?php echo $row_liste_fai_cat3['ch_fai_cat_couleur']; ?>;"></a></div>
                  <?php } while ($row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3)); ?>
              </div>
              <?php } ?>
            </li>
            <?php } ?>
            <?php } while ($row_classer_fait_hist = mysql_fetch_assoc($classer_fait_hist)); ?>
          </ul>
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
          <small class="pull-right">de <?php echo ($startRow_classer_fait_hist + 1) ?> &agrave; <?php echo min($startRow_classer_fait_hist + $maxRows_classer_fait_hist, $totalRows_classer_fait_hist) ?> sur <?php echo $totalRows_classer_fait_hist ?>
            <?php if ($pageNum_classer_fait_hist > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_fait_hist=%d%s#fait_hist", $currentPage, max(0, $pageNum_classer_fait_hist - 1), $queryString_classer_fait_hist); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_classer_fait_hist < $totalPages_classer_fait_hist) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_classer_fait_hist=%d%s#fait_hist", $currentPage, min($totalPages_classer_fait_hist, $pageNum_classer_fait_hist + 1), $queryString_classer_fait_hist); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?></small>
          <?php } else { ?>
          <p>Cette cat&eacute;gorie n'as pas encore de monument index&eacute;</p>
          <?php }  ?>
        </div>
      </section>
      <!-- Pays archive
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="pays-archives">
          <h1>Pays archiv&eacute;s</h1>
          <p class="well">Ces pays ont autrefois occup&eacute;s des emplacements dans le Monde GC. Ils ont influenc&eacute;s les peuples et font maintenant partie de l'histoire de ce monde</p>
        </div>
        <ul class="listes sepia">
          <?php do { ?>
            <li>
              <div class="row-fluid">
              <div class="span5 img-listes">
                <?php if ($row_pays_arch['ch_pay_lien_imgdrapeau']) {?>
                <a class="" href="page-pays.php?ch_pay_id=<?= e($row_pays_arch['ch_pay_id']) ?>"> <img src="<?= e($row_pays_arch['ch_pay_lien_imgdrapeau']) ?>" alt="drapeau <?= e($row_pays_arch['ch_pay_nom']) ?>"> </a>
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/drapeau.jpg" alt="pays">
                <?php } ?>
              </div>
              <div class="span6 info-listes"> <small>derni&egrave;re mise &agrave; jour le
                <?php  echo date("d/m/Y à G:i", strtotime($row_pays_arch['ch_pay_mis_jour'])); ?>
                </small>
                <h4><?= e($row_pays_arch['ch_pay_nom']) ?></h4>
                <p>Cr&eacute;&eacute; par&nbsp;: <strong><?= e($row_pays_arch['ch_use_prenom_dirigeant']) ?> <?= e($row_pays_arch['ch_use_nom_dirigeant']) ?></strong></p>
                <p>Population&nbsp;: <strong>
                  <?php 
	$population_pays_francais = number_format($row_pays_arch['ch_pay_population'], 0, ',', ' ');
	echo $population_pays_francais; ?>
                  habitants</strong></p>
                <p>Devise&nbsp;: <strong>
                  <?php if ($row_pays_arch['ch_pay_devise']) 
		  { echo $row_pays_arch['ch_pay_devise']; 
		  } else {echo 'NA'; 
		  }?>
                  </strong></p>
                <a class="btn btn-primary" href="page-pays.php?ch_pay_id=<?= e($row_pays_arch['ch_pay_id']) ?>">Visiter</a> </div>
            </li>
            <hr>
            <?php } while ($row_pays_arch = mysql_fetch_assoc($pays_arch)); ?>
        </ul>
        
        <small class="pull-right">de <?php echo ($startRow_pays_arch + 1) ?> &agrave; <?php echo min($startRow_pays_arch + $maxRows_pays_arch, $totalRows_pays_arch) ?> sur <?php echo $totalRows_pays_arch ?>
            <?php if ($pageNum_pays_arch > 0) { // Show if not first page ?>
            <a class="btn" href="<?php printf("%s?pageNum_pays_arch=%d%s#pays-archives", $currentPage, max(0, $pageNum_pays_arch - 1), $queryString_pays_arch); ?>"><i class=" icon-backward"></i></a>
            <?php } // Show if not first page ?>
            <?php if ($pageNum_pays_arch < $totalPages_pays_arch) { // Show if not last page ?>
            <a class="btn" href="<?php printf("%s?pageNum_pays_arch=%d%s#pays-archives", $currentPage, min($totalPages_pays_arch, $pageNum_pays_arch + 1), $queryString_pays_arch); ?>"> <i class="icon-forward"></i></a>
            <?php } // Show if not last page ?></small>
        
        
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