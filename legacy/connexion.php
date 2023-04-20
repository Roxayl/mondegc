<?php

//Connexion et deconnexion
include('php/log.php');

if(isset($_SESSION['userObject'])) {
    $url = legacyPage('back.membre-modifier_back',
        array('userID' => $_SESSION['userObject']->get('ch_use_id')));
    header('Location: ' . $url);
    exit;
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "OubliIdentifiant")) {

  $mailposte = $_POST['ch_use_mail']; // D�claration de l'adresse de destination.


$query_Compare_mail = sprintf("SELECT ch_use_id, ch_use_login, ch_use_password, ch_use_mail, ch_use_paysID, ch_use_statut FROM users WHERE ch_use_mail=%s", escape_sql($mailposte, "text"));
$Compare_mail = mysql_query($query_Compare_mail, $maconnexion);
$row_Compare_mail = mysql_fetch_assoc($Compare_mail);
$totalRows_Compare_mail = mysql_num_rows($Compare_mail);

if ( $row_Compare_mail ) {
//fonction clef activation
	$characts    = 'abcdefghijklmnopqrstuvwxyz';
    $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
	$characts   .= '1234567890'; 
	$code_aleatoire      = ''; 

	for($i=0;$i < 10;$i++)    //10 est le nombre de caract�res
	{ 
        $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
	}


  $mail = $row_Compare_mail['ch_use_mail'] ; // D�claration de l'adresse de destination.
  $login = $row_Compare_mail['ch_use_login']; // D�claration du login.
  $clef = $code_aleatoire; // D�claration de la clef d'activation.
  $paysID = $row_Compare_mail['ch_use_paysID']; // D�claration de l'emplacement.
  $statut = $row_Compare_mail['ch_use_statut']; // D�claration de l'adresse de destination.

  $insertSQL = sprintf("INSERT INTO users_provisoire (ch_use_prov_login, ch_use_prov_clef, ch_use_prov_mail, ch_use_prov_paysID, ch_use_prov_statut) VALUES (%s, %s, %s, %s, %s)",
                       escape_sql($login, "text"),
                       escape_sql($clef, "text"),
                       escape_sql($mail, "text"),
                       escape_sql($paysID, "int"),
                       escape_sql($statut, "int"));

  
  $Result1 = mysql_query($insertSQL, $maconnexion);
  $insertGoTo = 'liste-membres.php';
  appendQueryString($insertGoTo);


  
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn|outlook).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====D�claration des messages au format texte et au format HTML.
$message_txt = "Cher membre de G&eacute;n&eacute;ration City. Vous avez demand&eacute; &agrave; modifier vos identifiants personnels. Si cette demande n'est pas de votre fait, ne cliquez pas sur le lien et contactez imm&eacute;diatement un administrateur de G&eacute;n&eacute;ration City. Si vous souhaitez modifier vos identifiants, cliquez sur le lien ci-dessous : http://www.generation-city.com/monde/membre-identifiants.php?&amp;login=$login&amp;clef=$clef . Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site. l'&eacute;quipe de G&eacute;n&eacute;ration City";
$message_html = "<html><head></head><body><b>Cher membre de G&eacute;n&eacute;ration city</b>,<br><br> Vous avez demand&eacute; &agrave; modifier vos identifiants personnels. Si cette demande n'est pas de votre fait, ne cliquez pas sur le lien et contactez imm&eacute;diatement un administrateur de G&eacute;n&eacute;ration City.<br>
Si vous souhaitez modifier vos identifiants, cliquez sur le lien ci-dessous :<br>
<a href='http://www.generation-city.com/monde/membre-identifiants.php?&amp;login=$login&amp;clef=$clef'>http://www.generation-city.com/monde/membre-identifiants.php?&amp;login=$login&amp;clef=$clef</a><br><br>Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site.<br>Bienvenue dans le Monde GC.<br><br><br><em><i>L'&eacute;quipe de G&eacute;n&eacute;ration City</i></em></body></html>";
//==========

//=====Cr�ation de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====D�finition du sujet.
$sujet = "Modification indentifiants site Monde GC";
//=========

//=====Cr�ation du header de l'e-mail.
$header = "From: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "Reply-to: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Cr�ation du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========

//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
//==========
$mailSuccess = true;
    if (!mail($to, $subject, $body, $headers)) {
    // Redirect if there is an error.
	$mailfail = true;
	$mailSuccess = false;
    }
}
 else {
	$Wrongmail = true;
}
}
 ?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Connexion au compte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
<!-- Page Content
================================================== -->
<div class="container corps-page">
  <div class="row-fluid">
  <div class="titre-vert">

      <h1>Connexion au compte</h1>
      </div>

      <?php renderElement('errormsgs'); ?>

      <div class="row-fluid">
    <section class="span7">
      <p>&nbsp;</p>
      <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="connexion" class="form-horizontal">
        <input type="hidden" name="__csrf_magic" value="<?= csrf_get_tokens() ?>">
        <input type="hidden" name="_token"
               value="<?= \Illuminate\Support\Facades\Session::token() ?>">
        <div class="control-group">
          <label class="control-label" for="identifiant">Login</label>
          <div class="controls">
            <input class="span8" type="text" placeholder="Identifiant" name="identifiant"  id="identifiant">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="mot_de_passe">Password</label>
          <div class="controls">
            <input class="span8" type="password" placeholder="Mot de passe" name="mot_de_passe" id="mot_de_passe">
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-primary">connexion</button>
          </div>
        </div>
      </form>
    </section>
    <section class="span4">
    <p>&nbsp;</p>
      <div class="well">
          <p>Le nombre d'emplacements sur la carte du Monde GC &eacute;tant limit&eacute;, seuls les membres du <a href="https://forum-gc.com/">forum de G&eacute;n&eacute;ration City</a> peuvent y participer. Vous pouvez vous inscrire sur le forum <a href="http://www.forum-gc.com/register" title="lien vers la page d'inscription du forum de G�n�ration City" target="new">ici</a>.
      </p>
      </div>
      </section>
      </div>
    <!-- Page CONTENT
    ================================================== -->
    <section class="pull-center">
      <div>
        <div class="titre-gris">
          <h3>Vous avez oubli&eacute; votre identifiant ou votre mot de passe&nbsp;?</h3>
        </div>
        <div class="well">
          <p>Entrez votre adresse mail ci-dessous. S'il s'agit de l'adresse li&eacute;e &agrave; votre compte, un lien vous permettant de modifier vos identifiants vous sera envoy&eacute;.</p>
          <?php if ($Wrongmail == true) {?>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">�</button>
            <p>L'adresse mail ne correspond &agrave; aucun compte.</p>
          </div>
          <?php } ?>
          <?php if ($mailSuccess == true) {?>
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">�</button>
            <p>Un mail vous a &eacute;t&eacute; envoy&eacute;. Consultez votre messagerie.</p>
          </div>
          <?php } ?>
          <?php if ($mailfail == true) {?>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">�</button>
            <p>Un probl&egrave;me est survenu lors de l'envoi de votre message. Contactez un administrateur.</p>
          </div>
          <?php } ?>
          <form name="OubliIdentifiant" id="OubliIdentifiant" method="POST" action="" class="form-inline">
            <div id="sprytextfield1">
              <label for="ch_use_mail">Adresse E-mail&nbsp;:&nbsp;</label>
              <input type="text" name="ch_use_mail" id="ch_use_mail" class="span5">
              <button class="btn btn-primary" type="submit">Envoyer</button>
              <p>&nbsp;</p>
              <span class="textfieldRequiredMsg">Une valeur est requise.</span> <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
            <input type="hidden" name="MM_insert" value="OubliIdentifiant">
          </form>
        </div>
      </div>
    </section>
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {validateOn:["change"]});
</script>
</body>
</html>