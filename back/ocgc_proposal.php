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
if($_error) {
    getErrorMessage('ban_error', "Cette proposition n'existe pas.");
}

$formProposal = new \GenCity\Proposal\Proposal($_GET['id']);

$voteResults = $formProposal->getVote()->generateDiagramData();
$voteChartResults = $formProposal->getVote()->generateChartResults();

$voteList = new \GenCity\Proposal\VoteList($formProposal);
$userVotes = $voteList->getUserVotes($thisUser);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['voteCast'])) {

        $postVoteModel = new \GenCity\Proposal\Vote($_POST['voteCast']['id']);

        $postVoteModel->set('reponse_choisie', $_POST['voteCast']['reponse_choisie']);
        $voteValidate = $postVoteModel->validate($voteList, $formProposal);

        if(empty($voteValidate)) {
            $postVoteModel->castVote();
            getErrorMessage('success', "Vous avez voté !");
            header('Location: ocgc_proposal.php?id=' . $formProposal->get('id'));
            exit();
        }

        else {
            foreach($voteValidate as $validation) {
                getErrorMessage('error', $validation['errorMessage']);
            }
        }

    }

}

if($formProposal->isWithinDebatePeriod()) {
    $message_alert = "warning";
    $debate_message = "L'Assemblée Générale siège en session plénière. La procédure de vote a commencé.";
} else {
    $message_alert = "info";
    $debate_message = "La procédure de vote n'a pas commencé ou est terminée.";
}

use GenCity\Monde\Pays;use GenCity\Proposal\Vote;

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

    <div class="well well-dark">

    <!-- ZONE DE VOTE -->
    <div id="info-generales" class="titre-bleu titre-fond-blanc" style="margin: -20px -19px 0;">
        <h1>Vote</h1>
    </div>
    <p><?= $debate_message ?></p>

    <div class="row-fluid">
        <div class="span6" id="parliament-data-container">
            <svg id="parliament"></svg>
            <div id="parliament-chart-container">
                <canvas id="parliament-chart" width="110" height="60"></canvas>
            </div>
        </div>

        <div class="span6">
        <h3><?= $formProposal->question ?></h3>

        <?php

        /** @var Vote $thisVote */
        foreach($userVotes as $thisVote):

            $thisPays = new Pays($thisVote->get('ID_pays'));
            ?>

            <form method="POST" action="ocgc_proposal.php?id=<?= $formProposal->get('id') ?>">
                <input type="hidden" name="voteCast[ID_proposal]" value="<?= $formProposal->get('id') ?>">

                <input type="hidden" name="voteCast[id]" value="<?= $thisVote->get('id') ?>">

                <h4><img class="img-menu-drapeau" src="<?= $thisPays->get('ch_pay_lien_imgdrapeau') ?>">
                    <?= $thisPays->get('ch_pay_nom') ?></h4>

                <!-- Réponses -->
                <ul class="proposal-responses">
                <?php foreach($formProposal->getResponses() as $key => $thisResponse): ?>
                    <?php $voteArray = array(
                        'reponse_choisie' => $key
                    );
                    $thisColor = $formProposal->getVote()->getColorFromVote(
                            new \GenCity\Proposal\Vote($voteArray)); ?>
                    <li style="color: <?= $thisColor ?>; border-color: <?= $thisColor ?>;"
                        data-default-color="<?= $thisColor ?>">
                        <label><input type="checkbox" value="<?= $key ?>" name="voteCast[reponse_choisie]"
                              <?= ($key === (int)$thisVote->get('reponse_choisie')
                                   && $thisVote->get('reponse_choisie') !== null ? 'checked selected' : '') ?>>
                            <?= $thisResponse ?></label>
                    </li>
                <?php endforeach; ?>
                </ul>
            </form>

        <?php endforeach; ?>

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
<script src="../assets/js/Chart.2.7.3.bundle.js"></script>
<script type="text/javascript">
$(function() {
    $('[rel="clickover"]').clickover();})
</script>

<script type="text/javascript">
(function(window, document, $, d3, Chart, undefined) {

    /** Chart.js **/

    var ctx = document.getElementById("parliament-chart");
    setTimeout(function() {
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($voteChartResults['labels']) ?>,
                datasets: [{
                    label: '# of Votes',
                    data: <?= json_encode($voteChartResults['data']) ?>,
                    backgroundColor: <?= json_encode($voteChartResults['bgColor']) ?>,
                    borderWidth: 0
                }]
            },
            options: {
                rotation: 0.955 * Math.PI,
                circumference: 1.09 * Math.PI,
                legend: {
                    display: false
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }, 1000);


    /** Diagram init **/

    var parliament = d3.parliament().width(500).height(270).innerRadiusCoef(0.39);
    parliament.enter.fromCenter(true).smallToBig(true);
    parliament.exit.toCenter(true).bigToSmall(true);
    parliament.on("click", function(e) { console.log(e); });

    var diagramData = <?= json_encode($voteResults['d3DataSource']) ?>;

    var setData = function(d) {
        d3.select("#parliament").datum(d).call(parliament);
    };

    setData(diagramData);


    /** Editing **/

    var getSpecificSvgId = function(vote_id) {

        for(var i = 0; i < diagramData['d3DataSource'].length; i++) {
            if(diagramData['d3DataSource'][i]['id'] === vote_id) {
                return;
            }
        }

    };

    var manageColors = function($thisInput) {

        var selectedColor = '#83808A';

        $thisInput.closest('ul').find('li').each(function() {

            var el = $(this);

            if(el.find('input[name="voteCast[reponse_choisie]"]').prop('checked')) {
                el.css({
                    "border-color": el.attr('data-default-color'),
                    "background-color": el.attr('data-default-color'),
                    "color": "#ffffff"
                });
                selectedColor = el.attr('data-default-color');
            } else {
                el.css({
                    "border-color": el.attr('data-default-color'),
                    "background-color": "#fafafa",
                    "color": el.attr('data-default-color')
                });
            }

        });

        var row_id = $thisInput.closest('form').find('input[name="voteCast[id]"]').val();
        $('svg .seat.diagram-pays-' + row_id).css({'fill': selectedColor});

    };

    $(document).on('change', 'input[name="voteCast[reponse_choisie]"]', function(ev) {
        
        $('input[name="voteCast[reponse_choisie]"]').not(this).prop('checked', false);

        var $form = $(this).closest('form');

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize()
        }).success(function(data) {
            // TODO! Ajouter un message dans la bannière.
        });

        manageColors($(ev.target));

    });

    $('input[name="voteCast[reponse_choisie]"]').filter(':checked').each(function() {
        manageColors($(this));
    });

})(window, document, jQuery, d3, Chart);
</script>

</body>
</html>