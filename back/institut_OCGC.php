<?php
session_start();

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
$institut_id = 1;
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

$_SESSION['last_work'] = "institut_OCGC.php";
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Gérer l'<?php echo $row_institut['ch_ins_nom']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../assets/css/colorpicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
  <div class="clearfix"></div>
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
$com_element_id = 1;
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