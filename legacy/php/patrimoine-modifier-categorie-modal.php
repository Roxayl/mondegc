<?php

use Roxayl\MondeGC\Events\Patrimoine\PatrimoineCategorized;
use Roxayl\MondeGC\Models\MonumentCategory;

header('Content-Type: text/html; charset=utf-8');


$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-categorie")) {

    $eloquentCategory = MonumentCategory::query()->findOrFail($_POST['ch_mon_cat_ID']);

    $updateSQL = sprintf("UPDATE monument_categories 
  SET ch_mon_cat_label=%s, 
  ch_mon_cat_statut=%s, 
  ch_mon_cat_date=%s, 
  ch_mon_cat_mis_jour=%s, 
  ch_mon_cat_nb_update=%s, 
  ch_mon_cat_nom=%s, 
  ch_mon_cat_desc=%s, 
  ch_mon_cat_icon=%s, 
  ch_mon_cat_couleur=%s,
  bg_image_url=%s,
  ch_mon_cat_budget=%s, 
  ch_mon_cat_industrie=%s, 
  ch_mon_cat_commerce=%s, 
  ch_mon_cat_agriculture=%s, 
  ch_mon_cat_tourisme=%s, 
  ch_mon_cat_recherche=%s, 
  ch_mon_cat_environnement=%s, 
  ch_mon_cat_education=%s 
  WHERE ch_mon_cat_ID=%s",
        escape_sql($_POST['ch_mon_cat_label'], "text"),
        escape_sql($_POST['ch_mon_cat_statut'], "int"),
        escape_sql($_POST['ch_mon_cat_date'], "date"),
        escape_sql($_POST['ch_mon_cat_mis_jour'], "date"),
        escape_sql($_POST['ch_mon_cat_nb_update'], "int"),
        escape_sql($_POST['ch_mon_cat_nom'], "text"),
        escape_sql($_POST['ch_mon_cat_desc'], "text"),
        escape_sql($_POST['ch_mon_cat_icon'], "text"),
        escape_sql($_POST['ch_mon_cat_couleur'], "text"),
        escape_sql($_POST['bg_image_url'], "text"),
        escape_sql($_POST['ch_mon_cat_budget'], "text"),
        escape_sql($_POST['ch_mon_cat_industrie'], "text"),
        escape_sql($_POST['ch_mon_cat_commerce'], "text"),
        escape_sql($_POST['ch_mon_cat_agriculture'], "text"),
        escape_sql($_POST['ch_mon_cat_tourisme'], "text"),
        escape_sql($_POST['ch_mon_cat_recherche'], "text"),
        escape_sql($_POST['ch_mon_cat_environnement'], "text"),
        escape_sql($_POST['ch_mon_cat_education'], "text"),
        escape_sql($_POST['ch_mon_cat_ID'], "int"));

    $Result1 = mysql_query($updateSQL, $maconnexion);

    // Regénérer les influences des monuments de la catégorie modifiée.
    foreach($eloquentCategory->patrimoine as $eloquentPatrimoine) {
        $eloquentPatrimoine->generateInfluence();
    }

    $updateGoTo = DEF_URI_PATH . "back/institut_patrimoine.php";
    appendQueryString($updateGoTo);
    header(sprintf("Location: %s", $updateGoTo));
    exit;
}

//requete categories monuments

$colname_liste_mon_cat = "-1";
if (isset($_GET['mon_cat_id'])) {
  $colname_liste_mon_cat = $_GET['mon_cat_id'];
}

$query_liste_mon_cat = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_ID = %s ORDER BY ch_mon_cat_mis_jour DESC", escape_sql($colname_liste_mon_cat, "int"));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion);
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);
$totalRows_liste_mon_cat = mysql_num_rows($liste_mon_cat);

?>

<!-- Modal Header-->
<form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-categorie">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Modifier la cat&eacute;gorie <?= e($row_liste_mon_cat['ch_mon_cat_nom']) ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
<div class="span9">
            <!-- Boutons cachés -->
            <?php 
          $now= date("Y-m-d G:i:s");
                  $nb_update = $row_liste_mon_cat['ch_mon_cat_nb_update'] + 1; ?>
            <input name="ch_mon_cat_ID" type="hidden" value="<?= e($row_liste_mon_cat['ch_mon_cat_ID']) ?>">
            <input name="ch_mon_cat_label" type="hidden" value="mon_cat">
            <input name="ch_mon_cat_date" type="hidden" value="<?= e($row_liste_mon_cat['ch_mon_cat_date']) ?>">
            <input name="ch_mon_cat_mis_jour" type="hidden" value="<?php echo $now; ?>">
            <input name="ch_mon_cat_nb_update" type="hidden" value=<?php echo $nb_update; ?> >

        <!-- Statut -->
        <div id="spryradio20" class="control-group">
          <div class="control-label">Catégorie</div>
          <div class="controls">
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"0"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_0" value="0">
              Entreprise</label>
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"1"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_1" value="1">
              Ville</label>
            <label>
              <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"2"))) { echo "checked"; } ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_2" value="2">
              Pays</label>
            <span class="radioRequiredMsg">Choisissez une catégorie pour votre Quête</span></div>
        </div>

            <!-- Nom-->
            <div id="sprytextfield21" class="control-group">
              <label class="control-label" for="ch_mon_cat_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" id="ch_mon_cat_nom" name="ch_mon_cat_nom" value="<?= e($row_liste_mon_cat['ch_mon_cat_nom']) ?>">
                <br>
                <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
            </div>
            <!-- Icone -->
            <div id="sprytextfield23" class="control-group">
              <label class="control-label" for="ch_mon_cat_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" name="ch_mon_cat_icon" id="ch_mon_cat_icon" value="<?= e($row_liste_mon_cat['ch_mon_cat_icon']) ?>">
                <br>
                <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            </div>
            <!-- Couleur -->
            <div id="" class="control-group">
              <label class="control-label" for="ch_mon_cat_icon">Code et fond<a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour la cat&eacute;gorie"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append color" data-color="<?= e($row_liste_mon_cat['ch_mon_cat_couleur']) ?>" id="cp4">
                  <input type="text" class="input-small" style="width: 3em;" value="<?= e($row_liste_mon_cat['ch_mon_cat_couleur']) ?>" name="ch_mon_cat_couleur" id="ch_mon_cat_couleur">
                  </div>
              </div>
            </div>
            <!-- Fond -->
            <div id="sprytextfield23" class="control-group" style="margin-top: -3.1em; margin-left: 4em;">
              <div class="controls">
                <input class="input-xsmall" type="text" name="bg_image_url" id="bg_image_url" value="<?= e($row_liste_mon_cat['bg_image_url']) ?>">
                <br>
                <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            </div>
            <!-- Description -->
            <div id="sprytextarea24" class="control-group">
              <label class="control-label" for="ch_mon_cat_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <textarea rows="6" name="ch_mon_cat_desc" class="input-xlarge" id="ch_mon_cat_desc"><?= e($row_liste_mon_cat['ch_mon_cat_desc']) ?></textarea>
                <br>
                <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
            </div>
            </div>
            <div class="span3 icone-categorie"> 
          <img src="<?= e($row_liste_mon_cat['ch_mon_cat_icon']) ?>" alt="icone cat&eacute;gorie" style="background-color:<?= e($row_liste_mon_cat['ch_mon_cat_couleur']) ?>;"></div>
</div>
          </div>
      <h3>Influence sur l'économie</h3>
    <br />
      <div class="row-fluid">
        <div class="span5">
        <!-- ch_mon_cat_budget -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mon_cat_budget">Budget <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_budget" name="ch_mon_cat_budget" value="<?= e($row_liste_mon_cat['ch_mon_cat_budget']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!-- ch_mon_cat_industrie -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mon_cat_industrie">Industrie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_industrie" name="ch_mon_cat_industrie" value="<?= e($row_liste_mon_cat['ch_mon_cat_industrie']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!-- ch_mon_cat_commerce -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mon_cat_commerce">Commerce <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_commerce" name="ch_mon_cat_commerce" value="<?= e($row_liste_mon_cat['ch_mon_cat_commerce']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!--  ch_mon_cat_agriculture -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_mon_cat_agriculture">Agriculture <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_agriculture" name="ch_mon_cat_agriculture" value="<?= e($row_liste_mon_cat['ch_mon_cat_agriculture']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">1 caract&egrave;res max.</span></div>
          </div>
        </div>
        <div class="span6">
        <!-- ch_mon_cat_tourisme -->
          <div id="sprytextfield21" class="control-group">
            <label class="control-label" for="ch_mon_cat_tourisme">Tourisme <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_tourisme" name="ch_mon_cat_tourisme" value="<?= e($row_liste_mon_cat['ch_mon_cat_tourisme']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!--  ch_mon_cat_recherche  -->
          <div id="sprytextfield21" class="control-group">
            <label class="control-label" for="ch_mon_cat_recherche">Recherche <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_recherche" name="ch_mon_cat_recherche" value="<?= e($row_liste_mon_cat['ch_mon_cat_recherche']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!-- ch_mon_cat_environnement -->
          <div id="sprytextfield21" class="control-group">
            <label class="control-label" for="ch_mon_cat_environnement">Environnement <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_environnement" name="ch_mon_cat_environnement" value="<?= e($row_liste_mon_cat['ch_mon_cat_environnement']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
        <!--  ch_mon_cat_education  -->
          <div id="sprytextfield21" class="control-group">
            <label class="control-label" for="ch_mon_cat_education">Education <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
            <input class="input-small" type="text" id="ch_mon_cat_education" name="ch_mon_cat_education" value="<?= e($row_liste_mon_cat['ch_mon_cat_education']) ?>">
            <br>
            <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
          <input type="hidden" name="MM_update" value="ajout-categorie">
        </form>
<script>
    $(function () {
        $('#cp4').colorpicker({
            format: 'hex'
        });
    });
</script>
<script type="text/javascript">
var spryradio20 = new Spry.Widget.ValidationRadio("spryradio20", {validateOn:["change"]});
var sprytextfield21 = new Spry.Widget.ValidationTextField("sprytextfield21", "none", {minChars:2, maxChars:100, validateOn:["change"]});
var sprytextfield23 = new Spry.Widget.ValidationTextField("sprytextfield23", "url", {minChars:2, maxChars:1050, validateOn:["change"]});
var sprytextarea24 = new Spry.Widget.ValidationTextarea("sprytextarea24", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
