<?php

use Illuminate\Support\Facades\Gate;
use Roxayl\MondeGC\Models\Pays;

//Connexion et deconnexion
include('php/log.php');

// *** Requête fait_his.
$colname_fait_his = "-1";
if (isset($_GET['ch_his_id'])) {
  $colname_fait_his = $_GET['ch_his_id'];
}

$query_fait_his = sprintf("SELECT ch_his_id, ch_his_label, ch_his_statut, ch_his_profession, ch_his_personnage, ch_his_paysID, ch_his_date, ch_his_mis_jour, ch_his_nb_update, ch_his_date_fait, ch_his_date_fait2, ch_his_profession, ch_his_nom, ch_his_lien_img1, ch_his_legende_img1, ch_his_description, ch_his_contenu, ch_pay_id, ch_pay_nom, (SELECT GROUP_CONCAT(ch_disp_fait_hist_cat_id) FROM dispatch_fait_his_cat WHERE ch_his_ID = ch_disp_fait_hist_id) AS listcat FROM histoire INNER JOIN pays ON ch_his_paysID = ch_pay_id WHERE ch_his_id = %s", escape_sql($colname_fait_his, "int"));
$fait_his = mysql_query($query_fait_his, $maconnexion);
$row_fait_his = mysql_fetch_assoc($fait_his);
$totalRows_fait_his = mysql_num_rows($fait_his);

$eloquentPays = Pays::findOrFail($row_fait_his['ch_pay_id']);

// *** Requête pour infos sur les categories.
$listcategories = ($row_fait_his['listcat']);
if ($row_fait_his['listcat']) {
    $query_liste_fai_cat3 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_ID In ($listcategories) AND ch_fai_cat_statut = 1";
    $liste_fai_cat3 = mysql_query($query_liste_fai_cat3, $maconnexion);
    $row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3);
    $totalRows_liste_fai_cat3 = mysql_num_rows($liste_fai_cat3);
}

$_SESSION['last_work'] = 'page-fait-historique.php?ch_his_id='.$row_fait_his['ch_his_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Fait historique : <?= __s($row_fait_his['ch_his_nom']) ?></title>
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
<!-- Navbar
    ================================================== -->
<?php $pays=true; require('php/navbar.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page"> 
  <!-- Moderation
     ================================================== -->
  <?php if (Gate::allows('update', $eloquentPays)) { ?>
  <form class="pull-right" action="<?= DEF_URI_PATH ?>back/fait_historique_confirmation_supprimer.php" method="post">
    <input name="ch_his_id" type="hidden" value="<?= e($row_fait_his['ch_his_id']) ?>">
    <button class="btn btn-danger" type="submit" title="supprimer ce fait historique"><i class="icon-trash icon-white"></i></button>
  </form>
  <?php if ($row_fait_his['ch_his_personnage'] == 2) { ?>
  <form class="pull-right" action="<?= DEF_URI_PATH ?>back/personnage_historique_modifier.php" method="post">
    <input name="ch_his_id" type="hidden" value="<?= e($row_fait_his['ch_his_id']) ?>">
    <button class="btn btn-primary" type="submit" title="modifier ce fait historique"><i class="icon-pencil icon-white"></i></button>
  </form>
  <?php } else {?>
<form class="pull-right" action="<?= DEF_URI_PATH ?>back/fait_historique_modifier.php" method="post">
    <input name="ch_his_id" type="hidden" value="<?= e($row_fait_his['ch_his_id']) ?>">
    <button class="btn btn-primary" type="submit" title="modifier ce fait historique"><i class="icon-pencil icon-white"></i></button>
  </form>
  <?php }?>
  <?php }?>
  <?php if (Gate::allows('update', $eloquentPays)) { ?>
  <a class="btn btn-primary pull-right" href="php/partage-fait-hist.php?ch_his_id=<?= e($row_fait_his['ch_his_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Partager sur le forum</a>
  <?php } ?>
  <ul class="breadcrumb pull-left mt-3">
    <li><a href="Page-carte.php#liste-pays">Pays</a> <span class="divider">/</span></li>
    <li><a href="page-pays.php?ch_pay_id=<?= e($row_fait_his['ch_his_paysID']) ?>"><?= e($row_fait_his['ch_pay_nom']) ?></a> <span class="divider">/</span></li>
    <li><a href="page-pays.php?ch_pay_id=<?= e($row_fait_his['ch_his_paysID']) ?>#histoire">Histoire</a> <span class="divider">/</span></li>
    <li class="active"><?= e($row_fait_his['ch_his_nom']) ?></li>
  </ul>
  <div class="clearfix"></div>
  <!-- Titre-->
  <div class="titre-vert">
    <h1><?= e($row_fait_his['ch_his_nom']) ?></h1>
  </div>
  <!-- Affichage fait historique-->
  <div class="well">
    <p class="pull-right">Histoire du pays <a class="" href="page-pays.php?ch_pay_id=<?= e($row_fait_his['ch_his_paysID']) ?>#histoire"><?= e($row_fait_his['ch_pay_nom']) ?></a></p>
    <div class="row-fluid">
      <div class="span4"> <img src="<?php echo $row_fait_his['ch_his_lien_img1']; ?>" alt="illustration">
        <p><em><?php echo $row_fait_his['ch_his_legende_img1']; ?></em></p>
      </div>
      <div class="span8">
        <?php if (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 1)) { ?>
        <!-- si periode historique -->
        <h4>Période du <?php echo affDate($row_fait_his['ch_his_date_fait']); ?> au <?php echo affDate($row_fait_his['ch_his_date_fait2']); ?>&nbsp;:</h4>
        <em>
        <?php 
	  $d1 = new DateTime($row_fait_his['ch_his_date_fait']);
	  $d2 = new DateTime($row_fait_his['ch_his_date_fait2']);
	  $diff = get_timespan_string($d1, $d2);
	  echo $diff;?>
        </em>
        <?php } elseif (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
        <!-- si pers historique -->
        <h4><?= e($row_fait_his['ch_his_profession']) ?> (<?php echo affDate($row_fait_his['ch_his_date_fait']); ?> - <?php echo affDate($row_fait_his['ch_his_date_fait2']); ?>)</h4>
        <em>
        <?php 
	  $d1 = new DateTime($row_fait_his['ch_his_date_fait']);
	  $d2 = new DateTime($row_fait_his['ch_his_date_fait2']);
	  $diff = get_timespan_string($d1, $d2);
	  echo 'mort &agrave; '.$diff;?>
        </em>
        <?php } elseif (($row_fait_his['ch_his_date_fait2'] == NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
        <!-- si pers vivant -->
        <h4><?= e($row_fait_his['ch_his_profession']) ?> (<?php echo affDate($row_fait_his['ch_his_date_fait']); ?>-&nbsp;&nbsp;)</h4>
        <em>
        <?php 
	  $d1 = new DateTime($row_fait_his['ch_his_date_fait']);
	  $d2 = new DateTime('NOW');
	  $diff = get_timespan_string($d1, $d2);
	  echo $diff;?>
        </em>
        <?php } else { ?>
        <!-- si fait historique -->
        <h4>&Eacute;v&eacute;nement du <?php echo affDate($row_fait_his['ch_his_date_fait']); ?>&nbsp;:</h4>
        <?php } ?>
        <p>&nbsp;</p>
        <p><strong><?= e($row_fait_his['ch_his_description']) ?></strong></p>
        <div class="row-fluid"> 
          <!-- Liste des categories du monument -->
          <h4>Cat&eacute;gories&nbsp;:</h4>
          <?php if ($row_fait_his['listcat']) { ?>
          <ul class="listes">
            <?php do { ?>
              <li class="row-fluid">
                <div class="span1 icone-categorie"><img src="<?php echo $row_liste_fai_cat3['ch_fai_cat_icon']; ?>" alt="icone <?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?>" style="background-color:<?php echo $row_liste_fai_cat3['ch_fai_cat_couleur']; ?>;"></div>
                <div class="span8">
                  <p><strong><a href="histoire.php?fai_catID=<?php echo $row_liste_fai_cat3['ch_fai_cat_ID']; ?>#fait_hist"><?php echo $row_liste_fai_cat3['ch_fai_cat_nom']; ?></a></strong></p>
                </div>
              </li>
              <?php } while ($row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3)); ?>
          </ul>
          <?php mysql_free_result($liste_fai_cat3); ?>
          <?php } else { ?>
          <?php if (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 1)) { ?>
          <!-- si periode historique -->
          <p>Cette p&eacute;riode ne fait partie d'aucune cat&eacute;gorie.</p>
          <?php } elseif (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
          <!-- si pers historique -->
          <p>Ce personnage ne fait partie d'aucune cat&eacute;gorie.</p>
          <?php } elseif (($row_fait_his['ch_his_date_fait2'] == NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
          <!-- si pers vivant -->
          <p>Ce personnage ne fait partie d'aucune cat&eacute;gorie.</p>
          <?php } else { ?>
          <!-- si fait historique -->
          <p>Cet &eacute;v&eacute;nement ne fait partie d'aucune cat&eacute;gorie.</p>
          <?php } ?>
          <?php }?>
        </div>
      </div>
    </div>
    <?php if ($row_fait_his['ch_his_contenu']) { ?>
    <div class="well"> <?= htmlPurify($row_fait_his['ch_his_contenu']) ?> </div>
    <?php }?>
  </div>
  <!-- Commentaire
        ================================================== -->
  <section>
    <div id="commentaires" class="titre-vert anchor">
      <h1>Visites</h1>
    </div>
    <?php 
	  $ch_com_categorie = "com_fait_his";
	  $ch_com_element_id = $colname_fait_his;
	  require('php/commentaire.php'); ?>
  </section>
  <!-- END CONTENT
    ================================================== --> 
</div>
</div>
<div class="modal container fade" id="Modal-Monument"></div>
<!-- Footer
    ================================================== -->
<?php require('php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
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
<script>
$("a[data-toggle=modal]").click(function (e) {
  lv_target = $(this).attr('data-target')
  lv_url = $(this).attr('href')
  $(lv_target).load(lv_url)})

$('#closemodal').click(function() {
    $('#Modal-Monument').modal('hide');
});
</script>
<!-- EDITEUR -->
<script type="text/javascript" src="assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</body>
</html>