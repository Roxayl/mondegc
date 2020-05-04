<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');


$ville_ID = "-1";
if (isset($_GET['ch_vil_ID'])) {
  $ville_ID = $_GET['ch_vil_ID'];
}

$query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_vil_lien_img1, ch_vil_user, ch_vil_paysID, ch_pay_lien_forum, ch_use_id FROM villes INNER JOIN pays ON ch_vil_paysID = ch_pay_id LEFT JOIN  users ON ch_use_paysID = ch_vil_paysID WHERE ch_vil_ID = %s AND ch_vil_capitale<>3", GetSQLValueString($ville_ID, "int"));
$villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);

if (($row_villes['ch_pay_lien_forum']!= NULL) AND ($row_villes['ch_pay_lien_forum']!= "")) {
$input = $row_villes['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$msgHead = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/Ville1.png[/img]
[size=16][url=http://www.generation-city.com/monde/page-ville.php?ch_pay_id=".$row_villes['ch_vil_paysID']."&ch_ville_id=".$row_villes['ch_vil_ID']."][b]\n".$row_villes['ch_vil_nom']." a &eacute;t&eacute; mise &agrave; jour[/b][/url][/size][/center]";
$msgFooter = "[url=http://www.generation-city.com/monde/page-ville.php?ch_pay_id=".$row_villes['ch_vil_paysID']."&ch_ville_id=".$row_villes['ch_vil_ID']."][center][size=16][b]Visiter[/b][/size][/center][/url]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}

$paysid = $row_villes['ch_vil_paysID'];
//Mise à jour formulaire pays
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien")) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_forum=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_pay_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    $updateGoTo = DEF_URI_PATH . "page-ville.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $updateGoTo."?ch_pay_id=".$row_villes['ch_vil_paysID']."&ch_ville_id=".$row_villes['ch_vil_ID'];
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<!-- Modal Header si ID sujet correspond  a 4 chiffres-->
<?php if ($id_trouve == TRUE) { ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager <strong> <?php echo $row_villes['ch_vil_nom']; ?></strong> sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
        <h3>Message&nbsp;:</h3>
        <div class="pull-center"><img src="http://monde.generation-city.com/assets/img/IconesBDD/100/Ville1.png">
          <h4><?php echo $row_villes['ch_vil_nom']; ?> a &eacute;t&eacute; mise &agrave; jour</h4>
        </div>
        <script type="text/javascript">
        function envoiMessage(form)
        {
        // Créer un message à partir des informations fournies
        var txt_message = form.msgHead.value
        + "\n [b]" + form.typeMaj.value
        + " [/b]\n " + form.msgcorps.value
        + " \n " + form.msgFooter.value;
        form.message.value = txt_message;
        }
</script>
        <form action="http://www.forum-gc.com/post" method="post" name="post" enctype="multipart/form-data" onSubmit="envoiMessage(this)" target="_blank">
          <input type="hidden" name="mode" value="reply" />
          <!-- répondre au message -->
          <input type="hidden" name="t" value="<?php echo $id_sujet; ?>" />
          <!--  ID du topic dans lequel le message sera posté-->
          <input type="hidden" name="message" value="" />
          <!-- Contiendra le texte du message -->
          <input type="hidden" name="subject" value="" />
          <input name="msgHead" type="hidden" value="<?php echo htmlentities($msgHead, ENT_QUOTES, "ISO-8859-1"); ?>" />
          <!-- Type de jeu -->
          <div class="control-group">
            <label class="control-label" for="typeMaj">Partie mise &agrave; jour <a href="#" rel="clickover" title="Partie mise &eacute; jour" data-content="Pr&eacute;cisez la partie de votre page ville que vous avez modifi&eacute;e"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <select id="typeMaj" name="typeMaj">
                <option value="Les informations g&eacute;n&eacute;rales ont &eacute;t&eacute; modifi&eacute;es">Informations g&eacute;n&eacute;rales</option>
                <option value="Le journal de la ville a &eacute;t&eacute; compl&eacute;t&eacute;">Journal de la ville</option>
                <option value="Des images ont &eacute;t&eacute; ajout&eacute;es">Carrousel d'images</option>
                <option value="Les derniers chiffres &eacute;conomiques sont arriv&eacute;s">Ressources &eacute;conomiques</option>
                <option value="">Autres</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="msgcorps">Message <a href="#" rel="clickover" title="Message" data-content="Ecrivez ici votre message"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <textarea rows="3" name="msgcorps" class="span12"></textarea>
            </div>
          </div>
          <input name="msgFooter" type="hidden" value="<?php echo htmlentities($msgFooter, ENT_QUOTES, "ISO-8859-1"); ?>" />
          <input value="Envoyer" class="btn btn-primary" type="submit" name="post" />
        </form>
      </div>
    </div>
    <div class="span4">
      <div class="alert alert-danger">
        <h2>Attention&nbsp;!</h2>
        Avant d'envoyer ce message, vous devez être connecté sur le forum</div>
      <div class="pull-center">
        <div class="cache-forum">
          <iframe class="forum" src="http://www.generation-city.com/monde/php/login-forum.php" width="100%" height="300" frameborder="0" scrolling="no"><a href="http://www.forum-gc.com/">se connecter</a></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
</div>
<!-- Modal Header si ID sujet ne correspond pas a 4 chiffres-->
<?php } else { ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager la mise &agrave; jour d'une ville sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page"> 
  <!-- Si l'ID de la ville est trouve-->
  <?php if (isset($_GET['ch_vil_ID'])) {?>
  <!-- Si le lien du sujet sur le forum est trouv&eacute;-->
  <?php if ((($row_villes['ch_pay_lien_forum']==NULL) OR ($row_villes['ch_pay_lien_forum']=="") OR ($id_trouve== false)) AND ($row_villes['ch_vil_user']=== $row_villes['ch_use_id'])){?>
  <!-- Si le lien du sujet sur le forum n'est pas trouv&eacute;-->
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien" Id="ajout_lien">
    <?php if (($row_villes['ch_pay_lien_forum']== NULL) OR ($row_villes['ch_pay_lien_forum']== "")){?>
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à votre pays sur le Forum de G&eacute;n&eacute;ration City </h4>
    <?php } else { ?>
    <h4>Nous n'avons pas retrouv&eacute; votre sujet dans le lien que vous avez indiqu&eacute;</h4>
    <?php } ?>
    <!-- Lien Forum -->
    <input type="hidden" id="ch_pay_id" name="ch_pay_id" value="<?php echo $row_villes['ch_vil_paysID']; ?>">
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="ch_pay_lien_forum">Lien sujet sur le forum <a href="#" rel="clickover" data-placement="bottom" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacr&eacute; &agrave; votre pays sur le forum. Cette information sevira &agrave; poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-x-large" type="text" id="ch_pay_lien_forum" name="ch_pay_lien_forum" value="">
        <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <div class="controls">
      <p>&nbsp;</p>
      <button type="submit" class="btn btn-primary">Envoyer</button>
      <p>&nbsp;</p>
    </div>
    <input type="hidden" name="MM_update" value="ajout_lien">
  </form>
  <?php } else { ?>
  <h4>Le pays dans lequel est implant&eacute; votre ville n'as pas encore indiqu&eacute; le lien du sujet sur le Forum de G&eacute;n&eacute;ration City.</h4>
  <?php } ?>
  <!-- Si l'ID de la ville n'est pas trouve-->
  <?php } else { ?>
  <h4>Nous n'avons pas trouv&eacute; l'ID de votre ville.</h4>
  <?php } ?>
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {validateOn:["change"]});
</script>
<?php }?>
<?php
mysql_free_result($villes);?>
