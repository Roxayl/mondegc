<?php

require_once('Connections/maconnexion.php');

//deconnexion
include('php/log.php');

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Bourses mondiales</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
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
	background-image: url('assets/img/fond_haut-conseil.jpg');
	background-position: center;
}
</style>
<!-- Le javascript
    ================================================== -->
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
<!-- Color Picker  -->
<script src="assets/js/bootstrap-colorpicker.js" type="text/javascript"></script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script>
		$(function(){
			window.prettyPrint && prettyPrint()
			$('#cp3').colorpicker({
format: 'hex'});
$('#cp4').colorpicker({
format: 'hex'});
		});
	</script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <div class="pull-right span5" style="text-align:right;">
      <p>&nbsp;</p>
      <a href="economie.php" class="btn btn-primary">Retour Comité d'Économie</a> </div>
    <div class="span5 align-left">
      <h2>Qu'est-ce qu'une Bourse mondiale ?</h2>
      <p><em>Votre Bourse mondiale est la vitrine mettant en valeur vos ressources obtenues grâce aux infrastructures et grâce à l'outil "Zoning" du Monde GC. Elle vous permettra d'obtenir des points et une tendance, définissant ainsi votre valeur en bourse, en fonction de votre activité économique et de l'activité des autres membres.</em></p>
    </div>
  </div>
</div>
<div class="container">
  <div class="corps-page">

    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li><a href="economie.php">Économie</a> <span class="divider">/</span></li>
      <li class="active">Projet bourses</li>
    </ul>

    <div class="row-fluid">
      <div class="titre-bleu anchor" id="presentation">
        <h1>Principe des Bourses mondiales</h1>
      </div>
      <div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-tips">
          <p>Certaines de vos ressources seront mises en relation entre elles et comparées avec la moyenne mondiale afin de définir une valeur de départ de votre Bourse. L'équation, qui sera dévoilée très prochainement, permettra une mise à jour spontanée et automatique de cette valeur de départ en fonction de vos infrastructures mises en ligne, mais en les comparant dorénavant à la tendance mondiale.</p>
        </div>
        <ul>
          <li>
            <h4>Le premier objectif</h4>
            <p>Le but de ce projet est de rajouter une dynamique économique et politique sur le site GC. De projeter les membres dans le phénomène de la mondialisation et de les confronter à l'évolution constante de notre Monde.</p>
          </li>
          <li>
            <h4>Le second objectif</h4>
            <p>Le second objectif est bien évidemment de booster vos créations afin de faire évoluer la valeur de votre Bourse et battre la tendance mondiale !</p>
          </li>
          <li>
            <h4>Un résultat plus que bénéfique !</h4>
            <p>Le résultat n'est pas seulement la création physique d'une Bourse dans votre pays. C'est de vous donner la possibilité qu'elle joue, pour vous, un rôle majeur et dynamique dans les relations internationales. Qu'elle vous permette d'être en perpétuel mouvement, actif voire même réactif pour ne pas vous laisser distancer.</p>
          </li>
          <li>
            <h4>La mise en place d'outils supplémentaires</h4>
            <p>Qui dit projet supplémentaire dit mise en place de nouveaux outils intéressants sur le site GC vous permettant de controler au mieux votre Bourse.</p>
          </li>
        </ul>
        <div class="alert alert-tips">
          <h4>Le tableau dynamique</h4>
            <p>La mise en place d'un tableau dynamique à la manière des bourses dans le monde réel afin que vous puissiez suivre la tendance de votre pays, du monde GC et ainsi vous réadapter au marché !</p>
        </div>
        <div class="alert alert-tips">
        <h4> En construction</h4>
          <p>En construction</p>
         </div>
        <em>
        <p>Sakuro </p>
        </em> </div>

        <script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script> 
      </div>
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
<?php
mysql_free_result($liste_temperance);
?>
