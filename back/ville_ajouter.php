<?php


require_once('../Connections/maconnexion.php');
//deconnexion
include('../php/logout.php');

require_once('../Connections/maconnexion.php'); 
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_ville")) {
  $insertSQL = sprintf("INSERT INTO villes (ch_vil_paysID, ch_vil_user, ch_vil_label, ch_vil_date_enregistrement, ch_vil_mis_jour, ch_vil_nb_update, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_type_jeu, ch_vil_nom, ch_vil_armoiries, ch_vil_capitale, ch_vil_population, ch_vil_specialite, ch_vil_lien_img1, ch_vil_lien_img2, ch_vil_lien_img3, ch_vil_lien_img4, ch_vil_lien_img5, ch_vil_legende_img1, ch_vil_legende_img2, ch_vil_legende_img3, ch_vil_legende_img4, ch_vil_legende_img5, ch_vil_header, ch_vil_contenu) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ch_vil_paysID'], "int"),
                       GetSQLValueString($_POST['ch_vil_user'], "int"),
                       GetSQLValueString($_POST['ch_vil_label'], "text"),
                       GetSQLValueString($_POST['ch_vil_date_enregistrement'], "date"),
                       GetSQLValueString($_POST['ch_vil_mis_jour'], "date"),
                       GetSQLValueString($_POST['ch_vil_nb_update'], "int"),
                       GetSQLValueString($_POST['form_coord_X'], "decimal"),
                       GetSQLValueString($_POST['form_coord_Y'], "decimal"),
					   GetSQLValueString($_POST['ch_vil_type_jeu'], "text"),
                       GetSQLValueString($_POST['ch_vil_nom'], "text"),
					   GetSQLValueString($_POST['ch_vil_armoiries'], "text"),
                       GetSQLValueString($_POST['ch_vil_capitale'], "int"),
                       GetSQLValueString($_POST['ch_vil_population'], "int"),
                       GetSQLValueString($_POST['ch_vil_specialite'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img1'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img2'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img3'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img4'], "text"),
                       GetSQLValueString($_POST['ch_vil_lien_img5'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img1'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img2'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img3'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img4'], "text"),
                       GetSQLValueString($_POST['ch_vil_legende_img5'], "text"),
                       GetSQLValueString($_POST['ch_vil_header'], "text"),
                       GetSQLValueString($_POST['ch_vil_contenu'], "text"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $insertGoTo = "page_pays_back.php?paysID=" . (int)$_POST['ch_vil_paysID'] . "#mes-villes";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$User = $_SESSION['user_ID'];
mysql_select_db($database_maconnexion, $maconnexion);
$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_id = %s", GetSQLValueString($User, "int"));
$users = mysql_query($query_users, $maconnexion) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

//Liste des joueurs pour choisir maire
mysql_select_db($database_maconnexion, $maconnexion);
$query_list_users = sprintf("SELECT ch_use_id, ch_use_login FROM users ORDER BY ch_use_login");
$list_users = mysql_query($query_list_users, $maconnexion) or die(mysql_error());
$row_list_users = mysql_fetch_assoc($list_users);
$totalRows_list_users = mysql_num_rows($list_users);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Ajouter une ville</title>
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
<link href="../assets/css/GenerationCity.css" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<script type="text/javascript">
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
<!-- Subhead
================================================== -->
<header id="info-ville" class="jumbotron subhead anchor">
  <div class="container container-carousel">
    <h1>Nouvelle ville</h1>
  </div>
</header>
<div class="container"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <li class="row-fluid"><a href="#info-ville"> <img src="../assets/img/imagesdefaut/blason.jpg">
          <p><strong>Nouvelle ville</strong></p>
          <p><em>Cr&eacute;&eacute;e par <?php echo $row_users['ch_use_login']; ?></em></p>
          </a></li>
        <li><a href="#pageville">Page ville</a></li>
        <?php if ($row_User['ch_use_id'] == $_SESSION['user_ID']) { ?>
        <li><a href="page_pays_back.php">Retour &agrave; mon pays</a></li>
        <?php }?>
      </ul>
    </div>
    <!-- END Docs nav
    ================================================== --> 
    
    <!-- Page CONTENT
    ================================================== -->
    <div class="span9"> 
      <!-- Moderation
     ================================================== -->
      <?php if (($_SESSION['statut'] >= 20) AND ($row_User['ch_use_id'] != $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="membre-modifier_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $paysID; ?>">
        <input name="userID" type="hidden" value="<?php echo $row_users['ch_use_id']; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $paysID; ?>">
        <input name="userID" type="hidden" value="<?php echo $row_users['ch_use_id']; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <?php }?>
      <div class="clearfix"></div>
      <div id="pageville" class="titre-vert anchor"> 
        <h1>Ajouter une ville</h1>
      </div>
      <!-- Debut formulaire -->
      <form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal well" name="ajout_ville" Id="ajout_ville" onsubmit='return verif_champ(document.ajout_ville.form_coord_X.value);' >
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Ce formulaire contient les informations qui seront affich&eacute;e sur la page consacr&eacute;e &agrave; votre ville et plus g&eacute;n&eacute;ralement dans l'ensemble du site. Compl&eacute;tez-le au fur et &agrave; mesure et mettez-le &agrave; jour.</div>
        <!-- Bouton cachés -->
        <input name="ch_vil_paysID" type="hidden" value="<?php echo $paysID; ?>" >
        <input name="ch_vil_user" type="hidden" value="<?php echo $_SESSION['user_ID']; ?>" >
        <input name="ch_vil_label" type="hidden" value="ville">
        <?php 
				  $now= date("Y-m-d G:i:s");?>
        <input name="ch_vil_date_enregistrement" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_vil_mis_jour" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_vil_nb_update" type="hidden" value="0">
        <!-- Choix joueur -->
        <?php if (($_SESSION['statut'] >= 10)) { ?>
        <div class="control-group">
          <label class="control-label" for="ch_vil_user">Maire de la ville <a href="#" rel="clickover" title="Autre joueur" data-content="Vous pouvez choisir un autre joueur qui est d&eacute;j&agrave; inscrit sur le site. Contacter le Haut-Conseil pour inscrire de nouveaux membres. Attention, les villes confi&eacute;es &agrave; d'autres joueurs ne seront plus sous votre contr&ocirc;le"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <select id="ch_vil_user" name="ch_vil_user">
              <?php do { ?>
              <option value="<?php echo $row_list_users['ch_use_id'] ?>" <?php if (!(strcmp($_SESSION['user_ID'], $row_list_users['ch_use_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_list_users['ch_use_login'] ?></option>
              <?php } while ($row_list_users = mysql_fetch_assoc($list_users)); ?>
            </select>
          </div>
        </div>
        <?php } else { ?>
        <input name="ch_vil_user" type="hidden" value="<?php echo $row_ville['ch_vil_user'] ?>" >
        <?php } ?>
        <!-- Nom -->
        <div id="sprytextfield2" class="control-group">
          <label class="control-label" for="ch_vil_nom">Nom de la ville <a href="#" rel="clickover" title="Nom de la ville" data-content="30 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="ch_vil_nom" name="ch_vil_nom" value="" placeholder="ma ville">
            <span class="textfieldMaxCharsMsg">30 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Type de jeu -->
        <div class="control-group">
          <label class="control-label" for="ch_vil_type_jeu">Type de jeu <a href="#" rel="clickover" title="Type de jeu" data-content="Indiquez le jeu dans lequel vous avez construit votre ville"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <select id="ch_vil_type_jeu" name="ch_vil_type_jeu">
              <option value="SC5">Sim City 2013</option>
              <option value="CXL" selected>Cities (X)Xl</option>
              <option value="CL">City Life</option>
              <option value="SC4">Sim City 4</option>
              <option value="SIM">Les Sims</option>
              <option value="SKY">Cities Skylines</option>
            </select>
          </div>
        </div>
        <!-- Armoiries -->
        <div id="sprytextfield28" class="control-group">
          <label class="control-label" for="ch_vil_armoiries">Armoiries de la ville <a href="#" rel="clickover" title="Armoiries de la ville" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com). l'image des armoiries sera automatiquement redimensionn&eacute;e en 250 pixel de large et 250 pixels de haut."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" id="ch_vil_armoiries" name="ch_vil_armoiries" value="" placeholder="">
            <p>&nbsp;</p>
            <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
        </div>
        <!-- Statut -->
        <div id="spryradio1" class="control-group">
          <div class="control-label" >Statut de la ville <a href="#" rel="clickover" title="Statut de votre ville" data-content="Capitale : la ville sera identifiée comme la capitale de votre pays sur le site.
    Visible : la ville sera visible pour les visiteurs du site.
   Invisible : la ville sera invisible pour les visiteurs du site."><i class="icon-info-sign"></i></a></div>
          <div class="controls">
            <label>
              <input type="radio" name="ch_vil_capitale" value="1" id="ch_vil_capitale_0">
              capitale</label>
            <label>
              <input name="ch_vil_capitale" type="radio" id="ch_vil_capitale_1" value="2" checked="CHECKED">
              visible</label>
            <label>
              <input type="radio" name="ch_vil_capitale" value="3" id="ch_vil_capitale_2">
              invisible</label>
            <span class="radioRequiredMsg">Choisissez un statut pour votre ville</span></div>
        </div>
        <div id="sprytextfield3" class="control-group"> 
          <!-- Population -->
          <label class="control-label" for="ch_vil_population">Population <a href="#" rel="clickover" title="Population" data-content="Entrez le chiffre sans espaces"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" name="ch_vil_population" id="ch_vil_population" placeholder="0">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Spécialité -->
        <div id="sprytextfield4" class="control-group">
          <label class="control-label" for="ch_vil_specialite">Sp&eacute;cialit&eacute; <a href="#" rel="clickover" title="Sp&eacute;cialit&eacute;" data-content="Entrez ici la spécialit&eacute; de votre ville qui pourrait &ecirc;tre l'agriculture ou le macram&eacute;... 50 charact&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" name="ch_vil_specialite" id="ch_vil_specialite" placeholder="Petit artisanat local">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <p>&nbsp;</p>
        <!-- Placement sur carte monde GC -->
        <h3>Carte</h3>
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <h4>Attention&nbsp;!</h4>
          Vous devez obligatoirement placer votre ville sur la carte du Monde GC. Veillez &agrave; placer vos villes &agrave; l'int&eacute;rieur des fronti&egrave;res de votre pays.</div>
        <!-- Coordonnées -->
        <div id="map"></div>
        <p>&nbsp;</p>
        <div class="control-group">
          <label class="control-label">Coordonn&eacute;es X</label>
          <div class="controls">
            <input class="span2" type="text" name="form_coord_X" id="form_coord_X" placeholder="cliquez sur la carte" readonly>
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span> <span class="textfieldRequiredMsg">Une valeur est requise. Cliquez sur la carte</span></div>
        </div>
        <div class="control-group">
          <label class="control-label">Coordonn&eacute;es Y</label>
          <div class="controls">
            <input class="span2" type="text" name="form_coord_Y" id="form_coord_Y" placeholder="cliquez sur la carte" readonly>
          </div>
        </div>
        <h3 id="journal" class="anchor">Journal de la ville</h3>
        <!-- Présentation -->
        <div class="control-group" width="100%">
          <label class="control-label" for="ch_vil_header">Pr&eacute;sentation <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Mettez-ici un r&eacute;sum&eacute; du journal de votre ville. Utilisez les liens vers des ancres html pour des renvois vers le détail dans votre journal"><i class="icon-info-sign"></i></a></label>
        </div>
        <div>
          <textarea name="ch_vil_header" id="ch_vil_header" class="wysiwyg" rows="6"></textarea>
        </div>
        <p>&nbsp;</p>
        <div class="control-group" width="100%"> 
          <!-- Contenu -->
          <label class="control-label" for="ch_vil_contenu">Contenu de la page <a href="#" rel="clickover" title="Pr&eacute;sentation" data-content="Ecrivez ici le contenu d&eacute;taill&eacute; de la page de votre ville. R&eacute;alisez une mise en forme simple. Pensez &agrave; l'utilisation du site sur les &eacute;crans mobiles. Vous pouvez la mettre à jour au fur et &agrave; mesure"><i class="icon-info-sign"></i></a></label>
        </div>
        <div>
          <textarea name="ch_vil_contenu" id="ch_vil_contenu" class="wysiwyg"  rows="20"></textarea>
        </div>
        <br>
        <hr>
        <h3>Carrousel</h3>
        <!-- Carousel -->
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Le carrousel est une galerie d'images qui va d&eacute;filer en t&ecirc;te de la page de votre ville. La premi&egrave;re image sera reprise pour illustrer votre ville dans l'ensemble du site.</div>
        <div id="sprytextfield5" class="control-group">
          <label class="control-label" for="ch_vil_lien_img1">Lien image n&deg;1 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_lien_img1" id="ch_vil_lien_img1" value="" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield6" class="control-group">
          <label class="control-label" for="ch_vil_legende_img1">L&eacute;gende image n&deg;1 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_legende_img1" id="ch_vil_legende_img1">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_vil_lien_img2">Lien image n&deg;2 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_lien_img2" id="ch_vil_lien_img2" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield8" class="control-group">
          <label class="control-label" for="ch_vil_legende_img2">L&eacute;gende image n&deg;2 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_legende_img2" id="ch_vil_legende_img2">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield9" class="control-group">
          <label class="control-label" for="ch_vil_lien_img3">Lien image n&deg;3 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_lien_img3" id="ch_vil_lien_img3" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield10" class="control-group">
          <label class="control-label" for="ch_vil_legende_img3">L&eacute;gende image n&deg;3 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_legende_img3" id="ch_vil_legende_img3">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield11" class="control-group">
          <label class="control-label" for="ch_vil_lien_img4">Lien image n&deg;4 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_lien_img4" id="ch_vil_lien_img4" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield12" class="control-group">
          <label class="control-label" for="ch_vil_legende_img4">L&eacute;gende image n&deg;4 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_legende_img4" id="ch_vil_legende_img4">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield13" class="control-group">
          <label class="control-label" for="ch_vil_lien_img5">Lien image n&deg;5 <a href="#" rel="clickover" title="Lien image" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_lien_img5" id="ch_vil_lien_img5" class="span6">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span></div>
        </div>
        <div id="sprytextfield14" class="control-group">
          <label class="control-label" for="ch_vil_legende_img5">L&eacute;gende image n&deg;5 <a href="#" rel="clickover" title="L&eacute;gende image" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_vil_legende_img5" id="ch_vil_legende_img5">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <p>&nbsp;</p>
        <div class="controls">
          <button type="submit" class="btn btn-primary">Envoyer</button>
          <p>&nbsp;</p>
        </div>
        <input type="hidden" name="MM_insert" value="ajout_ville">
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
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>
<!-- SPRY ASSETS -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:30, validateOn:["change"], minChars:2});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["change"], useCharacterMasking:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
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
var sprytextfield28 = new Spry.Widget.ValidationTextField("sprytextfield28", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
</script>