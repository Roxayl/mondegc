<?php

header('Content-Type: text/html; charset=utf-8');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

$infraGroup = new \GenCity\Monde\Temperance\InfraGroup($_GET['group_id']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "infra_group_modal_suppr")) {

    $oldInfraGroup = new \GenCity\Monde\Temperance\InfraGroup($_GET['group_id']);
    $log_old_id = $oldInfraGroup->get('id');

    if($infraGroup->delete()) {
        \GenCity\Monde\Logger\Log::createItem('infrastructures_groupes', $log_old_id, 'delete',
            null, array('entity' => $oldInfraGroup->model->getInfo()));
        getErrorMessage('success',
            "Groupe d'infra supprimé ! Youhou, le pouvoir de la destruction !");
    } else {
        getErrorMessage("error", "Vous ne pouvez pas supprimer ce groupe
            d'infrastructure car il existe des <strong>infrastructures</strong> ou des
            <strong>infrastructures officielles</strong> appartenant à ce groupe d'infrastructures.
            Assurez-vous qu'aucune <strong>infrastructure</strong> ou <strong>infrastructure
            officielle</strong> n'est affiliée à ce groupe avant de continuer.");
    }

    $updateGoTo = DEF_URI_PATH . "back/institut_economie.php";
    appendQueryString($updateGoTo);
    $adresse = $updateGoTo .'#groupe-infra';
    header(sprintf("Location: %s", $adresse));
    exit;
}

?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="infra_group_modal" method="POST" class="form-horizontal" id="infra_group_modal_form">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Supprimer un groupe d'infrastructures</h3>
  </div>

  <div class="modal-body">
    <!-- Boutons cachés -->
    <input type="hidden" id="form[id]" name="form[id]" value="<?= $infraGroup->get('id') ?>">

    <p>Voulez-vous vraiment supprimer le groupe <strong><?= __s($infraGroup->get('nom_groupe')) ?></strong> ?</p>
  </div>

  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</button>
  </div>

  <input type="hidden" name="MM_update" value="infra_group_modal_suppr">

</form>