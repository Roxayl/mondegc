<?php

header('Content-Type: text/html; charset=utf-8');

// *** Requête fait_his.
$colname_fait_his = "-1";
if (isset($_GET['ch_his_id'])) {
  $colname_fait_his = $_GET['ch_his_id'];
}

$query_fait_his = sprintf("SELECT ch_his_id, ch_his_label, ch_his_statut, ch_his_paysID, ch_his_personnage, ch_his_date, ch_his_mis_jour, ch_his_nb_update, ch_his_date_fait, ch_his_date_fait2, ch_his_profession, ch_his_nom, ch_his_lien_img1, ch_his_legende_img1, ch_his_description, ch_his_contenu, ch_pay_nom, (SELECT GROUP_CONCAT(ch_disp_fait_hist_cat_id) FROM dispatch_fait_his_cat WHERE ch_his_ID = ch_disp_fait_hist_id) AS listcat FROM histoire INNER JOIN pays ON ch_his_paysID = ch_pay_id WHERE ch_his_id = %s", escape_sql($colname_fait_his, "int"));
$fait_his = mysql_query($query_fait_his, $maconnexion);
$row_fait_his = mysql_fetch_assoc($fait_his);
$totalRows_fait_his = mysql_num_rows($fait_his);

// *** Requête commentaires.
$ch_com_categorie = "com_fait_his";
$ch_com_element_id = $colname_fait_his;


$query_commentaire = sprintf("SELECT ch_com_ID, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_use_paysID, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = 'com_fait_his' AND ch_com_element_id = %s ORDER BY ch_com_date DESC", escape_sql($ch_com_element_id, "int"));
$commentaire = mysql_query($query_commentaire, $maconnexion);
$row_commentaire = mysql_fetch_assoc($commentaire);
$totalRows_commentaire = mysql_num_rows($commentaire);


// *** Requête pour infos sur les categories.
$listcategories = ($row_fait_his['listcat']);
			if ($row_fait_his['listcat']) {
          

$query_liste_fai_cat3 = "SELECT * FROM faithist_categories WHERE ch_fai_cat_ID In ($listcategories) AND ch_fai_cat_statut = 1";
$liste_fai_cat3 = mysql_query($query_liste_fai_cat3, $maconnexion);
$row_liste_fai_cat3 = mysql_fetch_assoc($liste_fai_cat3);
$totalRows_liste_fai_cat3 = mysql_num_rows($liste_fai_cat3);
}
?>

<!-- Modal Header-->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <!-- Titre-->
  <div class="titre-vert">
    <h1><?= e($row_fait_his['ch_his_nom']) ?></h1>
  </div>
</div>
<!-- Modal BODY-->
<div class="modal-body corps-page">
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
	  echo "mort &agrave; ".$diff;?>
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
          <?php if ($row_liste_fai_cat3) { ?>
          <ul class="listes">
            <?php do { ?>
              <li class="row-fluid">
                <div class="span1 icone-categorie"><img src="<?php echo e($row_liste_fai_cat3['ch_fai_cat_icon']); ?>" alt="icone <?php echo e($row_liste_fai_cat3['ch_fai_cat_nom']); ?>" style="background-color:<?php echo e($row_liste_fai_cat3['ch_fai_cat_couleur']); ?>;"></div>
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
  </div>
  <?php if ($row_fait_his['ch_his_contenu']) { ?>
  <div class="well"> <?= htmlPurify($row_fait_his['ch_his_contenu']) ?> </div>
  <?php }?>
  <!-- REACTIONS -->
  <div id="commentaires" class="titre-vert">
    <h1>R&eacute;actions</h1>
  </div>
  <?php if ($row_commentaire) { ?>
  <ul class="listes">
    <?php do { ?>
      <li class="row-fluid" id="commentaireID<?= e($row_commentaire['ch_com_ID']) ?>">
        <div class="span3 img-listes img-avatar"> <img src="<?= e($row_commentaire['ch_use_lien_imgpersonnage']) ?>"> </div>
        <div class="span9 info-listes"> 
          <!-- AFFICHAGE OUTILS MODERATION -->
          <div class="pull-right">
            <?php if (($_SESSION['statut'] >= 20) OR ($_SESSION['user_ID'] == $row_commentaire['ch_com_user_id'])) { ?>
            <form class="pull-right" action="back/communique_confirmation_supprimer.php" method="post">
              <input name="communique_ID" type="hidden" value="<?= e($row_commentaire['ch_com_ID']) ?>">
              <button class="btn" type="submit" title="supprimer le commentaire"><i class="icon-trash"></i></button>
            </form>
            <form class="pull-right" action="back/communique_modifier.php" method="post">
              <input name="com_id" type="hidden" value="<?= e($row_commentaire['ch_com_ID']) ?>">
              <button class="btn" type="submit" title="modifier le commentaire"><i class="icon-pencil"></i></button>
            </form>
            <?php } ?>
          </div>
          <h4><?= e($row_commentaire['ch_use_predicat_dirigeant']) ?> <?= e($row_commentaire['ch_use_prenom_dirigeant']) ?> <?= e($row_commentaire['ch_use_nom_dirigeant']) ?></h4>
          <h5><?= e($row_commentaire['ch_use_titre_dirigeant']) ?></h5>
          <!-- AFFICHAGE DATE --> 
          <small>Le <?php echo date("d/m/Y", strtotime($row_commentaire['ch_com_date'])); ?> &agrave; <?php echo date("G:i:s", strtotime($row_commentaire['ch_com_date'])); ?></small>
          <p><?= htmlPurify($row_commentaire['ch_com_contenu']) ?></p>
          <form class="" action="page-monument.php?ch_pay_id=<?= e($row_commentaire['ch_use_paysID']) ?>#diplomatie" method="post">
            <button class="btn btn-primary" type="submit">Afficher son profil</button>
          </form>
        </div>
      </li>
      <?php } while ($row_commentaire = mysql_fetch_assoc($commentaire)); ?>
  </ul>
  <!-- Message si pas de reactions -->
  <?php } else { ?>
  <?php if (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 1)) { ?>
  <!-- si periode historique -->
  <p>Cette p&eacute;riode n'a pas encore suscit&eacute;e de r&eacute;actions.</p>
  <?php } elseif (($row_fait_his['ch_his_date_fait2'] != NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
  <!-- si pers historique -->
  <p>Ce personnage n'a pas encore suscit&eacute; de r&eacute;actions.</p>
  <?php } elseif (($row_fait_his['ch_his_date_fait2'] == NULL) AND ($row_fait_his['ch_his_personnage'] == 2)) { ?>
  <!-- si pers vivant -->
  <p>Ce personnage n'a pas encore suscit&eacute; de r&eacute;actions.</p>
  <?php } else { ?>
  <!-- si fait historique -->
  <p>Cet &eacute;v&eacute;nement n'a pas encore suscit&eacute; de r&eacute;actions.</p>
  <?php } ?>
  <?php } ?>
</div>
<div class="modal-footer"> 
  <!-- NOUVEAU COMMENTAIRE SI CONNECTE -->
  <?php if ($_SESSION['connect']) { ?>
  <a href="page-fait-historique.php?ch_his_id=<?= e($row_fait_his['ch_his_id']) ?>" class="btn btn-danger" ><i class="icon-pencil icon-white"></i> Réagir</a>
  <?php } ?>
  <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>
