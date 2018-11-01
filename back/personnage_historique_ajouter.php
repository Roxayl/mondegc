<?php
session_start();
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

$paysID = "-1";
if (isset($_POST['paysID'])) {
  $paysID = $_POST['paysID'];
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_fait_his")) {
	if ($_POST['ch_his_periode'] == true) {
$_POST['ch_his_date_fait2'] == NULL;
}	
  $insertSQL = sprintf("INSERT INTO histoire (ch_his_paysID, ch_his_label, ch_his_date, ch_his_personnage, ch_his_mis_jour, ch_his_nb_update, ch_his_date_fait, ch_his_date_fait2, ch_his_profession, ch_his_nom, ch_his_statut, ch_his_lien_img1, ch_his_legende_img1, ch_his_description, ch_his_contenu) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_his_paysID'], "int"),
                       GetSQLValueString($_POST['ch_his_label'], "text"),
                       GetSQLValueString($_POST['ch_his_date'], "date"),
                       GetSQLValueString($_POST['ch_his_personnage'], "int"),
                       GetSQLValueString($_POST['ch_his_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_his_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_his_date_fait'], "date"),
                       GetSQLValueString($_POST['ch_his_date_fait2'], "date"),
                       GetSQLValueString($_POST['ch_his_profession'], "text"),
                       GetSQLValueString($_POST['ch_his_nom'], "text"),
                       GetSQLValueString($_POST['ch_his_statut'], "int"),
                       GetSQLValueString($_POST['ch_his_lien_img1'], "text"),
                       GetSQLValueString($_POST['ch_his_legende_img1'], "text"),
                       GetSQLValueString($_POST['ch_his_description'], "text"),
                       GetSQLValueString($_POST['ch_his_contenu'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "page_pays_back.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


mysql_select_db($database_maconnexion, $maconnexion);
$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", GetSQLValueString($paysID, "int"));
$users = mysql_query($query_users, $maconnexion) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Ajouter un personnage historique</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../datepicker/css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css">
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
<div class="container" id="overview"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid"> 
    <!-- Page CONTENT
    ================================================== -->
    <div class=""> 
      <!-- Moderation
     ================================================== -->
      <div id="fait-historique" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/faithistorique.png">
        <h1>Ajouter un personnage historique</h1>
      </div>
      <?php if (($_SESSION['statut'] > 1) AND ($row_users['ch_use_id'] != $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="membre-modifier_back.php" method="post">
        <input name="userID" type="hidden" value="<?php echo $row_users['ch_use_id']; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $paysID; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <?php }?>
      <div class="clearfix"></div>
      <!-- Debut formulaire -->
      <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_fait_hist" Id="ajout_fait_his">
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Ce formulaire contient les informations qui seront affich&eacute;e sur la page consacr&eacute;e &agrave; un personnage historique. Les personnages historiques construisent l'histoire de votre pays. Veillez &agrave; ce qu'elle soit coh&eacute;rente avec les pays qui vous entourent. La gestions de l'histoire du Monde GC est confi&eacute;e &agrave; <a href="../histoire.php" title="lien vers la page consacr&eacute;e &agrave; l'Institut">l'Institut G&eacute;c&eacute;en d'Histoire</a></div>
        <!-- Bouton cachés -->
        <input name="ch_his_paysID" type="hidden" value="<?php echo $paysID; ?>" >
        <input name="ch_his_label" type="hidden" value="fait_histo">
        <input name="ch_his_personnage" type="hidden" value="2">
        <?php 
				  $now= date("Y-m-d G:i:s");?>
        <input name="ch_his_date" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_his_mis_jour" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_his_nb_update" type="hidden" value="0">
        <div class="row-fluid">
          <div class="span8"> 
            <!-- Statut -->
            <div id="spryradio1" class="control-group">
              <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de votre &eacute;v&eacute;nement" data-content="
    Visible : Ce fait historique sera visible pour les visiteurs du site.
    Invisible : Ce fait historique sera cach&eacute; pour les visiteurs du site."><i class="icon-info-sign"></i></a></div>
              <div class="controls">
                <label>
                  <input type="radio" name="ch_his_statut" value="1" id="ch_his_statut_1" checked="CHECKED">
                  visible</label>
                <label>
                  <input name="ch_his_statut" type="radio" id="ch_his_statut_2" value="2">
                  invisible</label>
                <span class="radioRequiredMsg">Choisissez un statut pour votre fait historique</span></div>
            </div>
            <!-- Nom -->
            <div id="sprytextfield2" class="control-group">
              <label class="control-label" for="ch_his_nom">Nom du personnage <a href="#" rel="clickover" title="Nom du personnage" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input class="span6" type="text" id="ch_his_nom" name="ch_his_nom" value="" placeholder="nom">
                <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
            </div>
            <!-- DATE -->
            <?php $now= date("Y-m-d");?>
            <div class="control-group">
              <label class="control-label" for="ch_his_date_fait">Date de naissance <a href="#" rel="clickover" title="Date de naissance" data-content="Choisissez la date de naissance de votre personnage"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append date" id="dpYears" data-date="<?php echo $now; ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                  <input class="span6" type="text" value="<?php echo $now; ?>" id="ch_his_date_fait" name="ch_his_date_fait"  readonly>
                  <span class="add-on"><i class="icon-calendar"></i></span> </div>
                  <label style="display:inline;">
              <input  type="checkbox" name="ch_his_periode" value="1" id="ch_his_periode">
              Personnage vivant</label>
              </div>
            </div>
            <!-- DATE 2-->
            <div class="control-group periode" style="display:block">
              <label class="control-label" for="ch_his_date_fait2">Date de d&eacute;c&egrave;s <a href="#" rel="clickover" title="Date de d&eacute;c&egrave;s" data-content="Choisissez la date de d&eacute;c&egrave;s si votre personnage ne fait plus partie de notre monde"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <div class="input-append date" id="dpYears2" data-date="<?php echo $now; ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
                  <input class="span6" type="text" value="<?php echo $now; ?>" id="ch_his_date_fait2" name="ch_his_date_fait2"  readonly>
                  <span class="add-on"><i class="icon-calendar"></i></span> </div>
              </div>
            </div>
            <!-- profession -->
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_his_profession">Profession <a href="#" rel="clickover" title="Profession" data-content="Indiquez la profession ou le r&ocirc;le social de ce personnage"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_his_profession" id="ch_his_profession" value="" class="span12">
            <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
            <!-- image -->
            <div id="sprytextfield5" class="control-group">
              <label class="control-label" for="ch_his_lien_img1">Lien portrait <a href="#" rel="clickover" title="Lien portrait" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input type="text" name="ch_his_lien_img1" id="ch_his_lien_img1" value="" class="span12">
                <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
            </div>
            <!-- Legende image -->
            <div id="sprytextfield6" class="control-group">
              <label class="control-label" for="ch_his_legende_img1">L&eacute;gende portrait <a href="#" rel="clickover" title="L&eacute;gende portrait" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <input type="text" name="ch_his_legende_img1" id="ch_his_legende_img1" class="span12">
                <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
            </div>
            <!-- Description -->
            <div class="control-group" id="sprytextarea1">
              <label class="control-label" for="ch_his_description">Biographie <a href="#" rel="clickover" title="Biographie" data-content="Mettez-ici le r&eacute;sum&eacute; de la vie de votre personnage historique. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
              <div class="controls">
                <textarea name="ch_his_description" id="ch_his_description" class="span12" rows="6"></textarea>
                <span class="textareaRequiredMsg">Une valeur est requise.</span> <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">800 caract&egrave;res maximum.</span></div>
            </div>
          </div>
          <div class="span4 pull-center">
            <p>&nbsp;</p>
            <img id="portrait" src="../assets/img/imagesdefaut/personnage.jpg" alt="Personnage" width="250" height="250" title="Personnage"></div>
        </div>
        <!-- Contenu -->
        <div class="control-group">
          <label class="control-label" for="ch_his_contenu">Biographie d&eacute;taill&eacute;e <a href="#" rel="clickover" title="Biographie d&eacute;taill&eacute;e" data-content="Mettez-ici le détail de la vie de votre personnage historique. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea name="ch_his_contenu" id="ch_his_contenu" class="wysiwyg" rows="15"></textarea>
          </div>
        </div>
        <div class="controls">
          <button type="submit" class="btn btn-primary">Envoyer</button>
          &nbsp;&nbsp;<a class="btn btn-danger" href="page_pays_back.php">Annuler</a> </div>
        <input type="hidden" name="MM_insert" value="ajout_fait_his">
        <p>&nbsp;</p>
      </form>
    </div>
  </div>
  <!-- END CONTENT
    ================================================== --> 
</div>
<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php include('../php/carte-ajouter-marqueur.php'); ?>
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
<!-- DATE PICKER -->
<script src="../datepicker/js/bootstrap-datepicker.js"></script>
<script>
$(function(){
			window.prettyPrint && prettyPrint();
			$('#dpYears').datepicker();
			$('#dpYears2').datepicker();
		});
</script>
<script>
$('#ch_his_periode').change(function () {
    if ($(this).attr("checked")) 
    {
        $('.periode').fadeOut();
        return;
    }
   $('.periode').fadeIn();
});
</script>
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>

<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:50, validateOn:["change"], minChars:2});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {validateOn:["change"], maxChars:250});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "none", {isRequired:false, maxChars:250, validateOn:["change"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {minChars:2, validateOn:["change"], maxChars:800, useCharacterMasking:false});
</script>