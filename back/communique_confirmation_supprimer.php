<?php

require_once('../Connections/maconnexion.php');

//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers Haut Conseil
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
}


$colname_ch_communique_confimation_suppression = "-1";
if (isset($_POST['communique_ID'])) {
  $colname_ch_communique_confimation_suppression = $_POST['communique_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_ch_communique_confimation_suppression = sprintf("SELECT ch_com_ID, ch_com_titre, ch_com_categorie, ch_com_element_id FROM communiques WHERE ch_com_ID = %s", GetSQLValueString($colname_ch_communique_confimation_suppression, "int"));
$ch_communique_confimation_suppression = mysql_query($query_ch_communique_confimation_suppression, $maconnexion) or die(mysql_error());
$row_ch_communique_confimation_suppression = mysql_fetch_assoc($ch_communique_confimation_suppression);
$totalRows_ch_communique_confimation_suppression = mysql_num_rows($ch_communique_confimation_suppression);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Supprimer un communiqu&eacute;</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
<?php if (($row_ch_communique_confimation_suppression['ch_com_categorie'] == "pays") || ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "ville") AND ($_SESSION['fond_ecran']) ) {
?> .jumbotron {
 background-image: url("<?php echo $_SESSION['fond_ecran'] ?>");
}
<?php
} elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "institut") { ?> 
.jumbotron {
 background-image: url("../assets/img/fond_haut-conseil.jpg");
}
<?php
} else { ?> 
.jumbotron {
 background-image: url("../assets/img/imagesdefaut/Imgheader.jpg");
}
<?php }?>
</style>
</head>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>Attention&nbsp;!</h1>
    <?php if (($row_ch_communique_confimation_suppression['ch_com_categorie'] == "pays") || ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "ville") || ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "institut")) { ?>
    <p>Souhaitez-vous r&eacute;ellement supprimer le communiqu&eacute; intitul&eacute;&nbsp;: "<?php echo $row_ch_communique_confimation_suppression['ch_com_titre']; ?>"&nbsp;?</p>
    <?php  } else {?>
    <p>Souhaitez-vous r&eacute;ellement supprimer ce commentaire&nbsp;?</p>
    <?php  } ?>
    <p>Cette action sera irr&eacute;versible</p>
    <form action="communique_supprimer.php" method="post" class="form-button-inline">
      <input name="communique-ID" type="hidden" value="<?php echo $row_ch_communique_confimation_suppression['ch_com_ID']; ?>">
      <button type="submit" class="btn btn-large btn-danger" title="supprimer ce communiqu&eacute;"><i class="icon-trash icon-white"></i> Supprimer</button>
    </form>
    <?php if ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "pays") { ?>
    <form action="page_pays_back.php" method="post" class="form-button-inline">
      <input name="paysID" type="hidden" value="<?php echo $row_ch_communique_confimation_suppression['ch_com_element_id']; ?>">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "ville") { ?>
    <form action="ville_modifier.php" method="post" class="form-button-inline">
      <input name="ville-ID" type="hidden" value="<?php echo $row_ch_communique_confimation_suppression['ch_com_element_id']; ?>">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "institut") { ?>
    <form action="Haut-Conseil.php" method="post" class="form-button-inline">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "com_pays") { ?>
    <form action="../page-pays.php?ch_pay_id=<?php echo $row_ch_communique_confimation_suppression['ch_com_element_id']; ?>" method="post" class="form-button-inline">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "com_ville") { ?>
    <form action="../page-ville.php?ch_ville_id=<?php echo $row_ch_communique_confimation_suppression['ch_com_element_id']; ?>" method="post" class="form-button-inline">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } elseif ($row_ch_communique_confimation_suppression['ch_com_categorie'] == "com_communique") { ?>
    <form action="../page-communique.php?com_id=<?php echo $row_ch_communique_confimation_suppression['ch_com_element_id']; ?>" method="post" class="form-button-inline">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } else { ?>
    <form action="../index.php" method="post" class="form-button-inline">
      <button type="submit" class="btn btn-large btn-success" value="Annuler">Annuler</button>
    </form>
    <?php } ?>
  </div>
</header>
<div class="container corps-page"> </div>

<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
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
<?php
mysql_free_result($ch_communique_confimation_suppression);
?>
