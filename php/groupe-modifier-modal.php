<?php

include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-categorie")) {
  $updateSQL = sprintf("UPDATE membres_groupes SET ch_mem_group_label=%s, ch_mem_group_statut=%s, ch_mem_group_date=%s, ch_mem_group_mis_jour=%s, ch_mem_group_nb_update=%s, ch_mem_group_nom=%s, ch_mem_group_desc=%s, ch_mem_group_icon=%s, ch_mem_group_couleur=%s WHERE ch_mem_group_ID=%s",
                       GetSQLValueString($_POST['ch_mem_group_label'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_statut'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_date'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_mem_group_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_mem_group_nom'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_desc'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_icon'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_couleur'], "text"),
                       GetSQLValueString($_POST['ch_mem_group_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

if ($_SESSION['last_work'] = "institut_politique.php") {
  $updateGoTo = "../back/institut_politique.php";
} else {
  $updateGoTo = "../back/membre-modifier_back.php";
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//requete categories monuments

$colname_group_membre = "-1";
if (isset($_GET['mem_group_ID'])) {
  $colname_group_membre = $_GET['mem_group_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_group_membre = sprintf("SELECT * FROM membres_groupes WHERE ch_mem_group_ID = %s ORDER BY ch_mem_group_mis_jour DESC", GetSQLValueString($colname_group_membre, "int"));
$group_membre = mysql_query($query_group_membre, $maconnexion) or die(mysql_error());
$row_group_membre = mysql_fetch_assoc($group_membre);
$totalRows_group_membre = mysql_num_rows($group_membre);

?>

<!-- Modal Header-->
<form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-categorie">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
<h3 id="myModalLabel">Modifier le groupe <?php echo $row_group_membre['ch_mem_group_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
<div class="span9">
            <!-- Boutons cach�s -->
            <?php 
				  $now= date("Y-m-d G:i:s");
                  $nb_update = $row_group_membre['ch_mem_group_nb_update'] + 1; ?>
            <input name="ch_mem_group_ID" type="hidden" value="<?php echo $row_group_membre['ch_mem_group_ID']; ?>">
            <input name="ch_mem_group_label" type="hidden" value="mem_group">
            <input name="ch_mem_group_date" type="hidden" value="<?php echo $row_group_membre['ch_mem_group_date']; ?>">
            <input name="ch_mem_group_mis_jour" type="hidden" value="<?php echo $now; ?>">
            <input name="ch_mem_group_nb_update" type="hidden" value=<?php echo $nb_update; ?> >
            <!-- Statut -->
            <div id="spryradio20" class="control-group">
              <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de la cat&eacute;gorie" data-content="
    Visible : cette cat&eacute;gorie sera visible sur la page de l'institut du patrimoine.
    Invisible : cette cat&eacute;gorie sera cach&eacute;e sur la page de l'institut du patrimoine."><i class="icon-info-sign"></i></a></div>
              <div class="controls">
                <label>
                  <input <?php if (!(strcmp($row_group_membre['ch_mem_group_statut'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_mem_group_statut" value="1" id="ch_mem_group_statut_1">
                  visible</label>
                <label>
                  <input <?php if (!(strcmp($row_group_membre['ch_mem_group_statut'],"2"))) {echo "checked=\"checked\"";} ?> name="ch_mem_group_statut" type="radio" id="ch_mem_group_statut_2" value="2">
                  invisible</label>
                <span class="radioRequiredMsg">Choisissez un statut pour cette cat&eacute;gorie de monument</span></div>
            </div>
            <!-- Nom-->
            <div id="sprytextfield21" class="control-group">
              <label class="control-label" for="ch_mem_group_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" id="ch_mem_group_nom" name="ch_mem_group_nom" value="<?php echo $row_group_membre['ch_mem_group_nom']; ?>">
                <br>
                <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
            </div>
            <!-- Icone -->
            <div id="sprytextfield23" class="control-group">
              <label class="control-label" for="ch_mem_group_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" name="ch_mem_group_icon" id="ch_mem_group_icon" value="<?php echo $row_group_membre['ch_mem_group_icon']; ?>">
                <br>
                <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            </div>
            <!-- Couleur -->
            <div id="" class="control-group">
              <label class="control-label" for="ch_mem_group_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour la cat&eacute;gorie"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append color" data-color="<?php echo $row_group_membre['ch_mem_group_couleur']; ?>" data-color-format="hex" id="cp4">
                  <input type="text" class="input-large" value="<?php echo $row_group_membre['ch_mem_group_couleur']; ?>" name="ch_mem_group_couleur" id="ch_mem_group_couleur">
                  <span class="add-on"><i style="background-color: <?php echo $row_group_membre['ch_mem_group_couleur']; ?>)"></i></span> </div>
              </div>
            </div>
            <!-- Description -->
            <div id="sprytextarea24" class="control-group">
              <label class="control-label" for="ch_mem_group_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <textarea rows="6" name="ch_mem_group_desc" class="input-xlarge" id="ch_mem_group_desc"><?php echo $row_group_membre['ch_mem_group_desc']; ?></textarea>
                <br>
                <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
            </div>
            </div>
            <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_group_membre['ch_mem_group_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_group_membre['ch_mem_group_couleur']; ?>;"></div>
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
<?php
mysql_free_result($group_membre);?>