<?php

header('Content-Type: text/html; charset=utf-8');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-inf_off")) {

    $oldInfraOff = new \GenCity\Monde\Temperance\InfraOfficielle($_POST['ch_inf_off_id']);

    $updateSQL = sprintf("UPDATE infrastructures_officielles SET ch_inf_off_label=%s, ch_inf_off_date=%s, ch_inf_off_nom=%s, ch_inf_off_desc=%s, ch_inf_off_icone=%s, ch_inf_off_budget=%s, ch_inf_off_Industrie=%s, ch_inf_off_Commerce=%s, ch_inf_off_Agriculture=%s, ch_inf_off_Tourisme=%s, ch_inf_off_Recherche=%s, ch_inf_off_Environnement=%s, ch_inf_off_Education=%s WHERE ch_inf_off_id=%s",
        escape_sql($_POST['ch_inf_off_label'], "text"),
        escape_sql($_POST['ch_inf_off_date'], "date"),
        escape_sql($_POST['ch_inf_off_nom'], "text"),
        escape_sql($_POST['ch_inf_off_desc'], "text"),
        escape_sql($_POST['ch_inf_off_icone'], "text"),
        escape_sql($_POST['ch_inf_off_budget'], "int"),
        escape_sql($_POST['ch_inf_off_Industrie'], "int"),
        escape_sql($_POST['ch_inf_off_Commerce'], "int"),
        escape_sql($_POST['ch_inf_off_Agriculture'], "int"),
        escape_sql($_POST['ch_inf_off_Tourisme'], "int"),
        escape_sql($_POST['ch_inf_off_Recherche'], "int"),
        escape_sql($_POST['ch_inf_off_Environnement'], "int"),
        escape_sql($_POST['ch_inf_off_Education'], "int"),
        escape_sql($_POST['ch_inf_off_id'], "int"));

    $Result1 = mysql_query($updateSQL, $maconnexion);

    $newInfraOff = new \GenCity\Monde\Temperance\InfraOfficielle($_POST['ch_inf_off_id']);

    // Gestion des groupes d'infrastructures
    $delete_group = mysql_query('DELETE FROM infrastructures_officielles_groupes WHERE ID_infra_officielle = ' . mysql_real_escape_string($_POST['ch_inf_off_id']));
    $insert_group = mysql_query(sprintf('INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle) VALUES(%s, %s)',
        escape_sql($_POST['groupe_infra']),
        escape_sql($_POST['ch_inf_off_id'])
    ));

    \GenCity\Monde\Logger\Log::createItem('infrastructures_officielles', (int)$_POST['ch_inf_off_id'],
        'update', null,
        array('entity' => $newInfraOff->model->getInfo(), 'old_entity' => $oldInfraOff->model->getInfo()));

    // Recalculer les influences des infrastructures
    $eloquentInfrastructures = \Roxayl\MondeGC\Models\Infrastructure
        ::where('ch_inf_off_id', $newInfraOff->get('ch_inf_off_id'))->get();
    foreach($eloquentInfrastructures as $infrastructure) {
        $infrastructure->generateInfluence();
    }

    getErrorMessage('success', "Une infrastructure officielle a été modifiée !");

    $updateGoTo = DEF_URI_PATH . "back/institut_economie.php";
    appendQueryString($updateGoTo);
    $adresse = $updateGoTo . '#liste-infrastructures-officielles';
    header(sprintf("Location: %s", $adresse));
    exit;
}




//requete infrastructure a modifier

$colname_infra_officielles = "-1";
if (isset($_GET['infrastructure_off'])) {
  $colname_infra_officielles = $_GET['infrastructure_off'];
}

$query_infra_officielles = sprintf("SELECT * FROM infrastructures_officielles WHERE ch_inf_off_id = %s", escape_sql($colname_infra_officielles, "int"));
$infra_officielles = mysql_query($query_infra_officielles, $maconnexion);
$row_infra_officielles = mysql_fetch_assoc($infra_officielles);
$totalRows_infra_officielles = mysql_num_rows($infra_officielles);

// Obtenir tous les groupes d'infrastructures.
$query_infra_group = 'SELECT * FROM infrastructures_groupes';
$infra_group = mysql_query($query_infra_group, $maconnexion);

// Voir si l'infra en question fait partie d'un groupe.
$query_infra_officielles_group = 'SELECT * FROM infrastructures_officielles_groupes
  WHERE ID_infra_officielle = ' . mysql_real_escape_string($colname_infra_officielles);
$infra_officielles_group = mysql_query($query_infra_officielles_group);
$row_infra_officielles_group = mysql_fetch_assoc($infra_officielles_group);
$selected_infra_group = 0;
if(!empty($row_infra_officielles_group)) {
    $selected_infra_group = $row_infra_officielles_group['ID_groupes'];
}

?>

<!-- Modal Header-->

<form action="<?= e($editFormAction) ?>" name="ajout-inf_off" method="POST" class="form-horizontal" id="ajout-inf_off">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modifier une infrastructure officielle</h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cachés -->
    <?php $now= date("Y-m-d G:i:s");?>
    <input name="ch_inf_off_id" type="hidden" value="<?= e($row_infra_officielles['ch_inf_off_id']) ?>">
    <input name="ch_inf_off_label" type="hidden" value="<?= e($row_infra_officielles['ch_inf_off_label']) ?>">
    <input name="ch_inf_off_date" type="hidden" value="<?php echo $now; ?>">
    <!-- Nom -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="ch_inf_off_nom">Nom de l'infrastructure </label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="ch_inf_off_nom" name="ch_inf_off_nom" value="<?= e($row_infra_officielles['ch_inf_off_nom']) ?>" maxlength="50" />
        <br />
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span>
      <span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res min.</span></div>
    </div>
    <!-- Icone-->
    <div id="sprytextfield2" class="control-group">
      <label class="control-label" for="ch_inf_off_icone">Lien vers une icone </label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="ch_inf_off_icone" name="ch_inf_off_icone" value="<?= e($row_infra_officielles['ch_inf_off_icone']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Groupe d'infrastructure -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="groupe_infra">Groupe d'infrastructure</label>
      <div class="controls">
          <select name="groupe_infra" id="groupe_infra">
              <?php while($row_infra_group = mysql_fetch_assoc($infra_group)): ?>
                <option value="<?= e($row_infra_group['id']) ?>" <?= $selected_infra_group == $row_infra_group['id'] ? 'selected' : '' ?>><?= __s($row_infra_group['nom_groupe']) ?>
                </option>
              <?php endwhile; ?>
          </select>
        <br /></div >
    </div>
    <!-- Règles -->
    <div id="sprytextarea1" class="control-group">
      <label class="control-label" for="ch_inf_off_desc">Règles </label>
      <div class="controls">
        <textarea rows="4" name="ch_inf_off_desc" class="input-xxlarge" id="ch_inf_off_desc"><?= htmlPurify($row_infra_officielles['ch_inf_off_desc']) ?></textarea>
        <br />
        <span class="textareaMaxCharsMsg">2000 caract&egrave;res max.</span><span class="textareaRequiredMsg">Une valeur est requise.</span></div>
    </div>

    <h3>Influence sur l'économie</h3>
    <div class="row-fluid">
    <div class="span6">
     <!-- Ressource1 -->
    <div id="sprytextfield3" class="control-group">
      <label class="control-label" for="ch_inf_off_budget">Budget </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_budget" name="ch_inf_off_budget" value="<?= e($row_infra_officielles['ch_inf_off_budget']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource2 -->
    <div id="sprytextfield4" class="control-group">
      <label class="control-label" for="ch_inf_off_Industrie">Industrie </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Industrie" name="ch_inf_off_Industrie" value="<?= e($row_infra_officielles['ch_inf_off_Industrie']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource3 -->
    <div id="sprytextfield5" class="control-group">
      <label class="control-label" for="ch_inf_off_Commerce">Commerce </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Commerce" name="ch_inf_off_Commerce" value="<?= e($row_infra_officielles['ch_inf_off_Commerce']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource4 -->
    <div id="sprytextfield6" class="control-group">
      <label class="control-label" for="ch_inf_off_Agriculture">Agriculture </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Agriculture" name="ch_inf_off_Agriculture" value="<?= e($row_infra_officielles['ch_inf_off_Agriculture']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    </div>
    <div class="span6">
    <!-- Ressource5 -->
    <div id="sprytextfield7" class="control-group">
      <label class="control-label" for="ch_inf_off_Tourisme">Tourisme </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Tourisme" name="ch_inf_off_Tourisme" value="<?= e($row_infra_officielles['ch_inf_off_Tourisme']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
     <!-- Ressource6 -->
    <div id="sprytextfield8" class="control-group">
      <label class="control-label" for="ch_inf_off_Recherche">Recherche </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Recherche" name="ch_inf_off_Recherche" value="<?= e($row_infra_officielles['ch_inf_off_Recherche']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
     <!-- Ressource7 -->
    <div id="sprytextfield9" class="control-group">
      <label class="control-label" for="ch_inf_off_Environnement">Environnement </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Environnement" name="ch_inf_off_Environnement" value="<?= e($row_infra_officielles['ch_inf_off_Environnement']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource8 -->
    <div id="sprytextfield10" class="control-group">
      <label class="control-label" for="ch_inf_off_Education">Education </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Education" name="ch_inf_off_Education" value="<?= e($row_infra_officielles['ch_inf_off_Education']) ?>">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    </div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
  </div>
  <input type="hidden" name="MM_update" value="ajout-inf_off">
</form>
<script>
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:50, validateOn:["change"], minChars:2});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {validateOn:["change"], isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "integer", {validateOn:["change"], isRequired:false, useCharacterMasking:true});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:2000, validateOn:["change"], useCharacterMasking:false});
</script>
