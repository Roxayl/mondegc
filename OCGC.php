<?php
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

//requete instituts
$institut_id = 1;
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC- OCGC</title>
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
		<li><a href="#organigramme">Organigramme</a></li>
		<li><a href="#outils disponibles">Outils disponibles</a></li>
		<li><a href="#Missions accomplies">Missions accomplies</a></li>
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
      <!-- Organigramme
    ================================================== -->
      <section>
     <div class="titre-bleu anchor" id="organigramme"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
        <h1>Organigramme</h1>
		</div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
      </div>
      <div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>L'OCGC vous présente l'organigramme officiel de ses principaux représentants nommés également Conseillers permanents de l'OCGC.</p>
        </div>
        <ul>
          <li>
            <h4>Myname</h4>
            <p>Myname est un membre expert au courant de tout ce qui se passe sur GC et réussit à concentrer toutes les informations quotidiennes afin de nous guider. Il vient d'être nommé Coordinateur GC afin de garantir une activité correcte et la répartition des missions entre tous les responsables de notre communauté.</p>
			<p><a class="btn btn-primary" href="http://www.forum-gc.com/privmsg?mode=post&u=2345">Messagerie privée</a> <a class="btn btn-primary" href="http://generation-city.com/monde/page-pays.php?ch_pay_id=73">Coup d'oeil sur le Polaro</a></p>
          </li>
          <li>
            <h4>Vinceinovich</h4>
            <p>Vince' a été nommé responsable du Comité des Sciences et de l'Histoire Gécéenne. Véritable source de renseignements pour les RP militaires et la création d'infrastructures correspondantes, Vinceinovich sait également mettre en avant les inventions des membres.</p>
			<p><a class="btn btn-primary" href="http://www.forum-gc.com/privmsg?mode=post&u=2719">Messagerie privée</a> <a class="btn btn-primary" href="http://generation-city.com/monde/page-pays.php?ch_pay_id=98">Coup d'oeil sur Akitsu</a></p>
          </li>
          <li>
            <h4>Maori</h4>
			<p>Maori, ascension fulgurante sur le forum GC, est le responsable du Comité du Patrimoine et de la Culture. De nombreuses idées ou relances ont lieu suite à son mandat.</p>
			<p><a class="btn btn-primary" href="http://www.forum-gc.com/privmsg?mode=post&u=2393">Messagerie privée</a> <a class="btn btn-primary" href="http://generation-city.com/monde/page-pays.php?ch_pay_id=107">Coup d'oeil sur le royaume de Mapete</a></p>            
          </li>
          <li>
            <h4>Saynwen</h4>
			<p>Saynwen est le président actuel de l'OCGC. Nommé au premier trimestre 2018, il a la mission principale de concentrer toutes les idées des membres et veiller au développement du site et du forum en modernisant tous les outils mis à disposition des membres.</p>
			<p><a class="btn btn-primary" href="http://www.forum-gc.com/privmsg?mode=post&u=2497">Messagerie privée</a> <a class="btn btn-primary" href="http://generation-city.com/monde/page-pays.php?ch_pay_id=90">Coup d'oeil sur la république Norroise</a></p>           
          </li>		  
		  <li>
            <h4>Sakuro</h4>
			<p>Sakuro est le précedent président de l'OCGC. Il a la responsabilité du Comité Economique du forum et du site GC.</p>
			<p><a class="btn btn-primary" href="http://www.forum-gc.com/privmsg?mode=post&u=615">Messagerie privée</a> <a class="btn btn-primary" href="http://generation-city.com/monde/page-pays.php?ch_pay_id=39">Coup d'oeil sur le Lagos</a></p>           
          </li>
        </ul>
        <p>&nbsp;</p>
        <p></p>
        <div class="alert alert-success">
          <h4>Missions principales</h4>
            <p>La mission principale de l'OCGC est de développer le site et le forum afin de permettre aux membres de trouver leur place dans une communauté francophone passionnée et également de privilégier d'outils uniques permettant de développer l'expérience des jeux de construction.</p>
        </div>
        <div class="alert alert-success">
        <h4> Evolutions 2017</h4>
          <p>Une campagne de dynamisation du site et du forum est en cours de lancement afin de vous faire profiter d'une expérience unique en son genre. Tous les membres de l'OCGC restent à votre entière disposition afin de vous aider et vous guider.</p>
         </div>
        <em>
        <p>Sakuro </p>
        </em> </div>
      </section>
	        <!-- Outils disponibles
    ================================================== -->
      <section>
     <div class="titre-bleu anchor" id="outils disponibles"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
        <h1>Outils disponibles</h1>
		</div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
      </div>
      <div class="well">
        <p>&nbsp;</p>
        <div class="alert alert-success">
          <p>L'OCGC vous présente les outils ou programmes disponibles pour les membres possédant un pays sur GC et les membres actifs du forum éponyme.</p>
        </div>
        <ul>
          <li>
            <h4>Territoires d'Outre-Mer (TOM) - Mise à jour 2017 </h4>
            <p>Les Territoires d'Outre Mer (TOM) sont des territoires en dehors des continents classiques pouvant être adoptés par des pays du Monde GC. Pour espérer obtenir un TOM, il faut que le dirigeant du pays volontaire renseigne :
			<p>
<li>Le nom de la métropole et du futur territoire convoité (avec sa localisation dans le Monde GC grâce au numéro)</li>
<li>Une présentation détaillée du territoire à travers son histoire, son économie etc.</li>
<li>La présentation d'au moins une ville sur le forum qui sera par la suite intégrée au territoire comme chef-lieu</li>

<p></p>
<p>L'OCGC se réserve le droit de refuser une candidature sous quelques raison que ce soit.</p>
<p>Il n'y a plus de limite maximum de TOM pour un pays, mais une solide justification sera demandée pour éviter les abus.</li>
<p>Une candidature peut être actualisée et complétée autant de fois que le membre le veux pour la faire accepter et doit se faire sur le sujet officiel des TOM, le jugement se fait après demande du membre sur le topic de recensement du Monde GC (comme pour les pays, puisque ce sont les mêmes personnes qui s'en occupent).</li>

<p>Pour améliorer l'attractivité des TOM, un statut spécial serait créé sous forme d'une infrastructure généreuse en ressources.</p>
</p></div>
      </section>
	  
	        <!-- Missions accomplies
    ================================================== -->
      <section>
     <div class="titre-bleu anchor" id="Missions accomplies"> <img src="assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
        <h1>Missions accomplies</h1>
		</div>
        <div class="well">
          <div class="row-fluid">
            <div class="span7">
      </div>
      <div class="well">
        <p>&nbsp;</p>
                <div class="alert alert-success">
          <h4>Modification des profils pour lier le statut des membres et leurs rôles respectifs RP.</h4>
            <p>Faire un lien entre le site et le forum GC en permettant aux membres de fusionner les informations d'un même membre et le resituer dans le role play (RP).</p>
        </div>
        <div class="alert alert-success">
        <h4>Un organigramme à jour et efficace des postes occupés.</h4>
          <p>Savoir à qui s'adresser pour toute question importante concernant le fonctionnement de la Communauté.</p>
		  </div>
		  <div class="alert alert-success">
          <h4>La mise à jour des statuts et des couleurs par Comité. </h4>
            <p>Visibilité accrue et mise à jour des membres actifs et responsables du site et du forum GC</p>
			</div>
			<div class="alert alert-success">
          <h4>L'homogénéisation des icônes Eiffel entre autre.</h4>
            <p>Mise à niveau des statuts Eiffel sur le profil des membres. Nouvelle version mise en place avec le nouveau forum à prévoir cependant. Recherche de modernité.</p>
         </div>
		 <div class="alert alert-success">
          <h4>Messages divers et automatiques pour des évènements précis.</h4>
            <p>Messages de bienvenue et messages pour les anniversaires automatisés.</p>
         </div>
		  <div class="alert alert-success">
          <h4>Modifications des textes pour les moteurs de recherches.</h4>
            <p>Mise à jour des informations visibles sur les moteurs de recherches afin d'être plus lisible et visible sur internet.</p>
         </div>
		 <div class="alert alert-success">
          <h4>Intégration des nouveaux élus</h4>
            <p>Faciliter l'intégration des anciens ou nouveaux élus au site GC en leur donnant accès à leur comité de façon immédiate sur le site et le forum.</p>
         </div>
		 <div class="alert alert-success">
          <h4>Mise à jour de la Charte du site et du forum</h4>
            <p>Cohérence avec les nouveaux outils mis en place par l'OCGC et les membres sur le site et le forum. Homogénéisation des informations sur tous les supports</p>
		</div>
		 <div class="alert alert-success">
          <h4>Principe de newletters adopté</h4>
            <p>Ecrire aux membres actifs ou inactifs afin de leur faire part des nouveautés et les reconquérir.</p>
         </div>
        <p>Sakuro </p>
        </em> </div>
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