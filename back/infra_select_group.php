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

if($_GET['ville_id']) {

    $thisVille = new \GenCity\Monde\Ville($_GET['ville_id']);
    $thisPays = new \GenCity\Monde\Pays($thisVille->get('ch_vil_paysID'));

} else {

    $thisVille = null;
    $thisPays = new \GenCity\Monde\Pays($_GET['pays_id']);

}

/** @var \GenCity\Monde\Ville[] $listVille */
$listVille = \GenCity\Monde\Ville::getListFromPays($thisPays);
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
      <?php if(!is_null($thisVille)): ?>
        <small><img src="<?= __s((empty($thisVille->get('ch_vil_armoiries')) ? '../assets/img/imagesdefaut/blason.jpg' : $thisVille->get('ch_vil_armoiries')) ) ?>" style="height: 24px; width: 24px;"> Ville de <?= __s($thisVille->get('ch_vil_nom')) ?></small>
      <?php else: ?>
          <small>Pays : <?= __s($thisPays->get('ch_pay_nom')) ?></small>
      <?php endif; ?>
      </h1>
    </div>

    <ul class="breadcrumb">
      <li><a href="page_pays_back.php?paysID=<?= $thisPays->get('ch_pay_id') ?>&userID=<?= $thisUser->get('ch_use_id') ?>"
          >Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a>
          <span class="divider">/</span></li>
      <?php if(!is_null($thisVille)): ?>
        <li><a href="ville_modifier.php?ville-ID=<?= $thisVille->get('ch_vil_ID') ?>"
            >Gestion de la ville : <?= __s($thisVille->get('ch_vil_nom')) ?></a>
            <span class="divider">/</span></li>
      <?php endif; ?>
      <li class="active">Ajouter une infrastructure</li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <div class="well" style="margin-top: -20px;">
        <div class="alert alert-tips">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Les infrastructures sont des éléments de gameplay du Monde GC qui permettent de matérialiser vos créations en leur attribuant des points de 8 catégories, les fameuses ressources Tempérance.
            <a class="guide-link" href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures#Ajouter_une_infrastructure">Besoin d'aide ? GO!</a>
        </div>
    </div>

    <div class="alert alert-tips">
        <label for="select_ville_ID"><h4><i class="icon icon-home"></i> Publier l'infrastructure dans la ville : </h4></label>
        <select id="select_ville_ID" name="select_ville_ID">
        <?php foreach($listVille as $ville): ?>
            <option value="<?= $ville->get('ch_vil_ID') ?>"
                <?= $thisVille !== null && $thisVille->get('ch_vil_ID') === $ville->get('ch_vil_ID')
                        ? 'selected' : '' ?>
            ><?= __s($ville->get('ch_vil_nom')) ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <div class="well" id="thumbnail-infra-container" style="display: none;">
      <ul class="thumbnails">
      <?php /** @var \GenCity\Monde\Temperance\InfraGroup $row */
      foreach($infraGroupList as $key => $row): ?>

          <li class="span4">
            <div class="thumbnail">
              <img src="<?= __s($row->get('url_image')) ?>" data-src="holder.js/300x200" alt="">
              <h3><?= __s($row->get('nom_groupe')) ?></h3>
              <form action="infrastructure_ajouter.php" method="GET">
                <input name="ville_ID" type="hidden" class="infra_ville_id_form"
                       value="<?= is_null($thisVille) ? 0 : $thisVille->get('ch_vil_ID') ?>">
                <input name="infra_group_id" type="hidden"
                       value="<?= $row->get('id') ?>">
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

<script>
    (function($, document, undefined) {
        var autoselectVille = function() {
            var value = $('#select_ville_ID option:selected').val();
            $('.infra_ville_id_form').val(value);
        };

        autoselectVille();
        $('#select_ville_ID').on('change', autoselectVille);
        $('#thumbnail-infra-container').show();
    }($, document));
</script>
</body>
</html>