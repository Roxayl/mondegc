<?php

//Connexion et deconnexion
include('php/log.php');

// *** Requête monument.
$colname_monument = "-1";
if (isset($_GET['ch_pat_id'])) {
  $colname_monument = $_GET['ch_pat_id'];
}

$query_monument = sprintf("SELECT ch_pat_id, ch_pat_label, ch_pat_statut, ch_pat_paysID, ch_pat_villeID, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description, ch_pay_id, ch_pay_nom, ch_vil_ID, ch_vil_nom, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

// *** Ressources patrimoine
$query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
  INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
  INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument_ressources = mysql_query($query_monument_ressources, $maconnexion) or die(mysql_error());
$row_monument_ressources = mysql_fetch_assoc($monument_ressources);

// Connection infos dirigeant pays

$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", GetSQLValueString($row_monument['ch_pat_paysID'], "int"));
$users = mysql_query($query_users, $maconnexion) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

// *** Requête pour infos sur les categories.
$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
          

$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) ORDER BY ch_mon_cat_couleur";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
}

$_SESSION['last_work'] = 'page-monument.php?ch_pat_id='.$row_monument['ch_pat_id'];


$thisPays = new \GenCity\Monde\Pays($row_monument['ch_pat_paysID']);

$eloquentMonument = \App\Models\Patrimoine::findOrFail($colname_monument);

//calculs
$nb_cat_ok = 0;

if ($query_liste_mon_cat3) {$ressource = mysql_query($query_liste_mon_cat3);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_cat_ok = $nb_cat_ok + 1;}
   else {$nb_cat_ok = 1;}
}

mysql_data_seek($ressource, 0);}

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Quête : <?= __s($row_monument['ch_pat_nom']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
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
	background-image: url('<?php echo $background_jumbotron ?>');
}
</style>
<!-- Le javascript
    ================================================== -->
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
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
<?php $pays=true; include('php/navbar.php'); ?>
<header id="info-ville" class="jumbotron subhead anchor"> 
  <!-- Titre  et Carousel
    ================================================== -->
  <div class="container container-carousel">
    <?php if ($row_monument['ch_pat_lien_img1'] OR $row_monument['ch_pat_lien_img2'] OR $row_monument['ch_pat_lien_img3'] OR $row_monument['ch_pat_lien_img4'] OR $row_monument['ch_pat_lien_img5']) { ?>
    <div class="titre-caroussel-container">
        <?php if ($row_monument['ch_pat_statut']==0) { ?><h1 class="titre-caroussel">Entreprise</h1><?php } else { ?><h1 class="titre-caroussel">Quête</h1><?php }?>
    </div>
    <section id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <?php if ($row_monument['ch_pat_lien_img1']) { ?>
        <div class="item active" style="background-position: center; background-repeat: no-repeat; background-size: inherit; height: 460px; background-color: white; margin-top: -3em; background-image: url(<?php echo $row_monument['ch_pat_lien_img1']; ?>)">
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img2']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img2']; ?>)">
          <div class="carousel-caption">
            <p><?= __s($row_monument['ch_pat_legende_img2']) ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img3']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img3']; ?>)">
          <div class="carousel-caption">
            <p><?= __s($row_monument['ch_pat_legende_img3']) ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img4']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img4']; ?>)">
          <div class="carousel-caption">
            <p><?= __s($row_monument['ch_pat_legende_img4']) ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </section>
    <!-- Titre si pas de carrousel
    ================================================== -->
    <?php } else { ?>
    <h1><?= __s($row_monument['ch_pat_nom']) ?></h1>
    <?php } ?>
  </div>
</header>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">

    <ul class="breadcrumb pull-left">
        <li><a href="Page-carte.php#liste-pays">Pays</a> <span class="divider">/</span></li>
        <li><a href="page-pays.php?ch_pay_id=<?= e($row_monument['ch_pay_id']) ?>"><?= e($row_monument['ch_pay_nom']) ?></a> <span class="divider">/</span></li>
        <li><a href="page-pays.php?ch_pay_id=<?= e($row_monument['ch_pay_id']) ?>#villes">Villes</a> <span class="divider">/</span></li>
        <li><a href="page-ville.php?ch_pay_id=<?= e($row_monument['ch_pay_id']) ?>&ch_ville_id=<?= e($row_monument['ch_vil_ID']) ?>"><?= __s($row_monument['ch_vil_nom']) ?></a> <span class="divider">/</span></li>
        <li><a href="page-ville.php?ch_pay_id=<?= e($row_monument['ch_pay_id']) ?>&ch_ville_id=<?= e($row_monument['ch_vil_ID']) ?>#quetes">Quêtes</a> <span class="divider">/</span></li>
      <li class="active"><?= e($row_monument['ch_pat_nom']) ?></li>
    </ul>
  <!-- Moderation
     ================================================== -->
  <?php if (($_SESSION['statut'] >= 20) OR ($row_users['ch_use_id'] == $_SESSION['user_ID'])) { ?>
  <form class="pull-right" action="<?= DEF_URI_PATH ?>back/monument_confirmation_supprimer.php" method="post">
    <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
    <button class="btn btn-danger" type="submit" title="supprimer ce monument"><i class="icon-trash icon-white"></i></button>
  </form>
  <form class="pull-right" action="<?= DEF_URI_PATH ?>back/monument_modifier.php" method="post">
    <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
    <button class="btn btn-primary" type="submit" title="modifier ce monument"><i class="icon-pencil icon-white"></i></button>
  </form>
  <?php } ?>
  <?php if ($_SESSION['statut'] >= 20) { ?>
  <a class="btn btn-primary btn-margin-left" href="php/patrimoine-ajouter-monument-a-categorie-direct-modal.php?mon_id=<?= e($row_monument['ch_pat_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Modifier les catégories">Modifier les catégories</a>
  <?php } ?>
  <?php if ($row_users['ch_use_id'] == $_SESSION['user_ID']) { ?>
  <a class="btn btn-primary pull-right" href="php/partage-monument.php?ch_pat_id=<?= e($row_monument['ch_pat_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i>Partager sur le forum</a>
  <?php } ?>
  
  <div class="clearfix"></div>
  <div class="modal container fade" id="Modal-Monument"></div>
  <div class="titre-vert">
    <h1><?= __s($row_monument['ch_pat_nom']) ?></h1>
  </div>
  <div class="well">
    <div class="row-fluid">
      <div>
        <div class="alert alert-info">
            <h4>Je suis en <span class="badge badge-warning">BETA</span></h4>
            <p>Les quêtes sont une fonctionnalité en cours de test. N'hésitez pas à faire vos retours sur le forum !</p>
        </div>

        <p><img src="<?= __s($thisPays->get('ch_pay_lien_imgdrapeau')) ?>" class="img-menu-drapeau"> <a class="" href="page-pays.php?ch_pay_id=<?= e($row_monument['ch_pat_paysID']) ?>"><?= __s($row_monument['ch_pay_nom']) ?></a> • <?php if ($row_monument['ch_pat_statut']==0) { ?> Entreprise référencée à <?php } else { ?><?php }?>
        <a class="" href="page-ville.php?ch_pay_id=<?= e($row_monument['ch_pat_paysID']) ?>&ch_ville_id=<?= e($row_monument['ch_pat_villeID']) ?>"><?= __s($row_monument['ch_vil_nom']) ?></a></p>
        <p><?= __s($row_monument['ch_pat_description']) ?></p>


        <!-- Liste des categories du monument -->
        <p><strong><?php echo $nb_cat_ok ?> objectifs atteints jusqu'à présent&nbsp;:</strong></p>
        <?php if ($row_monument['listcat']) { ?>
        <ul class="listes">
          <?php do { ?>
            <li class="row-fluid" style="background-image: url('<?= __s($row_liste_mon_cat3['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie"><img src="<?= __s($row_liste_mon_cat3['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_liste_mon_cat3['ch_mon_cat_nom']) ?>"></div>
              <div class="span7" style="width: 90%; margin-left: 0em;">
                <p><strong><a href="politique.php?mon_cat_ID=<?php echo $row_liste_mon_cat3['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_liste_mon_cat3['ch_mon_cat_nom']) ?></a></strong> <br><?= __s($row_liste_mon_cat3['ch_mon_cat_desc']) ?> <br>
                    <div style="vertical-align: baseline; scale: 75%; margin-left: -2em; display: inline flow-root list-item; margin-top: -0.5em;"><img src="assets/img/ressources/budget.png" style="max-width: 15px" alt="icone Budget"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px" alt="icone Industrie"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px" alt="icone Commerce"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px" alt="icone Agriculture"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px" alt="icone Tourisme"><strong> <?= e($row_liste_mon_cat3['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px" alt="icone Recherche"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px" alt="icone Evironnement"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px" alt="icone Education"> <strong><?= e($row_liste_mon_cat3['ch_mon_cat_education']) ?></strong>
                  </div>
            </li>
            <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)) ?>
        </ul>
        <?php mysql_free_result($liste_mon_cat3); ?>
      <?php } else { ?>
      <p>Il n'y a aucun objectif validé, pour le moment...</p>
      <?php }?>
        <br>
        <p><strong>Influence sur l'économie :</strong></p>
             <div><?php
                renderElement('temperance/resources', array(
                'resources' => $row_monument_ressources
             ));
            ?></div>
      </div>
  </div>
</div>
<!-- Commentaire
        ================================================== -->
<section>
  <div id="commentaires" class="titre-vert anchor">
    <h1>Contenu additionnel</h1>
      <div class="alert alert-tips" style="padding-bottom: -0.3em">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Cette partie vous permet d'enrichir votre <?php if ($row_monument['ch_pat_statut']==0) { ?>Entreprise<?php } else { ?>Quête<?php }?> avec du contenu additionnel, c'est-à-dire présenter certains aspects à part à travers un RP, une construction ou un projet que vous pouvez mettre en avant ici.<br> Cet ajout sera affiché sur la page d'accueil, et permettra de juges de voir si vous avez atteints de nouveaux objectifs ! <a href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures" class="guide-link">Comment ça marche ? GO!</a></div>
    <div style="background: white; padding-left: 2em; padding-bottom: 2em;">
        <?php if($row_monument['ch_pat_legende_img5']) { ?>
        <a href="<?= __s($row_monument['ch_pat_legende_img5']) ?>">
            <div class="external-link-icon"
                 style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></div>
            Voir son sujet sur le <bold>Forum de Génération City</bold></a>  •
        <?php } ?>
            <?php if($row_monument['ch_pat_lien_img5']) { ?>
        <a href="<?= __s($row_monument['ch_pat_lien_img5']) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('https://romukulot.fr/kaleera/images/h4FQp.png');"></div>
            Voir sa présentation complète sur le <bold>Wiki GC</bold></a>  •
        <?php } ?>
            <?php if($row_monument['ch_pat_legende_img1']) { ?>
        <a href="<?= __s($row_monument['ch_pat_legende_img1']) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('http://squirrel.romukulot.fr/johk/profil/1-avatar-23864e8b7da23c4cd5b4.png');"></div>
            Voir son profil sur <bold>Squirrel</bold></a>
        <?php } ?>
    </div>
  </div>
  <div><?php
	  $ch_com_categorie = "com_monument";
	  $ch_com_element_id = $colname_monument;
	  include('php/commentaire.php'); ?></div>
</section>
<!-- END CONTENT
    ================================================== -->
</div>

<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>

<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
</body>
</html>