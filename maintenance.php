<?php

//Connexion et deconnexion
include('php/log.php');

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "OubliIdentifiant")) {

  $mail = $_POST['ch_use_mail']; // D�claration de l'adresse de destination.


$query_Compare_mail = "SELECT ch_use_id, ch_use_login, ch_use_password, ch_use_mail, ch_use_paysID, ch_use_statut FROM users WHERE ch_use_mail='$mail'";
$Compare_mail = mysql_query($query_Compare_mail, $maconnexion) or die(mysql_error());
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
                       GetSQLValueString($login, "text"),
                       GetSQLValueString($clef, "text"),
                       GetSQLValueString($mail, "text"),
                       GetSQLValueString($paysID, "int"),
                       GetSQLValueString($statut, "int"));

  
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());
  $insertGoTo = 'liste-membres.php';
  appendQueryString($insertGoTo);


  
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
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
<title>Monde GC-</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
<!-- Page Content
================================================== -->
<div class="container">
  <div class="row-fluid">
  <div class="titre-vert">

      <h1>Maintenance</h1>
      </div>
      <div class="row-fluid corps-page">
    <section class="span7">
      <p>&nbsp;</p>
      <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="connexion" class="form-horizontal">
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
      <p>Une mise &agrave; jour est en train d'&ecirc;tre effectu&eacute;e.  Seuls les administrateurs peuvent se connecter. Le site sera de nouveau acc&eacute;ssible le plus rapidement possible. 
      Nous annoncerons la r&eacute;-ouverture du site sur le <a href="http://www.forum-gc.com/" title="Lien vers le forum">forum de G�n�ration City </a></p>
      </div>
      </section>
      </div>
    <!-- END CONTENT
    ================================================== --> 
  </div>
</div>
<!-- Footer
    ================================================== -->

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
</body>
</html>
