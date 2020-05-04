<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

//requete dispatch
$colname_info_membre = "-1";
if (isset($_GET['ch_disp_MG_id'])) {
  $colname_info_membre = $_GET['ch_disp_MG_id'];
}

$query_info_dispatch = sprintf("SELECT * FROM dispatch_mem_group WHERE ch_disp_MG_id = %s", GetSQLValueString($colname_info_membre, "int"));
$info_dispatch = mysql_query($query_info_dispatch, $maconnexion) or die(mysql_error());
$row_info_dispatch = mysql_fetch_assoc($info_dispatch);
$totalRows_info_dispatch = mysql_num_rows($info_dispatch);


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-group")) {
  $updateSQL = sprintf("UPDATE dispatch_mem_group SET ch_disp_group_id=%s, ch_disp_MG_label=%s, ch_disp_mem_id=%s, ch_disp_MG_date=%s, ch_disp_mem_statut=%s WHERE ch_disp_MG_id=%s",
                       GetSQLValueString($_POST['ch_disp_group_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_label'], "text"),
                       GetSQLValueString($_POST['ch_disp_mem_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_date'], "date"),
                       GetSQLValueString($_POST['ch_disp_mem_statut'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_id'], "int"));
					   
  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = $_SESSION['last_work'];
  header(sprintf("Location: %s", $_SESSION['last_work']));
}
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-group" method="POST" class="form-horizontal" id="ajout-group">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <?php if ($row_info_dispatch['ch_disp_mem_statut'] == 3) { ?>
    <h3 id="myModalLabel">Choisir le statut de ce membre</h3>
    <?php } else { ?>
    <h3 id="myModalLabel">Modifier le statut de ce membre</h3>
    <?php } ?>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cach�s -->
    <?php 
	$now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_MG_id" type="hidden" value="<?php echo $row_info_dispatch['ch_disp_MG_id']; ?>">
    <input name="ch_disp_MG_label" type="hidden" value="<?php echo $row_info_dispatch['ch_disp_MG_label']; ?>">
    <input name="ch_disp_group_id" type="hidden" value="<?php echo $row_info_dispatch['ch_disp_group_id']; ?>">
    <input name="ch_disp_mem_id" type="hidden" value="<?php echo $row_info_dispatch['ch_disp_mem_id']; ?>">
    <input name="ch_disp_MG_date" type="hidden" value="<?php echo $now; ?>">
    <!-- Statut -->
    <div id="spryradio1" class="control-group">
      <div class="control-label">Statut <a href="#" rel="clickover" title="Statut du membre dans le groupe" data-content="
    membre simple : statut par d&eacute;faut.
    administrateur du groupe : un membre possedant ce statut pourra modifier le groupe, ajouter et supprimer d'autres membres."><i class="icon-info-sign"></i></a></div>
      <div class="controls">
        <label>
          <input <?php if (!(strcmp($row_info_dispatch['ch_disp_mem_statut'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_disp_mem_statut" value="1" id="ch_disp_mem_statut_1">
          membre simple</label>
        <label>
          <input <?php if (!(strcmp($row_info_dispatch['ch_disp_mem_statut'],"2"))) {echo "checked=\"checked\"";} ?> name="ch_disp_mem_statut" type="radio" id="ch_disp_mem_statut_2" value="2">
          administrateur du groupe</label>
        <span class="radioRequiredMsg">Choisissez un statut pour ce membre</span></div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_update" value="ajout-group">
</form>
<?php
mysql_free_result($info_dispatch);?>
