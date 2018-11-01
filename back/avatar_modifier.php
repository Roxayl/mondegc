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

//Récupération variables
$colname_user = $_SESSION['user_ID'];
if (isset($_POST['userID'])) {
  $colname_user = $_POST['userID'];
  unset($_POST['userID']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Modifier le drapeau de mon pays</title>
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
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php include('../php/navbarback.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">
  <div class="row-fluid">
 	<div id="info-generales" class="titre-vert anchor"> <img src="../assets/img/IconesBDD/100/Membre1.png">
          <h1>Modifier votre avatar</h1>
        </div>
    <p>&nbsp;</p>
    <section>
      <?php include('../php/upload.php');
if (isset($uploadconfirm)) {
  $updateSQL = sprintf("UPDATE users SET ch_use_lien_imgpersonnage=%s WHERE ch_use_id=%s",
                       GetSQLValueString($link, "text"),
                       GetSQLValueString($colname_user, "int"));
  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_avatar = sprintf("SELECT ch_use_id, ch_use_predicat_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_lien_imgpersonnage FROM users WHERE ch_use_id = %s", GetSQLValueString($colname_user, "int"));
$avatar = mysql_query($query_avatar, $maconnexion) or die(mysql_error());
$row_avatar = mysql_fetch_assoc($avatar);
$totalRows_avatar = mysql_num_rows($avatar);
?>
      
      <!-- Debut formulaire -->
      <div class="well well-large"> 
        <!-- Image de contrôle drapeau --> 
        <img src="<?php echo $row_avatar['ch_use_lien_imgpersonnage']; ?>" alt="avatar <?php echo $row_avatar['ch_use_nom_dirigeant']; ?>" title="drapeau <?php echo $row_avatar['ch_use_nom_dirigeant']; ?>>">
        <p>&nbsp;</p>
        <p><?php echo $row_avatar['ch_use_predicat_dirigeant']; ?> <?php echo $row_avatar['ch_use_nom_dirigeant']; ?> <?php echo $row_avatar['ch_use_prenom_dirigeant']; ?>
        <p>&nbsp;</p>
        <form action="avatar_modifier.php" method="post" enctype="multipart/form-data">
          <input type="file" name="fileToUpload" id="fileToUpload" data-filename-placement="inside" title="Choisir une nouvelle image">
          <input name="userID" id="userID" type="hidden" value="<?php echo $colname_user; ?>">
          <input name="maxwidth" id="maxwidth" type="hidden" value="250">
          <input name="ThumbMaxwidth" id="ThumbMaxwidth" type="hidden" value="100">
          <input name="SmallThumbMaxwidth" id="SmallThumbMaxwidth" type="hidden" value="50">
          <input type="submit" name="submit" value="Envoyer" class="btn btn-primary"/>
          <a class="btn btn-primary" href="membre-modifier_back.php" title="Retour &agrave; la page de gestion de votre profil">Retour</a>
        </form>
      </div>
    </section>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
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
<script src="../assets/js/bootstrap-clickover.js"></script>
<script src="../assets/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript">
$('input[type=file]').bootstrapFileInput();
</script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<?php
mysql_free_result($avatar);
?>
