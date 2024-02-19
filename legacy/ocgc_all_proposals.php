<?php

//Connexion et deconnexion
include('php/log.php');

if(isset($_SESSION['userObject'])) {
    $thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
    $userPaysAllowedToVote = $thisUser->getCountries(\GenCity\Monde\User::getUserPermission('Dirigeant'));
}

$proposalList = new \GenCity\Proposal\ProposalList();

// Find out how many items are in the table
$total = $proposalList->getFinishedTotal();

// How many items to list per page
$limit = 10;
// How many pages will there be
$pages = ceil($total / $limit);
// What page are we currently on?
$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => 1,
        'min_range' => 1,
    ),
)));

// Calculate the offset for the query
$offset = ($page - 1)  * $limit;

// Some information to display to the user
$start = $offset + 1;
$end = min(($offset + $limit), $total);

// The "back" link
$prevlink = ($page > 1) ? '<li><a href="?page=1" title="Page 1">&laquo;</a></li> <li><a href="?page=' . ($page - 1) . '" title="Page précédente">&lsaquo;</a></li>' : '<li><span class="disabled">&laquo;</span></li> <li><span class="disabled">&lsaquo;</span></li>';

// The "forward" link
$nextlink = ($page < $pages) ? '<li><a href="?page=' . ($page + 1) . '" title="Page suivante">&rsaquo;</a></li> <li><a href="?page=' . $pages . '" title="Dernière page">&raquo;</a></li>' : '<li><span class="disabled">&rsaquo;</span></li> <li><span class="disabled">&raquo;</span></li>';

/** @var \GenCity\Proposal\Proposal[] $proposalsFinished */
$proposalsFinished = $proposalList->getFinished($limit, $offset);


?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - Toutes les propositions à l'Assemblée générale</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="carto/OLdefault.css" rel="stylesheet">
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
      <li><a href="assemblee.php">Assemblée générale</a> <span class="divider">/</span></li>
      <li class="active">Liste des propositions</li>
    </ul>

    <section>

    <!-- PROPOSITIONS -->

    <div class="titre-bleu" id="propositions">
      <h1>Propositions</h1>
    </div>

    <p><a href="assemblee.php#propositions" class="btn btn-primary btn-margin-left"
        >Voir les propositions actives</a></p>

    <?php
    // Display the paging information
    echo '<div class="pagination pull-right" style="margin-top: 0; margin-right: 10px;"><ul>',
        $prevlink,
        "<li><span class='btn-small'>Page $page sur $pages</span></li>",
        $nextlink,
        '</ul></div>';
    ?>

    <h3>Propositions déjà votées</h3>

    <?php if(count($proposalsFinished)): ?>
        <?php renderElement('proposal/proposal_finished_list', array(
                'proposalList' => $proposalsFinished
            )); ?>
    <?php else: ?>
        <p>Aucune proposition votée pour le moment.</p>
    <?php endif; ?>

    <?php
    // Display the paging information
    echo '<div class="pagination pull-right" style="margin-top: 0; margin-right: 10px;"><ul>',
        $prevlink,
        "<li><span class='btn-small'>Page $page sur $pages</span></li>",
        $nextlink,
        '</ul></div>';
    ?>

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
