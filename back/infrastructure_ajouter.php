<?php

require_once('../Connections/maconnexion.php'); 
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'])
{
} else {
// Redirection vers Haut Conseil
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
}

$ville_ID = "-1";
if (isset($_REQUEST['ville_ID'])) {
  $ville_ID = $_REQUEST['ville_ID'];
}
elseif (isset($_REQUEST['ch_inf_villeid'])) {
  $ville_ID = $_REQUEST['ch_inf_villeid'];
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/** @var \GenCity\Monde\User $thisUser */
$thisUser = $_SESSION['userObject'];

if(isset($_REQUEST['infra_id']))
    $form_action = 'edit';
else
    $form_action = 'add';

if($form_action === 'edit') {

    $thisInfra = new \GenCity\Monde\Temperance\Infrastructure($_REQUEST['infra_id']);
    $infraOfficielle = new \GenCity\Monde\Temperance\InfraOfficielle($thisInfra->get('ch_inf_off_id'));
    $infraGroup = $infraOfficielle->getGroup();
    $thisVille = new \GenCity\Monde\Ville($thisInfra->get('ch_inf_villeid'));
    $thisPays = new \GenCity\Monde\Pays($thisVille->get('ch_vil_paysID'));

}

else {

    if(!isset($_REQUEST['infra_group_id'])) {
        throw new InvalidArgumentException("Veuillez spécifier un groupe d'infra.");
    }

    $thisInfra = new \GenCity\Monde\Temperance\Infrastructure(null);
    $infraGroup = new \GenCity\Monde\Temperance\InfraGroup($_REQUEST['infra_group_id']);
    $thisVille = new \GenCity\Monde\Ville($ville_ID);
    $thisPays = new \GenCity\Monde\Pays($thisVille->get('ch_vil_paysID'));

}

$paysID = $thisPays->get('ch_pay_id');


if ($form_action === 'add' && isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "ajout_infrastructure") {
  $insertSQL = sprintf("INSERT INTO infrastructures (ch_inf_label, ch_inf_off_id, ch_inf_villeid, ch_inf_date, ch_inf_statut, nom_infra, ch_inf_lien_image, ch_inf_lien_image2, ch_inf_lien_image3, ch_inf_lien_image4, ch_inf_lien_image5, ch_inf_lien_forum, lien_wiki, ch_inf_commentaire, ch_inf_juge, ch_inf_commentaire_juge) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_inf_label'], "text"),
					   GetSQLValueString($_POST['ch_inf_off_id'], "int"),
                       GetSQLValueString($_POST['ch_inf_villeid'], "int"),
                       GetSQLValueString($_POST['ch_inf_date'], "date"),
                       GetSQLValueString($_POST['ch_inf_statut'], "int"),
                       GetSQLValueString($_POST['nom_infra'], "text"),
                       GetSQLValueString($_POST['ch_inf_lien_image'], "text"),
                       GetSQLValueString($_POST['ch_inf_lien_image2'], "text"),
                       GetSQLValueString($_POST['ch_inf_lien_image3'], "text"),
                       GetSQLValueString('', "text"),
                       GetSQLValueString('', "text"),
                       GetSQLValueString($_POST['ch_inf_lien_forum'], "text"),
                       GetSQLValueString($_POST['lien_wiki'], "text"),
                       GetSQLValueString($_POST['ch_inf_commentaire'], "text"),
                       GetSQLValueString($_POST['ch_inf_juge'], "int"),
                       GetSQLValueString($_POST['ch_inf_commentaire_juge'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "ville_modifier.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

  getErrorMessage('success', "Une infrastructure a été ajoutée avec succès !");

  header(sprintf("Location: %s", $insertGoTo));
  exit;

}

if($form_action === 'edit' && isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "ajout_infrastructure") {

    $formData = array(
        'nom_infra' => $_POST['nom_infra'],
        'ch_inf_lien_image' => $_POST['ch_inf_lien_image'],
        'ch_inf_lien_image2' => $_POST['ch_inf_lien_image2'],
        'ch_inf_lien_image3' => $_POST['ch_inf_lien_image3'],
        'ch_inf_lien_forum' => $_POST['ch_inf_lien_forum'],
        'lien_wiki' => $_POST['lien_wiki']
    );

    $thisInfra->set('nom_infra', $_POST['nom_infra']);
    $thisInfra->set('ch_inf_lien_image', $_POST['ch_inf_lien_image']);
    $thisInfra->set('ch_inf_lien_image2', $_POST['ch_inf_lien_image2']);
    $thisInfra->set('ch_inf_lien_image3', $_POST['ch_inf_lien_image3']);
    $thisInfra->set('ch_inf_lien_forum', $_POST['ch_inf_lien_forum']);
    $thisInfra->set('lien_wiki', $_POST['lien_wiki']);

    $thisInfra->update();

    $insertGoTo = "ville_modifier.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }

    getErrorMessage('success', "L'infrastructure a été modifiée avec succès !");

    header(sprintf("Location: %s", $insertGoTo));
    exit;

}


//requete user 
mysql_select_db($database_maconnexion, $maconnexion);
$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", GetSQLValueString($paysID, "int"));
$users = mysql_query($query_users, $maconnexion) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

//requete liste infrastructures officielles
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_inf_off = sprintf(
    "SELECT infrastructures_officielles.* FROM infrastructures_officielles
     JOIN infrastructures_officielles_groupes ON ID_infra_officielle = ch_inf_off_id
     WHERE ID_groupes = %s
     ORDER BY ch_inf_off_nom ASC",
    GetSQLValueString($infraGroup->get('id'), 'int'));
$liste_inf_off = mysql_query($query_liste_inf_off, $maconnexion) or die(mysql_error());
$row_liste_inf_off = mysql_fetch_assoc($liste_inf_off);
$totalRows_liste_inf_off = mysql_num_rows($liste_inf_off);

//requete Infrastructure officielles choisie pour affichage des infos
$colname_inf_off_choisie = "-1";
if (isset($_REQUEST['infra_off_id'])) {
    $colname_inf_off_choisie = $_REQUEST['infra_off_id'];
} elseif($form_action === 'edit') {
    $colname_inf_off_choisie = $thisInfra->get('ch_inf_off_id');
} else {
    $colname_inf_off_choisie = -1;
} 
mysql_select_db($database_maconnexion, $maconnexion);
$query_inf_off_choisie = sprintf("SELECT * FROM infrastructures_officielles WHERE ch_inf_off_id = %s", GetSQLValueString($colname_inf_off_choisie, "int"));
$inf_off_choisie = mysql_query($query_inf_off_choisie, $maconnexion) or die(mysql_error());
$row_inf_off_choisie = mysql_fetch_assoc($inf_off_choisie);
$totalRows_inf_off_choisie = mysql_num_rows($inf_off_choisie);

if($totalRows_liste_inf_off == 0) {
    getErrorMessage('error', "Il n'y a pas d'infrastructure de ce type.");
    header('Location: infra_select_group.php?ville_id=' . __s($thisVille->get('ch_vil_ID')));
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Monde GC - <?= $form_action === 'add' ? 'Ajouter' : 'Modifier' ?> une infrastructure</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
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
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>
</header>
<div class="container corps-page" id="overview">
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid"> 
    <!-- Page CONTENT
    ================================================== --> 
    <!-- Moderation
     ================================================== -->
    <div id="infrastructure" class="titre-vert anchor">
      <h1><?= $form_action === 'add' ? 'Ajouter' : 'Modifier' ?> une infrastructure<br>
        <small><img src="<?= __s((empty($thisVille->get('ch_vil_armoiries')) ? '../assets/img/imagesdefaut/blason.jpg' : $thisVille->get('ch_vil_armoiries')) ) ?>" style="height: 24px; width: 24px;"> Ville de <?= __s($thisVille->get('ch_vil_nom')) ?></small></h1>
    </div>

    <ul class="breadcrumb">
      <li><a href="page_pays_back.php?paysID=<?= $thisVille->get('ch_vil_paysID') ?>&userID=<?= $thisUser->get('ch_use_id') ?>"
          >Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a> <span class="divider">/</span></li>
      <li><a href="ville_modifier.php?ville-ID=<?= $thisVille->get('ch_vil_ID') ?>"
          >Gestion de la ville : <?= __s($thisVille->get('ch_vil_nom')) ?></a> <span class="divider">/</span></li>
      <li class="active"><?= $form_action === 'add' ? 'Ajouter' : 'Modifier' ?> une infrastructure</li>
    </ul>

    <?php renderElement('errormsgs'); ?>

    <div class="well" style="margin-top: -20px;">
        <div class="alert alert-tips">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Les infrastructures sont des éléments de gameplay du Monde GC qui permettent de matérialiser vos créations en leur attribuant des points de 8 catégories, les fameuses ressources Tempérance.
            <a class="guide-link" href="http://vasel.yt/wiki/index.php?title=GO/Infrastructures#Ajouter_une_infrastructure">Besoin d'aide ? GO!</a>
        </div>
    </div>


    <div class="row-fluid">
      <div class="span6 well">

      <h3 style="margin: 0;"><?= __s($infraGroup->get('nom_groupe')) ?>
          <?php if($form_action === 'add'): ?>
            <small style="display: inline;"><a href="infra_select_group.php?ville_id=<?= $thisVille->get('ch_vil_ID') ?>"
                >(Changer)</a></small>
          <?php endif; ?>
      </h3>

      <br><br>

        <!-- choix infrastructure -->
        <form action="infrastructure_ajouter.php#infrastructure" method="GET" id="form-infra-list"
              class="form-inline">
          <div id="spryselect1" class="control-group">
          <div class="control-label"><h4 style="display: inline-block;">Choisissez votre infrastructure</h4> <a href="#" rel="clickover" title="Infrastructures de la liste officielle" data-content="Vous devez choisir une infrastructure dans la liste officielle. Chaque nouvelle infrastructure va modifier les valeurs de votre économie"><i class="icon-info-sign"></i></a></div>
          <div class="controls">
          <select name="infra_off_id" id="infra_off_id" placeholder="Rechercher une infrastructure..." <?= ($form_action === 'edit') ? 'disabled' : '' ?>>
            <option value=""></option>
            <?php do { ?>
            <option value="<?php echo $row_liste_inf_off['ch_inf_off_id']; ?>" <?php if ($colname_inf_off_choisie == $row_liste_inf_off['ch_inf_off_id']) {?>selected<?php } ?>><?php echo $row_liste_inf_off['ch_inf_off_nom']; ?></option>
            <?php } while ($row_liste_inf_off = mysql_fetch_assoc($liste_inf_off)); ?>
          </select>
      <input name="ville_ID" type="hidden" value="<?php echo $ville_ID; ?>">
      <input name="infra_group_id" type="hidden" value="<?= $infraGroup->get('id') ?>">
        <span class="selectRequiredMsg">Sélectionnez un élément.</span></div>
    </div>
        </form>
    <!-- Debut formulaire -->
    <form action="<?php echo $editFormAction; ?>" method="POST" class="" name="ajout_infrastructure" Id="ajout_infrastructure">

      <!-- En cas d'édition -->
      <?php if($form_action === 'edit'): ?>
        <input name="infra_id" type="hidden" value="<?= __s($thisInfra->get('ch_inf_id')) ?>">
      <?php endif; ?>
      
      <!-- Bouton cachés -->
      <input name="ch_inf_villeid" type="hidden" value="<?php echo $ville_ID; ?>">
      <input name="ch_inf_label" type="hidden" value="infrastructure">
      <input name="ch_inf_off_id" type="hidden" value="<?php echo $colname_inf_off_choisie; ?>">
      <input name="ch_inf_juge" type="hidden" value="">
      <input name="infra_group_id" type="hidden" value="<?= $infraGroup->get('id') ?>">
      <?php 
      $now = date("Y-m-d G:i:s");?>
      <input name="ch_inf_date" type="hidden" value="<?php echo $now; ?>" >
      <input name="ch_inf_statut" type="hidden" value="1">
      <input name="ch_inf_commentaire_juge" type="hidden" value="">

      <div id="infra_add_form_container" style="<?= empty($row_inf_off_choisie['ch_inf_off_nom']) && !isset($_REQUEST['infra_id']) ? 'display: none;' : ''; ?>">

          <h4>Informations générales</h4>

          <!-- Nom de l'infra -->
          <div id="sprytextfield_nom_infra" class="control-group">
            <label class="control-label" for="nom_infra">Nom de l'infrastructure <a href="#" rel="clickover" title="Nom de l'infrastructure" data-content="Un joli nom pour votre infrastructure ! Ce champ est obligatoire."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="span12" type="text" id="nom_infra" name="nom_infra" value="<?= __s($thisInfra->get('nom_infra')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caractères minimum.</span></div>
          </div>

          <!-- Description -->
          <div class="control-group" id="sprytextarea1">
            <label class="control-label" for="ch_inf_commentaire">Description <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Mettez &eacute;ventuellement une description rapide de votre infrastructure pour aider les juges &agrave; accepter votre demande. 400 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <textarea name="ch_inf_commentaire" id="ch_inf_commentaire" class="span12" rows="6"><?= __s($thisInfra->get('ch_inf_commentaire')) ?></textarea>
              <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">400 caract&egrave;res maximum.</span></div>
          </div>

          <!-- Image -->
          <div id="sprytextfield" class="control-group">
            <label class="control-label" for="ch_inf_lien_image">Image de votre infrastructure <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Copiez un lien vers une image qui prouve la construction de l'infrastructure dans l'un des jeux accept&eacute; par le site du Monde GC. Il vous appartient de veiller &agrave; ce que l'image montre clairement cette infrastructure avec les crit&egrave;res requis. Le moindre doute signifiera un refus. Ce champ est obligatoire."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="span12" type="text" id="ch_inf_lien_image" name="ch_inf_lien_image" value="<?= __s($thisInfra->get('ch_inf_lien_image')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>

          <!-- Image2 -->
          <div id="sprytextfield2" class="control-group">
            <label class="control-label" for="ch_inf_lien_image2">Image de votre infrastructure n°2 <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Image suppl&eacute;mentaire. Ce champ est optionnel."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="span12" type="text" id="ch_inf_lien_image2" name="ch_inf_lien_image2" value="<?= __s($thisInfra->get('ch_inf_lien_image2')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>

          <!-- Image3 -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_inf_lien_image3">Image de votre infrastructure n°3 <a href="#" rel="clickover" title="Image de l'infrastructure" data-content="Image suppl&eacute;mentaire. Ce champ est optionnel."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="span12" type="text" id="ch_inf_lien_image3" name="ch_inf_lien_image3" value="<?= __s($thisInfra->get('ch_inf_lien_image3')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>

            <h4>Lier l'infrastructure aux autres services GC</h4>

           <!-- Lien forum -->
          <div class="control-group" id="sprytextfield6">
            <label class="control-label" for="ch_inf_lien_forum">
                <span class="external-link-icon"
                 style="background-image:url('http://www.generation-city.com/forum/new/favicon.png');"></span>
                Lien sur le forum
                <a href="#" rel="clickover" title="Lien vers la page de pr&eacute;sentation" data-content="L'infrastructure doit obligatoirement appartenir &agrave; une ville pr&eacute;sent&eacute;e sur le forum. Mettez le lien vers la page du sujet o&ugrave; est present&eacute;e votre infrastructure"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input name="ch_inf_lien_forum" id="ch_inf_lien_forum" class="span12" type="text" value="<?= __s($thisInfra->get('ch_inf_lien_forum')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
          </div>

           <!-- Lien wiki -->
          <div class="control-group" id="sprytextfield_lien_wiki">
            <label class="control-label" for="lien_wiki">
                <span class="external-link-icon"
                 style="background-image:url('https://romukulot.fr/kaleera/images/h4FQp.png');"></span>
                Lien sur le wiki
                <a href="#" rel="clickover" title="Lien vers le Wiki GC" data-content="Si nécessaire, précisez un lien vers le wiki."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input name="lien_wiki" id="lien_wiki" class="span12" type="text" value="<?= __s($thisInfra->get('lien_wiki')) ?>">
              <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
          </div>

          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <input type="hidden" name="MM_insert" value="ajout_infrastructure">

      </div>

    </form>
    <form action="ville_modifier.php#mes-infrastructures" method="GET" class="form-inline">
      <input name="villeid" type="hidden" value="<?= $thisVille->get('ch_vil_ID') ?>">
      <button type="submit" class="btn btn-danger" title="retour &agrave; la page de modification du pays">Annuler</button>
    </form>
    <p>&nbsp;</p>
  </div>
  <div class="span6 well">
      <div class="span info-infrastructure-off">
        <div class="row-fluid">

        <?php if(empty($row_inf_off_choisie['ch_inf_off_nom'])): ?>
            <div class="span12"><h3>Veuillez choisir une infrastructure.</h3></div>

        <?php else: ?>
          <div class="span3 img-listes img-avatar">
              <img src="<?php echo $row_inf_off_choisie['ch_inf_off_icone']; ?>">
          </div>
          <div class="span9">
            <h2><?php echo $row_inf_off_choisie['ch_inf_off_nom']; ?></h2>
            <p><?php echo $row_inf_off_choisie['ch_inf_off_desc']; ?></p>
          </div>
        <?php endif; ?>

        </div>

        <h4>Influence</h4>
        <div class="row-fluid">
          <div class="span6 well icone-ressources">
            <img src="../assets/img/ressources/budget.png" alt="icone Budget"><p>Budget&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_budget']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/industrie.png" alt="icone Industrie"><p>Industrie&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Industrie']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/bureau.png" alt="icone Commerce"><p>Commerce&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Commerce']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/agriculture.png" alt="icone Agriculture"><p>Agriculture&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Agriculture']; ?></strong></p>
            <div class="clearfix"></div>
          </div>
          <div class="span6 well icone-ressources">
            <img src="../assets/img/ressources/tourisme.png" alt="icone Tourisme"><p>Tourisme&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Tourisme']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/recherche.png" alt="icone Recherche"><p>Recherche&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Recherche']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/environnement.png" alt="icone Evironnement"><p>Environnement&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Environnement']; ?></strong></p>
            <div class="clearfix"></div>
            <img src="../assets/img/ressources/education.png" alt="icone Education"><p>Education&nbsp;: <strong><?php echo $row_inf_off_choisie['ch_inf_off_Education']; ?></strong></p>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
  </div>
</div>
<!-- END CONTENT
    ================================================== -->
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() {
          $('[rel="clickover"]').clickover();})
    </script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script type="text/javascript">
var sprytextfield_nom_infra = new Spry.Widget.ValidationTextField("sprytextfield_nom_infra", "none", {maxChars:250, minChars:2, validateOn:["change"], isRequired:true});
var sprytextfield = new Spry.Widget.ValidationTextField("sprytextfield", "url", {maxChars:250, validateOn:["change"], isRequired:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "url", {maxChars:250, validateOn:["change"], isRequired:true});
var sprytextfield_lien_wiki = new Spry.Widget.ValidationTextField("sprytextfield_lien_wiki", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {validateOn:["change"], maxChars:400, isRequired:false, useCharacterMasking:false});

$(document).ready(function () {
  $('select').selectize({
      sortField: 'text',
      dropdownParent: "body",
      onChange: function() {
          $('#spryselect1').find('.control-label').html('<img class="pull-right" src="https://squirrel.romukulot.fr/media/icons/ajax-loader2.gif"> <i class="icon-time"></i> Chargement... ');
          $('#form-infra-list').submit();
      }
  });
});
</script>
</body>
</html>