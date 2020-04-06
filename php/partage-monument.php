<?php

require_once('../Connections/maconnexion.php');
header('Content-Type: text/html; charset=utf-8');
//Protection  données envoyées
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


$monument_ID = "-1";
if (isset($_GET['ch_pat_id'])) {
  $monument_ID = $_GET['ch_pat_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_monument = sprintf("SELECT ch_pat_id, ch_pat_nom, ch_pat_lien_img1, ch_pat_description, ch_pat_paysID, ch_pay_lien_forum, ch_vil_user, ch_use_id  FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID LEFT JOIN users ON ch_pat_paysID = ch_use_paysID WHERE ch_pat_id = %s AND ch_pat_statut=1", GetSQLValueString($monument_ID, "int"));
$monument = mysql_query($query_monument, $maconnexion) or die(mysql_error());
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);

if (($row_monument['ch_pay_lien_forum']!= NULL) AND ($row_monument['ch_pay_lien_forum']!= "")) {
$input = $row_monument['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$message = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/monument1.png[/img]
[size=16][b]".$row_monument['ch_pat_nom']."[/b][/size][/center]\n
[spoiler][url=http://www.generation-city.com/monde/page-monument.php?ch_pat_id=".$row_monument['ch_pat_id']."][img]".$row_monument['ch_pat_lien_img1']."[/img][/url][/spoiler]".$row_monument['ch_pat_description']."[url=http://www.generation-city.com/monde/page-monument.php?ch_pat_id=".$row_monument['ch_pat_id']."][center][size=16][b]Visiter[/b][/size][/url][/center]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}

$paysid = $row_monument['ch_pat_paysID'];
//Mise à jour formulaire pays
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien")) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_forum=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_pay_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
    $updateGoTo = "../page-monument.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  $adresse = $updateGoTo."?ch_pat_id=".$row_monument['ch_pat_id'];
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<!-- Modal Header si ID sujet correspond  a 4 chiffres-->
<?php if ($id_trouve == TRUE) { ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager <strong> <?php echo $row_monument['ch_pat_nom']; ?></strong> sur le forum de Génération City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
        <h3>Message&nbsp;:</h3>
        <div class="pull-center"><img src="http://monde.generation-city.com/assets/img/IconesBDD/100/monument1.png">
          <h4><?php echo $row_monument['ch_pat_nom']; ?></h4>
          <img src="<?php echo $row_monument['ch_pat_lien_img1']; ?>"></div>
        <p><?php echo $row_monument['ch_pat_description']; ?></p>
        <div class="pull-center"><a href="http://www.generation-city.com/monde/page-monument.php?ch_pat_id=<?php echo $row_monument['ch_pat_id']; ?>"><strong>Visiter</strong></a></div>
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
        Avant d'envoyer ce message, vous devez &ecirc;tre connect&eacute; sur le forum</div>
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
  <h3 id="myModalLabel">Partager la cr&eacute;ation d'un monument sur le forum de G&eacute;n&eacute;ration City</h3>
</div>
<div class="modal-body corps-page">
<!-- Si l'ID du monument est trouve-->
<?php if (isset($_GET['ch_pat_id'])) {?>
<!-- Si le lien du sujet sur le forum est trouv&eacute;-->
<?php if ((($row_monument['ch_pay_lien_forum']==NULL) OR ($row_monument['ch_pay_lien_forum']=="") OR ($id_trouve== false)) AND ($row_monument['ch_vil_user']=== $row_monument['ch_use_id'])) {?>
<!-- Si le lien du sujet sur le forum n'est pas trouv&eacute;-->
<form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien" Id="ajout_lien">
    <?php if (($row_monument['ch_pay_lien_forum']== NULL) OR ($row_monument['ch_pay_lien_forum']== "")){?>
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à votre pays sur le Forum de G&eacute;n&eacute;ration City </h4>
    <?php } else { ?>
    <h4>Nous n'avons pas retrouv&eacute; votre sujet dans le lien que vous avez indiqu&eacute;</h4>
    <?php } ?>
    <!-- Lien Forum -->
    <input type="hidden" id="ch_pay_id" name="ch_pay_id" value="<?php echo $row_monument['ch_pat_paysID']; ?>">
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
<!-- Si l'ID du monument n'est pas trouve-->
<?php } else { ?>
<h4>Nous n'avons pas trouv&eacute; l'ID de votre monument.</h4>
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
mysql_free_result($monument);?>
