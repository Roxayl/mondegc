<?php

require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

$_error = false;

if (!($_SESSION['statut']) or $_SESSION['statut'] < 10) {
    // Redirection vers page connexion
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ../connexion.php');
    exit();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
$userPaysAllowedToVote = $thisUser->getCountries(\GenCity\Monde\User::getUserPermission('Dirigeant'), true);
if($_error) {
    getErrorMessage('ban_error', "Vous ne pouvez pas créer de nouvelle proposition car vous n'êtes le dirigeant d'un pays.");
}


?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="iso-8859-1">
<title>Haut-Conseil - Nouveau pays</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<div class="container corps-page">
  <?php include('../php/menu-haut-conseil.php'); ?>
  <div class="row-fluid">
  <!-- Debut formulaire Page Pays
        ================================================== -->
  <section>
    <div id="info-generales" class="titre-bleu anchor">
      <h1>Nouvelle proposition à l'Assemblée Générale</h1>
    </div>

    <ul class="breadcrumb">
        <li><a href="../OCGC.php">OCGC</a> <span class="divider">/</span></li>
        <li><a href="../assemblee.php">Assemblée Générale</a> <span class="divider">/</span></li>
        <li class="active">Nouvelle proposition à l'Assemblée Générale</li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <?php if(!$_error): ?>

    <form method="POST">
    <div class="well">

        <select name="ocgc_proposal_create[ID_pays]" id="ocgc_proposal_create[ID_pays]">
            <?php foreach($userPaysAllowedToVote as $thisPays): ?>
                <option value="<?= $thisPays->ch_pay_id ?>"><?= $thisPays->ch_pay_nom ?></option>
            <?php endforeach; ?>
        </select>

        <div id="sprytextfield1" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[question]">Question <a href="#" rel="clickover" title="Objet de la proposition" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[question]" type="text" id="ocgc_proposal_create[question]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield2" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[reponse_1]">Réponse 1 <a href="#" rel="clickover" title="Réponse 1" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[reponse_1]" type="text" id="ocgc_proposal_create[reponse_1]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[reponse_2]">Réponse 2 <a href="#" rel="clickover" title="Réponse 2" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[reponse_2]" type="text" id="ocgc_proposal_create[reponse_2]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield4" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[reponse_3]">Réponse 3 <a href="#" rel="clickover" title="Réponse 3" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[reponse_3]" type="text" id="ocgc_proposal_create[reponse_3]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield5" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[reponse_4]">Réponse 4 <a href="#" rel="clickover" title="Réponse 4" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[reponse_4]" type="text" id="ocgc_proposal_create[reponse_4]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield6" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[reponse_5]">Réponse 5 <a href="#" rel="clickover" title="Réponse 5" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[reponse_5]" type="text" id="ocgc_proposal_create[reponse_5]" value="" maxlength="255">
                <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
            </div>
        </div>

        <div id="sprytextfield7" class="control-group">
            <label class="control-label" for="ocgc_proposal_create[debate_start]">Date de vote <a href="#" rel="clickover" title="Date du vote" data-content="Proposez une date à laquelle sera soumise la proposition durant la séance plénière."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
                <input class="input-xlarge" name="ocgc_proposal_create[debate_start]" type="text" id="ocgc_proposal_create[debate_start]" value="" maxlength="255">
            </div>
        </div>

    </div>
    </form>

  <?php else: // end if($_error) ?>

  <?php endif; ?>

    <!-- FIN formulaire Page Pays
        ================================================== -->
  </section>
</div>
</div>
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
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
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {isRequired:true, minChars:2, maxChars:250, validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {isRequired:true, minChars:2, maxChars:250, validateOn:["change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {isRequired:true, minChars:2, maxChars:250, validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {isRequired:false, minChars:2, maxChars:250, validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {isRequired:false, minChars:2, maxChars:250, validateOn:["change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "url", {isRequired:false, minChars:2, maxChars:250, validateOn:["change"]});
</script>
</body>
</html>