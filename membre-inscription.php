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


$query_user_prov = sprintf("SELECT * FROM users_provisoire WHERE ch_use_prov_login = %s AND ch_use_prov_clef = %s", GetSQLValueString($login, "text"), GetSQLValueString($clef, "text"));
$user_prov = mysql_query($query_user_prov, $maconnexion) or die(mysql_error());
$row_user_prov = mysql_fetch_assoc($user_prov);
$totalRows_user_prov = mysql_num_rows($user_prov);

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['path'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "InfoUser")) {
  include_once("php/config.php");
  $password = md5($_POST['ch-use_password'].$salt);
  $insertSQL = sprintf("INSERT INTO users (ch_use_date, ch_use_acces, ch_use_last_log, ch_use_login, ch_use_password, ch_use_mail, ch_use_paysID, ch_use_statut, ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant, ch_use_biographie_dirigeant)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NULL, NULL, NULL, NULL, NULL, NULL)",
       GetSQLValueString($_POST['ch_use_date'], "date"),
       GetSQLValueString($_POST['ch_use_acces'], "int"),
       GetSQLValueString($_POST['ch_use_last_log'], "date"),
       GetSQLValueString($_POST['ch_use_login'], "text"),
       GetSQLValueString($password, "text"),
       GetSQLValueString($_POST['ch_use_mail'], "text"),
       GetSQLValueString($_POST['ch_use_paysID'], "int"),
       GetSQLValueString($_POST['ch_use_statut'], "int"));

  
  $Result1 = mysql_query($insertSQL, $maconnexion) or die(mysql_error());

  $last_user_id = mysql_insert_id();

  // Ajouter l'utilisateur à la liste des dirigeants
  $insert_users_pays = sprintf(
      'INSERT INTO users_pays(ID_pays, ID_user, permissions) ' .
             'VALUES(%s, %s, %s)',
      GetSQLValueString($_POST['ch_use_paysID'], 'int'),
      GetSQLValueString($last_user_id, 'int'),
      10
  );
  mysql_query($insert_users_pays) or die(mysql_error());

  // On ajoute une entrée dans la table 'personnages' s'il n'y avait pas encore de perso.
  $thisPays = new \GenCity\Monde\Pays($_POST['ch_use_paysID']);
  $thisPersonnage = \GenCity\Monde\Personnage::constructFromEntity($thisPays);
  if(is_null($thisPersonnage)) {
      // Ajouter le personnage
      $insert_personnage = sprintf(
          "INSERT INTO personnage(entity, entity_id,
                           nom_personnage, predicat, prenom_personnage,
                           biographie, titre_personnage, lien_img)
                   VALUES(%s, %s,
                          '', '', '',
                          '', '', '')",
            GetSQLValueString('pays', 'text'),
            GetSQLValueString($_POST['ch_use_paysID'], 'int')
      );
      mysql_query($insert_personnage) or die(mysql_error());
  }

  
  // Effacement de la clef sur User_provisoire
  $userprov = $row_user_prov['ch_use_prov_ID'];
   $deleteSQL = sprintf("DELETE FROM users_provisoire WHERE ch_use_prov_ID=%s",
                       GetSQLValueString($userprov, "int"));

  
  $Result1 = mysql_query($deleteSQL, $maconnexion) or die(mysql_error());
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
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
    <div class="span10 well" id="Profil">
      <div class="titre-vert">
        <h1>Inscription dans le Monde GC</h1>
      </div>
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">�</button>
        <p>Bienvenue dans le Monde GC <?php echo $row_user_prov['ch_use_prov_login']; ?>. Compl&eacute;tez votre profil afin de finaliser votre inscription.</p>
      </div>
      <form action="<?php echo $editFormAction; ?>" name="InfoUser" method="POST" class="form-horizontal" id="InfoHeader">
        <!-- Boutons cach�s -->
        <?php 
		$now= date("Y-m-d G:i:s");?>
        <input name="ch_use_date" type="hidden" value="<?php echo $now; ?>">
        <input name="ch_use_last_log" type="hidden" value="<?php echo $now; ?>">
        <input name="ch_use_statut" type="hidden" value="<?php echo $row_user_prov['ch_use_prov_statut']; ?>">
        <input name="ch_use_paysID" type="hidden" value="<?php echo $row_user_prov['ch_use_prov_paysID']; ?>">
        <input name="ch_use_acces" type="hidden" value="1">
        
        <!-- Informations G�n�rales
        ================================================== -->
        <h3>Informations Profil</h3>
        <!-- Nom user -->
        <div id="sprytextfield3" class="control-group">
          <label class="control-label" for="ch_use_login">Login<a href="#" rel="clickover" title="Nom du pays" data-content="12 caract&egrave;res maximum. Ce nom servira &agrave; identifier le nouveau membre dans l'ensemble du monde GC. Votre login doit &ecirc;tre le m&ecirc;me que sur le forum afin d'assurer la fonction d'envoi de MP. Contactez un membre du haut-conseil s'il doit &ecirc;tre modifi&eacute;."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="text" id="ch_use_login" name="ch_use_login" maxlength="12" value="<?php echo $row_user_prov['ch_use_prov_login']; ?>" readonly>
            <span class="textfieldRequiredMsg">un login est obligatoire.</span> <span class="textfieldMinCharsMsg">min 2 caract&egrave;res.</span><span class="textfieldMaxCharsMsg">12 caract&egrave;res max.</span></div>
        </div>
        <!-- Password -->
        <div id="sprypassword1" class="control-group">
          <label class="control-label" for="ch_use_password">Mot de passe<a href="#" rel="clickover" title="Mot de passe" data-content="Entrez-ici un mot de passe"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" type="password" name="ch_use_password" id="ch_use_password" value="">
            <span class="passwordRequiredMsg">un mot de passe est obligatoire.</span><span class="passwordMinCharsMsg">2 caract&egrave;res min.</span><span class="passwordMaxCharsMsg">16 caract&egrave;res max.</span></div>
        </div>
        <div id="spryconfirm1" class="control-group">
          <label class="control-label" for="ch-use_password2" class="control-group">
          Confirmez le mot de passe
          </label>
          <div class="controls">
            <input class="input-xlarge" type="password" name="ch-use_password" id="ch-use_password" value="">
            <span class="confirmRequiredMsg">La confirmation du mot de passe est obligatoire..</span><span class="confirmInvalidMsg">Les valeurs ne correspondent pas.</span></div>
        </div>
        <br>
        <!-- Adresse mail -->
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_use_mail">Adresse mail<a href="#" rel="clickover" title="E-mail" data-content="Laissez une adresse de contact en cas de perte du mot de passe"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" name="ch_use_mail" type="text" id="ch_use_mail" value="<?php echo $row_user_prov['ch_use_prov_mail']; ?>" maxlength="50">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span></div>
        </div>
        <h3>Personnage</h3>
        <!-- Diplomatie
        ================================================== --> 
        <!-- Lien image personnage -->
        <div id="sprytextfield10" class="control-group">
          <label class="control-label" for="ch_use_lien_imgpersonnage">Lien image dirigeant <a href="#" rel="clickover" title="Lien image dirigeant" data-content="l'image du dirigeant servira pour vos &eacute;changes diplomatiques. Elle sera automatiquement redimensionn&eacute;e en 250 pixel de large et 250 pixels de haut. Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <br>
          <div class="controls">
            <input class="input-xlarge" type="text" id="ch_use_lien_imgpersonnage" name="ch_use_lien_imgpersonnage" value="http://www.generation-city.com/monde/assets/img/imagesdefaut/personnage.jpg" maxlength="250">
            <span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span><span class="textfieldInvalidFormatMsg">Format non valide.</span> </div>
        </div>
        <br>
        <!-- Predicat -->
        <div class="control-group">
          <label class="control-label" for="ch_use_predicat_dirigeant">Pr&eacute;dicat <a href="#" rel="clickover" title="Pr&eacute;dicat" data-content="Lorsque votre dirigeant sera nomm&eacute;, notamment lors des c&eacute;r&eacute;monies protocolaires, sp&eacute;cifiez quelle appellation doit �tre utilis&eacute;e. Le pr&eacute;dicat pr&eacute;c&egrave;de le nom et le pr&eacute;nom"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <select name="ch_use_predicat_dirigeant" id="ch_use_predicat_dirigeant" class="input-xlarge">
              <option value="">aucun</option>
              <option value="Chef" selected="selected">Chef</option>
              <option value="Le Capricieux">Le Capricieux</option>
              <option value="L'Ignoblissime">L'Ignoblissime</option>
              <option value="L'Incorrigible">L'Incorrigible</option>
              <option value="L'Intraitable">L'Intraitable</option>
              <option value="Le Terrible">Le Terrible</option>
              <option value="Le Tr�s honorable">Le Tr&egrave;s honorable</option>
              <option value="Madame">Madame</option>
              <option value="Mademoiselle">Mademoiselle</option>
              <option value="Messire">Messire</option>
              <option value="Monseigneur">Monseigneur</option>
              <option value="Monsieur">Monsieur</option>
              <option value="Notre Guide">Notre Guide</option>
              <option value="Notre Guide supr�me">Notre Guide supr&ecirc;me</option>
              <option value="Notre Grandeur">Notre Grandeur</option>
              <option value="Sa Gr�ce">Sa Gr&acirc;ce</option>
              <option value="Sa Haute Excellence">Sa Haute Excellence</option>
              <option value="Sa Haute Naissance">Sa Haute Naissance</option>
              <option value="Sa Majest�">Sa Majest&eacute;</option>
              <option value="Sa Majest� imp�riale">Sa Majest&eacute; imp&eacute;riale</option>
              <option value="Sa Saintet�">Sa Saintet&eacute;</option>
              <option value="Son Altesse">Son Altesse</option>
              <option value="Son Altesse illustrissime">Son Altesse illustrissime</option>
              <option value="Son Altesse imp�riale">Son Altesse imp&eacute;riale</option>
              <option value="Son Altesse royale">Son Altesse royale</option>
              <option value="Son Altesse s�r�nissime">Son Altesse s&eacute;r&eacute;nissime</option>
              <option value="Son illustrissime Luminescence">Son illustrissime Luminescence</option>
              <option value="Son Excellence">Son Excellence</option>
              <option value="Son �minence">Son &eacute;minence </option>
            </select>
          </div>
        </div>
        <!-- Nom dirigeant -->
        <div id="sprytextfield11" class="control-group">
          <label class="control-label" for="ch_use_nom_dirigeant">Nom du dirigeant <a href="#" rel="clickover" title="Nom du dirigeant" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" name="ch_use_nom_dirigeant" type="text" id="ch_use_nom_dirigeant" value="" maxlength="50">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Prenom dirigeant -->
        <div id="sprytextfield12" class="control-group">
          <label class="control-label" for="ch_use_prenom_dirigeant">Pr&eacute;nom du dirigeant <a href="#" rel="clickover" title="pr&eacute;nom du dirigeant" data-content="50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" name="ch_use_prenom_dirigeant" type="text" id="ch_use_prenom_dirigeant" value="" maxlength="50">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res max.</span></div>
        </div>
        <!-- Titre dirigeant -->
        <div id="sprytextfield13" class="control-group">
          <label class="control-label" for="ch_use_titre_dirigeant">Titre du dirigeant <a href="#" rel="clickover" title="Titre du dirigeant" data-content="Le titre doit faire r&eacute;f&eacute;rence au syst&egrave;me politique et citer le nom de votre pays. Par exemple : Pr&eacute;sident de la r&eacute;publique fran&ccedil;aise. 250 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="input-xlarge" name="ch_use_titre_dirigeant" type="text" id="ch_use_titre_dirigeant" value="" maxlength="250">
            <span class="textfieldMaxCharsMsg">250 caract&egrave;res max.</span></div>
        </div>
        <!-- Biographie dirigeant -->
        <div id="sprytextarea1" class="control-group">
          <label class="control-label" for="ch_use_biographie_dirigeant">Biographie <a href="#" rel="clickover" title="Biographie" data-content="Donnez en quelques lignes des informations qui permettrons &agrave; vos homologues du Monde GC de mieux cerner votre personnage. 500 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea rows="6" name="ch_use_biographie_dirigeant" class="input-xlarge" id="ch_use_biographie_dirigeant"></textarea>
            <span class="textareaMaxCharsMsg">500 caract&egrave;res max.</span></div>
        </div>
        <!-- Bouton envoyer
        ================================================== -->
        <p>&nbsp;</p>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </div>
        <input type="hidden" name="MM_insert" value="InfoUser">
      </form>
    </div>
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
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script type="text/javascript">
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars:2, maxChars:35, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "email", {isRequired:false, validateOn:["change"]});
var sprytextfield10 = new Spry.Widget.ValidationTextField("sprytextfield10", "url", {maxChars:250, validateOn:["change"], isRequired:false});
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11", "none", {maxChars:50, validateOn:["change"]});
var sprytextfield12 = new Spry.Widget.ValidationTextField("sprytextfield12", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield13 = new Spry.Widget.ValidationTextField("sprytextfield13", "none", {isRequired:false, maxChars:250, validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {maxChars:500, validateOn:["change"], isRequired:false, useCharacterMasking:false});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:2, validateOn:["change"], maxChars:16});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "ch_use_password", {validateOn:["change"]});
</script>
</body>
</html>