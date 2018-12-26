<?php 
header('Content-Type: text/html; charset=utf-8');
session_start();
require_once('Connections/maconnexion.php');
//Connexion et deconnexion
include('php/log.php');

//Initialisation des dates pour utilisation dans last_MAJ.php
$_SESSION['aujourdhui']=true;
$_SESSION['hier']=true;
$_SESSION['avanthier']=true;
$_SESSION['avantavanthier']=true;
$_SESSION['semaine']=true;
$_SESSION['deuxsemaine']=true;
$_SESSION['mois']=true;
$_SESSION['deuxmois']=true;
$_SESSION['troismois']=true;
$_SESSION['sixmois']=true;
$_SESSION['an']=true;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Le Monde GC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- TemplateEndEditable -->
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
	background-image: url('assets/img/ImgIntroheader.jpg');
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>

</head>
<body>
<!-- Navbar
    ================================================== -->
<?php $accueil=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <div class="header">
      <h1>Bienvenue sur le site du Monde de Génération City</h1>
      <p><em>Le Monde GC rassemble une communaut&eacute; de joueurs du site G&eacute;n&eacute;ration City qui ont souhait&eacute; s'unir pour construire leur propre monde et d&eacute;velopper <a href="participer.php#faq">une nouvelle expérience de jeu</a>.</em></p>
    </div>
    <div class="Master-link">
      <p class="hidden-phone">Débutez l'exploration&nbsp;:</p>
      <a href="Page-carte.php" class="btn btn-primary btn-large">Carte</a> <a href="histoire.php" class="btn btn-primary btn-large">Histoire</a> <a href="patrimoine.php" class="btn btn-primary btn-large">Patrimoine</a> <a href="economie.php" class="btn btn-primary btn-large">Economie</a> <a href="politique.php" class="btn btn-primary btn-large">Politique</a> </div>
  </div>
</div>
<!-- Bandeau stat
================================================== -->
<?php include('php/bandeauStat.php'); ?>
<!-- Icon Start
================================================== -->
<div class="container corps-page">
  <!-- CATEGORIE Dernières actualites
================================================== -->

<div id="actu" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="icone user">
  <h1>Derni&egrave;res actualit&eacute;s</h1>
</div>
  <!-- LISTE Dernières actualites
================================================== -->

<div class="row-fluid">
    <div class="span8" id="postswrapper">
        <?php include('last_MAJ.php'); ?>
    </div>
    <div class="span4">

    </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>