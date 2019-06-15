<?php
require_once('Connections/maconnexion.php');


//Connexion et deconnexion
include('php/log.php');

if(isset($_SESSION['login_user'])) {
    $thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
    $listePays = $thisUser->getCountries();
}

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - Tableau de bord</title>
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
	background-image: url('assets/img/bannieres-instituts/Geo.png');
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
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js"></script>
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
<?php $dashboard=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1>Tableau de bord</h1>
  </div>
</header>
<div class="container">

  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-dashboard">
          Bienvenue sur votre tableau de bord.</li>
        <li><a href="#notifications">Notifications</a></li>
        <li><a href="#gestion">Gestion</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== -->

    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">
    <ul class="breadcrumb">
      <li class="active">Tableau de bord</li>
    </ul>

    <section>
    <div class="titre-bleu anchor" id="notifications"> <img src="assets/img/IconesBDD/Bleu/100/carte_bleu.png">
      <h1>Notifications</h1>
    </div>
    <div class="well">
        <div class="alert alert-info">Vos notifications arrivent bientôt !</div>
    </div>
    </section>

    <section>
    <div class="titre-bleu anchor" id="gestion"> <img src="assets/img/IconesBDD/Bleu/100/carte_bleu.png">
      <h1>Gestion</h1>
    </div>
    <h3>Mes pays</h3>
    <div class="well">
        <?php if(empty($listePays)): ?>
        <p>Vous n'avez pas de pays.</p>
        <?php endif; ?>
        <ul class="listes">
            <?php foreach($listePays as $pays): ?>
            <li class="row-fluid">
              <div class="">
                <div class="span2"> <a href="page-pays.php?ch_pay_id=<?= $pays['ch_pay_id']; ?>"><img src="<?= $pays['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau"></a> </div>
                <div class="span4">
                  <h3><?= $pays['ch_pay_nom']; ?></h3>
                </div>
                <div class="span4">
                </div>
                <div class="span2">
                    <a href="page-pays.php?ch_pay_id=<?= $pays['ch_pay_id']; ?>" class="btn btn-primary">Visiter</a>
                    <a href="back/page_pays_back.php?paysID=<?= $pays['ch_pay_id'] ?>&userID=<?= $thisUser->model->ch_use_id ?>" class="btn btn-primary"><i class="icon-pays-small-white"></i> Gérer mon pays</a>
                </div>
              </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

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
