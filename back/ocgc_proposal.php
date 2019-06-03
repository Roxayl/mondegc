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

    elseif(isset($_POST['proposalValidate'])) {

        $proposalValidate = new \GenCity\Proposal\ProposalValidate($formProposal);
        $formProposal->set('is_valid', $_POST['proposalValidate']['is_valid']);
        $formValidate = $proposalValidate->validate();
        if(count($formValidate) > 0) {
            getErrorMessage('error', $formValidate);
        } else {
            $proposalValidate->update();
            getErrorMessage('success', "La proposition a été acceptée avec succès !");
        }

    }

}

$debate_message = "<h4 style='display: inline;'>" . $formProposal->getStatus() . "</h4> • ";

if($formProposal->getStatus(false) ===
    \GenCity\Proposal\Proposal::allValidationStatus('votePending')) {
    $message_alert = "warning";
    $debate_message .=  "L'Assemblée générale siège en session plénière. La procédure de vote a commencé.";
} else {
    $message_alert = "info";
    $debate_message .= ($formProposal->getStatus(false) <
        \GenCity\Proposal\Proposal::allValidationStatus('votePending') ?
            "La procédure de vote n'a pas commencé." :
            "La procédure de vote est terminée.");
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
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
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
    background-image: url('http://image.noelshack.com/fichiers/2019/14/6/1554565976-assemblee-ocgc.png');
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
    <h1>Assemblée générale</h1>
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
        <li><a href="../assemblee.php">Assemblée générale</a> <span class="divider">/</span></li>
        <li class="active">
            <?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
            <?= $formProposal->getProposalId(); ?> :
            <?= $formProposal->question ?>
        </li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <div class="well" style="margin-top: -50px;">

    <h1><small><?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
               <?= $formProposal->getProposalId(); ?></small><br />
        <?= $formProposal->question ?></h1>

    <p>Proposition déposée le <?= dateFormat($formProposal->get('created')) ?> par
      <img class="img-menu-drapeau" src="<?= $formProposal->getPaysAuthor()->get('ch_pay_lien_imgdrapeau') ?>">
      <a href="../page-pays.php?ch_pay_id=<?= $formProposal->getPaysAuthor()->get('ch_pay_id') ?>">
          <?= $formProposal->getPaysAuthor()->get('ch_pay_nom') ?></a>
    </p>

    </div>

    <div class="well well-dark">

    <!-- ZONE DE DÉBATS -->
    <div class="cta-title pull-right-cta">
        <a href="../php/Modal/proposal_debate_edit.php?ID_proposal=<?= $formProposal->get('id') ?>"
           data-toggle="modal" data-target="#Modal-Monument" class="btn btn-primary btn-cta">
            <i class="icon-white icon-edit"></i></a>
    </div>
    <div id="info-generales" class="titre-bleu titre-fond-blanc" style="margin: -20px -19px 0;">
        <h1>Débats</h1>
    </div>

    <div class="well">
    <?php if( !empty($formProposal->get('link_debate')) || !empty($formProposal->get('link_wiki')) ): ?>
        <p>Suivez les débats concernant cette proposition sur l'ensemble des sites GC suivants :</p>
    <?php else: ?>
        <p>N'hésitez pas à ajouter le lien vers un sujet sur le forum, le Monde GC, le wiki, ou tout autre
            document permettant de fournir à l'Assemblée les informations nécessaires afin de voter.
        Tout dirigeant peut proposer un lien.</p>
    <?php endif; ?>
    </div>

    <div class="row-fluid">
    <?php if(!empty($formProposal->get('link_debate'))): ?>
        <div class="span6 alert inline alert-info">
            <a href="<?= __s($formProposal->get('link_debate')) ?>">
                <h4><i class="icon-globe" style="vertical-align: middle;"></i>
                    <?= __s($formProposal->get('link_debate_name')) ?></h4>
                <?= __s($formProposal->get('link_debate')) ?>
            </a>
        </div>
    <?php endif; ?>
    <?php if(!empty($formProposal->get('link_wiki'))): ?>
        <div class="span6 alert inline alert-info">
            <a href="<?= __s($formProposal->get('link_wiki')) ?>">
                <h4><i class="icon-globe" style="vertical-align: middle;"></i>
                    <?= __s($formProposal->get('link_wiki_name')) ?></h4>
                <?= __s($formProposal->get('link_wiki')) ?>
            </a>
        </div>
    <?php endif; ?>
    </div>

    <?php
    if($formProposal->getStatus(false) ===
        \GenCity\Proposal\Proposal::allValidationStatus('pendingValidation')):
    ?>

    <div id="info-generales" class="titre-bleu titre-fond-blanc" style="margin: 0 -19px 0;">
        <h1>Conseil de l'OCGC</h1>
    </div>
    <div class="row-fluid">
        <h3>Validez-vous cette proposition ?</h3>

        <div class="well">

            <form method="POST" action="ocgc_proposal.php?id=<?= $formProposal->get('id') ?>"
                  style="display: inline-block;">
                <input type="hidden" name="proposalValidate[ID_proposal]" value="<?= $formProposal->get('id') ?>">
                <input type="hidden" name="proposalValidate[is_valid]" value="2">
                <button type="submit" class="btn btn-success form-button-inline">Accepter</button>
            </form>

            <form method="POST" action="ocgc_proposal.php?id=<?= $formProposal->get('id') ?>"
                  style="display: inline-block;">
                <input type="hidden" name="proposalValidate[ID_proposal]" value="<?= $formProposal->get('id') ?>">
                <input type="hidden" name="proposalValidate[is_valid]" value="0">
                <button type="submit" class="btn btn-danger form-button-inline">Refuser</button>
            </form>

            <p>Consultez les détails de cette proposition ci-dessous.</p>
            <p>En tant que membre du Conseil de l'OCGC, vous pouvez accepter ou refuser cette proposition.
            Vérifiez que cette proposition est conforme à la Charte de l'OCGC. Une proposition est
            automatiquement acceptée lorsqu'elle n'a pas reçu de réponse de la part du Conseil
            une semaine après sa création.</p>

        </div>
    </div>
    <?php
    endif;
    ?>

    <!-- ZONE DE VOTE -->
    <div id="info-generales" class="titre-bleu titre-fond-blanc" style="margin: 0 -19px 0;">
        <h1>Hémicycle</h1>
    </div>

    <div class="well">
        <p><?= $debate_message ?></p>
    </div>

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

        if($formProposal->getStatus(false) ===
            \GenCity\Proposal\Proposal::allValidationStatus('votePending')):

            renderElement('Proposal/proposal_pending_votes', array(
                'formProposal' => $formProposal,
                'userVotes' => $userVotes
            ));

        else:
        ?>

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
                    <label><?= $thisResponse ?></label>
                </li>
            <?php endforeach; ?>
            </ul>

            <?php if($formProposal->getStatus(false) ===
                \GenCity\Proposal\Proposal::allValidationStatus('debatePending')): ?>
            <p>La phase de vote aura lieu au cours de la session plénière de l'Assemblée à partir du
                <?= dateFormat($formProposal->get('debate_start')) ?>.</p>
            <?php endif; ?>

        <?php
        endif;
        ?>

        </div>
    </div>
    </div>


  <?php else: // end if($_error) ?>

    <?php renderElement('errormsgs'); ?>

  <?php endif; ?>

    <!-- FIN formulaire Page Pays
        ================================================== -->
  </section>
</div>
</div>

<!-- modal -->
<div class="modal container fade" id="Modal-Monument"></div>
<div class="clearfix"></div>

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
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script src="../assets/js/Chart.2.7.3.bundle.js"></script>
<script type="text/javascript">
$(function() {
    $('[rel="clickover"]').clickover();})
</script>

<script type="text/javascript">
(function(window, document, $, d3, Chart, undefined) {

    $("a[data-toggle=modal]").click(function (e) {
      var lv_target = $(this).attr('data-target');
      var lv_url = $(this).attr('href');
      $(lv_target).load(lv_url)});

    $('#closemodal').click(function() {
        $('#Modal-Monument').modal('hide');
    });

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