<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

$_error = false;

if(isset($_SESSION['userObject']) && $_SESSION['userObject']->minStatus('OCGC')) {
    $has_ocgc_perm = true;
} else {
    $has_ocgc_perm = false;
}

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_SESSION['userObject'])) {
    $thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
}
if($_error) {
    getErrorMessage('ban_error', "Cette proposition n'existe pas.");
}

$formProposal = new \GenCity\Proposal\Proposal($_GET['id']);

$voteResults = $formProposal->getVote()->generateDiagramData();
$voteChartResults = $formProposal->getVote()->generateChartResults();

$voteList = new \GenCity\Proposal\VoteList($formProposal);
$userVotes = isset($_SESSION['userObject']) ? $voteList->getUserVotes($thisUser) : array();

$reponsesData = $voteList->generateTooltipData();

$proposalDecision = new \GenCity\Proposal\ProposalDecisionMaker($voteList);


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['voteCast'])) {

        $postVoteModel = new \GenCity\Proposal\Vote($_POST['voteCast']['id']);

        $postVoteModel->set('reponse_choisie', $_POST['voteCast']['reponse_choisie']);
        $voteValidate = $postVoteModel->validate($voteList, $formProposal);

        if(empty($voteValidate)) {
            $postVoteModel->castVote();
            getErrorMessage('success', "Vous avez voté !");
            header('Location: ' . DEF_URI_PATH . 'back/ocgc_proposal.php?id=' . $formProposal->get('id'));
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

switch($formProposal->getStatus(false)) {
    case \GenCity\Proposal\Proposal::allValidationStatus('notValid'):
        $debate_message .= "Cette proposition a été rejetée par l'OCGC.";
        $countdown_text = "Rejeté par l'OCGC";
        break;

    case \GenCity\Proposal\Proposal::allValidationStatus('pendingValidation'):
        $debate_message .= "Cette proposition attend d'être validé par l'OCGC.";
        $countdown_text = "En attente de l'OCGC";
        break;

    case \GenCity\Proposal\Proposal::allValidationStatus('debatePending'):
        $debate_message .= "Les débats se poursuivent en l'attente de la procédure de vote.";
        $countdown_text = "Débat en cours";
        break;

    case \GenCity\Proposal\Proposal::allValidationStatus('votePending'):
        $debate_message .= "L'Assemblée générale siège en session plénière. La procédure de vote a commencé.";
        $countdown_text = "";
        break;

    case \GenCity\Proposal\Proposal::allValidationStatus('voteFinished'):
        $debate_message .= "La procédure de vote est terminée.";
        $countdown_text = "";
        break;

    default:
        throw new UnexpectedValueException("Mauvaise pioche !");
}

use GenCity\Monde\Pays;use GenCity\Proposal\Vote;

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="iso-8859-1">
<title>Monde GC - Proposition : <?= $formProposal->get('question') ?></title>
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
    background-image: url('https://romukulot.fr/kaleera/images/fnMcE.png');
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
        /*allow tooltips to spill into margins */
        overflow: visible;
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
<?php $institut = true;
include(DEF_ROOTPATH . 'php/navbarback.php'); ?>

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
            <?= __s($formProposal->question) ?>
        </li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <div class="well" style="margin-top: -50px;">

    <h1><small><?= \GenCity\Proposal\Proposal::$typeDetail[$formProposal->type] ?>
               <?= $formProposal->getProposalId(); ?></small><br />
        <?= __s($formProposal->question) ?></h1>

    <p>Proposition déposée le <?= dateFormat($formProposal->get('created')) ?> par
      <img class="img-menu-drapeau" src="<?= __s($formProposal->getPaysAuthor()->get('ch_pay_lien_imgdrapeau')) ?>">
      <a href="../page-pays.php?ch_pay_id=<?= __s($formProposal->getPaysAuthor()->get('ch_pay_id')) ?>">
          <?= __s($formProposal->getPaysAuthor()->get('ch_pay_nom')) ?></a>
    </p>

    </div>

    <div class="well well-dark">

    <!-- ZONE DE DÉBATS -->
    <div class="cta-title pull-right-cta">
    <?php if(isset($_SESSION['userObject'])): ?>
        <a href="../php/Modal/proposal_debate_edit.php?ID_proposal=<?= $formProposal->get('id') ?>"
           data-toggle="modal" data-target="#Modal-Monument" class="btn btn-primary btn-cta">
            <i class="icon-white icon-edit"></i></a>
    <?php endif; ?>
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
    if($has_ocgc_perm && $formProposal->getStatus(false) ===
        \GenCity\Proposal\Proposal::allValidationStatus('pendingValidation')):
    ?>
    <div id="info-generales" class="titre-bleu titre-fond-blanc" style="margin: 0 -19px 0;">
        <h1>Conseil de l'OCGC</h1>
    </div>
    <div class="row-fluid">
        <h3>Validez-vous cette proposition ?</h3>

        <div class="well">

            <form method="POST" action="<?= DEF_URI_PATH ?>back/ocgc_proposal.php?id=<?= $formProposal->get('id') ?>"
                  style="display: inline-block;">
                <input type="hidden" name="proposalValidate[ID_proposal]" value="<?= $formProposal->get('id') ?>">
                <input type="hidden" name="proposalValidate[is_valid]" value="2">
                <button type="submit" class="btn btn-success form-button-inline">Accepter</button>
            </form>

            <form method="POST" action="<?= DEF_URI_PATH ?>back/ocgc_proposal.php?id=<?= $formProposal->get('id') ?>"
                  style="display: inline-block;">
                <input type="hidden" name="proposalValidate[ID_proposal]" value="<?= $formProposal->get('id') ?>">
                <input type="hidden" name="proposalValidate[is_valid]" value="0">
                <button type="submit" class="btn btn-danger form-button-inline">Refuser</button>
            </form>

            <p>Consultez les détails de cette proposition ci-dessous.</p>
            <p>En tant que membre du Conseil de l'OCGC, vous pouvez accepter ou refuser cette proposition.
            Vérifiez que cette proposition est conforme au
            <a href="http://www.forum-gc.com/t6960-ag-sujet-officiel#278448"
            >règlement de l'Assemblée générale</a>. Une proposition est automatiquement acceptée
            lorsqu'elle n'a pas reçu de réponse de la part du Conseil une semaine après sa création.</p>

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

            <div class="mouse tooltip" >Tooltip content</div>

            <svg id="parliament"></svg>
            <div id="parliament-chart-container">
                <canvas id="parliament-chart" width="110" height="60"></canvas>
            </div>

            <div id="proposal-results-container" class="well row-fluid">
                <div class="span6">

                    <?php
                    if($formProposal->getStatus(false) ===
                        \GenCity\Proposal\Proposal::allValidationStatus('voteFinished')): ?>
                        <h2>
                        <?php
                        renderElement('Proposal/proposal_decision', array(
                            'decisionData' => $proposalDecision->outputFormat())); ?>
                        </h2>

                    <?php
                    else: ?>
                        <h2 id="proposal-countdown" <?= $formProposal->getStatus(false) ===
                        \GenCity\Proposal\Proposal::allValidationStatus('votePending') ?
                            'runCountdown' : ''?>>
                            <?= $countdown_text ?>
                        </h2>
                    <?php
                    endif;
                    ?>

                </div>

                <div class="span6 well">
                    <?php renderElement('Proposal/proposal_description',
                        array('formProposal' => $formProposal,
                              'decisionData' => $proposalDecision->outputFormat())); ?>
                </div>
            </div>

        </div>

        <div class="span6">
        <h3><?= $formProposal->question ?></h3>

        <?php

        if($formProposal->getStatus(false) ===
            \GenCity\Proposal\Proposal::allValidationStatus('votePending') &&
           count($userVotes) > 0):

            renderElement('Proposal/proposal_pending_votes', array(
                'formProposal' => $formProposal,
                'userVotes' => $userVotes
            ));

        elseif($formProposal->getStatus(false) ===
            GenCity\Proposal\Proposal::allValidationStatus('voteFinished')):

            renderElement('Proposal/proposal_final_votes', array(
                'formProposal' => $formProposal
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
                    <label><?= __s($thisResponse) ?></label>
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
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
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

    /** Countdown **/

    try {

        var countdownElementId = 'proposal-countdown';

        var CountDownTimer = function(dt, id) {
            var end = new Date(dt);

            var _second = 1000;
            var _minute = _second * 60;
            var _hour = _minute * 60;
            var _day = _hour * 24;
            var timer;

            function showRemaining() {
                var now = new Date();
                var distance = end - now;
                if (distance < 0) {

                    clearInterval(timer);
                    document.getElementById(id).innerHTML = 'Vote terminé';

                    return;
                }
                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour) + (days * 24);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);

                var output = '';

                output += ('0' + hours).slice(-2) + ':';
                output += ('0' + minutes).slice(-2) + ':';
                output += ('0' + seconds).slice(-2) + '';

                document.getElementById(id).innerHTML = '<h4 style="margin: 0;">Vote en cours</h4>' + output;
            }

            timer = setInterval(showRemaining, 1000);
        };

        if($('#' + countdownElementId).get(0).hasAttribute('runCountdown')) {
            CountDownTimer("<?= $formProposal->get('debate_end') ?>", countdownElementId);
        }

    } catch(err) { }


    /** Modal **/

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
                labels: <?= json_encode(__s($voteChartResults['labels'])) ?>,
                datasets: [{
                    label: '# of Votes',
                    data: <?= json_encode(__s($voteChartResults['data'])) ?>,
                    backgroundColor: <?= json_encode(__s($voteChartResults['bgColor'])) ?>,
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


    /** Tooltip **/

    var voteData = <?= json_encode($reponsesData); ?>;

    var tooltip = d3.selectAll(".tooltip:not(.css)");
    var HTMLmouseTip = d3.select("div.tooltip.mouse");
    /* If this seems like a lot of different variables,
       remember that normally you'd only implement one
       type of tooltip! */

    var generateTooltipHtmlData = function(voteId) {

        var str = '';
        str += '<div class="tooltip-container" style="border-color: ' + voteData[voteId].reponseColor + '">';
        str += '<img src="' + voteData[voteId].paysDrapeau + '" class="img-menu-drapeau" /> '
        str += "<span>"
        str += voteData[voteId].paysNom
        str += "</span>"
        str += "<br>"
        str += '<strong style="color: ' + voteData[voteId].reponseColor + '">'
        str += voteData[voteId].reponseIntitule.toUpperCase()
        str += '</strong>'
        str += '</div>';

        return str;

    };

    /* I'm using d3 to add the event handlers to the circles
       and set positioning attributes on the tooltips, but
       you could use JQuery or plain Javascript. */
    d3.select("svg").select("g")
        .selectAll("circle")

        /***** Easy but ugly tooltip *****/
        .attr("title", "Automatic Title Tooltip")

        .on("mouseover", function () {

            tooltip.style("opacity", "1");

            /* You'd normally set the tooltip text
               here, based on data from the  element
               being moused-over; I'm just setting colour. */
            tooltip.style("color", this.getAttribute("fill") );
          /* Note: SVG text is set in CSS to link fill colour to
             the "color" attribute. */

            var tooltipString = generateTooltipHtmlData(d3.select(this).attr('data-vote-id'));
            tooltip.html(tooltipString);

            /***** Positioning a tooltip precisely
                   over an SVG element *****/

            /***** For an HTML tooltip *****/

            //for the HTML tooltip, we're not interested in a
            //transformation relative to an internal SVG coordinate
            //system, but relative to the page body

            //We can't get that matrix directly,
            //but we can get the conversion to the
            //screen coordinates.

            var matrix = this.getScreenCTM()
                    .translate(+this.getAttribute("cx"),
                             +this.getAttribute("cy"));

        })
        .on("mousemove", function () {

            /***** Positioning a tooltip using mouse coordinates *****/

            /* The code is shorter, but it runs every time
               the mouse moves, so it could slow down other
               processes or animation. */

            /***** For an HTML tooltip *****/

            //mouse coordinates relative to the page as a whole
            //can be accessed directly from the click event object
            //(which d3 stores as d3.event)
            HTMLmouseTip
                .style("left", Math.max(0, d3.event.pageX - 150) + "px")
                .style("top", (d3.event.pageY + 20) + "px");
        })
        .on("mouseout", function () {
            return tooltip.style("opacity", "0");
        });


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