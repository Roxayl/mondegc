<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = isset($_GET['group_id']) ? 'edit' : 'add';

if($action === 'edit') {
    $infraGroup = new \GenCity\Monde\Temperance\InfraGroup($_GET['group_id']);
} else {
    $infraGroup = new \GenCity\Monde\Temperance\InfraGroup(null);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "infra_group_modal")) {

    if($action === 'edit') {
        $editInfraGroup = new \GenCity\Monde\Temperance\InfraGroup($_POST['form']);
        $editInfraGroup->set('created', $infraGroup->get('created'));
        $validate = $editInfraGroup->validate();
        $oldInfraGroup = new \GenCity\Monde\Temperance\InfraGroup($_POST['form']['id']);
        if(empty($validate)) {
            $editInfraGroup->update();
            \GenCity\Monde\Logger\Log::createItem('infrastructures_groupes', $editInfraGroup->get('id'), 'update', null, array('entity' => $editInfraGroup->model->getInfo(), 'old_entity' => $oldInfraGroup->model->getInfo()));
            getErrorMessage('success', "Ce groupe d'infrastructures a été modifié avec succès !");
        } else {
            getErrorMessage('error', $validate);
        }
    }

    else {
        $addInfraGroup = new \GenCity\Monde\Temperance\InfraGroup($_POST['form']);
        $validate = $addInfraGroup->validate();
        if(empty($validate)) {
            $addInfraGroup->create();
            \GenCity\Monde\Logger\Log::createItem('infrastructures_groupes', $addInfraGroup->get('id'), 'insert', null, array('entity' => $addInfraGroup->model->getInfo()));
            getErrorMessage('success', "Un groupe d'infrastructures a été créé avec succès !");
        } else {
            getErrorMessage('error', $validate);
        }
    }

    $updateGoTo = DEF_URI_PATH . "back/institut_economie.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    $adresse = $updateGoTo .'#groupe-infra';
    header(sprintf("Location: %s", $adresse));
    exit;
}

?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="infra_group_modal" method="POST" class="form-horizontal" id="infra_group_modal_form">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><?= $action === 'add' ? "Ajouter" : "Modifier" ?> un groupe d'infrastructures</h3>
  </div>

  <div class="modal-body">
    <!-- Boutons cachés -->
    <?php if($action === 'edit'): ?>
        <input type="hidden" id="form[id]" name="form[id]" value="<?= $infraGroup->get('id') ?>">
    <?php endif; ?>

    <!-- Nom -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="form[nom_groupe]">Nom du groupe </label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="form[nom_groupe]" name="form[nom_groupe]"
               value="<?= $infraGroup->get('nom_groupe') ?>" maxlength="50" />
        <br />
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span>
      <span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res min.</span></div>
    </div>

    <!-- Icone-->
    <div id="sprytextfield2" class="control-group">
      <label class="control-label" for="form[url_image]">Image de fond</label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="form[url_image]" name="form[url_image]"
               value="<?= $infraGroup->get('url_image') ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>

    <!-- Ordre -->
    <div id="sprytextfield3" class="control-group">
      <label class="control-label" for="form[order]">Ordre</label>
      <div class="controls">
        <input class="input-medium" type="text" id="form[order]" name="form[order]"
               value="<?= $infraGroup->get('order') ?>">
          <small><i class="icon-asterisk"></i> Les groupes sont triés par ordre croissant.</small>
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
  </div>

  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>

  <input type="hidden" name="MM_update" value="infra_group_modal">

</form>

<script>
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:50, validateOn:["change"], minChars:2});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {validateOn:["change"], isRequired:true});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"], isRequired:true, useCharacterMasking:true});
</script>