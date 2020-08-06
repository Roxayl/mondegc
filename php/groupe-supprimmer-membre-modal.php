<?php

header('Content-Type: text/html; charset=iso-8859-1');


//requete categories monuments

$colname_ch_disp_MG_id = "-1";
if (isset($_GET['ch_disp_MG_id'])) {
  $colname_ch_disp_MG_id = $_GET['ch_disp_MG_id'];
}

$query_mon = sprintf("SELECT ch_disp_MG_id, ch_disp_mem_statut, ch_disp_group_id, ch_disp_mem_id, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant, ch_use_lien_imgpersonnage, ch_mem_group_nom, ch_mem_group_icon, ch_mem_group_couleur FROM dispatch_mem_group INNER JOIN users ON ch_disp_mem_id = ch_use_id INNER JOIN membres_groupes ON ch_disp_group_id = ch_mem_group_ID WHERE ch_disp_MG_id = %s", GetSQLValueString($colname_ch_disp_MG_id, "int"));
$mon = mysql_query($query_mon, $maconnexion) or die(mysql_error());
$row_mon = mysql_fetch_assoc($mon);
$totalRows_mon = mysql_num_rows($mon);

?>

<!-- Modal Header-->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
  <?php if ($row_mon['ch_disp_mem_statut'] == 3) { ?>
  <h3 id="myModalLabel">Refuser la demande de <?php echo $row_mon['ch_use_nom_dirigeant']; ?> d'int&eacute;grer groupe <?php echo $row_mon['ch_mem_group_nom']; ?> </h3>
  <?php } else { ?>
  <h3 id="myModalLabel">Supprimer <?php echo $row_mon['ch_use_nom_dirigeant']; ?> du groupe <?php echo $row_mon['ch_mem_group_nom']; ?> </h3>
  <?php } ?>
</div>
<div class="modal-body">
  <div class="row-fluid">
    <div class="span9">
      <h1>Attention&nbsp;!</h1>
      <p><i class="icon-warning-sign"></i> Souhaitez-vous r&eacute;ellement supprimer <?php echo $row_mon['ch_use_prenom_dirigeant']; ?> <?php echo $row_mon['ch_use_nom_dirigeant']; ?>, <em><?php echo $row_mon['ch_use_titre_dirigeant']; ?></em>, de ce Groupe&nbsp;?</p>
    </div>
    <div class="span3 icone-categorie"> <img src="<?php echo $row_mon['ch_mem_group_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_mon['ch_mem_group_couleur']; ?>;"> <img src="<?php echo $row_mon['ch_use_lien_imgpersonnage']; ?>" alt="image monument"></div>
  </div>
</div>
<div class="modal-footer">
  <form action="disp_membre_supprimer.php" name="supprimer-categorie" method="POST" id="supprimer-categorie">
    <input name="ch_disp_MG_id" type="hidden" value="<?php echo $row_mon['ch_disp_MG_id']; ?>">
    <input name="ch_disp_group_id" type="hidden" value="<?php echo $row_mon['ch_disp_group_id']; ?>">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
  </form>
</div>
<?php mysql_free_result($mon);?>
