<?php

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 2;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

//requete liste pays pour pouvoir selectionner le pays

$query_liste_pays = "SELECT ch_pay_id, ch_pay_nom FROM pays WHERE ch_pay_publication = 1 ORDER BY ch_pay_mis_jour DESC";
$liste_pays = mysql_query($query_liste_pays, $maconnexion) or die(mysql_error());
$row_liste_pays = mysql_fetch_assoc($liste_pays);
$totalRows_liste_pays = mysql_num_rows($liste_pays);

if (!isset($_GET['ch_pay_id'])) { 
$_GET['ch_pay_id'] = $row_liste_pays['ch_pay_id'];
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
#map {
	height: 500px;
	background-color: #fff;
}
#mapPosition {
	height: 500px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
@media (max-width: 480px) {
#map {
	height: 260px;
}
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
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script> 
 $( document ).ready(function() {
init();
});
</script>
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

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-medium jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1><?= __s($row_institut['ch_ins_nom']) ?></h1>
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
          <img src="<?= __s($row_institut['ch_ins_logo']) ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?= __s($row_institut['ch_ins_sigle']) ?></strong></p>
          <p><em><?= __s($row_institut['ch_ins_nom']) ?></em></p>
          </a></li>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <li><a href="#carte">Cartes</a></li>
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
      <li class="active">Géographie</li>
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
      <!-- cartes des pays
    ================================================== -->
      <section>
        <div class="titre-bleu anchor" id="carte">
          <h1>Cartes des pays du Monde GC</h1>
        </div>
        <div class="row-fluid">
    <!-- Liste pour choix de la categories -->
    <div id="select-categorie">
      <form action="<?= DEF_URI_PATH ?>geographie.php#carte" method="GET">
        <select name="ch_pay_id" id="ch_pay_id" onchange="this.form.submit()">
          <option value="" <?php if ($_GET['ch_pay_id'] == NULL) {?>selected<?php } ?>>S&eacute;lectionnez un pays&nbsp;</option>
          <?php do { ?>
          <option value="<?= e($row_liste_pays['ch_pay_id']) ?>" <?php if ($row_liste_pays['ch_pay_id'] == $_GET['ch_pay_id']) {?>selected<?php } ?>><?= __s($row_liste_pays['ch_pay_nom']) ?></option>
          <?php } while ($row_liste_pays = mysql_fetch_assoc($liste_pays)); ?>
        </select>
      </form>
    </div>
    <div id="map" class="well"></div>
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
