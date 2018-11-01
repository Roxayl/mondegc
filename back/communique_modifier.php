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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modifier_communique")) {
  $updateSQL = sprintf("UPDATE communiques SET ch_com_label=%s, ch_com_statut=%s, ch_com_categorie=%s, ch_com_element_id=%s, ch_com_user_id=%s, ch_com_date=%s, ch_com_date_mis_jour=%s, ch_com_titre=%s, ch_com_contenu=%s WHERE ch_com_ID=%s",
                       GetSQLValueString($_POST['ch_com_label'], "text"),
                       GetSQLValueString($_POST['ch_com_statut'], "int"),
                       GetSQLValueString($_POST['ch_com_categorie'], "text"),
                       GetSQLValueString($_POST['ch_com_element_id'], "int"),
                       GetSQLValueString($_POST['ch_com_user_id'], "int"),
                       GetSQLValueString($_POST['ch_com_date'], "date"),
                       GetSQLValueString($_POST['ch_com_date_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_com_titre'], "text"),
                       GetSQLValueString($_POST['ch_com_contenu'], "text"),
                       GetSQLValueString($_POST['ch_com_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

if ( $_POST['ch_com_categorie'] == "pays") {
$updateGoTo = 'page_pays_back.php';
}
elseif ( $_POST['ch_com_categorie'] == "ville") {
$updateGoTo = 'ville_modifier.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 1)) {
$updateGoTo = 'institut_OCGC.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 2)) {
$updateGoTo = 'institut_geographie.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 3)) {
$updateGoTo = 'institut_patrimoine.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 4)) {
$updateGoTo = 'institut_histoire.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 5)) {
$updateGoTo = 'institut_economie.php';
}
elseif (( $_POST['ch_com_categorie'] == "institut") AND ( $_POST['ch_com_element_id'] == 6)) {
$updateGoTo = 'institut_sport.php';
}
else {
$updateGoTo = 'page_pays_back.php';
}

  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_communique = "-1";
if (isset($_POST['com_id'])) {
  $colname_communique = $_POST['com_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_communique = sprintf("SELECT * FROM communiques WHERE ch_com_ID = %s", GetSQLValueString($colname_communique, "int"));
$communique = mysql_query($query_communique, $maconnexion) or die(mysql_error());
$row_communique = mysql_fetch_assoc($communique);
$totalRows_communique = mysql_num_rows($communique);

$cat = $row_communique['ch_com_categorie'];
$elementID = $row_communique['ch_com_element_id'];

if ( $cat == "pays") {
  mysql_select_db($database_maconnexion, $maconnexion);
$query_cat_pays = sprintf("SELECT ch_pay_nom, ch_pay_devise, ch_pay_lien_imgdrapeau FROM pays WHERE ch_pay_id = %s", GetSQLValueString($elementID, "int"));
$cat_pays = mysql_query($query_cat_pays, $maconnexion) or die(mysql_error());
$row_cat_pays = mysql_fetch_assoc($cat_pays);
$totalRows_cat_pays = mysql_num_rows($cat_pays);

$ch_com_categorie = $cat;
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_cat_pays['ch_pay_nom'];
$insigne = $row_cat_pays['ch_pay_lien_imgdrapeau'];
$soustitre = $row_cat_pays['ch_pay_devise'];

mysql_free_result($cat_pays);
}

if ( $cat == "ville") {
  mysql_select_db($database_maconnexion, $maconnexion);
$query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_vil_specialite, ch_vil_armoiries, ch_pay_nom FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id WHERE ch_vil_ID = %s", GetSQLValueString($elementID, "int"));
$villes = mysql_query($query_villes, $maconnexion) or die(mysql_error());
$row_villes = mysql_fetch_assoc($villes);
$totalRows_villes = mysql_num_rows($villes);

$ch_com_categorie = $cat;
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_villes['ch_vil_nom'];
$insigne = $row_villes['ch_vil_armoiries'];
$soustitre = "Ville du pays " .$row_villes['ch_pay_nom'];

mysql_free_result($villes);
}

if ( $cat == "institut") {
mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT ch_ins_ID, ch_ins_nom, ch_ins_sigle, ch_ins_logo FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($elementID, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

$ch_com_categorie = $cat;
$ch_com_element_id = $colname_elementid;
$nom_organisation = $row_institut['ch_ins_sigle'];
$insigne = $row_institut['ch_ins_logo'];
$soustitre = $row_institut['ch_ins_nom'];

mysql_free_result($institut);
}

$colname_user = "-1";
if (isset($row_communique['ch_com_user_id'])) {
  $colname_user = $row_communique['ch_com_user_id'];
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_user = sprintf("SELECT ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_user, "int"));
$user = mysql_query($query_user, $maconnexion) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Modifier un communiqu&eacute;</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
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
</style>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">
  <div class="row-fluid communique">
    <section> 
      <!-- EN-tête Personnage pour communiquées officiels et commentaire-->
      <div class="span3 thumb"> <img src="<?php echo $row_user['ch_use_lien_imgpersonnage']; ?>" alt="photo <?php echo $row_user['ch_use_nom_dirigeant']; ?>">
        <div class="titre-gris">
          <p><?php echo $row_user['ch_use_predicat_dirigeant']; ?></p>
          <h3><?php echo $row_user['ch_use_prenom_dirigeant']; ?> <?php echo $row_user['ch_use_nom_dirigeant']; ?></h3>
          <small><?php echo $row_user['ch_use_titre_dirigeant']; ?></small> </div>
      </div>
      <div class="offset6 span3 thumb">
        <?php if (($row_communique['ch_com_categorie'] == "pays") || ($row_communique['ch_com_categorie'] == "ville") || ($row_communique['ch_com_categorie'] == "institut")) { ?>
        <!-- EN-tête Institution pour communiqués officiels-->
        
        <?php if ( $cat == "ville") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/blason.jpg" alt="armoirie">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="armoirie">
        <?php } ?>
        <?php } elseif ( $cat == "pays") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/drapeau.jpg" alt="drapeau">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="drapeau">
        <?php } ?>
        <?php } elseif ( $cat == "institut") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/blason.jpg" alt="logo">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="logo">
        <?php }
		 } else {?>
                <img src="<?php echo $insigne; ?>">
                <?php } ?>
        <div class="titre-gris">
          <h3><?php echo $nom_organisation; ?></h3>
          <small><?php echo $soustitre; ?></small> </div>
        <?php } ?>
      </div>
    </section>
  </div>
  <div class="row-fluid">
  
    <?php if (($row_communique['ch_com_categorie'] == "pays") || ($row_communique['ch_com_categorie'] == "ville")) { ?>
    <div class="titre-vert"> <img src="../assets/img/IconesBDD/100/Communique.png" alt="communiqu&eacute;">
      <h1>Modifier un communiqu&eacute;</h1>
      </div>
      <?php }?>
      <?php if ($row_communique['ch_com_categorie'] == "institut") { ?>
    <div class="titre-bleu"> <img src="../assets/img/IconesBDD/Bleu/100/Communique_bleu.png" alt="communiqu&eacute;">
      <h1>Modifier un communiqu&eacute;</h1>
      </div>
      <?php }?>
    <!-- Debut formulaire -->
    <form action="<?php echo $editFormAction; ?>" method="POST" name="modifier_communique" Id="modifier_communique">
      <!-- Bouton cachés -->
      <?php $now= date("Y-m-d G:i:s");?>
      <input name="ch_com_ID" type="hidden" value="<?php echo $row_communique['ch_com_ID']; ?>">
      <input name="ch_com_label" type="hidden" value="<?php echo $row_communique['ch_com_label']; ?>">
      <input name="ch_com_categorie" type="hidden" value="<?php echo $row_communique['ch_com_categorie']; ?>">
      <input name="ch_com_element_id" type="hidden" value="<?php echo $row_communique['ch_com_element_id']; ?>">
      <input name="ch_com_user_id" type="hidden" value="<?php echo $row_communique['ch_com_user_id']; ?>">
      <input name="ch_com_date" type="hidden" value="<?php echo $row_communique['ch_com_date']; ?>">
      <input name="ch_com_date_mis_jour" type="hidden" value="<?php echo $now; ?>">
      <!-- choix possibilité publication pour communiqués officiels-->
      <?php if (($row_communique['ch_com_categorie'] == "pays") || ($row_communique['ch_com_categorie'] == "ville") || ($row_communique['ch_com_categorie'] == "institut")) { ?>
      <!-- Statut -->
      <div id="spryradio1" class="form-inline pull-right"> Statut <a href="#" rel="clickover" title="Statut de votre communiqu&eacute;" data-content="
    Publi&eacute; : le communiqu&eacute; sera visible pour les visiteurs du site.
   brouillon : Retrouvez-le dans la liste de vos communiqu&eacute;s."><i class="icon-info-sign"></i></a> &nbsp;
        <label>
          <input <?php if (!(strcmp($row_communique['ch_com_statut'],"1"))) {echo "checked=\"checked\"";} ?> name="ch_com_statut" type="radio" id="ch_vil_capitale_1" value="1">
          Publi&eacute;</label>
        &nbsp;
        <label>
          <input <?php if (!(strcmp($row_communique['ch_com_statut'],"2"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_com_statut" value="2" id="ch_vil_capitale_2">
          Brouillon</label>
        &nbsp; <span class="radioRequiredMsg">Choisissez un statut pour votre communiqu&eacute;</span></div>
      <!-- Les commentaires sont automatiquements publiés-->
      <?php } else { ?>
      <input name="ch_com_statut" type="hidden" value="1">
      <?php } ?>
      <div class="span12 clearfix"></div>
      <!-- Titre uniquement dans le cas de communiqués officiels -->
      <?php if (($row_communique['ch_com_categorie'] == "pays") || ($row_communique['ch_com_categorie'] == "ville") || ($row_communique['ch_com_categorie'] == "institut")) { ?>
      <div id="sprytextfield1">
        <input name="ch_com_titre" type="text" class="span12" id="ch_com_titre" placeholder="Titre" value="<?php echo $row_communique['ch_com_titre']; ?>">
        <span class="textfieldMaxCharsMsg">100 caract&egrave;res max.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res min</span></div>
      <?php } ?>
      <p>&nbsp;</p>
      <!-- Contenu -->
      <textarea rows="20" name="ch_com_contenu" class="wysiwyg" id="ch_com_contenu"><?php echo $row_communique['ch_com_contenu']; ?></textarea>
      <p>&nbsp;</p>
      <button type="submit" class="btn btn-primary btn-margin-left">Envoyer</button>
      <input type="hidden" name="MM_update" value="modifier_communique">
    </form>
  </div>
</div>
<!-- END CONTENT
    ================================================== --> 

<!-- Footer
    ================================================== -->
<?php include('../php/footerback.php'); ?>
</body>
</html>
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
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>

<!-- SPRY ASSET -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:100, validateOn:["change"], minChars:2});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
</script>
<?php
mysql_free_result($communique);

mysql_free_result($user);
?>