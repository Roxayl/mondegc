<?php

header('Content-Type: text/html; charset=utf-8');

$ch_com_id = "-1";
if (isset($_GET['com_id'])) {
  $ch_com_id = $_GET['com_id'];
}

$query_communique = sprintf("SELECT ch_com_ID, ch_com_titre, ch_com_contenu, ch_com_element_id, ch_com_categorie, ch_com_label, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM communiques INNER JOIN users ON ch_use_id = ch_com_user_id WHERE ch_com_ID = %s AND ch_com_statut=1", GetSQLValueString($ch_com_id, "int"));
$communique = mysql_query($query_communique, $maconnexion);
$row_communique = mysql_fetch_assoc($communique);
$totalRows_communique = mysql_num_rows($communique);
$cat = $row_communique['ch_com_categorie'];
$elementID = $row_communique['ch_com_element_id'];

//Connexion BBD Pour info sur l'institution emmitrice
if ( $cat == "pays") {

$query_pays = sprintf("SELECT ch_pay_id, ch_pay_nom, ch_pay_lien_forum FROM pays WHERE ch_pay_id = %s",GetSQLValueString($elementID, "int"));
$pays = mysql_query($query_pays, $maconnexion);
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);

if (($row_pays['ch_pay_lien_forum']!= NULL) AND ($row_pays['ch_pay_lien_forum']!= "")) {
$input = $row_pays['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$message = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/Communique.png[/img]\r".$row_communique['ch_use_predicat_dirigeant']." ".$row_communique['ch_use_prenom_dirigeant']." ".$row_communique['ch_use_nom_dirigeant']."\r[i]".$row_communique['ch_use_titre_dirigeant']."[/i]\r a &eacute;crit un communiqu&eacute; officiel au nom du pays [url=http://www.generation-city.com/monde/page-pays.php?ch_pay_id=".$row_pays['ch_pay_id']."]".$row_pays['ch_pay_nom']."[/url] :\n[size=18][b]".$row_communique['ch_com_titre']."[/b][/size][/center]\n
[spoiler]".$row_communique['ch_com_contenu']."[/spoiler][url=http://www.generation-city.com/monde/page-communique.php?com_id=".$row_communique['ch_com_ID']."][center][size=16][b]Voir les r&eacute;actions[/b][/size][/url][/center]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}
mysql_free_result($pays);
}

if ( $cat == "ville") {
  
$query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_pay_id, ch_pay_lien_forum FROM villes INNER JOIN pays ON ch_vil_paysID = ch_pay_id WHERE ch_vil_ID = %s", GetSQLValueString($elementID, "int"));
$villes = mysql_query($query_villes, $maconnexion);
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);

if (($row_villes['ch_pay_lien_forum']!= NULL) AND ($row_villes['ch_pay_lien_forum']!= "")) {
$input = $row_villes['ch_pay_lien_forum'];
$id_sujet = substr("$input", 25, 4);
$message = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/100/Communique.png[/img]\r".$row_communique['ch_use_predicat_dirigeant']." ".$row_communique['ch_use_prenom_dirigeant']." ".$row_communique['ch_use_nom_dirigeant']."\r[i]".$row_communique['ch_use_titre_dirigeant']."[/i]\r a &eacute;crit un communiqu&eacute; officiel au nom de la ville [url=http://www.generation-city.com/monde/page-ville.php?ch_pay_id=".$row_villes['ch_pay_id']."&ch_ville_id=".$row_villes['ch_vil_ID']."]".$row_villes['ch_vil_nom']."[/url] :\n[size=18][b]".$row_communique['ch_com_titre']."[/b][/size][/center]\n
[spoiler]".$row_communique['ch_com_contenu']."[/spoiler][url=http://www.generation-city.com/monde/page-communique.php?com_id=".$row_communique['ch_com_ID']."][center][size=16][b]Voir les r&eacute;actions[/b][/size][/url][/center]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}
mysql_free_result($villes);
}


if ( $cat == "institut") {

$query_institut = sprintf("SELECT ch_ins_ID, ch_ins_nom, ch_ins_sigle, ch_ins_lien_forum, ch_ins_logo FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($elementID, "int"));
$institut = mysql_query($query_institut, $maconnexion);
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

if (($row_institut['ch_ins_lien_forum']!= NULL) AND ($row_institut['ch_ins_lien_forum']!= "")) {
$input = $row_institut['ch_ins_lien_forum'];
$id_sujet = substr("$input", 26, 4);
$message = "[center][img]http://monde.generation-city.com/assets/img/IconesBDD/Bleu/100/Communique_bleu.png[/img]\r".$row_communique['ch_use_predicat_dirigeant']." ".$row_communique['ch_use_prenom_dirigeant']." ".$row_communique['ch_use_nom_dirigeant']."\r[i]".$row_communique['ch_use_titre_dirigeant']."[/i]\r a &eacute;crit un communiqu&eacute; officiel au nom de l'".$row_institut['ch_ins_nom']." :\n[size=18][b]".$row_communique['ch_com_titre']."[/b][/size][/center]\n
[spoiler]".$row_communique['ch_com_contenu']."[/spoiler][url=http://www.generation-city.com/monde/page-communique.php?com_id=".$row_communique['ch_com_ID']."][center][size=16][b]Voir les r&eacute;actions[/b][/size][/url][/center]\n";
if (preg_match("#^[0-9]#", $id_sujet))
{
$id_trouve= true;
}
else
{
$id_trouve= false;
}
}
mysql_free_result($institut);
}


//Mise à jour formulaire pays
$paysid = $row_pays['ch_pay_id'];
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien")) {
  $updateSQL = sprintf("UPDATE pays SET ch_pay_lien_forum=%s WHERE ch_pay_id=%s",
                       GetSQLValueString($_POST['ch_pay_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_pay_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion);
    $updateGoTo = DEF_URI_PATH . "page-communique.php";
  appendQueryString($updateGoTo);
  $adresse = $updateGoTo."?ch_com_ID=".$row_communique['ch_com_ID'];
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}

//Mise à jour formulaire institut
$insid = $row_institut['ch_ins_ID'];
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_lien_institut")) {
  $updateSQL = sprintf("UPDATE instituts SET ch_ins_lien_forum=%s WHERE ch_ins_ID=%s",
                       GetSQLValueString($_POST['ch_ins_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_ins_ID'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion);
    $updateGoTo = DEF_URI_PATH . "page-communique.php";
  appendQueryString($updateGoTo);
  $adresse = $updateGoTo."?ch_com_ID=".$row_communique['ch_com_ID'];
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}
?>
<!-- Modal Header si ID sujet correspond  a 4 chiffres-->
<?php if ($id_trouve == TRUE) { ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h3 id="myModalLabel">Partager la cr&eacute;ation du communique <strong> <?= e($row_communique['ch_com_titre']) ?></strong> sur le forum de Génération City</h3>
</div>
<div class="modal-body corps-page">
  <div class="row-fluid">
    <div class="span8">
      <div class="well">
        <h3>Message&nbsp;:</h3>
        <div class="pull-center">
          <?php if ( $cat == "institut") { ?>
          <img src="http://monde.generation-city.com/assets/img/IconesBDD/Bleu/100/Communique_bleu.png">
          <?php } else { ?>
          <img src="http://monde.generation-city.com/assets/img/IconesBDD/100/Communique.png">
          <?php } ?>
          <p class="pull-center"><?= e($row_communique['ch_use_predicat_dirigeant']) ?> <?= e($row_communique['ch_use_prenom_dirigeant']) ?> <?= e($row_communique['ch_use_nom_dirigeant']) ?></p>
          <p class="pull-center"><em><?= e($row_communique['ch_use_titre_dirigeant']) ?></em></p>
          <?php if ( $cat == "pays") { ?>
          <p class="pull-center">a &eacute;crit un communiqu&eacute; officiel au nom du pays <a href="http://www.generation-city.com/monde/page-pays.php?ch_pay_id=<?= e($row_pays['ch_pay_id']) ?>"><?php echo $row_pays['ch_pay_nom'] ?></a></p>
          <?php } elseif ( $cat == "villes") { ?>
          <p class="pull-center">a &eacute;crit un communiqu&eacute; officiel au nom de la ville <a href="http://www.generation-city.com/monde/page-ville.php?ch_pay_id=<?= e($row_villes['ch_pay_id']) ?>&ch_ville_id=<?= e($row_villes['ch_vil_ID']) ?>"><?php echo $row_villes['ch_vil_nom'] ?></a></p>
          <?php } elseif ( $cat == "institut") { ?>
          <p class="pull-center">a &eacute;crit un communiqu&eacute; officiel au nom de l'<?php echo $row_institut['ch_ins_nom'] ?></p>
          <?php } ?>
          <h4 class="pull-center"><?php echo $row_communique['ch_com_titre'] ?></h4>
        </div>
        <p><?= htmlPurify($row_communique['ch_com_contenu']) ?></p>
        <div class="pull-center"><a href="http://www.generation-city.com/monde/page-communique.php?com_id=<?= e($row_communique['ch_com_ID']) ?>"><strong>Voir les r&eacute;actions</strong></a></div>
        <p>&nbsp;</p>
      </div>
      <form action="https://www.forum-gc.com/post" method="post" name="post" enctype="multipart/form-data" onSubmit="envoiMessage(this)">
        <input type="hidden" name="mode" value="reply" />
        <!-- répondre au message -->
        <input type="hidden" name="t" value="<?php echo $id_sujet; ?>" />
        <!--  ID du topic dans lequel le message sera posté-->
        <input type="hidden" name="message" value="<?php echo htmlentities($message, ENT_QUOTES, "utf-8"); ?>" />
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
  <h3 id="myModalLabel">Partager la cr&eacute;ation d'un communique sur le forum de Génération City</h3>
</div>
<div class="modal-body corps-page"> 
  <!-- Si l'ID du communique est trouve-->
  <?php if (isset($_GET['com_id'])) {?>
  <!-- Si le lien du sujet sur le forum est trouv&eacute;-->
  <?php if (($row_pays['ch_pay_lien_forum']==NULL) OR ($row_pays['ch_pay_lien_forum']=="") OR ($row_villes['ch_pay_lien_forum']==NULL) OR ($row_villes['ch_pay_lien_forum']=="")  OR ($row_insitut['ch_ins_lien_forum']==NULL) OR ($row_institut['ch_ins_lien_forum']=="") OR ($id_trouve== false) ) {?>
  <!-- Si le lien du sujet sur le forum n'est pas trouv&eacute;-->
  <?php if ( $cat == "pays") { ?>
  <?php if (($row_pays['ch_pay_lien_forum']== NULL) OR ($row_pays['ch_pay_lien_forum']== "")){?>
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien" Id="ajout_lien">
    <input type="hidden" name="ch_pay_id" id="ch_pay_id" value="<?= e($row_communique['ch_com_element_id']) ?>">
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à votre pays sur le Forum de G&eacute;n&eacute;ration City </h4>
    <!-- Lien Forum -->
    <input type="hidden" id="ch_pay_id" name="ch_pay_id" value="<?php echo $row_communique['ch_com_element_id'] ?>">
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
  <?php } ?>
  <?php } elseif ( $cat == "villes") { ?>
  <?php if (($row_villes['ch_pay_lien_forum']== NULL) OR ($row_villes['ch_pay_lien_forum']== "")){?>
  <h4>Le pays dans lequel est plac&eacute;votre ville n'a pas encore indiqu&eacute; de lien pour le sujet qui lui est consacr&eacute;.</h4>
  <?php } ?>
  <?php } elseif ( $cat == "institut") { ?>
  <?php if (($row_institut['ch_ins_lien_forum']== NULL) OR ($row_institut['ch_ins_lien_forum']== "")){?>
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_lien_institut" Id="ajout_lien_institut">
    <input type="hidden" name="ch_ins_ID" id="ch_ins_ID" value="<?= e($row_institut['ch_ins_ID']) ?>">
    <h4>Vous n'avez pas encore indiqu&eacute; le lien du sujet consacr&eacute; à cet institut sur le Forum de G&eacute;n&eacute;ration City </h4>
    <!-- Lien Forum -->
    <div id="sprytextfield2" class="control-group">
      <label class="control-label" for="ch_ins_lien_forum">Lien sujet sur le forum <a href="#" rel="clickover" data-placement="bottom" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacré à cet institut sur le forum. Cette information sevira à poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-x-large" type="text" id="ch_ins_lien_forum" name="ch_ins_lien_forum" value="">
        <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <div class="controls">
      <p>&nbsp;</p>
      <button type="submit" class="btn btn-primary">Envoyer</button>
      <p>&nbsp;</p>
    </div>
    <input type="hidden" name="MM_update" value="ajout_lien_institut">
  </form>
  <?php } ?>
  <?php } else { ?>
  <h4>Une erreur est survenue. Lien inexistant</h4>
  <?php } ?>
  <?php } else { ?>
  <h4>Une erreur est survenue</h4>
  <?php } ?>
  <!-- Si l'ID du communique n'est pas trouve-->
  <?php } else { ?>
  <h4>Nous n'avons pas trouv&eacute; l'ID de votre communique.</h4>
  <?php } ?>
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "url", {validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {validateOn:["change"]});
</script>
<?php }?>
<?php
mysql_free_result($communique);?>
