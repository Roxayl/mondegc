<?php

use App\Models\Infrastructure as EloquentInfrastructure;

//Connexion et deconnexion
include('php/log.php');

// *** Connection BDD ville pour afficher les infos de la ville
$colname_infoVille = "-1";
if (isset($_GET['ch_ville_id'])) {
  $colname_infoVille = $_GET['ch_ville_id'];
}

$query_infoVille = sprintf("SELECT * FROM villes WHERE ch_vil_ID = %s", GetSQLValueString($colname_infoVille, "int"));
$infoVille = mysql_query($query_infoVille, $maconnexion) or die(mysql_error());
$row_infoVille = mysql_fetch_assoc($infoVille);
$totalRows_infoVille = mysql_num_rows($infoVille);

$query_Pays = sprintf("SELECT ch_pay_id, ch_pay_publication, ch_pay_continent, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_use_id FROM pays INNER JOIN users ON ch_use_paysID=ch_pay_id AND ch_use_id >=10 WHERE ch_pay_id = %s", GetSQLValueString($row_infoVille['ch_vil_paysID'], "int"));
$Pays = mysql_query($query_Pays, $maconnexion) or die(mysql_error());
$row_Pays = mysql_fetch_assoc($Pays);
$totalRows_Pays = mysql_num_rows($Pays);

// *** Connection BDD pays pour afficher les infos du pays de la ville
$colname_Pays = $row_infoVille['ch_vil_paysID'];

// *** Connection BDD villes pour chercher les autres villes du meme pays

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

$query_User = sprintf("SELECT ch_use_id, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_biographie_dirigeant, ch_use_login, (SELECT GROUP_CONCAT(ch_disp_group_id) FROM dispatch_mem_group WHERE ch_use_id = ch_disp_mem_id AND ch_disp_mem_statut != 3) AS listgroup FROM users WHERE ch_use_id = %s", GetSQLValueString($UserID, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$listgroup = "-1";
if (isset($row_User['listgroup'])) {
$listgroup = $row_User['listgroup'];

//recherche des groupes du membre

$query_liste_group = "SELECT * FROM membres_groupes WHERE ch_mem_group_ID In ($listgroup) AND ch_mem_group_statut = 1";
$liste_group = mysql_query($query_liste_group, $maconnexion) or die(mysql_error());
$row_liste_group = mysql_fetch_assoc($liste_group);
$totalRows_liste_group = mysql_num_rows($liste_group);
}

//Recherche des monuments de la ville
$query_monument = sprintf("SELECT ch_pat_ID, ch_pat_paysID, ch_pat_date, ch_pat_mis_jour, ch_pat_nom, ch_pat_statut, ch_pat_lien_img1, ch_pat_description, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine WHERE ch_pat_villeID = %s ORDER BY ch_pat_mis_jour DESC", GetSQLValueString($colname_infoVille, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

//Recherche de la balance des ressources de la ville
$villeid = "-1";
if(isset($row_infoVille['ch_vil_ID'])) {
    $villeid = $row_infoVille['ch_vil_ID'];
}

//requete Infrastructure
$maxRows_infrastructure = 8;
$pageNum_infrastructure = 0;
if (isset($_GET['pageNum_infrastructure'])) {
  $pageNum_infrastructure = $_GET['pageNum_infrastructure'];
}
$startRow_infrastructure = $pageNum_infrastructure * $maxRows_infrastructure;


$query_infrastructure = sprintf(
    "SELECT * FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id=infrastructures_officielles.ch_inf_off_id WHERE ch_inf_villeid = %s AND infrastructurable_type = %s AND ch_inf_statut =2 ORDER BY ch_inf_date DESC",
    GetSQLValueString($villeid, "int"),
    GetSQLValueString(EloquentInfrastructure::getMorphFromUrlParameter('ville'), "text")
);
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

$query_temperance = sprintf("SELECT * FROM temperance WHERE ch_temp_element_id = %s AND ch_temp_element = 'ville' AND ch_temp_statut='3'", GetSQLValueString($villeid, "int"));
$temperance = mysql_query($query_temperance, $maconnexion) or die(mysql_error());
$row_temperance = mysql_fetch_assoc($temperance);
}

$thisVille = new \GenCity\Monde\Ville($_GET['ch_ville_id']);
$eloquentVille = \App\Models\Ville::findOrFail($_GET['ch_ville_id']);

// Ressources
$total_ressources = $eloquentVille->resources();
$row_patrimoine_ressources = $eloquentVille->patrimoineResources();
$row_infra_ressources = $eloquentVille->infrastructureResources();

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - <?= __s($row_infoVille['ch_vil_nom']) ?></title>
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
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    });
</script>
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('php/carteville.php'); ?>
<script>
    $(document).ready(function () {
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
    <div class="titre-caroussel-container">
    <h1 class="titre-caroussel"><?= e($row_infoVille['ch_vil_nom']) ?></h1>
    </div>
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
    <h1><?= e($row_infoVille['ch_vil_nom']) ?></h1>
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
          <img src="<?= e($row_infoVille['ch_vil_armoiries']) ?>">
          <?php } else { ?>
          <img src="assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?= e($row_infoVille['ch_vil_nom']) ?></strong></p>
          <p><em>Cr&eacute;&eacute;e par <?= e($row_User['ch_use_login']) ?></em></p>
          </a></li>
        <?php if($row_User['ch_use_id'] != $row_Pays['ch_use_id']) { ?>
        <li><a href="#diplomatie">Diplomatie</a></li>
        <?php } ?>
        <li><a href="#presentation">Pr&eacute;sentation</a></li>
        <li><a href="#communiques">Communiqu&eacute;s</a></li>
        <?php if ($row_infoVille['ch_vil_capitale']==4) { ?><?php } else { ?><li><a href="#carte">Carte</a></li><?php }?>
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
        <li><a href="#culture">Culture</a></li>
        <?php } ?>
        <li><a href="#quetes">Quêtes</a></li>
        <li><a href="#commentaires">Visites</a></li>
        <li><a href="page-pays.php?ch_pay_id=<?= e($row_Pays['ch_pay_id']) ?>"><?= e($row_Pays['ch_pay_nom']) ?></a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">

    <ul class="breadcrumb pull-left">
      <li><a href="Page-carte.php#liste-pays">Pays</a> <span class="divider">/</span></li>
      <li><a href="page-pays.php?ch_pay_id=<?= e($row_Pays['ch_pay_id']) ?>"><?= e($row_Pays['ch_pay_nom']) ?></a> <span class="divider">/</span></li>
        <li><a href="page-pays.php?ch_pay_id=<?= e($row_Pays['ch_pay_id']) ?>#villes">Villes</a> <span class="divider">/</span></li>
      <li class="active"><?= e($row_infoVille['ch_vil_nom']) ?></li>
    </ul>

      <?php if ($row_temperance) { ?>
      <a class="btn btn-primary" href="php/temperance-rapport-pays.php?ch_temp_id=<?= e($row_temperance['ch_temp_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le d&eacute;tail de cette note">Note des juges&nbsp;: <?php echo get_note_finale($row_temperance['ch_temp_note']); ?>
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
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/ville_confirmation_supprimer.php" method="post">
        <input name="ville-ID" type="hidden" value="<?= e($row_infoVille['ch_vil_ID']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer cette ville"><i class="icon-trash icon-white"></i></button>
      </form>
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/ville_modifier.php" method="get">
        <input name="ville-ID" type="hidden" value="<?= e($row_infoVille['ch_vil_ID']) ?>">
        <button class="btn btn-primary" type="submit" title="modifier la page de cette ville"><i class="icon-pencil icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="php/partage-ville.php?ch_vil_ID=<?= e($row_infoVille['ch_vil_ID']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Partager sur le forum</a>
      <?php } ?>
      <div class="clearfix"></div>


      <?php
      ob_start();
      ?>
      <!-- Cadre info ville
    ================================================== -->
    <div class="row-fluid">
      <!-- Armoiries
================================================== -->
      <div class="span12 thumb thumb-ville">
        <?php if ($row_infoVille['ch_vil_armoiries']) { ?>
        <img src="<?= e($row_infoVille['ch_vil_armoiries']) ?>">
        <?php } else { ?>
        <img src="assets/img/imagesdefaut/blason.jpg">
        <?php }?>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <h4>Informations</h4>
        <div class="well"> <img class="thumb-drapeau" src="<?= e($row_Pays['ch_pay_lien_imgdrapeau']) ?>">
          <strong>
            <?php if ($row_infoVille['ch_vil_capitale']==1) {
    echo "Capitale";} ?><?php if ($row_infoVille['ch_vil_capitale']==2) {echo "Ville";} ?><?php if ($row_infoVille['ch_vil_capitale']==4) {echo "Entité";} ?>
            de <a href="page-pays.php?ch_pay_id=<?= e($row_Pays['ch_pay_id']) ?>"><?= e($row_Pays['ch_pay_nom']) ?></a></strong>
          <p><strong>Derni&egrave;re mise &agrave; jour&nbsp;:</strong>
            <?php  echo date("d/m/Y", strtotime($row_infoVille['ch_vil_mis_jour'])); ?>
          </p>
          <p><strong>Date de recensement dans le monde GC&nbsp;:</strong>
            <?php  echo date("d/m/Y", strtotime($row_infoVille['ch_vil_date_enregistrement'])); ?>
          </p>
          <?php if ($row_infoVille['ch_vil_capitale']!==4) { ?><p><strong>Population :</strong>
            <?php
$population_ville_francais = number_format($row_infoVille['ch_vil_population'], 0, ',', ' ');
echo $population_ville_francais; ?></p><?php } else { ?><?php }?>
          <p><strong>Sp&eacute;cialit&eacute;&nbsp;:</strong> <?= e($row_infoVille['ch_vil_specialite']) ?></p>
        </div>
        <h4>R&eacute;alis&eacute;e avec</h4>
        <div class="well">
          <?php if($row_infoVille['ch_vil_type_jeu'] == 'CL') { ?>
          <img src="assets/img/jeux-ico/cl.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] == 'CXL'){ ?>
          <img src="assets/img/jeux-ico/cxl.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] == 'SKY'){ ?>
          <img src="assets/img/jeux-ico/sky.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] == 'SIM'){ ?>
          <img src="assets/img/jeux-ico/sim.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='SC5'){ ?>
          <img src="assets/img/jeux-ico/sc5.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='SC4'){ ?>
          <img src="assets/img/jeux-ico/sc4.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='SC3'){ ?>
          <img src="assets/img/jeux-ico/sc3.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='TAP'){ ?>
          <img src="assets/img/jeux-ico/tap.png" class="img-jeu">
          <?php } elseif ($row_infoVille['ch_vil_type_jeu'] =='MFT'){ ?>
          <img src="assets/img/jeux-ico/mft.png" class="img-jeu">
          <?php } else { ?>
          <p>Information sur le jeu manquante</p>
          <?php } ?>
        </div>
      </div>
    </div>

      <?php
      $infobox_contents = ob_get_clean();

      renderElement('infobox', array(
          'title' => __s($row_infoVille['ch_vil_nom']),
          'contents' => $infobox_contents
      ));
      ?>


      <!-- Presentation
    ================================================== -->
      <section id="presentation" class="titre-vert anchor">
        <div class="well">
          <p><?= htmlPurify($row_infoVille['ch_vil_header']) ?></p>
        </div>
      </section>

      <!-- Communiqués
        ================================================== -->
      <section>
        <div id="communiques" class="titre-vert anchor">
          <h1 style='background-image: url("assets/img/bg-titre-left.png"); background-position: left;'
            >Communiqu&eacute;s</h1>
        </div>
        <div class="span7" style="margin: 0; margin-right: -12px;">
        <?php 
	$ch_com_user_id = $row_User['ch_use_id'];
	 $ch_com_categorie = 'ville';
	  $ch_com_element_id = $colname_infoVille;
	  include('php/communiques.php'); ?>
        </div>
      </section>

      <div class="clearfix"></div>
      
      <!-- carte
    ================================================== -->
      <?php if ($row_infoVille['ch_vil_capitale']==4) { ?><?php } else { ?>
      <section>
        <div class="titre-vert anchor" id="carte">
          <h1>Carte</h1>
        </div>
        <div>
          <p>&nbsp;</p>
        </div>
        <div class="row-fluid">
          <div id="Autresvilles" class="span5">
            <?php if ($row_Autresvilles) { ?>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S&eacute;lectionnez une autre ville :</p>
            <form class="well" action="<?= DEF_URI_PATH ?>page-ville.php#carte" method="get">
              <input type="hidden" name="ch_pay_id" value="<?= e($row_infoVille['ch_vil_paysID']) ?>" />
              <select name="ch_ville_id" onchange="this.form.submit()">
                <?php do { ?>
                <option value="<?= e($row_Autresvilles['ch_vil_ID']) ?>" <?php if ($colname_infoVille == $row_Autresvilles['ch_vil_ID']) {?>selected<?php } ?>><?= e($row_Autresvilles['ch_vil_nom']) ?></option>
                <?php } while ($row_Autresvilles = mysql_fetch_assoc($Autresvilles)); ?>
              </select>
            </form>
            <?php } else { ?>
            <p>Ce pays n'a pas d'autres villes</p>
            <?php }?>
            <div id="info"></div>
          </div>
          <div id="map" class="span7"></div>
        </div>
      </section>
      <?php }?>
      <!-- Economie
    ================================================== -->
      <?php if ($row_infrastructure || $row_monument) { ?>
      <section>
        <div class="titre-vert anchor" id="Economie">
          <h1>Économie</h1>
        </div>
        <h3>Balance des ressources</h3>
        <div class="well">
          <?php
            renderElement('temperance/resources', array(
                'resources' => $total_ressources
            ));
          ?>
          <div class="clearfix"></div>

          <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#economie-detail">
                    Détail de la balance des ressources
                </a>
            </div>
            <div id="economie-detail" class="accordion-body collapse">
            <div class="accordion-inner">
              <h4><i class="icon-road"></i> Infrastructures</h4>
                <?php
                renderElement('temperance/resources_small', array(
                    'resources' => $row_infra_ressources
                ));
                ?>
                <p></p>
              <h4><i class="icon-star"></i> Quêtes</h4>
                <?php
                renderElement('temperance/resources_small', array(
                    'resources' => $row_patrimoine_ressources
                ));
                ?>
                <div class="clearfix"></div>
            </div>
            </div>
          </div>
        </div>

        <!-- Liste infrasructures
    ================================================== -->
        <h3>Infrastructures de la ville</h3>
        <div class="infra-well-container">
          <?php do {

              if(!isset($row_infrastructure['ch_inf_lien_image'])) break;

               $infraData = array(
                  'id' => $row_infrastructure['ch_inf_id'],
                  'overlay_image' => $row_infrastructure['ch_inf_off_icone'],
                  'overlay_text' => $row_infrastructure['ch_inf_off_nom'],
                  'image' => $row_infrastructure['ch_inf_lien_image'],
                  'nom' => $row_infrastructure['nom_infra'],
                  'description' => $row_infrastructure['ch_inf_commentaire']
              );

               renderElement('infrastructure/well', $infraData);

          } while ($row_infrastructure = mysql_fetch_assoc($infrastructure)); ?>
        </div>
      </section>
      <?php } ?>
      <!-- Journal
    ================================================== -->
      <section>
        <div class="titre-vert anchor" id="Journal">
          <h1>Journal</h1>
        </div>
        <div class="well">
            <?php if(!$row_infoVille['ch_vil_contenu']) { ?>
                <p><i>La présentation de cette ville est vide !</i></p>
            <?php } ?>
            <?= htmlPurify($row_infoVille['ch_vil_contenu']) ?>
        </div>
      </section>

        <!-- Politique et administration
    ================================================== -->
    <?php if($row_infoVille['ch_vil_administration']) { ?>
      <section>
        <div class="titre-vert anchor" id="politique">
          <h1>Politique et administration</h1>
        </div>
        <div class="well"> <?= htmlPurify($row_infoVille['ch_vil_administration']) ?> </div>
      </section>
    <?php } ?>

        <!-- Transports
    ================================================== -->
    <?php if($row_infoVille['ch_vil_transports']) { ?>
      <section>
        <div class="titre-vert anchor" id="transports">
          <h1>Transports</h1>
        </div>
        <div class="well"> <?= htmlPurify($row_infoVille['ch_vil_transports']) ?> </div>
      </section>
    <?php } ?>

        <!-- Culture
    ================================================== -->
    <?php if($row_infoVille['ch_vil_culture']) { ?>
      <section>
        <div class="titre-vert anchor" id="culture">
          <h1>Culture</h1>
        </div>
        <div class="well"> <?= htmlPurify($row_infoVille['ch_vil_culture']) ?> </div>
      </section>
    <?php } ?>

      <!-- Quêtes
        ================================================== -->
      <?php if ($row_monument || $row_infoVille['ch_vil_quêtes']) { ?>
      <section>
        <div class="titre-vert anchor" id="quetes">
          <h1>Quêtes</h1>
        </div>
        <!-- Liste des monuments
        ================================================== -->
        <h3>Quêtes en cours</h3>
        <div id="infra-well-container">
        <?php do {

			$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
                
                $query_liste_mon_cat3 = "SELECT * FROM monument_categories
                    WHERE ch_mon_cat_ID In ($listcategories) -- AND ch_mon_cat_statut =1--";
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
            renderElement('infrastructure/well', $infraData);

        } while ($row_monument = mysql_fetch_assoc($monument)); ?>
        </div>
        <div class="well"> <?= htmlPurify($row_infoVille['ch_vil_culture']) ?> </div>

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
        <div id="commentaires" class="titre-vert anchor">
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
</body>
</html>