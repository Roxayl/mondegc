<?php

use GenCity\Monde\Pays;
use GenCity\Monde\Ville;

//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_ville")) {

    $hasUserPermission = isset($_SESSION['userObject']);
    $thisUser = $_SESSION['userObject'];
    if($hasUserPermission) {
        /** @var \GenCity\Monde\User $thisUser */
        $hasUserPermission = $thisUser->minStatus('OCGC');
    }

    $thisVille = new Ville($_POST['ch_vil_ID']);
    $thisPays = new Pays($thisVille->ch_vil_paysID);
    if(!$hasUserPermission && $thisPays->getUserPermission() < Pays::$permissions['codirigeant']) {
        getErrorMessage('error', "Vous n'avez pas accès à cette partie.");
    }

    else {
  $updateSQL = sprintf("UPDATE villes SET ch_vil_paysID=%s, ch_vil_user=%s, ch_vil_label=%s, ch_vil_date_enregistrement=%s, ch_vil_mis_jour=%s, ch_vil_nb_update=%s, ch_vil_coord_X=%s, ch_vil_coord_Y=%s, ch_vil_type_jeu=%s, ch_vil_nom=%s, ch_vil_armoiries=%s, ch_vil_capitale=%s, ch_vil_population=%s, ch_vil_specialite=%s, ch_vil_lien_img1=%s, ch_vil_lien_img2=%s, ch_vil_lien_img3=%s, ch_vil_lien_img4=%s, ch_vil_lien_img5=%s, ch_vil_legende_img1=%s, ch_vil_legende_img2=%s, ch_vil_legende_img3=%s, ch_vil_legende_img4=%s, ch_vil_legende_img5=%s, ch_vil_header=%s, ch_vil_contenu=%s, ch_vil_transports=%s, ch_vil_administration=%s, ch_vil_culture=%s WHERE ch_vil_ID=%s",
                       GetSQLValueString($_POST['ch_vil_paysID'], "int"),
                       GetSQLValueString($_POST['ch_vil_user'], "int"),
                       GetSQLValueString($_POST['ch_vil_label'], "text"),
                       GetSQLValueString($_POST['ch_vil_date_enregistrement'], "date"),
                       GetSQLValueString($_POST['ch_vil_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_vil_nb_update'], "int"),
                       GetSQLValueString($_POST['form_coord_X'], "decimal"),
                       GetSQLValueString($_POST['form_coord_Y'], "decimal"),
					   GetSQLValueString($_POST['ch_vil_type_jeu'], "text"),
                       GetSQLValueString($_POST['ch_vil_nom'], "text"),
					   GetSQLValueString($_POST['ch_vil_armoiries'], "text"),
                       GetSQLValueString($_POST['ch_vil_capitale'], "int"),
                       GetSQLValueString($_POST['ch_vil_population'], "int"),
                       GetSQLValueString($_POST['ch_vil_specialite'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img1'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img2'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img3'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img4'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img5'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img1'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img2'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img3'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img4'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img5'], "text"),
                       GetSQLValueString($_POST['ch_vil_header'], "text"),
                       GetSQLValueString($_POST['ch_vil_contenu'], "text"),
                       GetSQLValueString($_POST['ch_vil_transports'], "text"),
                       GetSQLValueString($_POST['ch_vil_administration'], "text"),
                       GetSQLValueString($_POST['ch_vil_culture'], "text"),
					   GetSQLValueString($_POST['ch_vil_ID'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

        getErrorMessage('success', "Ville modifiée avec succès.");
    }

  $updateGoTo = DEF_URI_PATH . "back/ville_modifier.php";
  appendQueryString($updateGoTo);
  header(sprintf("Location: %s", $updateGoTo));
  exit;
}

//requete Villes

if (isset($_REQUEST['ville-ID'])) {
	$_SESSION['ville_encours'] = $_REQUEST['ville-ID'];
	unset($_REQUEST['ville-ID']);
}

$query_ville = sprintf("SELECT * FROM villes INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_vil_ID = %s", GetSQLValueString($_SESSION['ville_encours'], "int"));
$ville = mysql_query($query_ville, $maconnexion) or die(mysql_error());
$row_ville = mysql_fetch_assoc($ville);
$totalRows_ville = mysql_num_rows($ville);

$paysID = $row_ville['ch_vil_paysID'];

$thisVille = new Ville($_SESSION['ville_encours']);

//requete Infrastructure
$maxRows_infrastructure = 15;
$pageNum_infrastructure = 0;
if (isset($_GET['pageNum_infrastructure'])) {
  $pageNum_infrastructure = $_GET['pageNum_infrastructure'];
}
$startRow_infrastructure = $pageNum_infrastructure * $maxRows_infrastructure;


$query_infrastructure = sprintf("SELECT * FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id=infrastructures_officielles.ch_inf_off_id WHERE ch_inf_villeid = %s ORDER BY ch_inf_date DESC", GetSQLValueString($_SESSION['ville_encours'], "int"));
$query_limit_infrastructure = sprintf("%s LIMIT %d, %d", $query_infrastructure, $startRow_infrastructure, $maxRows_infrastructure);
$infrastructure = mysql_query($query_limit_infrastructure, $maconnexion) or die(mysql_error());
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

//requete Monuments
$maxRows_monument = 15;
$pageNum_monument = 0;
if (isset($_GET['pageNum_monument'])) {
  $pageNum_monument = $_GET['pageNum_monument'];
}
$startRow_monument = $pageNum_monument * $maxRows_monument;


$query_monument = sprintf("SELECT * FROM patrimoine WHERE ch_pat_villeID = %s ORDER BY ch_pat_mis_jour DESC", GetSQLValueString($_SESSION['ville_encours'], "int"));
$query_limit_monument = sprintf("%s LIMIT %d, %d", $query_monument, $startRow_monument, $maxRows_monument);
$monument = mysql_query($query_limit_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);


if (isset($_GET['totalRows_monument'])) {
  $totalRows_monument = $_GET['totalRows_monument'];
} else {
  $all_monument = mysql_query($query_monument);
  $totalRows_monument = mysql_num_rows($all_monument);
}
$totalPages_monument = ceil($totalRows_monument/$maxRows_monument)-1;

$queryString_monument = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_monument") == false && 
        stristr($param, "totalRows_monument") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_monument = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_monument = sprintf("&totalRows_monument=%d%s", $totalRows_monument, $queryString_monument);

//Requete User
$UserID = $row_ville['ch_vil_user'];

$query_User = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_id = %s", GetSQLValueString($UserID, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$_SESSION['last_work'] = "ville_modifier.php";

//Liste des joueurs pour choisir maire

$query_list_users = sprintf("SELECT ch_use_id, ch_use_login FROM users ORDER BY ch_use_login");
$list_users = mysql_query($query_list_users, $maconnexion) or die(mysql_error());
$row_list_users = mysql_fetch_assoc($list_users);
$totalRows_list_users = mysql_num_rows($list_users);

// Coordonnées marqueur carte
$coord_X = $row_ville['ch_vil_coord_X'];
$coord_Y = $row_ville['ch_vil_coord_Y'];

$eloquentVille = \App\Models\Ville::findOrFail($thisVille->get('ch_vil_ID'));

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<title>Monde GC - Modifier la ville <?= __s($thisVille->get('ch_vil_nom')) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-lightbox.min.css" rel="stylesheet" />
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('../assets/img/ImgIntroheader.jpg');
}
#map {
	height: 500px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
</style>
<script type="text/javascript">
function verif_champ(form_coord_X)
{
if ((form_coord_X == "") || (form_coord_X == 0))
{ alert("Vous devez obligatoirement indiquer l'emplacement de votre ville dans le monde GC. Appuyez sur le bouton carte puis cliquez directement sur la carte");
return false;
}
return true;
}
</script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header id="info-ville" class="jumbotron subhead anchor"> 
  <!-- Titre  et Carousel
    ================================================== -->
  <div class="container container-carousel">
    <?php if ($row_ville['ch_vil_lien_img1'] OR $row_ville['ch_vil_lien_img2'] OR $row_ville['ch_vil_lien_img3'] OR $row_ville['ch_vil_lien_img4'] OR $row_ville['ch_vil_lien_img5']) { ?>

    <div class="titre-caroussel-container-admin">
        <h2 class="titre-caroussel-h2">Gestion de la ville</h2>
        <h1 class="titre-caroussel"><?= e($row_ville['ch_vil_nom']) ?></h1>
    </div>
    <section id="myCarousel" class="carousel slide">
      <div class="carousel-inner vertical-align-center">
        <?php if ($row_ville['ch_vil_lien_img1']) { ?>
        <div class="item active" style="background-image:url(<?php echo $row_ville['ch_vil_lien_img1']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_ville['ch_vil_legende_img1']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_ville['ch_vil_lien_img2']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_ville['ch_vil_lien_img2']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_ville['ch_vil_legende_img2']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_ville['ch_vil_lien_img3']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_ville['ch_vil_lien_img3']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_ville['ch_vil_legende_img3']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_ville['ch_vil_lien_img4']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_ville['ch_vil_lien_img4']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_ville['ch_vil_legende_img4']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_ville['ch_vil_lien_img5']) { ?>
        <div class="item" style="background-image: url('[<?php echo $row_ville['ch_vil_lien_img5']; ?>]')">
          <div class="carousel-caption">
            <p><?php echo $row_ville['ch_vil_legende_img5']; ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </section>
    <!-- Titre si pas de carrousel
    ================================================== -->
    <?php } else { ?>
    <h2>Gestion de la ville</h2>
    <h1><?= e($row_ville['ch_vil_nom']) ?></h1>
    <?php } ?>
  </div>
</header>
<div class="container" id="overview"> 
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-ville">
          <?php if ($row_ville['ch_vil_armoiries']) { ?>
          <img src="<?= e($row_ville['ch_vil_armoiries']) ?>">
          <?php } else { ?>
          <img src="../assets/img/imagesdefaut/blason.jpg">
          <?php }?>
          <p><strong><?= e($row_ville['ch_vil_nom']) ?></strong></p>
          <p><em>Cr&eacute;&eacute;e par <?= e($row_User['ch_use_login']) ?></em></p>
          </a></li>
        <li><a href="#page_ville">Page ville</a></li>
        <li><a href="#mes-communiques">Communiqu&eacute;s officiels</a></li>
        <li><a href="#infrastructures">Infrastructures</a></li>
        <li><a href="#quetes">Quêtes</a></li>
        <li><a href="page_pays_back.php?paysID=<?= e($row_ville['ch_pay_id']) ?>">Retour &agrave; mon pays</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <section class="span9 corps-page">

    <ul class="breadcrumb pull-left">
      <li><a href="page_pays_back.php?paysID=<?= e($row_ville['ch_pay_id']) ?>&userID=<?= e($row_User['ch_use_id']) ?>">Gestion du pays : <?= e($row_ville['ch_pay_nom']) ?></a> <span class="divider">/</span></li>
      <li class="active">Gestion de la ville : <?= e($row_ville['ch_vil_nom']) ?></li>
    </ul>
    <div class="clearfix"></div>

    <div class="pull-left">
        <a class="btn btn-primary btn-margin-left" href="../page-ville.php?ch_pay_id=<?= e($row_ville['ch_pay_id']) ?>&ch_ville_id=<?= e($row_ville['ch_vil_ID']) ?>" type="submit" title="page de gestion du pays">Accéder à la page de la ville</a>
    </div>

      <!-- Moderation
     ================================================== -->
      <?php if (($_SESSION['statut'] >= 20) AND ($row_User['ch_use_id'] != $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="membre-modifier_back.php" method="post">
        <input name="userID" type="hidden" value="<?= e($row_User['ch_use_id']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?= e($row_ville['ch_vil_paysID']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <?php }?>
      <form class="pull-right" action="ville_confirmation_supprimer.php" method="post">
        <input name="ville-ID" type="hidden" value="<?= e($row_ville['ch_vil_ID']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer cette ville"><i class="icon-trash icon-white"></i></button>
      </form>
      <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="../php/partage-ville.php?ch_vil_ID=<?= e($row_ville['ch_vil_ID']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Partager sur le forum</a>
      <?php } ?>
      <div class="clearfix"></div>

        <?php renderElement('errormsgs'); ?>

      <!-- Debut formulaire de modification ville
     ================================================== -->
      <div id="page_ville" class="titre-vert anchor">
        <h1>Page de <?= e($row_ville['ch_vil_nom']) ?></h1>
      </div>
      <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_ville" Id="ajout_ville" onsubmit='return verif_champ(document.ajout_ville.form_coord_X.value);'>
        <section>
          <div class="alert alert-tips">
            <button type="button" class="close" data-dismiss="alert">×</button>
            Ce formulaire contient les informations qui seront affich&eacute;e sur la page consacr&eacute;e &agrave; votre ville et plus g&eacute;n&eacute;ralement dans l'ensemble du site. Compl&eacute;tez-le au fur et &agrave; mesure et mettez-le &agrave; jour.</div>
          <!-- boutons cachés -->
          <input name="ch_vil_ID" type="hidden" value="<?= e($row_ville['ch_vil_ID']) ?>">
          <input name="ch_vil_paysID" type="hidden" value="<?= e($row_ville['ch_vil_paysID']) ?>" >
          <input name="ch_vil_label" type="hidden" value="<?= e($row_ville['ch_vil_label']) ?>">
          <input name="ch_vil_date_enregistrement" type="hidden" value="<?= e($row_ville['ch_vil_date_enregistrement']) ?>">
          <?php $now = date("Y-m-d G:i:s");
				  $nbupdate = $row_ville['ch_vil_nb_update']+1; ?>
          <input name="ch_vil_mis_jour" type="hidden" value="<?php echo $now; ?>" >
          <input name="ch_vil_nb_update" type="hidden" value="<?php echo $nbupdate; ?>">

          <!-- Informations générales
     ================================================== -->

          <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseone"> Informations g&eacute;n&eacute;rales </a> </div>
            <div id="collapseone" class="accordion-body collapse">
              <div class="accordion-inner">
                <?php if (($_SESSION['statut'] >= 10)) { ?>
                <!-- Choix joueur -->
                <div class="control-group">
                  <label class="control-label" for="ch_vil_user">Maire de la ville <a href="#" rel="clickover" title="Autre joueur" data-content="Vous pouvez choisir un autre joueur qui est d&eacute;j&agrave; inscrit sur le site. Contacter le Haut-Conseil pour inscrire de nouveaux membres. Attention, les villes confi&eacute;es &agrave; d'autres joueurs ne seront plus sous votre contr&ocirc;le"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <select id="ch_vil_user" name="ch_vil_user">
                      <?php do { ?>
                      <option value="<?php echo $row_list_users['ch_use_id'] ?>" <?php if (!(strcmp($row_ville['ch_vil_user'], $row_list_users['ch_use_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_list_users['ch_use_login'] ?></option>
                      <?php } while ($row_list_users = mysql_fetch_assoc($list_users)); ?>
                    </select>
                  </div>
                </div>
                <?php } else { ?>
                <input name="ch_vil_user" type="hidden" value="<?php echo $row_ville['ch_vil_user'] ?>" >
                <?php } ?>
                <!-- Nom -->
                <div id="sprytextfield2" class="control-group">
                  <label class="control-label" for="ch_vil_nom">Nom de la ville <a href="#" rel="clickover" data-placement="bottom" title="Nom de la ville" data-content="30 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input class="input-xlarge" type="text" id="ch_vil_nom" name="ch_vil_nom" value="<?= e($row_ville['ch_vil_nom']) ?>" placeholder="ma ville">
                    <span class="textfieldMaxCharsMsg">30 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
                </div>
                <!-- Type de jeu -->
                <div class="control-group">
                  <label class="control-label" for="ch_vil_type_jeu">Type de jeu <a href="#" rel="clickover" title="Type de jeu" data-content="Indiquez le jeu dans lequel vous avez construit votre ville"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <select id="ch_vil_type_jeu" name="ch_vil_type_jeu">
                      <option value="SC5" <?php if (!(strcmp("SC5", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>SimCity 5</option>
                      <option value="CXL" <?php if (!(strcmp("CXL", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>Cities XL</option>
                      <option value="CL" <?php if (!(strcmp("CL", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>City Life</option>
                      <option value="SC4" <?php if (!(strcmp("SC4", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>SimCity 4</option>
                      <option value="SIM" <?php if (!(strcmp("SIM", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>Les Sims</option>
                      <option value="SKY" <?php if (!(strcmp("SKY", $row_ville['ch_vil_type_jeu']))) {echo "selected=\"selected\"";} ?>>Cities: Skylines</option>
                    </select>
                  </div>
                </div>
                <!-- Armoiries -->
                <div id="sprytextfield28" class="control-group">
                  <label class="control-label" for="ch_vil_armoiries">Armoiries de la ville <a href="#" rel="clickover" title="Armoiries de la ville" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com). l'image des armoiries sera automatiquement redimensionn&eacute;e en 250 pixel de large et 250 pixels de haut."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input class="span11" type="text" id="ch_vil_armoiries" name="ch_vil_armoiries" value="<?= e($row_ville['ch_vil_armoiries']) ?>" placeholder="">
                    <br>
                    <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
                </div>
                <!-- Statut -->
                <div id="spryradio1" class="control-group">
                  <div class="control-label" >Statut de la ville <a href="#" rel="clickover" title="Statut de votre ville" data-content="Capitale : la ville sera la capitale de votre pays. Elle sera visible dans la liste de vos villes sur votre page pays (par défaut). Visible : la ville sera visible dans la liste de vos villes. Invisible : la ville ne sera pas visible dans la liste de vos villes."><i class="icon-info-sign"></i></a></div>
                  <div class="controls">
                    <label>
                      <input <?php if (!(strcmp($row_ville['ch_vil_capitale'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_vil_capitale" value="1" id="ch_vil_capitale_0">
                      capitale</label>
                    <label>
                      <input <?php if (!(strcmp($row_ville['ch_vil_capitale'],"2"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_vil_capitale" value="2" id="ch_vil_capitale_1">
                      visible</label>
                    <label>
                      <input <?php if (!(strcmp($row_ville['ch_vil_capitale'],"3"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_vil_capitale" value="3" id="ch_vil_capitale_2">
                      invisible</label>
                    <span class="radioRequiredMsg">Choisissez un statut pour votre ville</span></div>
                </div>
                <div id="sprytextfield3" class="control-group"> 
                  <!-- Population -->
                  <label class="control-label" for="ch_vil_population">Population <a href="#" rel="clickover" title="Population" data-content="Entrez le chiffre sans espaces"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_population" type="text" class="input-xlarge" id="ch_vil_population" placeholder="0" value="<?= e($row_ville['ch_vil_population']) ?>">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
                </div>
                <!-- Spécialité -->
                <div id="sprytextfield4">
                  <label class="control-label" for="ch_vil_specialite">Sp&eacute;cialit&eacute; <a href="#" rel="clickover" title="Sp&eacute;cialit&eacute;" data-content="Entrez ici la spécialit&eacute; de votre ville qui pourrait &ecirc;tre l'agriculture ou le macram&eacute;... 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_specialite" type="text" class="input-xlarge" id="ch_vil_specialite" placeholder="Petit artisanat local"value="<?= e($row_ville['ch_vil_specialite']) ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
                <p>&nbsp;</p>
                <!-- Carte -->
                <div class="control-label">Emplacement <a href="#" rel="clickover" title="Emplacement" data-placement="top" data-content="Cliquez sur la carte pour définir le nouvel emplacement de votre ville"><i class="icon-info-sign"></i></a></div>
                <div class="controls">
                  <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#demo">carte </button>
                </div>
                <div id="demo" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <div id="map"></div>
                    <p>&nbsp;</p>
                    <!-- Coordonnées -->
                    <div id="sprytextfield29" class="control-group">
                      <label class="control-label">Coordonn&eacute;es X</label>
                      <div class="controls">
                        <input class="span4" type="text" name="form_coord_X" id="form_coord_X" value="<?= e($row_ville['ch_vil_coord_X']) ?>" readonly>
                        <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span> <span class="textfieldRequiredMsg">Une valeur est requise. Cliquez sur la carte</span></div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">Coordonn&eacute;es Y</label>
                      <div class="controls">
                        <input class="span4" type="text" name="form_coord_Y" id="form_coord_Y" value="<?= e($row_ville['ch_vil_coord_Y']) ?>" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsetwo">Introduction</a> </div>
            <div id="collapsetwo" class="accordion-body collapse">
              <div class="accordion-inner"> 
                <!-- Présentation -->
                <div class="control-group" width="100%">
                  <label class="control-label" for="ch_vil_header">Pr&eacute;sentation <a href="#" rel="clickover" title="Pr&eacute;sentation" data-placement="bottom" data-content="Mettez-ici un r&eacute;sum&eacute; du journal de votre ville. Utilisez les liens vers des ancres html pour des renvois vers le détail dans votre journal"><i class="icon-info-sign"></i></a></label>
                </div>
                <div>
                  <textarea name="ch_vil_header" id="ch_vil_header" class="wysiwyg" rows="15"><?= e($row_ville['ch_vil_header']) ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsethree">Présentation générale</a> </div>
            <div id="collapsethree" class="accordion-body collapse">
              <div class="accordion-inner">
                <div class="control-group" width="100%">
                  <!-- Contenu -->
                  <label class="control-label" for="ch_vil_contenu">Contenu de la page <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; de la page de votre ville. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles. Vous pouvez la mettre à jour au fur et &agrave; mesure"><i class="icon-info-sign"></i></a></label>
                </div>
                <div>
                  <textarea name="ch_vil_contenu" id="ch_vil_contenu" class="wysiwyg"  rows="30"><?= htmlPurify($row_ville['ch_vil_contenu']) ?></textarea>
                </div>
              </div>
            </div>
          </div>

            <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefour">Politique et administration urbaine</a> </div>
            <div id="collapsefour" class="accordion-body collapse">
              <div class="accordion-inner">
                <!-- Présentation -->
                <div class="control-group" width="100%">
                  <label class="control-label" for="ch_vil_administration">Politique et administration urbaine <a href="#" rel="clickover" title="Pr&eacute;sentation" data-placement="bottom" data-content="Parlez de l'administration de votre ville. Qui est le maire ?"><i class="icon-info-sign"></i></a></label>
                </div>
                <div>
                  <textarea name="ch_vil_administration" id="ch_vil_administration" class="wysiwyg" rows="15"><?= htmlPurify($row_ville['ch_vil_administration']) ?></textarea>
                </div>
                </div>
              </div>
            </div>

          <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefive">Transports</a> </div>
            <div id="collapsefive" class="accordion-body collapse">
              <div class="accordion-inner">
                <!-- Présentation -->
                <div class="control-group" width="100%">
                  <label class="control-label" for="ch_vil_transports">Transports <a href="#" rel="clickover" title="Pr&eacute;sentation" data-placement="bottom" data-content="Infrastructures de transports dans votre ville"><i class="icon-info-sign"></i></a></label>
                </div>
                <div>
                  <textarea name="ch_vil_transports" id="ch_vil_transports" class="wysiwyg" rows="15"><?= htmlPurify($row_ville['ch_vil_transports']) ?></textarea>
                </div>
                </div>
              </div>
            </div>

            <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsesix">Culture et Patrimoine</a> </div>
            <div id="collapsesix" class="accordion-body collapse">
              <div class="accordion-inner">
                <!-- Présentation -->
                <div class="control-group" width="100%">
                  <label class="control-label" for="ch_vil_culture">Culture et Patrimoine <a href="#" rel="clickover" title="Pr&eacute;sentation" data-placement="bottom" data-content="Parlez de ce qui fait le rayonnement culturel de votre ville. Quels sont les événements populaires ?"><i class="icon-info-sign"></i></a></label>
                </div>
                <div>
                  <textarea name="ch_vil_culture" id="ch_vil_culture" class="wysiwyg" rows="15"><?= htmlPurify($row_ville['ch_vil_culture']) ?></textarea>
                </div>
                </div>
              </div>
            </div>

        </section>
        <!-- Carousel -->
        <section>
          <div class="accordion-group">
            <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseseven"> Carrousel d'images </a> </div>
            <div id="collapseseven" class="accordion-body collapse">
              <div class="accordion-inner">
                <div class="alert alert-tips">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  Le carrousel est une galerie d'images qui va d&eacute;filer en t&ecirc;te de la page de votre ville. La premi&egrave;re image sera reprise pour illustrer votre ville dans l'ensemble du site.</div>
                <div id="sprytextfield5" class="control-group">
                  <label class="control-label" for="ch_vil_lien_img1">Lien image n&deg;1 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_lien_img1" type="text" id="ch_vil_lien_img1" value="<?php echo $row_ville['ch_vil_lien_img1']; ?>" class="span11">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield6" class="control-group">
                  <label class="control-label" for="ch_vil_legende_img1">L&eacute;gende image n&deg;1 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_legende_img1" type="text" id="ch_vil_legende_img1" value="<?php echo $row_ville['ch_vil_legende_img1']; ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield7" class="control-group">
                  <label class="control-label" for="ch_vil_lien_img2">Lien image n&deg;2 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_lien_img2" type="text" id="ch_vil_lien_img2" value="<?php echo $row_ville['ch_vil_lien_img2']; ?>" class="span11">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield8" class="control-group">
                  <label class="control-label" for="ch_vil_legende_img2">L&eacute;gende image n&deg;2 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_legende_img2" type="text" id="ch_vil_legende_img2" value="<?php echo $row_ville['ch_vil_legende_img2']; ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield9" class="control-group">
                  <label class="control-label" for="ch_vil_lien_img3">Lien image n&deg;3 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_lien_img3" type="text" id="ch_vil_lien_img3" value="<?php echo $row_ville['ch_vil_lien_img3']; ?>" class="span11">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield10" class="control-group">
                  <label class="control-label" for="ch_vil_legende_img3">L&eacute;gende image n&deg;3 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_legende_img3" type="text" id="ch_vil_legende_img3" value="<?php echo $row_ville['ch_vil_legende_img3']; ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield11" class="control-group">
                  <label class="control-label" for="ch_vil_lien_img4">Lien image n&deg;4 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_lien_img4" type="text" id="ch_vil_lien_img4" value="<?php echo $row_ville['ch_vil_lien_img4']; ?>" class="span11">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield12" class="control-group">
                  <label class="control-label" for="ch_vil_legende_img4">L&eacute;gende image n&deg;4 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_legende_img4" type="text" id="ch_vil_legende_img4" value="<?php echo $row_ville['ch_vil_legende_img4']; ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield13" class="control-group">
                  <label class="control-label" for="ch_vil_lien_img5">Lien image n&deg;5 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_lien_img5" type="text" id="ch_vil_lien_img5" value="<?php echo $row_ville['ch_vil_lien_img5']; ?>" class="span11">
                    <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
                </div>
                <div id="sprytextfield14" class="control-group">
                  <label class="control-label" for="ch_vil_legende_img5">L&eacute;gende image n&deg;5 <a href="#" rel="clickover" title="L&eacute;gende image" data-placement="top" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                  <div class="controls">
                    <input name="ch_vil_legende_img5" type="text" id="ch_vil_legende_img5" value="<?php echo $row_ville['ch_vil_legende_img5']; ?>">
                    <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section>
        <div class="controls">
          <p>&nbsp;</p>
          <button type="submit" class="btn btn-primary">Envoyer</button>
          <p>&nbsp;</p>
        </div>
        <input type="hidden" name="MM_update" value="ajout_ville">
      </form>
    </section>
    <div class="clearfix"></div>


    <!-- Liste des Communiqués
        ================================================== -->
    <?php if (auth()->check() && auth()->user()->ownsPays($eloquentVille->pays)): ?>
      <div class="pull-right-cta cta-title">
          <a href="<?= url('back/communique_ajouter.php?userID='
              . auth()->user()->ch_use_id . '&cat=ville&com_element_id='
              . $eloquentVille->pays->ch_pay_id) ?>"
             class="btn btn-primary btn-cta">
              <i class="icon-plus-sign icon-white"></i> Ajouter un communiqué
          </a>
      </div>
    <?php endif; ?>

    <section>
      <div id="mes-communiques" class="titre-vert anchor">
        <h1>Communiqu&eacute;s</h1>
      </div>
      <div class="alert alert-tips">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Lancez un communiqu&eacute; au nom de votre ville. Les communiqu&eacute;s post&eacute;s &agrave; partir de cette page seront consid&eacute;r&eacute;s comme des annonces officielles &eacute;manant des services officiels de la ville.</div>
      <?php 
$userID = $row_User['ch_use_id'];
$com_cat = "ville";
$com_element_id = $row_ville['ch_vil_ID'];
include(DEF_ROOTPATH . 'php/communiques-back.php'); ?>
    </section>
    <div class="clearfix"></div>


    <!-- Liste des infrastructures
        ================================================== -->
    <?php
    renderElement('infrastructure/back_list', [
          'infrastructurable' => $eloquentVille,
    ]); ?>
    <div class="clearfix"></div>


    <!-- Liste des quêtes
        ================================================== -->
    <div class="pull-right-cta cta-title">
        <form action="monument_ajouter.php" method="post">
            <input name="paysID" type="hidden" value="<?= e($row_ville['ch_vil_paysID']) ?>">
            <input name="ville_ID" type="hidden" value="<?= e($row_ville['ch_vil_ID']) ?>">
            <button class="btn btn-primary btn-margin-left" type="submit">
                <i class="icon icon-star icon-white"></i> Se lancer dans une nouvelle quête !</button>
        </form>
    </div>
    <section>
      <div id="quetes" class="titre-vert anchor">
        <h1>Quêtes</h1>
      </div>
      <div class="alert alert-tips">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Identifiez-ici les &eacute;l&eacute;ments de votre ville que vous souhaitez mettre en valeur en leur cr&eacute;ant une page d&eacute;di&eacute;e. Ces &eacute;l&eacute;ments seront publi&eacute;s dans la section Culture de la page de votre pays.</div>
      <?php if ($row_monument) { ?>
      <table width="539" class="table table-hover">
        <thead>
          <tr class="tablehead">
            <th width="5%" scope="col"><a href="#" rel="clickover" title="Statut de votre monument" data-content="Un monument peut-&ecirc;tre publi&eacute;e sur votre page pays ou masqu&eacute;e."><i class="icon-globe"></i></a></th>
            <th width="62%" scope="col">Nom</th>
            <th width="21%" scope="col">Date</th>
            <th width="4%" scope="col">&nbsp;</th>
            <th width="4%" scope="col">&nbsp;</th>
            <th width="4%" scope="col">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php do { ?>
            <tr>
              <td><img src="../assets/img/statutvil_<?= e($row_monument['ch_pat_statut']) ?>.png" alt="Statut"></td>
              <td><?= __s($row_monument['ch_pat_nom']) ?></td>
              <td><?php echo date("d/m/Y", strtotime($row_monument['ch_pat_date'])); ?></td>
              <td>
                  <a class="btn" href="../page-monument.php?ch_pat_id=<?= e($row_monument['ch_pat_id']) ?>" title="Voir les détails" style="margin-top: -22px;"><i class="icon-eye-open"></i></a>
              </td>
              <td><form action="monument_modifier.php" method="post">
                  <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
                  <button class="btn btn-primary" type="submit" title="modifier ce monument"><i class="icon-pencil icon-white"></i></button>
                </form></td>
              <td><form action="monument_confirmation_supprimer.php" method="post"">
                  <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
                  <button class="btn btn-danger" type="submit" title="supprimer ce monument"><i class="icon-trash icon-white"></i></button>
                </form></td>
            </tr>
            <?php } while ($row_monument = mysql_fetch_assoc($monument)); ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5"><p class="pull-right">de <?php echo ($startRow_monument + 1) ?> &agrave; <?php echo min($startRow_monument + $maxRows_monument, $totalRows_monument) ?> sur <?php echo $totalRows_monument ?>
                <?php if ($pageNum_monument > 0) { // Show if not first page ?>
                  <a class="btn" href="<?php printf("%s?pageNum_monument=%d%s#quetes", (int)$currentPage, max(0, $pageNum_monument - 1), $queryString_monument); ?>"><i class=" icon-backward"></i> </a>
                  <?php } // Show if not first page ?>
                <?php if ($pageNum_monument < $totalPages_monument) { // Show if not last page ?>
                  <a class="btn" href="<?php printf("%s?pageNum_monument=%d%s#quetes", $currentPage, min($totalPages_monument, $pageNum_monument + 1), $queryString_monument); ?>"> <i class="icon-forward"></i></a>
                  <?php } // Show if not last page ?>
              </p>
              <form action="monument_ajouter.php" method="post">
                <input name="paysID" type="hidden" value="<?= e($row_ville['ch_vil_paysID']) ?>">
                <input name="ville_ID" type="hidden" value="<?= e($row_ville['ch_vil_ID']) ?>">
                <button class="btn btn-primary btn-margin-left" type="submit">Se lancer dans une nouvelle
                    quête !</button>
              </form></td>
          </tr>
        </tfoot>
      </table>
      <?php } else { ?>
      <form action="monument_ajouter.php" method="post">
        <input name="paysID" type="hidden" value="<?= e($row_ville['ch_vil_paysID']) ?>">
        <input name="ville_ID" type="hidden" value="<?= e($row_ville['ch_vil_ID']) ?>">
        <button class="btn btn-primary btn-margin-left" type="submit">Se lancer dans une
            nouvelle quête !</button>
      </form>
      <?php } ?>
    </section>
  </div>

  <!-- END CONTENT
    ================================================== --> 
</div>
<div class="modal container fade" id="Modal-Monument"></div>
<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include(DEF_ROOTPATH . 'php/carte-ajouter-marqueur.php'); ?>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
    $(document).ready(function () {
        init();
    });
</script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script>
    $("a[data-toggle=modal]").click(function (e) {
        lv_target = $(this).attr('data-target')
        lv_url = $(this).attr('href')
        $(lv_target).load(lv_url)
    })

    $('#closemodal').click(function () {
        $('#Modal-Monument').modal('hide');
    });
</script>
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:30, validateOn:["change"], minChars:2});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"], useCharacterMasking:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {isRequired:false, validateOn:["change"], maxChars:250});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {maxChars:50, validateOn:["change"], isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
</script>
</body>
</html>
