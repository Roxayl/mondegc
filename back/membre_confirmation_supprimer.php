<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=30))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

$colname_membre = "-1";
if (isset($_POST['ch_use_id'])) {
  $colname_membre = $_POST['ch_use_id'];
}

$query_membre = sprintf("SELECT ch_use_id, ch_use_login, ch_use_lien_imgpersonnage FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_membre, "int"));
$membre = mysql_query($query_membre, $maconnexion) or die(mysql_error());
$row_membre = mysql_fetch_assoc($membre);
$totalRows_membre = mysql_num_rows($membre);

$currentPage = $_SERVER["PHP_SELF"];
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Supprimer une ville</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('../assets/img/fond_haut-conseil.jpg');
}
</style>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbarback.php'); ?>

<!-- Subhead
================================================== -->
<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>Attention&nbsp;!</h1>
    <img src="<?php echo $row_membre['ch_use_lien_imgpersonnage']; ?>" width="100px" class="pull-right">
    <p>Souhaitez-vous r&eacute;ellement supprimer le profil du membre <?php echo $row_membre['ch_use_login']; ?>&nbsp;?</p>
    <p>Cette action sera irr&eacute;versible</p>
    <form action="membre_supprimer.php" method="post" class="form-button-inline">
      <input name="ch_use_id" type="hidden" value="<?php echo $row_membre['ch_use_id']; ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer ce membre"><i class="icon-trash icon-white"></i> Supprimer</button>
    </form>
    <form action="liste-membres.php" method="post" class="form-button-inline">
      <input name="userID" type="hidden" value="<?php echo $row_membre['ch_use_id']; ?>">
      <button type="submit" class="btn btn-large btn-success" title="retour &agrave; la lsite des membres">Annuler</button>
    </form>
  </div>
</header>
<div class="container corps-page"> </div>
<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
 <script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<?php
mysql_free_result($membre);
?>