<?php

//requete categories monuments

$colname_infra_officielles = "-1";
if (isset($_GET['infrastructure_off'])) {
  $colname_infra_officielles = $_GET['infrastructure_off'];
}

$query_infra_officielles = sprintf("SELECT * FROM infrastructures_officielles WHERE ch_inf_off_id = %s", GetSQLValueString($colname_infra_officielles, "int"));
$infra_officielles = mysql_query($query_infra_officielles, $maconnexion);
$row_infra_officielles = mysql_fetch_assoc($infra_officielles);
$totalRows_infra_officielles = mysql_num_rows($infra_officielles);
?>

<!-- Modal Header-->
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
<h3 id="myModalLabel">Supprimer <?= e($row_infra_officielles['ch_inf_off_nom']) ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
          <div class="span9">
          <h1>Attention&nbsp;!</h1>
    <p>Souhaitez-vous r&eacute;ellement supprimer cette infrastructure de la liste officielle&nbsp;?</p>
    <p><i class="icon-warning-sign"></i> Cette action sera irr&eacute;versible</p>
    </div>
    <div class="span3 icone-categorie"> 
          <img src="<?= e($row_infra_officielles['ch_inf_off_icone']) ?>" alt="icone infrastructure"></div>
    </div>
          </div>
          <div class="modal-footer">
<form action="infrastructure_officielle_supprimer.php" name="supprimer-infrastructure" method="POST" id="supprimer-infrastructure">
            <input name="ch_inf_off_id" type="hidden" value="<?= e($row_infra_officielles['ch_inf_off_id']) ?>">
<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
            <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
            </form>
          </div>
