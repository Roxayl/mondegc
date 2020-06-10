<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers Haut Conseil
header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . legacyPage('connexion'));
exit();
}

$colname_ch_his_confimation_suppression = "-1";
if (isset($_POST['ch_his_id'])) {
  $colname_ch_his_confimation_suppression = $_POST['ch_his_id'];
}

$query_ch_his_confimation_suppression = sprintf("SELECT ch_his_id, ch_his_paysID, ch_his_nom, ch_his_lien_img1 FROM histoire WHERE ch_his_id = %s", GetSQLValueString($colname_ch_his_confimation_suppression, "int"));
$ch_his_confimation_suppression = mysql_query($query_ch_his_confimation_suppression, $maconnexion) or die(mysql_error());
$row_ch_his_confimation_suppression = mysql_fetch_assoc($ch_his_confimation_suppression);
$totalRows_ch_his_confimation_suppression = mysql_num_rows($ch_his_confimation_suppression);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer un fait historique</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
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
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('<?php echo $row_ch_pat_confimation_suppression['ch_his_lien_img1']; ?>');
	background-position: center;
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
    <p>Souhaitez-vous r&eacute;ellement supprimer <?php echo $row_ch_his_confimation_suppression['ch_his_nom']; ?>&nbsp;?</p>
    <p>Cette action sera irr&eacute;versible</p>
    <form action="<?= DEF_URI_PATH ?>back/fait_historique_supprimer.php" method="post" class="form-button-inline">
      <input name="ch_his_id" type="hidden" value="<?php echo $row_ch_his_confimation_suppression['ch_his_id']; ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer le monument"><i class="icon-trash icon-white"></i> Supprimer</button>
    </form>
    <form action="<?= DEF_URI_PATH ?>back/page_pays_back.php#faits-historiques" method="post" class="form-button-inline">
      <input name="paysID" type="hidden" value="<?php echo $row_ch_his_confimation_suppression['ch_his_paysID']; ?>">
      <button type="submit" class="btn btn-large btn-success" title="retour &agrave; la page de modification du pays">Annuler</button>
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
mysql_free_result($ch_his_confimation_suppression);
?>
