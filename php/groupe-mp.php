<?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

$group_ID = "-1";
if (isset($_GET['ch_mem_group_ID'])) {
  $group_ID = $_GET['ch_mem_group_ID'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_group = sprintf("SELECT ch_disp_mem_id, ch_use_paysID, ch_use_login, ch_use_lien_imgpersonnage, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_id, ch_pay_lien_imgdrapeau FROM dispatch_mem_group INNER JOIN users ON ch_disp_mem_id = ch_use_id INNER JOIN pays ON ch_use_paysID = ch_pay_id WHERE ch_disp_group_id = %s AND ch_pay_publication=1", GetSQLValueString($group_ID, "int"));
$group = mysql_query($query_group, $maconnexion) or die(mysql_error());
$row_group = mysql_fetch_assoc($group);
$totalRows_group = mysql_num_rows($group);



mysql_select_db($database_maconnexion, $maconnexion);
$query_group_info = sprintf("SELECT ch_mem_group_nom, ch_mem_group_icon, ch_mem_group_couleur FROM membres_groupes WHERE ch_mem_group_ID = %s", GetSQLValueString($group_ID, "int"));
$group_info = mysql_query($query_group_info, $maconnexion) or die(mysql_error());
$row_group_info = mysql_fetch_assoc($group_info);
$totalRows_group_info = mysql_num_rows($group_info);

$msgHead = "[center][img]".$row_group_info['ch_mem_group_icon']."[/img]
[size=16][url=http://www.generation-city.com/monde/politique.php?mem_groupID=".$group_ID."#groupes][b]".$row_group_info['ch_mem_group_nom']."[/b][/url][/size]\nMissive secr�te en provenance du monde GC[/center]\n";

?>
<script type="text/javascript">
        function envoiMessage(form)
        {
        // Cr�er un message � partir des informations fournies
        var txt_message = form.msgHead.value
        + "\n" + form.msgcorps.value;
	    form.message.value = txt_message;
        }
</script>
<!-- Modal Header-->
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
  <h3 id="myModalLabel">Envoyer un message aux membres du groupe <strong><?php echo $row_group_info['ch_mem_group_nom']; ?></strong> sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
      <h3>Destinataires</h3>
        <form action="http://www.forum-gc.com/privmsg.forum" method="post" name="post" enctype="multipart/form-data" onSubmit="envoiMessage(this)" target="_blank">
        <input type="hidden" name="message" value="" />
        <input type="hidden" name="lt" value="" />
        <input type="hidden" name="folder" value="inbox" />
        <input type="hidden" name="mode" value="post" />
        <input type="hidden" name="new_pm_time" value="1344284054">
      <?php do { ?>
      <label class="checkbox">
      <div class="row-fluid">
      <div class="span1">
      <input type="checkbox" name="username[]" value="<?php echo $row_group['ch_use_login']; ?>" checked="checked"/>
      </div>
      <div class="span1">
      <img src="<?php echo $row_group['ch_use_lien_imgpersonnage']; ?>" class="img-mp"/> 
	  </div>
      <div class="span10">
	  <?php echo $row_group['ch_use_predicat_dirigeant']; ?> <?php echo $row_group['ch_use_prenom_dirigeant']; ?> <?php echo $row_group['ch_use_nom_dirigeant']; ?>
      </div>
      </div>
       </label>
<?php } while ($row_group = mysql_fetch_assoc($group)); ?>
        <h3>Message&nbsp;:</h3>
        <input class="input-xlarge" type="text" name="subject" value="" placeholder="titre">
          <input name="msgHead" type="hidden" value="<?php echo htmlentities($msgHead, ENT_QUOTES, "ISO-8859-1"); ?>" />
        <div class="pull-center icone-categorie"><img src="<?php echo $row_group_info['ch_mem_group_icon']; ?>" style="background-color:<?php echo $row_group_info['ch_mem_group_couleur']; ?>">
          <h4><?php echo $row_group_info['ch_mem_group_nom']; ?></h4>
          <p class="pull-center">Message aux membres</p>
          <p>&nbsp;</p>
        </div>
              <textarea rows="3" name="msgcorps" class="span12" placeholder="message"></textarea>
          <input value="Envoyer" class="btn btn-primary" type="submit" name="post" />
        </form>
      </div>
    </div>
    <div class="span4">
      <div class="alert alert-danger">
        <h2>Attention&nbsp;!</h2>
        Avant d'envoyer ce message, vous devez �tre connect� sur le forum</div>
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
<?php
mysql_free_result($group);?>
