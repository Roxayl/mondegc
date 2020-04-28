<?php


if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');
//deconnexion
include(DEF_ROOTPATH . 'php/logout.php');

if ($_SESSION['statut'] AND ($_SESSION['statut']>=20))
{
} else {
	// Redirection vers page connexion
header("Status: 301 Moved Permanently", false, 301);
header('Location: ../connexion.php');
exit();
	}

if(!isset($mondegc_config['front-controller'])) require_once(DEF_ROOTPATH . 'Connections/maconnexion.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_user")) {

  $mail = $_POST['ch_use_prov_mail']; // Déclaration de l'adresse de destination.
  $login = $_POST['ch_use_prov_login']; // Déclaration du login.
  $clef = $_POST['ch_use_prov_clef']; // Déclaration de la clef d'activation.
  $paysID = $_POST['ch_use_prov_paysID']; // Déclaration de l'emplacement.
  $ch_use_prov_statut = $_POST['ch_use_prov_statut']; // Déclaration de l'adresse de destination.


  $insertSQL = sprintf("INSERT INTO users_provisoire (ch_use_prov_login, ch_use_prov_clef, ch_use_prov_mail, ch_use_prov_paysID, ch_use_prov_statut) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($login, "text"),
                       GetSQLValueString($clef, "text"),
                       GetSQLValueString($mail, "text"),
                       GetSQLValueString($paysID, "int"),
                       GetSQLValueString($ch_use_prov_statut, "int"));

  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  \GenCity\Monde\Logger\Log::createItem('users_provisoire', null, 'insert',
      $_SESSION['userObject']->get('ch_use_id'), array('data', array('ch_use_prov_login' => $login)));

  $insertGoTo = 'liste-membres.php';
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn|outlook).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Cher membre de G&eacute;n&eacute;ration City. Vous avez sollicit&eacute; l'obtention d'un emplacement dans le Monde GC afin d'y b&acirc;tir votre pays. 
Afin de finaliser votre inscription sur le site, cliquez sur le lien  : http://www.generation-city.com/monde/membre-inscription.php?&amp;login=$login&amp;clef=$clef . Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site. Bienvenue dans le Monde GC. l'&eacute;quipe de G&eacute;n&eacute;ration City";
$message_html = "<html><head></head><body><b>Cher membre de G&eacute;n&eacute;ration city</b>,<br><br>Vous avez sollicit&eacute; l'obtention d'un emplacement dans le Monde GC afin d'y b&acirc;tir votre pays.<br><br>Afin de finaliser votre inscription sur le site, cliquez sur le lien&nbsp;:<br>
<a href='http://www.generation-city.com/monde/membre-inscription.php?&amp;login=$login&amp;clef=$clef'>http://www.generation-city.com/monde/membre-inscription.php?&amp;login=$login&amp;clef=$clef</a><br><br>Nous vous remercions de l'inter&ecirc;t que vous portez &agrave; notre site.<br>Bienvenue dans le Monde GC.<br><br><br><em><i>L'&eacute;quipe de G&eacute;n&eacute;ration City</i></em></body></html>";
//==========

//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========

//=====Définition du sujet.
$sujet = "Inscription Site Monde GC";
//=========

//=====Création du header de l'e-mail.
$header = "From: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "Reply-to: \"Generation City\"<monde@generation-city.com>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========

//=====Création du message.
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
mail('contact@romukulot.fr',$sujet,$message,$header);
//==========
    if (!mail($to, $subject, $body, $headers)) {
              $redirect_error= "error.php"; // Redirect if there is an error.
      header( "Location: ".$redirect_error ) ;
    }
  header(sprintf("Location: %s", $insertGoTo));
  exit();
}


$query_pays = "SELECT ch_pay_id, ch_pay_nom FROM pays ORDER BY ch_pay_nom ASC";
$pays = mysql_query($query_pays, $maconnexion) or die(mysql_error());
$row_pays = mysql_fetch_assoc($pays);
$totalRows_pays = mysql_num_rows($pays);

//fonction clef activation
	$characts    = 'abcdefghijklmnopqrstuvwxyz';
    $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
	$characts   .= '1234567890'; 
	$code_aleatoire      = ''; 

	for($i=0;$i < 10;$i++)    //10 est le nombre de caractères
	{ 
        $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Haut-Conseil - Nouveau membre</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/navbarback.php'); ?>
<!-- Subhead
================================================== -->
<div class="container corps-page">
  <?php include(DEF_ROOTPATH . 'php/menu-haut-conseil.php'); ?>
  <div class="titre-bleu">
    <h1>Cr&eacute;er un nouveau profil</h1>
  </div>
  <div class="row-fluid">
    <div class="span8"> 
      <!-- Debut formulaire membre
        ================================================== -->
      <section id="info-generales" class="well">
        <form action="<?php echo $editFormAction; ?>" name="new_user" method="POST" class="form-horizontal" id="InfoHeader">
          <!-- Definir statut du membre -->
          <h3>D&eacute;finir le statut du membre :</h3>
          <div id="spryradio1">
            <label class="radio" for="ch_use_prov_statut_2">
              <input name="ch_use_prov_statut" type="radio" id="ch_use_prov_statut_2" value="10">
              Membre <a href="#" rel="clickover" title="Membre" data-content="Un compte de base. Généralement vous choisirez cette option lors de la création d'un compte."><i class="icon-info-sign"></i></a></label>
            <br>
             <label class="radio" for="ch_use_statut_5">
                  <input name="ch_use_prov_statut" type="radio" id="ch_use_statut_5" value="15" >
                  Juge Temp&eacute;rant <a href="#" rel="clickover" title="Juge Temp&eacute;rant" data-content="Le juge temp&eacute;rant op&egrave;re dans le cadre du projet Temp&eacute;rance en lien avec l'institut d'&eacute;conomie."><i class="icon-info-sign"></i></a></label>
            <br>
            <?php if ($_SESSION['statut'] >= 30) { ?>
            <label class="radio" for="ch_use_prov_statut_3">
              <input type="radio" name="ch_use_prov_statut" value="20" id="ch_use_prov_statut_3">
              Membre du Haut-Conseil <a href="#" rel="clickover" title="Haut-Conseil" data-content="Le membre du Haut-Conseil aura acc&egrave;s aux outils de mod&eacute;ration du Monde GC. Il pourra &eacute;galement alimenter les bases de donn&eacute;es au nom des comités."><i class="icon-info-sign"></i></a></label>
            <br>
            <label class="radio" for="ch_use_prov_statut_4">
              <input type="radio" name="ch_use_prov_statut" value="30" id="ch_use_statut_3">
              Administrateur <a href="#" rel="clickover" title="Haut-Conseil" data-content="L'administrateur a la possibilit&eacute; d'effacer les donn&eacute;es et peut attribuer des statuts sup&eacute;rieurs &agrave; celui des dirigeants."><i class="icon-info-sign"></i></a></label>
            <br>
            <span class="radioRequiredMsg">Effectuez une s&eacute;lection.</span></div>
          <?php } ?>
          <h3>Attribuer un pays au nouveau membre :</h3>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p>Cr&eacute;ez d'abord un nouveau pays avant de l'assigner au profil d'un nouveau dirigeant. Un maire doit &ecirc;tre obligatoirement rattach&eacute; &agrave; un pays.</p>
          </div>
          <div class="control-group">
            <label class="control-label" for="ch_use_prov_paysID">Pays associé au membre</label>
            <div class="controls">
              <select name="ch_use_prov_paysID" id="ch_use_prov_paysID" class="input-xlarge">
                <option>--S&eacute;lectionnez un pays--</option>
                <?php do { ?>
                <option value="<?php echo $row_pays['ch_pay_id']; ?>"><?php echo $row_pays['ch_pay_nom']; ?></option>
                <?php } while ($row_pays = mysql_fetch_assoc($pays)); ?>
              </select>
            </div>
          </div>
          
          <!-- Informations Générales
        ================================================== -->
          <h3>Informations Profil</h3>
          <!-- Nom user -->
          <div id="sprytextfield3" class="control-group">
            <label class="control-label" for="ch_use_prov_login">Login<a href="#" rel="clickover" title="Nom du pays" data-content="12 caract&egrave;res maximum. Ce nom doit être rigoureusement le même que sur le forum de génération city afin de pouvoir lui envoyer des mp. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" type="text" id="ch_use_prov_login" name="ch_use_prov_login" maxlength="12" value="">
              <span class="textfieldRequiredMsg">un login est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">12 caract&egrave;res max.</span></div>
          </div>
          <!-- Adresse mail -->
          <div id="sprytextfield7" class="control-group">
            <label class="control-label" for="ch_use_prov_mail">Adresse mail<a href="#" rel="clickover" title="E-mail" data-content="Entrez l'adresse sur laquelle seront envoy&eacute;s le login et la clef d'activation pour confirmer l'inscription. Ce champ est obligatoire."><i class="icon-info-sign"></i></a></label>
            <div class="controls">
              <input class="input-xlarge" name="ch_use_prov_mail" type="text" id="ch_use_prov_mail" value="" maxlength="50">
              <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
          </div>
          <input class="input-xlarge" type="hidden" name="ch_use_prov_clef" id="ch_use_prov_clef" value="<?php echo $code_aleatoire; ?>">
          <br>
          <!-- Bouton envoyer
        ================================================== -->
          <p>&nbsp;</p>
          <div class="alert alert-tips">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p>Le pseudo doit &ecirc;tre identique &agrave; celui du forum. Le nouveau membre doit ensuite activer son compte en utilisant les informations qui lui ont &eacute;t&eacute; envoy&eacute;es par mail. </p>
          </div>
          <div class="control-group">
            <div class="controls">
              <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
          </div>
          <input type="hidden" name="MM_insert" value="new_user">
        </form>
        <!-- FIN formulaire Page Pays
        ================================================== --> 
      </section>
    </div>
    <div class="span4 pull-center">
      <p>&nbsp;</p>
      <img id="portrait" src="../assets/img/imagesdefaut/personnage.jpg" alt="Personnage" width="250" height="250" title="Personnage"></div>
  </div>
</div>
<!-- Footer
    ================================================== -->
<?php include(DEF_ROOTPATH . 'php/footerback.php'); ?>
</body>
</html>
<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
      $(function() { 
          $('[rel="clickover"]').clickover();})
    </script>
<!-- SPRY ASSETS-->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars:2, maxChars:35, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "email", {validateOn:["change"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
</script>