
<?php
// *** Requête commentaires.
$maxRows_commentaire = 10;
$pageNum_commentaire = 0;
if (isset($_GET['pageNum_commentaire'])) {
  $pageNum_commentaire = $_GET['pageNum_commentaire'];
}
$startRow_commentaire = $pageNum_commentaire * $maxRows_commentaire;

mysql_select_db($database_maconnexion, $maconnexion);
$query_commentaire = sprintf("SELECT ch_com_ID, ch_com_label, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_use_paysID, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_com_user_id = ch_use_id WHERE ch_com_categorie = %s AND ch_com_element_id = %s ORDER BY ch_com_date DESC", GetSQLValueString($ch_com_categorie, "text"), GetSQLValueString($ch_com_element_id, "int"));
$query_limit_commentaire = sprintf("%s LIMIT %d, %d", $query_commentaire, $startRow_commentaire, $maxRows_commentaire);
$commentaire = mysql_query($query_limit_commentaire, $maconnexion) or die(mysql_error());
$row_commentaire = mysql_fetch_assoc($commentaire);

if (isset($_GET['totalRows_commentaire'])) {
  $totalRows_commentaire = $_GET['totalRows_commentaire'];
} else {
  $all_commentaire = mysql_query($query_commentaire);
  $totalRows_commentaire = mysql_num_rows($all_commentaire);
}
$totalPages_commentaire = ceil($totalRows_commentaire/$maxRows_commentaire)-1;

$queryString_commentaire = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_commentaire") == false && 
        stristr($param, "totalRows_commentaire") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_commentaire = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_commentaire = sprintf("&totalRows_commentaire=%d%s", $totalRows_commentaire, $queryString_commentaire);
?>

<!-- REACTIONS -->
<?php if ($row_commentaire) { ?>
<ul class="listes listes-visiteurs">
  <?php do { ?>
  <li class="row-fluid anchor" id="commentaireID<?php echo $row_commentaire['ch_com_ID']; ?>"> 
    <div>
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
      <a class="btn btn-primary" href="page-pays.php?ch_pay_id=<?php echo $row_commentaire['ch_use_paysID']; ?>#diplomatie">Afficher son profil</a> </div>
      </div>
  </li>
  <?php } while ($row_commentaire = mysql_fetch_assoc($commentaire)); ?>
</ul>
<!-- PAGINATION --> 
<small class="pull-right">de <?php echo ($startRow_commentaire + 1) ?> &agrave; <?php echo min($startRow_commentaire + $maxRows_commentaire, $totalRows_commentaire) ?> sur <?php echo $totalRows_commentaire ?>
<?php if ($pageNum_commentaire > 0) { // Show if not first page ?>
<a class="btn" href="<?php printf("%s?pageNum_commentaire=%d%s#commentaires", $currentPage, max(0, $pageNum_commentaire - 1), $queryString_commentaire); ?>"><i class="icon-backward"></i></a>
<?php } // Show if not first page ?>
<?php if ($pageNum_commentaire < $totalPages_commentaire) { // Show if not last page ?>
<a class="btn" href="<?php printf("%s?pageNum_commentaire=%d%s#commentaires", $currentPage, min($totalPages_commentaire, $pageNum_commentaire + 1), $queryString_commentaire); ?>"> <i class="icon-forward"></i></a>
<?php } // Show if not last page ?>
</small>
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
<!-- NOUVEAU COMMENTAIRE SI CONNECTE -->
<?php if ($_SESSION['connect']) { ?>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <h3>Ecrire un commentaire</h3>
<ul id="EcrireCommentaire" class="listes listes-visiteurs">
  <li class="row-fluid">
    <div class="span3 img-listes img-avatar"> <img src="<?php echo $_SESSION['img_dirigeant']; ?>"> </div>
    <div class="span9 info-listes">
      <h4><?php echo $_SESSION['predicat_dirigeant']; ?> <?php echo $_SESSION['prenom_dirigeant']; ?> <?php echo $_SESSION['nom_dirigeant']; ?></h4>
      <h5><?php echo $_SESSION['titre_dirigeant']; ?></h5>
    </div>
    <p>&nbsp;</p>
  </li>
</ul>
<form action="" method="POST" name="ajout_communique" id="ajout_communique">
        <!-- Bouton cachés -->
        <?php 
				  $now= date("Y-m-d G:i:s");?>
        <input name="ch_com_label" type="hidden" value="communique">
        <input name="ch_com_categorie" type="hidden" value="<?php echo $ch_com_categorie ?>">
        <input name="ch_com_element_id" type="hidden" value="<?php echo $ch_com_element_id ?>">
        <input name="ch_com_user_id" type="hidden" value="<?php echo $_SESSION['user_ID'] ?>">
        <input name="ch_com_date" type="hidden" value="<?php echo $now; ?>">
        <input name="ch_com_date_mis_jour" type="hidden" value="<?php echo $now; ?>">
        <input name="ch_com_statut" type="hidden" value="1">
        <!-- Contenu -->
        <textarea rows="15" name="ch_com_contenu" class="wysiwyg" id="ch_com_contenu"></textarea>
        <p>&nbsp;</p>
        <button type="submit" class="btn btn-primary btn-margin-left">Envoyer</button>
        <input type="hidden" name="MM_insert" value="ajout_communique">
      </form>
<?php } ?>
<?php mysql_free_result($commentaire); ?>