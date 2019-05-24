<?php
require_once('Connections/maconnexion.php');

//Connexion et deconnexion
include('php/log.php');

if(!isset($_SESSION['login_user'])) {
    header('connexion.php');
    exit;
}

$thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
$userPaysAllowedToVote = $thisUser->getCountries(\GenCity\Monde\User::getUserPermission('Dirigeant'));

$proposalList = new \GenCity\Proposal\ProposalList();

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - Assemblée Générale de l'OCGC</title>
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
	background-image: url('assets/img/bannieres-instituts/Geo.png');
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
<?php include('php/cartepays.php'); ?>
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
<header class="jumbotron jumbotron-institut subhead anchor" id="info-institut" >
  <div class="container">
    <h1>Assemblée Générale</h1>
  </div>
</header>
<div class="container">

    <!-- Page CONTENT
    ================================================== -->
    <div class="corps-page">
    <ul class="breadcrumb">
      <li><a href="OCGC.php">OCGC</a> <span class="divider">/</span></li>
      <li class="active">Assemblée Générale</li>
    </ul>

    <section>
    <div class="well">

        <?php renderElement('errormsgs'); ?>

        <a class="btn btn-primary" href="back/ocgc_proposal_create.php">Nouvelle proposition</a>

        <p>Vous pouvez voter au nom des pays suivants :</p>
        <?php foreach($userPaysAllowedToVote as $userPays): ?>
            <div>
                <img src="<?= $userPays['ch_pay_lien_imgdrapeau'] ?>" style="height: 40px;" />
                <?= $userPays['ch_pay_nom'] ?>
            </div>
        <?php endforeach; ?>
    </div>
    </section>

    <section>
    <div class="titre-bleu anchor" id="notifications">
      <h1>Liste des propositions</h1>
    </div>

    <div id="categories">
      <table width="100%" class="table table-hover " cellspacing="1">
        <thead>
          <tr class="tablehead2">
            <th scope="col">Identifiant</th>
            <th scope="col">Type</th>
            <th scope="col">Question</th>
            <th scope="col">Proposée le</th>
            <th scope="col">Période de vote</th>
            <th scope="col">Statut</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php
        /** @var \GenCity\Proposal\Proposal $pending */
        foreach($proposalList->getAll() as $pending): ?>
            <tr>
              <td><?= $pending->getProposalId(); ?></td>
              <td><?= \GenCity\Proposal\Proposal::$typeDetail[$pending->get('type')] ?>
                  (<?= $pending->get('type') ?>)</td>
              <td><?= $pending->get('question') ?></td>
              <td><?= $pending->get('created') ?></td>
              <td>Du <?= $pending->get('debate_start') ?> au <?= $pending->get('debate_end') ?></td>
              <td></td>
              <td><a href="back/ocgc_proposal.php?id=<?= $pending->get('id') ?>">Voir la proposition</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td><div class="btn-group pull-right">
                <a class="btn disabled" href="#">2</a>
                </div></td>
          </tr>
        </tfoot>
      </table>
    </div>

    </section>

    </div>
    <!-- END CONTENT
    ================================================== -->
</div>
<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>
