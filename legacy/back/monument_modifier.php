<?php

use GenCity\Monde\Institut\Institut;

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

// Connection infos monument

$monument_ID = "-1";
if (isset($_POST['monument_ID'])) {
  $monument_ID = $_POST['monument_ID'];
  unset($_POST['monument_ID']);
}

$query_monument = sprintf("SELECT ch_pat_id, ch_pat_label, ch_pat_statut, ch_pat_paysID, ch_pat_villeID, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description, ch_pay_id, ch_pay_nom, ch_vil_ID, ch_vil_nom, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_ID = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN pays ON ch_pat_paysID = ch_pay_id INNER JOIN villes ON ch_pat_villeID = ch_vil_ID WHERE ch_pat_id = %s", escape_sql($monument_ID, "int"));
$monument = mysql_query($query_monument, $maconnexion);
$row_monument = mysql_fetch_assoc($monument);
$totalRows_monument = mysql_num_rows($monument);
$ville_id = $row_monument['ch_pat_villeID'];
$paysID = $row_monument['ch_pat_paysID'];

// Connection infos dirigeant pays

$query_users = sprintf("SELECT ch_vil_user, ch_use_id, ch_use_login FROM villes INNER JOIN users ON ch_vil_user=ch_use_id WHERE ch_vil_ID = %s", escape_sql($ville_id, "int"));
$users = mysql_query($query_users, $maconnexion);
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

// *** Requête pour infos sur les categories.
$listcategories = ($row_monument['listcat']);
      if ($row_monument['listcat']) {
          

$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) ORDER BY ch_mon_cat_couleur";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion);
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);

if($totalRows_liste_mon_cat3) {
    $query_liste_mon_cat_nope = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID NOT In ($listcategories) AND ch_mon_cat_couleur NOT BETWEEN 100 AND 199 ORDER BY ch_mon_cat_couleur";
    $liste_mon_cat_nope = mysql_query($query_liste_mon_cat_nope, $maconnexion);
    $row_liste_mon_cat_nope = mysql_fetch_assoc($liste_mon_cat_nope);
    $totalRows_liste_mon_cat_nope = mysql_num_rows($liste_mon_cat_nope);
} else {
    $totalRows_liste_mon_cat_nope = 0;
}
      }

$_SESSION['last_work'] = 'page-monument.php?ch_pat_id='.$row_monument['ch_pat_id'];

//requete catégorie 1
$query_mon_cat_a = sprintf("SELECT * FROM monument_categories WHERE ch_mon_cat_couleur BETWEEN 0 AND 199 ORDER BY ch_mon_cat_couleur", escape_sql($mon_ID, "int"));
$mon_cat_a = mysql_query($query_mon_cat_a, $maconnexion);
$row_mon_cat_a = mysql_fetch_assoc($mon_cat_a);
$totalRows_mon_cat_a = mysql_num_rows($mon_cat_a);

//calculs
$nb_cat_ok = 0;

if ($query_liste_mon_cat3) {$ressource = mysql_query($query_liste_mon_cat3);

while($row = mysql_fetch_assoc($ressource)) {
if ($row_monument['listcat'])
   {$nb_cat_ok = $nb_cat_ok + 1;}
   else {$nb_cat_ok = 1;}
}
}

// Coordonnées marqueur carte
$coord_X = $row_monument['ch_pat_coord_X'];
$coord_Y = $row_monument['ch_pat_coord_Y'];


// Mise a jour fiche patrimoine
$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modifier_monument")) {
  $updateSQL = sprintf("UPDATE patrimoine SET ch_pat_label=%s, ch_pat_statut=%s, ch_pat_paysID=%s, ch_pat_villeID=%s, ch_pat_date=%s, ch_pat_mis_jour=%s, ch_pat_nb_update=%s, ch_pat_coord_X=%s, ch_pat_coord_Y=%s, ch_pat_nom=%s, ch_pat_lien_img1=%s, ch_pat_lien_img2=%s, ch_pat_lien_img3=%s, ch_pat_lien_img4=%s, ch_pat_lien_img5=%s, ch_pat_legende_img1=%s, ch_pat_legende_img2=%s, ch_pat_legende_img3=%s, ch_pat_legende_img4=%s, ch_pat_legende_img5=%s, ch_pat_description=%s WHERE ch_pat_id=%s",
                       escape_sql($_POST['ch_pat_label'], "text"),
                       escape_sql($_POST['ch_pat_statut'], "int"),
                       escape_sql($_POST['ch_pat_paysID'], "int"),
                       escape_sql($_POST['ch_pat_villeID'], "int"),
                       escape_sql($_POST['ch_pat_date'], "date"),
                       escape_sql($_POST['ch_pat_mis_jour'], "date"),
                       escape_sql($_POST['ch_pat_nb_update'], "int"),
                       escape_sql($_POST['form_coord_X'], "text"),
                       escape_sql($_POST['form_coord_Y'], "text"),
                       escape_sql($_POST['ch_pat_nom'], "text"),
                       escape_sql($_POST['ch_pat_lien_img1'], "text"),
                       escape_sql($_POST['ch_pat_lien_img2'], "text"),
                       escape_sql($_POST['ch_pat_lien_img3'], "text"),
                       escape_sql($_POST['ch_pat_lien_img4'], "text"),
                       escape_sql($_POST['ch_pat_lien_img5'], "text"),
                       escape_sql($_POST['ch_pat_legende_img1'], "text"),
                       escape_sql($_POST['ch_pat_legende_img2'], "text"),
                       escape_sql($_POST['ch_pat_legende_img3'], "text"),
                       escape_sql($_POST['ch_pat_legende_img4'], "text"),
                       escape_sql($_POST['ch_pat_legende_img5'], "text"),
                       escape_sql($_POST['ch_pat_description'], "text"),
                       escape_sql($_POST['ch_pat_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion);

  getErrorMessage('success', "Le monument a été modifié avec succès.");

  $updateGoTo = DEF_URI_PATH . "back/ville_modifier.php?ville-ID=" . $_POST['ch_pat_villeID'] . "#mes-monuments";
  appendQueryString($updateGoTo);
  header(sprintf("Location: %s", $updateGoTo));
  exit;
}

$institutCulture = new Institut(Institut::$instituts['culture']);

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Modifier un monument</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="../assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('../assets/img/ImgIntroheader.jpg');
}
#map {
	height: 500px;
	background-color: #fff;
}
img.olTileImage {
	max-width: none;
}
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140"> 
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
</header>
<div class="container" id="overview"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid corps-page">
    <!-- Page CONTENT
    ================================================== -->
    <div class=""> 
      <!-- Moderation
     ================================================== -->
      <div id="monument" class="titre-vert anchor">
        <h1 style="font-weight: 200;">Gestion de <?php if ($row_monument['ch_pat_statut']==0) { ?>l'entreprise<?php } else { ?>la quête<?php }?></h1>
      </div>
      <div class="well">
      <?php if (($_SESSION['statut'] >= 20) AND ($row_users['ch_use_id'] != $_SESSION['user_ID'])) { ?>
       <form class="pull-right" action="<?= DEF_URI_PATH ?>back/monument_confirmation_supprimer.php" method="post">
        <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer ce monument"><i class="icon-trash icon-white"></i></button>
      </form>
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/membre-modifier_back.php" method="get">
        <input name="userID" type="hidden" value="<?= e($row_users['ch_use_id']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/page_pays_back.php" method="get">
        <input name="paysID" type="hidden" value="<?= e($row_monument['ch_pat_paysID']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <form class="pull-right" action="<?= DEF_URI_PATH ?>back/ville_modifier.php" method="get">
        <input name="ville-ID" type="hidden" value="<?= e($row_monument['ch_pat_villeID']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion de la ville"> Modifier la ville</button>
      </form>
      <?php } else {?>
       <form class="pull-right" action="<?= DEF_URI_PATH ?>back/monument_confirmation_supprimer.php" method="post">
        <input name="monument_ID" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer ce monument"><i class="icon-trash icon-white"></i></button>
      </form>
      <a class="btn btn-primary pull-right" style="height: 21px; background: #f0eeec; color: black; margin-top: -3.7em; margin-right: -4.7em;" title="retour &agrave; la page de gestion de la ville" href="ville_modifier.php#mes-monuments">Retourner à la gestion de la ville</a>
      <?php } ?>
      <div class="alert alert-tips">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Cette page pour permet d'administrer votre <?php if ($row_monument['ch_pat_statut']==0) { ?>l'entreprise<?php } else { ?>la quête<?php }?>. <a href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures" class="guide-link">Comment ça marche ? GO!</a></div>
</div>

<!-- Colonne gauche -->
<div class="row-fluid">
<div class="span6">

      <div class="clearfix"></div>
      <!-- Debut formulaire -->
      <form action="<?= e($editFormAction) ?>" method="POST" class="form-horizontal" name="modifier_monument" Id="modifier_monument" onsubmit='return verif_champ(document.modifier_monument.form_coord_X.value);'>
        <!-- Bouton cachés -->
        <input name="ch_pat_id" type="hidden" value="<?= e($row_monument['ch_pat_id']) ?>" >
        <input name="ch_pat_paysID" type="hidden" value="<?= e($row_monument['ch_pat_paysID']) ?>" >
        <input name="ch_pat_villeID" type="hidden" value="<?= e($row_monument['ch_pat_villeID']) ?>">
        <input name="ch_pat_label" type="hidden" value="<?= e($row_monument['ch_pat_label']) ?>">
        <?php 
        $now= date("Y-m-d G:i:s");
        $nb_update = $row_monument['ch_pat_nb_update'] + 1;
        ?>
        <input name="ch_pat_date" type="hidden" value="<?= e($row_monument['ch_pat_date']) ?>" >
        <input name="ch_pat_mis_jour" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_pat_nb_update" type="hidden" value="<?php echo $nb_update; ?>">
        <input name="ch_pat_statut" type="hidden" value="<?= e($row_monument['ch_pat_statut']) ?>" >
        <input name="form_coord_X" type="hidden" value="<?= e($row_monument['form_coord_X']) ?>" >
        <input name="form_coord_Y" type="hidden" value="<?= e($row_monument['form_coord_Y']) ?>" >
        <!-- Nom -->
        <div id="sprytextfield2" class="control-group">
          <label class="control-label" for="ch_pat_nom">Nom de <?php if ($row_monument['ch_pat_statut']==0) { ?>l'entreprise<?php } else { ?>la quête<?php }?>  <a href="#" rel="clickover" title="Nom du monument" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" style="width: 400px;" id="ch_pat_nom" name="ch_pat_nom" value="<?= e($row_monument['ch_pat_nom']) ?>" placeholder="mon monument">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Logo -->
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_pat_lien_img1"><?php if ($row_monument['ch_pat_statut']==0) { ?>Logo de la compagnie<?php } else { ?>Logo de la quête<br>ou image de couverture<?php }?></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img1" style="width: 400px;" id="ch_pat_lien_img1" class="span6" value="<?php echo e($row_monument['ch_pat_lien_img1']) ?>">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <!-- Description -->
        <div class="control-group" id="sprytextarea1">
          <label class="control-label" for="ch_pat_description">Description <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Mettez-ici une description de votre monument. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea name="ch_pat_description" style="width: 400px; height: 200px;" id="ch_pat_description" class="span6" rows="6"><?= e($row_monument['ch_pat_description']) ?></textarea>
            <span class="textareaRequiredMsg">Une valeur est requise.</span> <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">800 caract&egrave;res maximum.</span></div>
        </div>
        <!-- Liens -->
        <h5 style="padding-left: 2em; padding-bottom: 0.5em;">Connecter  <?php if ($row_monument['ch_pat_statut']==0) { ?>l'entreprise<?php } else { ?>la quête<?php }?> avec les plateformes GC :</h3>
        <div id="sprytextfield9" class="control-group">
          <label class="control-label" for="ch_pat_legende_img5">Lien sur le Forum GC  <a href="#" rel="clickover" title="Lien Forum GC" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" style="width: 400px;" id="ch_pat_legende_img5" name="ch_pat_legende_img5" value="<?= e($row_monument['ch_pat_legende_img5']) ?>" placeholder="https://www.forum-gc.com/"></div>
        </div>
        <div id="sprytextfield9" class="control-group">
          <label class="control-label" for="ch_pat_lien_img5">Lien sur le Wiki GC  <a href="#" rel="clickover" title="Lien Forum GC" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" style="width: 400px;" id="ch_pat_lien_img5" name="ch_pat_lien_img5" value="<?= e($row_monument['ch_pat_lien_img5']) ?>" placeholder="http://vasel.yt/wiki/index.php"></div>
        </div>
        <div id="sprytextfield9" class="control-group">
          <label class="control-label" for="ch_pat_legende_img1">Lien sur Squirrel  <a href="#" rel="clickover" title="Lien Forum GC" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" style="width: 400px;" id="ch_pat_legende_img1" name="ch_pat_legende_img1" value="<?= e($row_monument['ch_pat_legende_img1']) ?>" placeholder="https://squirrel.roxayl.fr/"></div>
        </div>

        <!--Images d'illustration -->
        <h3 style="padding-left: 2em; padding-bottom: 0.5em;">Ajouter des illustrations pour la bannière de haut de page :</h3>
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_pat_lien_img2">Image<br>d'illustration 1</label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img2" style="width: 103%;" id="ch_pat_lien_img2" class="span6" value="<?php echo e($row_monument['ch_pat_lien_img2']) ?>">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield8" class="control-group">
          <div class="controls" style="padding-left: 2em; margin-top: -2.3em;">
            <input type="text" name="ch_pat_legende_img2" style="width: 99%; font-style: oblique;" id="ch_pat_legende_img2" value="<?php echo e($row_monument['ch_pat_legende_img2']) ?>" placeholder="Légende de l'illustration">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_pat_lien_img3">Image<br>d'illustration 2</label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img3" style="width: 103%;" id="ch_pat_lien_img3" class="span6" value="<?php echo e($row_monument['ch_pat_lien_img3']) ?>">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield8" class="control-group">
          <div class="controls" style="padding-left: 2em; margin-top: -2.3em;">
            <input type="text" name="ch_pat_legende_img3" style="width: 99%; font-style: oblique;" id="ch_pat_legende_img3" value="<?php echo e($row_monument['ch_pat_legende_img3']) ?>" placeholder="Légende de l'illustration">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_pat_lien_img4">Image<br>d'illustration 3</label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img4" style="width: 103%;" id="ch_pat_lien_img4" class="span6" value="<?php echo e($row_monument['ch_pat_lien_img4']) ?>">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield8" class="control-group">
          <div class="controls" style="padding-left: 2em; margin-top: -2.3em;">
            <input type="text" name="ch_pat_legende_img4" style="width: 99%; font-style: oblique;" id="ch_pat_legende_img4" value="<?php echo e($row_monument['ch_pat_legende_img4']) ?>" placeholder="Légende de l'illustration">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>

        <div class="controls">
          <button type="submit" class="btn btn-primary">Envoyer</button>&nbsp;&nbsp;<a class="btn btn-danger" href="ville_modifier.php">Annuler</a>
        </div>
        <input type="hidden" name="MM_insert" value="ajout_monument">
        <input type="hidden" name="MM_update" value="modifier_monument">
      </form>
   </div>

<!-- Colonne droite -->
  <div class="span6" style="padding-left: 1em;">
              <div style="text-align: center;">
                <?php if(!empty($row_monument['ch_pat_lien_img1'])): ?>
                <img style="max-width: 500px; max-height: 200px; padding: 2em;"
                     src="<?= __s($row_monument['ch_pat_lien_img1']) ?>"><?php endif; ?>
              </div>

      <?php if($row_mon_cat_a['ch_mon_cat_ID'] = $row_liste_mon_cat3['ch_mon_cat_ID']) { ?>
         <h4 style="text-align: center; color: #1a2638;"> <?php echo $row_monument['ch_pat_nom']; ?> a déjà atteint <?php echo $nb_cat_ok ?> objectifs !<br><small>Cliquez sur les icones pour avoir plus de détail.</small></h4>
            <ul class="listes" style="text-align: center;">
               <?php do { ?> <a href="#" rel="clickover" title="<?= __s($row_liste_mon_cat3['ch_mon_cat_nom']) ?>" data-content="<?= __s($row_liste_mon_cat3['ch_mon_cat_desc']) ?>"><img style="max-width: 50px;" src="<?= __s($row_liste_mon_cat3['ch_mon_cat_icon']) ?>"></i></a>
            <?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)) ?>
            </ul>
           <?php mysql_free_result($liste_mon_cat3); ?>
         <?php } else { ?>
         <h4 style="text-align: center">Jusqu'à présent, <?php echo $row_monument['ch_pat_nom']; ?> ne s'est rien fait valider...</h4>
      <?php }?>
                <div class="clearfix"></div>
        <?php if($row_monument['ch_pat_statut'] == $row_liste_mon_cat_nope['ch_mon_cat_statut']) { ?>
        <div style="text-align: center; padding-bottom: 0.5em;"><br><br>Voici tous les <?php if ($nb_cat_ok!==0) { ?>autres<?php } else { ?><?php }?> objectifs que vous pourriez tenter d'obtenir :</div>
            <?php if($totalRows_liste_mon_cat_nope): ?>
            <ul class="listes" style="text-align: center;">
               <?php do { ?> <a href="#" rel="clickover" title="<?= __s($row_liste_mon_cat_nope['ch_mon_cat_nom']) ?>" data-content="<?= __s($row_liste_mon_cat_nope['ch_mon_cat_desc']) ?>"><img style="max-width: 50px;" src="<?= __s($row_liste_mon_cat_nope['ch_mon_cat_icon']) ?>"></i></a>
            <?php } while ($row_liste_mon_cat_nope = mysql_fetch_assoc($liste_mon_cat_nope)) ?>
            </ul>
           <?php mysql_free_result($liste_mon_cat_nope); ?>
            <?php endif; ?>
         <?php } else { ?>
         <div style="text-align: center; background-image: url('https://i11.servimg.com/u/f11/18/33/87/18/z_lum_11.jpg'); background-attachment: fixed; background-position: right; padding:2em; margin-top: 2em;"><h2>Vous avez terminé tous les objectifs disponibles pour le moment,<br> FÉLICITATIONS !
          <br><small style="font-size: 15px;">Et si vous proposiez de nouveaux objectifs à ajouter pour les <?php if ($row_monument['ch_pat_statut']==0) { ?>Entreprise<?php } else { ?>Quêtes<?php }?> ?</small></h4><br><button style="background: #f0eeec; color: black;" href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures" class="btn btn-primary">Je participe au projet</button></h2>
      <?php }?>


   </div>
</div>

    <div class="modal container fade" id="Modal-Monument"></div>

  </div>
  </div>
  <!-- END CONTENT
    ================================================== --> 
</div>

<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require(DEF_LEGACYROOTPATH . 'php/carte-ajouter-marqueur.php'); ?>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function() {
        $('[rel="clickover"]').clickover();
    })
</script>
<script>
 $( document ).ready(function() {
init();
});
</script>
 <!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script>
    $("a[data-toggle=modal]").click(function() {
        lv_target = $(this).attr('data-target');
        lv_url = $(this).attr('href');
        $(lv_target).load(lv_url);
    })

    $('#closemodal').click(function () {
        $('#Modal-Monument').modal('hide');
    });
</script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:50, validateOn:["change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {isRequired:false, validateOn:["change"], maxChars:250});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "none", {maxChars:50, validateOn:["change"], isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13", "url", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextfield14 = new Spry.Widget.ValidationTextField("sprytextfield14", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {minChars:2, validateOn:["change"], maxChars:800, useCharacterMasking:false});
</script>
</body>
</html>