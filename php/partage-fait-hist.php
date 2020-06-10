<?php

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');

$Fait_hist_ID = "-1";
if (isset($_GET['ch_his_id'])) {
  $Fait_hist_ID = $_GET['ch_his_id'];
}

$query_fait_hist = sprintf("SELECT ch_his_id, ch_his_nom, ch_his_lien_img1, ch_his_description, ch_his_paysID, ch_pay_lien_forum FROM histoire INNER JOIN pays ON ch_his_paysID = ch_pay_id WHERE ch_his_id = %s AND ch_his_statut=1", GetSQLValueString($Fait_hist_ID, "int"));
$fait_hist = mysql_query($query_fait_hist, $maconnexion) or die(mysql_error());
$row_fait_hist = mysql_fetch_assoc($fait_hist);
$totalRows_fait_hist = mysql_num_rows($fait_hist);

if (($row_fait_hist['ch_pay_lien_forum']!= NULL) AND ($row_fait_hist['ch_pay_lien_forum']!= "")) {
$input = $row_fait_hist['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$message = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/faithistorique.png[/img]
[size=16][b]".$row_fait_hist['ch_his_nom']."[/b][/size][/center]\n
[spoiler][url=http://www.generation-city.com/monde/page-fait-historique.php?ch_his_id=".$row_fait_hist['ch_his_id']."][img]".$row_fait_hist['ch_his_lien_img1']."[/img][/url][/spoiler]".$row_fait_hist['ch_his_description']."[url=http://www.generation-city.com/monde/page-fait-historique.php?ch_his_id=".$row_fait_hist['ch_his_id']."][center][size=16][b]Consulter[/b][/size][/url][/center]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}

$paysid = $row_fait_hist['ch_his_paysID'];
//Mise à jour formulaire pays
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien")) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_forum=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_pay_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    $updateGoTo = DEF_URI_PATH . "page-fait-historique.php";
  appendQueryString($updateGoTo);
  $adresse = $updateGoTo."?ch_his_id=".$row_fait_hist['ch_his_paysID'];
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}
?>
<!-- Modal Header si ID sujet correspond  a 4 chiffres-->
<?php if ($id_trouve == TRUE) { ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager <strong> <?php echo $row_fait_hist['ch_his_nom']; ?></strong> sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
        <h3>Message&nbsp;:</h3>
        <div class="pull-center"><img src="http://monde.generation-city.com/assets/img/IconesBDD/100/faithistorique.png">
          <h4><?php echo $row_fait_hist['ch_his_nom']; ?></h4>
          <img src="<?php echo $row_fait_hist['ch_his_lien_img1']; ?>"></div>
        <p><?php echo $row_fait_hist['ch_his_description']; ?></p>
        <div class="pull-center"><a href="http://www.generation-city.com/monde/page-fait-historique.php?ch_his_id=<?php echo $row_fait_hist['ch_his_id']; ?>"><strong>Consulter</strong></a></div>
        <p>&nbsp;</p>
      </div>
      <form action="http://www.forum-gc.com/post" method="post" name="post" enctype="multipart/form-data" onSubmit="envoiMessage(this)" target="_blank">
        <input type="hidden" name="mode" value="reply" />
        <!-- répondre au message -->
        <input type="hidden" name="t" value="<?php echo $id_sujet; ?>" />
        <!--  ID du topic dans lequel le message sera posté-->
        <input type="hidden" name="message" value="<?php echo htmlentities($message, ENT_QUOTES, "ISO-8859-1"); ?>" />
        <!-- Contiendra le texte du message -->
        <input type="hidden" name="subject" value="" />
        <input value="Envoyer" class="btn btn-primary" type="submit" name="post" />
      </form>
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
  <h3 id="myModalLabel">Partager la cr&eacute;ation d'un fait historique sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page"> 
  <!-- Si l'ID du fait_hist est trouve-->
  <?php if (isset($_GET['ch_his_id'])) {?>
  <!-- Si le lien du sujet sur le forum est trouv&eacute;-->
  <?php if (($row_fait_hist['ch_pay_lien_forum']==NULL) OR ($row_fait_hist['ch_pay_lien_forum']=="") OR ($id_trouve== false) ) {?>
  <!-- Si le lien du sujet sur le forum n'est pas trouv&eacute;-->
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien" Id="ajout_lien">
    <input type="hidden" name="ch_pay_id" id="ch_pay_id" value="<?php echo $row_fait_hist['ch_his_paysID']; ?>">
    <?php if (($row_fait_hist['ch_pay_lien_forum']== NULL) OR ($row_fait_hist['ch_pay_lien_forum']== "")){?>
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à votre pays sur le Forum de G&eacute;n&eacute;ration City </h4>
    <?php } else { ?>
    <h4>Nous n'avons pas retrouv&eacute; votre sujet dans le lien que vous avez indiqu&eacute;</h4>
    <?php } ?>
    <!-- Lien Forum -->
    <input type="hidden" id="ch_pay_id" name="ch_pay_id" value="<?php echo $row_fait_hist['ch_his_paysID'] ?>">
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="ch_pay_lien_forum">Lien sujet sur le forum <a href="#" rel="clickover" data-placement="bottom" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacré à votre pays sur le forum. Cette information sevira à poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
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
  <h4>Une erreur est survenue</h4>
  <?php } ?>
  <!-- Si l'ID du fait_hist n'est pas trouve-->
  <?php } else { ?>
  <h4>Nous n'avons pas trouv&eacute; l'ID de votre fait_hist.</h4>
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
mysql_free_result($fait_hist);?>
