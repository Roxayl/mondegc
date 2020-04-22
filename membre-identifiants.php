<?php
require_once('Connections/maconnexion.php');


//Connexion et deconnexion
include('php/log.php');

$colname_Recordset1 = "-1";
if (isset($_GET['login'])) {
  $login = $_GET['login'];
}

$colname_Recordset1 = "-1";
if (isset($_GET['clef'])) {
  $clef = $_GET['clef'];
}

mysql_select_db($database_maconnexion, $maconnexion);
$query_user_prov = sprintf("SELECT * FROM users_provisoire WHERE ch_use_prov_login = %s AND ch_use_prov_clef = %s", GetSQLValueString($login, "text"), GetSQLValueString($clef, "text"));
$user_prov = mysql_query($query_user_prov, $maconnexion) or die(mysql_error());
$row_user_prov = mysql_fetch_assoc($user_prov);
$totalRows_user_prov = mysql_num_rows($user_prov);

$colname_UserID = "-1";
if (isset($row_user_prov['ch_use_prov_login'])) {
  $colname_UserID = $row_user_prov['ch_use_prov_login'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_UserID = sprintf("SELECT ch_use_id FROM users WHERE ch_use_login = %s", GetSQLValueString($colname_UserID, "text"));
$UserID = mysql_query($query_UserID, $maconnexion) or die(mysql_error());
$row_UserID = mysql_fetch_assoc($UserID);
$totalRows_UserID = mysql_num_rows($UserID);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "InfoUser")) {
  include_once("php/config.php");
  $password = md5($_POST['ch-use_password'].$salt);
  $updateSQL = sprintf("UPDATE users SET ch_use_login=%s, ch_use_password=%s WHERE ch_use_id=%s",
                       GetSQLValueString($_POST['ch_use_login'], "text"),
                       GetSQLValueString($password, "text"),
                       GetSQLValueString($_POST['ch_use_id'], "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($updateSQL, $maconnexion) or die(mysql_error());
  
  // Effacement de la clef sur User_provisoire
  $userprov = $row_user_prov['ch_use_prov_ID'];
   $deleteSQL = sprintf("DELETE FROM users_provisoire WHERE ch_use_prov_ID=%s",
                       GetSQLValueString($userprov, "int"));

  mysql_select_db($database_maconnexion, $maconnexion);
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
  $insertGoTo = 'index.php';
  if (isset($_SERVER['QUERY_STRING'])) {
  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="iso-8859-1">
<title>Monde GC-</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<style>
.jumbotron {
	background-image: url('assets/img/ImgIntroheader.jpg');
}
</style>
</head>

<body>
<!-- Navbar
    ================================================== -->
<?php $accueil=true; include('php/navbar.php'); ?>
<!-- Page CONTENT
    ================================================== -->
<div class="container">
  <div class="row-fluid corps-page">
    <?php if ($row_user_prov == true) { ?>
    <div class="span1">
      <p>&nbsp;</p>
    </div>
    <div id="categories" class="span10 well">
      <div class="titre-vert">
        <h1>Changement identifiants</h1>
      </div>
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">�</button>
        <p>Cher <?php echo $row_user_prov['ch_use_prov_login']; ?>, entrez un nouveau mot de passe afin d'acc&eacute;der &agrave; votre compte.</p>
      </div>
      <form action="<?php echo $editFormAction; ?>" name="InfoUser" method="POST" class="form-horizontal" id="InfoHeader">
        <input name="ch_use_id" type="hidden" value="<?php echo $row_UserID['ch_use_id']; ?>">
        <!-- Informations G�n�rales
        ================================================== -->
        <h3>Informations Profil</h3>
        <!-- Nom user -->
        <div id="sprytextfield3" class="control-group">
          <label class="control-label" for="ch_use_login">Login<a href="#" rel="clickover" title="Nom du pays" data-content="2 caract&egrave;res maximum. Ce nom servira &agrave; identifier le nouveau membre dans l'ensemble du monde GC. Votre login doit &ecirc;tre le m&ecirc;me que sur le forum afin d'assurer la fonction d'envoi de MP. Contactez un membre du haut-conseil s'il doit &ecirc;tre modifi&eacute;."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="ch_use_login" name="ch_use_login" maxlength="12" value="<?php echo $row_user_prov['ch_use_prov_login']; ?>" readonly>
            <span class="textfieldRequiredMsg">un login est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">12 caract&egrave;res max.</span></div>
        </div>
        <!-- Password -->
        <div id="sprypassword1" class="control-group">
          <label class="control-label" for="ch_use_password">Mot de passe<a href="#" rel="clickover" title="Mots de passe" data-content="Entrez-ici un mot de passe."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="password" name="ch_use_password" id="ch_use_password" value="">
            <span class="passwordRequiredMsg">un mot de passe est obligatoire.</span><span class="passwordMinCharsMsg">2 caract&egrave;res min.</span><span class="passwordMaxCharsMsg">16 caract&egrave;res max.</span></div>
        </div>
        <div id="spryconfirm1">
          <label class="control-label" for="ch-use_password2" class="control-group">
          Confirmez le mot de passe
          </label>
          <div class="controls">
            <input class="input-xlarge" type="password" name="ch-use_password" id="ch-use_password" value="">
            <span class="confirmRequiredMsg">La confirmation du mot de passe est obligatoire..</span><span class="confirmInvalidMsg">Les valeurs ne correspondent pas.</span></div>
        </div>
        <!-- Bouton envoyer
        ================================================== -->
        <p>&nbsp;</p>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </div>
        <input type="hidden" name="MM_update" value="InfoUser">
      </form>
    </div>
    <?php } else { ?>
    <p>&nbsp;</p>
    <div class="well">
      <h2>Clef d'activation incorrecte.</h2>
      <p><i>Contactez un membre du Haut-Conseil pour r&eacute;soudre ce probl&egrave;me</i></p>
      <p>Pour vous inscrire sur le Monde GC et b&acirc;tir votre pays, vous devez demander un emplacement sur le forum de <a href="http://www.forum-gc.com/" title="lien vers le forum (ouvre une nouvelle fen&ecirc;tre )" target="new">G&eacute;n&eacute;ration City</a></p>
      <form>
        <input name="ch_use_password" id="ch_use_password" type="hidden" value="">
      </form>
    </div>
    <?php } ?>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>

<!-- Footer
    ================================================== -->
<?php include('php/footer.php'); ?>
</body>
</html>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-affix.js"></script>
<script src="assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="assets/js/bootstrap-scrollspy.js"></script>
<script src="assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
</script>
<!-- SPRY ASSETS -->
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars:2, maxChars:35, validateOn:["change"]});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:2, validateOn:["change"], maxChars:16});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "ch_use_password", {validateOn:["change"]});
</script>
<?php
mysql_free_result($user_prov);

mysql_free_result($UserID);
?>
