<?php
        
//Connexion et deconnexion
include('php/log.php');

if(isset($_SESSION['login_user'])) {
    $thisUser = new GenCity\Monde\User($_SESSION['user_ID']);
    $listePays = $thisUser->getCountries();
}

$listeInstituts = \GenCity\Monde\Institut\Institut::getAll();

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Monde GC - Tableau de bord</title>
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
<?php $dashboard=true; require('php/navbar.php'); ?>
<!-- Subhead
================================================== -->
<header class="jumbotron jumbotron-institut jumbotron-small subhead anchor" id="info-dashboard"
    style="background: url('https://www.generation-city.com/forum/new/img/cat2.jpg');">
  <div class="container" style="text-align: center;">
    <h1>Tableau de bord</h1>
    <h2>.</h2>
  </div>
</header>
<div class="container">

  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-dashboard">
          Bienvenue sur votre tableau de bord.</li>
        <li><a href="#gestion">Gestion</a></li>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== -->

    <!-- Page CONTENT
    ================================================== -->
    <div class="span9 corps-page">
    <ul class="breadcrumb">
      <li class="active">Tableau de bord</li>
    </ul>

    <section>
    <div class="titre-bleu anchor" id="gestion">
      <h1>Gestion</h1>
    </div>

    <?php if($thisUser->minStatus(\GenCity\Monde\User::getUserPermission("OCGC"))): ?>
    <small class="pull-right"><i class="icon-info-sign"></i> <i>Vous êtes membre du Conseil de l'OCGC.</i></small>
    <h3>Conseil de l'OCGC</h3>
    <div class="well">
        <div class="row-fluid thumbnails">

        <?php foreach($listeInstituts as $thisInstitut): ?>
            <div class="span2 thumbnail">

                <img src="<?= __s($thisInstitut->get('ch_ins_logo')) ?>" style="max-height: 60px;"
                     alt="Logo <?= __s($thisInstitut->get('ch_ins_nom')) ?>">
                <h4><?= __s($thisInstitut->get('ch_ins_nom')) ?></h4>

                <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    Actions
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    <li class="dropdown-li-force"><a tabindex="-1" href="back/communique_ajouter.php?userID=<?= $_SESSION['userObject']->get('ch_use_id') ?>&cat=institut&com_element_id=<?= $thisInstitut->get('ch_ins_ID') ?>">
                            <i class="icon-file"></i> Publier un communiqué</a></li>
                </ul>
                </div>

            </div>
        <?php endforeach; ?>

        </div>
    </div>
    <?php endif; ?>

    <h3>Mes pays</h3>
    <div class="well">
        <?php if(empty($listePays)): ?>
        <p>Vous n'avez pas de pays. Quelle tristesse !</p>
        <?php endif; ?>
        <ul class="listes">
            <?php foreach($listePays as $pays): ?>
            <li class="row-fluid">
              <div class="">
                <div class="span2">
                    <a href="page-pays.php?ch_pay_id=<?= e($pays['ch_pay_id']); ?>"><img src="<?= e($pays['ch_pay_lien_imgdrapeau']); ?>" alt="drapeau"></a>
                </div>
                <div class="span5">
                    <h3><?= e($pays['ch_pay_nom']); ?></h3>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                            <li class="dropdown-li-force">
                                <a tabindex="-1" href="back/communique_ajouter.php?paysID=<?= e($pays['ch_pay_id']) ?>&userID=<?= $_SESSION['userObject']->get('ch_use_id') ?>&cat=pays&com_element_id=<?= e($pays['ch_pay_id']) ?>">
                                    <i class="icon-file"></i> Publier un communiqué</a></li>
                            <li class="dropdown-li-force">
                                <a tabindex="0" href="<?= route('infrastructure.select-group',
                                    ['infrastructurable_type' => 'pays',
                                     'infrastructurable_id' => $pays['ch_pay_id']]) ?>">
                                    <i class="icon-home"></i> Ajouter une infrastructure</a></li>
                        </ul>
                    </div>
                </div>
                <div class="span2">
                </div>
                <div class="span3">
                    <a href="page-pays.php?ch_pay_id=<?= e($pays['ch_pay_id']); ?>" class="span btn btn-primary">Visiter</a>
                    <a href="back/page_pays_back.php?paysID=<?= e($pays['ch_pay_id']) ?>&userID=<?= $thisUser->model->ch_use_id ?>" class="span btn btn-primary"><i class="icon-pays-small-white"></i> Gérer mon pays</a>
                </div>
              </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

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
