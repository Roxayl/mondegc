<?php

header('Content-Type: text/html; charset=utf-8');

$pays_ID = "-1";
if (isset($_GET['ch_pay_id'])) {
  $pays_ID = $_GET['ch_pay_id'];
}

$query_pays = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_imgdrapeau, ch_pay_lien_forum, ch_use_id FROM pays INNER JOIN  users ON ch_use_paysID = ch_pay_id WHERE ch_pay_id = %s AND ch_pay_publication=1", GetSQLValueString($pays_ID, "int"));
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);

if (($row_pays['ch_pay_lien_forum']!= NULL) AND ($row_pays['ch_pay_lien_forum']!= "")) {
$input = $row_pays['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$msgHead = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/Pays1.png[/img]
[size=16][url=http://www.generation-city.com/monde/page-pays.php?ch_pay_id=".$row_pays['ch_pay_id']."][b]".$row_pays['ch_pay_nom']." a &eacute;t&eacute; mis &agrave; jour[/b][/url][/size][/center]\n";
$msgFooter = "[url=http://www.generation-city.com/monde/page-pays.php?ch_pay_id=".$row_pays['ch_pay_id']."][center][size=16][b]Visiter[/b][/size][/center][/url]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}

$paysid = $row_pays['ch_pay_id'];
//Mise à jour formulaire pays
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien")) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_forum=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_pay_id'], "int"));


  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    $updateGoTo = DEF_URI_PATH . "page-ville.php";
  appendQueryString($updateGoTo);
  $adresse = $updateGoTo."?ch_pay_id=".$row_villes['ch_vil_paysID']."&ch_ville_id=".$row_villes['ch_vil_ID'];
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}
?>
<!-- Modal Header si ID sujet correspond  a 4 chiffres-->
<?php if ($id_trouve == TRUE) { ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager <strong> <?= e($row_pays['ch_pay_nom']) ?></strong> sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
        <h3>Message&nbsp;:</h3>
        <div class="pull-center"><img src="http://monde.generation-city.com/assets/img/IconesBDD/100/Pays1.png">
          <h4><?= e($row_pays['ch_pay_nom']) ?> a &eacute;t&eacute; mis &agrave; jour</h4>
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
            <label class="control-label" for="typeMaj">Partie mise &agrave; jour <a href="#" rel="clickover" title="Partie mise &agrave; jour" data-content="Pr&eacute;cisez la partie de votre page pays que vous avez modifi&eacute;e"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <select id="typeMaj" name="typeMaj">
                <option value="Les informations g&eacute;n&eacute;rales ont &eacute;t&eacute; modifi&eacute;es">Informations g&eacute;n&eacute;rales</option>
                <option value="La pr&eacute;sentation du pays a &eacute;t&eacute; modifi&eacute;es">Pr&eacute;sentation</option>
                <option value="La partie consacr&eacute;e &agrave; la g&eacute;ographie a &eacute;t&eacute; modifi&eacute;es">G&eacute;ographie</option>
                <option value="La partie consacr&eacute;e &agrave; la politique a &eacute;t&eacute; modifi&eacute;es">Politique</option>
                <option value="La partie consacr&eacute;e &agrave; l'histoire a &eacute;t&eacute; modifi&eacute;es">Histoire</option>
                <option value="La partie consacr&eacute;e &agrave; l'&eacute;conomie a &eacute;t&eacute; modifi&eacute;es">&Eacute;conomie</option>
                <option value="La partie consacr&eacute;e aux transports a &eacute;t&eacute; modifi&eacute;es">Transport</option>
                <option value="La partie consacr&eacute;e au sport a &eacute;t&eacute; modifi&eacute;es">Sport</option>
                <option value="La partie consacr&eacute;e &agrave; la culture a &eacute;t&eacute; modifi&eacute;es">Culture</option>
                <option value="La partie consacr&eacute;e au patrimoine a &eacute;t&eacute; modifi&eacute;es">Patrimoine</option>
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
  <h3 id="myModalLabel">Partager la mise &agrave; jour d'un pays sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page"> 
  <!-- Si l'ID du fait_hist est trouve-->
  <?php if (isset($_GET['ch_pay_id'])) {?>
  <!-- Si le lien du sujet sur le forum est trouv&eacute;-->
  <?php if (($row_pays['ch_pay_lien_forum']==NULL) OR ($row_pays['ch_pay_lien_forum']=="") OR ($id_trouve== false)){?>
  <!-- Si le lien du sujet sur le forum n'est pas trouv&eacute;-->
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien" Id="ajout_lien">
    <?php if (($row_pays['ch_pay_lien_forum']== NULL) OR ($row_pays['ch_pay_lien_forum']== "")){?>
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à votre pays sur le Forum de G&eacute;n&eacute;ration City </h4>
    <?php } else { ?>
    <h4>Nous n'avons pas retrouv&eacute; votre sujet dans le lien que vous avez indiqu&eacute;</h4>
    <?php } ?>
    <!-- Lien Forum -->
    <input type="hidden" id="ch_pay_id" name="ch_pay_id" value="<?= e($row_pays['ch_pay_id']) ?>">
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
  <h4>Une erreur est survenue.</h4>
  <?php } ?>
  <!-- Si l'ID de la ville n'est pas trouve-->
  <?php } else { ?>
  <h4>Nous n'avons pas trouv&eacute; l'ID de votre pays.</h4>
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
mysql_free_result($pays);?>
