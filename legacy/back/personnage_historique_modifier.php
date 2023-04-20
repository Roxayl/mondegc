<?php

//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if(!isset($_SESSION['userObject'])) {
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "ajout_fait_hist")) {
if ($_POST['ch_his_periode'] == true) {
$_POST['ch_his_date_fait2'] = NULL;
}	
  $updateSQL = sprintf("UPDATE histoire SET ch_his_label=%s, ch_his_paysID=%s, ch_his_personnage=%s, ch_his_statut=%s, ch_his_date=%s, ch_his_mis_jour=%s, ch_his_nb_update=%s, ch_his_date_fait=%s, ch_his_date_fait2=%s, ch_his_profession=%s, ch_his_nom=%s, ch_his_lien_img1=%s, ch_his_legende_img1=%s, ch_his_description=%s, ch_his_contenu=%s WHERE ch_his_id=%s",
                       escape_sql($_POST['ch_his_label'], "text"),
                       escape_sql($_POST['ch_his_paysID'], "int"),
                       escape_sql($_POST['ch_his_personnage'], "int"),
                       escape_sql($_POST['ch_his_statut'], "int"),
                       escape_sql($_POST['ch_his_date'], "date"),
                       escape_sql($_POST['ch_his_mis_jour'], "date"),
                       escape_sql($_POST['ch_his_nb_update'], "int"),
                       escape_sql($_POST['ch_his_date_fait'], "date"),
                       escape_sql($_POST['ch_his_date_fait2'], "date"),
                       escape_sql($_POST['ch_his_profession'], "text"),
                       escape_sql($_POST['ch_his_nom'], "text"),
                       escape_sql($_POST['ch_his_lien_img1'], "text"),
                       escape_sql($_POST['ch_his_legende_img1'], "text"),
                       escape_sql($_POST['ch_his_description'], "text"),
                       escape_sql($_POST['ch_his_contenu'], "text"),
                       escape_sql($_POST['ch_his_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $maconnexion);

  getErrorMessage('success', "Votre personnage historique, " . __s($_POST['ch_his_nom']) .
          ", a été modifié avec succès !");

  $updateGoTo = DEF_URI_PATH . "back/page_pays_back.php?paysID=" . (int)$_POST['ch_his_paysID'];
  appendQueryString($updateGoTo);
  header(sprintf("Location: %s", $updateGoTo));
 exit;
}

$colname_Fait_his = "-1";
if (isset($_POST['ch_his_id'])) {
  $colname_Fait_his = $_POST['ch_his_id'];
}

$query_Fait_his = sprintf("SELECT * FROM histoire WHERE ch_his_id = %s", escape_sql($colname_Fait_his, "int"));
$Fait_his = mysql_query($query_Fait_his, $maconnexion);
$row_Fait_his = mysql_fetch_assoc($Fait_his);
$totalRows_Fait_his = mysql_num_rows($Fait_his);


// Connection infos dirigeant pays

$query_users = sprintf("SELECT ch_use_id, ch_use_login FROM users WHERE ch_use_paysID = %s", escape_sql($row_Fait_his['ch_his_paysID'], "int"));
$users = mysql_query($query_users, $maconnexion);
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);


$thisPays = new \GenCity\Monde\Pays($row_Fait_his['ch_his_paysID']);

?>
<!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>modifier un fait historique</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../Carto/OLdefault.css" rel="stylesheet">
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="../assets/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<link href="../datepicker/css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="../assets/css/GenerationCity.css?v=<?= $mondegc_config['version'] ?>" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i|Titillium+Web:400,600&subset=latin-ext" rel="stylesheet">
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

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-offset="140" onLoad="init()">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>
</header>
<div class="container" id="overview"> 
  
  <!-- Docs nav
    ================================================== -->
  <div class="row-fluid corps-page">
    <!-- Page CONTENT
    ================================================== -->
    <div class=""> 
      <!-- Moderation
     ================================================== -->
      <div id="fait-historique" class="titre-vert anchor">
        <h1>Modifier un personnage historique</h1>
      </div>
      <?php if (($_SESSION['statut'] >= 20) AND ($row_users['ch_use_id'] != $_SESSION['user_ID'])) { ?>
      <form class="pull-right" action="membre-modifier_back.php" method="post">
        <input name="userID" type="hidden" value="<?= e($row_users['ch_use_id']) ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du profil"><i class="icon-user-white"></i> Profil du dirigeant</button>
      </form>
      <form class="pull-right" action="page_pays_back.php" method="post">
        <input name="paysID" type="hidden" value="<?php echo $paysID; ?>">
        <button class="btn btn-danger" type="submit" title="page de gestion du pays"><i class="icon-pays-small-white"></i> Modifier le pays</button>
      </form>
      <?php } else {?>
      <form class="pull-right" action="fait_historique_confirmation_supprimer.php" method="post">
        <input name="ch_his_id" type="hidden" value="<?= e($row_Fait_his['ch_his_id']) ?>">
        <button class="btn btn-danger" type="submit" title="supprimer ce fait historique"><i class="icon-trash icon-white"></i></button>
      </form>
      <?php } ?>
      <?php if ($row_users['ch_use_id'] == $_SESSION['user_ID']) { ?>
      <a class="btn btn-primary pull-right" href="../php/partage-fait-hist.php?ch_his_id=<?= e($row_Fait_his['ch_his_id']) ?>" data-toggle="modal" data-target="#Modal-Monument" title="Poster sur le forum"><i class="icon-share icon-white"></i> Partager sur le forum</a>
      <?php } ?>

        <ul class="breadcrumb pull-left">
          <li><a href="page_pays_back.php?paysID=<?= $thisPays->get('ch_pay_id') ?>&userID=<?= $_SESSION['userObject']->get('ch_use_id') ?>">Gestion du pays : <?= __s($thisPays->get('ch_pay_nom')) ?></a> <span class="divider">/</span></li>
          <li class="active">Modifier un personnage historique</li>
        </ul>

      <div class="clearfix"></div>
      <!-- Debut formulaire -->
      <form action="<?= e($editFormAction) ?>" method="POST" class="form-horizontal well" name="ajout_fait_hist" Id="ajout_fait_his">
        <div class="alert alert-tips">
          <button type="button" class="close" data-dismiss="alert">×</button>
          Ce formulaire contient les informations qui seront affich&eacute;es sur la page consacr&eacute;e &agrave; un fait historique. Les faits historiques construisent l'histoire de votre pays. Veillez a ce qu'elle soit coh&eacute;rente avec les pays qui vous entourent. La gestion de l'histoire du Monde GC est confi&eacute;e au <a href="../histoire.php" title="lien vers la page consacr&eacute;e au Comité">Comité d'Histoire</a>.</div>
        <!-- Bouton cachУЉs -->
        <input name="ch_his_id" type="hidden" value="<?= e($row_Fait_his['ch_his_id']) ?>" >
        <input name="ch_his_paysID" type="hidden" value="<?= e($row_Fait_his['ch_his_paysID']) ?>" >
        <input name="ch_his_personnage" type="hidden" value="2">
        <input name="ch_his_label" type="hidden" value="<?= e($row_Fait_his['ch_his_label']) ?>">
        <?php $now= date("Y-m-d G:i:s");
		$nb_update = $row_Fait_his['ch_his_nb_update'] + 1;?>
        <input name="ch_his_date" type="hidden" value="<?= e($row_Fait_his['ch_his_date']) ?>" >
        <input name="ch_his_mis_jour" type="hidden" value="<?php echo $now; ?>" >
        <input name="ch_his_nb_update" type="hidden" value="<?php echo $nb_update; ?>">
        <div class="row-fluid">
          <div class="span8"> 
        <!-- Statut -->
        <div id="spryradio1" class="control-group">
          <div class="control-label">Statut <a href="#" rel="clickover" title="Statut de votre &eacute;v&eacute;nement" data-content="
    Visible : Ce fait historique sera visible pour les visiteurs du site.
    Invisible : Ce fait historique sera cach&eacute; pour les visiteurs du site."><i class="icon-info-sign"></i></a></div>
          <div class="controls">
            <label>
              <input <?php if (!(strcmp($row_Fait_his['ch_his_statut'],"1"))) {echo "checked=\"checked\"";} ?> type="radio" name="ch_his_statut" value="1" id="ch_his_statut_1">
              visible</label>
            <label>
              <input <?php if (!(strcmp($row_Fait_his['ch_his_statut'],"2"))) {echo "checked=\"checked\"";} ?> name="ch_his_statut" type="radio" id="ch_his_statut_2" value="2">
              invisible</label>
            <span class="radioRequiredMsg">Choisissez un statut pour votre fait historique</span></div>
        </div>
        <!-- Nom -->
        <div id="sprytextfield2" class="control-group">
          <label class="control-label" for="ch_his_nom">Nom du personnage <a href="#" rel="clickover" title="Nom du personnage" data-content="50 caract&egrave;res maximum. Ce champ est obligatoire"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input class="span6" type="text" id="ch_his_nom" name="ch_his_nom" value="<?= e($row_Fait_his['ch_his_nom']) ?>">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- DATE -->
        <div class="control-group">
          <label class="control-label" for="ch_his_date_fait">Date de naissance <a href="#" rel="clickover" title="Date de naissance" data-content="Choisissez la date de naissance de votre personnage"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <div class="input-append date" id="dpYears" data-date="<?= e($row_Fait_his['ch_his_date_fait']) ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
              <input class="span6" type="text" value="<?= e($row_Fait_his['ch_his_date_fait']) ?>" id="ch_his_date_fait" name="ch_his_date_fait"  readonly>
              <span class="add-on"><i class="icon-calendar"></i></span> </div>
            <label style="display:inline;">
              <input <?php if ($row_Fait_his['ch_his_date_fait2'] == NULL) {echo "checked=\"checked\"";} ?> type="checkbox" name="ch_his_periode" value="1" id="ch_his_periode">
              Personnage vivant</label>
          </div>
        </div>
        <!-- DATE 2-->
        <div class="control-group periode" <?php if ($row_Fait_his['ch_his_date_fait2'] == NULL) {echo "style='display:none'";} else {echo "style='display:block'";} ?>>
          <label class="control-label" for="ch_his_date_fait2">Date de d&eacute;c&egrave;s <a href="#" rel="clickover" title="Date de d&eacute;c&egrave;s" data-content="Choisissez la date de d&eacute;c&egrave;s si votre personnage ne fait plus partie de notre monde"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <div class="input-append date" id="dpYears2" data-date="<?php if ($row_Fait_his['ch_his_date_fait2'] != NULL) {echo $row_Fait_his['ch_his_date_fait2'];} else {echo $row_Fait_his['ch_his_date_fait'];} ?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
              <input class="span6" type="text" value="<?php if ($row_Fait_his['ch_his_date_fait2'] != NULL) {echo $row_Fait_his['ch_his_date_fait2'];} else {echo $row_Fait_his['ch_his_date_fait'];} ?>" id="ch_his_date_fait2" name="ch_his_date_fait2" readonly>
              <span class="add-on"><i class="icon-calendar"></i></span> </div>
          </div>
        </div>
         <!-- profession -->
        <div id="sprytextfield7" class="control-group">
          <label class="control-label" for="ch_his_profession">Profession <a href="#" rel="clickover" title="Profession" data-content="Indiquez la profession ou le r&ocirc;le social de ce personnage"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_his_profession" id="ch_his_profession" value="<?= e($row_Fait_his['ch_his_profession']) ?>" class="span12">
            <span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- image -->
        <div id="sprytextfield5" class="control-group">
          <label class="control-label" for="ch_his_lien_img1">Lien portrait <a href="#" rel="clickover" title="Lien portrait" data-content="Mettez-ici un lien http:// vers une image d&eacute;ja stock&eacute;e sur un serveur d'image (du type servimg.com)"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_his_lien_img1" id="ch_his_lien_img1" value="<?php echo $row_Fait_his['ch_his_lien_img1']; ?>" class="span12">
            <span class="textfieldInvalidFormatMsg">Format non valide.</span><span class="textfieldMaxCharsMsg">250 caract&egrave;res maximum.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span></div>
        </div>
        <!-- Legende image -->
        <div id="sprytextfield6" class="control-group">
          <label class="control-label" for="ch_his_legende_img1">L&eacute;gende portrait <a href="#" rel="clickover" title="L&eacute;gende portrait" data-content="Mettez-ici la l&eacute;gende qui correspond &agrave; l'image. 50 caract&egrave;res maximum."><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <input type="text" name="ch_his_legende_img1" id="ch_his_legende_img1" class="span12" value="<?php echo $row_Fait_his['ch_his_legende_img1']; ?>">
            <span class="textfieldMaxCharsMsg">50 caract&egrave;res maximum.</span></div>
        </div>
        <!-- Description -->
        <div class="control-group" id="sprytextarea1">
          <label class="control-label" for="ch_his_description">Biographie <a href="#" rel="clickover" title="Biographie" data-content="Mettez-ici le r&eacute;sum&eacute; de la vie de votre personnage historique. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea name="ch_his_description" id="ch_his_description" class="span12" rows="6"><?= e($row_Fait_his['ch_his_description']) ?></textarea>
            <span class="textareaRequiredMsg">Une valeur est requise.</span> <span class="textareaMinCharsMsg">2 caract&egrave;res minimum.</span><span class="textareaMaxCharsMsg">800 caract&egrave;res maximum.</span></div>
        </div>
        </div>
          <div class="span4 pull-center">
            <p>&nbsp;</p>
            <img id="portrait" src="<?php echo $row_Fait_his['ch_his_lien_img1']; ?>" alt="Personnage" width="250" height="250" title="Personnage"></div>
        </div>
        <!-- Contenu -->
        <div class="control-group">
          <label class="control-label" for="ch_his_contenu">Biographie d&eacute;taill&eacute;e <a href="#" rel="clickover" title="Contenu" data-content="Mettez-ici le dУЉtail de la vie de votre personnage historique. 800 caract&egrave;res maximum"><i class="icon-info-sign"></i></a></label>
          <div class="controls">
            <textarea name="ch_his_contenu" id="ch_his_contenu" class="wysiwyg" rows="15"><?= htmlPurify($row_Fait_his['ch_his_contenu']) ?></textarea>
          </div>
        </div>
        <div class="controls">
          <button type="submit" class="btn btn-primary">Envoyer</button>
          &nbsp;&nbsp;<a class="btn btn-danger" href="page_pays_back.php?paysID=<?= $thisPays->get('ch_pay_id') ?>">Annuler</a> </div>
        <input type="hidden" name="MM_update" value="ajout_fait_hist">
      </form>
      <p>&nbsp;</p>
    </div>
  </div>
  <div class="modal container fade" id="Modal-Monument"></div>
  <!-- END CONTENT
    ================================================== --> 
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

<!-- Le javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- CARTE -->
<script src="../assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="../assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require(DEF_LEGACYROOTPATH . 'php/carte-ajouter-marqueur.php'); ?>
<!-- BOOTSTRAP -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-affix.js"></script>
<script src="../assets/js/application.js?v=<?= $mondegc_config['version'] ?>"></script>
<script src="../assets/js/bootstrap-scrollspy.js"></script>
<script src="../assets/js/bootstrapx-clickover.js"></script>
<script type="text/javascript">
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
<!-- MODAL -->
<script src="../assets/js/bootstrap-modalmanager.js"></script>
<script src="../assets/js/bootstrap-modal.js"></script>
<script>
    $("a[data-toggle=modal]").click(function (e) {
        lv_target = $(this).attr('data-target')
        lv_url = $(this).attr('href')
        $(lv_target).load(lv_url)
    })

    $('#closemodal').click(function () {
        $('#Modal-Monument').modal('hide');
    });
</script>
<!-- DATE PICKER -->
<script src="../datepicker/js/bootstrap-datepicker.js"></script>
<script>
    $(function () {
        window.prettyPrint && prettyPrint();
        $('#dpYears').datepicker();
        $('#dpYears2').datepicker();
    });
</script>
<script>
    $('#ch_his_periode').change(function () {
        if ($(this).attr("checked")) {
            $('.periode').fadeOut();
            return;
        }
        $('.periode').fadeIn();
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {maxChars:50, validateOn:["change"], minChars:2});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {validateOn:["change"], maxChars:250});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {isRequired:false, maxChars:50, validateOn:["change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "none", {isRequired:false, maxChars:250, validateOn:["change"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn:["change"]});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1", {minChars:2, validateOn:["change"], maxChars:800, useCharacterMasking:false});
</script>
</body>
</html>
