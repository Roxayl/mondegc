<?php
        
//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 1;

$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", escape_sql($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion);
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - OCGC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="carto/OLdefault.css" rel="stylesheet">
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
	background-image: url('assets/img/bannieres-instituts/OCGC.png');
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
<?php require('php/cartepays.php'); ?>
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
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut" >
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
        <li><a href="#communiques">Communiqu&eacute;s officiels</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">

    <ul class="breadcrumb">
      <li class="active">OCGC</li>
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