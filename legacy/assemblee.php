<?php
        
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
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('https://roxayl.fr/kaleera/images/fnMcE.png');
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $institut=true; require('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut">
  <div class="container">
    <h2 style="text-transform: uppercase">Organisation des Cités gécéennes</h2>
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
          <img src="https://roxayl.fr/kaleera/images/7YPwC.png">
          <p><strong>Assemblée générale</strong></p>
          <p><em>Organisation des Cités gécéennes</em></p>
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
            <p><a href="http://www.forum-gc.com/t6960-ag-sujet-officiel#278448"><i class="icon-globe"></i> Consulter le règlement de l'AG</a></p>
        </div>
        <div class="span4">
            <a href="http://vasel.yt/wiki/images/e/e5/Organigramme_OCGCbis.png">
              <img src="http://vasel.yt/wiki/images/e/e5/Organigramme_OCGCbis.png"
                    alt="Schéma représentant le fonctionnement des organes de l'OCGC">
            </a>
        </div>
    </div>

    </div>
    </section>
    <div class="clearfix"></div>

    <?php if(isset($userPaysAllowedToVote)): ?>
    <div class="cta-title pull-right-cta">
        <a href="back/ocgc_proposal_create.php"
           class="btn btn-primary btn-cta">
            <i class="icon-white icon-pencil"></i> Lancer une proposition</a>
    </div>
    <?php endif; ?>
    <section>

    <!-- PROPOSITIONS -->
    <div class="titre-bleu" id="propositions">
      <h1>Propositions</h1>
    </div>

    <h3>Propositions actives</h3>

    <?php if(count($proposalsPendingVotes)): ?>
        <h4 class="well">Vote en cours</h4>
        <?php renderElement('proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingVotes
            )); ?>
    <?php endif; ?>

    <?php if(count($proposalsPendingDebate)): ?>
        <h4 class="well">Débat en cours</h4>
        <?php renderElement('proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingDebate
            )); ?>
    <?php endif; ?>

    <?php if(count($proposalsPendingValidation)): ?>
        <h4 class="well">En attente d'une validation du Conseil de l'OCGC</h4>
        <?php renderElement('proposal/proposal_active_list', array(
                'proposalList' => $proposalsPendingValidation
            )); ?>
    <?php endif; ?>

    <?php if( !count($proposalsPendingVotes) && !count($proposalsPendingDebate)
           && !count($proposalsPendingValidation)): ?>
        <div class="alert-block alert alert-info">Aucune proposition n'est préparée à l'Assemblée générale actuellement.</div>
    <?php endif; ?>

    <h3>Propositions déjà votées</h3>
    <?php if(count($proposalsFinished)): ?>
        <?php renderElement('proposal/proposal_finished_list', array(
                'proposalList' => $proposalsFinished
            )); ?>
    <?php else: ?>
        <p>Aucune proposition votée pour le moment.</p>
    <?php endif; ?>

    <a class="btn btn-primary btn-margin-left" href="ocgc_all_proposals.php"
        >Voir les propositions plus anciennes</a>
    <p></p>

    </section>

    </div>
    <!-- END CONTENT
    ================================================== -->
    </div>

</div>
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
</body>
</html>
