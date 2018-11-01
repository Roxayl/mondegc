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

$ville_ID = "-1";
if (isset($_POST['ville_ID'])) {
  $ville_ID = $_POST['ville_ID'];
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_monument")) {
  $insertSQL = sprintf("INSERT INTO patrimoine (ch_pat_paysID, ch_pat_villeID, ch_pat_label, ch_pat_date, ch_pat_mis_jour, ch_pat_nb_update, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_nom, ch_pat_statut, ch_pat_lien_img1, ch_pat_lien_img2, ch_pat_lien_img3, ch_pat_lien_img4, ch_pat_lien_img5, ch_pat_legende_img1, ch_pat_legende_img2, ch_pat_legende_img3, ch_pat_legende_img4, ch_pat_legende_img5, ch_pat_description) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_pat_paysID'], "int"),
					   GetSQLValueString($_POST['ch_pat_villeID'], "int"),
                       GetSQLValueString($_POST['ch_pat_label'], "text"),
                       GetSQLValueString($_POST['ch_pat_date'], "date"),
                       GetSQLValueString($_POST['ch_pat_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_pat_nb_update'], "int"),
                       GetSQLValueString($_POST['form_coord_X'], "decimal"),
                       GetSQLValueString($_POST['form_coord_Y'], "decimal"),
                       GetSQLValueString($_POST['ch_pat_nom'], "text"),
                       GetSQLValueString($_POST['ch_pat_statut'], "int"),
                       GetSQLValueString($_POST['ch_pat_lien_img1'], "text"),
                       GetSQLValueString($_POST['ch_pat_lien_img2'], "text"),
                       GetSQLValueString($_POST['ch_pat_lien_img3'], "text"),
                       GetSQLValueString($_POST['ch_pat_lien_img4'], "text"),
                       GetSQLValueString($_POST['ch_pat_lien_img5'], "text"),
                       GetSQLValueString($_POST['ch_pat_legende_img1'], "text"),
                       GetSQLValueString($_POST['ch_pat_legende_img2'], "text"),
                       GetSQLValueString($_POST['ch_pat_legende_img3'], "text"),
                       GetSQLValueString($_POST['ch_pat_legende_img4'], "text"),
                       GetSQLValueString($_POST['ch_pat_legende_img5'], "text"),
                       GetSQLValueString($_POST['ch_pat_description'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "ville_modifier.php";
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
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Ajouter un monument</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
<script>
function verif_champ(form_coord_X)
{
if ((form_coord_X == "") || (form_coord_X == 0))
{ alert("Vous devez obligatoirement indiquer l'emplacement de votre ville en cliquant sur la carte");
return false;
}
return true;
}
</script> 
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140">
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
      <div id="monument" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/monument1.png">
        <h1>Ajouter un monument</h1>
      </div>
      <?php if (($_SESSION['statut'] >= 20) AND ($row_users['ch_use_id'] != $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="membre-modifier_back.php" method="post">
        <input name="userID" type="hidden" value="<?php echo $row_users['ch_use_id']; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $paysID; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <form class="pull-right" action="ville_modifier.php" method="post">
        <input name="ville-ID" type="hidden" value="<?php echo $row_monument['ch_pat_villeID']; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion de la ville"> Modifier la ville</button>
      </form>
      <?php }?>
      <div class="clearfix"></div>
      <!-- Debut formulaire -->
      <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_monument" Id="ajout_monument" onsubmit='return verif_champ(document.ajout_monument.form_coord_X.value);' >
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Ce formulaire contient les informations qui seront affich&eacute;e sur la page consacr&eacute;e &agrave; votre monument. Les monuments sont des constructions exceptionelles de votre pays. La promotion des monuments du Monde GC est confi&eacute;e &agrave; <a href="../patrimoine.php" title="lien vers la page consacr&eacute;e &agrave; l'Institut">l'Institut G&eacute;c&eacute;en du Patrimoine</a></div>
        <!-- Bouton cachés -->
        <input name="ch_pat_paysID" type="hidden" value="<?php echo $paysID; ?>" >
        <input name="ch_pat_villeID" type="hidden" value="<?php echo $ville_ID; ?>">
        <input name="ch_pat_label" type="hidden" value="monument">
        <?php 
				  $now= date("Y-m-d G:i:s");?>
        <input name="ch_pat_date" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_pat_mis_jour" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_pat_nb_update" type="hidden" value="0">
        <!-- Statut -->
        <div id="spryradio1" class="control-group">
          <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de votre monument" data-content="
    Visible : le monument sera visible pour les visiteurs du site.
    Invisible : le monument sera cach&eacute; pour les visiteurs du site."><i class="icon-info-sign"></i></a></div>
          <div class="controls">
            <label>
              <input type="radio" name="ch_pat_statut" value="1" id="ch_pat_statut_1" checked="CHECKED">
              visible</label>
            <label>
              <input name="ch_pat_statut" type="radio" id="ch_pat_statut_2" value="2">
              invisible</label>
            <span class="radioRequiredMsg">Choisissez un statut pour votre monument</span></div>
        </div>
        <!-- Nom -->
        <div id="sprytextfield2" class="control-group">
          <label class="control-label" for="ch_pat_nom">Nom du Monument <a href="#" rel="clickover" title="Nom du monument" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" id="ch_pat_nom" name="ch_pat_nom" value="" placeholder="mon monument">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Description -->
        <div class="control-group" id="sprytextarea1">
          <label class="control-label" for="ch_pat_description">Description <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Mettez-ici une description de votre monument. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea name="ch_pat_description" id="ch_pat_description" class="span6" rows="6"></textarea>
            <span class="textareaRequiredMsg">Une valeur est requise.</span> <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">800 caract&egrave;res maximum.</span></div>
        </div>
        <!-- Placement sur carte monde GC -->
        <h3>Carte</h3>
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <h4>Attention&nbsp;!</h4>
          Vous devez obligatoirement placer votre monument sur la carte du Monde GC. Veillez &agrave; placer votre monument &agrave; l'int&eacute;rieur des fronti&egrave;res de votre pays.</div>
        <!-- Coordonnées -->
        <div id="map"></div>
        <p>&nbsp;</p>
        <div class="control-group">
          <label class="control-label">Coordonn&eacute;es X</label>
          <div class="controls">
            <input class="span2" type="text" name="form_coord_X" id="form_coord_X" placeholder="cliquez sur la carte" readonly required>
          <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span> <span class="textfieldRequiredMsg">Une valeur est requise. Cliquez sur la carte</span></div>
        </div>
        <div class="control-group">
          <label class="control-label">Coordonn&eacute;es Y</label>
          <div class="controls">
        <input class="span2" type="text" name="form_coord_Y" id="form_coord_Y" placeholder="cliquez sur la carte" readonly required>
          </div>
        </div>
        <p>&nbsp;</p>
        <hr>
        <h3>Carrousel</h3>
        <!-- Carousel -->
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Le carrousel est une galerie d'images qui va d&eacute;filer en t&ecirc;te de la page de votre monument. La premi&egrave;re image sera reprise pour illustrer votre monument dans l'ensemble du site.</div>
        <div id="sprytextfield5" class="control-group">
          <label class="control-label" for="ch_pat_lien_img1">Lien image n&deg;1 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img1" id="ch_pat_lien_img1" value="" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield6" class="control-group">
          <label class="control-label" for="ch_pat_legende_img1">L&eacute;gende image n&deg;1 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_legende_img1" id="ch_pat_legende_img1">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_pat_lien_img2">Lien image n&deg;2 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img2" id="ch_pat_lien_img2" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield8" class="control-group">
          <label class="control-label" for="ch_pat_legende_img2">L&eacute;gende image n&deg;2 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_legende_img2" id="ch_pat_legende_img2">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield9" class="control-group">
          <label class="control-label" for="ch_pat_lien_img3">Lien image n&deg;3 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img3" id="ch_pat_lien_img3"class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield10" class="control-group">
          <label class="control-label" for="ch_pat_legende_img3">L&eacute;gende image n&deg;3 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_legende_img3" id="ch_pat_legende_img3">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield11" class="control-group">
          <label class="control-label" for="ch_pat_lien_img4">Lien image n&deg;4 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img4" id="ch_pat_lien_img4" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield12" class="control-group">
          <label class="control-label" for="ch_pat_legende_img4">L&eacute;gende image n&deg;4 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_legende_img4" id="ch_pat_legende_img4">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield13" class="control-group">
          <label class="control-label" for="ch_pat_lien_img5">Lien image n&deg;5 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_lien_img5" id="ch_pat_lien_img5" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield14" class="control-group">
          <label class="control-label" for="ch_pat_legende_img5">L&eacute;gende image n&deg;5 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_pat_legende_img5" id="ch_pat_legende_img5">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div class="controls">
          <button type="submit" class="btn btn-primary">Envoyer</button>&nbsp;&nbsp;<a class="btn btn-danger" href="ville_modifier.php">Annuler</a>
        </div>
        <input type="hidden" name="MM_insert" value="ajout_monument">
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
<script> 
 $( document ).ready(function() {
init();
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