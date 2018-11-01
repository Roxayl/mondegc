<?php
session_start();
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
$maxRows_classer_mon = 10;
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
$query_classer_mon = sprintf("SELECT monument.ch_disp_id as id, monument.ch_disp_mon_id, ch_pat_nom, ch_pat_mis_jour, ch_pat_lien_img1, ch_pat_villeID, ch_pat_paysID, ch_vil_nom, ch_vil_ID, ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, (SELECT GROUP_CONCAT(categories.ch_disp_cat_id) FROM dispatch_mon_cat as categories WHERE monument.ch_disp_mon_id = categories.ch_disp_mon_id) AS listcat
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
$query_info_cat = sprintf("SELECT ch_mon_cat_nom, ch_mon_cat_desc, ch_mon_cat_icon, ch_mon_cat_couleur
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
<title>Monde GC- patrimoine</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
	background-image: url('assets/img/bannieres-instituts/Patrimoine.png');
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
        <li><a href="#monument">Monuments index&eacute;s</a></li>
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
      <section>
	  		<div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>Responsable actuel de cet Institut : Maori</p></div>
        <div class="titre-bleu anchor" id="presentation"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
          <h1>Présentation</h1>
        </div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
              <p><?php echo $row_institut['ch_ins_desc']; ?></p>
            </div>
            <div class="span5"><img src="<?php echo $row_institut['ch_ins_img']; ?>"></div>			
          </div>
        </div>
      </section>
      <!-- Monument indexe
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="monument"> <img src="assets/img/IconesBDD/Bleu/100/monument1_bleu.png">
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
          <div class="well">
            <div class="row-fluid">
              <div class="span8">
                <p><strong><?php echo $row_info_cat['ch_mon_cat_nom']; ?></strong></p>
                <p><?php echo $row_info_cat['ch_mon_cat_desc']; ?></p>
              </div>
              <div class="span2 icone-categorie icone-large"><img src="<?php echo $row_info_cat['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_info_cat['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_info_cat['ch_mon_cat_couleur']; ?>;"></div>
            </div>
          </div>
          <?php }?>
          <?php if ($row_classer_mon) {?>
          <!-- Liste des monuments de la categorie -->
          <ul class="listes">
            <!-- Requetes pour infos et icones des catégories du monuments -->
            <?php do { 
	  
			$listcategories = $row_classer_mon['listcat'];
			if ($row_classer_mon['listcat']) {
          
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut = 1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 } ?>
            <?php if ($row_classer_mon) {
									if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_classer_mon['ch_pay_lien_imgdrapeau']))
					{
					$row_classer_mon['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_classer_mon['ch_pay_lien_imgdrapeau']);
					}
				?>
            <!-- Item monument -->
            <li class="row-fluid"> 
              <!-- Image du monument -->
              <div class="span2 img-listes">
                <?php if ($row_classer_mon['ch_pat_lien_img1']) {?>
                <img src="<?php echo $row_classer_mon['ch_pat_lien_img1']; ?>" alt="image <?php echo $row_classer_mon['ch_pat_nom']; ?>">
                <?php } else { ?>
                <img src="assets/img/imagesdefaut/ville.jpg" alt="monument">
                <?php } ?>
                <?php if ($row_classer_mon['ch_pay_id']) {?>
                <a href="page-pays.php?ch_pay_id=<?php echo $row_classer_mon['ch_pat_paysID']; ?>"><img class="img-drapeau-hist" src="<?php echo $row_classer_mon['ch_pay_lien_imgdrapeau']; ?>" alt="<?php echo $row_classer_mon['ch_pay_nom']; ?>" title="<?php echo $row_classer_mon['ch_pay_nom']; ?>"></a>
                <?php } ?>
              </div>
              <!-- Nom, date et lien vers la page du monument -->
              <div class="span6 info-listes">
                <h4><?php echo $row_classer_mon['ch_pat_nom']; ?></h4>
                <p><strong>Ville&nbsp;: <a href="page-ville.php?ch_pay_id=<?php echo $row_classer_mon['ch_pat_paysID']; ?>&ch_ville_id=<?php echo $row_classer_mon['ch_pat_villeID']; ?>"><?php echo $row_classer_mon['ch_vil_nom']; ?></a></strong><br>
                <strong>Derni&egrave;re mise &agrave; jour&nbsp;: </strong>le
                  <?php  echo date("d/m/Y", strtotime($row_classer_mon['ch_pat_mis_jour'])); ?>
                  &agrave; <?php echo date("G:i:s", strtotime($row_classer_mon['ch_pat_mis_jour'])); ?> </p>
                <a class="btn btn-primary" href="php/patrimoine-modal.php?ch_pat_id=<?php echo $row_classer_mon['ch_disp_mon_id']; ?>" data-toggle="modal" data-target="#Modal-Monument">Visiter</a> </div>
              <!-- Affichage des categories du monument -->
               <?php if ($row_liste_mon_cat3) {?>
              <div class="span4 icone-categorie">
                <?php do { ?>
                  <!-- Icone et popover de la categorie -->
                  <div class=""><a href="#" rel="clickover" title="<?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_mon_cat3['ch_mon_cat_desc']; ?>"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>;"></a></div>
                  <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
              </div>
               <?php } ?>
            </li>
            <?php } ?>
            <?php } while ($row_classer_mon = mysql_fetch_assoc($classer_mon)); ?>
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