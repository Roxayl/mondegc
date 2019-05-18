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

$formProposal = new \GenCity\Proposal\Proposal(null);
$formNextDebates = \GenCity\Proposal\Proposal::getNextDebates(true);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postProposal = new \GenCity\Proposal\Proposal($_POST['ocgc_proposal_create']);
    $proposalValidate = $postProposal->validate();

    if(empty($proposalValidate)) {
        $postProposal->create();
        getErrorMessage('success', "Votre proposition a été créée avec succès !");
        header('Location: ../assemblee.php');
        exit();
    }

    else {
        foreach($proposalValidate as $validation) {
            getErrorMessage('error', $validation['errorMessage']);
        }
    }

    $formProposal = $postProposal;

}

// Préselection des checkbox/radio.
// 'type'
$form_radio_type = array('IRL' => '', 'RP' => '');
$form_radio_type[$formProposal->type] = 'checked selected';

// 'type_reponse'
$form_radio_type_reponse = array('dual' => '', 'multiple' => '');
$form_radio_type_reponse[$formProposal->type_reponse] = 'checked selected';

// 'debate_start'
$form_select_debate_start = \GenCity\Proposal\Proposal::getNextDebates();
$form_select_debate_start[$formProposal->debate_start] = 'checked selected';


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

    <form method="POST" action="ocgc_proposal_create.php" class="form-horizontal" id="ProposalForm">

        <h3>Type de proposition</h3>

        <div class="well">
            <p>
            <input class="input-xlarge" type="radio" name="ocgc_proposal_create[type]" style="display: inline-block;"
                   id="ocgc_proposal_create[type][RP]" value="RP" <?= $form_radio_type['RP'] ?>>
            <label for="ocgc_proposal_create[type][RP]" style="display: inline-block;">
                Role-play (Résolution)</label><br />
                Vous pouvez créer une <strong>résolution</strong> afin de solliciter l'avis de l'Assemblée Générale sur
                un événement du role-play.
            </p>

            <p>
            <input class="input-xlarge" type="radio" name="ocgc_proposal_create[type]" style="display: inline-block;"
                   id="ocgc_proposal_create[type][IRL]" value="IRL" <?= $form_radio_type['IRL'] ?>>
            <label for="ocgc_proposal_create[type][IRL]" style="display: inline-block;">
                Réel (Sondage)</label><br />
                Vous pouvez interroger les membres de la communauté participant au Monde GC en créant un <strong>sondage</strong>.
            </p>
        </div>

        <h3>Créer la proposition en tant que</h3>

        <div class="well">
            <label for="ocgc_proposal_create[ID_pays]">Créer la proposition en tant que :</label>
            <select name="ocgc_proposal_create[ID_pays]" id="ocgc_proposal_create[ID_pays]">
                <?php foreach($userPaysAllowedToVote as $thisPays): ?>
                    <option value="<?= $thisPays->ch_pay_id ?>"><?= $thisPays->ch_pay_nom ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3>Contenu de la proposition</h3>

        <div class="well">

            <h4>Type de réponses : "pour/contre" ou personnalisé</h4>

            <p>
            <input class="input-xlarge" type="radio" name="ocgc_proposal_create[type_reponse]" style="display: inline-block;"
                   id="ocgc_proposal_create[type_reponse][dual]" value="dual"
                   <?= $form_radio_type_reponse['dual'] ?>>
            <label for="ocgc_proposal_create[type_reponse][dual]" style="display: inline-block;">
                Vote de type "POUR/CONTRE"</label><br />
                Un vote à deux réponses : pour ou contre.
            </p>

            <p>
            <input class="input-xlarge" type="radio" name="ocgc_proposal_create[type_reponse]" style="display: inline-block;"
                   id="ocgc_proposal_create[type_reponse][multiple]" value="multiple"
                   <?= $form_radio_type_reponse['multiple'] ?>>
            <label for="ocgc_proposal_create[type_reponse][multiple]" style="display: inline-block;">
                Vote à réponses personnalisés.</label><br />
                Vous pouvez définir manuellement les réponses à ce sondage.
            </p>

            <h4>Contenu</h4>

            <div id="sprytextfield1" class="control-group">
                <label class="control-label" for="ocgc_proposal_create[question]">Question <a href="#" rel="clickover" title="Objet de la proposition" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                    <input class="input-xlarge" name="ocgc_proposal_create[question]" type="text" id="ocgc_proposal_create[question]" value="<?= $formProposal->get('question') ?>" maxlength="255">
                    <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                </div>
            </div>

            <div id="detail_reponses" style="display: none;">

                <div id="sprytextfield2" class="control-group">
                    <label class="control-label" for="ocgc_proposal_create[reponse_1]">Réponse 1 <a href="#" rel="clickover" title="Réponse 1" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="ocgc_proposal_create[reponse_1]" type="text" id="ocgc_proposal_create[reponse_1]" value="<?= $formProposal->get('reponse_1') ?>" maxlength="255">
                        <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                    </div>
                </div>

                <div id="sprytextfield3" class="control-group">
                    <label class="control-label" for="ocgc_proposal_create[reponse_2]">Réponse 2 <a href="#" rel="clickover" title="Réponse 2" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="ocgc_proposal_create[reponse_2]" type="text" id="ocgc_proposal_create[reponse_2]" value="<?= $formProposal->get('reponse_2') ?>" maxlength="255">
                        <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                    </div>
                </div>

                <div id="sprytextfield4" class="control-group">
                    <label class="control-label" for="ocgc_proposal_create[reponse_3]">Réponse 3 <a href="#" rel="clickover" title="Réponse 3" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="ocgc_proposal_create[reponse_3]" type="text" id="ocgc_proposal_create[reponse_3]" value="<?= $formProposal->get('reponse_3') ?>" maxlength="255">
                        <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                    </div>
                </div>

                <div id="sprytextfield5" class="control-group">
                    <label class="control-label" for="ocgc_proposal_create[reponse_4]">Réponse 4 <a href="#" rel="clickover" title="Réponse 4" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="ocgc_proposal_create[reponse_4]" type="text" id="ocgc_proposal_create[reponse_4]" value="<?= $formProposal->get('reponse_4') ?>" maxlength="255">
                        <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                    </div>
                </div>

                <div id="sprytextfield6" class="control-group">
                    <label class="control-label" for="ocgc_proposal_create[reponse_5]">Réponse 5 <a href="#" rel="clickover" title="Réponse 5" data-content="255 caractères maximum."><i class="icon-info-sign"></i></a></label>
                    <div class="controls">
                        <input class="input-xlarge" name="ocgc_proposal_create[reponse_5]" type="text" id="ocgc_proposal_create[reponse_5]" value="<?= $formProposal->get('reponse_5') ?>" maxlength="255">
                        <span class="textfieldMaxCharsMsg">255 caract&egrave;res max.</span>
                    </div>
                </div>

            </div> <!-- end détail réponses -->

        </div>

        <h3>Modalités de vote</h3>

        <div class="well">
            <div id="sprytextfield7" class="control-group">
                <label class="control-label" for="ocgc_proposal_create[debate_start]">Date de vote <a href="#" rel="clickover" title="Date du vote" data-content="Proposez une date à laquelle sera soumise la proposition durant la séance plénière."><i class="icon-info-sign"></i></a></label>
                <div class="controls">
                    <select name="ocgc_proposal_create[debate_start]" id="ocgc_proposal_create[debate_start]">
                    <?php foreach($formNextDebates as $nextDebate): ?>
                        <option value="<?= $nextDebate['debate_start'] ?>"
                          <?= $form_select_debate_start[$nextDebate['debate_start']] ?>>Du <?=
                            date('d/m/Y H:i:s', strtotime($nextDebate['debate_start'])); ?> au <?=
                            date('d/m/Y H:i:s', strtotime($nextDebate['debate_end'])); ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="well">
            <input type="submit" class="btn btn-primary" value="Envoyer !">
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

<script type="text/javascript">
(function(document, window, $, undefined) {

    var $input = $('input[name="ocgc_proposal_create[type_reponse]"]');
    function updateTypeReponse() {
        var $checked = $input.filter(':checked');
        if($checked.attr('value') === 'dual') {
            $('#detail_reponses').hide();
        } else {
            $('#detail_reponses').show();
        }
    }
    $($input).on('change', function() {
        updateTypeReponse();
    });
    updateTypeReponse();

})(document, window, jQuery);
</script>
</body>
</html>