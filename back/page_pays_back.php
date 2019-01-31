<?php

use GenCity\Monde\Pays;

require_once('../Connections/maconnexion.php');

 session_start();
 
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers page de connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
}

//Mise a jour parametres donnees personnelles
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//Récupération variables
$colname_paysID = $_REQUEST['paysID'];
unset($_REQUEST['paysID']);

//Requete Pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_InfoGenerale = sprintf("SELECT * FROM pays WHERE ch_pay_id = %s", GetSQLValueString($colname_paysID, "int"));
$InfoGenerale = mysql_query($query_InfoGenerale, $maconnexion) or die(mysql_error());
$row_InfoGenerale = mysql_fetch_assoc($InfoGenerale);
$totalRows_InfoGenerale = mysql_num_rows($InfoGenerale);

//Requete User
mysql_select_db($database_maconnexion, $maconnexion);
$query_User = sprintf("SELECT ch_use_id, ch_use_login, ch_use_statut FROM users WHERE ch_use_paysID = %s AND ch_use_statut >= 10", GetSQLValueString($colname_paysID, "int"));
$User = mysql_query($query_User, $maconnexion) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);

//Mise à jour formulaire pays
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "InfoHeader")) {

    $thisUser = $_SESSION['userObject'];
    $thisPays = new Pays($_POST['ch_pay_id']);
    if($thisUser->minStatus('OCGC') || $thisPays->getUserPermission($thisUser) >= Pays::$permissions['codirigeant'])
    {

        if ($_POST['ch_pay_emplacement'] >= 3 and $_POST['ch_pay_emplacement'] <= 4 ){ $ch_pay_continent = "RFGC";}
        if ($_POST['ch_pay_emplacement'] >= 5 and $_POST['ch_pay_emplacement'] < 14 ){ $ch_pay_continent = "Aurinea";}
        if ($_POST['ch_pay_emplacement'] < 3 ){ $ch_pay_continent = "Aurinea";}
        if ($_POST['ch_pay_emplacement'] >= 14 and $_POST['ch_pay_emplacement'] < 18 ){ $ch_pay_continent = "Oceania";}
        if ($_POST['ch_pay_emplacement'] >= 18 and $_POST['ch_pay_emplacement'] < 24 ){ $ch_pay_continent = "Volcania";}
        if ($_POST['ch_pay_emplacement'] >= 24 and $_POST['ch_pay_emplacement'] <= 27 ){ $ch_pay_continent = "Aldesyl";}
        if ($_POST['ch_pay_emplacement'] >= 27 and $_POST['ch_pay_emplacement'] <= 42 ){ $ch_pay_continent = "Philicie";}
        if( $_POST['ch_pay_emplacement'] >= 42 and $_POST['ch_pay_emplacement'] <= 56 ){ $ch_pay_continent = "Aldesyl";}
        if( $_POST['ch_pay_emplacement'] >= 56 and $_POST['ch_pay_emplacement'] <= 57 ){ $ch_pay_continent = "Volcania";}
        if ($_POST['ch_pay_emplacement'] >= 57 and $_POST['ch_pay_emplacement'] <= 58 ){ $ch_pay_continent = "Aldesyl";}

      $updateSQL = sprintf("UPDATE pays SET ch_pay_label=%s, ch_pay_publication=%s, ch_pay_continent=%s, ch_pay_emplacement=%s, ch_pay_lien_forum=%s, ch_pay_nom=%s, ch_pay_devise=%s, ch_pay_lien_imgheader=%s, ch_pay_lien_imgdrapeau=%s, ch_pay_date=%s, ch_pay_mis_jour=%s, ch_pay_nb_update=%s, ch_pay_forme_etat=%s, ch_pay_capitale=%s, ch_pay_langue_officielle=%s, ch_pay_monnaie=%s, ch_pay_header_presentation=%s, ch_pay_text_presentation=%s, ch_pay_header_geographie=%s, ch_pay_text_geographie=%s, ch_pay_header_politique=%s, ch_pay_text_politique=%s, ch_pay_header_histoire=%s, ch_pay_text_histoire=%s, ch_pay_header_economie=%s, ch_pay_text_economie=%s, ch_pay_header_transport=%s, ch_pay_text_transport=%s, ch_pay_header_sport=%s, ch_pay_text_sport=%s, ch_pay_header_culture=%s, ch_pay_text_culture=%s, ch_pay_header_patrimoine=%s, ch_pay_text_patrimoine=%s WHERE ch_pay_id=%s",
                           GetSQLValueString($_POST['ch_pay_label'], "text"),
                           GetSQLValueString($_POST['ch_pay_publication'], "int"),
                           GetSQLValueString($ch_pay_continent, "text"),
                           GetSQLValueString($_POST['ch_pay_emplacement'], "int"),
                           GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                           GetSQLValueString($_POST['ch_pay_nom'], "text"),
                           GetSQLValueString($_POST['ch_pay_devise'], "text"),
                           GetSQLValueString($_POST['ch_pay_lien_imgheader'], "text"),
                           GetSQLValueString($_POST['ch_pay_lien_imgdrapeau'], "text"),
                           GetSQLValueString($_POST['ch_pay_date'], "date"),
                           GetSQLValueString($_POST['ch_pay_mis_jour'], "date"),
                           GetSQLValueString($_POST['ch_pay_nb_update'], "int"),
                           GetSQLValueString($_POST['ch_pay_forme_etat'], "text"),
                           GetSQLValueString($_POST['ch_pay_capitale'], "text"),
                           GetSQLValueString($_POST['ch_pay_langue_officielle'], "text"),
                           GetSQLValueString($_POST['ch_pay_monnaie'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_presentation'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_presentation'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_geographie'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_geographie'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_politique'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_politique'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_histoire'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_histoire'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_economie'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_economie'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_transport'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_transport'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_sport'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_sport'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_culture'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_culture'], "text"),
                           GetSQLValueString($_POST['ch_pay_header_patrimoine'], "text"),
                           GetSQLValueString($_POST['ch_pay_text_patrimoine'], "text"),
                           GetSQLValueString($_POST['ch_pay_id'], "int"));

      mysql_select_db($database_maconnexion, $maconnexion);
      $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
      getErrorMessage('success', "Le pays a été modifié avec succès !");
    } else {
        getErrorMessage('error', "Vous n'avez pas accès à cette partie.");
    }
  
  $updateGoTo = "page_pays_back.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit;
}

//requete liste des villes du joueur
$maxRows_mesvilles = 8;
$pageNum_mesvilles = 0;
if (isset($_GET['pageNum_mesvilles'])) {
  $pageNum_mesvilles = $_GET['pageNum_mesvilles'];
}
$startRow_mesvilles = $pageNum_mesvilles * $maxRows_mesvilles;

mysql_select_db($database_maconnexion, $maconnexion);
$query_mesvilles = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_capitale, ch_vil_population, ch_use_paysID, ch_pay_lien_imgdrapeau, ch_pay_nom FROM villes INNER JOIN users ON ch_vil_user = ch_use_id INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_vil_user= %s AND ch_pay_id = %s ORDER BY ch_vil_date_enregistrement ASC", GetSQLValueString($_SESSION['user_ID'], "int"), GetSQLValueString($colname_paysID, 'int'));
$query_limit_mesvilles = sprintf("%s LIMIT %d, %d", $query_mesvilles, $startRow_mesvilles, $maxRows_mesvilles);
$mesvilles = mysql_query($query_limit_mesvilles, $maconnexion) or die(mysql_error());
$row_mesvilles = mysql_fetch_assoc($mesvilles);

if (isset($_GET['totalRows_mesvilles'])) {
  $totalRows_mesvilles = $_GET['totalRows_mesvilles'];
} else {
  $all_mesvilles = mysql_query($query_mesvilles);
  $totalRows_mesvilles = mysql_num_rows($all_mesvilles);
}
$totalPages_mesvilles = ceil($totalRows_mesvilles/$maxRows_mesvilles)-1;

$queryString_mesvilles = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_mesvilles") == false && 
        stristr($param, "totalRows_mesvilles") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_mesvilles = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_mesvilles = sprintf("&totalRows_mesvilles=%d%s", $totalRows_mesvilles, $queryString_mesvilles);

//requete liste villes autre joueurs
$maxRows_autres_villes = 8;
$pageNum_autres_villes = 0;
if (isset($_GET['pageNum_autres_villes'])) {
  $pageNum_autres_villes = $_GET['pageNum_autres_villes'];
}
$startRow_autres_villes = $pageNum_autres_villes * $maxRows_autres_villes;

mysql_select_db($database_maconnexion, $maconnexion);
$query_autres_villes = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_nom, ch_vil_capitale, ch_vil_population, ch_use_login FROM villes INNER JOIN users ON ch_vil_user = ch_use_id WHERE ch_vil_paysID = %s AND ch_vil_user != %s ORDER BY ch_vil_date_enregistrement ASC", GetSQLValueString($colname_paysID, "int"), GetSQLValueString($_SESSION['user_ID'], "int"));
$query_limit_autres_villes = sprintf("%s LIMIT %d, %d", $query_autres_villes, $startRow_autres_villes, $maxRows_autres_villes);
$autres_villes = mysql_query($query_limit_autres_villes, $maconnexion) or die(mysql_error());
$row_autres_villes = mysql_fetch_assoc($autres_villes);

if (isset($_GET['totalRows_autres_villes'])) {
  $totalRows_autres_villes = $_GET['totalRows_autres_villes'];
} else {
  $all_autres_villes = mysql_query($query_autres_villes);
  $totalRows_autres_villes = mysql_num_rows($all_autres_villes);
}
$totalPages_autres_villes = ceil($totalRows_autres_villes/$maxRows_autres_villes)-1;

$queryString_autres_villes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_autres_villes") == false && 
        stristr($param, "totalRows_autres_villes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_autres_villes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_autres_villes = sprintf("&totalRows_autres_villes=%d%s", $totalRows_autres_villes, $queryString_autres_villes);



//requete liste communiqués

$userID = $row_User['ch_use_id'];
$maxRows_communiquesPays = 10;
$pageNum_communiquesPays = 0;
if (isset($_GET['pageNum_communiquesPays'])) {
  $pageNum_communiquesPays = $_GET['pageNum_communiquesPays'];
}
$startRow_communiquesPays = $pageNum_communiquesPays * $maxRows_communiquesPays;

mysql_select_db($database_maconnexion, $maconnexion);
$query_communiquesPays = sprintf("SELECT * FROM communiques WHERE communiques.ch_com_categorie = 'pays'  AND communiques.ch_com_element_id = %s", GetSQLValueString($colname_paysID, "int"));
$query_limit_communiquesPays = sprintf("%s LIMIT %d, %d", $query_communiquesPays, $startRow_communiquesPays, $maxRows_communiquesPays);
$communiquesPays = mysql_query($query_limit_communiquesPays, $maconnexion) or die(mysql_error());
$row_communiquesPays = mysql_fetch_assoc($communiquesPays);

if (isset($_GET['totalRows_communiquesPays'])) {
  $totalRows_communiquesPays = $_GET['totalRows_communiquesPays'];
} else {
  $all_communiquesPays = mysql_query($query_communiquesPays);
  $totalRows_communiquesPays = mysql_num_rows($all_communiquesPays);
}
$totalPages_communiquesPays = ceil($totalRows_communiquesPays/$maxRows_communiquesPays)-1;

$queryString_communiquesPays = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_communiquesPays") == false && 
        stristr($param, "totalRows_communiquesPays") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_communiquesPays = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_communiquesPays = sprintf("&totalRows_communiquesPays=%d%s", $totalRows_communiquesPays, $queryString_communiquesPays);



//requete faits historiques
$maxRows_fait_hist = 8;
$pageNum_fait_hist = 0;
if (isset($_GET['pageNum_fait_hist'])) {
  $pageNum_fait_hist = $_GET['pageNum_fait_hist'];
}
$startRow_fait_hist = $pageNum_fait_hist * $maxRows_fait_hist;

mysql_select_db($database_maconnexion, $maconnexion);
$query_fait_hist = sprintf("SELECT ch_his_id, ch_his_statut, ch_his_personnage, ch_his_date_fait, ch_his_date_fait2, ch_his_nom FROM  histoire WHERE ch_his_paysID = %s ORDER BY ch_his_date_fait ASC", GetSQLValueString($colname_paysID, "int"));
$query_limit_fait_hist = sprintf("%s LIMIT %d, %d", $query_fait_hist, $startRow_fait_hist, $maxRows_fait_hist);
$fait_hist = mysql_query($query_limit_fait_hist, $maconnexion) or die(mysql_error());
$row_fait_hist = mysql_fetch_assoc($fait_hist);

if (isset($_GET['totalRows_fait_hist'])) {
  $totalRows_fait_hist = $_GET['totalRows_fait_hist'];
} else {
  $all_fait_hist = mysql_query($query_fait_hist);
  $totalRows_fait_hist = mysql_num_rows($all_fait_hist);
}
$totalPages_fait_hist = ceil($totalRows_fait_hist/$maxRows_fait_hist)-1;

$queryString_fait_hist = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_fait_hist") == false && 
        stristr($param, "totalRows_fait_hist") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_fait_hist = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_fait_hist = sprintf("&totalRows_fait_hist=%d%s", $totalRows_fait_hist, $queryString_fait_hist);

$_SESSION['fond_ecran'] = $row_InfoGenerale['ch_pay_lien_imgheader'];
$_SESSION['last_work'] = "page_pays_back.php";

// Obtenir liste des dirigeants
$thisPays = new Pays($row_InfoGenerale['ch_pay_id']);
$paysLeaders = $thisPays->getLeaders();
$paysPersonnages = $thisPays->getCharacters();
if(!empty($paysPersonnages)) {
    $paysPersonnages = $paysPersonnages[0];
}
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Gestion&nbsp;<?php echo $row_InfoGenerale['ch_pay_nom']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">

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
 background-image: url(<?php echo $row_InfoGenerale['ch_pay_lien_imgheader'];
?>);
	background-position: center;
}
#map {
	height: 350px;
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
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
</script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<?php if ($colname_paysID != NULL AND $row_InfoGenerale['ch_pay_publication'] !=3) { ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead anchor" id="overview">
  <div class="container">
      <h2>Gestion du pays</h2>
      <h1><?php echo $row_InfoGenerale['ch_pay_nom']; ?></h1>
  </div>
</header>
<!-- Docs nav
    ================================================== -->
<div class="container">
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav cadre-bleu">
        <li class="row-fluid"><a href="#overview">
          <?php if ($row_InfoGenerale['ch_pay_lien_imgdrapeau']) { ?>
          <img src="<?php echo $row_InfoGenerale['ch_pay_lien_imgdrapeau']; ?>">
          <?php }?>
          <p><strong><?php echo $row_InfoGenerale['ch_pay_nom']; ?></strong></p>
          </a></li>
        <li><a href="#dirigeants">Dirigeants</a></li>
        <?php if ($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
        <li><a href="#info-generales">Présentation</a></li>
        <li><a href="#personnage">Personnage</a></li>
        <?php }?>
        <li><a href="#villes">Villes</a></li>
        <li><a href="#routes-campagne">Routes et campagne</a></li>
        <?php if ($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
        <li><a href="#mes-communiques">Communiqu&eacute;s officiels</a></li>
        <li><a href="#faits-historiques">Histoire</a></li>
        <?php }?>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== -->
    
    <div class="span9 corps-page">

    <ul class="breadcrumb pull-left">
      <li class="active">Gestion du pays : <?= $row_InfoGenerale['ch_pay_nom'] ?></a></li>
    </ul>

      <!-- Moderation
     ================================================== -->
      <?php if ($_SESSION['userObject']->minStatus('Administrateur')) { ?>
      <form class="pull-right" action="page_pays_confirmer_supprimer.php" method="post">
      <input name="paysID" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_id']; ?>">
      <button class="btn btn-danger" type="submit" title="supprimer ce pays"><i class="icon-trash icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if ($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
      <a class="btn btn-primary pull-right" href="../php/partage-pays.php?ch_pay_id=<?php echo $row_InfoGenerale['ch_pay_id']; ?>" data-toggle="modal" data-target="#Modal-Monument" title="Annoncez sur le forum une mise &agrave; jour de votre page"><i class="icon-share icon-white"></i> Partager sur le forum</a>
      <?php } ?>
      <?php if ($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
      <form class="pull-right" action="drapeau_modifier.php" method="post">
      <input name="paysID" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_id']; ?>">
      <button class="btn btn-primary" type="submit" title="Chargez une nouvelle image sur le serveur"><i class="icon-pays-small-white"></i> Modifier le drapeau</button>
      </form>
      <?php } ?>
      <div class="modal container fade" id="Modal-Monument"></div>
      <div class="clearfix"></div>

        <?php renderElement('errormsgs'); ?>


  <!-- Dirigeants
    ================================================== -->
    <section>
    <div id="dirigeants" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Pays1.png">
      <h1>Dirigeants</h1>
    </div>
    <table width="539" class="table table-hover">
      <thead>
        <tr class="tablehead">
          <th width="50%" scope="col">Pseudo</th>
          <th width="35%" scope="col">Permissions</th>
          <th width="5%" scope="col">&nbsp;</th>
          <th width="5%" scope="col">&nbsp;</th>
          <th width="5%" scope="col">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($paysLeaders as $rowLeaders) { ?>
          <tr>
            <td><?= $rowLeaders['ch_use_login'] ?></td>
            <td><?= Pays::getPermissionName($rowLeaders['permissions']); ?></td>
            <td>
            <?php if($_SESSION['userObject']->minStatus('OCGC') ||
                     $thisPays->getUserPermission() >= Pays::$permissions['dirigeant']): ?>
                <a class="btn btn-primary" href="../php/Modal/pays_leader_edit.php?user_pays_ID=<?= $rowLeaders['users_pays_ID'] ?>" data-toggle="modal" data-target="#Modal-Monument">Gérer cet utilisateur</a>
            <?php endif; ?>
            </td>
            <td>
            <?php if($_SESSION['userObject']->minStatus('OCGC') ||
                     $thisPays->getUserPermission() >= Pays::$permissions['dirigeant']): ?>
                <a class="btn btn-danger" href="../php/Modal/pays_leader_delete.php?user_pays_ID=<?= $rowLeaders['users_pays_ID'] ?>" data-toggle="modal" data-target="#Modal-Monument"><i class="icon-trash icon-white"></i> Supprimer</a>
            <?php endif; ?>
            </td>
            <td></td>
          </tr>
          <?php } ?>
      </tbody>
    </table>

    <a class="btn btn-primary btn-margin-left" href="../php/Modal/pays_leader_add.php?pays_ID=<?= $thisPays->ch_pay_id ?>" data-toggle="modal" data-target="#Modal-Monument">Ajouter un dirigeant...</a>

    </section>


      <?php if ($_SESSION['userObject']->minStatus('OCGC') ||
                $thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
      <!-- Debut formulaire Page Pays
        ================================================== -->
      <section class="">
        <div id="info-generales" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Pays1.png">
          <h1>Présentation du pays</h1>
        </div>
          <div class="well">
              <p>Gérez les informations qui seront affich&eacute;es sur la page consacr&eacute;e &agrave; votre
                  pays et plus g&eacute;n&eacute;ralement dans l'ensemble du site. Compl&eacute;tez-le au fur et &agrave; mesure que
                  votre pays grandit. </p>
          </div>
        <form action="<?php echo $editFormAction; ?>" name="InfoHeader" method="POST" class="form-horizontal" id="InfoHeader">
          <div class="accordion" id="accordion2"> 
            <!-- Boutons cachés -->
            <?php 
				  $now= date("Y-m-d G:i:s");
                  $nbupdate = $row_InfoGenerale['ch_pay_nb_update']+1; ?>
            <input name="ch_pay_date" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_date']; ?>">
            <input name="ch_pay_mis_jour" type="hidden" value="<?php echo $now; ?>">
            <input name="ch_pay_nb_update" type="hidden" value="<?php echo $nbupdate; ?>">
            <input name="ch_pay_id" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_id']; ?>">
            <input name="ch_pay_label" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_label']; ?>">
            <!-- Si l'ID du pays a ete trouve
        ================================================== -->
            <?php if ($_SESSION['userObject']->minStatus('OCGC')) { ?>
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle alert-danger" data-toggle="collapse" href="#collapsemoderation"> Param&egrave;tres r&eacute;serv&eacute;s &agrave; la mod&eacute;ration </a> </div>
              <div id="collapsemoderation" class="accordion-body collapse">
                <div class="accordion-inner">
                  <div class="row-fluid"> 
                    <!-- carte -->
                    <div id="map" class="span7"></div>
                    <!-- choix emplacement -->
                    <div class="span5">
                      <h3>Modifier l'emplacement :</h3>
                      <div id="spryradio2">
                        <select name="ch_pay_emplacement" id="ch_pay_emplacement">
                          <?php for ($nb_emplacement = 1; $nb_emplacement <= 58; $nb_emplacement++) {?>
                          <option value="<?php echo $nb_emplacement ?>"<?php if (!(strcmp("$nb_emplacement", $row_InfoGenerale['ch_pay_emplacement']))) {echo "selected=\"selected\"";} ?>> N°<?php echo $nb_emplacement ?></option>
                          <?php }?>
                        </select>
                        <span class="radioRequiredMsg">Effectuez une sélection.</span> </div>
                      <p>&nbsp;</p>
                      <!-- Definir statut du pays -->
                      <h3>Modifier le statut du pays :</h3>
                      <div id="spryradio1">
                        <label class="radio" for="ch_pay_publication_0">
                          <input <?php if (!(strcmp($row_InfoGenerale['ch_pay_publication'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" selected="selected" name="ch_pay_publication" value="1" id="ch_pay_publication_0">
                          Visible<a href="#" rel="clickover" title="Visible" data-content="Le pays sera visible dans le menu des continents et sur la carte du mondeGC"><i class="icon-info-sign"></i></a></label>
                        <p>&nbsp;</p>
                        <label class="radio" for="ch_pay_publication_1">
                          <input <?php if (!(strcmp($row_InfoGenerale['ch_pay_publication'],"2"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_pay_publication" value="2" id="ch_pay_publication_1">
                          Archiv&eacute;<a href="#" rel="clickover" title="Archiv&eacute;" data-content="Le pays est conserv&eacute; en tant qu'archive, dans l'histoire du Monde GC."><i class="icon-info-sign"></i></a></label>
                        <br>
                        <span class="radioRequiredMsg">Effectuez une sélection.</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            <!-- Informations Générales
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseOne"> Informations G&eacute;n&eacute;rales </a> </div>
              <div id="collapseOne" class="accordion-body collapse">
                <div class="accordion-inner">
                  <?php if ($_SESSION['statut'] < 20) { ?>
                  <!-- Boutons cachés -->
                  <input name="ch_pay_publication" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_publication']; ?>">
                  <input name="ch_pay_continent" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_continent']; ?>">
                  <input name="ch_pay_emplacement" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_emplacement']; ?>">
                  <?php } ?>
                  <!-- Nom pays -->
                  <div id="sprytextfield3" class="control-group">
                    <label class="control-label" for="ch_pay_nom">Nom du pays <a href="#" rel="clickover" data-placement="bottom" title="Nom du pays" data-content="35 caract&egrave;res maximum. Ce nom servira &agrave; identifier votre pays dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="input-xlarge" type="text" id="ch_pay_nom" name="ch_pay_nom" value="<?php echo $row_InfoGenerale['ch_pay_nom']; ?>" maxlength="50">
                      <span class="textfieldRequiredMsg">Le nom du pays est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">35 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Lien Forum -->
                  <div id="sprytextfield10" class="control-group">
                    <label class="control-label" for="ch_pay_lien_forum">Lien sujet sur le forum <a href="#" rel="clickover" data-placement="bottom" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacré à votre pays sur le forum. Cette information sevira à poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="span12" type="text" id="ch_pay_lien_forum" name="ch_pay_lien_forum" value="<?php echo $row_InfoGenerale['ch_pay_lien_forum']; ?>">
                      <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Devise -->
                  <div id="sprytextfield2" class="control-group">
                    <label class="control-label" for="ch_pay_devise">Devise du pays <a href="#" rel="clickover" title="Devise du pays" data-content="100 caract&egrave;res maximum. Mettez-ici une phrase d'accroche ou une devise du type : Libert&eacute; - Egalit&eacute; - Fraternit&eacute;"><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="span12" type="text" id="ch_pay_devise" name="ch_pay_devise" value="<?php echo $row_InfoGenerale['ch_pay_devise']; ?>" maxlength="100" >
                      <span class="textfieldMaxCharsMsg">100 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Lien image fond -->
                  <div id="sprytextfield1" class="control-group">
                    <label class="control-label" for="ch_pay_lien_imgheader">Lien image de fond <a href="#" rel="clickover" title="Image d'en-t&ecirc;te" data-content="Il s'agit de l'image qui sera affich&eacute;e en en-t&ecirc;te de la page de votre pays. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="span12" name="ch_pay_lien_imgheader" type="text" id="ch_pay_lien_imgheader" value="<?php echo $row_InfoGenerale['ch_pay_lien_imgheader']; ?>" maxlength="250">
                      <span class="textfieldInvalidFormatMsg">Format non valide.</span> <span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Lien Image drapeau -->
                      <input class="span12" name="ch_pay_lien_imgdrapeau" type="hidden" id="ch_pay_lien_imgdrapeau" value="<?php echo $row_InfoGenerale['ch_pay_lien_imgdrapeau']; ?>">
                  <!-- Forme de l'état -->
                  <div id="sprytextfield6" class="control-group">
                    <label class="control-label" for="ch_pay_forme_etat">Forme de l'&eacute;tat <a href="#" rel="clickover" title="R&eacute;gime politique" data-content="Fait r&eacute;f&eacute;rence &agrave; la mani&egrave;re dont le pouvoir est organis&eacute; et exerc&eacute; au sein de votre pays. Par exemple s'il s'agit d'une r&eacute;publique ou d'un royaume. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="input-xlarge" name="ch_pay_forme_etat" type="text" id="ch_pay_forme_etat" value="<?php echo $row_InfoGenerale['ch_pay_forme_etat']; ?>" maxlength="50">
                      <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Capitale -->
                  <div id="sprytextfield7" class="control-group">
                    <label class="control-label" for="ch_pay_capitale">Nom de votre capitale <a href="#" rel="clickover" title="Capitale" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="input-xlarge" name="ch_pay_capitale" type="text" id="ch_pay_capitale" value="<?php echo $row_InfoGenerale['ch_pay_capitale']; ?>" maxlength="50">
                      <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Langue officielle -->
                  <div id="sprytextfield8" class="control-group">
                    <label class="control-label" for="ch_pay_langue_officielle">Langue officielle <a href="#" rel="clickover" title="Langue officielle" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="input-xlarge" name="ch_pay_langue_officielle" type="text" id="ch_pay_langue_officielle" value="<?php echo $row_InfoGenerale['ch_pay_langue_officielle']; ?>" maxlength="50">
                      <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
                  </div>
                  <!-- Monnaie -->
                  <div id="sprytextfield9" class="control-group">
                    <label class="control-label" for="ch_pay_monnaie">monnaie officielle <a href="#" rel="clickover" data-placement="top" title="Monnaie" data-content=" La monnaie de votre pays reste fictive. Vous pouvez choisir le nom que vous souhaitez. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                      <input class="input-xlarge" name="ch_pay_monnaie" type="text" id="ch_pay_monnaie" value="<?php echo $row_InfoGenerale['ch_pay_monnaie']; ?>" maxlength="50">
                      <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Présentation
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefour"> Présentation </a> </div>
              <div id="collapsefour" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    Attention, ne pas passer &agrave; la ligne ou faire diff&eacute;rents paragraphes pour l'en-t&ecirc;te pr&eacute;sentation. L'en-t&ecirc;te est affich&eacute; sur la carte  et le passage &agrave; la ligne emp&ecirc;che la carte g&eacute;n&eacute;rale de s'afficher.</div>
                  <div id="sprytextarea2">
                    <label for="ch_pay_header_presentation">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te présentation" data-content=" L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_presentation" class="span12" id="ch_pay_header_presentation"><?php echo $row_InfoGenerale['ch_pay_header_presentation']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_presentation">Contenu <a href="#" rel="clickover" title="Contenu pr&eacute;sentation" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre de pr&eacute;sentation de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_presentation" class="wysiwyg" id="ch_pay_text_presentation"><?php echo $row_InfoGenerale['ch_pay_text_presentation']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Geographie
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsefive"> G&eacute;ographie </a> </div>
              <div id="collapsefive" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea3">
                    <label for="ch_pay_header_geographie">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te g&eacute;ographie" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_geographie" class="span12" id="ch_pay_header_geographie"><?php echo $row_InfoGenerale['ch_pay_header_geographie']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_geographie">Contenu <a href="#" rel="clickover" title="Contenu g&eacute;ographie" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; la g&eacute;ographie de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_geographie" class="wysiwyg" id="ch_pay_text_geographie"><?php echo $row_InfoGenerale['ch_pay_text_geographie']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- politique
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsetwelve"> Politique </a> </div>
              <div id="collapsetwelve" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea10">
                    <label for="ch_pay_header_politique">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te politique" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_politique" class="span12" id="ch_pay_header_politique"><?php echo $row_InfoGenerale['ch_pay_header_politique']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_politique">Contenu <a href="#" rel="clickover" title="Contenu politique" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; la politique de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_politique" class="wysiwyg" id="ch_pay_text_politique"><?php echo $row_InfoGenerale['ch_pay_text_politique']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Histoire
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsesix"> Histoire </a> </div>
              <div id="collapsesix" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea4">
                    <label for="ch_pay_header_histoire">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te histoire" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_histoire" class="span12" id="ch_pay_header_histoire"><?php echo $row_InfoGenerale['ch_pay_header_histoire']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_histoire">Contenu <a href="#" rel="clickover" title="Contenu histoire" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; l'histoire de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i> class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_histoire" class="wysiwyg" id="ch_pay_text_histoire"><?php echo $row_InfoGenerale['ch_pay_text_histoire']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Economie
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseseven"> Economie </a> </div>
              <div id="collapseseven" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea5">
                    <label for="ch_pay_header_economie">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te &eacute;conomie" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_economie" class="span12" id="ch_pay_header_economie"><?php echo $row_InfoGenerale['ch_pay_header_economie']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_economie">Contenu <a href="#" rel="clickover" title="Contenu histoire" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; &agrave; l'histoire de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_economie" class="wysiwyg" id="ch_pay_text_economie"><?php echo $row_InfoGenerale['ch_pay_text_economie']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Transport
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseeight"> Transport </a> </div>
              <div id="collapseeight" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea6">
                    <label for="ch_pay_header_transport">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te transport" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_transport" class="span12" id="ch_pay_header_transport"><?php echo $row_InfoGenerale['ch_pay_header_transport']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_transport">Contenu <a href="#" rel="clickover" title="Contenu transport" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; aux transports de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_transport" class="wysiwyg" id="ch_pay_text_transport"><?php echo $row_InfoGenerale['ch_pay_text_transport']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Sport
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapsenine"> Sport </a> </div>
              <div id="collapsenine" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea7">
                    <label for="ch_pay_header_sport">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te sport" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_sport" class="span12" id="ch_pay_header_transport"><?php echo $row_InfoGenerale['ch_pay_header_sport']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_sport">Contenu <a href="#" rel="clickover" title="Contenu sport" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute; aux sports de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_sport" class="wysiwyg" id="ch_pay_text_sport"><?php echo $row_InfoGenerale['ch_pay_text_sport']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Culture
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseten"> Culture </a> </div>
              <div id="collapseten" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea8">
                    <label for="ch_pay_header_culture">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te culture" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_culture" class="span12" id="ch_pay_header_culture"><?php echo $row_InfoGenerale['ch_pay_header_culture']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_culture">Contenu <a href="#" rel="clickover" title="Contenu culture" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute;  &agrave; la culture de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_culture" class="span12 wysiwyg" id="ch_pay_text_culture"><?php echo $row_InfoGenerale['ch_pay_text_culture']; ?></textarea>
                </div>
              </div>
            </div>
            <!-- Patrimoine
        ================================================== -->
            <div class="accordion-group">
              <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" href="#collapseeleven"> Patrimoine </a> </div>
              <div id="collapseeleven" class="accordion-body collapse">
                <div class="accordion-inner"> 
                  <!-- Header -->
                  <div id="sprytextarea9">
                    <label for="ch_pay_header_patrimoine">En-t&ecirc;te <a href="#" rel="clickover" title="En-t&ecirc;te patrimoine" data-content="L'en-t&ecirc;te est mis en exergue dans la mise en page. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
                    <textarea rows="3" name="ch_pay_header_patrimoine" class="span12" id="ch_pay_header_patrimoine"><?php echo $row_InfoGenerale['ch_pay_header_patrimoine']; ?></textarea>
                    <br>
                    <span class="textareaMaxCharsMsg">250 caract&egrave;res max.</span> </div>
                  <p>&nbsp;</p>
                  <!-- Contenu -->
                  <label for="ch_pay_text_patrimoine">Contenu <a href="#" rel="clickover" title="Contenu culture" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; du chapitre consacr&eacute;  au patrimoine de votre pays. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles."><i class="icon-info-sign"></i></a></label>
                  <textarea rows="20" name="ch_pay_text_patrimoine" class="span12 wysiwyg" id="ch_pay_text_patrimoine"><?php echo $row_InfoGenerale['ch_pay_text_patrimoine']; ?></textarea>
                </div>
              </div>
            </div>
          </div>
          <!-- Bouton envoyer
        ================================================== -->
          <button type="submit" class="btn btn-primary btn-margin-left">Enregistrer</button>
          <input type="hidden" name="MM_update" value="InfoHeader">
        </form>
        <!-- FIN formulaire Page Pays
        ================================================== -->
        <?php  } else { // fin section pays affichée que pr statut sup ou egal à celui de dirigeant ?>
        <div class="alert alert-success"> Contactez le dirigeant pour modifier la page de votre pays</div>
        <?php } ?>
      </section>


    <!-- Personnage
    ================================================== -->
    <section>
    <div id="personnage" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Membre1.png">
      <h1>Personnage</h1>
    </div>
    <div class="well">
        <p>Ici, vous pourrez gérer le personnage représentant le dirigeant de votre pays.</p>
    </div>
    <?php if(empty($paysPersonnages)) { ?>
    <p>Ce pays n'a pas encore de dirigeant.</p>
    <?php } else { ?>
    <div class="row-fluid">
        <div class="span4">
            <img src="<?= $paysPersonnages['lien_img'] ?>" />
        </div>
        <div class="span8">
            <h3>
                <?= $paysPersonnages['predicat'] ?>
                <?= $paysPersonnages['prenom_personnage'] ?>
                <?= $paysPersonnages['nom_personnage'] ?></h3>
            <small><?= $paysPersonnages['titre_personnage'] ?></small>
            <br>
        <p><a href="avatar_modifier.php?paysID=<?= $thisPays->model->ch_pay_id ?>"
             class="btn btn-primary">Modifier l'avatar du personnage</a></p>
         <p><a href="#ModalPers" role="button" class="btn btn-primary" data-toggle="modal">Modifier personnage</a>
        </p>
        </div>
    </div>
    <?php } ?>

    </section>


      <!-- Liste des Villes du membre
        ================================================== -->
      <section>
        <div id="villes" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Ville1.png">
          <h1>Villes</h1>
        </div>

        <?php if ($row_mesvilles) { ?>
          <h3>Mes villes</h3>
        <table width="539" class="table table-hover">
          <thead>
            <tr class="tablehead">
              <th width="5%" scope="col"><a href="#" rel="clickover" title="Statut de votre ville" data-content="la ville peut-&ecirc;tre publi&eacute;e sur votre page pays ou masqu&eacute;e. Le drapeau indique la capitale."><i class="icon-globe"></i></a></th>
              <th width="64%" scope="col">Nom</th>
              <th width="23%" scope="col">population</th>
              <th width="4%" scope="col">&nbsp;</th>
              <?php if($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']): ?>
              <th width="4%" scope="col">&nbsp;</th>
              <th width="4%" scope="col">&nbsp;</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php do { ?>
              <tr>
                <td><img src="../assets/img/statutvil_<?php echo $row_mesvilles['ch_vil_capitale']; ?>.png" alt="Statut"></td>
                <td><?php if ($row_mesvilles['ch_vil_paysID'] != $row_mesvilles['ch_use_paysID']) { ?>
                  <img src="<?php echo $row_mesvilles['ch_pay_lien_imgdrapeau']; ?>" alt="drapeau" width="50px" title="ville appartenant au pays <?php echo $row_mesvilles['ch_pay_nom'] ?>">
                  <?php } ?>
                  <?php echo $row_mesvilles['ch_vil_nom']; ?></td>
                <td><?php echo formatNum($row_mesvilles['ch_vil_population']); ?></td>
                <td>
                    <a class="btn btn-primary" href="../page-ville.php?ch_pay_id=<?= $row_mesvilles['ch_vil_paysID'] ?>&ch_ville_id=<?= $row_mesvilles['ch_vil_ID'] ?>">Visiter</a>
                </td>
                <?php if($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']): ?>
                <td><form action="ville_modifier.php" method="post">
                    <input name="ville-ID" type="hidden" value="<?php echo $row_mesvilles['ch_vil_ID']; ?>">
                    <button class="btn" type="submit" title="modifier la ville"><i class="icon-pencil"></i></button>
                  </form></td>
                <td><form action="ville_confirmation_supprimer.php" method="post">
                    <input name="ville-ID" type="hidden" value="<?php echo $row_mesvilles['ch_vil_ID']; ?>">
                    <button class="btn btn-danger" type="submit" title="supprimer la ville"><i class="icon-trash icon-white"></i></button>
                  </form></td>
                <?php endif; ?>
              </tr>
              <?php } while ($row_mesvilles = mysql_fetch_assoc($mesvilles)); ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5"><p class="pull-right">de <?php echo ($startRow_mesvilles + 1) ?> &agrave; <?php echo min($startRow_mesvilles + $maxRows_mesvilles, $totalRows_mesvilles) ?> sur <?php echo $totalRows_mesvilles ?>
                  <?php if ($pageNum_mesvilles > 0) { // Show if not first page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_mesvilles=%d%s#mes-villes", $currentPage, max(0, $pageNum_mesvilles - 1), $queryString_mesvilles); ?>"><i class=" icon-backward"></i> </a>
                    <?php } // Show if not first page ?>
                  <?php if ($pageNum_mesvilles < $totalPages_mesvilles) { // Show if not last page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_mesvilles=%d%s#mes-villes", $currentPage, min($totalPages_mesvilles, $pageNum_mesvilles + 1), $queryString_mesvilles); ?>"> <i class="icon-forward"></i></a>
                    <?php } // Show if not last page ?>
                </p>
              <?php if($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']): ?>
                <form action="ville_ajouter.php" method="post">
                  <input name="paysID" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_id']; ?>">
                  <input name="user_ID" type="hidden" value="<?php echo $row_User['ch_use_id']; ?>">
                  <button class="btn btn-primary btn-margin-left" type="submit">Ajouter une ville</button>
                </form>
                <?php endif; ?></td>
            </tr>
          </tfoot>
        </table>
        <?php } else { ?>
            <?php if($thisPays->getUserPermission() >= Pays::$permissions['codirigeant']): ?>
                <h3>Mes villes</h3>
                <form action="ville_ajouter.php" method="post">
                  <input name="paysID" type="hidden" value="<?php echo $row_InfoGenerale['ch_pay_id']; ?>">
                  <input name="user_ID" type="hidden" value="<?php echo $row_User['ch_use_id']; ?>">
                  <button class="btn btn-primary btn-margin-left" type="submit">Ajouter une ville</button>
                </form>
            <?php endif; ?></td>
        <?php } ?>

    <!-- Liste des Villes des autres joueurs
        ================================================== -->
      <?php if ($row_autres_villes) { ?>
          <?php if ($_SESSION['userObject']->minStatus('OCGC')) { ?>
          <h3>Villes du pays</h3>
          <?php } else { ?>
          <!-- titre si modération -->
          <h3>Villes des autres dirigeants</h3>
          <?php } ?>
        <table width="539" class="table table-hover">
          <thead>
            <tr class="tablehead">
              <th width="5%" scope="col"><a href="#" rel="clickover" title="Statut de la ville" data-content="la ville peut-&ecirc;tre publi&eacute;e sur la page pays ou masqu&eacute;e. Le drapeau indique la capitale."><i class="icon-globe"></i></a></th>
              <th width="46%" scope="col">Nom</th>
              <th width="23%" scope="col">Maire</th>
              <th width="23%" scope="col">Population</th>
              <th width="4%" scope="col">&nbsp;</th>
              <?php if ($_SESSION['userObject']->minStatus('OCGC') ||
                        $thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) { ?>
              <th width="4%" scope="col">&nbsp;</th>
              <th width="4%" scope="col">&nbsp;</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php do { ?>
              <tr>
                <td><img src="../assets/img/statutvil_<?php echo $row_autres_villes['ch_vil_capitale']; ?>.png" alt="Statut"></td>
                <td><?php echo $row_autres_villes['ch_vil_nom']; ?></td>
                <td><?php echo $row_autres_villes['ch_use_login']; ?></td>
                <td><?php echo formatNum($row_autres_villes['ch_vil_population']); ?></td>
                <td>
                    <a class="btn btn-primary" href="../page-ville.php?ch_pay_id=<?= $row_autres_villes['ch_vil_paysID'] ?>&ch_ville_id=<?= $row_autres_villes['ch_vil_ID'] ?>">Visiter</a>
                </td>
                <?php if ($_SESSION['userObject']->minStatus('OCGC') ||
                        $thisPays->getUserPermission() >= Pays::$permissions['codirigeant']) {
                    // Affichage si sup ou egal à dirigeant ?>
                <td><form action="ville_modifier.php" method="post">
                    <input name="ville-ID" type="hidden" value="<?php echo $row_autres_villes['ch_vil_ID']; ?>">
                    <button class="btn" type="submit" title="modifier la ville"><i class="icon-pencil"></i></button>
                  </form></td>
                <td><form action="ville_confirmation_supprimer.php" method="post">
                    <input name="ville-ID" type="hidden" value="<?php echo $row_autres_villes['ch_vil_ID']; ?>">
                    <button class="btn btn-danger" type="submit" title="supprimer la ville"><i class="icon-trash icon-white"></i></button>
                  </form></td>
                <?php } ?>
              </tr>
              <?php } while ($row_autres_villes = mysql_fetch_assoc($autres_villes)); ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6"><p class="pull-right">de <?php echo ($startRow_autres_villes + 1) ?> &agrave; <?php echo min($startRow_autres_villes + $maxRows_autres_villes, $totalRows_autres_villes) ?> sur <?php echo $totalRows_autres_villes ?>
                  <?php if ($pageNum_autres_villes > 0) { // Show if not first page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_autres_villes=%d%s#mes-villes", $currentPage, max(0, $pageNum_autres_villes - 1), $queryString_autres_villes); ?>"><i class=" icon-backward"></i> </a>
                    <?php } // Show if not first page ?>
                  <?php if ($pageNum_autres_villes < $totalPages_autres_villes) { // Show if not last page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_autres_villes=%d%s#mes-villes", $currentPage, min($totalPages_autres_villes, $pageNum_autres_villes + 1), $queryString_autres_villes); ?>"> <i class="icon-forward"></i></a>
                    <?php } // Show if not last page ?>
                </p></td>
            </tr>
          </tfoot>
        </table>
      </section>
      <?php }  // fin affichage section autre villes si existantes.  ?>

      <!-- Routes et campagne
        ================================================== -->
      <section>
        <div id="routes-campagne" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/carte.png">
          <h1>Routes et campagne</h1>
        </div>
        <div class="alert alert-success"> Vous pouvez dessiner des routes, des zones agricoles ou des zones naturelles entre vos villes sur la carte de votre pays. Ces zones vont avoir une influence sur l'économie et la population de votre pays </div>
        <h3>Balance des ressources issues de la carte</h3>
        <ul class="token">
          <li class="span1"><a title="Budget"><img src="../assets/img/ressources/Budget.png" alt="icone Budget"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_budget_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Industrie"><img src="../assets/img/ressources/Industrie.png" alt="icone Industrie"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_industrie_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Commerce"><img src="../assets/img/ressources/Bureau.png" alt="icone Commerce"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_commerce_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Agriculture"><img src="../assets/img/ressources/Agriculture.png" alt="icone Agriculture"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_agriculture_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Tourisme"><img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_tourisme_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Recherche"><img src="../assets/img/ressources/Recherche.png" alt="icone Recherche"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_recherche_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Environnement"><img src="../assets/img/ressources/Environnement.png" alt="icone Environnement"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_environnement_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span1"><a title="Education"><img src="../assets/img/ressources/Education.png" alt="icone Education"></a>
            <p> <strong>
              <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_education_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
              </strong> </p>
          </li>
          <li class="span4"> <a class="btn btn-primary" href="../php/ressource-rapport-carte.php?ch_pay_id=<?php echo $colname_paysID ?>" data-toggle="modal" data-target="#Modal-Monument" title="voir le d&eacute;tail des ressources">D&eacute;tail</a> </li>
        </ul>
        <div class="span12">
          <p>&nbsp;</p>
          <p>Population rurale&nbsp;: <strong>
            <?php $chiffre_francais = number_format($row_InfoGenerale['ch_pay_population_carte'], 0, ',', ' '); echo $chiffre_francais; ?>
            habitants</strong></p>
        </div>
        <div class="span12">
          <form action="../Carte-modifier.php" method="post">
            <input name="paysID" type="hidden" value="<?php echo $colname_paysID; ?>">
            <button class="btn btn-primary" type="submit" title="lien vers les outils de dessin">Dessiner sur la carte</button>
          </form>
        </div>
      <div class="clearfix"></div>
      </section>
      
      <!-- Liste des Communiqués
        ================================================== -->
      <?php if ($thisPays->getUserPermission() >= Pays::$permissions['dirigeant']) { // Affichage si sup ou egal à dirigeant ?>
      <section id="mes-communiques">
        <div id="mes-communiques" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Communique.png">
          <h1>Communiqu&eacute;s du pays</h1>
        </div>
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Lancez un communiqu&eacute; au nom de votre pays. Les communiqu&eacute;s post&eacute;s &agrave; partir de cette page seront consid&eacute;r&eacute;s comme des annonces officielles &eacute;manant du chef votre gouvernement.</div>
        <?php 
$userID = $row_User['ch_use_id'];
$com_cat = "pays";
$com_element_id = $row_InfoGenerale['ch_pay_id'];
include('../php/communiques-back.php'); ?>
      </section>
      <?php } // Affichage si sup ou egal à dirigeant ?>
      <!-- Liste des faits historiques
        ================================================== -->
      <?php if ($thisPays->getUserPermission() >= Pays::$permissions['dirigeant']) { // Affichage si sup ou egal à dirigeant?>
      <section>
        <div id="faits-historiques" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/faithistorique.png">
          <h1>L'histoire du pays</h1>
        </div>
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Identifiez-ici les éléments de l'histoire de votre pays que vous souhaitez mettre en valeur en leur créant une page dédiée. Ces éléments seront publiés dans la section histoire de la page de votre pays et pourront prétendre faire partie de la grande histoire du Monde GC.</div>
        <?php if ($row_fait_hist) { ?>
        <table width="539" class="table table-hover">
          <thead>
            <tr class="tablehead">
              <th width="5%" scope="col"><a href="#" rel="clickover" title="Statut de votre fait historique" data-content="Un fait historique peut-&ecirc;tre publi&eacute; sur votre page pays ou masqu&eacute;."><i class="icon-globe"></i></a></th>
              <th width="15%" scope="col">Type</th>
              <th width="54%" scope="col">Nom</th>
              <th width="23%" scope="col">Date</th>
              <th width="4%" scope="col">&nbsp;</th>
              <th width="4%" scope="col">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php do { ?>
              <tr>
                <td><img src="../assets/img/statutvil_<?php echo $row_fait_hist['ch_his_statut']; ?>.png" alt="Statut"></td>
                <td><?php if ($row_fait_hist['ch_his_date_fait2'] == NULL AND $row_fait_hist['ch_his_personnage']== 1) {
					echo "Fait";
					} elseif ($row_fait_hist['ch_his_date_fait2'] != NULL AND $row_fait_hist['ch_his_personnage']== 1) {
					echo "P&eacute;riode";
					} elseif ($row_fait_hist['ch_his_personnage']== 2) {
					echo "Personnage";
					} else {
					echo "Inconnu";
						} ?></td>
                <td><strong><?php echo $row_fait_hist['ch_his_nom']; ?></strong></td>
                <td>Le <?php echo affDate($row_fait_hist['ch_his_date_fait']); ?></td>
                <td><form action="<?php if ($row_fait_hist['ch_his_personnage'] == 2) {
					echo "personnage_historique_modifier.php";
					} else {
					echo "fait_historique_modifier.php";
						} ?>" method="post">
                    <input name="ch_his_id" type="hidden" value="<?php echo $row_fait_hist['ch_his_id']; ?>">
                    <button class="btn" type="submit" title="modifier cet &eacute;l&eacute;ment historique"><i class="icon-pencil"></i></button>
                  </form></td>
                <td><form action="fait_historique_confirmation_supprimer.php" method="post">
                    <input name="ch_his_id" type="hidden" value="<?php echo $row_fait_hist['ch_his_id']; ?>">
                    <button class="btn" type="submit" title="supprimer ce fait historique"><i class="icon-trash"></i></button>
                  </form></td>
              </tr>
              <?php } while ($row_fait_hist = mysql_fetch_assoc($fait_hist)); ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5"><p class="pull-right">de <?php echo ($startRow_fait_hist + 1) ?> &agrave; <?php echo min($startRow_fait_hist + $maxRows_fait_hist, $totalRows_fait_hist) ?> sur <?php echo $totalRows_fait_hist ?>
                  <?php if ($pageNum_fait_hist > 0) { // Show if not first page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_fait_hist=%d%s#mes-fait_hists", $currentPage, max(0, $pageNum_fait_hist - 1), $queryString_fait_hist); ?>"><i class=" icon-backward"></i> </a>
                    <?php } // Show if not first page ?>
                  <?php if ($pageNum_fait_hist < $totalPages_fait_hist) { // Show if not last page ?>
                    <a class="btn" href="<?php printf("%s?pageNum_fait_hist=%d%s#mes-fait_hists", $currentPage, min($totalPages_fait_hist, $pageNum_fait_hist + 1), $queryString_fait_hist); ?>"> <i class="icon-forward"></i></a>
                    <?php } // Show if not last page ?>
                </p>
                <form action="fait_historique_ajouter.php" method="post" class="form-button-inline">
                  <input name="paysID" type="hidden" value="<?php echo $colname_paysID;?>">
                  <button class="btn btn-primary btn-margin-left" type="submit">Ajouter un fait historique</button>
                </form>
                <form action="personnage_historique_ajouter.php" method="post" class="form-button-inline">
                  <input name="paysID" type="hidden" value="<?php echo $colname_paysID;?>">
                  <button class="btn btn-primary btn-margin-left" type="submit">Ajouter un personnage historique</button>
                </form></td>
            </tr>
          </tfoot>
        </table>
        <?php } else { ?>
        <form action="fait_historique_ajouter.php" method="post" class="form-button-inline">
          <input name="paysID" type="hidden" value="<?php echo $colname_paysID; ?>">
          <button class="btn btn-primary btn-margin-left" type="submit">Ajouter un fait historique</button>
        </form>
        <form action="personnage_historique_ajouter.php" method="post" class="form-button-inline">
          <input name="paysID" type="hidden" value="<?php echo $colname_paysID;?>">
          <button class="btn btn-primary btn-margin-left" type="submit">Ajouter un personnage historique</button>
        </form>
        <?php } ?>
      </section>
      <?php } // Affichage si sup ou egal à dirigeant ?>
    </div>
  </div>


    <!-- Formulaire Personnage
        ================================================== -->
<!-- Modal -->
<div id="ModalPers" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ModalPersLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Informations Personnage</h3>
  </div>
  <div class="modal-body">
  <form action="membre-modifier_back.php" name="InfoUser" method="POST" class="form-horizontal" id="InfoUser">

      <input name="personnage_id" type="hidden" value="<?php echo $paysPersonnages['id']; ?>">
    <!-- Predicat -->
    <div class="control-group">
      <label class="control-label" for="ch_use_predicat_dirigeant">Pr&eacute;dicat <a href="#" rel="clickover" title="Pr&eacute;dicat" data-content="Lorsque votre dirigeant sera nomm&eacute;, notamment lors des c&eacute;r&eacute;monies protocolaires, sp&eacute;cifiez quelle appellation doit être utilis&eacute;e. Le pr&eacute;dicat pr&eacute;c&egrave;de le nom et le pr&eacute;nom"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <select name="ch_use_predicat_dirigeant" id="ch_use_predicat_dirigeant" class="input-xlarge">
          <option value="" <?php if (!(strcmp("", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>aucun</option>
          <option value="Chef" selected="selected" <?php if (!(strcmp("Chef", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Chef</option>
          <option value="Le Capricieux" <?php if (!(strcmp("Le Capricieux", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Le Capricieux</option>
          <option value="L'Ignoblissime" <?php if (!(strcmp("L\'Ignoblissime", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>L'Ignoblissime</option>
          <option value="L'Incorrigible" <?php if (!(strcmp("L\'Incorrigible", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>L'Incorrigible</option>
          <option value="L'Intraitable" <?php if (!(strcmp("L\'Intraitable", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>L'Intraitable</option>
          <option value="Le Terrible" <?php if (!(strcmp("Le Terrible", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Le Terrible</option>
          <option value="Le Très honorable" <?php if (!(strcmp("Le Très honorable", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Le Tr&egrave;s honorable</option>
          <option value="Madame" <?php if (!(strcmp("Madame", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Madame</option>
          <option value="Mademoiselle" <?php if (!(strcmp("Mademoiselle", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Mademoiselle</option>
          <option value="Messire" <?php if (!(strcmp("Messire", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Messire</option>
          <option value="Monseigneur" <?php if (!(strcmp("Monseigneur", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Monseigneur</option>
          <option value="Monsieur" <?php if (!(strcmp("Monsieur", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Monsieur</option>
          <option value="Notre Guide" <?php if (!(strcmp("Notre Guide", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Notre Guide</option>
          <option value="Notre Guide suprême" <?php if (!(strcmp("Notre Guide suprême", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Notre Guide supr&ecirc;me</option>
          <option value="Notre Grandeur" <?php if (!(strcmp("Notre Grandeur", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Notre Grandeur</option>
          <option value="Sa Grâce" <?php if (!(strcmp("Sa Grâce", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Gr&acirc;ce</option>
          <option value="Sa Haute Excellence" <?php if (!(strcmp("Sa Haute Excellence", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Haute Excellence</option>
          <option value="Sa Haute Naissance" <?php if (!(strcmp("Sa Haute Naissance", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Haute Naissance</option>
          <option value="Sa Majesté" <?php if (!(strcmp("Sa Majesté", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Majest&eacute;</option>
          <option value="Sa Majesté impériale" <?php if (!(strcmp("Sa Majesté impériale", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Majest&eacute; imp&eacute;riale</option>
          <option value="Sa Sainteté" <?php if (!(strcmp("Sa Sainteté", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Sa Saintet&eacute;</option>
          <option value="Son Altesse" <?php if (!(strcmp("Son Altesse", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Altesse</option>
          <option value="Son Altesse illustrissime" <?php if (!(strcmp("Son Altesse illustrissime", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Altesse illustrissime</option>
          <option value="Son Altesse impériale" <?php if (!(strcmp("Son Altesse impériale", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Altesse imp&eacute;riale</option>
          <option value="Son Altesse royale" <?php if (!(strcmp("Son Altesse royale", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Altesse royale</option>
          <option value="Son Altesse sérénissime" <?php if (!(strcmp("Son Altesse sérénissime", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Altesse s&eacute;r&eacute;nissime</option>
          <option value="Son illustrissime Luminescence" <?php if (!(strcmp("Son illustrissime Luminescence", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son illustrissime Luminescence</option>
          <option value="Son Excellence" <?php if (!(strcmp("Son Excellence", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son Excellence</option>
          <option value="Son éminence" <?php if (!(strcmp("Son éminence", $paysPersonnages['predicat']))) {echo "selected=\"selected\"";} ?>>Son &eacute;minence </option>
        </select>
      </div>
    </div>
    <!-- Nom dirigeant -->
    <div id="sprytextfield31" class="control-group">
      <label class="control-label" for="ch_use_nom_dirigeant">Nom du dirigeant <a href="#" rel="clickover" title="Nom du dirigeant" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" name="ch_use_nom_dirigeant" type="text" id="ch_use_nom_dirigeant" value="<?php echo $paysPersonnages['nom_personnage']; ?>" maxlength="50">
        <br>
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
    </div>
    <!-- Prenom dirigeant -->
    <div id="sprytextfield32" class="control-group">
      <label class="control-label" for="ch_use_prenom_dirigeant">Pr&eacute;nom du dirigeant <a href="#" rel="clickover" title="pr&eacute;nom du dirigeant" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" name="ch_use_prenom_dirigeant" type="text" id="ch_use_prenom_dirigeant" value="<?php echo $paysPersonnages['prenom_personnage']; ?>" maxlength="50">
        <br>
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
    </div>
    <!-- Titre dirigeant -->
    <div id="sprytextfield33" class="control-group">
      <label class="control-label" for="ch_use_titre_dirigeant">Titre du dirigeant <a href="#" rel="clickover" title="Titre du dirigeant" data-content="Le titre doit faire r&eacute;f&eacute;rence au syst&egrave;me politique et citer le nom de votre pays. Par exemple : Pr&eacute;sident de la r&eacute;publique fran&ccedil;aise. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" name="ch_use_titre_dirigeant" type="text" id="ch_use_titre_dirigeant" value="<?php echo $paysPersonnages['titre_personnage']; ?>" maxlength="250">
        <br>
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
    </div>
    <!-- Biographie dirigeant -->
    <div id="sprytextarea30" class="control-group">
      <label class="control-label" for="ch_use_biographie_dirigeant">Biographie <a href="#" rel="clickover" title="Biographie" data-content="Donnez en quelques lignes des informations qui permettrons &agrave; vos homologues du Monde GC de mieux cerner votre personnage. 500 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <textarea rows="6" name="ch_use_biographie_dirigeant" class="input-xlarge" id="ch_use_biographie_dirigeant"><?php echo $paysPersonnages['biographie']; ?></textarea>
        <br>
        <span class="textareaMaxCharsMsg">500 caract&egrave;res max.</span></div>
    </div>
    </div>
    <div class="modal-footer">
      <!-- Bouton envoyer
        ================================================== -->
      <button data-dismiss="modal" aria-hidden="true" class="btn">Fermer</button>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <input type="hidden" name="MM_update" value="InfoUser">
    </div>
  </form>
</div>


</div>
</div>
<!-- MESSAGE EN CAS DE PAYS ARCHIVE
================================================== -->
<?php } elseif ($colname_paysID != NULL AND $row_InfoGenerale['ch_pay_publication'] ==3) { ?>
<header class="jumbotron subhead anchor" id="overview">
  <div class="container">
    <div class="well">
      <h3>Votre pays est archiv&eacute;</h3>
      <p>Votre pays est archiv&eacute; dans le Monde G&eacute;n&eacute;ration City. Contactez un membre du Haut-Conseil.</p>
    </div>
  </div>
</header>

<!-- MESSAGE EN CAS DE PAYS ID INCONNU
================================================== -->
<?php } else { ?>
<header class="jumbotron subhead anchor" id="overview">
  <div class="container">
    <div class="well">
      <h3>L'identifiant de votre pays est inconnu</h3>
      <p>Vous n'avez pas de pays dans le Monde G&eacute;n&eacute;ration City. Contactez un membre du Haut-Conseil afin qu'il vous attribue un emplacement</p>
    </div>
  </div>
</header>
<?php } ?>
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('../php/carteemplacements.php'); ?>
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.css" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:100, isRequired:false, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars:2, maxChars:35, validateOn:["change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "url", {isRequired:false, maxChars:500, validateOn:["change"]});
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea3 = new Spry.Widget.ValidationTextarea("sprytextarea3", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea4 = new Spry.Widget.ValidationTextarea("sprytextarea4", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea5 = new Spry.Widget.ValidationTextarea("sprytextarea5", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea6 = new Spry.Widget.ValidationTextarea("sprytextarea6", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea7 = new Spry.Widget.ValidationTextarea("sprytextarea7", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea8 = new Spry.Widget.ValidationTextarea("sprytextarea8", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea9 = new Spry.Widget.ValidationTextarea("sprytextarea9", {maxChars:250, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextarea10 = new Spry.Widget.ValidationTextarea("sprytextarea10", {maxChars:500, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprytextfield31 = new Spry.Widget.ValidationTextField("sprytextfield31", "none", {maxChars:50, validateOn:["change"]});
var sprytextfield32 = new Spry.Widget.ValidationTextField("sprytextfield32", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield33 = new Spry.Widget.ValidationTextField("sprytextfield33", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextarea30 = new Spry.Widget.ValidationTextarea("sprytextarea30", {maxChars:500, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
<?php
mysql_free_result($mesvilles);

mysql_free_result($communiquesPays);

mysql_free_result($InfoGenerale);

mysql_free_result($User);

mysql_free_result($fait_hist);