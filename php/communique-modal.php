<?php


require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=uft-8');

//Connexion BBD Communique
$colname_communique = "-1";
if (isset($_GET['com_id'])) {
  $colname_communique = $_GET['com_id'];}
mysql_select_db($database_maconnexion, $maconnexion);
$query_communique = sprintf("SELECT * FROM communiques WHERE ch_com_ID = %s", GetSQLValueString($colname_communique, "int"));
$communique = mysql_query($query_communique, $maconnexion) or die(mysql_error());
$row_communique = mysql_fetch_assoc($communique);
$totalRows_communique = mysql_num_rows($communique);
$cat = $row_communique['ch_com_categorie'];
$elementID = $row_communique['ch_com_element_id'];

//Connexion BBD Pour info sur l'institution emmitrice
if ( $cat == "pays") {
  mysql_select_db($database_maconnexion, $maconnexion);
$query_pays = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_devise, ch_pay_lien_imgdrapeau, ch_pay_lien_imgheader FROM pays WHERE ch_pay_id = %s", GetSQLValueString($elementID, "int"));
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);

$ch_com_categorie = $cat;
$ch_com_element_id = isset($colname_elementid) ?: 0;
$nom_organisation = $row_pays['ch_pay_nom'];
$insigne = $row_pays['ch_pay_lien_imgdrapeau'];
$soustitre = $row_pays['ch_pay_devise'];
$background_jumbotron = $row_pays['ch_pay_lien_imgheader'];
mysql_free_result($pays);

$thisPays = new \GenCity\Monde\Pays($elementID);
$personnage = \GenCity\Monde\Personnage::constructFromEntity($thisPays);
}

if ( $cat == "ville") {
  mysql_select_db($database_maconnexion, $maconnexion);
$query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_vil_specialite, ch_vil_armoiries, ch_pay_id, ch_pay_nom, ch_vil_lien_img1 FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id WHERE ch_vil_ID = %s", GetSQLValueString($elementID, "int"));
$villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);

$ch_com_categorie = $cat;
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_villes['ch_vil_nom'];
$insigne = $row_villes['ch_vil_armoiries'];
$soustitre = $row_villes['ch_pay_nom'];
$background_jumbotron = $row_villes['ch_vil_lien_img1'];
mysql_free_result($villes);
}


if ( $cat == "institut") {
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT ch_ins_ID, ch_ins_nom, ch_ins_sigle, ch_ins_logo FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($elementID, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

$ch_com_categorie = $cat;
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_institut['ch_ins_sigle'];
$insigne = $row_institut['ch_ins_logo'];
$soustitre = $row_institut['ch_ins_nom'];
$background_jumbotron = "assets/img/fond_haut-conseil.jpg";
mysql_free_result($institut);
}

//Connexion BBD user pour info sur l'auteur
$colname_user = "-1";
if (isset($row_communique['ch_com_user_id'])) {
  $colname_user = $row_communique['ch_com_user_id'];
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_user = sprintf("SELECT ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_login FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_user, "int"));
$user = mysql_query($query_user, $maconnexion) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

// *** Requête commentaires.
$ch_com_categorie = "com_communique";
$ch_com_element_id = $colname_communique;

mysql_select_db($database_maconnexion, $maconnexion);
$query_commentaire = sprintf("SELECT ch_com_ID, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_com_pays_id AS ch_use_paysID, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = %s AND ch_com_element_id = %s ORDER BY ch_com_date DESC", GetSQLValueString($ch_com_categorie, "text"), GetSQLValueString($ch_com_element_id, "int"));
$commentaire = mysql_query($query_commentaire, $maconnexion) or die(mysql_error());
$row_commentaire = mysql_fetch_assoc($commentaire);
$totalRows_commentaire = mysql_num_rows($commentaire);
?>
<!-- Modal Header-->
<div class="modal-header">
<div class="row-fluid"> 
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>
  <div class="row-fluid communique" style="background-color: #e6eaff; padding: 20px 0; margin-top: -30px;">
    <!-- EN-tête Auteur-->
    <?php if(isset($personnage)): ?>
      <!-- EN-tête Personnage pour communiquées officiels et commentaire-->
      <div class="span2 thumb"> <img src="<?= $personnage->get('lien_img') ?>" alt="photo <?= $personnage->get('nom_personnage') ?>">
        <div class="titre-gris">
          <p><?= $personnage->get('predicat') ?></p>
          <h3><?= $personnage->get('prenom_personnage') ?> <?= $personnage->get('nom_personnage') ?></h3>
          <small><?= $personnage->get('titre_personnage') ?></small> </div>
      </div>
    <?php endif; ?>
    <!-- EN-tête Institution-->
    <div class="offset8 span2 thumb">
      <?php if ( $cat == "ville") {?>
      <?php if ($insigne == NULL) {?>
      <img src="assets/img/imagesdefaut/blason.jpg" alt="armoirie">
      <?php } else { ?>
      <img src="<?php echo $insigne; ?>" alt="armoirie">
      <?php } ?>
      <?php } elseif ( $cat == "pays") {?>
      <?php if ($insigne == NULL) {?>
      <img src="assets/img/imagesdefaut/drapeau.jpg" alt="drapeau">
      <?php } else { ?>
      <img src="<?php echo $insigne; ?>" alt="drapeau">
      <?php } ?>
      <?php } elseif ( $cat == "institut") {?>
      <?php if ($insigne == NULL) {?>
      <img src="assets/img/imagesdefaut/blason.jpg" alt="logo">
      <?php } else { ?>
      <img src="<?php echo $insigne; ?>" alt="logo">
      <?php }
		 } else {?>
      <img src="<?php echo $insigne; ?>">
      <?php } ?>
      <div class="titre-gris">
        <h3><?php echo $nom_organisation; ?></h3>
        <small><?php echo $soustitre; ?></small> </div>
    </div>
  </div>
</div>
<!-- Modal BODY-->

<div class="modal-body corps-page">
<?php if ( $cat == "institut") {?>
  <div class="titre-bleu">
    <h1><?php echo $row_communique['ch_com_titre']; ?></h1>
  </div>
  <?php } else { ?>
  <div class="titre-vert">
    <h1><?php echo $row_communique['ch_com_titre']; ?></h1>
  </div>
  <?php } ?>
  <div class="well"><?php echo $row_communique['ch_com_contenu']; ?></div>
  
  <!-- REACTIONS -->
  <?php if ( $cat == "institut") {?>
  <div id="commentaires" class="titre-bleu anchor">
    <h1>R&eacute;actions</h1>
  </div>
  <?php } else { ?>
  <div id="commentaires" class="titre-vert anchor">
    <h1>R&eacute;actions</h1>
  </div>
  <?php } ?>
  <?php if ($row_commentaire) { ?>
  <ul class="listes">
    <?php do {

    $paysReaction = new \GenCity\Monde\Pays($row_commentaire['ch_use_paysID']);
    $persoReaction = \GenCity\Monde\Personnage::constructFromEntity($paysReaction);
    ?>

      <li class="row-fluid" id="commentaireID<?php echo $row_commentaire['ch_com_ID']; ?>"> 
        <!-- AFFICHAGE OUTILS MODERATION -->
        <div class="span2 img-listes"> <img src="<?php echo $row_commentaire['ch_use_lien_imgpersonnage']; ?>"> </div>
        <div class="span10 info-listes">
         <div class="pull-right">
          <?php if (($_SESSION['statut'] >= 20) OR ($_SESSION['user_ID'] == $row_commentaire['ch_com_user_id'])) { ?>
          <form class="pull-right" action="back/communique_confirmation_supprimer.php" method="post">
            <input name="communique-ID" type="hidden" value="<?php echo $row_commentaire['ch_com_ID']; ?>">
            <button class="btn" type="submit" title="supprimer le commentaire"><i class="icon-trash"></i></button>
          </form>
          <form class="pull-right" action="back/communique_modifier.php" method="post">
            <input name="com_id" type="hidden" value="<?php echo $row_commentaire['ch_com_ID']; ?>">
            <button class="btn" type="submit" title="modifier le commentaire"><i class="icon-pencil"></i></button>
          </form>
          <?php } ?>
        </div>

          <?php if(isset($persoReaction)): ?>
          <h4><?= __s($persoReaction->get('predicat')) ?> <?= __s($persoReaction->get('prenom_personnage')) ?> <?= __s($persoReaction->get('nom_personnage')) ?></h4>
          <h5>
              <?= isset($paysReaction) ? '<img class="img-menu-drapeau" src="' . __s($paysReaction->get('ch_pay_lien_imgdrapeau')) . '">
                ' . __s($paysReaction->get('ch_pay_nom')) . ' &#183; ' : '' ?>
              <?= __s($persoReaction->get('titre_personnage')) ?></h5>
          <!-- AFFICHAGE DATE -->
          <small>Le <?php echo date("d/m/Y", strtotime($row_commentaire['ch_com_date'])); ?> &agrave; <?php echo date("G:i:s", strtotime($row_commentaire['ch_com_date'])); ?></small>
          <p><?php echo $row_commentaire['ch_com_contenu']; ?></p>
          <a class="btn btn-primary" href="page-pays.php?ch_pay_id=<?php echo $row_commentaire['ch_use_paysID']; ?>#diplomatie">Afficher la page du pays</a>

          <?php else: ?>
          <h4><?php echo $row_commentaire['ch_use_predicat_dirigeant']; ?> <?php echo $row_commentaire['ch_use_prenom_dirigeant']; ?> <?php echo $row_commentaire['ch_use_nom_dirigeant']; ?></h4>
          <h5><?php echo $row_commentaire['ch_use_titre_dirigeant']; ?></h5>
          <!-- AFFICHAGE DATE -->
          <small>Le <?php echo date("d/m/Y", strtotime($row_commentaire['ch_com_date'])); ?> &agrave; <?php echo date("G:i:s", strtotime($row_commentaire['ch_com_date'])); ?></small>
          <p><?php echo $row_commentaire['ch_com_contenu']; ?></p>
          <a class="btn btn-primary" href="page-pays.php?ch_pay_id=<?php echo $row_commentaire['ch_use_paysID']; ?>#diplomatie">Afficher son profil</a>
          <?php endif; ?>

          <form class="" action="page-communique.php?ch_pay_id=<?php echo $row_commentaire['ch_use_paysID']; ?>#diplomatie" method="post">
            <button class="btn btn-primary" type="submit">Afficher son profil</button>
          </form>
        </div>
      </li>
      <?php } while ($row_commentaire = mysql_fetch_assoc($commentaire)); ?>
  </ul>
  <!-- Message si pas de reactions -->
  <?php } else { ?>
  <?php if ($ch_com_categorie == 'com_pays') { ?>
  <p>Ce pays n'a pas encore de visiteurs</p>
  <?php } else if ($ch_com_categorie == 'com_ville') { ?>
  <p>Cette ville n'a pas encore de visiteurs</p>
  <?php } else if ($ch_com_categorie == 'com_communique') { ?>
  <p>Ce communiqu&eacute; n'as pas encore suscit&eacute; de r&eacute;actions</p>
  <?php } else { 
} 
} ?>
</div>
<div class="modal-footer"> 
  <!-- NOUVEAU COMMENTAIRE SI CONNECTE -->
  <?php if ($_SESSION['connect']) { ?>
  <a href="page-communique.php?com_id=<?php echo $row_communique['ch_com_ID']; ?>" class="btn btn-danger" ><i class="icon-pencil icon-white"></i> Réagir</a>
  <?php } ?>
  <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Fermer</button>
</div>
<?php
mysql_free_result($communique);

mysql_free_result($user);

mysql_free_result($commentaire);?>
