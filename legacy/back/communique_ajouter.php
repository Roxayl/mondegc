<?php

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
 
//deconnexion
require(DEF_LEGACYROOTPATH . 'php/logout.php');

if (!$_SESSION['statut']) {
    // Redirection vers Haut Conseil
    header("Status: 301 Moved Permanently", false, 301);
    header('Location: ' . legacyPage('connexion'));
    exit();
}

$cat = "-1";
if (isset($_REQUEST['cat'])) {
    $cat = $_REQUEST['cat'];
}

if($cat == "pays") {
    $colname_elementid = $_SESSION['pays_ID'];
    if (isset($_REQUEST['com_element_id'])) {
        $colname_elementid = $_REQUEST['com_element_id'];
        unset($_REQUEST['com_element_id']);

        $thisPays = new \GenCity\Monde\Pays($_REQUEST['paysID']);
        $personnage = \GenCity\Monde\Personnage::constructFromEntity($thisPays);
    }

    $query_pays = sprintf("SELECT ch_pay_nom, ch_pay_devise, ch_pay_lien_imgdrapeau
        FROM pays WHERE ch_pay_id = %s",
        escape_sql($colname_elementid, "int"));
    $pays = mysql_query($query_pays, $maconnexion);
    $row_pays = mysql_fetch_assoc($pays);
    $totalRows_pays = mysql_num_rows($pays);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $row_pays['ch_pay_nom'];
    $insigne = $row_pays['ch_pay_lien_imgdrapeau'];
    $soustitre = $row_pays['ch_pay_devise'];

    mysql_free_result($pays);
}

elseif($cat == "ville") {
    $colname_elementid = $_SESSION['ville_encours'];
    if (isset($_REQUEST['com_element_id'])) {
        $colname_elementid = $_REQUEST['com_element_id'];
        unset($_REQUEST['com_element_id']);
    }

    $query_villes = sprintf("SELECT ch_vil_ID, ch_vil_nom, ch_vil_specialite, ch_vil_armoiries, ch_pay_nom FROM villes INNER JOIN pays ON villes.ch_vil_paysID = ch_pay_id WHERE ch_vil_ID = %s", escape_sql($colname_elementid, "int"));
    $villes = mysql_query($query_villes, $maconnexion);
    $row_villes = mysql_fetch_assoc($villes);
    $totalRows_villes = mysql_num_rows($villes);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $row_villes['ch_vil_nom'];
    $insigne = $row_villes['ch_vil_armoiries'];
    $soustitre = $row_villes['ch_pay_nom'];

    mysql_free_result($villes);
}

elseif($cat == "institut") {
    $colname_elementid = -1;
    if (isset($_REQUEST['com_element_id'])) {
        $colname_elementid = $_REQUEST['com_element_id'];
        unset($_REQUEST['com_element_id']);
    }

    $query_institut = sprintf("SELECT ch_ins_ID, ch_ins_nom, ch_ins_sigle, ch_ins_logo FROM instituts WHERE ch_ins_ID = %s", escape_sql($colname_elementid, "int"));
    $institut = mysql_query($query_institut, $maconnexion);
    $row_institut = mysql_fetch_assoc($institut);
    $totalRows_institut = mysql_num_rows($institut);

    $ch_com_categorie = $cat;
    $ch_com_element_id = $colname_elementid;
    $nom_organisation = $row_institut['ch_ins_sigle'];
    $insigne = $row_institut['ch_ins_logo'];
    $soustitre = $row_institut['ch_ins_nom'];

    mysql_free_result($institut);
}

elseif($cat == "organisation") {
    $organisation = \Roxayl\MondeGC\Models\Organisation::query()->findOrFail($_REQUEST['com_element_id']);

    if(!auth()->user()->can('administrate', $organisation)) {
        throw new AccessDeniedHttpException("Permissions insuffisantes.");
    }

    $ch_com_categorie = $cat;
    $ch_com_element_id = (int)$_REQUEST['com_element_id'];
    $colname_elementid = $ch_com_element_id;
    $nom_organisation = $organisation->name;
    $insigne = $organisation->flag;
    $soustitre = 'Organisation';
}


//Récupération variables
$colname_user = $_SESSION['user_ID'];
if (isset($_REQUEST['userID'])) {
    $colname_user = $_REQUEST['userID'];
    unset($_REQUEST['userID']);
}

$query_user = sprintf("SELECT ch_use_lien_imgpersonnage, ch_use_predicat_dirigeant, ch_use_titre_dirigeant, ch_use_nom_dirigeant, ch_use_prenom_dirigeant FROM users WHERE ch_use_id = %s", escape_sql($colname_user, "int"));
$user = mysql_query($query_user, $maconnexion);
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$editFormAction = DEF_URI_PATH . $mondegc_config['front-controller']['uri'] . '.php';
appendQueryString($editFormAction);

/*** Traitement données POST ***/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ajout_communique")) {

    if($_POST['ch_com_categorie'] == "organisation") {
        $organisation = \Roxayl\MondeGC\Models\Organisation::query()->findOrFail($_REQUEST['com_element_id']);
        if(!auth()->check() || !auth()->user()->can('administrate', $organisation)) {
            throw new AccessDeniedHttpException("Vous ne pouvez pas ajouter de communiqué.");
        }
        $insertGoTo = route('organisation.showslug',
            $organisation->showRouteParameter());
    }

    $insertSQL = sprintf("INSERT INTO communiques (ch_com_label, ch_com_statut, ch_com_categorie, ch_com_element_id, ch_com_user_id, ch_com_date, ch_com_date_mis_jour, ch_com_titre, ch_com_contenu, ch_com_pays_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       escape_sql($_POST['ch_com_label'], "text"),
                       escape_sql($_POST['ch_com_statut'], "int"),
                       escape_sql($_POST['ch_com_categorie'], "text"),
                       escape_sql($_POST['ch_com_element_id'], "int"),
                       escape_sql($_SESSION['userObject']->get('ch_use_id'), "int"),
                       escape_sql($_POST['ch_com_date'], "date"),
                       escape_sql($_POST['ch_com_date_mis_jour'], "date"),
                       escape_sql($_POST['ch_com_titre'], "text"),
                       escape_sql($_POST['ch_com_contenu'], "text"),
                       escape_sql($_POST['ch_com_pays_id'], 'int'));
  
    $Result1 = mysql_query($insertSQL, $maconnexion);

    $last_insert_id = mysql_insert_id();
    $banner_text = "Votre communiqué a été ajouté avec succès !<br />";
    $banner_text .= '<a href="' . DEF_URI_PATH . 'page-communique.php?com_id='
                 . $last_insert_id . '">Accéder à votre communiqué</a>';
    getErrorMessage('success', $banner_text);

    if($_POST['ch_com_categorie'] == "pays") {
        $insertGoTo = 'page_pays_back.php?paysID=' . (int)$_POST['ch_com_element_id'];
    }
    elseif($_POST['ch_com_categorie'] == "ville") {
        $insertGoTo = 'ville_modifier.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 1)) {
        $insertGoTo = 'institut_OCGC.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 2)) {
        $insertGoTo = 'institut_geographie.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 3)) {
        $insertGoTo = 'institut_patrimoine.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 4)) {
        $insertGoTo = 'institut_histoire.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 5)) {
        $insertGoTo = 'institut_economie.php';
    }
    elseif(($_POST['ch_com_categorie'] == "institut") AND ($_POST['ch_com_element_id'] == 6)) {
        $insertGoTo = 'institut_sport.php';
    }
    elseif($_POST['ch_com_categorie'] == "organisation") {
        $insertGoTo = route('organisation.showslug', $organisation->showRouteParameter());
    }
    else {
        $insertGoTo = DEF_URI_PATH;
    }
    appendQueryString($insertGoTo);
    header(sprintf("Location: %s", $insertGoTo));

    exit;

}


if(isset($thisPays)) {
    $pays_id = $thisPays->get('ch_pay_id');
} else {
    $pays_id = 0;
}

?><!DOCTYPE html>
<html lang="fr">
<!-- head Html -->
<head>
<meta charset="utf-8">
<title>Monde GC - Ajouter un communiqu&eacute;</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../assets/css/bootstrap.css" rel="stylesheet">
<link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
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
</style>

<?php
Eventy::action('display.beforeHeadClosingTag')
?>
</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!-- Navbar
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/navbar.php'); ?>

<!-- Page CONTENT
    ================================================== -->
<div class="container corps-page">
  <div class="row-fluid communique">
    <section>

      <!-- EN-tête Personnage pour communiquées officiels et commentaire-->
      <div class="span3 thumb">
      <?php if(isset($personnage)): ?>
          <img src="<?= $personnage->get('lien_img') ?>" alt="photo <?= $personnage->get('nom_personnage') ?>">
        <div class="titre-gris">
          <p><?= $personnage->get('predicat') ?></p>
          <h3><?= $personnage->get('prenom_personnage') ?> <?= $personnage->get('nom_personnage') ?></h3>
          <p><small><?= $personnage->get('titre_personnage') ?></small></p></div>
      <?php endif; ?>
      </div>

      <div class="offset6 span3 thumb">
        <?php if (in_array($cat, ['ville', 'pays', 'institut', 'organisation'])) { ?>
        <!-- EN-tête Institution pour communiqués officiels-->
        
        <!-- EN-tête Institution pour communiqués officiels-->
        
        <?php if ( $cat == "ville") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/blason.jpg" alt="armoirie">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="armoirie">
        <?php } ?>
        <?php } elseif ( $cat == "pays") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/drapeau.jpg" alt="drapeau">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="drapeau">
        <?php } ?>
        <?php } elseif ( $cat == "institut") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/blason.jpg" alt="logo">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="logo">
        <?php }
		 } elseif ( $cat == "organisation") {?>
        <?php if ($insigne == NULL) {?>
        <img src="../assets/img/imagesdefaut/blason.jpg" alt="logo">
        <?php } else { ?>
        <img src="<?php echo $insigne; ?>" alt="logo">
        <?php }
		 } else { ?>
                <img src="<?php echo $insigne; ?>">
                <?php } ?>
        <div class="titre-gris">
          <h3><?php echo $nom_organisation; ?></h3>
          <p><small><?php echo $soustitre; ?></small></p> </div>
        <?php } ?>
      </div>
    </section>
  </div>
  <div class="row-fluid">
   <?php if ($cat == "institut") { ?>
    <div class="titre-bleu clearfix">
      <h1>Ajouter un communiqu&eacute;</h1>
    </div>
      <?php } else { ?>
    <div class="titre-vert clearfix">
      <h1>Ajouter un communiqu&eacute;</h1>
    </div>
    <?php }?>
    <!-- Page CONTENT
    ================================================== -->
    <section class="well">
    <!-- Debut formulaire -->
    <form action="" method="POST" name="ajout_communique" Id="ajout_communique">
      <!-- Bouton cachés -->
      <?php 
				  $now= date("Y-m-d G:i:s");?>
      <input name="ch_com_label" type="hidden" value="communique">
      <input name="ch_com_pays_id" type="hidden" value="<?php echo $pays_id ?>">
      <input name="ch_com_categorie" type="hidden" value="<?php echo $cat ?>">
      <input name="ch_com_element_id" type="hidden" value="<?php echo $colname_elementid ?>">
      <input name="ch_com_user_id" type="hidden" value="<?php echo $colname_user ?>">
      <input name="ch_com_date" type="hidden" value="<?php echo $now; ?>">
      <input name="ch_com_date_mis_jour" type="hidden" value="<?php echo $now; ?>">
      <!-- Statut -->
      <div id="spryradio1" class="form-inline pull-right"> Statut <a href="#" rel="clickover" title="Statut de votre communiqu&eacute;" data-content="
    Publi&eacute; : le communiqu&eacute; sera visible pour les visiteurs du site. /
    Brouillon : Retrouvez-le dans la liste de vos communiqu&eacute;s."><i class="icon-info-sign"></i></a> &nbsp;
        <label>
          <input name="ch_com_statut" type="radio" id="ch_vil_capitale_1" value="1" checked="CHECKED">
          Publi&eacute;</label>
        &nbsp;
        <label>
          <input type="radio" name="ch_com_statut" value="2" id="ch_vil_capitale_2">
          Brouillon</label>
        &nbsp; <span class="radioRequiredMsg">Choisissez un statut pour votre communiqu&eacute;</span></div>
      <div class="span12 clearfix"></div>
      <!-- Titre -->
      <div id="sprytextfield1">
        <input class="span12" type="text" name="ch_com_titre" id="ch_com_titre" placeholder="Titre">
        <span class="textfieldMaxCharsMsg">100 caract&egrave;res max.</span><span class="textfieldRequiredMsg">Une valeur est requise.</span><span class="textfieldMinCharsMsg">2 caract&egrave;res min</span></div>
      <!-- Contenu -->
      <p>&nbsp;</p>
      <textarea rows="20" name="ch_com_contenu" class="wysiwyg" id="ch_com_contenu"></textarea>
      <p>&nbsp;</p>
      <button type="submit" class="btn btn-primary btn-margin-left">Publier</button>
      <input type="hidden" name="MM_insert" value="ajout_communique">
    </form>
  </div>
  </section>
  <!-- END CONTENT
    ================================================== --> 
</div>
</div>
<!-- Footer
    ================================================== -->
<?php require(DEF_LEGACYROOTPATH . 'php/footer.php'); ?>

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
    $(function () {
        $('[rel="clickover"]').clickover();
    })
</script>
<!-- EDITEUR -->
<script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/js/Editeur.js"></script>

<!-- SPRY ASSET -->
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script type="text/javascript">
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars: 100, validateOn: ["change"], minChars: 2});
    var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1", {validateOn: ["change"]});
</script>
</body>
</html>