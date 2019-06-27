<?php


require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

//requete instituts
$institut_id = 2;
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_feature")) {
  $insertSQL = sprintf("INSERT INTO geometries (ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_geo_wkt'], "text"),
					   GetSQLValueString($_POST['ch_geo_pay_id'], "int"),
					   GetSQLValueString($_POST['ch_geo_user'], "int"),
					   GetSQLValueString($_POST['ch_geo_maj_user'], "int"),
                       GetSQLValueString($_POST['ch_geo_date'], "date"),
                       GetSQLValueString($_POST['ch_geo_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_geo_geometries'], "text"),
                       GetSQLValueString($_POST['ch_geo_mesure'], "int"),
                       GetSQLValueString($_POST['ch_geo_type'], "text"),
                       GetSQLValueString($_POST['ch_geo_nom'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "institut_geographie.php?bounds=".$_POST['ch_geo_bounds'];
  header(sprintf("Location: %s", $insertGoTo));
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modifier_feature")) {
  $updateSQL = sprintf("UPDATE geometries SET ch_geo_wkt=%s, ch_geo_pay_id=%s, ch_geo_user=%s, ch_geo_maj_user=%s, ch_geo_date=%s, ch_geo_mis_jour=%s, ch_geo_geometries=%s, ch_geo_mesure=%s, ch_geo_type=%s, ch_geo_nom=%s WHERE ch_geo_id=%s",
                       GetSQLValueString($_POST['ch_geo_wkt'], "text"),
					   GetSQLValueString($_POST['ch_geo_pay_id'], "int"),
					   GetSQLValueString($_POST['ch_geo_user'], "int"),
					   GetSQLValueString($_POST['ch_geo_maj_user'], "int"),
                       GetSQLValueString($_POST['ch_geo_date'], "date"),
                       GetSQLValueString($_POST['ch_geo_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_geo_geometries'], "text"),
                       GetSQLValueString($_POST['ch_geo_mesure'], "decimal"),
                       GetSQLValueString($_POST['ch_geo_type'], "text"),
                       GetSQLValueString($_POST['ch_geo_nom'], "text"),
					   GetSQLValueString($_POST['ch_geo_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
  $updateGoTo = "institut_geographie.php?bounds=".$_POST['ch_geo_bounds'];
  header(sprintf("Location: %s", $updateGoTo));
}


$bounds = "0,0";
if (isset($_GET['bounds'])) {
	$bounds = $_GET['bounds'];
}


$_SESSION['last_work'] = "institut_geographie.php";
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Gérer l'<?php echo $row_institut['ch_ins_nom']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
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
	background-image: url('');
}
#map {
	width: 100%;
	height: 500px;
	background: #FFFFFF;
}
#info {
	color: #FFFFFF;
}
img.olTileImage {
	max-width: none;
}
@media (min-width: 1200px) {
#map {
}
}
@media (max-width: 480px) {
#map {
	height: 360px;
}
}
div.olControlPanel {
	top: 0px;
	left: 50px;
	position: absolute;
}
.olControlPanel div {
	display: block;
	width: 22px;
	height: 22px;
	border: thin solid black;
	margin-top: 10px;
	background-color: white
}
div.editPanel {
	top: 77px;
	right: 0px;
	position: absolute;
}
.editPanel div {
	background-image: url("../Carto/images/edit_sprite.png");
	background-repeat: no-repeat;
	width: 40px;
	height: 30px;
	border: none;
	margin-top: 5px;
	cursor:pointer;
}
.lineButtonItemInactive {
	background-position: 0px 0px;
}
.lineButtonItemActive {
	background-position: 0px -30px;
}
.polygonButtonItemInactive {
	background-position: 0px -60px;
}
.polygonButtonItemActive {
	background-position: 0px -90px;
}
.olControlNavigationItemInactive {
	background-position: 0px -120px;
}
.olControlNavigationItemActive {
	background-position: 0px -150px;
}
.ModifyLineButtonItemInactive {
	background-position: 0px -180px;
}
.ModifyLineButtonItemActive {
	background-position: 0px -210px;
}
.ModifyPolygonButtonItemInactive {
	background-position: 0px -240px;
}
.ModifyPolygonButtonItemActive {
	background-position: 0px -270px;
}
.ModifyAdministrativeButtonItemInactive {
	background-position: 0px -300px;
}
.ModifyAdministrativeButtonItemActive {
	background-position: 0px -330px;
}
</style>
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include("../php/carte-modifier-zone-institut.php"); ?>
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
<!-- Subhead
================================================== -->
<div class="container" id="overview"> 
  
  <!-- Page CONTENT
    ================================================== -->
  <section class="corps-page">
  <?php include('../php/menu-haut-conseil.php'); ?>
  
  <!-- Liste des Communiqués
        ================================================== -->
  <div id="titre_institut" class="titre-bleu anchor"> <img src="../assets/img/IconesBDD/Bleu/100/ocgc_bleu.png">
    <h1>G&eacute;rer l'<?php echo $row_institut['ch_ins_nom']; ?></h1>
  </div>
  <!-- formulaire de modification instituts
     ================================================== -->
  <form class="pull-right" action="insitut_modifier.php" method="post">
    <input name="institut_id" type="hidden" value="<?php echo $row_institut['ch_ins_ID']; ?>">
    <button class="btn btn-primary" type="submit" title="modifier les informations sur l'institut"><i class="icon-edit icon-white"></i> Modifier description</button>
  </form>

  <div class="well">
      <?php renderElement('errormsgs'); ?>
  </div>

  <div class="clearfix"></div>
    <!-- Carte
     ================================================== -->
  <div class="row-fluid">
  <div class="span12">
      <div class="titre-gris" id="mes-communiques" class="anchor">
      <h3>Carte</h3>
    </div>
    <div class="span9" style="margin:0px; padding:0px;">
        <div id="map"></div>
      </div>
      <div class="" id="info">
        <h1>Modifier la carte</h1>
  <p>Cliquez sur les outils &agrave; droite de la carte pour ajouter ou modifier des &eacute;l&eacute;ments</p>
      </div>
      </div>
  <!-- liste communique de l'institut
     ================================================== -->
  <div class="row-fluid">
    <div class="span12">
      <div class="titre-gris" id="mes-communiques" class="anchor">
      <h3>Communiqu&eacute;s</h3>
    </div>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      Les communiqu&eacute;s post&eacute;s &agrave; partir de cette page seront consid&eacute;r&eacute;s comme des annonces officielles &eacute;manant de cette institution. Ils seront publiés sur la page de l'institut et dans la partie événement du site. Utilisez les communiqu&eacute;s pour animer le site</div>
    <?php 
$com_cat = "institut";
$userID = $_SESSION['user_ID'];
$com_element_id = 2;
include('../php/communiques-back.php'); ?>
  </div>
  
  </div>
</section>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<?php
mysql_free_result($institut);
?>