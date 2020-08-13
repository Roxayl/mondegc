<?php

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-mem_groupegorie")) {
  $insertSQL = sprintf("INSERT INTO dispatch_mem_group (ch_disp_group_id, ch_disp_MG_label, ch_disp_mem_id, ch_disp_MG_date, ch_disp_mem_statut) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_disp_group_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_label'], "text"),
                       GetSQLValueString($_POST['ch_disp_mem_id'], "int"),
                       GetSQLValueString($_POST['ch_disp_MG_date'], "date"),
					   GetSQLValueString($_POST['ch_disp_mem_statut'], "date"));
					   

  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = DEF_URI_PATH . 'back/institut_politique.php?mem_groupID='. $row_mem_group['ch_mem_group_ID'] .'';
  appendQueryString($insertGoTo);
  $adresse = $insertGoTo .'#classer-membres';
  header(sprintf("Location: %s", $adresse));
 exit;
}



//requete listes faits hist
$colname_group_id = "-1";
if (isset($_GET['mem_groupID'])) {
  $colname_group_id = $_GET['mem_groupID'];
}

$query_info_membre = sprintf("SELECT ch_use_id, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_titre_dirigeant FROM users WHERE ch_use_id NOT IN (SELECT ch_disp_mem_id FROM dispatch_mem_group WHERE ch_disp_group_id = %s)  ORDER BY ch_use_last_log DESC", GetSQLValueString($colname_group_id, ""));
$info_membre = mysql_query($query_info_membre, $maconnexion) or die(mysql_error());
$row_info_membre = mysql_fetch_assoc($info_membre);
$totalRows_info_membre = mysql_num_rows($info_membre);


//requete info cat�gorie

$query_mem_group = sprintf("SELECT ch_mem_group_ID, ch_mem_group_nom FROM membres_groupes WHERE ch_mem_group_ID = %s", GetSQLValueString($colname_group_id, "int"));
$mem_group = mysql_query($query_mem_group, $maconnexion) or die(mysql_error());
$row_mem_group = mysql_fetch_assoc($mem_group);
$totalRows_mem_group = mysql_num_rows($mem_group);
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-mem_groupegorie" method="POST" class="form-horizontal" id="ajout-mem_groupegorie">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel">Ajouter un membre dans le groupe <?php echo $row_mem_group['ch_mem_group_nom']; ?></h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cach�s -->
    <?php 
				  $now= date("Y-m-d G:i:s");?>
    <input name="ch_disp_group_id" type="hidden" value="<?php echo $row_mem_group['ch_mem_group_ID']; ?>">
    <input name="ch_disp_MG_label" type="hidden" value="disp_mem">
    <input name="ch_disp_MG_date" type="hidden" value="<?php echo $now; ?>">
    <select name="ch_disp_mem_id" id="ch_disp_mem_id">
      <?php do { ?>
      <option value="<?php echo $row_info_membre['ch_use_id']; ?>"><?php echo $row_info_membre['ch_use_nom_dirigeant']; ?></option>
      <?php } while ($row_info_membre = mysql_fetch_assoc($info_membre)); ?>
    </select>
    <p>&nbsp;</p>
    <!-- Statut -->
          <div id="spryradio1" class="control-group">
            <div class="control-label">Statut <a href="#" rel="clickover" title="Statut du membre dans le groupe" data-content="
    membre simple : statut par d&eacute;faut.
    administrateur du groupe : un membre possedant ce statut pourra modifier le groupe, ajouter et supprimer d'autres membres."><i class="icon-info-sign"></i></a></div>
            <div class="controls">
              <label>
                <input type="radio" name="ch_disp_mem_statut" value="1" id="ch_disp_mem_statut_1" checked="checked">
                membre simple</label>
              <label>
                <input name="ch_disp_mem_statut" type="radio" id="ch_disp_mem_statut_2" value="2">
                administrateur du groupe</label>
              <span class="radioRequiredMsg">Choisissez un statut pour ce membre</span></div>
          </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_insert" value="ajout-mem_groupegorie">
</form>
