<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if (!($_SESSION['statut'])) {
    // Redirection vers Haut Conseil
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ../connexion.php');
    exit();
}

$infraGroupList = \GenCity\Monde\Temperance\InfraGroup::getAll();

$thisVille = new \GenCity\Monde\Ville($_GET['ville_id']);

$thisPays = new \GenCity\Monde\Pays($thisVille->get('ch_vil_paysID'));

/** @var \GenCity\Monde\User $thisUser */
$thisUser = $_SESSION['userObject'];

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Ajouter une infrastructure</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>

<div class="container corps-page">

  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <!-- Page CONTENT
    ================================================== -->
    <!-- Moderation
     ================================================== -->
    <div id="infrastructure" class="titre-vert anchor">
      <h1>Ajouter une infrastructure<br>
        <small><img src="<?= __s((empty($thisVille->get('ch_vil_armoiries')) ? '../assets/img/imagesdefaut/blason.jpg' : $thisVille->get('ch_vil_armoiries')) ) ?>" style="height: 24px; width: 24px;"> Ville de <?= __s($thisVille->get('ch_vil_nom')) ?></small></h1>
    </div>

    <ul class="breadcrumb">
      <li><a href="page_pays_back.php?paysID=<?= $thisVille->get('ch_vil_paysID') ?>&userID=<?= $thisUser->get('ch_use_id') ?>"
          >Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a> <span class="divider">/</span></li>
      <li><a href="ville_modifier.php?ville-ID=<?= $thisVille->get('ch_vil_ID') ?>"
          >Gestion de la ville : <?= __s($thisVille->get('ch_vil_nom')) ?></a> <span class="divider">/</span></li>
      <li class="active">Ajouter une infrastructure</li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">×</button>
      Ce formulaire vous permet d'ajouter une infrastructure &agrave; votre ville. Les infrastructures vous permettent de  construire l'&eacute;conomie de votre pays. L'existence de votre infrastructure doit &ecirc;tre prouv&eacute;e par une image. Avant d'&ecirc;tre comptabilis&eacute;e, votre infrastructure sera mod&eacute;r&eacute;e par les juges du projet <a href="../economie.php" title="Lien vers l'Institut Economique Gécéen">Tempérance</a>
    </div>

    <div class="well">
      <ul class="thumbnails">
      <?php /** @var \GenCity\Monde\Temperance\InfraGroup $row */
      foreach($infraGroupList as $key => $row): ?>

          <li class="span4">
            <div class="thumbnail">
              <img src="<?= __s($row->get('url_image')) ?>" data-src="holder.js/300x200" alt="">
              <h3><?= __s($row->get('nom_groupe')) ?></h3>
              <form action="infrastructure_ajouter.php" method="GET">
                <input name="ville_ID" type="hidden" value="<?= $thisVille->get('ch_vil_ID') ?>">
                <input name="infra_group_id" type="hidden" value="<?= $row->get('id') ?>">
                <button class="btn btn-primary btn-margin-left" type="submit">Choisir...</button>
              </form>
            </div>
          </li>

          <?php if(($key + 1) % 3 === 0): ?>
        </ul>
        <ul class="thumbnails">
          <?php endif; ?>

      <?php endforeach; ?>

      </ul>
    </div>

  </div>

</div>


<!-- END CONTENT
    ================================================== -->
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
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
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
</body>
</html>