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
        height: 200px;
    }
    svg .seat {
        cursor: pointer;
        transition: all 800ms;
    }

    /* European parliament colors */
    svg .seat.gue-ngl { fill: #990000 }
    svg .seat.sd { fill: #F0001C }
    svg .seat.greens-efa { fill: #32CD32 }
    svg .seat.alde { fill: #FFD700 }
    svg .seat.epp { fill: #3399FF }
    svg .seat.ecr { fill: #0054A5 }
    svg .seat.efdd { fill: #40E0D0 }
    svg .seat.enf { fill: #000000 }

    /* French parliament colors */
    svg .seat.com { fill: #990000; }
    svg .seat.soc { fill: #D58490; }
    svg .seat.eelv { fill: #32CD32; }
    svg .seat.edsr { fill: #BF80FF; }
    svg .seat.uc { fill: #B2C6FF; }
    svg .seat.lr { fill: #4C6099; }

    /* common */
    svg .seat.vacant { fill: #FFFFFF }
    svg .seat.no-party { fill: #909090; }
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

    var data = [
        {
            "id": "gue-ngl",
            "legend": "GUE-NGL",
            "name": "European United Left–Nordic Green Left",
            "seats": 7
        },
        {
            "id": "sd",
            "legend": "S&D",
            "name": "Progressive Alliance of Socialists and Democrats",
            "seats": 22
        },
        {
            "id": "greens-efa",
            "legend": "Greens-EFA",
            "name": "The Greens–European Free Alliance",
            "seats": 16
        },
        {
            "id": "alde",
            "legend": "ALDE",
            "name": "Alliance of Liberals and Democrats for Europe Group",
            "seats": 15
        },
        {
            "id": "epp",
            "legend": "EPP",
            "name": "European People's Party Group",
            "seats": 32
        },
        {
            "id": "ecr",
            "legend": "ECR",
            "name": "European Conservatives and Reformists",
            "seats": 6
        },
        {
            "id": "efdd",
            "legend": "EFDD",
            "name": "Europe of Freedom and Direct Democracy",
            "seats": 8
        },
        {
            "id": "enf",
            "legend": "ENF",
            "name": "Europe of Nations and Freedom",
            "seats": 10
        },
        {
            "id": "no-party",
            "legend": "Non-Inscrits",
            "name": "Non-Inscrits",
            "seats": 7
        }
    ];

    var parliament = d3.parliament().width(500).height(200).innerRadiusCoef(0.4);
    parliament.enter.fromCenter(true).smallToBig(true);
    parliament.exit.toCenter(true).bigToSmall(true);
    parliament.on("click", function(e) { console.log(e); });

    var setData = function(d) {
        d3.select("#parliament").datum(d).call(parliament);
    };

    setData(data);
</script>

</body>
</html>