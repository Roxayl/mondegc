<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

$ch_temp_id = -1 ;
if (isset ($_GET['ch_temp_id'])){
	$ch_temp_id = $_GET['ch_temp_id'];
	}


$query_temperance = sprintf("SELECT * FROM temperance WHERE ch_temp_id = %s", GetSQLValueString($ch_temp_id, "int"));
$query_limit_temperance = sprintf("%s LIMIT %d, %d", $query_temperance, $startRow_temperance, $maxRows_temperance);
$temperance = mysql_query($query_temperance, $maconnexion) or die(mysql_error());
$row_temperance = mysql_fetch_assoc($temperance);
?>

<!-- Modal Header-->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
  <h3 id="myModalLabel">Supprimer cette temp&eacute;rance</h3>
</div>
<div class="modal-body">
  <div class="row-fluid">
      <h1>Attention&nbsp;!</h1>
      <p>Souhaitez-vous r&eacute;ellement supprimer cette temp&eacute;rance?</p>
  <div class="alert alert-danger">
      <p><i class="icon-warning-sign"></i> Cette action supprimera d&eacute;finitivement toute la proc&eacute;dure ainsi que les jugements et les notes affili&eacute;es. N'oubliez pas d'informer le joueur de cette situation.</p>
      </div>
  </div>
</div>
<div class="modal-footer">
  <form action="temperance_supprimer.php" name="supprimer-temperance" method="POST" id="supprimer-temperance">
    <input name="ch_temp_id" type="hidden" value="<?php echo $row_temperance['ch_temp_id']; ?>">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
  </form>
</div>
