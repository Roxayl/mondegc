<?php

//deconnexion
include('php/log.php');


$query_HautConseil = "SELECT ch_use_login, ch_use_statut FROM users WHERE ch_use_statut >= 20 ORDER BY ch_use_login ASC";
$HautConseil = mysql_query($query_HautConseil, $maconnexion) or die(mysql_error());
$row_HautConseil = mysql_fetch_assoc($HautConseil);
$totalRows_HautConseil = mysql_num_rows($HautConseil);

$pageConseilOCGC = new \GenCity\Monde\Page('conseil_ocgc_desc');

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Conseil de l'OCGC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
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
	background-image: url('assets/img/fond_haut-conseil.jpg');
	background-position:center;
}
</style>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<div id="introheader" class="jumbotron">
  <div class="container">
    <div class="pull-right span5 align-left">
      <h4>Vous n'avez pas l'autorisation d'acc&eacute;der &agrave; cette partie</h4>
      <hr>
    </div>
    <div class="span5 align-left">
    <?= $pageConseilOCGC->content() ?>
     <h5>Les membres du Conseil de l'OCGC sont&nbsp;:</h5>
	 <?php do { ?>
     <?php if ($row_HautConseil['ch_use_statut']==30) { ?>
     <span><em style="color: #FF4F4F"><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
     <?php } elseif ($row_HautConseil['ch_use_statut']==20) { ?>
     <span><em style="color: #FF9900"><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
		<?php } else { ?>
     <span><em><?php echo $row_HautConseil['ch_use_login'] ?></em> -</span>
        <?php } ?>
		<?php } while ($row_HautConseil = mysql_fetch_assoc($HautConseil)); ?>
    </div>
</div>
</div>
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</html>
</body>
