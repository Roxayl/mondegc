<?php

header('Content-Type: text/html; charset=utf-8');

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-categorie")) {
  $updateSQL = sprintf("UPDATE faithist_categories SET ch_fai_cat_label=%s, ch_fai_cat_statut=%s, ch_fai_cat_date=%s, ch_fai_cat_mis_jour=%s, ch_fai_cat_nb_update=%s, ch_fai_cat_nom=%s, ch_fai_cat_desc=%s, ch_fai_cat_icon=%s, ch_fai_cat_couleur=%s WHERE ch_fai_cat_ID=%s",
                       GetSQLValueString($_POST['ch_fai_cat_label'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_statut'], "int"),
                       GetSQLValueString($_POST['ch_fai_cat_date'], "date"),
                       GetSQLValueString($_POST['ch_fai_cat_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_fai_cat_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_fai_cat_nom'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_desc'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_icon'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_couleur'], "text"),
                       GetSQLValueString($_POST['ch_fai_cat_ID'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $updateGoTo = "../back/institut_histoire.php";
  appendQueryString($updateGoTo);
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}
//requete categories faits historiques

$colname_liste_fait_cat = "-1";
if (isset($_GET['fai_cat_id'])) {
  $colname_liste_fait_cat = $_GET['fai_cat_id'];
}

$query_liste_fait_cat = sprintf("SELECT * FROM faithist_categories WHERE ch_fai_cat_ID = %s ORDER BY ch_fai_cat_mis_jour DESC", GetSQLValueString($colname_liste_fait_cat, "int"));
$liste_fait_cat = mysql_query($query_liste_fait_cat, $maconnexion) or die(mysql_error());
$row_liste_fait_cat = mysql_fetch_assoc($liste_fait_cat);
$totalRows_liste_fait_cat = mysql_num_rows($liste_fait_cat);

?>

<!-- Modal Header-->
<form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-categorie">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Modifier la cat&eacute;gorie <?php echo $row_liste_fait_cat['ch_fai_cat_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
<div class="span9">
            <!-- Boutons cachés -->
            <?php 
				  $now= date("Y-m-d G:i:s");
                  $nb_update = $row_liste_fait_cat['ch_fai_cat_nb_update'] + 1; ?>
            <input name="ch_fai_cat_ID" type="hidden" value="<?php echo $row_liste_fait_cat['ch_fai_cat_ID']; ?>">
            <input name="ch_fai_cat_label" type="hidden" value="fai_cat">
            <input name="ch_fai_cat_date" type="hidden" value="<?php echo $row_liste_fait_cat['ch_fai_cat_date']; ?>">
            <input name="ch_fai_cat_mis_jour" type="hidden" value="<?php echo $now; ?>">
            <input name="ch_fai_cat_nb_update" type="hidden" value=<?php echo $nb_update; ?> >
            <!-- Statut -->
            <div id="spryradio20" class="control-group">
              <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de la cat&eacute;gorie" data-content="
    Visible : cette cat&eacute;gorie sera visible sur la page de l'institut d'histoire.
    Invisible : cette cat&eacute;gorie sera cach&eacute;e sur la page de l'institut d'histoire."><i class="icon-info-sign"></i></a></div>
              <div class="controls">
                <label>
                  <input <?php if (!(strcmp($row_liste_fait_cat['ch_fai_cat_statut'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_fai_cat_statut" value="1" id="ch_fai_cat_statut_1">
                  visible</label>
                <label>
                  <input <?php if (!(strcmp($row_liste_fait_cat['ch_fai_cat_statut'],"2"))) {echo "checked=\"checked\"";} ?> name="ch_fai_cat_statut" type="radio" id="ch_fai_cat_statut_2" value="2">
                  invisible</label>
                <span class="radioRequiredMsg">Choisissez un statut pour cette cat&eacute;gorie de faits historiques</span></div>
            </div>
            <!-- Nom-->
            <div id="sprytextfield21" class="control-group">
              <label class="control-label" for="ch_fai_cat_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" id="ch_fai_cat_nom" name="ch_fai_cat_nom" value="<?php echo $row_liste_fait_cat['ch_fai_cat_nom']; ?>">
                <br>
                <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
            </div>
            <!-- Icone -->
            <div id="sprytextfield23" class="control-group">
              <label class="control-label" for="ch_fai_cat_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" name="ch_fai_cat_icon" id="ch_fai_cat_icon" value="<?php echo $row_liste_fait_cat['ch_fai_cat_icon']; ?>">
                <br>
                <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            </div>
            <!-- Couleur -->
            <div id="" class="control-group">
              <label class="control-label" for="ch_fai_cat_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour la cat&eacute;gorie"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append color" data-color="<?php echo $row_liste_fait_cat['ch_fai_cat_couleur']; ?>" data-color-format="hex" id="cp4">
                  <input type="text" class="input-large" value="<?php echo $row_liste_fait_cat['ch_fai_cat_couleur']; ?>" name="ch_fai_cat_couleur" id="ch_fai_cat_couleur">
                  <span class="add-on"><i style="background-color: <?php echo $row_liste_fait_cat['ch_fai_cat_couleur']; ?>)"></i></span> </div>
              </div>
            </div>
            <!-- Description -->
            <div id="sprytextarea24" class="control-group">
              <label class="control-label" for="ch_fai_cat_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <textarea rows="6" name="ch_fai_cat_desc" class="input-xlarge" id="ch_fai_cat_desc"><?php echo $row_liste_fait_cat['ch_fai_cat_desc']; ?></textarea>
                <br>
                <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
            </div>
            </div>
            <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_liste_fait_cat['ch_fai_cat_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_liste_fait_cat['ch_fai_cat_couleur']; ?>;"></div>
</div>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
          <input type="hidden" name="MM_update" value="ajout-categorie">
        </form>
        <script>
		$(function(){
$('#cp4').colorpicker({
format: 'hex'});
		});
	</script>
    <script type="text/javascript">
var spryradio20 = new Spry.Widget.ValidationRadio("spryradio20", {validateOn:["change"]});
var sprytextfield21 = new Spry.Widget.ValidationTextField("sprytextfield21", "none", {minChars:2, maxChars:30, validateOn:["change"]});
var sprytextfield23 = new Spry.Widget.ValidationTextField("sprytextfield23", "url", {minChars:2, maxChars:250, validateOn:["change"]});
var sprytextarea24 = new Spry.Widget.ValidationTextarea("sprytextarea24", {maxChars:400, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>
