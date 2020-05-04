<?php
if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout-inf_off")) {
  $insertSQL = sprintf("INSERT INTO infrastructures_officielles (ch_inf_off_label, ch_inf_off_date, ch_inf_off_nom, ch_inf_off_desc, ch_inf_off_icone, ch_inf_off_budget, ch_inf_off_Industrie, ch_inf_off_Commerce, ch_inf_off_Agriculture, ch_inf_off_Tourisme, ch_inf_off_Recherche, ch_inf_off_Environnement, ch_inf_off_Education) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_inf_off_label'], "text"),
                       GetSQLValueString($_POST['ch_inf_off_date'], "date"),
                       GetSQLValueString($_POST['ch_inf_off_nom'], "text"),
                       GetSQLValueString($_POST['ch_inf_off_desc'], "text"),
                       GetSQLValueString($_POST['ch_inf_off_icone'], "text"),
                       GetSQLValueString($_POST['ch_inf_off_budget'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Industrie'], "int"),
					   GetSQLValueString($_POST['ch_inf_off_Commerce'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Agriculture'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Tourisme'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Recherche'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Environnement'], "int"),
                       GetSQLValueString($_POST['ch_inf_off_Education'], "int"));
  
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $last_id = mysql_insert_id();
  $insert_group = mysql_query(sprintf('INSERT INTO infrastructures_officielles_groupes(ID_groupes, ID_infra_officielle) VALUES(%s, %s)',
      GetSQLValueString($_POST['groupe_infra']),
      GetSQLValueString($last_id)
  ));

  $thisInfraOff = new \GenCity\Monde\Temperance\InfraOfficielle($last_id);
  \GenCity\Monde\Logger\Log::createItem('infrastructures_officielles', $thisInfraOff->get('ch_inf_off_id'),
      'insert', null, array('entity' => $thisInfraOff->model->getInfo()));

  getErrorMessage('success', "Une infrastructure officielle a �t� ajout�e !");

  $insertGoTo = DEF_URI_PATH . 'back/institut_economie.php';
  appendQueryString($insertGoTo);
  $adresse = $insertGoTo .'#liste-infrastructures-officielles';
  header(sprintf("Location: %s", $adresse));
 exit;
}

// Obtenir tous les groupes d'infrastructures.
$query_infra_group = 'SELECT * FROM infrastructures_groupes';
$infra_group = mysql_query($query_infra_group, $maconnexion);

?>

<!-- Modal Header-->

<form action="<?php echo $editFormAction; ?>" name="ajout-inf_off" method="POST" class="form-horizontal" id="ajout-inf_off">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
    <h3 id="myModalLabel">Ajouter une infrastructure dans la liste officielle</h3>
  </div>
  <div class="modal-body"> 
    <!-- Boutons cach�s -->
    <?php $now= date("Y-m-d G:i:s");?>
    <input name="ch_inf_off_label" type="hidden" value="inf_off">
    <input name="ch_inf_off_date" type="hidden" value="<?php echo $now; ?>">
    <!-- Nom -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="ch_inf_off_nom">Nom de l'infrastructure </label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="ch_inf_off_nom" name="ch_inf_off_nom" value="" maxlength="50" />
        <br />
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span>
      <span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res min.</span></div>
    </div>
    <!-- Icone-->
    <div id="sprytextfield2" class="control-group">
      <label class="control-label" for="ch_inf_off_icone">Lien vers une icone </label>
      <div class="controls">
        <input class="input-xxlarge" type="text" id="ch_inf_off_icone" name="ch_inf_off_icone" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Groupe d'infrastructure -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="groupe_infra">Groupe d'infrastructure</label>
      <div class="controls">
          <select name="groupe_infra" id="groupe_infra">
              <?php while($row_infra_group = mysql_fetch_assoc($infra_group)): ?>
                <option value="<?= $row_infra_group['id'] ?>"><?= __s($row_infra_group['nom_groupe']) ?>
                </option>
              <?php endwhile; ?>
          </select>
        <br /></div >
    </div>
    <!-- R�gles -->
    <div id="sprytextarea1" class="control-group">
      <label class="control-label" for="ch_inf_off_desc">R�gles </label>
      <div class="controls">
        <textarea rows="4" name="ch_inf_off_desc" class="input-xxlarge" id="ch_inf_off_desc"></textarea>
        <br />
        <span class="textareaMaxCharsMsg">2000 caract&egrave;res max.</span><span class="textareaRequiredMsg">Une valeur est requise.</span></div>
    </div>
    <h3>Influence sur l'�conomie</h3>
    <div class="row-fluid">
    <div class="span6">
     <!-- Ressource1 -->
    <div id="sprytextfield3" class="control-group">
      <label class="control-label" for="ch_inf_off_budget">Budget </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_budget" name="ch_inf_off_budget" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource2 -->
    <div id="sprytextfield4" class="control-group">
      <label class="control-label" for="ch_inf_off_Industrie">Industrie </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Industrie" name="ch_inf_off_Industrie" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource3 -->
    <div id="sprytextfield5" class="control-group">
      <label class="control-label" for="ch_inf_off_Commerce">Commerce </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Commerce" name="ch_inf_off_Commerce" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource4 -->
    <div id="sprytextfield6" class="control-group">
      <label class="control-label" for="ch_inf_off_Agriculture">Agriculture </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Agriculture" name="ch_inf_off_Agriculture" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    </div>
    <div class="span6">
    <!-- Ressource5 -->
    <div id="sprytextfield7" class="control-group">
      <label class="control-label" for="ch_inf_off_Tourisme">Tourisme </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Tourisme" name="ch_inf_off_Tourisme" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
     <!-- Ressource6 -->
    <div id="sprytextfield8" class="control-group">
      <label class="control-label" for="ch_inf_off_Recherche">Recherche </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Recherche" name="ch_inf_off_Recherche" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
     <!-- Ressource7 -->
    <div id="sprytextfield9" class="control-group">
      <label class="control-label" for="ch_inf_off_Environnement">Environnement </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Environnement" name="ch_inf_off_Environnement" value="">
        <br />
      <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Ressource8 -->
    <div id="sprytextfield10" class="control-group">
      <label class="control-label" for="ch_inf_off_Education">Education </label>
      <div class="controls">
        <input class="input-small" type="text" id="ch_inf_off_Education" name="ch_inf_off_Education" value="">
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
  <input type="hidden" name="MM_insert" value="ajout-inf_off">
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