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

$voteResults = $formProposal->getVote()->generateDiagramData();

if($formProposal->isWithinDebatePeriod()) {
    $message_alert = "warning";
    $debate_message = "L'Assemblée Générale siège en session plénière. La procédure de vote a commencé.";
} else {
    $message_alert = "info";
    $debate_message = "La procédure de vote n'a pas commencé ou est terminée.";
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
.jumbotron {
    background-image: url('../assets/img/bannieres-instituts/Geo.png');
}
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

<!-- PARLIAMENT -->
<script src="../assets/js/d3.v4.min.js"></script>
<script src="../assets/js/d3-parliament.js"></script>

<!-- STYLE -->
<style media="screen">
    svg {
        width: 500px;
        height: 270px;
    }
    svg .seat {
        cursor: pointer;
        transition: all 800ms;
    }

    /* European parliament colors */
    svg .seat.gue-ngl { fill: #990000 }
    <?php
    foreach($voteResults['css'] as $thisCss): ?>
        <?= key($thisCss) ?> { fill: <?= $thisCss[key($thisCss)] ?> }
    <?php endforeach; ?>
    </style>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut jumbotron-small subhead anchor" id="info-institut" >
  <div class="container">
    <h2>Organisation des Cités Gécéennes</h2>
    <h1>Assemblée Générale</h1>
  </div>
</header>

<div class="container corps-page">
  <div class="row-fluid">
  <!-- Debut formulaire Page Pays
        ================================================== -->
  <section>

  <?php if(!$_error): ?>

    <ul class="breadcrumb">
        <li><a href="../OCGC.php">OCGC</a> <span class="divider">/</span></li>
        <li><a href="../assemblee.php">Assemblée Générale</a> <span class="divider">/</span></li>
        <li class="active">
            <?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
            <?= $formProposal->getProposalId(); ?> :
            <?= $formProposal->question ?>
        </li>
    </ul>
    <div class="well" style="padding-top: 0; padding-bottom: 0;">
    <h1><small><?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
               <?= $formProposal->getProposalId(); ?></small><br />
        <?= $formProposal->question ?></h1>
    </div>

    <?php renderElement('errormsgs'); ?>

    <!-- ZONE DE VOTE -->
    <div id="info-generales" class="titre-bleu">
        <h1>Vote</h1>
    </div>

    <div class="well">
        <div class="alert alert-block <?= $message_alert ?>"><?= $debate_message ?></div>

        <div class="row-fluid">
            <div class="span6">
                <svg id="parliament"></svg>
            </div>

            <div class="span6">
                <h3><?= $formProposal->question ?></h3>
                <ul>
                <?php foreach($formProposal->getResponses() as $thisResponse): ?>
                    <li><?= $thisResponse ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- ZONE DE DÉBATS -->
    <div id="info-generales" class="titre-bleu">
        <h1>Débats</h1>
    </div>


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

<script type="text/javascript">

    var parliament = d3.parliament().width(500).height(270).innerRadiusCoef(0.39);
    parliament.enter.fromCenter(true).smallToBig(true);
    parliament.exit.toCenter(true).bigToSmall(true);
    parliament.on("click", function(e) { console.log(e); });

    var setData = function(d) {
        d3.select("#parliament").datum(d).call(parliament);
    };

    setData(<?= json_encode($voteResults['d3DataSource']) ?>);

</script>

</body>
</html>