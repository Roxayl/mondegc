<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

$ch_inf_id = -1 ;
if (isset ($_GET['ch_inf_id'])){
	$ch_inf_id = $_GET['ch_inf_id'];
	}


$query_infrastructure = sprintf("SELECT * FROM infrastructures INNER JOIN infrastructures_officielles ON infrastructures.ch_inf_off_id=infrastructures_officielles.ch_inf_off_id WHERE ch_inf_id = %s ORDER BY ch_inf_date DESC", GetSQLValueString($ch_inf_id, "int"));
$query_limit_infrastructure = sprintf("%s LIMIT %d, %d", $query_infrastructure, $startRow_infrastructure, $maxRows_infrastructure);
$infrastructure = mysql_query($query_infrastructure, $maconnexion) or die(mysql_error());
$row_infrastructure = mysql_fetch_assoc($infrastructure);
?>

<!-- Modal Header-->

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
  <h3 id="myModalLabel">Supprimer l'infrastructure <?php echo $row_infrastructure['ch_inf_off_nom']; ?></h3>
</div>
<div class="modal-body">
  <div class="row-fluid">
    <div class="span9">
      <h1>Attention&nbsp;!</h1>
      <p>Souhaitez-vous r&eacute;ellement enlever cette <?php echo $row_infrastructure['ch_inf_off_nom']; ?> de votre ville&nbsp;?</p>
      <p><i class="icon-warning-sign"></i> Cette suppression sera d&eacute;finitive. Elle pourrait affecter l'&eacute;conomie de votre ville et de votre pays.</p>
    </div>
    <div class="span3"> <img src="<?php echo $row_infrastructure['ch_inf_lien_image']; ?>" alt="image de votre construction"> </div>
  </div>
</div>
<div class="modal-footer">
  <form action="infrastructure_supprimer.php" name="supprimer-infrastructure" method="POST" id="supprimer-infrastructure">
    <input name="ch_inf_id" type="hidden" value="<?php echo $row_infrastructure['ch_inf_id']; ?>">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
    <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
  </form>
</div>
