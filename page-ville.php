<?php
session_start();
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

// *** Connection BDD pays pour afficher les infos du pays de la ville
$colname_Pays = "-1";
if (isset($_GET['ch_pay_id'])) {
  $colname_Pays = $_GET['ch_pay_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_Pays = sprintf("SELECT ch_pay_id, ch_pay_publication, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_use_id FROM pays INNER JOIN users ON ch_use_paysID=ch_pay_id AND ch_use_id >=10 WHERE ch_pay_id = %s", GetSQLValueString($colname_Pays, "int"));
$Pays = mysql_query($query_Pays, $maconnexion) or die(mysql_error());
$row_Pays = mysql_fetch_assoc($Pays);
$totalRows_Pays = mysql_num_rows($Pays);

// *** Connection BDD ville pour afficher les infos de la ville
$colname_infoVille = "-1";
if (isset($_GET['ch_ville_id'])) {
  $colname_infoVille = $_GET['ch_ville_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_infoVille = sprintf("SELECT * FROM villes WHERE ch_vil_ID = %s", GetSQLValueString($colname_infoVille, "int"));
$infoVille = mysql_query($query_infoVille, $maconnexion) or die(mysql_error());
$row_infoVille = mysql_fetch_assoc($infoVille);
$totalRows_infoVille = mysql_num_rows($infoVille);

// *** Connection BDD villes pour chercher les autres villes du meme pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_Autresvilles = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_capitale, ch_vil_population FROM villes WHERE ch_vil_capitale <> 3 AND villes.ch_vil_paysID = %s ORDER BY ch_vil_date_enregistrement ASC", GetSQLValueString($colname_Pays, "int"));
$Autresvilles = mysql_query($query_Autresvilles, $maconnexion) or die(mysql_error());
$row_Autresvilles = mysql_fetch_assoc($Autresvilles);
$totalRows_Autresvilles = mysql_num_rows($Autresvilles);
$coordX=$row_infoVille['ch_vil_coord_X'];
$coordY=$row_infoVille['ch_vil_coord_ Y'];
$Text=$row_infoVille['ch_vil_nom'];

if ($totalRows_Autresvilles == $row_Autresvilles['ch_vil_ID']) {
  $row_Autresvilles = NULL;
}

 //Connexion base de données utilisateur pour info personnage
$UserID = $row_infoVille['ch_vil_user'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_User = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_biographie_dirigeant, ch_use_login, (SELECT GROUP_CONCAT(ch_disp_group_id) FROM dispatch_mem_group WHERE ch_use_id = ch_disp_mem_id AND ch_disp_mem_statut != 3) AS listgroup FROM users WHERE ch_use_id = %s", GetSQLValueString($UserID, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$listgroup = "-1";
if (isset($row_User['listgroup'])) {
$listgroup = $row_User['listgroup'];

//recherche des groupes du membre
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_group = "SELECT * FROM membres_groupes WHERE ch_mem_group_ID In ($listgroup) AND ch_mem_group_statut = 1";
$liste_group = mysql_query($query_liste_group, $maconnexion) or die(mysql_error());
$row_liste_group = mysql_fetch_assoc($liste_group);
$totalRows_liste_group = mysql_num_rows($liste_group);
}

//Recherche des monuments de la ville
mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_ID, ch_pat_paysID, ch_pat_date, ch_pat_mis_jour, ch_pat_nom, ch_pat_statut, ch_pat_lien_img1, ch_pat_description, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine WHERE ch_pat_statut = 1 AND ch_pat_villeID = %s ORDER BY ch_pat_mis_jour DESC", GetSQLValueString($colname_infoVille, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);


//Recherche de la balance des ressources de la ville
$villeid = "-1";
if (isset($row_infoVille['ch_vil_ID'])) {
$villeid = $row_infoVille['ch_vil_ID'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_somme_ressources = sprintf("SELECT SUM(ch_inf_off_budget) AS budget,SUM(ch_inf_off_Industrie) AS industrie, SUM(ch_inf_off_Commerce) AS commerce, SUM(ch_inf_off_Agriculture) AS agriculture, SUM(ch_inf_off_Tourisme) AS tourisme, SUM(ch_inf_off_Recherche) AS recherche, SUM(ch_inf_off_Environnement) AS environnement, SUM(ch_inf_off_Education) AS education FROM infrastructures_officielles INNER JOIN infrastructures ON infrastructures_officielles.ch_inf_off_id = infrastructures.ch_inf_off_id INNER JOIN villes ON ch_inf_villeid = ch_vil_ID WHERE ch_vil_ID = %s AND ch_vil_capitale != 3 AND ch_inf_statut = 2", GetSQLValueString($villeid, "int"));
$somme_ressources = mysql_query($query_somme_ressources, $maconnexion) or die(mysql_error());
$row_somme_ressources = mysql_fetch_assoc($somme_ressources);

//Recherche de la balance des ressources monument
$query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
  INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
  INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_villeID = %s", GetSQLValueString($villeid, "int"));
$monument_ressources = mysql_query($query_monument_ressources, $maconnexion) or die(mysql_error());
$row_monument_ressources = mysql_fetch_assoc($monument_ressources);

// Total ressources
$total_ressources = array('budget' => 0, 'industrie' => 0, 'commerce' => 0, 'agriculture' => 0, 'tourisme' => 0, 'recherche' => 0, 'environnement' => 0, 'education' => 0);
foreach($total_ressources as $resourceName => $value) {
    $total_ressources[$resourceName] = $row_monument_ressources[$resourceName] + $row_somme_ressources[$resourceName];
}

}

//requete Infrastructure
$maxRows_infrastructure = 8;
$pageNum_infrastructure = 0;
if (isset($_GET['pageNum_infrastructure'])) {
  $pageNum_infrastructure = $_GET['pageNum_infrastructure'];
}
$startRow_infrastructure = $pageNum_infrastructure * $maxRows_infrastructure;

mysql_select_db($database_maconnexion, $maconnexion);
$query_infrastructure = sprintf("SELECT * FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id=infrastructures_officielles.ch_inf_off_id WHERE ch_inf_villeid = %s AND ch_inf_statut =2 ORDER BY ch_inf_date DESC", GetSQLValueString($villeid, "int"));
$query_limit_infrastructure = sprintf("%s LIMIT %d, %d", $query_infrastructure, $startRow_infrastructure, $maxRows_infrastructure);
$infrastructure = mysql_query($query_infrastructure, $maconnexion) or die(mysql_error());
$row_infrastructure = mysql_fetch_assoc($infrastructure);

if (isset($_GET['totalRows_infrastructure'])) {
  $totalRows_infrastructure = $_GET['totalRows_infrastructure'];
} else {
  $all_infrastructure = mysql_query($query_infrastructure);
  $totalRows_infrastructure = mysql_num_rows($all_infrastructure);
}
$totalPages_infrastructure = ceil($totalRows_infrastructure/$maxRows_infrastructure)-1;

$queryString_infrastructure = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_infrastructure") == false && 
        stristr($param, "totalRows_infrastructure") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_infrastructure = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_infrastructure = sprintf("&totalRows_infrastructure=%d%s", $totalRows_infrastructure, $queryString_infrastructure);

$_SESSION['last_work'] = 'page-ville.php?ch_pay_id='.$row_infoVille['ch_vil_paysID'].'&ch_ville_id='.$row_infoVille['ch_vil_ID'];


//recherche de la note temperance
if (isset($colname_Pays)) {
mysql_select_db($database_maconnexion, $maconnexion);
$query_temperance = sprintf("SELECT * FROM temperance WHERE ch_temp_element_id = %s AND ch_temp_element = 'ville' AND ch_temp_statut='3'", GetSQLValueString($villeid, "int"));
$temperance = mysql_query($query_temperance, $maconnexion) or die(mysql_error());
$row_temperance = mysql_fetch_assoc($temperance);
}
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC-<?php echo $row_infoVille['ch_vil_nom']; ?></title>
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
	background-image: url('assets/img/ImgIntroheader.jpg');
}
#map {
	height: 500px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
 @media (min-width: 0px) {
#info {
	height: 415px;
	width: 93%;
	background-color: transparent;
	margin-left: 18px;
	margin-right: 18px;
	color: rgba(255,255,255,1);
	display: block;
}
#info .fiche {
	background-color: #1d459d;
}
}
@media (max-width: 480px) {
#map {
	height: 260px;
}
}
</style>
<!-- Le javascript
    ================================================== -->
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
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('php/carteville.php'); ?>
<script> 
 $( document ).ready(function() {
init();
});
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

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" <?php if ($row_Pays['ch_pay_publication']== 2) { echo 'class="sepia" id="contain"';} ?>>
<!-- Navbar
    ================================================== -->
<?php $pays=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header id="info-ville" class="jumbotron subhead anchor"> 
  <!-- Titre  et Carousel
    ================================================== -->
  <div class="container container-carousel">
    <?php if ($row_infoVille['ch_vil_lien_img1'] OR $row_infoVille['ch_vil_lien_img2'] OR $row_infoVille['ch_vil_lien_img3'] OR $row_infoVille['ch_vil_lien_img4'] OR $row_infoVille['ch_vil_lien_img5']) { ?>
    <h1 class="titre-caroussel"><?php echo $row_infoVille['ch_vil_nom']; ?></h1>
    <section id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <?php if ($row_infoVille['ch_vil_lien_img1']) { ?>
        <div class="item active" style="background-image: url(<?php echo $row_infoVille['ch_vil_lien_img1']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_infoVille['ch_vil_legende_img1']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_infoVille['ch_vil_lien_img2']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_infoVille['ch_vil_lien_img2']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_infoVille['ch_vil_legende_img2']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_infoVille['ch_vil_lien_img3']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_infoVille['ch_vil_lien_img3']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_infoVille['ch_vil_legende_img3']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_infoVille['ch_vil_lien_img4']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_infoVille['ch_vil_lien_img4']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_infoVille['ch_vil_legende_img4']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_infoVille['ch_vil_lien_img5']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_infoVille['ch_vil_lien_img5']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_infoVille['ch_vil_legende_img5']; ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </section>
    <!-- Titre si pas de carrousel
    ================================================== -->
    <?php } else { ?>
    <h1><?php echo $row_infoVille['ch_vil_nom']; ?></h1>
    <?php } ?>
  </div>
</header>
<div class="container"> 
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-ville">
          <?php if ($row_infoVille['ch_vil_armoiries']) { ?>
          <img src="<?php echo $row_infoVille['ch_vil_armoiries']; ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?php echo $row_infoVille['ch_vil_nom']; ?></strong></p>
          <p><em>Cr&eacute;&eacute;e par <?php echo $row_User['ch_use_login']; ?></em></p>
          </a></li>
        <?php if($row_User['ch_use_id'] != $row_Pays['ch_use_id']) { ?>
        <li><a href="#diplomatie">Diplomatie</a></li>
        <?php } ?>
        <li><a href="#communiques">Communiqu&eacute;s</a></li>
        <?php if ($row_infoVille['ch_vil_header']) { ?>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <?php } ?>
        <li><a href="#carte">Carte</a></li>
        <?php if ($row_infrastructure || $row_monument) { ?>
        <li><a href="#Economie">Économie</a></li>
        <?php } ?>
        <li><a href="#Journal">Journal</a></li>
        <?php if ($row_infoVille['ch_vil_administration']) { ?>
        <li><a href="#politique">Politique et administration</a></li>
        <?php } ?>
        <?php if ($row_infoVille['ch_vil_transports']) { ?>
        <li><a href="#transports">Transports</a></li>
        <?php } ?>
        <?php if ($row_monument || $row_infoVille['ch_vil_culture']) { ?>
        <li><a href="#patrimoine">Patrimoine</a></li>
        <?php } ?>
        <li><a href="#commentaires">Visites</a></li>
        <li><a href="page-pays.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>"><?php echo $row_Pays['ch_pay_nom']; ?></a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">
      <?php if ($row_temperance) { ?>
      <a class="btn btn-primary" href="php/temperance-rapport-pays.php?ch_temp_id=<?php echo $row_temperance['ch_temp_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le d&eacute;tail de cette note">Note des juges&nbsp;: <?php echo get_note_finale($row_temperance['ch_temp_note']); ?>
      <?php	if ($row_temperance['ch_temp_tendance'] == "sup") { ?>
      <i class="icon-arrow-up icon-white"></i>
      <?php } elseif ($row_temperance['ch_temp_tendance'] == "inf") { ?>
      <i class="icon-arrow-down icon-white"></i>
      <?php } else { ?>
      <i class=" icon-arrow-right icon-white"></i>
      <?php } ?>
      </a>
      <?php } ?>
      <!-- Si c'est une ville d'un pays archive
    ================================================== -->
      <?php if ($row_Pays['ch_pay_publication'] == 2) { ?>
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4>Cette ville appartient &agrave; un pays qui n'est plus actif, elle fait partie de l'histoire du Monde GC</h4>
      </div>
      <?php } ?>
      <!-- Moderation
     ================================================== -->
      <?php if (($_SESSION['statut'] >= 20) OR ($row_User['ch_use_id'] == $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="back/ville_confirmation_supprimer.php" method="post">
        <input name="ville-ID" type="hidden" value="<?php echo $row_infoVille['ch_vil_ID']; ?>">
        <button class="btn btn-danger" type="submit" title="supprimer cette ville"><i class="icon-trash icon-white"></i></button>
      </form>
      <form class="pull-right" action="back/ville_modifier.php" method="post">
        <input name="ville-ID" type="hidden" value="<?php echo $row_infoVille['ch_vil_ID']; ?>">
        <button class="btn btn-danger" type="submit" title="modifier la page de cette ville"><i class="icon-pencil icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="php/partage-ville.php?ch_vil_ID=<?php echo $row_infoVille['ch_vil_ID']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Forum</a>
      <?php } ?>
      <!-- Cadre info ville
    ================================================== -->
      <section>
        <div class="row-fluid"> 
          <!-- Armoiries
    ================================================== -->
          <div class="span4 thumb thumb-ville">
            <?php if ($row_infoVille['ch_vil_armoiries']) { ?>
            <img src="<?php echo $row_infoVille['ch_vil_armoiries']; ?>">
            <?php } else { ?>
            <img src="assets/img/imagesdefaut/blason.jpg">
            <?php }?>
          </div>
          <div class="span8">
            <h3>Informations&nbsp;:&nbsp;</h3>
            <div class="well"> <img class="thumb-drapeau" src="<?php echo $row_Pays['ch_pay_lien_imgdrapeau']; ?>">
              <h4>
                <?php if ($row_infoVille['ch_vil_capitale']==1) {
	    echo "capitale";
   } else { echo "ville"; } ?>
                du pays <a href="page-pays.php?ch_pay_id=<?php echo $row_Pays['ch_pay_id']; ?>"><?php echo $row_Pays['ch_pay_nom']; ?></a></h4>
              <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;:</strong> le
                <?php  echo date("d/m/Y à G:i", strtotime($row_infoVille['ch_vil_mis_jour'])); ?>
              </p>
              <p><strong>Date de recensement dans le monde GC&nbsp;:</strong> le
                <?php  echo date("d/m/Y à G:i", strtotime($row_infoVille['ch_vil_date_enregistrement'])); ?>
              </p>
              <p><strong>Population&nbsp;:</strong>
                <?php 
	$population_ville_francais = number_format($row_infoVille['ch_vil_population'], 0, ',', ' ');
	echo $population_ville_francais; ?>
                habitants</p>
              <p><strong>Sp&eacute;cialit&eacute;&nbsp;:</strong> <?php echo $row_infoVille['ch_vil_specialite']; ?></p>
            </div>
            <h3>R&eacute;alis&eacute;e avec&nbsp;:&nbsp;</h3>
            <div class="well">
              <?php if($row_infoVille['ch_vil_type_jeu'] == 'CL') { ?>
              <img src="assets/img/jeux-ico/cl.png" class="img-jeu">
              <?php } elseif ($row_infoVille['ch_vil_type_jeu'] == 'CXL'){ ?>
              <img src="assets/img/jeux-ico/cxl.png" class="img-jeu">
              <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='SC5'){ ?>
              <img src="assets/img/jeux-ico/sc5.png" class="img-jeu">
              <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='SC4'){ ?>
              <img src="assets/img/jeux-ico/sc4.png" class="img-jeu">
              <?php } else { ?>
              <p>Information sur le jeu manquante</p>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
      <!-- Diplomatie
     ================================================== -->
      <?php if($row_User['ch_use_id'] != $row_Pays['ch_use_id']) { ?>
      <section>
        <div id="diplomatie" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png">
          <h1>Diplomatie</h1>
        </div>
        <div class="row-fluid">
          <div class="span4 thumb">
            <?php if ($row_User['ch_use_lien_imgpersonnage']) {?>
            <img src="<?php echo $row_User['ch_use_lien_imgpersonnage']; ?>" alt="<?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?>" title="<?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?>">
            <?php } else { ?>
            <img src="assets/img/imagesdefaut/personnage.jpg" alt="personnage par default">
            <?php } ?>
            <div class="titre-gris">
              <?php if ($row_User['ch_use_nom_dirigeant'] OR $row_User['ch_use_prenom_dirigeant']) {?>
              <h3><?php echo $row_User['ch_use_nom_dirigeant']; ?> <?php echo $row_User['ch_use_prenom_dirigeant']; ?></h3>
              <?php } else { ?>
              <h3>Pas de dirigeant</h3>
              <?php } ?>
            </div>
          </div>
          <div class="span8">
            <h3>Maire de la ville&nbsp;:</h3>
            <div class="well">
              <p><i><?php echo $row_User['ch_use_predicat_dirigeant']; ?></i></p>
              <p><i><?php echo $row_User['ch_use_titre_dirigeant']; ?></i></p>
            </div>
            <?php if ($row_User['listgroup']) { ?>
            <h3>Groupes politiques</h3>
            <div class="row-fluid">
              <?php do { ?>
                <!-- Icone et popover de la categorie -->
                <div class="span2 icone-categorie"><a href="#" rel="clickover" title="<?php echo $row_liste_group['ch_mem_group_nom']; ?>" data-placement="top" data-content="<?php echo $row_liste_group['ch_mem_group_desc']; ?>"><img src="<?php echo $row_liste_group['ch_mem_group_icon']; ?>" alt="icone <?php echo $row_liste_group['ch_mem_group_nom']; ?>" style="background-color:<?php echo $row_liste_group['ch_mem_group_couleur']; ?>;"></a></div>
                <?php } while ($row_liste_group = mysql_fetch_assoc($liste_group)); ?>
            </div>
            <?php } ?>
            <?php if ($row_User['ch_use_biographie_dirigeant']) { ?>
            <h3>Biographie&nbsp;:</h3>
            <div class="well">
              <p><?php echo $row_User['ch_use_biographie_dirigeant']; ?></p>
            </div>
          </div>
          <?php } ?>
        </div>
      </section>
      <?php } ?>
      <!-- Communiqués
        ================================================== -->
      <section>
        <div id="communiques" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Communique.png">
          <h1>Communiqu&eacute;s</h1>
        </div>
        <?php 
	$ch_com_user_id = $row_User['ch_use_id'];
	 $ch_com_categorie = 'ville';
	  $ch_com_element_id = $colname_infoVille;
	  include('php/communiques.php'); ?>
      </section>
      <!-- Presentation
    ================================================== -->
      <?php if ($row_infoVille['ch_vil_header']) { ?>
      <section>
        <div id="presentation" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Ville1.png">
          <h1>Pr&eacute;sentation</h1>
        </div>
        <div class="well">
          <p><?php echo $row_infoVille['ch_vil_header']; ?></p>
        </div>
      </section>
      <?php } ?>
      
      <!-- carte
    ================================================== -->
      <section>
        <div class="titre-vert anchor" id="carte"> <img src="assets/img/IconesBDD/100/carte.png">
          <h1>Carte</h1>
        </div>
        <div>
          <p>&nbsp;</p>
        </div>
        <div class="row-fluid">
          <div id="Autresvilles" class="span6">
            <?php if ($row_Autresvilles) { ?>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S&eacute;lectionnez une autre ville :</p>
            <form class="well" action="page-ville.php#carte" method="get">
              <input type="hidden" name="ch_pay_id" value="<?php echo $row_infoVille['ch_vil_paysID']; ?>" />
              <select name="ch_ville_id" onchange="this.form.submit()">
                <?php do { ?>
                <option value="<?php echo $row_Autresvilles['ch_vil_ID']; ?>" <?php if ($colname_infoVille == $row_Autresvilles['ch_vil_ID']) {?>selected<?php } ?>><?php echo $row_Autresvilles['ch_vil_nom']; ?></option>
                <?php } while ($row_Autresvilles = mysql_fetch_assoc($Autresvilles)); ?>
              </select>
            </form>
            <?php } else { ?>
            <p>Ce pays n'a pas d'autres villes</p>
            <?php }?>
            <div id="info"></div>
          </div>
          <div id="map" class="span6"></div>
        </div>
      </section>
      <!-- Economie
    ================================================== -->
      <?php if ($row_infrastructure || $row_monument) { ?>
      <section>
        <div class="titre-vert anchor" id="Economie"> <img src="assets/img/IconesBDD/100/eco.png">
          <h1>Économie</h1>
        </div>
        <h3>Balance des ressources</h3>

          <?php renderResources($total_ressources); ?>
          <div class="clearfix"></div>

        <h3>Détail des ressources</h3>

          <h4 style="margin-left: 10px;">Infrastructures</h4>
          <?php renderResources($row_somme_ressources); ?>
          <div class="clearfix"></div>

          <h4 style="margin-left: 10px;">Patrimoine</h4>
          <?php renderResources($row_monument_ressources); ?>
          <div class="clearfix"></div>
        <!-- Liste infrasructures
    ================================================== -->
        <h3>Infrastructures de la ville</h3>
        <div class="infra-well-container">
          <?php do {

              if(!isset($row_infrastructure['ch_inf_lien_image'])) break;

               $infraData = array(
                  'id' => $row_infrastructure['ch_inf_id'],
                  'overlay_text' => 'Infrastructure',
                  'image' => $row_infrastructure['ch_inf_lien_image'],
                  'nom' => $row_infrastructure['ch_inf_off_nom'],
                  'description' => $row_infrastructure['ch_inf_off_desc']
              );

               renderElement('infrastructure_well', $infraData);

          } while ($row_infrastructure = mysql_fetch_assoc($infrastructure)); ?>
        </div>
      </section>
      <?php } ?>
      <!-- Journal
    ================================================== -->
      <section>
        <div class="titre-vert anchor" id="Journal"> <img src="assets/img/IconesBDD/100/Ville1.png">
          <h1>Journal</h1>
        </div>
        <div class="well">
            <?php if(!$row_infoVille['ch_vil_contenu']) { ?>
                <p><i>La présentation de cette ville est vide !</i></p>
            <?php } ?>
            <?php echo $row_infoVille['ch_vil_contenu']; ?>
        </div>
      </section>

        <!-- Politique et administration
    ================================================== -->
    <?php if($row_infoVille['ch_vil_administration']) { ?>
      <section>
        <div class="titre-vert anchor" id="politique"> <img src="assets/img/IconesBDD/100/Ville1.png">
          <h1>Politique et administration</h1>
        </div>
        <div class="well"> <?php echo $row_infoVille['ch_vil_administration']; ?> </div>
      </section>
    <?php } ?>

        <!-- Transports
    ================================================== -->
    <?php if($row_infoVille['ch_vil_transports']) { ?>
      <section>
        <div class="titre-vert anchor" id="transports"> <img src="assets/img/IconesBDD/100/Ville1.png">
          <h1>Transports</h1>
        </div>
        <div class="well"> <?php echo $row_infoVille['ch_vil_transports']; ?> </div>
      </section>
    <?php } ?>

      <!-- Patrimoine
        ================================================== -->
      <?php if ($row_monument || $row_infoVille['ch_vil_culture']) { ?>
      <section>
        <div id="patrimoine" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/monument1.png">
          <h1>Patrimoine</h1>
        </div>
        <!-- Liste des monuments
        ================================================== -->
        <h3>Liste des monuments</h3>
        <div id="infra-well-container">
        <?php do {

			$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
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
              'id' => $row_monument['ch_pat_ID'],
              'type' => 'patrimoine',
              'overlay_image' => $row_liste_mon_cat3['ch_mon_cat_icon'],
              'overlay_text' => $overlay_text,
              'image' => $row_monument['ch_pat_lien_img1'],
              'nom' => $row_monument['ch_pat_nom'],
              'description' => $row_monument['ch_pat_description']
            );
            renderElement('infrastructure_well', $infraData);

        } while ($row_monument = mysql_fetch_assoc($monument)); ?>
        </div>
        <div class="well"> <?php echo $row_infoVille['ch_vil_culture']; ?> </div>

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
      </section>
      <?php } ?>
      
      <!-- Commentaire
        ================================================== -->
      <section>
        <div id="commentaires" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="visites">
          <h1>Visites</h1>
        </div>
        <?php 
	  $ch_com_categorie = "com_ville";
	  $ch_com_element_id = $row_infoVille['ch_vil_ID'];
	  include('php/commentaire.php'); ?>
      </section>
      <!-- END CONTENT
    ================================================== --> 
    </div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
<div class="modal container fade" id="Modal-Monument"></div>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
<?php
mysql_free_result($infoVille);

mysql_free_result($Autresvilles);

mysql_free_result($Pays);

mysql_free_result($User);

mysql_free_result($monument);

mysql_free_result($somme_ressources);

mysql_free_result($infrastructure);
?>
