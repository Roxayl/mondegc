<?php


require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//requete instituts
$institut_id = -1;
if (isset($_POST['institut_id'])) {
    $institut_id = $_POST['institut_id'];
  }

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "modifier_institut")) {

  $oldInstitut = new \GenCity\Monde\Institut\Institut($_POST['ch_ins_ID']);

  $updateSQL = sprintf("UPDATE instituts SET ch_ins_label=%s, ch_ins_lien_forum=%s, ch_ins_date_enregistrement=%s, ch_ins_mis_jour=%s, ch_ins_nb_update=%s, ch_ins_user_ID=%s, ch_ins_sigle=%s, ch_ins_nom=%s, ch_ins_statut=%s, ch_ins_logo=%s, ch_ins_img=%s, ch_ins_desc=%s, ch_ins_coord_X=%s, ch_ins_coord_Y=%s WHERE ch_ins_ID=%s",
                       GetSQLValueString($_POST['ch_ins_label'], "text"),
                       GetSQLValueString($_POST['ch_ins_lien_forum'], "text"),
                       GetSQLValueString($_POST['ch_ins_date_enregistrement'], "date"),
                       GetSQLValueString($_POST['ch_ins_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_ins_nb_update'], "int"),
                       GetSQLValueString($_POST['ch_ins_user_ID'], "int"),
                       GetSQLValueString($_POST['ch_ins_sigle'], "text"),
                       GetSQLValueString($_POST['ch_ins_nom'], "text"),
                       GetSQLValueString($_POST['ch_ins_statut'], "int"),
                       GetSQLValueString($_POST['ch_ins_logo'], "text"),
                       GetSQLValueString($_POST['ch_ins_img'], "text"),
                       GetSQLValueString($_POST['ch_ins_desc'], "text"),
					   GetSQLValueString($_POST['form_coord_X'], "decimal"),
                       GetSQLValueString($_POST['form_coord_Y'], "decimal"),
                       GetSQLValueString($_POST['ch_ins_ID'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());

  $newInstitut = new \GenCity\Monde\Institut\Institut($_POST['ch_ins_ID']);

  getErrorMessage('success', "La description de " . __s($newInstitut->get('ch_ins_nom')) .
      " a été modifiée !");

  \GenCity\Monde\Logger\Log::createItem('instituts', $newInstitut->get('ch_ins_ID'), 'update',
      null, array('entity' => $newInstitut->model->getInfo(), 'old_entity' => $oldInstitut->model->getInfo()));
  
  if ($_POST['ch_ins_ID']==1) {
    $updateGoTo = "institut_OCGC.php";
} elseif ($_POST['ch_ins_ID']==2) {
    $updateGoTo = "institut_geographie.php";
} elseif ($_POST['ch_ins_ID']==3) {
  $updateGoTo = "institut_patrimoine.php";
} elseif ($_POST['ch_ins_ID']==4) {
  $updateGoTo = "institut_histoire.php";
} elseif ($_POST['ch_ins_ID']==5) {
	  $updateGoTo = "institut_economie.php";
} elseif ($_POST['ch_ins_ID']==6) {
  $updateGoTo = "institut_politique.php";
 } else  {
	 
 }
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_institut = sprintf("SELECT * FROM instituts WHERE ch_ins_ID = %s", GetSQLValueString($institut_id, "int"));
$institut = mysql_query($query_institut, $maconnexion) or die(mysql_error());
$row_institut = mysql_fetch_assoc($institut);
$totalRows_institut = mysql_num_rows($institut);

// Coordonnées marqueur carte
$coord_X = $row_institut['ch_ins_coord_X'];
$coord_Y = $row_institut['ch_ins_coord_Y'];

?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Modifier un Comité de l'OCGC : <?= __s($row_institut['ch_ins_nom']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
	background-image: url('../assets/img/fond_haut-conseil.jpg');
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
<!-- Navbar haut-conseil
    ================================================== -->
<div class="container corps-page">
<?php include('../php/menu-haut-conseil.php'); ?>

  <!-- Page CONTENT
    ================================================== -->
  <div class="titre-bleu">
    <h1>Modifier la description d'un Comité</h1>
  </div>
  <section>
    <div id="categories">
     <section>
  <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="modifier_institut" Id="modifier_institut">
    <div class="alert alert-tips">
      <button type="button" class="close" data-dismiss="alert">×</button>
      Ce formulaire contient les informations qui seront affich&eacute;es sur la page consacr&eacute;e au Comité et plus g&eacute;n&eacute;ralement dans l'ensemble du site. Mettez-le &agrave; jour.</div>
    <!-- boutons cachés -->
    <input name="ch_ins_ID" type="hidden" value="<?= __s($row_institut['ch_ins_ID']) ?>">
    <input name="ch_ins_label" type="hidden" value="<?= __s($row_institut['ch_ins_label']) ?>">
    <input name="ch_ins_date_enregistrement" type="hidden" value="<?= __s($row_institut['ch_ins_date_enregistrement']) ?>">
    <?php $now = date("Y-m-d G:i:s");
				  $nbupdate = $row_institut['ch_ins_nb_update']+1; ?>
    <input name="ch_ins_mis_jour" type="hidden" value="<?= __s($now) ?>" >
    <input name="ch_ins_nb_update" type="hidden" value="<?= __s($nbupdate) ?>">
    <input name="ch_ins_user_ID" type="hidden" value="<?= __s($_SESSION['user_ID']) ?>">
    <input name="ch_ins_statut" type="hidden" value="<?= __s($row_institut['ch_ins_statut']) ?>">
    
    <!-- Nom -->
    <div id="sprytextfield1" class="control-group">
      <label class="control-label" for="ch_ins_nom">Nom du Comité <a href="#" rel="clickover" title="Nom de l'institut" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" type="text" id="ch_ins_nom" name="ch_ins_nom" value="<?= __s($row_institut['ch_ins_nom']) ?>">
        <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
    </div>
     <!-- Image -->
    <div id="sprytextfield5" class="control-group">
      <label class="control-label" for="ch_ins_lien_forum">Lien sujet sur le forum <a href="#" rel="clickover" data-placement="bottom" title="Lien du sujet" data-content="250 caract&egrave;res maximum. Copiez/collez ici le lien vers le sujet consacré à votre pays sur le forum. Cette information sevira à poster des messages dans votre sujet directement depuis le site"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="span9" type="text" id="ch_ins_lien_forum" name="ch_ins_lien_forum" value="<?= __s($row_institut['ch_ins_lien_forum']) ?>" placeholder="">
        <br>
        <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Sigle -->
    <div id="sprytextfield2" class="control-group">
      <label class="control-label" for="ch_ins_sigle">Sigle du Comité <a href="#" rel="clickover" title="Sigle de l'institut" data-content="10 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="input-xlarge" type="text" id="ch_ins_sigle" name="ch_ins_sigle" value="<?= __s($row_institut['ch_ins_sigle']) ?>">
        <span class="textfieldMaxCharsMsg">10 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
    </div>
    <!-- Logo -->
    <div id="sprytextfield28" class="control-group">
      <label class="control-label" for="ch_ins_logo">Logo du Comité <a href="#" rel="clickover" title="Logo du comité" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com). L'image du logo sera automatiquement redimensionn&eacute;e en 250 pixel de large et 250 pixels de haut."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="span9" type="text" id="ch_ins_logo" name="ch_ins_logo" value="<?= __s($row_institut['ch_ins_logo']) ?>" placeholder="">
        <br>
        <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <!-- Image -->
    <div id="sprytextfield4" class="control-group">
      <label class="control-label" for="ch_ins_img">Image du Comité <a href="#" rel="clickover" title="Image de l'institut" data-content="Mettez une image des bâtiments du comité."><i class="icon-info-sign"></i></a></label>
      <div class="controls">
        <input class="span9" type="text" id="ch_ins_img" name="ch_ins_img" value="<?= __s($row_institut['ch_ins_img']) ?>" placeholder="">
        <br>
        <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
    </div>
    <p>&nbsp;</p>
    <!-- Carte -->
    <div class="control-label">Emplacement <a href="#" rel="clickover" title="Emplacement" data-content="Cliquez sur la carte pour définir le nouvel emplacement du comité."><i class="icon-info-sign"></i></a></div>
    <div class="controls">
      <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#demo">carte </button>
    </div>
    <div id="demo" class="accordion-body collapse">
      <div class="accordion-inner">
        <div id="map"></div>
        <p>&nbsp;</p>
        <div class="control-group">
          <div class="control-label">Coordonn&eacute;es X <a href="#" rel="clickover" title="Coordonn&eacute;es" data-content="Cliquez sur la carte pour modifier l'emplacement du comité."><i class="icon-info-sign"></i></a> =</div>
          <div class="controls">
            <p id="coord_X"><?= __s($coord_X) ?></p>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label">Coordonn&eacute;es Y <a href="#" rel="clickover" title="Coordonn&eacute;es" data-content="Cliquez sur la carte pour modifier l'emplacement du comité."><i class="icon-info-sign"></i></a> =</div>
          <div class="controls">
            <p id="coord_Y"><?= __s($coord_Y) ?></p>
          </div>
        </div>
      </div>
    </div>
    <p>&nbsp;</p>
    <!-- Coordonnées -->
    <input type="hidden" name="form_coord_X" id="form_coord_X" value="<?= __s($row_institut['ch_ins_coord_X']) ?>">
    <input type="hidden" name="form_coord_Y" id="form_coord_Y" value="<?= __s($row_institut['ch_ins_coord_Y']) ?>">
    <!-- Description -->
    <div id="sprytextarea1" class="control-group">
      <label class="control-label" for="ch_ins_desc">Description <a href="#" rel="clickover" title="Description" data-content="D&eacute;crivez en quelques mots la mission du comité. 6000 caractères maximum"><i class="icon-info-sign"></i></a></label>
    <div class="controls">
      <textarea name="ch_ins_desc" id="ch_ins_desc" class="wysiwyg" rows="15"><?= __s($row_institut['ch_ins_desc']) ?></textarea>
      <br>
      <span class="textareaMaxCharsMsg">6000 caract&egrave;res maximum.</span><span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span>
    </div>
    </div>

    <div class="controls">
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
    <input type="hidden" name="MM_update" value="modifier_institut">
  </form>
</section>
    </div>
  </section>
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
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:50, validateOn:["change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:10, validateOn:["change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:6000, minChars:2, validateOn:["change"], isRequired:false, useCharacterMasking:false});
</script>