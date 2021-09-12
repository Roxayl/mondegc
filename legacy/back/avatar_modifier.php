<?php

use GenCity\Monde\Pays;

 
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');
if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$pays_ID = 0;
if(isset($_GET['paysID'])) {
    $pays_ID = (int)$_GET['paysID'];
}

$thisPays = new Pays($pays_ID);
$character = $thisPays->getCharacters();
if(empty($character)) {
    getErrorMessage('error', "Ce personnage ou pays n'existe pas.");
    exit;
}
$character = $character[0];
if($thisPays->getUserPermission() < Pays::$permissions['codirigeant']) {
    getErrorMessage('error', "Vous ne pouvez pas modifier l'avatar de ce membre.");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Modifier l'avatar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
</style>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbar.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">
  <div class="row-fluid">
 	<div id="info-generales" class="titre-vert anchor">
        <h1>Modifier l'avatar du personnage</h1>
    </div>

      <ul class="breadcrumb">
          <li><a href="page_pays_back.php?paysID=<?= $thisPays->get('ch_pay_id') ?>&userID=<?= $_SESSION['userObject']->get('ch_use_id') ?>">Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a> <span class="divider">/</span></li>
          <li class="active">Modifier l'avatar du personnage</li>
      </ul>
    <p>&nbsp;</p>
    <section>
      <?php include(DEF_ROOTPATH . 'php/upload.php');
if (isset($uploadconfirm)) {
  $updateSQL = sprintf("UPDATE personnage SET lien_img=%s WHERE entity='pays' AND entity_id=%s",
                       GetSQLValueString($link, "text"),
                       GetSQLValueString($pays_ID, "int"));

  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  getErrorMessage('success', "L'avatar a été modifié avec succès !");

  getErrorMessage('success', "Lien img : {$link}");
}
?>
      
      <!-- Debut formulaire -->
      <div class="well well-large">

        <?php renderElement('errormsgs'); ?>

        <!-- Image de contr�le drapeau --> 
        <img src="<?= e($character['lien_img']) ?>" alt="avatar <?= e($character['nom_personnage']) ?>" title="drapeau <?= e($character['nom_personnage']) ?>>">
        <p>&nbsp;</p>
        <p><?= e($character['predicat']) ?> <strong><?= e($character['prenom_personnage']) ?> <?= e($character['nom_personnage']) ?></strong>
        <p>&nbsp;</p>
        <form action="<?= DEF_URI_PATH ?>back/avatar_modifier.php?paysID=<?= $thisPays->ch_pay_id ?>" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" data-filename-placement="inside" title="Choisir une nouvelle image">
          <input name="paysID" id="paysID" type="hidden" value="<?= $thisPays->ch_pay_id ?>">
          <input name="maxwidth" id="maxwidth" type="hidden" value="250">
          <input name="ThumbMaxwidth" id="ThumbMaxwidth" type="hidden" value="100">
          <input name="SmallThumbMaxwidth" id="SmallThumbMaxwidth" type="hidden" value="50">
          <input type="submit" name="submit" value="Envoyer" class="btn btn-primary"/>
          <a class="btn btn-primary" href="page_pays_back.php?paysID=<?= $thisPays->ch_pay_id ?>#personnage"
             title="Retour &agrave; la page de gestion de votre profil">Retour</a>
        </form>
      </div>
    </section>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>

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
<script src="../assets/js/bootstrap-filestyle.js"></script>
<script type="text/javascript">
    $('input[type=file]').bootstrapFileInput();
</script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
</body>
</html>
