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
    getErrorMessage('ban_error', "Cette proposition n'existe pas.");
}

$formProposal = new \GenCity\Proposal\Proposal($_GET['id']);

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
  <div class="row-fluid">
  <!-- Debut formulaire Page Pays
        ================================================== -->
  <section>

  <?php if(!$_error): ?>

    <div id="info-generales" class="titre-bleu anchor">
        <h1><small><?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
                   <?= $formProposal->getProposalId(); ?></small><br />
            <?= $formProposal->question ?></h1>
    </div>

    <ul class="breadcrumb">
        <li><a href="../OCGC.php">OCGC</a> <span class="divider">/</span></li>
        <li><a href="../assemblee.php">Assemblée Générale</a> <span class="divider">/</span></li>
        <li class="active">
            <?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
            <?= $formProposal->getProposalId(); ?> :
            <?= $formProposal->question ?>
        </li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <!-- ZONE DE VOTE -->
    <?php

    if($formProposal->isWithinDebatePeriod()): ?>

        <div class="well">
            <p>Les débats ont commencé !</p>
        </div>

    <?php endif; /* end isWithinDebatePeriod() */ ?>

  <?php else: // end if($_error) ?>

    <?php renderElement('errormsgs'); ?>

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

</body>
</html>