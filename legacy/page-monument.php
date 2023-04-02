<?php

use Carbon\Carbon;

//Connexion et deconnexion
include('php/log.php');

// *** Requête monument.
$colname_monument = "-1";
if (isset($_GET['ch_pat_id'])) {
  $colname_monument = $_GET['ch_pat_id'];
}

$query_monument = sprintf("SELECT ch_pat_id, ch_pat_label, ch_pat_statut, ch_pat_paysID, ch_pat_villeID, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description, ch_pat_commentaire, ch_pay_id, ch_pay_nom, ch_vil_ID, ch_vil_nom, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument = mysql_query($query_monument, $maconnexion);
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

// *** Ressources patrimoine
$query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
  INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
  INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument_ressources = mysql_query($query_monument_ressources, $maconnexion);
$row_monument_ressources_neutre = mysql_fetch_assoc($monument_ressources);


        $lastActivity = Carbon::createFromFormat('Y-m-d H:i:s', $row_monument['ch_pat_mis_jour']);
        $coefficient = 1;

        if($lastActivity < Carbon::now()->subMonths(6)) {
            $coefficient = 0.1;
        }
        elseif($lastActivity < Carbon::now()->subMonths(3)) {
            $coefficient = 0.5;
        }

$row_monument_ressources_neutre['budget'] = $row_monument_ressources_neutre['budget'] * $coefficient;
$row_monument_ressources = $row_monument_ressources_neutre ;

// Connection infos dirigeant pays
$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", GetSQLValueString($row_monument['ch_pat_paysID'], "int"));
$users = mysql_query($query_users, $maconnexion);
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);


// *** Requête pour infos sur les categories.
$listcategories = ($row_monument['listcat']);
if ($row_monument['listcat']) {


$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) ORDER BY ch_mon_cat_couleur";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion);
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);

//requete TOUT
$query_mon_cat = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_couleur NOT BETWEEN 100 AND 199 ORDER BY ch_mon_cat_couleur", GetSQLValueString($mon_ID, "int"));
$mon_cat = mysql_query($query_mon_cat, $maconnexion);
$row_mon_cat = mysql_fetch_assoc($mon_cat);
$totalRows_mon_cat = mysql_num_rows($mon_cat);

//requete 0
$query_mon_cat_GO_0 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur BETWEEN 0 AND 299 AND ch_mon_cat_couleur NOT BETWEEN 100 AND 199 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_0 = mysql_query($query_mon_cat_GO_0, $maconnexion);
$row_mon_cat_GO_0 = mysql_fetch_assoc($mon_cat_GO_0);
$totalRows_mon_cat_GO_0 = mysql_num_rows($mon_cat_GO_0);

//requete 310
$query_mon_cat_GO_310 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur = 310 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_310 = mysql_query($query_mon_cat_GO_310, $maconnexion);
$row_mon_cat_GO_310 = mysql_fetch_assoc($mon_cat_GO_310);
$totalRows_mon_cat_GO_310 = mysql_num_rows($mon_cat_GO_310);
$nb_mon_cat_GO_310 = 0;
if ($query_mon_cat_GO_310) {$ressource = mysql_query($query_mon_cat_GO_310);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_mon_cat_GO_310 = $nb_mon_cat_GO_310 + 1;}
   else {$nb_mon_cat_GO_310 = 1;}}}

//requete 320
$query_mon_cat_GO_320 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur = 320 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_320 = mysql_query($query_mon_cat_GO_320, $maconnexion);
$row_mon_cat_GO_320 = mysql_fetch_assoc($mon_cat_GO_320);
$totalRows_mon_cat_GO_320 = mysql_num_rows($mon_cat_GO_320);
$nb_mon_cat_GO_320 = 0;
if ($query_mon_cat_GO_320) {$ressource = mysql_query($query_mon_cat_GO_320);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_mon_cat_GO_320 = $nb_mon_cat_GO_320 + 1;}
   else {$nb_mon_cat_GO_320 = 1;}}}

//requete 330
$query_mon_cat_GO_330 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur = 330 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_330 = mysql_query($query_mon_cat_GO_330, $maconnexion);
$row_mon_cat_GO_330 = mysql_fetch_assoc($mon_cat_GO_330);
$totalRows_mon_cat_GO_330 = mysql_num_rows($mon_cat_GO_330);
$nb_mon_cat_GO_330 = 0;
if ($query_mon_cat_GO_330) {$ressource = mysql_query($query_mon_cat_GO_330);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_mon_cat_GO_330 = $nb_mon_cat_GO_330 + 1;}
   else {$nb_mon_cat_GO_330 = 1;}}}

//requete 340
$query_mon_cat_GO_340 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur = 340 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_340 = mysql_query($query_mon_cat_GO_340, $maconnexion);
$row_mon_cat_GO_340 = mysql_fetch_assoc($mon_cat_GO_340);
$totalRows_mon_cat_GO_340 = mysql_num_rows($mon_cat_GO_340);
$nb_mon_cat_GO_340 = 0;
if ($query_mon_cat_GO_340) {$ressource = mysql_query($query_mon_cat_GO_340);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_mon_cat_GO_340 = $nb_mon_cat_GO_340 + 1;}
   else {$nb_mon_cat_GO_340 = 1;}}}

//requete 400
$query_mon_cat_GO_400 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur BETWEEN 400 AND 480 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_400 = mysql_query($query_mon_cat_GO_400, $maconnexion);
$row_mon_cat_GO_400 = mysql_fetch_assoc($mon_cat_GO_400);
$totalRows_mon_cat_GO_400 = mysql_num_rows($mon_cat_GO_400);

//requete 999
$query_mon_cat_GO_999 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur = 999 ORDER BY ch_mon_cat_couleur";
$mon_cat_GO_999 = mysql_query($query_mon_cat_GO_999, $maconnexion);
$row_mon_cat_GO_999 = mysql_fetch_assoc($mon_cat_GO_999);
$totalRows_mon_cat_GO_999 = mysql_num_rows($mon_cat_GO_999);
}

$_SESSION['last_work'] = 'page-monument.php?ch_pat_id='.$row_monument['ch_pat_id'];


$thisPays = new \GenCity\Monde\Pays($row_monument['ch_pat_paysID']);

$eloquentMonument = \Roxayl\MondeGC\Models\Patrimoine::query()->findOrFail($colname_monument);

//calculs
$nb_cat_ok = 0;

if ($query_liste_mon_cat3) {$ressource = mysql_query($query_liste_mon_cat3);

while($row = mysql_fetch_assoc($ressource)) {
    if($row_monument['listcat']) {
        $nb_cat_ok = $nb_cat_ok + 1;
    } else {
        $nb_cat_ok = 1;
    }
}

// Mise a jour actu de l'entreprise
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajouter_actu")) {
    $updateSQL = sprintf("UPDATE patrimoine SET ch_pat_commentaire=%s WHERE ch_pat_id=%s",
        GetSQLValueString($_POST['ch_pat_commentaire'], "text"),
        GetSQLValueString($_POST['ch_pat_id'], "int"));

    $Result1 = mysql_query($updateSQL, $maconnexion);
    getErrorMessage('success', "L'actualité de l'entreprise a été modifiée avec succès !");
}
}

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title><?= __s($row_monument['ch_pat_nom']) ?> - Monde GC</title>
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $pays=true; require('php/navbar.php'); ?>
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

        <p><img src="<?= __s($thisPays->get('ch_pay_lien_imgdrapeau')) ?>" class="img-menu-drapeau"> <a class="" href="page-pays.php?ch_pay_id=<?= e($row_monument['ch_pat_paysID']) ?>"><?= __s($row_monument['ch_pay_nom']) ?></a> • <?php if ($row_monument['ch_pat_statut']==0) { ?> Entreprise référencée à <?php } else { ?><?php }?>
        <a class="" href="page-ville.php?ch_pay_id=<?= e($row_monument['ch_pat_paysID']) ?>&ch_ville_id=<?= e($row_monument['ch_pat_villeID']) ?>"><?= __s($row_monument['ch_vil_nom']) ?></a></p>

        <p><?= __s($row_monument['ch_pat_description']) ?></p><br>

     <?php renderElement('errormsgs'); ?>
        <!-- Dernières actualités -->
        <?php if($row_monument['ch_mon_cat_statut'] == 0) { ?>

        <?php /*
        <div class="accordion-group">
              <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" href="#spoiler-actu" style="background: #f0eeec;"><h4 style="margin-top: 0px; margin-bottom: 0px; text-transform: uppercase; font-weight: lighter; color:#101010; ">Dernières actualités de l'entreprise</h4><div class='' style="font-size: 13px;color: #577991;">Dernière mise à jour le <?php echo date("d/m/Y", strtotime($row_monument['ch_pat_mis_jour'])); ?></div></a>
              </div>
                <div id="spoiler-actu" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <?php if($row_monument['ch_pat_commentaire']) { ?>
                      <div style=><?= htmlPurify($row_monument['ch_pat_commentaire']) ?></div>
                    <?php } else { ?><p style='font-style: italic;'>Rien à signaler pour le moment...</p><?php }?>

           <?php if (($_SESSION['statut'] >= 20) OR ($row_users['ch_use_id'] == $_SESSION['user_ID'])) { ?>
           <div class="accordion-group" style="border: none;">
              <div class="accordion-heading">
                <a class="accordion-toggle thumbnail btn-ajout" data-toggle="collapse" href="#spoiler-actu-modif" style="scale: 85%;margin: 0.5em 0em 0em -1em;width: 250px;">Ajouter une actualité</a>
              </div>
                <div id="spoiler-actu-modif" class="accordion-body collapse" style="height: 0px;">
                  <div class="accordion-inner" style="scale: 0.95; border: 5px dotted rgb(255, 78, 0); border-radius: 15px; box-shadow: rgba(0, 0, 0, 0.1) 0px 8px 9px -5px, rgba(19, 5, 5, 0.04) 0px 15px 22px 2px, rgba(0, 0, 0, 0.1) 0px 6px 28px 5px !important; padding: 0em; margin-top: 0.2em;">
                    <form action="<?= e($editFormAction) ?>" method="POST" class="form-horizontal" name="ajouter_actu" Id="ajouter_actu" style="margin: 0;">
                    <textarea rows="20" name="ch_pat_commentaire" class="wysiwyg" id="ch_pat_commentaire" style="height: 200px;"><?= htmlPurify($row_monument['ch_pat_commentaire']) ?></textarea>
                    <button type="submit" class="btn btn-primary" style="width: 96%; text-align: center; margin: 1em 1em; border-radius: 0px 0px 10px 10px;">Modifier cette section</button>
                    <input type="hidden" name="MM_update" value="ajouter_actu">
                   </form>
                  </div>
                </div>
              </div>

                  <?php } ?>
                  </div></div>
                </div><br><br> */ ?>

      <?php } ?>

<?php if($nb_cat_ok !== 0) { ?>
        <!-- Liste des categories du monument -->
        <h3 class="souligne" style="margin-top: 0; border-color:#1a2638"><?php echo $nb_cat_ok ?> objectifs atteints jusqu'à présent&nbsp;</h3>

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

        <h3 class="souligne" style="margin-top: 0; border-color:#1a2638">Influence sur l'économie</h3>
             <div><?php
                renderElement('temperance/resources', array(
                'resources' => $row_monument_ressources
             ));
            ?></div>

<?php } ?>
      </div>
  </div>
</div>
<!-- Commentaire
        ================================================== -->
  <?php if ($_SESSION['statut'] >= 20) { ?>
      <div class="pull-right-cta cta-title">
          <a href="php/patrimoine-ajouter-monument-a-categorie-direct-modal.php?mon_id=<?= e($row_monument['ch_pat_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Modifier les catégories" class="btn btn-primary btn-cta" style="margin-top: -0.3em;">Modifier les catégories</a></div>
  <?php } ?>
<section>
  <div id="commentaires" class="titre-vert anchor">
    <h1>Contenu additionnel</h1>
      <div class="alert alert-tips" style="padding-bottom: -0.3em">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Cette partie vous permet d'enrichir votre <?php if ($row_monument['ch_pat_statut']==0) { ?>Entreprise<?php } else { ?>Quête<?php }?> avec du contenu additionnel, c'est-à-dire présenter certains aspects à part à travers un RP, une construction ou un projet que vous pouvez mettre en avant ici.<br> Cet ajout sera affiché sur la page d'accueil, et permettra de juges de voir si vous avez atteints de nouveaux objectifs ! <a target="_blank" href="https://www.forum-gc.com/t7224-go-entreprises#292209" class="guide-link">Comment ça marche ? GO!</a></div>
    <div style="background: white; padding-left: 2em; padding-bottom: 1em;">
        <?php if($row_monument['ch_pat_legende_img5']) { ?>
        <a target="_blank" href="<?= __s($row_monument['ch_pat_legende_img5']) ?>">
            <div class="external-link-icon"
                 style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></div>
            Voir son sujet sur le <bold>Forum de Génération City</bold></a>  •
        <?php } ?>
            <?php if($row_monument['ch_pat_lien_img5']) { ?>
        <a target="_blank" href="<?= __s($row_monument['ch_pat_lien_img5']) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('https://roxayl.fr/kaleera/images/h4FQp.png');"></div>
            Voir sa présentation complète sur le <bold>Wiki GC</bold></a>  •
        <?php } ?>
            <?php if($row_monument['ch_pat_legende_img1']) { ?>
        <a target="_blank" href="<?= __s($row_monument['ch_pat_legende_img1']) ?>" target="_blank">
            <div class="external-link-icon"
                 style="background-image:url('http://squirrel.roxayl.fr/johk/profil/1-avatar-23864e8b7da23c4cd5b4.png');"></div>
            Voir son profil sur <bold>Squirrel</bold></a>
        <?php } ?><br>

<!-- Boite à outil du GO! -->
<?php if ($row_users['ch_use_id'] == $_SESSION['user_ID']) { ?>
<!--  C'est une entreprise -->
<?php if($row_monument['ch_mon_cat_statut'] == 0) { ?>
<?php if(is_null($row_mon_cat_GO_0) == false) { ?>

<div class="accordion-group">
      <div class="accordion-heading guide-boite">
        <a class="accordion-toggle" data-toggle="collapse" href="#cat0"><h4 style="margin-top: 0px; margin-bottom: 0px; text-transform: uppercase; font-weight: lighter; color:#101010; "><div class="external-link-icon"
                 style="background-image:url('https://image.flaticon.com/icons/png/512/807/807313.png'); margin: 0em 0.2em 0.2em 0em;"></div> La boîte à Outils du GO!</h4></a>
      </div>
        <div id="cat0" class="accordion-body ">
          <div class="accordion-inner" style="background: #f0eeec; border-left: 5px solid #ffcd00;">
      <ul  class="listes">
     <?= __s($row_monument['ch_pat_nom']) ?> peut encore se développer, voici quelques idées d'objectifs que tu pourrais essayer d'atteindre :

    <div class="slider" style="padding-top: 45%;">
    <input type="radio" name="slider" title="slide1" checked="checked" class="slider__nav"/>
    <input type="radio" name="slider" title="slide2" class="slider__nav"/>
    <input type="radio" name="slider" title="slide3" class="slider__nav"/>
    <input type="radio" name="slider" title="slide4" class="slider__nav"/>
    <input type="radio" name="slider" title="slide5" class="slider__nav"/>
    <input type="radio" name="slider" title="slide6" class="slider__nav"/>
    <input type="radio" name="slider" title="slide7" class="slider__nav"/>

    <div class="slider__inner" style="width: 700%;">

    <div class="slider__contents"><i class="slider__image fa fa-codepen"></i>
      <h2 class="slider__caption">En avant les présentations !</h2>
      <div class="slider__txt">
      <?php do { ?><?php if($row_mon_cat_GO_0['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid listes-blanc" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_0['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_0['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_0['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_0['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_0['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_0['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_0['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_0['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li><?php } else { ?><?php }?>
      <?php } while ($row_mon_cat_GO_0 = mysql_fetch_assoc($mon_cat_GO_0)); ?>
      </div>
    </div>
    <div class="slider__contents"><i class="slider__image fa fa-codepen"></i>
      <h2 class="slider__caption">Un pour tous, tous pour un</h2>
      <div class="slider__txt"><?php if($nb_mon_cat_GO_310 == 6) { ?><span style="font-weight: bold; color: #ff4e00;">Tu peux dôter <?= __s($row_monument['ch_pat_nom']) ?> d'une <a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise#Conditions_de_travail">politique sociale</a> reprenant l'une des 6 classes suivantes, numérotées de A à F.<br> Alors, plutôt meilleur ami de tes employés ou au contraire boss tyranique prêt à sacrifier ses troupes pour de l'industrie et du budget ?</span><?php } else { ?>Rien n'est jamais figé dans la vie d'une entreprise,<br> tu peux donc demander à tout moment de changer de classe en matière sociale que ce soit pour améliorer le sort de tes employés... ou pas !<?php }?>
      <?php do { ?>
      <?php if($row_mon_cat_GO_310['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_310['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_310['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_310['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_310['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_310['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_310['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_310['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_310['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li>
      <?php } else { ?><?php }?>
      <?php } while ($row_mon_cat_GO_310 = mysql_fetch_assoc($mon_cat_GO_310)); ?>
      </div>
    </div>
    <div class="slider__contents"><i class="slider__image fa fa-codepen"></i>
      <h2 class="slider__caption">Une force de la nature</h2>
      <div class="slider__txt"><?php if($nb_mon_cat_GO_320 == 6) { ?><span style="font-weight: bold; color: #ff4e00;"><?= __s($row_monument['ch_pat_nom']) ?> peut se positionner sur le <a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise#Respect_de_l.27environnement">respect à l'environnement</a>, en demandant l'une des 6 classes suivantes numérotées de A pour la première à F pour <italic>L'ennemi juré de la planète</italic>... Mais attention aux équilibres en ressources !</span><?php } else { ?>Tu as déjà choisi une classe environnementale pour <?= __s($row_monument['ch_pat_nom']) ?>,<br> mais rien ne t'empêche de changer à tout moment pour l'une des autres catégories qui s'afiche juste en dessous, en faisant un RP de transition pour demander à changer de catégorie par exemple !<?php }?><br>
      <?php do { ?>
      <?php if($row_mon_cat_GO_320['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_320['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_320['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_320['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_320['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_320['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_320['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_320['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_320['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li>
      <?php } else { ?><?php }?>
      <?php } while ($row_mon_cat_GO_320 = mysql_fetch_assoc($mon_cat_GO_320)); ?>
      </div>
    </div>
    <div class="slider__contents"><i class="slider__image fa fa-newspaper-o"></i>
      <h2 class="slider__caption">Les affaires sont les affaires</h2>
      <div class="slider__txt"><?php if($nb_mon_cat_GO_330 !== 6) { ?>Tu t'es déjà fait validé l'état actuel de ton entreprise...<br> mais rien ne t'empêche de demander à changer quand tu voudras commencer un nouveau RP !<?php } else { ?><span style="font-weight: bold; color: #ff4e00;">Pour le moment, <?= __s($row_monument['ch_pat_nom']) ?> n'a pas de <a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise#Situation_financi.C3.A8re">situation financière</a> définie... et si tu demandais à te faire valider d'une des 6 classes suivantes ?<br>N'hésite pas à cliquer sur les titres de chaque classe pour voir quelles entreprises se sont elles déjà jetées à l'eau !</span><?php }?><br>
      <?php do { ?><?php if($row_mon_cat_GO_330['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_330['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_330['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_330['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_330['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_330['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_330['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_330['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_330['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li>
      <?php } else { ?><?php }?><?php } while ($row_mon_cat_GO_330 = mysql_fetch_assoc($mon_cat_GO_330)); ?></div>
    </div>
    <div class="slider__contents"><i class="slider__image fa fa-newspaper-o"></i>
      <h2 class="slider__caption">Libérééée, délivrééééeééée</h2>
      <div class="slider__txt"><?php if($nb_mon_cat_GO_340 !== 6) { ?>Tu veux nationaliser ou au contraire privatiser ta compagnie, ou plus simplement changer son organisation ?<br> Rien de plus simple, il suffit de l'annoncer en commentaire juste ci dessous, en demandant la nouvelle classe que tu veux pour <?= __s($row_monument['ch_pat_nom']) ?> !<?php } else { ?><span style="font-weight: bold; color: #ff4e00;">Tu n'as pas encore précisé le <a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise#Niveau_d.27autonomie">niveau d'autonomie</a> de <?= __s($row_monument['ch_pat_nom']) ?>,<br> alors qu'avec tu pourrais tant détailler ton RP national qu'en créer de nouveaux avec d'autres membres !</span><?php }?>
      <?php do { ?><?php if($row_mon_cat_GO_340['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_340['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_340['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_340['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_340['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_340['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_340['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_340['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_340['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li>
      <?php } else { ?><?php }?><?php } while ($row_mon_cat_GO_340 = mysql_fetch_assoc($mon_cat_GO_340)); ?></div>
    </div>
    <div class="slider__contents"><i class="slider__image fa fa-television"></i>
      <h2 class="slider__caption">À la conquête du Monde GC !</h2>
      <div class="slider__txt">
      <?php do { ?><?php if($row_mon_cat_GO_400['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid listes-blanc" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_400['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_400['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_400['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_400['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_400['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_400['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_400['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_400['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li><?php } else { ?><?php }?>
      <?php } while ($row_mon_cat_GO_400 = mysql_fetch_assoc($mon_cat_GO_400)); ?>
      </div>    </div>
    <div class="slider__contents"><i class="slider__image fa fa-diamond"></i>
      <h2 class="slider__caption">L'appel au public</h2>
      <div class="slider__txt"><?php if($nb_cat_ok < 7 ) { ?><span style="font-weight: bold;"><?= __s($row_monument['ch_pat_nom']) ?> n'a validé que <?php echo $nb_cat_ok ?> objectifs pour le moment, mais c'est déjà un excellent début !</span><br><br> Quand ton entreprise se sera encore plus développée, tu pourras organiser un sondage pour mesurer sa <a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise#Notori.C3.A9t.C3.A9">notoriété</a>.<br> Mais pour le moment, cela me semble un peu prématuré pour ne rien te cacher... mais en obtenant quelques autres objectifs présentés dans cette boite à outils, en un rien de temps tu seras fin prêt !<?php } else { ?>
        <span style="font-weight: bold;">Félicitations, <?= __s($row_monument['ch_pat_nom']) ?> cumule déjà plus de <?php echo $nb_cat_ok ?> objectifs différents !</span><br><br>Si tu te sens prêt, tu peux lancer un sondage sur le <a target="_blank" href="<?= __s($row_monument['ch_pat_legende_img5']) ?>"> topic de <?= __s($row_monument['ch_pat_nom']) ?></a> sur le forum, avec en réponse les 6 classes suivantes :
      <?php do { ?><?php if($row_mon_cat_GO_999['ch_mon_cat_statut'] == 0) { ?>
            <li class="row-fluid" style="margin-top: 0.5em; background-image: url('<?= __s($row_mon_cat_GO_999['bg_image_url']) ?>'); background-attachment: fixed; background-position: center; background-size: 110%;">
              <div class="span1 icone-categorie" style="width: 4%;"><img src="<?= __s($row_mon_cat_GO_999['ch_mon_cat_icon']) ?>" alt="icone <?= __s($row_mon_cat_GO_999['ch_mon_cat_nom']) ?>" style="max-width: 25px; margin-left: -0.7em; margin-top: 0.45em;"></div>
              <div class="span11" style="margin-left: 0em;">
                <p><strong><a target="_blank" href="politique.php?mon_cat_ID=<?php echo $row_mon_cat_GO_999['ch_mon_cat_ID']; ?>#entreprises"><?= __s($row_mon_cat_GO_999['ch_mon_cat_nom']) ?></a></strong>, <?= __s($row_mon_cat_GO_999['ch_mon_cat_desc']) ?>
                    <div style="scale: 85%; margin-left: -4em;  margin-bottom: -0.5em; margin-top: -0.5em; position: initial; text-align: initial;"><img src="assets/img/ressources/budget.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Budget"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_budget']) ?></strong>  <img src="assets/img/ressources/industrie.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Industrie"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_industrie']) ?></strong>  <img src="assets/img/ressources/bureau.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Commerce"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_commerce']) ?></strong>  <img src="assets/img/ressources/agriculture.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Agriculture"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_agriculture']) ?></strong>  <img src="assets/img/ressources/tourisme.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Tourisme"><strong> <?= e($row_mon_cat_GO_999['ch_mon_cat_tourisme']) ?></strong>  <img src="assets/img/ressources/recherche.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Recherche"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_recherche']) ?></strong>  <img src="assets/img/ressources/environnement.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Evironnement"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_environnement']) ?></strong>  <img src="assets/img/ressources/education.png" style="max-width: 15px; position: inherit; margin: auto;" alt="icone Education"> <strong><?= e($row_mon_cat_GO_999['ch_mon_cat_education']) ?></strong>
                  </div></p></div>
            </li>
      <?php } else { ?><?php }?><?php } while ($row_mon_cat_GO_999 = mysql_fetch_assoc($mon_cat_GO_999)); ?>
        Si au moins 7 membres votent, la solution la plus plebiscitée deviendra officiellement la classe de notoriété de ton entreprise. Tu peux aussi en élaborer des stratégies : envie d'être populaire mais d'en payer le prix, ou au contraire de t'illustrer comme un fauteur de trouble qui tire profit de ses possibles méfaits ? Il n'y a pas de mauvaise solution, tout dépend du RP que tu veux mener !
      <?php }?></div>
    </div>
  </div>
</div>

<div style="padding-top: 1em;">Pense bien à déjà faire tes présentations sur le <a target="_blank" href="<?= __s($row_monument['ch_pat_legende_img5']) ?>">sujet de ton entreprise</a> sur le forum,<br> puis de demander ensuite juste en dessous à ce que ton objectif soit validé par le Comité Politique !</div><a target="_blank" href="http://vasel.yt/wiki/index.php?title=GO/Entreprise" class="guide-link">Envie de rentrer dans les détails ? GO!</a>
    </ul>
                <div class="clearfix"></div>
            </div>
            </div>
</div>
<?php } else { ?>
      <div><div class="guide-boite" style="padding: 0.5em">
      <h4 style="margin-top: 0px; margin-bottom: 0px; text-transform: uppercase; font-weight: lighter; color:#101010; "><div class="external-link-icon" style="background-image:url('https://image.flaticon.com/icons/png/512/807/807313.png'); margin: 0em 0.2em 0.2em 0em;"></div> La boîte à Outils du GO!</h4>
      <div>Avant de pleinement te lancer dans le développement de ton entreprise, tu dois déjà te faire valider les connexions entre cette page et les autres plateformes du Monde GC où <?= __s($row_monument['ch_pat_nom']) ?> est présente.
        <form action="<?= DEF_URI_PATH ?>back/monument_modifier.php" method="post" style="margin: 1em 0em 0.6em;">
    <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
    <button class="btn btn-primary" type="submit" title="Modifier cette entreprise"><i class="icon-pencil icon-white"></i>  Ajouter les liens</button>
  </form></div>
      </div><?php } ?>

<?php } ?><?php } ?>
  </div>
  </div>

  <div><?php
	  $ch_com_categorie = "com_monument";
	  $ch_com_element_id = $colname_monument;
	  require('php/commentaire.php'); ?></div>
</section>
<!-- END CONTENT
    ================================================== -->
</div>

<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>

<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>

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