<?php

header('Content-Type: text/html; charset=utf-8');


// *** Requête monument.
$colname_monument = "-1";
if (isset($_GET['ch_pat_id'])) {
  $colname_monument = $_GET['ch_pat_id'];
}

$query_monument = sprintf("SELECT ch_pat_id, ch_pat_label, ch_pat_statut, ch_pat_paysID, ch_pat_villeID, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description, ch_pay_nom, ch_vil_nom, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);


// *** Requête commentaires.
$ch_com_categorie = "com_monument";
$ch_com_element_id = GetSQLValueString($colname_monument, "int");


$query_commentaire = "SELECT ch_com_ID, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_use_paysID, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = 'com_monument' AND ch_com_element_id = '$ch_com_element_id' ORDER BY ch_com_date DESC";
$commentaire = mysql_query($query_commentaire, $maconnexion) or die(mysql_error());
$row_commentaire = mysql_fetch_assoc($commentaire);
$totalRows_commentaire = mysql_num_rows($commentaire);

// *** Ressources patrimoine
$query_monument_ressources = sprintf("SELECT SUM(ch_mon_cat_budget) AS budget,SUM(ch_mon_cat_industrie) AS industrie, SUM(ch_mon_cat_commerce) AS commerce, SUM(ch_mon_cat_agriculture) AS agriculture, SUM(ch_mon_cat_tourisme) AS tourisme, SUM(ch_mon_cat_recherche) AS recherche, SUM(ch_mon_cat_environnement) AS environnement, SUM(ch_mon_cat_education) AS education FROM monument_categories
  INNER JOIN dispatch_mon_cat ON dispatch_mon_cat.ch_disp_cat_id = monument_categories.ch_mon_cat_ID
  INNER JOIN patrimoine ON ch_pat_id = ch_disp_mon_id WHERE ch_pat_id = %s", GetSQLValueString($colname_monument, "int"));
$monument_ressources = mysql_query($query_monument_ressources, $maconnexion) or die(mysql_error());
$row_monument_ressources = mysql_fetch_assoc($monument_ressources);


// *** Requête pour infos sur les categories.
$listcategories = ($row_monument['listcat']);
			if ($row_monument['listcat']) {
          

$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
}

$thisPays = new \GenCity\Monde\Pays($row_monument['ch_pat_paysID']);

?>

<!-- Modal Header-->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <!-- Titre-->
  <div class="titre-vert">
    <h1><?php echo $row_monument['ch_pat_nom']; ?><br>
    <small style="font-size: 20px;">Un monument au <?= __s($thisPays->get('ch_pay_nom')) ?></small></h1>
  </div>
</div>
<!-- Modal BODY-->

<div class="modal-body corps-page"> 
  <!-- Titre  et Carousel
    ================================================== -->
  <div class="">
    <?php if ($row_monument['ch_pat_lien_img1'] OR $row_monument['ch_pat_lien_img2'] OR $row_monument['ch_pat_lien_img3'] OR $row_monument['ch_pat_lien_img4'] OR $row_monument['ch_pat_lien_img5']) { ?>
    <section id="Carousel-monument" class="carousel slide">
      <div class="carousel-inner">
        <?php if ($row_monument['ch_pat_lien_img1']) { ?>
        <div class="item active" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img1']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img1']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img2']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img2']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img2']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img3']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img3']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img3']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img4']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img4']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img4']; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if ($row_monument['ch_pat_lien_img5']) { ?>
        <div class="item" style="background-image: url(<?php echo $row_monument['ch_pat_lien_img5']; ?>)">
          <div class="carousel-caption">
            <p><?php echo $row_monument['ch_pat_legende_img5']; ?></p>
          </div>
        </div>
        <?php } ?>
      </div>
      <a class="left carousel-control" href="#Carousel-monument" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#Carousel-monument" data-slide="next">&rsaquo;</a> </section>
    <!-- Titre si pas de carrousel
    ================================================== -->
    <?php } else { ?>
    <h1><?php echo $row_monument['ch_pat_nom']; ?></h1>
    <?php } ?>
  </div>
  <div class="well">
    <div class="row-fluid">
      <div class="span8">
        <p><strong>Pays&nbsp;:</strong> <img src="<?= __s($thisPays->get('ch_pay_lien_imgdrapeau')) ?>" class="img-menu-drapeau"> <a class="" href="page-pays.php?ch_pay_id=<?php echo $row_monument['ch_pat_paysID']; ?>"><?php echo $row_monument['ch_pay_nom']; ?></a></p>
        <p><strong>Ville&nbsp;:</strong> <a class="" href="page-ville.php?ch_pay_id=<?php echo $row_monument['ch_pat_paysID']; ?>&ch_ville_id=<?php echo $row_monument['ch_pat_villeID']; ?>"><?php echo $row_monument['ch_vil_nom']; ?></a></p>
        <p><?php echo $row_monument['ch_pat_description']; ?></p>
        <!-- Liste des categories di monument -->
        <p><strong>Cat&eacute;gories&nbsp;:</strong></p>
        <?php if ($row_liste_mon_cat3) { ?>
        <ul class="listes">
          <?php do { ?>
            <li class="row-fluid icone-categorie">
              <div class="span1"><img src="<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>" alt="icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>" style="background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>;"></div>
              <div class="span8">
                <p><strong><a href="patrimoine.php?mon_catID=<?php echo $row_liste_mon_cat3['ch_mon_cat_ID']; ?>#monument"><?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?></a></strong></p>
              </div>
            </li>
            <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); ?>
        </ul>

        <?php mysql_free_result($liste_mon_cat3); ?>
        <?php } else { ?>
        <p>Ce monument ne fait partie d'aucune cat&eacute;gorie.</p>
        <?php }?>
          <br>
        <p><strong>Influence sur l'économie :</strong></p>
          <?php
            renderElement('Temperance/resources_small', array(
                'resources' => $row_monument_ressources
            ));
          ?>
          <div class="clearfix"></div>
      </div>
      <div class="span4">
        <iframe width="100%" height="300px" frameborder="0" scrolling="no" src="<?= DEF_URI_PATH ?>Iframeposition.php?x=<?= __s($row_monument['ch_pat_coord_X']) ?>&y=<?= __s($row_monument['ch_pat_coord_Y']) ?>" name="iframe"></iframe>
      </div>
    </div>
  </div>
  <!-- REACTIONS -->
  <div id="commentaires" class="titre-vert">
    <h1>Visites</h1>
  </div>
  <?php if ($row_commentaire) { ?>
  <ul class="listes">
    <?php do { ?>
      <li class="row-fluid" id="commentaireID<?php echo $row_commentaire['ch_com_ID']; ?>"> 
        <div class="span3 img-listes img-avatar"> <img src="<?php echo $row_commentaire['ch_use_lien_imgpersonnage']; ?>"> </div>
        <div class="span9 info-listes">
        <!-- AFFICHAGE OUTILS MODERATION -->
        <div class="pull-right">
          <?php if (($_SESSION['statut'] >= 20) OR ($_SESSION['user_ID'] == $row_commentaire['ch_com_user_id'])) { ?>
          <form class="pull-right" action="back/communique_confirmation_supprimer.php" method="post">
            <input name="communique_ID" type="hidden" value="<?php echo $row_commentaire['ch_com_ID']; ?>">
            <button class="btn" type="submit" title="supprimer le commentaire"><i class="icon-trash"></i></button>
          </form>
          <form class="pull-right" action="back/communique_modifier.php" method="post">
            <input name="com_id" type="hidden" value="<?php echo $row_commentaire['ch_com_ID']; ?>">
            <button class="btn" type="submit" title="modifier le commentaire"><i class="icon-pencil"></i></button>
          </form>
          <?php } ?>
        </div>
          <h4><?php echo $row_commentaire['ch_use_predicat_dirigeant']; ?> <?php echo $row_commentaire['ch_use_prenom_dirigeant']; ?> <?php echo $row_commentaire['ch_use_nom_dirigeant']; ?></h4>
          <h5><?php echo $row_commentaire['ch_use_titre_dirigeant']; ?></h5>
          <!-- AFFICHAGE DATE --> 
          <small>Le <?php echo date("d/m/Y", strtotime($row_commentaire['ch_com_date'])); ?> &agrave; <?php echo date("G:i:s", strtotime($row_commentaire['ch_com_date'])); ?></small>
          <p><?php echo $row_commentaire['ch_com_contenu']; ?></p>
          <form class="" action="page-monument.php?ch_pay_id=<?php echo $row_commentaire['ch_use_paysID']; ?>#diplomatie" method="post">
            <button class="btn btn-primary" type="submit">Afficher son profil</button>
          </form>
        </div>
      </li>
      <?php } while ($row_commentaire = mysql_fetch_assoc($commentaire)); ?>
  </ul>
  <!-- Message si pas de reactions -->
  <?php } else { ?>
  <p>Ce monument n'as pas encore eu de visites</p>
  <?php } ?>
</div>
<div class="modal-footer"> 
  <!-- NOUVEAU COMMENTAIRE SI CONNECTE -->
  
  <?php if ($_SESSION['connect']) { ?>
  <a href="page-monument.php?ch_pat_id=<?php echo $row_monument['ch_pat_id']; ?>" class="btn btn-danger" ><i class="icon-pencil icon-white"></i> Réagir</a>
  <?php } ?>
  <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>
<?php
mysql_free_result($monument);
mysql_free_result($commentaire);
?>
