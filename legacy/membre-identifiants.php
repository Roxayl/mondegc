<?php

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


$query_user_prov = sprintf("SELECT * FROM users_provisoire WHERE ch_use_prov_login = %s AND ch_use_prov_clef = %s", escape_sql($login, "text"), escape_sql($clef, "text"));
$user_prov = mysql_query($query_user_prov, $maconnexion);
$row_user_prov = mysql_fetch_assoc($user_prov);
$totalRows_user_prov = mysql_num_rows($user_prov);

if(! $row_user_prov) {
    abort(404);
}

$colname_UserID = "-1";
if (isset($row_user_prov['ch_use_prov_login'])) {
  $colname_UserID = $row_user_prov['ch_use_prov_login'];
}

$query_UserID = sprintf("SELECT ch_use_id FROM users WHERE ch_use_login = %s", escape_sql($colname_UserID, "text"));
$UserID = mysql_query($query_UserID, $maconnexion);
$row_UserID = mysql_fetch_assoc($UserID);
$totalRows_UserID = mysql_num_rows($UserID);

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "InfoUser")) {
  $salt = config('legacy.salt');
  $password = md5($_POST['ch_use_password'].$salt);
  $updateSQL = sprintf("UPDATE users SET ch_use_login=%s, ch_use_password=%s WHERE ch_use_id=%s",
                       escape_sql($_POST['ch_use_login'], "text"),
                       escape_sql($password, "text"),
                       escape_sql($_POST['ch_use_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion);
  
  // Effacement de la clef sur User_provisoire
  $userprov = $row_user_prov['ch_use_prov_ID'];
   $deleteSQL = sprintf("DELETE FROM users_provisoire WHERE ch_use_prov_ID=%s",
                       escape_sql($userprov, "int"));

  
  $Result1 = mysql_query($deleteSQL, $maconnexion);
  $insertGoTo = DEF_URI_PATH . 'index.php';
  appendQueryString($insertGoTo);
  header(sprintf("Location: %s", $insertGoTo));
 exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>

<body>
<!-- Navbar
    ================================================== -->
<?php $accueil=true; require('php/navbar.php'); ?>
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
        <p>Cher <?= e($row_user_prov['ch_use_prov_login']) ?>, entrez un nouveau mot de passe afin d'acc&eacute;der &agrave; votre compte.</p>
      </div>
      <form action="<?= e($editFormAction) ?>" name="InfoUser" method="POST" class="form-horizontal" id="InfoHeader">
        <input name="ch_use_id" type="hidden" value="<?= e($row_UserID['ch_use_id']) ?>">
        <!-- Informations G�n�rales
        ================================================== -->
        <h3>Informations Profil</h3>
        <!-- Nom user -->
        <div id="sprytextfield3" class="control-group">
          <label class="control-label" for="ch_use_login">Login<a href="#" rel="clickover" title="Nom du pays" data-content="2 caract&egrave;res maximum. Ce nom servira &agrave; identifier le nouveau membre dans l'ensemble du monde GC. Votre login doit &ecirc;tre le m&ecirc;me que sur le forum afin d'assurer la fonction d'envoi de MP. Contactez un membre du haut-conseil s'il doit &ecirc;tre modifi&eacute;."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="ch_use_login" name="ch_use_login" maxlength="12" value="<?= e($row_user_prov['ch_use_prov_login']) ?>" readonly>
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
<?php require('php/footer.php'); ?>

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
</body>
</html>