<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);



//recuperation ID temperance

$colname_temperance = "-1";
if (isset($_GET['ch_temp_id'])) {
  $colname_temperance = $_GET['ch_temp_id'];
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "phase-temperance")) {
  $updateSQL = sprintf("UPDATE temperance SET ch_temp_statut=%s, ch_temp_mis_jour=%s WHERE ch_temp_id=%s",
                       GetSQLValueString($_POST['ch_temp_statut'], "int"),
                       GetSQLValueString($_POST['ch_temp_mis_jour'], "date"),
					   GetSQLValueString($_POST['ch_temp_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $updateGoTo = DEF_URI_PATH . "back/institut_economie.php";
  appendQueryString($updateGoTo);
  $adresse = $updateGoTo .'#liste-temperance';
  header(sprintf("Location: %s", $adresse));
 exit;
}
?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="phase-temperance" method="POST" class="form-horizontal" id="phase-temperance">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel">Ouverture des votes</h3>
  </div>
  <div class="modal-body"> 
    <p>Passer &agrave; la deuxi&egrave;me phase de la proc&eacute;dure permet de soumettre cet &eacute;lement aux juges temp&eacute;rants.</p>
    <div class="alert alert-danger"><p><i class="icon-warning-sign"></i> Cette action sera d&eacute;finitive.</p></div>
    <!-- Boutons cach�s -->
      <?php $now= date("Y-m-d G:i:s");?>
      <input name="ch_temp_statut" type="hidden" value="2">
      <input name="ch_temp_mis_jour" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_temp_id" type="hidden" value="<?php echo $colname_temperance; ?>">
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Valider</button>
  </div>
  <input type="hidden" name="MM_update" value="phase-temperance">
</form>