<?php ?>
<?php

switch ($row_User['ch_use_statut']) {
case "5" : 
$Rang_statut = 'Maire de villes';
break;
case "10" :  
$Rang_statut = 'Dirigeant de pays';
break;
case "15" :  
$Rang_statut = 'Juge Tempérant';
break;
case "20" :  
$Rang_statut = 'Membre du Haut-Conseil';
break;
case "30" :  
$Rang_statut = 'Administrateur';
break;
}

//Liste pays pour moderation

$query_pays = "SELECT ch_pay_id, ch_pay_nom FROM pays ORDER BY ch_pay_nom ASC";
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);
?>

<div class="well">
  <div class="row-fluid"> 
    <!-- image personnage -->
    <div class="span3">
    </div>
    <!-- donnees personnage -->
    <div class="span4">
    </div>
    <!-- donnees personnelles -->
    <div class="span5">
      <p><strong>Statut :</strong> <?php echo $Rang_statut; ?></p>
      <p><strong>Login :</strong> <?= e($row_User['ch_use_login']) ?></p>
      <p><strong>Mail :</strong> <?= e($row_User['ch_use_mail']) ?></p>
    </div>
  </div>
  <div class="row-fluid"> 
    <!-- bouton upload image personnage -->
    <div class="span3">
    </div>
    <div class="span5">
      <p class="visible-phone">&nbsp;</p>
    </div>
    <div class="span4"> 
      <!-- Button to trigger modal --> 
      <a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">Paramètres compte</a> </div>
  </div>
</div>


<!-- Formulaire profil
        ================================================== --> 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Param&egrave;tres du compte</h3>
  </div>
  <div class="modal-body">
  <form action="<?php echo $editFormAction; ?>" name="ProfilUser" method="POST" class="form-horizontal" id="ProfilUser">
    <!-- Boutons cachés -->
    <input name="ch_use_id" type="hidden" value="<?= e($row_User['ch_use_id']) ?>">
    <input name="userID" type="hidden" value="<?= e($row_User['ch_use_id']) ?>">
    <?php if ($_SESSION['statut'] >=20) {?>
    <div class="alert-danger">
      <h4>Param&egrave;tres r&eacute;serv&eacute;s &agrave; la mod&eacute;ration</h4>
      <!-- Bannissement du membre -->
      <div class="control-group"> <span class="control-label">Banissement</span>
        <div class="controls">
          <label class="checkbox">
            <input  type="checkbox" <?php if (!(strcmp($row_User['ch_use_acces'], NULL))) {echo "checked=\"checked\"";} ?> name="ch_use_acces_Checkbox"  value="1" id="ch_use_acces_Checkbox">
            Membre Banni <a href="#" rel="clickover" title="Membre Banni" data-content="Le membre ne pourra plus se connecter au site, acc&eacute;der aux formulaires ou poster des messages" data-placement="bottom"><i class="icon-info-sign"></i></a></label>
        </div>
      </div>
          <!-- Definir statut du membre -->
      <div id="spryradio10" class="control-group"> <span class="control-label">Statut</span>
        <div class="controls">
          <label class="radio" for="ch_use_statut_1">
            <input name="ch_use_statut" type="radio" id="ch_use_prov_statut_1" value="5" <?php if (!(strcmp($row_User['ch_use_statut'],"5"))) {echo "checked=\"checked\"";} ?>>
            Maire de villes<a href="#" rel="clickover" title="Maire" data-content="Un maire appartient &agrave; un pays. Il peut ajouter des villes qui appartiendront &agrave; son pays, cr&eacute;er des monuments et des communiqu&eacute;s. Il n'aura pas acc&egrave;s au formulaire permettant de modifier la page du pays."><i class="icon-info-sign"></i></a></label>
          <label class="radio" for="ch_use_statut_2">
            <input <?php if (!(strcmp($row_User['ch_use_statut'],"10"))) {echo "checked=\"checked\"";} ?> name="ch_use_statut" type="radio" id="ch_use_statut_2" value="10" checked="CHECKED" selected="selected">
            Dirigeant de pays<a href="#" rel="clickover" title="Dirigeant" data-content="Le dirigeant d'un pays peut alimenter les diff&eacute;rentes bases de donn&eacute;es au nom de son pays."><i class="icon-info-sign"></i></a></label>
          <label class="radio" for="ch_use_statut_5">
            <input <?php if (!(strcmp($row_User['ch_use_statut'],"15"))) {echo "checked=\"checked\"";} ?> name="ch_use_statut" type="radio" id="ch_use_statut_5" value="15">
            Juge Temp&eacute;rant <a href="#" rel="clickover" title="Juge Temp&eacute;rant" data-content="Le juge temp&eacute;rant op&egrave;re dans le cadre du projet Temp&eacute;rance en lien avec l'institut d'&eacute;conomie."><i class="icon-info-sign"></i></a></label>
          <?php if ($_SESSION['statut'] >=30) {?>
          <label class="radio" for="ch_use_statut_3">
            <input <?php if (!(strcmp($row_User['ch_use_statut'],"20"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_use_statut" value="20" id="ch_use_statut_3">
            Membre du Haut-Conseil <a href="#" rel="clickover" title="Haut-Conseil" data-content="Le membre du Haut-Conseil aura acc&egrave;s aux outils de mod&eacute;ration du Monde GC. Il peux &eacute;galement alimenter les bases de donn&eacute;es au nom des instituts."><i class="icon-info-sign"></i></a></label>
          <label class="radio" for="ch_use_statut_4">
            <input <?php if (!(strcmp($row_User['ch_use_statut'],"30"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_use_statut" value="30" id="ch_use_statut_2">
            Administrateur <a href="#" rel="clickover" title="Haut-Conseil" data-content="L'administrateur a la possibilit&eacute; d'effacer les donn&eacute;es et peut attribuer des statut sup&eacute;rieurs &agrave; celui de dirigeant"><i class="icon-info-sign"></i></a></label>
          <?php }  ?>
          <span class="radioRequiredMsg">Effectuez une s&eacute;lection.</span></div>
      </div>
      
      <!-- Pays du membre -->
      <div class="control-group">
        <label class="control-label" for="ch_use_paysID" >Pays associé au membre</label>
        <div class="controls">
          <select name="ch_use_paysID" id="ch_use_paysID">
            <option value="">Aucun</option>
            <?php do { ?>
            <option value="<?= e($row_pays['ch_pay_id']) ?>" <?php if (!(strcmp($row_pays['ch_pay_id'], $row_User['ch_use_paysID']))) {echo "selected=\"selected\"";} ?>><?= e($row_pays['ch_pay_nom']) ?></option>
            <?php } while ($row_pays = mysql_fetch_assoc($pays)); ?>
          </select>
        </div>
      </div>
      <p>&nbsp;</p>
    </div>
    <!-- Si pas de moderation -->
    <?php } else {  ?>
    <input name="ch_use_statut" type="hidden" value="<?= e($row_User['ch_use_statut']) ?>">
    <input name="ch_use_paysID" type="hidden" value="<?= e($row_User['ch_use_paysID']) ?>">
    <input name="ch_use_acces" type="hidden" value="<?= e($row_User['ch_use_acces']) ?>">
    <?php } ?>
    <!-- Nom user -->
    <div id="sprytextfield14" class="control-group">
      <label class="control-label" for="ch_use_login">Login<a href="#" rel="clickover" title="Nom du membre" data-content="12 caract&egrave;res maximum. Le login doit &ecirc;tre identique au pseudo utilis&eacute; sur le forum afin d'assurer l'envoi de mp. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" type="text" id="ch_use_login" name="ch_use_login" maxlength="12" value="<?= e($row_User['ch_use_login']) ?>" <?php if ($_SESSION['statut'] < 20) {?> readonly="readonly"<?php } ?>>
        <br />
        <span class="textfieldRequiredMsg">un login est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">12 caract&egrave;res max.</span></div>
    </div>
    <?php if ($_SESSION['user_ID'] == $row_User['ch_use_id']) {?>
    <!-- Password -->
    <div id="sprypassword10" class="control-group">
      <label class="control-label" for="ch_use_password">Mot de passe<a href="#" rel="clickover" title="Mot de passe" data-content="Entrez-ici un mot de passe"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" type="password" name="ch_use_password" id="ch_use_password" value="">
        <br>
        <span class="passwordRequiredMsg">un mot de passe est obligatoire.</span><span class="passwordMinCharsMsg">2 caract&egrave;res min.</span><span class="passwordMaxCharsMsg">32 caract&egrave;res max.</span></div>
    </div>
    <div id="spryconfirm10">
      <label class="control-label" for="ch-use_password2" class="control-group">
      Confirmez le mot de passe
      </label>
      <div class="controls">
        <input class="input-xlarge" type="password" name="ch-use_password" id="ch-use_password" value="">
        <br>
        <span class="confirmRequiredMsg">La confirmation du mot de passe est obligatoire..</span><span class="confirmInvalidMsg">Les valeurs ne correspondent pas.</span></div>
    </div>
    <br>
    <?php }  ?>
    <!-- Adresse mail -->
    <div id="sprytextfield15" class="control-group">
      <label class="control-label" for="ch_use_mail">Adresse mail<a href="#" rel="clickover" title="E-mail" data-content="Laissez une adresse de contact en cas de perte du mot de passe"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" name="ch_use_mail" type="text" id="ch_use_mail" value="<?= e($row_User['ch_use_mail']) ?>" maxlength="50">
        <br>
        <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldRequiredMsg">Une adresse mail valide est requise.</span></div>
    </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <input type="hidden" name="MM_update" value="ProfilUser">
    </div>
  </form>
</div>

<!-- SPRY ASSETS --> 
<script type="text/javascript">
var spryradio10 = new Spry.Widget.ValidationRadio("spryradio10", {validateOn:["change"]});
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none", {minChars:2, maxChars:35, validateOn:["change"]});
<?php if ($_SESSION['user_ID'] == $row_User['ch_use_id']) {?>
var sprypassword10 = new Spry.Widget.ValidationPassword("sprypassword10", {minChars:2, validateOn:["change"], maxChars:32});
var spryconfirm10 = new Spry.Widget.ValidationConfirm("spryconfirm10", "ch_use_password", {validateOn:["change"]});
var sprytextfield15 = new Spry.Widget.ValidationTextField("sprytextfield15", "email", {validateOn:["change"]});
<?php }  ?>
</script>
