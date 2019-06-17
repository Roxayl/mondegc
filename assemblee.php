<?php
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

if(isset($_SESSION['userObject'])) {
    $thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
    $userPaysAllowedToVote = $thisUser->getCountries(\GenCity\Monde\User::getUserPermission('Dirigeant'));
}

$paysRFGC = new \GenCity\Monde\Pays(29);

$proposalList = new \GenCity\Proposal\ProposalList();


/** @var \GenCity\Proposal\Proposal[] $proposalsPendingVotes */
$proposalsPendingVotes = $proposalList->getPendingVotes();
/** @var \GenCity\Proposal\Proposal[] $proposalsPendingDebate */
$proposalsPendingDebate = $proposalList->getPendingDebate();
/** @var \GenCity\Proposal\Proposal[] $proposalsPendingValidation */
$proposalsPendingValidation = $proposalList->getPendingValidation();
/** @var \GenCity\Proposal\Proposal[] $proposalsFinished */
$proposalsFinished = $proposalList->getFinished();

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - Assemblée générale de l'OCGC</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="Carto/OLdefault.css" rel="stylesheet">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('http://image.noelshack.com/fichiers/2019/14/6/1554565976-assemblee-ocgc.png');
    background-position: 0 400px;
}
#map {
	height: 500px;
	background-color: #fff;
}
#mapPosition {
	height: 500px;
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
<!-- CARTE -->
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script>
 $( document ).ready(function() {
init();
});
</script>
<script type="text/javascript">
      $(function() {
          $('[rel="clickover"]').clickover();})
</script>
<!-- MODAL -->
<script src="assets/js/bootstrap-modalmanager.js"></script>
<script src="assets/js/bootstrap-modal.js"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $institut=true; include('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut">
  <div class="container">
    <h2 style="text-transform: uppercase">Organisation des Cités Gécéennes</h2>
    <h1>Assemblée générale</h1>
  </div>
</header>
<div class="container">

    <!-- Docs nav
    ================================================== -->
    <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-institut">
          <img src="http://image.noelshack.com/fichiers/2019/24/6/1560607735-ocgc-logo-ag-bis.png">
          <p><strong>Assemblée Générale</strong></p>
          <p><em>Organisation des Cités Gécéennes</em></p>
          </a></li>
        <li><a href="#presentation">Présentation</a></li>
        <li><a href="#propositions">Propositions</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== -->

    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">
    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li class="active">Assemblée générale</li>
    </ul>

    <section id="presentation">
    <div class="well">
        <?php renderElement('errormsgs'); ?>
    <div class="row-fluid">
        <div class="span8">
            <p>L'Assemblée générale de l'Organisation des Cités gécéennes (OCGC) est un organe de délibération et de prise de décisions composé de tous les pays officiellement reconnus par la communauté internationale. Son siège se trouve au même endroit que celui de l'OCGC, à Lutèce, la capitale de la
                <a href="page-pays.php?ch_pay_id=<?= $paysRFGC->get('ch_pay_id') ?>">
                  <img class="img-menu-drapeau" src="<?= $paysRFGC->get('ch_pay_lien_imgdrapeau') ?>">&nbsp;RFGC</a>.
            </p>
            <p><i>Articles détaillés :</i> <a href="http://www.forum-gc.com/f18-partie-privee"><i class="icon-globe"></i> Forum</a> &#183; <a href="http://vasel.yt/wiki/index.php?title=Assembl%C3%A9e_G%C3%A9n%C3%A9rale_de_l%27OCGC"><i class="icon-globe"></i> Wiki</a></p>
        <br>
            <h4>Fonctionnement</h4>
            <p>Chaque pays reconnu internationalement est membre de droit de l'Assemblée générale. Elle compte aujourd'hui une vingtaine de pays membres.</p>
            <p>Chaque pays peut faire une proposition à l'AG, qui sera votée en session plénière si le Conseil de l'OCGC valide le projet. L'approbation d'une proposition nécessite la majorité qualifiée de 50%, à l'exception de certaines motions comme la reconnaissance internationale d'un nouveau pays.</p>
        </div>
        <div class="span4">
            <a href="http://image.noelshack.com/fichiers/2019/21/5/1558707533-organigramme-ocgcbis.png">
              <img src="http://image.noelshack.com/fichiers/2019/21/5/1558707533-organigramme-ocgcbis.png"
                    alt="Schéma représentant le fonctionnement des organes de l'OCGC">
            </a>
        </div>
    </div>

    </div>
    </section>

    <section>

    <!-- PROPOSITIONS -->
    <?php if(isset($userPaysAllowedToVote)): ?>
    <div class="cta-title pull-right-cta">
        <a href="back/ocgc_proposal_create.php"
           class="btn btn-primary btn-cta">
            <i class="icon-white icon-pencil"></i> Lancer une proposition</a>
    </div>
    <?php endif; ?>

    <div class="titre-bleu" id="propositions">
      <h1>Propositions</h1>
    </div>

    <h3>Propositions actives</h3>

    <?php if(count($proposalsPendingVotes)): ?>
        <h4 class="well">Vote en cours</h4>
        <?php renderElement('Proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingVotes
            )); ?>
    <?php endif; ?>

    <?php if(count($proposalsPendingDebate)): ?>
        <h4 class="well">Débat en cours</h4>
        <?php renderElement('Proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingDebate
            )); ?>
    <?php endif; ?>

    <?php if(count($proposalsPendingValidation)): ?>
        <h4 class="well">En attente d'une validation du Conseil de l'OCGC</h4>
        <?php renderElement('Proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingValidation
            )); ?>
    <?php endif; ?>

    <h3>Propositions déjà votées</h3>
    <?php if(count($proposalsFinished)): ?>
        <?php renderElement('Proposal/proposal_finished_list', array(
                'proposalList' => $proposalsFinished
            )); ?>
    <?php else: ?>
        <p>Aucune proposition votée pour le moment.</p>
    <?php endif; ?>


    </section>

    </div>
    <!-- END CONTENT
    ================================================== -->
    </div>

</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
